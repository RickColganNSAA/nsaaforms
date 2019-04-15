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
$sportname=GetSportName($sport);
$table=$sport."ruleshosts";

if($save)	//add assignment to database
{
   $hostname=ereg_replace("`","'",$hostname);
   $hostname2=addslashes($hostname);
   $mtgdate="$year-$month-$day";
   $mtgtime2=addslashes($mtgtime);
   $sql="INSERT INTO $table (type,mtgdate,mtgtime,hostname) VALUES ('$type','$mtgdate','$mtgtime2','$hostname2')";
   $result=mysql_query($sql);
//echo $sql."<br>";
   if(mysql_error())
   {
      echo $init_html_ajax."</head>";
      echo GetHeader($session,"contractsadmin");
      echo "<br><br><b>ERROR:</b> ".mysql_error();
      echo $end_html;
   }
   else
   {
      $sql="SELECT id FROM $table WHERE type='$type' AND mtgdate='$mtgdate' AND mtgtime='$mtgtime2' AND hostname='$hostname2' ORDER BY id DESC LIMIT 1";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $siteid=$row[0];
      header("Location:ruleshostbyhost.php?session=$session&sport=$sport&siteid=$siteid&added=1");
   }
   exit();
}
echo $init_html_ajax."</head>";
?>
<body onload="UserLookup.initialize('<?php echo $session; ?>','assignform');">
<?php
echo GetHeader($session,"contractsadmin");
if(!$sport || $sport=='')
{
   echo "<br><br>No sport selected.";
   echo $end_html;
   exit();
}

echo "<form method=post action=\"addruleshost.php\" name=\"assignform\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=sport value=\"$sport\">";
echo "<br><a class=small href=\"rulescontracts.php?session=$session&sport=$sport\">$sportname Rules Meeting Host Contracts MAIN MENU</a><br><br>";
echo "<table><caption><b>Assign New Rules Meeting Host ($sportname):</b><hr></caption>";
echo "<tr valign=top align=left><th align=left>Type:</td>";
echo "<td><input type=radio name=\"type\" value=\"Originating\"";
if($type=="Originating") echo " checked";
echo "> Originating<br>";
echo "<input type=radio name=\"type\" value=\"Receiving\"";
if($type=="Receiving") echo " checked";
echO "> Receiving<br>";
echo "<input type=radio name=\"type\" value=\"Regular\"";
if($type=="Regular") echo " checked";
echo "> Regular</td></tr>";
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
echO "<td><input type=text class=tiny size=35 name=\"hostname\" id=\"hostname\" value=\"$hostname\" onkeyup=\"UserLookup.lookup('hostname',this.value,'','ruleshosts');\"><div class=\"list\" id=\"hostnameList\"></div></td></tr>";
echo "<tr align=center><td colspan=2><input type=submit name=save value=\"Save Assignment\"></td></tr>";
echo "</table>";
echo "</form>";
?>
<div id="loading" style="display:none;"></div>
<?php
echo $end_html;
?>
