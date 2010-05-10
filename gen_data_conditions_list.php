<?php
/**
 * Copyright (C) 2010 DIYgenomics           diygenomics.org
 * Copyright (C) 2010 Melanie Swan          mxswan@gmail
 * Copyright (C) 2010 Marat Nepomnyashy     maratbn@gmail
 *
 * Module:      gen_data_conditions_list.php
 *
 * Description: Renders the conditions list combo box, called from 'gen_data.php'.
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
 
    function renderConditionsList() {
        ?>
            <form action='gen_data.php' method='post'>
                <select name='condition' onchange='this.form.submit()'>

                    <?php
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
                        $SQLstring3 = "SELECT 2_conditions.Primary, 2_conditions.Condition FROM 2_conditions;";

                        $QueryResult3 = mysql_query($SQLstring3)
                                            or die("<p>Unable to query a database table for conditions.  Error code: " . mysql_connect_errno() . "</p>");


                        //READ QUERY 3 RESULTS INTO ARRAY
                        // conditions holds rows {condition_ix, condition_name}
                        $Conditions = array();
                        while ($CondRow = mysql_fetch_array($QueryResult3)) {
                            $Conditions[] = $CondRow;
                        }

                        $CurrentCondition=$Conditions[$COND-1][1];

                        foreach ($Conditions as $cond) {
                            $selected = (($cond[0] == $COND) ? "selected" : "");
                    ?>

                    <option value='<?=$cond[0]?>' <?=$selected?>><?=$cond[1]?></option>

                    <?php
                        }
                    ?>

                </select>
            </form>
        <?php
    }
?>
