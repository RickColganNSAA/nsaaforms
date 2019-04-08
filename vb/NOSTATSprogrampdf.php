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

$sport="vb";
$sportname="Volleyball";
$table="vb_state";
$form_type="STATE";

$sql="USE nsaascores20122013";	//TESTING
$result=mysql_query($sql);
$year=GetFallYear('vb');
$year=2012;	//TESTING

   //include PDF creation tool:
   require_once('../../tcpdf_php4/config/lang/eng.php');
   require_once('../../tcpdf_php4/tcpdf.php');

   //INITIALIZE PDF
   $pdf = new TCPDF("P", PDF_UNIT, "LETTER", true); //LETTER = 8.5 x 11 in or 216 x 280 mm
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   //$pdf->SetMargins(0,0);
   $pdf->SetAutoPageBreak(FALSE, 1);
   $pdf->setLanguageArray($l);
   $pdf->AliasNbPages();
   $pdf->AddPage();

$sql0="SELECT * FROM vbschool WHERE (sid='$sid1' OR sid='$sid2') ORDER BY programorder";
$result0=mysql_query($sql0);
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
   $colors=$row2[color_names];
   $mascot=$row2[mascot];
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
   if(($ix%2)>0) $y+=140;
   $pdf->SetFont("berthold","B","18");
   $pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0,0,0);
   $pdf->writeHTMLCell("","",$x,$y,"<span style=\"font-weight:bold;\">".strtoupper("$schoolname")."</span><span style=\"color: rgb(81, 81, 81);\"> $mascot </span> <span style=\"color: rgb(41, 41, 41);\">| $record |</span>",0,0,1,true,"L");
   $pdf->SetFont("berthold","B","13");
   $pdf->SetTextColor(0,0,0);
   $x=$origx; $y=$origy+8;
   if(($ix%2)>0) $y+=140;
   $pdf->writeHTMLCell("","",$x,$y,"$colors",0,0,1,true,"L");

   //TEAM PHOTO:
   $maxwidth=89; //99;
   $maxheight=56; //66;
   //TESTING:
   $photos=array("ba_teamphoto_60_9.jpg","ba_teamphoto_12_3.jpg","ba_teamphoto_20_9.jpg","ba_teamphoto_9_1.jpg");
   $x=rand(0,3);
   $teamphoto=$photos[$x];
   list($pixw, $pixh) = getimagesize(getbucketurl("../downloads/".$teamphoto));
   $ratio=$pixw/$pixh;
   $width=$maxwidth;	//IDEAL
   $height=$width/$ratio;
   if($height>$maxheight)
   {
      $height=$maxheight;
      $width=$height*$ratio;
   }
   $x=$origx+64+($maxwidth/2)-($width/2);
   $y=$origy+20;
   if(($ix%2)>0) $y+=140;
   if(citgf_file_exists("../downloads/".$teamphoto))
      $pdf->Image("../downloads/".$teamphoto,$x,$y,$width,'','','','',false,72,'',false,false,0,false,false,true);
   $teamphotoheight=$maxheight;

   //THE ROSTER:
   $smallw=25;
   $html="<table cellspacing=\"0\" cellpadding=\"0\"><tr align=\"center\">
        <td width=\"18\"><b>No.</b></td><td width=\"80\"><b>Name</b></td><td width=\"$smallw\"><b>Grd.</b></td><td width=\"$smallw\"><b>Ht.</b></td><td width=\"$smallw\"><b>Pos.</b></td></tr>";
   $sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND ((t1.school='$school2' AND t2.school='$school2') OR (t1.co_op='$school2' AND t2.school=t1.school)) AND t1.checked='y' ORDER BY CAST(t1.jersey_lt AS DECIMAL), t1.libero";
   $result=mysql_query($sql);
   $count=0;
   while($row=mysql_fetch_array($result))
   {
       $last=$row[last];
       if($row[nickname]!='') $first=$row[nickname];
       else $first=$row[first];
       $grade=GetYear($row[semesters]);
       if(trim($row[5])!="")
       {
          $height=ereg_replace("-","'",$row[5]);
          $height.="\"";
       }
       $html.="<tr align=\"center\"><td width=\"18\">$row[3]&nbsp;</td><td width=\"80\" align=\"left\">$first $last</td><td width=\"$smallw\">$grade</td><td width=\"$smallw\">$height</td><td width=\"$smallw\">$row[14]</td></tr>";
       $count++;
   }
   $x=$origx; $y=$origy+20;
   if(($ix%2)>0) $y+=140;
   $pdf->SetFont("berthold","",7.5);
   $html.="</table>";
   $pdf->SetTextColor(0,0,0);
   $pdf->writeHTMLCell("","",$x,$y,$html,0,0,1,true,"C");

   $sched=GetSchedule($sid,'vb',$year);
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
         $html.="<tr valign=\"bottom\" align=\"left\"><td width=\"100\">".ConfigureSchoolForProgramSchedule(GetSchoolName($sched[oppid][$i],'vb',$year),35)."</td>";
         if($score[0]>$score[1]) $html.="<td align=\"center\" width=\"15\">W</td>";
         else $html.="<td align=\"center\" width=\"15\">L</td>";
         $html.="<td align=\"center\" width=\"25\">$score[0]-$score[1]";
         $html.="</td></tr>";
      }
   }

   $html.="</table>"; 
   $y=$origy+7;
   if(($ix%2)>0) $y+=140;
   $x=$origx+155;
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
   $x=$origx+2; 
   $y=$origy+100;
   if(($ix%2)>0) $y+=140;
	//COLUMN 1
      $pdf->SetFont("berthold","",8);
      $pdf->writeHTMLCell($width,"",$x,$y,$html1,0,0,1,true,"L");
	//COLUMN 2
      $width=90;
      $x=$origx+62;
      $pdf->writeHTMLCell($width,"",$x,$y,$html2,0,0,1,true,"L");
   $ix++;
}	//END FOR EACH SCHOOL WITH APPROVED DATA
      //OUTPUT PDF FILE
      $pdffilename=$sportname."_Rosters_for_Program_".$sid1."_".$sid2.".pdf";
      $pdf->Output("../downloads/$pdffilename", "I");
?>
