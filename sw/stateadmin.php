<?php
/***************************
stateadmin.php
NSAA Swimming State Entry Forms Admin
Created 2/17/09
Author Ann Gaffigan
****************************/
require '../functions.php';
require '../variables.php';
require 'swfunctions.php';
require '../../calculate/functions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

echo $init_html;
echo $header;

echo "<br>";
echo "<table cellspacing='0' cellpadding='5'><caption><b>State Swimming Entry Forms Submitted to the NSAA:</b><hr></caption>";
echo "<tr valign='top' align='left'><td><b><u>BOYS:</b></u>&nbsp;&nbsp;&nbsp;<!--<a href=\"printnonapproved.php?session=$session&gender=b\" class=\"small\" target=\"_blank\">Print all NON-APPROVED Boys Verification Forms</a>--><br><br>";
	if(!$sort) $sort="stateform_b DESC";
	$sql="SELECT * FROM swschool WHERE stateform_b!='' ORDER BY $sort";
	$result=mysql_query($sql);
	echo "<table cellspacing='0' cellpadding='2' frame=all rules=all style=\"border:#808080 1px solid;\">";
	if(mysql_num_rows($result)>0)
  	{
   		echo "<tr align=center>";
		if($sort=="school DESC")
		{
   			$curimg="arrowup.png"; $cursort="school ASC";
		}
		else if($sort=="school ASC")
		{
   			$curimg="arrowdown.png"; $cursort="school DESC";
		}
		else
		{
   			$curimg=""; $cursort="school DESC";
		}
		echo "<td><a class=small href=\"stateadmin.php?session=$session&sort=$cursort\">School (click for form)</a>";
		if(ereg("school",$sort))
   			echo "&nbsp;<a href=\"stateadmin.php?session=$session&sort=$cursort\"><img border=0 src=\"../$curimg\" width=10></a>";
		echo "</td>";
                if($sort=="stateform_b DESC")
                {
                        $curimg="arrowup.png"; $cursort="stateform_b ASC";
                }
                else if($sort=="stateform_b ASC")
                {
                        $curimg="arrowdown.png"; $cursort="stateform_b DESC";
                }
                else
                {
                        $curimg=""; $cursort="stateform_b DESC";
                }
                echo "<td><a class=small href=\"stateadmin.php?session=$session&sort=$cursort\">Date Submitted</a>";
                if(ereg("stateform",$sort))
                        echo "&nbsp;<a href=\"stateadmin.php?session=$session&sort=$cursort\"><img border=0 src=\"../$curimg\" width=10></a>";
                echo "</td></tr>";
	}
	while($row=mysql_fetch_array($result))
	{
		$schoolch=GetMainSchoolName($row[sid],'sw');
		echo "<tr align=left><td><a class=small href=\"sw_state_view_b.php?session=$session&school_ch=$schoolch&print=1\" target=\"_blank\">$row[school]</td>";
		echo "<td>".date("M j, Y",$row[stateform_b])."</td>";
		echo "</tr>";
	}
	if(mysql_num_rows($result)==0) echo "<tr align=center><td>(NONE)</td></tr>";
	echo "</table>";
echo "</td><td><b><u>GIRLS:</b></u>&nbsp;&nbsp;&nbsp;<!--<a href=\"printnonapproved.php?session=$session&gender=g\" class=\"small\" target=\"_blank\">Print all NON-APPROVED Girls Verification Forms</a>--><br><br>";
        if(!$sortg) $sortg="stateform_g DESC";
        $sql="SELECT * FROM swschool WHERE stateform_g!='' ORDER BY $sortg";
        $result=mysql_query($sql);
        echo "<table cellspacing='0' cellpadding='2' frame=all rules=all style=\"border:#808080 1px solid;\">";
        if(mysql_num_rows($result)>0)
        {
                echo "<tr align=center>";
                if($sortg=="school DESC")
                {
                        $curimg="arrowup.png"; $cursort="school ASC";
                }
                else if($sortg=="school ASC")
                {
                        $curimg="arrowdown.png"; $cursort="school DESC";
                }
                else
                {
                        $curimg=""; $cursort="school DESC";
                }
                echo "<td><a class=small href=\"stateadmin.php?session=$session&sort=$cursort\">School (click for form)</a>";
                if(ereg("school",$sortg))
                        echo "&nbsp;<a href=\"stateadmin.php?session=$session&sort=$cursort\"><img border=0 src=\"../$curimg\" width=10></a>";
                echo "</td>";
                if($sortg=="stateform_g DESC")
                {
                        $curimg="arrowup.png"; $cursort="stateform_g ASC";
                }
                else if($sortg=="stateform_g ASC")
                {
                        $curimg="arrowdown.png"; $cursort="stateform_g DESC";
                }
                else
                {
                        $curimg=""; $cursort="stateform_g DESC";
                }
                echo "<td><a class=small href=\"stateadmin.php?session=$session&sort=$cursort\">Date Submitted</a>";
                if(ereg("stateform",$sortg))
                        echo "&nbsp;<a href=\"stateadmin.php?session=$session&sort=$cursort\"><img border=0 src=\"../$curimg\" width=10></a>";
                echo "</td></tr>";
        }
        while($row=mysql_fetch_array($result))
        {
                $schoolch=GetMainSchoolName($row[sid],'sw');
                echo "<tr align=left><td><a class=small href=\"sw_state_view_g.php?session=$session&school_ch=$schoolch&print=1\" target=\"_blank\">$row[school]</td>";
                echo "<td>".date("M j, Y",$row[stateform_g])."</td>";
                echo "</tr>";
        }
        if(mysql_num_rows($result)==0) echo "<tr align=center><td>(NONE)</td></tr>";
        echo "</table>";
echo "</td></tr></table>";

echo $end_html;
?>
