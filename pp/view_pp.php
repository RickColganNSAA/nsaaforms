<?php
//export_pp.php: export play production form to excel
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name,$db);

$level=GetLevel($session);

if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

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
   $sql="SELECT * FROM $db_name2.ppdistricts WHERE (hostid='$hostid' OR hostschool='$hostsch2')";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      echo "You are not the host of this school's district.";
      exit();
   }
}
$school2=ereg_replace("\'","\'",$school);

$sid=GetSID2($school,'pp');

if($makepdf)    //GET OTHER SCHOOLS TO GO ON THIS PAGE
{
   $sql="SELECT programorder,class,approvedforprogram FROM ppschool WHERE sid='$sid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $order=$row[programorder]; $class=$row['class'];
   $approved=$row[approvedforprogram];
   //GET OTHER SCHOOLs ON SAME PAGE
	/*
   if($order%3==0)      //THIS SCHOOL IS THE THIRD ONE ON THE PAGE
   {
      $order1=$order-2;
      $order2=$order-1;
   }
   else if($order%3==2)	//IT IS THE SECOND ONE ON THE PAGE
   {
	*/
   if($order%2==0)	//IT IS THE SECOND ONE ON THE PAGE
   {
      $order1=$order-1;
      //$order2=$order+1;
   }
   else                 //ELSE IT IS THE FIRST ONE ON THE PAGE
   {
      $order1=$order+1;
      //$order2=$order+2;
   }
   $sql="SELECT * FROM ppschool WHERE class='$class' AND programorder='$order1'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sid2=$row[sid]; $approved2=$row[approvedforprogram];
	/*
   $sql="SELECT * FROM ppschool WHERE class='$class' AND programorder='$order2'";
   $result2=mysql_query($sql);
   $row=mysql_fetch_array($result2);
   $sid3=$row[sid]; $approved3=$row[approvedforprogram];
	*/
   //IF $makepdf && NOT NSAA - CHECK THAT ALL SID's HAVE BEEN APPROVED FOR PROGRAM
   if($level!=1)
   {
      if(!$approved || !$approved2 || !$approved3)
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
   header("Location:programpdf.php?session=$session&sid1=$sid&sid2=$sid2&sid3=$sid3&viewdistrict=$viewdistrict");
   exit();
}

//CHECK IF THIS SCHOOL IS ON THE LIST OF SCHOOLS FOR THIS SPORT AS A HEAD SCHOOL (Co-op or not)
        //get schoolid
$sql="SELECT id FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schoolid=$row[id]; $sport="pp";
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

//get mascot, colors
$sql="SELECT mascot,color_names FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$mascot=$row[0]; $colors=$row[1];

//AS OF 5/11/10: JUST ONE ENTRY FORM, NO DUE DATE
$duedate=GetDueDate("pp");
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";
$state=0;
$table1="pp";
$table2="pp_students";

//Get info already submitted for this school
  //play info
$entered1=0;
  $sql="SELECT * FROM $table1 WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$sch_id=$row[1];
$title=$row[2];
$short_title=$row[14];
$playwright=$row[3];
$directorname=$row[4];
$time=split(":",$row[5]);
$hrs=$time[0];
$min=$time[1];
$contest_site=$row[7];
$adult=$row[adult];
$weapons=$row[weapons];
$permission=$row[permission];
$royalty=$row[royalty];
if(mysql_num_rows($result)>0) $entered1=1;

//get coach
$sql="SELECT name, asst_coaches FROM logins WHERE school='$school2' AND level=3 AND sport='Play Production'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$coach=$row[0];
$asst=$row[1];

$entered2=0;
  //students: cast
$sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM $table2 AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND t1.school='$school2' AND t1.part IS NOT NULL AND t1.part!='' ORDER BY t1.partorder"; 
$result=mysql_query($sql);
if(mysql_num_rows($result)>0) $entered2=1;
  //students: crew
$sql2="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM $table2 AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND t1.school='$school2' AND t1.crew='y' ORDER BY t2.last,t2.first";
$result2=mysql_query($sql2);
if(mysql_num_rows($result2)>0) $entered2=1;
if($entered1==0 || $entered2==0)
{
   if($director!=1)
   {
      header("Location:edit_pp.php?session=$session&school_ch=$school_ch");
   }
   else
      echo "$school has not completed an entry form.";
   exit();
}
echo $init_html;
$string=$init_html;
$csv="";
if($print!=1)
{
   echo GetHeader($session);
   if($level==1)
      echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Play Production\">Return to Home-->Play Production Entry Forms</a><br>";
}
$string.="<center><br>";

if($print!=1)	//non printer friendly
{
?>

<a href="view_pp.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>&print=1" class=small target=new>Printer-Friendly Version</a>
&nbsp;&nbsp;&nbsp;
<a href="edit_pp.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>" class=small>Edit this Form</a><br><br>
<?php
if($level==1)
{
   //NSAA ABILITY TO CHECK SCHOOL AS A STATE QUALIFIER
   if($statequal)
   {
      $sql3="UPDATE pp SET statequalifier='$statequalifier' WHERE school='$school2'";
      $result3=mysql_query($sql3);
      $sql3="UPDATE headers SET statepp='x' WHERE school='$school2'";
      $result3=mysql_query($sql3);
   }
   $sql3="SELECT statequalifier FROM pp WHERE school='$school2'";
   $result3=mysql_query($sql3);
   $row3=mysql_fetch_array($result3);
   echo "<form method=post action='view_pp.php'><input type=hidden name=session value='$session'><input type=hidden name=\"school_ch\" value=\"$school_ch\">";
   echo "<div class='alert' style='width:800px'><input type=checkbox name=\"statequalifier\" value=\"x\"";
   if($row3[statequalifier]=='x') echo " checked";
   echo "> Check HERE to mark this school as a STATE QUALIFIER and export their entry to the <a class=small href=\"/pp.php\" target=\"_blank\">NSAA Play Production page</a>.";
   echo "&nbsp;<input type=submit name=\"statequal\" value=\"Save Checkmark\"></div></form>";
}
$sql3="SELECT submitted FROM $table1 WHERE submitted!=''";
$result3=mysql_query($sql3);
if(mysql_num_rows($result3)>0)
{
   $row3=mysql_fetch_array($result3);
   echo "<font style=\"color:red\"><b>You submitted your STATE form on ".date("m/d/Y",$row3[0]).".<br>";
   echo "If you need to make another change, you may do so and submit this form again.<br>";
   echo "Otherwise, your last submission will be considered final.</b></font><br><br>";
}
} //end if print!=1
$info="";
$info.="<font size=2><b>PLAY PRODUCTION CONTEST ENTRY FORM</b></font>";
$info.="<table><!--Table of Tables-->";
$info.="<tr align=center>";
$info.="<td>";
$export="PLAY PRODUCTION CONTEST ENTRY FORM\r\n";

if(!$state)
{
   $info.="<div class='alert' style='width:500px;'>You do <b><u>NOT</b></u> need to email or otherwise send this form to the director of your Play Production contest. This form will be <b><i>automatically</b></i> sent to the director on the due date, so please make sure the information below is COMPLETE by <b><u>$duedate2</b></u>.</div>";
}
if(!$royalty)
{
   $info.="<div class='error'>ERROR: You must check Yes, No or N/A in answer to \"Royalty payments have been paid.\"</div>";
}
if(!$permission)
{
   $info.="<div class='error'>ERROR: You must check Yes or No in answer to \"The Playwright has granted our school permission to air this production....\"</div>";
}
$info.="<table><!--School & Play Info-->";
$info.="<tr align=left>";
$info.="<th>School/Mascot:</th>";

//check if special co-op mascot/colors/coach for this sport
$sql2="SELECT * FROM ppschool WHERE sid='$sid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
if($row2[mascot]!='') $mascot=$row2[mascot];
if($row2[colors]!='') $colors=$row2[colors];
if($row2[coach]!='') $coach=$row2[coach];
$class=$row2['class']; 
$filename=$row2[filename];
$schoolname=GetSchoolName($sid,'pp');
$info.="<td>$schoolname $mascot</td></tr>";
$csv.="School/Mascot:,$schoolname $mascot\r\n";
$export.="School/Mascot:\t$schoolname $mascot\r\n";
$statetxt="$schoolname\r\n";
$statestr="<b>".$statetxt."</b><br>";
$statestr=ereg_replace("\r\n","",$statestr);
$info.="<tr align=left><th>Colors:</th><td>$colors</td></tr>";
$export.="Colors:\t$colors\r\n";
$info.="<tr align=left>";
$info.="<th>Class:</th>";
$info.="<td>$class</td>";
$info.="</tr>";
$export.="Class:\t$class\r\n";
$info.="<tr align=left>";
$info.="<th>NSAA-Certified Coach:</th>";
$info.="<td>$coach</td></tr>";
$export.="NSAA-Certified Coach:\t$coach\r\n";
$info.="<tr align=left>";
$info.="<th>Assistant Coach(es):</th>";
$info.="<td>$asst</td></tr>";
$export.="Assistant Coach(es):\t$asst\r\n";
$info.="<tr align=left>";
$info.="<th>Title of Play:</th>";
if(!empty($short_title))
$info.="<td>$short_title</td>";
else
$info.="<td>$title</td>";
//$info.="<td>$title</td>";
$export.="Title of Play:\t$title\r\n";
$statestr.="<b>\"$title\"</b><br>";
$statetxt.="\"$title\"\r\n";
$info.="</tr>";
$info.="<tr align=left>";
$info.="<th>Written By:</th>";
$info.="<td>$playwright</td>";
$statestr.="Author:&nbsp;$playwright<br>";
$statetxt.="Author: $playwright\r\n";
$info.="</tr>";
$export.="Written By:\t$playwright\r\n";
$info.="<tr align=left>";
$info.="<th>Director:</th>";
$info.="<td>$directorname</td>";
$export.="Director:\t$directorname\r\n";
$statestr.="Director:&nbsp;&nbsp;$directorname<br>";
$statetxt.="Director:  $directorname\r\n";
$statestr.="Assistant Director:&nbsp;&nbsp;$asst<br>";
$statetxt.="Assistant Director:  $asst\r\n";
$info.="</tr>";
$eport.="Assistant Director:\t$asst\r\n";
$info.="<tr align=left>";
$info.="<th>Playing Time:</th>";
$info.="<td>$hrs<b>:</b>$min</td>";
$info.="</tr>";
$export.="Playing Time:\t$hrs:$min\r\n";
$info.="<tr align=left>";
$info.="<th>Contest Site:</th>";
$info.="<td>$contest_site</td>";
$info.="</tr>";
$info.="<tr align=left><th>Photo of Cast & Crew:</th></td>";
if($filename!='')
{
   $info.="<td><a href=\"/nsaaforms/downloads/$filename\" target=\"_blank\">Preview Photo</a></td>";
}
else
{
   $info.="<td><a href=\"edit_pp.php?session=$session&school_ch=$school_ch\">Click here to upload your photo</a></td>";
}
$info.="</tr>";
$export.="Contest Site:\t$contest_site\r\n";
if($adult=='x') {
   $info.="<tr align=left><th colspan=2>PLEASE NOTE: Production contains adult subject matter.</th></tr>";
   $export.="PLEASE NOTE:  Production contains adult subject matter\r\n"; 
   }
if($weapons=='x') {
   $info.="<tr align=left><th colspan=2>PLEASE NOTE: Our school utilizes simulated weapons in our production.</th></tr>";
   $export.="PLEASE NOTE:  Our school utilizes simulated weapons in our production\r\n";
   }

if(!$royalty) $royalty="<label style=\"color:red\"><b>???</label></b>";
if(!$permission) $permission="<label style=\"color:red\"><b>???</b></label>";

$info.="<tr align=left><th colspan=2><b>If applicable, royalty payments have been paid.</b> ".strtoupper($royalty)."</td></tr>";
$info.="<tr align=left><th colspan=2><b>The Playwright has granted our school permission to air this production via webcasting/television for educational purposes.</b> ".strtoupper($permission)."</td></tr>";
$csv.="Colors:,$colors\r\nClass:,$class\r\n";
$asst=ereg_replace(",","/",$asst);
$csv.="NSAA-Certified Coach:,$coach\r\nAsst Coach(es):,$asst\r\n";
$csv.="Title of Play:,$title\r\nWritten By:,$playwright\r\n";
$csv.="Director:,$directorname\r\nPlaying Time:,$hrs:$min\r\n";
$csv.="Contest Site:,$contest_site\r\n";
$info.="</table><!--End Play & School Info-->";
$info.="</td>";
$info.="</tr>";
$info.="<tr align=center>";
$info.="<td>";
$info.="<table cellspacing=0 cellpadding=4 frame=all rules=all style=\"border:#808080 1px solid;\">";
$info.="<!--Cast-->";
$info.="<caption><font size=2><b>Cast:</b></font></caption>";
$info.="<tr align=center>";
$info.="<th class=smaller>Character</th>";
$info.="<th class=smaller>Name</th>";
$info.="<th class=smaller>Grade</th>";
$info.="</tr>";
$csv.="\r\nCast:\r\nCharacter,Name,Grade\r\n";
$export.="\r\nCast:\r\nCharacter\tName\tGrade\r\n";
$statestr.="<table cellspacing=2 cellpadding=2 width='500px'><tr align=left><td width='150px'>Character</td><td>Name of Student</td></tr>";
$statetxt.="Character\tName of Student\r\n";

   while($row=mysql_fetch_array($result))
   {
      $info.="<tr align=left>";
      $info.="<td align=left>$row[2]</td>";
      $info.="<td align=left>$row[8], $row[9] $row[10]</td>";
      $year=GetYear($row[11]);
      $info.="<td align=center>$year</td>";
      $info.="</tr>";
      $csv.="$row[2],$row[9] $row[8],$year\r\n";
	  $export.="$row[2]\t$row[9] $row[8]\t$year\r\n";
      $statestr.="<tr align=left><td>$row[2]</td><td>$row[9] $row[8]</td></tr>";
      $statetxt.="$row[2]\t$row[9] $row[8]\r\n";
   }
   
$info.="</table><!--End Cast-->";
$info.="</td>";
$info.="</tr>";
$info.="<tr align=center>";
$info.="<td>";
$info.="<table cellspacing=0 cellpadding=4 frame=all rules=all style=\"border:#808080 1px solid;\">";
$info.="<!--Crew-->";
$info.="<caption><font size=2><b>Crew:</b></font></caption>";
$info.="<tr align=center>";
$info.="<th class=smaller>Name</th>";
$info.="<th class=smaller>Grade</th>";
$info.="</tr>";
$csv.="\r\nCrew:\r\nName,Grade\r\n";
$export.="\r\nCrew\r\nName\tGrade\r\n";
$statestr.="<tr align=left valign=top><td>Technical Crew:</td><td>";
$statetxt.="Technical Crew:\t";
$sql2="SELECT t1.id,t1.last,t1.first,t1.middle,t1.semesters,t1.eligible FROM eligibility AS t1, $table2 AS t2 WHERE t1.id=t2.student_id AND t2.crew='y' AND t2.school='$school2' ORDER BY t1.last";
$result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $info.="<tr align=center>";
      $info.="<td align=left>$row2[last], $row2[first]</td>";
      $year=GetYear($row2[semesters]);
      $info.="<td>$year</td>";
      $info.="</tr>";
      $csv.="$row2[first] $row2[last],$year\r\n";
	  $export.="$row2[first] $row2[last]\t$year\r\n";
      $statestr.="$row2[first] $row2[last], ";
      $statetxt.="$row2[first] $row2[last], ";
   }
