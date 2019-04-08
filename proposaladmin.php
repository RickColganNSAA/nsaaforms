<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php");
   exit();
}

echo $init_html;
echo $header;

if($save)
{
   $showstart=$yr1."-".$mo1."-".$day1;
   $showend=$yr2."-".$mo2."-".$day2;
   $duedate=$yr."-".$mo."-".$day;
   $sql="UPDATE proposaladmin SET showstart='$showstart', showend='$showend', duedate='$duedate' WHERE type='Legislative'";
   $result=mysql_query($sql);
   $showstart=$yr3."-".$mo3."-".$day3;
   $showend=$yr4."-".$mo4."-".$day4;
   $duedate=$yr5."-".$mo5."-".$day5;
   $sql="UPDATE proposaladmin SET showstart='$showstart', showend='$showend', duedate='$duedate' WHERE type='Class Caucus'";
   $result=mysql_query($sql);

   for($i=0;$i<count($id);$i++)
   {
      if($delete[$i]=='x')
      {
	 $sql="DELETE FROM proposals WHERE id='$id[$i]'";
	 $result=mysql_query($sql);
	 //echo $sql."<br>".mysql_error();
      }
   }
   for($i=0;$i<count($id);$i++)
   {
      $notes[$i]=addslashes($notes[$i]);
      $sql="UPDATE proposals SET verify='$verify[$i]',locked='$locked[$i]',notes='$notes[$i]' WHERE id='$id[$i]'";
      $result=mysql_query($sql);
   }

   $sql="UPDATE logins SET caucusproposal=''";
   $result=mysql_query($sql);
   for($i=0;$i<count($loginid);$i++)
   {
      $sql="UPDATE logins SET caucusproposal='x' WHERE id='$loginid[$i]'";
      $result=mysql_query($sql);
   }
}

echo "<form method=post action=\"proposaladmin.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<br><table cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#808080 1px solid;width:800px;\" class='nine'><caption><b>Submitted Proposals for Change in NSAA Regulations:</b>";
//GET LEGISLATIVE DATES
$sql="SELECT * FROM proposaladmin WHERE type='Legislative'";	
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$temp=split("-",$row[showstart]);
$showstart=mktime(0,0,0,$temp[1],$temp[2],$temp[0]);
$temp=split("-",$row[showend]);
$showend=mktime(0,0,0,$temp[1],$temp[2],$temp[0]);
$temp=split("-",$row[duedate]);
$duedate=mktime(0,0,0,$temp[1],$temp[2],$temp[0]);
$lastarchive=$row[lastarchive];
//GET CLASS CAUCUS DATES
$sql="SELECT * FROM proposaladmin WHERE type='Class Caucus'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$temp=split("-",$row[showstart]);
$showstart2=mktime(0,0,0,$temp[1],$temp[2],$temp[0]);
$temp=split("-",$row[showend]);
$showend2=mktime(0,0,0,$temp[1],$temp[2],$temp[0]);
$temp=split("-",$row[duedate]);
$duedate2=mktime(0,0,0,$temp[1],$temp[2],$temp[0]);
$lastarchive2=$row[lastarchive];

$year=date("Y"); $year1=$year-1;

//SHOW ARCHIVE OPTIONS:
if($lastarchive!="$year1-$year")
{
   if($archive=='yes')
      echo "<p><font style=\"color:red;font-size:9pt;\">The $year1-$year Proposals have been Archived.</font></p>";
   else
      echo "<p><a href=\"proposalarchive.php?session=$session\" onClick=\"return confirm('Are you sure you want to archive the $year1-$year Proposals?  You will no longer be able to view these proposals on this screen, but they will be archived into the $year1-$year database.');\">Click Here to Archive the $year1-$year Proposals</a></p>";
}
else
{
   echo "<p><font style=\"font-size:9pt;\"><i>(The <b>$year1-$year</b> proposals have been archived.)</i></font></p>";
}

echo "</b><div class='normalwhite'>";
if($save)
   echo "<div class='alert'>Your changes have been saved.</div>";
echo "<h3><u>Legislative Proposals:</u></h3><p><b>Display LEGISLATIVE Proposals</b> from&nbsp;";
echo "<select name=\"mo1\">";
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option value=\"$m\"";
   if(date("m",$showstart)==$m) echo " selected";
   echo ">$m</option>";
}
echo "</select>/<select name=\"day1\">";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option value=\"$d\"";
   if(date("d",$showstart)==$d) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select name=\"yr1\">";
