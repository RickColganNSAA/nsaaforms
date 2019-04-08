<?php
//view_survey.php: view activity participation surveys for specified year

require 'functions.php';
require 'variables.php';

$level=GetLevel($session);
//validate user
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php");
   exit();
}

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name,$db);

?>
<script language="javascript">
window.status='Please be patient.  These queries may take some time...';
</script>

<html>
<head>
   <title>NSAA Home</title>
   <link href="../css/nsaaforms.css" rel="stylesheet" type="text/css">
</head>
<script language="javascript">
win_progressbar=window.open('progressbar.htm','Searching','channelmode=no, directories=no, toolbar=no,titlebar=no,left=300,top=500,width=300,height=100,status=no,scrollbars=no,resizable=no,menubar=no');
win_progressbar.opener=self;
function close_progressbar()
{
   if(!win_progressbar.closed)  win_progressbar.close();
}
</script>
<body onLoad="close_progressbar();">
<table width=100%>
<tr align=center><td>
<a href="welcome.php?session=<?php echo $session; ?>" class=small>Home</a><br>
   <br>
   <form method=post action="view_survey.php">
   <input type=hidden name=session value="<?php echo $session; ?>">
   <input type=hidden name=method value="<?php echo $method; ?>">
<?php
   $sql="SHOW DATABASES LIKE '$db_name%'";
   $result=mysql_query($sql);
   echo "<b>Select a Year:</b><select name=database>";
   while($row=mysql_fetch_array($result))
   {
      echo "<option";
      if($row[0]==$database) echo " selected";
      if($row[0]=="$db_name")
         echo " value=\"$row[0]\">This Year</option>";
      else
      {
         $temp=split("nsaascores",$row[0]);
         $year1=substr($temp[1],0,4);
         $year2=substr($temp[1],4,4);
         echo " value=\"$row[0]\">$year1-$year2</option>";
      }
   }
   echo "</select>&nbsp;";

   echo "<b>Select a school:</b>";
   echo "<select name=school_ch>";
   echo "<option value=\"Unique Count\"";
   if($school_ch=="Unique Count") echo " selected";
   echo ">Number of Students Participating in >=1 NSAA Activity</option>";
   echo "<option value=\"All Sports\"";
   if($school_ch=="All Sports") echo " selected";
   echo ">All Schools, broken down by school & sport</option>";
   echo "<option value=\"All Schools\"";
   if($school_ch=="All Schools") echo " selected";
   echo ">All Schools, broken down by sport & grade</option>";

//get list of schools
$sql="SELECT school FROM $database.headers WHERE school!='Test\'s School' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option";
   if($school_ch==$row[0]) echo " selected";
   echo ">$row[0]";
}
?>
   </select>
   <input type=submit name=submit value="Go">
   </form>
   <hr>
