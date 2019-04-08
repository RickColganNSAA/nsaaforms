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

echo $init_html;
echo $header;
echo "<br>";

if($deleteid)
{
   $sql="SELECT * FROM hardship WHERE school='$school2' AND id='$deleteid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
   {
      $sql="DELETE FROM hardship WHERE id='$deleteid'";
      $result=mysql_query($sql);
      $sql="SELECT * FROM hardship_documents WHERE hardship_id='$deleteid'";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         citgf_unlink("/home/nsaahome/attachments/".$row[document]);
         $sql2="DELETE FROM hardship_documents WHERE id='$row[id]'";
         $result2=mysql_query($sql2);
      }
      echo "<div class='alert'>The hardship request form and associated documents were deleted.</div>";
   }
   else
   {
      echo "<div class='error'>The hardship request form could not be deleted.</div>";
   }
}

$sql="SELECT * FROM hardship WHERE school='$school2' ORDER BY datesub,execsignature";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   echo "[You currently have no Hardship Request forms on file.]";
}
else
{
   echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">";
   echo "<caption class=small><b>Hardship Request Forms on file for $school:</b><br><br></caption>";
   echo "<tr align=center><td><b>Submitted</b></td><td><b>Student</b><br>(Click for form)</td><td><b>Action taken by<br>Executive Director</b></td><td><b>Delete</b></td></tr>";
   while($row=mysql_fetch_array($result))
   {
      echO "<tr align=left><td>";
      if($row[datesub]=='') echo "NO";
      else echo date("m/d/Y",$row[datesub]);
      echo "</td><td><a class=small ";
      if($row[datesub]!='') echo "target=\"_blank\" ";
      echo "href=\"hardship.php?session=$session";
      if($row[datesub]!='') echo "&header=no";
      echo "&id=$row[id]\">";
      $sql2="SELECT first,last FROM eligibility WHERE id='$row[studentid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(mysql_num_rows($result2)==0)
         echo "Click to Edit</a></td>";
      else
         echo "$row2[first] $row2[last]</a></td>";
      echo "<td>";
      if($row[execsignature]=='') echo "NO";
      else echo date("m/d/Y",$row[execdate]);
      echo "</td>";
      if($row[execsignature]=='')
         echo "<td align=center><a href=\"hardshipforms.php?session=$session&deleteid=$row[id]\" onClick=\"return confirm('Are you sure you want to delete this Hardship Request Form and all association documents?');\">X</a></td></tr>";
      else
         echo "<td align=center>N/A</td></tr>";
   }
   echo "</table>";
}
echo "<br><br>";
echo "<font style=\"font-size:9pt;\">Start a new: <a href=\"hardship.php?session=$session\">Hardship Request Form</a></font>";
echo $end_html;
?>
