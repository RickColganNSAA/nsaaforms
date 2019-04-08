<?php
//view_bb_g.php: Show submitted district
//   entry info.  If none have been submitted,
//   redirect to edit_bb_g.php

require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require $_SERVER['DOCUMENT_ROOT'].'/calculate/variables.php'; //Wildcard Variables

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

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
else if($level==1 || GetUserName($session)=="Cornerstone" || $level==9)
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
   $sql="SELECT * FROM $db_name2.bbgdistricts WHERE (hostid='$hostid' OR hostid2='$hostid')";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      //CHECK bbgdisttimes (CLASS A BASKETBALL)
      $sql="SELECT * FROM $db_name2.bbgdisttimes WHERE hostid='$hostid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)
      {
         echo "You are not the host of this school's district.";
         exit();
      }
   }
}
$school2=ereg_replace("\'","\'",$school);
$sid=GetSID2($school,'bbg');
$sport='bbg';

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
   header("Location:programpdf.php?session=$session&sid1=$sid&sid2=$sid2&viewdistrict=$viewdistrict&sport=bbg");
   exit();
}
//CHECK IF THIS SCHOOL IS ON THE LIST OF SCHOOLS FOR THIS SPORT AS A HEAD SCHOOL (Co-op or not)
        //get schoolid
$sql="SELECT * FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$enrollment=$row[enrollment];
$schoolid=$row[id]; $sport="bbg";
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

//check if this is state form or district form
$duedate=GetDueDate("bb_g");
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";
if($director!=1 && PastDue($duedate,10))       //state form
{
   $state=1;
   $table="bb_gstate";
   $form_type="STATE";
}
else
{
   $state=0;
   $table="bb_g";
   $form_type="DISTRICT";
}

//check if this form has already been submitted:
$sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$school2' OR t1.co_op='$school2') AND t1.checked='y' ORDER BY CAST(t1.jersey_lt AS DECIMAL), CAST(t1.jersey_dk AS DECIMAL)";
$result=mysql_query($sql); 
  //if it hasn't been submitted, redirect to Edit page:
if(mysql_num_rows($result)==0)
{
   if($director!=1)
      header("Location:edit_bb_g.php?session=$session&school_ch=$school_ch");
   else
      echo "$school has not completed an entry form.";
   exit();
}
  //if it has been submitted, show submitted info:
  $entryct=mysql_num_rows($result);
  $string=$init_html;
  $csv="";
  echo $init_html;

if($print!=1)
{
   $header=GetHeader($session);
   echo $header;

if($level==1)
   echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Basketball\">Return to Home-->Basketball Entry Forms</a><br>";
}

//get information about school and coach:
$sql2="SELECT * FROM headers WHERE school='$school2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$colors=$row2[5];
$mascot=$row2[6];
$enroll=trim($row2[enrollment]);
$conf=$row2[conference];
//see if co-op schools; if so, add enrollments together
$sql2="SELECT DISTINCT co_op FROM $table WHERE school='$school2' AND co_op IS NOT NULL";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   $row2[0]=ereg_replace("\'","\'",$row2[0]);
   $sql3="SELECT enrollment FROM headers WHERE school='$row2[0]'";
   $result3=mysql_query($sql3);
   $row3=mysql_fetch_array($result3);
   $enroll+=$row3[0];
}
$sql2="SELECT name,asst_coaches FROM logins WHERE school='$school2' AND sport='Girls Basketball'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$coach=$row2[0];
$asst=$row2[1];

//get class/dist, team record & off/def avg from table:
$sql2="SELECT * FROM $table WHERE school='$school2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$off_avg=$row2[off_avg];
$def_avg=$row2[def_avg];
$class_dist=$row2[class_dist];

$record=GetWinLoss($sid,$sport);

