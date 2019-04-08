<?php
/**********************************
export_students.php
Allow school to export 7th graders
from last year to be imported into
this year's DB
Copied 12/29/09 from ../export_students.php
Author: Ann Gaffigan
***********************************/
require '../functions.php';
require '../variables.php';

$level=GetLevel($session);

//validate user
if(!ValidUser($session) || ($level!=1 && $level!=8))
{
   header("Location:index.php?error=1");
   exit();
}

if(!$submit)
{
   $school=GetSchool($session);
}
$school2=ereg_replace("\'","\'",$school);

echo $init_html;
echo GetHeader($session);

//input school as hidden unless user is NSAA/Level 1:
if($school!="All")
   echo "<input type=hidden name=school value=\"$school\">";

//Figure out what the last year archived was.  Will show those rosters below current ones:
$year=date("Y"); $year1=$year+1; $year0=$year-1;
$archivedb="$db_name".$year0.$year;
$sql="SHOW DATABASES LIKE '$archivedb'";
$result=mysql_query($sql);
$archive=0;
if(mysql_num_rows($result)==0)
{
   $year00=$year0-1;
   $archivedb="$db_name".$year00.$year0;
   $curyear="$year0-$year";
   $lastyear="$year00-$year0";
   $sql="SHOW DATABASES LIKE '$archivedb'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) $archive=0;
   else $archive=1;
}
else
{
   $archive=1;
   $curyear="$year-$year1";
   $lastyear="$year0-$year";
}
if($archive==0)
{
   echo "<br><br><b>No archived eligiblity list is currently available for export.<br><br>";
   exit();
}
echo "<form method=\"export_students.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
?>
<table width=80%>
<tr align=center>
<th>Export Student Eligibility List from <?php echo $curyear; ?> Eligibility Database</th>
</tr>
<tr><td><hr></td></tr>
<tr align=left><td>
<?php
echo "
<p>An easy way to populate your $curyear database is to export your 7th-Graders from last year, add them to the database and THEN add your incoming 7th Graders for $curyear.  If this makes sense for your school, please follow the instructions below.  If NOT, please populate your $curyear eligibility database either <a href=\"add_students.php?session=$session\">Manually</a> OR by <a href=\"import_students.php?session=$session\">Importing a file of ALL your students</a> for the $curyear school year.</p>";
?>
<p><b><u>To Export Last Year's 7th-Graders:</b></u><br></p>
<p>1)&nbsp;&nbsp;Your school's export of last year's 7th-Graders is ready for you to download below.  This file includes the following fields of information, in this order, and delimited by commas: <i><b>last name, first name (alias), middle initial, gender, date of birth, </i></b>and <i><b>semesters of attendance</b></i>.  Each line will hold a different student's information, as shown in the following example:</p>
<table width=80%>
<tr align=left><td>
&nbsp;Smith&nbsp;,&nbsp;John&nbsp;,&nbsp;T&nbsp;,&nbsp;M&nbsp;,&nbsp;10-18-<?php echo date("Y")-14; ?>&nbsp;,&nbsp;3&nbsp;<br>
&nbsp;Johnson&nbsp;,&nbsp;Kimberly (Jane)&nbsp;,&nbsp;J&nbsp;,&nbsp;F&nbsp;,&nbsp;05-03-<?php echo date("Y")-14; ?>&nbsp;,&nbsp;3&nbsp;<br>
&nbsp;Hanson&nbsp;,&nbsp;Troy&nbsp;,&nbsp;&nbsp;,&nbsp;M&nbsp;,&nbsp;11-12-<?php echo date("Y")-15; ?>&nbsp;,&nbsp;3&nbsp;
</td></tr>
</table> </td></tr>
<?php
echo "<tr align=center><td>";

   //if user is NSAA/Level 1, have them choose which school:
   if($level==1)
   {
      //get array of schools from middleschools table in db
      $sql="SELECT school FROM middleschools ORDER BY school";
      $result=mysql_query($sql);
      $ix=0;
      $schools=array();
      while($row=mysql_fetch_array($result))
      {
	 $schools[$ix]=$row[0];
	 $ix++;
      }
      echo "<select name=school>";
      echo "<option>Choose a Middle School</option>";
      for($i=0;$i<count($schools);$i++)
      {
	 echo "<option";
         if($school_ch==$schools[$i]) echo " selected";
         echo ">$schools[$i]</option>";
      }
      echo "</select>&nbsp;<input type=submit name=submit value=\"Create Export\"><br><br>";
   }
   if($school!="All")
   {
   //create export:
   $sql="SELECT * FROM $archivedb.middleeligibility WHERE school='$school2' AND semesters<4 ORDER BY last,first,middle";
   $result=mysql_query($sql);
   $csv="";
   while($row=mysql_fetch_array($result))
   {
      if($row[semesters]>0)
         $semesters=$row[semesters]+1;
      else
	 $semesters=$row[semesters];
      $temp=split("-",$row[dob]); $dob="$temp[1]-$temp[2]-$temp[0]";
      $csv.="$row[last],$row[first],$row[middle],$row[gender],$dob,$semesters\r\n";
   }
   $today=time();
   $filename="eligexport".$today.".csv";
   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename");
   echo "<a target=new href=\"attachments.php?session=$session&filename=$filename\">Click Here to Download $school's $lastyear Eligibility Export</a><br><br>";
   }
   echo "</td>
</tr>";

echo "<tr align=left><td><p>2)&nbsp;&nbsp;<u>Save the exported file to your Desktop</u>.  This file is now ready to import into your current eligibility database (the semester of each student has been increased by 1, so the $lastyear 7th-Graders will now be listed as 8th-Graders).<br></td></tr>";
echo "<tr align=center>
<td><a href=\"import_students.php?session=$session&school_ch=$school\">Click Here to Import your File into the $school $curyear Eligibility Database</a></td>
</tr>";
?>
</table>
</form> 
</center>
<?php echo $end_html; ?>
