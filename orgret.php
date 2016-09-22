<?php
/**
 * Copyright (c) Bishoy Hanna 2016. 
 *   This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

include_once('wasal/connect.php');
// Configure connection settings
$orgname = $_GET["org"];
$tablename = 'biomingenes';

// Connect to DB

$sql = mysql_connect("localhost", $db_admin, $db_password)
or die(mysql_error());
mysql_select_db("$db", $sql);
// Fetch the data
$query = "SELECT * FROM $tablename where Organism = '$orgname' ";
$result = mysql_query($query);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>BioMine-DB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="./css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
    </style>
    <link href="./css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->


</head>

<body>

<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"></a> <a class="brand"
                                                                                                 href="index.html">BioMine-DB-&gt;</a>

            <div class="nav-collapse">
                <ul class="nav">
                    <li class="active"><a href="index.html">Home</a></li>

                    <li><a href="download.html">Download</a></li>

                    <li><a href="http://montastraea.psu.edu:4567/">BLAST</a></li>

                    <li><a href="about.html">About</a></li>f
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container">
    <div class="page-header">
        <h1><?php echo "<i>$orgname</i>"; ?></h1>
    </div>
    <?php while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
//get gene counts

        echo "<pre><a href=\"seqret.php?seq={$row['Entry']}\">>{$row['Entry']}</a>|{$row['Protein_names']}<br>{$row['sequence']}</pre>";

    }
    ?>
    <hr>

    <footer>
        <p>&copy; BioMine-DB Team 2016</p>
    </footer>

</div> <!-- /container -->

<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="./js/jquery.js"></script>
<script src="./js/bootstrap-transition.js"></script>
<script src="./js/bootstrap-alert.js"></script>
<script src="./js/bootstrap-modal.js"></script>
<script src="./js/bootstrap-dropdown.js"></script>
<script src="./js/bootstrap-scrollspy.js"></script>
<script src="./js/bootstrap-tab.js"></script>
<script src="./js/bootstrap-tooltip.js"></script>
<script src="./js/bootstrap-popover.js"></script>
<script src="./js/bootstrap-button.js"></script>
<script src="./js/bootstrap-collapse.js"></script>
<script src="./js/bootstrap-carousel.js"></script>
<script src="./js/bootstrap-typeahead.js"></script>

</body>
</html>
