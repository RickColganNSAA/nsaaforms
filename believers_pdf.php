<?php

require 'functions.php';
require 'variables.php';
require_once('../tcpdf_php4/config/lang/eng.php');
require_once('../tcpdf_php4/tcpdf.php');

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
/* if(!ValidUser($session) )
{
   header("Location:index.php?error=3");
   exit();
} */
 if (!empty($_GET[school])){
 $school = preg_replace('/\.[^.]+$/','',$_GET[school]);
  }
 
 $sql="SELECT * FROM eligibility WHERE fb68='x' OR fb11='x' OR vb='x' OR sb='x' OR cc='x' OR te='x' OR bb='x' OR wr='x' OR sw='x' OR go='x' OR tr='x' OR ba='x' OR so='x' OR ch='x' OR sp='x' OR pp='x' OR de='x' OR im='x' OR vm='x' OR jo='x' OR ubo='x' ";
 $result_name=mysql_query($sql);
 
 $sql1="SELECT school FROM headers ORDER BY school";
 $result1=mysql_query($sql1);
 
 $sql_question="SELECT * FROM believers_duedates where  id=1 ";
 $result_question=mysql_query($sql_question);
 $row_question=mysql_fetch_array($result_question);
 
 if ($_SERVER["REQUEST_METHOD"] == "POST") { 
 
 
	if (empty($_POST["name"])){
	$nameErr = "Field is required";
	} 
	if ($id) {   
	if((!empty($_POST["name"])) && (!empty($_POST["school"]))){
	//$school = mysql_real_escape_string($school);
    $sql="UPDATE believers SET name='$name',gender='$gender',race='$race',school='$school',street='$street',city='$city',zip='$zip',cell='$cell',email='$email',submitted='$submitted',title='$title',class='$class',average='$average',list='$list',activity='$activity',award='$award',activity1='$activity1',office1='$office1',length1='$length1',time1='$length1',activity2='$activity2',office2='$office2',length2='$length2',time2='$time2',activity3='$activity3',office3='$office3',length3='$length3',time3='$time3',activity4='$activity4',office4='$office4',length4='$length4',time4='$time4', c_activity1='$c_activity1',c_office1='$c_office1',c_length1='$c_length1',c_time1='$c_time1',c_activity2='$c_activity2',c_office2='$c_office2',c_length2='$c_length2',c_time2='$c_time2',c_activity3='$c_activity3',c_office3='$c_office3',c_length3='$c_length3',c_time3='$c_time3',c_activity4='$c_activity4',c_office4='$c_office4',c_length4='$c_length4',c_time4='$c_time4',essay='$essay',parent_name='$parent_name',parent_email='$parent_email'  WHERE id='$id'";  
	$result=mysql_query($sql);

    header("Location:believers_list.php?session=$session");
    exit();
	}
	}
 }
  if (isset($_GET['id'])) {

   $sql="SELECT * FROM believers WHERE id =$_GET[id]";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result); 
   $name =$row[name];
   $id =$row[id];
   $gender =$row[gender];
   $race =$row[race];
   $school = $row[school];
   $street =$row[street];
   $get_school = mysql_real_escape_string($row[school]);
   $sql_address="SELECT * FROM headers WHERE school ='".$get_school."'"; 
   $result_address=mysql_query($sql_address);
   $row_address=mysql_fetch_array($result_address); 
   $address =$row_address[address1].' '.$row_address[address2].' '.$row_address[city_state].'  '.$row_address[zip];
   $city =$row[city];
   $zip =$row[zip];
   $cell =$row[cell];
   $email =$row[email];
   $submitted =$row[submitted];
   $title =$row [title];
   $class =$row ['class'];
   $average =$row [average];
   $list =strip_tags($row ['list']); 
   //$list =preg_replace("/•/", "", $row ['list']);
   $activity_1 =$row [activity];
   $activity =explode(',',$row [activity]);
   $award =$row [award];
   $activity1 =$row [activity1];
   $office1 =$row [office1];
   $length1 =$row [length1];
   $time1 =$row [time1];
   $activity2 =$row [activity2];
   $office2 =$row [office2];
   $length2 =$row [length2];
   $time2 =$row [time2];
   $activity3 =$row [activity3];
   $office3 =$row [office3];
   $length3 =$row [length3];
   $time3 =$row [time3];
   $activity4 =$row [activity4];
   $office4 =$row [office4];
   $length4 =$row [length4];
   $time4 =$row [time4];
   $c_activity1 =$row [c_activity1];
   $c_office1 =$row [c_office1];
   $c_length1 =$row [c_length1];
   $c_time1 =$row [c_time1];
   $c_activity2 =$row [c_activity2];
   $c_office2 =$row [c_office2];
   $c_length2 =$row [c_length2];
   $c_time2 =$row [c_time2];
   $c_activity3 =$row [c_activity3];
   $c_office3 =$row [c_office3];
   $c_length3 =$row [c_length3];
   $c_time3 =$row [c_time3];
   $c_activity4 =$row [c_activity4];
   $c_office4 =$row [c_office4];
   $c_length4 =$row [c_length4];
   $c_time4 =$row [c_time4];
   $essay =strip_tags($row [essay]);
   $document =$row [document];
   $image =$row [image];
   $parent_name =$row [parent_name];
   $parent_email =$row [parent_email];
   $question19 =strip_tags($row [question19]);

   //echo '<pre>';print_r($date); exit;
}
   
 //echo $init_html;
 //echo $header;
   //INITIALIZE PDF
   $pdf = new TCPDF("P", PDF_UNIT, "LETTER", true); //LETTER = 8.5 x 11 in or 216 x 280 mm
   $pdf->SetCreator("NSAA");
   $pdf->SetAuthor("NSAA");
   //$pdf->SetMargins(0,0);
   $pdf->SetAutoPageBreak(True, 1);
   $pdf->setLanguageArray($l);
   $pdf->AliasNbPages();
   $pdf->AddPage();
   
   $origx=5; $origy=5;
   $x=$origx; $y=$origy;
   if(($ix%2)>0) $y+=135;
   $pdf->SetFont("berthold","B","14");
   $pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0,0,0);
   $pdf->SetFillColor(0,0,0);
   $pdf->SetTextColor(255,255,255);
   $pdf->writeHTMLCell("206","",$x,$y,"<span style=\"font-weight:bold;\">U.S. BANK BELIEVERS & ACHIEVERS APPLICATION</span> ",0,0,1,true,"L");
   $pdf->SetFont("berthold","B","10");
   $y=$y+20;
   $pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0,0,0);
   $pdf->SetFillColor(0,0,0);
   $pdf->SetTextColor(255,255,255);
   $pdf->writeHTMLCell("206","",$x,$y,"<span style=\"font-weight:bold;\">APPLICANT INFORMATION</span> ",0,0,1,true,"L");
   
   $pdf->SetTextColor(0,0,0);
   
   $html="<table>";
   $html.="<tr align='left'><td><b>1. Applicant Name:</b>&nbsp;&nbsp;$name</td></tr>";
   $html.="<tr align='left'><td><b>2. Gender:</b>&nbsp;&nbsp;$gender</td></tr>";
   $html.="<tr align='left'><td><b>3. Please specify the applicant's ethnicity or race:</b>&nbsp;&nbsp;$race</td></tr>";
   $html.="<tr align='left'><td><b>4. School:</b>&nbsp;&nbsp;$school</td></tr>";
   $html.="<tr align='left'><td><b>5. Applicant Home Address:</b>&nbsp;&nbsp;</td></tr>";
   $html.="<tr align='left'><td><b>&nbsp;&nbsp;   Street Address:</b>&nbsp;&nbsp;$street</td></tr>";
   $html.="<tr align='left'><td><b>&nbsp;&nbsp;   City:</b>&nbsp;&nbsp;$city</td></tr>";
   $html.="<tr align='left'><td><b>&nbsp;&nbsp;   Zip:</b>&nbsp;&nbsp;$zip</td></tr>";
   $html.="<tr align='left'><td><b>6. Applicant Cell Phone:</b>&nbsp;&nbsp;$cell</td></tr>";
   $html.="<tr align='left'><td><b>7. Applicant Email Address:</b>&nbsp;&nbsp;$email</td></tr>";
   $html.="<tr align='left'><td><b>8. Parent/Guardian Name(s):</b>&nbsp;&nbsp;$parent_name</td></tr>";
   $html.="<tr align='left'><td><b>9. Parent/Guardian email address:</b>&nbsp;&nbsp;$parent_email</td></tr>";
   $html.="<tr align='left'><td><b>10. Person Submitting Application:</b>&nbsp;&nbsp;$submitted</td></tr>";
   $html.="<tr align='left'><td><b>11. Title of person submitting application:</b>&nbsp;&nbsp;$title</td></tr>";
   $html.="<tr align='left'><td><b>12. NSAA Classification(for Track & Field):</b>&nbsp;&nbsp;$class</td></tr>";
   $y=$y+16;
   $pdf->writeHTMLCell($width,"",$x,$y,$html,0,0,0,true,"C");
   $y=$y+84;
   $pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0,0,0);
   $pdf->SetFillColor(0,0,0);
   $pdf->SetTextColor(255,255,255);
   $pdf->writeHTMLCell("206","",$x,$y,"<span style=\"font-weight:bold;\">Scholastic Achievement</span> ",0,0,1,true,"L");
   $html1="<tr align='left'><td><b>13. Cumulative Grade Point Average on an Unweighted Scale:</b>&nbsp;&nbsp;$average</td></tr>";
   $html1.="<tr align='left'><td ><b>14. List Academic Honors and Awards:</b></td></tr>";
   $html1.="<tr align='left'><td>&nbsp;&nbsp;$list</td></tr>"; 
   $y=$y+16;
   $pdf->SetTextColor(0,0,0);
   $pdf->writeHTMLCell($width,"",$x,$y,utf8_encode($html1),0,0,0,true,"C");
   $y=$y+66;
   $pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0,0,0);
   $pdf->SetFillColor(0,0,0);
   $pdf->SetTextColor(255,255,255);
   $pdf->writeHTMLCell("206","",$x,$y,"<span style=\"font-weight:bold;\">NSAA Activity Participation</span> ",0,0,1,true,"L");
   $html2="<tr align='left'><td><b>15. NSAA Activity Participation:</b>&nbsp;&nbsp;$activity_1</td></tr>";
   $html2.="<tr align='left'><td><b>16. List Awards from NSAA Activities:</b></td></tr>";
   $html2.="<tr align='left'><td>&nbsp;&nbsp;$award</td></tr>";
   $y=$y+16;
   $pdf->SetTextColor(0,0,0);
   $pdf->writeHTMLCell($width,"",$x,$y,utf8_encode($html2),0,0,0,true,"C");
   $y=$y+76;
   $pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0,0,0);
   $pdf->SetFillColor(0,0,0);
   $pdf->SetTextColor(255,255,255);
   $pdf->writeHTMLCell("206","",$x,$y,"<span style=\"font-weight:bold;\">School Involvement</span> ",0,0,1,true,"L");
   $html3="<tr align='left'><td><b>17. School Involvement-List Top 4:</b>&nbsp;&nbsp;</td></tr>";
   $html3.="<tr align='left'><td><b>&nbsp;&nbsp; # 1 Group/Club Activity:</b>&nbsp;&nbsp;$activity1</td></tr>";
   $html3.="<tr align='left'><td><b>&nbsp;&nbsp; Involvement or Office/Title Held:</b>&nbsp;&nbsp;$office1</td></tr>";
   $html3.="<tr align='left'><td><b>&nbsp;&nbsp; Involvement or Office/Title Held:</b>&nbsp;&nbsp;$length1</td></tr>";
   $html3.="<tr align='left'><td><b>&nbsp;&nbsp; Estimated time per month:</b>&nbsp;&nbsp;$time1</td></tr>";
   $html3.="<tr align='left'><td><b>&nbsp;&nbsp; # 2 Group/Club Activity:</b>&nbsp;&nbsp;$activity2</td></tr>";
   $html3.="<tr align='left'><td><b>&nbsp;&nbsp; Involvement or Office/Title Held:</b>&nbsp;&nbsp;$office2</td></tr>";
   $html3.="<tr align='left'><td><b>&nbsp;&nbsp; Involvement or Office/Title Held:</b>&nbsp;&nbsp;$length2</td></tr>";
   $html3.="<tr align='left'><td><b>&nbsp;&nbsp; Estimated time per month:</b>&nbsp;&nbsp;$time2</td></tr>";
   $html3.="<tr align='left'><td><b>&nbsp;&nbsp; # 3 Group/Club Activity:</b>&nbsp;&nbsp;$activity3</td></tr>";
   $html3.="<tr align='left'><td><b>&nbsp;&nbsp; Involvement or Office/Title Held:</b>&nbsp;&nbsp;$office3</td></tr>";
   $html3.="<tr align='left'><td><b>&nbsp;&nbsp; Involvement or Office/Title Held:</b>&nbsp;&nbsp;$length3</td></tr>";
   $html3.="<tr align='left'><td><b>&nbsp;&nbsp; Estimated time per month:</b>&nbsp;&nbsp;$time3</td></tr>";
   $html3.="<tr align='left'><td><b>&nbsp;&nbsp; # 4 Group/Club Activity:</b>&nbsp;&nbsp;$activity4</td></tr>";
   $html3.="<tr align='left'><td><b>&nbsp;&nbsp; Involvement or Office/Title Held:</b>&nbsp;&nbsp;$office4</td></tr>";
   $html3.="<tr align='left'><td><b>&nbsp;&nbsp; Involvement or Office/Title Held:</b>&nbsp;&nbsp;$length4</td></tr>";
   $html3.="<tr align='left'><td><b>&nbsp;&nbsp; Estimated time per month:</b>&nbsp;&nbsp;$time4</td></tr>";
   $y=$y-280;
   $pdf->SetTextColor(0,0,0);
   $pdf->writeHTMLCell($width,"",$x,$y,utf8_encode($html3),0,0,0,true,"C");
   $y=$y+86;
   $pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0,0,0);
   $pdf->SetFillColor(0,0,0);
   $pdf->SetTextColor(255,255,255);
   $pdf->writeHTMLCell("206","",$x,$y,"<span style=\"font-weight:bold;\">Community Involvement</span> ",0,0,1,true,"L");
   $html4="<tr align='left'><td><b>18. Community Involvement-List Top 4:</b>&nbsp;&nbsp;</td></tr>";
   $html4.="<tr align='left'><td><b>&nbsp;&nbsp; # 1 Group/Club Activity:</b>&nbsp;&nbsp;$c_activity1</td></tr>";
   $html4.="<tr align='left'><td><b>&nbsp;&nbsp; Involvement or Office/Title Held:</b>&nbsp;&nbsp;$c_office1</td></tr>";
   $html4.="<tr align='left'><td><b>&nbsp;&nbsp; Involvement or Office/Title Held:</b>&nbsp;&nbsp;$c_length1</td></tr>";
   $html4.="<tr align='left'><td><b>&nbsp;&nbsp; Estimated time per month:</b>&nbsp;&nbsp;$c_time1</td></tr>";
   $html4.="<tr align='left'><td><b>&nbsp;&nbsp; # 2 Group/Club Activity:</b>&nbsp;&nbsp;$c_activity2</td></tr>";
   $html4.="<tr align='left'><td><b>&nbsp;&nbsp; Involvement or Office/Title Held:</b>&nbsp;&nbsp;$c_office2</td></tr>";
   $html4.="<tr align='left'><td><b>&nbsp;&nbsp; Involvement or Office/Title Held:</b>&nbsp;&nbsp;$c_length2</td></tr>";
   $html4.="<tr align='left'><td><b>&nbsp;&nbsp; Estimated time per month:</b>&nbsp;&nbsp;$c_time2</td></tr>";
   $html4.="<tr align='left'><td><b>&nbsp;&nbsp; # 3 Group/Club Activity:</b>&nbsp;&nbsp;$c_activity3</td></tr>";
   $html4.="<tr align='left'><td><b>&nbsp;&nbsp; Involvement or Office/Title Held:</b>&nbsp;&nbsp;$c_office3</td></tr>";
   $html4.="<tr align='left'><td><b>&nbsp;&nbsp; Involvement or Office/Title Held:</b>&nbsp;&nbsp;$c_length3</td></tr>";
   $html4.="<tr align='left'><td><b>&nbsp;&nbsp; Estimated time per month:</b>&nbsp;&nbsp;$c_time3</td></tr>";
   $html4.="<tr align='left'><td><b>&nbsp;&nbsp; # 4 Group/Club Activity:</b>&nbsp;&nbsp;$c_activity4</td></tr>";
   $html4.="<tr align='left'><td><b>&nbsp;&nbsp; Involvement or Office/Title Held:</b>&nbsp;&nbsp;$c_office4</td></tr>";
   $html4.="<tr align='left'><td><b>&nbsp;&nbsp; Involvement or Office/Title Held:</b>&nbsp;&nbsp;$c_length4</td></tr>";
   $html4.="<tr align='left'><td><b>&nbsp;&nbsp; Estimated time per month:</b>&nbsp;&nbsp;$c_time4</td></tr>";
   $y=$y-270;
   $pdf->SetTextColor(0,0,0);
   $pdf->writeHTMLCell($width,"",$x,$y,utf8_encode($html4),0,0,0,true,"C");
   //$y=$y-195;
   $y=$y+86;
   $pdf->SetFillColor(255,255,255);
   $pdf->SetTextColor(0,0,0);
   $pdf->SetFillColor(0,0,0);
   $pdf->SetTextColor(255,255,255);
   $pdf->writeHTMLCell("206","",$x,$y,"<span style=\"font-weight:bold;\">Citizenship Essay</span> ",0,0,1,true,"L"); 
   $html5="<tr align='left'><td><b>19 $question19:</b></td></tr>";
   $html5.="<tr align='left'><td>$essay</td></tr>";
   if (!empty($document)){
   $html5.="<tr align='left'><td><b>Essay: </b>&nbsp;&nbsp; <a href='believers/$document' target='_blank'>$document;</a></td></tr>";
   } 
   if (!empty($image)){
   $html5.="<tr align='left'><td><b>Image: </b>&nbsp;&nbsp; <a href='believers/$image' target='_blank'>$image;</a></td></tr>";
   } 

   $y=$y+10;
   $pdf->SetTextColor(0,0,0);
   $pdf->writeHTMLCell($width,"",$x,$y,utf8_encode($html5),0,0,0,true,"C");
   
      if(citgf_file_exists("/believers/".$image))
   {
      list($pixw, $pixh) = getimagesize(getbucketurl("/believers/".$image));
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
      //if(($ix%2)>0) $y+=135;
      $photoy=$y;
      $pdf->Image("/believers/".$image,$x,$y,$width,'','','','',false,72,'',false,false,0,false,false,true);
   }
   
   $pdffilename="believers".$id.".pdf";
   if(!$pdf->Output("/downloads/$pdffilename", "I")) echo "OUTPUT ERROR";

