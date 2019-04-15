<?php
/*************************
view_wrd.php
View Dual Wrestling Form
and Make Program PDF
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
   $sql="SELECT * FROM $db_name2.wrdistricts WHERE hostid='$hostid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      echo "You are not the host of this school's district.";
      exit();
   }
}
$school2=ereg_replace("\'","\'",$school);
$sid=GetSID2($school,'wr');
$sport='wr';

if($makepdf)    //GET OTHER 3 SCHOOLS TO GO ON THIS PAGE
{
   $sql="SELECT programorder,class,approvedforprogram FROM wrschool WHERE sid='$sid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $order=$row[programorder]; $class=$row['class'];
   $approved=$row[approvedforprogram];
   //GET OTHER SCHOOL ON SAME PAGE ($sid2, $sid3, $sid4)
   if($order%4==0)      //THIS SCHOOL IS THE 4th ONE ON THE PAGE
   {
      $order2=$order-3; $order3=$order-2; $order4=$order-1;
   }
   else if($order%4==3) //3rd
   {
      $order2=$order-2; $order3=$order-1; $order4=$order+1;
   } 
   else if($order%4==2) //2nd
   {
      $order2=$order-1; $order3=$order+1; $order4=$order+2;
   } 
   else                 //ELSE IT IS THE FIRST ONE ON THE PAGE
   {
      $order2=$order+1; $order3=$order+2; $order4=$order+3;
   }
   $sql="SELECT * FROM wrschool WHERE class='$class' AND programorder='$order2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sid2=$row[sid]; $approved2=$row[approvedforprogram];
   $sql="SELECT * FROM wrschool WHERE class='$class' AND programorder='$order3'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sid3=$row[sid]; $approved3=$row[approvedforprogram];
   $sql="SELECT * FROM wrschool WHERE class='$class' AND programorder='$order4'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sid4=$row[sid]; $approved4=$row[approvedforprogram];

   //IF $makepdf && NOT NSAA - CHECK THAT ALL SID's HAVE BEEN APPROVED FOR PROGRAM
   if($level!=1)
   {
      if((!$sid2 || !$sid3 || !$sid4) && $approved>0)    //CAN'T FIND ONE OF THEM
      {
         echo $init_html;
         echo "<table style=\"width:100%;\"><tr align=center><td><div style=\"width:600px;\">";
         echo "<br><br><div class='error'>The teams that will share this page with ".GetSchoolName($sid,$sport)." have not been indicated yet by the NSAA. The NSAA will need to mark teams as being in positions <b><u>$order2, $order3 and $order4</b></u> for Class $class in order for this page to be previewed.</div>";
         echo "<br><br><a href=\"javascript:window.close();\">Close</a></div>";
         echo $end_html;
         exit();
      }
      if(!$approved || !$approved2 || !$approved3 || !$approved4)
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
   header("Location:programpdf.php?session=$session&sid1=$sid&sid2=$sid2&sid3=$sid3&sid4=$sid4&viewdistrict=$viewdistrict");
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
$duedate=GetDueDate("wrd");
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";
$state=1;
$table="wrd";

//check if this form has already been submitted:
$sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$school2' OR t1.co_op='$school2') AND t1.checked='y' ORDER BY t1.weight,t2.last,t2.first";
$result=mysql_query($sql); 
  //if it hasn't been submitted, redirect to Edit page:
if(mysql_num_rows($result)==0)
{
   if($director!=1)
      header("Location:edit_wrd.php?session=$session&school_ch=$school_ch");
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
      echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Wrestling#3\">Return to Home &rarr; Wrestling Entry Forms</a><br>";
}

//Get Coaches, Mascot & Colors
$coach=GetCoaches($schoolid,'wr');
$asst=GetAsstCoaches($schoolid,'wr');
$mascot=GetMascot($schoolid,'wr');
$colors=GetColors($schoolid,'wr');
$sid=GetSID2($school,'wr');
$class=GetClass($sid,'wr');
$sql="SELECT * FROM wrschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(mysql_num_rows($result) && citgf_file_exists("../downloads/".$row[filename]))
{
   $teamphoto=$row[filename];
}
else
{
   $teamphoto="";
}
$schid=GetSchoolID2($school);

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
    $string.="<br><a href=\"view_wrd.php?session=$session&school_ch=$school_ch&print=1\" class=small target=new>Printer-Friendly Version</a>&nbsp;&nbsp;&nbsp;&nbsp;";
    $string.="<a href=\"edit_wrd.php?session=$session&school_ch=$school_ch\" class=small>Edit this Form</a><br><br>";

$sql2="SELECT submitted FROM $table WHERE submitted>0 AND school='$school2'";
$result2=mysql_query($sql2);
if(mysql_num_rows($result2)>0)
{
   $row2=mysql_fetch_array($result2);
   echo "<br><div class='alert'><p><b>You submitted your STATE TOURNAMENT ROSTER to the NSAA on ".date("m/d/Y",$row2[0])." at ".date("g:ia T",$row2[0]).".</b></p>";
   echo "<p>If you need to make another change, you may do so by clicking <a class=small href=\"edit_wrd.php?school_ch=$school_ch&session=$session\">Edit This Form</a>, making the necessary updates, <u>checking the box at the bottom</u> of the page, and submitting this form to the NSAA again.</p><p>";
   echo "Otherwise, your last submission will be considered final, and will be the information used for the NSAA State Tournament Program.</p></div>";
   echo "<br>";
}
else
   echo "<br><div class='alert'><p>You have not yet submitted your State Tournament Roster to the NSAA. Once you have completed your roster and entered your regular season results, you must submit this completed for to the NSAA by checking the box at the bottom of the <a href=\"edit_wrd.php?session=$session&school_ch=$school_ch\">Edit this Form</a> screen.</p></div>";

}
$string.="<h3>NSAA Dual Wrestling Roster Form</h3><table>";
$string.="<tr align=left><td>";
$string.="<table cellspacing=2 cellpadding=2>";
$string.="<tr align=left><th align=left>School/Mascot:</th>";
$string.="<td>".GetSchoolName($sid,'wr')." $mascot</td></tr>";
$csv.="School/Mascot:,".GetSchoolName($sid,'wr')." $mascot\r\n";
$string.="<tr align=left><th align=left>School Colors:</th><td>$colors</td></tr>";
$string.="<tr align=left><th align=left>$stateassn-Certified Coach:</th><td>$coach</td></tr>";
$string.="<tr align=left><th align=left>Assistant Coaches:</th><td>$asst</td></tr>";
$string.="<tr align=left><th align=left>Class:</th><td>$row2[class]</td></tr>";
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
        //Conference
      $string.="<tr align=\"left\"><th>Conference:</th><td>$conference</td></tr>";
      $sql2="SELECT * FROM wrschool WHERE sid='$sid'";
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
        <form method=\"post\" action=\"view_wrd.php\" target=\"_blank\">
        <input type=hidden name=\"session\" value=\"$session\">
        <input type=hidden name=\"school_ch\" value=\"$school\">
        <div id=\"pdflink\" style=\"margin:10px;\"></div><input type=submit name=\"makepdf\" value=\"Preview State Program Page (PDF)\">
        </form></td></tr>";
      }
} //END IF LEVEL 1
$string.="</table></td></tr>";
$csv.="School Colors:,$colors\r\n";
$csv.="Class:,$class\r\n";
$string.="<tr align=center><td><br>";
$string.="<table width='100%' cellpadding=5 cellspacing=0 class='nine' frame=all rules=all style='border:#808080 1px solid;'>";
$string.="<tr align=center><th class=smaller>Name</th>";
$string.="<th class=smaller>Grade</th><th class=smaller>Weight</th>";
$string.="<th class=smaller>Record</th></tr>";
if($makepdf)
{
   $smallw=35;
   $html="<table cellspacing=\"0\" cellpadding=\"1\"><tr align=\"center\">
        <td width=\"85\"><b>Name</b></td><td width=\"$smallw\"><b>Grade</b></td><td width=\"$smallw\"><b>Weight</b></td><td width=\"$smallw\"><b>Record.</b></td></tr>";
}
$csv.="Name,Grade,Weight,Wins,Losses\r\n";

$sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM $table AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$school2' OR t1.co_op='$school2') AND t1.checked='y' ORDER BY t1.weight,t2.last,t2.first"; 
$result=mysql_query($sql);
$count=0;
while($row=mysql_fetch_array($result))
{
  if($row[checked]=="y")	//that student was checked to be on the roster
  {
     $string.="<tr align=left>";
     $last=$row[last];
      if($row[nickname]!='') $first=$row[nickname];
     else $first=$row[first];
     $string.="<td>$first $last";
     $string.="</td>";
     $year=GetYear($row[semesters]);
     $string.="<td>$year</td>";
     $string.="<td>$row[weight]</td>";
     $string.="<td>$row[record]</td>";
     $string.="</tr>";
     $rec=explode("-",$row[record]);
     $csv.="$row[first] $row[last],$year,$row[weight],$rec[0],$rec[1]\r\n";
     $html.="<tr align=\"center\"><td width=\"85\" align=\"left\">$first $last</td><td width=\"$smallw\">$year</td><td width=\"$smallw\">$row[weight]</td><td width=\"$smallw\">$row[record]</td></tr>";
     $count++;
  }
}
$string.="</table></td></tr>";
/****** DUAL MEETS FROM THEIR SCHEDULE *******/
$sql="SELECT * FROM wrdsched WHERE sid='$sid' ORDER BY received";
$result=mysql_query($sql);
$string.="<tr align=center><td><br><h3>Dual Wrestling Schedule for $school:</h3>";
$string.="<table cellspacing=0 cellpadding=5 frame='all' rules='all' style=\"border:#808080 1px solid;\"><tr align=center><th>Opponent</th><th>W/L</th><th>Score</th></tr>";
$i=0;
while($row=mysql_fetch_array($result))
{
   if($sid==$row[sid])
   {
      $oppname=GetSchoolName($row[oppid],'wr');
      $oppscore=$row[oppscore];
      $sidscore=$row[sidscore];
      $oppsid=$row[oppid];
   }
   else
   {
      $oppname=GetSchoolName($row[sid],'wr');
      $oppscore=$row[sidscore];
      $sidscore=$row[oppscore];
      $oppsid=$row[sid];
   }
   if($sidscore>$oppscore) $winloss="W";
   else if($oppscore>$sidscore) $winloss="L";
   $date=explode("-",$row[received]);
   $string.="<tr align=center><td align=left>$oppname</td><td>$winloss</td><td>$sidscore-$oppscore</td></tr>";
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

$sched=GetSchedule($sid,'wrd');
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
         $csv.="\"".GetSchoolName($sched[oppid][$i],'wr')."\",\"";
         $score=split("-",$sched[score][$i]);
         $html.="<tr valign=\"bottom\" align=\"left\"><td width=\"100\">".ConfigureSchoolForProgramSchedule(GetSchoolName($sched[oppid][$i],'wr'),35)."</td>";
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
      $sql="SELECT * FROM wrschool WHERE sid='$sid'";
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

      $pdffilename=preg_replace("/[^0-9a-zA-Z]/","",$schoolname)."_".strtoupper($sport)."Dual.pdf";
      $pdf->Output("../downloads/$pdffilename", "I");
      $pdflink="<a href=\"../downloads/$pdffilename\" target=\"_blank\" class=\"small\">Preview PDF</a>";
   }


