<?php
//view_sb.php: Show submitted district
//   entry info.  If none have been submitted,
//   redirect to edit_sb.php

require '../functions.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/variables.php'; //Wildcard Variables

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

$level=GetLevel($session);
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
   $sql="SELECT * FROM $db_name2.sbdistricts WHERE hostid='$hostid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      echo "You are not the host of this school's district.";
      exit();
   }
}
$school2=addslashes($school);
$sid=GetSID2($school,'sb');
$sport='sb';

if($makepdf)    //GET OTHER SCHOOL TO GO ON THIS PAGE
{
   $sql="SELECT programorder,class,approvedforprogram FROM sbschool WHERE sid='$sid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $order=$row[programorder]; $class=$row['class'];
   $approved=$row[approvedforprogram];
   //GET OTHER SCHOOL ON SAME PAGE
   if($order%2==0)      //THIS SCHOOL IS THE SECOND ONE ON THE PAGE
      $order2=$order-1;
   else                 //ELSE IT IS THE FIRST ONE ON THE PAGE
      $order2=$order+1;
   $sql="SELECT * FROM sbschool WHERE class='$class' AND programorder='$order2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sid2=$row[sid]; $approved2=$row[approvedforprogram];
   //IF $makepdf && NOT NSAA - CHECK THAT BOTH SID's HAVE BEEN APPROVED FOR PROGRAM
/*    if($level!=1)
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
   } */
   //ELSE GO TO PROGRAM PAGE
   header("Location:programpdf.php?session=$session&sid1=$sid&sid2=$sid2&viewdistrict=$viewdistrict");
   exit();
}
//CHECK IF THIS SCHOOL IS ON THE LIST OF SCHOOLS FOR THIS SPORT AS A HEAD SCHOOL (Co-op or not)
        //get schoolid
$sql="SELECT id FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schoolid=$row[id]; $sport="sb";
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
$duedate=GetDueDate("sb");
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";
if($director!=1 && PastDue($duedate,8))       //state form
{
   $state=1;
   $table="sb_state";
   $form_type="STATE";
}
else
{
   $state=0;
   $table="sb";
   $form_type="DISTRICT";
}

//get class/dist submitted for this team
$sql="SELECT class_dist FROM $table WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class_dist=$row[0];

//check if this form has already been submitted:
$sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND ((t1.school='$school2' AND t2.school='$school2') OR (t1.co_op='$school2' AND t2.school=t1.school)) AND t1.checked='y' ORDER BY CAST(t1.jersey_lt AS DECIMAL), t1.libero";

$sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$school2' OR t1.co_op='$school2') AND t1.checked='y' ORDER BY CAST(t1.jersey_lt AS DECIMAL), CAST(t1.jersey_dk AS DECIMAL)";
//echo $sql;
$result=mysql_query($sql); 
  //if it hasn't been submitted, redirect to Edit page:
if(mysql_num_rows($result)==0)
{
   if($director!=1)
      header("Location:edit_sb.php?session=$session&school_ch=$school_ch");
   else
      echo "$school has not completed an entry form.";
   exit();
}
  //if it has been submitted, show submitted info:
  $string=$init_html;
  $csv="";
  echo $init_html;

if($print!=1)
{
   $header=GetHeader($session);
   echo $header;

   if($level==1)
      echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Softball\">Return to Home-->Softball Entry Forms</a><br>";
}

