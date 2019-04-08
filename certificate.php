<?php
/*********************************************
certificate.php
Dynamically Create PDF for Music Small/Large Emsemble Award Certificate
Created 7/27/18
Author citg
**********************************************/
require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';
require 'mu/mufunctions.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);
 if(!ValidUser($session))
{
   header("Location:../index.php?error=1");
   exit();
} 
$level=GetLevel($session);

require_once('../tcpdf/tcpdf.php');

$result=mysql_query($sql);
if(mysql_error()) 
{
   echo $sql."<br>".mysql_error()."<br>";
   exit();
}


// create new PDF document
    $pageLayout = array(280, 186);
   $pdf = new TCPDF("L", PDF_UNIT, $pageLayout, true); //LETTER = 8.5 x 11 in or 216 x 280 mm 
   //$pdf = new TCPDF("L", PDF_UNIT, "LETTER", true); //LETTER = 8.5 x 11 in or 216 x 280 mm

   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   $pdf->SetMargins(0,0);
   $pdf->SetAutoPageBreak(false, 1);
   $pdf->setLanguageArray($l);
   $pdf->SetFont("helvetica","",14);

   //BACKGROUND:
   $img_file = $_SERVER['DOCUMENT_ROOT'].'/images/certificate.png';

   //FOR EACH SOLOIST:
   $x=0; $y=1; $width=214;
/*    for($i=0;$i<count($entryids);$i++)
   { */

     $pdf->AddPage();
   	 //BACKGROUND:
   	 $pdf->Image($img_file, 0, 0, 280, 186, '', '', '', false, 300, '', false, false, 0);
   	 $pdf->setPageMark();  //(Make it a background image)
         //GRID LINES
     $style = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,5,5,10', 'phase' => 10, 'color' => array(210, 210, 210));
     //  $pdf->Line(0, 180/2, 216, 180/2, $style);
	 $x=35; $y=1;

	  $sql="SELECT * FROM believers WHERE id =$_GET[id]";
	  $result=mysql_query($sql);
	  $row=mysql_fetch_array($result); 
	  $name =$row[name];
	  $id =$row[id];
	  $gender =$row[gender];
	  $race =$row[race];
	  $school = $row[school];

      $y2+=40;
      $pdf->SetFont("freeserifi","","40");
      $pdf->writeHTMLCell("$width","",$x,$y2,$name,0,0,0,true,"C");
      $pdf->SetFont("dejavuserif","","12");
      $pdf->SetFont("helvetica","","26");
      $y2+=20; 
	  if ($showschool=="Test's School") $showschool=$showschool;
	  else
	  $showschool="$school High School";
      $pdf->writeHTMLCell("$width","",$x,$y2,"$showschool",0,0,0,true,"C");


   $pdf->Output("/home/nsaahome/attachments/$filename1", "I");
   //header("Location:../attachments.php?session=$session&filename=$filename1");

?>