echo "<option";
if(date("Y",$showstart)==$year1) echo " selected";
echo ">$year1</option><option";
if(date("Y",$showstart)==$year) echo " selected";
echo ">$year</option><option";
$year_1=$year+1;
if(date("Y",$showstart)==$year_1) echo " selected";
echo ">$year_1</option></select> to&nbsp;";
echo "<select name=\"mo2\">";
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option value=\"$m\"";
   if(date("m",$showend)==$m) echo " selected";
   echo ">$m</option>";
}
echo "</select>/<select name=\"day2\">";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option value=\"$d\"";
   if(date("d",$showend)==$d) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select name=\"yr2\">";
echo "<option";
if(date("Y",$showend)==$year1) echo " selected";
echo ">$year1</option><option";
if(date("Y",$showend)==$year) echo " selected";
echo ">$year</option><option";
if(date("Y",$showend)==$year_1) echo " selected";
echo ">$year_1</option></select></p>";
echo "<p>The <b>Due Date</b> for LEGISLATIVE Proposals is:&nbsp;";
echo "<select name=\"mo\">";
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option value=\"$m\"";
   if(date("m",$duedate)==$m) echo " selected";
   echo ">$m</option>";
}
echo "</select>/<select name=\"day\">";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option value=\"$d\"";
   if(date("d",$duedate)==$d) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select name=\"yr\">";
echo "<option";
if(date("Y",$duedate)==$year1) echo " selected";
echo ">$year1</option><option";
if(date("Y",$duedate)==$year) echo " selected";
echo ">$year</option><option";
$year_1=$year+1;
if(date("Y",$duedate)==$year_1) echo " selected";
echo ">$year_1</option></select></p>";
//CLASS CAUCUS DATES:
echo "<h3><u>Class Caucus Proposals:</u></h3><p><b>Display CLASS CAUCUS Proposals</b> from ";
echo "<select name=\"mo3\">";
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option value=\"$m\"";
   if(date("m",$showstart2)==$m) echo " selected";
   echo ">$m</option>";
}
echo "</select>/<select name=\"day3\">";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option value=\"$d\"";
   if(date("d",$showstart2)==$d) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select name=\"yr3\">";
echo "<option";
if(date("Y",$showstart2)==$year1) echo " selected";
echo ">$year1</option><option";
if(date("Y",$showstart2)==$year) echo " selected";
echo ">$year</option><option";
$year_1=$year+1;
if(date("Y",$showstart2)==$year_1) echo " selected";
echo ">$year_1</option></select> to ";
echo "<select name=\"mo4\">";
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option value=\"$m\"";
   if(date("m",$showend2)==$m) echo " selected";
   echo ">$m</option>";
}
echo "</select>/<select name=\"day4\">";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option value=\"$d\"";
   if(date("d",$showend2)==$d) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select name=\"yr4\">";
echo "<option";
if(date("Y",$showend)==$year1) echo " selected";
echo ">$year1</option><option";
if(date("Y",$showend2)==$year) echo " selected";
echo ">$year</option><option";
if(date("Y",$showend2)==$year_1) echo " selected";
echo ">$year_1</option></select>";
echo "</p>";
echo "<p>The <b>Due Date</b> for CLASS CAUCUS Proposals is:&nbsp;";
echo "<select name=\"mo5\">";
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option value=\"$m\"";
   if(date("m",$duedate2)==$m) echo " selected";
   echo ">$m</option>";
}
echo "</select>/<select name=\"day5\">";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;
   echo "<option value=\"$d\"";
   if(date("d",$duedate2)==$d) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select name=\"yr5\">";
echo "<option";
if(date("Y",$duedate2)==$year1) echo " selected";
echo ">$year1</option><option";
if(date("Y",$duedate2)==$year) echo " selected";
echo ">$year</option><option";
$year_1=$year+1;
if(date("Y",$duedate2)==$year_1) echo " selected";
echo ">$year_1</option></select>";
echo "</p>";
echo "<p><b>Select administrators allowed to access Class Caucus Proposals:</b>&nbsp;&nbsp;";
echo "(Edit <b>Large School Group</b> logins <a href=\"largeschools.php?session=$session\" target=\"_blank\">HERE</a>.)</p>";
//CAUCUS PROPOSAL ACCESS:
   //FIRST GET ARRAY OF AD's, Supers AND LEVEL 5's
   //LEVEL 5's at the top:
