<?php
/*********************************************
createcertificate.php
Dynamically Create PDF Speech Award Certificate
Copied from mu folder & adapted on 4/20/10
Author Ann Gaffigan
**********************************************/
require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';

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
}
//else school is given
if(!$school || $school=='')
{
   echo "ERROR: No School Indicated";
   exit();
}
$sid=GetSID2($school,'sp');
$mainschool=GetSchoolName($sid,'sp');

$events[short]=array("hum","ser","ext","poet","pers","ent","inf","dram","duet");
$events[long]=array("Humorous Interpretation of Prose Literature","Serious Interpretation of Prose Literature","Extemporaneous Speaking","Oral Interpretation of Poetry","Persuasive Speaking","Entertainment Speaking","Informative Public Speaking","Oral Interpretation of Drama","Duet Acting");

if($preview || $download)
{
   //CREATE PDFs: Black & White, then Color
   $orientation="L";
   $pdf = new TCPDF($orientation, PDF_UNIT, PDF_PAGE_FORMAT, true);
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   $pdf->SetMargins(5,5);
   $pdf->SetAutoPageBreak(TRUE, 1);
   $pdf->setLanguageArray($l);
   $pdf->AliasNbPages();
   $pdf->AddPage();
   //$pdf->Rect(7,7,282,196,"","all");
   $pdf->SetFont("helvetica","",14);
   $pdf->Image("../../images/certtopCOLOR.png",49,10,200);
   $pdf->SetXY(10,70);

   if(ereg("Duet",$event))
   {
      if($students1!='') $students.=", $students1";
   }
   else if(ereg("Drama",$event))
   {
      if($students1!='') $students.=", $students1";
      if($students2!='') $students.=", $students2";
      if($students3!='') $students.="<br>$students3";
      if($students4!='') $students.=", $students4";
   }

   $studentstop=90;
   $y=$studentstop-10; $x=49;
   $pdf->SetFont("helvetica","","16");
   $pdf->writeHTMLCell("200","",$x,$y,"This certifies that",0,0,0,true,"C");
   $pdf->SetFont("helvetica","",$fontsize);
   $students=ereg_replace("\"","\"",$students);
   $pdf->writeHTMLCell("200","","49",$studentstop,"<b>$students</b>",0,0,0,true,"C");
   $pdf->SetFont("helvetica","","18");
   $x=49; $y=100;
   if(ereg("Drama",$event)) $y=110;
   $pdf->writeHTMLCell("200","",$x,$y,"$school High School",0,0,0,true,"C");
   $y+=13;
   $pdf->SetFont("helvetica","","16");
   $pdf->writeHTMLCell("200","",$x,$y,"received a <b>SUPERIOR</b> rating in",0,0,0,true,"C");
   $pdf->SetFont("helvetica","","18");
   $y+=10;
   $pdf->writeHTMLCell("200","",$x,$y,"<b>$event</b>",0,0,0,true,"C");
   $y+=10;
   $pdf->SetFont("helvetica","","16");
   $pdf->writeHTMLCell("200","",$x,$y,"at the",0,0,0,true,"C");
   $pdf->SetFont("helvetica","","18");
   $y+=10;
   if($state==1) $html="<b>".date("Y")." STATE SPEECH CHAMPIONSHIP</b>";
   else $html.="<b>".date("Y")." DISTRICT SPEECH TOURNAMENT</b>";
   $pdf->writeHTMLCell("200","",$x,$y,$html,0,0,0,true,"C");
   //TOURNAMENT DIRECTOR SIGNATURE
   $x=50; $y=170; $x2=120; $y2=185;
   if($state==1)
      $pdf->Image("../../images/dvsignature.png",$x,$y,70);
  else 
      $pdf->Line($x, $y2, $x2, $y2);
   $pdf->SetFont("helvetica","","14");
   $y+=15;
   $pdf->writeHTMLCell("65","",$x,$y,"Tournament Director",0,0,0,true,"C");

   if($state==1) $statedist="State";
   else $statedist="District";
   $filename1="certificates/SP".$statedist."Cert".ereg_replace("[^a-zA-Z]","",$school).ereg_replace("[^a-zA-Z]","",$event);
   $pdf->Output("$filename1.pdf", "F");

   if($preview)	//show link to Download or go back and make changes
   {
      echo $init_html;
      echo GetHeader($session);
      echo "<br><br><br><table width='500px' class='nine' cellspacing=2 cellpadding=2><tr align=left><td><b>Your certificate has been created.</b><br><br>To <b><i>preview</b></i> the certificate, click: <a href=\"$filename1.pdf\" target=\"_blank\">Preview</a>.<br><br>If you are <b><i>satisfied</i></b> with the certificate, you can save it to your computer (after opening the Preview, select File->Save As from the browser menu) and/or print the certificate (File->Print).<br><br>If you need to <b><i>make changes</b></i> to the certificate, <a href=\"javascript:history.go(-1);\">Go Back</a> and do so.<br><br><br>If you are ready to start a <b><i>NEW</i></b> certificate, <a href=\"createcertificate.php?school=$school&state=$state&session=$session\">Click Here</a>.</td></tr></table>";
      echo $end_html;
      exit();
   }

}
else
{
   //Get Information to Pre-Populate Form with:
   $school2=addslashes($school); unset($students);

   echo $init_html;
   echo GetHeader($session);
   echo "<br><form method=post action=\"createcertificate.php\">";
   echo "<input type=hidden name=\"session\" value=\"$session\">";
   echo "<input type=hidden name=\"school\" value=\"$school\">";
   echo "<input type=hidden name=\"state\" value=\"$state\">";
   echo "<table class=nine cellspacing=2 cellpadding=5 width='500px'><caption><b>";
   if($state==1) echo "NSAA State Speech Superior Award Certificate:</b><br>";
   else echo "NSAA District Speech Award Certificates:</b><br>";
   if($state==1)
   {
      echo "<div class='alert'><B>INSTRUCTIONS:</b><ul><li>Select the <b>Event</b> to load the names of the students who have been <u>verified by the NSAA</u> as receiving a \"Superior\" rating in that event at the State Speech Tournament.<br><br>(If you do not see the event you are looking for, no students receiving an award for that event have been verified by the NSAA.)</li><li>Then click \"Continue to Preview Certificate.\"</li></ul><b>*** PLEASE NOTE ***</b> The names of the students receiving a \"Superior\" award will not be available until the week after the state meet.</div></caption>";
   }
   else
   {
      echo "<div class='alert'><b>INSTRUCTIONS:</b><ul><li>Select an <b>Event</b>.</li><li>Select the <b>name of the student</b>.</li><li>Click \"Continue to Preview Certificate.\"</li></ul><b>*** PLEASE NOTE ***</b> If a name of a student is incorrect or absent, you must correct it in your school's <a class=small href=\"../eligibility.php?activity_ch=Speech&session=$session\">Eligibility Database.</a></div></caption>";
   }
   echo "<tr align=left><td>School:</td><td>$school</td></tr>";
   echo "<tr align=left><td>Select Event:</td><td>";
   echo "<select name=\"event\" onchange=\"submit();\"><option value=''>Select Event</option>";
   if($state==1)
   {
      $sql="SELECT DISTINCT event FROM nsaaofficials.spsuperior WHERE school='".addslashes($mainschool)."' ORDER BY event";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         echo "<option value=\"$row[event]\"";
         if($event==$row[event]) echo " selected";
         echo ">$row[event]</option>";
      }
   }
   else
   {
      for($i=0;$i<count($events[long]);$i++)
      {
	 echo "<option value=\"".$events[long][$i]."\"";
	 if($event==$events[long][$i]) echo " selected";
	 echo ">".$events[long][$i]."</option>";
      }
   }
   echo "</select>";
   echo "</td></tr>";
   if($event && $event!='')
   {
      if($state==1)
         $sql2="SELECT DISTINCT students FROM nsaaofficials.spsuperior WHERE school='".addslashes($mainschool)."' AND event='$event'";
      else 
         $sql2="SELECT * FROM eligibility WHERE school='$school2' AND sp='x'";
      $result2=mysql_query($sql2);
      echo "<tr align=left valign=top><td>Select Student(s)*:</td><td>";
      if($state==1)
      {
         $i=0;
         while($row2=mysql_fetch_array($result2))
         {
            if($i==0 && (!$students || $students=='')) $students=$row2[students];
            echo "<input type=radio name=\"students\" value=\"$row2[students]\"";
	    if($students==$row2[students]) echo " checked";
            echo "> $row2[students]<br>";
	    $i++;
         }
      }
      else
      {
	 echo "<select name=\"students\"><option value=''>Select Student</option>";
	 $studs=array(); $ix=0;
	 while($row2=mysql_fetch_array($result2))
	 {
	    $name=GetStudentInfo($row2[id],FALSE);
	    echo "<option value=\"$name\"";
	    if($students==$name) echo " selected";
	    echo ">$name</option>";
	    $studs[$ix]=$name; $ix++;
	 }
	 echo "</select><br>";
	 if(ereg("Duet",$event))	$max=2;
	 else if(ereg("Drama",$event))	$max=5;
	 else $max=1;
	 $selectct=1;	//already have one select box in place
	 while($selectct<$max)
	 {
	    $selectname="students".$selectct;
	    echo "<select name=\"$selectname\"><option value=''>Select Student</option>";
	    for($i=0;$i<count($studs);$i++)
	    {
		echo "<option value=\"".$studs[$i]."\"";
	        if($$selectname==$studs[$i]) echo " selected";
		echo ">$studs[$i]</option>";
	    }
	    echo "</select><br>";
	    $selectct++;
	 }
      }
      echo "</td></tr><tr align=left><td colspan=2>*<i> If a name of a student is incorrect, you must correct it in your school's <a href=\"../eligibility.php?activity_ch=Speech&session=$session\">Eligibility Database.</a></i></td></tr>";
      echo "<tr align=center><td colspan=2><input type=submit name=\"preview\" value=\"Continue to Preview Certificate\"></td></tr>";
   }
   else echo "<tr align=center><td colspan=2><i>Please select an event.</i></td></tr>";
   echo "</table></form>";
   echo $end_html;
}
exit();
?>
