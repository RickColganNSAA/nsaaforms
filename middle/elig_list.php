<?php
/************************************
elig_list.php
Frame showing Middle School Students
Created 12/26/09
Author: Ann Gaffigan
*************************************/
require '../variables.php';
require '../functions.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//validate user
if(!ValidUser($session))
{
?>
   <html><body>
   <script language="javascript">
   top.location.replace('index.php');
   </script></body></html>
<?php
   exit();
}

$level=GetLevel($session);
if($level==8) $school=GetSchool($session);
else $school=$school_ch;
$school2=addslashes($school);

if($level==1)	//level-1/NSAA access
{
   $multiple_schools=0;
   if(ereg("All Schools",$school))
   {
      $sql="SELECT * FROM middleeligibility";
      $multiple_schools=1;
   }
   else 
   {
      $sql="SELECT * FROM middleeligibility WHERE (";
      $schools=split(",",$school2);
      for($i=0;$i<count($schools);$i++)
      {
         $sql.="school='$schools[$i]' OR ";
      }
      $sql=substr($sql,0,strlen($sql)-3).")";
      if(count($schools)>1)  $multiple_schools=1;
   }
}
else if($level==8)	//School Administrator Access
{
   $sql="SELECT * FROM middleeligibility WHERE school='$school2'";
}

//ADVANCED SEARCH
//If this is an advanced search, add more to the SQL statement:
if($gender && $gender!="Any")
{
   if(ereg("WHERE",$sql)) $sql.=" AND";
   else $sql.=" WHERE";
   $sql.=" gender='$gender'";
}
if($grade && $grade!="Any")
{
   if(ereg("WHERE",$sql)) $sql.=" AND";
   else $sql.=" WHERE";
   //change grade to semesters
   switch($grade)
   {
      case 7: 
         $sql.=" (semesters='1' OR semesters='2')";
	 break;
      case 8:
	 $sql.=" (semesters='3' OR semesters='4')";
	 break;
      default:
	 $sql.=" semesters='0'";
   }
}
if($eligible)
{
   if(ereg("WHERE",$sql))   $sql.=" AND";
   else $sql.=" WHERE";
   $sql.=" eligible='$eligible'";
}
if($physical)
{
   if(ereg("WHERE",$sql))   $sql.=" AND";
   else $sql.=" WHERE";
   $sql.=" physical='$physical'";
}
if($parent)
{
   if(ereg("WHERE",$sql))   $sql.=" AND";
   else $sql.=" WHERE";
   $sql.=" parent='$parent'";
}
?>
<html>
<head>
<script language="javascript">
function Color(element)
{
   while(element.tagName.toUpperCase() != 'TD' && element != null)
      element = document.all ? element.parentElement : element.parentNode;
   if(element)
   {
      element.bgColor="FFFF33";
   }
}

win_progressbar=window.open('progressbar.htm','Searching','channelmode=no, directories=no, toolbar=no,titlebar=no,left=300,top=500,width=300,height=100,status=no,scrollbars=no,resizable=no,menubar=no');

win_progressbar.opener=self;

function close_progressbar()
{
   if(!win_progressbar.closed)  win_progressbar.close();
}
</script>
<link rel="stylesheet" href="../../css/nsaaforms.css" type="text/css">
</head>
<body onLoad="close_progressbar();">
<?php echo GetHeader($session); ?>
<table width=100% bordercolor=#000000 border=1 cellspacing="0" cellpadding="0">
<form method="post" name="elig_form" action="update_elig.php">
<input type=hidden name=session value="<?php echo $session; ?>">
<input type=hidden name=school_ch value="<?php echo $school_ch_str; ?>">
<input type=hidden name=letter value="<?php echo $last; ?>">
<?php
//***DISPLAY STUDENTS***//
$ix=0;	//ix is used to see if row is even or odd
$result=mysql_query($sql);
$tot_ct=mysql_num_rows($result);
echo "<tr align=left><td colspan=7>Your search returned <b>$tot_ct</b> results,";
if($last!="All" && ereg("WHERE",$sql)) $sql.=" AND last LIKE '$last%'";
$sql.=" ORDER BY last";
$result=mysql_query($sql);
$ct=mysql_num_rows($result);
echo " <b>$ct</b> of which are showing:";
//show links to letters of alphabet for navigation:
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$alphabet=array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
for($i=0;$i<count($alphabet);$i++)
{
   $upper=strtoupper($alphabet[$i]);
   if($last==$alphabet[$i])
   {
      echo "<b><font size=2>$upper&nbsp;</font></b>";
   }
   else
   {
      echo "<a href=\"elig_list.php?school_ch=$school_ch_str&session=$session&gender=$gender&grade=$grade&eligible=$eligible&physical=$physical&parent=$parent&last=$alphabet[$i]\">$upper</a>&nbsp;";
   }
}
if(!$last)   echo "<b><font size=2>All</font></b>";
else
   echo "<a href=\"elig_list.php?school_ch=$school_ch_str&session=$session&gender=$gender&grade=$grade&eligible=$eligible&physical=$physical&parent=$parent\">All</a>";
