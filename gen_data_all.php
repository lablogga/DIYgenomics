<?php
    /**
     * Copyright (C) 2010 DIYgenomics           diygenomics.org
     * Copyright (C) 2010 Melanie Swan          mxswan@gmail
     * Copyright (C) 2010 Marat Nepomnyashy     maratbn@gmail
     *
     * Module:          gen_data_all.php
     *
     * Description:     This module is intended to generate a JSON expression
     *                  containing all the information on all the conditions
     *                  and their associated variants and related studies.
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
    $DBConnect = openTheDatabase() or die ("<p>Unable to open the appropriate database.  Error code: " . mysql_connect_errno() . "</p>");

    // This queries the database for the data containing all the information on all the conditions and
    // their associated variants and related studies, and returns that data as a JSON textual expression.
    require('gen_data_queries.php');
    echo json_encode(queryDataForConditionsAll());
?>