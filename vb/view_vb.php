<?php
//view_vb.php: Show submitted district
//   entry info.  If none have been submitted,
//   redirect to edit_vb.php
/*************************
July/August 2013:
Added dynamic creation of PDF for Printer.
This is the 2-schools-per-page version.
**************************/

require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/variables.php'; //Wildcard Variables
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

$level=GetLevel($session);

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

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
   $sql="SELECT * FROM $db_name2.vbdistricts WHERE hostid='$hostid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      echo "You are not the host of this school's district.";
      exit();
   }
}
$school2=ereg_replace("\'","\'",$school);
$sid=GetSID2($school,'vb');
$sport='vb';

if($makepdf)    //GET OTHER SCHOOL TO GO ON THIS PAGE
{
   //$sql="USE nsaascores20122013";       //TESTING
   //$result=mysql_query($sql);
   $sql="SELECT programorder,class,approvedforprogram FROM vbschool WHERE sid='$sid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $order=$row[programorder]; $class=$row['class'];
   $approved=$row[approvedforprogram];
   //GET OTHER SCHOOL ON SAME PAGE
   if($order%2==0)      //THIS SCHOOL IS THE SECOND ONE ON THE PAGE
      $order2=$order-1;
   else                 //ELSE IT IS THE FIRST ONE ON THE PAGE
      $order2=$order+1;
   $sql="SELECT * FROM vbschool WHERE class='$class' AND programorder='$order2'";
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
$sql="SELECT * FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schoolid=$row[id]; 
$conference=$row[conference]; $enrollment=$row[enrollment];
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
$duedate=GetDueDate("vb");
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";
if(PastDue($duedate,8) && $director!=1)	//state form
{
   $state=1;
   $table="vb_state";
   $form_type="STATE";
}
else
{
   $state=0;
   $table="vb";
   $form_type="DISTRICT";
}

if($makepdf) 
{
   $table="vb_state";
   $form_type="STATE";
}

//get class/dist for this team
$sql="SELECT class_dist FROM $table WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class_dist=$row[0];

//check if this form has already been submitted:
$sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$school2' OR t1.co_op='$school2') AND t1.checked='y' ORDER BY CAST(t1.jersey_lt AS DECIMAL), t1.libero";
$result=mysql_query($sql); 
  //if it hasn't been submitted, redirect to Edit page:
if(mysql_num_rows($result)==0)
{
   if($director!=1)
      header("Location:edit_vb.php?session=$session&school_ch=$school_ch");
   else
      echo "$school has not completed an entry form.";
   exit();
}
  //if it has been submitted, show submitted info:
if(!$makepdf) echo $init_html;

$string="";	//Begin writing files to be e-mailed to dist dir
$string.=$init_html;
$csv="";

if($print!=1 && !$makepdf)
{
   $header=GetHeader($session);
   echo $header;

   if($level==1)
      echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Volleyball#3\">Return to Home-->Volleyball Entry Forms</a><br>";
}

//get information about school and coach:
$sql2="SELECT * FROM headers WHERE school='$school2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$colors=$row2[color_names];
$mascot=$row2[mascot];
$schid=$row[id];
$sql2="SELECT name, asst_coaches FROM logins WHERE school='$school2' AND sport='Volleyball'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$coach=$row2[0];
$asst=$row2[1];

$record=GetWinLoss($sid,$sport);

if($makepdf)
{
   //include PDF creation tool:
   require_once('../../tcpdf_php4/config/lang/eng.php');
   require_once('../../tcpdf_php4/tcpdf.php');

   //INITIALIZE PDF
   $pdf = new TCPDF("P", PDF_UNIT, "LETTER", true); //LETTER = 8.5 x 11 in or 216 x 280 mm
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   $pdf->SetMargins(0,0);
   $pdf->SetAutoPageBreak(FALSE, 1);
   $pdf->setLanguageArray($l);
   $pdf->AliasNbPages();
   $pdf->AddPage();
}

if($print!=1)
{
    $string.="<br><a href=\"view_vb.php?session=$session&school_ch=$school_ch&print=1\" class=small target=new>Printer-Friendly Version</a>&nbsp;&nbsp;&nbsp;&nbsp;";
    $string.="<a href=\"edit_vb.php?session=$session&school_ch=$school_ch\" class=small>Edit this Form</a><br><br>";
/*
$sql2="SELECT submitted FROM $table WHERE submitted!='' AND school='$school2'";
$result2=mysql_query($sql2);
if(mysql_num_rows($result2)>0)
{
   $row2=mysql_fetch_array($result2);
   echo "<font style=\"color:red\"><b>You submitted your STATE form on ".date("m/d/Y",$row2[0]).".<br><br>";
   echo "If you need to make another change, you may do so by clicking <a class=small href=\"edit_vb.php?session=$session\">Edit This Form</a>, making the changes, <u>checking the box at the bottom</u> of the page, and submitting this form again.<br><br>";
   echo "Otherwise, your last submission will be considered final.</b></font>";
   echo "<br>";
}
*/
}
$string.="<table><tr align=center><th>VOLLEYBALL $form_type ENTRY</th></tr>";
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
$string.="<tr align=left><td>";
$string.="<table cellspacing=2 cellpadding=2>";
$string.="<tr align=left><th align=left>School/Mascot:</th>";
//check if special co-op mascot/colors/coach for this sport
$sql2="SELECT * FROM vbschool WHERE sid='$sid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
if($row2[mascot]!='') $mascot=$row2[mascot];
if($row2[colors]!='') $colors=$row2[colors];
if($row2[coach]!='') $coach=$row2[coach];
$string.="<td>".GetSchoolName($sid,'vb')." $mascot</td></tr>";
$csv.="School/Mascot:,".GetSchoolName($sid,'vb')." $mascot\r\n";
$string.="<tr align=left><th align=left>School Colors:</th><td>$colors</td></tr>";
$string.="<tr align=left><th align=left>$stateassn-Certified Coach:</th><td>$coach</td></tr>";
$string.="<tr align=left><th align=left>Assistant Coaches:</th><td>$asst</td></tr>";
$string.="<tr align=left><th align=left>Class:</th><td>$row2[class]</td></tr>";
$string.="<tr align=left><th align=left>Team Record:</th><td>$record</td></tr>";
//TEAM PHOTO
$sql2="SELECT filename FROM vbschool WHERE sid='$sid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$teamphoto=$row2[filename];
$string.="<tr align=left><th>Team Photo:</th>";
if(mysql_num_rows($result2) && citgf_file_exists("../downloads/".$row2[filename]) && $row2[filename]!='')
{
   $string.="<td><a href=\"/nsaaforms/downloads/$row2[filename]\" target=\"_blank\">Preview Team Photo</a></td></tr>";
}
else
{
   $string.="<td>&nbsp;</td></tr>";
}
if($makepdf)
{
   $pdf->SetFont("berthold","B","20");
   $pdf->SetFillColor(0,0,0);
   $pdf->SetTextColor(255,255,255);
   $schoolname=GetSchoolName($sid,$sport);
   $pdf->writeHTMLCell("216","10",0,5,strtoupper("$schoolname $mascot"),0,0,1,true,"L");
   $pdf->SetFont("berthold","","16");
   $pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0,0,0);
   $pdf->writeHTMLCell("216","8",0,16,"$colors ($record)",0,0,1,true,"L");

   //TEAM PHOTO:
   $maxwidth=216;
   $maxheight=144;
   list($pixw, $pixh) = getimagesize(getbucketurl("../downloads/".$teamphoto));
   $ratio=$pixw/$pixh;
   $width=$maxwidth;	//IDEAL
   $height=$width/$ratio;
   if($height>$maxheight)
   {
      $height=$maxheight;
      $width=$height*$ratio;
   }
   $x=(216-$width)/2;
   $y=25+(($maxheight-$height)/2);
   if(citgf_file_exists("../downloads/".$teamphoto))
      $pdf->Image("../downloads/".$teamphoto,$x,$y,$width,'','','','',false,72,'',false,false,0,false,false,true);
   $teamphotoheight=$maxheight;
}
if($level==1)
{
        //Superintendent
      $sql2="SELECT name FROM logins WHERE school='$school2' AND sport='Superintendent'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $string.="<tr align=\"left\"><th>Superintendent:</b></th><td>$row2[name]</td></tr>";
        //Principal
      $sql2="SELECT name FROM logins WHERE school='$school2' AND sport='Principal'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $string.="<tr align=\"left\"><th>Principal:</th><td>$row2[name]</td></tr>";
        //AD
      $sql2="SELECT name FROM logins WHERE school='$school2' AND level='2'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $string.="<tr align=\"left\"><th>Athletic Director:</th><td>$row2[name]</td></tr>";
        //Enrollment
      $string.="<tr align=\"left\"><th>NSAA Enrollment:</th><td>$enrollment</td></tr>";
      $sql2="SELECT * FROM vbschool WHERE sid='$sid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
        //Trips to State: 4
      $string.="<tr align=\"left\"><th>Trips to State:</th><td>$row2[tripstostate]</td></tr>";
        //Most Recent: 2012
      $string.="<tr align=\"left\"><th>Most Recent:</th><td>$row2[mostrecent]</td></tr>";
        //Championships: None
      $string.="<tr align=\"left\"><th>Championships:</th><td>$row2[championships]</td></tr>";
        //Runner-up: B/2008, B/2010
      $string.="<tr align=\"left\"><th>Runner-up:</th><td>$row2[runnerup]</td></tr>";
        //GENERATE PDF
      if($state==1)
      {
         $string.="<tr align=left><td colspan=2>
        <form method=\"post\" action=\"view_vb.php\" target=\"_blank\">
        <input type=hidden name=\"session\" value=\"$session\">
        <input type=hidden name=\"school_ch\" value=\"$school\">
        <div id=\"pdflink\" style=\"margin:10px;\"></div><input type=submit name=\"makepdf\" value=\"Preview State Program Page (PDF)\">
        </form></td></tr>";
      }
} //END IF LEVEL 1
$string.="</table></td></tr>";
$csv.="School Colors:,$colors\r\n";
$csv.="Class:,$class_dist\r\n";
$csv.="Team Record:,$record\r\n";
$string.="<tr align=center><td><br>";
$string.="<table width='100%' cellpadding=5 cellspacing=0 class='nine' frame=all rules=all style='border:#808080 1px solid;'>";
$string.="<tr align=center><th class=smaller>Name</th>";
$string.="<th class=smaller>Grade</th><th class=smaller>Position</th>";
$string.="<th class=smaller>Light<br>Jersey<br>No.</th>";
$string.="<th class=smaller>Dark OR<br>Libero<br>Jersey No.</th>";
$string.="<th class=smaller>Height</th>";
$string.="<th class=smaller>Digs</th>";
$string.="<th class=smaller>Serve<br>Receptions</th>";
$string.="<th class=smaller>Ace<br>Serves</th>";
$string.="<th class=smaller>Solo<br>Blocks</th>";
$string.="<th class=smaller>Kills</th><th class=smaller>Assists</th>";
echo "</tr>";
if($makepdf)
{
   $smallw=35;
   $html="<table cellspacing=\"0\" cellpadding=\"1\"><tr align=\"center\">
        <td width=\"20\"><b>No.</b></td><td width=\"85\"><b>Name</b></td><td width=\"$smallw\"><b>Grd.</b></td><td width=\"$smallw\"><b>Ht.</b></td><td width=\"$smallw\"><b>Pos.</b></td>
   <td width=\"$smallw\"><b>Digs</b></td><td width=\"$smallw\"><b>Rec.</b></td><td width=\"$smallw\"><b>Aces</b></td><td width=\"$smallw\"><b>Blocks</b></td><td width=\"$smallw\"><b>Kills</b></td><td width=\"$smallw\"><b>Assists</b></td>";
   $html.="</tr>";
}
$csv.="Light Jersey #,Dark OR Libero Jersey #,Name,Grade,Height,Position,Digs,Serve Receptions,Ace Serves,Ace Blocks,Kills,Assists\r\n";