if($print!=1)
{
echo "<a href=\"view_bb_g.php?session=$session&school_ch=$school_ch&print=1\" target=new class=small>Printer/E-mail Friendly Version</a>&nbsp;&nbsp;&nbsp;";
echo "<a href=\"edit_bb_g.php?session=$session&school_ch=$school_ch\" class=small>Edit this Form</a><br><br>";
$sql0="SELECT submitted FROM $table WHERE submitted!=''";
$result0=mysql_query($sql0);
if(mysql_num_rows($result0)>0)
{
   $row0=mysql_fetch_array($result0);
   echo "<font style=\"color:red\"><b>You submitted your STATE form on ".date("m/d/Y",$row0[0]).".<br>";
   echo "If you need to make another change, you may do so and submit this form again.<br>";
   echo "Otherwise, your last submission will be considered final.</b></font><br><br>";
}
}
$info="<table width='800px'>";
$info.="<tr align=center>";
$info.="<th>GIRLS BASKETBALL $form_type ENTRY</th>";
$info.="</tr>";
if($state!=1)
{
   $info.="<tr align=center>";
   $info.="<td><b>Due $duedate2</b><br><br></td></tr>";
}
$info.="<tr align=left><td>";
$info.="<table cellspacing=2 cellpadding=2><!--Show school, coach, etc.-->";
$info.="<tr align=left><th align=left>School/Mascot:</th>";
//check if special co-op mascot/colors/coach for this sport
$sid=GetSID2($school,'bbg');
$sql2="SELECT * FROM bbgschool WHERE sid='$sid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$filename=$row2[filename];
$class=$row2['class'];
if($row2[mascot]!='') $mascot=$row2[mascot];
if($row2[colors]!='') $colors=$row2[colors];
if($row2[coach]!='') $coach=$row2[coach];
$info.="<td>".GetSchoolName($sid,'bbg')." $mascot</td></tr>";
$csv.="School/Mascot:,".GetSchoolName($sid,'bbg')." $mascot\r\n";
$info.="<tr align=left><th align=left>School Colors:</th>";
$info.="<td>$colors</td></tr>";
$info.="<tr align=left><th align=left>Coach:</th>";
$info.="<td>$coach</td></tr>";
$info.="<tr align=left><th align=left>Assistant Coach(es):</th>";
$info.="<td style=\"width:400px;\">$asst</td></tr>";
$info.="<tr align=left><th align=left>Class:</th>";
$info.="<td>$class</td></tr>";
$info.="<tr align=left><th align=left>Team Record:</th>";
$info.="<td>$record</td></tr>";
$info.="<tr align=left><th align=left>Offensive Avg:</th>";
$info.="<td>$off_avg</td></tr>";
$info.="<tr align=left><th align=left>Defensive Avg:</th>";
$info.="<td>$def_avg</td></tr>";
$info.="<tr align=left><th>Team Photo:</th>";
if($filename!='')
   $info.="<td><a href=\"../downloads/$filename\" target=\"_blank\">Preview Photo</a></td>";
else
   $info.="<td><a href=\"edit_bb_g.php?session=$session&school_ch=$school_ch\">Click Here to Upload your Team Photo</a></td>";
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
      $sql2="SELECT * FROM bbgschool WHERE sid='$sid'";
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
        <form method=\"post\" action=\"view_bb_g.php\" target=\"_blank\">
        <input type=hidden name=\"session\" value=\"$session\">
        <input type=hidden name=\"school_ch\" value=\"$school\">
        <div id=\"pdflink\" style=\"margin:10px;\"></div><input type=submit name=\"makepdf\" value=\"Preview State Program Page (PDF)\">
        </form></td></tr>";
      }
} //END IF LEVEL 1
$info.="</table>";
$csv.="School Colors:,$colors\r\n";
$csv.="Class:,$class\r\n";
$csv.="Team Record: $record\r\n";
$csv.="Coach:, $coach\r\nAssistant Coach(es):,$asst\r\n";
$info.="</td></tr>";
$info.="<tr align=center>";
$info.="<td><br>";
if($entryct>14)
{
   echo "<font style=\"color:red; font-size:9pt\"><b>You have entered too many students!! </b>You may only enter <b>14</b> on this form. Please <a href=\"edit_bb_g.php?session=$session&school_ch=$school_ch\">correct this error</a>.</font>";
}
$info.="<table cellpadding=5 cellspacing=0 frame='all' rules='all' style=\"border:#808080 1px solid;\">";
$info.="<tr align=center>";
$info.="<th class=smaller>Name</th>";
$info.="<th class=smaller>Grade</th>";
$info.="<th class=smaller>Light<br>Jersey<br>No.</th>";
$info.="<th class=smaller>Dark<br>Jersey<br>No.</th>";
$info.="<th class=smaller>Position</th>";
$info.="<th class=smaller>Height</th>";
$info.="<th class=smaller>Total<br>Points</th>";
$info.="<th class=smaller>Point<br>Average</th>";
$info.="<th class=smaller>Total<br>Rebounds</th>";
$info.="<th class=smaller>Rebound<br>Average</th>";
$info.="<th class=smaller>Total<br>Assists</th>";
$info.="<th class=smaller>Total<br>Steals</th>";
$info.="<th class=smaller>Total<br>Blocks</th>";
$info.="</tr>";

