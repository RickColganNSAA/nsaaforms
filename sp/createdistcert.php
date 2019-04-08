<?php
/*********************************************
createdistcert.php
Dynamically Create PDF Speech Award Certificates for Districts Schools
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
   $schoolid=GetSchoolID2($school);
}
//else school is given
if(!$school || $school=='')
{
   echo "ERROR: No School Indicated";
   exit();
}

$events[short]=array("hum","ser","ext","poet","pers","ent","inf","dram","duet");
$events[long]=array("Humorous Interpretation of Prose Literature","Serious Interpretation of Prose Literature","Extemporaneous Speaking","Oral Interpretation of Poetry","Persuasive Speaking","Entertainment Speaking","Informative Public Speaking","Oral Interpretation of Drama","Duet Acting");

if(!$distid)
{
    //Get Host ID
    $sql="SELECT t1.id FROM logins AS t1, headers AS t2 WHERE t1.school=t2.school AND t2.id='$schoolid' AND t1.level=2";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $hostid=$row[id];
 
    //Get District this School is Hosting
    $sql="SELECT * FROM $db_name2.spdistricts WHERE hostid='$hostid'";
}
else
    $sql="SELECT * FROM $db_name2.spdistricts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class=$row['class']; $district=$row[district];
$distid=$row[id]; 
$sids=split(",",$row[sids]);
$spschs[sid]=array(); $spschs[school]=array();
for($i=0;$i<count($sids);$i++)
{
    $spschs[sid][$i]=trim($sids[$i]);
    $spschs[school][$i]=GetSchoolName($spschs[sid][$i],'sp');
}

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
   if($break>0)	//set up line breaks between names
   {
      $studs=split(",",$students);
      $students="";
      for($i=0;$i<count($studs);$i++)
      {
	 if(($i%$break)==0 && $i>0) $students.="<br>";
         $students.=trim($studs[$i]).", ";
      }
      if($students!='') $students=substr($students,0,strlen($students)-2);
      $studentstop-=ceil(count($studs)/3);
   }
   $y=$studentstop-10; $x=49;
   $pdf->SetFont("helvetica","","16");
   $pdf->writeHTMLCell("200","",$x,$y,"This certifies that",0,0,0,true,"C");
   $pdf->SetFont("helvetica","",$fontsize);
   $pdf->writeHTMLCell("200","","49",$studentstop,"<b>$students</b>",0,0,0,true,"C");
   $pdf->SetFont("helvetica","","18");
   $x=49; $y=100;
   if(ereg("Drama",$event)) $y=110;
   $school=GetSchoolName($sid,'sp');
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
   $html.="<b>".date("Y")." DISTRICT SPEECH TOURNAMENT</b>";
   $pdf->writeHTMLCell("200","",$x,$y,$html,0,0,0,true,"C");
   //TOURNAMENT DIRECTOR SIGNATURE
   $x=50; $y=170; $x2=120; $y2=185;
  
      $pdf->Line($x, $y2, $x2, $y2);
   $pdf->SetFont("helvetica","","14");
   $y+=15;
   $pdf->writeHTMLCell("65","",$x,$y,"Tournament Director",0,0,0,true,"C");

   $statedist="District";
   $filename1="certificates/SP".$statedist."Cert".ereg_replace("[^a-zA-Z]","",$school).ereg_replace("[^a-zA-Z]","",$event);
   $pdf->Output("$filename1.pdf", "F");

   if($preview)	//show link to Download or go back and make changes
   {
      echo $init_html;
      echo GetHeader($session);
      echo "<br><br><br><table width='500px' class='nine' cellspacing=2 cellpadding=2><tr align=left><td><b>Your certificate has been created.</b><br><br>To <b><i>preview</b></i> the certificate, click: <a href=\"$filename1.pdf\" target=\"_blank\">Preview</a>.<br><br>If you are <b><i>satisfied</i></b> with the certificate, you can save it to your computer (after opening the Preview, select File->Save As from the browser menu) and/or print the certificate (File->Print).<br><br>If you need to <b><i>make changes</b></i> to the certificate, <a href=\"javascript:history.go(-1);\">Go Back</a> and do so.<br><br><br>If you are ready to start a <b><i>NEW</i></b> certificate, <a href=\"createdistcert.php?distid=$distid&school=$school&session=$session\">Click Here</a>.</td></tr></table>";
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
   echo "<br><form method=post action=\"createdistcert.php\">";
   echo "<input type=hidden name=\"session\" value=\"$session\">";
   echo "<input type=hidden name=\"distid\" value=\"$distid\">";
   echo "<input type=hidden name=\"school\" value=\"$school\">";
   echo "<table class=nine cellspacing=2 cellpadding=5 width='500px'><caption><b>";
   echo "NSAA District Speech Award Certificates:</b><br>DISTRICT $class-$district<br><br>";
   
      echo "<div class='alert'><b>INSTRUCTIONS:</b><ul><li>Select a <b>School</b>.</li><li>Select the <b>name of the student</b>.</li><li>Select an <b>Event</b>.</li><li>Click \"Continue to Preview Certificate.\"</li></ul><b>*** PLEASE NOTE ***</b> If a name of a student is incorrect or absent, you must correct it in your school's <a class=small href=\"../eligibility.php?activity_ch=Speech&session=$session\">Eligibility Database.</a></div></caption>";
  
    echo "<tr align=left><td>School:</td><td><select onchange=\"submit();\" name=\"sid\"><option value='0'>Select School</option>";
    for($i=0;$i<count($spschs[sid]);$i++)
    {
       echo "<option value='".$spschs[sid][$i]."'";
       if($sid==$spschs[sid][$i]) echo " selected";
       echo ">".$spschs[school][$i]."</option>";
    }
    echo "</select></td></tr>";
	/*
    if($sid)
    {
       $sql="SELECT * FROM spschool WHERE sid='$sid'";
       $result=mysql_query($sql);
       $row=mysql_fetch_array($result);
       $sql2="SELECT DISTINCT t1.id,t1.first,t1.last FROM eligibility AS t1,sp AS t2,headers AS t3 WHERE t1.id=t2.student_id AND t1.school=t3.school AND (t3.id='$row[mainsch]' OR ";
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
	*/
   echo "<tr align=left><td>Select Event:</td><td>";
   echo "<select name=\"event\" onchange=\"submit();\"><option value=''>Select Event</option>";
      for($i=0;$i<count($events[long]);$i++)
      {
	 echo "<option value=\"".$events[long][$i]."\"";
	 if($event==$events[long][$i]) echo " selected";
	 echo ">".$events[long][$i]."</option>";
      }
   echo "</select>";
   echo "</td></tr>";
   if($event && $event!='' && $sid)
   {
      $sql2="SELECT * FROM spschool WHERE sid='$sid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
     
      $sql2="SELECT t1.* FROM eligibility AS t1,headers AS t2 WHERE t1.school=t2.school AND (";
      $sql2.="t2.id='$row2[mainsch]'";
      if($row2[othersch1]>0) $sql2.=" OR t2.id='$row2[othersch1]'";
      if($row2[othersch2]>0) $sql2.=" OR t2.id='$row2[othersch2]'";
      if($row2[othersch3]>0) $sql2.=" OR t2.id='$row2[othersch3]'";
      $sql2.=") AND t1.sp='x'";
      $result2=mysql_query($sql2);
      echo "<tr align=left valign=top><td>Select Student(s)*:</td><td><select name=\"students\"><option value=''>Select Student</option>";

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
         if(ereg("Duet",$event))        $max=2;
         else if(ereg("Drama",$event))  $max=5;
         else $max=1;
         $selectct=1;   //already have one select box in place
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
      

      echo "</td></tr><tr align=left><td colspan=2>*<i> If a name of a student is incorrect, you must correct it in your school's <a href=\"../eligibility.php?activity_ch=Speech&session=$session\">Eligibility Database.</a></i></td></tr>";
      echo "<tr align=center><td colspan=2><input type=submit name=\"preview\" value=\"Continue to Preview Certificate\"></td></tr>";
   }
   else echo "<tr align=center><td colspan=2><i>Please select an event.</i></td></tr>";
   echo "</table></form>";
   echo $end_html;
}
exit();
?>
