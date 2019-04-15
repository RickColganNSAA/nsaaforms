<?php
//allow NSAA to retrieve school's votes

require 'functions.php';
require 'variables.php';

//connect to db:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$header=GetHeader($session,"vote");
$level=GetLevel($session);
if($level==4) $level=1;

//verify user
if(!ValidUser($session) || $level!='1')
{
   header("Location:index.php");
   exit();
}

//get school user chose 
$school=$school_ch;
$school2=ereg_replace("\'","\'",$school);
$sportname=GetSportName($sport);

if($upload=="Upload")
{
   $uploadedfile=$_FILES['dijudgesfile']['tmp_name'];
   if(is_uploaded_file($uploadedfile))
   {
      $sql="DELETE FROM di_judges";
      $result=mysql_query($sql);
      if(!citgf_copy($uploadedfile,"/home/nsaahome/reports/tmpdijudges.csv"))
      {
	 $uploadsuccess='COULD NOT COPY';
      }
      else
      {
      $open=fopen(citgf_fopen('/home/nsaahome/reports/tmpdijudges.csv'),"r");

      $line=file(getbucketurl('/home/nsaahome/reports/tmpdijudges.csv'));
      fclose($open);
      for($i=0;$i<count($line);$i++)
      {
         $cur=split(",",$line[$i]);
         if($cur[0]!='')
         {
            $first=addslashes(trim($cur[0]));
  	    $last=addslashes(trim($cur[1]));
	    $city=addslashes(trim($cur[2]));
	    $register=addslashes(trim($cur[3]));
            $sql="INSERT INTO di_judges (first,last,city,register) VALUES ('$first','$last','$city','$register')";
	    $result=mysql_query($sql);
 	 }
      } 
      $uploadsuccess='y';
      }
   }
   else $uploadsuccess='IS NOT UPLOADED FILE';
}
if($submit2=="Save")
{
   if(strlen($month1)==1) $month1="0".$month1;
   if(strlen($day1)==1)  $day1="0".$day1;
   if(strlen($month2)==1) $month2="0".$month2;
   if(strlen($day2)==1) $day2="0".$day2;
   $date1="$year1-$month1-$day1";
   $date2="$year2-$month2-$day2";
   $sql="UPDATE vote_duedates SET startdate='$date1', enddate='$date2' WHERE sport='$sport'";
   $result=mysql_query($sql);
}

if($submit1=="Go")
{
   if($school_ch!="Choose School" && $ad_coach)
   {
      header("Location:vote_$sport.php?session=$session&nsaa=1&school_ch=$school_ch&ad_coach=$ad_coach");
      exit();
   }
   else if($school_ch!='Choose School' && $coach)
   {
      header("Location:vote_di.php?session=$session&nsaa=1&school_ch=$school_ch&coach=$coach");
      exit();
   }
}

echo $init_html;
echo $header;

echo "<br>";
echo "<form method=post action=\"vote.php\" enctype=\"multipart/form-data\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<select name=sport onChange=\"submit();\"><option value=''>Choose Sport</option>";
$sql="SHOW TABLES LIKE '%_votes'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $temp=split("_votes",$row[0]);
   echo "<option value=\"$temp[0]\"";
   if($sport==$temp[0]) echo " selected";
   echo ">".GetSportName($temp[0])."</option>";
}
echo "</select><input type=submit name=go value=\"Go\">";
if($sport && $sport!='')
{
if($sport=='di') $offjudge="Judges";
else $offjudge="Officials";
echo "<br><br><table cellspacing=5><caption><b>$sportname $offjudge Ballots Admin:</b></caption>";
if($sport=='di')
{
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;Upload List of Judges:</th></tr>";
   echO "<tr align=center><td><table width=400>";
   if($uploadsuccess=='y')
      echo "<tr align=left><td><font style=\"color:blue\"><b>The list of Diving Judges has been successfully uploaded.</b></font></td></tr>";
   elseif($uploadsuccess!='')
      echo "<tr align=left><td><font style=\"color:red\"><b>There was an error in uploading the list of Diving Judges.  The file was not uploaded successfully. $uploadsuccess</b></font></td></tr>"; 
   echo "<tr align=left><td><a href=\"vote_di.php?session=$session&nsaa=1&sample=1\" target=\"_blank\">Preview Current Diving Judges Ballot</a></td></tr>";
   echo "<tr align=left><td>Please make sure your file is comma-delimited.  You can create the file in Excel and then click Save As-->CSV (Comma-Delimited).  Your file should be in the following format, one judge per row (omit any column headers, like the word \"First\"):</td></tr>";
   echo "<tr align=center><td><br>First, Last, City, Registered/Non-Registered</td></tr>";
   echo "<tr align=center><td><br><input type=file name=\"dijudgesfile\">";
   echo "<input type=submit name=upload value=\"Upload\"></td></tr>";
}
else
   echo "<tr align=left><td><a href=\"vote_".$sport.".php?session=$session&nsaa=1&sample=1\" target=\"_blank\">Preview Current $sportname Ballot</a></td></tr>";
echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;$offjudge Ballot Forms:</th></tr>";
echo "<tr align=center><td>Select a school and \"AD\" or \"Coach\" to view/edit the ballot:";
if($submit1 && ($school_ch=="Choose School" || ($sport=='di' && !$coach) || ($sport!='di' && !$ad_coach)))
{
   echo "<div class='error'>Please select a school";
   if($sport=='di') echo " and Boys' Coach or Girls' Coach.";
   else if($sport=='wr') echo ".";
   else echo " and AD or Coach.";
   echo "</div>";
}
echo "<table>";
echo "<tr align=left><td><select name=school_ch><option>Choose School";
$votestbl=$sport."_votes";
$sql="SELECT DISTINCT school FROM $votestbl ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option>$row[0]";
}
echo "</select>&nbsp;";
if($sport=='di')
{
   echo "<input type=radio name=coach value='boys'>&nbsp;Boys' Coach&nbsp;&nbsp;&nbsp;";
   echo "<input type=radio name=coach value='girls'>&nbsp;Girls' Coach&nbsp;&nbsp;";
}
else if($sport=='wr')
   echo "<input type=hidden name=\"ad_coach\" value=\"coach\">";