$csv.="\r\n,,,,,Total";
$csv.="\r\nLight Jersey No,Dark Jersey No,Name,GR,HT,Position,Points,Point Avg,Rebounds,Rebound Avg,Assists,Steals,Blocks\r\n";
$requiredfielderr=0;
while($row=mysql_fetch_array($result))
{
     $info.="<tr align=left>";
     $last=$row[last];
     $first=$row[first];
     $mid=$row[middle];
     $info.="<td>$first $last</td>";
     $year=GetYear($row[semesters]);
     $info.="<td>$year</td>";
     $info.="<td";
     if(trim($row[jersey_lt])=="") { $info.=" bgcolor=\"red\""; $requiredfielderr=1; }
     $info.=">$row[jersey_lt]</td>";
     $info.="<td";
     if(trim($row[jersey_dk])=="") { $info.=" bgcolor=\"red\""; $requiredfielderr=1; }
     $info.=">$row[jersey_dk]</td>";
     $info.="<td";
     if(trim($row[position])=="") { $info.=" bgcolor=\"red\""; $requiredfielderr=1; }
     $info.=">$row[position]</td>";
     $info.="<td";
     if(trim($row[height])=="-") { $info.=" bgcolor=\"red\""; $requiredfielderr=1; }
     $info.=">$row[height]</td>";
     $info.="<td";
     if($state==1 && trim($row[total_pts])=="") { $info.=" bgcolor=\"red\""; $requiredfielderr=1; }
     $info.=">$row[total_pts]</td>";
     $info.="<td";
     if($state==1 && trim($row[pt_avg])=="") { $info.=" bgcolor=\"red\""; $requiredfielderr=1; }
     $info.=">$row[pt_avg]</td>";
     $info.="<td";
     if($state==1 && trim($row[total_rb])=="") { $info.=" bgcolor=\"red\""; $requiredfielderr=1; }
     $info.=">$row[total_rb]</td>";
     $info.="<td";
     if($state==1 && trim($row[reb_avg])=="") { $info.=" bgcolor=\"red\""; $requiredfielderr=1; }
     $info.=">$row[reb_avg]</td>";
     $info.="<td";
     if($state==1 && trim($row[total_assists])=="") { $info.=" bgcolor=\"red\""; $requiredfielderr=1; }
     $info.=">$row[total_assists]</td>";
     $info.="<td";
     if($state==1 && trim($row[total_steals])=="") { $info.=" bgcolor=\"red\""; $requiredfielderr=1; }
     $info.=">$row[total_steals]</td>";
     $info.="<td";
     if($state==1 && trim($row[total_blocks])=="") { $info.=" bgcolor=\"red\""; $requiredfielderr=1; }
     $info.=">$row[total_blocks]</td>";
     $info.="</tr>";
     $row[3]=split("-",$row[3]);
     $height=$row[3][0]."'".$row[3][1]."\"";
     $csv.="$row[jersey_lt],$row[jersey_dk],$first $last,$year,$height,$row[position],$row[total_pts],$row[pt_avg],$row[total_rb],$row[reb_avg],$row[total_assists],$row[total_steals],$row[total_blocks]\r\n";
}

$info.="</table>";
if($requiredfielderr==1)
   $info.="<div class=error>You are missing information in some of your required fields.  Please click \"Edit this Form\" and complete ALL required fields.<br><br>If a player has NO STATS for a certain field, please enter a \"0\" in that column.</div>";
$info.="</td></tr>";
$csv.="Off Avg:,$off_avg\r\nDef Avg:,$def_avg\r\n\r\n";
echo $info;
$string.=$info;

