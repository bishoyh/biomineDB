
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

/**
 * @param $q
 */
function get_names($q){
$tablename = 'biomingenes';
$sql = mysql_connect("localhost", $db_admin, $db_password)
or die(mysql_error());

mysql_select_db("$db", $sql);
    if (!empty($query_name)) {
        $query_name = "SELECT Distinct * FROM $tablename where Protein_names like '%$q%'  OR Gene_names like '%$q%' ";
    }

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
