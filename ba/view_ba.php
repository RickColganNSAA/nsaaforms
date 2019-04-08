<?php
//view_ba.php: Show submitted district
//   entry info.  If none have been submitted,
//   redirect to edit_ba.php

require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require $_SERVER['DOCUMENT_ROOT'].'/calculate/variables.php'; //Wildcard Variables

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) && !$makepdf)
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
   $sql="SELECT * FROM $db_name2.badistricts WHERE hostid='$hostid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      //CHECK badisttimes
      $sql="SELECT * FROM $db_name2.badisttimes WHERE hostid='$hostid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)
      {
         echo "You are not the host of this school's district.";
         exit();
      }
   }
}
$school2=ereg_replace("\'","\'",$school);
$sid=GetSID2($school,'ba');
$sport='ba';

if($makepdf)    //GET OTHER SCHOOL TO GO ON THIS PAGE
{
   $sql="SELECT programorder,class,approvedforprogram FROM baschool WHERE sid='$sid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $order=$row[programorder]; $class=$row['class'];
   $approved=$row[approvedforprogram];
   //GET OTHER SCHOOL ON SAME PAGE
   if($order%2==0)      //THIS SCHOOL IS THE SECOND ONE ON THE PAGE
      $order2=$order-1;
   else                 //ELSE IT IS THE FIRST ONE ON THE PAGE
      $order2=$order+1;
   $sql="SELECT * FROM baschool WHERE class='$class' AND programorder='$order2'";
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
   header("Location:programpdf.php?session=$session&sid1=$sid&sid2=$sid2&viewdistrict=$viewdistrict");
   exit();
}

//CHECK IF THIS SCHOOL IS ON THE LIST OF SCHOOLS FOR THIS SPORT AS A HEAD SCHOOL (Co-op or not)
	//get schoolid
$sql="SELECT id FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schoolid=$row[id]; $sport="ba";
if(!IsHeadSchool($schoolid,$sport) && !GetCoopHeadSchool($schoolid,$sport) && $school!="Test's School")	//NOT a $sport school at all
{
   echo $init_html;
   echo GetHeader($session);
   echo "<br><br><br><div class='alert' style='width:400px;'><b>$school</b> is not listed as a ".GetActivityName($sport)." school.<br><br>If you believe this is an error, please contact the NSAA office.</div><br><br><br>";
   echo $end_html;
   exit();
}
else if(!IsHeadSchool($schoolid,$sport) && $school!="Test's School")	//in a Co-op, not the head school
{
   echo $init_html;
   echo GetHeader($session);
   $mainsch=GetCoopHeadSchool($schoolid,$sport);
   echo "<br><br><br><div class='alert' style='width:400px'><b>$school</b> is in a co-op with <b>$mainsch</b> for ".GetActivityName($sport).".<br><br>Only the head school of the co-op can fill out this entry form.  <b>$mainsch</b> is listed as the head school for this co-op.<br><br>If you believe this is an error, please contact the NSAA office.</div><br><br><br>";
   echo $end_html;
   exit();
}

//check if this is state form or district form
$duedate=GetDueDate("ba");
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";
if($director!=1 && PastDue($duedate,8))       //state form
{
   $state=1;
   $table="ba_state";
   $form_type="STATE";
}
else
{
   $state=0;
   $table="ba";
   $form_type="DISTRICT";
}

//get class/dist submitted for this team
$sql="SELECT class_dist FROM $table WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class_dist=$row[0];

//check if this form has already been submitted:
$sql="SELECT * FROM $table WHERE school='$school2' OR co_op='$school2'";
//echo $sql;
$result=mysql_query($sql); 
  //if it hasn't been submitted, redirect to Edit page:
if(mysql_num_rows($result)==0)
{
   if($director!=1)
      header("Location:edit_ba.php?session=$session&school_ch=$school_ch");
   else
      echo "$school has not completed an entry form.";
   exit();
}
  //if it has been submitted, show submitted info:
  $string=$init_html;
  $csv="";
  if(!$makepdf) echo $init_html;

