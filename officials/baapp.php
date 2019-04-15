<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$level=GetLevel($session);
if(!$givenoffid) $offid=GetOffID($session);
else 
{
   $offid=$givenoffid;
   $header="no";
}
$curryear=date("Y",time());
if($level==4) $level=1;

//GET DATES
$sql="SELECT * FROM batourndates WHERE offdate='x' ORDER BY tourndate,label,id";
$result=mysql_query($sql);
$badates=array(); $i=0;
$stateix=0; 
while($row=mysql_fetch_array($result))
{
   $date=explode("-",$row[tourndate]);
   $badates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
   $i2=$i+1; $field="date".$i2;
   $sql2="SHOW FULL COLUMNS FROM baapply WHERE Field='$field'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $sql2="ALTER TABLE baapply ADD `$field` VARCHAR(10) NOT NULL";
      $result2=mysql_query($sql2);
   }
   $sql2="SELECT label FROM batourndates WHERE tourndate='$row[tourndate]' AND label='%State%'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0 && $stateix==0)
      $stateix=$i;
   $i++;
}

if($submit)
{
   $conflict=addslashes($conflict);
   $date=time();

   $sql2="SELECT id FROM baapply WHERE offid='$offid'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $sql="INSERT INTO baapply (offid,";
      for($i=0;$i<count($badates);$i++)
      {
         $i2=$i+1; $field="date".$i2;
	 $sql.=$field.",";
      }
      $sql.="early,conflict,appdate) VALUES ('$offid',";
      for($i=0;$i<count($badates);$i++)
      {
         $i2=$i+1; $field="date".$i2;
	 $sql.="'".$$field."',";
      }
      $sql.="'$early','$conflict','$date')";
   }
   else
   {
      $sql="UPDATE baapply SET ";
      for($i=0;$i<count($badates);$i++)
      {
         $i2=$i+1; $field="date".$i2;
         $sql.="$field='".$$field."',";
      }
      $sql.="early='$early',conflict='$conflict',appdate='$date' WHERE offid='$offid'";
   }
   $result=mysql_query($sql);
   if($submit=="Save & Close")
   {
?>
<script language="javascript">
window.close();
window.opener.location="https://secure.nsaahome.org/nsaaforms/officials/apptooff.php?session=<?php echo $session; ?>&sport=ba&sort=<?php echo $sort; ?>&searchquery=<?php echo $searchquery; ?>";
</script>
<?php
   }
}

