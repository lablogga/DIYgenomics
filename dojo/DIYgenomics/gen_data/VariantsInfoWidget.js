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
dojo.require('dojox.timing');

dojo.declare(
    'DIYgenomics.gen_data.VariantsInfoWidget',
    [dijit._Widget, dijit._Templated],
    {
        condition:                  "",
        idReplace:                  "",

        templateString:             [   "<div>",
                                            "<div dojoAttachPoint='_divStatus'>",
                                            "</div>",
                                            "<div dojoAttachPoint='_divContent' style='display:none;'>",
                                                "<div class='selection_combobox'>",
                                                    "<select dojoAttachPoint='_selectConditions' dojoAttachEvent='onchange:onSelectedCondition'>",
                                                    "</select>",
                                                "</div>",
                                                "<h3>Variants reviewed for ",
                                                    "<span dojoAttachPoint='_spanCondition'>${condition}</span>",
                                                "</h3>",
                                                "<h4 dojoAttachPoint='_h4Controls' style='display:none;text-align:center;'>",
                                                    "<span dojoAttachPoint='_spanVYD' style='display:none;'>",
                                                        "<a href='#' dojoAttachEvent='onclick:_onClickVYD'>Privately view your own data</a>",
                                                    "</span>",
                                                    "<span dojoAttachPoint='_spanVYD_Instructions' style='display:none;'>",
                                                      "<p style='font-size:9pt;text-align:left;'>",
                                                        "Please click 'Browse...' and select your genome file from 23andMe.  ",
                                                        "It should be a basic text file with each line formatted like this:",
                                                        "<br />",
                                                        "<code>",
                                                          "rsid &lt;TAB&gt; chromosome &lt;TAB&gt; position &lt;TAB&gt; genotype",
                                                        "</code>",
                                                      "</p>",
                                                      "<span dojoAttachPoint='_elLoadFile'></span>",
                                                      "<input type='button' value='Cancel' dojoAttachEvent='onclick:_onClickCancelLoadFile'>",
                                                      "</input>",
                                                    "</span>",
                                                    "<span dojoAttachPoint='_spanVYDW' style='display:none;'>",
                                                        "Loading your genome data file...  Please wait...",
                                                    "</span>",
                                                    "<span dojoAttachPoint='_spanVYDP' style='display:none;'>",
                                                        "Processing your genome data, ",
                                                        "<span dojoAttachPoint='_spanVYDP_Percent'>0</span>",
                                                        "% complete, found ",
                                                        "<span dojoAttachPoint='_spanVYDP_Relevant'>0</span>",
                                                        " relevant variants so far...",
                                                        "<br>",
                                                        "<a href='#' dojoAttachEvent='onclick:_onClickAbortProcessing'>Abort processing of and purge your private data</a>",
                                                    "</span>",
                                                    "<span dojoAttachPoint='_spanPYD' style='display:none;'>",
                                                        "<a href='#' dojoAttachEvent='onclick:_onClickPYD'>Purge your private data</a>",
                                                        "<br>",
                                                        "(Found ",
                                                        "<span dojoAttachPoint='_spanPYD_Relevant'>0</span>",
                                                        " relevant variants.)",
                                                    "</span>",
                                                    "<span dojoAttachPoint='_spanUFF' style='display:none;'>",
                                                      "Use the latest version of ",
                                                       "<a href='http://www.google.com/chrome' target='_blank'>",
                                                        "Chrome",
                                                      "</a>",
                                                      " or ",
                                                      "<a href='http://www.getfirefox.com/' target='_blank'>",
                                                        "Firefox",
                                                      "</a>",
                                                      " to privately view your own data.",
                                                    "</span>",
                                                "</h4>",
                                                "<table class='variants_table' cellpadding='0' cellspacing='0'>",
                                                    "<thead>",
                                                        "<tr dojoAttachPoint='_trVariantsTableHeader'>",
                                                        "</tr>",
                                                    "</thead>",
                                                    "<tbody dojoAttachPoint='_trVariantsTableBody'>",
                                                    "</tbody>",
                                                "</table>",
                                                "<h4><i>Research references cited by consumer genomic entities:</i></h4>",
                                                "<ol dojoAttachPoint='_olReferences'>",
                                                "</ol>",
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

                                        this._refreshFileInput();
                                    },

        onLoadedData:               function(data) {
                                        this._updateStatus("Processing reviewed variants data...");

                                        this.data = data;

                                        this._processVariants();

                                        this._fillConditionsCB();
                                        this._displayCurrentCondition();

                                        this._updateStatus("Completed loading and processing reviewed variants data.");
                                        dojo.style(this._divContent, 'display', "");
                                        dojo.style(this._divStatus, 'display', 'none');

                                        if (this.idReplace) {
                                            var elReplace = dojo.byId(this.idReplace);
                                            if (elReplace) dojo.style(elReplace, 'display', 'none');
                                        }

                                        if (h5ile.isFileAPISupported()) {
                                            dojo.style(this._spanVYD, 'display', "");
                                        } else {
                                            dojo.style(this._spanUFF, 'display', "");
                                        }
                                        dojo.style(this._h4Controls, 'display', "");
                                    },

        onSelectedCondition:        function() {
                                        this.condition = dojo.attr(this._selectConditions, 'value');
                                        this._displayCurrentCondition();
                                    },

        _processVariants:           function() {
                                        mapRelevantVariants = {};

                                        if (!this.data || !this.data.conditions || !this.data.conditions_keyed) return;

                                        for (var i = 0; i < this.data.conditions.length; i++) {
                                            var strCondition = this.data.conditions[i];
                                            if (!strCondition) continue;

                                            var dataCondition = this.data.conditions_keyed[strCondition];
                                            if (!dataCondition ||
                                                !dataCondition.condition_data ||
                                                !dataCondition.condition_data.variants ||
                                                !dataCondition.condition_data.variants_keyed) continue;

                                            for (var j = 0; j < dataCondition.condition_data.variants.length; j++) {
                                                var strVariant = dataCondition.condition_data.variants[j];
                                                if (!strVariant) continue;

                                                mapRelevantVariants[strVariant] = true;
                                            }
                                        }
                                    },

        _clearChildNodes:           function(elNode) {
                                        if (!elNode) return;

                                        while (elNode.hasChildNodes()) {
                                            elNode.removeChild(elNode.lastChild);
                                        }
                                    },

        _displayCurrentCondition:   function() {
                                        this._spanCondition.innerHTML = this.condition;
                                        this._fillVariantsTable();
                                        this._fillReferencesList();
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

        _fillReferencesList:        function() {
                                        this._clearChildNodes(this._olReferences);

                                        var condition_data =    this.data &&
                                                                this.data.conditions_keyed &&
                                                                this.data.conditions_keyed[this.condition] &&
                                                                this.data.conditions_keyed[this.condition].condition_data;
                                        if (!condition_data || !condition_data.studies || !condition_data.studies_keyed) return;

                                        var that = this;

                                        function _appendStudy(dataStudy) {
                                            var liStudy = dojo.create(
                                                            'li',
                                                            {},
                                                            that._olReferences);

                                            liStudy.innerHTML = [   that._getAnchorOpeningTag(dataStudy.url),
                                                                        dojox.html.entities.encode(dataStudy.citation),
                                                                    "</a>"
                                                                ].join("");
                                        }

                                        for (var i = 0; i < condition_data.studies.length; i++) {
                                            var strStudy = condition_data.studies[i];
                                            if (!strStudy) continue;

                                            _appendStudy(condition_data.studies_keyed[strStudy]);
                                        }
                                    },

        _fillVariantsTable:         function() {
                                        this._clearChildNodes(this._trVariantsTableHeader);
                                        this._clearChildNodes(this._trVariantsTableBody);

                                        var condition = this.data && this.data.conditions_keyed && this.data.conditions_keyed[this.condition];
                                        if (!condition) return;

                                        var that = this;

                                        function _addHeaderColumn(strName, strLink) {
                                            var strHTML = [ (strLink ? that._getAnchorOpeningTag(strLink) : ""),
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
                                        if (!this._isPrivateDataLoaded) _addHeaderColumn('Sample data');
                                        if (this._isPrivateDataLoaded) _addHeaderColumn('Your private data');

                                        _addHeaderColumn('Rank');

                                        var arrVariants =   condition.condition_data &&
                                                            condition.condition_data.variants;

                                        if (!arrVariants || !condition.condition_data.variants_keyed) return;

                                        function _addVariantRow(dataVariant, strCSSClass) {
                                            if (!dataVariant) return;

                                            var trRow = dojo.create(
                                                            'tr',
                                                            {
                                                                'class':    strCSSClass
                                                            },
                                                            that._trVariantsTableBody);

                                            function _addVariantColumn(arrHTML) {
                                                var tdColumn = dojo.create(
                                                                'td',
                                                                {
                                                                },
                                                                trRow);
                                                if (arrHTML) tdColumn.innerHTML = arrHTML.join("");
                                            }

                                            function _getColorSNP(strSNP) {
                                                return (strSNP == dataVariant['23andMe_normal'])
                                                            ?   "green"
                                                            :   "red";
                                            }

                                            _addVariantColumn([ that._getAnchorOpeningTag(dataVariant.locus_url),
                                                                    dojox.html.entities.encode(dataVariant.locus),
                                                                "</a>"]);

                                            _addVariantColumn([ that._getAnchorOpeningTag(dataVariant.gene_url),
                                                                    dojox.html.entities.encode(dataVariant.gene),
                                                                "</a>"]);

                                            _addVariantColumn([ that._getAnchorOpeningTag(dataVariant.variant_url),
                                                                    dojox.html.entities.encode(dataVariant.variant),
                                                                "</a>"]);

                                            for (var i = 0; i < arrEntities.length; i++) {
                                                var strEntity = arrEntities[i];
                                                var arrHTMLEntityColumn = [];

                                                if (dataVariant.studies) {
                                                    var totalStudies = 0;
                                                    for (var j = 0; j < dataVariant.studies.length; j++) {
                                                        var strStudy = dataVariant.studies[j];
                                                        if (dataVariant.studies_keyed &&
                                                            dataVariant.studies_keyed[strStudy] &&
                                                            dataVariant.studies_keyed[strStudy].entities &&
                                                            dataVariant.studies_keyed[strStudy].entities[strEntity]) {
                                                            if (totalStudies > 0) arrHTMLEntityColumn.push(",");
                                                            arrHTMLEntityColumn.push(that._getAnchorOpeningTag(condition.condition_data.studies_keyed[strStudy].url));
                                                            arrHTMLEntityColumn.push(dojox.html.entities.encode("" + condition.condition_data.studies_keyed[strStudy].number));
                                                            arrHTMLEntityColumn.push("</a>");
                                                            totalStudies++;
                                                        }
                                                    }
                                                }

                                                _addVariantColumn(arrHTMLEntityColumn);
                                            }

                                            _addVariantColumn([ that._getAnchorOpeningTag(dataVariant.variant_url),
                                                                dojox.html.entities.encode(dataVariant.dbSNP_normal) || "",
                                                                "/",
                                                                dojox.html.entities.encode(dataVariant.dbSNP_risk) || "",
                                                                "</a>"]);

                                            if (!that._isPrivateDataLoaded) {
                                                var strColor1 = _getColorSNP(dataVariant.dbSNP_sample_1);
                                                var strColor2 = _getColorSNP(dataVariant.dbSNP_sample_2);

                                                _addVariantColumn([ "<span style='color:", strColor1, "'>",
                                                                        (dojox.html.entities.encode(dataVariant.dbSNP_sample_1) || ""),
                                                                    "</span>",
                                                                    "<span style='color:", strColor2, "'>",
                                                                        (dojox.html.entities.encode(dataVariant.dbSNP_sample_2) || ""),
                                                                    "</span>"]);
                                            }

                                            if (that._isPrivateDataLoaded) {
                                                var htmlPrivateData = [];
                                                if (dataVariant.dbSNP_user) {
                                                    for (var i = 0; i < dataVariant.dbSNP_user.length; i++) {
                                                        var l = dataVariant.dbSNP_user[i];
                                                        var strColorPD = _getColorSNP(l);
                                                        htmlPrivateData.push(
                                                                    "<span style='color:", strColorPD, "'>",
                                                                        (dojox.html.entities.encode(l) || ""),
                                                                    "</span>");
                                                    }
                                                }
                                                _addVariantColumn(htmlPrivateData);
                                            }

                                            _addVariantColumn([dojox.html.entities.encode(dataVariant.rank)]);   

                                        }

                                        for (var i = 0; i < arrVariants.length; i++) {
                                            var strVariant = arrVariants[i];
                                            var dataVariant = condition.condition_data.variants_keyed[strVariant];
                                            if (!dataVariant) continue;
                                            _addVariantRow(dataVariant, i % 2 == 0 ? 'vtr_even' : 'vtr_odd');
                                        }
                                    },

        _getAnchorOpeningTag:       function(strLink) {
                                        return "<a href='" + dojox.html.entities.encode(strLink) + "' target='_blank'>";
                                    },

        _onClickAbortProcessing:    function() {
                                        this.flagAbortProcessing = true;
                                    },

                                    /**
                                     *  Event handler for user clicks on 'Purge your private data'.
                                     */
        _onClickPYD:                function() {
                                        this._purgeUserPrivateData();

                                        this._fillVariantsTable();
                                        dojo.style(this._spanVYD, 'display', "");
                                        dojo.style(this._spanPYD, 'display', 'none');
                                    },

        _onClickVYD:                function() {
                                        dojo.style(this._spanVYD, 'display', 'none');
                                        dojo.style(this._spanVYD_Instructions, 'display', "");
                                    },

        _onClickCancelLoadFile:     function() {
                                        dojo.style(this._spanVYD, 'display', "");
                                        dojo.style(this._spanVYD_Instructions, 'display', 'none');
                                    },

        _onDoneProcessingUserData:  function() {
                                        this._fillVariantsTable();
                                        dojo.style(this._spanVYDP, 'display', 'none');
                                        dojo.style(this._spanPYD, 'display', "");
                                    },

        _processUserGenomeFile:     function(fileUserGenome) {
                                        if (!fileUserGenome) {
                                            alert("Could not load your genome file.  You either canceled, or there was an error.");
                                            dojo.style(this._spanVYDW, 'display', 'none');
                                            dojo.style(this._spanVYD, 'display', "");
                                            return;
                                        }

                                        this._updateStatusLoadingProgress(0, 0);
                                        this._purgeUserPrivateData();

                                        dojo.style(this._spanVYDW, 'display', 'none');
                                        dojo.style(this._spanVYDP, 'display', "");

                                        var totalRelevantVariants = 0;
                                        var lineCurrent = 0;

                                        var timer = new dojox.timing.Timer(100);
                                        var that = this;
                                        timer.onTick =  function() {
                                                            if (that.flagAbortProcessing) {                                 // This checks if the user decided to cancel the data processing.
                                                                that.flagAbortProcessing = false;
                                                                timer.stop();
                                                                dojo.style(that._spanVYDP, 'display', 'none');
                                                                that._onClickPYD();
                                                                return;
                                                            }

                                                            // This just makes sure that the widget data is healthy.
                                                            if (!that.data || !that.data.conditions || !that.data.conditions_keyed) throw new Error("Logic error.");

                                                            // Cycling through 3000 lines of user private genome data at a time:
                                                            for (var i = 0; i < 3000; i++, lineCurrent++) {
                                                                if (lineCurrent == fileUserGenome.totalLines) {
                                                                    timer.stop();
                                                                    that._isPrivateDataLoaded = true;
                                                                    that._onDoneProcessingUserData();
                                                                    break;
                                                                }

                                                                var totalTokensOnLine = fileUserGenome.getTotalTokensOnLine(lineCurrent);

                                                                // Each line with the data that we are after looks like this:
                                                                // rsid    chromosome  position    genotype
                                                                if (totalTokensOnLine != 4) continue;                       // Number of tokens on the line is not 4.

                                                                // The first token should be the variant identifier, which looks like this:
                                                                // rs#######
                                                                var strTokenFirst = fileUserGenome.getTokenOnLine(0, lineCurrent);
                                                                if (!strTokenFirst) continue;
                                                                if (strTokenFirst.search(/^rs\d+$/) != 0) continue;         // First token does not appear to be a variant.

                                                                // OK, after this point we have a valid variant.
                                                                if (!mapRelevantVariants[strTokenFirst]) continue;          // Not a relevant variant.

                                                                // After this point we found a relevant variant, time to save the user data to it.
                                                                // But need to cycle through all the conditions to find out which condition(s) the
                                                                // variant is applicable to, as one variant may be applicable to multiple conditions.
                                                                for (var i = 0; i < that.data.conditions.length; i++) {
                                                                    var strCondition = that.data.conditions[i];
                                                                    if (!strCondition) continue;

                                                                    var dataCondition = that.data.conditions_keyed[strCondition];
                                                                    if (!dataCondition ||
                                                                        !dataCondition.condition_data ||
                                                                        !dataCondition.condition_data.variants ||
                                                                        !dataCondition.condition_data.variants_keyed) continue;

                                                                    if (dataCondition.condition_data.variants_keyed[strTokenFirst]) {
                                                                        // Looks like the relevant variant is applicable to this condition.
                                                                        dataCondition.condition_data.variants_keyed[strTokenFirst].dbSNP_user = fileUserGenome.getTokenOnLine(3, lineCurrent);
                                                                    }
                                                                }

                                                                totalRelevantVariants++;
                                                            }
                                                            that._updateStatusLoadingProgress(
                                                                        100 * (lineCurrent / fileUserGenome.totalLines),
                                                                        totalRelevantVariants);
                                                        }

                                        timer.start();
                                    },

        _purgeUserPrivateData:      function() {
                                        if (!this.data || !this.data.conditions || !this.data.conditions_keyed) throw new Error("Logic error.");

                                        for (var i = 0; i < this.data.conditions.length; i++) {
                                            var strCondition = this.data.conditions[i];
                                            if (!strCondition) continue;

                                            var dataCondition = this.data.conditions_keyed[strCondition];
                                            if (!dataCondition ||
                                                !dataCondition.condition_data ||
                                                !dataCondition.condition_data.variants ||
                                                !dataCondition.condition_data.variants_keyed) continue;

                                            for (var j = 0; j < dataCondition.condition_data.variants.length; j++) {
                                                var strVariant = dataCondition.condition_data.variants[j];
                                                if (!strVariant) continue;

                                                dataCondition.condition_data.variants_keyed[strVariant].dbSNP_user = null;
                                            }
                                        }

                                        this._refreshFileInput();
                                        this._isPrivateDataLoaded = false;
                                    },

        _refreshFileInput:          function() {
                                        dojo.empty(this._elLoadFile);

                                        var that = this;

                                        h5ile.createVisibleFileInput(
                                            this._elLoadFile,
                                            {
                                                loadtext: {
                                                    onloadend: function(file, file_reader) {
                                                        that._processUserGenomeFile(h5ile.splitIntoLines(file_reader));
                                                    },
                                                    onprogress: function(progress_event) {
                                                        dojo.style(that._spanVYD_Instructions, 'display', 'none');
                                                        dojo.style(that._spanVYDW, 'display', "");
                                                    }
                                                }
                                            });
                                    },

        _updateStatus:              function(strStatus) {
                                        this._divStatus.innerHTML = dojox.html.entities.encode(strStatus);
                                    },

                                    /**
                                     *  Updates the relevant <span>s with the latest progress counters.
                                     */
        _updateStatusLoadingProgress:
                                    function(totalPercentComplete, totalRelevantVariants) {
                                        this._spanVYDP_Percent.innerHTML    = totalPercentComplete.toFixed(2);
                                        this._spanVYDP_Relevant.innerHTML   = "" + totalRelevantVariants;
                                        this._spanPYD_Relevant.innerHTML    = "" + totalRelevantVariants;
                                    }
    });
