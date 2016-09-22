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

$seqid = 'U123';
$filename = "./prot_stat/$seqid.png";

if (file_exists($filename)) {
echo " <img src=\"./prot_stat/$seqid.png\"/></img>";
} else {
    echo "The file $filename does not exist";

 include("./class/pData.class.php");
 include("./class/pDraw.class.php");
 include("./class/pImage.class.php");
	$seq = "MTCTLRLTVAALVLLGICHLSRPVAAYQKCARYWYCWLPYDIERDRYDDGYRLCCYCRNAWTPWQCREDEQFERLRCGSRYYTLCCYTEDDNGNGNGNGNGYGNGNGNGNGNNYLKYLFGGNGNGNGEFWEEYIDERYDK";
	$aminoacid_content= aminoacid_content($seq);
	global $points ;
	$points = produce_aminoacid_content($aminoacid_content);
 
  make_photo($points);
}
 function make_photo($points){
 
 /* CAT:Bar Chart */
 /* pChart library inclusions */

 /* Create and populate the pData object */
 $MyData = new pData();  
 $MyData->addPoints($points,"Probe 3");
 $MyData->setAxisName(0,"Count");
 $MyData->addPoints(array("A","R","N","D","C","E","Q","G","H","I","L","K","M","F","P","S","T","W","Y","V","X","*"),"Labels");
 $MyData->setSerieDescription("Labels","Months");
 $MyData->setAbscissa("Labels");

 /* Create the pChart object */
 $myPicture = new pImage(480,230,$MyData);

 /* Draw the background */
 $Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
 $myPicture->drawFilledRectangle(0,0,700,230,$Settings);

 /* Overlay with a gradient */
 $Settings = array("StartR"=>219, "StartG"=>231, "StartB"=>139, "EndR"=>1, "EndG"=>138, "EndB"=>68, "Alpha"=>50);
 $myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);
 $myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));
 
 /* Write the picture title */ 
 $myPicture->setFontProperties(array("FontName"=>"./fonts/Silkscreen.ttf","FontSize"=>6));
 $myPicture->drawText(10,13,"Amino Acid Statistics",array("R"=>255,"G"=>255,"B"=>255));

 /* Write the chart title */ 
 $myPicture->setFontProperties(array("FontName"=>"./fonts/Forgotte.ttf","FontSize"=>11));
 $myPicture->drawText(250,55,"Amino Acid counts",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

 /* Draw the scale and the 1st chart */
 $myPicture->setGraphArea(60,60,450,190);
 $myPicture->drawFilledRectangle(60,60,450,190,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));
 $myPicture->drawScale(array("DrawSubTicks"=>TRUE));
 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
 $myPicture->setFontProperties(array("FontName"=>"../fonts/pf_arma_five.ttf","FontSize"=>6));
 $myPicture->drawBarChart(array("DisplayValues"=>TRUE,"DisplayColor"=>DISPLAY_AUTO,"Rounded"=>TRUE,"Surrounding"=>30));
 $myPicture->setShadow(FALSE);


 /* Write the chart legend */
 $myPicture->drawLegend(510,205,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));
//header("Content-Type: multipart/mixed");
$seqid = 'U123';

//$base64=$myPicture->autoOutput();
$myPicture->Render("./prot_stat/$seqid.png");
 /* Render the picture (choose the best way) */
echo " <img src=\"./prot_stat/$seqid.png\"/></img>";


  }

 
    function produce_aminoacid_content($aminoacid_content) {
        $results="<table class=\"table table-striped\">";
        $tab = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp";
         $stack[]= 0;
        foreach($aminoacid_content as $aa => $count){
		array_push($stack,$count);
        }
        $results.= "</table>";
        return $stack;
}

function aminoacid_content($seq) {
        $array=array("A"=>0,"R"=>0,"N"=>0,"D"=>0,"C"=>0,"E"=>0,"Q"=>0,"G"=>0,"H"=>0,"I"=>0,"L"=>0,
                     "K"=>0,"M"=>0,"F"=>0,"P"=>0,"S"=>0,"T"=>0,"W"=>0,"Y"=>0,"V"=>0,"X"=>0,"*"=>0);
        for($i=0; $i<strlen($seq);$i++){
                $aa=substr($seq,$i,1);
                $array[$aa]++;
        }
         
        return $array;
        }
?>