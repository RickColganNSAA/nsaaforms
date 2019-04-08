<?php
//submit_sp.php: submit speech dist form

if($save=="Cancel")
{
   header("Location:../welcome.php?session=$session");
   exit();
}

require '../functions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if($school_ch && GetLevel($session)==1)
{
   $school=$school_ch;
}
else
{
   $school=GetSchool($session);
}
$school2=ereg_replace("\'","\'",$school);

//update asst coach info
$asst=ereg_replace("\'","\'",$asst);
$asst=ereg_replace("\"","\'",$asst);
$sql="UPDATE logins SET asst_coaches='$asst' WHERE level=3 AND school='$school2' AND sport='Speech'";
$result=mysql_query($sql);

//fix up class and contest site info
if($class=="Choose") $class="";
$contest_site=ereg_replace("\"","\'",$contest_site);
$contest_site=ereg_replace("\'","\'",$contest_site);
 $emergph=$area.$pre.$post;

//check that school followed rules for speech entry
$hum=0;	//up to 2 entries for Humorous Int of Prose
$ser=0;	//same for Serious Int of Prose
$poet=0;	//up to 2 for Oral Int of Poetry
$pers=0;	//up to 2 for Persuasive Speaking
$ent=0;	//up to 2 for Entertainment Speaking
$ext=0;	//up to 2 for Extemporaneous Speaking
$inf=0;	//up to 2 for Informative Punlic Speaking
$duet1=0;	//2 entries for Duet Acting (2 students)
$duet2=0;
$dram1=0;	//2 entries for Oral Int of Drama (5 students)
$dram2=0;
$cur_student=0;	//up to 2 events per student allowed
for($i=0;$i<count($student);$i++)
{
   $cur_student=0;
   if($drama1[$i]=='y') 
   {
      $cur_student++; $dram1++;
   }
   if($drama2[$i]=='y')
   {
      $cur_student++; $dram2++;
   }
   if($poetry[$i]=='y') 
   {
      $cur_student++; $poet++;
   }
   if($pers_speak[$i]=='y') 
   {
      $cur_student++; $pers++;
   }
   if($inform[$i]=='y') 
   {
      $cur_student++; $inf++;
   }
   if($extemp[$i]=='y') 
   {
      $cur_student++; $ext++;
   }
   if($ent_speak[$i]=='y') 
   {
      $cur_student++; $ent++;
   }
   if($duet_acting1[$i]=='y') 
   {
      $cur_student++; $duet1++;
   }
   if($duet_acting2[$i]=='y')
   {
      $cur_student++; $duet2++;
   }
   if($prose_humor[$i]=='y') 
   {
      $cur_student++; $hum++;
   }
   if($prose_serious[$i]=='y') 
   {
      $cur_student++; $ser++;
   }
   if($cur_student>2) $stud_error=1;
}
//coop-students:
for($i=0;$i<count($coop_student);$i++)
{
   $cur_student=0;
   if($coop_drama1[$i]=='y')
   {
      $cur_student++; $dram1++;
   }
   if($coop_drama2[$i]=='y')
   {
      $cur_student++; $dram2++;
   }
   if($coop_poet[$i]=='y')
   {
      $cur_student++; $poet++;
   }
   if($coop_pers[$i]=='y')
   {
      $cur_student++; $pers++;
   }
   if($coop_inf[$i]=='y')
   {
      $cur_student++; $inf++;
   }
   if($coop_ext[$i]=='y')
   {
      $cur_student++; $ext++;
   }
   if($coop_ent[$i]=='y')
   {
      $cur_student++; $ent++;
   }
   if($coop_duet1[$i]=='y')
   {
      $cur_student++; $duet1++;
   }
   if($coop_duet2[$i]=='y')
   {
      $cur_student++; $duet2++;
   }
   if($coop_hum[$i]=='y')
   {
      $cur_student++; $hum++;
   }
   if($coop_ser[$i]=='y')
   {
      $cur_student++; $ser++;
   }
   if($cur_student>2) $stud_error=1;
}
if($hum>2 || $ser>2 || $poet>2 || $pers>2 || $ent>2 || $ext>2 || $inf>2 || $duet1>2 || $duet2>2 || $dram1>5 || $dram2>5 || $stud_error==1)
{
   echo $init_html;
   echo GetHeader($session);
   echo "<center><br><br><table><caption><b>";
   echo "Your input has the following errors:</b></caption>";
   if($hum>2 || $ser>2 || $poet>2 || $pers>2 || $ent>2 || $ext>2 || $inf>2)
   {
      echo "<tr align=left><td><br>";
      echo "You have checked too many entries for individual events. You may only check 2 entries for each individual event for your school.  Individual events include all of the events except Duet Acting and Drama.</td></tr>";
   }
   if($dram1>5 || $dram2>5 || $duet1>2 || $duet2>2)
   {
      echo "<tr align=left><td><br>";
      echo "You have checked too many entries for group events (Duet Acting and Drama).  You may only check 2 pairs of students for Duet Acting and 2 groups of 5 students for Drama.</td></tr>";
   }
   if($stud_error==1)
   {
      echo "<tr align=left><td><br>";
      echo "You have checked too many events for at least one of your students.  Each student may be entered in up to 2 events.</td></tr>";
   }
   echo "</table><br><a href=\"javascript:history.go(-1)\">Go Back</a>";
   echo "</td></tr></table></body></html>";
   exit();
}

