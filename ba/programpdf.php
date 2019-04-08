<?php
/*************************
July/August 2013:
Dynamic creation of PDF for Printer.
This is the 2-schools-per-page version.
Shows all schools marked for approval.
**************************/

require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/variables.php'; //Wildcard Variables
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

$level=GetLevel($session);

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) && !$makepdf)
{
   header("Location:../index.php");
   exit();
}

$sport="ba";
$sportname="Baseball";
if($viewdistrict==1) $table="ba";
else $table="ba_state";

//$sql="USE nsaascores20122013";	//TESTING
//$result=mysql_query($sql);
$year=GetFallYear('ba');
//$year=2012;	//TESTING

   //include PDF creation tool:
   require_once('../../tcpdf/tcpdf.php');

   //INITIALIZE PDF
   $pdf = new TCPDF("P", PDF_UNIT, "LETTER", true); //LETTER = 8.5 x 11 in or 216 x 280 mm
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   //$pdf->SetMargins(0,0);
   $pdf->SetAutoPageBreak(FALSE, 1);
   $pdf->setLanguageArray($l);
   $pdf->AddPage();

if($sid2 && $sid2!='')
   $sql0="SELECT * FROM ".$sport."school WHERE (sid='$sid1' OR sid='$sid2') ORDER BY programorder";
else
   $sql0="SELECT * FROM ".$sport."school WHERE sid='$sid1'";
