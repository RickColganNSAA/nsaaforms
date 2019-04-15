<?php
/*********************************************
createcertificate.php
Dynamically Create PDF for PP Certificate
Copied from sp/createcertificate && allstatenomcert & adapted on 10/12/10
Author Ann Gaffigan
**********************************************/
require '../functions.php';
require '../../calculate/functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//include PDF creation tool:
require_once('../../tcpdf_php4/config/lang/eng.php');
require_once('../../tcpdf_php4/tcpdf.php');

if(!ValidUser($session))
{
   header("Location:../index.php?error=1");
   exit();
}
$level=GetLevel($session);
if($level==2 || $level==3)
{
   $school=GetSchool($session); 
   $schoolid=GetSchoolID2($school);
}
//else school doesn't matter

if($preview || $download)
{
   //CREATE PDFs: Black & White, then Color

   // create new PDF document
   $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true);
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   $pdf->SetMargins(5,5);
   $pdf->SetAutoPageBreak(TRUE, 1);
   $pdf->setLanguageArray($l);
   $pdf->AliasNbPages();
   $pdf->AddPage();

   if($rating=="Superior")
   {
   $pdf->Image("../../images/certtopCOLOR.png",49,17,200);
   $y=87; $x=49;
   $pdf->SetFont("helvetica","","16");
   $pdf->writeHTMLCell("200","",$x,$y,"This certifies that",0,0,0,true,"C");
   $pdf->SetFont("helvetica","",30);
   $y+=15;
   $pdf->writeHTMLCell("200","",$x,$y,"<b>$student</b>",0,0,0,true,"C");
   $pdf->SetFont("helvetica","","22");
   $y+=15;
   $pdf->writeHTMLCell("200","",$x,$y,"$school High School",0,0,0,true,"C");
   $y+=20;
   $pdf->SetFont("helvetica","","16");
   $pdf->writeHTMLCell("200","",$x,$y,"received a <b>SUPERIOR</b> rating",0,0,0,true,"C");
   $y+=10;
   $pdf->writeHTMLCell("200","",$x,$y,"at the ".GetFallYear('pp')." Play Production District Contest",0,0,0,true,"C");
   //EXEC DIRECTOR SIGNATURE:
   $x=55; $y=177; $x2=120;
   //$pdf->Image("../../images/tenopirsig.png",$x,$y,70);
   $pdf->Line($x, $y, $x2, $y);
   $pdf->SetFont("helvetica","","14");
   $y+=3;
   $pdf->writeHTMLCell("65","",$x,$y,"Director's Signature",0,0,0,true,"C");
   }
   else
   {
   $img_file = $_SERVER['DOCUMENT_ROOT'].'/images/PPDistCertBack.jpg';
   $pdf->Image($img_file, 25, 10, 250, 167, '', '', '', false, 300, '', false, false, 0);
   $pdf->setPageMark(); //THIS LINE MAKES THE ABOVE IMAGE ACT AS A BACKGROUND

   $pdf->SetXY(10,70);
   $fontsize=30; $align="C"; $break="3";
   $y=45; $x=50;
   $pdf->SetFont("freeserif","","16");
   $pdf->writeHTMLCell("200","",$x,$y,"This certifies that:",0,0,0,true,"C");

   //$student is given
   $y+=25;
   $pdf->SetFont("freeserif","",$fontsize);
   $pdf->writeHTMLCell("200","",$x,$y,"<b>$student</b><br><i>of</i><br><b>$school High School</b>",0,0,0,true,"C");
   $y+=50;
   //$pdf->SetFont("dejavuserif","","20");
   $pdf->SetFont("freeserif","","20");
   if($rating=="Superior") $html="<i>received a </i><b>$rating</b><i> rating</i>";
   else $html="<i>received an </i><b>Outstanding Performance</b><i> award</i>";
   $pdf->writeHTMLCell("200","",$x,$y,$html,0,0,0,true,"C");
   $y+=28;
   //$pdf->SetFont("dejavuserifi","","16");
   $pdf->SetFont("freeserif","","16");
   $pdf->writeHTMLCell("200","",$x,$y,"at the ".GetFallYear('pp')." Play Production District Contest",0,0,0,true,"C");
   //EXEC DIRECTOR SIGNATURE:
   $x=38; $y=177; $x2=$x+65;
   //$pdf->Image("../../images/tenopirsig.png",$x,$y,70);
   $pdf->Line($x, $y, $x2, $y);
   $pdf->SetFont("helvetica","","14");
   $y+=3;
   $pdf->writeHTMLCell("65","",$x,$y,"Director's Signature",0,0,0,true,"C");
   }

   $filename1="certificates/PPDistrict".$rating."Award".ereg_replace("[^a-zA-Z]","",$student);
   $pdf->Output("$filename1.pdf", "F");

   if($preview) //show link to Download or go back and make changes
   {
      echo $init_html;
      echo GetHeader($session);
      echo "<br><br><br><table width='500px' class='nine' cellspacing=2 cellpadding=2><tr align=left><td><b>Your certificate has been created.</b><br><br>To <b><i>preview</b></i> the certificate, click: <a href=\"$filename1.pdf\" target=\"_blank\">Preview</a>.<br><br>If you are <b><i>satisfied</i></b> with the certificate, you can save it to your computer (after opening the Preview, select File->Save As from the browser menu) and/or print the certificate (File->Print).<br><br>If you need to <b><i>make changes</b></i> to the certificate, <a href=\"javascript:history.go(-1);\">Go Back</a> and do so.<br><br><br>If you are ready to start a <b><i>NEW</i></b> certificate, <a href=\"createcertificate.php?school=$school&session=$session\">Click Here</a>.</td></tr></table>";
      echo $end_html;
      exit();
   }
}//end if $preview or $download
else
{
   //Get Information to Pre-Populate Form with:
   $school2=addslashes($school); unset($student); unset($rating);

   echo $init_html;
   echo GetHeader($session);
   echo "<br><form method=post action=\"createcertificate.php\">";
   echo "<input type=hidden name=\"session\" value=\"$session\">";
   echo "<input type=hidden name=\"school\" value=\"$school\">";
   echo "<table class=nine cellspacing=2 cellpadding=5 width='500px'><caption><b>NSAA District Play Production Award Certificate:</b><br>";
   echo "<div class='alert'><B>INSTRUCTIONS:</b><ul><li>Please type the name of the student and the type of award he or she is to receive. Then click \"Continue to Preview Certificate.\"</li></ul></div></caption>";
   echo "<tr align=left><td>School:</td><td>$school</td></tr>";
   echo "<tr align=left valign=top><td>Student Name:</td><td><input type=text name=\"student\" size=30></td></tr>";
   echo "<tr align=left valign=top><td>Type of Award:</td><td><select name=\"rating\"><option value='Outstanding'>Outstanding</option><option value='Superior'>Superior</option></select></td></tr>";
   echo "<tr align=center><td colspan=2><input type=submit name=\"preview\" value=\"Continue to Preview Certificate\"></td></tr>";
   echo "</table></form>";
   echo $end_html;
}
exit();

?>
