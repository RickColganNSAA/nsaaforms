<?php

require 'functions.php';
require 'variables.php';
require '../calculate/functions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php");
   exit();
}

if($delete)
{
   $sql="DELETE FROM hardship WHERE id='$delete'";
   $result=mysql_query($sql);
   //echo $sql;
}

echo $init_html;
echo $header;

if($report==1)
{
   if(!$sort) $sort="t2.datesub DESC";
   $sql="SELECT t1.first,t1.last,t2.* FROM eligibility AS t1,hardship AS t2 WHERE t1.id=t2.studentid AND t2.datesub!='' AND t2.execsignature='x' ORDER BY $sort";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      echo "<br><br><font style=\"font-size:9pt;\"><b>Report of Completed Hardship Request forms:<br><br></b></font>[There are no completed hardship request forms on file.]";      
      echo "<br><br><a class=small href=\"hardshipadmin.php?session=$session\">Hardship Request Forms MAIN MENU</a><br><br>";
      exit();
   }
   else
   {
      echo "<br><a class=small href=\"hardshipadmin.php?session=$session\">Hardship Request Forms MAIN MENU</a><br><br>";
      echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">";
      echo "<caption><b>Report of Completed Hardship Request forms:</b><br>(Click column headers to sort by that field.)</caption>";
      echo "<tr align=\"center\">";
      if($sort=="t2.datesub ASC") $datesub="t2.datesub DESC";
      else $datesub="t2.datesub ASC";
      echo "<td><a class=small href=\"hardshipadmin.php?session=$session&report=1&sort=$datesub\">";
      if($sort=="t2.datesub ASC") echo "&Delta; ";
      else if($sort=="t2.datesub DESC") echo "&nabla; ";
      echo "Date Submitted</a></td>";
      if($sort=="t2.school ASC") $sch="t2.school DESC";
      else $sch="t2.school ASC";
      echo "<td><a class=small href=\"hardshipadmin.php?session=$session&report=1&sort=$sch\">";
      if($sort=="t2.school ASC") echo "&Delta; ";
      else if($sort=="t2.school DESC") echo "&nabla; ";
      echo "School: Student</a></td>";
      if($sort=="t2.eligible ASC") $elig="t2.eligible DESC";
      else $elig="t2.eligible ASC";
      echo "<td><a class=small href=\"hardshipadmin.php?session=$session&report=1&sort=$elig\">";
      if($sort=="t2.eligible ASC") echo "&Delta; ";
      else if($sort=="t2.eligible DESC") echo "&nabla; ";
      echo "Eligible</a></td>";
      if($sort=="t2.execdate ASC") $exec="t2.execdate DESC";
      else $exec="t2.execdate ASC";
      echo "<td><b>Comments</b></td><td><b>Comments for NSAA ONLY</b></td>";
      echo "<td><a class=small href=\"hardshipadmin.php?session=$session&report=1&sort=$exec\">";
      if($sort=="t2.execdate ASC") echo "&Delta; ";
      else if($sort=="t2.execdate DESC") echo "&nabla; ";
      echo "Date of Action</a></td>"; 
      echo "</tr>";
      while($row=mysql_fetch_array($result))
      {
   	 echo "<tr align=center valign=top><td>".date("m/d/Y",$row[datesub])."</td>";
	 echo "<td align=left><a class=small target=new href=\"hardship.php?session=$session&header=no&id=$row[id]\">$row[school]: $row[first] $row[last]</a></td>";
	 if($row[eligible]=='y') echo "<td>YES</td>";
	 else echo "<td><font style=\"color:red\">NO</font></td>";
         echo "<td align=left width='300px'>$row[execcomments]</td>";
	 echo "<td align=left width='300px'>$row[nsaacomments]</td>";
	 echo "<td>".date("m/d/Y",$row[execdate])."</td></tr>";
      }
      echo "</table>";
      echo "<br><br><a class=small href=\"hardshipadmin.php?session=$session\">Hardship Request Forms MAIN MENU</a>";
      exit();
   }
}

echo "<br><table width=700 class=nine cellspacing=3 cellpadding=3><caption><b>Hardship Eligibility Confirmation MAIN MENU:</b><hr></caption>";

