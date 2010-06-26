<?php
    /**
     * Copyright (C) 2010 DIYgenomics           diygenomics.org
     * Copyright (C) 2010 Melanie Swan          mxswan@gmail
     * Copyright (C) 2010 Marat Nepomnyashy     maratbn@gmail
     * All rights reserved.
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


   /**
     * A function to render conditions
     */

       function renderVariantsInfo($idForCondition, $idTable) {

        function renderConditionsFormComboBox($arrConditions, $idForCondition) {
            ?>
                <form method='get'>
                    <select name='condition' onchange='this.form.submit()'>

                        <?php
                            foreach ($arrConditions as $cond) {
                                $selected = (($cond[0] == $idForCondition) ? "selected" : "");
                        ?>

                        <option value='<?=htmlentities($cond[0])?>' <?=$selected?>><?=htmlentities($cond[1])?></option>

                        <?php
                            }
                        ?>

                    </select>
                </form>
            <?php
        }

        // Include the utility functions involving database queries:
        require ('gen_data_queries.php');

        $arrConditions = queryArrayConditions();                                // This is an array of all the conditions that the user can look at.
        $strCondition = $arrConditions[$idForCondition - 1][1];                 // This is the name of the condition that the user is currently looking at.

        $mapDataCurrentCondition = queryDataForCondition($idForCondition);
    ?>


    /**
     * A function to render drugs
     */

      function renderVariantsInfoForDrugs($idForDrug, $idTable) {

          function renderDrugsFormComboBox($arrDrugs, $idForDrug) {
              ?>
                  <form method='get'>
                      <select name='drug' onchange='this.form.submit()'>

                          <?php
                              foreach ($arrDrugs as $drug) {
                                  $selected = (($drug[0] == $idForDrug) ? "selected" : "");
                          ?>

                          <option value='<?=htmlentities($drug[0])?>' <?=$selected?>><?=htmlentities($drug[1])?></option>

                          <?php
                              }
                          ?>

                      </select>
                  </form>
              <?php
          }

          // Include the utility functions involving database queries:
          require ('gen_data_queries.php');

          $arrDrugs = queryArrayDrugs();                                // This is an array of all the drugs that the user can look at.
          $strDrug = $arrDrugs[$idForDrug - 1][1];                      // This is the name of the drug that the user is currently looking at.

          $mapDataCurrentDrug = queryDataForDrug($idForDrug);
      ?>





    <!-- The following generates the variant info via Dojo.  The dojo rendering will hide the PHP rendering below after it completely initializes. -->
    <!--     <div                                                                             -->
    <!--         dojoType='DIYgenomics.gen_data.VariantsInfoWidget'                           -->
    <!--         condition='<?=htmlentities($strCondition, ENT_QUOTES)?>'                     -->
    <!--         idReplace='<?=$idTable?>'></div>                                             -->

    <!-- The following generates the variant info via PHP.  The content is still rendered server-side to be indexable by search engines. -->
    <div id='<?=$idTable?>'>
        <div class='condlist'>
            <!-- DROPDOWN MENU -->
            <?php
                renderConditionsFormComboBox($arrConditions, $idForCondition);
            ?>
        </div>
        <h3>Variants reviewed for <?=htmlentities($strCondition)?></h3>
        <?php
            //CREATE RESULTS TABLE FROM MAIN QUERY (QUERY 1)
        ?>
        <table class='variants_table' cellpadding='0' cellspacing='0'>
            <tr>
                <th>Locus</th>
                <th>Gene</th>
                <th>Variant</th>
                <?php
                    $arrEntityColumns = array('deCODEme', 'Navigenics', '23andMe');
                    foreach ($arrEntityColumns as $strEntity) {
                        ?>
                            <th><a href='<?=htmlentities($mapDataCurrentCondition['entities_keyed'][$strEntity]['entity_cond_url'])?>'><?=htmlentities($strEntity)?></a></th>
                        <?php
                    }
                ?>
                <th><a href='http://www.ncbi.nlm.nih.gov/projects/SNP'>dbSNP (Nrml/Rsk)</a></th>
                <th>Sample data</th>
            </tr>
            <?php
                for ($i = 0; $i < count($mapDataCurrentCondition['variants']); $i++) {
                    $strVariant = $mapDataCurrentCondition['variants'][$i];
                    $strRowClass = $i % 2 == 0 ? 'vtr_even' : 'vtr_odd';
                    ?>
                        <tr class='<?=$strRowClass?>'>
                            <td>
                                <a href='<?=htmlentities($mapDataCurrentCondition['variants_keyed'][$strVariant]['locus_url'])?>'>
                                    <?=htmlentities($mapDataCurrentCondition['variants_keyed'][$strVariant]['locus'])?>
                                </a>
                            </td>
                            <td>
                                <a href='<?=htmlentities($mapDataCurrentCondition['variants_keyed'][$strVariant]['gene_url'])?>'>
                                    <?=htmlentities($mapDataCurrentCondition['variants_keyed'][$strVariant]['gene'])?>
                                </a>
                            </td>
                            <td>
                                <a href='<?=htmlentities($mapDataCurrentCondition['variants_keyed'][$strVariant]['variant_url'])?>'>
                                    <?=htmlentities($mapDataCurrentCondition['variants_keyed'][$strVariant]['variant'])?>
                                </a>
                            </td>

                            <?php
                                foreach ($arrEntityColumns as $strEntity) {
                                    ?>
                                    <td>
                                        <?php
                                            $totalStudies = 0;
                                            foreach ($mapDataCurrentCondition['variants_keyed'][$strVariant]['studies'] as $strStudy) {
                                                if ($mapDataCurrentCondition['variants_keyed'][$strVariant]['studies_keyed'][$strStudy]['entities'][$strEntity]) {
                                                    if ($totalStudies > 0) echo ",";
                                                    ?><a href='<?=htmlentities($mapDataCurrentCondition['studies_keyed'][$strStudy]['url'])?>'><?=htmlentities($mapDataCurrentCondition['studies_keyed'][$strStudy]['number'])?></a><?php
                                                    $totalStudies++;
                                                }
                                            }
                                        ?>
                                    </td>
                                    <?php
                                }
                            ?>

                            <td>
                                <a href='<?=htmlentities($mapDataCurrentCondition['variants_keyed'][$strVariant]['variant_url'])?>'>
                                    <?=htmlentities($mapDataCurrentCondition['variants_keyed'][$strVariant]['dbSNP_normal'])?>/<?=htmlentities($mapDataCurrentCondition['variants_keyed'][$strVariant]['dbSNP_risk'])?>
                                </a>
                            </td>
                            <td>
                                <?php
                                    $strColor1 = $mapDataCurrentCondition['variants_keyed'][$strVariant]['dbSNP_sample_1']
                                                    ==  $mapDataCurrentCondition['variants_keyed'][$strVariant]['dbSNP_normal']
                                                    ?   "green"
                                                    :   "red";

                                    $strColor2 = $mapDataCurrentCondition['variants_keyed'][$strVariant]['dbSNP_sample_2']
                                                    ==  $mapDataCurrentCondition['variants_keyed'][$strVariant]['dbSNP_normal']
                                                    ?   "green"
                                                    :   "red";

                                    echo "<span style='color:$strColor1;'>" . htmlentities($mapDataCurrentCondition['variants_keyed'][$strVariant]['dbSNP_sample_1']) . "</span>";
                                    echo "<span style='color:$strColor2;'>" . htmlentities($mapDataCurrentCondition['variants_keyed'][$strVariant]['dbSNP_sample_2']) . "</span>";
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
                            <a href='<?=htmlentities($citurl)?>'><?=htmlentities($cit)?></a>
                        </li>
                    <?php
                }
            ?>
        </ol>
    </div>
    <?php
    }
?>

<!-- The following includes a JavaScript file for the Dojo-based variant info generation widget. -->
<script type='text/javascript'>
    dojo.require('DIYgenomics.gen_data.VariantsInfoWidget');
</script>
