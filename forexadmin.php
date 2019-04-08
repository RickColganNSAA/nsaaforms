<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

if($delete)
{
   $sql="DELETE FROM forex WHERE id='$delete'";
   $result=mysql_query($sql);
   //echo $sql;
}

//GET SETTINGS OF THE FORM FROM forexsettings
$sql="SELECT * FROM forexsettings";
$result=mysql_query($sql);
$forminfo=mysql_fetch_array($result);

echo $init_html;
echo $header;

if($report==1)
{
   if(!$sort) $sort="t2.datesub DESC";
   $sql="SELECT t1.first,t1.last,t2.* FROM eligibility AS t1,forex AS t2 WHERE t1.id=t2.studentid AND t2.datesub!='' AND t2.execsignature='x' ORDER BY $sort";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      echo "<br><br><font style=\"font-size:9pt;\"><b>Report of Completed ".$forminfo[formtitle]."s:<br><br></b></font>[There are no completed forms on file.]";      
      echo "<br><br><a class=small href=\"forexadmin.php?session=$session\">".$forminfo[formnickname]."s MAIN MENU</a><br><br>";
      exit();
   }
   else
   {
      echo "<br><a class=small href=\"forexadmin.php?session=$session\">".$forminfo[formnickname]."s MAIN MENU</a><br><br>";
      echo "<table class=nine border=1 bordercolor=#000000 cellspacing=1 cellpadding=5>";
      echo "<caption><b>REPORT of Completed ".$forminfo[formnickname]."s:</b><br>(Click column headers to sort by that field.)</caption>";
      echo "<tr align=center>";
      if($sort=="t2.datesub ASC") $datesub="t2.datesub DESC";
      else $datesub="t2.datesub ASC";
      echo "<td><a class=small href=\"forexadmin.php?session=$session&report=1&sort=$datesub\">";
      if($sort=="t2.datesub ASC") echo "&Delta; ";
      else if($sort=="t2.datesub DESC") echo "&nabla; ";
      echo "Date Submitted</a></td>";
      if($sort=="t2.school ASC") $sch="t2.school DESC";
      else $sch="t2.school ASC";
      echo "<td><a class=small href=\"forexadmin.php?session=$session&report=1&sort=$sch\">";
      if($sort=="t2.school ASC") echo "&Delta; ";
      else if($sort=="t2.school DESC") echo "&nabla; ";
      echO "School: Student (Country)</a></td>";
      if($sort=="t2.eligible ASC") $elig="t2.eligible DESC";
      else $elig="t2.eligible ASC";
      echo "<td><a class=small href=\"forexadmin.php?session=$session&report=1&sort=$elig\">";
      if($sort=="t2.eligible ASC") echo "&Delta; ";
      else if($sort=="t2.eligible DESC") echo "&nabla; ";
      echo "Eligible</a></td>";
      if($sort=="t2.execdate ASC") $exec="t2.execdate DESC";
      else $exec="t2.execdate ASC";
      echo "<td><b>Comments</b></td>";
      echo "<td><a class=small href=\"forexadmin.php?session=$session&report=1&sort=$exec\">";
      if($sort=="t2.execdate ASC") echo "&Delta; ";
      else if($sort=="t2.execdate DESC") echo "&nabla; ";
      echo "Date of Action</a></td>"; 
      echo "</tr>";
      while($row=mysql_fetch_array($result))
      {
   	 echo "<tr align=center valign=top><td>".date("m/d/Y",$row[datesub])."</td>";
	 echo "<td align=left><a class=small target=new href=\"forex.php?session=$session&header=no&id=$row[id]\">$row[school]: $row[first] $row[last] ($row[country])</a></td>";
	 if($row[eligible]=='y') echo "<td>YES</td>";
	 else echo "<td><font style=\"color:red\">NO</font></td>";
         echo "<td align=left><font style=\"font-size:8pt\">$row[execcomments]</font></td>";
	 echo "<td>".date("m/d/Y",$row[execdate])."</td></tr>";
      }
      echo "</table>";
      echo "<br><br><a class=small href=\"forexadmin.php?session=$session\">".$forminfo[formnickname]."s MAIN MENU</a>";
      exit();
   }
}

echo "<br><table width=600 class=nine cellspacing=3 cellpadding=3><caption><b>".$forminfo[formnickname]."s MAIN MENU:</b><br>
	<p><a href=\"forex.php?session=$session&school_ch=Test's School\" class=\"small\">Preview the ".$forminfo[formtitle]."</a></p><hr><br></caption>";