else if($sport=='so')
{
   echo "<input type=radio name=ad_coach value='ad'>&nbsp;AD&nbsp;&nbsp;&nbsp;";
   echo "<input type=radio name=ad_coach value='bcoach'>&nbsp;Boys Coach&nbsp;&nbsp;";
   echo "<input type=radio name=ad_coach value='gcoach'>&nbsp;Girls Coach&nbsp;&nbsp;";
   echo "<input type=radio name=ad_coach value='coach'>&nbsp;Coach&nbsp;&nbsp;";
}
else
{
   echo "<input type=radio name=ad_coach value='ad'>&nbsp;AD&nbsp;&nbsp;&nbsp;";
   echo "<input type=radio name=ad_coach value='coach'>&nbsp;Coach&nbsp;&nbsp;";
}
echo "<input type=submit name=\"submit1\" value=\"Go\"></td></tr>";
echo "</table></td></tr>";

//or let user choose from numerous reports
echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;$offjudge Ballot Reports:</th></tr>";
echo "<tr align=center><td><table>";
echo "<tr align=left>";
echo "<td colspan=2><a class=small href=\"votereport.php?sport=$sport&type=schools&session=$session\">Schools Who Have Voted</a></td></tr>";
echo "<tr valign=top align=left>";
echo "<td><a class=small href=\"votereport.php?session=$session&sport=$sport\">Full Sortable Report</a></td>";
echo "</table></td></tr>";

//allow user to put in due date for ballots
echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;Date Range for $offjudge Ballots to be available to schools:</th></tr>";
echo "<tr align=center><td>";
$sql="SELECT * FROM vote_duedates WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$date=split("-",$row[startdate]);
$curmo=$date[1];
$curday=$date[2];
$curyr=$date[0];
echo "from&nbsp;<select name=month1>";
for($i=0;$i<count($months);$i++)
{
   $mo=$i+1;
   echo "<option value=$mo";
   if($curmo==$mo) echo " selected";
   echo ">$months[$i]";
}
echo "</select>&nbsp;";
echo "<input class=tiny type=text name=day1 value=\"$curday\" size=2>&nbsp;";
if($curyr=="") $curyr=date("Y",time());
echo "<input class=tiny type=text name=year1 value=\"$curyr\" size=4>&nbsp;to&nbsp;";
$date=split("-",$row[enddate]);
$curmo=$date[1];
$curday=$date[2];
$curyr=$date[0];
echo "<select name=month2>";
for($i=0;$i<count($months);$i++)
{
   $mo=$i+1;
   echo "<option value=$mo";
   if($curmo==$mo) echo " selected";
   echo ">$months[$i]";
}
echo "</select>&nbsp;";
echo "<input type=text name=day2 value=\"$curday\" size=2 class=tiny>&nbsp;";
if($curyr=="") $curyr=date("Y");
echo "<input type=text name=year2 value=\"$curyr\" size=4 class=tiny>";
echo "&nbsp;<input type=submit name=submit2 value=\"Save\"></td></tr>";
echo "</table></form>";
}//end if sport chosen
echo $end_html;
?>
