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

if($submit)
{
   for($i=0;$i<count($appid);$i++)
   {
      $note[$i]=ereg_replace("\'","\'",$note[$i]);
      $note[$i]=ereg_replace("\"","\'",$note[$i]);

      $sql="UPDATE judgesapp SET checked='$check[$i]', notes='$note[$i]', nosee='$nosee[$i]' WHERE appid='$appid[$i]'";
      $result=mysql_query($sql);
   }
}

echo $init_html;
echo $header;

echo "<center><br>";
echo "<form method=post action=\"judgesreg.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=3>";
echo "<caption><b>Submitted Judges Registration Forms:<br><br></b></caption>";
$colheaders="<tr align=center><th class=smaller>Delete<br>from View</th><th class=smaller>Application</th><th class=smaller>Check if<br>Viewed</th><th class=smaller>Notes</th></tr>";

$sql="SELECT * FROM judgesapp WHERE approved='yes' AND nosee!='y' ORDER BY appid DESC";
$result=mysql_query($sql);
$ix=0;
while($row=mysql_fetch_array($result))
{
   if(trim($row[1])!="")
   {
   $date=date("r",$row[1]);
   $id=$row[1];
   if($ix%15==0) echo $colheaders;
   echo "<tr align=left><td align=center><input type=checkbox name=\"nosee[$ix]\" value='y'";
   if($row[5]=='y') echo " checked";
   echo "></td><td><a href=\"../officials/japp$row[1].html\" target=new>#$row[1]: $date</a></td><td align=center>";
   echo "<input type=checkbox name=\"check[$ix]\" value='y'";
   if($row[3]=='y') echo " checked";
   echo "></td><td><input type=text size=40 name=\"note[$ix]\" value=\"$row[4]\"></td></tr>";
   echo "<input type=hidden name=\"appid[$ix]\" value=\"$id\">";
   $ix++;
   }
}
echo "</table><br><input type=submit name=submit value=\"Save\"></form>";

echo $end_html;
?>
