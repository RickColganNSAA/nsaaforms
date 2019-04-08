<?php
/***********************************
schoolregadmin.php
NSAA can administer registrations
submitted via
schoolregistration.php
Created 6/20/11
Author: Ann Gaffigan
************************************/
require '../calculate/functions.php';
require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

if($section=="settings" && $resetmem==1)	//CLEAR OUT schoolmembership TABLE (AND ARCHIVE IT)
{
   $date=date("mdy");

   //MEMBERSHIP
   $sql="CREATE TABLE IF NOT EXISTS `schoolmembership".$date."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schoolid` int(11) NOT NULL,
  `datesubmitted` bigint(20) NOT NULL,
  `amtpaid` float NOT NULL,
  `datepaid` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Can completely remove data each year at the end of APRIL' AUTO_INCREMENT=1";
   $result=mysql_query($sql);
   $error="";
   if(!mysql_error())
   {
      $sql="INSERT INTO schoolmembership".$date." SELECT * FROM schoolmembership";
      $result=mysql_query($sql);
      if(!mysql_error())
      {
         $sql="DELETE FROM schoolmembership";
         $result=mysql_query($sql);
      }
      else $error.="There was an error with the query $sql: ".mysql_error()."<br>";
   }
   else $error.="There was an error with the query $sql: ".mysql_error()."<br>";

   ///REGISTRATION
   $sql="CREATE TABLE IF NOT EXISTS `schoolregistration".$date."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schoolid` int(11) NOT NULL,
  `sport` varchar(10) NOT NULL,
  `participate` varchar(10) NOT NULL,
  `postseason` varchar(10) NOT NULL,
  `possible` varchar(10) NOT NULL,
  `wrfee` varchar(10) NOT NULL,
  `wrfee2` varchar(5) NOT NULL,
  `ccfee` varchar(10) NOT NULL,
  `wrfeedatepaid` date NOT NULL,
  `wrfee2datepaid` date NOT NULL,
  `ccfeedatepaid` date NOT NULL,
  `amtpaid` float NOT NULL,
  `datepaid` date NOT NULL,
  `othersch1` int(11) NOT NULL,
  `othersch2` int(11) NOT NULL,
  `othersch3` int(11) NOT NULL,
  `datesub` bigint(20) NOT NULL,
  `notes` text NOT NULL,
  `signature` varchar(100) NOT NULL,
  `totalfee` float NOT NULL,
  `overrideamtpaid` varchar(10) NOT NULL,
  `latefee` float NOT NULL,
  `overridelatefee` varchar(10) NOT NULL,
  `checkno` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
   $result=mysql_query($sql);
   if(!mysql_error())
   {
      $sql="INSERT INTO schoolregistration".$date." SELECT * FROM schoolregistration";
      $result=mysql_query($sql);
      if(!mysql_error())
      {
   	 $sql="DELETE FROM schoolregistration";
   	 $result=mysql_query($sql);
         //Make sure the NSAA Cup now refers to this new table:
         $sql="UPDATE cupregistrationtable SET tablename='schoolregistration".$date."'";
         $result=mysql_query($sql);
      }
      else $error.="There was an error with the query $sql: ".mysql_error()."<br>";
   }
   else $error.="There was an error with the query $sql: ".mysql_error()."<br>";

   if($error!='')
   {
      header("Location:schoolregadmin.php?session=$session&resetmemed=1");
      exit();
   }
}
if($section=="settings" && $savesettings)
{
   $sql="UPDATE misc_duedates SET showdate='$showy-$showm-$showd', duedate='$duey-$duem-$dued' WHERE sport='membership'";
   $result=mysql_query($sql);
   $sql="UPDATE misc_duedates SET duedate='$fally-$fallm-$falld' WHERE sport='registration_fall'";
   $result=mysql_query($sql);
   $sql="UPDATE misc_duedates SET duedate='$springy-$springm-$springd' WHERE sport='registration_spring'";
   $result=mysql_query($sql);
   $sql="UPDATE misc_duedates SET duedate='$wintery-$winterm-$winterd' WHERE sport='registration_winter'";
   $result=mysql_query($sql);
}

echo $init_html;
echo $header;

//GET SCHOOL YEAR
if(date("m")>=5) $year1=date("Y");
else $year1=date("Y")-1;
$year2=$year1+1;

echo "<form method=post action=\"schoolregadmin.php\">";
echo "<input type=\"hidden\" name=\"section\" value=\"$section\">";
echo "<input type=hidden name=session value=\"$session\">";

if($filterschoolid)
{
   $filteracts="any";
   $mo1='00'; $day1='00'; $mo2='00'; $day2='00';
   $filterpossible=''; $filterpostseason="";
   $filterunpaid=''; $filtersubmitted="";
}
echo "<br><h1>NSAA Membership & Activities Registration:</h1>";
echo "<p><a href=\"schoolregistration.php?session=$session\" target=\"_blank\">Preview Membership & Activities Registration Form</a></p>";

/***** NAVIGATION *****/
echo "<div class=\"mini-tabs-wrapper\" style=\"max-width:900px;\">
        <ul class=\"mini-tabs\">
        <li";
if($section=="" || !$section) echo " class=\"current\"";
echo "><a href=\"schoolregadmin.php?session=$session\">Search Forms & Run Reports</a></li>
        <li";
if($section=="settings") echo " class=\"current\"";
echo "><a href=\"schoolregadmin.php?session=$session&section=settings\"";
echo ">Settings</a></li>
        </ul>
        </div>";
/***** NAVIGATION *****/

