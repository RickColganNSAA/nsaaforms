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

echo $init_html;
echo $header;
echo "<br>";

echo "<table cellspacing=3 cellpadding=3><caption><b>Download .hy3 Swimming Rosters</b><hr></caption>";
echo "<tr align=center><td colspan=2><font style=\"color:red\"><b><i>Be sure to right-click (Mac users: hold down ctrl and click) on the links below and save the file to your computer.  DO NOT rename the file.</b></i></td></tr>";
$sql="SELECT * FROM sw_hy3files ORDER BY school";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   if($ix%2==0) echo "<tr align=left>";
   echo "<td>";
   echo "<a class=small target=new href=\"sw/hytek/$row[filename]\">$row[school]</a> (".date("m/d/Y",$row[lastupload]).")</td>";
   if(($ix+1)%2==0) echo "</tr>";
   $ix++;
}
if($ix==0) echo "<tr align=center><td><br><br>[No Hytek files have been uploaded yet this season.  Please check back at a later date.  Thank You!]</td></tr>";
echo "</table>";
echo $end_html;
?>
