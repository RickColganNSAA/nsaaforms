<?php
/*************************
July-Oct 2013:
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
if($viewdistrict==1) $table="vb";
else $table="vb_state";

//$sql="USE nsaascores20122013";	//TESTING
//$result=mysql_query($sql);
$year=GetFallYear('vb');
//$year=2012;	//TESTING

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

if($sid2 && $sid2!='')
   $sql0="SELECT * FROM vbschool WHERE (sid='$sid1' OR sid='$sid2') ORDER BY programorder,school";
else
   $sql0="SELECT * FROM vbschool WHERE sid='$sid1'";
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
   $mascot=$row2[mascot]; $enrollment=$row2[girlsenrollment];

    $sql1="SELECT id FROM headers WHERE  school='$school2' ";
    $result1=mysql_query($sql1);
    $row1=mysql_fetch_array($result1);
    $sql_coop="SELECT * FROM vbschool WHERE mainsch='$row1[id]' AND (othersch1!=0 OR othersch2!=0 OR othersch3!=0)";
    $result_coop=mysql_query($sql_coop);
    $coop=mysql_fetch_array($result_coop);
    $co_op=0;
    if(!empty($coop))
    {
        if (!empty($coop['othersch1'])){
            $sql_coop1="SELECT girlsenrollment FROM headers WHERE school='$coop[othersch1]'";
            $result_coop1=mysql_query($sql_coop1);
            $row_coop1=mysql_fetch_array($result_coop1);
            $co_op+=$row_coop1[girlsenrollment];
        }
        if (!empty($coop['othersch2'])){
            $sql_coop2="SELECT girlsenrollment FROM headers WHERE school='$coop[othersch2]'";
            $result_coop2=mysql_query($sql_coop2);
            $row_coop2=mysql_fetch_array($result_coop2);
            $co_op+=$row_coop2[girlsenrollment];
        }
        if (!empty($coop['othersch3'])){
            echo $sql_coop2="SELECT girlsenrollment FROM headers WHERE school='$coop[othersch3]'";
            $result_coop3=mysql_query($sql_coop3);
            $row_coop3=mysql_fetch_array($result_coop3);
            $co_op+=$row_coop3[girlsenrollment];
        }
    }
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
   $maxwidth=106;
   $maxheight=73;
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
   $html="<table cellspacing=\"0\" cellpadding=\"0\">";
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
    $html.="<tr align='left'><td><b>NSAA Team Enrollment</b> ".($enrollment+$co_op)."</td></tr>";
        //Conference
      $html.="<tr align='left'><td><b>Conference:</b> $conference</td></tr>";
        //Head Coach
      $html.="<tr align='left'><td><b>Head Coach:</b>&nbsp;$coach</td></tr>";
        //Assistants
      $html.="<tr align='left'><td><b>Assistant Coaches:</b>&nbsp;$asst</td></tr>";
      $sql="SELECT * FROM vbschool WHERE sid='$sid'";
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
   $max=count($sched[oppid]);
   //if($max>39) $max=39;
   for($i=0;$i<$max;$i++)
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
   $pdf->SetFont("berthold","B",7);
   $pdf->writeHTMLCell("","",$x,$y,"Season Record $record",0,0,0,true,"L");
   $y+=3;
   $schedulex=$x;
   $pdf->SetFont("berthold","",6.5);
   $pdf->writeHTMLCell("","",$x,$y,$html,0,0,1,true,"C");

   //THE ROSTER:
   $smallw=18; $posw=35;
   $html="<table cellspacing=\"0\" cellpadding=\"0\"><tr align=\"center\">
        <td width=\"15\"><b>No.</b></td><td width=\"90\"><b>Name</b></td><td width=\"$smallw\"><b>GR</b></td><td><b>POS</b></td>
        <td><b>HT</b></td><td><b>DIG</b></td><td><b>REC</b></td><td><b>ACE</b></td><td><b>BLK</b></td><td><b>KILL</b></td><td><b>AST</b></td></tr>";
   $sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$school2' OR t1.co_op='$school2') AND t1.checked='y' ORDER BY CAST(t1.jersey_lt AS DECIMAL)";
   $result=mysql_query($sql);
   $count=0;
   //$html.="<tr align=center><td colspan=5>".mysql_error()." $sql $ct</td></tr>";
   $poswidth=40;
   while($row=mysql_fetch_array($result))
   {
       $last=$row[last];
       if($row[nickname]!='') $first=$row[nickname];
       else $first=$row[first];
       $grade=GetYear($row[semesters]);
	
	if($row[jersey_lt]=="") $row[jersey_lt]=$row[libero];
	if($row[good_serves]=="") $row[good_serves]="0";
        if($row[att_serves]=="") $row[att_serves]="NA";
        if($row[ace_serves]=="") $row[ace_serves]="0";
        if($row[blocks]=="") $row[blocks]="0";
        if($row[kills]=="") $row[kills]="0";
        if($row[assists]=="") $row[assists]="0";

	$pos=explode("/",$row[position]);
     	if(count($pos)>2)	
	{
	   if(count($pos)>4)
	   {
	      $row[position]="";
	      for($p=0;$p<4;$p++)
	      {
	         $row[position].=$pos[$p];
	         if($p==0) $row[position].="/";
	      }
	   }
           $row[position]="<font size=\"6\">$row[position]</font>";
	}
        if(trim($row[height])!="")
        {
           $height=ereg_replace("-","'",$row[height]);
           $height.="\"";
        }

       $html.="<tr align=\"center\"><td width=\"15\">$row[jersey_lt]&nbsp;</td><td width=\"90\" align=\"left\">$first $last</td><td width=\"$smallw\">$grade</td><td>$row[position]</td><td>$height</td><td>$row[good_serves]</td><td>$row[att_serves]</td><td>$row[ace_serves]</td><td>$row[blocks]</td><td>$row[kills]</td><td>$row[assists]</td></tr>";
       $count++;
   }
   $html.="</table>";

   $x=$origx; 
   $y=$photoy+$maxheight+1;
   $width=$schedulex-$origx-2;
   $pdf->SetFont("berthold","",7);
   $pdf->writeHTMLCell($width,"",$x,$y,$html,0,0,1,true,"L");

//echo $html; exit();

   $ix++;
}	//END FOR EACH SCHOOL WITH APPROVED DATA
      //OUTPUT PDF FILE
      $pdffilename=$sportname."_Rosters_for_Program_".$sid1."_".$sid2.".pdf";
      if(!$pdf->Output("../downloads/$pdffilename", "I")) echo "OUTPUT ERROR";
?>