if($print!=1 && !$makepdf)
{
   $header=GetHeader($session);
   echo $header;

   if($level==1)
      echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Baseball\">Return to Home-->Baseball Entry Forms</a><br>";
}

//get information about school and coach:
$sql2="SELECT * FROM headers WHERE school='$school2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$colors=$row2[5];
$mascot=$row2[6];
$conference=trim($row2[conference]);
$enrollment=trim($row2[enrollment]);

      $sql_coop="SELECT * FROM baschool WHERE mainsch='$row2[id]' AND (othersch1!='' OR othersch2!='' OR othersch3!='') ";
      $result_coop=mysql_query($sql_coop);
      $row_coop=mysql_fetch_array($result_coop);
	  if (!empty($row_coop[mainsch])) $coop_info[]=$row_coop[mainsch];
	  if (!empty($row_coop[othersch1])) $coop_info[]=$row_coop[othersch1];
	  if (!empty($row_coop[othersch2])) $coop_info[]=$row_coop[othersch2];
	  if (!empty($row_coop[othersch3])) $coop_info[]=$row_coop[othersch3];
	  //echo '<pre>'; print_r($coop_info); 
	  //$enroll=0;
	  foreach ($coop_info as $info)
	  {
	  $sql_school="SELECT * FROM headers WHERE id='$info'";
      $result_school=mysql_query($sql_school);
      $row_school=mysql_fetch_array($result_school);
	  
	  $sql="SELECT name FROM logins WHERE school='$row_school[school]' AND sport='Superintendent'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
	  $super[] = $row[name];
	  
	  $sql="SELECT id,name FROM logins WHERE school='$row_school[school]' AND sport='Principal'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
	  $prin[] = $row[name];
	  
	  $sql="SELECT id,name FROM logins WHERE school='$row_school[school]' AND level='2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
	  $ad[]= $row[name];
	  
	  $sql="SELECT * FROM headers WHERE school='$row_school[school]' ";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
	  $enroll=$enroll+$row[enrollment];
	  
	  }
	  
	  $super=implode(", ",$super);
	  $prin=implode(", ",$prin);
	  $ad=implode(", ",$ad);
//$db=mysql_connect("$db_host","root","nsaahome");
//mysql_select_db("$db_name20042005", $db);
$sql2="SELECT name,asst_coaches FROM logins WHERE school='$school2' AND sport='Baseball'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$coach=$row2[0]; $asst=$row2[1];

$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//get team record from table:
$record=GetWinLoss($sid,'ba'); 

$sql="SELECT team_record,class_dist FROM $table WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
 $record=$row[0];