$sql="SELECT * FROM wrschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
//add coaches,etc info to excel file
$csv.="\r\n\"Head Coach:\",$coach\r\n";
$csv.="\"Assistant Coaches:\",\"$asst\"\r\n";
$csv.="\"NSAA Enrollment:\",\"$enrollment\"\r\n";
$csv.="\"Conference:\",\"$conference\"\r\n";
$csv.="\"State Tournament Appearances:\",\"$row[tripstostate]\"\r\n";
$csv.="\"Most Recent State Tournament:\",\"$row[mostrecent]\"\r\n";
$csv.="\"State Championship Years:\",\"$row[championships]\"\r\n";
$csv.="\"State Runner-Up Years:\",\"$row[runnerup]\"\r\n";

if($print!=1 && !$makepdf)
{
?>
<tr align=center>
<td><br>
    <a href="view_wrd.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>&print=1" target=new class=small>Printer-Friendly Version</a>
    &nbsp;&nbsp;&nbsp;
    <a href="edit_wrd.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>" class=small>Edit this Form</a>
    &nbsp;&nbsp;&nbsp;
    <a href="../welcome.php?session=<?php echo $session; ?>" class=small>Home</a>
</td>
</tr>
<?php
}
   //Write to .html and .csv file to be sent/e-mailed:
   $string.="</table></td></tr></table></body></html>";
   $activ="Dual Wrestling";
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
   <input type=hidden name=state value="<?php echo $state; ?>">
   <input type=hidden name=session value="<?php echo $session; ?>">
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
