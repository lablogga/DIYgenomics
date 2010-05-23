<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<?php
/**
 * Copyright (C) 2010 DIYgenomics           diygenomics.org
 * Copyright (C) 2010 Melanie Swan          mxswan@gmail
 * Copyright (C) 2010 Marat Nepomnyashy     maratbn@gmail
 *
 * Module: gen_data.php
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
    function getArrayConditions() {
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
                            or die("<p>Unable to query a database table for conditions.  Error code: " . mysql_connect_errno() . "</p>");


        //READ QUERY 3 RESULTS INTO ARRAY
        // conditions holds rows {condition_ix, condition_name}
        $Conditions = array();
        while ($CondRow = mysql_fetch_array($QueryConditions)) {
            $Conditions[] = $CondRow;
        }

        return $Conditions;
    }

    /**
     *  Returns the condition ID that the user is currently looking at.
     */
    function getCurrentConditionID() {
        $COND = $_GET["condition"];
        if ($COND == "") {
          $COND = 1;
        }
        return $COND;
    }
?>

<html>
    <head>

        <title>PersonalGenomics Application </title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="description" content="This is the home page of DIYgenomics" />
        <meta name="keywords" content="genomics, genome, personal genomes, personal genome, citizen science, science, technology, direct-to-consumer, consumer genomics, genomic testing, genetic testing, research" />

        <script type="text/javascript" src="http://www.diygenomics.org/ms.js"> </script>

        <!-- Favicon Information -->
        <link rel="shortcut icon" type="image/x-icon" href="http://www.diygenomics.org/images/favicon.ico">

        <!-- JQuery Tablesorter -->
        <script type="text/javascript" src="jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="jquery.tablesorter.min.js"></script>
    </head>
    <body id="top" style="background-color: #FFFFFF;">

        <script type="text/javascript">
        <!-- hide from old browsers
            //  Image and Title Information
            displayHeader(); //call the displayHeader() function from ms.js

            //  Menu Navigation Information
            displayMenu(); //call the displayMenu() function from ms.js
        // stop hiding -->
        </script>


        <!--
        ===================================================================
        Main body text
        ===================================================================
        -->

        <h3>Web app</h3>


        <?php
            // This starts up the database connection.  Actual authentication is done in a different file to keep the
            // authentication info out of the source repo.
            require('database_opener.php');
            $DBConnect = openTheDatabase() or die ("<p>Unable to open the appropriate database.  Error code: " . mysql_connect_errno() . "</p>");
            
            $arrConditions = getArrayConditions();                                  // This is an array of all the conditions that the user can look at.
            $CurrentCondition = $arrConditions[getCurrentConditionID() - 1][1];     // This is the name of the condition that the user is currently looking at.
        ?>
        <div>
            <div style='float:left;width:70%;'>
                <b>PersonalGenomics:</b> Side-by-side comparison of consumer genomic services
                (deCODEme, Navigenics and 23andme) by locus, gene and variant for 20 conditions
                (diabetes, cancers, heart disease, etc.). If a company reviews the variant, the
                underlying research reference cited by the company is posted in the table below.
            </div>
            <div style='float:left;margin-left:20px;'>
                <!-- DROPDOWN MENU -->
                <?php
                    require('gen_data_conditions_list.php');
                    renderConditionsList($arrConditions);
                ?>
            </div>
            <div style='clear:both;'></div>
        </div>

        <!-- SET FONT SIZE TO 10 pt -->
        <div style='font-size: 10pt;'>

        <?php
            require('gen_data_variants_info.php');
            printVariantsInfo($CurrentCondition);
        ?>
    </div>

	<script type="text/javascript">
	<!-- hide from old browsers

	$(document).ready(function()
	    {
	        $("#myTable").tablesorter();
	    }
	);
	// stop hiding -->
	</script>


	<script type="text/javascript">
	<!-- hide from old browsers
    $(document).ready(function()
	    {
	        $("#myTable").tablesorter( {sortList: [[0,0], [1,0]]} );
	    }
	);
	// stop hiding -->
	</script>


	<script type="text/javascript">
	<!-- hide from old browsers
		displayFooter(); //call the displayFooter() function from ms.js
	// stop hiding -->
	</script>



<!--
===================================================================
Glossary
===================================================================
-->
<br />
<div style="margin-left: .5in; margin-right: 1in; clear: left; max-width:100em; font-size: 10pt;">
<i>Glossary:</i><br />
<a href="http://en.wikipedia.org/wiki/Locus_%28genetics%29">Locus</a> (plural loci):
the specific location of a gene or DNA sequence on a chromosome, (e.g.; 8p21.1 is the p21.1 region on Chromosome 8).<br />

<a href="http://en.wikipedia.org/wiki/Gene">Gene</a>: a series of variants at a locus that code for proteins or RNA chains.<br />

Variant: the specific location with two <a href="http://en.wikipedia.org/wiki/Allele">alleles</a> (e.g.; 'CG')
or genotype values. Companies may check different variants in the same gene and locus.<br />
</div>


<!--
===================================================================
Footer
===================================================================
-->
	<script type="text/javascript">
	<!-- hide from old browsers
		displayFooter(); //call the displayFooter() function from ms.js
	// stop hiding -->
	</script>

</body>
</html>