$sql="SELECT * FROM logins WHERE level=5 ORDER BY school";
$result=mysql_query($sql);
$logins[id]=array(); $logins[name]=array(); $i=0;
while($row=mysql_fetch_array($result))
{
   $logins[id][$i]=$row[id];
   $logins[name][$i]="$row[school]: $row[name]";
   $i++;
}
$sql="SELECT * FROM logins WHERE (sport='Superintendent' OR level=2) ORDER BY school,sport";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $logins[id][$i]=$row[id];
   $logins[name][$i]="$row[school]: $row[name]";
   if($row[sport]=="Superintendent") $logins[name][$i].=" (Sup)";
   else if($row[level]==2) $logins[name][$i].=" (AD)";
   $i++;
}
$sql="SELECT * FROM logins WHERE caucusproposal='x' ORDER BY school";
$result=mysql_query($sql);
$ct=mysql_num_rows($result);
$max=$ct+2;
if($max<8) $max=8;
if($max%2>0) $max++;
$percol=$max/2;
echo "<table cellspacing=0 cellpadding=3><tr align=left><td>";
$i=0; 
while($row=mysql_fetch_array($result))
{
   echo "<select name=\"loginid[$i]\"><option value=\"0\">Select Name/School</option>";
   for($j=0;$j<count($logins[id]);$j++)
   {
      echo "<option value=\"".$logins[id][$j]."\"";
      if($row[id]==$logins[id][$j]) echo " selected";
      echo ">".$logins[name][$j]."</option>";
   }
   echo "</select><br>";
   $i++;
   if($i==$percol)
      echo "</td><td>";
}
while($i<$max)
{
   echo "<select name=\"loginid[$i]\"><option value=\"0\">Select Name/School</option>";
   for($j=0;$j<count($logins[id]);$j++)
   {
      echo "<option value=\"".$logins[id][$j]."\"";
      echo ">".$logins[name][$j]."</option>";
   }
   echo "</select><br>";
   $i++;
   if($i==$percol)
      echo "</td><td>";
}
echo "</td></tr></table>";
echo "<br><input class=\"fancybutton2\" type=submit name=\"save\" value=\"Save\"></div><br><br></caption>";

//Get Proposals
$sql="SELECT * FROM proposals WHERE filename!='' ORDER BY school,type,datesub DESC";
$result=mysql_query($sql);
$ct=mysql_num_rows($result);
if($ct>0)
{
   echo "<tr align=center><td><b>Delete</b></td>";
   echo "<td><b>Proposal</b></td><td align=80><b>Date Submitted</b></td><td><b>Verified</b></td>";
   echo "<td><b>Locked</b></td><td><b>Notes</b></td></tr>";
}
else
{
   echo "<tr align=center><td>[There are currently no submitted proposals for this year.]</td></tr>";
}
$cursch=""; $ix=0; $x=0;
while($row=mysql_fetch_array($result))
{
   if($cursch!=$row[school])	//new group of school's proposals
   {
      $ix++;
      $cursch=$row[school];
      echo "<tr align=left>";
      echo "<td colspan=6>";
      echo "<b>$cursch:</b></td></tr>";
   }
   $filename=$row[filename];
   $date=date("M d, Y",$row[datesub]);
   echo "<input type=hidden name=\"id[$x]\" value=\"$row[id]\">";
   echo "<tr align=center><td><input type=checkbox name=\"delete[$x]\" value='x'></td>";
   echo "<td align=left>";
   if($row[type]=="caucus") echo "Class Caucus Proposal ";
   else echo "Legislative Proposal ";
   echo "(<a target=new href=\"attachments/$filename\">View</a>)&nbsp;";
   echo "(<a target=new href=\"proposal.php?session=$session&givenid=$row[id]&school_ch=$row[school]\">Edit</a>)</td>";
   echo "<td align=left width=80>$date</td>";
   echo "<td><input type=checkbox name=\"verify[$x]\" value='x'";
   if($row[verify]=='x') echo " checked";
   echo "></td>";
   echo "<td><input type=checkbox name=\"locked[$x]\" value='x'";
   if($row[locked]=='x') echo " checked";
   echo "></td>";
   echo "<td align=left><input type=text name=\"notes[$x]\" value=\"$row[notes]\" size=40></td></tr>";
   $x++;
}
echo "</td></tr>";
//echo "<tr align=center><td><input type=submit name=submit value=\"Delete Checked\"></td></tr>";
echo "</table>";
if($x>0)
   echo "<br><input type=submit name=\"save\" value=\"Save Changes\">";
echo "<br><br><a href=\"welcome.php?session=$session\" class=small>Home</a>";
echo $end_html;
?>