echo "</td></tr>";
while($row=mysql_fetch_array($result))
{
   //get student id and submit as hidden to form
   echo "<input type=hidden name=student_id[$ix] value=\"$row[id]\">";
   if($ix%15==0)
   {
?>
<tr align=center>
<th class=small>Name<br>(Last, First (Nickname) MI)</th>
<th class=small title="Gender">M/F</th>
<th class=small title="Date of Birth">Date of Birth<br>(yyyy-mm-dd)</th>
<th class=small title="Semesters of Attendance">Semesters</th>
<th class=small title="Eligible">Eligible</th>
<th class=small title="Physical Exam">Physical Exam</th>
<th class=small title="Parent Consent Form">Parent Consent Form</th>
</tr>
<?php
   }
   echo "<tr title=\"$row[last], $row[first] $row[middle]\" align=center";
   if($ix%2==0)
   {
      $color="#E0E0E0";
      echo " bgcolor='$color'";
   }
   else $color="#FFFFFF";
   echo ">";
   echo "<td align=left";
   if($row[eligible]!="y") 		     //student is ineligible
	echo " bgcolor=\"red\"";
   else if(WillBeTooOldM($row[dob],$row[semesters]))    //student will be 19 by sr yr
	echo " bgcolor=\"#FFCC00\"";
   echo "> <a class=small style=\"color:black\" target=\"_top\" title=\"$row[1]\" href=\"edit_student.php?session=$session&id=$row[id]&school_ch=$row[1]&letter=$last\">";
   echo "$row[last], $row[first] $row[middle]</a></td>";
   echo "<td>$row[gender]</td>";
   echo "<td>$row[dob]</td><td>$row[semesters]</td>";
   
   //submit student info as hidden to form:
   echo "<input type=hidden name=\"last[$ix]\" value=\"$row[last]\">";
   echo "<input type=hidden name=\"first[$ix]\" value=\"$row[first]\">";
   echo "<input type=hidden name=\"middle[$ix]\" value=\"$row[middle]\">";
   echo "<input type=hidden name=\"dob[$ix]\" value=\"$row[dob]\">";
   echo "<input type=hidden name=\"semesters[$ix]\" value=\"$row[semesters]\">";

   echo "<td><input type=checkbox onClick=\"Color(this)\" name=\"eligible[$ix]\" value=y";
   if($row[eligible]=="y") echo " checked";
   echo "></td>";
   echo "<td";
   if($row[physical]=="n") echo " bgcolor='#00ff99'";
   echo "><input type=checkbox onClick=\"Color(this)\" name=\"physical[$ix]\" value=y";
   if($row[physical]=="y") echo " checked";
   echo "></td>";
   echo "<td";
   if($row[parent]=="n") echo " bgcolor='#6699ff'";
   echo "><input type=checkbox onClick=\"Color(this)\" name=\"parent[$ix]\" value=y";
   if($row[parent]=="y") echo " checked";
   echo "></td>";
   echo "</tr>";
   $ix++;
}
?>
</table>
<br>
<input type=hidden name=count value="<?php echo $ix; ?>">
</form>
</body>
</html>