$count=0;
while($row=mysql_fetch_array($result))
{
  if($row[7]=="y")	//that student was checked to be on the roster
  {
     $string.="<tr align=left>";
     $last=$row[last];
      if($row[nickname]!='') $first=$row[nickname];
     else $first=$row[first];
     $string.="<td>$first $last";
     $string.="</td>";
     $year=GetYear($row[semesters]);
     $string.="<td>$year</td>";
     $string.="<td>$row[14]</td>";
     $string.="<td>$row[3]</td>";
     //if($row[4]=='0') $row[4]="";
     $string.="<td>$row[4]</td>";
     $string.="<td>$row[5]</td>";
     $string.="<td>$row[8]</td>";
     $string.="<td>$row[9]</td>";
     $string.="<td>$row[10]</td>";
     $string.="<td>$row[11]</td>";
     $string.="<td>$row[12]</td>";
     $string.="<td>$row[13]</td>";
     $string.="</tr>";
     if(trim($row[5])!="")
     {
        $height=ereg_replace("-","'",$row[5]);
        $height.="\"";
     }
     $csv.="$row[3],$row[4],$row[first] $row[last],$year,$height,$row[14],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13]\r\n";
     $html.="<tr align=\"center\"><td width=\"20\">$row[3]&nbsp;</td><td width=\"85\" align=\"left\">$first $last</td><td width=\"$smallw\">$year</td><td width=\"$smallw\">$height</td><td width=\"$smallw\">$row[14]</td>
    <td width=\"$smallw\">$row[8]</td><td width=\"$smallw\">$row[9]</td><td width=\"$smallw\">$row[10]</td><td width=\"$smallw\">$row[11]</td><td width=\"$smallw\">$row[12]</td><td width=\"$smallw\">$row[13]</td></tr>";
     $count++;
  }
}
$string.="</table></td></tr>";
if(!$makepdf) echo $string;

