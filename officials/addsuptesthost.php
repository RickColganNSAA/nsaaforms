<?php
require 'functions.php';
require_once('variables.php');
$db=mysql_connect($db_host2,$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

if(!ValidUser($session))
{
   header("Location:/nsaaforms/officials/index.php?error=1");
   exit();
}
$table="suptesthosts";

if($save)	//add assignment to database
{
   $hostname=ereg_replace("`","'",$hostname);
   $hostname2=addslashes($hostname);
   $mtgdate="$year-$month-$day";
   $mtgtime2=addslashes($mtgtime);
   $tests="";
   for($i=0;$i<count($sports);$i++)
   {
      if($test[$i]=='x') $tests.=$sports[$i]."/";
   }
   $tests=substr($tests,0,strlen($tests)-1);
   $sql="INSERT INTO $table (mtgdate,mtgtime,hostname,sports) VALUES ('$mtgdate','$mtgtime2','$hostname2','$tests')";
   $result=mysql_query($sql);
   if(mysql_error())
   {
      echo $init_html_ajax."</head>";
      echo GetHeader($session,"contractsadmin");
      echo "<br><br><b>ERROR:</b> ".mysql_error();
      echo $end_html;
   }
   else
   {
      $sql="SELECT id FROM $table WHERE mtgdate='$mtgdate' AND mtgtime='$mtgtime2' AND hostname='$hostname2' ORDER BY id DESC LIMIT 1";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $siteid=$row[0];
      header("Location:suptesthostbyhost.php?session=$session&siteid=$siteid&added=1");
   }
   exit();
}
echo $init_html_ajax."</head>";
?>
<body onload="UserLookup.initialize('<?php echo $session; ?>','assignform');">
<?php
echo GetHeader($session,"contractsadmin");

echo "<form method=post action=\"addsuptesthost.php\" name=\"assignform\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<br><a class=small href=\"suptestcontracts.php?session=$session\">Supervised Test Host Contracts MAIN MENU</a><br><br>";
echo "<table><caption><b>Assign New Supervised Test Host:</b><hr></caption>";
echo "<tr align=left><th align=left>Date & Time:</th>";
echo "<td><select name=\"month\"><option value=''>MM</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option";
   if($month==$m) echo " selected";
   echO ">$m</option>";
}
echo "</select> / <select name=\"day\"><option value=''>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option";
   if($day==$d) echo " selected";
   echO ">$d</option>";
}
echo "</select> / <select name=\"year\"><option value=''>YYYY</option>";
if(!$year || $year=='') $year=date("Y");
$year1=$year+1;
for($i=$year;$i<=$year1;$i++)
{
   echo "<option";
   if($year==$i) echo " selected";
   echo ">$i</option>";
}
echo "</select> at <input type=text class=tiny size=15 name=\"mtgtime\" value=\"7:00 PM local time\"></td></tr>";
echo "<tr align=left valign=top><th align=left>Host:</th>";
echO "<td><input type=text class=tiny size=35 name=\"hostname\" id=\"hostname\" value=\"$hostname\" onkeyup=\"UserLookup.lookup('hostname',this.value,'','newsuptesthosts');\"><div class=\"list\" id=\"hostnameList\"></div></td></tr>";
echo "<tr align=left valign=top><th align=left>Tests:</th>";
echo "<td>";
$sql="SHOW TABLES LIKE '%off'";
$result=mysql_query($sql);
$ix=0;
echo "<table><tr align=left>";
while($row=mysql_fetch_array($result))
{
   $temp=split("off",$row[0]);
   $cursp=$temp[0];
   if($cursp!='sw' && $cursp!='di')
   {
      echo "<td><input type=checkbox name=\"test[$ix]\" value=\"x\"> ".GetSportName($cursp)."</td>";
      echo "<input type=hidden name=\"sports[$ix]\" value=\"$cursp\">";
      $ix++;
      if($ix==4)
	 echo "</tr><tr align=left>";
   }
}
echo "</tr></table></td></tr>";
echo "<tr align=center><td colspan=2><input type=submit name=save value=\"Save Assignment\"></td></tr>";
echo "</table>";
echo "</form>";
?>
<div id="debug"></div>
<div id="loading" style="display:none;"></div>
<?php
echo $end_html;
?>
