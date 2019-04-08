<?php
/*************************
Dynamic creation of PDF for Printer.
This is the 1-school-per-page version.
11/12/13 by Ann Gaffigan
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

$sport="fb";
$sportname="Football";
$table="fb_state";

//$sql="USE nsaascores20122013";	//TESTING
//$result=mysql_query($sql);
$year=GetFallYear('fb');
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

if(!$sid1 && $school_ch!='')
{
   $sid1=GetSID2($school_ch,$sport);
}
if(!$sid1) $sid1=60; 	//ASHLAND GREENWOOD for testing

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
   $record=GetWinLoss($sid,$sport,$year,TRUE);
   $teamphoto=$row0[filename];
   $schoolname=$row0[school];

   //get information about school and coach:
	//SCHOOL
   $sql2="SELECT * FROM headers WHERE school='$school2'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $colors=$row2[color_names]; $conference=$row2[conference];
   $mascot=$row2[mascot]; $enrollment=$row2[boysenrollment];
    $co_op=0;
    $sql1="SELECT id FROM headers WHERE  school='$school2' ";
    $result1=mysql_query($sql1);
    $row1=mysql_fetch_array($result1);
    $sql_coop="SELECT * FROM fbschool WHERE mainsch='$row1[id]' AND (othersch1!=0 OR othersch2!=0 OR othersch3!=0)";
    $result_coop=mysql_query($sql_coop);
    $coop=mysql_fetch_array($result_coop);
    if(!empty($coop))
    {
        if (!empty($coop['othersch1'])){
            $sql_coop1="SELECT boysenrollment FROM headers WHERE id='$coop[othersch1]'";
            $result_coop1=mysql_query($sql_coop1);
            $row_coop1=mysql_fetch_array($result_coop1);
            $co_op+=(int)$row_coop1[boysenrollment];
        }

        if (!empty($coop['othersch2'])){
            $sql_coop2="SELECT boysenrollment FROM headers WHERE id='$coop[othersch2]'";
            $result_coop2=mysql_query($sql_coop2);
            $row_coop2=mysql_fetch_array($result_coop2);
            $co_op+=(int)$row_coop2[boysenrollment];
        }
        if (!empty($coop['othersch3'])){
            $sql_coop3="SELECT boysenrollment FROM headers WHERE id='$coop[othersch3]'";
            $result_coop3=mysql_query($sql_coop3);
            $row_coop3=mysql_fetch_array($result_coop3);
            $co_op+=(int)$row_coop3[boysenrollment];
        }

    }
   $logo=trim($row2[logo]);  $logoName=""; 
   if(!citgf_file_exists("../../images/$logo")) 
   {
      $logo=""; $logoerror="$logo doesn't exist";
   }
   else	//GET JPEG
   {
      $ext=end(explode(".",$logo));
      $image;
      $origFileName="../../images/$logo";	//ORIGINAL FILE (convert to JPEG)
      if( strcasecmp($ext, "jpeg") == 0 || strcasecmp($ext, "jpg") == 0 )
          $image = imagecreatefromjpeg( $origFileName );
      elseif( strcasecmp($ext, "png") == 0 )
          $image = imagecreatefrompng( $origFileName );
      elseif( strcasecmp($ext, "gif") == 0 )
          $image = imagecreatefromgif( $origFileName );
      else if(citgf_file_exists($origFileName))   //ASSUME JPEG
          $image = imagecreatefromjpeg( $origFileName );
      else $image="NONE";
      if($image!="NONE" && $image)
      {
          $logoWidth = imagesx( $image );
          $logoHeight = imagesy( $image );
	  $logoName="../downloads/fb_".$sid1."_logo.jpg";
          if(!imagejpeg($image,$logoName)) 
	  {
	      $logoerror="Could not create $logoName"; $logoName="";
	  }
          imagedestroy($image);
      }
      else $logoerror="Could not create from $ext";
   }
	//COACH
   $sql2="SELECT name, asst_coaches FROM logins WHERE school='$school2' AND sport LIKE '$sportname%'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $coach=$row2[0]; $asst=$row2[1];
   $sql2="SELECT asst_coaches FROM fb_staff WHERE school_id='$schoolid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if($row2[0]!='') $asst=$row2[0];
	//check if special co-op mascot/colors/coach for this sport
   if($row0[mascot]!='') $mascot=$row0[mascot];
   if($row0[colors]!='') $colors=$row0[colors];
   if($row0[coach]!='') $coach=$row0[coach];

   $origx=5; $origy=5;
   $x=$origx; $y=$origy;
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
   $maxwidth=216-(2*$origx);
   $maxheight=108;
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
      $x=(216/2)-($width/2);
      $photox=$x;
      $y=$origy+10;
      $photoy=$y;
      $pdf->Image("../downloads/".$teamphoto,$x,$y,$width,'','','','',false,72,'',false,false,0,false,false,true);
   }
   else
   {
      $teamphotowidth=$maxwidth;
      $photox=(216/2)-($teamphotowidth/2);
      $x=$photox;
      $y=$origy+10;
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
    $html.="<tr align='left'><td><b>NSAA Team Enrollment:</b> $co_op". ($enrollment+$co_op)."</td></tr>";
    $html="<tr align='left'><td></td></tr>";
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
         $html.="<tr align='left'><td><b>Playoff Appearances:</b>&nbsp;$row[tripstostate]</td></tr>";
        //Most Recent: 2012
      if(trim($row[mostrecent])!='')
         $html.="<tr align='left'><td><b>Most Recent Playoff Appearance:</b>&nbsp;$row[mostrecent]</td></tr>";
        //Championships: None
      if(trim($row[championships])!='')
         $html.="<tr align='left'><td><b>State Champion:</b>&nbsp;$row[championships]</td></tr>";
        //Runner-up: B/2008, B/2010
      if(trim($row[runnerup])!='')
         $html.="<tr align='left'><td><b>Runner-up:</b>&nbsp;$row[runnerup]</td></tr>";
      $html.="</table>";
   $x=$origx; $y=$origy+10+$maxheight+3;
   $pdf->SetTextColor(0,0,0);
   $pdf->SetFont("berthold","",9);
   $width=55;
   $starty=$y;
   $pdf->writeHTMLCell($width,"",$x,$y,"<b>TEAM INFORMATION</b>",0,1,0,true,"C");
   $pdf->SetFont("berthold","",7);
   $y+=5;
   $pdf->writeHTMLCell($width,"",$x,$y,$html,0,1,0,true,"L");

   $y=$pdf->GetY();
   $y+=4;

   //THE LOGO
   if($logoName!='')
   {
	$lwidth=45; $maxlheight=220-$y;
	if($maxlheight>40) $maxlheight=40;
        $lheight=$lwidth*($logoHeight/$logoWidth);
	if($lheight>$maxlheight)
	{
           $lratio=$lwidth/$lheight;
           $lheight=$maxlheight;
           $lwidth=$lheight*$lratio;
	}
        $lx=($width/2)-($lwidth/2)+$origx;
	$pdf->Image($logoName,$lx,$y,$lwidth,$lheight,'','','',false,72,'',false,false,0,false,false,true); 
   }
   else 
   {
      $lheight=0;
      $pdf->writeHTMLCell($width,"",$x,$y,$logoerror,0,1,0,true,"L");
   }

   //THE SCHEDULE
   $sched=GetSchedule($sid,$sport,$year,TRUE,TRUE,TRUE);
   $gamect=0;
   for($i=0;$i<count($sched[oppid]);$i++)
   {
      if($sched[oppid][$i]!='0')        //only individual games, not tournament names
         $gamect++;
   }
   $html="<table cellspacing=\"0\" cellpadding=\"0\"><tr valign=\"bottom\" align=\"left\"><td width=\"110\"><b>Opponent</b></td><td align=\"center\" width=\"15\"><b>W/L</b></td><td align=\"center\" width=\"25\"><b>Score</b></td></tr>";
   $max=count($sched[oppid]);
   for($i=0;$i<$max;$i++)
   {
      if($sched[oppid][$i]!='0')        //only individual games, not tournament names
      {
         $score=split("-",$sched[score][$i]);
         if(!preg_match("/CANCELLED/",$sched[score][$i]))
     	 {
         $html.="<tr valign=\"bottom\" align=\"left\"><td width=\"110\">".ConfigureSchoolForProgramSchedule(GetSchoolName($sched[oppid][$i],$sport),35)."</td>";
         if($score[0]>$score[1]) $html.="<td align=\"center\" width=\"15\">W</td>";
         else $html.="<td align=\"center\" width=\"15\">L</td>";
         $html.="<td align=\"center\" width=\"25\">$score[0]-$score[1]";
         $html.="</td></tr>";
	 }
      }
   }
   $html.="</table>"; 
   if($lheight>0) $y+=($lheight+4);
   $x=$origx;
   $pdf->SetFont("berthold","",9);
   $pdf->writeHTMLCell($width,"",$x,$y,"<b>".date("Y")." SCHEDULE</b>",0,1,0,true,"C");
   $y+=5;
   $pdf->SetFont("berthold","",7);
   $pdf->writeHTMLCell($width,"",$x,$y,$html,0,0,1,true,"C");

   //THE ROSTER: 2 tables (columns)
   $x=$origx+$width+5;
   $width=73;
   $headerwidth=2*$width;
   $y=$starty-2;
   $pdf->SetFont("berthold","B",13);
   $pdf->writeHTMLCell($headerwidth,"",$x,$y,"<b>".date("Y")." ROSTER</b>",0,1,0,true,"C");
   $pdf->SetFont("berthold","",7);
   $starty+=6;
   $smallw=20;
   $html1="<table cellspacing=\"0\" cellpadding=\"0\"><tr align=\"center\" valign=\"bottom\">
        <td width=\"$smallw\"><b>No.</b></td><td width=\"85\" align=\"left\"><b>Name</b></td><td width=\"$smallw\"><b>GR</b></td><td><b>POS<br />Off/Def</b></td>
        <td width=\"$smallw\"><b>HT</b></td><td width=\"$smallw\"><b>WT</b></td></tr></table>";
   $y=$starty;
   $pdf->SetFillColor(0,0,0);
   $pdf->SetTextColor(255,255,255);
   $pdf->writeHTMLCell($width,"",$x,$y,$html1,0,0,1,true,"L");
   $pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0,0,0);
   $html1="<table cellspacing=\"0\" cellpadding=\"0\">";
   $sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2') ORDER BY CAST(t1.jersey_lt AS DECIMAL),CAST(t1.jersey_dk AS DECIMAL)";
   $result=mysql_query($sql);
   $total=mysql_num_rows($result);
   if($total%2 != 0) $half1=ceil($total/2);
   else $half1=($total/2);
   $half2=$total-$half1;
   $sqlHALF1=$sql." LIMIT 0,$half1";
   $result=mysql_query($sqlHALF1);
   while($row=mysql_fetch_array($result))
   {
       	$last=$row[last];
       	if($row[nickname]!='') $first=$row[nickname];
       	else $first=$row[first];
       	$grade=GetYear($row[semesters]);
	if($row[jersey_lt]=="") $row[jersey_lt]=$row[jersey_dk];
	$position="$row[off_posn]/$row[def_posn]";
        if(trim($row[height])!="")
        {
           $height=ereg_replace("-","'",$row[height]);
           $height.="\"";
        }
       	$html1.="<tr align=\"center\"><td width=\"$smallw\">$row[jersey_lt]&nbsp;</td><td width=\"85\" align=\"left\">$first $last</td><td width=\"$smallw\">$grade</td><td>$position</td><td width=\"$smallw\">$height</td><td width=\"$smallw\">$row[weight]</td></tr>";
   }
   $html1.="</table>";

   $y+=8;
   $pdf->SetFont("berthold","",7);
   $pdf->writeHTMLCell($width,"",$x,$y,$html1,0,0,1,true,"L");

   //2nd COLUMN:
   $html2="<table cellspacing=\"0\" cellpadding=\"0\"><tr align=\"center\" valign=\"bottom\">
        <td width=\"$smallw\"><b>No.</b></td><td width=\"85\" align=\"left\"><b>Name</b></td><td width=\"$smallw\"><b>GR</b></td><td><b>POS<br />Off/Def</b></td>
        <td width=\"$smallw\"><b>HT</b></td><td width=\"$smallw\"><b>WT</b></td></tr></table>";
   $pdf->SetFillColor(0,0,0);
   $pdf->SetTextColor(255,255,255);
   $x+=$width;
   $y=$starty;
   $pdf->writeHTMLCell($width,"",$x,$y,$html2,0,0,1,true,"L");
   $pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0,0,0);
   $html2="<table cellspacing=\"0\" cellpadding=\"0\">";
   $sqlHALF2=$sql." LIMIT $half1,$half2";
   $result=mysql_query($sqlHALF2);
   while($row=mysql_fetch_array($result))
   {
        $last=$row[last];
        if($row[nickname]!='') $first=$row[nickname];
        else $first=$row[first];
        $grade=GetYear($row[semesters]);
        if($row[jersey_lt]=="") $row[jersey_lt]=$row[jersey_dk];
        $position="$row[off_posn]/$row[def_posn]";
        if(trim($row[height])!="")
        {
           $height=ereg_replace("-","'",$row[height]);
           $height.="\"";
        }
        $html2.="<tr align=\"center\"><td width=\"$smallw\">$row[jersey_lt]&nbsp;</td><td width=\"85\" align=\"left\">$first $last</td><td width=\"$smallw\">$grade</td><td>$position</td><td width=\"$smallw\">$height</td><td width=\"$smallw\">$row[weight]</td></tr>";
   }
   $html2.="</table>";

   $y+=8;
   $pdf->writeHTMLCell($width,"",$x,$y,$html2,0,0,1,true,"L");

   //DRAW LINE BETWEEN TWO COLUMNS:
   $x1=$x; $x2=$x1;
	//WHITE ON BLACK
   $y1=$y-8; $y2=$y;
   $style=array('color' => array(255,255,255));
   $pdf->Line($x1,$y1,$x2,$y2,$style);
	//BLACK ON WHITE
   $y1=$y; $y2=265;
   $style=array('color' => array(0,0,0));
   $pdf->Line($x1,$y1,$x2,$y2,$style);


   $ix++;
}	//END FOR EACH SCHOOL WITH APPROVED DATA
      //OUTPUT PDF FILE
      $pdffilename=$sportname."_Rosters_for_Program_".$sid1.".pdf";
      if(!$pdf->Output("../downloads/$pdffilename", "I")) echo "OUTPUT ERROR";
?>
