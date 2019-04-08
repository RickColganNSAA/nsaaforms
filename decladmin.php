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

if($clear==1)
{
   $schch="Show Summary";
   $sql="DELETE FROM declaration";
   $result=mysql_query($sql);
}

$fallact=array("fb11","vb","fb8","go_g","fb6","te_b","cc_b","sb","cc_g","pp");
$fallactlong=array("Football 11-Man","Volleyball","Football 8-Man","Girls Golf","Football 6-Man","Boys Tennis","Boys Cross-Country","Softball","Girls Cross-Country","Play Production");

if($save=="Save Changes")
{
   $schch2=ereg_replace("\'","\'",$schch);
   $sql0="SELECT * FROM declaration WHERE school='$schch2'";
   $result0=mysql_query($sql0);
   if(mysql_num_rows($result0)>0)
   {
      $sql="UPDATE declaration SET ";
      for($i=0;$i<count($fallact);$i++)
      {
         $sql.=$fallact[$i]."='$declare[$i]', ";
      }
      $sql=substr($sql,0,strlen($sql)-2);
      $sql.=" WHERE school='$schch2'";
      $result=mysql_query($sql);
   }
   else
   {
      $sql="INSERT INTO declaration (school,";
      for($i=0;$i<count($fallact);$i++)
      {
         $sql.=$fallact[$i].",";
      }
      $sql=substr($sql,0,strlen($sql)-1);
      $sql.=") VALUES ('$schch2',";
      for($i=0;$i<count($fallact);$i++)
      {
         $sql.="'$declare[$i]',";
      }
      $sql=substr($sql,0,strlen($sql)-1).")";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;

echo "<br>";
echo "<a class=small target=new href=\"declexport.php?session=$session\">Click Here to Export the Declarations Table (Excel file)</a><br><br>";
echo "<form method=post action=\"decladmin.php\">";
echo "<input type=hidden name=session value=$session>";
   $sql="SELECT duedate FROM misc_duedates WHERE sport='declaration'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $date=split("-",$row[0]);
   $duedate2="$date[1]/$date[2]/$date[0]";
echo "<table style=\"width:600px;\"><caption><b>NSAA Declarations:</b><p>Declarations currently have a due date of <b>$duedate2</b>. <a href=\"duedates.php?session=$session&table=misc_duedates\">Edit this Due Date HERE</a></p><p><i>Please choose a school and click \"Go\" OR choose \"Summary\" to see a summary of the declarations for all schools</i></p></caption>";
echo "<tr align=center><td><select class=small name=schch><option>Show Summary</option>";
$sql="SELECT school FROM headers ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option";
   if($row[0]==$schch) echo " selected";
   echo ">$row[0]</option>";
}
echo "</select>&nbsp;<input type=submit name=go value=\"Go\"></td></tr>";
if($schch=="Show Summary")
{
   if($clear==1)
      echo "<tr align=center><td><div class='alert'>The declarations have been cleared from the database.</div></td></tr>";
   else
      echo "<tr align=left><td><p>To CLEAR OUT all declarations in the database, <a href=\"decladmin.php?clear=1&session=$session\" onClick=\"return confirm('Are you sure you want to clear out all submitted declarations?');\">CLICK HERE</a>.</p></td></tr>";
   echo "<tr align=center><td><br><table cellspacing=0 cellpadding=5 frame=all rules=all style=\"width:100%;border:#808080 1px solid;\">";
   echo "<tr align=center><td><b>Sport</b></td><td><b>Total<br>Declarations</b></td><td><b>List of Schools</b></td></tr>";
   for($i=0;$i<count($fallact);$i++)
   {
      echo "<tr align=left><td><b>$fallactlong[$i]</td>";
      $sql="SELECT id FROM declaration WHERE $fallact[$i]='y'";
      $result=mysql_query($sql);
      $ct=mysql_num_rows($result);
      echo "<td>$ct</td>";
      echo "<td><a href='#' onclick=\"window.open('decllist.php?session=$session&sport=".$fallact[$i]."','decllist','menubar=no,location=no,scrollbars=yes,height=600,width=300');\" class=small>Click to see list of schools</a></td></tr>";
   }
   echo "</table></td></tr>";

   //now show list of schools who have not checked ANY SPORT
   $sql="SELECT * FROM declaration WHERE ";
   for($i=0;$i<count($fallact);$i++)
   {
      $sql.="$fallact[$i]!='y' AND ";
   }
   $sql=substr($sql,0,strlen($sql)-5);
   $sql.=" ORDER BY school";
   $result=mysql_query($sql);
   $schools=array();
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      $schools[$ix]=$row[school];
      $ix++;
   }
   //now get schools that aren't in declaration table at all
   $sql="SELECT school FROM headers ORDER BY school";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $sch2=ereg_replace("\'","\'",$row[0]);
      $sql2="SELECT school FROM declaration WHERE school='$sch2'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)
      {
	 $schools[$ix]=$row[0];
	 $ix++;
      }
   }
   echo "<tr align=center><td><br><table><tr align=center><th colspan=3 class=smaller>Schools who have not declared for ANY sport:<hr></th></tr>";
   sort($schools);
   for($i=0;$i<count($schools);$i++)
   {
      if($i%3==0) echo "<tr align=left>";
      echo "<td>";
      echo "$schools[$i]";
      echo "</td>";
      if(($ix+1)%3==0) echo "</tr>";
   }
   echo "</table></td></tr>";
}
else if($schch && $schch!="Show Summary")
{
   echo "<tr align=center><td><br><table cellspacing=1 cellpadding=4>";
   $schch2=ereg_replace("\'","\'",$schch);
   $sql="SELECT * FROM declaration WHERE school='$schch2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   for($i=0;$i<count($fallact);$i++)
   {
      if($i%2==0) echo "<tr align=left>";
      echo "<td><input type=checkbox name=\"declare[$i]\" value='y'";
      if($row[$fallact[$i]]=='y') echo " checked";
      echo ">&nbsp;".$fallactlong[$i]."</td>";
      if(($i+1)%2==0) echo "</tr>";
   }
   echo "<tr align=center><td colspan=2><input type=submit name=save value=\"Save Changes\"></td></tr>";
   echo "</table></td></tr>";
}

echo "</table>";
echo "</form>";
echo "<a href=\"welcome.php?session=$session\" class=small>Home</a>";

echo $end_html;
?>
