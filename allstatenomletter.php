<?php
/******************************************************
allstatenomletter.php
Dynamically Create PDF All State-Academic Award Letter
Copied from allstatenomcert.php & adapted on 9/3/10
Author Ann Gaffigan
*******************************************************/
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

//mysql_select_db("nsaascores20102011",$db);

if(PastDue(date("Y")."-05-31",0) && !PastDue(date("Y")."-07-01",0))       //IF IT IS PAST END OF SCHOOL YEAR, NEED TO REFER TO ARCHIVED DB
{
   $year1=date("Y")-1; $year2=date("Y");
   mysql_select_db($db_name.$year1.$year2, $db);
   $fallyear=$year1;
}
else if(date("m")>=6) $fallyear=date("Y");
else $fallyear=date("Y")-1;
$springyear=$fallyear+1;

if($level!=1) $schoolid=GetSchoolID2($school);


//include PDF creation tool:
require_once('../tcpdf/tcpdf.php');

//CHECK THIS IS A VALID NOMINATION TO PRINT
$sql="SELECT * FROM allstatenom WHERE id='$nomid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$sport=$row[sport];
if(IsInCoop($schoolid,$row[sport]))
   $headschoolid=GetSchoolID2(GetCoopHeadSchool($schoolid,$row[sport]));
else $headschoolid=$schoolid;
$theschoolid=$row[schoolid];
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
   $pdf = new TCPDF("P", PDF_UNIT, "LETTER", true);
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   $pdf->SetMargins(5,5);
   $pdf->SetAutoPageBreak(TRUE, 1);
   $pdf->setLanguageArray($l);
   $pdf->AddPage();
   $pdf->SetFont("helvetica","",10);

   //Get Rid of Line at Top 
   $pdf->setPrintFooter(false);
   $pdf->SetFillColor(255,255,255);
   $pdf->writeHTMLCell("216","50",0,0,"",0,1,1,true,"C");

   $img_file = $_SERVER['DOCUMENT_ROOT'].'/images/logofullsize.png';
   $pdf->Image($img_file, 79, 10, 50);

   $x=0; $y=40;
   $html="<b>NEBRASKA SCHOOL ACTIVITIES ASSOCIATION</b><br>
500 Charleston Street, Suite 1<br>
Lincoln, Nebraska 68508<br>
(402) 489-0386";
   $pdf->writeHTMLCell("200","",$x,$y,$html,0,0,0,true,"C");

   $x=25; $y=68; 
   if(substr($row[school],strlen($row[school])-5,5)==" High")
        $row[school]=substr($row[school],0,strlen($row[school])-5);
   $sql2="SELECT * FROM misc_duedates WHERE sport LIKE 'allstatenom_".strtolower(GetSeason($sport))."'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $date=explode("-",$row2[duedate]);

   $html=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]))."<br><br>$row[school] High School";
   $pdf->writeHTMLCell("160","",$x,$y,$html,0,0,0,true,"L");

   if($row[studentname]!='') $student=$row[studentname];
   else $student="$row[first] $row[last]";
   $y+=19;
   $pdf->writeHTMLCell("160","",$x,$y,"Dear ".$student.":",0,0,0,true,"L"); 

   $sql2="SELECT * FROM allstatenomletter";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $html=preg_replace("/\[YEAR\]/","$fallyear-$springyear",$row2[letterbody]);

   $y+=11;
   $pdf->writeHTMLCell("160","",$x,$y,$html,0,1,0,true,"L");

   $html="Sincerely,";
   $y=$pdf->GetY();
   $y+=6;
   $pdf->writeHTMLCell("160","",$x,$y,$html,0,0,0,true,"L");
  
   $y+=13;
   $pdf->Image("../images/jay.png",$x,$y,55);

   $html="Jay Bellar<br>
Executive Director<br>Nebraska School Activities Association";
   $y+=22;   
   $pdf->writeHTMLCell("","",$x,$y,$html,0,0,0,true,"L");

   $y-=22; $x2=120;
   $pdf->Image("../images/LouSignature.png",$x2,$y,65);

   $html="Louis Andersen<br>
Executive Director<br>Nebraska Chiropractic Physicians Association";
   $y+=22;  
   $pdf->writeHTMLCell("","",$x2,$y,$html,0,0,0,true,"L");

   $filename1="AcademicAllStateAwardLetter".ereg_replace("[^a-zA-Z]","",$student).".pdf";
   $pdf->Output("/home/nsaahome/attachments/$filename1", "F");
   header("Location:attachments.php?session=$session&filename=$filename1");

?>