//get information about school and coach:
$sql2="SELECT * FROM headers WHERE school='$school2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$schid=$row[id];
$colors=$row2[color_names];
$mascot=$row2[mascot];
$enrollment=$row2[enrollment];
   $co_op=0;
   $sql1="SELECT id FROM headers WHERE  school='$school2' ";
   $result1=mysql_query($sql1);
   $row1=mysql_fetch_array($result1);
   $sql_coop="SELECT * FROM sbschool WHERE mainsch='$row1[id]' AND (othersch1!=0 OR othersch2!=0 OR othersch1!=0)";
   $result_coop=mysql_query($sql_coop);
   $coop=mysql_fetch_array($result_coop);

   if(!empty($coop))
   {
	 if (!empty($coop['othersch1'])){
	 $sql_coop1="SELECT enrollment FROM headers WHERE id='$coop[othersch1]'";
	 $result_coop1=mysql_query($sql_coop1);
	 $row_coop1=mysql_fetch_array($result_coop1);
	 $co_op+=$row_coop1[enrollment];
	 }
	 if ($coop['othersch2'] !=0){
	 $sql_coop2="SELECT enrollment FROM headers WHERE id='$coop[othersch2]'";
	 $result_coop2=mysql_query($sql_coop2);
	 $row_coop2=mysql_fetch_array($result_coop2);
	 $co_op+=$row_coop2[enrollment];
	 }
	 if ($coop['othersch3'] !=0){
	 $sql_coop2="SELECT enrollment FROM headers WHERE id='$coop[othersch3]'";
	 $result_coop3=mysql_query($sql_coop3);
	 $row_coop3=mysql_fetch_array($result_coop3);
	 $co_op+=$row_coop3[enrollment];
	 }
   }
$sql2="SELECT name,asst_coaches FROM logins WHERE school='$school2' AND sport='Softball'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$coach=$row2[0]; $asst=$row2[1];

//get team record from table:
$record=GetWinLoss($sid,$sport);

if($print!=1)
{
echo "<br><a href=\"view_sb.php?session=$session&school_ch=$school_ch&print=1\" target=new class=small>Printer-Friendly Version</a>&nbsp;&nbsp;&nbsp;";
echo "<a href=\"edit_sb.php?session=$session&school_ch=$school_ch\" class=small>Edit this Form</a><br><br>";
$sql2="SELECT submitted FROM $table WHERE submitted!=''";
$result2=mysql_query($sql2);
if(mysql_num_rows($result2)>0)
{
   $row2=mysql_fetch_array($result2);
   echo "<font style=\"color:red\"><b>You submitted your STATE form on ".date("m/d/Y",$row2[0]).".<br>";
   echo "If you need to make another change, you may do so and submit this form again.<br>";
   echo "Otherwise, your last submission will be considered final.</b></font>";
   echo "<br><br>";
}
}
$info="<table class='nine'>";
$info.="<tr align=center>";
$info.="<th>SOFTBALL $form_type ENTRY</th>";
$info.="</tr>";
if($state!=1)
{
   $info.="<tr align=center>";
   $info.="<td>";
   if(PastDue($duedate,0))
   {
      $info.="<div class='error' style='width:400px;text-align:left;'><p><b>Due $duedate2.</b></p><p>Please let your District Director know of any changes you make to this form, since the due date for this information has passed.</div>";
   }
   else
      $info.="<b>Due $duedate2</b>";
   $info.="<br><br></td></tr>";
}
else if($state==1 && $send=='y')
{
   $info.="<tr align=center><td><font style=\"color:red\"><b>Your Softball State Entry Form has been sent to the NSAA.</b></font></td></tr>";

   $today=time();
   $sql2="UPDATE $table SET submitted='$today' WHERE school='$school2'";
   $result2=mysql_query($sql);
}
$info.="<tr align=left><td>";
$info.="<table cellspacing=2 cellpadding=2><!--Show school, coach, etc.-->";
$info.="<tr align=left><th>School/Mascot:</th>";
//check if special co-op mascot/colors/coach for this sport
$sql2="SELECT * FROM sbschool WHERE sid='$sid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
if($row2[mascot]!='') $mascot=$row2[mascot];
if($row2[colors]!='') $colors=$row2[colors];
if($row2[coach]!='') $coach=$row2[coach];
$info.="<td>".GetSchoolName($sid,'sb')." $mascot</td></tr>";
$csv.="School/Mascot:,".GetSchoolName($sid,'sb')." $mascot\r\n";
$info.="<tr align=left><th>School Colors:</th>";
$info.="<td>$colors</td></tr>";
$info.="<tr align=left><th>NSAA-Certified Coach:</th>";
$info.="<td>$coach</td></tr>";
$info.="<tr align=left><th>Assistant Coaches:</th>";
$info.="<td>$asst</td></tr>";
$info.="<tr align=left><th>Class:</th>";
$info.="<td>$row2[class]</td></tr>";
$info.="<tr align=left><th>Team Record:</th>";
$info.="<td>$record</td></tr>";
$info.="<tr align=left><th>Team Photo:</th>";
if($row2[filename]!='')
   $info.="<td><a href=\"../downloads/$row2[filename]\" target=\"_blank\">Preview Photo</a></td>";