if($print!=1 && !$makepdf)
{
echo "<a href=\"view_ba.php?session=$session&school_ch=$school_ch&print=1\" target=new class=small>Printer/E-mail Friendly Version</a>&nbsp;&nbsp;&nbsp;";
echo "<a href=\"edit_ba.php?session=$session&school_ch=$school_ch\" class=small>Edit this Form</a><br><br>";
$sql2="SELECT submitted FROM $table WHERE submitted!='' AND school='$school2'";
$result2=mysql_query($sql2);
if(mysql_num_rows($result2)>0)
{
   $row2=mysql_fetch_array($result2);
   echo "<font style=\"color:red\"><b>You submitted your STATE form on ".date("m/d/Y",$row2[0]).".<br>";
   echo "If you need to make another change, you may do so and submit this form again.<br>";
   echo "Otherwise, your last submission will be considered final.</b></font><br><br>";
}
}
$info="<table>";
$info.="<tr align=center>";
$info.="<th>BASEBALL ENTRY & STATISTICS FORM</th>";
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
$info.="<tr align=left><td>";
$info.="<table cellspacing=2 cellpadding=2><!--Show school, coach, etc.-->";
$info.="<tr align=left><th>School/Mascot:</th>";
//check if special co-op mascot/colors/coach for this sport
$sql="SELECT * FROM baschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[mascot]!='') $mascot=$row[mascot];
if($row[colors]!='') $colors=$row[colors];
if($row[coach]!='') $coach=$row[coach];
$schoolname=GetSchoolName($sid,'ba');
if($makepdf)
{
   //include PDF creation tool:
   require_once('../../tcpdf/tcpdf.php');

   //INITIALIZE PDF
   $pdf = new TCPDF("P", PDF_UNIT, PDF_PAGE_FORMAT, true);
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   $pdf->SetMargins(0,0);
   $pdf->SetAutoPageBreak(FALSE, 1);
   $pdf->setLanguageArray($l);
   $pdf->AddPage();
}
$info.="<td>".GetSchoolName($sid,'ba')." $mascot</td></tr>";
$csv.="School/Mascot: ".GetSchoolName($sid,'ba')." $mascot\r\n";
$info.="<tr align=left><th>School Colors:</th>";
$info.="<td>$colors</td></tr>";
$info.="<tr align=left><th>Coach:</th>";
$info.="<td>$coach</td></tr>";
$info.="<tr align=left><th>Assistant Coaches:</th>";
$info.="<td>$asst</td></tr>";
$sql="SELECT class,filename FROM baschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$info.="<tr align=left><th>Class:</th>";
$info.="<td>$row[class]</td></tr>";
$info.="<tr align=left><th>Team Record:</th>";
$info.="<td>$record</td></tr>";
$teamphoto=$row[filename];
$info.="<tr align=left><th>Team Photo:</th>";
if(mysql_num_rows($result) && citgf_file_exists("../downloads/".$row[filename]) && $row[filename]!='')
{
   $info.="<td><a href=\"/nsaaforms/downloads/$row[filename]\" target=\"_blank\">Preview Team Photo</a></td></tr>";
}
else
{
   $info.="<td>&nbsp;</td></tr>";
}
if($level==1)
{
        //Superintendent
      $sql="SELECT name FROM logins WHERE school='$school2' AND sport='Superintendent'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $info.="<tr align=\"left\"><th>Superintendent:</b></th><td>$row[name]</td></tr>";
        //Principal
      $sql="SELECT name FROM logins WHERE school='$school2' AND sport='Principal'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $info.="<tr align=\"left\"><th>Principal:</th><td>$row[name]</td></tr>";
        //AD
      $sql="SELECT name FROM logins WHERE school='$school2' AND level='2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $info.="<tr align=\"left\"><th>Athletic Director:</th><td>$row[name]</td></tr>";
        //Enrollment
      $info.="<tr align=\"left\"><th>NSAA Enrollment:</th><td>$enroll</td></tr>";
      $sql="SELECT * FROM baschool WHERE sid='$sid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
        //Trips to State: 4
      $info.="<tr align=\"left\"><th>Trips to State:</th><td>$row[tripstostate]</td></tr>";
        //Most Recent: 2012
      $info.="<tr align=\"left\"><th>Most Recent:</th><td>$row[mostrecent]</td></tr>";
        //Championships: None
      $info.="<tr align=\"left\"><th>Championships:</th><td>$row[championships]</td></tr>";
        //Runner-up: B/2008, B/2010
      $info.="<tr align=\"left\"><th>Runner-up:</th><td>$row[runnerup]</td></tr>";
	//GENERATE PDF
      $info.="<tr align=left><td colspan=2>
	<form method=\"post\" action=\"view_ba.php\" target=\"_blank\">
	<input type=hidden name=\"session\" value=\"$session\">
	<input type=hidden name=\"school_ch\" value=\"$school\">
	<div id=\"pdflink\" style=\"margin:10px;\"></div><input type=submit name=\"makepdf\" value=\"Generate Program Page (PDF)\">
	</form></td></tr>";
} //END IF LEVEL 1
$info.="</table>";
if($makepdf)
{
   $pdf->SetFont("berthold","B","20");
   $pdf->SetFillColor(0,0,0);
   $pdf->SetTextColor(255,255,255);
   $pdf->writeHTMLCell("210","10",0,5,strtoupper("$schoolname $mascot"),0,0,1,true,"L");
   $pdf->SetFont("berthold","","16");
   $pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0,0,0);
   $pdf->writeHTMLCell("210","8",0,16,"$colors ($record)",0,0,1,true,"L");
   //We want the image W/H ratio to be 190/131
   $iratio=190/131;
   $iwidth=155;
   $iheight=floor($iwidth/$iratio);
   $x=(210-$iwidth)/2;
   $pdf->Image("../downloads/$teamphoto",$x,25,$iwidth,$iheight,'','','',false,72,'',false,false,0,false,false,true);
}
$csv.="School Colors:,$colors\r\n";
$csv.="Coach:,$coach\r\n";
$csv.="Class:,$class_dist\r\n";
$csv.="Team Record:,$record\r\n";
$info.="</td></tr>";
$info.="<tr align=center>";
$info.="<td><br>";
$info.="<table cellpadding=5 cellspacing=0 style=\"border:#808080 1px solid;\" frame=all rules=all>";
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
$info.="<th class=smaller>Pitching<br>ERA</th></tr>";
if($makepdf)
{
   $html="<table cellspacing=\"0\" cellpadding=\"1\"><tr align=\"center\">
	<td><b>No.</b></td><td width=\"80\"><b>Name</b></td><td><b>Grd.</b></td><td><b>Pos.</b></td><td><b>Bat.<br />Avg.</b></td><td><b>At<br />Bats</b></td><td><b>Hits</b></td><td><b>Runs<br />Scored</b></td><td><b>RBI</b></td><td><b>HR</b></td><td><b>Pitch.<br />Record</b></td><td><b>Pitch.<br />ERA</b></td></tr>";
}

