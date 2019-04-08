<?php
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

$tables=array("headers","hostapp_ba","hostapp_bb_b","hostapp_bb_g","hostapp_cc","hostapp_go_b","hostapp_go_g","hostapp_pp","hostapp_sb","hostapp_so","hostapp_sp","hostapp_tr","hostapp_vb","hostapp_wr","logins","messages","registration","declaration","eligibility");

if($submit=="Merge")
{
   $headsch=ereg_replace("\'","\'",$headsch);
   $othersch=ereg_replace("\'","\'",$othersch);
   for($i=0;$i<count($tables);$i++)
   {
      $sql="UPDATE $tables[$i] SET school='$headsch-$othersch' WHERE school='$headsch'";
      $result=mysql_query($sql);
      if($tables[$i]=="eligibility")
	 $sql="UPDATE $tables[$i] SET school='$headsch-$othersch' WHERE school='$othersch'";
      else
         $sql="DELETE FROM $tables[$i] WHERE school='$othersch'";
      $result=mysql_query($sql);
   }
   $change=1;
}
else if($submit=="Change Name")
{
   $schname=ereg_replace("\'","\'",$schname);
   for($i=0;$i<count($tables);$i++)
   {
      $newname=ereg_replace("\'","\'",$newname);
      $schname=ereg_replace("\'","\'",$schname);
      $sql="UPDATE $tables[$i] SET school='$newname' WHERE school='$schname'";
      $result=mysql_query($sql);
   }
   $change=1;
}
else if($submit=="Delete School")
{
   $delsch=ereg_replace("\'","\'",$delsch);
   for($i=0;$i<count($tables);$i++)
   {
      $delsch=ereg_replace("\'","\'",$delsch);
      $sql="DELETE FROM $tables[$i] WHERE school='$delsch'";
      $result=mysql_query($sql);
   }
   $change=1;
}
   
echo $init_html;
echo $header;
?>
<center><br><br><table>
<caption align=left><b>Please use one of the following options to manage the NSAA schools:</b></caption>
<?php
if($change==1)
{
   echo "<tr align=center><th><font style=\"color:red\">The specified changes have been made.</font></th></tr>";
}
?>
<tr align=left><th>
<form method=post action="changeschool.php">
<input type=hidden name=session value=<?php echo $session; ?>>
<ul>
<li>Merge 2 existing schools into one school:<br>
Head school:&nbsp;&nbsp;&nbsp;
<?php
$schools=array();
$ix=0;
$sql="SELECT school FROM headers ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $schools[$ix]=$row[0];
   $ix++;
}
echo "<select name=headsch><option>Choose School";
for($i=0;$i<count($schools);$i++)
{
   echo "<option>$schools[$i]";
}
echo "</select><br>Other School:&nbsp;&nbsp;&nbsp;";
echo "<select name=othersch><option>Choose School";
for($i=0;$i<count($schools);$i++)
{
   echo "<option>$schools[$i]";
}
echo "</select><br><input type=submit name=submit value=\"Merge\"></form></li>";

//Change a school name:
echo "<form method=post action=\"changeschool.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<li>Change an existing school's name:<br>";
echo "Current Name: <select name=schname><option>Choose School";
for($i=0;$i<count($schools);$i++)
{
   echo "<option>$schools[$i]";
}
echo "</select><br>";
echo "New Name: <input type=text name=newname size=40><br>";
echo "<input type=submit name=submit value=\"Change Name\"></form></li>";

//Delete a school:
echo "<form method=post action=\"changeschool.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<li>Delete an existing school from the NSAA database:<br>";
echo "<select name=delsch><option>Choose School";
for($i=0;$i<count($schools);$i++)
{
   echo "<option>$schools[$i]";
}
echo "</select>&nbsp;<input type=submit name=submit value=\"Delete School\">";
echo "</form></li>";
echo "</ul></th></tr></table>";

echo $end_html;
?>
