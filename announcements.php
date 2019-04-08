<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

if($save)
{
   $announcement=addslashes($announcement);
   $sql="UPDATE announcements SET announcement='$announcement'";
   $result=mysql_query($sql);
}

echo $init_html;
echo $header;

echo "<form method=post action=\"announcements.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<br><table width=\"500px\" class=nine><caption><b>Edit Announcements on NSAA Home Page:</b></caption>";
echo "<tr align=center><td><br>";
if($save)
{
   echo "<div class=alert style=\"width:400px;\">The announcements on the NSAA home page have been updated.</div><br>";
}
echo "<a target=\"_blank\" href=\"/\">Preview NSAA Home Page</a></td></tr>";
echo "<tr align=left><td><b>Edit announcements here:</b><br>";
echo "<font style=\"color:red;font-size:9pt;\"><b>PLEASE NOTE:</b><br>Words between &lt;u&gt; and &lt;/u&gt; will be <u>underlined</u>.<br>Words between &lt;b&gt; and &lt;/b&gt; will be <b>bolded</b>.<br>Words between &lt;i&gt; and &lt;/i&gt; will be <i>italicized</i>.<br>To insert a line break, use &lt;br&gt;.</font></td></tr>";
$sql="SELECT announcement FROM announcements LIMIT 1";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
echo "<tr align=center><td><textarea cols=70 rows=15 name=\"announcement\">$row[0]</textarea></td></tr>";
echo "<tr align=center><td><input type=submit name=save value=\"Save Announcement\"></td></tr>";
echo "</table></form>";
echo $end_html;
?>