//Forms that have been submitted but need action:
echo "<tr align=left><td><b>Submitted forms that <u>need Action of the Executive Director</u>:</b></td></tr>";
echo "<tr align=center><td>";
if($delete)
{
   echo "<font style=\"color:red\">Form #$delete has been deleted.</font><br>";
}
if($sort || $sort=='') $sort="datesub DESC";
$sql="SELECT * FROM hardship WHERE datesub!='' AND execsignature='' ORDER BY $sort";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<table cellspacing=0 cellpadding=4 frame=all rules=all style=\"border:#808080 1px solid;\">";
   echO "<tr align=center><td><b>Date<br>Submitted</b></td><td><b>School</b></td>";
   echo "<td><b>Student</b><br>(Click for Form)</td>";
   echo "<td><b>Delete</b></td></tr>";
}
else echo "[There are no forms in need of Action of the Executive Director.]";
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left><td>".date("m/d/y",$row[datesub])."</td>";
   echo "<td>$row[school]</td>";
         $year=date("Y",$row[datesub]);
         $month=date("m",$row[datesub]);
         if($month<6) $year--;
   $name=GetStudentInfo($row[studentid],FALSE,GetDatabase($year));
   if(trim($name)=="") $name="[No Name Found for Student ID# $row[studentid]]";
   echo "<td><a class=small target=new href=\"hardship.php?session=$session&id=$row[id]&header=no\">$name</a></td>";
   echo "<td><a class=small href=\"hardshipadmin.php?session=$session&delete=$row[id]\" onclick=\"return confirm('Are you sure you want to delete this form?  The information cannot be recovered once you do so.');\">Delete</a></td></tr>";
}
if(mysql_num_rows($result)>0)
   echo "</table>";
echo "</td></tr>";

//Search for completed forms by school:
echo "<tr align=left><td><br><b>Search for <u>completed forms</u> by school:</b><br>(Only schools with forms on which executive action has already been taken are listed)</td></tr>";
echO "<tr align=center><td>";
echo "<form method=post action=\"hardshipadmin.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<select name=schch><option value=''>Choose School</option>";
$sql="SELECT DISTINCT school FROM hardship WHERE datesub!='' AND execsignature='x' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echO "<option";
   if($schch==$row[0]) echo " selected";
   echo ">$row[0]</option>";
}
echo "</select>&nbsp;<input type=submit name=search value=\"Search\"><br>";
echo "</form>";
if($search && $schch!='')
{
   $schch2=addslashes($schch);
   $sql="SELECT * FROM hardship WHERE school='$schch2' AND datesub!='' AND execsignature='x' ORDER BY datesub DESC";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      echo "<br>[No forms were found for $schch.]";
   }
   else
   {
      echo "<br><table cellspacing=0 cellpadding=4 frame=all rules=all style=\"border:#808080 1px solid;\">";
      echo "<tr align=center><td><b>Date<br>Submitted</b></td><td><b>Student</b><br>(Click for form)</td><td><b>Action<br>Taken</b></td><td><b>Comments</b></td><td><b>Comments for NSAA ONLY</b></td><td><b>Action<br>Date</b></td><td><b>Delete</b></td></tr>";
      while($row=mysql_fetch_array($result))
      {
	 echo "<tr align=left><td>".date("m/d/Y",$row[datesub])."</td>";
	 $year=date("Y",$row[datesub]);
	 $month=date("m",$row[datesub]);
	 if($month<6) $year--;
   	 echo "<td width='150px'><a class=small target=new href=\"hardship.php?session=$session&header=no&id=$row[id]\">";
   $name=GetStudentInfo($row[studentid],FALSE,GetDatabase($year));
   if(trim($name)=="") $name="[No Name Found for Student ID# $row[studentid]]";
	 echo $name."</a></td>";
	 echo "<td>";
	 if($row[eligible]=='y') echo "Eligible";
	 else echo "<font style=\"color:red\">Ineligible</font>";
	 echo "</td><td width='300px'>";
         echo "$row[execcomments]</td><td width='300px'>$row[nsaacomments]</td><td>";
	 echo date("m/d/Y",$row[execdate]);
	 echo "</td>";
         echo "<td><a class=small href=\"hardshipadmin.php?session=$session&delete=$row[id]\" onclick=\"return confirm('Are you sure you want to delete this form?  The information cannot be recovered once you do so.');\">Delete</a></td>";
	 echo "</tr>";
      }
      echo "</table>";
   }
   echO "<br>";
}
echo "</td></tr>";

//Report of all forms for the year
echo "<tr align=left><td><a href=\"hardshipadmin.php?session=$session&report=1\">Report of Completed Hardship Request forms</a></td></tr>";

//Export of all forms for the year
$sql="SELECT t1.first,t1.last,t2.* FROM eligibility AS t1,hardship AS t2 WHERE t1.id=t2.studentid AND t2.datesub!='' AND t2.execsignature='x' ORDER BY execdate DESC,school";
$result=mysql_query($sql);
$csv="\"Date of Action\",\"School\",\"Student\",\"Eligible\",\"Comments\",\"Comments for NSAA ONLY\"\r\n";
while($row=mysql_fetch_array($result))
{
   $csv.="\"".date("m/d/y",$row[execdate])."\",\"$row[school]\",\"$row[first] $row[last]\",\"";
   if($row[eligible]=="y") $csv.="YES";
   else $csv.="NO";
   $csv.="\",\"$row[execcomments]\",\"$row[nsaacomments]\"\r\n";
}
$open=fopen(citgf_fopen("/home/nsaahome/reports/hardshipexport.csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/hardshipexport.csv");
echo "<tr align=left><td><a href=\"exports.php?session=$session&filename=hardshipexport.csv\" target=\"_blank\">Export of Completed Hardship Request forms</a></td></tr>";

echo $end_html;
 
?>
