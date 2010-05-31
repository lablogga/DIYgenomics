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
         *  This function returns a map of disease URLs keyed by their providers.  The URLs are available to the user
         *  by clicking on the provider column name in the variants table header.
         *
         *  The map looks like this:
         *
         *  {
         *      "deCODEme":     "http://demo.decodeme.com/health-watch/details/BCRS",
         *      "Navigenics":   "http://www.navigenics.com/demo/for_scientists/d/breast_cancer/",
         *      "23andMe":      "https://www.23andme.com/health/Breast-Cancer/"
         *  }
         */
        function getMapDiseaseURLs() {
            // Query the disease / condition URLs from the database:
            $strQueryDiseaseURLs = "SELECT 4_entities.Entity, 6_map_entity_condition.URL"
                                        . " FROM 6_map_entity_condition JOIN 4_entities"
                                        . " WHERE Condition_index = " . getCurrentConditionID() . " AND 6_map_entity_condition.Entity_index = 4_entities.Primary"
                                        . " ORDER BY 6_map_entity_condition.Entity_index";
            $resultQueryDiseaseURLs = mysql_query($strQueryDiseaseURLs)
                or die("<p>Unable to query the database for conditions.  Error code: " . mysql_connect_errno() . "</p>");

            // Process the query results into the map / associative array:
            $mapDiseaseURLs = array();
            while ($arrDiseaseURL = mysql_fetch_array($resultQueryDiseaseURLs)) {
                $mapDiseaseURLs[$arrDiseaseURL[0]] = $arrDiseaseURL[1];
            }

            return $mapDiseaseURLs;
        }

        //QUERY THE DATABASE - QUERY 1 (MAIN QUERY)
        $SQLstring = "SELECT 8_map_variant_condition_entity_study.Variant_index,"
                            . " 3_variants.Locus,"              // Column 1 'Locus'
                            . " 3_variants.Gene,"               // Column 2 'Gene'
                            . " 3_variants.Variant,"            // Column 3 'Variant'
                            . " 4_entities.Primary,"
                            . " 1_studies.PMID,"
                            . " 1_studies.PMID_URL,"
                            . " 1_studies.Citation,"
                            . " 3_variants.Locus_URL,"
                            . " 3_variants.Gene_URL,"
                            . " 3_variants.Variant_URL,"
                            . " 3_variants.dbSNP,"              // Column 7 'dbSNP'
                            . " 3_variants.23andme"             // Column 6 '23andme'
                            . " FROM 2_conditions"
                            . " LEFT JOIN 8_map_variant_condition_entity_study ON 2_conditions.Primary = 8_map_variant_condition_entity_study.Condition_index"
                            . " LEFT JOIN 4_entities ON 8_map_variant_condition_entity_study.Entity_index = 4_entities.Primary"
                            . " LEFT JOIN 1_studies ON 8_map_variant_condition_entity_study.Study_index = 1_studies.Primary"
                            . " LEFT JOIN 3_variants ON 8_map_variant_condition_entity_study.Variant_index = 3_variants.Primary"
                            . " WHERE (2_conditions.Primary = " . getCurrentConditionID() . ")";

        $QueryResult = mysql_query($SQLstring)
            or die("<p>Unable to query the database for variants.  Error code: " . mysql_connect_errno() . "</p>");

        //READ QUERY 1 RESULTS INTO ARRAY
        $Rows = array();

        while ($Row = mysql_fetch_array($QueryResult)) {
            $Rows[] = $Row;
        }

        //FIRST LOOP TO COLLECT ALL STUDIES
        function getStudyIndex($studies, $pubmedid) {
            foreach ($studies as $key => $study) {
                if ($study["pubmedid"] == $pubmedid) {
                    return $key;
                }
            }
            return -1;
        }

        $Studies = array();

        foreach ($Rows as $row) {
            // each study is an array of pubmed, url, citation
            if (getStudyIndex($Studies,$row[5]) == -1) {
                $study = array();
                $study["pubmedid"] = $row[5];
                $study["url"] = $row[6];
                $study["citation"] = $row[7];
                $Studies[] = $study;
            }
        }

        //ASSIGN KEY TO STUDY URL
        foreach ($Studies as $key => $study) {
            $ix = $key+1;
            $cit = $study["citiation"];
            $cit_URL = $Studies[$ix-1]["url"];
        }

        function sortByCitation($r1,$r2) {
            return strcmp($r1["citation"],$r2["citation"]);
        }

        usort($Studies, sortByCitation);

        //SORT BY LOCUS
        function sortByLocus($p1,$p2) {
          $cmp1 = strnatcmp($p1[1],$p2[1]);
          if ($cmp1 == '0') {
            return strcmp($p1[3],$p2[3]);
          }
          return $cmp1;
        }
        usort($Rows, sortByLocus);
        
        $mapDiseaseURLs = getMapDiseaseURLs();

        //CREATE RESULTS TABLE FROM MAIN QUERY (QUERY 1)
    ?>
    <h3>Variants reviewed for <?=$CurrentCondition?></h3>
    <table id='myTable' width='80%' cellspacing='1' cellpadding='0' style='border:solid 1px #cccccc; margin-left: 1.4 in; background:#cccccc'>
        <tr ALIGN='center'>
            <th style='background:white;'>Locus</th>
            <th style='background:white;'>Gene</th>
            <th style='background:white;'>Variant</th>
            <th style='background:white;'><a href='<?=$mapDiseaseURLs["deCODEme"]?>'>deCODEme</a></th>
            <th style='background:white;'><a href='<?=$mapDiseaseURLs["Navigenics"]?>'>Navigenics</a></th>
            <th style='background:white;'><a href='<?=$mapDiseaseURLs["23andMe"]?>'>23andMe</a></th>
            <th style='background:white;'><a href='http://www.ncbi.nlm.nih.gov/projects/SNP'>dbSNP (Nrml/Rsk)</a></th>
            <th style='background:white;'>Sample data</th>
        </tr>

        <?php
            //PRINT OUT THE DATA TABLE
            function printRow($row,$studies) {
                ?>
                    <tr>
                        <td align='center' style='background:white;'><a href='<?=$row['locus_url']?>'><?=$row['locus']?></a></td>
                        <td align='center' style='background:white;'><a href='<?=$row['gene_url']?>'><?=$row['gene']?></a></td>
                        <td align='center' style='background:white;'><a href='<?=$row['variant_url']?>'><?=$row['variant']?></a></td>
                <?php
                    $comps = array();
                    $comps[] = "comp1";
                    $comps[] = "comp2";
                    $comps[] = "comp3";
                    foreach ($comps as $comp) {   //deCodeme, Navigenics and 23andme fields
                        ?>
                        <td align='center' style='background:white;'>
                        <?php
                            $compdata = $row[$comp];
                            sort($compdata);
                            $n = count($compdata);
                            $i = 0;
                            foreach ($compdata as $index) {
                                $ix = $index+1;
                                $citurl = $studies[$index]["url"];
                                echo "<a href=\"{$citurl}\">{$ix}</a>";  //print the study number
                                if ($i < $n - 1) {
                                    echo ",";
                                }
                                $i++;
                            }
                        ?>
                        </td>
                        <?php
                    }
                    //PRINT DBSNP and 23andme EXAMPLE VALUES
                ?>
                        <td align='center' style='background:white;'><a href='<?=$row['variant_url']?>'><?=$row['dbsnp']?></a></td>
                        <td align='center' style='background:white;'><?=$row['23andme']?></a></td>
                    </tr>
                <?php
            }


            //READY MAIN QUERY DATA INTO ARRAY
            $oldlocus ="";
            $oldvariant = "";
            $first = 1;

            $Printrow = array();

            foreach ($Rows as $rd) {
                $locus = $rd[1];
                $variant = $rd[3];
                $company = $rd[4];
                $pubmed = $rd[5];

                // oldlocus and oldvariant will hold the previous loc/var for the data we try to print
                // locus and variant is the data from the current row
                // Printrow has our accumulated data collected for oldlocus/oldvariant
                if (($locus != $oldlocus) || ($variant != $oldvariant)) {
                    if ($first != 1) {
                        // print the printrow data
                        printRow($Printrow,$Studies);
                    } else {
                        $first = 0;
                    }
                    // reset printrow/oldlocus/oldvariant
                    $oldlocus = $locus;
                    $oldvariant = $variant;
                    $Printrow = array();   //declare new array
                    $Printrow["locus"] = $rd[1];    //read values into array
                    $Printrow["gene"] = $rd[2];
                    $Printrow["variant"] = $rd[3];
                    $Printrow["comp1"] = array();
                    $Printrow["comp2"] = array();
                    $Printrow["comp3"] = array();
                    $Printrow["locus_url"] = $rd[8];
                    $Printrow["gene_url"] = $rd[9];
                    $Printrow["variant_url"] = $rd[10];
                    $Printrow["dbsnp"] = $rd[11];
                    $Printrow["23andme"] = $rd[12];
                    //echo $rd[3];
                    //echo "<br />";
                }

                $studylist = &$Printrow["comp1"];
                if ($company == 2) {
                    $studylist = &$Printrow["comp2"];
                } else if ($company == 3) {
                    $studylist = &$Printrow["comp3"];
                }
                $studylist[] = getStudyIndex($Studies,$pubmed);

            }
            printRow($Printrow,$Studies);
        ?>
    </table>

    <h4><i>Research references cited by consumer genomic companies:</i></h4>

    <ol>
        <?php
            foreach ($Studies as $key => $study) {
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