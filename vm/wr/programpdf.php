<?php
/*************************
1/19/16
Dynamic creation of PDF for Printer.
This is a 4-schools-per-page version.
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

$sport="wr";
$sportname="Wrestling";
$table="wrd";

$year=GetFallYear('wr');

   //include PDF creation tool:
   require_once('../../tcpdf/tcpdf.php');

   //INITIALIZE PDF
   $pdf = new TCPDF("P", PDF_UNIT, "LETTER", true); //LETTER = 8.5 x 11 in or 216 x 280 mm
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   //$pdf->SetMargins(0,0);
   $pdf->SetAutoPageBreak(FALSE, 1);
   $pdf->setLanguageArray($l);
//   $pdf->AliasNbPages();
   $pdf->AddPage();

//TESTING: Valentine, East Butler, Battle Creek, O'Neill
//$sid1=500; $sid2=104; $sid3=153; $sid4=467;

$sql0="SELECT * FROM wrschool WHERE (sid='$sid1' OR sid='$sid2' OR sid='$sid3' OR sid='$sid4') ORDER BY programorder,school";
$result0=mysql_query($sql0);
if(mysql_error()) 
{
   echo $sql0."<br>".mysql_error();
   exit();
}
$ix=0; $page=0; $origx=5; $origy=5;
$halfx=108; //half of 216
$halfy=140; //half of 280
while($row0=mysql_fetch_array($result0))	//FOR EACH TEAM
{
   //PREP THE INFORMATION:
   $school=GetMainSchoolName($row0[sid],$sport);
   $sid=$row0[sid];
   $schoolid=$row0[mainsch];
   $school2=ereg_replace("\'","\'",$school);
   $schoolname=$row0[school];
   $sql2="SELECT * FROM headers WHERE school='$school2'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $conference=$row2[conference];
   $enrollment=$row2[enrollment];
   $colors=GetColors($schoolid,'wr');
   $mascot=GetMascot($schoolid,'wr');
   $coach=GetCoaches($schoolid,'wr');
   $asst=GetAsstCoaches($schoolid,'wr');

   if($ix%2==0)	//LEFTMOST
      $x=$origx;
   else		//RIGHTMOST
      $x=$halfx;
   if($ix<2)	//TOP ROW
      $y=$origy;
   else		//BOTTOM ROW
      $y=$halfy;

   $pdf->SetFont("berthold","B","14");
   $pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0,0,0);
	//first do a blank white box at top to cover the header line
	//(if this is first one on the page)
   if($x==$origx && $y==$origy)
      $pdf->writeHTMLCell("216","10",0,0,"",0,1,1,true,"C");
	//NOW write the HTML:
   $pdf->writeHTMLCell("98","",$x,$y,"<span style=\"font-weight:bold;\">".strtoupper("$schoolname")."</span>",0,1,1,true,"L");
   $y=$pdf->GetY();
   $pdf->SetFont("berthold","","11");
   $pdf->writeHTMLCell("98","",$x,$y,"$colors | $mascot",0,1,1,true,"L");

   //THE ROSTER:
   $smallw=40; $count=0;
   $html="<table cellspacing=\"0\" cellpadding=\"0\"><tr align=\"center\">
<td align=\"left\" width=\"$smallw\"><b>Wt Class</b></td><td align=\"left\" width=\"100\"><b>Name</b></td><td width=\"$smallw\"><b>Grade</b></td><td width=\"$smallw\"><b>Wins</b></td><td width=\"$smallw\"><b>Losses</b></td></tr>";
   $sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$school2' OR t1.co_op='$school2') AND t1.checked='y' ORDER BY t1.weight";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
        $last=$row[last];
        if($row[nickname]!='') $first=$row[nickname];
        else $first=$row[first];
        $grade=GetYear($row[semesters]);
        $wl=explode("-",$row[record]);
        $html.="<tr align=\"center\"><td align=\"left\" width=\"$smallw\">$row[weight]&nbsp;</td><td width=\"100\" align=\"left\">$first $last</td><td width=\"$smallw\">$grade</td><td width=\"$smallw\">$wl[0]</td><td width=\"$smallw\">$wl[1]</td></tr>";
        $count++;
   }
   $html.="</table>";
   $pdf->SetFont("berthold","",7);
   $y=$pdf->GetY(); $y+=2;
   $pdf->writeHTMLCell(98,"",$x,$y,$html,0,1,1,true,"L");

   //SCHOOL AND HISTORICAL INFO:
	/*
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
	*/
        //AD
      $sql="SELECT name FROM logins WHERE school='$school2' AND level='2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      //$html.="<tr align='left'><td><b>Athletic Director:</b>&nbsp;&nbsp;$row[name]</td></tr>";

      	//Coaches
      $html="<b>Head Coach:</b> $coach<br>";
	//Asst Coaches
      $html.="<b>Assistant Coaches:</b> $asst";
      $y=$pdf->GetY(); $y+=2;
      $pdf->writeHTMLCell("46","",$x,$y,$html,0,1,1,true,"L");
	$yleft=$pdf->GetY();
	//Enrollment
      $html="<b>NSAA Enrollment:</b> $enrollment<br>";
        //Conference
      $html.="<b>Conference:</b> $conference<br>";
      $sql="SELECT * FROM wrschool WHERE sid='$sid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
        //Trips to State: 4
      $html.="<b>Qualifications for State:</b>&nbsp;";
      if(trim($row[tripstostate])!='') $html.="$row[tripstostate]";
      else $html.="0";
      
      $x2=$x+49;
      $pdf->writeHTMLCell("49","",$x2,$y,$html,0,1,1,true,"L");
	$yright=$pdf->GetY();

        //Most Recent: 
	/*
      if(trim($row[mostrecent])!='')
         $html.="<tr align='left'><td><b>Most Recent State Tournament:</b>&nbsp;$row[mostrecent]</td></tr>";
        //Championships: None
      if(trim($row[championships])!='')
         $html.="<tr align='left'><td><b>State Championship Years:</b>&nbsp;$row[championships]</td></tr>";
        //Runner-up: B/2008, B/2010
      if(trim($row[runnerup])!='')
         $html.="<tr align='left'><td><b>Runner-up:</b>&nbsp;$row[runnerup]</td></tr>";
	*/
      $html.="</table>";

   //THE SCHEDULE
   $year2=$year+1;
   $sql="SELECT DISTINCT * FROM wrdsched WHERE sid='$sid' AND (received='$year-00-00' OR (received>='$year-08-01' AND received<'$year2-08-01')) AND oppscore IS NOT NULL AND sidscore IS NOT NULL ORDER BY received";
   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   $ct++; 	//header row
   if($ct%2==0) $percol=$ct/2;
   else   $percol=ceil($ct/2);
   $html="<table cellspacing=\"0\" cellpadding=\"0\"><tr align=\"center\"><td align=\"left\" width=\"80\"><b>Opponent</b></td><td width=\"20\"><b>W/L</b></td><td width=\"20\"><b>Score</b></td></tr>";
   $curcol=1;
   if($yleft>$yright) $y=$yleft;
   else $y=$yright;
   $y+=2;
   while($row=mysql_fetch_array($result))
   {
      if($curcol==$percol)
      {
	 $html.="</table>";
         $pdf->writeHTMLCell("46","",$x,$y,"$html",0,0,0,true,"L");
	 $html="<table cellspacing=\"0\" cellpadding=\"0\">";
      }
      if($sid==$row[sid])
      {
         $sidscore=$row[sidscore]; $oppid=$row[oppid]; 
         $oppscore=$row[oppscore];
      }
      else
      {
	 $sidscore=$row[oppscore]; $oppid=$row[sid];
  	 $oppscore=$row[sidscore];
      }
      if($sidscore>$oppscore) $wl="W";
      else $wl="L";
      $oppname=GetSchoolName($oppid,'wr',$year,TRUE);
      if(strlen($oppname)>20) $oppname=substr($oppname,0,20);
      $html.="<tr align=\"center\"><td align=\"left\" width=\"80\">".$oppname."</td><td width=\"20\">$wl</td><td width=\"20\">$sidscore-$oppscore</td></tr>";
      $curcol++;
   }
   $html.="</table>"; 
   $x2=$x+49;
   $pdf->writeHTMLCell("49","",$x2,$y,"$html",0,0,0,true,"L");

   $ix++;
}	//END FOR EACH SCHOOL WITH APPROVED DATA
      //OUTPUT PDF FILE
      $pdffilename=$sportname."_Rosters_for_Program_".$sid1."_".$sid2."_".$sid3."_".$sid4.".pdf";
      if(!$pdf->Output("../downloads/$pdffilename", "I")) echo "OUTPUT ERROR";
?>
