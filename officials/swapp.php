<?php

require 'functions.php';
require 'variables.php';
$thisyear=GetSchoolYear(date("Y"),date("m"));

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$level=GetLevel($session);
if($level!=1 && !$givenoffid) $offid=GetOffID($session);
else 
{
   $offid=$givenoffid;
   if($level==1 && !$givenoffid) $offid=3427;
   $header="no";
}
$curryear=date("Y",time());

//GET DATES
$sql="SELECT * FROM swtourndates WHERE offdate='x' ORDER BY tourndate,label,id";
$result=mysql_query($sql);
$swdates=array(); $i=0;
$stateix=0;
while($row=mysql_fetch_array($result))
{
   $date=explode("-",$row[tourndate]);
   $swdates[$i]=date("l, M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
   if(trim($row[label])!='') $swdates[$i].=" ($row[label])";
   $i2=$i+1; $field="date".$i2;
   $sql2="SHOW FULL COLUMNS FROM swapply WHERE Field='$field'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $sql2="ALTER TABLE swapply ADD `$field` VARCHAR(10) NOT NULL";
      $result2=mysql_query($sql2);
   }
   $i++;
}

if($submit)
{
   $position1=addslashes($position1);
   $position2=addslashes($position2);
   $position3=addslashes($position3);
   $affiliation=addslashes($affiliation);
   $capacity=addslashes($capacity);
   $date=time();

   $sql2="SELECT id FROM swapply WHERE offid='$offid'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $sql="INSERT INTO swapply (offid,";
      for($i=1;$i<=count($swdates);$i++)
      {
	 $var="date".$i;
	 $sql.="$var, ";
      }
      $sql.="position1,position2,position3,affiliation,meets,capacity,child,shirt,appdate) VALUES ('$offid',";
      for($i=1;$i<=count($swdates);$i++)      
      {         
	 $var="date".$i;         
	 $sql.="'".$$var."', ";      
      }
      $sql.="'$position1','$position2','$position3','$affiliation','$meets','$capacity','$child','$shirt','$date')";
   }
   else
   {
      $sql="UPDATE swapply SET ";
      for($i=1;$i<=count($swdates);$i++)      
      {         
         $var="date".$i;         
         $sql.="$var='".$$var."', ";
      }
      $sql.="position1='$position1',position2='$position2',position3='$position3',affiliation='$affiliation',meets='$meets',capacity='$capacity',child='$child',shirt='$shirt',appdate='$date' WHERE offid='$offid'";
   }
   $result=mysql_query($sql);
   if($submit=="Save & Close")
   {
?>
<script language="javascript">
window.close();
window.opener.location="https://secure.nsaahome.org/nsaaforms/officials/apptooff.php?session=<?php echo $session; ?>&sport=sw&sort=<?php echo $sort; ?>&searchquery=<?php echo $searchquery; ?>";
</script>
<?php
   }
}