else
   $info.="<td><a href=\"edit_sb.php?session=$session&school_ch=$school_ch\">Click Here to Upload your Team Photo</a></td>";
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
      $info.="<tr align=\"left\"><th>Team Enrollment:</th><td>".($enrollment+$co_op)."</td></tr>";
      $sql2="SELECT * FROM sbschool WHERE sid='$sid'";
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
        <form method=\"post\" action=\"view_sb.php\" target=\"_blank\">
        <input type=hidden name=\"session\" value=\"$session\">
        <input type=hidden name=\"school_ch\" value=\"$school\">
        <div id=\"pdflink\" style=\"margin:10px;\"></div><input type=submit name=\"makepdf\" value=\"Preview State Program Page (PDF)\">
        </form></td></tr>";
      }
} //END IF LEVEL 1
$info.="</table>";
$csv.="School Colors:,$colors\r\n";
$csv.="NSAA-Certified Coach:,$coach\r\n";
$csv.="Assistant Coaches:,$asst\r\n";
$csv.="Class:,$class_dist\r\n";
$temp=split("-",$record);
$csv.="Team Record:,$temp[0],$temp[1]\r\n";
$info.="</td></tr>";
$info.="<tr align=center>";
$info.="<td><br>";
$info.="<table cellpadding=4 cellspacing=0 frame=all rules=all style=\"border:#808080 1px solid;\">";
$info.="<tr align=center>";
$info.="<th class=smaller>Name</th><th class=smaller>Grade</th>";
$info.="<th class=smaller>Light<br>Jersey<br>No.</th>";
$info.="<th class=smaller>Dark<br>Jersey<br>No.</th>";
$info.="<th class=smaller>Position</th>";
$info.="<th class=smaller>Batting<br>Average</th>";
$info.="<th class=smaller>At<br>Bats</th><th class=smaller>Hits</th>";
$info.="<th class=smaller>Runs<br>Scored</th>";
$info.="<th class=smaller>Runs<br>Batted<br>In</th>";
$info.="<th class=smaller>Home<br>Runs</th>";
$info.="<th class=smaller>Pitching<br>Record</th>";
$info.="<th class=smaller>Pitching<br>ERA</th>";
$info.="</tr>";

$csv.="Light Jersey No.,Dark Jersey No.,Name,Grade,Position\r\n";
//, Average,At Bats, Hits, Runs Scored, Runs Batted In, Home Runs, Pitching Wins,Pitching Losses,Pitching ERA\r\n";
while($row=mysql_fetch_array($result))
{
  if($row[checked]=="y")	//that student was checked to be on the roster
  {
     $info.="<tr align=left>";
     $last=$row[last];
     $first=$row[first];
     $mid=$row[middle];
     $nick=trim($row[nickname]);
     if($nick!='') $first=$nick;
     $info.="<td>$first $last</td>";
     $year=GetYear($row[semesters]);
     $info.="<td>$year</td>";
     $info.="<td>$row[5]</td>";
     $info.="<td>$row[6]</td>";
     $info.="<td>$row[7]</td>";
     $info.="<td>$row[8]</td>";
     $info.="<td>$row[9]</td>";
     $info.="<td>$row[10]</td>";
     $info.="<td>$row[11]</td>";
     $info.="<td>$row[12]</td>";
     $info.="<td>$row[13]</td>";
     $temp=split("-",$row[14]);
     $info.="<td>$row[14]</td>";
     $info.="<td>$row[15]</td>";
     $info.="</tr>";
     $row[8]=number_format($row[8],3,'.','');
     $csv.="$row[5],$row[6],$row[first] $row[last],$year,$row[7],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13],$temp[0],$temp[1],$row[15]\r\n";
  }
}

