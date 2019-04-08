<?php

require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
echo GetHeader($session);
echo "<br><a class=small href=\"cc_main.php?session=$session\">Cross-Country District Results & State Qualfiers MAIN MENU</a><br>";
echo "<br><form method=post action=\"cc_teamreport.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<select name=\"genderclass\" onchange=\"submit();\"><option value=''>Select Gender & Class</option>";
$classes=GetClasses('ccb');
for($i=0;$i<count($classes);$i++)
{
   echo "<option value=\"cc_b;".$classes[$i]."\"";
   if($genderclass=="cc_b;".$classes[$i]) echo " selected";
   echo ">Boys Class ".$classes[$i]."</option>";
}
for($i=0;$i<count($classes);$i++)
{
   echo "<option value=\"cc_g;".$classes[$i]."\"";
   if($genderclass=="cc_g;".$classes[$i]) echo " selected";
   echo ">Girls Class ".$classes[$i]."</option>";
}
echo "</select><br>";
if($genderclass && $genderclass!='')
{
   $temp=split(";",$genderclass);
   $sport=$temp[0]; $class=$temp[1];
   if($sport=="cc_b") { $schooltbl="ccbschool"; $sport2=ereg_replace("_","",$sport); }
   else { $schooltbl="ccgschool"; $sport2=ereg_replace("_","",$sport); }

$sql="DELETE FROM ".$sport."_state_teamquals";
$result=mysql_query($sql);

//get all schools qualifying runners for this class:
$sql="SELECT t1.* FROM ".$sport."_state_team AS t1,$schooltbl AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' AND t1.student_ids IS NOT NULL AND t1.sid!='0' ORDER BY t2.school";
$result=mysql_query($sql);
$sids="";
while($row=mysql_fetch_array($result))
{
   $studs=split(",",$row[student_ids]);
   for($i=0;$i<count($studs);$i++)
   {
      $studs[$i]=trim($studs[$i]);
      if($studs[$i]!='' && $studs[$i]!='0')
      {
         $sql2="SELECT * FROM ".$sport."_state_teamquals WHERE sid='$row[sid]' AND studentid='$studs[$i]'";   
	 $result2=mysql_query($sql2);
         if(mysql_num_rows($result2)==0)      //student not in table yet; INSERT
         {
            $sql3="INSERT INTO ".$sport."_state_teamquals (sid,studentid) VALUES ('$row[sid]','$studs[$i]')";
            $result3=mysql_query($sql3);
         }
      }
   }
}

$sql="SELECT t2.sid,count(t2.sid) AS sidct FROM ".$sport."_state_team AS t1,".$sport."_state_teamquals AS t2 WHERE t1.sid=t2.sid GROUP BY t2.sid ORDER BY sidct ASC ";
$result=mysql_query($sql);
echo mysql_error();
echo "<br><table rows=all frames=all style=\"border:#333333 1px solid;\" cellspacing=0 cellpadding=3>";
echo "<tr align=center><td><b>Qualifying Team</b></td><td><b># of Students</b></td></tr>";
while($row=mysql_fetch_array($result))
{
   echo "<tr><td align=left>".GetSchoolName($row[sid],$sport2,date("Y"))."</td>";
   echo "<td align=center>$row[1]</td></tr>";
}
echo "</table>";
}//end if genderclass selected
echo "</form><br>";
echo "<br><a class=small href=\"cc_main.php?session=$session\">Cross-Country District Results & State Qualfiers MAIN MENU</a><br>";
echo $end_html;

exit();
?>