//enter info into db
$emergname=addslashes($emergname);
for($i=0;$i<count($student);$i++)
{
   $sql="SELECT * FROM sp WHERE student_id='$student[$i]'";
   $result=mysql_query($sql);
   
   
   if(mysql_num_rows($result)>0)	//UDPATE
   {
      $sql2="UPDATE sp SET school='$school2',checked='$check[$i]',class_dist='$class',contest_site='$contest_site',drama1='$drama1[$i]',drama2='$drama2[$i]',poetry='$poetry[$i]',pers_speak='$pers_speak[$i]',
					inform='$inform[$i]',extemp='$extemp[$i]',ent_speak='$ent_speak[$i]',duet_acting1='$duet_acting1[$i]',duet_acting2='$duet_acting2[$i]',prose_humor='$prose_humor[$i]',prose_serious='$prose_serious[$i]',emergname='$emergname',emergph='$emergph' WHERE student_id='$student[$i]'";
   }
   else					//INSERT
   {
      //$sql2="INSERT INTO sp (school,student_id,checked,class_dist,contest_site,drama1,drama2,poetry,pers_speak,	  inform,extemp,ent_speak,duet_acting1,duet_acting2,prose_humor,prose_serious,emergname,emergph) VALUES ('$school2','$student[$i]','$check[$i]','$class','$contest_site','$drama1[$i]','$drama2[$i]','$poetry[$i]','$pers_speak[$i]','$inform[$i]','$extemp[$i]','$ent_speak[$i]','$duet_acting1[$i]','$duet_acting2[$i]','$prose_humor[$i]','$prose_serious[$i]','$emergname','$emergph')";
		$sql2="insert into sp SET school='$school2',checked='$check[$i]',class_dist='$class',contest_site='$contest_site',drama1='$drama1[$i]',drama2='$drama2[$i]',poetry='$poetry[$i]',pers_speak='$pers_speak[$i]',
					inform='$inform[$i]',extemp='$extemp[$i]',ent_speak='$ent_speak[$i]',duet_acting1='$duet_acting1[$i]',duet_acting2='$duet_acting2[$i]',prose_humor='$prose_humor[$i]',prose_serious='$prose_serious[$i]',emergname='$emergname',emergph='$emergph' , student_id='$student[$i]'";
   }
   $result=mysql_query($sql2);
if(mysql_error()) echo $sql2."<br>".mysql_error()."<br><br>";
}
//enter co_op students' info
for($i=0;$i<count($coop_student);$i++)
{
   $sql="UPDATE sp SET co_op='$school2',checked='$coop_check[$i]',class_dist='$class',contest_site='$contest_site',drama1='$coop_drama1[$i]',drama2='$coop_drama2[$i]',poetry='$coop_poet[$i]',pers_speak='$coop_pers[$i]',inform='$coop_inf[$i]',extemp='$coop_ext[$i]',ent_speak='$coop_ent[$i]',duet_acting1='$coop_duet1[$i]',duet_acting2='$coop_duet2[$i]',prose_humor='$coop_hum[$i]',prose_serious='$coop_ser[$i]' WHERE student_id='$coop_student[$i]'";
   $result=mysql_query($sql);
}
if(!$save || $save=="Save & Keep Editing")
{
   header("Location:edit_sp.php?session=$session&school_ch=$school_ch");
}
else if($save=="Save & View Form")
{
   header("Location:view_sp.php?session=$session&school_ch=$school_ch");
}
exit();
?>
