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
?>

<html>
    <head>

        <title>PersonalGenomics Application </title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="description" content="DIYgenomics Genome Health Risk Web Application" />
        <meta name="keywords" content="genomics, genome, personal genomes, personal genome, citizen science, science, technology, direct-to-consumer, consumer genomics, genomic testing, genetic testing, research" />

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
            
            .DIYgenomics_health_app .layout .variants_table {
                border:                         1px solid #cccccc;
                width:                          100%;
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

            // This starts up the database connection.  Actual authentication is done in a different file to keep the
            // authentication info out of the source repo.
            require('database_opener.php');
            $DBConnect = openTheDatabase() or die ("<p>Unable to open the appropriate database.  Error code: " . mysql_connect_errno() . "</p>");
        ?>

        <div class='layout'>
            <h3>DIYgenomics Genome Health Risk Web Application</h3>
            <div>
                <p>
                    <b>PersonalGenomics:</b> Side-by-side comparison of consumer genomic services
                    (deCODEme, Navigenics and 23andme) by locus, gene and variant for 20 conditions
                    (diabetes, cancers, heart disease, etc.). If a company reviews the variant, the
                    underlying research reference cited by the company is posted in the table below.
                </p>
                <p>
                    NOTE: This information is provided by non-medical professionals.
                </p>
            </div>

            <!-- SET FONT SIZE TO 10 pt -->
            <div style='font-size: 10pt;'>
                <?php
                    require('gen_data_variants_info.php');
                    printVariantsInfo($CurrentCondition);
                ?>
            </div>
        </div>

         <?php
			//Call PHP functions
			require('../glossary.php');
    		require('../footer_site.php');
    		require('footer_webapp.php');
	     ?>


</body>
</html>

