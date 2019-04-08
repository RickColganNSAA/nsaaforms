<?php
/*********************************************
createcertificate.php
Dynamically Create PDF for JO Certificate (TOP 3 IN PRELIM RESULTS)
4/9/13
Author Ann Gaffigan
**********************************************/
require '../functions.php';
require '../../calculate/functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//include PDF creation tool:
require_once('../../tcpdf/tcpdf.php');

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
$sid=GetSID2($school,'jo');
if($school=="Test's School") $sid=216;
$school2=addslashes($school);

if(!$state)	//SHOW LIST OF CERTIFICATES (IF $state JUST GENERATE THE CERT)
{
echo $init_html;
echo GetHeader($session);
//IF SCHOOL HAS SOMEONE IN THE TOP 3 IN AN EVENT, ALLOW THEM TO DOWNLOAD A CERTIFICATE:
$sql="SELECT t1.*,t2.category FROM joentries AS t1,jocategories AS t2 WHERE t1.catid=t2.id AND t1.sid='$sid' AND t1.classrank>=1 AND t1.classrank<=3 ";
if($level!=1) $sql.="AND t2.webapproved>0 ";
$sql.="ORDER BY t2.category,t1.classrank";
$result=mysql_query($sql);
//echo $sql;
echo mysql_error();
$curcat="";
if(mysql_num_rows($result)==0)
{
   echo "<br><h2>NSAA Journalism Contest Certificates:</h2>";
   echo "<p>At this time, there are no certificates available to download for students at your school.</p>";
   echo "<p><a href=\"../welcome.php?session=$session\">Return Home</a></p>";
}	//END IF NO STUDENTS TO SHOW
else
{
   echo "<br><h2>NSAA Journalism Contest Certificates:</h2>";
   //LEFT COLUMN: Top 3 in Prelims
   echo "<table cellspacing=0 cellpadding=5><tr align=left valign=top><td width='350px'>";
   echo "<h3>PRELIMINARIES:</h3><p>The following students from your school placed in the top three in Class ".GetClass($sid,'jo')." in the Preliminary Round of Judging. Click on a student's name to download their certificate in PDF format.</p>";
   while($row=mysql_fetch_array($result))
   {
   if($curcat!=$row[category])
   {
      if($curcat!='') echo "</ul>";
      echo "<p style=\"text-align:left;\"><b>$row[category]:</b></p><ul>";
      $curcat=$row[category];
   }
   for($i=1;$i<=6;$i++)
   {
      if($i==1) $var="studentid";
      else $var="studentid".$i;
      if($row[$var]>0)	//MAKE A CERT
      {
   // create new PDF document
   $pdf = new TCPDF("L", PDF_UNIT, "LETTER", true);
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   $pdf->SetMargins(0,0);
   $pdf->SetAutoPageBreak(TRUE, 1);
   $pdf->setLanguageArray($l);
   $pdf->AddPage();

   //Get Rid of Line at Top
   $pdf->setPrintFooter(false);
   $pdf->SetFillColor(255,255,255);
   $pdf->writeHTMLCell("280","50",0,0,"",0,1,1,true,"C");

   $pdf->Image("../../images/JOCertBack.png",3,3,274,210,'','','',false,72,'',false,false,0,false,false,true);
   $pdf->setPageMark();	//MAKES ABOVE IMAGE A BACKGROUND IMAGE
   $y=20; $x=49;
   $pdf->SetFont("times","I","22");
   $pdf->writeHTMLCell("280","",0,$y,"Nebraska High School Press Association",0,0,0,true,"C");
   $y+=8;
   $pdf->SetFont("times","I","18");
   $pdf->writeHTMLCell("280","",0,$y,"and the",0,0,0,true,"C");
   $y+=6;
   $pdf->SetFont("times","I","22");
   $pdf->writeHTMLCell("280","",0,$y,"Nebraska School Activities Association",0,0,0,true,"C");
   $y+=8;
   $pdf->SetFont("times","I","18");
   $pdf->writeHTMLCell("280","",0,$y,"is proud to award:",0,0,0,true,"C");
   $student=GetStudentInfo($row[$var],FALSE);
   $namesize=45;
   if(strlen($student)>=22) $namesize=35;
   else if(strlen($student)>=16) $namesize=40;
   $pdf->SetFont("dejavuserifb","",$namesize);
   $y+=18;
   $pdf->writeHTMLCell("280","",0,$y,"$student",0,0,0,true,"C");
   $pdf->SetFont("dejavuserifb","",35);
   $y+=20; $y=$pdf->GetY(); $y+=20;
   $schoolname=GetSchoolName($sid,'jo');
   $pdf->writeHTMLCell("280","",0,$y,"$schoolname",0,0,0,true,"C");
   $y+=20;
   $pdf->SetFont("times","B","24");
   if($row[classrank]==1) $place="First";
   else if($row[classrank]==2) $place="Second";
   else $place="Third";
   $class=GetClass($sid,'jo');
   $pdf->writeHTMLCell("280","",0,$y,"$place<br>in<br>$row[category]<br>Class $class<br>".date("Y"),0,0,0,true,"C");

   //NHSPA EXEC DIRECTOR SIGNATURE:
   $x=40; $y=156; $x2=115;
   //$pdf->Line($x, $y, $x2, $y);
   $pdf->SetFont("helvetica","","14");
   $pdf->Image("../../images/nhspadirectorsig.png",$x,$y,70);
   $pdf->SetFont("helvetica","","14");
   $y=175;
   $pdf->writeHTMLCell("65","",$x,$y,"NHSPA Executive Director",0,0,0,true,"C");

   //NSAA EXEC DIRECTOR SIGNATURE:
   $x=170; $y=155; $x2=115;
   $pdf->Image("../../images/tenopirsig.png",$x,$y,65);
   $pdf->SetFont("helvetica","","14");
   $y=175;
   $pdf->writeHTMLCell("65","",$x,$y,"NSAA Executive Director",0,0,0,true,"C");

   $filename1="certificates/$row[id]_$i";
   $pdf->Output("$filename1.pdf", "F");

   echo "<li><a href=\"$filename1.pdf\" target=\"_blank\" class=\"small\">$student, $place</a></li>";
   }//END IF STUDENTID>0
   }//END FOR EACH STUDENTID
   }//end for each student in top 3
   echo "</ul></td>";
  
   //RIGHT COLUMN: STATE QUALIFIERS
   echo "<td width='75px'>&nbsp;&nbsp;&nbsp;&nbsp;</td><td width='350px'>";
   $curcat="";
   echo "<h3>STATE QUALIFIERS:</h3>";
   $duedate=GetDueDate("jo");
   if(!PastDue($duedate,3))
      echo "<div class='alert'><p>Your state qualifier certificates will be available 3-4 days after the due date for the state entry form.</p></div>";
   else
   {
   echo "<p>The following students from your school have qualified for the State Journalism Contest.</p>";
   $sql="SELECT t3.category,t1.* FROM joentries AS t1, joqualifiers AS t2, jocategories AS t3 WHERE t1.id=t2.entryid AND t1.catid=t3.id AND t1.sid='$sid' ORDER BY t3.category";
   $sql="SELECT t1.* FROM jo AS t1,eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$school2' OR t1.co_op='$school2') ORDER BY t2.last,t2.first";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))	//FOR EACH ENTRY FOR THIS SCHOOL THAT QUALIFIED:
   {
	/*
      if($curcat!=$row[category])
      {
         if($curcat!='') echo "</ul>";
         echo "<p><b>$row[category]:</b></p><ul>";
         $curcat=$row[category];
      }
	*/
      for($i=1;$i<=2;$i++)
      {
	 $field="event".$i;
	 if($row[$field]!='')
	 {
	    echo "<li><a class='small' href=\"createcertificate.php?session=$session&state=1&event=".$row[$field]."&studentid=".$row[student_id]."\" target=\"_blank\">".GetStudentInfo($row[student_id])."</a> (".$row[$field].")</li><br>";
         } 
      }
   }
   echo "</ul>";
   } //END IF PAST DUE 3 DAYS
   echo "</td></tr></table>";
}//IF there are students in top 3
echo $end_html;
}	//END IF NOT $state
else	//GENERATE STATE QUALIFIER CERTIFICATE
{
   $pdf = new TCPDF("L", PDF_UNIT, "LETTER", true);
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   $pdf->SetMargins(0,0);
   $pdf->SetAutoPageBreak(TRUE, 1);
   $pdf->setLanguageArray($l);
   $pdf->AddPage();

   //Get Rid of Line at Top
   $pdf->setPrintFooter(false);
   $pdf->SetFillColor(255,255,255);
   $pdf->writeHTMLCell("280","50",0,0,"",0,1,1,true,"C");

   $pdf->Image("../../images/JOCertBack.png",3,3,274,210,'','','',false,72,'',false,false,0,false,false,true);
   $pdf->setPageMark(); //MAKES ABOVE IMAGE A BACKGROUND IMAGE
   $y=22; $x=49;
   $pdf->SetFont("times","I","22");
   $pdf->writeHTMLCell("280","",0,$y,"Nebraska High School Press Association",0,0,0,true,"C");
   $y+=8;
   $pdf->SetFont("times","I","18");
   $pdf->writeHTMLCell("280","",0,$y,"and the",0,0,0,true,"C");
   $y+=6;
   $pdf->SetFont("times","I","22");
   $pdf->writeHTMLCell("280","",0,$y,"Nebraska School Activities Association",0,0,0,true,"C");
   $y+=8;
   $pdf->SetFont("times","I","18");
   $pdf->writeHTMLCell("280","",0,$y,"are proud to award:",0,0,0,true,"C");
   $namesize=45;
   $pdf->SetFont("dejavuserifb","",$namesize);
   $y+=20;

   if($entryid)
   {
      $sql="SELECT t1.school,t2.* FROM eligibility AS t1,jo AS t2 WHERE t1.id=t2.student_id AND t2.id='$entryid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $sid=GetSID2($row[school],'jo');
      $student=GetStudentInfo($row[student_id],FALSE);
   }
   else //$studentid
   {
      $sql="SELECT school FROM eligibility WHERE id='$studentid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $sid=GetSID2($row[school],'jo');
      $student=GetStudentInfo($studentid,FALSE);
   }

   $pdf->writeHTMLCell("280","",0,$y,"$student",0,0,0,true,"C");
   $pdf->SetFont("dejavuserifb","",35);
   $y+=20; $y=$pdf->GetY(); $y+=20;
   $schoolname=GetSchoolName($sid,'jo');
   $pdf->writeHTMLCell("280","",0,$y,"$schoolname",0,0,0,true,"C");
   $y+=22;
   $pdf->SetFont("times","B","24");
   $pdf->writeHTMLCell("280","",0,$y,"State Qualifier<br>in<br>$event",0,0,0,true,"C");

   //NHSPA EXEC DIRECTOR SIGNATURE:
   $x=40; $y=156; $x2=115;
   //$pdf->Line($x, $y, $x2, $y);
   $pdf->SetFont("helvetica","","14");
   $pdf->Image("../../images/nhspadirectorsig.png",$x,$y,70);
   $pdf->SetFont("helvetica","","14");
   $y=175;
   $pdf->writeHTMLCell("65","",$x,$y,"NHSPA Executive Director",0,0,0,true,"C");

   //NSAA EXEC DIRECTOR SIGNATURE:
   $x=170; $y=155; $x2=115;
   $pdf->Image("../../images/tenopirsig.png",$x,$y,65);
   $pdf->SetFont("helvetica","","14");
   $y=175;
   $pdf->writeHTMLCell("65","",$x,$y,"NSAA Executive Director",0,0,0,true,"C");

	//OUTPUT:
   $filename1="certificates/".preg_replace("/[^a-zA-Z]/","",$student).preg_replace("/[^a-zA-Z]/","",$event)."STATE";
   $pdf->Output("$filename1.pdf", "F");
   header("Location:".$filename1.".pdf");
}
?>
