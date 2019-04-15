<?php

if($submit=="Cancel")
{
   header("Location:welcome.php?session=$session");
   exit();
}

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

echo $init_html;
echo $header;

if($submit=="Save Changes")
{
   for($i=0;$i<count($id);$i++)
   {
      if($delete[$i]=='y')
      {
	 $sql="DELETE FROM downloads WHERE id='$id[$i]'";
	 $result=mysql_query($sql);
      }
      else
      {
	 $curtitle[$i]=addslashes($curtitle[$i]);
   	 $curlink[$i]=addslashes($curlink[$i]);
	 $sql="UPDATE downloads SET active='$active[$i]',doctitle='$curtitle[$i]',filename='$curlink[$i]' WHERE id='$id[$i]'";
	 $result=mysql_query($sql);
      }
   }
}
else if($submit=="Upload" && (trim($linkurl)!='' || is_uploaded_file($_FILES['docfile']['tmp_name'])))
{
   //upload file to downloads/ directory and add to database
   $time=time(); $error="";

   if(trim($linkurl)!="")
   {
      $filename=$linkurl;
   }
   else
   {
      $filename=$_FILES["docfile"]["name"]; 
      if(!citgf_copy($_FILES["docfile"]["tmp_name"], "downloads/$filename")) $error="Could not copy the uploaded file.";
      $filename="https://secure.nsaahome.org/nsaaforms/officials/downloads/$filename";
   }
   $rec="";
   for($i=0;$i<count($recipients);$i++)
   {
      $rec.=$recipients[$i]."/";
   }
   $rec=substr($rec,0,strlen($rec)-1);
   if(!$error)
   {
      $doctitle=addslashes($doctitle);
      $filename=addslashes($filename);
      $sql="INSERT INTO downloads (dateadded,filename,doctitle,active,recipients) VALUES ('".time()."','$filename','$doctitle','y','$rec')";
      $result=mysql_query($sql);
      if(mysql_error()) $error=mysql_error()."<br>(Query: $sql)";
      else
         echo "<br><br><font style=\"color:blue\"><b>Your link has been successfully added!</b></font>";
   }
   if($error!='')
   {
      echo "<br><br><div class='error'>There was an error uploading your file: $error</div>";
   }
}

echo "<br><br>";
echo "<form enctype=\"multipart/form-data\" method=post action=\"uploaddoc.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table><caption><b>NSAA Officials New Document Upload/Link Addition:</b><hr></caption>";
echo "<tr align=left><th align=left>Please give your document a descriptive title:</th>";
echo "<td align=left><input type=text name=doctitle size=40></td></tr>";
echo "<tr align=left><th align=left>Find Your Document:</th>";
echo "<td align=left><input type=file name=\"docfile\"></td></tr>";
echo "<tr valign=top align=left><th align=left>OR Type in the Link URL to Your Document:</th>";
echo "<td><input type=text class=tiny size=50 name=linkurl><br>";
echo "(Example: https://nsaahome.org/fbl/textfile/fbassign.htm)</td></tr>";
echo "<tr valign=top align=left><th align=left>Choose Your Recipients:<br>";
echo "<font style=\"font-size:8pt\">(To choose more than one, hold down CTRL (PC) or Apple (Mac))</font></th>";
echo "<td align=left><select name=recipients[] multiple size=5>";
echo "<option value='All' selected>All Officials</option>";
$fb=0;
for($i=0;$i<count($activity);$i++)
{
   $cur_act=$activity[$i];
   echo "<option value='$cur_act'>$act_long[$i] Officials";
}
echo "</select></td></tr>";
echo "<tr align=center><td colspan=2>";
echo "<input type=submit name=submit value=\"Upload\">&nbsp;&nbsp;";
echo "<input type=submit name=submit value=\"Cancel\"></td></tr>";
echo "</table>";
echo "</form><br>";

//Now list currently uplaoded files and their status; allow to change status
//and/or delete files
echo "<form method=post action=\"uploaddoc.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<table width=500 border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
echo "<caption><b>Documents & Links You Have Already Added:</b><hr></caption>";
echo "<tr align=center><th width=50 class=smaller>Active</th><th class=smaller>Link Title</th><th>Link URL</th><th width=50 class=smaller>Delete</th></tr>";
$sql="SELECT * FROM downloads ORDER BY dateadded DESC,doctitle";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr align=center><td><input type=checkbox name=\"active[$ix]\" value='y'";
   if($row[active]=='y') echo " checked";
   echo "></td>";
   echo "<input type=hidden name=\"id[$ix]\" value=\"$row[id]\">";
   echo "<td align=left><input type=text class=tiny name=\"curtitle[$ix]\" value=\"$row[doctitle]\" size=40></td>";
            $row[filename]=preg_replace("/(www.)/","",$row[filename]);
   echo "<td align=left><textarea name=\"curlink[$ix]\" rows=2 cols=40>$row[filename]</textarea><br>";
   echo "<a class=small target=\"_blank\" href=\"$row[filename]\">Preview Link</a></td>";
   echo "<td><input type=checkbox name=\"delete[$ix]\" value='y'></td></tr>";
   $ix++;
}
echo "</table><br>";
echo "<input type=submit name=submit value=\"Save Changes\">&nbsp;&nbsp;";
echo "<input type=submit name=submit value=\"Cancel\">";
echo "</form>";

echo $end_html;
?>
