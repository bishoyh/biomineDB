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

// Configure connection settings
include_once('wasal/connect.php');
$seqname = $_GET["seq"]; // get seq id
$tablename = 'biomingenes';

$sql = mysql_connect("localhost", $db_admin, $db_password)
or die(mysql_error());

mysql_select_db("$db", $sql);

// Fetch the data

$query = "SELECT * FROM $tablename where entry = '$seqname' ";
$blast_query = "select *  from uniprot_blastp  where SUBSTR(query_id,  4 , 6) = '$seqname' order by e_value ASC limit 10";
$my_result = mysql_query($query);
$blast_query_result = mysql_query($blast_query);
?>
<!DOCTYPE html>

<html lang="en" ng-app="pubMedApp">

<head>
    <meta http-equiv="content-type" content="tecxt/html; charset=utf-8">

    <title>BioMine-DB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content=""><!-- Le styles -->
    <link href="./css/bootstrap.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
    </style>
    <link href="./css/bootstrap-responsive.css" rel="stylesheet" type="text/css">
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>

    <![endif]-->
    <!-- Le fav and touch icons -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.11.3.js"></script>
    <script src="./js/pubmed.js"></script>
    <script src="./js/app.js"></script>
    <script src="./js/ng-onload.js"></script>c



</head>

<body ng-controller="ViewController as view">
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

                    <li><a href="about.html">About</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container">
    <div class="page-header">
        <h1><?php echo "<i>$seqname</i>"; // sequence name ?></h1>
    </div><?php global $row ; while ( $row = mysql_fetch_array($my_result, MYSQL_ASSOC)) {

        echo "<pre>>{$row['Entry']}|{$row['Protein_names']}<br>{$row['sequence']}</pre>"; //Print protein in fasta format - ID Name then sequence
        $pH = 7.0;// set PH to 7 for IEP calculation
        /** @var TYPE_NAME $data_source */
        $data_source = "EMBOSS"; // Use Emboss numbers for charge calculation
        /** @var TYPE_NAME $seq */
        $seq = remove_non_coding_prot($row['sequence']);    //remove non standard amino acids


        // we will save the original sequence, just in case subsequence is used
        $original_seq = chunk_split($seq, 70);
        // if subsequence is requested

        // length of sequence
        $seqlen = strlen($seq);

        // compute requested parameter
        // calculate aminoacid_content conposition
        global $seqname;
        $seqid = $seqname;
        $filename = "./prot_stat/$seqid.png";

        if (file_exists($filename)) {
            echo " <img src=\"./prot_stat/$seqid.png\"/></img>";

            //         $result.="<b><img src=\"./prot_stat/$seqid.png\"/></img>";
        } else {
            //  echo "The file $filename does not exist";

            include("./class/pData.class.php");
            include("./class/pDraw.class.php");
            include("./class/pImage.class.php");
            $aminoacid_content = aminoacid_content($seq);
            global $points;
            $points = produce_aminoacid_content($aminoacid_content);

            make_photo($points);
        }
        $aminoacid_content = aminoacid_content($seq);
        // prepare aminoacid_content composition to be printed out
        // $result.="<b>Aminoacid composition of protein:</b><br>".print_aminoacid_content($aminoacid_content);
        // calculate molecular weight of protein
        $molweight = protein_molecular_weight($seq, $aminoacid_content);
        $result .= "<b><br>Molecular weight:</b><br>$molweight Daltons";
        $abscoef = molar_absorption_coefficient_of_prot($seq, $aminoacid_content, $molweight);
        $result .= "<p><b>Molar Absorption Coefficient at 280 nm:</b><br>" . round($abscoef, 2);
        // get pk values for charged aminoacids
        $pK = pK_values($data_source);
        // calculate isoelectric point of protein
        $charge = protein_isoelectric_point($pK, $aminoacid_content);
        $result .= "<p><b>Isoelectric point of sequence ($data_source):</b><br>" . round($charge, 2);
        // get pk values for charged aminoacids
        $pK = pK_values($data_source);
        // calculate charge of protein at requested pH
        $charge = protein_charge($pK, $aminoacid_content, $pH);
        $result .= "<p><b>Charge of sequence at pH = $pH ($data_source):</b><br>" . round($charge, 2);

        // 50 characters per line before output
        $seq = chunk_split($seq, 70);

        // colored sequence based in polar/non-plar/charged aminoacids
        // get the colored sequence (html code)
        $colored_seq = protein_aminoacid_nature1($seq);
        // add to result
        $result .= "<p><b><font color=Magenta>Polar</font>, <font color=orange>Nonpolar</font></b> or <b><font color=red>Charged</font></b> aminoacids:<br>" . $colored_seq;

        // colored sequence based in polar/non-plar/charged aminoacids
        // get the colored sequence (html code)
        $colored_seq = protein_aminoacid_nature2($seq);
        // add to result
        $result .= "<p><b><font color=magenta>Polar</font>, <font color=orange>small non-polar</font>, <font color=green>hydrophobic</font>, <font color=red>negatively</font></b> or <b><font color=blue>positively</font> charged</b> aminoacids:<br>" . $colored_seq;
        print "<tr><td bgcolor=FFFFFF><pre>$result</pre></td></tr>";
        echo "<h2> Homologs in other species</h2>";
        echo "<pre>";


        while ($blastrow = mysql_fetch_array($blast_query_result, MYSQL_ASSOC)) {


            $geneid = explode("|", $blastrow['subject_id']);
            $completeurl = "http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=protein&id=$geneid[1]&rettype=fasta&retmode=xml";
            //echo "<br>$completeurl<br>";
            $xml = simplexml_load_file($completeurl);
            $species = $xml->TSeq->TSeq_orgname;
            $sequence = $xml->TSeq->TSeq_sequence;

            echo "<br><a href=\"http://www.ncbi.nlm.nih.gov/entrez/viewer.fcgi?db=protein&id=$geneid[1]\">";
            echo $blastrow['subject_id'] . "</a>$species<br>";


        }
        echo "</pre>";

        echo "<h2> Suggested Publications</h2>";
        global $pubgetname ;
        $pubgetname = $row['Protein_names'];
      //  echo $pubgetname;


    }


    ?>

    <style>
        .article {
            border-bottom-style: solid;
            border-bottom-width: 1px;
            margin-top: 15px;
        }
    </style>

    <body ng-controller="ViewController as view" >
