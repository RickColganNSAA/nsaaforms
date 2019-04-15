<?php

if($submit=="Cancel")
{
   header("Location:welcome.php?session=$session");
   exit();
}

require 'functions.php';
require 'variables.php';

$header=GetHeaderJ($session);
$level=GetLevelJ($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:jindex.php");
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
	 $sql="DELETE FROM downloads_j WHERE id='$id[$i]'";
	 $result=mysql_query($sql);
      }
      else
      {
	 $curtitle[$i]=addslashes($curtitle[$i]);
   	 $curlink[$i]=addslashes($curlink[$i]);
         $recips="sp/pp";
         if($sp[$i]=='x' && $pp[$i]=='x') $recips="sp/pp";
         else if($sp[$i]=='x') $recips='sp';
         else if($pp[$i]=='x') $recips='pp';
	 $sql="UPDATE downloads_j SET active='$active[$i]',recipients='$recips',doctitle='$curtitle[$i]',filename='$curlink[$i]' WHERE id='$id[$i]'";
	 $result=mysql_query($sql);
      }
   }
}
else if($submit=="Upload")
{
   //upload file to downloads/ directory and add to database
   $time=time();

   if(trim($linkurl)!="")
   {
      $filename=$linkurl;
   }
   else
   {
      $filename=$_FILES["docfile"]["name"]; 
      if(is_uploaded_file($_FILES["docfile"]["tmp_name"]))
      {
         if(!citgf_copy($_FILES["docfile"]["tmp_name"], "downloads/$filename")) 
	 {
	    echo "COULD NOT COPY";
	    exit();
	 }
         $filename="https://secure.nsaahome.org/nsaaforms/officials/downloads/$filename";
      }
      else 
      {
	 echo "NO FILE UPLOADED";
	 exit();
      }
   }
   if($recipient=="All") $rec="pp/sp";
   else $rec=$recipient;
   $sql="INSERT INTO downloads_j (dateadded,filename,doctitle,active,recipients) VALUES ('".time()."','$filename','$doctitle','y','$rec')";
   $result=mysql_query($sql);

   echo "<br><br><font style=\"color:blue\"><b>Your link has been successfully added!</b></font>";

}

echo "<br><br>";
echo "<form enctype=\"multipart/form-data\" method=post action=\"juploaddoc.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table><caption><b>NSAA Judges New Document Upload/Link Addition:</b><hr></caption>";
echo "<tr align=left><th align=left>Please give your document a descriptive title:</th>";
echo "<td align=left><input type=text name=doctitle size=40></td></tr>";
echo "<tr align=left><th align=left>Find Your Document:</th>";
echo "<td align=left><input type=file name=docfile></td></tr>";
echo "<tr valign=top align=left><th align=left>OR Type in the Link URL to Your Document:</th>";
echo "<td><input type=text class=tiny size=50 name=linkurl><br>";
echo "(Example: https://nsaahome.org/fbl/textfile/fbassign.htm)</td></tr>";
echo "<tr valign=top align=left><th align=left>Choose Your Recipients:</th>";
echo "<td align=left><select name=recipient>";
echo "<option value='All' selected>All Judges</option>";
echo "<option value='sp'>Speech Judges</option><option value='pp'>Play Judges</option>";
echo "</select></td></tr>";
echo "<tr align=center><td colspan=2>";
echo "<input type=submit name=submit value=\"Upload\">&nbsp;&nbsp;";
echo "<input type=submit name=submit value=\"Cancel\"></td></tr>";
echo "</table>";
echo "</form><br>";

//Now list currently uplaoded files and their status; allow to change status
//and/or delete files
echo "<form method=post action=\"juploaddoc.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<table frame=all rules=all cellspacing=0 cellpadding=3 style=\"border:#808080 1px solid;\">";
echo "<caption><b>Documents & Links You Have Already Added:</b></caption>";
echo "<tr align=center><th width=50 class=smaller>Active</th><th class=smaller>Link Title</th><th>Link URL</th><th>Recipients</th><th width=50 class=smaller>Delete</th></tr>";
$sql="SELECT * FROM downloads_j ORDER BY dateadded DESC,doctitle";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr align=center><td><input type=checkbox name=\"active[$ix]\" value='y'";
   if($row[active]=='y') echo " checked";
   echo "></td>";
   echo "<input type=hidden name=\"id[$ix]\" value=\"$row[id]\">";
   echo "<td align=left><input type=text class=tiny name=\"curtitle[$ix]\" value=\"$row[doctitle]\" size=40></td>";
   echo "<td align=left><textarea name=\"curlink[$ix]\" rows=2 cols=40>$row[filename]</textarea><br>";
   echo "<a class=small target=\"_blank\" href=\"$row[filename]\">Preview Link</a></td>";
   echo "<td><input type=checkbox name=\"sp[$ix]\" value=\"x\"";
   if(preg_match("/sp/",$row[recipients])) echo " checked";
   echo "> Speech  <input type=checkbox name=\"pp[$ix]\" value=\"x\"";
   if(preg_match("/pp/",$row[recipients])) echo " checked";
   echo "> Play</td>";
   echo "<td><input type=checkbox name=\"delete[$ix]\" value='y'></td></tr>";
   $ix++;
}
echo "</table><br>";
echo "<input type=submit name=submit value=\"Save Changes\">&nbsp;&nbsp;";
echo "<input type=submit name=submit value=\"Cancel\">";
echo "</form>";

echo $end_html;
?>