if($section=="" || !$section)
{
echo "<table cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#a0a0a0 1px solid;\">";
echo "<caption>";
if($resetmemed==1)
   echo "<div class='alert'>The membership and activities registration forms for the year have been archived and reset.</div><br>";
else if($error!='')
   echo "<div class='error'>$error</div>";
echo "<div class='alert' style='width:650px'>";
echo "<b>FILTER BY:</b><br><br>";
echo "<input type=radio name='filtersubmitted' value='3'";
if($filtersubmitted==3 || !$filtersubmitted) echo " checked";
echo "> <font style='font-size:10pt;'>Schools that have <b>SUBMITTED</b> their MEMBERSHIP online</font><br />";
echo "<br><div style='margin-left:50px;'>";
echo "<b><u>AND</u> who were MARKED AS PAID on or between:</b><br>";
echo "on/between <select name=\"mmo1\"><option value='00'>MM</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option value='$m'";
   if($mmo1==$m) echo " selected";
   echo ">$m</option>";
}
echo "</select>/<select name=\"mday1\"><option value='00'>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;   echo "<option value='$d'";
   if($mday1==$d) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select name=\"myr1\">";
$sql="SELECT DISTINCT YEAR(datepaid) AS year FROM schoolmembership WHERE YEAR(datepaid)>0 ORDER BY year";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
	echo "<option value='".date("Y")."' >".date("Y")."</option>";

else {
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[year]\"";
   if($myr2==$row[year]) echo " selected";
   echo ">$row[year]</option>";
}


}
echo "</select> and ";
echo "<select name=\"mmo2\"><option value='00'>MM</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option value='$m'";
   if($mmo2==$m) echo " selected";
   echo ">$m</option>";
}
echo "</select>/<select name=\"mday2\"><option value='00'>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;   echo "<option value='$d'";
   if($mday2==$d) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select name=\"myr2\">";
$sql="SELECT DISTINCT YEAR(datepaid) AS year FROM schoolmembership WHERE YEAR(datepaid)>0 ORDER BY year";
$result=mysql_query($sql);

if(mysql_num_rows($result)==0)
	echo "<option value='".date("Y")."' >".date("Y")."</option>";

else {
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[year]\"";
   if($myr2==$row[year]) echo " selected";
   echo ">$row[year]</option>";
}


}

echo "</select><br><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(If you want to see memberships for ONE DAY ONLY, leave the second date field blank.)</i><br><br>";
echo "<b><u>AND</u> <input type=checkbox name=\"mfilterunpaid\" id=\"mfilterunpaid\" value='x'";
if($mfilterunpaid=='x') echo " checked";
echo "> who still have PAYMENT DUE</b>";
echo "<br /><br /></div>";
echo "<input type=radio name='filtersubmitted' value='4'";
if($filtersubmitted==4) echo " checked";
echo "> <font style='font-size:10pt;'>Schools that have <b>NOT submitted</b> their MEMBERSHIP online</font>";
echo "<hr />";
echo "<p><b><u>OR</b></u></p><input type=radio name='filtersubmitted' value='1'";
if($filtersubmitted==1) echo " checked";
echo "> <font style='font-size:10pt;'>Schools that have <b>SUBMITTED</b> their registration online for:&nbsp;</font>";
echo "<select name=\"filteracts\">";
echo "<option value='any'";
if(!$filteracts) $filteracts="any";
if($filteracts=="any") echo " selected";
echo ">ANY Activity</option>";
$curseason="";
for($i=0;$i<count($regacts);$i++)
{
   if($regactseasons[$i]!=$curseason)
   {
      $curseason=$regactseasons[$i];
      echo "<option value='$curseason'";
      if($filteracts==$curseason) echo " selected";
      echo ">ANY $curseason Sport";
      if($curseason=="Winter") echo " or Non-Athletic Activity";
      echo "</option>";
   }
   echo "<option value='$regacts[$i]'";
   if($filteracts==$regacts[$i]) echo " selected";
   echo ">".GetActivityName($regacts[$i])."</option>";
}
echo "</select>";
echo "<br><div style='margin-left:50px;'><br>";
echo "<b><u>AND</u> who were MARKED AS PAID on or between:</b><br>";
echo "on/between <select name=\"mo1\"><option value='00'>MM</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option value='$m'";
   if($mo1==$m) echo " selected";
   echo ">$m</option>";
}
echo "</select>/<select name=\"day1\"><option value='00'>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;   echo "<option value='$d'";
   if($day1==$d) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select name=\"yr1\">";
$sql="SELECT DISTINCT YEAR(datepaid) AS year FROM schoolregistration WHERE YEAR(datepaid)>0 ORDER BY year";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
	echo "<option value='".date("Y")."' >".date("Y")."</option>";

else {
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[year]\"";
   if($myr2==$row[year]) echo " selected";
   echo ">$row[year]</option>";
}


}
echo "</select> and ";
echo "<select name=\"mo2\"><option value='00'>MM</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $m="0".$i;
   else $m=$i;
   echo "<option value='$m'";
   if($mo2==$m) echo " selected";
   echo ">$m</option>";
}
echo "</select>/<select name=\"day2\"><option value='00'>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $d="0".$i;
   else $d=$i;   echo "<option value='$d'";
   if($day2==$d) echo " selected";
   echo ">$d</option>";
}
echo "</select>/<select name=\"yr2\">";
$sql="SELECT DISTINCT YEAR(datepaid) AS year FROM schoolregistration WHERE YEAR(datepaid)>0 ORDER BY year";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
	echo "<option value='".date("Y")."' >".date("Y")."</option>";