<?php
    $proteinName = $row['Protein_names'];
//echo "<br>$pubgetname<br>";

  echo  "<div class=\"container\" ng-init=\"search('$pubgetname', '$pubgetname', searchTerm3)\">";
?>
<!---->
<!--        <div id="results" ng-show="resultsOn">-->
<!---->
<!--            <br>-->
<!--            <a href="" ng-click="showQuery=true">-->
<!--					<span ng-hide="showQuery">-->
<!--						<h4>Show Query Translation</h4>-->
<!--					</span>-->
<!--            </a>-->
<!--            <a href="" ng-click="showQuery=false">-->
<!--					<span ng-show="showQuery">-->
<!--						<h4>Hide Query Translation</h4>-->
<!--					</span>-->
<!--            </a>-->
<!---->
<!--            <div ng-show="showQuery">-->
<!--                <p>{{metaSearch.queryTranslation}}</p>-->
<!--            </div>-->

            <h4>Total Number of Results: {{metaSearch.totalResults}}</h4>

            <br><br><br>

            <div ng-repeat="article in articles">
                <div class="article">
                    <p><a href="{{article.link}}">{{article.title}}</a></p>
                    <p>{{article.source}}.
                        {{article.pubdate}};{{article.volume}}({{article.issue}}):{{article.pages}}.</p>
                </div>
            </div>

        </div>

    </div>