$csv.="Light Jersey No.,Dark Jersey No.,Name,Grade,Position,Average,At Bats,Hits,Runs Scored,Runs Batted In,Home Runs,Pitching Wins,Pitching Losses,Pitching Saves,Pitching ERA\r\n";

$sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$school2' OR t1.co_op='$school2') AND t1.checked='y' ORDER BY CAST(t1.jersey_lt AS DECIMAL), CAST(t1.jersey_dk AS DECIMAL)";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
  if($row[3]=="y")	//that student was checked to be on the roster
  {
     $info.="<tr align=left>";
     $last=$row[last];
   if($row[nickname]!='') $first=$row[nickname];
     else $first=$row[first];
     $mid=$row[middle];
     $info.="<td>$last, $first $mid</td>";
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
     $info.="<td>$row[14]</td>";
     $info.="<td>$row[15]</td>";
     $info.="</tr>";
     $pitch=split("-",$row[14]);
     $csv.="$row[5],$row[6],$first $last,$year,$row[7],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13],$pitch[0],$pitch[1],$pitch[2],$row[15]\r\n";
      $html.="<tr align=\"center\"><td>$row[5]&nbsp;</td><td width=\"80\" align=\"left\">$first $last</td><td>$year</td><td>$row[7]</td><td>$row[8]</td><td>$row[9]</td><td>$row[10]</td><td>$row[11]</td><td>$row[12]</td><td>$row[13]</td><td>$row[14]</td><td>$row[15]</td></tr>";
  }
}

$info.="</table></td></tr>";
if(!$makepdf) echo $info;
$string.=$info;
if($makepdf)
{
   $y=135; $x=0;
   $pdf->SetFont("berthold","",8);
   $html.="</table>";
   $pdf->SetTextColor(0,0,0);
   $pdf->writeHTMLCell("200","",$x,$y,$html,0,0,1,true,"C");
   $pdf->SetX(10); $pdf->SetY($y);
   //$pdf->writeHTML($html,true,false,false,false,'');
}

if($makepdf) $secret=1; 	//TESTING