//Forms that have been submitted but need action:
echo "<tr align=left><td><b>Submitted forms that <u>need Action of the Executive Director</u>:</b></td></tr>";
echo "<tr align=center><td>";
if($delete)
{
   echo "<font style=\"color:red\">Form #$delete has been deleted.</font><br>";
}
if($sort || $sort=='') $sort="datesub DESC";
$sql="SELECT * FROM forex WHERE datesub!='' && execsignature='' ORDER BY $sort";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<table cellspacing=1 cellpadding=4 border=1 bordercolor=#000000>";
   echO "<tr align=center><td><b>Date<br>Submitted</b></td><td><b>School</b></td>";
   echo "<td><b>Student</b><br>(Click for Form)</td><td><b>Country</b></td>";
   echo "<td><b>Delete</b></td></tr>";
}
else echo "[There are no forms in need of Action of the Executive Director.]";
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left><td>".date("m/d/y",$row[datesub])."</td>";
   echo "<td>$row[school]</td>";
   $sql2="SELECT first,last FROM eligibility WHERE id='$row[studentid]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<td><a class=small target=new href=\"forex.php?session=$session&id=$row[id]&header=no\">$row2[first] $row2[last]</a></td>";
   echO "<td>$row[country]</td>";
   echo "<td><a class=small href=\"forexadmin.php?session=$session&delete=$row[id]\" onclick=\"return confirm('Are you sure you want to delete this form?  The information cannot be recovered once you do so.');\">Delete</a></td></tr>"; 
}
if(mysql_num_rows($result)>0)
   echo "</table>";
echo "</td></tr>";

//Search for completed forms by school:
echo "<tr align=left><td><br><b>Search for <u>completed forms</u> by school:</b><br>(Only schools with forms on which executive action has already been taken are listed)</td></tr>";
echO "<tr align=center><td>";
echo "<form method=post action=\"forexadmin.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<select name=schch><option value=''>Choose School</option>";
$sql="SELECT DISTINCT school FROM forex WHERE datesub!='' AND execsignature='x' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echO "<option";
   if($schch==$row[0]) echo " selected";
   echo ">$row[0]</option>";
}
echo "</select>&nbsp;<input type=submit name=search value=\"Search\"><br>";
echo "</form>";
if($search && $schch!='')
{
   $schch2=addslashes($schch);
   $sql="SELECT * FROM forex WHERE school='$schch2' AND datesub!='' AND execsignature='x' ORDER BY datesub DESC";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      echo "<br>[No forms were found for $schch.]";
   }
   else
   {
      echo "<br><table cellspacing=1 cellpadding=4 border=1 bordercolor=#000000>";
      echo "<tr align=center><td><b>Date<br>Submitted</b></td><td><b>Student (Country)</b><br>(Click for form)</td><td><b>Action<br>Taken</b></td><td><b>Comments</b></td><td><b>Action<br>Date</b></td><td><b>Delete</b></td></tr>";
      while($row=mysql_fetch_array($result))
      {
	 echo "<tr align=left><td>".date("m/d/Y",$row[datesub])."</td>";
   	 echo "<td><a class=small target=new href=\"forex.php?session=$session&header=no&id=$row[id]\">";
         $sql2="SELECT first,last FROM eligibility WHERE id='$row[studentid]'";
    	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 echo "$row2[first] $row2[last] ($row[country])</a></td>";
	 echo "<td>";
	 if($row[eligible]=='y') echo "Eligible";
	 else echo "<font style=\"color:red\">Ineligible</font>";
	 echo "</td><td>";
         echo "<font style=\"font-size:8pt\">$row[execcomments]</font></td><td>";
	 echo date("m/d/Y",$row[execdate]);
	 echo "</td>";
         echo "<td><a class=small href=\"forexadmin.php?session=$session&delete=$row[id]\" onclick=\"return confirm('Are you sure you want to delete this form?  The information cannot be recovered once you do so.');\">Delete</a></td>";
	 echo "</tr>";
      }
      echo "</table>";
   }
   echO "<br>";
}
echo "</td></tr>";

//Report of all forms for the year
echo "<tr align=left><td><a href=\"forexadmin.php?session=$session&report=1\">REPORT of Completed ".$forminfo[formnickname]."s</a></td></tr>";