</body>

    <?php


    function remove_non_coding_prot($seq)
    {
        // change the sequence to upper case
        $seq = strtoupper($seq);
        // remove non-coding characters([^ARNDCEQGHILKMFPSTWYVX\*])
        $seq = preg_replace("([^ARNDCEQGHILKMFPSTWYVX\*])", "", $seq);
        return $seq;
    }

    function protein_isoelectric_point($pK, $aminoacid_content)
    {
        // At isoelectric point, charge of protein will be 0
        // To calculate pH where charge is 0 a loop is required
        // The loop will start computing charge of protein at pH=7, and if charge is not 0, new charge value will be computed
        //    by using a different pH. Procedure will be repeated until charge is 0 (at isoelectric point)
        $pH = 7;          // pH value at start
        $delta = 4;       // this parameter will be used to modify pH when charge!=0. The value of $delta will change during the loop
        while (1) {
            // compute charge of protein at corresponding pH (uses a function)
            $charge = protein_charge($pK, $aminoacid_content, $pH);
            // check whether $charge is 0 (consecuentely, pH will be the isoelectric point
            if (round($charge, 4) == 0) {
                break;
            }
            // next line to check how computation is perform
            // print "<br>$charge\t$pH";
            // modify pH for next round
            if ($charge > 0) {
                $pH = $pH + $delta;
            } else {
                $pH = $pH - $delta;
            }
            // reduce value for $delta
            $delta = $delta / 2;
        }
        // return pH at which charge=0 (the isoelectric point) with two decimals
        return round($pH, 2);
    }

    function partial_charge($val1, $val2)
    {
        // compute concentration ratio
        $cr = pow(10, $val1 - $val2);
        // compute partial charge
        $pc = $cr / ($cr + 1);
        return $pc;
    }

    // computes protein charge at corresponding pH
    /**
     * @param $pK
     * @param $aminoacid_content
     * @param $pH
     * @return float
     */
    function protein_charge($pK, $aminoacid_content, $pH)
    {
        $charge = partial_charge($pK["N_terminus"], $pH);
        $charge += partial_charge($pK["K"], $pH) * $aminoacid_content["K"];
        $charge += partial_charge($pK["R"], $pH) * $aminoacid_content["R"];
        $charge += partial_charge($pK["H"], $pH) * $aminoacid_content["H"];
        $charge -= partial_charge($pH, $pK["D"]) * $aminoacid_content["D"];
        $charge -= partial_charge($pH, $pK["E"]) * $aminoacid_content["E"];
        $charge -= partial_charge($pH, $pK["C"]) * $aminoacid_content["C"];
        $charge -= partial_charge($pH, $pK["Y"]) * $aminoacid_content["Y"];
        $charge -= partial_charge($pH, $pK["C_terminus"]);
        return $charge;
    }

    function pK_values($data_source)
    {
        // pK values for each component (aa)
        if ($data_source == "EMBOSS") {
            $pK = array(
                "N_terminus" => 8.6,
                "K" => 10.8,
                "R" => 12.5,
                "H" => 6.5,
                "C_terminus" => 3.6,
                "D" => 3.9,
                "E" => 4.1,
                "C" => 8.5,
                "Y" => 10.1
            );
        } elseif ($data_source == "DTASelect") {
            $pK = array(
                "N_terminus" => 8,
                "K" => 10,
                "R" => 12,
                "H" => 6.5,
                "C_terminus" => 3.1,
                "D" => 4.4,
                "E" => 4.4,
                "C" => 8.5,
                "Y" => 10
            );
        } elseif ($data_source == "Solomon") {
            $pK = array(
                "N_terminus" => 9.6,
                "K" => 10.5,
                "R" => 125,
                "H" => 6.0,
                "C_terminus" => 2.4,
                "D" => 3.9,
                "E" => 4.3,
                "C" => 8.3,
                "Y" => 10.1
            );
        }
        return $pK;
    }


    function print_aminoacid_content($aminoacid_content)
    {
        $results = "<table class=\"table table-striped\">";
        $tab = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp";
        foreach ($aminoacid_content as $aa => $count) {
            $results .= "<br>$aa $tab $count <br>";
            //$results.=" $aa </tr></td>"."<tr>".seq_1letter_to_3letter($aa)."</tr>"."<tr> $count</tr>";

        }
        $results .= "</table>";
        return $results;
    }

    function aminoacid_content($seq)
    {
        $array = array("A" => 0, "R" => 0, "N" => 0, "D" => 0, "C" => 0, "E" => 0, "Q" => 0, "G" => 0, "H" => 0, "I" => 0, "L" => 0,
            "K" => 0, "M" => 0, "F" => 0, "P" => 0, "S" => 0, "T" => 0, "W" => 0, "Y" => 0, "V" => 0, "X" => 0, "*" => 0);
        for ($i = 0; $i < strlen($seq); $i++) {
            $aa = substr($seq, $i, 1);
            $array[$aa]++;
        }
        return $array;
    }

    function molar_absorption_coefficient_of_prot($seq, $aminoacid_content, $molweight)
    {
        // Prediction of the molar absorption coefficient of a protein
        // Pace et al. . Protein Sci. 1995;4:2411-23.
        $abscoef = ($aminoacid_content["A"] * 5500 + $aminoacid_content["Y"] * 1490 + $aminoacid_content["C"] * 125) / $molweight;
        return $abscoef;
    }

    // molecular weight calculation
    function protein_molecular_weight($seq, $aminoacid_content)
    {
        $molweight = $aminoacid_content["A"] * 71.07;         // for Alanine
        $molweight += $aminoacid_content["R"] * 156.18;        // for Arginine
        $molweight += $aminoacid_content["N"] * 114.08;        // for Asparagine
        $molweight += $aminoacid_content["D"] * 115.08;        // for Aspartic Acid
        $molweight += $aminoacid_content["C"] * 103.10;        // for Cysteine
        $molweight += $aminoacid_content["Q"] * 128.13;        // for Glutamine
        $molweight += $aminoacid_content["E"] * 129.11;        // for Glutamic Acid
        $molweight += $aminoacid_content["G"] * 57.05;         // for Glycine
        $molweight += $aminoacid_content["H"] * 137.14;        // for Histidine
        $molweight += $aminoacid_content["I"] * 113.15;        // for Isoleucine
        $molweight += $aminoacid_content["L"] * 113.15;        // for Leucine
        $molweight += $aminoacid_content["K"] * 128.17;        // for Lysine
        $molweight += $aminoacid_content["M"] * 131.19;        // for Methionine
        $molweight += $aminoacid_content["F"] * 147.17;        // for Phenylalanine
        $molweight += $aminoacid_content["P"] * 97.11;         // for Proline
        $molweight += $aminoacid_content["S"] * 87.07;         // for Serine
        $molweight += $aminoacid_content["T"] * 101.10;        // for Threonine
        $molweight += $aminoacid_content["W"] * 186.20;        // for Tryptophan
        $molweight += $aminoacid_content["Y"] * 163.17;        // for Tyrosine
        $molweight += $aminoacid_content["Z"] * 99.13;         // for Valine
        $molweight += 18.02;                     // water
        $molweight += $aminoacid_content["X"] * 114.822;       // for unkwon aminoacids, add avarage of all aminoacids
        return $molweight;

    }


    function protein_aminoacid_nature1($seq)
    {
        $result = "";
        for ($i = 0; $i < strlen($seq); $i++) {
            // non-polar aminoacids, magenta
            if (strpos(" GAPVILFM", substr($seq, $i, 1)) > 0) {
                $result .= "<font color=orange>" . substr($seq, $i, 1) . "</font>";
                continue;
            }
            // polar aminoacids, magenta
            if (strpos(" SCTNQHYW", substr($seq, $i, 1)) > 0) {
                $result .= "<font color=magenta>" . substr($seq, $i, 1) . "</font>";
                continue;
            }
            // charged aminoacids, red
            if (strpos(" DEKR", substr($seq, $i, 1)) > 0) {
                $result .= "<font color=red>" . substr($seq, $i, 1) . "</font>";
                continue;
            }

        }
        return $result;
    }

    function protein_aminoacid_nature2($seq)
    {
        $result = "";
        for ($i = 0; $i < strlen($seq); $i++) {
            // Small nonpolar (orange)
            if (strpos(" GAST", substr($seq, $i, 1)) > 0) {
                $result .= "<font color=orange>" . substr($seq, $i, 1) . "</font>";
                continue;
            }
            // Small hydrophobic (green)
            if (strpos(" CVILPFYMW", substr($seq, $i, 1)) > 0) {
                $result .= "<font color=green>" . substr($seq, $i, 1) . "</font>";
                continue;
            }
            // Polar
            if (strpos(" DQH", substr($seq, $i, 1)) > 0) {
                $result .= "<font color=magenta>" . substr($seq, $i, 1) . "</font>";
                continue;
            }
            // Negatively charged
            if (strpos(" NE", substr($seq, $i, 1)) > 0) {
                $result .= "<font color=red>" . substr($seq, $i, 1) . "</font>";
                continue;
            }
            // Positively charged
            if (strpos(" KR", substr($seq, $i, 1)) > 0) {
                $result .= "<font color=blue>" . substr($seq, $i, 1) . "</font>";
                continue;
            }

        }
        return $result;
    }

    // Chemical group/aminoacids:
    //   L/GAVLI       Amino Acids with Aliphatic R-Groups
    //   H/ST          Non-Aromatic Amino Acids with Hydroxyl R-Groups
    //   M/NQ          Acidic Amino Acids
    //   R/FYW         Amino Acids with Aromatic Rings
    //   S/CM          Amino Acids with Sulfur-Containing R-Groups
    //   I/P           Imino Acids
    //   A/DE          Acidic Amino Acids
    //   C/KRH         Basic Amino Acids
    //   */*
    //   X/X
    function protein_aminoacids_chemical_group($amino_seq)
    {
        $chemgrp_seq = "";
        $ctr = 0;
        while (1) {
            $amino_letter = substr($amino_seq, $ctr, 1);
            if ($amino_letter == "") break;
            if (strpos(" GAVLI", $amino_letter) > 0) $chemgrp_seq .= "L";
            elseif (($amino_letter == "S") or ($amino_letter == "T")) $chemgrp_seq .= "H";
            elseif (($amino_letter == "N") or ($amino_letter == "Q")) $chemgrp_seq .= "M";
            elseif (strpos(" FYW", $amino_letter) > 0) $chemgrp_seq .= "R";
            elseif (($amino_letter == "C") or ($amino_letter == "M")) $chemgrp_seq .= "S";
            elseif ($amino_letter == "P") $chemgrp_seq .= "I";
            elseif (($amino_letter == "D") or ($amino_letter == "E")) $chemgrp_seq .= "A";
            elseif (($amino_letter == "K") or ($amino_letter == "R") or ($amino_letter == "H"))
                $chemgrp_seq .= "C";
            elseif ($amino_letter == "*") $chemgrp_seq .= "*";
            elseif ($amino_letter == "X" or $amino_letter == "N") $chemgrp_seq .= "X";
            else die("Invalid amino acid symbol in input sequence.");
            $ctr++;
        }
        return $chemgrp_seq;
    }

    function produce_aminoacid_content($aminoacid_content)
    {
        // $results="<table class=\"table table-striped\">";
        //$tab = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp";
        $stack[] = '';
        foreach ($aminoacid_content as $aa => $count) {
            array_push($stack, $count);
        }
        return $stack;
    }

    function make_photo($points)
    {

        /* CAT:Bar Chart */
        /* pChart library inclusions */

        /* Create and populate the pData object */
        $MyData = new pData();
        //print_r ($points); Debugging
        $MyData->addPoints($points, "Amino acids ");
        $MyData->setAxisName(0, "Counts");
        $MyData->addPoints(array(" ", "A", "R", "N", "D", "C", "E", "Q", "G", "H", "I", "L", "K", "M", "F", "P", "S", "T", "W", "Y", "V", "X", "*"), "Labels");
        $MyData->setSerieDescription("Amino acids", "Labels");
        $MyData->setAbscissa("Labels");

        /* Create the pChart object */
        $myPicture = new pImage(480, 230, $MyData);

        /* Draw the background */
        $Settings = array("R" => 170, "G" => 183, "B" => 87, "Dash" => 1, "DashR" => 190, "DashG" => 203, "DashB" => 107);
        $myPicture->drawFilledRectangle(0, 0, 700, 230, $Settings);

        /* Overlay with a gradient */
        $Settings = array("StartR" => 219, "StartG" => 231, "StartB" => 139, "EndR" => 1, "EndG" => 138, "EndB" => 68, "Alpha" => 50);
        $myPicture->drawGradientArea(0, 0, 700, 230, DIRECTION_VERTICAL, $Settings);
        $myPicture->drawGradientArea(0, 0, 700, 20, DIRECTION_VERTICAL, array("StartR" => 0, "StartG" => 0, "StartB" => 0, "EndR" => 50, "EndG" => 50, "EndB" => 50, "Alpha" => 80));

        /* Add a border to the picture */
        $myPicture->drawRectangle(0, 0, 699, 229, array("R" => 0, "G" => 0, "B" => 0));

        /* Write the picture title */
        $myPicture->setFontProperties(array("FontName" => "./fonts/Silkscreen.ttf", "FontSize" => 6));
        $myPicture->drawText(10, 13, "Amino Acid Statistics", array("R" => 255, "G" => 255, "B" => 255));

        /* Write the chart title */
        $myPicture->setFontProperties(array("FontName" => "./fonts/Forgotte.ttf", "FontSize" => 11));
        $myPicture->drawText(250, 55, "Amino Acid counts", array("FontSize" => 20, "Align" => TEXT_ALIGN_BOTTOMMIDDLE));

        /* Draw the scale and the 1st chart */
        $myPicture->setGraphArea(50, 50, 460, 200);
        $myPicture->drawFilledRectangle(50, 50, 450, 190, array("R" => 255, "G" => 255, "B" => 255, "Surrounding" => -200, "Alpha" => 10));
//     $myPicture->drawScale(array("DrawSubTicks"=>TRUE));
        //   $myPicture->drawScale(array("CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"GridR"=>0,"Floating0Value"=> 5, "Floating0Serie" => A , "AroundZero"=>TRUE,"GridG"=>0,"GridB"=>0,"GridAlpha"=>10));
        $myPicture->drawScale(array('Mode' => SCALE_MODE_START0));
        $myPicture->setShadow(TRUE, array("X" => 0, "Y" => 0, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));
        $myPicture->setFontProperties(array("FontName" => "../fonts/pf_arma_five.ttf", "FontSize" => 5));
        $myPicture->drawBarChart(array("DisplayValues" => TRUE, "DisplayColor" => DISPLAY_AUTO, "Rounded" => FALSE, "Surrounding" => 10));
        $myPicture->setShadow(FALSE);

        global $seqname;
        $seqid = $seqname;


        $myPicture->Render("./prot_stat/$seqid.png");
        /* Render the picture (choose the best way) */
        echo " <img src=\"./prot_stat/$seqid.png\"/></img>";

    }

    ?>
    <hr>

    <p>© BioMine Team 2016</p>
</div><!-- /container -->
<!-- Le javascript
  ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="./js/jquery.js" type="text/javascript">
</script>
<script src="./js/bootstrap-transition.js" type="text/javascript">
</script>
<script src="./js/bootstrap-alert.js" type="text/javascript">
</script>
<script src="./js/bootstrap-modal.js" type="text/javascript">
</script>
<script src="./js/bootstrap-dropdown.js" type="text/javascript">
</script>
<script src="./js/bootstrap-scrollspy.js" type="text/javascript">
</script>
<script src="./js/bootstrap-tab.js" type="text/javascript">
</script>
<script src="./js/bootstrap-tooltip.js" type="text/javascript">
</script>
<script src="./js/bootstrap-popover.js" type="text/javascript">
</script>
<script src="./js/bootstrap-button.js" type="text/javascript">
</script>
<script src="./js/bootstrap-collapse.js" type="text/javascript">
</script>
<script src="./js/bootstrap-carousel.js" type="text/javascript">
</script>
<script src="./js/bootstrap-typeahead.js" type="text/javascript">

</script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">


<?php


?>
</body>
</html>