//ADD SEASON GAMES AND SCORES TO CSV FILE (state form only)
if($state==1 || $secret==1)
{
   $sid=GetSID2($school,'ba');
   $year=GetFallYear('ba');
   
   $csv.="\r\nGames:\r\n\"Opponent\",\"W/L\",\"Score\",\"Opp.Score\",\"Extra\"\r\n";
   $sched=GetSchedule($sid,'ba',$year,FALSE,TRUE);
   $html1="<table cellspacing=\"0\" cellpadding=\"1\">";	//1st half of season
   $html2="<table cellspacing=\"0\" cellpadding=\"1\">";	//2nd half of season
   $gamect=0;
   for($i=0;$i<count($sched[oppid]);$i++)
   {
      if($sched[oppid][$i]!='0')        //only individual games, not tournament names
         $gamect++;
   }
   if($gamect%2==0) $percol=$gamect/2;
   else $percol=ceil($gamect/2);
   $curcol=0;
   for($i=0;$i<count($sched[oppid]);$i++)
   {
      if($sched[oppid][$i]!='0')        //only individual games, not tournament names
      {
         $csv.="\"".GetSchoolName($sched[oppid][$i],'ba')."\",";
         $score=split("-",$sched[score][$i]);
         if($score[0]>$score[1]) $csv.="\"W\",";
	 else $csv.="\"L\",";
         $csv.="\"$score[0]\",\"$score[1]\",\"".$sched[extra][$i]."\"\r\n";
         $html="<tr valign=\"bottom\" align=\"left\"><td width=\"110\">".ConfigureSchoolForProgramSchedule(GetSchoolName($sched[oppid][$i],'ba'))."</td>";
         if($score[0]>$score[1]) $html.="<td align=\"center\" width=\"25\">W</td>";
         else $html.="<td align=\"center\" width=\"25\">L</td>";
         $html.="<td align=\"center\" width=\"30\">$score[0]-$score[1]";
         //if(trim($sched[extra][$i])!='') $html.=" (".$sched[extra][$i].")";
         $html.="</td></tr>";
	 $curcol++;
	 if($curcol<=$percol) $html1.=$html;
	 else $html2.=$html;
      }
   }
   if($makepdf)
   {
      $html1.="</table>"; $html2.="</table>";
      $html="<table cellspacing=\"0\" cellpadding=\"0\"><tr align=\"left\"><td width=\"170\">$html1</td><td width=\"170\">$html2</td></tr></table>"; 
      $y=232; $x=85;
      $pdf->SetFont("berthold","B",10);
      $pdf->writeHTMLCell("","",$x,$y,"Season Record $record",0,0,1,true,"L");
      $y+=5;
      $pdf->SetFont("berthold","",8);
      $pdf->writeHTMLCell("","",$x,$y,$html,0,0,1,true,"C");
      //NOW ADD school and historical info:
      $html="<table cellspacing=\"0\" cellpadding=\"2\">";
	//Superintendent
      $sql="SELECT name FROM logins WHERE school='$school2' AND sport='Superintendent'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $html.="<tr align=\"left\"><td><b>Superintendent:</b> $row[name]</td></tr>";
	//Principal
      $sql="SELECT name FROM logins WHERE school='$school2' AND sport='Principal'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $html.="<tr align=\"left\"><td><b>Principal:</b> $row[name]</td></tr>";
	//AD
      $sql="SELECT name FROM logins WHERE school='$school2' AND level='2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $html.="<tr align=\"left\"><td><b>Athletic Director:</b> $row[name]</td></tr>";
	//Head Coach
      $html.="<tr align=\"left\"><td><b>Head Coach:</b> $coach</td></tr>";
	//Assistants
      $html.="<tr align=\"left\"><td><b>Assistant Coaches:</b> $asst</td></tr>";
	//Enrollment
      $html.="<tr align=\"left\"><td><b>NSAA Enrollment:</b> $enrollment</td></tr>";
      $sql="SELECT * FROM baschool WHERE sid='$sid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
	//Trips to State: 4
      $html.="<tr align=\"left\"><td><b>Trips to State:</b> $row[tripstostate]</td></tr>";
	//Most Recent: 2012
      $html.="<tr align=\"left\"><td><b>Most Recent:</b> $row[mostrecent]</td></tr>";
	//Championships: None
      $html.="<tr align=\"left\"><td><b>Championships:</b> $row[championships]</td></tr>";
	//Runner-up: B/2008, B/2010
      $html.="<tr align=\"left\"><td><b>Runner-up:</b> $row[runnerup]</td></tr>";
      $html.="</table>";
      $width=$x-10;
      $x=0;  $y-=5;
      $pdf->writeHTMLCell($width,"",$x,$y,$html,0,0,1,true,"C");
      $pdffilename=preg_replace("/[^0-9a-zA-Z]/","",$schoolname)."_".strtoupper($sport).".pdf";
      $pdf->Output("../downloads/$pdffilename", "I");
      $pdflink="<a href=\"../downloads/$pdffilename\" target=\"_blank\" class=\"small\">Preview PDF</a>";
   }

   //Add table for history information
   $csv.="\r\nHead Coach:,$coach\r\n";
   $csv.="Assistant Coaches:,\"$asst\"\r\n";
   $csv.="\r\nHistory:\r\n";
   $csv.="NSAA Enrollment:,$enrollment\r\n";
   $csv.="Conference:,$conference\r\n";
   $sql="SELECT * FROM baschool WHERE sid='$sid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   //add coaches,etc info to excel file
   $csv.="State Tournament Appearances:,\"$row[tripstostate]\"\r\n";
   $csv.="Most Recent State Tournament:,\"$row[mostrecent]\"\r\n";
   $csv.="State Championship Years:,\"$row[championships]\"\r\n";
   $csv.="State Runner-Up Years:,\"$row[runnerup]\"\r\n";
}//end if state=1

