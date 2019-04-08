<?php
//swstate.php: NSAA SW State Admin Page: look at a school's state form or get full sw state report (.html)

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

//get list of swimming schools
$sql="SELECT school, hytekabbr,coops FROM swschool ORDER BY school";
$result=mysql_query($sql);
$i=0;
$sch=array();
while($row=mysql_fetch_array($result))
{
   $sch[name][$i]=$row[0];
   $sch[abbr][$i]=$row[1];
   $sch[coops][$i]=$row[2];
   $i++;
}

if($submit=="Go")
{
   $schch2=split(",",$schch);
   $school_ch="";
   $coops=split("/",$sch[coops][$schch2[1]]);
   if(count($coops)>0)
      $school_ch=$coops[0];
   if($school_ch=="") 
      $school_ch=$schch2[0];
   $page="sw_state_edit_".$gender.".php?session=$session&school_ch=".$school_ch;
   if($gender && $schch!="Choose School")
   {
      header("Location:sw/$page");
      exit();
   }
   else
   {
      if($schch=="Choose School")
	 $error="school";
      else
         $error="gender";
   }
}

echo $init_html;
echo $header;

echo "<br><br>";
echo "<form method=post action=\"swstate.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table cellspacing=0 cellpadding=10><caption><b>State Swimming Admin:<br><br></b></caption>";
echo "<tr align=left><td><ul><li>Choose a <b>school and gender</b> to view a specific state entry form:<br>";
if($error=="school")
{
   echo "<br><font style=\"color:red\">Please specify a school</font>";
}
else if($error=="gender")
{
   echo "<br><font style=\"color:red\">Please specify a gender</font>";
}
echo "<br><select name=schch><option>Choose School";

for($i=0;$i<count($sch[name]);$i++)
{
   echo "<option value=\"".$sch[name][$i].",$i\"";
   if($schch==$sch[name][$i].",$i") echo " selected";
   echo ">".$sch[name][$i];
}
echo "</select><br>";
echo "<input type=radio name=gender value='b'>Boys&nbsp;&nbsp;&nbsp;";
echo "<input type=radio name=gender value='g'>Girls<br>";
echo "<br><input type=submit name=submit value=\"Go\"></td></tr>";
echo "<tr align=left><td><ul><li><a href=\"sw/stateadmin.php?session=$session\">Submitted State Entry Forms</a></li></ul></td></tr>";
echo "<tr align=left><td><ul><li>Complete state entry form reports:<br><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"sw/statereport.php?session=$session&gender=b\">Boys</a>&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"sw/statereport.php?session=$session&gender=g\">Girls</a></td></tr>";
echo "<tr align=left><td><ul><li><a href=\"swstandards.php?session=$session\">Edit Swimming Qualifying Standards</a></li></ul></td></tr>";

echo "</table></form>";

echo $end_html;
?>