?>


   <tr><td><h4>12. NSAA Classification(for Track & Field):</h4></td>
   <td><?php echo $class;?></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr align="center" style="background-color:#dbe1dd;"><td colspan="3"><h3>Scholastic Achievement</h3></td></tr> 
   <tr style="height:5px"><td ></td></tr>
   <tr><td colspan="3"><h4>Individuals must have a cumulative grade point average of 3.75(on an unweighted 4.0 scale) or higher. 
   All academic classes that the student has taken since entering grade nine, and which count toward fulfillment
   of the school's graduation requirements are to be used in determining the grade point average. The grade point 
   average will be calculated through the second semester of the nominee's junior year. Grade point averages are
   to be rounded off to the nearest hundredth (example:3.756 = 3.76)</h4></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr><td><h4>13. Cumulative Grade Point Average on an Unweighted Scale</h4></td>
   <td><?php echo $average; ?></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr><td colspan="3"><h4>14. List Academic Honors and Awards (e.g. 2017 NCPA Academic All-state, Nationa Honor-society, Honor Roll, Academic Letter, etc)</h4></td></tr>
   <tr><td></td><td><?php echo $list; ?></td></tr>
   <tr style="height:5px"><td ></td></tr>
   <tr align="center" style="background-color:#dbe1dd;"><td colspan="3"><h3>NSAA Activity Participation</h3></td></tr>
   <tr><td colspan="3"><h4>Activities sanctioned by the NSAA are listed. A requirement of this award is the student must
   participant in an NSAA sanctioned activity. Achievement or recognition attained by participation in 
   non-NSAA sanctioned activities are prohibited in this category (but can be listed in community involvement).
   Outstanding achievement does not have to be primary factor. An individual serving as a student manager or
   member of a stage crew can be judged on his/her commitment and positive contributions to the activity.</h4></td></tr>
   <tr style="height:5px"><td ></td></tr>
   
   <tr><td ><h4 >15. NSAA Activity Participation:(Check all activities participated in as a junior)</h4></td><td><?php echo $activity_1; ?></td></tr>

   <tr style="height:5px"><td ></td></tr>
    <tr><td colspan="3"><h4>16. List Awards from NSAA Activities (e.g. 2016 NSAA State Girls Golf Champion, Member of the 2016 NSAA Class C2 Girls Basketball
	Championship 4th Place Team, 2016 NSAA Class C2 State Speech 3rd Place Medalist in Serious Prose, etc)</h4></td></tr>
    <tr><td></td><td><?php echo $award; ?></td></tr>
    <tr style="height:5px"><td ></td></tr>
    <tr align="center" style="background-color:#dbe1dd;"><td colspan="3"><h3>School Involvement</h3></td></tr>
	<tr style="height:5px"><td ></td></tr>
	<tr><td colspan="3"><h4>Involvement in clubs or organizations, volunteerism in school programs, support of activities other than those in which applicant participates,
	and non-NSAA activities during applicant's high school career.</h4></td></tr>
    
    <tr><td><h4>17. School Involvement-List Top 4</h4></td></tr>
    <tr><td><h4># 1 Group/Club Activity: </h4></td><td><?php echo $activity1; ?></td></tr><br>
	<tr style="height:5px"><td ></td></tr>
    <tr><td><h4>Involvement or Office/Title Held: </h4></td><td><?php echo $office1; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Length of involvement: </h4></td><td><?php echo $length1; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Estimated time per month: </h4></td><td><?php echo $time1; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4># 2 Group/Club Activity: </h4></td><td><?php echo $activity2; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Involvement or Office/Title Held: </h4></td><td><?php echo $office2; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Length of involvement: </h4></td><td><?php echo $length2; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Estimated time per month: </h4></td><td><?php echo $time2; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4># 3 Group/Club Activity: </h4></td><td><?php echo $activity3; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Involvement or Office/Title Held: </h4></td><td><?php echo $office3; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Length of involvement: </h4></td><td><?php echo $length3; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Estimated time per month: </h4></td><td><?php echo $time3; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4># 4 Group/Club Activity: </h4></td><td><?php echo $activity4; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Involvement or Office/Title Held: </h4></td><td><?php echo $office4; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Length of involvement:</h4> </td><td><?php echo $length4; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Estimated time per month: </h4></td><td><?php echo $time4; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	
	<tr align="center" style="background-color:#dbe1dd;"><td colspan="3"><h3>Community Involvement</h3></td></tr>
	<tr style="height:5px"><td ></td></tr>
	<tr><td colspan="3"><h4>Involvement and volunteerism in community organizations, youth groups and programs during the applicant's high school career.</h4></td></tr>
    <tr style="height:5px"><td ></td></tr>
    <tr><td><h4>18. Community Involvement-List Top 4 </h4></td></tr>
	<tr style="height:5px"><td ></td></tr>
    <tr><td><h4># 1 Group/Club Activity: </h4></td><td><?php echo $c_activity1; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Involvement or Office/Title Held: </h4></td><td><?php echo $c_office1; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Length of involvement: </h4></td><td><?php echo $c_length1; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Estimated time per month: </h4></td><td><?php echo $c_time1; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4># 2 Group/Club Activity: </h4></td><td><?php echo $c_activity2; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Involvement or Office/Title Held: </h4></td><td><?php echo $c_office2; ?><br></td></tr>	
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Length of involvement: </h4></td><td><?php echo $c_length2; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Estimated time per month: </h4></td><td><?php echo $c_time2; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4># 3 Group/Club Activity: </h4></td><td><?php echo $c_activity3; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Involvement or Office/Title Held: </h4></td><td><?php echo $c_office3; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Length of involvement: </h4></td><td><?php echo $c_length3; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Estimated time per month: </h4></td><td><?php echo $c_time3; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4># 4 Group/Club Activity: </h4></td><td><?php echo $c_activity4; ?><br></td></tr>	
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Involvement or Office/Title Held: </h4></td><td><?php echo $c_office4; ?><br></td></tr>	
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Length of involvement: </h4></td><td><?php echo $c_length4; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Estimated time per month: </h4></td><td><?php echo $c_time4; ?><br>	</td></tr>
    <tr style="height:5px"><td ></td></tr>
	
	<tr align="center" style="background-color:#dbe1dd;"><td colspan="3"><h3>Citizenship Essay</h3></td></tr>
	<tr><td colspan="2"><h4>The applicant shall respond to the following in 250-300 words.</h4></td></tr>
	

	<tr><td colspan="3"><h4>19. <?php echo ' '.$question19;?></h4></td></tr>
	<tr style="height:2px"><td ></td></tr>
	<tr><td colspan="3"><?php echo $essay; ?></td></tr> 
	<tr style="height:5px"><td ></td></tr>
	<tr><td><h4>Upload Document:</td>
	<?php if (!empty($document)){?>
    <td> <a href="believers/<?php echo $document; ?>" target="_blank"><?php echo $document; ?></a></td>
	<?php } ?>
	</tr>
	<tr><td><h4>Upload Image:</td>
	<?php if (!empty($image)){?>
    <td> <a href="believers/<?php echo $image; ?>" target="_blank"><?php echo $image; ?></a></td>
	<?php } ?>
	</tr>
	<tr style="height:10px"><td ></td></tr>

	<tr style="height:10px"><td ></td></tr>
</form>

</table>