if($print!=1 && !$makepdf)
{
?>
<tr align=center>
<td><br>
    <a href="view_ba.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>&print=1" target=new class=small>Printer/E-mail Friendly Version</a>
    &nbsp;&nbsp;&nbsp;
    <a href="edit_ba.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>" class=small>Edit this Form</a>
    &nbsp;&nbsp;&nbsp;
    <a href="../welcome.php?session=<?php echo $session; ?>" class=small>Home</a>
</td>
</tr>
<?php
}//end if print!=1
   //Allow user to e-mail form
   $string.="</table></td></tr></table></body></html>";
   $activ="Baseball";
   $activ_lower=strtolower($activ);

   $sch=ereg_replace(" ","",$school);
   $sch=ereg_replace("-","",$sch);
   $sch=ereg_replace("\.","",$sch);
   $sch=ereg_replace("\'","",$sch);
   $sch=strtolower($sch);
   $activ_lower=ereg_replace(" ","",$activ_lower);
   $filename="$sch$activ_lower";
   if($state==1 || $secret==1)
      $filename.="state";
   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.html"),"w");
   fwrite($open,$string);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.html");

   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.csv"),"w");
   if(!fwrite($open,$csv)) echo "Could not write $filename.csv";
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
<tr align=left><td><b>
Your e-mail address:</b></td>
<td><input type=text name=reply size=30></td>
</tr>
<tr align=left><td><b>
Recipient(s)' address(es):</b></td>
<td><textarea name=email cols=50 rows=5 class=email><?php echo $recipients; ?></textarea>
<?php
//echo "<input type=button name=addressbook value=\"Address Book\" onclick=\"window.open('../addressbook.php?session=$session&school_ch=$school2&form=ba','addressbook','menubar=no,location=no,resizable=no,scrollbars=yes,width=500,height=600')\">";
?>
</td>
</tr>
<tr align=center><td colspan=2>
<input type=submit name=submit value="Send">
</td></tr>
</table>
<font style="font-size:8pt"><?php echo $email_note; ?></font>
</form>
</th></tr>
<?php
}  //end if print=1
if($send=='y')  //if box checked at bottom of edit screen, send to State Assn
{
   $From=$from_email;
   $FromName=$stateassn;
   $To="jangele@nsaahome.org";
   $ToName=$stateassn;
   $Subject="$school $activ State Tournament Roster";
   $Text="Attached is a CSV for Excel file of $school's $activ State Tournament Roster Information.  Thank you.";
   $Html="<font size=2 family=arial>Attached is a CSV-for-Excel file of $school'
s $activ State Tournament Roster information.<br><br>They have approved this as
their final submission.<br><br>Thank you!</font>";
   $AttmFiles=array("/home/nsaahome/attachments/$filename.csv");

   //SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles);
   //SendMail($From,$FromName,"run7soccer@aol.com",$ToName,$Subject,$Text,$Html,$AttmFiles);

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
