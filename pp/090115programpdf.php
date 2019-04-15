<?php
/*************************
July-Oct 2013:
Dynamic creation of PDF for Printer.
This is the 3-schools-per-page version.
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

$sport="pp";
$sportname="Play Production";
$table="pp";	//NO SEPARATE STATE TABLE FOR PP
$studtable=$table."_students";

$sql="USE nsaascores20142015";	//TESTING
$result=mysql_query($sql);
$year=GetFallYear($sport);
//$year=2012;	//TESTING

   //include PDF creation tool:
   //require_once('../../tcpdf/config/lang/eng.php');
   require_once('../../tcpdf/tcpdf.php');


   //INITIALIZE PDF
   $pdf = new TCPDF("P", PDF_UNIT, "LETTER", true); //LETTER = 8.5 x 11 in or 216 x 280 mm
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   $pdf->SetMargins(0,0);
   $pdf->SetAutoPageBreak(FALSE, 1);
   $pdf->setHeaderData('',0,'','',array(0,0,0), array(255,255,255) );  	//REMOVES BORDER AT BOTTOM OF HEADER
   $pdf->AddPage();

if($sid1 && $sid1!='' && $sid2 && $sid2!='' && $sid3 && $sid3!='')
   $sql0="SELECT * FROM ppschool WHERE (sid='$sid1' OR sid='$sid2' OR sid='$sid3') ORDER BY programorder";
else if($sid1 && $sid1!='')
   $sql0="SELECT * FROM ppschool WHERE sid='$sid1'";
else 
{
   echo "No School Indicated"; exit();
}

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
   $school2=addslashes($school);
   $teamphoto=$row0[filename]; 
   if(trim($teamphoto)=="") $teamphoto="pp_teamphoto_300_1.jpg";
   $schoolname=$row0[school]; $class=$row0['class'];

   //INFO ABOUT THE PLAY
   $sql2="SELECT * FROM $table WHERE school='$school2'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $title=$row2[title]; $playwright=$row2[playwright];
   $director=$row2[director];

   //get information about school and coach:
	//COACH
   $sql2="SELECT name, asst_coaches FROM logins WHERE school='$school2' AND sport='$sportname'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $coach=$row2[0];
   $asst=$row2[1];

   //GET CAST
   $cast="";
   $sql2="SELECT DISTINCT part FROM $studtable WHERE (school='$school2' OR co_op='$school2') AND (crew='' OR crew IS NULL) ORDER BY partorder";
   $result2=mysql_query($sql2);
  	//CALCULATE "Per column" NUMBER BY number of names, not parts
      $sql3="SELECT part FROM $studtable WHERE (school='$school2' OR co_op='$school2') AND (crew='' OR crew IS NULL) ORDER BY partorder";
      $result3=mysql_query($sql3);
      $ct=mysql_num_rows($result2);
      if($ct%2==0) $percol=$ct/2;
      else $percol=ceil($ct/2);
   $curcol=0; $extras=array(); $e=0;
   $cast="<tr align=\"left\" valign=\"top\"><td width=\"165\"><table cellspacing=\"0\" cellpadding=\"0\">";
   while($row2=mysql_fetch_array($result2))
   {
      if($curcol>=$percol)
      {
	 $cast.="</table></td><td width=\"5\">&nbsp;</td><td width=\"165\"><table cellspacing=\"0\" cellpadding=\"0\">"; $curcol=0;
      }
      $sql3="SELECT * FROM $studtable WHERE (school='$school2' OR co_op='$school2') AND part='".addslashes($row2[part])."' ORDER BY partorder";
      $result3=mysql_query($sql3);
      $names=""; $namect=0;
      while($row3=mysql_fetch_array($result3))
      {
         $names.=GetStudentInfo($row3[student_id],FALSE).", "; $namect++;
      }
      if($names!='') $names=substr($names,0,strlen($names)-2);
      if($namect<=2)
      {
         $cast.="<tr valign=\"top\"><td align=\"left\">$row2[part]:</td><td align=\"right\">";
         $cast.=$names."</td></tr>";
         $curcol++;
      }
      else
      {
	 $extras[$e]="<tr valign=\"top\"><td align=\"left\" width=\"80\">$row2[part]:</td><td align=\"left\" width=\"250\">$names</td></tr>";
	 $e++;
      }
   }
   $cast.="</table></td></tr>";
   $cast="<table cellspacing=\"0\" cellpadding=\"0\">".$cast."</table>";
   if(count($extras)>0)
   {
      $cast.="<table cellspacing=\"0\" cellpadding=\"1\">";
      for($e=0;$e<count($extras);$e++)
      {
	 $cast.=$extras[$e];
      }
      $cast.="</table>";
   }

   //GET CREW
   $crew="";
   $sql2="SELECT t1.* FROM $studtable AS t1,eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$school2' OR t1.co_op='$school2') AND t1.crew='y' ORDER BY t2.last,t2.first";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $crew.=GetStudentInfo($row2[student_id],FALSE).", ";
   }
   if($crew!='') $crew=substr($crew,0,strlen($crew)-2);


   //OUTPUT

   //HEADER (BLACK BACKGROUND, WHITE LETTERS)
   $origx=5; $origy=5;
   $x=$origx; $y=$origy;
   $y+=(90*($ix%3));
   $fsize=13;
   $title=trim($title);
   $title=ereg_replace("\"","",$title);
   if(strlen("$schoolname $title $playwright")>80) $fsize=11;
   else if(strlen("$schoolname $title $playwright")>70) $fsize=12;
   $pdf->SetFont("berthold","B","$fsize");
   $pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0,0,0);
   $y2=$y-1;
   //$pdf->writeHTMLCell("206",$y2,$x,0,"",0,0,1,true,"L");	//GET RID OF LINE SHOWING AT TOP OF PAGE
   $pdf->SetFillColor(0,0,0);
   $pdf->SetTextColor(255,255,255);
   $pdf->writeHTMLCell("206","",$x,$y,"<span style=\"font-weight:bold;\">".strtoupper("$schoolname")."</span> | \"$title\" written by $playwright",0,0,1,true,"L");
   $pdf->SetFont("berthold","B","13");
   $pdf->SetTextColor(0,0,0);


   //RESET BACKGROUND TO WHITE AND TEXT TO BLACK
   $pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0,0,0);

   $x=$origx; $y=$origy+6;
   $y+=(90*($ix%3));
   $pdf->SetFont("berthold","",7);
   $pdf->SetTextColor(0,0,0);
   $playinfo="";
      //Superintendent
      $sql="SELECT name FROM logins WHERE school='$school2' AND sport='Superintendent'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if(trim($row[name])!='') $playinfo.="<b>Superintendent:</b> $row[name]<br />";
        //Principal
      $sql="SELECT name FROM logins WHERE school='$school2' AND sport='Principal'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $playinfo.="<b>Principal:</b> $row[name]<br />";
   $playinfo.="<b>Director:</b> $director";
   if(trim($asst)!='') $playinfo.="<br /><b>Assistant Directors:</b> $asst";
   $pdf->writeHTMLCell(85,"",$x,$y,"$playinfo",0,1,0,true,"L");

   //CALCULATE PHOTO SPACE AND SET PAGE REGION FOR TEAM PHOTO
   $maxwidth=85;
   $maxheight=53;
   $xright=216;
   $ytop=$pdf->GetY();
   $ytop+=1;
   //$ytop+=(90*($ix%3));
   
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
      $x=$origx; //$xright-$width-$origx;
      $photox=$x;
      $y=$ytop;
      $photoy=$y;
      $xr=$x+$width;
      $pdf->Image("../downloads/".$teamphoto,$x,$y,$width,'','','','',false,72,'',false,false,0,false,false);
      $teamphotowidth=$width;
      $teamphotoheight=floor($height);
   }
   else
   {
      $teamphotowidth=$maxwidth;
      $teamphotoheight=$maxheight;
      $photox=$origx; //$xright-$teamphotowidth;
      $photoy=$ytop;
   }
   $xleft=$origx; //216-$teamphotowidth-5;
   $xright=$xleft+$teamphotowidth+1;
   $ybottom=$ytop+$teamphotoheight+1;

   //SET PAGE REGION
	/*
   $regions = array(
	array('page' => '', 'xt' => $xright, 'yt' =>  $ytop, 'xb' => $xright, 'yb' =>  $ybottom, 'side' => 'L'),
   );
   $pdf->setPageRegions($regions);
	*/

   $y=$origy+8; //$pdf->GetY();
   $y+=(90*($ix%3));
   $x=$xright;
   $pdf->SetFont("berthold","",8);
   $pdf->writeHTMLCell("","",$x,$y,"<b>Cast:</b>",0,1,0,true,"L");
   $y=$pdf->GetY();
   $pdf->SetFont("berthold","",6.5);
   $pdf->writeHTMLCell("","",$x,$y,"$cast",0,1,0,true,"L");
   $y=$pdf->GetY();
   $y+=3;
   $pdf->SetFont("berthold","",8);
   $x=$origx; $y=$ybottom;
   $pdf->writeHTMLCell("","",$x,$y,"<b>Technical Crew:</b>",0,1,0,true,"L");
   $y=$pdf->GetY();
   $pdf->SetFont("berthold","",6.5);
   $width=$teamphotowidth; //216-$x-$origx;
   $pdf->writeHTMLCell($width,"",$x,$y,"$crew",0,0,0,true,"L");

   $ix++;
}	//END FOR EACH SCHOOL WITH APPROVED DATA
      //OUTPUT PDF FILE
      $pdffilename=$sportname."_for_Program_".$sid."_".$othersid1."_".$othersid2.".pdf";
      if(!$pdf->Output("../downloads/$pdffilename", "I")) echo "OUTPUT ERROR";
?>
