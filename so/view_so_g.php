<?php
//edit_so_g.php: Girls Soccer Entry form

require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require $_SERVER['DOCUMENT_ROOT'].'/calculate/variables.php'; //Wildcard Variables

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

$header=GetHeader($session);
$level=GetLevel($session);

//verify user
if(!ValidUser($session) && !$makepdf)
{
   header("Location:../index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if((!$school_ch || $level==2 || $level==3) && $director!=1)
{
   $school=GetSchool($session);
}
else if($level==1)
{
   $school=$school_ch;
}
else if($director==1)
{
   $print=1;
   $school=$school_ch;
   $hostsch=GetSchool($session);
   $hostsch2=addslashes($hostsch);
   $sql="SELECT id FROM logins WHERE school='$hostsch2' AND level='$level'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $hostid=$row[0];
   $sql="SELECT * FROM $db_name2.sogdistricts WHERE hostid='$hostid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      echo "You are not the host of this school's district.";
      exit();
   }
}
$school2=addslashes($school);
$sid=GetSID2($school,'sog');
$sport='sog';

if($makepdf)    //GET OTHER SCHOOL TO GO ON THIS PAGE
{
   //$sql="USE nsaascores20122013";       //TESTING
   //$result=mysql_query($sql);
   $sql="SELECT programorder,class,approvedforprogram FROM ".$sport."school WHERE sid='$sid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $order=$row[programorder]; $class=$row['class'];
   $approved=$row[approvedforprogram];
   //GET OTHER SCHOOL ON SAME PAGE
   if($order%2==0)      //THIS SCHOOL IS THE SECOND ONE ON THE PAGE
      $order2=$order-1;
   else                 //ELSE IT IS THE FIRST ONE ON THE PAGE
      $order2=$order+1;
   $sql="SELECT * FROM ".$sport."school WHERE class='$class' AND programorder='$order2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sid2=$row[sid]; $approved2=$row[approvedforprogram];
   //IF $makepdf && NOT NSAA - CHECK THAT BOTH SID's HAVE BEEN APPROVED FOR PROGRAM
   if($level!=1)
   {
      if(mysql_num_rows($result)==0 && $approved>0)    //CAN'T FIND $sid2
      {
         echo $init_html;
         echo "<table style=\"width:100%;\"><tr align=center><td><div style=\"width:600px;\">";
         echo "<br><br><div class='error'>The team that will share this page with ".GetSchoolName($sid,$sport)." has not been indicated yet by the NSAA. The NSAA will need to mark a team as being in position <b><u>$order2</b></u> for Class $class in order for this page to be previewed.</div>";
         echo "<br><br><a href=\"javascript:window.close();\">Close</a></div>";
         echo $end_html;
         exit();
      }
      if(!$approved || !$approved2)
      {
         echo $init_html;
         echo "<table style=\"width:100%;\"><tr align=center><td><div style=\"width:600px;\">";
         echo "<br><br><div class='error'>This page has not been approved for the State Program yet.</div>";
         echo "<br><br><a href=\"javascript:window.close();\">Close</a></div>";
         echo $end_html;
         exit();
      }
   }
   //ELSE GO TO PROGRAM PAGE
   header("Location:programpdf.php?sport=$sport&session=$session&sid1=$sid&sid2=$sid2&viewdistrict=$viewdistrict");
   exit();
}
//CHECK IF THIS SCHOOL IS ON THE LIST OF SCHOOLS FOR THIS SPORT AS A HEAD SCHOOL (Co-op or not)
        //get schoolid
$sql="SELECT * FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$enrollment=$row[enrollment];
$schoolid=$row[id]; $sport="sog";
if(!IsHeadSchool($schoolid,$sport) && !GetCoopHeadSchool($schoolid,$sport) && $school!="Test's School") //NOT a $sport school at all
{
   echo $init_html;
   echo GetHeader($session);
   echo "<br><br><br><div class='alert' style='width:400px;'><b>$school</b> is not listed as a ".GetActivityName($sport)." school.<br><br>If you believe this is an error, please contact the NSAA office.</div><br><br><br>";
   echo $end_html;
   exit();
}
else if(!IsHeadSchool($schoolid,$sport) && $school!="Test's School")    //in a Co-op, not the head school
{
   echo $init_html;
   echo GetHeader($session);
   $mainsch=GetCoopHeadSchool($schoolid,$sport);
   echo "<br><br><br><div class='alert' style='width:400px'><b>$school</b> is in a co-op with <b>$mainsch</b> for ".GetActivityName($sport).".<br><br>Only the head school of the co-op can fill out this entry form.  <b>$mainsch</b> is listed as the head school for this co-op.<br><br>If you believe this is an error, please contact the NSAA office.</div><br><br><br>";
   echo $end_html;
   exit();
}

//get due date from db
$sql="SELECT duedate FROM form_duedates WHERE form='so_g'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";

//Check if State Form should show up now (Dist Form is 10 days past due date)
if($director!=1 && PastDue($duedate,8))
{
   $form_type="STATE";
   $state=1;
   $table="so_gstate";
}
else
{  
   $form_type="DISTRICT";
   $state=0;
   $table="so_g";
}

//get class/dist for this team
$sql="SELECT t1.class_dist FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND t2.school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class_dist=$row[0];

//get name of coach from logins table
$sql="SELECT name, asst_coaches FROM logins WHERE school='$school2' AND sport='Girls Soccer'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$coach=$row[0];
$asst_coaches=$row[1];

//get mascot and colors from headers table
$sql="SELECT * FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$colors=$row[5];
$mascot=$row[6];

//get team record from db
$record=GetWinLoss($sid,$sport);
$record=split("-",$record);
$win=$record[0];
$loss=$record[1];

//get due date from db
$sql="SELECT duedate FROM form_duedates WHERE form='so_g'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";

//If form has already been submitted, get info from db:
$sql="SELECT t1.* FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$school2' OR t1.co_op='$school2') AND t1.checked='y'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)	//no data entered yet, re-direct to Edit pg
{
   if($director!=1)
      header("Location:edit_so_g.php?session=$session&school_ch=$school_ch");
   else
      echo "$school has not completed an entry form.";
   exit(); 
}
else
{
   $row=mysql_fetch_array($result);
   $submitted=$row[submitted];
}

echo $init_html;
if($print!=1) 
{
   echo $header;
   if($level==1)
      echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Soccer\">Return to Home-->Soccer Entry Forms</a><br>";
}

$string=$init_html;
$csv="";

if($print!=1)
{
   echo "<br><a href=\"view_so_g.php?session=$session&school_ch=$school_ch&print=1\" class=small target=new>Printer-Friendly Version</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"edit_so_g.php?session=$session&school_ch=$school_ch\" class=small>Edit this Form</a><br><br>";
   if($send=='y')
   {
      echo "<font style=\"color:red\"><b>Your form has been submitted to the NSAA!</b></font><br><br>";
   }
   else if($state==1 && $submitted!='')
   {
      echo "<div class=alert style=\"width:500px;\"><b>You submitted this form to the NSAA on ".date("F j, Y",$submitted)." at ".date("g:i a",$submitted).".</b><br><br><u>If you need to make any last-minute changes</u>, please click \"Edit this Form\" above, make the necessary changes, and check the box on the Edit screen indicating this is your final submission.  You MUST check that box and click \"Save\" in order for the NSAA to receive your changes.</div><br><br>";
   }
}

$info="<table><tr align=center>";
$info.="<th>GIRLS SOCCER $form_type ENTRY</th></tr>";
if($state!=1)
{
   $string.="<tr align=center>";
   $string.="<td>";
   if(PastDue($duedate,0))
   {
      $string.="<div class='error' style='width:400px;text-align:left;'><p><b>Due $duedate2.</b></p><p>Please let your District Director know of any changes you make to this form, since the due date for this information has passed.</div>";
   }
   else
      $string.="<b>Due $duedate2</b>";
   $string.="<br><br></td></tr>";
}

$info.="<tr align=left><td>";
$info.="<table cellspacing=0 cellpadding=2>";
$info.="<tr align=left>";
$info.="<th>School/Mascot:</th>";
//check for special coop info
//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'sog');
$sql="SELECT * FROM sogschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];
$filename=$row[filename];
$info.="<td>".GetSchoolName($sid,'sog')." $mascot</td></tr>";
$csv.="School/Mascot:,".GetSchoolName($sid,'sog')." $mascot\r\n";
$info.="<tr align=left>";
$info.="<th>Colors:</th><td>$colors</td></tr>";
$info.="<tr align=left>";
$info.="<th>$stateassn-Certified Coach:</th><td>$coach</td></tr>";
$info.="<tr align=left>";
$info.="<th>Assistant Coaches:</th><td>$asst_coaches</td></tr>";
$info.="<tr align=left><th>Class:</th><td>$row[class]</td></tr>";
$info.="<tr align=left>";
$info.="<th>Team Record:</th><td>$win-$loss</td></tr>";
$info.="<tr align=left><th>Team Photo:</th>";
if($filename!='')
   $info.="<td><a href=\"../downloads/$filename\" target=\"_blank\">Preview Photo</a></td>";
else
   $info.="<td><a href=\"edit_so_g.php?session=$session&school_ch=$school_ch\">Click Here to Upload your Team Photo</a></td>";
if($level==1)
{
        //Superintendent
      $sql2="SELECT name FROM logins WHERE school='$school2' AND sport='Superintendent'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $info.="<tr align=\"left\"><th>Superintendent:</b></th><td>$row2[name]</td></tr>";
        //Principal
      $sql2="SELECT name FROM logins WHERE school='$school2' AND sport='Principal'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $info.="<tr align=\"left\"><th>Principal:</th><td>$row2[name]</td></tr>";
        //AD
      $sql2="SELECT name FROM logins WHERE school='$school2' AND level='2'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $info.="<tr align=\"left\"><th>Athletic Director:</th><td>$row2[name]</td></tr>";
        //Enrollment
      $info.="<tr align=\"left\"><th>NSAA Enrollment:</th><td>$enrollment</td></tr>";
      $sql2="SELECT * FROM sogschool WHERE sid='$sid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
        //Trips to State: 4
      $info.="<tr align=\"left\"><th>Trips to State:</th><td>$row2[tripstostate]</td></tr>";
        //Most Recent: 2012
      $info.="<tr align=\"left\"><th>Most Recent:</th><td>$row2[mostrecent]</td></tr>";
        //Championships: None
      $info.="<tr align=\"left\"><th>Championships:</th><td>$row2[championships]</td></tr>";
        //Runner-up: B/2008, B/2010
      $info.="<tr align=\"left\"><th>Runner-up:</th><td>$row2[runnerup]</td></tr>";
        //GENERATE PDF
      if($state==1)
      {
         $info.="<tr align=left><td colspan=2>
        <form method=\"post\" action=\"view_so_g.php\" target=\"_blank\">
        <input type=hidden name=\"session\" value=\"$session\">
        <input type=hidden name=\"school_ch\" value=\"$school\">
        <div id=\"pdflink\" style=\"margin:10px;\"></div><input type=submit name=\"makepdf\" value=\"Preview State Program Page (PDF)\">
        </form></td></tr>";
      }
      else
      {
         $info.="<tr align=left><td colspan=2>
        <form method=\"post\" action=\"view_so_g.php\" target=\"_blank\">
        <input type=hidden name=\"session\" value=\"$session\">
        <input type=hidden name=\"school_ch\" value=\"$school\"><input type=hidden name=\"viewdistrict\" value=\"1\"e
        <div id=\"pdflink\" style=\"margin:10px;\"></div><input type=submit name=\"makepdf\" value=\"Preview Program Page (PDF)\">
        </form></td></tr>";
      }
} //END IF LEVEL 1
$info.="</table></td></tr>";
//$csv.="Colors:,$colors\r\n";
//$csv.="$stateassn-Certified Coach:,$coach\r\n";
$asst_coaches2=preg_replace("/,/","/",$asst_coaches);
//$csv.="Asst Coaches:,$asst_coaches2\r\n";
//$csv.="Class:,$class_dist\r\nRecord:,$win,$loss\r\n";

$info.="<tr align=center><td>";
$info.="<table width=\"100%\" cellspacing=0 cellpadding=5 frame='all' rules='all' style=\"border:#808080 1px solid;\">";
$info.="<tr align=center>";
$info.="<th rowspan=2 class=smaller>Light<br>Jersey<br>No.</th>";
$info.="<th rowspan=2 class=smaller>Dark<br>Jersey<br>No.</th>";
$info.="<th rowspan=2 class=smaller>Name</th><th rowspan=2 class=smaller>Nickname</th>";
$info.="<th rowspan=2 class=smaller>Grade</th>";
$info.="<th rowspan=2 class=smaller>Position</th>";
$info.="<th rowspan=2 class=smaller>Goals</th>";
$info.="<th rowspan=2 class=smaller>Assists</th>";
$info.="<th colspan=3 class=smaller>Goalkeeper Stats</th></tr>";
$info.="<tr align=center>";
$info.="<th class=smaller>Games</th>";
$info.="<th class=smaller>Goals<br>Allowed</th>";
$info.="<th class=smaller>Saves</th></tr>";
//$csv.="\r\nLight Jersey,Dark Jersey,Name,Grade,Position,Assists,Goals,GK Games,GK Goals Allowed,GK Saves\r\n";
$csv.="\r\nLight Jersey,Dark Jersey,Name\r\n";
$goaliecsv="Goalkeeping\r\nNo.,Name,GK Games, GK Goals Allowed, GK Saves\r\n";

   //get checked students for this school's form
   $sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$school2' OR t1.co_op='$school2') AND t1.checked='y' ORDER BY CAST(t1.jersey_lt AS DECIMAL), CAST(t1.jersey_dk AS DECIMAL)";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $info.="<tr align=center>";
      $info.="<td>$row[5]</td><td>$row[6]</td>";
      $info.="<td align=left>$row[first] $row[last]</td><td>$row[nickname]</td>";
      $year=GetYear($row[semesters]);
      $info.="<td>$year</td>";
      $info.="<td>$row[7]</td>";
      $info.="<td>$row[8]</td><td>$row[9]</td>";
      $info.="<td>$row[10]</td><td>$row[11]</td><td>$row[12]</td></tr>";
      //$csv.="$row[5],$row[6],$row[first] $row[last],$year,$row[7],$row[9],$row[8],$row[10],$row[11],$row[12]\r\n";
      $csv.="$row[5],$row[6],$row[first] $row[last]\r\n";
      if($row[7]=="GK")
      {
	 $goaliecsv.="$row[5],$row[first] $row[last],$row[10],$row[11],$row[12]\r\n";
      }
      $ix++;
   }
   $info.="</table>";

