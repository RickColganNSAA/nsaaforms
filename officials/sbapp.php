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
if($level==4) $level=1;

if(!$givenoffid) $offid=GetOffID($session);
else 
{
   $offid=$givenoffid;
   $header="no";
}
$curryear=date("Y",time());
$offname=GetOffName($offid);

//GET DATES
$sql="SELECT * FROM sbtourndates WHERE offdate='x' ORDER BY tourndate,label,id";
$result=mysql_query($sql);
$sbdates=array(); $i=0;
$stateix=0;
while($row=mysql_fetch_array($result))
{
   $date=explode("-",$row[tourndate]);
   $sbdates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
   $i2=$i+1; $field="date".$i2;
   $sql2="SHOW FULL COLUMNS FROM sbapply WHERE Field='$field'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $sql2="ALTER TABLE sbapply ADD `$field` VARCHAR(10) NOT NULL";
      $result2=mysql_query($sql2);
   }
   if(preg_match("/State/",$row[label]) && $stateix==0)
      $stateix=$i;
   $i++;
}

if($submit)
{
   $conflict=addslashes($conflict);
   $date=time();

   $sql2="SELECT id FROM sbapply WHERE offid='$offid'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $sql="INSERT INTO sbapply (offid,";
      for($i=0;$i<count($sbdates);$i++)
      {
         $i2=$i+1; $field="date".$i2;
         $sql.="$field,";
      }
      $sql.="early,conflict,appdate) VALUES ('$offid',";
      for($i=0;$i<count($sbdates);$i++)
      {
         $i2=$i+1; $field="date".$i2;
         $sql.="'".$$field."',";
      }
      $sql.="'$early','$conflict','$date')";
   }
   else
   {
      $sql="UPDATE sbapply SET ";
      for($i=0;$i<count($sbdates);$i++)
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
window.opener.location="https://secure.nsaahome.org/nsaaforms/officials/apptooff.php?session=<?php echo $session; ?>&sport=sb&sort=<?php echo $sort; ?>&searchquery=<?php echo $searchquery; ?>";
</script>
<?php
   }
}

