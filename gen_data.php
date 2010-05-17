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
        <table border='0' cellspacing='0' cellpadding='0' style='margin-left: 1.4in; margin-right: 1in;'>
            <tr>
                <td style='text-align: left;'>
                    <b>PersonalGenomics:</b> Side-by-side comparison of consumer genomic services
                    (deCODEme, Navigenics and 23andme) by locus, gene and variant for 20 conditions
                    (diabetes, cancers, heart disease, etc.). If a company reviews the variant, the
                    underlying research reference cited by the company is posted in the table below.
                </td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>


                <!-- DROPDOWN MENU -->
                <td valign = 'top'>
                    <?php
                        require('gen_data_conditions_list.php');
                        renderConditionsList($arrConditions);
                    ?>
                </td>
            </tr>
        </table>

        <!-- SET FONT SIZE TO 10 pt -->
        <div style='font-size: 10pt;'>

        <?php
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


            //QUERY THE DATABASE - QUERY 2 (CONDITION URLs)
            $SQLstring2 = "SELECT 2_conditions.Primary, 6_map_entity_condition.Entity_index, 6_map_entity_condition.URL"
                                . " FROM 2_conditions"
                                . " LEFT JOIN gen.6_map_entity_condition ON 2_conditions.Primary = 6_map_entity_condition.Condition_index"
                                . " WHERE (2_conditions.Primary = " . getCurrentConditionID() . ")";

            $QueryResult2 = mysql_query($SQLstring2)
                or die("<p>Unable to query the database for conditions.  Error code: " . mysql_connect_errno() . "</p>");


            //READ QUERY 2 RESULTS INTO ARRAY
            $Rows2 = array();
            while ($Row2 = mysql_fetch_array($QueryResult2)) {
                $Rows2[] = $Row2;
            }



echo("<h3>Variants reviewed for " . $CurrentCondition . "<br /></h3>");

//CREATE RESULTS TABLE FROM MAIN QUERY (QUERY 1)
echo "<table id=\"myTable\" width='80%' cellspacing='1' cellpadding='0'
style=\"border:solid 1px #cccccc; margin-left: 1.4 in; background:#cccccc\">";
echo "<tr ALIGN=\"center\">
<th style='background:white;'>Locus</th>
<th style='background:white;'>Gene</th>
<th style='background:white;'>Variant</th>
<th style='background:white;'><a href=\"{$Rows2[0][2]}\">deCODEme</a></th>
<th style='background:white;'><a href=\"{$Rows2[1][2]}\">Navigenics</a></th>
<th style='background:white;'><a href=\"{$Rows2[2][2]}\">23andme</a></th>
<th style='background:white;'><a href=\"http://www.ncbi.nlm.nih.gov/projects/SNP\">dbSNP (Nrml/Rsk)</a></th>
<th style='background:white;'>Sample data</th></tr>";

//SORT BY LOCUS
function sortByLocus($p1,$p2) {
  $cmp1 = strnatcmp($p1[1],$p2[1]);
  if ($cmp1 == '0') {
    return strcmp($p1[3],$p2[3]);
  }
  return $cmp1;
}
usort($Rows,'sortByLocus');


$stack = array();
//PRINT OUT THE DATA TABLE
function printRow($row,$studies) {

  echo "<tr>";
  echo "<td align='center' style='background:white;'> <a href=\"{$row['locus_url']}\"> {$row['locus']} </a> </td>";
  echo "<td align='center' style='background:white;'> <a href=\"{$row['gene_url']}\"> {$row['gene']} </a> </td>";
  echo "<td align='center' style='background:white;'> <a href=\"{$row['variant_url']}\"> {$row['variant']} </a> </td>";
 $comps = array();
  $comps[] = "comp1";
  $comps[] = "comp2";
  $comps[] = "comp3";
  foreach ($comps as $comp) {   //deCodeme, Navigenics and 23andme fields
    echo "<td align='center' style=\"background:white;\">";
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
    echo "</td>";
  }
//PRINT DBSNP and 23andme EXAMPLE VALUES
  echo "<td align='center' style='background:white;'> <a href=\"{$row['variant_url']}\"> {$row['dbsnp']} </a> </td>";
  echo "<td align='center' style='background:white;'>  {$row['23andme']} </a> </td>";
  echo "</tr>";
}


//FIRST LOOP TO COLLECT ALL STUDIES
$Studies = array();

function sortByCitation($r1,$r2) {
  return strcmp($r1["citation"],$r2["citation"]);
}

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


usort($Studies,'sortByCitation');

function getStudyIndex($studies,$pubmedid) {
  foreach ($studies as $key => $study) {
    if ($study["pubmedid"] == $pubmedid) {
      return $key;
    }
  }
  return -1;
}


//READY MAIN QUERY DATA INTO ARRAY
$oldlocus ="";
$oldvariant = "";
$first = 1;

$Printrow = array();

foreach ($Rows as $rd) {
  $locus = $rd[1];
  $variant = $rd[3];

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

  $company = $rd[4];
  $pubmed = $rd[5];
  $studylist = &$Printrow["comp1"];
  if ($company == 2) {
    $studylist = &$Printrow["comp2"];
  } else if ($company == 3) {
    $studylist = &$Printrow["comp3"];
  }
  $studylist[] = getStudyIndex($Studies,$pubmed);

}
printRow($Printrow,$Studies);
    echo "<tr><td style='background:white;' ALIGN='left' colspan='8'>&nbsp;</td></tr>\n";
    echo "<tr><td style='background:white;' ALIGN='left' colspan='8'><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Research references cited by consumer genomic companies:</i></td></tr>\n";

foreach ($Studies as $key => $study) {
  $ix = $key+1;
  $cit = $study["citation"];
  $citurl = $study["url"];
    echo "<tr><td style='background:white;' VALIGN='center'; ALIGN='right'>{$ix}.&nbsp;</td>
    <td style='background:white;' colspan=\"7\"><a href=\"{$citurl}\">{$cit}</a></td></tr>\n";
}


echo "</table>";

//CLOSE THE CONNECTION TO THE DATABASE
mysql_close($DBConnect);

echo("</div>");

//END PHP
?>

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