$info.="</td></tr>";

//$csv.="\r\n$goaliecsv\r\n\r\n\r\n";

echo $info;
$string.=$info;
$string.="</table></td></tr></table></body></html>";

//Write to .html and .csv file to be sent/e-mailed:
$string.="</table></td></tr></table></body></html>";
$activ="Girls Soccer";
$activ_lower=strtolower($activ);
$activ_lower=preg_replace("/ /","",$activ_lower);

$sch=preg_replace("/[^0-9a-zA-Z]/","",$school);
$sch=strtolower($sch);
$filename="$sch$activ_lower";
if($state==1)
{
   $filename.="state";
}
$open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.html"),"w");
fwrite($open,$string);
fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.html");

//add stats to bottom of CSV file
//$csv.="Head Coach:,$coach\r\n";
//$csv.="Assistant Coaches:,$asst_coaches\r\n";
$sql="SELECT enrollment,conference FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
/* $csv.="NSAA Enrollment:,$row[enrollment]\r\nConference:,$row[conference]\r\n";
$csv.="Number of State Tournament Appearances:,\r\n";
$csv.="Most Recent State Tournament Apperance:,\r\n";
$csv.="State Championship Years:,\r\n";
$csv.="State Runner-Up Years:\r\n"; */
if($state==1)
{
   $sid=GetSID2($school,'sog');
   $year=GetFallYear('sog');
   $year1=$year+1;

   //$csv.="\r\n$year-$year1 (".GetWinLoss($sid,'sog',$year).")\r\nOpponent,W/L,Score,Opp.Score,Extra\r\n";
   $sched=GetSchedule($sid,'sog',$year);
   for($i=0;$i<count($sched[oppid]);$i++)
   {
      if($sched[oppid][$i]!='0')        //only individual games, not tournament names
      {
         $curopp=preg_replace("/, /"," ",GetSchoolName($sched[oppid][$i],'sog'));
         $curopp=preg_replace("/,/","",$curopp);
         //$csv.="$curopp,";
         $score=split("-",$sched[score][$i]);
         //if($score[0]>$score[1]) $csv.="W,";
         //else if($score[1]>$score[0]) $csv.="L,";
         //else $csv.="T,";
         //$csv.="$score[0],$score[1],".$sched[extra][$i]."\r\n";
      }
   }
}

