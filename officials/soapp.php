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

//GET DATES
$sodates=array(); $solabels=array();
$sql2="SELECT * FROM sotourndates WHERE offdate='x' AND (label LIKE '%Substate%' OR label NOT LIKE '%State%') ORDER BY tourndate,id";
$result2=mysql_query($sql2);
$i=1;
while($row2=mysql_fetch_array($result2))
{
   $index="date".$i;
   $index2=$i-1;
   $date=explode("-",$row2[tourndate]);
   $sodates[$index2]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
   $solabels[$index2]=$row2[label];
   $sql="SHOW FULL COLUMNS FROM soapply WHERE Field='$index'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql="ALTER TABLE soapply ADD `$index` VARCHAR(10) NOT NULL";
      $result=mysql_query($sql);
   }
   $i++;
}
$sql2="SELECT * FROM sotourndates WHERE offdate='x' AND label NOT LIKE '%Substate%' AND label LIKE '%State%' ORDER BY tourndate,id";
$result2=mysql_query($sql2);
$stateix=$i-1;
while($row2=mysql_fetch_array($result2))
{
   $index="date".$i;
   $index2=$i-1;
   $date=explode("-",$row2[tourndate]);
   $sodates[$index2]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
   $solabels[$index2]=$row2[label];
   $sql="SHOW FULL COLUMNS FROM soapply WHERE Field='$index'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql="ALTER TABLE soapply ADD `$index` VARCHAR(10) NOT NULL";
      $result=mysql_query($sql);
   }
   $i++;
}

if($submit)
{
   $partner1=addslashes($partner1);
   $city1=addslashes($city1);
   $partner2=addslashes($partner2);
   $city2=addslashes($city2);
   $conflict=addslashes($conflict);
   $date=time();

   $sql2="SELECT id FROM soapply WHERE offid='$offid'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $sql="INSERT INTO soapply (offid,";
      for($i=1;$i<=count($sodates);$i++)
      {
         $var="date".$i;
	 $sql.="$var, ";
      }
      $sql.="partner1,city1,partner2,city2,conflict,appdate) VALUES ('$offid',";
      for($i=1;$i<=count($sodates);$i++)      
      {
         $var="date".$i;
         $sql.="'".$$var."', ";      
      }
      $sql.="'$partner1','$city1','$partner2','$city2','$conflict','$date')";
   }
   else
   {
      $sql="UPDATE soapply SET ";
      for($i=1;$i<=count($sodates);$i++)      
      {
         $var="date".$i;
         $sql.="$var='".$$var."', ";      
      }
      $sql.="partner1='$partner1',city1='$city1',partner2='$partner2',city2='$city2',conflict='$conflict',appdate='$date' WHERE offid='$offid'";
   }
   $result=mysql_query($sql);
   if($submit=="Save & Close")
   {
?>
<script language="javascript">
window.close();
window.opener.location="https://secure.nsaahome.org/nsaaforms/officials/apptooff.php?session=<?php echo $session; ?>&sport=so&sort=<?php echo $sort; ?>&searchquery=<?php echo $searchquery; ?>";
</script>
<?php
   }
}

