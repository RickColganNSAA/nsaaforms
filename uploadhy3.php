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
   header("Location:index.php?error=1");
   exit();
}

if($submit=="Delete Checked")
{
   for($i=0;$i<count($fileid);$i++)
   {
      if($delete[$i]=='x')
      {
	 $sql="DELETE FROM sw_hy3files WHERE id='$fileid[$i]'";
	 $result=mysql_query($sql);
      }
   }
}
if($submit=="Upload")
{
   if($school=='') $error="school";
   if(!$hysfile) $error="file";
   else $error=0;

   if($error==0)
   {
      $school2=addslashes($school);
      $filename=$_FILES["hy3file"]["name"];
      $filename=ereg_replace(" ","",$filename);
      $filename=ereg_replace("\'","",$filename);
      if(!citgf_copy($hy3file,"sw/hytek/$filename"))
      {
	 $error="upload";
      }
      else
      {
	 $error="none";
      }
      $sql="SELECT * FROM sw_hy3files WHERE school='$school2'";
      $result=mysql_query($sql);
      $today=time();
      if(mysql_num_rows($result)==0)
      {
	 $sql2="INSERT INTO sw_hy3files (school,filename,lastupload) VALUES ('$school2','$filename','$today')";
      }
      else
      {
	 $sql2="UPDATE sw_hy3files SET filename='$filename',lastupload='$today' WHERE school='$school2'";
      }
      $result2=mysql_query($sql2);
   }
}

echo $init_html;
echo $header;
echo "<br><br><a class=small href=\"welcome.php?session=$session&toggle=menu3&menu3sport=Swimming\">Return to Home-->Swimming</a><br><br>";
if($error)
{
   if($error=="school")
   {
      echo "<font style=\"color:red\"><b>You must select a school.</b></font>";
   }
   else if($error=="file")
   {
      echo "<font style=\"color:red\"><b>You must select a file to upload.</b></font>";
   }
   else if($error=="upload")
   {
      echo "<font style=\"color:red\"><b>There was an error uploading your file.  Please try again.</b></font>";
   }
   else if($error=="none")
   {
      echo "<font style=\"color:blue\"><b>Your .hy3 file for $school has been successfully uploaded!</b></font>";
   }
   echo "<br><br>";
}

echo "<form method=post action=\"uploadhy3.php\" enctype=\"multipart/form-data\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table><caption><b>Upload .hy3 Swimming Rosters</b><hr></caption>";
echo "<tr align=left><td><b>Choose a School:&nbsp;</b>";
echo "<select name=school><option value=''>~</option>";
$sql="SELECT school FROM swschool ORDER by school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option ";
   if($school==$row[0]) echo " selected";
   echo ">$row[0]</option>";
}
echo "</select></td></tr>";
echo "<tr align=left><td>";
echo "<input type=file name=\"hy3file\"></td></tr>";
echo "<tr align=center><td><input type=submit name=submit value=\"Upload\"></td></tr>";
echo "</table>";
echo "</form>";

echo "<form method=post action=\"uploadhy3.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<table cellspacing=2 cellpadding=3>";
$sql="SELECT * FROM sw_hy3files ORDER BY school";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   if($ix%2==0) echo "<tr align=left>";
   echo "<td>";
   echo "<input type=hidden name=\"fileid[$ix]\" value=\"$row[id]\">";
   echo "<input type=checkbox name=\"delete[$ix]\" value='x'>";
   echo "<a class=small target=new href=\"sw/hytek/$row[filename]\">$row[school]</a> (".date("m/d/Y",$row[lastupload]).")</td>";
   if(($ix+1)%2==0) echo "</tr>";
   $ix++;
}
if(mysql_num_rows($result)>0)
   echo "<tr align=center><td colspan=2><input type=submit name=submit value=\"Delete Checked\"></td></tr>";
echo "</table>";
echo "</form>";
echo $end_html;
?>
