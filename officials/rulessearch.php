<?php
require 'functions.php';
require_once('variables.php');
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

if(!ValidUser($session))
{
   header("Location:/nsaaforms/officials/index.php?error=1");
   exit();
}

echo $init_html_ajax."</head>";
echo GetHeader($session);

?>
<body onload="UserLookup.initialize('<?php echo $session; ?>','rulessearch');">
<?php
echo "<table width=100%><tr align=center><td>";

echo "<br><br><form method=post action=\"rulessearch.php\" name=\"rulessearch\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<table><caption><b>Rules Meetings Search: All Meetings by One Host</b><hr></caption>";
echo "<tr align=center valign=top><td><b>Host Name:</b></td>";
echo "<input type=hidden name=\"hostnameid\">";
echo "<td><input type=text class=tiny size=35 name=\"hostname\" id=\"hostname\" value=\"$hostname\" onkeyup=\"UserLookup.lookup('hostname',this.value,'','rulessearch')\"><br><div class=\"list\" id=\"hostnameList\"></div>";
echo "</td></tr>";
if($hostname!='')
{
   $sql="SHOW TABLES LIKE '%ruleshosts'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $temp=split("ruleshosts",$row[0]);
      $cursp=$temp[0];
      $table=$row[0];
      $hostname2=addslashes($hostname);
      $sql2="SELECT * FROM $table WHERE hostname='$hostname2' ORDER BY mtgdate";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         $date=split("-",$row2[mtgdate]);
         echo "<tr align=left><td colspan=2><a class=small href=\"ruleshostbyhost.php?session=$session&sport=$cursp&siteid=$row2[id]\">".GetSportName($cursp).": $date[1]/$date[2]/$date[0]</a></td></tr>";
      }
   }
}
echO "</table>";
echO "</form>";
?>
<div id="loading" style="display:none;"></div>
<?php
echo $end_html;
?>
