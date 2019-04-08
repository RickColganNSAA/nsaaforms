<?php

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

if($reset && $reset!='')
{
   $reset2=addslashes($reset);
   $sql="UPDATE muschools SET submitted='' WHERE school='$reset2'";
   $result=mysql_query($sql);
}
else if($resetall=="yes")	//RESET ALL FOR NEW YEAR
{
   $sql="UPDATE muschools SET submitted='',studcount='',homedistrict='',distid='0'";
   $result=mysql_query($sql);
   $sql="DELETE FROM muentries";
   $result=mysql_query($sql);
   $sql="DELETE FROM mupercinsts";
   $result=mysql_query($sql);
   $sql="DELETE FROM mustudentries";
   $result=mysql_query($sql);
}
echo $init_html;
echo $header;

echo "<br>";
echo "<a class=small href=\"muadmin.php?session=$session\">Return to Music Entry Form Admin</a>";
echo "<form method=post action=\"view_mu.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<br><font style=\"font-size:9pt;\">Music Forms that have NOT been submitted:</font>&nbsp;<select name=\"school_ch\"><option value=''>Choose a School</option>";
$sql="SELECT school FROM $db_name.headers ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sch=addslashes($row[school]);
   $sql2="SELECT * FROM muschools WHERE school='$sch' AND submitted>0";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
      echo "<option>$row[school]</option>";
}
echo "</select>&nbsp;<input type=submit name=submit value=\"Go\"></form>";

echo "<table cellspacing=0 cellpadding=3><caption><b><u>Submitted</u> Music District Entry Forms:</b>&nbsp;&nbsp;";
echo "<a href=\"entriesadmin.php?session=$session&resetall=yes\" onClick=\"return confirm('Are you sure you want to reset ALL Music District Entry Forms? This will clear out all information in each school\'s entry form.');\">Reset Entry Forms for New Year</a>";
if($reset && $reset!='')
   echo "<div class=alert style=\"width:400px\"><b>$reset's</b> form has been reset.  However, none of their information has been deleted.</div>";
echo "<br><br></caption>";
if(!$sort || $sort=="") $sort="t2.submitted DESC";
echo "<tr align=left><td><a class=small href=\"entriesadmin.php?session=$session&sort=t1.distnum,t1.classes\">District # -- Class</a>";
if($sort=="t1.distnum,t1.classes")
   echo "&nbsp;<a href=\"entriesadmin.php?session=$session&sort=$sort\"><img border=0 src=\"../arrowdown.png\" width=15></a>";
echo "</td><td><a class=small href=\"entriesadmin.php?session=$session&sort=t2.school\">School</a>";
if($sort=="t2.school")
   echo "&nbsp;<a href=\"entriesadmin.php?session=$session&sort=$sort\"><img border=0 src=\"../arrowdown.png\" width=15></a>";
echo "<br>(click School Name to view form)</td><td><b># Students</b></td><td><a href=\"entriesadmin.php?session=$session&sort=t2.submitted DESC\" class=small>Submission<br>Date & Time</a>";
if($sort=="t2.submitted DESC")
   echo "&nbsp;<a href=\"entriesadmin.php?session=$session&sort=$sort\"><img border=0 src=\"../arrowup.png\" width=15></a>";
echo "</td><td><b>Reset Form</b></td></tr>";
$sql="SELECT t1.distnum,t1.classes,t2.* FROM mudistricts AS t1, muschools AS t2 WHERE t1.id=t2.distid AND t2.submitted!='' ORDER BY $sort";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left bgcolor=#E0E0E0><td width=\"200px\">$row[distnum] -- $row[classes]</td>";
   echo "<td><a href=\"view_mu.php?session=$session&school_ch=$row[school]\">$row[school]</a></td>";
   echo "<td align=center>$row[studcount]</td>";
   echo "<td align=center>".date("m/d/y",$row[submitted])." @ ".date(" h:i a",$row[submitted])."</td>";
   echo "<td align=center><a class=small href=\"entriesadmin.php?session=$session&reset=$row[school]\">Reset Form</a></td>";
   //show links to attachments that were sent with form:
   echo "<tr align=center><td colspan=5><table width=90%>";
      //Get file names of attachments:
      $summary=strtolower($row[school]);
      $summary=ereg_replace(" ","",$summary);
      $summary=ereg_replace("[.]","",$summary);
      $summary=ereg_replace("\'","",$summary);
      $summary=ereg_replace("-","",$summary);
      $summary.="summary";
      $full=ereg_replace("summary","full",$summary);
      $eliglist=ereg_replace("summary","eliglist",$summary);
      $payment=ereg_replace("summary","payment",$summary);
   echo "<tr align=left>";
   echo "<td><a class=small target=\"_blank\" href=\"../attachments.php?session=$session&filename=".$summary.".html\">Summary (.html)</a></td>";
   echo "<td><a class=small target=\"_blank\" href=\"../attachments.php?session=$session&filename=".$full.".html\">Full Version (.html)</a></td>";
   echo "<td><a class=small target=\"_blank\" href=\"../attachments.php?session=$session&filename=".$eliglist.".html\">Eligibility List (.html)</a></td>";
   echo "<td><a class=small target=\"_blank\" href=\"../attachments.php?session=$session&filename=".$payment.".html\">Payment Summary (.html)</a></td></tr>";
   echo "<tr align=left>";
   echo "<td><a class=small target=\"_blank\" href=\"../attachments.php?session=$session&filename=".$summary.".csv\">Summary (.csv)</a></td>";
   echo "<td><a class=small target=\"_blank\" href=\"../attachments.php?session=$session&filename=".$full.".csv\">Full Version (.csv)</a></td>";
   echo "<td><a class=small target=\"_blank\" href=\"../attachments.php?session=$session&filename=".$eliglist.".csv\">Eligibility List (.csv)</a></td>";
   echo "<td><a class=small target=\"_blank\" href=\"../attachments.php?session=$session&filename=".$payment.".csv\">Payment Summary (.csv)</a></td>";
   echo "</td></table></td></tr>";
} 
echo "</table>";

echo $end_html;
?>
