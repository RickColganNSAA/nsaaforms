<?php
/*********************************************
createcertificate.php
Dynamically Create PDF Music Award Certificate
Created 4/19/09
Author Ann Gaffigan
**********************************************/
require '../functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

$database="nsaascores";

//include PDF creation tool:
require_once('../../tcpdf/tcpdf.php');

if(!ValidUser($session))
{
   header("Location:../index.php?error=1");
   exit();
}
$level=GetLevel($session);
if($level==2 || $level==3)
{
   $schoolid=GetSchoolID($session); $loginid=GetUserID($session);
   $mudistid=GetMusicDistrictID($schoolid,$loginid);
   if($mudistid && $siteid)
      $musiteid=$siteid;
   else
      $musiteid=GetMusicSiteID($schoolid);
}
else if(!$siteid)
{
      $loginid=GetUserID($session);
      $musiteid=GetMusicSiteID(0,$loginid);
      $mudistid=GetMusicDistrictID(0,$loginid);
}
else $musiteid=$siteid;
if(!$musiteid && $level!=1)
{
   echo "ERROR: No Music Site Indicated $mudistid $siteid";
   exit();
}

if($preview || $download)
{
   //CREATE PDFs: Black & White, then Color
   $orientation="L";
   $pdf = new TCPDF($orientation, PDF_UNIT, PDF_PAGE_FORMAT, true);
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   $pdf->SetMargins(5,5);
   $pdf->SetAutoPageBreak(TRUE, 1);
   $pdf->setLanguageArray($l);
   $pdf->AddPage();
   //$pdf->Rect(7,7,282,196,"","all");
   $pdf->SetFont("helvetica","",14);
   $pdf->Image("../../images/nsaalogoblackwhite.gif",118,8,60);
   if($hm=="yes")
      $pdf->Image("../../images/mucerttitlehm.gif",39,50,220);
   else
      $pdf->Image("../../images/mucerttitle.gif",39,50,220);
   $pdf->SetXY(10,70);
   if(!ereg(",",$students))	//1 student
   {
      $fontsize=28; $align="C"; $break="0";
   }
   else if(strlen($students)<=50)
   {
      $fontsize=24; $align="C"; $break="0";
   }
   else if(strlen($students)<=100)
   {
      $fontsize=22; $align="C"; $break="3";
      $studs=split(",",$students);
      if(count($studs)<=4) $break="2";
   }
   else if(strlen($students)<=150)
   {
      $fontsize=18; $align="C"; $break="3";
   }
   else if(strlen($students)<=200)
   {
      $fontsize=16; $align="C"; $break="4";
   }
   else if(strlen($students)<=250)
   {
      $fontsize=14; $align="C"; $break="4";
   }
   else if(strlen($students)<=300)
   {
      $fontsize=12; $align="C"; $break="4";
   }
   else 
   {
      $fontsize=10; $align="C"; $break="5"; 
   }
   $studentstop=90;
   if($break>0)	//set up line breaks between names
   {
      $studs=split(",",$students);
      $students="";
      for($i=0;$i<count($studs);$i++)
      {
	 if(($i%$break)==0 && $i>0) $students.="<br>";
         if(trim($studs[$i])!='') $students.=trim($studs[$i]).", ";
      }
      if($students!='') $students=substr($students,0,strlen($students)-2);
      if(substr($students,count($students)-1,1)==",")
	 $students=substr($students,0,strlen($students)-1);
      $studentstop-=ceil(count($studs)/$break);
   }
   $pdf->SetFont("helvetica","",$fontsize);
   $students=ereg_replace("\"","\"",$students);
   $pdf->writeHTMLCell("200","","49",$studentstop,"$students",0,0,0,true,"C");
   $pdf->SetFont("helvetica","","16");
   $x=49; $y=120;
   $pdf->writeHTMLCell("200","",$x,$y,"<b>$school High School</b>",0,0,0,true,"C");
   $pdf->SetFont("helvetica","","14");
   $y+=10;
   if($director=="other") $director=$other;
   $pdf->writeHTMLCell("200","",$x,$y,"<u>&nbsp;&nbsp;$director&nbsp;&nbsp;</u>",0,0,0,true,"C");
   $y+=7;
   $pdf->SetFont("helvetica","","12");
   $pdf->writeHTMLCell("200","",$x,$y,"Music Director",0,0,0,true,"C");
   $y+=10;
   $pdf->SetFont("helvetica","","16");
   $pdf->writeHTMLCell("200","",$x,$y,"<b>FOR YOUR PERFORMANCE AT NSAA DISTRICT MUSIC CONTEST</b>",0,0,0,true,"C");
   $y+=10;
   $pdf->SetFont("helvetica","","16");
   $pdf->writeHTMLCell("200","",$x,$y,"<b>$event</b>",0,0,0,true,"C");
   $pdf->SetFont("helvetica","","14");
   $y+=10;
   $pdf->writeHTMLCell("200","",$x,$y,"<u>&nbsp;&nbsp;$site&nbsp;&nbsp;</u>",0,0,0,true,"C");
   $y+=7;
   $pdf->SetFont("helvetica","","12");
   $pdf->writeHTMLCell("200","",$x,$y,"Contest Site",0,0,0,true,"C");
   //EXEC DIRECTOR SIGNATURE:
   $x=41; $y=173;
   $pdf->Image("../../images/jay.png",$x,$y,70);
   $pdf->SetFont("helvetica","","14");
   $y+=17;
   $pdf->writeHTMLCell("70","",$x,$y,"Jay Bellar<br>NSAA Executive Director",0,0,0,true,"C");
   //DATE:
   $x=200; $y-=10;
   $pdf->writeHTMLCell("70","",$x,$y,"<u>&nbsp;&nbsp;&nbsp;$certdate&nbsp;&nbsp;&nbsp;</u>",0,0,0,true,"C");
   $y+=8;
   $pdf->SetFont("helvetica","","14");
   $pdf->writeHTMLCell("70","",$x,$y,"Date",0,0,0,true,"C");
   $filename1="certificates/mucert".$musiteid."blackwhite";
   citgf_unlink($filename1);
   if($preview) $pdf->Output("$filename1.pdf", "I");
   else if($bwc=="blackwhite") $pdf->Output("$filename1.pdf","O");
} elseif($preview2)
   {
   //COLOR:
   $orientation="L";
   $pdf = new TCPDF($orientation, PDF_UNIT, PDF_PAGE_FORMAT, true);
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   $pdf->SetMargins(5,5);
   $pdf->SetAutoPageBreak(TRUE, 1);
   $pdf->setLanguageArray($l);
   $pdf->AddPage();
   //$pdf->Rect(7,7,282,196,"","all");
   $pdf->SetFont("helvetica","",14);
   $pdf->Image("../../images/nsaalogocolor.png",122,8,50);
   if($hm=="yes")
      $pdf->Image("../../images/mucerttitlehm.gif",39,50,220);
   else
      $pdf->Image("../../images/mucerttitle.gif",39,50,220);
   $pdf->SetXY(10,70);
   if(!ereg(",",$students))	//1 student
   {
      $fontsize=28; $align="C"; $break="0";
   }
   else if(strlen($students)<=50)
   {
      $fontsize=24; $align="C"; $break="0";
   }
   else if(strlen($students)<=100)
   {
      $fontsize=22; $align="C"; $break="3";
      $studs=split(",",$students);
      if(count($studs)<=4) $break="2";
   }
   else if(strlen($students)<=150)
   {
      $fontsize=18; $align="C"; $break="3";
   }
   else if(strlen($students)<=200)
   {
      $fontsize=16; $align="C"; $break="4";
   }
   else if(strlen($students)<=250)
   {
      $fontsize=14; $align="C"; $break="4";
   }
   else if(strlen($students)<=300)
   {
      $fontsize=12; $align="C"; $break="4";
   }
   else 
   {
      $fontsize=10; $align="C"; $break="5"; 
   }
   $studentstop=90;
   if($break>0)	//set up line breaks between names
   {
      $studs=split(",",$students);
      $students="";
      for($i=0;$i<count($studs);$i++)
      {
	 if(($i%$break)==0 && $i>0) $students.="<br>";
         if(trim($studs[$i])!='') $students.=trim($studs[$i]).", ";
      }
      if($students!='') $students=substr($students,0,strlen($students)-2);
      if(substr($students,count($students)-1,1)==",")
	 $students=substr($students,0,strlen($students)-1);
      $studentstop-=ceil(count($studs)/$break);
   }
   $pdf->SetFont("helvetica","",$fontsize);
   $students=ereg_replace("\"","\"",$students);
   $pdf->writeHTMLCell("200","","49",$studentstop,"$students",0,0,0,true,"C");
   $pdf->SetFont("helvetica","","16");
   $x=49; $y=120;
   $pdf->writeHTMLCell("200","",$x,$y,"<b>$school High School</b>",0,0,0,true,"C");
   $pdf->SetFont("helvetica","","14");
   $y+=10;
   if($director=="other") $director=$other;
   $pdf->writeHTMLCell("200","",$x,$y,"<u>&nbsp;&nbsp;$director&nbsp;&nbsp;</u>",0,0,0,true,"C");
   $y+=7;
   $pdf->SetFont("helvetica","","12");
   $pdf->writeHTMLCell("200","",$x,$y,"Music Director",0,0,0,true,"C");
   $y+=10;
   $pdf->SetFont("helvetica","","16");
   $pdf->writeHTMLCell("200","",$x,$y,"<b>FOR YOUR PERFORMANCE AT NSAA DISTRICT MUSIC CONTEST</b>",0,0,0,true,"C");
   $y+=10;
   $pdf->SetFont("helvetica","","16");
   $pdf->writeHTMLCell("200","",$x,$y,"<b>$event</b>",0,0,0,true,"C");
   $pdf->SetFont("helvetica","","14");
   $y+=10;
   $pdf->writeHTMLCell("200","",$x,$y,"<u>&nbsp;&nbsp;$site&nbsp;&nbsp;</u>",0,0,0,true,"C");
   $y+=7;
   $pdf->SetFont("helvetica","","12");
   $pdf->writeHTMLCell("200","",$x,$y,"Contest Site",0,0,0,true,"C");
   //EXEC DIRECTOR SIGNATURE:
   $x=41; $y=173;
   $pdf->Image("../../images/jay.png",$x,$y,70);
   $pdf->SetFont("helvetica","","14");
   $y+=17;
   $pdf->writeHTMLCell("70","",$x,$y,"Jay Bellar<br>NSAA Executive Director",0,0,0,true,"C");
   //DATE:
   $x=200; $y-=10;
   $pdf->writeHTMLCell("70","",$x,$y,"<u>&nbsp;&nbsp;&nbsp;$certdate&nbsp;&nbsp;&nbsp;</u>",0,0,0,true,"C");
   $y+=8;
   $pdf->SetFont("helvetica","","14");
   $pdf->writeHTMLCell("70","",$x,$y,"Date",0,0,0,true,"C");
   $filename2="certificates/mucert".$musiteid."color";
   citgf_unlink($filename2);
   if($preview2) $pdf->Output("$filename2.pdf", "I");
   else if($bwc=="color") $pdf->Output("$filename2.pdf","O");

   if($preview)	//show link to Download or go back and make changes
   {
      echo $init_html;
      echo GetHeader($session);
      echo "<br><br><br><table width='600px' class='nine' cellspacing=2 cellpadding=2><tr align=left><td><b>Your certificate has been created.</b><br><br>To <b><i>preview</b></i> the certificate, click: <a href=\"$filename1.pdf\" target=\"_blank\">Preview (Black & White)</a> or <a href=\"$filename2.pdf\" target=\"_blank\">Preview (Color)</a><br><br>If you are <b><i>satisfied</i></b> with the certificate, you may now print it:<br><input type=button name='printingtips' value='Printing Tips' onClick=\"window.open('../printingtips.php','Printing_Tips','width=500,height=350');\">";
      echo "<br><br>If you need to <b><i>make changes</b></i> to the certificate, <a href=\"javascript:history.go(-1);\">Go Back</a> and do so.<br><br>To return to the <u>List of Music District Award Winners</u> and <b>print more certificates</b>, <a href=\"viewawardwinners.php?session=$session&musiteid=$musiteid\">Click Here</a>.<br><br>If you need to <b>manually generate a certificate</b> from scratch, <a href=\"createcertificate.php?siteid=$siteid&session=$session\">Click Here</a>.</td></tr></table><br><br>";
      if($level==1)
         echo "<a href=\"muadmin.php?session=$session\">Return to Music District Entry Form Admin</a>";
      echo $end_html;
      exit();
   }
   else exit();
}
else
{
   //Get Information to Pre-Populate Form with:
   $sql="SELECT * FROM $database.mudistricts WHERE id='$musiteid'";
   $result=mysql_query($sql);
   $row2=mysql_fetch_array($result);
   $row2[director]=ereg_replace(", District Music Contest","",$row2[director]);

   echo $init_html;
   echo GetHeader($session);
   echo "<br><form method=post action=\"createcertificate.php\">";
   echo "<input type=hidden name=\"session\" value=\"$session\">";
   echo "<input type=hidden name=\"siteid\" value=\"$siteid\">";
   echo "<a href=\"viewawardwinners.php?session=$session&musiteid=$musiteid\">Return to Complete List of Award Winners for this Site</a><br><br>";
   echo "<table class=nine cellspacing=2 cellpadding=3><caption><b>NSAA District Music Contest Outstanding Performance Award Certificate:</b></caption>";
   if($mudistid && $siteid)
   {
         echo "<tr align=center><td colspan=2>Select District Site: ";
         echo "<select name=\"siteid\" onchange=\"submit();\"><option value='0'>Select District Site</option>";
         $sql="SELECT t1.* FROM $database.mudistricts AS t1,mubigdistricts AS t2 WHERE t1.distnum=t2.distnum AND t2.id='$mudistid' ORDER BY t1.classes";
         $result=mysql_query($sql);
         while($row=mysql_fetch_array($result))
         {
            echo "<option value=\"$row[id]\"";
            if($siteid==$row[id]) echo " selected";
            echo ">$row[distnum] -- $row[classes] at $row[site]</option>";
         }
         echo "</select>&nbsp;<input type=submit name=\"go\" value=\"Go\">";
	 echo "</td></tr>";
   }
   if($site!='') $row2[site]=$site;
   echo "<tr align=left><td colspan=2><div class=\"alert\" style=\"font-size:9pt;\"><b><i>Please fill out the information below as it should appear on the certificate:</i></b></div></td></tr>";
   echo "<tr align=left><td>Show District Contest Site as:</td><td><input type=text size=50 name=\"site\" value=\"$row2[site]\"></td></tr>";
   echo "<tr valign=top align=left><td>Name(s) of Student(s):</td><td><textarea name=\"students\" rows=\"5\" cols=\"40\">$students</textarea></td></tr>";
   echo "<tr align=left><td>School:</td><td><select name=\"school\" onchange=\"submit();\"><option value=\"\">Select School</option>";
   $sql="SELECT * FROM $database.muschools WHERE distid='$musiteid'";
   //PART OF A COMBO SITE? 
      $sqlM="SELECT id FROM $database.mudistricts WHERE distid1='$musiteid' OR distid2='$musiteid'";
      $resultM=mysql_query($sqlM);
      while($rowM=mysql_fetch_array($resultM))
      {
	 $sql.=" OR distid='$rowM[id]'";
      }
   if($musiteid==0) $sql="SELECT * FROM $database.headers";
   $sql.=" ORDER BY school";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[school]\"";
      if($school==$row[school]) echo " selected";
      echo ">$row[school]</option>";
      //CHECK IF THIS SCHOOL IS HEAD OF A CO-OP
      $sqlC="SELECT * FROM $database.mucoops WHERE mainsch='".addslashes($row[school])."'";
      $resultC=mysql_query($sqlC);
      if(mysql_num_rows($resultC)>0)
      {
         $rowC=mysql_fetch_array($resultC);
	 if($rowC[othersch1]!='')
         {
	    echo "<option value=\"$rowC[othersch1]\"";
	    if($school==$rowC[othersch1]) echo " selected";
	    echo ">$rowC[othersch1]</option>";
         }
         if($rowC[othersch2]!='')
         {
            echo "<option value=\"$rowC[othersch2]\"";
            if($school==$rowC[othersch2]) echo " selected";
            echo ">$rowC[othersch2]</option>";
         }
      }
   }
   echo "</select></td></tr>";
   echo "<tr align=left valign=top><td>Director's Name:<br><i>(select school first)</i></td><td><select name=\"director\"";
   if($school && $school!='')
   {
      echo "><option value=''>Select Director</option>";
      $school2=addslashes($school);
      $sql="SELECT DISTINCT name FROM $database.logins WHERE school='$school2' AND sport LIKE '%Music%'";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         echo "<option value=\"$row[name]\"";
	 if($director==$row[name]) echo " selected";
	 echo ">$row[name]</option>";
      }
      echo "<option value=\"other\"";
      if($director=="other") echo " selected";
      echo ">Other (specify below)</option></select><br>";
      echo "&nbsp;&nbsp;&nbsp;If <i>Other</i>, specify: <input type=text size=30 name=\"other\" value=\"$other\">";
      echo "</td></tr>";
   }
   else echo " disabled></select></td></tr>";
   echo "<tr align=left><td>Event:</td><td><select name=\"event\"><option value=\"\">Select Event</option>";
   echo "<option value=\"Instrumental Solo\"";
   if($event=="Instrumental Solo") echo " selected";
   echo ">Instrumental Solo</option><option value=\"Instrumental Ensemble\"";
   if($event=="Instrumental Ensemble") echo " selected";
   echo ">Instrumental Ensemble</option><option value=\"Vocal Solo\"";
   if($event=="Vocal Solo") echo " selected";
   echo ">Vocal Solo</option><option value=\"Vocal Ensemble\"";
   if($event=="Vocal Ensemble") echo " selected";
   echo ">Vocal Ensemble</option></select></td></tr>";
   echo "<tr align=left><td>Date:</td><td>";
   if($siteid)
   {
      echo "<select name=\"certdate\"><option value=''>Select Date</option>";
      $dates=split("/",$row2[dates]);
      for($i=0;$i<count($dates);$i++)
      {
         $date=split("-",$dates[$i]);
         echo "<option value=\"".date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]))."\">".date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]))."</option>";
      }
      echo "</select></td></tr>";
   }
   else
   {
      echo "<input type=text size=25 name=\"certdate\"></td></tr>";
   }
   echo "<tr align=left><td>Type of Certificate:</td><td><input type=radio name=\"hm\" value=\"no\"";
   if($hm=="no" || !$hm) echo " checked";
   echo "> Outstanding Performance&nbsp;&nbsp;&nbsp;<input type=radio name=\"hm\" value=\"yes\"";
   if($hm=="yes") echo " checked";
   echo "> Outstanding Performance <u>Honorable Mention</u></td></tr>";
   echo "<tr align=center><td colspan=2><input type=submit name=\"preview\" value=\"Continue to Preview Black & White Certificate\">&nbsp;&nbsp;&nbsp;&nbsp;";
   echo "<input type=submit name=\"preview2\" value=\"Continue to Preview Color Certificate\"></td></tr>";
   echo "</table></form>";
   echo $end_html;
}
exit();
?>