$statestr=substr($statestr,0,strlen($statestr)-2);
$statetxt=substr($statetxt,0,strlen($statetxt)-2);
$statestr.="</td></tr></table>";
$info.="</table>";
$info.="</td>";
$info.="</tr>";

echo $info;
$string.=$info;

if($print!=1)	//non-printer friendly
{
?>

<tr align=center>
<td><br>
<a href="view_pp.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>&print=1" class=small target=new>Printer-Friendly Version</a>
&nbsp;&nbsp;&nbsp;
<a href="edit_pp.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>" class=small>Edit this Form</a>
&nbsp;&nbsp;&nbsp;
<a href="../welcome.php?session=<?php echo $session; ?>" class=small>Home</a>
</td>
</tr>
<?php
}//end if print!=1
   $string.="</table></td></tr></table></body></html>";
   $activ="Play Production";
   $activ_lower=strtolower($activ);
   $export = chr(255).chr(254).mb_convert_encoding( $export, 'UTF-16LE', 'UTF-8');
   $sch=ereg_replace(" ","",$school);
   $sch=ereg_replace("-","",$sch);
   $sch=ereg_replace("\.","",$sch);
   $sch=ereg_replace("\'","",$sch);
   $sch=strtolower($sch);
   $activ_lower=ereg_replace(" ","",$activ_lower);
   $filename="$sch$activ_lower";
   $fname=$filename.".xls";
   if($state==1) 
   {
	$filename.="state";
        $statestr="<html><head><style>body { font-family:arial;font-size:9pt; } table {font-family:arial;font-size:9pt; }</style><body>".$statestr.$end_html;
        $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.html"),"w");
        fwrite($open,$statestr);
        fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.html");
        $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.txt"),"w");   
        fwrite($open,$statetxt);   
        fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.txt");
		$open=fopen(citgf_fopen("/home/nsaahome/reports/$fname"),"w");   
        fwrite($open,$export);   
        fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$fname");
   }
   else
   {
   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.html"),"w");
   fwrite($open,$string);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.html");

   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.csv"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.csv");
   
   $open=fopen(citgf_fopen("/home/nsaahome/reports/$fname"),"w");
   fwrite($open,$export);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$fname");
   }

if($send==y)	//send state file to NSAA
{
   $From=GetEmail("main");
   $FromName=$stateassn;
   $To="callaway@nsaahome.org";
   $ToName="Cindy Callaway";
   $Subject="$school $activ State Tournament Roster";
   $Text="Attached is a CSV for Excel file of $school's $activ State Tournament Roster Information.  Thank you.";
   $Html="<font size=2 family=arial>Attached is a CSV-for-Excel file of $school's $activ State Tournament Roster Information.<br><br>They have approved this as their final submission.<br><br>Thank you!</font>";
   $AttmFiles=array("../../../attachments/$filename.html","../../../attachments/$filename.txt");

   SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles);
   SendMail($From,$FromName,"run7soccer@aim.com","Ann Gaffigan",$Subject,$Text,$Html,$AttmFiles);

   $today=time();
   $sql="UPDATE $table1 SET submitted='$today' WHERE school='$school2'";
   $result=mysql_query($sql);
}
?>
</table><!--End Table of Tables-->

</td>
</tr>
</table>
<a href="../exports.php?session=<?php echo $session; ?>&filename=<?php echo $fname; ?>">Download this Entry Form as an Excel File</a>

</body>
</html>
