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
 
    function renderConditionsList($Conditions) {
        ?>
            <form method='get'>
                <select name='condition' onchange='this.form.submit()'>

                    <?php
                        $conditionCurrent = getCurrentConditionID();
                        foreach ($Conditions as $cond) {
                            $selected = (($cond[0] == $conditionCurrent) ? "selected" : "");
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
