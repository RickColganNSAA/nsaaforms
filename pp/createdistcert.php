<?php
/*********************************************
createdistcert.php
Dynamically Create PDF for PP District Certificate
For District Hosts
Copied from createcertificate.php & adapted on 11/24/10
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

if(!$distid)
{
   //Get Host ID
   $sql="SELECT t1.id FROM logins AS t1, headers AS t2 WHERE t1.school=t2.school AND t2.id='$schoolid' AND t1.level=2";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostid=$row[id];

   //Get District this School is Hosting
   $sql="SELECT * FROM $db_name2.ppdistricts WHERE hostid='$hostid'";
}
else
   $sql="SELECT * FROM $db_name2.ppdistricts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$distid=$row[id]; 
$sids=split(",",$row[sids]);
$ppschs[sid]=array(); $ppschs[school]=array();
for($i=0;$i<count($sids);$i++)
{
   $ppschs[sid][$i]=trim($sids[$i]);
   $ppschs[school][$i]=GetSchoolName($ppschs[sid][$i],'pp');
}

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
   $curschool=trim(GetSchoolName($sid,'pp'));
   if(substr($curschool,strlen($curschool)-4,4)=="High")
      $curschool=ereg_replace(" High","",$curschool);
   $pdf->writeHTMLCell("200","",$x,$y,"$curschool High School",0,0,0,true,"C");
   $y+=20;
   $pdf->SetFont("helvetica","","16");
   $pdf->writeHTMLCell("200","",$x,$y,"received a <b>SUPERIOR</b> rating",0,0,0,true,"C");
   $y+=10;
   $pdf->writeHTMLCell("200","",$x,$y,"at the ".GetFallYear('pp')." Play Production District Contest",0,0,0,true,"C");
   //EXEC DIRECTOR SIGNATURE:
   $x=55; $y=177; $x2=120;
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
   $curschool=trim(GetSchoolName($sid,'pp'));
   if(substr($curschool,strlen($curschool)-4,4)=="High")
      $curschool=ereg_replace(" High","",$curschool);
   $pdf->writeHTMLCell("200","",$x,$y,"<b>$student</b><br><i>of</i><br><b>$curschool High School</b>",0,0,0,true,"C");
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
      echo "<br><br><br><table width='500px' class='nine' cellspacing=2 cellpadding=2><tr align=left><td><b>Your certificate has been created.</b><br><br>To <b><i>preview</b></i> the certificate, click: <a href=\"$filename1.pdf\" target=\"_blank\">Preview</a>.<br><br>If you are <b><i>satisfied</i></b> with the certificate, you can save it to your computer (after opening the Preview, select File->Save As from the browser menu) and/or print the certificate (File->Print).<br><br>If you need to <b><i>make changes</b></i> to the certificate, <a href=\"javascript:history.go(-1);\">Go Back</a> and do so.<br><br><br>If you are ready to start a <b><i>NEW</i></b> certificate, <a href=\"createdistcert.php?school=$school&session=$session&distid=$distid\">Click Here</a>.</td></tr></table>";
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
   echo "<br><form method=post action=\"createdistcert.php\">";
   echo "<input type=hidden name=\"session\" value=\"$session\">";
   echo "<input type=hidden name=\"school\" value=\"$school\">";
   echo "<input type=hidden name=\"distid\" value=\"$distid\">";
   echo "<table class=nine cellspacing=2 cellpadding=5 width='500px'><caption><b>NSAA District Play Production Award Certificate:</b><br>";
   echo "<div class='alert'><B>INSTRUCTIONS:</b><ul><li>Please type the name of the student and the type of award he or she is to receive. Then click \"Continue to Preview Certificate.\"</li></ul></div></caption>";
   echo "<tr align=left><td>School:</td><td><select onchange=\"submit();\" name=\"sid\"><option value='0'>Select School</option>";
   for($i=0;$i<count($ppschs[sid]);$i++)
   {
      echo "<option value='".$ppschs[sid][$i]."'";
      if($sid==$ppschs[sid][$i]) echo " selected";
      echo ">".$ppschs[school][$i]."</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=left valign=top><td>Student Name:</td><td><select name=\"student\"><option value=''>Select Student</option>";
   if($sid)
   {
      $sql="SELECT * FROM ppschool WHERE sid='$sid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $sql2="SELECT DISTINCT t1.id,t1.first,t1.last FROM eligibility AS t1,pp_students AS t2,headers AS t3 WHERE t1.id=t2.student_id AND t1.school=t3.school AND (t3.id='$row[mainsch]' OR ";
      if($row[othersch1]>0) $sql2.="t3.id='$row[othersch1]' OR ";
      if($row[othersch2]>0) $sql2.="t3.id='$row[othersch2]' OR ";
      if($row[othersch3]>0) $sql2.="t3.id='$row[othersch3]' OR ";
      $sql2=substr($sql2,0,strlen($sql2)-4).") ORDER BY t1.last,t1.first";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
	 $studentname=GetStudentInfo($row2[id],FALSE);
  	 echo "<option value=\"$studentname\"";
	 if($student==$studentname) echo " selected";
	 echo ">$studentname</option>";
      }
   }
   echo "</select></td></tr>";
   echo "<tr align=left valign=top><td>Type of Award:</td><td><select name=\"rating\"><option value='Outstanding'>Outstanding</option><option value='Superior'>Superior</option></select></td></tr>";
   echo "<tr align=center><td colspan=2><input type=submit name=\"preview\" value=\"Continue to Preview Certificate\"></td></tr>";
   echo "</table></form>";
   echo $end_html;
}
exit();

?>
