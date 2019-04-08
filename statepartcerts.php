<?php
/*****************************************
statepartcerts.php
AD can generate state participation 
certificates (PDF) for certain year and
sport
Author: Ann Gaffigan
Created: 6/3/2010
*****************************************/

require 'functions.php';
require 'variables.php';
require '../calculate/functions.php';
//include PDF creation tool:
require_once('../tcpdf/tcpdf.php');

$level=GetLevel($session);

//validate user
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

$school=GetSchool($session);
$school2=addslashes($school);

if($level>2)	//COACH OR DIRECTOR - ISOLATE TO HIS/HER SPORT ONLY
{
   $myactivity=ereg_replace("Boys ","",GetActivity($session));
   $myactivity=ereg_replace("Girls ","",$myactivity);
}

if($printpdfs || $printblank)
{
   if($printblank)
   {
      $studentid[0]=0; $check[0]='x';
   }
         //$orientation="L";
         //$pdf = new TCPDF($orientation, PDF_UNIT, PDF_PAGE_FORMAT, true);
	 $pdf = new TCPDF("L", PDF_UNIT, "LETTER", true); //LETTER = 8.5 x 11 in or 216 x 280 mm
         $pdf->SetCreator("NSAA");
         $pdf->SetAuthor("NSAA");
         $pdf->SetMargins(0,0);
         $pdf->SetAutoPageBreak(TRUE, 1);
         $pdf->setLanguageArray($l);
	 $season=GetSeason($sport);
	 if($database=="nsaascores")	//get current year
	 {
	    $year=date("Y");
	    if(date("m")<6) { $year1=$year-1; $year2=$year; }
	    else { $year1=$year; $year2=$year+1; }
	 }
	 else
	 {
	    $years=ereg_replace("[^0-9]","",$database);
	    $year1=substr($years,0,4); $year2=substr($years,4,4);
	 }
	 $showyear="$year1-$year2";

   for($i=0;$i<count($studentid);$i++)
   {
      if($check[$i]=='x' || $checkall=='x')
      {
	 $sql="SELECT * FROM $database.eligibility WHERE id='$studentid[$i]'";
	 $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
         if(ereg("[(]",$row[first]))      //nickname
         {
            $first_nick=explode("(",$row[first]);
            $first_nick[1]=trim($first_nick[1]);
            $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
            $row[first]=$first;
         }
         $student="$row[first] $row[last]"; 
   	 $pdf->AddPage();
   	 $pdf->SetFont("helvetica","",14);
	 $pdf->SetFillColor(255,255,255);
         $pdf->writeHTMLCell("280","50",0,0,"",0,1,1,true,"C");
         if($color=="yes")
	    $pdf->Image("../images/StatePartTopColor.jpg",40,15,200);
	 else
   	    $pdf->Image("../images/StatePartTopBW.jpg",40,15,200);
   	 $align="C"; $break="3";
   	 $y=105;
   	 $x=50;
   	 $pdf->SetFont("helvetica","",28);
         if(!$printblank) $pdf->writeHTMLCell("280","",0,$y,"<b>$student</b>",0,0,0,true,"C");
   	 $y+=13;
         $pdf->SetFont("helvetica","",24);
   	 if(!$printblank) $pdf->writeHTMLCell("280","",0,$y,GetSchoolName(GetSIDByStudent($studentid[$i],$sport),$sport),0,0,0,true,"C");
	//freesans helveticai
         $pdf->SetFont("freesans","",24);
   	 $y+=15;
   	 if(!$printblank) $pdf->writeHTMLCell("280","",0,$y,$showyear,0,0,0,true,"C");
	 $y+=12;
	 $activityname=GetActivityName($sport);
	 if($sport=='sw') $activityname="Swimming & Diving";
	 if($sport=='ubo') $activityname="Unified Sports, Bowling ";
	 $activityname="NSAA State ".$activityname." Championship";
	 if($sport=='sp' || $sport=='pp' || $sport=='jo')
	 {
	    $activityname=ereg_replace("Championship","Contest",$activityname);
	 }
	 else if($sport=='fb')
	 {
	    $activityname=ereg_replace("Championship","Playoffs",$activityname);
	 }
	 if(!$printblank) $pdf->writeHTMLCell("280","",0,$y,$activityname,0,0,0,true,"C");

         //$pdf->SetFillColor(255,255,255);
         //$pdf->writeHTMLCell("295","50",0,,"",0,1,1,true,"C");

   	 //EXEC DIRECTOR SIGNATURE:
   	 $x=30; $y=172;
   	 $pdf->Image("../images/jay.png",$x,$y,70);
   	 $pdf->SetFont("helvetica","","14");
   	 $y+=15;
   	 $pdf->writeHTMLCell("70","",$x,$y,"Jay Bellar<br>NSAA Executive Director",0,0,0,true,"C");
   	 //USBANK
	 $x=210; $y=171;
         //if($color=="yes") $pdf->Image("../images/usbankforcertCOLOR.png",$x,$y,60);
	 //else $pdf->Image("../images/usbankforcert.png",$x,$y,60);
      }
   }

   //Close and output PDF document
   $filename=ereg_replace("[^a-zA-Z]","",$school)."ParticipationCerts.pdf";
   $pdf->Output($filename, "I");
   exit();
}

echo $init_html;
echo GetHeader($session);

