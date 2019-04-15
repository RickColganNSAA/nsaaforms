<?php
require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

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
$curryear=GetFallYear('bb');
$curryear++;

//GET DATES
$bbdates=array();
$sql2="SELECT DISTINCT tourndate FROM bbtourndates WHERE offdate='x' AND (label LIKE '%Substate%' OR label NOT LIKE '%State%') ORDER BY tourndate";
$result2=mysql_query($sql2);
$i=1;
while($row2=mysql_fetch_array($result2))
{
   $index="date".$i;
   $index2=$i-1;
   $date=explode("-",$row2[tourndate]);
   $bbdates[$index2]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
   $sql="SHOW FULL COLUMNS FROM bbapply WHERE Field='$index'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql="ALTER TABLE bbapply ADD `$index` VARCHAR(10) NOT NULL";
      $result=mysql_query($sql);
   }
   $i++;
}
$sql2="SELECT DISTINCT tourndate,girls,boys FROM bbtourndates WHERE offdate='x' AND label NOT LIKE '%Substate%' AND label LIKE '%State%' ORDER BY tourndate,girls DESC";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   $index="date".$i;
   $index2=$i-1;
   $date=explode("-",$row2[tourndate]);
   $bbdates[$index2]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
   $sql="SHOW FULL COLUMNS FROM bbapply WHERE Field='$index'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql="ALTER TABLE bbapply ADD `$index` VARCHAR(10) NOT NULL";
      $result=mysql_query($sql);
   }
   $i++;
}

if($submit)
{
   $conflict=addslashes($conflict);
   $partner1=addslashes($partner1);
   $city1=addslashes($city1);
   $partner2=addslashes($partner2);
   $city2=addslashes($city2);
   $statepartner1=addslashes($statepartner1);
   $statecity1=addslashes($statecity1);
   $statepartner2=addslashes($statepartner2);
   $statecity2=addslashes($statecity2);
   $date=time();

   $sql2="SELECT * FROM bbapply WHERE offid='$offid'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $sql="INSERT INTO bbapply (offid,";
      for($i=0;$i<count($bbdates);$i++)
      {
         $i2=$i+1; $field="date".$i2;
	 $sql.=$field.",";
	
      }
      $sql.="partner1,city1,partner2,city2,statepartner1,statecity1,statepartner2,statecity2,conflict,appdate) VALUES ('$offid',";
      for($i=0;$i<count($bbdates);$i++)
      {
         $i2=$i+1; $field="date".$i2;
	 $sql.="'".$$field."',";
      }
      $sql.="'$partner1','$city1','$partner2','$city2','$statepartner1','$statecity1','$statepartner2','$statecity2','$conflict','$date')";
   }
   else
   {
      $sql="UPDATE bbapply SET ";
      for($i=0;$i<count($bbdates);$i++)
      {
         $i2=$i+1; $field="date".$i2;
         $sql.="$field='".$$field."',";
      }
      $sql.="partner1='$partner1', city1='$city1', partner2='$partner2', city2='$city2', statepartner1='$statepartner1', statecity1='$statecity1', statepartner2='$statepartner2', statecity2='$statecity2', conflict='$conflict', appdate='$date' WHERE offid='$offid'";
   }
   $result=mysql_query($sql); echo mysql_error();
   if($submit=="Save & Close")
   {
?>
<script language="javascript">
window.close();
window.opener.location="https://secure.nsaahome.org/nsaaforms/officials/apptooff.php?session=<?php echo $session; ?>&sport=bb&sort=<?php echo $sort; ?>&searchquery=<?php echo $searchquery; ?>";
</script>
<?php
   }
}

