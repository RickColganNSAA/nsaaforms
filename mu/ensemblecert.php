<?php
/*********************************************
ensemblecert.php
Dynamically Create PDF for Music Small/Large Emsemble Award Certificate
Created 5/8/14
Author Ann Gaffigan
**********************************************/
require '../functions.php';
require '../../calculate/functions.php';
require '../variables.php';
require 'mufunctions.php';


//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);
if(!ValidUser($session))
{
   header("Location:../index.php?error=1");
   exit();
}
$level=GetLevel($session);
// if($level==2 || $level==3)
 if($selectschool)
{
   $school=GetSchool($session);
} 

if(IsCooping($school,"Vocal")) $school=GetHeadCoopSchool($school,"Vocal");
if(IsCooping($school,"Instrumental")) $school=GetHeadCoopSchool($school,"Instrumental");
$school2=addslashes($school);

//print_r($school); exit;
$sql="SELECT * FROM muschools WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class=$row[classch];
$distid=$row[distid];
$schid=$row[id];
$homedist=$row[homedistrict];
$sql="SELECT * FROM mudistricts WHERE id='$distid'"; 
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$district="$row[distnum] -- $row[classes]";
$director=preg_replace("/, District Music Contest/","",$row[director]);
$name=preg_replace("/, District Music Director/","",$name);
if (!empty($name)&& $name=='Becky Wilhelm')$director=$name;

$director=trim($director);

      $dct=1;
      if(preg_match("/\//",$director))
      {
         $dirs=explode("/",$director);
         $director=$dirs[0]."<br>".$dirs[1];
         $dct=2;
      }
      else if(preg_match("/,/",$director))
      {
         $dirs=explode(",",$director); $dct=2;
      }
   $title="Contest Director";
   if($dct>1) $title.="s";

//USE ARCHIVE DATABASE?
/*
if(date("m")>=6 && date("m")<=8)
{
   $yr1=date("Y")-1; $yr2=$yr1+1;
   $sql="USE nsaascores".$yr1.$yr2;
   $result=mysql_query($sql);
}
*/

if($generatesolo)       //PUT $entryids list together and send to snsemblecert.php
{
   $list="";
   for($i=0;$i<count($entryids);$i++)
   {
      if($checks[$i]=='x') $list.=$entryids[$i].",";
   }
   if($list!='')
   {
      $list=substr($list,0,strlen($list)-1);
      $entryids=$list;
   }
}
if($generateens || $ensemblestuds==1)       //PUT $studentids list together and send to snsemblecert.php
{
   if($ensemblestuds==1) $studentids=explode(",",$studentids);
   $list=""; $ix=0; $students=array();
   for($i=0;$i<count($studentids);$i++)
   {
      if($checks[$i]=='x' || $ensemblestuds==1) 
      {
	 $students[$ix]=GetStudentInfo($studentids[$i],FALSE); $ix++;
      }
   }
   if($ix>0)
   {
      $schoolid=$schid;
      $ensemblestuds=1;
   }
}
else if($generateens2)
{
      $schoolid=$schid;
      $ensemblestuds=1;
}

if(PastDue(date("Y")."-05-31",0) && !PastDue(date("Y")."-07-01",0))       //IF IT IS PAST END OF SCHOOL YEAR, NEED TO REFER TO ARCHIVED DB
{
   $year1=date("Y")-1; $year2=date("Y");
   mysql_select_db($db_name.$year1.$year2, $db);
   $fallyear=$year1;
}
else if(date("m")>=6) $fallyear=date("Y");
else $fallyear=date("Y")-1;

//GIVEN:
//$school
//$entryid
//$ensemblestuds if we are generating individual certs for members of an ensemble (1 page of 5 rows of 2)
if(!$schoolid && $entryid)
{
   $sql="SELECT * FROM muentries WHERE id='$entryid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $schoolid=$row[schoolid];
}
$sport='mu';

//include PDF creation tool:
require_once('../../tcpdf/tcpdf.php');