<?php
if($submit=="Go" && $school_ch=="Unique Count")
{
   echo "<table><tr align=center><td>";
   echo "<table cellspacing=0 cellpadding=3 class='nine' frame=all rules=all style=\"border:#808080 1px solid;\">";
   echo "<caption><b>Annual Activity Participation Report:<br>Number of Students Participating in at Least 1 NSAA Activity</b></caption>";
   echo "<tr align=center><th rowspan=2>&nbsp;</th>";
   echo "<td colspan=2><b>Grade <9</b></td>";
   echo "<td colspan=2><b>Grade 9</b></td><td colspan=2><b>Grade 10</b></td>";
   echo "<td colspan=2><b>Grade 11</b></td><td colspan=2><b>Grade 12</b></td>";
   echo "<td colspan=2><b>Semesters>8</b></td>";
   echo "<td rowspan=2><b>Total</b></td></tr>"; 
   echo "<tr align=center><td><b>Boys</b></td><td><b>Girls</b></td>";
   echo "<td><b>Boys</b></td><td><b>Girls</b></td>";
   echo "<td><b>Boys</b></td><td><b>Girls</b></td>";
   echo "<td><b>Boys</b></td><td><b>Girls</b></td>";
   echo "<td><b>Boys</b></td><td><b>Girls</b></td>";
   echo "<td><b>Boys</b></td><td><b>Girls</b></td></tr>";
   $sql="SELECT * FROM headers WHERE school!='Test\'s School' ORDER BY school";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $school2=addslashes($row[school]);
      echo "<tr align=center><td align=left><b>$row[school]</b></td>";
      //SEMESTER<1
      $sql2="SELECT DISTINCT id FROM $database.eligibility WHERE school='$school2' AND semesters<1 AND gender='M' AND (";
      for($j=0;$j<count($activity);$j++)
      {
         $sql2.="$activity[$j]='x' OR ";
      }
      $sql2=substr($sql2,0,strlen($sql2)-4);
      $sql2.=")";
      $result2=mysql_query($sql2);
      echo "<td>".mysql_num_rows($result2)."</td>";
      $sql2=ereg_replace("gender='M'","gender='F'",$sql2);
      $result2=mysql_query($sql2);
      echo "<td>".mysql_num_rows($result2)."</td>";
      for($i=1;$i<=7;$i+=2)
      {
	 $i2=$i+1;
	 $sql2="SELECT DISTINCT id FROM $database.eligibility WHERE school='$school2' AND (semesters='$i' OR semesters='$i2') AND gender='M' AND (";
	 for($j=0;$j<count($activity);$j++)
	 {
	    $sql2.="$activity[$j]='x' OR ";
         }
	 $sql2=substr($sql2,0,strlen($sql2)-4);
	 $sql2.=")";
	 $result2=mysql_query($sql2);
	 echo "<td>".mysql_num_rows($result2)."</td>";
	 $sql2=ereg_replace("gender='M'","gender='F'",$sql2);
	 $result2=mysql_query($sql2);
	 echo "<td>".mysql_num_rows($result2)."</td>";
      }
      //SEMESTER>8
      $sql2="SELECT DISTINCT id FROM $database.eligibility WHERE school='$school2' AND semesters>8 AND gender='M' AND (";
      for($j=0;$j<count($activity);$j++)
      {
         $sql2.="$activity[$j]='x' OR ";
      }
      $sql2=substr($sql2,0,strlen($sql2)-4);
      $sql2.=")";
      $result2=mysql_query($sql2);
      echo "<td>".mysql_num_rows($result2)."</td>";
      $sql2=ereg_replace("gender='M'","gender='F'",$sql2);
      $result2=mysql_query($sql2);
      echo "<td>".mysql_num_rows($result2)."</td>";
      //TOTAL
      $sql2="SELECT DISTINCT id FROM $database.eligibility WHERE school='$school2' AND (";
      for($j=0;$j<count($activity);$j++)
      {
         $sql2.="$activity[$j]='x' OR ";
      }
      $sql2=substr($sql2,0,strlen($sql2)-4);
      $sql2.=")";
      $result2=mysql_query($sql2);
      echo "<td>".mysql_num_rows($result2)."</td></tr>";
   } 	//END FOR EACH SCHOOL
   //TOTALS:
   echo "<tr align=center><th rowspan=2>&nbsp;</th>";
   echo "<td colspan=2><b>Grade <9</b></td>";
   echo "<td colspan=2><b>Grade 9</b></td><td colspan=2><b>Grade 10</b></td>";
   echo "<td colspan=2><b>Grade 11</b></td><td colspan=2><b>Grade 12</b></td>";
   echo "<td colspan=2><b>Semesters>8</b></td>";
   echo "<td rowspan=2><b>Total</b></td></tr>";
   echo "<tr align=center><td><b>Boys</b></td><td><b>Girls</b></td>";
   echo "<td><b>Boys</b></td><td><b>Girls</b></td>";
   echo "<td><b>Boys</b></td><td><b>Girls</b></td>";
   echo "<td><b>Boys</b></td><td><b>Girls</b></td>";
   echo "<td><b>Boys</b></td><td><b>Girls</b></td>";
   echo "<td><b>Boys</b></td><td><b>Girls</b></td></tr>";
   echo "<tr align=center><td align=right><b>TOTALS:</b></td>";
      //SEMESTER<1
      $sql2="SELECT DISTINCT id FROM $database.eligibility WHERE semesters<1 AND gender='M' AND school!='Test\'s School' AND (";
      for($j=0;$j<count($activity);$j++)
      {
         $sql2.="$activity[$j]='x' OR ";
      }
      $sql2=substr($sql2,0,strlen($sql2)-4);
      $sql2.=")";
      $result2=mysql_query($sql2);
      echo "<td>".mysql_num_rows($result2)."</td>";
      $sql2=ereg_replace("gender='M'","gender='F'",$sql2);
      $result2=mysql_query($sql2);
      echo "<td>".mysql_num_rows($result2)."</td>";
   for($i=1;$i<=7;$i+=2)
   {
      $i2=$i+1;
      $sql2="SELECT DISTINCT id FROM $database.eligibility WHERE (semesters='$i' OR semesters='$i2') AND gender='M' AND school!='Test\'s School' AND (";
      for($j=0;$j<count($activity);$j++)
      {
         $sql2.="$activity[$j]='x' OR ";
      }
      $sql2=substr($sql2,0,strlen($sql2)-4);
      $sql2.=")";
      $result2=mysql_query($sql2);
      echo "<td>".mysql_num_rows($result2)."</td>";
      $sql2=ereg_replace("gender='M'","gender='F'",$sql2);
      $result2=mysql_query($sql2);
      echo "<td>".mysql_num_rows($result2)."</td>";
   }
      //SEMESTER>8
      $sql2="SELECT DISTINCT id FROM $database.eligibility WHERE semesters>8 AND gender='M' AND school!='Test\'s School' AND (";
      for($j=0;$j<count($activity);$j++)
      {
         $sql2.="$activity[$j]='x' OR ";
      }
      $sql2=substr($sql2,0,strlen($sql2)-4);
      $sql2.=")";
      $result2=mysql_query($sql2);
      echo "<td>".mysql_num_rows($result2)."</td>";
      $sql2=ereg_replace("gender='M'","gender='F'",$sql2);
      $result2=mysql_query($sql2);
      echo "<td>".mysql_num_rows($result2)."</td>";
   //TOTALS
   $sql2="SELECT DISTINCT id FROM $database.eligibility WHERE school!='Test\'s School' AND (";
   for($j=0;$j<count($activity);$j++)
   {
      $sql2.="$activity[$j]='x' OR ";
   }
   $sql2=substr($sql2,0,strlen($sql2)-4);
   $sql2.=")";
   $result2=mysql_query($sql2);
   echo "<td><b>".mysql_num_rows($result2)."</b></td></tr>";
   echo "</table>";
}
else if($submit=="Go" && $school_ch=="All Sports")
{
   echo "<table><tr align=center><td>";
   echo "<table cellspacing=0 cellpadding=3 class='nine' frame=all rules=all style=\"border:#808080 1px solid;\">";
   echo "<caption><b>Annual Activity Participation Report: All Activities, All Schools</b></caption>";
   echo "<tr align=center><th>&nbsp;</th>";
   for($i=0;$i<count($activity);$i++)
   {
      echo "<td><b>";
      if($activity[$i]=='cc' || $activity[$i]=='te' || $activity[$i]=='bb' || $activity[$i]=='sw' || $activity[$i]=='go' || $activity[$i]=='tr' || $activity[$i]=='so')
      {
         echo "Boys<br>".strtoupper($activity[$i])."</b></td>";
         echo "<td><b>Girls<br>".strtoupper($activity[$i])."</b></td>";
      }
      else
         echo strtoupper($activity[$i])."</b></td>";
   }
   echo "<td><b>Total</b></td>";
   echo "</tr>";
   $sql="SELECT school FROM $database.headers WHERE school!='Test\'s School' ORDER BY school";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $total=0;
      $school2=addslashes($row[school]);
      echo "<tr align=center><td align=left><b>$row[school]</b></td>";
      for($i=0;$i<count($activity);$i++)
      {
         if($activity[$i]=='cc' || $activity[$i]=='te' || $activity[$i]=='bb' || $activity[$i]=='sw' || $activity[$i]=='go' || $activity[$i]=='tr' || $activity[$i]=='so')
  	 {
	    $sql2="SELECT id FROM $database.eligibility WHERE $activity[$i]='x' AND gender='M' AND school='$school2'";
	    $result2=mysql_query($sql2);
	    $curcount=mysql_num_rows($result2);
            echo "<td>$curcount</td>";
	    $total+=$curcount;
	    $sql2="SELECT id FROM $database.eligibility WHERE $activity[$i]='x' AND gender='F' AND school='$school2'";
	    $result2=mysql_query($sql2);
	    $curcount=mysql_num_rows($result2);
	    echo "<td>$curcount</td>";
	    $total+=$curcount;
	 }
	 else
	 {
	    $sql2="SELECT id FROM $database.eligibility WHERE $activity[$i]='x' AND school='$school2'";
	    $result2=mysql_query($sql2);
	    $curcount=mysql_num_rows($result2);
	    echo "<td>$curcount</td>";
	    $total+=$curcount;
	 }
      }
      echo "<td>$total</td>";
      echo "</tr>";
   } 
   echo "</table></td></tr></table>";
}
else if($submit=="Go" && $school_ch!="All Sports")
{
//show sports surveys by season
?>
   <table>
   <tr align=center>
   <td>
  <table cellspacing=0 cellpadding=3 class='nine' frame=all rules=all style="border:#808080 1px solid;">
<caption><b>Annual Activity Participation Report: All Activities</b></caption>
<tr align=center><th>&nbsp;</th>
<th class=smaller colspan=5>Boys:</th>
<th></th>
<th class=smaller colspan=5>Girls:</th>
</tr>
<tr align=center><th class=smaller>Activity:</th>
<th class=smaller>9</th><th class=smaller>10</th>
<th class=smaller>11</th><th class=smaller>12</th>
<th class=smaller>All</th>
<th></th>
<th class=smaller>9</th><th class=smaller>10</th>
<th class=smaller>11</th><th class=smaller>12</th>
<th class=smaller>All</th>
</tr>
<?php
   $totals=array();	//array of totals for each activity
   //get total participation count for each activity, sorted by gender and then grade
   $music=array();
   $line=0;
   for($i=0;$i<count($activity);$i++)
   {
      echo "<tr ";
	if($line%2==0) echo "bgcolor='#f0f0f0'";
	echo "align=center><td align=left><b>$act_long2[$i]</b></td>";
      $male=0;
      $female=0;
      for($j=0;$j<4;$j++)	//for each class, fresh through senior, get count for this activity
      {
	 $grade1=($j*2)+1;	//fall semester
	 $grade2=($j*2)+2;	//spring semester
	 $sql="SELECT * FROM $database.eligibility WHERE $activity[$i]='x' AND (semesters='$grade1' OR semesters='$grade2') AND gender='M'";
	 if(!ereg("All Schools",$school_ch))
	 {
	    $school_ch2=ereg_replace("\'","\'",$school_ch);
	    $sql.=" AND school='$school_ch2'";
	 }
         else
	    $sql.=" AND school!='Test\'s School'";
	 $result=mysql_query($sql);
	 $totals[$i][$j][0]=mysql_num_rows($result);	//total[i][j][0]=total for activity i, grade j, males
	 $total=$totals[$i][$j][0];
	 echo "<td width='30px' align=center>$total</td>";
	 $male+=$total;
	 if($activity[$i]=="im" || $activity[$i]=="vm")	//add to music total
	 {
	    $music[$j][0]+=$total;
	 }
      }
      echo "<td width='30px' bgcolor='yellow'>$male</td>";
      //do same thing for females:
      echo "<td></td>";
      for($j=0;$j<4;$j++)
      {
	 $grade1=($j*2)+1;
	 $grade2=($j*2)+2;
	 $sql="SELECT * FROM $database.eligibility WHERE $activity[$i]='x' AND (semesters='$grade1' OR semesters='$grade2') AND gender='F'";
	 if(!ereg("All Schools",$school_ch)) 
	 {
	    $school_ch2=ereg_replace("\'","\'",$school_ch);
	    $sql.=" AND school='$school_ch2'";
	 }
	 else
	    $sql.=" AND school!='Test\'s School'";
	 $result=mysql_query($sql);
	 $totals[$i][$j][1]=mysql_num_rows($result);
	 $total=$totals[$i][$j][1];	//total for activity i, grade j, female
	 echo "<td width='30px'>$total</td>";
	 $female+=$total;
	 if($activity[$i]=="im" || $activity[$i]=="vm")
	 {
	    $music[$j][1]+=$total;
	 }
      }
      echo "<td width='30px' bgcolor='yellow'>$female</td>";
      echo "</tr>";
	$line++;
      if($activity[$i]=="vm")	//show Music total
      {
	 $musicmale=$music[0][0]+$music[1][0]+$music[2][0]+$music[3][0];
	 $musicfemale=$music[0][1]+$music[1][1]+$music[2][1]+$music[3][1];
	 echo "<tr ";
	 if($line%2==0) echo "bgcolor='#f0f0f0'";
	 echo "align=center><td align=left><b>MUSIC (IM & VM)</b></td>";
	 echo "<td>".$music[0][0]."</td><td>".$music[1][0]."</td><td>".$music[2][0]."</td><td>".$music[3][0]."</td><td bgcolor='yellow'>".$musicmale."</td>";
	 echo "<td>&nbsp;</td>";
	 echo "<td>".$music[0][1]."</td><td>".$music[1][1]."</td><td>".$music[2][1]."</td><td>".$music[3][1]."</td><td bgcolor='yellow'>".$musicfemale."</td></tr>";
	 $line++;
      }
   }
?>
</table>
</td>
</tr>
</table>

<?php
}
echo "<br><a href=\"welcome.php?session=$session\">Home</a>";
echo $end_html;
?>