$result0=mysql_query($sql0);
if(mysql_error()) 
{
   echo $sql0."<br>".mysql_error();
   exit();
}
$ix=0; $page=0;
while($row0=mysql_fetch_array($result0))
{
   $school=GetMainSchoolName($row0[sid],$sport);
   $sid=$row0[sid];
   $schoolid=$row0[mainsch];
   $school2=ereg_replace("\'","\'",$school);
   $record=GetWinLoss($sid,$sport,$year);
   $teamphoto=$row0[filename];
   $schoolname=$row0[school];

   //get information about school and coach:
	//SCHOOL
   $sql2="SELECT * FROM headers WHERE school='$school2'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $colors=$row2[color_names]; $conference=$row2[conference];
   $mascot=$row2[mascot]; $enrollment=$row2[boysenrollment];
	//COACH
   $sql2="SELECT name, asst_coaches FROM logins WHERE school='$school2' AND sport='$sportname'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $coach=$row2[0];
   $asst=$row2[1];
	//check if special co-op mascot/colors/coach for this sport
   if($row0[mascot]!='') $mascot=$row0[mascot];
   if($row0[colors]!='') $colors=$row0[colors];
   if($row0[coach]!='') $coach=$row0[coach];

   $origx=5; $origy=5;
   $x=$origx; $y=$origy;
   if(($ix%2)>0) $y+=135;
   $pdf->SetFont("berthold","B","14");
   $pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0,0,0);
   $pdf->SetFillColor(0,0,0);
   $pdf->SetTextColor(255,255,255);
   $pdf->writeHTMLCell("206","",$x,$y,"<span style=\"font-weight:bold;\">".strtoupper("$schoolname")."</span> $mascot | $colors | $record",0,0,1,true,"L");
   $pdf->SetFont("berthold","B","13");
   $pdf->SetTextColor(0,0,0);

   $pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0,0,0);

   //TEAM PHOTO:
   $maxwidth=89; //99;
   $maxheight=51; //66;
   //TESTING:
	/*
   $photos=array("ba_teamphoto_60_9.jpg","ba_teamphoto_12_3.jpg","ba_teamphoto_20_9.jpg","ba_teamphoto_9_1.jpg");
   $x=rand(0,3);
   $teamphoto=$photos[$x];
	*/
   if(citgf_file_exists("../downloads/".$teamphoto) && trim($teamphoto)!='')
   {
      list($pixw, $pixh) = getimagesize(getbucketurl("../downloads/".$teamphoto));
      $ratio=$pixw/$pixh;
      $width=$maxwidth;	//IDEAL
      $height=$width/$ratio;
      if($height>$maxheight)
      {
         $height=$maxheight;
         $width=$height*$ratio;
      }
      $x=$origx+153-$width;
      $photox=$x;
      $y=$origy+9;
      if(($ix%2)>0) $y+=135;
      $photoy=$y;
      $pdf->Image("../downloads/".$teamphoto,$x,$y,$width,'','','','',false,72,'',false,false,0,false,false,true);
   }
   else
   {
      $teamphotowidth=$maxwidth;
      $photox=$origx+153-$teamphotowidth;
      $x=$photox;
      $y=$origy+9;
      if(($ix%2)>0) $y+=135;
      $photoy=$y;
   }
   $teamphotoheight=$maxheight;

   //SCHOOL AND HISTORICAL INFO:
   $html="<table cellspacing=\"0\" cellpadding=\"1\">";
      //Superintendent
      $sql="SELECT name FROM logins WHERE school='$school2' AND sport='Superintendent'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $html.="<tr align='left'><td><b>Superintendent:</b>&nbsp;&nbsp;$row[name]</td></tr>";
        //Principal
      $sql="SELECT name FROM logins WHERE school='$school2' AND sport='Principal'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $html.="<tr align='left'><td><b>Principal:</b>&nbsp;&nbsp;$row[name]</td></tr>";
        //AD
      $sql="SELECT name FROM logins WHERE school='$school2' AND level='2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $html.="<tr align='left'><td><b>Athletic Director:</b>&nbsp;&nbsp;$row[name]</td></tr>";
        //Enrollment
    $html.="<tr align='left'><td><b>NSAA Team Enrollment:</b> $enrollment</td></tr>";
        //Conference
      $html.="<tr align='left'><td><b>Conference:</b> $conference</td></tr>";
        //Head Coach
      $html.="<tr align='left'><td><b>Head Coach:</b>&nbsp;$coach</td></tr>";
        //Assistants
      $html.="<tr align='left'><td><b>Assistant Coaches:</b>&nbsp;$asst</td></tr>";
      $sql="SELECT * FROM ".$sport."school WHERE sid='$sid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
        //Trips to State: 4
      if(trim($row[tripstostate])!='')
         $html.="<tr align='left'><td><b>State Tournament Appearances:</b>&nbsp;$row[tripstostate]</td></tr>";
        //Most Recent: 2012
      if(trim($row[mostrecent])!='')
         $html.="<tr align='left'><td><b>Most Recent State Tournament:</b>&nbsp;$row[mostrecent]</td></tr>";
        //Championships: None
      if(trim($row[championships])!='')
         $html.="<tr align='left'><td><b>State Championship Years:</b>&nbsp;$row[championships]</td></tr>";
        //Runner-up: B/2008, B/2010
      if(trim($row[runnerup])!='')
         $html.="<tr align='left'><td><b>Runner-up:</b>&nbsp;$row[runnerup]</td></tr>";
      $html.="</table>";
   $x=$origx; $y=$origy+8;
   if(($ix%2)>0) $y+=135;
   $pdf->SetFont("berthold","",7);
   $pdf->SetTextColor(0,0,0);
   $width=$photox-$origx-2;
   $pdf->writeHTMLCell($width,"",$x,$y,$html,0,0,0,true,"C");
   //THE SCHEDULE
   $sched=GetSchedule($sid,$sport,$year,TRUE,TRIE);
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
         $score=split("-",$sched[score][$i]);
         if(!preg_match("/CANCELLED/",$sched[score][$i]))
     	 {
         $html.="<tr valign=\"bottom\" align=\"left\"><td width=\"100\">".ConfigureSchoolForProgramSchedule(GetSchoolName($sched[oppid][$i],$sport,$year),35)."</td>";
         if($score[0]>$score[1]) $html.="<td align=\"center\" width=\"15\">W</td>";
         else $html.="<td align=\"center\" width=\"15\">L</td>";
         $html.="<td align=\"center\" width=\"25\">$score[0]-$score[1]";
         $html.="</td></tr>";
	 }
      }
   }
   $html.="</table>"; 
   $y=$origy+8;
   if(($ix%2)>0) $y+=135;
   $x=$origx+155;
   $pdf->SetFont("berthold","B",8);
   $pdf->writeHTMLCell("","",$x,$y,"Season Record $record",0,0,0,true,"L");
   $y+=4;
   $schedulex=$x;
   $pdf->SetFont("berthold","",6.5);
   $pdf->writeHTMLCell("","",$x,$y,$html,0,0,1,true,"C");

   //THE ROSTER:
   $smallw=18; $posw=35;
   $html="<table cellspacing=\"0\" cellpadding=\"0\"><tr align=\"center\">
        <td width=\"15\"><b>No.</b></td><td width=\"90\"><b>Name</b></td><td width=\"$smallw\"><b>GR</b></td><td width=\"35\"><b>POS</b></td>
        <td><b>BA</b></td><td><b>AB</b></td><td><b>H</b></td><td><b>R</b></td><td><b>RBI</b></td><td><b>HR</b></td><td><b>W-L</b></td><td><b>ERA</b></td></tr>";
   $sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$school2' OR t1.co_op='$school2') AND t1.checked='y' ORDER BY CAST(t1.jersey_lt AS DECIMAL)";
   $result=mysql_query($sql);
   $count=0;
   //$html.="<tr align=center><td colspan=5>".mysql_error()." $sql $ct</td></tr>";
   while($row=mysql_fetch_array($result))
   {
       $last=$row[last];
       if($row[nickname]!='') $first=$row[nickname];
       else $first=$row[first];
       $grade=GetYear($row[semesters]);
	
  	if($row[average]=="") $row[average]="0";
        if($row[at_bats]=="") $row[at_bats]="0";
        if($row[hits]=='') $row[hits]="0";
        if($row[runs_scored]=='') $row[runs_scored]="0";
        if($row[runs_batted]=='') $row[runs_batted]="0";
        if($row[home_runs]=='') $row[home_runs]="0";
        if($row[pitching_record]=='') $row[pitching_record]="&nbsp;";
        if($row[pitching_era]=="" || $row[pitching_era]=='0') $row[pitching_era]="&nbsp;";
 	if($row[jersey_lt]=='') $row[jersey_lt]=$row[jersey_dk];
        if($row[pitching_record]=="--") $row[pitching_record]="&nbsp;";

	$pos=explode("/",$row[position]);
     	if(count($pos)>2)
	{
	   $row[position]="<font size=\"5\">$row[position]</font>";
	   //$row[position]="";
	   for($p=0;$p<2;$p++)
	   {
	     // $row[position].=$pos[$p];
	      //if($p==0) $row[position].="/";	//STOP
	   }
	}

       $html.="<tr align=\"center\"><td width=\"15\">$row[jersey_lt]&nbsp;</td><td width=\"90\" align=\"left\">$first $last</td><td width=\"$smallw\">$grade</td><td>$row[position]</td><td>$row[average]</td><td>$row[at_bats]</td><td>$row[hits]</td><td>$row[runs_scored]</td><td>$row[runs_batted]</td><td>$row[home_runs]</td><td>$row[pitching_record]</td><td>$row[pitching_era]</td></tr>";
       $count++;
   }
   //TESTING:
	/*
   while($count<20)
   {
       $num=$count+1;
       $html.="<tr align=\"center\"><td width=\"15\">$num&nbsp;</td><td width=\"90\" align=\"left\">Longfirstname Longlastname</td><td width=\"$smallw\">$grade</td><td>3B</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>";
       $count++;
   }
	*/
   $html.="</table>";

   $x=$origx; 
   $y=$photoy+$maxheight+1;
   $width=$schedulex-$origx-3;
   $pdf->SetFont("berthold","",7);
   $pdf->writeHTMLCell($width,"",$x,$y,$html,0,0,1,true,"L");
//echo $html; exit();

   $ix++;
}	//END FOR EACH SCHOOL WITH APPROVED DATA
      //OUTPUT PDF FILE
      $pdffilename=$sportname."_Rosters_for_Program_".$sid1."_".$sid2.".pdf";
      if(!$pdf->Output("../downloads/$pdffilename", "I")) echo "OUTPUT ERROR";
?>
