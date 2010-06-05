<?php
    /**
     * Copyright (C) 2010 DIYgenomics           diygenomics.org
     * Copyright (C) 2010 Melanie Swan          mxswan@gmail
     * Copyright (C) 2010 Marat Nepomnyashy     maratbn@gmail
     *
     * Module: gen_data_variants_info.php
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

    function printVariantsInfo($CurrentCondition) {

        /**
         *  This function queries the database for data associated with the currently selected health condition, and
         *  returns this data in a hierarchial data structure.
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
        function getDataCurrentCondition() {

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
                                . " WHERE 8_map_variant_condition_entity_study.Condition_index = " . getCurrentConditionID()
                                . " ORDER BY 1_studies.Citation";

            $resultQueryStudiesInfo = mysql_query($strQueryStudiesInfo)
                or die("<p>Unable to query the database for studies information.  Error code: " . mysql_connect_errno() . "</p>");

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
                                        . " WHERE Condition_index = " . getCurrentConditionID() . " AND 6_map_entity_condition.Entity_index = 4_entities.Primary";
            $resultQueryDiseaseURLs = mysql_query($strQueryDiseaseURLs)
                or die("<p>Unable to query the database for conditions.  Error code: " . mysql_connect_errno() . "</p>");

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

        $mapDataCurrentCondition = getDataCurrentCondition();

        //CREATE RESULTS TABLE FROM MAIN QUERY (QUERY 1)
    ?>
    <h3>Variants reviewed for <?=$CurrentCondition?></h3>
    <table id='myTable' width='80%' cellspacing='1' cellpadding='0' style='border:solid 1px #cccccc; margin-left: 1.4 in;'>
        <tr ALIGN='center'>
            <th>Locus</th>
            <th>Gene</th>
            <th>Variant</th>
            <?php
                $arrEntityColumns = array('deCODEme', 'Navigenics', '23andMe');
                foreach ($arrEntityColumns as $strEntity) {
                    ?>
                        <th><a href='<?=$mapDataCurrentCondition['entities_keyed'][$strEntity]['entity_cond_url']?>'><?=$strEntity?></a></th>
                    <?php
                }
            ?>
            <th><a href='http://www.ncbi.nlm.nih.gov/projects/SNP'>dbSNP (Nrml/Rsk)</a></th>
            <th>Sample data</th>
        </tr>
        <?php
            foreach ($mapDataCurrentCondition['variants'] as $strVariant) {
                ?>
                    <tr>
                        <td align='center'>
                            <a href='<?=$mapDataCurrentCondition['variants_keyed'][$strVariant]['locus_url']?>'>
                                <?=$mapDataCurrentCondition['variants_keyed'][$strVariant]['locus']?>
                            </a>
                        </td>
                        <td align='center'>
                            <a href='<?=$mapDataCurrentCondition['variants_keyed'][$strVariant]['gene_url']?>'>
                                <?=$mapDataCurrentCondition['variants_keyed'][$strVariant]['gene']?>
                            </a>
                        </td>
                        <td align='center'>
                            <a href='<?=$mapDataCurrentCondition['variants_keyed'][$strVariant]['variant_url']?>'>
                                <?=$mapDataCurrentCondition['variants_keyed'][$strVariant]['variant']?>
                            </a>
                        </td>

                        <?php
                            foreach ($arrEntityColumns as $strEntity) {
                                ?>
                                <td align='center'>
                                    <?php
                                        $totalStudies = 0;
                                        foreach ($mapDataCurrentCondition['variants_keyed'][$strVariant]['studies'] as $strStudy) {
                                            if ($mapDataCurrentCondition['variants_keyed'][$strVariant]['studies_keyed'][$strStudy]['entities'][$strEntity]) {
                                                if ($totalStudies > 0) echo ",";
                                                ?><a href='<?=$mapDataCurrentCondition['studies_keyed'][$strStudy]['url']?>'><?=$mapDataCurrentCondition['studies_keyed'][$strStudy]['number']?></a><?php
                                                $totalStudies++;
                                            }
                                        }
                                    ?>
                                </td>
                                <?php
                            }
                        ?>

                        <td align='center'>
                            <a href='<?=$mapDataCurrentCondition['variants_keyed'][$strVariant]['variant_url']?>'>
                                <?=$mapDataCurrentCondition['variants_keyed'][$strVariant]['dbSNP_normal']?>/<?=$mapDataCurrentCondition['variants_keyed'][$strVariant]['dbSNP_risk']?>
                            </a>
                        </td>
                        <td align='center'>
                            <?php
                                $strColor1 = $mapDataCurrentCondition['variants_keyed'][$strVariant]['dbSNP_sample_1']
                                                ==  $mapDataCurrentCondition['variants_keyed'][$strVariant]['dbSNP_normal']
                                                ?   "green"
                                                :   "red";

                                $strColor2 = $mapDataCurrentCondition['variants_keyed'][$strVariant]['dbSNP_sample_2']
                                                ==  $mapDataCurrentCondition['variants_keyed'][$strVariant]['dbSNP_normal']
                                                ?   "green"
                                                :   "red";

                                echo "<span style='color:$strColor1;'>" . $mapDataCurrentCondition['variants_keyed'][$strVariant]['dbSNP_sample_1'] . "</span>";
                                echo "<span style='color:$strColor2;'>" . $mapDataCurrentCondition['variants_keyed'][$strVariant]['dbSNP_sample_2'] . "</span>";
                            ?>
                        </td>
                    </tr>
                <?php
            }
        ?>
    </table>

    <h4><i>Research references cited by consumer genomic companies:</i></h4>

    <ol>
        <?php
            foreach ($mapDataCurrentCondition['studies'] as $key => $field_pubmedid) {
                $study = $mapDataCurrentCondition['studies_keyed'][$field_pubmedid];
                $cit = $study["citation"];
                $citurl = $study["url"];
                ?>
                    <li>
                        <a href='<?=$citurl?>'><?=$cit?></a>
                    </li>
                <?php
            }
        ?>
    </ol>
    <?php
    }
?>