//ADD SEASON GAMES AND SCORES TO CSV FILE (state form only)
if($state==1)
{
   $sid=GetSID2($school,'bbg');
   $year=GetFallYear('bbg');
   $year1=$year+1;
   $csv.="Head Coach:,$coach\r\n";
   $csv.="Assistant Coaches:,\"$asst\"\r\n";
   $csv.="Enrollment:,$enroll\r\nConference:,$conf\r\n";
   $sql="SELECT * FROM bbgschool WHERE sid='$sid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   //add coaches,etc info to excel file
   $csv.="State Tournament Appearances:,\"$row[tripstostate]\"\r\n";
   $csv.="Most Recent State Tournament:,\"$row[mostrecent]\"\r\n";
   $csv.="State Championship Years:,\"$row[championships]\"\r\n";
   $csv.="State Runner-Up Years:,\"$row[runnerup]\"\r\n";

   $csv.="\r\n$year-$year1 (".GetWinLoss($sid,'bbg',$year).")\r\nOpponent,W/L,Score,Opp.Score,Extra\r\n";
   $sched=GetSchedule($sid,'bbg',$year);
   for($i=0;$i<count($sched[oppid]);$i++)
   {
      if($sched[oppid][$i]!='0')        //only individual games, not tournament names
      {
         $curopp=ereg_replace(", "," ",GetSchoolName($sched[oppid][$i],'bbg'));
	 $curopp=ereg_replace(",","",$curopp);
	 $csv.="$curopp,";
         $score=split("-",$sched[score][$i]);
	 if($score[0]>$score[1]) $csv.="W,";
         else if($score[1]>$score[0]) $csv.="L,";
	 else $csv.="T,";
         $csv.="$score[0],$score[1],".$sched[extra][$i]."\r\n";
      }
   }
}//end if state=1

if($print!=1)
{
?>
<tr align=center>
<td><br>
    <a href="view_bb_g.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>&print=1" target=new class=small>Printer/E-mail Friendly Version</a>
    &nbsp;&nbsp;&nbsp;
    <a href="edit_bb_g.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>" class=small>Edit this Form</a>
    &nbsp;&nbsp;&nbsp;
    <a href="../welcome.php?session=<?php echo $session; ?>" class=small>Home</a>
</td>
</tr>
<?php
}//end if print!=1
   //Allow user to e-mail form
   $string.="</table></td></tr></table></body></html>";
   $activ="Girls Basketball";
   $activ_lower=strtolower($activ);

   $sch=ereg_replace(" ","",$school);
   $sch=ereg_replace("-","",$sch);
   $sch=ereg_replace("\.","",$sch);
   $sch=ereg_replace("\'","",$sch);
   $sch=strtolower($sch);
   $activ_lower=ereg_replace(" ","",$activ_lower);
   $filename="$sch$activ_lower";
   if($state==1)
      $filename.="state";
   if(!$open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.html"),"w")) echo "Can't write file.";
   if(!fwrite($open,$string)) echo "Could not write to $filename.html";
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.html");

   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.csv"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.csv");

if($print==1)
{
?>
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
<textarea name=email cols=50 rows=5 class=email><?php echo $recipients; ?></textarea>
<?php
//echo "<input type=button name=addressbook value=\"Address Book\" onclick=\"window.open('../addressbook.php?session=$session&school_ch=$school2&form=bb_g','addressbook','menubar=no,location=no,resizable=no,scrollbars=yes,width=500,height=600')\">";
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
<?php
}  //end if print=1
if($send=='y')  //if box checked at bottom of edit screen, send to State Assn
{
   $From="nsaa@nsaahome.org";
   $FromName=$stateassn;
   $To=$main_email;
   $ToName=$stateassn;
   $Subject="$school $activ State Tournament Roster";
   $Text="Attached is a CSV for Excel file of $school's $activ State Tournament Roster Information.  Thank you.";
   $Html="<font size=2 family=arial>Attached is a CSV-for-Excel file of $school'
s $activ State Tournament Roster information.<br><br>They have approved this as
their final submission.<br><br>Thank you!</font>";
   $AttmFiles=array("/home/nsaahome/attachments/$filename.csv");

   SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles);
   //SendMail($From,$FromName,"run7soccer@aol.com","Ann Gaffigan",$Subject,$Text,$Html,$AttmFiles);

   $today=time();
   $sql="UPDATE $table SET submitted='$today' WHERE school='$school2'";
   $result=mysql_query($sql);
}
?>

</table>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
