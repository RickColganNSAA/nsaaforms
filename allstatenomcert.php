<?php
/*********************************************
allstatenomcert.php
Dynamically Create PDF All State-Academic Award Certificate
Copied from sp folder & adapted on 5/24/10
Author Ann Gaffigan
**********************************************/
require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
$level=GetLevel($session);
if($level==2 || $level==3)
{
   $school=GetSchool($session);
}

if(PastDue(date("Y")."-05-31",0) && !PastDue(date("Y")."-07-01",0))       //IF IT IS PAST END OF SCHOOL YEAR, NEED TO REFER TO ARCHIVED DB
{
   $year1=date("Y")-1; $year2=date("Y");
   mysql_select_db($db_name.$year1.$year2, $db);
   $fallyear=$year1;
}
else if(date("m")>=6) $fallyear=date("Y");
else $fallyear=date("Y")-1;

if($level==2 || $level==3)
{
   $schoolid=GetSchoolID2($school);
}

//include PDF creation tool:
require_once('../tcpdf/tcpdf.php');

//CHECK THIS IS A VALID NOMINATION TO PRINT
$sql="SELECT * FROM allstatenom WHERE id='$nomid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(IsInCoop($schoolid,$row[sport]))	//$headschoolid = SCHOOLID OF HEAD SCHOOL (IF IN COOP) OR SUBMITTING SCHOOL (IF NOT)
   $headschoolid=GetSchoolID2(GetCoopHeadSchool($schoolid,$row[sport]));
else $headschoolid=$schoolid;
$theschoolid=$row[schoolid];	//$theschoolid = SCHOOL ID OF THE STUDENT
//echo "Head School ID: $headschoolid, School ID: $schoolid";

$sql="SELECT t1.first,t1.last,t1.semesters,t1.school,t2.* FROM eligibility AS t1,allstatenom AS t2 WHERE t1.id=t2.studentid AND t2.id='$nomid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(mysql_num_rows($result)==0 || ($level!=1 && $schoolid!=$theschoolid && $row[schoolid]!=$headschoolid))
{
   //IF THE NOMINATION CAN'T BE FOUND OR THE USER ISN'T ASSOCIATED WITH THE SCHOOL WHO SUBMITTED THE FORM OR THE SCHOOL THE STUDENT ATTENDS, EXIT:
   echo $init_html2;
   echo "<div class=error>ERROR: Invalid nomination ID.</div>";
   echo $end_html2;
   exit();
}

//CREATE PDFs: Black & White, then Color

// create new PDF document
   //$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true);
   $pdf = new TCPDF("L", PDF_UNIT, "LETTER", true); //LETTER = 8.5 x 11 in or 216 x 280 mm
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   $pdf->SetMargins(5,5);
   $pdf->SetAutoPageBreak(TRUE, 1);
   $pdf->setLanguageArray($l);
   $pdf->AddPage();
   $pdf->SetFont("helvetica","",14);

   //Get Rid of Line at Top 
   $pdf->setPrintFooter(false);
   $pdf->SetFillColor(255,255,255);
   $pdf->writeHTMLCell("280","50",0,0,"",0,1,1,true,"C");

   //BACKGROUND:
   $img_file = $_SERVER['DOCUMENT_ROOT'].'/images/AllStateNomBack.jpg';
   $pdf->Image($img_file, 15, 10, 250, 167, '', '', '', false, 300, '', false, false, 0);
   $pdf->setPageMark();	 //(Make it a background image)

   //BODY OF THE CERT:
   $pdf->SetXY(10,70);
   $fontsize=48; $align="C"; $break="3";
   $y=45; $x=50;
   $pdf->SetFont("dejavuserifi","","16");
   $pdf->writeHTMLCell("280","",0,$y,"Presented to:",0,0,0,true,"C");
   $pdf->SetFont("freeserifi","",$fontsize);
   //GET STUDENT NAME
   if($row[studentname]!='') $student=$row[studentname];
   else $student=GetStudentInfo($row[studentid],FALSE);
   if(strtoupper($student)==$student) $student=CapFirst($student);

   $y+=35;
   $pdf->writeHTMLCell("280","",0,$y,"<b>$student</b>",0,0,0,true,"C");
   $pdf->SetFont("helvetica","B","24");
   $y+=22;
   if(substr($row[school],strlen($row[school])-5,5)==" High")
	$row[school]=substr($row[school],0,strlen($row[school])-5);
   $pdf->writeHTMLCell("280","",0,$y,"$row[school] High School",0,0,0,true,"C");
   $y+=47;
   $pdf->SetFont("dejavuserifi","","16");
   $pdf->writeHTMLCell("280","",0,$y,"In recognition for academic excellence and exemplary leadership in",0,0,0,true,"C");
   $pdf->SetFont("freeserifi","B","24");
   $y+=10;
   $sportname=GetActivityName($row[sport]);
   if($sportname=="Cross-Country") $sportname="Cross Country";
   $pdf->writeHTMLCell("280","",0,$y,"<b>$sportname</b>",0,0,0,true,"C");
   $pdf->SetFont("freeserifi","","18");
   $y+=12;
   $season=GetSeason($row[sport]);
   if($season=="Fall") $year=$fallyear;
   else if($season=="Spring")
      $year=$springyear;
   else 
   {
      $springyear=$fallyear+1;
      $year="$fallyear-$springyear";
   }
   $pdf->writeHTMLCell("280","",0,$y,"$season Season $year",0,0,0,true,"C");
   //EXEC DIRECTOR SIGNATURE:
   $x=25; $y=172;
   $pdf->Image("../images/jay.png",$x,$y,70);
   //SPONSOR LOGO
   $x=213; $y=173;
   $pdf->Image("../images/NCPAgrayscale.png",$x,$y,50);

   $pdf->SetFont("helvetica","","14");
   $x=25; $y=172+15;
   $pdf->writeHTMLCell("70","",$x,$y,"Jay Bellar<br>NSAA Executive Director",0,0,0,true,"C");

   $filename1="AcademicAllStateAward".ereg_replace("[^a-zA-Z]","",$student).".pdf";
   $pdf->Output("/home/nsaahome/attachments/$filename1", "F");
   header("Location:attachments.php?session=$session&filename=$filename1");

?>
