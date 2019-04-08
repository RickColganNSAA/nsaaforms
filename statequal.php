<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
if($save)
{
   $field="state".$activitych;
   for($i=0;$i<count($schools);$i++)
   {
      $school2=addslashes($schools[$i]);
      $sql="UPDATE headers SET $field='$check[$i]' WHERE school='$school2'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;

echo "<br><form method=post action=\"statequal.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table width=350 class=nine cellspacing=0 cellpadding=4><caption><b>Manage State Qualifiers (by Activity):</b></caption>";
echo "<tr align=center><td colspan=2>Please select an activity:&nbsp;";
echo "<select name=\"activitych\" onchange=\"submit();\"><option value=''>~</option>";
for($i=0;$i<count($stateacts);$i++)
{
   echo "<option value=\"$stateacts[$i]\"";
   if($activitych==$stateacts[$i]) echo " selected";
   echo ">".GetActivityName($stateacts[$i])."</option>";
}
echo "</select></td></tr>";
if($activitych && $activitych!='')
{
   $actname=GetActivityName($activitych);
   if($save)
      echo "<tr align=center><td><font style=\"color:blue\">The <b>$actname</b> state qualifiers have been saved.</font></td></tr>";
   echo "<tr align=left><td colspan=2><i>The following schools are registered for <b>$actname</b>.  Please check the box in the right-hand column if that school has <u>qualified for State</u> in <b>$actname</b>.</td></tr>";
   echo "<tr align=center><td colspan=2><a class=small target=new href=\"statequalexport.php?session=$session&activitych=$activitych\">Mailing Export: $actname State Qualifying Schools</a></td></tr>";
   $schooltbl=GetSchoolTable($activitych);
   $sql="SELECT t1.class,t2.* FROM $schooltbl AS t1,headers AS t2 WHERE t1.mainsch=t2.id ORDER BY t1.class,t2.school";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      //if(IsRegistered($row[school],$activitych))
      //{
	 echo "<input type=hidden name=\"schools[$ix]\" value=\"$row[school]\">";
	 echo "<tr align=left";
         if($ix%2==0) echo " bgcolor=\"#E0E0E0\"";
    	 echo "><td>$row[school]</td><td>$row[class]</td>";
         echo "<td align=center><input type=checkbox name=\"check[$ix]\" value='x'";
	 $field="state".$activitych;
	 if($row[$field]=='x') echo " checked";
	 echo "></td></tr>";
	 $ix++;
         if($ix%15==0)
            echo "<tr align=center><td colspan=2><input type=submit name=save value=\"Save\"></td></tr>";
      //}
   }
   if($ix%15!=0)
      echo "<tr align=center><td colspan=2><input type=submit name=save value=\"Save\"></td></tr>";
}

echo "</table>";
echO "</form>";

echo $end_html;
?>