//check if already submitted
$sql="SELECT * FROM sbapply WHERE offid='$offid'";
$result=mysql_query($sql);
$duedate=GetDueDate("sb","app");
$date=split("-",$duedate);
$duetime=mktime(0,0,0,$date[1],$date[2],$date[0]);
$year=date("Y");
$june1="$year-06-01";
$june1time=mktime(0,0,0,6,1,$year);
if(PastDue(GetDueDate("sb","app"),1) && $level!=1 && $offid!=3427)
{
   $row=mysql_fetch_array($result);
   echo $init_html;
   echo GetHeader($session);
   echo "<br>";
   $sql2="SELECT email FROM app_duedates WHERE sport='sb'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<i>This form is currently unavailable.</i><br><br>  ";
   //echo "You must e-mail <a class=small href=\"mailto:$row2[0]\">$row2[0]</a> with any changes.</i><br><br>";
   if(mysql_num_rows($result)==0 && !(PastDue($june1,0) && $june1time>$duetime))
   {
      echo "[You did not submit an Application to Umpire $curryear Softball Tournaments.]<br><br>";
   }
   else if(!(PastDue($june1,0) && $june1time>$duetime))
   {
   $appdate=date("F d, Y",$row[appdate]);
   echo "<table width=500><caption><b>Application to Umpire $curryear District and State Softball Tournament:<br></b>(This form's due date is ".GetDueDate("sb","app").")<hr></caption>";
   echo "<tr valign=top align=left><th align=left class=smaller>Available Dates:</th>";
   echo "<td><p><b>Districts:</b>&nbsp;&nbsp;";
   for($i=0;$i<$stateix;$i++)
   {
      $ix=$i+1; $var="date".$ix;
      echo "<input type=checkbox name=\"$var\" value='x' disabled";
      if($row[$var]=='x') echo " checked";
      echo ">$sbdates[$i]&nbsp;&nbsp;";
   }
   echo "</p><p><b>State:</b>&nbsp;&nbsp;";
   for($i=$stateix;$i<count($sbdates);$i++)
   {
      $ix=$i+1; $var="date".$ix;
      echo "<input type=checkbox name=\"$var\" value='x' disabled";
      if($row[$var]=='x') echo " checked";
      echo ">$sbdates[$i]&nbsp;&nbsp;";
   }
   echo "</p></td></tr>";


   echo "<tr align=left><th align=left class=smaller>Available for games beginning at 8:00AM:</th>";
   if($row[early]=='y') echo "<td>YES</td>";
   else echo "<td>NO</td>";
   echo "</tr>";
   echo "<tr align=left><th align=left class=smaller>Conflict of interest:</th>";
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
   for($i=0;$i<count($sbdates);$i++)
   {
      $i2=$i+1; $field="date".$i2;
      $$field=$row[$field];
   }
   $early=$row[early];
   $conflict=$row[conflict];
}

echo $init_html;
if($level!=1 && $level!=4)
   echo GetHeader($session);
else echo "<table width=100%><tr align=center><td>";
echo "<br>";
echo "<form method=post action=\"sbapp.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=givenoffid value=\"$givenoffid\">";
echo "<input type=hidden name=sort value=$sort>";
echo "<input type=hidden name=searchquery value=\"$searchquery\">";
$duedate=GetDueDate("sb","app");
$date=split("-",$duedate);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
if($submit && $level!=1 && $level!=4)
{
   echo "<font style=\"color:blue\"><b>Your application has been saved.  ";
   echo "You may make updates to your application until the due date listed below.</b></font><br><br>";
}
else if($submit)
   echo "<font style=\"color:blue\"><b>The application has been saved.";
else if($level!=1 && $level!=4)
   echo "<font style=\"color:blue\"><b>The following application to officiate is currently posted to the NSAA 
   by you.  You may make updates to this application until the due date listed below.</b></font><br><br>";

echo "<table cellspacing=3 cellpadding=3><caption><b>Application to Umpire $curryear District and State Softball Tournament</b><br> Due $duedate2<hr></caption>";
echo "<tr align=left><td><b>Umpire: $offname</b></td></tr>";
echo "<tr align=left><td>";
echo "I am applying to umpire the following tournament: (Please check available dates)</td></tr>";
echo "<tr align=left><td><table>";
echo "<tr align=left><td><b>Districts:</b></td>";
echo "<td>";
for($i=0;$i<$stateix;$i++)
{
   $ix=$i+1; $var="date".$ix;
   echo "<input type=checkbox name=\"$var\" value='x'";
   if($$var=='x') echo " checked";
   echo ">$sbdates[$i]&nbsp;&nbsp;";
}
echo "</td></tr>";
echo "<tr align=left><td><b>State:</b></td>";
echo "<td>";
for($i=$stateix;$i<count($sbdates);$i++)
{
   $ix=$i+1; $var="date".$ix;
   echo "<input type=checkbox name=\"$var\" value='x'";
   if($$var=='x') echo " checked";
   echo ">$sbdates[$i]&nbsp;&nbsp;";
}
echo "</td></tr>";
echo "</table></td></tr>";
echo "<tr align=left><td>I am available for games beginning at 8:00 AM:&nbsp;&nbsp;";
echo "<input type=radio name=early value='y'";
if($early=='y') echo " checked";
echo ">Yes&nbsp;&nbsp;";
echo "<input type=radio name=early value='n'";
if($early=='n') echo " checked";
echo ">No</td></tr>";
echo "<tr align=left><td>Schools with which I have, or my partner has, a conflict of interest:<br>";
echo "<textarea rows=5 cols=60 name=conflict>$conflict</textarea></td></tr>";
echo "</table><br>";
echo "<input type=submit name=submit value=\"Save & Submit\">";
if($givenoffid && ($level==1 || $level==4))
{
   echo "&nbsp;<input type=submit name=submit value=\"Save & Close\">";
}
echo "</form>";
echo "<a class=small href=\"welcome.php?session=$session\">Home</a>";
echo $end_html;
?>
