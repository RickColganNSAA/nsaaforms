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
if($print==1)
   $header="no";
$curryear=date("Y",time());
$offname=GetOffName($offid);

//GET DATES
$sql="SELECT * FROM vbtourndates WHERE offdate='x' ORDER BY tourndate,label,id";
$result=mysql_query($sql);
$vbdates=array(); $i=0;
$stateix=0;
while($row=mysql_fetch_array($result))
{
   $date=explode("-",$row[tourndate]);
   $vbdates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
   $i2=$i+1; $field="date".$i2;
   $sql2="SHOW FULL COLUMNS FROM vbapply WHERE Field='$field'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $sql2="ALTER TABLE vbapply ADD `$field` VARCHAR(10) NOT NULL";
      $result2=mysql_query($sql2);
   }
   if(preg_match("/State/",$row[label]) && $stateix==0)
      $stateix=$i;
   $i++;
}

if($submit)
{
   $conflict=addslashes($conflict);
   $partner1=addslashes($partner1);
   $city1=addslashes($city1);
   $partner2=addslashes($partner2);
   $city2=addslashes($city2);
   $date=time();

   $sql2="SELECT * FROM vbapply WHERE offid='$offid'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $sql="INSERT INTO vbapply (offid,";
      for($i=1;$i<=count($vbdates);$i++)
      {
         $sql.="date".$i.", ";
      }
      $sql.="available,partner1,city1,partner2,city2,conflict,appdate) VALUES ('$offid',";
      for($i=1;$i<=count($vbdates);$i++)
      {
         $field="date".$i;
	 $sql.="'".$$field."', ";
      }
      $sql.="'$available','$partner1','$city1','$partner2','$city2','$conflict','$date')";
   }
   else
   {
      $sql="UPDATE vbapply SET ";
      for($i=1;$i<=count($vbdates);$i++)
      {
         $field="date".$i;
         $sql.="$field='".$$field."', ";
      }
      $sql.="available='$available', partner1='$partner1', city1='$city1', partner2='$partner2', city2='$city2', conflict='$conflict', appdate='$date' WHERE offid='$offid'";
   }
   $result=mysql_query($sql);
   if($submit=="Save & Close")
   {
?>
<script language="javascript">
window.close();
</script>
<?php
   }
}

//check if already submitted
$sql="SELECT * FROM vbapply WHERE offid='$offid'";
$result=mysql_query($sql);
$duedate=GetDueDate("vb","app");
$date=split("-",$duedate);
$duetime=mktime(0,0,0,$date[1],$date[2],$date[0]);
$year=date("Y");
$june1="$year-06-01";
$june1time=mktime(0,0,0,6,1,$year);
if(PastDue(GetDueDate("vb","app"),1) && $level!=1 && $offid!=3427)
{
   //if past due date, show submitted form
   $row=mysql_fetch_array($result);
   echo $init_html;
   if(!($givenoffid && ($level==1 || $level==4))) echo GetHeader($session);
   else echo "<table width=100%><tr align=center><td>";
   echo "<br>";
   $sql2="SELECT email FROM app_duedates WHERE sport='vb'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<i>This form is currently unavailable.</i><br><br>";
   //echo "You must e-mail <a class=small href=\"mailto:$row2[0]\">$row2[0]</a> with any changes.</i><br><br>";
   if(mysql_num_rows($result)==0 && !(PastDue($june1,0) && $june1time>$duetime))
   {
      echo "[You did not submit an Application to Officiate $curryear Volleyball Tournaments.]<br><br>";
   }
   else if(!(PastDue($june1,0) && $june1time>$duetime))
   {
   $appdate=date("F d, Y",$row[appdate]);
   echo "<table width=500><caption><b>Application to Officiate $curryear Subdistrict, District, Substate and State Volleyball Tournaments:<br>".GetOffName($offid)."<br></b>(This form's due date is ".GetDueDate("vb","app").")<hr></caption>";
   echo "<tr valign=top align=left><td><b>Available Dates:</b></td>";
   echo "<td>";
   for($i=1;$i<$stateix;$i++)
   {
      $index="date".$i;
      $index2=$i-1;
      if($row[$index]=='x') echo "$vbdates[$index2]&nbsp;&nbsp;";
   }
   echo "<br><b>State:&nbsp;</b>";
   for($i=$stateix;$i<=count($vbdates);$i++)
   {
      $index="date".$i;
      $index2=$i-1;
      if($row[$index]=='x') echo "$vbdates[$index2]&nbsp;&nbsp;";
   }
   echo "</td></tr>";
   echo "<tr align=left><th align=left class=smaller>Available at 4:30 PM:";
   echo "&nbsp;&nbsp;<input type=radio name=available value='y'";
   if($row[available]=='y') echo " checked";
   echo ">YES&nbsp;&nbsp;";
   echo "<input type=radio name=available value='n'";
   if($row[available]=='n') echo " checked";
   echo ">NO</th></tr>";
   echo "<tr align=left><th align=left colspan=2 class=smaller>Preferred Partner(s):</th></tr>";
   echo "<tr align=left><td><b>Partner's Name:</b> $row[partner1]</td>";
   echo "<td><b>City:</b> $row[city1]</td></tr>";
   echo "<tr align=left><td><b>Partner's Name:</b> $row[partner2]</td>";
   echo "<td><b>City:</b> $row[city2]</td></tr>";
   echo "<tr align=left><th align=left class=smaller>Conflict of interest:</th>";
   echo "<td>$row[conflict]</td></tr>";
   echo "</table><br><br>";
   }
   echo "<a href=\"welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}
else	//if not past due date, show editable form with current submitted info
{
   $row=mysql_fetch_array($result);
   for($i=1;$i<=count($vbdates);$i++)
   {
      $index="date".$i;
      $$index=$row[$index];
   }
   $available=$row[available];
   $partner1=$row[partner1];
   $partner2=$row[partner2];
   $city1=$row[city1];
   $city2=$row[city2];
   $conflict=$row[conflict];
   $submitted=1;
}

echo $init_html;
if(!($givenoffid && ($level==4 || $level==1)) && $header!='no') echo GetHeader($session);
else echo "<table width=100%><tr align=center><td>";
echo "<br>";
echo "<form method=post action=\"vbapp.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=givenoffid value=\"$givenoffid\">";
echo "<input type=hidden name=sort value=$sort>";
echo "<input type=hidden name=searchquery value=\"$searchquery\">";
$duedate=GetDueDate("vb","app");
$date=split("-",$duedate);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));