//check if already submitted
$sql="SELECT * FROM soapply WHERE offid='$offid'";
$result=mysql_query($sql);
$duedate=GetDueDate("so","app");
$date=split("-",$duedate);
$duetime=mktime(0,0,0,$date[1],$date[2],$date[0]);
$year=date("Y");
$june1="$year-06-01";
$june1time=mktime(0,0,0,6,1,$year);
if(PastDue(GetDueDate("so","app"),1) && $level!=1)// && $offid!=3427)
{
   $row=mysql_fetch_array($result);
   echo $init_html;
   echo GetHeader($session);
   echo "<br>";
   $sql2="SELECT email FROM app_duedates WHERE sport='so'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<i>This form is currently unavailable.</i><br><br>  ";
   //echo "You must e-mail <a class=small href=\"mailto:$row2[0]\">$row2[0]</a> with any changes.</i><br><br>";
   if(mysql_num_rows($result)==0 && !(PastDue($june1,0) && $june1time>$duetime))
   {
      echo "[You did not submit an Application to Officiate $curryear Soccer Tournaments.]<br><br>";
   }
   else if(!(PastDue($june1,0) && $june1time>$duetime))
   {
   $appdate=date("F d, Y",$row[appdate]);
   $due=split("-",GetDueDate("so","app"));
   echo "<table><caption><b>Application to Officiate $curryear District and State Soccer Tournament:<br><br>".GetOffName($offid)."</b><br>(Due Date: $due[1]/$due[2]/$due[0])<hr></caption>";
   echo "<tr valign=top align=left><th align=left class=smaller>Available Dates:</th><td>";
   for($i=0;$i<count($sodates);$i++)
   {
      $index2=$i+1;
      $index="date".$index2;
      if($row[$index]=='x') echo "<p style='margin:1px;padding-left:20px'>$sodates[$i] ($solabels[$j])</p>";
   }
   echo "</td></tr>";
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
   for($i=0;$i<count($sodates);$i++)
   {
      $index2=$i+1;
      $index="date".$index2;
      $$index=$row[$index];
   }
   $partner1=$row[partner1];
   $city1=$row[city1];
   $partner2=$row[partner2];
   $city2=$row[city2];
   $conflict=$row[conflict];
}

echo $init_html;
if($level!=1) echo GetHeader($session);
else echo "<table width=\"100%\"><tr align=center><td>";
echo "<br>";
echo "<form method=post action=\"soapp.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=givenoffid value=\"$givenoffid\">";
echo "<input type=hidden name=sort value=$sort>";
echo "<input type=hidden name=searchquery value=\"$searchquery\">";
$duedate=GetDueDate("so","app");
$date=split("-",$duedate);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
if($submit && $level!=1 && $level!=4)
{
   echo "<font style=\"color:blue\"><b>Your application has been saved.  ";
   echo "You may make updates to your application until the due date listed below.</b></font><br>";
   echo "Don't forget to complete your varsity <a class=small href=\"schedule.php?session=$session&sport=so\">schedule</a> as well.<br><br>";
}
else if($submit)
   echo "<font style=\"color:blue\"><b>The application has been saved.";
else if($level!=1 && $level!=4)
   echo "<font style=\"color:blue\"><b>The following application to officiate is currently posted to the NSAA 
   by you.  You may make updates to this application until the due date listed below.</b></font><br><br>";

echo "<h3>Application to Officiate $curryear District and State Soccer Tournament</h3><p><b>Due $duedate2</b></p>";
echo "<table width=\"700px\"><tr align=center><td>";
echo "<p>I am applying to officiate the following tournament(s): (Please check available dates/times)</p>";
echo "<table cellspacing=0 cellpadding=5>";
//NON-STATE FIRST, IN LEFT <td>
echo "<tr valign=top align=left><td width='50%'>";
$curdate="";
for($i=0;$i<$stateix;$i++)
{
   $index=$i+1;
   $var="date".$index;
   
   if($curdate!=$sodates[$i])	//NEW DATE
   {
      if($curdate!='') echo "</p>";
      echo "<p>";
      $curdate=$sodates[$i];
   }
   echo "<input type=checkbox name=\"$var\" value='x'";
   if($$var=='x') echo " checked";
   echo "> <b>$sodates[$i]</b> ($solabels[$i])<br>";
}
echo "</p></td>";
//NOW STATE:
echo "<td width='50%'>";
$curdate="";
for($i=$stateix;$i<count($sodates);$i++)
{
   $index=$i+1;
   $var="date".$index;
  
   if($curdate!=$sodates[$i])   //NEW DATE
   {
      if($curdate!='') echo "</p>";
      echo "<p>";
      $curdate=$sodates[$i];
   }
   echo "<input type=checkbox name=\"$var\" value='x'";
   if($$var=='x') echo " checked";
   echo "> <b>$sodates[$i]</b> ($solabels[$i])<br>";
}
echo "</p></td></tr></table>";
echo "<p style=\"text-align:left;\">If selected to officiate, <b><u>I would prefer to work with the following officials</b></u>:";
echo "<table><tr align=left><td><b>Name:</b>";
echo "&nbsp;<input type=text name=partner1 value=\"$partner1\" size=20></td>";
echo "<td><b>City:</b>";
echo "&nbsp;<input type=text name=city1 value=\"$city1\" size=20></td></tr>";
echo "<tr align=left><td><b>Name:</b>";
echo "&nbsp;<input type=text name=partner2 value=\"$partner2\" size=20></td>";
echo "<td><b>City:</b>";
echo "&nbsp;<input type=text name=city2 value=\"$city2\" size=20></td></tr>";
echo "</table></p>";
echo "<p style=\"text-align:left;\"><br>Schools with which I have a <u><b>conflict of interest</b></u>:<br><br>";
echo "<textarea rows=5 cols=60 name=conflict>$conflict</textarea></p>";
echo "<p style=\"text-align:left;\"><i>Don't forget to complete your <b>varsity soccer schedule</b> as well.  You can find the link to this form on your home page.</i></p>";
echo "<input type=submit name=submit value=\"Save & Submit\">";
if($givenoffid && $level==1)
{
   echo "&nbsp;<input type=submit name=submit value=\"Save & Close\">";
}
echo "</form>";
echo "<br><br><a class=small href=\"welcome.php?session=$session\">Home</a>";
echo "</td></tr></table>";
echo $end_html;
?>