if($makepdf)
{
   $y=25+$teamphotoheight+2; $x=0;
   $pdf->SetFont("berthold","",8);
   $html.="</table>";
   $pdf->SetTextColor(0,0,0);
   $pdf->writeHTMLCell("200","",$x,$y,$html,0,0,1,true,"C");
   $pdf->SetX(10); $pdf->SetY($y);
   //$pdf->writeHTML($html,true,false,false,false,'');
}

$csv.="Games\r\n\"Opponent\",\"W/L\",\"Score\",\"Opp. Score\"\r\n";

$sched=GetSchedule($sid,'vb');
   $html1="<table cellspacing=\"0\" cellpadding=\"1\">";        //1st half of season
   $html2="<table cellspacing=\"0\" cellpadding=\"1\">";        //2nd half of season
   $gamect=0;
   for($i=0;$i<count($sched[oppid]);$i++)
   {
      if($sched[oppid][$i]!='0')        //only individual games, not tournament names
         $gamect++;
   }
   $html="<table cellspacing=\"0\" cellpadding=\"0\">";
   for($i=0;$i<count($sched[oppid]);$i++)
   {
      if($sched[oppid][$i]!='0')        //only individual games, not tournament names
      {
         $csv.="\"".GetSchoolName($sched[oppid][$i],'vb')."\",\"";
         $score=split("-",$sched[score][$i]);
         $html.="<tr valign=\"bottom\" align=\"left\"><td width=\"100\">".ConfigureSchoolForProgramSchedule(GetSchoolName($sched[oppid][$i],'vb'),35)."</td>";
         if($score[0]>$score[1]) $csv.="W\",\"";
         else if($score[1]>$score[0]) $csv.="L\",\"";
         else $csv.="\",\"";
         $csv.="$score[0]\",\"$score[1]\"\r\n";
         if($score[0]>$score[1]) $html.="<td align=\"center\" width=\"20\">W</td>";
         else $html.="<td align=\"center\" width=\"20\">L</td>";
         $html.="<td align=\"center\" width=\"25\">$score[0]-$score[1]";
         $html.="</td></tr>";
      }
   }

   if($makepdf)
   {
      $html.="</table>"; 
      $y=25+$teamphotoheight+2;
      $x=165;
      $pdf->SetFont("berthold","B",8);
      $pdf->writeHTMLCell("","",$x,$y,"Season Record $record",0,0,1,true,"L");
      $y+=4;
      $pdf->SetFont("berthold","",6.5);
      $pdf->writeHTMLCell("","",$x,$y,$html,0,0,1,true,"C");
      //NOW ADD school and historical info:
      $html1="<table cellspacing=\"0\" cellpadding=\"2\">";
        //Superintendent
      $sql="SELECT name FROM logins WHERE school='$school2' AND sport='Superintendent'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $html1.="<tr align='left'><td><b>Superintendent:</b>&nbsp;&nbsp;$row[name]</td></tr>";
        //Principal
      $sql="SELECT name FROM logins WHERE school='$school2' AND sport='Principal'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $html1.="<tr align='left'><td><b>Principal:</b>&nbsp;&nbsp;$row[name]</td></tr>";
        //AD
      $sql="SELECT name FROM logins WHERE school='$school2' AND level='2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $html1.="<tr align='left'><td><b>Athletic Director:</b>&nbsp;&nbsp;$row[name]</td></tr>";
        //Enrollment
      $html1.="<tr align='left'><td><b>NSAA Enrollment:</b> $enrollment</td></tr>";
        //Conference
      $html1.="<tr align='left'><td><b>Conference:</b> $conference</td></tr>";
      $html1.="</table>";

      $html2="<table cellspacing=\"0\" cellpadding=\"1\">";
        //Head Coach
      $html2.="<tr align='left'><td><b>Head Coach:</b>&nbsp;$coach</td></tr>";
        //Assistants
      $html2.="<tr align='left'><td><b>Assistant Coaches:</b>&nbsp;$asst</td></tr>";
      $sql="SELECT * FROM vbschool WHERE sid='$sid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
        //Trips to State: 4
      if(trim($row[tripstostate])!='')
         $html2.="<tr align='left'><td><b>State Tournament Appearances:</b>&nbsp;$row[tripstostate]</td></tr>";
        //Most Recent: 2012
      if(trim($row[mostrecent])!='')
         $html2.="<tr align='left'><td><b>Most Recent State Tournament:</b>&nbsp;$row[mostrecent]</td></tr>";
        //Championships: None
      if(trim($row[championships])!='')
         $html2.="<tr align='left'><td><b>State Championship Years:</b>&nbsp;$row[championships]</td></tr>";
        //Runner-up: B/2008, B/2010
      if(trim($row[runnerup])!='')
         $html2.="<tr align='left'><td><b>Runner-up:</b>&nbsp;$row[runnerup]</td></tr>";
      $html2.="</table>";
      $width=57;
      $x=2; 
      $y=240;
	//COLUMN 1
      $pdf->SetFont("berthold","",8);
      $pdf->writeHTMLCell($width,"",$x,$y,$html1,0,0,1,true,"L");
	//COLUMN 2
      $width=100;
      $x=62;
      $pdf->writeHTMLCell($width,"",$x,$y,$html2,0,0,1,true,"L");

      $pdffilename=preg_replace("/[^0-9a-zA-Z]/","",$schoolname)."_".strtoupper($sport).".pdf";
      $pdf->Output("../downloads/$pdffilename", "I");
      $pdflink="<a href=\"../downloads/$pdffilename\" target=\"_blank\" class=\"small\">Preview PDF</a>";
   }