if($submit && $level!=1 && $level!=4)
{
   echo "<font style=\"color:blue\"><b>Your application has been saved.  ";
   echo "You may make updates to your application until the due date listed below.</b></font><br><br>";
}
else if($submit)
   echo "<font style=\"color:blue\"><b>The application has been saved";
else if($level!=1 && $level!=4)
   echo "<font style=\"color:blue\"><b>The following application to officiate is currently posted to the NSAA 
   by you.  You may make updates to this application until the due date listed below.</b></font><br><br>";

echo "<table cellspacing=3 cellpadding=3><caption><b>Application to Officiate $curryear Subdistrict, District, Substate and State Volleyball Tournaments:</b><br> Due $duedate2<hr></caption>";
echo "<tr align=left><td><b>Official: $offname</b></td></tr>";
echo "<tr align=left><td>";
echo "<b>I am applying to officiate on the following dates: (Please check available dates)</b></td></tr>";
echo "<tr align=left><td><table cellspacing=2 cellpadding=2>";
echo "<tr align=left>";
for($i=0;$i<$stateix;$i++)
{
   $index2=$i+1;
   $index="date".$index2;
   echo "<td><input type=checkbox name=\"$index\" value='x'";
   if($$index=='x') echo " checked";
   echo ">&nbsp;$vbdates[$i]</td>";
}
echo "</tr>";
echo "<tr align=left><th class=smaller align=left colspan=5>$curryear State Tournament:</th></tr>";
echo "<tr align=left>";
for($i=$stateix;$i<count($vbdates);$i++)
{
   $index2=$i+1;
   $index="date".$index2;
   echo "<td><input type=checkbox name=\"$index\" value='x'";
   if($$index=='x') echo " checked";
   echo ">&nbsp;$vbdates[$i] ";
   echo "</td>";
}
echo "</tr></table></td></tr>";
echo "<tr align=left><th align=left class=smaller>Available at 4:30 PM:";
echo "&nbsp;&nbsp;<input type=radio name=available value='y'";
if($available=='y') echo " checked";
echo ">YES&nbsp;&nbsp;";
echo "<input type=radio name=available value='n'";
if($available=='n') echo " checked";
echo ">NO</th></tr>";
echo "<tr align=left><th align=left class=smaller>If selected to officiate, I would prefer the following partner:</th></tr>";
echo "<tr align=center><td><table>";
echo "<tr align=left><th align=left class=smaller>Partner's Name:&nbsp;<input type=text size=20 class=tiny name=\"partner1\" value=\"$partner1\"></th>";
echo "<th align=left class=smaller>City:&nbsp;<input type=text size=20 class=tiny name=city1 value=\"$city1\"></td></tr>";
echo "<tr align=left><th align=left class=smaller>Partner's Name:&nbsp;<input type=text size=20 class=tiny name=\"partner2\" value=\"$partner2\"></th>";
echo "<th align=left class=smaller>City:&nbsp;<input type=text size=20 class=tiny name=city2 value=\"$city2\"></td></tr>";
echo "</table></td></tr>";
echo "<tr align=left><td>Schools with which I have, or my partner has, a conflict of interest:<br>";
echo "<textarea rows=5 cols=60 name=conflict>$conflict</textarea></td></tr>";
echo "</table><br>";
echo "<input type=submit name=submit value=\"Save & Submit\">";
if($givenoffid && ($level==1|| $level==4))
{
   echo "&nbsp;<input type=submit name=submit value=\"Save & Close\">";
}
echo "</form>";
echo "<A class=small href=\"welcome.php?session=$session\">Home</a>";
echo $end_html;
?>
