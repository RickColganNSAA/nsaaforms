<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

//Figure out what the last year archived was.  Will show those rosters below current ones:
$year=date("Y"); $year1=$year+1; $year0=$year-1;
$archivedbroster="$db_name2".$year0.$year;
$sql="SHOW DATABASES LIKE '$archivedbroster'";
$result=mysql_query($sql);
$archiveroster=0;
if(mysql_num_rows($result)==0)
{
   $year00=$year0-1;
   $archivedbroster="$db_name2".$year00.$year0;
   $curyearroster="$year0-$year";
   $lastyearroster="$year00-$year0";
   $sql="SHOW DATABASES LIKE '$archivedbroster'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) $archiveroster=0;
   else $archiveroster=1;
}
else
{
   $archiveroster=1;
   $curyearroster="$year-$year1";
   $lastyearroster="$year0-$year";
}

echo $init_html;
echo GetHeader($session,"manageoff");

echo "<br><br><table><caption style=\"background-color:#E0E0E0\"><b>Manage Officials:</b></caption>";
echo "<tr align=left><td><ul class=menu>";
echo "<li><a href=\"#\" onClick=\"window.open('add_off.php?session=$session','addoff','menubar=yes,resizable=yes,scrollbars=yes,titlebar=yes,width=600,height=700');\">Add New Official</a></li>";
echo "<li class=bigger><b>Find Officials:</b><ul>";

        echo "<li><form method=post action=\"officials.php\">";
        echo "<input type=hidden name=session value=\"$session\">";
        echo "<b>by Last Name: <input type=text name=\"lastname\" size=20>";
        echo "&nbsp;<input type=submit name=go value=\"Go\"></form></li>";

	echo "<li><form method=post action=\"officials.php\">";
	echo "<input type=hidden name=session value=\"$session\">";
	echo "<b>by Sport: <select name=sport><option>All Sports";
	for($i=0;$i<count($activity);$i++)
	{
   		echo "<option value='$activity[$i]'>$act_long[$i]";
	}
	echo "</select>";
	echo "&nbsp;<input type=submit name=go value=\"Go\"></form></li>";

	echo "<li><a href=\"off_query.php?session=$session\">Officials Advanced Search</a></li>";

echo "</ul></li><li class=bigger><b>Manage Officials:</b><ul>";

	echo "<li><a href=\"classificationsettings.php?session=$session\">Manage Classification Settings</a></li>";
	echo "<li><a href=\"mergeoffs.php?session=$session\">Merge 2 Officials' Records</a></li>";
	echo "<li><a href=\"photosadmin.php?session=$session\">Officials' Profile Pictures ADMIN</a></li>";

echo "</ul><li class=bigger><b>Reports:</b><ul>";

	echo "<li><a href=\"addressbook2.php?session=$session\">Address Book (Get Officials' Emails)</a></li>";
	echo "<li><a target=new href=\"bbclinicnames.php?session=$session\">Basketball Clinic Attendees (".GetSchoolYear(date("Y"),date("n")).")</a></li>";
	echo "<li><a target=\"_blank\" href=\"wrclinicnames.php?session=$session\">Wrestling Clinic Attendees (".GetSchoolYear(date("Y"),date("n")).")</a></li>";
	echo "<li><a target=new href=\"fbcrewexport.php?session=$session\">Football Crew Information Export</a></li>";
	echo "<li><a href=\"mailnumsummary.php?session=$session\">Mailing Number Summary</a></li>";
	echo "<li><a href=\"convictions.php?session=$session\">Officials with Misdemeanor or Felony Convictions</a></li>";
	echo "<li><b>NFHS Export:</b>";
	echo " <a target=new onclick=\"return confirm('Are you sure you want to continue with this export? These officials will be updated in the database as Sent to the NFHS.');\" href=\"fedexport.php?session=$session\">Export File of Paid Officials for Federation</a></li>";
	echo "<li><form method=post action=\"roster.php\" target=new>";
	echo "<input type=hidden name=session value=\"$session\">";
	if($archiveroster==1) echo "<b>$curyearroster Officials Rosters:&nbsp;</b>";
	echo "<select name=sport onchange=\"submit();\">";
	$sql="SELECT * FROM rosters ORDER BY sport";
	$result=mysql_query($sql);
	while($row=mysql_fetch_array($result))
	{
   		//if active='x', will show on AD and officials pages
   		echo "<option value=\"$row[sport]\">".GetSportName($row[sport]);
   		if($row[active]=='x') echo " (active)";
   		else echo " (inactive)";
   		echo "</option>";
	}
	echo "</select><input type=submit name=go value=\"Go\"></form></li>";
	if($archiveroster==1)
	{
   		//if showold='x', archived rosters will show on AD and officials pages
   		echo "<li><form method=post action=\"roster.php\" target=new>";
   		echo "<input type=hidden name=session value=\"$session\">";
   		echo "<b>$lastyearroster Officials Rosters:&nbsp;</b>";
   		echo "<input type=hidden name=archive value=\"$archivedbroster\">";
   		echo "<select name=sport onchange=\"submit();\">";
   		$sql="SELECT * FROM rosters ORDER BY sport";
   		$result=mysql_query($sql);
   		while($row=mysql_fetch_array($result))
   		{
      			echo "<option value=\"$row[sport]\">".GetSportName($row[sport]);
      			if($row[showold]=='x') echo " (active)";
      			else echo " (inactive)";
      			echo "</option>";
   		}
   		echo "</select><input type=submit name=go value=\"Go\"></li>";
	}

echo "</ul></li>";

echo "</ul></td></tr></table>";

echo $end_html;
?>
