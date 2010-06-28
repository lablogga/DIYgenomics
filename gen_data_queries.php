<?php
    /**
     * Copyright (C) 2010 DIYgenomics           diygenomics.org
     * Copyright (C) 2010 Melanie Swan          mxswan@gmail
     * Copyright (C) 2010 Marat Nepomnyashy     maratbn@gmail
     * All rights reserved.
     *
     * Module:          gen_data_queries.php
     *
     * Description:     This module is intended to contain all the utility
     *                  functions involving database queries.
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

    /**
     *  Returns an array of conditions that the user can browse through.
     */
   function queryArrayConditions() {
        /*  QUERY THE DATABASE - QUERY 3(CONDITIONS LIST)
                Returns a list like this:
                1   Alzheimer's disease
                2   Atrial fibrillation
                3   Breast cancer
                4   Celiac disease
                5   Colorectal cancer
                6   Crohn's disease
                7   Diabetes (type 1)
                8   Diabetes (type 2)
                9   Glaucoma
                10  Heart attack
                11  Lung cancer
                12  Lupus
                13  Macular degeneration
                14  Multiple sclerosis
                15  Obesity
                16  Prostate cancer
                17  Psoriasis
                18  Restless legs syndrome
                19  Rheumatoid arthritis
                20  Ulcerative colitis
        */

        $QueryConditions = mysql_query("SELECT 2_conditions.Primary, 2_conditions.Condition FROM 2_conditions;")
                            or die("<p>Unable to query a database table for conditions.  Error code: " . mysql_errno() . "</p>");


        //READ QUERY 3 RESULTS INTO ARRAY
        // conditions holds rows {condition_ix, condition_name}
        $Conditions = array();
        while ($CondRow = mysql_fetch_array($QueryConditions)) {
            $Conditions[] = $CondRow;
        }

        return $Conditions;
    }

    /**
     *  This function queries the database for the data associated with the specified health condition id, and
     *  returns that data in a hierarchial data structure.
     *
     *  The data structure looks like this:
     *
     *  {
     *      entities:                           ["Navigenics", "deCODEme"],
     *      entities_keyed: {
     *          "Navigenics": {
     *              "entity":                   "Navigenics",
     *              "entity_cond_url":          "http://www.navigenics.com/demo/for_scientists/d/alzheimers_disease/"
     *          },
     *          "deCODEme": {
     *              "entity":                   "deCODEme",
     *              "entity_cond_url":          "http://demo.decodeme.com/health-watch/details/ALZ"
     *          }
     *      },
     *      studies:                            ["17474819", "9343467", "19734902"],
     *      studies_keyed: {
     *          "17474819": {
     *              "number":                   1,
     *              "pubmedid":                 "17474819",
     *              "url":                      "http://www.ncbi.nlm.nih.gov/pubmed/17474819",
     *              "citation":                 "Coon KD et al.; A high-density whole-genome association study reveals that APOE is the major susceptibility gene for sporadic late-onset Alzheimer's disease; J ClinPsychiatry; 2007 Apr;68(4):613-8."
     *          },
     *          "9343467": {
     *              "number":                   2,
     *              "pubmedid":                 "9343467",
     *              "url":                      "http://www.ncbi.nlm.nih.gov/pubmed/9343467",
     *              "citation":                 "Farrer LA et al.; Effects of age sex and ethnicity on the association between apolipoprotein E genotype and Alzheimer disease. A meta-analysis. APOE and Alzheimer Disease Meta Analysis Consortium; JAMA; 1997 Oct 22-29;278(16):1349-56."
     *          },
     *          "19734902": {
     *              "number":                   3,
     *              "pubmedid":                 "19734902",
     *              "url":                      "http://www.ncbi.nlm.nih.gov/pubmed/19734902",
     *              "citation":                 "Harold D et al.; Genome-wide association study identifies variants at CLU and PICALM associated with Alzheimer's disease; Nat Genet; 2009 Oct;41(10):1088-93."
     *          }
     *      },
     *      variants:                           ["rs11136000", "rs429358", "rs7412"],
     *      variants_keyed: {
     *          "rs11136000": {
     *              "dbSNP_normal":             "C",
     *              "dbSNP_risk":               "T",
     *              "dbSNP_sample_1":           "T",
     *              "dbSNP_sample_2":           "T",
     *              "gene":                     "CLU",
     *              "gene_url":                 "http://www.ncbi.nlm.nih.gov/sites/entrez?db=gene&cmd=search&term=CLU",
     *              "locus":                    "8p21.1",
     *              "locus_url":                "http://www.ncbi.nlm.nih.gov/Omim/getmap.cgi?chromosome=8p21.1",
     *              "studies":                  ["19734902"],
     *              "studies_keyed": {
     *                  "19734902": [
     *                      "entities": [
     *                          "deCODEme":     true
     *                      ]
     *                  ]
     *              },
     *              "variant":                  "rs11136000",
     *              "variant_url":              "http://www.ncbi.nlm.nih.gov/projects/SNP/snp_ref.cgi?rs=rs11136000"
     *          },
     *          "rs429358": {
     *              "dbSNP_normal":             "C",
     *              "dbSNP_risk":               "T",
     *              "dbSNP_sample_1":           "",
     *              "dbSNP_sample_2":           "",
     *              "gene":                     "APOE",
     *              "gene_url":                 "http://www.ncbi.nlm.nih.gov/sites/entrez?db=gene&cmd=search&term=APOE",
     *              "locus":                    "19q13.32",
     *              "locus_url":                "http://www.ncbi.nlm.nih.gov/Omim/getmap.cgi?chromosome=19q13.32",
     *              "studies":                  ["17474819", "9343467"],
     *              "studies_keyed": {
     *                  "17474819": [
     *                      "entities": [
     *                          "Navigenics":   true
     *                      ]
     *                  ],
     *                  "9343467": [
     *                      "entities": [
     *                          "deCODEme":     true
     *                      ]
     *                  ]
     *              },
     *              "variant":                  "rs429358",
     *              "variant_url":              "http://www.ncbi.nlm.nih.gov/projects/SNP/snp_ref.cgi?rs=rs429358"
     *          },
     *          "rs7412": {
     *              "dbSNP_normal":             "C",
     *              "dbSNP_risk":               "T",
     *              "dbSNP_sample_1":           "T",
     *              "dbSNP_sample_2":           "T",
     *              "gene":                     "APOE",
     *              "gene_url":                 "http://www.ncbi.nlm.nih.gov/sites/entrez?db=gene&cmd=search&term=APOE",
     *              "locus":                    "19q13.32",
     *              "locus_url":                "http://www.ncbi.nlm.nih.gov/Omim/getmap.cgi?chromosome=19q13.32",
     *              "studies":                  ["17474819", "9343467"],
     *              "studies_keyed": {
     *                  "17474819": [
     *                      "entities": [
     *                          "Navigenics":   true
     *                      ]
     *                  ],
     *                  "9343467": [
     *                      "entities": [
     *                          "deCODEme":     true
     *                      ]
     *                  ]
     *              },
     *              "variant":                  "rs7412",
     *              "variant_url":              "http://www.ncbi.nlm.nih.gov/projects/SNP/snp_ref.cgi?rs=rs7412"
     *          }
     *      }
     *  }
     */
    function queryDataForCondition($idCondition) {

        $strQueryStudiesInfo = "SELECT 1_studies.PMID,"
                            . " 1_studies.PMID_URL,"
                            . " 1_studies.Citation,"
                            . " 3_variants.Variant,"
                            . " 3_variants.Variant_URL,"
                            . " 3_variants.Locus,"
                            . " 3_variants.Locus_URL,"
                            . " 3_variants.Gene,"
                            . " 3_variants.Gene_URL,"
                            . " 3_variants.dbSNP_normal,"
                            . " 3_variants.dbSNP_risk,"
                            . " 3_variants.23andMe_1,"
                            . " 3_variants.23andMe_2,"
                            . " 4_entities.Entity"
                            . " FROM 1_studies"
                            . " JOIN 8_map_variant_condition_entity_study ON 1_studies.Primary = 8_map_variant_condition_entity_study.Study_index"
                            . " JOIN 3_variants ON 3_variants.Primary = 8_map_variant_condition_entity_study.Variant_index"
                            . " JOIN 4_entities ON 4_entities.Primary = 8_map_variant_condition_entity_study.Entity_index"
                            . " WHERE 8_map_variant_condition_entity_study.Condition_index = " . $idCondition
                            . " ORDER BY 1_studies.Citation";

        $resultQueryStudiesInfo = mysql_query($strQueryStudiesInfo)
            or die("<p>Unable to query the database for studies information.  Error code: " . mysql_errno() . "</p>");

        $mapDataCurrentCondition = array(
                                        'entities'          => array(),
                                        'entities_keyed'    => array(),
                                        'studies'           => array(),
                                        'studies_keyed'     => array(),
                                        'variants'          => array(),
                                        'variants_keyed'    => array());

        while ($arrStudyInfo = mysql_fetch_array($resultQueryStudiesInfo)) {
            $field_pubmedid         = $arrStudyInfo[0];
            $field_url              = $arrStudyInfo[1];
            $field_citation         = $arrStudyInfo[2];
            $field_variant          = $arrStudyInfo[3];
            $field_variant_url      = $arrStudyInfo[4];
            $field_locus            = $arrStudyInfo[5];
            $field_locus_url        = $arrStudyInfo[6];
            $field_gene             = $arrStudyInfo[7];
            $field_gene_url         = $arrStudyInfo[8];
            $field_dbSNP_normal     = $arrStudyInfo[9];
            $field_dbSNP_risk       = $arrStudyInfo[10];
            $field_23andMe_1        = $arrStudyInfo[11];
            $field_23andMe_2        = $arrStudyInfo[12];
            $field_entity           = $arrStudyInfo[13];

            if (!$mapDataCurrentCondition['entities_keyed'][$field_entity]) {
                $mapDataCurrentCondition['entities_keyed'][$field_entity] = array(
                                                                                'entity'        => $field_entity);
                $mapDataCurrentCondition['entities'][] = $field_entity;
            }

            if (!$mapDataCurrentCondition['studies_keyed'][$field_pubmedid]) {
                $mapDataCurrentCondition['studies'][] = $field_pubmedid;
                $mapDataCurrentCondition['studies_keyed'][$field_pubmedid] = array(
                                                                                'number'        => count($mapDataCurrentCondition['studies']),
                                                                                'pubmedid'      => $field_pubmedid,
                                                                                'url'           => $field_url,
                                                                                'citation'      => $field_citation);
            }

            if (!$mapDataCurrentCondition['variants_keyed'][$field_variant]) {
                $mapDataCurrentCondition['variants_keyed'][$field_variant] = array(
                                                                                'gene'          => $field_gene,
                                                                                'gene_url'      => $field_gene_url,
                                                                                'locus'         => $field_locus,
                                                                                'locus_url'     => $field_locus_url,
                                                                                'studies'       => array(),
                                                                                'variant'       => $field_variant,
                                                                                'variant_url'   => $field_variant_url,
                                                                                'dbSNP_normal'  => $field_dbSNP_normal,
                                                                                'dbSNP_risk'    => $field_dbSNP_risk,
                                                                                'dbSNP_sample_1'=> $field_23andMe_1,
                                                                                'dbSNP_sample_2'=> $field_23andMe_2);

                $mapDataCurrentCondition['variants'][] = $field_variant;
            }

            if (!$mapDataCurrentCondition['variants_keyed'][$field_variant]['studies_keyed'][$field_pubmedid]) {
                $mapDataCurrentCondition['variants_keyed'][$field_variant]['studies_keyed'][$field_pubmedid]
                                                                            = array(
                                                                                'entities'      => array());
                $mapDataCurrentCondition['variants_keyed'][$field_variant]['studies'][] = $field_pubmedid;
            }

            if (!$mapDataCurrentCondition['variants_keyed'][$field_variant]['studies_keyed'][$field_pubmedid]['entities'][$field_entity]) {
                $mapDataCurrentCondition['variants_keyed'][$field_variant]['studies_keyed'][$field_pubmedid]['entities'][$field_entity]
                                                                            = true;
            }
        }

        //SORT BY LOCUS
        usort(
            $mapDataCurrentCondition['variants'],
            function($strVariant1, $strVariant2) use ($mapDataCurrentCondition) {
                $cmp = strnatcmp(
                            $mapDataCurrentCondition['variants_keyed'][$strVariant1]['locus'],
                            $mapDataCurrentCondition['variants_keyed'][$strVariant2]['locus']);
                if ($cmp <> 0) return $cmp;

                return strcmp($strVariant1, $strVariant2);
            });

        // Query the disease / condition URLs from the database:
        $strQueryDiseaseURLs = "SELECT 4_entities.Entity, 6_map_entity_condition.URL"
                                    . " FROM 6_map_entity_condition JOIN 4_entities"
                                    . " WHERE Condition_index = " . $idCondition . " AND 6_map_entity_condition.Entity_index = 4_entities.Primary";
        $resultQueryDiseaseURLs = mysql_query($strQueryDiseaseURLs)
            or die("<p>Unable to query the database for conditions.  Error code: " . mysql_errno() . "</p>");

        // Process the query results into the current condition data:
        while ($arrDiseaseURL = mysql_fetch_array($resultQueryDiseaseURLs)) {
            $field_entity           = $arrDiseaseURL[0];
            $field_entity_cond_url  = $arrDiseaseURL[1];

            if ($mapDataCurrentCondition['entities_keyed'][$field_entity]) {
                $mapDataCurrentCondition['entities_keyed'][$field_entity]['entity_cond_url'] = $field_entity_cond_url;
            }
        }

        return $mapDataCurrentCondition;
    }

    /**
     *  Queries the database for the data containing all the information on all the conditions
     *  and their associated variants and related studies, and returns that data in a hierarchial
     *  data structure.
     *
     *  The data structure looks like this:
     *
     *  {
     *      conditions:                         [
     *                                              "Alzheimer's disease",
     *                                              "Atrial fibrillation",
     *                                              "Breast cancer",
     *                                              "Celiac disease",
     *                                              "Colorectal cancer",
     *                                              "Crohn"s disease",
     *                                              "Diabetes (type 1)",
     *                                              "Diabetes (type 2)",
     *                                              "Glaucoma",
     *                                              "Heart attack",
     *                                              "Lung cancer",
     *                                              "Lupus",
     *                                              "Macular degeneration",
     *                                              "Multiple sclerosis",
     *                                              "Obesity",
     *                                              "Prostate cancer",
     *                                              "Psoriasis",
     *                                              "Restless legs syndrome",
     *                                              "Rheumatoid arthritis",
     *                                              "Ulcerative colitis"
     *                                          ],
     *      conditions_keyed: {
     *          "Alzheimer's disease": {
     *              id:                         1,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Atrial fibrillation": {
     *              id:                         2,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Breast cancer": {
     *              id:                         3,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Celiac disease": {
     *              id:                         4,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Colorectal cancer": {
     *              id:                         5,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Crohn"s disease": {
     *              id:                         6,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Diabetes (type 1)": {
     *              id:                         7,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Diabetes (type 2)": {
     *              id:                         8,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Glaucoma": {
     *              id:                         9,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Heart attack": {
     *              id:                         10,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Lung cancer": {
     *              id:                         11,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Lupus": {
     *              id:                         12,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Macular degeneration": {
     *              id:                         13,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Multiple sclerosis": {
     *              id:                         14,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Obesity": {
     *              id:                         15,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Prostate cancer": {
     *              id:                         16,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Psoriasis": {
     *              id:                         17,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Restless legs syndrome": {
     *              id:                         18,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Rheumatoid arthritis": {
     *              id:                         19,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          },
     *          "Ulcerative colitis": {
     *              id:                         20,
     *              condition_data:             // data structure from queryDataForCondition(id)
     *          }
     *      }
     *  }
     */
    function queryDataForConditionsAll() {
        $arrConditions = queryArrayConditions();

        $mapDataConditions = array(
                                'conditions'        => array(),
                                'conditions_keyed'  => array());

        foreach ($arrConditions as $arrCondition) {
            $idCondition = $arrCondition[0];
            $strCondition = $arrCondition[1];

            $mapDataConditions['conditions'][] = $strCondition;
            $mapDataConditions['conditions_keyed'][$strCondition] = array(
                                                                        'id'                => $idCondition,
                                                                        'condition_data'    => queryDataForCondition($idCondition));
        }

        return $mapDataConditions;
    }



 /**
   *  The same three queries for the drug response app
   */

   /**
     *  Returns an array of drugs that the user can browse through.
     */
    function queryArrayDrugs() {
        /*  QUERY THE DATABASE - QUERY 3(DRUG LIST)
                Returns a list like this:
                1   abacavir
                2   acenocoumarol
                3   acitretin
                4   adalimumab
                .
                .
                .
                233 zoledronate
                234 zolmitriptan
        */

     $QueryDrugs = mysql_query("SELECT 5_drugs.Primary, 5_drugs.Drug FROM 5_drugs;")
                            or die("<p>Unable to query a database table for conditions.  Error code: " . mysql_errno() . "</p>");

        //READ QUERY 3 RESULTS INTO ARRAY
        // drugs holds rows {drug_ix, drug_name}
        $Drugs = array();
        while ($DrugRow = mysql_fetch_array($QueryDrugs)) {
            $Drugs[] = $DrugRow;
        }

        return $Drugs;
    }

    /**
     *  This function queries the database for the data associated with the specified drug id, and
     *  returns that data in a hierarchial data structure.
     *
     *  The data structure looks like this:
     *
     *  {
     *      entities:                           ["PharmGKB", "Navigenics"],
     *      entities_keyed: {
     *          "PharmGKB": {
     *              "entity":                   "PharmGKB",
     *              "entity_cond_url":          "http://www.pharmgkb.org/search/search.action?typeFilter=&exactMatch=false&query=abacavir"
     *          },
     *          "Navigenics": {
     *              "entity":                   "Navigenics",
     *              "entity_cond_url":          "http://www.navigenics.com/demo/for_scientists/d/alzheimers_disease/"
     *          }
     *      },
     *      studies:                            ["17474819", "9343467", "19734902"],
     *      studies_keyed: {
     *          "17474819": {
     *              "number":                   1,
     *              "pubmedid":                 "17474819",
     *              "url":                      "http://www.ncbi.nlm.nih.gov/pubmed/17474819",
     *              "citation":                 "Coon KD et al.; A high-density whole-genome association study reveals that APOE is the major susceptibility gene for sporadic late-onset Alzheimer's disease; J ClinPsychiatry; 2007 Apr;68(4):613-8."
     *          },
     *          "9343467": {
     *              "number":                   2,
     *              "pubmedid":                 "9343467",
     *              "url":                      "http://www.ncbi.nlm.nih.gov/pubmed/9343467",
     *              "citation":                 "Farrer LA et al.; Effects of age sex and ethnicity on the association between apolipoprotein E genotype and Alzheimer disease. A meta-analysis. APOE and Alzheimer Disease Meta Analysis Consortium; JAMA; 1997 Oct 22-29;278(16):1349-56."
     *          },
     *          "19734902": {
     *              "number":                   3,
     *              "pubmedid":                 "19734902",
     *              "url":                      "http://www.ncbi.nlm.nih.gov/pubmed/19734902",
     *              "citation":                 "Harold D et al.; Genome-wide association study identifies variants at CLU and PICALM associated with Alzheimer's disease; Nat Genet; 2009 Oct;41(10):1088-93."
     *          }
     *      },
     *      variants:                           ["rs11136000", "rs429358", "rs7412"],
     *      variants_keyed: {
     *          "rs11136000": {
     *              "dbSNP_normal":             "C",
     *              "dbSNP_risk":               "T",
     *              "dbSNP_sample_1":           "T",
     *              "dbSNP_sample_2":           "T",
     *              "gene":                     "CLU",
     *              "gene_url":                 "http://www.ncbi.nlm.nih.gov/sites/entrez?db=gene&cmd=search&term=CLU",
     *              "locus":                    "8p21.1",
     *              "locus_url":                "http://www.ncbi.nlm.nih.gov/Omim/getmap.cgi?chromosome=8p21.1",
     *              "studies":                  ["19734902"],
     *              "studies_keyed": {
     *                  "19734902": [
     *                      "entities": [
     *                          "deCODEme":     true
     *                      ]
     *                  ]
     *              },
     *              "variant":                  "rs11136000",
     *              "variant_url":              "http://www.ncbi.nlm.nih.gov/projects/SNP/snp_ref.cgi?rs=rs11136000"
     *          },
     *          "rs429358": {
     *              "dbSNP_normal":             "C",
     *              "dbSNP_risk":               "T",
     *              "dbSNP_sample_1":           "",
     *              "dbSNP_sample_2":           "",
     *              "gene":                     "APOE",
     *              "gene_url":                 "http://www.ncbi.nlm.nih.gov/sites/entrez?db=gene&cmd=search&term=APOE",
     *              "locus":                    "19q13.32",
     *              "locus_url":                "http://www.ncbi.nlm.nih.gov/Omim/getmap.cgi?chromosome=19q13.32",
     *              "studies":                  ["17474819", "9343467"],
     *              "studies_keyed": {
     *                  "17474819": [
     *                      "entities": [
     *                          "Navigenics":   true
     *                      ]
     *                  ],
     *                  "9343467": [
     *                      "entities": [
     *                          "deCODEme":     true
     *                      ]
     *                  ]
     *              },
     *              "variant":                  "rs429358",
     *              "variant_url":              "http://www.ncbi.nlm.nih.gov/projects/SNP/snp_ref.cgi?rs=rs429358"
     *          },
     *          "rs7412": {
     *              "dbSNP_normal":             "C",
     *              "dbSNP_risk":               "T",
     *              "dbSNP_sample_1":           "T",
     *              "dbSNP_sample_2":           "T",
     *              "gene":                     "APOE",
     *              "gene_url":                 "http://www.ncbi.nlm.nih.gov/sites/entrez?db=gene&cmd=search&term=APOE",
     *              "locus":                    "19q13.32",
     *              "locus_url":                "http://www.ncbi.nlm.nih.gov/Omim/getmap.cgi?chromosome=19q13.32",
     *              "studies":                  ["17474819", "9343467"],
     *              "studies_keyed": {
     *                  "17474819": [
     *                      "entities": [
     *                          "Navigenics":   true
     *                      ]
     *                  ],
     *                  "9343467": [
     *                      "entities": [
     *                          "deCODEme":     true
     *                      ]
     *                  ]
     *              },
     *              "variant":                  "rs7412",
     *              "variant_url":              "http://www.ncbi.nlm.nih.gov/projects/SNP/snp_ref.cgi?rs=rs7412"
     *          }
     *      }
     *  }
     */
    function queryDataForDrug($idDrug) {

        $strQueryStudiesInfo = "SELECT 1_studies.PMID,"
                            . " 1_studies.PMID_URL,"
                            . " 1_studies.Citation,"
                            . " 3_variants.Variant,"
                            . " 3_variants.Variant_URL,"
                            . " 3_variants.Locus,"
                            . " 3_variants.Locus_URL,"
                            . " 3_variants.Gene,"
                            . " 3_variants.Gene_URL,"
                            . " 3_variants.dbSNP_normal,"
                            . " 3_variants.dbSNP_risk,"
                            . " 3_variants.23andMe_1,"
                            . " 3_variants.23andMe_2,"
                            . " 4_entities.Entity"
                            . " FROM 1_studies"
                            . " JOIN 8_map_variant_condition_entity_study ON 1_studies.Primary = 8_map_variant_condition_entity_study.Study_index"
                            . " JOIN 3_variants ON 3_variants.Primary = 8_map_variant_condition_entity_study.Variant_index"
                            . " JOIN 4_entities ON 4_entities.Primary = 8_map_variant_condition_entity_study.Entity_index"
                            . " WHERE 8_map_variant_condition_entity_study.Drug_index = " . $idDrug
                            . " ORDER BY 1_studies.Citation";


        $resultQueryStudiesInfo = mysql_query($strQueryStudiesInfo)
            or die("<p>Unable to query the database for studies information.  Error code: " . mysql_errno() . "</p>");

        $mapDataCurrentDrug = array(
                                        'entities'          => array(),
                                        'entities_keyed'    => array(),
                                        'studies'           => array(),
                                        'studies_keyed'     => array(),
                                        'variants'          => array(),
                                        'variants_keyed'    => array());

        while ($arrStudyInfo = mysql_fetch_array($resultQueryStudiesInfo)) {
            $field_pubmedid         = $arrStudyInfo[0];
            $field_url              = $arrStudyInfo[1];
            $field_citation         = $arrStudyInfo[2];
            $field_variant          = $arrStudyInfo[3];
            $field_variant_url      = $arrStudyInfo[4];
            $field_locus            = $arrStudyInfo[5];
            $field_locus_url        = $arrStudyInfo[6];
            $field_gene             = $arrStudyInfo[7];
            $field_gene_url         = $arrStudyInfo[8];
            $field_dbSNP_normal     = $arrStudyInfo[9];
            $field_dbSNP_risk       = $arrStudyInfo[10];
            $field_23andMe_1        = $arrStudyInfo[11];
            $field_23andMe_2        = $arrStudyInfo[12];
            $field_entity           = $arrStudyInfo[13];

            if (!$mapDataCurrentDrug['entities_keyed'][$field_entity]) {
                $mapDataCurrentDrug['entities_keyed'][$field_entity] = array(
                                                                                'entity'        => $field_entity);
                $mapDataCurrentDrug['entities'][] = $field_entity;
            }

            if (!$mapDataCurrentDrug['studies_keyed'][$field_pubmedid]) {
                $mapDataCurrentDrug['studies'][] = $field_pubmedid;
                $mapDataCurrentDrug['studies_keyed'][$field_pubmedid] = array(
                                                                                'number'        => count($mapDataCurrentDrug['studies']),
                                                                                'pubmedid'      => $field_pubmedid,
                                                                                'url'           => $field_url,
                                                                                'citation'      => $field_citation);
            }

            if (!$mapDataCurrentDrug['variants_keyed'][$field_variant]) {
                $mapDataCurrentDrug['variants_keyed'][$field_variant] = array(
                                                                                'gene'          => $field_gene,
                                                                                'gene_url'      => $field_gene_url,
                                                                                'locus'         => $field_locus,
                                                                                'locus_url'     => $field_locus_url,
                                                                                'studies'       => array(),
                                                                                'variant'       => $field_variant,
                                                                                'variant_url'   => $field_variant_url,
                                                                                'dbSNP_normal'  => $field_dbSNP_normal,
                                                                                'dbSNP_risk'    => $field_dbSNP_risk,
                                                                                'dbSNP_sample_1'=> $field_23andMe_1,
                                                                                'dbSNP_sample_2'=> $field_23andMe_2);

                $mapDataCurrentDrug['variants'][] = $field_variant;
            }

            if (!$mapDataCurrentDrug['variants_keyed'][$field_variant]['studies_keyed'][$field_pubmedid]) {
                $mapDataCurrentDrug['variants_keyed'][$field_variant]['studies_keyed'][$field_pubmedid]
                                                                            = array(
                                                                                'entities'      => array());
                $mapDataCurrentDrug['variants_keyed'][$field_variant]['studies'][] = $field_pubmedid;
            }

            if (!$mapDataCurrentDrug['variants_keyed'][$field_variant]['studies_keyed'][$field_pubmedid]['entities'][$field_entity]) {
                $mapDataCurrentDrug['variants_keyed'][$field_variant]['studies_keyed'][$field_pubmedid]['entities'][$field_entity]
                                                                            = true;
            }
        }

        //SORT BY LOCUS
        usort(
            $mapDataCurrentDrug['variants'],
            function($strVariant1, $strVariant2) use ($mapDataCurrentDrug) {
                $cmp = strnatcmp(
                            $mapDataCurrentDrug['variants_keyed'][$strVariant1]['locus'],
                            $mapDataCurrentDrug['variants_keyed'][$strVariant2]['locus']);
                if ($cmp <> 0) return $cmp;

                return strcmp($strVariant1, $strVariant2);
            });

        // Query the drug URLs from the database:
        $strQueryDrugURLs = "SELECT 4_entities.Entity, 6_map_entity_condition.URL"
                                    . " FROM 6_map_entity_condition JOIN 4_entities"
                                    . " WHERE Drug_index = " . $idDrug . " AND 6_map_entity_condition.Entity_index = 4_entities.Primary";
        $resultQueryDrugURLs = mysql_query($strQueryDrugURLs)
            or die("<p>Unable to query the database for drugs.  Error code: " . mysql_errno() . "</p>");

        // Process the query results into the current drug data:
        while ($arrDrugURL = mysql_fetch_array($resultQueryDrugURLs)) {
            $field_entity           = $arrDrugURL[0];
            $field_entity_cond_url  = $arrDrugURL[1];

            if ($mapDataCurrentDrug['entities_keyed'][$field_entity]) {
                $mapDataCurrentDrug['entities_keyed'][$field_entity]['entity_drug_url'] = $field_entity_drug_url;
            }
        }

        return $mapDataCurrentDrug;
    }


    /**
     *  Queries the database for the data containing all the information on all the drugs
     *  and their associated variants and related studies, and returns that data in a hierarchial
     *  data structure.
     *
     *  The data structure looks like this:
     *
     *  {
     *      drugs:                         [
     *                                              "abacavir",
     *                                              "acenocoumarol",
     *                                              "acitretin",
     *                                              "adalimumab",
     *                                              ".",
     *                                              ".",
     *                                              ".",
     *                                              "zoledronate",
     *                                              "zolmitriptan"
     *                                          ],
     *      drugs_keyed: {
     *          "abacavir": {
     *              id:                         1,
     *              drug_data:                  // data structure from queryDataForDrug(id)
     *          },
     *          "acenocoumarol": {
     *              id:                         2,
     *              drug_data:                  // data structure from queryDataForDrug(id)
     *          },
     *          "acitretin": {
     *              id:                         3,
     *              drug_data:                  // data structure from queryDataForDrug(id)
     *          },
     *          "adalimumab": {
     *              id:                         4,
     *              drug_data:                  // data structure from queryDataForDrug(id)
     *          },
     *          ".": {
     *              id:                         5,
     *              drug_data:                  // data structure from queryDataForDrug(id)
     *          },
     *          ".": {
     *              id:                         6,
     *              drug_data:                  // data structure from queryDataForDrug(id)
     *          },
     *          ".": {
     *              id:                         7,
     *              drug_data:                  // data structure from queryDataForDrug(id)
     *          },
     *          "zoledronate": {
     *              id:                         8,
     *              drug_data:                  // data structure from queryDataForDrug(id)
     *          },
     *          "zolmitriptan": {
     *              id:                         9,
     *              drug_data:                  // data structure from queryDataForDrug(id)
     *          }
     *      }
     *  }
     */
    function queryDataForDrugsAll() {
        $arrDrugs = queryArrayDrugs();

        $mapDataDrugs = array(
                                'drugs'        => array(),
                                'drugs_keyed'  => array());

        foreach ($arrDrugs as $arrDrug) {
            $idDrug = $arrDrug[0];
            $strDrug = $arrDrug[1];

            $mapDataDrugs['drugs'][] = $strDrug;
            $mapDataDrugs['drugs_keyed'][$strDrug] = array(
                                                                        'id'                => $idDrug,
                                                                        'drug_data'         => queryDataForDrug($idDrug));
        }

        return $mapDataDrugs;
    }


    ?>