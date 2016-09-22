
<?php
include_once('wasal/connect.php');

// Configure connection settings
$q=$_GET["q"];
$type=$_GET["t"];
$tablename = 'biomingenes';
If ($type=='name'){
get_names($q);

}else{
get_taxa($q);

}
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

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="./assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="./assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="./assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="./assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="./assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"></a> <a class="brand" href="index.html">BioMine-DB-&gt;</a>

                    <div class="nav-collapse">
                        <ul class="nav">
                            <li class="active"><a href="index.html">Home</a></li>

                            <li><a href="download.html">Download</a></li>

                            <li><a href ="http://montastraea.psu.edu:4567/">BLAST</a></li>

                            <li><a href="about.html">About</a></li>
                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

    <div class="container">
<div class="page-header">

<?php
// Title

//echo "Results:";

// Connect to DB

$sql = mysql_connect("localhost", $db_admin, $db_password)
or die(mysql_error());

mysql_select_db("$db", $sql);

// Fetch the data


// echo "$query\n";
// Return the results, loop through them and echo
function get_taxa($q){
$tablename = 'biomingenes';
$sql = mysql_connect("localhost", $db_admin, $db_password)
or die(mysql_error());

mysql_select_db("$db", $sql);
$query = "SELECT Distinct Organism FROM $tablename where Organism like '%$q%' ";
$result = mysql_query($query);

while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
//get gene counts
$query_counts = "SELECT COUNT(entry) as counts FROM $tablename where Organism = '{$row['Organism']}' ";
$result_counts = mysql_query($query_counts);
$row_counts = mysql_fetch_array($result_counts, MYSQL_ASSOC);
echo "<pre><a href=\"orgret.php?org={$row['Organism']}\">{$row['Organism']}<a><span class=\"badge badge-important\">{$row_counts['counts']}</span></pre>";
}
}
function get_names($q){
$db = 'biomin';
$tablename = 'biomingenes';
$sql = mysql_connect("localhost", $db_admin, $db_password)
or die(mysql_error());

mysql_select_db("$db", $sql);
$query_name = "SELECT Distinct * FROM $tablename where Protein_names like '%$q%'  OR Gene_names like '%$q%' ";

$result = mysql_query($query_name);


while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
//get gene counts
//$query_counts = "SELECT COUNT(entry) as counts FROM $tablename where Organism = '{$row['Organism']}' ";
//$result_counts = mysql_query($query_counts);
//$row_counts = mysql_fetch_array($result_counts, MYSQL_ASSOC);
echo "<pre><a href=\"seqret.php?seq={$row['Entry']}\">{$row['Protein_names']}|{$row['Organism']}<a></pre>";
}

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