else {
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[year]\"";
   if($myr2==$row[year]) echo " selected";
   echo ">$row[year]</option>";
}


}
echo "</select><br><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(If you want to see registrations for ONE DAY ONLY, leave the second date field blank.)</i><br><br>";

//POSTSEASON
echo "<u><b>AND</u> who marked their school as:<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio name=\"filterpostseason\" id=\"filterpostseason\" value='yes'";
if($filterpostseason=='yes') echo " checked";
echo "> PARTICIPATING  <input type=radio name=\"filterpostseason\" id=\"filterpostseasonno\" value='no'";
if($filterpostseason=='no') echo " checked";
echo "> NOT PARTICIPATING ... IN DISTRICT & STATE COMPETITION</b>";
echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>(If you use this option, you should clear the date range above.)</i><br><br>";

//POSSIBLES
/*
echo "<u><b>AND</u> <input type=checkbox name=\"filterpossible\" id=\"filterpossible\" value='x'";
if($filterpossible=='x') echo " checked";
echo "> who marked their school as POSSIBLY PARTICIPATING</b>";
echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>(If you use this option, you should clear the date range above.)</i>";
echo "<div style='margin:5px 0px 0px 25px;padding:5px;'>* NOTE: You can find, for example, all schools who marked \"Possible\" for Wrestling but since then haven't registered for Wrestling by selecting Wrestling above and checking this box.)<br>";
echo "<p style='font-size:8pt;margin:5px 0px;'><label style='font-size:9pt;'><b><u>DECLARATIONS:</b></u></label> Schools submit declarations for Fall Sports (and Play) and do NOT have a \"Possible\" checkbox for those activities. For those activities, you can instead download a report showing whether a school completed registration for the activities in which they declared:";
echo "<p style='margin:5px;font-size:8pt;'><a class=small style='margin-top:10px;' href=\"declexport.php?registrations=1&session=$session\">Download Declarations Report</a><br>(<label style='color:#ff0000;'><b>RED</b></label> = Declared but did NOT register in an activity, <label style='color:#0000ff'><b>BLUE</b></label> = Did NOT declare but REGISTERED in an activity)</p>";
echo "</div>";
*/
//PAYMENT DUE
echo "<b><u>AND</u> <input type=checkbox name=\"filterunpaid\" id=\"filterunpaid\" value='x'";
if($filterunpaid=='x') echo " checked";
echo "> who still have PAYMENT DUE</b>";
echo "</div><hr>";
echo "<b><u>OR</b></u><br><br><input type=radio name='filtersubmitted' value='2'";
if($filtersubmitted==2) echo " checked";
echo "> <font style='font-size:10pt;'>Schools that have <b>NOT SUBMITTED</b> their registration online.</font>";
echo "<br><br><hr>";
echo "<b><u>OR</u></b><br><br><font style='font-size:10pt;'>Select a <b>SPECIFIC SCHOOL</b>: </font><select name=\"filterschoolid\">
<option value=''>~</option>";
$sql="SELECT school,id FROM headers ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value='$row[id]'";
   if($filterschoolid==$row[id]) echo " selected";
   echo ">$row[school]</option>";
}
echo "</select><br><br><hr>";

echo "<input type=submit name=\"go\" value=\"Filter\">";
echo "</div><br>";
//GET STRING OF FILTER VARIABLES
$filterstring="filtersubmitted=$filtersubmitted&filteracts=$filteracts&filterunpaid=$filterunpaid&mfilterunpaid=$mfilterunpaid&mmo1=$mmo1&mday1=$mday1&myr1=$myr1&mmo2=$mmo2&mday2=$mday2&myr2=$myr2&mo1=$mo1&day1=$day1&yr1=$yr1&mo2=$mo2&day2=$day2&yr2=$yr2&filterpossible=$filterpossible&filterpostseason=$filterpostseason";
//DIV CONTAINING EXPORT:
$filename="Registration".time().".xls";
$filename2="Registration".$mo1.$day1.$yr1.".xls";

if($filtersubmitted==1 || $filtersubmitted==2 || $filtersubmitted==3 || $filtersubmitted==4)
{
	//MAILING EXPORT
   if($filtersubmitted<=2) 
   {
	$filename3="Registration_Mailing_".date("mdy").".xls";
   	echo "<div id='exportdiv' class='normalwhite' style='padding:10px;'><p><b>Download a MAILING EXPORT for the schools shown below to an Excel file:</b> (includes school, address, activities registered)</p><a href=\"exports.php?session=$session&filename=$filename3\">Download Export</a> (Wait until page finishes loading to click this link)</div><br>";
   }
   else $filename3="";
	//CONTACT INFO EXPORT
   if($filtersubmitted<=2) $filename="ActivitiesRegistration_Emails_".date("mdy").".xls";
   else $filename="Membership_Emails_".date("mdy").".xls";
   echo "<div id='exportdiv' class='normalwhite' style='padding:10px;'><p><b>Export the CONTACT INFORMATION for the schools shown below to an Excel file:</b></p><a href=\"exports.php?session=$session&filename=$filename\">Download Export</a> (Wait until page finishes loading to click this link)</div><br>";
	//ACTIVITIES REGISTERED EXPORT - FOR EACH ACTIVITY, SHOW COUNT
   echo "<div id='exportdiv3' class='normalwhite' style='padding:10px;'><p><b>Activities Registration Report:</b></p><a href=\"actregreport.php?session=$session\">Download Report of Number of Schools Registered for each Activity</a></div><br />";
   if($mo1!='00' && $day1!='00')	//DATE EXPORT - IF DATE CHOSEN, INCLUDE EXPORT LISTING EACH SCHOOL AND EACH ACTIVITY'S PAYMENT
   {
      echo "<div id='exportdiv2' class='normalwhite' style='padding:10px;'><p><b>Export the TOTALS for each ACTIVITY for $mo1/$day1/$yr1 (Excel):</b></p><a href=\"exports.php?session=$session&filename=$filename2\">Download Export</a> (Wait until page finishes loading to click this link)</div><br>";
   }
}

