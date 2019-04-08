<?php
require 'variables.php';
require 'functions.php';

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

$level=GetLevel($session);

session_start();

//save school_ch and activity_ch strings to be submitted with rest of form:
$school_ch_str=$school_ch;
$activity_ch_str=$activity_ch;
$school_ch2=ereg_replace("\'","\'",$school_ch);
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
<link rel="stylesheet" href="../css/nsaaforms.css" type="text/css">
</head>
<body onLoad="close_progressbar();">
<table width=100% bordercolor=#000000 border=1 cellspacing="0" cellpadding="0">
<form method="post" name="elig_form" action="update_elig.php">
<input type=hidden name=session value="<?php echo $session; ?>">
<input type=hidden name=activity_ch value="<?php echo $activity_ch_str; ?>">
<input type=hidden name=school_ch value="<?php echo $school_ch_str; ?>">
<input type=hidden name=letter value="<?php echo $last; ?>">

<?php

//***DISPLAY STUDENTS***//
$ix=0;	//ix is used to see if row is even or odd
if($_SESSION['query']) $sql=$_SESSION['query'];
//echo $sql;
$result=mysql_query($sql);
$tot_ct=mysql_num_rows($result);
echo "<tr align=left><td colspan=27>Your search returned <b>$tot_ct</b> results,";

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
      echo "<a href=\"elig_list.php?school_ch=$school_ch_str&activity_ch=$activity_ch_str&session=$session&gender=$gender&grade=$grade&transfer=$transfer&ineligible=$ineligible&foreign_x=$foreign_x&enroll_option=$enroll_option&last=$alphabet[$i]\">$upper</a>&nbsp;";
   }
}
if(!$last)   echo "<b><font size=2>All</font></b>";
else
   echo "<a href=\"elig_list.php?school_ch=$school_ch_str&activity_ch=$activity_ch_str&session=$session&gender=$gender&grade=$grade&transfer=$transfer&ineligible=$ineligible&foreign_x=$foreign_x&enroll_option=$enroll_option\">All</a>";
echo "</td></tr>";

while($row=mysql_fetch_array($result))
{
   //get student id and submit as hidden to form
   echo "<input type=hidden name=student_id[$ix] value=$row[0]>";
   if($ix%15==0)
   {
?>
<tr height=27 align=center>
<th class=small width=16%>Name<br>(last, first(alias) MI)</th>
<th class=small width=3% title="Gender">M/F</th>
<th class=small width=6% title="Date of Birth">DOB<br>(mm-dd-yyyy)</th>
<th class=small width=3% title="Semesters of Attendance">Sem</th>
<th width=3% class=small title="Eligible">E</th>
<!--<th width=3% class=small title="Transfer">T</th>-->
<th width=3% class=small title="International Transfer">iT</th>
<!--<th width=3% class=small title="Enrollment Option">New<br>EO</th>-->
<th width=3% class=small title="Football 6/8">FB<br>6/8</th>
<th width=3% class=small title="Football 11">FB<br>11</th>
<th width=3% class=small title="Volleyball">VB</th>
<th width=3% class=small title="Softball">SB</th>
<th width=3% class=small title="Cross-Country">CC</th>
<th width=3% class=small title="Tennis">TE</th>
<th width=3% class=small title="Basketball">BB</th>
<th width=3% class=small title="Wrestling">WR</th>
<th width=3% class=small title="Swimming">SW</th>
<th width=3% class=small title="Golf">GO</th>
<th width=3% class=small title="Track & Field">TR</th>
<th width=3% class=small title="Baseball">BA</th>
<th width=3% class=small title="Soccer">SO</th>
<th width=3% class=small title="Cheerleading/Spirit">CH</th>
<th width=3% class=small title="Speech">SP</th>
<th width=3% class=small title="Play Production">PP</th>
<th width=3% class=small title="Debate">DE</th>
<th width=3% class=small title="Instrumental Music">IM</th>
<th width=3% class=small title="Vocal Music">VM</th>
<th width=3% class=small title="Journalism">JO</th>
<th width=3% class=small title="Unified Bowling">UBO</th>
<th width=3% class=small title="Unified Track & Field">UTR</th>
</tr>
<?php
   }
   echo "<tr title=\"$row[2], $row[3] $row[4]\" align=center";
   if($ix%2==0)
   {
      $color="#D0D0D0";
      echo " bgcolor=#D0D0D0";
   }
   else $color="#FFFFFF";
   echo ">";
   echo "<td align=left width=16%";

   if($row[11]!="y") 		     //student is ineligible
	echo " bgcolor=\"red\"";
   else if(WillBeTooOld($row[7],$row[8]))    //student will be 19 by sr yr
	echo " bgcolor=\"#FFCC00\"";
   //else if($row[15]=="y")	//student took enrollment option
	//echo " bgcolor=\"#00FF00\"";
   echo "> <a class=small style=\"color:black\" target=\"_top\" title=\"$row[1]\" href=\"view_student.php?session=$session&id=$row[0]&activity_ch=$activity_ch_str&school_ch=$row[1]&letter=$last\">";
   echo "$row[2], $row[3] $row[4]</a></td>";
   echo "<td width=3%>$row[5]</td>";
   echo "<td width=6%>$row[7]</td><td width=3%>$row[8]</td>";
   
   //submit student info as hidden to form:
   echo "<input type=hidden name=last[$ix] value=$row[2]>";
   echo "<input type=hidden name=first[$ix] value=$row[3]>";
   echo "<input type=hidden name=middle[$ix] value=$row[4]>";
   echo "<input type=hidden name=dob[$ix] value=$row[7]>";
   echo "<input type=hidden name=semesters[$ix] value=$row[8]>";

   //display checkboxes for transfer, eligible, enrollment opt, foreign x:
   echo "<td width=3%><input type=checkbox onClick=\"Color(this)\" name=eligible[$ix] value='y'";
   if($row[11]=="y") echo " checked";
   echo "></td>";
   /*hide transfer column
   echo "<td width=3%><input type=checkbox onClick=\"Color(this)\" name=transfer[$ix] value='y'";
   if($row[9]=="y") echo " checked"; 
   echo "></td>";
   */
   echo "<td width=3%><input type=checkbox onClick=\"Color(this)\" name=foreignx[$ix] value='y'";
   if($row[13]=="y") echo " checked"; 
   echo "></td>";
   /*hide new eo column
   echo "<td width=3%><input type=checkbox onClick=\"Color(this)\" name=enroll_option[$ix] value='y'";
   if($row[15]=="y") echo " checked";
   echo "></td>";
   */
   for($i=0;$i<22;$i++)
   {
      echo "<td width=3%><input type=\"checkbox\" onClick=\"Color(this)\" name=\"".$activity[$i]."[$ix]\" value=\"x\"";      
      if($row[$activity[$i]]=="x") echo " checked";
      echo "></td>";
   }
   echo "</tr>";
   $ix++;
}
?>
</table>
<br>
<input type=hidden name=count value=<?php echo $ix; ?>>
</form>
</body>
</html>