echo "<br>
<form method=post action=\"statepartcerts.php\">
<input type=hidden name=session value=\"$session\">";
echo "<table cellspacing=0 cellpadding=3 frame=all rules=all class=nine style=\"width:700px;border:#808080 1px solid;\">";
echo "<caption><b>NSAA State Participation Certificates</b>";
echo "<div class='alert'><b>INSTRUCTIONS:</b>";
echo "<ul class='bigger'><li>Select a <b>school year</b> and <b>activity</b> below.</li>";
echo "<li><b>Check the box</b> next to each student for which you want to print a State Participation Certificate. You can also check the \"Check All\" box at the bottom of the list to print certificates for all of the students.</li>";
echo "<li>Indicate if you want the certificates to be in <b>color or black and white</b>.</li>";
echo "<li>Click <b>\"Preview Certificates\"</b> to preview the PDF containing all of the certificates. Then select File -- Print to print the certificates or File -- Save to save them to your computer.</li>";
echo "</ul></div><br>";
echo "<b>SCHOOL YEAR AND ACTIVITY:</b> <select name=\"database\" onchange=\"submit();\"><option value='0'>Select School Year</option>";
$sql="SHOW DATABASES LIKE 'nsaascores%'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $temp=preg_split("/nsaascores/",$row[0]);
   if(is_array($temp)) $year=end($temp);
   else $year="";
   if($year=="") 
   {
      $value="nsaascores"; $view="CURRENT SCHOOL YEAR";
      if(date("m")<6) $showyear1=date("Y")-1;
      else $showyear1=date("Y");
      $showyear2=$showyear1+1;
      $view.=" ($showyear1-$showyear2)";
   }
   else
   {
      $value=$row[0]; $view=substr($year,0,4)."-".substr($year,4,4)." School Year";
   }
   echo "<option value=\"$value\"";
   if($database==$value) echo " selected";
   echo ">$view</option>";
}
echo "</select>&nbsp;<select name=\"sport\" onchange=\"submit();\"><option value='0'>Select Activity</option>";
for($i=0;$i<count($statepartacts);$i++)
{
   if($level<3 || ($level==3 && ereg("$myactivity",GetActivityName($statepartacts[$i]))))
   {
      echo "<option value=\"$statepartacts[$i]\"";
      if($sport==$statepartacts[$i]) echo " selected";
      echo ">".GetActivityName($statepartacts[$i])."</option>";
   }
}
echo "</select></form>";
echo "</caption>";
echo "<form method=post action=\"statepartcerts.php\" target=\"_blank\">
<input type=hidden name=session value=\"$session\"><input type=hidden name=database value=\"$database\">
<input type=hidden name=sport value=\"$sport\">";

if($database && $sport)
{
         if($database=="nsaascores")    //get current year
         {
            $year=date("Y");
            if(date("m")<6) { $year1=$year-1; $year2=$year; }
            else { $year1=$year; $year2=$year+1; }
         }
         else
         {
            $years=ereg_replace("[^0-9]","",$database);
            $year1=substr($years,0,4); $year2=substr($years,4,4);
         }
?>
<script language='javascript'>
function CheckAll()
{
   var studchecks=document.getElementsByTagName('input');
   for(var i=0;i<studchecks.length;i++)
   {
      if(studchecks[i].type=="checkbox")
         studchecks[i].checked=true;
   }
}
</script>
<?php
	/*
   if(strlen($sport)==3)
   {
      $gender=substr($sport,2,1);
      if($gender=='b') $gender='M';
      else $gender='F';
      $sport=substr($sport,0,2);
   }
   else
      $gender='';
   $sql="SELECT * FROM $database.eligibility WHERE $sport='x'";
   if($sport=='fb')
      $sql="SELECT * FROM $database.eligibility WHERE (fb68='x' OR fb11='x')";
   if($gender!='') $sql.=" AND gender='$gender'";
	*/
 
   $string=GetPlayers($sport,$school,$year1,FALSE);
   if(!ereg("Please",$string) && $string && $string!='')
   {
      $results=split("<result>",$string);
      if(count($results)>0)
      {
         echo "<tr align=center><td><b>Check</b><td><b>Student Name</b></td></tr>";
         for($i=0;$i<count($results);$i++)
         {
            $details=split("<detail>",$results[$i]);
            echo "<tr align=left><td align=center><input type=hidden name=\"studentid[$i]\" value=\"$details[0]\">";
	    echo "<input type=checkbox id=\"check".$i."\" class=\"studchecks\" name=\"check[$i]\" value=\"x\"></td>";
            echo "<td>$details[1]</td></tr>";
         }
         echo "<tr align=center bgcolor='#f0f0f0'><td>Check All<br><input type=checkbox name=\"checkall\" onClick=\"if(this.checked) { CheckAll(); }\" value=\"x\"></td><td align=left><b>Check this box to print certificates for ALL students listed above.</b></td></tr>";
         echo "<tr align=left><td colspan=2>Print Certificates in: <input type=radio name=\"color\" value=\"yes\" checked> Color&nbsp;&nbsp;&nbsp;";
         echo "<input type=radio name=\"color\" value=\"no\"> Black & White<br>";
         echo "<input type=submit name=\"printpdfs\" value=\"Preview Certificates\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=button name='printingtips' value='Printing Tips' onClick=\"window.open('printingtips.php','Printing_Tips','width=500,height=350');\"></td></tr>";
      }
      else echo "<tr align=center><td>[Your school has no students on the eligibility list for the selected year and activity.]</td></tr>";
   }
   else echo "<tr align=center><td>[Your school has no students on the eligibility list for the selected year and activity.]</td></tr>";
}
echo "</table>";
echo "<br><br><b>You can also print BLANK Participation Certificates if you wish to hand-write them: <a href=\"statepartcerts.php?session=$session&printblank=1\" target=\"_blank\">Black & White</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a target=\"_blank\" href=\"statepartcerts.php?session=$session&printblank=1&color=yes\">Color</a>";
echo "</form>";
echo $end_html;
?>