$sql="SELECT * FROM vbschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
//add coaches,etc info to excel file
$csv.="\r\nHead Coach:,$coach\r\n";
$csv.="Assistant Coaches:,\"$asst\"\r\n";
$csv.="NSAA Enrollment:,$enrollment\r\n";
$csv.="Conference:,\"$conference\"\r\n";
$csv.="State Tournament Appearances:,\"$row[tripstostate]\"\r\n";
$csv.="Most Recent State Tournament:,\"$row[mostrecent]\"\r\n";
$csv.="State Championship Years:,\"$row[championships]\"\r\n";
$csv.="State Runner-Up Years:,\"$row[runnerup]\"\r\n";

if($print!=1 && !$makepdf)
{
   if($count>14)
   {
      echo "<tr align=left><th align=left><font color=red>You have entered too many students!<br>";
      echo "Please make sure you have checked only 14 students by the";
      echo " due date of this form</font></th></tr>";
   }
?>
<tr align=center>
<td><br>
    <a href="view_vb.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>&print=1" target=new class=small>Printer-Friendly Version</a>
    &nbsp;&nbsp;&nbsp;
    <a href="edit_vb.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>" class=small>Edit this Form</a>
    &nbsp;&nbsp;&nbsp;
    <a href="../welcome.php?session=<?php echo $session; ?>" class=small>Home</a>
</td>
</tr>
<?php
}
   //Write to .html and .csv file to be sent/e-mailed:
   $string.="</table></td></tr></table></body></html>";
   $activ="Volleyball";
   $activ_lower=strtolower($activ);
   
   $sch=ereg_replace(" ","",$school);
   $sch=ereg_replace("-","",$sch);
   $sch=ereg_replace("\.","",$sch);
   $sch=ereg_replace("\'","",$sch);
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

   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename.csv"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename.csv");

