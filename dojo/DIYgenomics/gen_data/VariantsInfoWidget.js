/**
 * Copyright (C) 2010 DIYgenomics           diygenomics.org
 * Copyright (C) 2010 Melanie Swan          mxswan@gmail
 * Copyright (C) 2010 Marat Nepomnyashy     maratbn@gmail
 * All rights reserved.
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

dojo.require('dojox.html.entities');

dojo.declare(
    'DIYgenomics.gen_data.VariantsInfoWidget',
    [dijit._Widget, dijit._Templated],
    {
        condition:                  "",

        templateString:             [   "<div>",
                                            "<div dojoAttachPoint='_divStatus'>",
                                            "</div>",
                                            "<div dojoAttachPoint='_divContent' style='display:none;'>",
                                                "<div class='condlist'>",
                                                    "<select dojoAttachPoint='_selectConditions' dojoAttachEvent='onchange:onSelectedCondition'>",
                                                    "</select>",
                                                "</div>",
                                                "<h3>Variants reviewed for ",
                                                    "<span dojoAttachPoint='_spanCondition'>${condition}</span>",
                                                "</h3>",
                                                "<table class='variants_table' cellpadding='0' cellspacing='0'>",
                                                    "<thead>",
                                                        "<tr dojoAttachPoint='_trVariantsTableHeader'>",
                                                        "</tr>",
                                                    "</thead>",
                                                    "<tbody dojoAttachPoint='_trVariantsTableBody'>",
                                                    "</tbody>",
                                                "</table>",
                                            "</div>",
                                        "</div>"
                                    ].join(""),

        postCreate:                 function() {
                                        this.inherited(arguments);

                                        this._updateStatus("Loading reviewed variants data...");

                                        var that = this;

                                        dojo.xhrGet(
                                                {
                                                    handleAs:   'json',
                                                    url:        'gen_data_all.php',
                                                    error:      function(error) {
                                                                    that._updateStatus(
                                                                            "Encountered error loading reviewed variants data..."
                                                                            + "  Error number: " + (error && error.number || "unknown"),
                                                                            + "  Error description: " + (error && error.description || "unknown"));
                                                                },

                                                    load:       function(data) {
                                                                    that.onLoadedData(data);
                                                                }
                                                });
                                    },

        onLoadedData:               function(data) {
                                        this._updateStatus("Processing reviewed variants data...");

                                        this.data = data;
                                        this._fillConditionsCB();
                                        this._displayCurrentCondition();

                                        this._updateStatus("Completed loading and processing reviewed variants data.");
                                        dojo.style(this._divContent, 'display', "");
                                        dojo.style(this._divStatus, 'display', 'none');
                                    },

        onSelectedCondition:        function() {
                                        this.condition = dojo.attr(this._selectConditions, 'value');
                                        this._displayCurrentCondition();
                                    },

        _displayCurrentCondition:   function() {
                                        this._spanCondition.innerHTML = this.condition;
                                        this._fillVariantsTable();
                                    },

                                    /**
                                     *  Private function to initialize the conditions combo box.
                                     */
        _fillConditionsCB:          function() {
                                        if (!this.data || !this.data.conditions || !this.data.conditions.length) return;

                                        var that = this;

                                        function _addCondition(strCondition) {
                                            var strConditionEncoded = dojox.html.entities.encode(strCondition);

                                            var elOption = dojo.create(
                                                            'option',
                                                            {
                                                                innerHTML:  strConditionEncoded,
                                                                value:      strConditionEncoded
                                                            });

                                            if (that.condition == strCondition) elOption.selected = 'selected';

                                            dojo.place(elOption, that._selectConditions);
                                        }                                

                                        for (var i = 0; i < this.data.conditions.length; i++) {
                                            _addCondition(this.data.conditions[i]);
                                        }
                                    },

        _fillVariantsTable:         function() {
                                        function _clearChildNodes(elNode) {
                                            if (!elNode) return;

                                            while (elNode.hasChildNodes()) {
                                                elNode.removeChild(elNode.lastChild);
                                            }
                                        }

                                        _clearChildNodes(this._trVariantsTableHeader);
                                        _clearChildNodes(this._trVariantsTableBody);

                                        var condition = this.data && this.data.conditions_keyed && this.data.conditions_keyed[this.condition];
                                        if (!condition) return;

                                        var that = this;

                                        function _getAnchorOpeningTag(strLink) {
                                            return "<a href='" + dojox.html.entities.encode(strLink) + "' target='_blank'>";
                                        }

                                        function _addHeaderColumn(strName, strLink) {
                                            var strHTML = [ (strLink ? _getAnchorOpeningTag(strLink) : ""),
                                                            dojox.html.entities.encode(strName),
                                                            (strLink ? "</a>" : "")
                                                        ].join("");

                                            dojo.place(
                                                    dojo.create(
                                                            'th',
                                                            {
                                                                innerHTML:  strHTML
                                                            }),
                                                    that._trVariantsTableHeader);
                                        }

                                        var arrEntities = ['deCODEme', 'Navigenics', '23andMe'];

                                        _addHeaderColumn('Locus');
                                        _addHeaderColumn('Gene');
                                        _addHeaderColumn('Variant');

                                        for (var i = 0; i < arrEntities.length; i++) {
                                            var strEntity = arrEntities[i];
                                            var strLink =   condition.condition_data.entities_keyed &&
                                                            condition.condition_data.entities_keyed[strEntity] &&
                                                            condition.condition_data.entities_keyed[strEntity].entity_cond_url;
                                            _addHeaderColumn(strEntity, strLink);
                                        }

                                        _addHeaderColumn('dbSNP (Nrml/Rsk)', 'http://www.ncbi.nlm.nih.gov/projects/SNP');
                                        _addHeaderColumn('Sample data');

                                        var arrVariants =   condition.condition_data &&
                                                            condition.condition_data.variants;

                                        if (!arrVariants || !condition.condition_data.variants_keyed) return;

                                        function _addVariantRow(dataVariant, strCSSClass) {
                                            if (!dataVariant) return;

                                            var arrHTML = [ "<td>",
                                                                _getAnchorOpeningTag(dataVariant.locus_url),
                                                                    dojox.html.entities.encode(dataVariant.locus),
                                                                "</a>",
                                                            "</td>",
                                                            "<td>",
                                                                _getAnchorOpeningTag(dataVariant.gene_url),
                                                                    dojox.html.entities.encode(dataVariant.gene),
                                                                "</a>",
                                                            "</td>",
                                                            "<td>",
                                                                _getAnchorOpeningTag(dataVariant.variant_url),
                                                                    dojox.html.entities.encode(dataVariant.variant),
                                                                "</a>",
                                                            "</td>"
                                                        ];

                                            for (var i = 0; i < arrEntities.length; i++) {
                                                var strEntity = arrEntities[i];
                                                arrHTML.push("<td>");

                                                if (dataVariant.studies) {
                                                    var totalStudies = 0;
                                                    for (var j = 0; j < dataVariant.studies.length; j++) {
                                                        var strStudy = dataVariant.studies[j];
                                                        if (dataVariant.studies_keyed &&
                                                            dataVariant.studies_keyed[strStudy] &&
                                                            dataVariant.studies_keyed[strStudy].entities &&
                                                            dataVariant.studies_keyed[strStudy].entities[strEntity]) {
                                                            if (totalStudies > 0) arrHTML.push(",");
                                                            arrHTML.push(_getAnchorOpeningTag(condition.condition_data.studies_keyed[strStudy].url));
                                                            arrHTML.push(dojox.html.entities.encode("" + condition.condition_data.studies_keyed[strStudy].number));
                                                            arrHTML.push("</a>");
                                                            totalStudies++;
                                                        }
                                                    }
                                                }

                                                arrHTML.push("</td>");
                                            }

                                            arrHTML.push(   "<td>",
                                                                _getAnchorOpeningTag(dataVariant.variant_url),
                                                                dataVariant.dbSNP_normal || "",
                                                                "/",
                                                                dataVariant.dbSNP_risk || "",
                                                                "</a>",
                                                            "</td>");

                                            var strColor1 = dataVariant.dbSNP_sample_1 == dataVariant.dbSNP_normal
                                                            ?   "green"
                                                            :   "red";

                                            var strColor2 = dataVariant.dbSNP_sample_2 == dataVariant.dbSNP_normal
                                                            ?   "green"
                                                            :   "red";

                                            arrHTML.push(   "<td>",
                                                                "<span style='color:", strColor1, "'>",
                                                                    (dataVariant.dbSNP_sample_1 || ""),
                                                                "</span>",
                                                                "<span style='color:", strColor2, "'>",
                                                                    (dataVariant.dbSNP_sample_2 || ""),
                                                                "</span>",
                                                            "</td>");

                                            var trRow = dojo.place(
                                                    dojo.create(
                                                            'tr',
                                                            {
                                                                'class':    strCSSClass
                                                            }),
                                                    that._trVariantsTableBody);
                                            trRow.innerHTML = arrHTML.join("");
                                        }

                                        for (var i = 0; i < arrVariants.length; i++) {
                                            var strVariant = arrVariants[i];
                                            var dataVariant = condition.condition_data.variants_keyed[strVariant];
                                            if (!dataVariant) continue;
                                            _addVariantRow(dataVariant, i % 2 == 0 ? 'vtr_even' : 'vtr_odd');
                                        }
                                    },

        _updateStatus:              function(strStatus) {
                                        this._divStatus.innerHTML = dojox.html.entities.encode(strStatus);
                                    }
    });