$info.="</table></td></tr>";
echo $info;
$string.=$info;

//ADD SEASON GAMES AND SCORES TO CSV FILE (state form only)
if($state==1)
{
   $sid=GetSID2($school,'sb');
   $year=GetFallYear('sb');

   $csv.="\"Games\"\r\n\"Opponent\",\"W/L\",\"Score\",\"Opp. Score\",\"Extra\"\r\n";

   $sched=GetSchedule($sid,'sb',$year);
   for($i=0;$i<count($sched[oppid]);$i++)
   {
      if($sched[oppid][$i]!='0')	//only individual games, not tournament names
      {
         $csv.="\"".GetSchoolName($sched[oppid][$i],'sb')."\",";
         $score=split("-",$sched[score][$i]);
	 if($score[0]>$score[1]) $wl="W";
	 else $wl="L";
         $csv.="\"$wl\",\"$score[0]\",\"$score[1]\",\"".$sched[extra][$i]."\"\r\n";
      }
   }

$sql="SELECT * FROM sbschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
//add coaches,etc info to excel file
$csv.="\r\nHead Coach:,$coach\r\n";
$csv.="Assistant Coaches:,\"$asst\"\r\n";
$csv.="NSAA Enrollment:,$enrollment\r\n";
$csv.="Team Enrollment:,".($enrollment+$co_op)."\r\n";
$csv.="Conference:,\"$conference\"\r\n";
$csv.="State Tournament Appearances:,\"$row[tripstostate]\"\r\n";
$csv.="Most Recent State Tournament:,\"$row[mostrecent]\"\r\n";
$csv.="State Championship Years:,\"$row[championships]\"\r\n";
$csv.="State Runner-Up Years:,\"$row[runnerup]\"\r\n";
}//end if state=1

if($print!=1)
{
?>
<tr align=center>
<td><br>
    <a href="view_sb.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>&print=1" target=new class=small>Printer-Friendly Version</a>
    &nbsp;&nbsp;&nbsp;
    <a href="edit_sb.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>" class=small>Edit this Form</a>
    &nbsp;&nbsp;&nbsp;
    <a href="../welcome.php?session=<?php echo $session; ?>" class=small>Home</a>
</td>
</tr>
<?php
}//end if print!=1
   //Allow user to e-mail form
   $string.="</table></td></tr></table></body></html>";
   $activ="Softball";
   $activ_lower=strtolower($activ);

   $sch=preg_replace("/[^0-9a-zA-Z]/","",$school);
   $sch=strtolower($sch);
   $activ_lower=preg_replace("/ /","",$activ_lower);
   $filename="$sch$activ_lower";
   if($state==1)
      $filename.="state";
   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.html"),"w");
   fwrite($open,$string);
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
//echo "<input type=button name=addressbook value=\"Address Book\" onclick=\"window.open('../addressbook.php?session=$session','addressbook','menubra=no,location=no,resizable=no,scrollbars=yes,width=500,height=600')\">";
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
if($send=='y')  //if box checked at bottom of edit screen, send to NSAA
{
   $From="nsaa@nsaahome.org";
   $FromName=$stateassn;
   $To="jangele@nsaahome.org";
   $ToName="NSAA";
   $Subject="$school $activ State Tournament Roster";
   $Text="Attached is a CSV for Excel file of $school's $activ State Tournament Roster Information.  Thank you.";
   $Html="<font size=2 family=arial>Attached is a CSV-for-Excel file of $school'
s $activ State Tournament Roster information.<br><br>They have approved this as
their final submission.<br><br>Thank you!</font>";
   $AttmFiles=array("/home/nsaahome/attachments/$filename.csv");

   SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles);
   SendMail($From,$FromName,"run7soccer@aim.com","Ann Gaffigan",$Subject,$Text,$Html,$AttmFiles);
   SendMail($From,$FromName,"kallol@primtechs.com","Kallol",$Subject,$Text,$Html,$AttmFiles);
}
?>

</table>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