//CHECK THIS IS A VALID ENSEMBLE TO PRINT
if($entryid)
   //$sql="SELECT t1.ensemble,t2.* FROM muentries AS t2, muensembles AS t1 WHERE t2.ensembleid=t1.id AND t2.id='$entryid' AND t2.schoolid='$schoolid'";
   $sql="SELECT t3.category,t1.ensemble,t2.* FROM muentries AS t2, muensembles AS t1, mucategories AS t3 WHERE t2.ensembleid=t1.id AND t1.categid=t3.id AND t2.id='$entryid' AND t2.schoolid='$schoolid'";
else
{
   $entryids=explode(",",$entryids);
  // $sql="SELECT t1.ensemble,t2.* FROM muentries AS t2, muensembles AS t1 WHERE t2.ensembleid=t1.id AND (t2.id='$entryids[0]' OR t2.id='$entryids[1]') AND t2.schoolid='$schoolid'";
  $sql="SELECT t3.category,t1.ensemble,t2.* FROM muentries AS t2, muensembles AS t1, mucategories AS t3 WHERE t2.ensembleid=t1.id AND t1.categid=t3.id AND (t2.id='$entryids[0]' OR t2.id='$entryids[1]') AND t2.schoolid='$schoolid'";
  }
$result=mysql_query($sql);
if(mysql_error()) 
{
   echo $sql."<br>".mysql_error()."<br>";
   exit();
}
$row=mysql_fetch_array($result);
$theschoolid=$row[schoolid];	//$theschoolid = SCHOOL ID OF THE STUDENT
$customtitle=$row[customtitle];	//This will replace default "the ENSEMBLE of SCHOOL high school"
if (preg_match('/Small Vocal/',$row[category]))$titles='Vocal';
else if (preg_match('/Miscellaneous/',$row[ensemble]))$titles=' ';
else $titles=' ';
//echo "Head School ID: $headschoolid, School ID: $schoolid";

if(mysql_num_rows($result)==0)
{
   //IF THE ENTRY IS NOT ATTACHED TO THIS SCHOOL, EXIT
   /* HID on May 11, 2016 - this does not need to be locked down so tight; it's keeping co-ops from doing what they need to
   echo $init_html2;
   echo "<div class=error>ERROR: Invalid entry ID.</div>";
   echo $end_html2;
   exit();
   */
}

