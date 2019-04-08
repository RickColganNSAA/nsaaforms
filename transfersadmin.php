<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

$states=array("AL","AK","AZ","AR","CA","CO","CT","DE","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WV","WA","WI","WY","DC");

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
echo $header;

echo "<form method=post action=\"transfersadmin.php\">";
echo "<input type=hidden name=session value=\"$session\">";
$thisyear=date("Y");
$thismo=date("m");
if($thismo<6) $nextyear=$thisyear;
else $nextyear=$thisyear+1;
echo "<br><table cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#808080 1px solid;\" class=\"nine\">";
echo "<caption><b>$nextyear INCOMING TRANSFER STUDENTS</b><br>(Due May 1, $nextyear)<br>";
echo "<table style=\"width:500px;\" class=nine><tr align=left><td>";
echo "<b>Show Schools:</b> <select name=\"school\"><option value=''>All Schools</option>";
$sql="SELECT school FROM headers ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option";
   if($school==$row[school]) echo " selected";   
   echo ">$row[school]</option>";
}
echo "</select><br>-AND-<br>";
echo "<b>First Name (begins with):</b> <input type=text class=tiny size=25 name=\"first\" value=\"$first\"><br>-AND-<br>";
echo "<b>Last Name (begins with):</b> <input type=text class=tiny size=25 name=\"last\" value=\"$last\">";
echo "</td></tr>";
echo "<tr align=right><td><input type=submit name=\"go\" value=\"Filter\"></form></td></tr>";
if(!$go)
{
   echo "<tr align=left><td><br><font style=\"font-size:9pt\"><i>To search by school, select a school.  To search by a student, enter their first and/or last name.  Or you may do both.  Then click \"Filter\".  To see all transfer students submitted by all schools, simply click \"Filter\"</td></tr>";
}
//Export of all forms for the year
$sql="SELECT * FROM transfers ORDER BY school";
$result=mysql_query($sql);
$csv="\"School\",\"NDE #\",\"Student\",\"DOB\",\"Grade\",\"Transferred From School\",\"Transferred from City, State\",\"Public/Private\",\"Comments\"\r\n";
while($row=mysql_fetch_array($result))
{
   $csv.="\"$row[school]\",\"$row[ndenumber]\",\"$row[first] $row[last]\",\"$row[dob]\",\"$row[grade]\",\"$row[otherschool]\",\"$row[othercity], $row[otherstate]\",\"$row[publicprivate]\",\"$row[comments]\"\r\n";
}
$open=fopen(citgf_fopen("/home/nsaahome/reports/transferexport.csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/transferexport.csv");
echo "<tr align=left><td><form method=post action=\"transfers.php\"><input type=hidden name=\"session\" value=\"$session\">";
echo "<b>OR:</b> Add Transfer Students for <select name=\"school_ch\"><option value=\"\">Select School</option>";
$sql="SELECT school FROM headers ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[school]\">$row[school]</option>";
}
echo "</select> <input type=submit name=\"go\" value=\"Go\">";
if($selectstud)
   echo "<div class='error'>Please select a school and click \"Go.\"</div>";
