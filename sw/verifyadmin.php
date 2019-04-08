<?php
/***************************
verifyadmin.php
NSAA Swimming Verification Forms Admin
Created 2/3/09
Author Ann Gaffigan
****************************/
require '../functions.php';
require '../variables.php';
require 'swfunctions.php';

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

if($approveb>0)
{
   $sql="UPDATE sw_verify_b SET approved='y' WHERE id='$approveb'";
   $result=mysql_query($sql);
}
if($approveg>0)
{
   $sql="UPDATE sw_verify_g SET approved='y' WHERE id='$approveg'";
   $result=mysql_query($sql);
}

echo $init_html;
echo $header;

echo "<br>";
if($showapproved==1)
   echo "[Currently showing APPROVED forms. <a href=\"verifyadmin.php?session=$session\" class=small>Show All NON-Approved Forms</a>]<br><br>";
else
   echo "[Currently showing NON-Approved forms. <a href=\"verifyadmin.php?showapproved=1&session=$session\" class=small>Show All APPROVED Forms</a>]<br><br>";
echo "<table cellspacing='0' cellpadding='5'><caption><b>Swimming Verification Forms Submitted to the NSAA:</b><hr></caption>";
echo "<tr valign='top' align='left'><td><b><u>BOYS:</b></u>&nbsp;&nbsp;&nbsp;<a href=\"printnonapproved.php?session=$session&gender=b\" class=\"small\" target=\"_blank\">Print all NON-APPROVED Boys Verification Forms</a><br>";
echo "<br><br>";
	if(!$sort) $sort="datesub DESC";
	$sql="SELECT * FROM sw_verify_b WHERE senttoNSAA='y'";
        if($showapproved==1) $sql.=" AND approved='y'";
        else $sql.=" AND approved!='y'";
        $sql.=" ORDER BY $sort";
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
		echo "<td><a class=small href=\"verifyadmin.php?session=$session&sort=$cursort\">School</a>";
		if(ereg("school",$sort))
   			echo "&nbsp;<a href=\"verifyadmin.php?session=$session&sort=$cursort\"><img border=0 src=\"../$curimg\" width=10></a>";
		echo "</td>";
		echo "<td><b>Verification Form</b></td>";
                if($sort=="datesub DESC")
                {
                        $curimg="arrowup.png"; $cursort="datesub ASC";
                }
                else if($sort=="datesub ASC")
                {
                        $curimg="arrowdown.png"; $cursort="datesub DESC";
                }
                else
                {
                        $curimg=""; $cursort="datesub DESC";
                }
                echo "<td><a class=small href=\"verifyadmin.php?session=$session&sort=$cursort\">Date Submitted</a>";
                if(ereg("datesub",$sort))
                        echo "&nbsp;<a href=\"verifyadmin.php?session=$session&sort=$cursort\"><img border=0 src=\"../$curimg\" width=10></a>";
                echo "</td>";
                if($sort=="approved DESC")
                {
                        $curimg="arrowup.png"; $cursort="approved ASC";
                }
                else if($sort=="approved ASC")
                {
                        $curimg="arrowdown.png"; $cursort="approved DESC";
                }
                else
                {
                        $curimg=""; $cursort="approved DESC";
                }
                echo "<td><a class=small href=\"verifyadmin.php?session=$session&sort=$cursort\">Approved</a>";
                if(ereg("approved",$sort))
                        echo "&nbsp;<a href=\"verifyadmin.php?session=$session&sort=$cursort\"><img border=0 src=\"../$curimg\" width=10></a>";
                echo "</td></tr>";
	}
	while($row=mysql_fetch_array($result))
	{
		echo "<tr align=left><td>$row[school]</td>";
   		$meetname=GetMeetName($row[meetid]);
   		echo "<td><a href=\"view_sw_verify_b.php?school_ch=$row[school]&session=$session&formid=$row[id]\" class=\"small\">";
   		echo "$meetname</a></td>";
		echo "<td>".date("M j, Y",$row[datesub])."</td>";
	  	echo "<td>";
	   	if($row[approved]=='y') echo "APPROVED";
	        else echo "<a class=small href=\"verifyadmin.php?session=$session&sort=$sort&approveb=$row[id]\" onClick=\"return confirm('Are you sure you want to approve this verification form?');\">Click to Verify</a>";
		echo "</td></tr>";
	}
	if(mysql_num_rows($result)==0) echo "<tr align=center><td>(NONE)</td></tr>";
	echo "</table>";
echo "</td><td><b><u>GIRLS:</b></u>&nbsp;&nbsp;&nbsp;<a href=\"printnonapproved.php?session=$session&gender=g\" class=\"small\" target=\"_blank\">Print all NON-APPROVED Girls Verification Forms</a><br><br>";
        $sql="SELECT * FROM sw_verify_g WHERE senttoNSAA='y'";
        if($showapproved==1) $sql.=" AND approved='y'";
        else $sql.=" AND approved!='y'";
        $sql.=" ORDER BY $sort";
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
                echo "<td><a class=small href=\"verifyadmin.php?session=$session&sort=$cursort\">School</a>";
                if(ereg("school",$sort))
                        echo "&nbsp;<a href=\"verifyadmin.php?session=$session&sort=$cursort\"><img border=0 src=\"../$curimg\" width=10></a>";
                echo "</td>";
                echo "<td><b>Verification Form</b></td>";
                if($sort=="datesub DESC")
                {
                        $curimg="arrowup.png"; $cursort="datesub ASC";
                }
                else if($sort=="datesub ASC")
                {
                        $curimg="arrowdown.png"; $cursort="datesub DESC";
                }
                else 
                {
                        $curimg=""; $cursort="datesub DESC";
                }
                echo "<td><a class=small href=\"verifyadmin.php?session=$session&sort=$cursort\">Date Submitted</a>";
                if(ereg("datesub",$sort))
                        echo "&nbsp;<a href=\"verifyadmin.php?session=$session&sort=$cursort\"><img border=0 src=\"../$curimg\" width=10></a>";
                echo "</td>";
                if($sort=="approved DESC")
                {
                        $curimg="arrowup.png"; $cursort="approved ASC";
                }
                else if($sort=="approved ASC")
                {
                        $curimg="arrowdown.png"; $cursort="approved DESC";
                }
                else
                {
                        $curimg=""; $cursort="approved DESC";
                }
                echo "<td><a class=small href=\"verifyadmin.php?session=$session&sort=$cursort\">Approved</a>";
                if(ereg("approved",$sort))
                        echo "&nbsp;<a href=\"verifyadmin.php?session=$session&sort=$cursort\"><img border=0 src=\"../$curimg\" width=10></a>";
		echo "</td></tr>";
	}
        while($row=mysql_fetch_array($result))
        {
                echo "<tr align=left><td>$row[school]</td>";
                $meetname=GetMeetName($row[meetid]);
                echo "<td><a href=\"view_sw_verify_g.php?school_ch=$row[school]&session=$session&formid=$row[id]\" class=\"small\">";
                echo "$meetname</a></td>";
                echo "<td>".date("M j, Y",$row[datesub])."</td>";
                echo "<td>";
                if($row[approved]=='y') echo "APPROVED";
                else echo "<a class=small href=\"verifyadmin.php?session=$session&sort=$sort&approveg=$row[id]\" onClick=\"return confirm('Are you sure you want to approve this verification form?');\">Click to Verify</a>";
                echo "</td></tr>";
        }
        if(mysql_num_rows($result)==0) echo "<tr align=center><td>(NONE)</td></tr>";
        echo "</table>";
echo "</td></tr></table>";

echo $end_html;
?>
