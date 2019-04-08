<?php
/*********************************************
reviewadmin.php
Admin Report for NSAA for Content Review Forms 
9/9/14
Author Ann Gaffigan
**********************************************/
require '../functions.php';
require '../../calculate/functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!ValidUser($session) || GetLevel($session)!=1)
{
   header("Location:../index.php?error=1");
   exit();
}
$sport='sp';
$sportname=GetActivityName($sport);

if($delete>0)
{
   $sql="DELETE FROM contentreviews WHERE id='$delete' AND sport='$sport'";
   $result=mysql_query($sql);
}

$sql="SELECT * FROM contentreview WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$wording=$row[wording];

echo $init_html;
echo GetHeader($session);

echo "<br><form method=post action=\"reviewadmin.php\">
	<input type=hidden name=\"session\" value=\"$session\">";
echo "<h2>$sportname Content Review Form Report</h2>";

echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">";
echo "<caption>";

//FILTER
echo "<div class=\"alert\"><h3>Toggle Report:</h3>";
if(!$schools || $schools=="") $schools="submitted";
if($schools=="submitted")
   echo "<p>Below are all schools who HAVE SUBMITTED the $sportname Content Review Form.</p><p><a href=\"reviewadmin.php?session=$session&schools=notsubmitted\">View schools who have NOT SUBMITTED the form</a></p>";
else
   echo "<p>Below are all schools who have NOT SUBMITTED the $sportname Content Review Form.</p><p><a href=\"reviewadmin.php?session=$session&schools=submitted\">View schools who HAVE SUBMITTED the form</a></p>";
echo "</div><br /><br />";
if($delete>0)
{
   echo "<div class='error'><i>The form has been deleted.</i></div>";
}
echo "</caption>";
if($schools=="submitted")
{
   echo "<tr align=center>";
   if(!$sort) $sort="t2.datesub DESC";
   if($sort=="t1.school ASC")
   {
      $cursort="t1.school ASC"; $curimg="arrowdown.png";
   }
   else if($sort=="t1.school DESC")
   {
      $cursort="t1.school ASC"; $curimg="arrowup.png";
   }
   else
   {
      $cursort="t1.school ASC"; $curimg="";
   }
   echo "<td><a class=\"small\" href=\"reviewadmin.php?session=$session&schools=$schools&sort=$cursort\">School";
   if($curimg!='') echo "<img src=\"../$curimg\" style=\"height:10px;margin:0 0 0 5px;\">";
   echo "</a></td>";
   echo "<td><b>Administrator Signature</b></td>";
   if($sort=="t2.datesub DESC")
   {
      $cursort="t2.datesub ASC"; $curimg="arrowup.png";
   }
   else if($sort=="t2.datesub ASC")
   {
      $cursort="t2.datesub DESC"; $curimg="arrowdown.png";
   }
   else 
   {
      $cursort="t2.datesub DESC"; $curimg="";
   }
   echo "<td><a class=\"small\" href=\"reviewadmin.php?session=$session&schools=$schools&sort=$cursort\">Date Submitted";
   if($curimg!='') echo "<img src=\"../$curimg\" style=\"height:10px;margin:0 0 0 5px;\">";
   echo "</a></td>";
   echo "<td><b>Delete</b></td>";
   echo "</tr>";
}
else
 echo "<tr align=center><td><b>School</b></td><td><b>Notify Administrator</b></td></tr>";

//MAKE QUERY
if($schools!="submitted")	//NOT SUBMITTEd
{
   $sql="SELECT * FROM headers ORDER BY school";
}
else	//SUBMITTED 
{
   $sql="SELECT t1.school,t2.* FROM headers AS t1, contentreviews AS t2 WHERE t1.id=t2.schoolid AND t2.sport='$sport' AND t2.datesub>0 ORDER BY $sort";
}
$result=mysql_query($sql);
echo mysql_error();
while($row=mysql_fetch_array($result))
{
   $proceed=1;
   if($schools=='notsubmitted')	//FILTER
   {
      if(IsRegistered2011($row[schoolid],$sport))
      {
	    $sql2="SELECT * FROM contentreviews WHERE sport='$sport' AND schoolid='$row[schoolid]'";
	    $result2=mysql_query($sql2);
	    if(mysql_num_rows($result2)==0) $proceed=1;
      }
   }
   else $proceed=1;
   if($proceed=1)
   {
      if($schools=='submitted')
      {
         echo "<tr align=left><td>$row[school]</td>";
         echo "<td>$row[adminsig]</td><td>".date("F j, Y",$row[datesub])." at ".date("g:ia T",$row[datesub])."</td>
	<td><a href=\"reviewadmin.php?session=$session&schools=$schools&sort=$cursort&delete=$row[id]\" onClick=\"return confirm('Are you sure you want to delete this submitted form?');\" class=\"small\">Delete</a></td></tr>";
      }
      else
      {
         $sql2="SELECT email,name FROM logins WHERE school='".addslashes($row[school])."' AND level=2";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         echo "<tr align=left><td>$row[school]</td>";
         echo "<td>$row2[name]: <a class=\"small\" href=\"mailto:$row2[email]\">$row2[email]</a></td></tr>";
      }
   }	//end if proceed
}

echo "</table>";
echo "</form>";
echo $end_html;


?>