if(!$makepdf && $print==1)	//printer-friendly version: form for user to e-mail file
{
?>
   </form>
   <table>
   <tr align=center><th><br><br>
   <form method=post action="../email_form.php" name=emailform>
   <input type=hidden name=state value=<?php echo $state; ?>>
   <input type=hidden name=session value=<?php echo $session; ?>>
   <input type=hidden name=school value="<?php echo $school; ?>">
   <input type=hidden name=activ value="<?php echo $activ; ?>">
   <table>
<tr align=center><td colspan=2><b>E-MAIL THIS FORM:</b><br>PLEASE NOTE: Your district director will automatically receive these forms once the due date has passed. You do NOT need to email this form to the district director.</td></tr>
   <tr align=left><th align=left>
   Your e-mail address:</th>
   <td><input type=text name=reply size=30></td>
   </tr>
   <tr align=left><th align=left>
   Recipient(s)' address(es):</th>
   <td>
   <textarea name=email class=email cols=50 rows=5><?php echo $recipients; ?></textarea>
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
<?php
}  //end if print=1
if($send=='y')	//if box checked at bottom of edit screen, send to state assn
{
   $From="nsaa@nsaahome.org";
   $FromName="NSAA";
   $To=$main_email;
   $ToName="Jim Angele";
   $Subject="$school $activ State Tournament Roster";
   $Text="Attached is a CSV for Excel file of $school's $activ State Tournament Roster Information.  Thank you.";
   $Html="<font size=2 family=arial>Attached is a CSV-for-Excel file of $school's $activ State Tournament Roster information.<br><br>They have approved this as their final submission.<br><br>Thank you!</font>";
   $AttmFiles=array("/home/nsaahome/attachments/$filename.csv");

   SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles);
   SendMail($From,$FromName,"run7soccer@aim.com","Ann Gaffigan",$Subject,$Text,$Html,$AttmFiles);

   $today=time();
   $sql="UPDATE $table SET submitted='$today' WHERE school='$school2'";
   $result=mysql_query($sql);
}
if(!$makepdf)
{
?>
</table>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
<?php
}
?>