if($filteracts!='any')
{
   echo "<div id='schoolsexportdiv' class='normalwhite' style='width:600px;padding:10px;text-align:center;'><b>Export the current list of schools, including co-op and registration status for:</b><ul>";
   for($i=0;$i<count($regacts);$i++)
   {
      if($filteracts==$regacts[$i] || $filteracts==$regactseasons[$i])
      {
	 $proceed=1;
	 $curactname=GetActivityName($regacts[$i]);
	 if(GetSchoolsTable($regacts[$i])!=$regacts[$i]."school")	//Debate, Something with "_" or Combined girls/boys table
	 {
	    if($regacts[$i]=="de_ld") $curactname="Debate";
	    else if($regacts[$i]=="de_cx") $proceed=0;	//already would have shown the Debate table b/c of deld
	    else if(ereg("sw",$regacts[$i]) || ereg("tr",$regacts[$i]) || ereg("cc",$regacts[$i]))
	    {
	       if(ereg("_b",$regacts[$i])) $curactname=ereg_replace("Boys ","",GetActivityName($regacts[$i]));
	       else $proceed=0;
   	    }
	    //else if(ereg("bb",$regacts[$i]) || ereg("so",$regacts[$i]))	//Just removed the _, ignore
	 }
	 if($proceed)
	 {
            echo "<li style='list-style-type:none;float:left;background-color:#f0f0f0;border:#808080 1px dotted;padding:5px;margin:3px;'>";
	    echo "<a class=small href=\"exportschools.php?session=$session&sport=$regacts[$i]\">".$curactname."</a> (<a class=small href=\"exportschools.php?session=$session&sport=".$regacts[$i]."&coopsonly=1\">JUST Co-ops</a>)</li>";
	 }
      }
   }
   echo "</ul><div style='clear:both;'></div><br>";
   echo "To manage the lists of schools for each sport, <a class=small href=\"/calculate/wildcard/schools.php?session=$session\">Go to Wildcard Program --> Manage Schools</a></div><br>";
}
echo "</caption>";
if($filtersubmitted==4)	//SCHOOLS THAT HAVE NOT SUBMITTED THEIR MEMBERSHIP
{
   $sql="SELECT t1.phone,t1.school FROM headers AS t1 LEFT JOIN schoolmembership AS t2 ON t1.id=t2.schoolid WHERE t2.id IS NULL ORDER BY t1.school";
   $result=mysql_query($sql);
   echo "<tr align=left><td><b>The following schools have NOT SUBMITTED their Membership online yet:</b><br><br>";
   $cinfo="\"SCHOOL\"\t\"CONTACT NAME\"\t\"CONTACT EMAIL\"\t\"CONTACT PHONE\"\r\n";
   while($row=mysql_fetch_array($result))
   {
      echo "<p>".$row[school]."&nbsp;&nbsp;";
      $sql2="SELECT * FROM logins WHERE school='".addslashes($row[school])."' AND maincontact='y'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $schph=split("-",$row[phone]); $schoolarea=$schph[0]; $schoolpre=$schph[1]; $schoolpost=$schph[2];
      $ph=split("-",$row2[phone]);
      $phone="";
      if($ph[0]!='') $phone.="($ph[0])";
      else $phone.="($schoolarea)";
      if($ph[1]!='') $phone.="$ph[1]-";
        else $phone.="$schoolpre-";
      if($ph[2]!='') $phone.="$ph[2] ";
        else $phone.="$schoolpost ";
      if($ph[3]!='') $phone.="x$ph[3]";
      echo "<a class=small href=\"mailto:$row2[email]\">$row2[email]</a>&nbsp;&nbsp;$phone";
      echo "</p>";
      $cinfo.="\"$row[school]\"\t\"$row2[name]\"\t\"$row2[email]\"\t\"$phone\"\r\n";
   }
   echo "</td></tr>";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/".$filename),"w");
   fwrite($open,$cinfo);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/".$filename);
}
else if($filtersubmitted==3)	//HAVE SUBMITTED MEMBERSHIP
{
   $sql="SELECT t1.phone,t1.school,t2.* FROM headers AS t1, schoolmembership AS t2 WHERE t1.id=t2.schoolid AND ";
   if($mmo1!='00' && $mday1!='00')
   {
      if($mmo2=='00' || $mday2=='00')
      {
         $mmo2=$mmo1; $mday2=$mday1; $myr2=$myr1;
      }
      $startdate="$myr1-$mmo1-$mday1"; $enddate="$myr2-$mmo2-$mday2";
      $sql.="datepaid>='$startdate' AND datepaid<='$enddate' AND ";
      $filtered="yes";
   }
   if($mfilterunpaid=='x')       //ONLY SCHOOLS THAT HAVE PAYMENT DUE
   {
      $sql.="datepaid='0000-00-00' AND ";
      $filtered="yes";
   }
   $sql=substr($sql,0,strlen($sql)-4);
   $sql.="ORDER BY t1.school";
   $result=mysql_query($sql);
//echo $sql;
   if(mysql_num_rows($result)==0)
   {
      if($filtered=='no')
         echo "<tr align=center><td class=nine><b>Please indicate your search criteria above and click \"Filter\"</b></td></tr>";
      else
         echo "<tr align=center><td class=nine><i>Your search returned no results.</i></td></tr>";
   }
   else
   {
      echo "<tr align=center><td><b>School</b></td><td><b>Date Submitted</b></td><td><b>Amount Paid</b></td><td><b>Date Paid</b></td></tr>";
   }
   $cinfo="\"SCHOOL\"\t\"CONTACT NAME\"\t\"CONTACT EMAIL\"\t\"CONTACT PHONE\"\r\n";
   while($row=mysql_fetch_array($result))
   {
      if($row[datepaid]=="0000-00-00")
         $datepaid="<font style=\"color:red\"><b>NOT PAID</b></font>";
      else
      {
	 $date=explode("-",$row[datepaid]);
	 $datepaid="$date[1]/$date[2]/$date[0]";
      }
      echo "<tr align=left><td>$row[school]";
      echo "<br /><a href=\"schoolregistration.php?print=1&$filterstring&session=$session&schoolid=$row[schoolid]\" target=\"_blank\" class=small>View Form</a>&nbsp;&nbsp;<a class=small href=\"schoolregistration.php?$filterstring&session=$session&schoolid=$row[schoolid]\">Edit Form</a>";
      echo "</td><td>".date("m/d/y",$row[datesubmitted])."</td><td>$".number_format($row[amtpaid],2,'.','')."</td><td>$datepaid</td></tr>";
      $sql2="SELECT * FROM logins WHERE school='".addslashes($row[school])."' AND maincontact='y'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $schph=split("-",$row[phone]); $schoolarea=$schph[0]; $schoolpre=$schph[1]; $schoolpost=$schph[2];
      $ph=split("-",$row2[phone]);
      $phone="";
      if($ph[0]!='') $phone.="($ph[0])";
      else $phone.="($schoolarea)";
      if($ph[1]!='') $phone.="$ph[1]-";
        else $phone.="$schoolpre-";
      if($ph[2]!='') $phone.="$ph[2] ";
        else $phone.="$schoolpost ";
      if($ph[3]!='') $phone.="x$ph[3]";
      $cinfo.="\"$row[school]\"\t\"$row2[name]\"\t\"$row2[email]\"\t\"$phone\"\r\n";
   }
   $open=fopen(citgf_fopen("/home/nsaahome/reports/".$filename),"w");
   fwrite($open,$cinfo);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/".$filename);
}
else if($filtersubmitted==2) 	//SCHOOLS THAT HAVE NOT SUBMITTED THEIR REGISTRATION
{
   $sql="SELECT t1.phone,t1.school FROM headers AS t1 LEFT JOIN schoolregistration AS t2 ON t1.id=t2.schoolid WHERE t2.id IS NULL ORDER BY t1.school";
   $result=mysql_query($sql);
   echo "<tr align=left><td><b>The following schools have NOT SUBMITTED their registration online yet:</b><br><br>";
   $cinfo="\"SCHOOL\"\t\"CONTACT NAME\"\t\"CONTACT EMAIL\"\t\"CONTACT PHONE\"\r\n";
   while($row=mysql_fetch_array($result))
   {
      echo "<p>".$row[school]."&nbsp;&nbsp;";
      $sql2="SELECT * FROM logins WHERE school='".addslashes($row[school])."' AND maincontact='y'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $schph=split("-",$row[phone]); $schoolarea=$schph[0]; $schoolpre=$schph[1]; $schoolpost=$schph[2];
      $ph=split("-",$row2[phone]);
      $phone="";
      if($ph[0]!='') $phone.="($ph[0])";
      else $phone.="($schoolarea)";
      if($ph[1]!='') $phone.="$ph[1]-";
	else $phone.="$schoolpre-";
      if($ph[2]!='') $phone.="$ph[2] ";
	else $phone.="$schoolpost ";
      if($ph[3]!='') $phone.="x$ph[3]";
      echo "<a class=small href=\"mailto:$row2[email]\">$row2[email]</a>&nbsp;&nbsp;$phone";
      echo "</p>";
      $cinfo.="\"$row[school]\"\t\"$row2[name]\"\t\"$row2[email]\"\t\"$phone\"\r\n";
   }
   echo "</td></tr>";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/".$filename),"w");
   fwrite($open,$cinfo);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/".$filename);
}
else	//LOOKING AT SUBMITTED REGISTRATIONS (REGISTRATIONS IN THE DATABASE)
{
   $filtered="no";
   $sql="SELECT DISTINCT t1.phone,t1.school,t1.address1,t1.address2,t1.city_state,t1.zip,t2.schoolid FROM headers AS t1, schoolregistration AS t2 WHERE t1.id=t2.schoolid AND ";
   if($filterschoolid && $filterschoolid!='')
   {
      $sql.="t2.schoolid='$filterschoolid' AND "; 
      $filtered="yes";
   }
   else if($filtersubmitted && $filterpossible!='x')
   {
      $sql.="t2.participate='x' AND ";
      $filtered="yes";
   }
   if($filteracts=="Fall" || $filteracts=="Winter" || $filteracts=="Spring")
   {
      $sql.="(";
      for($i=0;$i<count($regacts);$i++)
      {
         if($regactseasons[$i]==$filteracts)
            $sql.="sport='$regacts[$i]' OR ";
      }
      $sql=substr($sql,0,strlen($sql)-4).") AND ";
      $filtered="yes";
   }
   else if($filteracts!='any')   //specific activity
   {
      $sql.="sport='$filteracts' AND ";
      $filtered="yes";
   }
   if($filterunpaid=='x')	//ONLY SCHOOLS THAT HAVE PAYMENT DUE
   {
      $sql.="((participate='x' AND datepaid='0000-00-00') OR (sport='cc_g' AND ccfee='x' AND ccfeedatepaid='0000-00-00') OR (sport='wr' AND wrfee='x' AND wrfeedatepaid='0000-00-00')) AND ";
      $filtered="yes";
   }
   if($mo1!='00' && $day1!='00')
   {
      if($mo2=='00' || $day2=='00') 
      {
	 $mo2=$mo1; $day2=$day1; $yr2=$yr1;
      }
      $startdate="$yr1-$mo1-$day1"; $enddate="$yr2-$mo2-$day2";
      $sql.="datepaid>='$startdate' AND datepaid<='$enddate' AND ";
      $filtered="yes";
   }
   if($filterpostseason=='yes')
   {
      $sql.="postseason='x' AND "; $filtered="yes";
   }
   else if($filterpostseason=='no')
   {
      $sql.="postseason!='x' AND "; $filtered="yes";
   }
   if($filterpossible=='x')
   {
      $sql.="possible='x' AND ";
      $filtered="yes";
   }
   $sql=substr($sql,0,strlen($sql)-4);
   $sql.="ORDER BY t1.school";
   $result=mysql_query($sql);
//echo $sql;
   if(mysql_num_rows($result)==0)
   {
      if($filtered=='no')
	 echo "<tr align=center><td class=nine><b>Please indicate your search criteria above and click \"Filter\"</b></td></tr>";
      else
      	 echo "<tr align=center><td class=nine><i>Your search returned no results.</i></td></tr>";
   }
   else
   {
      echo "<tr align=center><td><b>School</b></td><td><b>Date Submitted/<br>Last Updated</b></td><td><b>Activities Registered</b></td><td><b>Total Fee</b></td><td><b>Amount Paid</b></td><td><b>Amount Owed</b></td></tr>";
   }
   $cinfo="\"SCHOOL\"\t\"CONTACT NAME\"\t\"CONTACT EMAIL\"\t\"CONTACT PHONE\"\r\n";
   $querytotalfee=0; $querytotalpaid=0; $querytotalowed=0; $querytotalschools=0;
      $acttotalpaid=array();
      for($i=0;$i<count($regacts);$i++)
      {
         $acttotalpaid[$regacts[$i]]=0;
      }
      $acttotalpaid['ccfee']=0; $acttotalpaid['wrfee']=0; 
      $startsec=mktime(0,0,0,$mo1,$day1,$yr1);
      $endsec=mktime(0,0,0,$mo2,$day2,$yr2);
      $csv="\"School\"\t";
      $mailing="\"School\"\t\"Address 1\"\t\"Address 2\"\t\"City & State\"\t\"Zip\"\t\"Contact Name\"\t\"Contact Email\"\t";
      for($i=0;$i<count($regacts);$i++)
      {
         $csv.="\"".GetActivityName($regacts[$i])."\"\t";
	 $mailing.="\"".GetActivityName($regacts[$i])."\"\t";
         if($regacts[$i]=='cc_g') $csv.="\"CC Chip Timing Fee\"\t";
         else if($regacts[$i]=='wr') $csv.="\"WR Weight Management Fee\"\t\"WR Hydration Strips\"\t";
      }
      $csv.="\"TOTAL\"\r\n";
      $mailing.="\r\n";
   while($row=mysql_fetch_array($result))
   {
      $csv.="\"$row[school]\"\t";
      $mailing.="\"$row[school]\"\t\"$row[address1]\"\t\"$row[address2]\"\t\"$row[city_state]\"\t\"$row[zip]\"\t";
      //Get Contact Person for Mailing Export
      $sql2="SELECT sport,level,name,email FROM logins WHERE school='".addslashes($row[school])."' AND maincontact='y'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $title=$row2[sport]; 
      $title2=CoachOrDirector($row2[sport]);
      if($title2!='') $title.=" $title2";
      if(trim($title)=='' && $row2[level]==2) $title="Athletic Director";
      $mailing.="\"$title\"\t\"$row2[email]\"\t";
      echo "<tr align=left><td><h3>$row[school]</h3> <a href=\"schoolregistration.php?print=1&$filterstring&session=$session&schoolid=$row[schoolid]\" target=\"_blank\" class=small>View Form</a>&nbsp;&nbsp;<a class=small href=\"schoolregistration.php?$filterstring&session=$session&schoolid=$row[schoolid]\">Edit Form</a><br>";
      $sql2="SELECT * FROM logins WHERE school='".addslashes($row[school])."' AND maincontact='y'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $schph=split("-",$row[phone]); $schoolarea=$schph[0]; $schoolpre=$schph[1]; $schoolpost=$schph[2];
      $ph=split("-",$row2[phone]);
      $phone="";
      if($ph[0]!='') $phone.="($ph[0])";
      else $phone.="($schoolarea)";
      if($ph[1]!='') $phone.="$ph[1]-";
        else $phone.="$schoolpre-";
      if($ph[2]!='') $phone.="$ph[2] ";
        else $phone.="$schoolpost ";
      if($ph[3]!='') $phone.="x$ph[3]";
      echo "Main Contact: $row2[name]<br><a class=small href=\"mailto:$row2[email]\">$row2[email]</a><br>$phone";
      echo "</td>";
      $cinfo.="\"$row[school]\"\t\"$row2[name]\"\t\"$row2[email]\"\t\"$phone\"\r\n";
      $partacts=""; $possacts=""; $totalpaid="0.00";
      for($r=0;$r<count($regacts);$r++)
      {
	 $actschoolpaid[$regacts[$r]]=0;	//AMOUNT PAID PER SPORT FOR EACH SCHOOL (RESET FOR EACH SCHOOL)
      }
      $actschoolpaid['ccfee']=0; $actschoolpaid['wrfee']=0; 
      $i=0;
      for($r=0;$r<count($regacts);$r++)
      {
         $sql2="SELECT * FROM schoolregistration WHERE schoolid='$row[schoolid]' AND sport='$regacts[$r]'";
         $result2=mysql_query($sql2); 
	 $row2=mysql_fetch_array($result2);
         if($i==0)
      	    echo "<td>".date("m/d/y",$row2[datesub])." at ".date("g:ia",$row2[datesub])."</td>";
         $date=split("-",$row2[datepaid]);
	 $datepaid=mktime(0,0,0,$date[1],$date[2],$date[0]);
         if($row2[participate]=='x') 
	 {
	    if($datepaid>=$startsec && $datepaid<=$endsec && $row2[datepaid]!='0000-00-00')	//DID THEY PAY??
	    {
	       $acttotalpaid[$row2[sport]]+=60; $actschoolpaid[$row2[sport]]+=60;
	       $querytotalpaid+=60;
	    }
	    $partacts.=GetActivityName($row2[sport]).", ";
	    if($row2[sport]=='cc_g' && $row2[ccfee]=='x') 
	    {
	       $ccfeedate=split("-",$row2[ccfeedatepaid]);
	       $ccfeedatesec=mktime(0,0,0,$ccfeedate[1],$ccfeedate[2],$ccfeedate[0]);
	       if($ccfeedatesec>=$startsec && $ccfeedatesec<=$endsec && $row2[ccfeedatepaid]!='0000-00-00')
	       {
	          $acttotalpaid['ccfee']+=20; $actschoolpaid['ccfee']+=20;
	          $querytotalpaid+=20;
	       }
	    }
	    else if($row2[sport]=='wr')
	    {
	       if($row2[wrfee]=='x')
	       {
                  $wrfeedate=split("-",$row2[wrfeedatepaid]);
                  $wrfeedatesec=mktime(0,0,0,$wrfeedate[1],$wrfeedate[2],$wrfeedate[0]);
                  if($wrfeedatesec>=$startsec && $wrfeedatesec<=$endsec && $row2[wrfeedatepaid]!='0000-00-00')
	          {
	             $acttotalpaid['wrfee']+=30; $actschoolpaid['wrfee']+=30;
	             $querytotalpaid+=30;
	          }
	       }
	    }
	 }
	 else if($row2[sport]=='cc_g')	//check if participating in BOYS not GIRLS
	 {
	    $sql3="SELECT * FROM schoolregistration WHERE schoolid='$row[schoolid]' AND sport='cc_b' AND participate='x'";
	    $result3=mysql_query($sql3);
	    $row3=mysql_fetch_array($result3);
	    if(mysql_num_rows($result3)>0)	//YES, participating in Boys CC, add Timing fee to total
	    {
               if($row2[ccfee]=='x')
               {
                  $ccfeedate=split("-",$row2[ccfeedatepaid]);
                  $ccfeedatesec=mktime(0,0,0,$ccfeedate[1],$ccfeedate[2],$ccfeedate[0]);
                  if($ccfeedatesec>=$startsec && $ccfeedatesec<=$endsec && $row2[ccfeedatepaid]!='0000-00-00')
		  {
                     $acttotalpaid['ccfee']+=20; $actschoolpaid['ccfee']+=20;
		     $querytotalpaid+=20;
	     	  }
               }
	    }
	 }
         if($row2[possible]=='x') $possacts.=GetActivityName($row2[sport]).", ";
         $totalpaid+=$row2[amtpaid]; $totalfee=$row2[totalfee];
         $csv.="\"".$actschoolpaid[$regacts[$r]]."\"\t";
	 if($regacts[$r]=='cc_g') $csv.="\"".$actschoolpaid['ccfee']."\"\t";
         else if($regacts[$r]=='wr') $csv.="\"".$actschoolpaid['wrfee']."\"\t";
         if(IsRegistered2011($row[schoolid],$row2[sport],'',TRUE)) 
	    $mailing.="\"X\"\t";
	 else
	    $mailing.="\"\"\t";
	 $i++;
      } //END FOR EACH ACTIVITY
      if($partacts!='') $partacts=substr($partacts,0,strlen($partacts)-2);
	else $partacts=" - ";
      if($possacts!='') $possacts=substr($possacts,0,strlen($possacts)-2);
	else $possacts=" - ";
      echo "<td width='400px'><p><b>Participating in: </b>$partacts</p><p><b>Possibly Participating in: </b>$possacts</p></td>";
      echo "<td>$".number_format(GetRegistrationAmount($row[schoolid]),2,'.',',')."</td>";
      $querytotalschools++;
      $querytotalfee+=$totalfee;
      $amountpaid=GetRegistrationAmount($row[schoolid],"paid","");
      echo "<td>$".number_format($amountpaid,2,'.','')."</td>";
      $csv.="\"$amountpaid\"\r\n";
      $amountowed=GetRegistrationAmount($row[schoolid],"due","");
      echo "<td>$".number_format($amountowed,2,'.','')."</td>";
      $querytotalowed+=$amountowed;
      echo "</tr>";
      $csv.="\r\n";
      $mailing.="\r\n";
   }	//END FOR EACH SCHOOL
   $csv.="\"TOTAL:\"\t";
   for($i=0;$i<count($regacts);$i++)
   {
      if($filteracts==$regacts[$i])
      {
         $querytotalpaid=$acttotalpaid[$filteracts];
	 if($filteracts=='cc_g' || $filteracts=='cc_b')
	    $querytotalpaid+=$acttotalpaid['ccfee'];
	 else if($filteracts=='wr')
	 {
	    $querytotalpaid+=$acttotalpaid['wrfee']; 
	 }
      }
      $csv.="\"".$acttotalpaid[$regacts[$i]]."\"\t";
      if($regacts[$i]=='cc_g')
	 $csv.="\"".$acttotalpaid['ccfee']."\"\t";
      else if($regacts[$i]=='wr')
      {
	 $csv.="\"".$acttotalpaid['wrfee']."\"\t";
      }
   }
   echo "<tr align=center><td colspan=3 align=right><b>TOTAL AMOUNT PAID FOR THIS QUERY ($querytotalschools Schools):</b></td><td>&nbsp;</td><td><b><u>$".number_format($querytotalpaid,2,'.',',')." PAID</u></b></td><td>&nbsp;</td></tr>";
   $csv.="\"$querytotalpaid\"\r\n";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/".$filename),"w");
   fwrite($open,$cinfo);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/".$filename);
   $open=fopen(citgf_fopen("/home/nsaahome/reports/".$filename2),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/".$filename2);
   if($filename3!='')
   {
      $open=fopen(citgf_fopen("/home/nsaahome/reports/".$filename3),"w");
      fwrite($open,$mailing);
      fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/".$filename3);
   }
}//end if filtersubmitted!=2
echo "</table>";
} //END IF !$section || $section==""
else	//SETTINGS
{
   echo "<div style=\"max-width:850px;text-align:left;\">";
   echo "<h3>CLEAR OUT last year's data:</h3>";
   $sql="SELECT DISTINCT schoolid FROM schoolregistration WHERE datesub>0";
   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   if($ct==1) echo "<p>There is <b><u>1</b></u> submitted Membership & Activities Registration form in the database.";
   else echo "<p>There are <b><u>$ct</b></u> submitted Membership & Activities Registration forms in the database."; 
   if($ct>0)
      echo "<ul><li><a href=\"schoolregadmin.php?session=$session&section=$section&resetmem=1\" onClick=\"return confirm('Are you sure you want to clear out the Membership and Activities Registration forms for the year? They will be archived.');\">Clear out ALL Membership & Activities Registration forms (to start over for the new year)</a></li></ul>";
   echo "</p>";

   echo "<h3>Manage OPENING Date & DUE Dates:</h3><ul>";
   $yr1=date("Y")-1; $yr2=date("Y")+1;
   $date=explode("-",GetMiscDueDate("membership","showdate"));
   echo "<li><b>OPEN</b> the Membership & Registration Form on: <select name=\"showm\">".GetDateSelectOptions("MM",$date[1],1,12)."</select>/";
   echo "<select name=\"showd\">".GetDateSelectOptions("DD",$date[2],1,31)."</select>/";
   echo "<select name=\"showy\">".GetDateSelectOptions("YYYY",$date[0],$yr1,$yr2)."</select></li>";
   $date=explode("-",GetMiscDueDate("membership"));
   echo "<li><b>MEMBERSHIP is DUE</b> on: <select name=\"duem\">".GetDateSelectOptions("MM",$date[1],1,12)."</select>/";
   echo "<select name=\"dued\">".GetDateSelectOptions("DD",$date[2],1,31)."</select>/";
   echo "<select name=\"duey\">".GetDateSelectOptions("YYYY",$date[0],$yr1,$yr2)."</select></li>";
   $date=explode("-",GetMiscDueDate("registration_fall"));
   echo "<li>REGISTRATION for <b>FALL SPORTS</b> is DUE on: <select name=\"fallm\">".GetDateSelectOptions("MM",$date[1],1,12)."</select>/";
   echo "<select name=\"falld\">".GetDateSelectOptions("DD",$date[2],1,31)."</select>/";
   echo "<select name=\"fally\">".GetDateSelectOptions("YYYY",$date[0],$yr1,$yr2)."</select></li>";
   $date=explode("-",GetMiscDueDate("registration_winter"));
   echo "<li>REGISTRATION for <b>WINTER SPORTS & NON-ATHLETIC ACTIVITIES</b> is DUE on: <select name=\"winterm\">".GetDateSelectOptions("MM",$date[1],1,12)."</select>/";
   echo "<select name=\"winterd\">".GetDateSelectOptions("DD",$date[2],1,31)."</select>/";
   echo "<select name=\"wintery\">".GetDateSelectOptions("YYYY",$date[0],$yr1,$yr2)."</select></li>";
   $date=explode("-",GetMiscDueDate("registration_spring"));
   echo "<li>REGISTRATION for <b>SPRING SPORTS</b> is DUE on: <select name=\"springm\">".GetDateSelectOptions("MM",$date[1],1,12)."</select>/";
   echo "<select name=\"springd\">".GetDateSelectOptions("DD",$date[2],1,31)."</select>/";
   echo "<select name=\"springy\">".GetDateSelectOptions("YYYY",$date[0],$yr1,$yr2)."</select></li>";
   echo "</ul>";
   echo "<input type=\"submit\" name=\"savesettings\" class=\"fancybutton\" value=\"Save Dates\">";
   echo "</div>";
}
echo "</form>";
echo $end_html;
?>