echo "</form></td></tr>";
echo "<tr align=left><td>";
echo "<b>OR:</b> <a href=\"exports.php?session=$session&filename=transferexport.csv\" target=\"_blank\">Export of $nextyear Incoming Transfer Students</a></td></tr>";
//get LAST YEAR's export:
$year=date("Y"); $year1=$year+1; $year0=$year-1;
$archivedb=$db_name.$year0.$year;
$sql="SHOW DATABASES LIKE '$archivedb'";
$result=mysql_query($sql);
$archive=0;
if(mysql_num_rows($result)==0)
{
   $year00=$year0-1;
   $archivedb=$db_name.$year00.$year0;
   $curyear=$year;
   $lastyear=$year0;
   $sql="SHOW DATABASES LIKE '$archivedb'";   
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) $archive=0;
   else $archive=1;
}
else
{
   $archive=1;
   $curyear=$year1;
   $lastyear=$year;
}
if($archive==1) //show link to last year's export
{
   $sql="SELECT * FROM $archivedb.transfers ORDER BY school";
   $result=mysql_query($sql);
   $csv="\"School\",\"NDE #\",\"Student\",\"DOB\",\"Grade\",\"Transferred From School\",\"Transferred from City, State\",\"Public/Private\",\"Comments\"\r\n";
   while($row=mysql_fetch_array($result))
   {   
      $csv.="\"$row[school]\",\"$row[ndenumber]\",\"$row[first] $row[last]\",\"$row[dob]\",\"$row[grade]\",\"$row[otherschool]\",\"$row[othercity], $row[otherstate]\",\"$row[publicprivate]\",\"$row[comments]\"\r\n";
   }
   $open=fopen(citgf_fopen("/home/nsaahome/reports/transferexport".$lastyear.".csv"),"w");
   fwrite($open,$csv); 
 citgf_makepublic("/home/nsaahome/reports/transferexport".$lastyear.".csv");fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/transferexport".$lastyear.".csv");
   echo "<tr align=left><td><b>OR:</b> <a href=\"exports.php?session=$session&filename=transferexport".$lastyear.".csv\">Export of $lastyear Incoming Transfer Students</a></td></tr>";

   //Previous Year:
   $ayears=preg_replace("/[^0-9]/","",$archivedb);
   $ayear1=substr($ayears,0,4); $ayear2=substr($ayears,4,4);
   $ayear1--; $ayear2--;
   $nextdb="nsaascores".$ayear1.$ayear2;
   $sql="SELECT * FROM $nextdb.transfers ORDER BY school";
   $result=mysql_query($sql);
   $csv="\"School\",\"NDE #\",\"Student\",\"DOB\",\"Grade\",\"Transferred From School\",\"Transferred from City, State\",\"Public/Private\",\"Comments\"\r\n";
   while($row=mysql_fetch_array($result))
   {
      $csv.="\"$row[school]\",\"$row[ndenumber]\",\"$row[first] $row[last]\",\"$row[dob]\",\"$row[grade]\",\"$row[otherschool]\",\"$row[othercity], $row[otherstate]\",\"$row[publicprivate]\",\"$row[comments]\"\r\n";
   }
   $open=fopen(citgf_fopen("/home/nsaahome/reports/transferexport".$ayear2.".csv"),"w");
   fwrite($open,$csv); 
 citgf_makepublic("/home/nsaahome/reports/transferexport".$ayear2.".csv");fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/transferexport".$ayear2.".csv");
   echo "<tr align=left><td><b>OR:</b> <a href=\"exports.php?session=$session&filename=transferexport".$ayear2.".csv\">Export of $ayear2 Incoming Transfer Students</a></td></tr>";

   //Previous Year:
   $ayear1--; $ayear2--;
   $nextdb="nsaascores".$ayear1.$ayear2;
   $sql="SELECT * FROM $nextdb.transfers ORDER BY school";
   $result=mysql_query($sql);
   $csv="\"School\",\"NDE #\",\"Student\",\"DOB\",\"Grade\",\"Transferred From School\",\"Transferred from City, State\",\"Public/Private\",\"Comments\"\r\n";
   while($row=mysql_fetch_array($result))
   {
      $csv.="\"$row[school]\",\"$row[ndenumber]\",\"$row[first] $row[last]\",\"$row[dob]\",\"$row[grade]\",\"$row[otherschool]\",\"$row[othercity], $row[otherstate]\",\"$row[publicprivate]\",\"$row[comments]\"\r\n";
   }
   $open=fopen(citgf_fopen("/home/nsaahome/reports/transferexport".$ayear2.".csv"),"w");
   fwrite($open,$csv); 
 citgf_makepublic("/home/nsaahome/reports/transferexport".$ayear2.".csv");fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/transferexport".$ayear2.".csv");
   echo "<tr align=left><td><b>OR:</b> <a href=\"exports.php?session=$session&filename=transferexport".$ayear2.".csv\">Export of $ayear2 Incoming Transfer Students</a></td></tr>";
}

echo "</table>";
echo "</caption>";
$sql="SELECT * FROM transfers WHERE";
if($school && $school!='')
{
   $school2=addslashes($school);
   $sql.=" school='$school2'"; 
}
if(trim($first)!='')
{
   if($sql=="SELECT * FROM transfers WHERE")
      $sql.=" first LIKE '$first%'";
   else
      $sql.=" AND first LIKE '$first%'";
}
if(trim($last)!='')
{
   if($sql=="SELECT * FROM transfers WHERE")
      $sql.=" last LIKE '$last%'";
   else
      $sql.=" AND last LIKE '$last%'";
}
if($sql=="SELECT * FROM transfers WHERE")
   $sql="SELECT * FROM transfers";
$sql.=" ORDER BY school,last,first";
$result=mysql_query($sql);
//echo $sql;
if($go)
{
   if(mysql_num_rows($result)==0)
      echo "<tr align=center><td class=nine><i>Your filter return no results.</i></td></tr>";
   else
   {
      echo "<tr align=center><td><b>School</b><br>(Click to view/edit Transfer List)</td><td><b>Student Transferring</b></td>";
      echo "<td><b>School Transferred From</b></td><td><b>Comments</b></td></tr>";
   }
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left valign=top><td><a href=\"transfers.php?session=$session&school_ch=$row[school]\">$row[school]</a></td>";
   echo "<td><b>$row[first] $row[last]</b><br>";
   $date=split("-",$row[dob]);
   echo "DOB: $date[1]/$date[2]/$date[0]<br>";
   echo "Grade: $row[grade]<br>NDE#: $row[ndenumber]</td>";
   echo "<td>$row[otherschool]";
   if($row[othercity]!='')
   {
      echo "<br>$row[othercity], $row[otherstate]<br>";
      echo "($row[publicprivate])";
   }
   echo "</td><td width=400>$row[comments]</td>";
   echo "</tr>";
}
}
echo "</table>";
echo $end_html;
?>
