<?php
/****************************************************
adcontactinfo.php
Screen Displaying AD/ActD phone & email to officials
Created 8/26/09
Author: Ann Gaffigan
*****************************************************/

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
echo GetHeader($session);

echo "<a name=\"top\"><br></a><table cellspacing=0 cellpadding=4 frame=all rules=all style=\"border:#808080 1px solid;\">";
echo "<caption><b>NSAA Member Schools' Athletic & Activities Directors' Contact Information:</b></caption>";
echo "<tr align=center><td><b>School</b></td><td><b>Athletic Director</b></td><td><b>Activities Director</b></td></tr>";

/****SWITCH TO nsaascores DATABASE****/
$sql="USE $db_name";
$result=mysql_query($sql);

$sql="SELECT * FROM headers ORDER BY school";
$result=mysql_query($sql);
$i=0;
while($row=mysql_fetch_array($result))
{
   echo "<tr ";
   if($i%2==0) echo "bgcolor='#f0f0f0' ";
   echo "align=left><td><b>$row[school]</b></td>";
   $ph=split("-",$row[phone]);
   $mainareacode=$ph[0];
   $mainph="(".$ph[0].")".$ph[1]."-".$ph[2]; //MAIN SCHOOL PHONE
   $sql2="SELECT * FROM logins WHERE school='".addslashes($row[school])."' AND level='2'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if(trim($row2[name])!='')
   {
      $adname=trim($row2[name]);
      echo "<td><b>$row2[name]</b><br>".$mainph."<br><a class=small href=\"mailto:$row2[email]\">$row2[email]</a></td>";
   }
   else echo "<td>&nbsp;</td>";
   $sql2="SELECT * FROM logins WHERE school='".addslashes($row[school])."' AND sport='Activities Director'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if(trim($row2[name])!='' && trim($row2[name])!=$adname)
   {
      echo "<td><b>$row2[name]</b><br>".$mainph."<br><a class=small href=\"mailto:$row2[email]\">$row2[email]</a></td>";
   }
   else echo "<td>&nbsp;</td>";
   echo "</tr>";
   $i++;
}
echo "</table>";
echo "<br><a href=\"#top\" class=small>Return to Top</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"welcome.php?session=$session\" class=small>Return Home</a>";

echo $end_html;

/****SWITCH BACK TO nsaaofficials DATABASE****/
$sql="USE $db_name2";
$result=mysql_query($sql);
?>
