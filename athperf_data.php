<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<?php
    /**
     * Copyright (C) 2010 DIYgenomics           diygenomics.org
     * Copyright (C) 2010 Melanie Swan          mxswan@gmail
     * Copyright (C) 2010 Marat Nepomnyashy     maratbn@gmail
     * All rights reserved.
     *
     * Module: athperf_data.php
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


    // This starts up the database connection.  Actual authentication is done in a different file to keep the
    // authentication info out of the source repo.
    require('database_opener.php');
    $DBConnect = openTheDatabase() or die ("<p>Unable to open the appropriate database.  Error code: " . mysql_errno() . "</p>");

    /**
     *  Returns the Athletic Performance category ID that the user is currently looking at.
     */
    function getCurrentAPcatID() {
        $APCAT = $_GET["apcat"];
        if ($APCAT == "") {
          $APCAT = 1;
        }
        return $APCAT;
    }

    $idCurrentAPcat = getCurrentAPcatID();
?>


<html>
    <head>

        <title>DIYgenomics Application </title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="description" content="DIYgenomics Athletic Performance Web Application" />
        <meta name="keywords" content="genomics, genome, personal genomes, athletic performance, personal genome, citizen science, science, technology, direct-to-consumer, consumer genomics, genomic testing, genetic testing, research" />

        <!--  The following includes the h5ile HTML 5 File API helper library
              that is used to load the user genome files. -->
        <script type='text/javascript' src='/h5ile-release-0.1-min.js'>
        </script>

        <!--  The following includes the Dojo release 1.5.0 script from '/dojo-release-1.5.0/dojo/dojo.js'
              and will access non-Dojo-specific modules from '/dojo/DIYgenomics/'. -->
        <script
            type='text/javascript'
            src='/dojo-release-1.5.0/dojo/dojo.js'
            djConfig="parseOnLoad: true, modulePaths:{'DIYgenomics':'/dojo/DIYgenomics/'}">
        </script>

         <!-- Favicon Information -->
        <link rel="shortcut icon" type="image/x-icon" href="http://www.diygenomics.org/images/favicon.ico">

        <style type='text/css'>
            .DIYgenomics_health_app {
                background-color:               #ffffff;
            }

            .DIYgenomics_health_app .layout {
                margin:                         25px auto 25px;
                width:                          85%;
            }

            .DIYgenomics_health_app .layout .selection_combobox {
                float:                          right;
                margin-left:                    20px;
            }

            .DIYgenomics_health_app .layout .variants_table {
                border:                         1px solid #cccccc;
                text-align:                     center;
                width:                          100%;
            }

            .DIYgenomics_health_app .layout .variants_table tr th {
                background-color:               #ffff77;
            }

            .DIYgenomics_health_app .layout .variants_table tr.vtr_odd {
                background-color:               #dddddd;
            }
        </style>
    </head>
    <body class='DIYgenomics_health_app'>


        <!--
        ===================================================================
        Main body text
        ===================================================================
        -->


        <?php

         //Call PHP function to display page header and menus
           require('../header.php');
        ?>

        <div class='layout'>
            <h3>DIYgenomics Athletic Performance Web Application</h3>
            <div>
                <p>
                   A list of the genes and variants that research studies link to thirteen different categories
                   of athletic performance including strength, endurance, muscle development, lung capacity, and recovery.

                   <!-- ============== PDF Link =============== -->
                   <a href="http://www.DIYgenomics.org/files/DIYgenomics_Athletic_Performance_Report.pdf">
                   <img src="http://www.DIYgenomics.org/images/icon-pdf.jpg" width="17" height="17" alt="image" title="image"
                   style="float:center; margin-left: 0px; margin-top: 0px; border-width: 0px;"/>
                    Personalized report</a> (based on Sample data).
                    'Rank' is a composite score assigned to the variant by DIYgenomics per 
                    journal ranking, number of cases and controls, p-value, and odds ratio
                    (<a href="http://www.diygenomics.org/files/rank.xls">rank assessment methodology</a>).
                </p>
                <p>
                    NOTE: The information on this page is intended for research and educational
                    purposes only, and is not for diagnostic use.
               </p>
            </div>

            <!-- SET FONT SIZE TO 10 pt -->
            <div style='font-size: 10pt;'>
                <?php
                    require('gen_data_variants_info.php');
//                    Test();
                    renderVariantsInfoForAPcats($idCurrentAPcat, 'diyghavi');
                 ?>
            </div>
        </div>

         <?php
            //Call PHP functions
            require('../glossary.php');
            require('footer_webapp.php');
            require('../footer_site.php');
         ?>


</body>
</html>