//Export of all forms for the year
$sql="SELECT t1.first,t1.last,t2.* FROM eligibility AS t1,forex AS t2 WHERE t1.id=t2.studentid AND t2.datesub!='' AND t2.execsignature='x' ORDER BY execdate DESC,school";
$result=mysql_query($sql);
$csv="\"Date of Action\",\"School\",\"Student (Country)\",\"Eligible\",\"Program Name\",\"VISA Type\",\"Comments\"\r\n";
while($row=mysql_fetch_array($result))
{
   $csv.="\"".date("m/d/y",$row[execdate])."\",\"$row[school]\",\"$row[first] $row[last] ($row[country])\",\"";
   if($row[eligible]=="y") $csv.="YES";
   else $csv.="NO";
   $csv.="\",\"$row[pgmname]\",\"$row[visatype]\",\"$row[execcomments]\"\r\n";
}
$open=fopen(citgf_fopen("/home/nsaahome/reports/forexexport.csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/forexexport.csv");
echo "<tr align=left><td><a href=\"exports.php?session=$session&filename=forexexport.csv\" target=\"_blank\">EXPORT of Completed ".$forminfo[formnickname]."s</a></td></tr>";
//get LAST YEAR's export:
$year=date("Y"); $year1=$year+1; $year0=$year-1;
$archivedb=$db_name.$year0.$year;
$sql="SHOW DATABASES LIKE '$archivedb'";
$result=mysql_query($sql);
$archive=0;
if(mysql_num_rows($result)==0)	//GO ONE MORE YEAR BACK 
{
   $year00=$year0-1;
   $archivedb=$db_name.$year00.$year0;
   $curyear="$year0-$year";
   $lastyear="$year00-$year0";
   $sql="SHOW DATABASES LIKE '$archivedb'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) $archive=0;	//THIS REALLY SHOULD NEVER BE THE CASE
   else $archive=1;
}
else
{
   $archive=1;
   $curyear="$year-$year1";
   $lastyear="$year0-$year";
}
if($archive==1)	//show link to last year's export (AND THEN THE PREVIOUS YEAR TOO)
{
   $sql="SELECT t1.first,t1.last,t2.* FROM $archivedb.eligibility AS t1,$archivedb.forex AS t2 WHERE t1.id=t2.studentid AND t2.datesub!='' AND t2.execsignature='x' ORDER BY execdate DESC,school";
   $result=mysql_query($sql);
   $csv="\"Date of Action\",\"School\",\"Student (Country)\",\"Eligible\",\"Program Name\",\"VISA Type\",\"Comments\"\r\n";
   while($row=mysql_fetch_array($result))
   {
      $csv.="\"".date("m/d/y",$row[execdate])."\",\"$row[school]\",\"$row[first] $row[last] ($row[country])\",\"";
      if($row[eligible]=="y") $csv.="YES";
      else $csv.="NO";
      $csv.="\",\"$row[pgmname]\",\"$row[visatype]\",\"$row[execcomments]\"\r\n";
   }
   $filename=ereg_replace("-","",$lastyear);
   $filename="forexexport".$filename.".csv";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/".$filename),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/".$filename);
   echo "<tr align=left><td><a href=\"exports.php?session=$session&filename=$filename\" target=\"_blank\">Export of $lastyear Completed ".$forminfo[formnickname]."s</a></td></tr>";

   //GO BACK THROUGH THE REST OF THE YEAR's TO nsaascores20062007
   $fallyear=substr(preg_replace("/[^0-9]/","",$archivedb),0,4); 	//FALL YEAR for the $archivedb
   $fallyear--;
   while($fallyear>=2006)
   {
      $springyear=$fallyear+1;
      $filename="forexexport".$fallyear.$springyear.".csv";
      if(!citgf_file_exists("/home/nsaahome/reports/".$filename))
      {
         $archivedb=$db_name.$fallyear.$springyear;
         $sql="SELECT t1.first,t1.last,t2.* FROM $archivedb.eligibility AS t1,$archivedb.forex AS t2 WHERE t1.id=t2.studentid AND t2.datesub!='' AND t2.execsignature='x' ORDER BY execdate DESC,school";
         $result=mysql_query($sql);
         $csv="\"Date of Action\",\"School\",\"Student (Country)\",\"Eligible\",\"Program Name\",\"VISA Type\",\"Comments\"\r\n";
         while($row=mysql_fetch_array($result))
         {
            $csv.="\"".date("m/d/y",$row[execdate])."\",\"$row[school]\",\"$row[first] $row[last] ($row[country])\",\"";
            if($row[eligible]=="y") $csv.="YES";
            else $csv.="NO";
            $csv.="\",\"$row[pgmname]\",\"$row[visatype]\",\"$row[execcomments]\"\r\n";
         }
         $open=fopen(citgf_fopen("/home/nsaahome/reports/".$filename),"w");
         fwrite($open,$csv);
         fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/".$filename);
      }
      echo "<tr align=left><td><a href=\"exports.php?session=$session&filename=$filename\" target=\"_blank\">Export of $fallyear-$springyear Completed ".$forminfo[formnickname]."s</a></td></tr>";
      $fallyear--;
   }
}

echo $end_html;
 
?>