//check if already submitted
$sql="SELECT * FROM bbapply WHERE offid='$offid'";
$result=mysql_query($sql);
$duedate=GetDueDate("bb","app");
$date=split("-",$duedate);
$duetime=mktime(0,0,0,$date[1],$date[2],$date[0]);
$year=date("Y");
$june1="$year-06-01";
$june1time=mktime(0,0,0,6,1,$year);
if(PastDue(GetDueDate("bb","app"),1) && $level!=1 && $offid!=3427)
{
   $row=mysql_fetch_array($result);
   echo $init_html;
   echo GetHeader($session);
   echo "<br>";
   $sql2="SELECT email FROM app_duedates WHERE sport='bb'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<i>This form is currently unavailable.</i><br><br>  ";
   //echo "You must e-mail <a class=small href=\"mailto:$row2[0]\">$row2[0]</a> with any changes.</i><br><br>";
   if(mysql_num_rows($result)==0 && !(PastDue($june1,0) && $june1time>$duetime))
   {
      echo "[You did not submit an Application to Officiate $curryear Basketball Tournaments.]<br><br>";
   }
   elseif(!(PastDue($june1,0) && $june1time>$duetime))
   {
   $appdate=date("F d, Y",$row[appdate]);
   echo "<table width=500><caption><b>Application to Officiate $curryear Subdistrict, District and State Basketball Tournaments:<br>".GetOffName($offid)."<br></b>(This form's due date is ".GetDueDate("bb","app").")<hr></caption>";
   echo "<tr valign=top align=left><th align=left class=smaller>Available Dates:</th>";
   echo "<td>";
   $sql2="SELECT DISTINCT tourndate FROM bbtourndates WHERE offdate='x' AND label NOT LIKE '%State%' ORDER BY tourndate";
   $result2=mysql_query($sql2);
   $i=1;
   while($row2=mysql_fetch_array($result2))
   {
      $index="date".$i;
      $index2=$i-1;
      $date=explode("-",$row2[tourndate]);
      $curdate=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      if($row[$index]=='x') echo "$curdate&nbsp;&nbsp;";
      $i++;
   }
   $sql2="SELECT DISTINCT tourndate,girls,boys FROM bbtourndates WHERE offdate='x' AND label LIKE '%State%' ORDER BY tourndate,girls DESC";
   $result2=mysql_query($sql2);
   $curgender="";
   while($row2=mysql_fetch_array($result2))
   {
      if($row2[girls]=='x' && $curgender!='g')
      {
         echo "<br><b>Girls State:&nbsp;</b>"; $curgender='g';
      }
      else if($row2[boys]=='x' && $curgender!='b')
      {
         echo "<br><b>Boys State:&nbsp;</b>"; $curgender='b';
      }
      $index="date".$i;
      $index2=$i-1;
      $date=explode("-",$row2[tourndate]);
      $curdate=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      if($row[$index]=='x') echo "$curdate&nbsp;&nbsp;";
      $i++;
   }
   echo "</td></tr>";
	/*
   echo "<tr align=left><th align=left colspan=2 class=smaller>Preferred Partner(s) for DISTRICTS/SUBDISTRICTS:</th></tr>";
   echo "<tr align=left><td><b>Partner's Name:</b> $row[partner1]</td>";
   echo "<td><b>City:</b> $row[city1]</td></tr>";
   echo "<tr align=left><td><b>Partner's Name:</b> $row[partner2]</td>";
   echo "<td><b>City:</b> $row[city2]</td></tr>";
   echo "<tr align=left><th align=left colspan=2 class=smaller>Preferred Partner(s) for STATE:</th></tr>";
   echo "<tr align=left><td><b>Partner's Name:</b> $row[statepartner1]</td>";
   echo "<td><b>City:</b> $row[statecity1]</td></tr>";
   echo "<tr align=left><td><b>Partner's Name:</b> $row[statepartner2]</td>";
   echo "<td><b>City:</b> $row[statecity2]</td></tr>";
	*/
   echo "<tr align=left><th align=left class=smaller>Schools with which I have a conflict of interest:</th>";
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
   for($i=1;$i<=count($bbdates);$i++)
   {
      $index="date".$i;
      $$index=$row[$index];
   }
   $partner1=$row[partner1];
   $partner2=$row[partner2];
   $city1=$row[city1];
   $city2=$row[city2];
   $statepartner1=$row[statepartner1];
   $statepartner2=$row[statepartner2];
   $statecity1=$row[statecity1];
   $statecity2=$row[statecity2];
   $conflict=$row[conflict];
   $submitted=1;
}

echo $init_html;
if($level!=1) echo GetHeader($session);
else echo "<table width=100%><tr align=center><td>";
echo "<br>";
echo "<form method=post action=\"bbapp.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=givenoffid value=\"$givenoffid\">";
echo "<input type=hidden name=sort value=$sort>";
echo "<input type=hidden name=searchquery value=\"$searchquery\">";
$duedate=GetDueDate("bb","app");
$date=split("-",$duedate);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
if($submit && $level!=1 && $level!=4)
{
   echo "<font style=\"color:blue\"><b>Your application has been saved.  ";
   echo "You may make updates to your application until the due date listed below.</b></font><br><br>";
}
else if($submit)
{
   echo "<font style=\"color:blue\"><b>The application has been saved.";
}
else if($level!=1 && $level!=4)
   echo "<font style=\"color:blue\"><b>The following application to officiate is currently posted to the NSAA 
   by you.  You may make updates to this application until the due date listed below.</b></font><br><br>";

echo "<table cellspacing=3 cellpadding=3><caption><b>Application to Officiate $curryear Subdistrict, District and State Basketball Tournament</b><br> Due $duedate2<hr></caption>";
echo "<tr align=left><td>";
echo "I am applying to officiate on the following dates: (Please check available dates)</td></tr>";
echo "<tr align=left><td><table cellspacing=2 cellpadding=2>";
//DISTRICTS
echo "<tr align=left><td colspan=5><b>Districts:</b></td></tr>";
$sql="SELECT DISTINCT tourndate FROM bbtourndates WHERE offdate='x' AND label NOT LIKE '%State%' ORDER BY tourndate";
$result=mysql_query($sql);
$i=1;
while($row=mysql_fetch_array($result))
{
   $index="date".$i;
   $index2=$i-1;
   $date=explode("-",$row[tourndate]);
   $curdate=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
   if($index2%5==0) echo "<tr align=left>";
   echo "<td><input type=checkbox name=\"$index\" value='x'";
   if($$index=='x') echo " checked";
   echo ">&nbsp;$curdate</td>";
   if(($index2+1)%5==0) echo "</tr>";
   $i++;
}
//STATE
echo "<tr align=left><th class=smaller align=left colspan=5>$curryear State Tournament:</th></tr>";
$sql="SELECT DISTINCT tourndate,girls,boys FROM bbtourndates WHERE offdate='x' AND label LIKE '%State%' ORDER BY tourndate,girls DESC";
$result=mysql_query($sql);
$curgender="";
while($row=mysql_fetch_array($result))
{
   $index="date".$i;
   $index2=$i-1;
   if($row[girls]=='x' && $curgender!='g')
   {
      echo "<tr align=left><td><b>Girls:</td>";
      $curgender='g';
   }
   else if($row[boys]=='x' && $curgender!='b')
   {
      echo "<td>&nbsp;</td></tr><tr align=left><td><b>Boys:</td>";
      $curgender='b';
   }
   $date=explode("-",$row[tourndate]);
   $curdate=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
   echo "<td><input type=checkbox name=\"$index\" value='x'";
   if($$index=='x') echo " checked";
   echo ">&nbsp;$curdate</td>";
   $i++;
}
echo "<td>&nbsp;</td></tr></table></td></tr>";
/*
echo "<tr align=left><th align=left class=smaller>If selected to officiate DISTRICTS/SUBDISTRICTS, I would prefer the following partner(s):</th></tr>";
echo "<tr align=center><td><table>";
echo "<tr align=left><th align=left class=smaller>Partner's Name:&nbsp;<input type=text size=20 class=tiny name=\"partner1\" value=\"$partner1\"></th>";
echo "<th align=left class=smaller>City:&nbsp;<input type=text size=20 class=tiny name=city1 value=\"$city1\"></td></tr>";
echo "<tr align=left><th align=left class=smaller>Partner's Name:&nbsp;<input type=text size=20 class=tiny name=\"partner2\" value=\"$partner2\"></th>";
echo "<th align=left class=smaller>City:&nbsp;<input type=text size=20 class=tiny name=city2 value=\"$city2\"></td></tr>";
echo "</table></td></tr>";
echo "<tr align=left><th align=left class=smaller>If selected to officiate STATE, I would prefer the following partner(s):</th></tr>";
echo "<tr align=center><td><table>";
echo "<tr align=left><th align=left class=smaller>Partner's Name:&nbsp;<input type=text size=20 class=tiny name=\"statepartner1\" value=\"$statepartner1\"></th>";
echo "<td><b>City:&nbsp;<input type=text size=20 class=tiny name=statecity1 value=\"$statecity1\"></td></tr>";
echo "<tr align=left><td><b>Partner's Name:&nbsp;<input type=text size=20 class=tiny name=statepartner2 value=\"$statepartner2\"></td>";
echo "<td><b>City:&nbsp;<input type=text size=20 class=tiny name=statecity2 value=\"$statecity2\"></td></tr>";
echo "</table></td></tr>";
*/
echo "<tr align=left><td>Schools with which I have a conflict of interest:<br>";
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