//check if already submitted
$sql="SELECT * FROM baapply WHERE offid='$offid'";
$result=mysql_query($sql);
$duedate=GetDueDate("ba","app");
$date=split("-",$duedate);
$duetime=mktime(0,0,0,$date[1],$date[2],$date[0]);
$year=date("Y");
$june1="$year-06-01";
$june1time=mktime(0,0,0,6,1,$year);
if(PastDue(GetDueDate("ba","app"),0) && $level!=1)
{
   $row=mysql_fetch_array($result);
   echo $init_html;
   echo GetHeader($session);
   echo "<br>";
   $sql2="SELECT email FROM app_duedates WHERE sport='ba'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<i>This form is currently unavailable.</i><br><br>";
   //echo "You must e-mail <a class=small href=\"mailto:$row2[0]\">$row2[0]</a> with any changes.</i><br><br>";
   if(mysql_num_rows($result)==0 && !(PastDue($june1,0) && $june1time>$duetime))
   {
      echo "[You did not submit an Application to Umpire $curryear Baseball Tournaments.]<br><br>";
   }
   else if(!(PastDue($june1,0) && $june1time>$duetime))
   {
   $appdate=date("F d, Y",$row[appdate]);
   echo "<table width=500><caption><b>Application to Umpire $curryear District and State Baseball Tournament:<br>".GetOffName($offid)."<br></b>(This form's due date is ".GetDueDate("ba","app").")<hr></caption>";
   echo "<tr valign=top align=left><th align=left class=smaller>Available Dates:</th>";
   echo "<td>Districts:&nbsp;";
   for($i=0;$i<$stateix;$i++)
   {
      $index2=$i+1;
      $index="date".$index2;
      if($row[$index]=='x') echo $badates[$i]."&nbsp;&nbsp;";
   }
   echo "<br>State:&nbsp;";
   for($i=$stateix;$i<count($badates);$i++)
   {
      $index2=$i+1;
      $index="date".$index2;
      if($row[$index]=='x') echo $badates[$i]."&nbsp;&nbsp;";
   }
   echo "</td></tr>";
   echo "<tr align=left><th align=left class=smaller>Available for games beginning at 11:00AM:</th>";
   if($row[early]=='y') echo "<td>YES</td>";
   else echo "<td>NO</td>";
   echo "</tr>";
   echo "<tr align=left><th align=left class=smaller>Conflict of Interest:</th>";
   echo "<td>$row[conflict]</td></tr>";
   echo "</table><br><br>";
   }
   echo "<a href=\"welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}
else
{
   $submitted=1;
   $row=mysql_fetch_array($result);
   for($i=0;$i<count($badates);$i++)
   {
      $i2=$i+1; $field="date".$i2;
      $$field=$row[$field];
   }
   $early=$row[early];
   $conflict=$row[conflict];
}

echo $init_html;
if($level!=1)
   echo GetHeader($session);
else echo "<table width=100%><tr align=center><td>";
echo "<br>";
echo "<form method=post action=\"baapp.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=givenoffid value=\"$givenoffid\">";
echo "<input type=hidden name=sort value=$sort>";
echo "<input type=hidden name=searchquery value=\"$searchquery\">";
$duedate=GetDueDate("ba","app");
$date=split("-",$duedate);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
if($submit && $level!=1)
{
   echo "<font style=\"color:blue\"><b>Your application has been saved.  ";
   echo "You may make updates to your application until the due date listed below.</b></font><br>";
   echo "Don't forget to complete your <a class=small href=\"schedule.php?session=$session&sport=ba\">schedule</a> as well.<br><br>";
}
else if($submit)
   echo "<font style=\"color:blue\"><b>The application has been saved.";
else if($level!=1)
   echo "<font style=\"color:blue\"><b>The following application to officiate is currently posted to the NSAA by you.  You may make updates to this application until the due date listed below.</b></font><br><br>";

echo "<table width=500 cellspacing=3 cellpadding=3><caption align=center><b>Application to Umpire $curryear District and State Baseball Tournament</b><br> Due $duedate2<br>";
echo "<table><tr align=left><td>";
echo "<b><u>Instructions:</b></u><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "Please check your available dates for the tournament(s) for which you wish to be considered.  Also check whether you are available for games beginning at 11:00am.  Then list any schools with which you have a conflict of interest.<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "If possible, please accurately complete your $curryear high school umpiring schedule as well.  This form can be found on your home page.  Thank you!";
echo "</td></tr></table><hr></caption>";
echo "<tr align=left><td>";
echo "I am applying to umpire the following tournament:<br>(Please check available dates for the Tournament(s) for which you wish to be considered)</td></tr>";
echo "<tr align=left><td><table>";
echo "<tr align=left><td><b>Districts:</b></td><td>";
for($i=0;$i<$stateix;$i++)
{
   $index2=$i+1;
   $index="date".$index2;
   echo "<input type=checkbox name=\"$index\" value='x'";
   if($$index=='x') echo " checked";
   echo ">$badates[$i]&nbsp;&nbsp;";
}
echo "</td></tr><tr align=left><td><b>State:</b></td><td>";
for($i=$stateix;$i<count($badates);$i++)
{
   $index2=$i+1;
   $index="date".$index2;
   echo "<input type=checkbox name=\"$index\" value='x'";
   if($$index=='x') echo " checked";
   echo ">$badates[$i]&nbsp;&nbsp;";
}
echo "</td></tr></table></td></tr>";
echo "<tr align=left><th class=smaller align=left>I am available for games beginning at 11:00 AM:&nbsp;&nbsp;";
echo "<input type=radio name=early value='y'";
if($early=='y') echo " checked";
echo ">Yes&nbsp;&nbsp;";
echo "<input type=radio name=early value='n'";
if($early=='n') echo " checked";
echo ">No</th></tr>";
echo "<tr align=left><td><b>Schools with which I have a conflict of interest:<br></b>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<i>Please name any high school where you may have a conflict of interest, or any high school which you would prefer to avoid.  Examples: A high school where you have taught, attended school, relative teachers/coaches, students in schools, etc.</i><br>";
echo "<textarea rows=5 cols=60 name=conflict>$conflict</textarea></td></tr>";
echo "</table><br>";
echo "<input type=submit name=submit value=\"Save & Submit\">";
if($givenoffid && $level==1)
{
   echo "&nbsp;<input type=submit name=submit value=\"Save & Close\">";
}
echo "</form>";
echo "<a class=small href=\"welcome.php?session=$session\">Home</a>";
echo $end_html;
?>