//check if already submitted
$sql="SELECT * FROM swapply WHERE offid='$offid'";
$result=mysql_query($sql);
$duedate=GetDueDate("sw","app");
$date=split("-",$duedate);
$duetime=mktime(0,0,0,$date[1],$date[2],$date[0]);
$year=date("Y");
$june1="$year-06-01";
$june1time=mktime(0,0,0,6,1,$year);
if(PastDue(GetDueDate("sw","app"),1) && $level!=1 && $offid!=3427)
{
   $row=mysql_fetch_array($result);
   echo $init_html;
   echo GetHeader($session);
   echo "<br>";
   $sql2="SELECT email FROM app_duedates WHERE sport='sw'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<i>This form is currently unavailable.  <br><br></i>";
   if(mysql_num_rows($result)>0 && !(PastDue($june1,0) && $june1time>$duetime))
   {
   $appdate=date("F d, Y",$row[appdate]);
   echo "<table width=500><caption><b>Application to Officiate State Swimming/Diving:<br>".GetOffName($offid)."<br></b>(This form's due date is ".GetDueDate("sw","app").")<hr></caption>";
   echo "<tr valign=top align=left><th align=left class=smaller>Available Dates:</th>";
   echo "<td>";
   for($i=0;$i<count($swdates);$i++)
   {
      $index2=$i+1;
      $index="date".$index2;
      if($row[$index]=='x') echo "$swdates[$i]&nbsp;&nbsp;";
   }
   echo "</td></tr>";
   echo "<tr align=left valign=top><th align=left class=smaller>Positions I wish to be considered for:</th>";
   echo "<td>";
   for($i=1;$i<=3;$i++)
   {
      $index="position".$i;
      echo "$i.&nbsp;$row[$index]<br>";
   }
   echo "</td></tr>";
   echo "<tr align=left><td><b>High School Affiliation:</b></td><td>$row[affiliation]</td></tr>";
   echo "<tr align=left><td><b>Number of High School Meets Worked THIS Year:</b></td>";
   echo "<td>$row[meets]</td></tr>";
   echo "<tr align=left><td><b>In What Capacity?</b></td><td>$row[capacity]</td></tr>";
   echo "<tr align=left><td><b>Will you have a child competing in the $thisyear State Meet?</b></td>";
   echo "<td>";
   if($row[child]=='y') echo "Yes";
   else echo "No";
   echo "</td></tr>";
   echo "<tr align=left><td><b>Shirt Size:</b></td>";
   echo "<td>$row[shirt]</td></tr>";
   echo "</table><br><br>";
   }//end if submitted an app
   else if(!(PastDue($june1,0) && $june1time>$duetime))
   {
      echo "[You did not submit an Application to Officiate State Swimming/Diving.]<br><br>";
   }
   echo "<a href=\"welcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}
else
{
   $sql2="SELECT mailing FROM swoff WHERE offid='$offid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if($row2[0]<100 && $offid!='3427')
   {
      echo $init_html;
      echo GetHeader($session);
      echo "<br><br>You have not completed the registration process; therefore you may not fill out an application at this time.<br><br>Please contact the NSAA with questions about registration requirements.<br><br><a href=\"welcome.php?session=$session\">Home</a>";
      exit();
   }
   $submitted=1;
   $row=mysql_fetch_array($result);
   for($i=0;$i<count($swdates);$i++)
   {
      $index2=$i+1;
      $index="date".$index2;
      $$index=$row[$index];
   }
   $position1=$row[position1];
   $position2=$row[position2];
   $position3=$row[position3];
   $affiliation=$row[affiliation];
   $meets=$row[meets];
   $capacity=$row[capacity];
   $child=$row[child];
   $shirt=$row[shirt];
}

echo $init_html;
if($level!=1) echo GetHeader($session);
else echo "<table width=100%><tr align=center><td>";
if($submit) echo "<div class=alert>Your application to officiate has been saved.</div>";
echo "<br>";
echo "<form method=post action=\"swapp.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=givenoffid value=\"$givenoffid\">";
echo "<input type=hidden name=sort value=$sort>";
echo "<input type=hidden name=searchquery value=\"$searchquery\">";
$duedate=GetDueDate("sw","app");
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

echo "<table cellspacing=3 cellpadding=3><caption><b>Application to Officiate State Swimming/Diving</b><br> Due $duedate2<hr></caption>";
echo "<tr align=left><td>";
echo "Please check available dates:</td></tr>";
echo "<tr align=left><td><table>";
echo "<tr align=left>";
echo "<td colspan=2>";
for($i=0;$i<count($swdates);$i++)
{
   $index2=$i+1;
   $index="date".$index2;
   echo "<input type=checkbox name=\"$index\" value='x'";
   if($$index=='x') echo " checked";
   echo ">$swdates[$i]&nbsp;&nbsp;";
}
echo "</td></tr>";
echo "</table></td></tr>";
echo "<tr align=left><td colspan=2><b>Position I wish to be considered for:</b></td></tr>";
echo "<tr align=left><td colspan=2>";
for($i=1;$i<=3;$i++)
{
   $index="position".$i;
   echo "$i.&nbsp;&nbsp;<input type=text name=\"$index\" size=30 value=\"".$$index."\"><br>";
}
echo "</td></tr>";
echo "<tr align=left><td colspan=2><b>High School Affiliation:</b>&nbsp;&nbsp;";
echo "<input type=text name=affiliation size=30 value=\"$affiliation\"></td></tr>";
echo "<tr align=left><td colspan=2><b>Number of High School Meets Worked THIS Year:</b>";
echo "&nbsp;&nbsp;<input type=text name=meets size=5 value=\"$meets\"></td></tr>";
echo "<tr align=left><td colspan=2><b>In What Capacity?&nbsp;&nbsp;";
echo "<input type=text name=capacity size=40 value=\"$capacity\"></td></tr>";
echo "<tr align=left><td colspan=2><b>Will you have a child competing in the $thisyear State Meet?&nbsp;&nbsp;";
echo "<input type=radio name=child value='y'";
if($child=='y') echo " checked";
echo ">Yes&nbsp;&nbsp;";
echo "<input type=radio name=child value='n'";
if($child=='n') echo " checked";
echo ">No</td></tr>";
echo "<tr align=left><td colspan=2><b>Shirt Size:&nbsp;&nbsp;";
echo "<input type=radio name=shirt value='s'";
if($shirt=='s') echo " checked";
echo ">S&nbsp;&nbsp;";
echo "<input type=radio name=shirt value='m'";
if($shirt=='m') echo " checked";
echo ">M&nbsp;&nbsp;";
echo "<input type=radio name=shirt value='l'";
if($shirt=='l') echo " checked";
echo ">L&nbsp;&nbsp;";
echo "<input type=radio name=shirt value='xl'";
if($shirt=='xl') echo " checked";
echo ">XL&nbsp;&nbsp;";
echo "<input type=radio name=shirt value='xxl'";
if($shirt=='xxl') echo " checked";
echo ">XXL</td></tr>";

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