$open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.csv");

if($print==1)
{
?>
   <tr align=center><td>
   <table>
   <tr align=center><th><br><br>
   <form method=post action="../email_form.php" name=emailform>
   <input type=hidden name=state value=<?php echo $state; ?>>
   <input type=hidden name=session value=<?php echo $session; ?>>
   <input type=hidden name=school value="<?php echo $school; ?>">
   <input type=hidden name=activ value="<?php echo $activ; ?>">
      <table>
<tr align=center><td colspan=2><b>E-MAIL THIS FORM:</b><br>PLEASE NOTE: Your district director will automatically receive these forms once the due date has passed. You do NOT need to email this form to the district director.</td></tr>
      <tr align=left><th>
      Your e-mail address:</th>
      <td><input type=text name=reply size=30></td>
      </tr>
      <tr align=left><th>
      Recipient(s)' address(es):</th>
      <td>
      <textarea name=email cols=50 rows=5><?php echo $recipients; ?></textarea>
      <?php
      //echo "<input type=button name=addressbook value=\"Address Book\" onclick=\"window.open('../addressbook.php?session=$session','addressbook','menubar=no,location=no,resizable=no,scrollbars=yes,width=500,height=600')\">";
      ?>
      </td>
      </tr>
      <tr align=center><td colspan=2>
      <input type=submit name=submit value="Send">
      </td></tr>
      </table>
      <font style="font-size:8pt">
   <?php echo $email_note; ?>
   </font>
   </form>
   </th></tr>
   </table>
   </td></tr>
<?php
}//end if print=1
else	//print!=1
{
   echo "<tr align=center><td><br>";
   echo "<a href=\"edit_so_g.php?session=$session&school_ch=$school_ch\" class=small>Edit This Form</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"view_so_g.php?session=$session&school_ch=$school_ch&print=1\" class=small target=new>Printer-Friendly Version</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"../welcome.php?session=$session\" class=small>Home</a>";
   echo "</td></tr>";
}
if($send=='y')  //if box checekd at bottom of edit screen, send to NSAA
{
   $From=GetEmail("main");
   $FromName=$stateassn;
   $To="jangele@nsaahome.org";
   $ToName="Jim Angele";
   $Subject="$school Girls Soccer State Tournament Roster";
   $Text="Attached is a CSV for Excel file of $school's Girls Soccer State Tournament 
Roster Information.  Thank you.";
   $Html="<font size=2 family=arial>Attached is a CSV-for-Excel file of $school'
s Girls Soccer State Tournament Roster information.<br><br>They have approved this as
their final submission.<br><br>Thank you!</font>";
   $AttmFiles=array("/home/nsaahome/attachments/$filename.csv");

   SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles);
   //SendMail($From,$FromName,"run7soccer@aim.com","Ann Gaffigan",$Subject,$Text,$Html,$AttmFiles);

   $today=time();
   $sql="UPDATE $table SET submitted='$today' WHERE school='$school2'";
   $result=mysql_query($sql);
}

echo "</table>";
?>
</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