if($entryid && !$ensemblestuds && !$soloist)	//Big 8.5x11 Cert for Ensembles
{
// create new PDF document
   $pdf = new TCPDF("L", PDF_UNIT, "LETTER", true); //LETTER = 8.5 x 11 in or 216 x 280 mm
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   $pdf->SetMargins(0,0);
   $pdf->SetAutoPageBreak(TRUE, 1);
   $pdf->setLanguageArray($l);
   $pdf->setPrintHeader(false);	//THIS GETS RID OF THAT BLACK LINE!
   $pdf->AddPage();
   $pdf->SetFont("helvetica","",14);

   //BACKGROUND:
   $img_file = $_SERVER['DOCUMENT_ROOT'].'/images/MusicEnsembleBack.jpg';
   $pdf->Image($img_file, 15, 15, 250, 167, '', '', '', false, 300, '', false, false, 0);
   $pdf->setPageMark();	 //(Make it a background image)

   //BODY OF THE CERT:
   $pdf->SetXY(10,70);
   $fontsize=22; 
   $align="C"; $break="3";
   $y=51; $x=50;
   $pdf->SetFont("dejavuserifi","","16");
   $pdf->writeHTMLCell("280","",0,$y,"This certifies that",0,0,0,true,"C");
   $pdf->SetFont("freeserifi","",$fontsize);

   $y+=30;
   if(substr($school,strlen($school)-5,5)==" High")
      $showschool=substr($school,0,strlen($school)-5);
   else $showschool=$school;
   //if($showschool=="Test's School") $showschool="Grand Island Central Catholic";
   if($showschool=="Test's School") $showschool="Test's School";
   //else if($showschool=="Test's") $showschool.=" School";
   else
   $showschool.=" High School";
   //$showschool.=" High School";
   if(preg_match("/Miscellaneous/",$row[ensemble]) && $row[event]!='')
      $row[ensemble]=$row[event];
   else
      $row[ensemble]=trim(preg_replace("/Miscellaneous/","",$row[ensemble]));
   if(trim($customtitle)=="") $customtitle=$titles." $row[ensemble]<br />of $showschool";
   else
   $customtitle="$customtitle<br />of $showschool";
   $pdf->writeHTMLCell("280","",0,$y,"The <font size=\"30\">$customtitle</font><br />received a <font size=\"40\"><b>Superior</b></font> rating",0,0,0,true,"C");
   $y+=45;
   $pdf->writeHTMLCell("280","",0,$y,"at the ".date("Y")." District Contest",0,0,0,true,"C");

   //DISTRICT DIRECTOR SIGNATURE:
   $pdf->SetFont("helvetica","","15");
   $x=15; 
   if($dct==2) $y=179;
   else $y=188;
   $pdf->writeHTMLCell("80","",$x,$y,"$director",0,1,0,true,"C");
   $pdf->SetFont("helvetica","B","13");
   $y2=$pdf->GetY();
   $pdf->writeHTMLCell("80","",$x,$y2,"$title",0,0,0,true,"C");

   //EXEC DIRECTOR SIGNATURE:
   $x=190; 
   if($dct>1) $y-=4;
   else $y-=10;
   $pdf->Image("../../images/Jay.png",$x,$y,70);
   $y+=13;
   $pdf->writeHTMLCell("70","",$x,$y,"Jay Bellar<br>Executive Director",0,0,0,true,"C");

   $filename1="DistrictMusicCertificate".preg_replace("/[^0-9a-zA-Z]/","","$row[ensemble] $school").".pdf";
   $pdf->Output("/home/nsaahome/attachments/$filename1", "I");
   //header("Location:../attachments.php?session=$session&filename=$filename1");
}	//END ENSEMBLE
else if($entryids)	//1 Page with 2 Soloist Certificates
{
// create new PDF document
   $pdf = new TCPDF("P", PDF_UNIT, "LETTER", true); //LETTER = 8.5 x 11 in or 216 x 280 mm
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   $pdf->SetMargins(0,0);
   $pdf->SetAutoPageBreak(TRUE, 1);
   $pdf->setLanguageArray($l);
   $pdf->SetFont("helvetica","",14);

   //BACKGROUND:
   $img_file = $_SERVER['DOCUMENT_ROOT'].'/images/MUSoloistCertBack.png';

   //FOR EACH SOLOIST:
   $x=0; $y=1; $width=214;
   for($i=0;$i<count($entryids);$i++)
   {
      if($i%2==0)
      {
         $pdf->AddPage();
   	 //BACKGROUND:
   	 $pdf->Image($img_file, 0, 0, 216, 280, '', '', '', false, 300, '', false, false, 0);
   	 $pdf->setPageMark();  //(Make it a background image)
         //GRID LINES
         $style = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,5,5,10', 'phase' => 10, 'color' => array(210, 210, 210));
         $pdf->Line(0, 280/2, 216, 280/2, $style);
	 $x=0; $y=1;
      }
      $sql="SELECT t1.ensemble,t2.* FROM muentries AS t2, muensembles AS t1 WHERE t2.ensembleid=t1.id AND t2.id='$entryids[$i]' AND t2.schoolid='$schoolid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);

      $pdf->SetFont("dejavuserif","","14");
      $y2=$y+27;
      $pdf->writeHTMLCell("$width","",$x,$y2,"This certifies that",0,0,0,true,"C");
      $y2+=15;
      $pdf->SetFont("freeserifi","","28");
      $pdf->writeHTMLCell("$width","",$x,$y2,GetStudentInfo($row[studentid],FALSE),0,0,0,true,"C");
      $pdf->SetFont("dejavuserif","","12");
      $y2+=12;
      $pdf->writeHTMLCell("$width","",$x,$y2,"of",0,0,0,true,"C");
      $pdf->SetFont("freeserifi","","26");
      $y2+=6;
      if(trim($customschool)!='')
         $showschool=trim($customschool);
      else
	 $showschool="$school High School";
      $pdf->writeHTMLCell("$width","",$x,$y2,"$showschool",0,0,0,true,"C");
      $pdf->SetFont("freeserif","","16");
      $y2+=12;
      $pdf->writeHTMLCell("$width","",$x,$y2,"received a <font size='20'>SUPERIOR</font> rating<br /> at the ".date("Y")." District Music Contest",0,0,0,true,"C");
      $y2+=16;
	//SPECIAL EXCEPTIONS FOR SOLO EVENT NAMES
      $row[event]=preg_replace("/Voice/","Vocal",$row[event]);
      if($row[event]=="Cornet/Trumpet") $row[event]="Trumpet";
      if($row[event]=="Snare") $row[event]="Snare Drum";
	//END SPECIAL EXCEPTIONS
      $pdf->writeHTMLCell("$width","",$x,$y2,"for <font size='22'><i>$row[event] Solo</i></font>",0,0,0,true,"C");
        //DISTRICT DIRECTOR
      if($dct==2)
         $y2=(280/2)-25;
      else
	 $y2=(280/2)-20;
      if($i%2>0) $y2+=(280/2);
      $pdf->SetFont("helvetica","","12");
      $x2=10; 
      $pdf->writeHTMLCell("70","",$x2,$y2,"$director",0,1,0,true,"C");
      $y3=$pdf->GetY();
      $pdf->SetFont("helvetica","B","11");
      $pdf->writeHTMLCell("70","",$x2,$y3,"$title",0,0,0,true,"C"); 

      //EXEC DIRECTOR SIGNATURE:
      $x2=130; 
      if($dct==2) $y2-=3;
      else $y2-=8;
      $pdf->Image("../../images/jay.png",$x2,$y2,54);
      $y2+=10;
      $pdf->writeHTMLCell("55","",$x2,$y2,"Jay Bellar<br>Executive Director",0,0,0,true,"C");

      $y+=(280/2);
   }

   $filename1="DistrictMusicCertificate".preg_replace("/[^0-9a-zA-Z]/","","$row[ensemble] $school").".pdf";
   $pdf->Output("/home/nsaahome/attachments/$filename1", "I");
   //header("Location:../attachments.php?session=$session&filename=$filename1");
}
else if($entryid && $ensemblestuds)	//1 Page with 5 rows of 2 small certs for members of ensemble
{
// create new PDF document
   $pdf = new TCPDF("P", PDF_UNIT, "LETTER", true); //LETTER = 8.5 x 11 in or 216 x 280 mm
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   $pdf->SetMargins(0,0);
   $pdf->SetAutoPageBreak(TRUE, 1);
   $pdf->setLanguageArray($l);
   $pdf->SetFont("helvetica","",14);

   //BACKGROUND:
   $img_file = $_SERVER['DOCUMENT_ROOT'].'/images/MUEnsembleStudentsCertBack.png';

   //$sql="SELECT * FROM muentries WHERE id='$entryid'";
   $sql="SELECT t1.ensemble,t2.* FROM muentries AS t2, muensembles AS t1 WHERE t2.ensembleid=t1.id AND t2.id='$entryid' ";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $customtitle=trim($row['customtitle']);

   //LINE STYLES
   $solidstyle = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 10, 'color' => array(0, 0, 0));
   $dottedstyle = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,5,5,10', 'phase' => 10, 'color' => array(210, 210, 210));

   if(substr($school,strlen($school)-5,5)==" High")
        $showschool=substr($school,0,strlen($school)-5);
   else $showschool=$school;

   //FOR EACH STUDENT:
   $x=0; $y=1; $width=108-(2*$x);
   for($i=0;$i<count($students);$i++)
   {set_time_limit(0);
   if(trim($students[$i])!='')
   {
      if($i%10==0)
      {
         $pdf->AddPage();
         //BACKGROUND:
         $pdf->Image($img_file, 0, 0, 216, 280, '', '', '', false, 300, '', false, false, 0);
         $pdf->setPageMark();  //(Make it a background image)
	 //GRID LINES
   	 $x=(216/2); $y1=0; $y2=280;
   	 $pdf->Line($x, $y1, $x, $y2, $dottedstyle);
   	 $y=280/5;
   	 for($j=1;$j<5;$j++)
   	 {
      	    $x1=0; $x2=216;
      	    $pdf->Line($x1, $y, $x2, $y, $style);
      	    $y+=(280/5);
   	 }
	 $x=0; $y=1;
      }
      $pdf->SetFont("dejavuserif","","14");
      $pdf->writeHTMLCell("$width","",$x,$y,"Nebraska School Activities Association",0,0,0,true,"C");
      $pdf->SetFont("dejavuserif","","11");
      $y2=$y+6;
      $pdf->writeHTMLCell("$width","",$x,$y2,"This is to certify that",0,0,0,true,"C");
      $y2+=5;
      $pdf->SetFont("freeserifi","","20");
      $pdf->writeHTMLCell("$width","",$x,$y2,"$students[$i]",0,0,0,true,"C");
      $pdf->SetFont("dejavuserif","","10");
      $y2+=8;
      $pdf->writeHTMLCell("$width","",$x,$y2,"is a member of",0,0,0,true,"C");
      $pdf->SetFont("freeserif","","9");
      $y2+=5;
      $row[ensemble]=trim(preg_replace("/Miscellaneous/","",$row[ensemble]));
	  if($showschool=="Test's School") $showschool="Test's";
      if(trim($customtitle)=="") $customtitle="$row[ensemble]<br />of $showschool High School";
      $pdf->writeHTMLCell("$width","",$x,$y2,"the <font size=\"10\"><i>$titles $customtitle</i></font><br />which received a SUPERIOR rating at the ".date("Y")." District Contest",0,0,0,true,"C");
	//DISTRICT DIRECTOR
      $pdf->SetFont("helvetica","","7");
      if($dct==2) $y2=$y+(280/5)-14;
      else $y2=$y+(280/5)-12;
      $x2=$x+2;
      $pdf->writeHTMLCell("30","",$x2,$y2,"$director",0,1,0,true,"C");
      $y3=$pdf->GetY();
      //$y3-=2;
      $pdf->SetFont("helvetica","B","6.5");
      $pdf->writeHTMLCell("30","",$x2,$y3,"$title",0,0,0,true,"C");
	//EXEC DIRECTOR OF NSAA
      $sigwidth=31;
      $x2=$x+$width-$sigwidth-7; 
      if($dct==2) $y2-=1;
      else $y2-=4;
      $pdf->Image("../../images/jay.png",$x2,$y2,$sigwidth);
      //$pdf->Line($x2, $y2+6, $x2+$sigwidth, $y2+6,$solidstyle);
      $y2+=5; 
      $pdf->writeHTMLCell("$sigwidth","",$x2,$y2,"Jay Bellar<br>Executive Director",0,0,0,true,"C");
      if($i%2==0) 
         $x=(216/2);
      else 
      {
         $x=0; $y+=(280/5);
      }
   } //END IF STUDENT NOT BLANK
   } //END FOR EACH STUDENT

   $filename1="DistrictMusicCertificate".preg_replace("/[^0-9a-zA-Z]/","","$row[ensemble] $school")."Students.pdf";
   $pdf->Output("/home/nsaahome/attachments/$filename1", "I");
   //header("Location:../attachments.php?session=$session&filename=$filename1");
}	//END MEMBERS OF ENSEMBLE
else if($entryid && $soloist)	//SOLOIST
{
}	//END SOLOIST
?>
