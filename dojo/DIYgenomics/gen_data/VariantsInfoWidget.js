/**
 * Copyright (C) 2010 DIYgenomics           diygenomics.org
 * Copyright (C) 2010 Melanie Swan          mxswan@gmail
 * Copyright (C) 2010 Marat Nepomnyashy     maratbn@gmail
 *
 * Module:          VariantsInfoWidget.js
 * Description:     The Dojo widget for the genome variants info. 
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * Redistributions of source code must retain the above copyright
 * notice, this list of conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright
 * notice, this list of conditions and the following disclaimer in the
 * documentation and/or other materials provided with the distribution.
 * Neither the name of the DIYgenomics nor the
 * names of its contributors may be used to endorse or promote products
 * derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

dojo.provide('DIYgenomics.gen_data.VariantsInfoWidget');

dojo.require('dijit._Templated');
dojo.require('dijit._Widget');

dojo.declare(
    'DIYgenomics.gen_data.VariantsInfoWidget',
    [dijit._Widget, dijit._Templated],
    {
        condition:          "",

        templateString:     [   "<div>",
                                    "<h3>Variants reviewed for ${condition}</h3>",
                                    "<div>",
                                        "<select dojoAttachPoint='_selectConditions'>",
                                        "</select>",
                                    "</div",
                                "</div>"
                            ].join(""),

        postCreate:         function() {
                                this.inherited(arguments);

                                var that = this;

                                dojo.xhrGet(
                                        {
                                            handleAs:   'json',
                                            url:        'gen_data_all.php',
                                            load:       function(data) {
                                                            that.onLoadedData(data);
                                                        }
                                        });
                            },

        onLoadedData:       function(data) {
                                if (!data || !data.conditions || !data.conditions.length) return;

                                var that = this;

                                function _addCondition(strCondition) {
                                    var elOption = dojo.create(
                                                    'option',
                                                    {
                                                        innerHTML:  strCondition,
                                                        value:      strCondition
                                                    });

                                    if (that.condition == strCondition) elOption.selected = 'selected';

                                    dojo.place(elOption, that._selectConditions);
                                }                                

                                for (var i = 0; i < data.conditions.length; i++) {
                                    _addCondition(data.conditions[i]);
                                }
                            }
    });
