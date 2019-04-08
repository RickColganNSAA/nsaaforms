<?php
//hardship.php: Hardship Request Form

require 'functions.php';
require_once($_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php');
require 'variables.php';

$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if($edit==1 && !$id || $id=="") $edit=0;
if(!$school_ch && $level!=1)
{
   $school=GetSchool($session);
}
else if($school_ch)
{
   $school=$school_ch;
}
else if($id)
{
   $sql="SELECT school FROM hardship WHERE id='$id'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $school=$row[0];
}
else
{
   echo "<br><br>ERROR: No school or form id provided.";
   exit();
}
$school2=ereg_replace("\'","\'",$school);

if(!$id) $id='0';

if($submit && $level==1)	//action of the executive director
{
   $execcomments=addslashes($execcomments);
   $nsaacomments=addslashes($nsaacomments);
   $execdate=time();
   $execsignature='x';
   $sql="SELECT * FROM hardship WHERE id='$id'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $oldexeccomments=addslashes($row[execcomments]); $oldexecsignature=$row[execsignature]; $oldeligible=$row[eligible];
   $oldnsaacomments=addslashes($row[nsaacomments]);

   $sql="UPDATE hardship SET execcomments='$execcomments', nsaacomments='$nsaacomments', execdate='$execdate', execsignature='$execsignature', execsigfile='$execsigfile', eligible='$eligible' WHERE id='$id'";
   $result=mysql_query($sql);

   //update eligibility table with eligible value
   $sql="SELECT studentid FROM hardship WHERE id='$id'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $studid=$row[0];
   if($eligible=='y') $elig='y';
   else $elig='';
   $sql="UPDATE eligibility SET eligible='$elig',eligible_comment='$execcomments' WHERE id='$studid'";
   $result=mysql_query($sql);

   //if swimmer is now eligible, send e-mail to Cindy
   $sql="SELECT * FROM eligibility WHERE id='$studid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[eligible]=='y' && $row[sw]=='x')
   {
      //IF NEW SWIMMER ADDED TO THE LIST, MARK IN DATABASE SO CINDY CAN CHECK THEM
      $sql="SELECT * FROM eligibility_sw WHERE studentid='$row[id]'";
      $result=mysql_query($sql);
      if(mysql_fetch_array($result)==0)
         $sql2="INSERT INTO eligibility_sw (studentid,dateadded) VALUES ('$row[id]','".time()."')";
      else
         $sql2="UPDATE eligibility_sw SET dateadded='".time()."' WHERE studentid='$row[id]'";
      $result2=mysql_query($sql2);
   }
   if($oldexeccomments!=$execcoments || $oldexecsignature!=$execsignature || $oldeligible!=$eligible) //send notification of update to school
   {
      $sql3="SELECT email,name,school,level FROM logins WHERE school='$school2' AND level='2'";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      if($row3[email]=="") //get other email
      {
         $sql3="SELECT email,name,school,level,sport FROM logins WHERE school='$school2' AND sport='Activities Director'";
         $result3=mysql_query($sql3);
         $row3=mysql_fetch_array($result3);
      }
      $From="nsaa@nsaahome.org"; $FromName="NSAA";
      $To=$row3[email]; $ToName=$row3[name];
      $Subject="Hardship Waiver Application Status Notification";
      $sql3="SELECT first,last FROM eligibility WHERE id='$studid'";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      $Html="Action of the Executive Director of the NSAA has been taken regarding your hardship waiver application, $row3[first] $row3[last].<br><br>Please login at <a href=\"https://secure.nsaahome.org/nsaaforms\">https://secure.nsaahome.org/nsaaforms</a> to view the action of the Executive Director.<br><br>Thank You!";
      $Text="Action of the Executive Director of the NSAA has been taken regarding your hardship waiver application, $row3[first] $row3[last].\r\n\r\nPlease login at https://secure.nsaahome.org/nsaaforms to view the action of the Executive Director.\r\n\r\nThank You!";
      $Attm=array();
      //if($To!='')
        // SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);
   }
}

if($submitfinal)
{
   if(trim($submitter)=="") $error=1;
}

if($save || $submitfinal || $savechanges || $upload || $upload2)
{
   $height=addslashes($height);
   $weight=addslashes($weight);
   $parents_name=addslashes($parents_name);
   $parents_address=addslashes($parents_address);
   $person_live=addslashes($person_live);
   $address_live=addslashes($address_live);
   $schools_attended1=addslashes($schools_attended1);
   $dates_attended1=addslashes($dates_attended1);
   $schools_attended2=addslashes($schools_attended2);
   $dates_attended2=addslashes($dates_attended2);
   $schools_attended3=addslashes($schools_attended3);
   $dates_attended3=addslashes($dates_attended3);
   $schools_attended4=addslashes($schools_attended4);
   $dates_attended4=addslashes($dates_attended4);
   $hours_credit_preceding=addslashes($hours_credit_preceding);
   $hours_credit_total=addslashes($hours_credit_total);
   $frfall=addslashes($frfall);
   $frwinter=addslashes($frwinter);
   $frspring=addslashes($frspring);
   $sofall=addslashes($sofall);
   $sowinter=addslashes($sowinter);
   $sospring=addslashes($sospring);
   $jrfall=addslashes($jrfall);
   $jrwinter=addslashes($jrwinter);
   $jrspring=addslashes($jrspring);
   $srfall=addslashes($srfall);
   $srwinter=addslashes($srwinter);
   $srspring=addslashes($srspring);
   $duration=addslashes($duration);
   $eligible_activities_list = implode(",",$eligible_activities);
   $rules_ineligible_list = implode(",",$rules_ineligible);
   $rules_ineligible_others=addslashes($rules_ineligible_others);
   $age_description=addslashes($age_description);
   $semester_hours_reason=addslashes($semester_hours_reason);
   $progress_report_provided=addslashes($progress_report_provided);
   $authority_aware_progress=addslashes($authority_aware_progress);
   $corrective_action_taken=addslashes($corrective_action_taken);
   $advised_summer_school=addslashes($advised_summer_school);
   $scholastic_requirements_policy=addslashes($scholastic_requirements_policy);
   $waive_fulfill_requirements=addslashes($waive_fulfill_requirements);
   $waive_fulfill_rationale=addslashes($waive_fulfill_rationale);
   $eligible_previous_school=addslashes($eligible_previous_school);
   $semester1_days=addslashes($semester1_days);
   $semester2_days=addslashes($semester2_days);
   $semester3_days=addslashes($semester3_days);
   $semester4_days=addslashes($semester4_days);
   $semester5_days=addslashes($semester5_days);
   $semester6_days=addslashes($semester6_days);
   $semester7_days=addslashes($semester7_days);
   $semester8_days=addslashes($semester8_days);
   $unable_8semesters_reason=addslashes($unable_8semesters_reason);
   $future_eligibility_considered=addslashes($future_eligibility_considered);
   $future_eligibility_rationale=addslashes($future_eligibility_rationale);
   $dropout_circumstances=addslashes($dropout_circumstances);
   $additional_information=addslashes($additional_information);
   $ward_of_court=addslashes($ward_of_court);
   $previous_school_eligible=addslashes($previous_school_eligible);
   $unable_live_parent=addslashes($unable_live_parent);
   $submitter=addslashes($submitter);
   if($submitfinal && ($studentid=="" || $parents_name=="" || $parents_address=="" || $person_live=="" || $address_live=="" || $schools_attended1=="" || $dates_attended1=="" || $hours_credit_preceding=="" || $hours_credit_total=="" || $eligible_activities=="" || $rules_ineligible=="" || $submitter==""))
   {
      $error='1';
   }
   else
   {
      $error='0';
   }

   //if applicable, delete uploaded documents
   for($i=0;$i<count($deletedocid);$i++)
   {
      if($deletedoc[$i]=='x')
      {
         $sql="SELECT * FROM hardship_documents WHERE id='$deletedocid[$i]'";
	 $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 citgf_unlink("/home/nsaahome/attachments/".$row[document]);
	 $sql="DELETE FROM hardship_documents WHERE id='$deletedocid[$i]'";
	 $result=mysql_query($sql);
//	echo "$sql<br>".mysql_error();
      }
   }
   
   //update database
   if($upload || $upload2 || $save || ($error=='1' && $submitfinal))
      $datesub="";
   else $datesub=time();
   if($id=='0')
   {
      $sql="INSERT INTO hardship (school,datesub,submitter,studentid,height,weight,parents_name,parents_address,person_live,address_live,schools_attended1,dates_attended1,schools_attended2,dates_attended2,schools_attended3,dates_attended3,schools_attended4,dates_attended4,hours_credit_preceding,hours_credit_total,frfall,frwinter,frspring,sofall,sowinter,sospring,jrfall,jrwinter,jrspring,srfall,srwinter,srspring,eligible_activities,rules_ineligible,rules_ineligible_others,age_description,semester_hours_reason,progress_report_provided,authority_aware_progress,corrective_action_taken,advised_summer_school,scholastic_requirements_policy,waive_fulfill_requirements,waive_fulfill_rationale,eligible_previous_school,semester1_days,semester2_days,semester3_days,semester4_days,semester5_days,semester6_days,semester7_days,semester8_days,unable_8semesters_reason,future_eligibility_considered,future_eligibility_rationale,dropout_circumstances,additional_information,ward_of_court,previous_school_eligible,unable_live_parent) VALUES ('$school2','$datesub','$submitter','$studentid','$height','$weight','$parents_name','$parents_address','$person_live','$address_live','$schools_attended1','$dates_attended1','$schools_attended2','$dates_attended2','$schools_attended3','$dates_attended3','$schools_attended4','$dates_attended4','$hours_credit_preceding','$hours_credit_total','$frfall','$frwinter','$frspring','$sofall','$sowinter','$sospring','$jrfall','$jrwinter','$jrspring','$srfall','$srwinter','$srspring','$eligible_activities_list','$rules_ineligible_list','$rules_ineligible_others','$age_description','$semester_hours_reason','$progress_report_provided','$authority_aware_progress','$corrective_action_taken','$advised_summer_school','$scholastic_requirements_policy','$waive_fulfill_requirements','$waive_fulfill_rationale','$eligible_previous_school','$semester1_days','$semester2_days','$semester3_days','$semester4_days','$semester5_days','$semester6_days','$semester7_days','$semester8_days','$unable_8semesters_reason','$future_eligibility_considered','$future_eligibility_rationale','$dropout_circumstances','$additional_information','$ward_of_court','$previous_school_eligible','$unable_live_parent')";
   }
   else
   {
      $sql="UPDATE hardship SET ";
      if(!$savechanges) $sql.="school='$school2',datesub='$datesub',submitter='$submitter',";
      if($savechanges && $level==1 && $edit==1) $sql.="eligible='$eligible',execcomments='".addslashes($execcomments)."',nsaacomments='".addslashes($nsaacomments)."',execdate='".mktime(0,0,0,$execm,$execd,$execy)."',execsigfile='$execsigfile',";
      $sql.="studentid='$studentid',height='$height',weight='$weight',parents_name='$parents_name',parents_address='$parents_address',person_live='$person_live',address_live='$address_live',schools_attended1='$schools_attended1',dates_attended1='$dates_attended1',schools_attended2='$schools_attended2',dates_attended2='$dates_attended2',schools_attended3='$schools_attended3',dates_attended3='$dates_attended3',schools_attended4='$schools_attended4',dates_attended4='$dates_attended4',hours_credit_preceding='$hours_credit_preceding',hours_credit_total='$hours_credit_total',frfall='$frfall',frspring='$frspring',frwinter='$frwinter',sofall='$sofall',sowinter='$sowinter',sospring='$sospring',jrfall='$jrfall',jrwinter='$jrwinter',jrspring='$jrspring',srfall='$srfall',srwinter='$srwinter',srspring='$srspring',eligible_activities='$eligible_activities_list',rules_ineligible='$rules_ineligible_list',rules_ineligible_others='$rules_ineligible_others',age_description='$age_description',semester_hours_reason='$semester_hours_reason',progress_report_provided='$progress_report_provided',authority_aware_progress='$authority_aware_progress',corrective_action_taken='$corrective_action_taken',advised_summer_school='$advised_summer_school',scholastic_requirements_policy='$scholastic_requirements_policy',waive_fulfill_requirements='$waive_fulfill_requirements',waive_fulfill_rationale='$waive_fulfill_rationale',eligible_previous_school='$eligible_previous_school',semester1_days='$semester1_days',semester2_days='$semester2_days',semester3_days='$semester3_days',semester4_days='$semester4_days',semester5_days='$semester5_days',semester6_days='$semester6_days',semester7_days='$semester7_days',semester8_days='$semester8_days',unable_8semesters_reason='$unable_8semesters_reason',future_eligibility_considered='$future_eligibility_considered',future_eligibility_rationale='$future_eligibility_rationale',dropout_circumstances='$dropout_circumstances',additional_information='$additional_information',ward_of_court='$ward_of_court',previous_school_eligible='$previous_school_eligible',unable_live_parent='$unable_live_parent' WHERE id='$id'";
   }
   $result=mysql_query($sql);
   //echo $edit."<br>".$sql."<br>".mysql_error();
   //exit();
   if($id=='0')
   {
      $id=mysql_insert_id();
	/*
      $sql="SELECT id FROM hardship WHERE school='$school2' AND studentid='$studentid' ORDER BY id DESC LIMIT 1";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $id=$row[0];
	*/
   }

   if($upload)
   {
      $uploadedfile = $_FILES['document']['tmp_name'];
      $uploaderror="";
      if(is_uploaded_file($uploadedfile))
      {
         $ext = strtolower(pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION));
         $filename="hardship".time().".".$ext;
         if(!citgf_copy($uploadedfile,"/home/nsaahome/attachments/".$filename))
            $uploaderror="Could not upload the file. Is the file too large? File must be smaller than 3MB.";
         else
         {
            $sql="INSERT INTO hardship_documents (hardship_id,document) VALUES ('$id','$filename')";
            $result=mysql_query($sql);
            if(mysql_error()) $uploaderror=mysql_error();
         }
      }
      else
      {
         $uploaderror="We're sorry, but it appears you did not select a file to upload. Please try again.";
      }
   }

   if($upload2)
   {
      $uploadedfile = $_FILES['transcript']['tmp_name'];
      $uploaderror2="";
      if(is_uploaded_file($uploadedfile))
      {
         $ext = strtolower(pathinfo($_FILES['transcript']['name'], PATHINFO_EXTENSION));
         $filename="transcript".time().".".$ext;
         if(!citgf_copy($uploadedfile,"/home/nsaahome/attachments/".$filename))
            $uploaderror2="Could not upload the file. Is the file too large? File must be smaller than 3MB.";
         else
         {
            $sql="UPDATE hardship SET transcript='$filename' WHERE id='$id'";
            $result=mysql_query($sql);
            if(mysql_error()) $uploaderror2=mysql_error();
         }
      }
      else
      {
         $uploaderror2="We're sorry, but it appears you did not select a file to upload. Please try again.";
      }
   }

   $sql2="SELECT * FROM hardship WHERE id='$id'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $tfile=trim($row2[transcript]);
   if($tfile=='' || !citgf_file_exists("/home/nsaahome/attachments/".$tfile))
      $error=1;

   if($level!=1 && trim($submitter)=="") $error=1;


   if($submitfinal && $error==0)
   {
      $From="nsaa@nsaahome.org"; $FromName="NSAA";
      $To="mhuber@nsaahome.org"; $ToName="Megan Huber";
      $Subject="A Hardship Request Form has been Submitted";
      $Text="A Hardship Request form has been submitted by $school.\r\n\r\nPlease login to the School's Login and go to the Hardship Admin under the Eligibility Section to view and take action on this form.\r\n\r\nThank You!";
      $Html="A Hardship Request form has been submitted by $school.<br><br>Please login to the <a href=\"https://secure.nsaahome.org/nsaaforms/\">School's Login</a> and go to the Hardship Admin under the Eligibility Section to view and take action on this form.<br><br>Thank You!";
      $Attm=array();
      if($school!="Test's School") SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);
   }

   if($edit==1) $header="no";
   if($upload || $upload2)
      header("Location:hardship.php?session=$session&id=$id&error=$error&uploaderror2=$uploaderror2&uploaderror=$uploaderror&header=$header#uploaddocs");
   else
      header("Location:hardship.php?session=$session&id=$id&error=$error&uploaderror=$uploaderror&header=$header");
   exit();
}

echo $init_html;
?>
<script type="text/javascript">
function updateStudentInfo(option){
    var id = option.options[option.selectedIndex].id.split("_");
    document.getElementById('dob').value = id[1];
    document.getElementById('semesters').value = id[2];
    document.getElementById('grade').value = id[3];
}

function toggleSection(chkbox){
    var disSetting = (chkbox.checked) ? "block" : "none";
    document.getElementById(chkbox.value+'_section').style.display = disSetting;
}
</script>
<?php
if($header=='no' || $edit==1)
   echo "<table width=100%><tr align=center><td>";
else
{
   echo GetHeader($session);
   if($level!=1)
      echo "<br><a href=\"hardshipforms.php?session=$session\">Hardship Request Forms HOME</a><br>";
   else
      echo "<br><a class=small href=\"hardshipadmin.php?session=$session\">Hardship Request Forms ADMIN</a><br>";
}
if($error=='0' && $level!=1)	//just submitted
{
   echo "<br><table width=600><tr align=left><td><font style=\"color:red\"><b>The following form has been submitted to the NSAA.<br>You may print this form off for your records OR view it at any time using the <a class=small href=\"hardshipforms.php?session=$session\">Hardship Forms</a> link under the Eligibility section on your welcome page.</b></font></td></tr></table>";
}
if($level==1 && $edit!=1)
{
   echo "<br><a href=\"hardship.php?session=$session&id=$id&edit=1&header=no\">EDIT</a> &nbsp;&nbsp; <a href=\"javascript:window.print();\">PRINT</a> &nbsp;&nbsp; <a href=\"javascript:window.close();\">CLOSE</a><br>";
}
echo "<br><table class=nine width=90%><caption><b>HARDSHIP REQUEST/NSAA RULING NOTICE</b><br><br><i>Student transcripts must be submitted with this request form.</i><br><br></caption>";

//if $id given, check if submitted or not and get entered info
if($id)
{
   $sql="SELECT * FROM hardship WHERE id='$id'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[datesub]=='') $submitted=0;
   else $submitted=1;
   $school=$row[school];
   $school2=addslashes($school);
   $datesub=$row[datesub];
   $submitter=$row[submitter];
   if($edit==1 && !$studentid) $studentid=$row[studentid];
   else if($edit!=1 && ($submitted || !$studentid)) $studentid=$row[studentid];
   $height=$row[height];
   $weight=$row[weight];
   $parents_name=$row[parents_name];
   $parents_address=$row[parents_address];
   $person_live=$row[person_live];
   $address_live=$row[address_live];
   $schools_attended1=$row[schools_attended1];
   $dates_attended1=$row[dates_attended1];
   $schools_attended2=$row[schools_attended2];
   $dates_attended2=$row[dates_attended2];
   $schools_attended3=$row[schools_attended3];
   $dates_attended3=$row[dates_attended3];
   $schools_attended4=$row[schools_attended4];
   $dates_attended4=$row[dates_attended4];
   $hours_credit_preceding=$row[hours_credit_preceding];
   $hours_credit_total=$row[hours_credit_total];
   $frfall=$row[frfall]; $frwinter=$row[frwinter]; $frspring=$row[frspring];
   $sofall=$row[sofall]; $sowinter=$row[sowinter]; $sospring=$row[sospring];
   $jrfall=$row[jrfall]; $jrwinter=$row[jrwinter]; $jrspring=$row[jrspring];
   $srfall=$row[srfall]; $srwinter=$row[srwinter]; $srspring=$row[srspring];
   $eligible_activities=$row[eligible_activities];
   $rules_ineligible=$row[rules_ineligible];
   $rules_ineligible_others=$row[rules_ineligible_others];
   $age_description=$row[age_description];
   $semester_hours_reason=$row[semester_hours_reason];
   $progress_report_provided=$row[progress_report_provided];
   $authority_aware_progress=$row[authority_aware_progress];
   $corrective_action_taken=$row[corrective_action_taken];
   $advised_summer_school=$row[advised_summer_school];
   $scholastic_requirements_policy=$row[scholastic_requirements_policy];
   $waive_fulfill_requirements=$row[waive_fulfill_requirements];
   $waive_fulfill_rationale=$row[waive_fulfill_rationale];
   $eligible_previous_school=$row[eligible_previous_school];
   $semester1_days=$row[semester1_days];
   $semester2_days=$row[semester2_days];
   $semester3_days=$row[semester3_days];
   $semester4_days=$row[semester4_days];
   $semester5_days=$row[semester5_days];
   $semester6_days=$row[semester6_days];
   $semester7_days=$row[semester7_days];
   $semester8_days=$row[semester8_days];
   $unable_8semesters_reason=$row[unable_8semesters_reason];
   $future_eligibility_considered=$row[future_eligibility_considered];
   $future_eligibility_rationale=$row[future_eligibility_rationale];
   $dropout_circumstances=$row[dropout_circumstances];
   $additional_information=$row[additional_information];
   $ward_of_court=$row[ward_of_court];
   $previous_school_eligible=$row[previous_school_eligible];
   $unable_live_parent=$row[unable_live_parent];
   $execcomments=$row[execcomments]; $nsaacomments=$row[nsaacomments];
   $execdate=$row[execdate];
   $execsignature=$row[execsignature];
   $execsigfile=trim($row[execsigfile]);
   $eligible=$row[eligible];
   $transcript=$row[transcript];

   // get student information
   $sql2="SELECT * FROM eligibility WHERE id='$studentid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $dob=$row2[dob];
   $semesters=$row2[semesters];
   if($studentid) $grade=GetYear($row2['semesters']);
} 

if($submitted==0 || ($level==1 && $edit==1)){
//Instructions/Notes:
echo "<tr align=left><td><b>This form is to be used when requesting a waiver of any eligibility rule.</b></td></tr>";
echo "<tr align=left><td>The Executive Director shall have the authority to make specific exception to the student eligibility rules, provided such exceptions are based upon hardship conditions, which are deemed to have contributed in a significant way to the failure of a student to be able to comply with a specific rule(s).";
echo "<ol><li>Loss of eligibility in itself is not to be considered as a hardship.  The circumstances causing the ineligibility will be the basis upon which a waiver is considered.</li>";
echo "<li>A hardship exists only when there are some unique circumstances concerning the student's educational, physical, or emotional status, which are beyond the control of the student and his/her parents or legal guardian.</li>";
echo "<li>The circumstances must be totally different from those, which exist for the majority of students who are confronted with similar situations and choices.  Usual maturation problems and family situations, which do not cause physical harm, academic or athletic deficiencies in a school's curriculum or extracurricular activities, do not constitute a hardship.</li>";
echo "<li>There must be no reason to believe that non-compliance with the rule requested to be waived was for activity participation purposes.</li>";
echo "</ol></td></tr>";
echo "<tr align=left><td><b>If your school wishes to file a hardship request for an individual student, please follow the instructions given.</b>";
echo "<ol><li>Using the four criteria given above as a guide, please submit an explanation specifying the reasons why, in your opinion, this case is a hardship situation.</li>";
echo "<li>Requests for hardship rulings will be considered by the Executive Director after such time that a student is actually ineligible for interscholastic competition.  If the case involves the request to waive Article 2, Section 2.6 & 2.7, Domicile and Transfer Rules, the transfer or change in domicile must have occurred.  A request made in advance of the loss of eligibility, or on an assumption, will not be considered.</li>";
echo "<li>The Age Limitation Bylaw 2.3.1 is an objective standard; a student must meet the age limitation set forth in Bylaw 2.3.1 to be eligible for participation and competition.  The only consideration for a waiver of the age eligibility rule shall be to determine if a discrepancy exists in the student’s reported Date of Birth that, upon further examination and evidence, would result in the establishment of the correct Date of Birth.  [See, Pottgen v. MSHSAA, 40 F. 3d. 926 (8th Cir. 1994).]</li>";
echo "<li>Hardship requests shall be initiated by the school where the student is enrolled.  It is the school's responsibility to obtain all requested documentation and to provide all information.</li>";
echo "<li><u>If the documentation or information requested or provided is of a confidential nature such as transcripts, medical records, law enforcement records, etc., be sure to obtain a signed waiver from the student, his/her parents, and any other required party prior to making the information or document available.  Submit a copy of the signed waiver.</u></li>";
echo "<li>When submitting a request, be sure to provide sufficient time for the Executive Director to consider the case and return a statement of the findings and decision.  The general information portion of the form is to be completed for all requests.  On the remaining pages, the information requested is specifically related to certain rules.  Provide the requested information, which applies to the particular rule for which the waiver is being requested.  In addition, any information or evidence pertinent to the situation including the following should be submitted.";
echo "<ol><li>If a statement is made as to an existing situation causing a hardship, the situation should be fully explained and documented by factual information.</li>";
echo "<li>If the student is a transfer, a statement from the superintendent, principal, or designate of the school formerly attended by the student relating to the case.</li>";
echo "<li>Statements from state and county officials, welfare agencies, parents, relatives, physicians, and/or others who are in a position to provide information regarding the situation.</li>";
echo "</ol>";
echo "</li>";
echo "<li>A hearing may be requested.  If a hearing is desired, please submit the request with the application for a waiver.</li>";
echo "<li>The decision of the Executive Director may be appealed to the Board of Control as provided in Article I, Section 1.10, Paragraph 1.10.3.12. NSAA Constitution.</li>";
echo "</ol></td></tr>";
echo "<tr align=left><td>This form must be completed and submitted on-line.  One copy, with the Executive Director's decision, will be returned to the school.</td></tr>";
echo "<tr align=left><td>ALL FIELDS IN <font style=\"color:red\">RED</font> ARE REQUIRED.<br><br></td></tr>";
}
if($error==1)
{
?>
<script language="javascript">
alert('You have not completed one or more REQUIRED fields (in red). Please complete these fields and submit your form again.');
</script>
<?php
}

//School Name, Date Submitted, Person Submitting Information:
if($submitted==0) $color="red";
else $color="black";
echo "<tr align=left><td><font style=\"color:$color\"><b>School:</b></font>&nbsp;$school</td></tr>";
echo "<tr align=left><td><font style=\"color:$color\"><b>Today's Date:</b></font>&nbsp;".date("m/d/Y")."</td></tr>";
if($submitted==1)
{
   echo "<tr align=left><td colspan=2><b>$school submitted this form to the NSAA on:</b> ".date("F j, Y",$datesub)."</td></tr>";
}

echo "<form id=\"hardship\" method=post action=\"hardship.php#formtop\" enctype=\"multipart/form-data\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=id value=\"$id\">";
echo "<input type=hidden name=edit value=\"$edit\">";
echo "<input type=hidden name=header value=\"$header\">";
if($submitted==0 || ($level==1 && $edit==1))
{
     echo "<tr align=center><td>";
     echo "<ol><a name=\"formtop\">&nbsp;</a>";
     echo "<li><i>(If your student is not listed or the Date of Birth and/or Grade is incorrect, you must go to your eligibility list and make the necessary additions/corrections.)</i></li>";
     echo "<li>";
     echo "<label for=\"studentid\" class=\"red\">Name of Student:</label>";
     echo "<select name=studentid onchange=\"submit();\"><option value=''>~</option>";
	if($datesub)
	{
            $year=date("Y",$datesub);
            $month=date("m",$datesub);
            if($month<6) $year--;
            $eligdb=GetDatabase($year);
	}
	else $eligdb=$db_name;
     $sql="SELECT * FROM $eligdb.eligibility WHERE school='$school2' ";
     $sql.="ORDER BY last,first,semesters";
     $result=mysql_query($sql);
     while($row=mysql_fetch_array($result))
     {
        $curgrade=GetYear($row['semesters']);
        echo "<option id=\"$row[id]_$row[dob]_$row[semesters]_$curgrade\" value=\"$row[id]\"";
        if($studentid==$row[id]) { echo " selected"; $grade=$curgrade; $dob=$row[dob]; $semesters=$row[semesters]; }
        echo ">$row[last], $row[first] ($curgrade)</option>";
     }
     ?>
     </select>
     <ul>
	<?php if($dob): ?>
	<?php
	$d=explode("-",$dob);
	$sql3="SELECT TIMESTAMPDIFF(YEAR,'$d[2]-$d[0]-$d[1]',CURDATE()) AS age";
	$result3=mysql_query($sql3);
	$row3=mysql_fetch_array($result3);
	?>
      <li>
     <label for="dob" class="red">Date of Birth:</label> <?php echo $dob?>
     </li>
      <li>
     <label for="age" class="red">Age:</label> <?php echo $row3[age]; ?>
     </li>
	<?php endif; ?>
<?php if($grade): ?>
     <li>
     <label for="grade" class="red">Grade in School:</label> <?php echo $grade; ?>
     </li>
<?php endif; ?>
     <li>
     <label for="height">Height:</label>
     <input type="text" class="tiny" size="5" name="height" value="<?php echo $height; ?>">
     </li>
     <li>
     <label for="weight">Weight:</label>
     <input type="text" class="tiny" size="5" name="weight" value="<?php echo $weight; ?>">
     </li>
	</ul>
      </li>
     <li>
     <label for="parents_name" class="red">Name of Parents:</label>
     <input type="text" class="tiny" size="60" name="parents_name" value="<?php echo $parents_name; ?>">
     </li>
     <li>
     <label for="parents_address" class="red">Address of Parents:</label>
     <input type="text" class="tiny" size="60" name="parents_address" value="<?php echo $parents_address; ?>">
     </li>
     <li>
     <label for="person_live" class="red">Person with whom student will live:</label>
     <input type="text" class="tiny" size="60" name="person_live" value="<?php echo $person_live; ?>">
     </li>
     <li>
     <label for="address_live" class="red">Address (where student will live):</label>
     <input type="text" class="tiny" size="60" name="address_live" value="<?php echo $address_live; ?>">
     </li>
     <li><label for="schools_attended" class="oneline">Give the Name(s) of Schools Attended Since Enrollment in Grade 9.</label><ul>
     <?php for($i=1; $i <= 4; $i++){ ?>
          <?php
          $schools_attended = "schools_attended".$i;
          $dates_attended = "dates_attended".$i;
          ?>
          <li>
          <label for="schools_attended<?php echo $i; ?>" <?php if($i == 1){ echo "class=\"red\""; } ?>>School:</label>
          <input type="text" class="tiny" size="60" name="schools_attended<?php echo $i; ?>" value="<?php echo $$schools_attended; ?>">
          &nbsp;&nbsp;&nbsp;
          <label for="dates_attended<?php echo $i; ?>" <?php if($i == 1){ echo "class=\"red\""; } ?>>Dates Attended:</label><input type="text" class="tiny" size="20" name="dates_attended<?php echo $i; ?>" value="<?php echo $$dates_attended; ?>">
          </li>
     <?php } ?>
	</ul></li>
     <li>
     <label style="width: 275px;" for="semesters" class="red">Total Semesters of Membership Grades 9-12:</label> <?php echo $semesters; ?>
     </li>
     <li>
     <label for="hours_credit_preceding" class="red">Hours of Credit Earned Immediate Preceding Semester:</label>
     <input type="text" class="tiny" size="5" name="hours_credit_preceding" value="<?php echo $hours_credit_preceding; ?>">
     </li>
     <li>
     <label for="hours_credit_total" class="red">Total Semester Hours of Credit Earned:</label>
     <input type="text" class="tiny" size="5" name="hours_credit_total" value="<?php echo $hours_credit_total; ?>">
     </li>
     <li><label for="activity" class="oneline"><b>For each year, list the Fall, Spring and Winter activities the student has participated in.</b></label><br>
	<table cellspacing=0 cellpadding=3>
	<tr align=center><td>&nbsp;<td>FALL</td><td>WINTER</td><td>SPRING</td></tr>
	<tr align=center><td align=right>Freshman</td>
		<td><input type=text size=30 name="frfall" value="<?php echo $frfall; ?>"></td>
                <td><input type=text size=30 name="frwinter" value="<?php echo $frwinter; ?>"></td>
                <td><input type=text size=30 name="frspring" value="<?php echo $frspring; ?>"></td>
	</tr>
        <tr align=center><td align=right>Sophomore</td>
                <td><input type=text size=30 name="sofall" value="<?php echo $sofall; ?>"></td>
                <td><input type=text size=30 name="sowinter" value="<?php echo $sowinter; ?>"></td>
                <td><input type=text size=30 name="sospring" value="<?php echo $sospring; ?>"></td>
        </tr>
        <tr align=center><td align=right>Junior</td>
                <td><input type=text size=30 name="jrfall" value="<?php echo $jrfall; ?>"></td>
                <td><input type=text size=30 name="jrwinter" value="<?php echo $jrwinter; ?>"></td>
                <td><input type=text size=30 name="jrspring" value="<?php echo $jrspring; ?>"></td>
        </tr>
        <tr align=center><td align=right>Senior</td>
                <td><input type=text size=30 name="srfall" value="<?php echo $srfall; ?>"></td>
                <td><input type=text size=30 name="srwinter" value="<?php echo $srwinter; ?>"></td>
                <td><input type=text size=30 name="srspring" value="<?php echo $srspring; ?>"></td>
        </tr>
	</table>
     </li>
	<li>
     <label for="eligible_activities" class="oneline red">Our school requests this student be declared eligible for (check activities)</label>
	<table cellspacing=0 cellpadding=5>
	<tr align=left valign=top><td>
     <?php
	$curcol=0; $percol=count($activity)/4; $ix=0;
     foreach($activity as $num => $act)
     {
	if($curcol>=$percol)
	{
	   $curcol=0;
	   echo "</td><td>";
 	}
        $act_name = GetActivityName($act);
        echo "<input type=\"checkbox\" name=\"eligible_activities[]\" value=\"$act\"";
        if(strpos($eligible_activities, $act) !== FALSE) { echo " checked"; }
        echo "> $act_name<br>";
	$curcol++; $ix++;
     }
     ?>
	</td></tr></table>
	<div style='clear:both;'></div>
     </li>
     <li>
     <label for="rules_ineligible" class="oneline red">What NSAA rule(s) cause(s) this student to be ineligible for interscholastic activities? (check those that apply to this case)</label>
     <div>
     <input type="checkbox" name="rules_ineligible[]" value="age_discrepancy" <?php if (strpos($rules_ineligible, "age_discrepancy") !== FALSE){ echo "checked"; } ?> onclick="toggleSection(this);"> Age-discrepancy in the reported Date of Birth.<br>
     <input type="checkbox" name="rules_ineligible[]" value="domicile_transfer" <?php if (strpos($rules_ineligible, "domicile_transfer") !== FALSE){ echo "checked"; } ?> onclick="toggleSection(this);"> Domicile-transfer rule.<br>
     <input type="checkbox" name="rules_ineligible[]" value="scholastic" <?php if (strpos($rules_ineligible, "scholastic") !== FALSE){ echo "checked"; } ?> onclick="toggleSection(this);"> Scholastic rule-not passing twenty hours the immediate preceding semester.<br>
     <input type="checkbox" name="rules_ineligible[]" value="semester" <?php if (strpos($rules_ineligible, "semester") !== FALSE){ echo "checked"; } ?> onclick="toggleSection(this);"> Eight or six-semester rule.<br>
     <input type="checkbox" name="rules_ineligible[]" value="enrollment" <?php if (strpos($rules_ineligible, "enrollment") !== FALSE){ echo "checked"; } ?>> Did not enroll in some school by the eleventh school day of the current semester. (<b>Document</b> circumstances causing the failure to enroll by the eleventh school day.)<br>
     <input type="checkbox" name="rules_ineligible[]" value="others" <?php if (strpos($rules_ineligible, "others") !== FALSE){ echo "checked"; } ?>> Others. (Please Specify) <input type="text" class="tiny" size="125" name="rules_ineligible_others" value="<?php echo $rules_ineligible_others; ?>">
     </div>
     </li>
     <?php $style = (strpos($rules_ineligible, "age_discrepancy") !== FALSE) ? "display: block;" : "display: none;"; ?>
     <li id="age_discrepancy_section" style="<?php echo $style; ?>">
     <fieldset>
     <legend>Information for a waiver of Article 2, Section 2.3, Paragraph 2.3.1, NSAA Bylaws, the Age Rule</legend>
     <ol>
     <li><label for="age_description" class="oneline">If there is a discrepancy in the student's reported Date of Birth, what was the nature of that erroneous report?</label>
     <br>
     <textarea name=age_description rows=6 cols=70><?php echo $age_description; ?></textarea>
     </li>
     <li>
     <label class="oneline">In order for a waiver to be granted, a birth certificate certified by the Bureau of Vital Statistics, State Capitol, Lincoln, or the Bureau of Vital Statistics of any other state must be produced to verify the correct age of the student.</label>
     </li>
     </ol>
     </fieldset>
     </li>
     <?php $style = (strpos($rules_ineligible, "scholastic") !== FALSE) ? "display: block;" : "display: none;"; ?>
     <li id="scholastic_section" style="<?php echo $style; ?>">
     <fieldset>
     <legend>Information for a waiver of Article 2, Section 2.5, Paragraph 2.5.2, NSAA Bylaws, the Preceding Semester Rule</legend>
     <p><label for="semester_hours_reason" class="oneline">In your opinion, why wasn't the student able to perform the necessary requirements in order to obtain the minimum of twenty sememster hours of credit?</label>
     <br>
     <textarea name="semester_hours_reason" rows="6" cols="70"><?php echo $semester_hours_reason; ?></textarea>
     </p>
     <p><label for="progress_report_provided" class="oneline">During the previous semester, was an academic progress report provided to the student and his/her parents?</label></p>
     <p>
     <input type="radio" name="progress_report_provided" value="y" <?php if($progress_report_provided=='y'){ echo " checked"; } ?>>Yes&nbsp;
     <input type="radio" name="progress_report_provided" value="n" <?php if($progress_report_provided=='n'){ echo " checked"; } ?>>No
     </p>
     <p><label for="authority_aware_progress" class="oneline">During the previous semester, were the student's counselor, coach, principal, or teachers aware that the student was not making sufficient academic progress?</label></p>
     <p>
     <input type="radio" name="authority_aware_progress" value="y" <?php if($authority_aware_progress=='y'){ echo " checked"; } ?>>Yes&nbsp;
     <input type="radio" name="authority_aware_progress" value="n" <?php if($authority_aware_progress=='n'){ echo " checked"; } ?>>No
     </p>
     <p>
     <label for="corrective_action_taken" class="oneline">What corrective action was taken?</label>
     <br>
     <textarea name="corrective_action_taken" rows="6" cols="70"><?php echo $corrective_action_taken; ?></textarea>
     </p>
     <p><label for="advised_summer_school" class="oneline">If the immediate Preceding Semester was the Spring Semester, was the student advised to attend Summer School or enroll in Correspondence Courses?</label></p>
     <p>
     <input type="radio" name="advised_summer_school" value="y" <?php if($advised_summer_school=='y'){ echo " checked"; } ?>>Yes&nbsp;
     <input type="radio" name="advised_summer_school" value="n" <?php if($advised_summer_school=='n'){ echo " checked"; } ?>>No
     </p>
     <p><label for="scholastic_requirements_policy" class="oneline">Does your school district have a policy requiring a student to meet certain scholastic requirements in order to participate in activities?</label></p>
     <p>
     <input type="radio" name="scholastic_requirements_policy" value="y" <?php if($scholastic_requirements_policy=='y'){ echo " checked"; } ?>>Yes&nbsp;
     <input type="radio" name="scholastic_requirements_policy" value="n" <?php if($scholastic_requirements_policy=='n'){ echo " checked"; } ?>>No
     </p>
     <p><label for="waive_fulfill_requirements" class="oneline">Prior to making an application for a Hardship Waiver, has the school agreed to waive the district policy or give the student an opportunity to fulfill the requirements?</label></p>
     <p>
     <input type="radio" name="waive_fulfill_requirements" value="y" <?php if($waive_fulfill_requirements=='y'){ echo " checked"; } ?>>Yes&nbsp;
     <input type="radio" name="waive_fulfill_requirements" value="n" <?php if($waive_fulfill_requirements=='n'){ echo " checked"; } ?>>No
     </p>
     <p>
     <label for="waive_fulfill_rationale" class="oneline">Please give rationale for answer</label>
     <br>
     <textarea name="waive_fulfill_rationale" rows="6" cols="70"><?php echo $waive_fulfill_rationale; ?></textarea>
     </p>
     <p><label for="eligible_previous_school" class="oneline">If the student is a transfer student, would he/she have been eligible at his/her previous school?</label></p>
     <p>
     <input type="radio" name="eligible_previous_school" value="y" <?php if($eligible_previous_school=='y'){ echo " checked"; } ?>>Yes&nbsp;
     <input type="radio" name="eligible_previous_school" value="n" <?php if($eligible_previous_school=='n'){ echo " checked"; } ?>>No
     </p>
     <p><label class="oneline">Please <b>document</b> any extenuating circumstances, which may have caused the student to be ineligible.</label></p>
     </fieldset>
     </li>
     <?php $style = (strpos($rules_ineligible, "semester") !== FALSE) ? "display: block;" : "display: none;"; ?>
     <li id="semester_section" style="<?php echo $style; ?>">
     <fieldset>
     <legend>Information for a waiver of Article 2, Section 2.2, Paragraph 2.2.2 & 2.2.3, NSAA Bylaws, the Eight Semester or Four Seasons of Participation Rule</legend>
     <p><label class="oneline">Please give the number of days of school membership for each of the semesters in which the student was a member of some school in grades 9 through and including the present semester.</label></p>
     <p>
     <?php for($i=1; $i <=8; $i++){ ?>
         <?php $value_var = "semester".$i."_days"; ?>
         <label for="semester<?php echo $i;?>_days">Semester <?php echo $i; ?></label>
         <input type="text" class="tiny" size="5" name="semester<?php echo $i; ?>_days" value="<?php echo $$value_var; ?>">
     <?php } ?>
     </p>
     <p><label for="unable_8semesters_reason" class="oneline">Give the reasons why the student was unable to complete grades 9-12 in eight semesters.</label>
     <br>
     <textarea name="unable_8semesters_reason" rows="6" cols="70"><?php echo $unable_8semesters_reason; ?></textarea>
     </p>
     <p style="margin-left: 20px;">
     <label for="future_eligibility_considered" class="oneline">If the reasons were disciplinary action by the school, was future eligibility to participate in activities taken into consideration prior to taking the action?</label>
     <br>
     <input type="radio" name="future_eligibility_considered" value="y" <?php if($future_eligibility_considered=='y'){ echo " checked"; } ?>>Yes&nbsp;
     <input type="radio" name="future_eligibility_considered" value="n" <?php if($future_eligibility_considered=='n'){ echo " checked"; } ?>>No
     </p>
     <p style="margin-left: 20px;">
     <label for="future_eligibility_rationale" class="oneline">Please give rationale.</label>
     <br>
     <textarea name="future_eligibility_rationale" rows="6" cols="70"><?php echo $future_eligibility_rationale; ?></textarea>
     </p>
     <p style="margin-left: 20px;">
     <label class="oneline">If the reasons were because of a physical injury, illness, chemical dependency rehabilitation, or incarceration, please explain and provide the following information if applicable:  (1) Statements or records from a licensed medical physician which include diagnoses, treatment, and care. (2) If due to chemical dependency, statements from the care facility and/or the individual responsible for the treatment.</label>
     </p>
     <p style="margin-left: 20px;">
     <label for="dropout_circumstances" class="oneline">If the reason was voluntary dropout, and there were extenuating circumstances, please explain the circumstances. </label>
     <br>
     <textarea name="dropout_circumstances" rows="6" cols="70"><?php echo $dropout_circumstances; ?></textarea>
     </p>
     <p><label for="addtional_information" class="oneline">Additional Information</label>
     <br>
     <textarea name="additional_information" rows="6" cols="70"><?php echo $additional_information; ?></textarea>
     </p>
     </fieldset>
     </li>
     <?php $style = (strpos($rules_ineligible, "domicile_transfer") !== FALSE) ? "display: block;" : "display: none;"; ?>
     <li id="domicile_transfer_section" style="<?php echo $style; ?>">
     <fieldset>
     <legend>Information for a waiver of Article 2, Sections 2.6 & 2.7, NSAA Bylaws, the Domicile and Transfer Rule</legend>
     <p><label for="ward_of_court" class="oneline">Is Student a Ward of a Court?</label></p>
     <p>
     <input type="radio" name="ward_of_court" value="y" <?php if($ward_of_court=='y'){ echo " checked"; } ?>>Yes&nbsp;
     <input type="radio" name="ward_of_court" value="n" <?php if($ward_of_court=='n'){ echo " checked"; } ?>>No
	&nbsp;&nbsp;<i>(If <b>YES</b> must upload the legal documenation below.)</i>
     </p>
     <p><label for="previous_school_eligible" class="oneline">If the student had continued to attend the school from which he/she transferred, would the student have been eligible?</label></p>
     <p>
     <input type="radio" name="previous_school_eligible" value="y" <?php if($previous_school_eligible=='y'){ echo " checked"; } ?>>Yes&nbsp;
     <input type="radio" name="previous_school_eligible" value="n" <?php if($previous_school_eligible=='n'){ echo " checked"; } ?>>No
     </p>
     <p>
     <label class="oneline">Why is it necessary for the student to transfer? (<b>Document</b> with written statements from individuals who have knowledge of the situation which cause the hardship conditions)</label>
     </p>
     <p><label for="unable_live_parent" class="oneline">If the individual's parents are divorced or separated and the student has previously lived with one of the parents and will live with someone other than either parent when transferring to your school, explain why he/she is unable to live with the other parent.</label>
     <br>
     <textarea name="unable_live_parent" rows="6" cols="70"><?php echo $unable_live_parent; ?></textarea>
     </p>
     </fieldset>
     </li>
     <li id="transcript_upload_section">
     <fieldset><a name="uploadtrans">&nbsp;</a>
     <legend style="color:red;">Upload Transcript (REQUIRED)</legend>
	<?php
	if(citgf_file_exists("/home/nsaahome/attachments/".$transcript) && $transcript!='')
	{
        	echo "<p><a href=\"attachments.php?session=$session&filename=$transcript\">$transcript (Click to View)</a>&nbsp;&nbsp;&nbsp;<input type=checkbox name=\"deletetranscript\" value=\"x\"> Delete Transcript</p>";
	}
	?>
     <p><input type="file" name="transcript">&nbsp;<input type="submit" name="upload2" value="UPLOAD TRANSCRIPT"><br>
     <?php
     if($uploaderror2!=""){ echo "<div class=error style=\"width:400px;\">$uploaderror2</div><br>"; }
     echo "<div class=alert><p><b>PLEASE NOTE:</b> The file must be <b><u>LESS THAN 3 MEGABYTES</b></u> in size.</p>";
     echo "<p>The file can be in the following formats: .pdf, .jpg, .png, .gif, .doc, .docx</p></div>";
     ?>
     </p>
	<?php
        if(citgf_file_exists("/home/nsaahome/attachments/".$transcript) && $transcript!='')
        {
           echo "<br><p><i>Check the box next to \"Delete Transcript\" and then click \"Save\" below to delete any of the documents above.</i></p>";
	}
	?>
     </fieldset>
     </li>
     <li id="file_upload_section">
     <fieldset><a name="uploaddocs">&nbsp;</a>
     <legend>Upload Supporting Documentation</legend>
     <p><label class="oneline"><b>Upload DIGITAL COPIES of supporting documentation, one at a time, below:</b></label></p>
     <?php
     $sql="SELECT document,id FROM hardship_documents WHERE hardship_id='$id'";
     $result=mysql_query($sql);
	$d=0;
     while($row=mysql_fetch_array($result)){
        echo "<p><a href=\"attachments.php?session=$session&filename=$row[document]\" class=small>$row[document] (Click to View)</a>&nbsp;&nbsp;&nbsp;<input type=checkbox name=\"deletedoc[$d]\" value=\"x\"><input type=hidden name=\"deletedocid[$d]\" value=\"$row[id]\"> Delete Document</p>";
	$d++;
     }
     ?>
     <p><input type="file" name="document">&nbsp;<input type="submit" name="upload" value="UPLOAD DOCUMENTATION"><br>
     <?php
     if($uploaderror!=""){ echo "<div class=error style=\"width:400px;\">$uploaderror</div><br>"; }
     echo "<div class=alert><p><b>PLEASE NOTE:</b> The file must be <b><u>LESS THAN 3 MEGABYTES</b></u> in size.</p>";
     echo "<p>The file can be in the following formats: .pdf, .jpg, .png, .gif, .doc, .docx</p></div>";
     ?>
     </p>
	<?php if($d>0): ?>
		<br><p><i>Check the box next to "Delete Document" and then click "Save" below to delete any of the documents above.</i></p>
	<?php endif; ?>
     </fieldset>
     </li>
     </ol>
     </td></tr>
<?php
}
else	//SUBMITTED & NOT EDITING - PRINTABLE
{
         $year=date("Y",$datesub);
         $month=date("m",$datesub);
         if($month<6) $year--;
         $eligdb=GetDatabase($year);
   $sql="SELECT * FROM $eligdb.eligibility WHERE id='$studentid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $grade = GetYear($row['semesters']);
   $dob=$row[dob]; $d=explode("-",$dob);
        $sql3="SELECT TIMESTAMPDIFF(YEAR,'$d[2]-$d[0]-$d[1]',CURDATE()) AS age";
        $result3=mysql_query($sql3);
        $row3=mysql_fetch_array($result3);
	$age=$row3[age];
   ?>
     <tr align=left><td><b>Name of Student:</b> <?php echo $row['first']." ".$row['last']; ?></td></tr>
     <tr align=left><td><b>Date of Birth:</b> <?php echo $row['dob']; ?></td></tr>
     <tr align=left><td><b>Age:</b> <?php echo $age; ?></td></tr>
     <tr align=left><td><b>Height:</b> <?php echo $height; ?>&nbsp;&nbsp;&nbsp;<b>Weight:</b> <?php echo $weight; ?></td></tr>
     <tr align=left><td><b>Name of Parents:</b> <?php echo $parents_name; ?></td></tr>
     <tr align=left><td><b>Address of Parents:</b> <?php echo $parents_address; ?></td></tr>
     <tr align=left><td><b>Person with whom student will live:</b> <?php echo $person_live; ?></td></tr>
     <tr align=left><td><b>Address </b>(where student will live): <?php echo $address_live; ?></td></tr>
     <tr align=left><td><b>Name(s) of Schools Attended Since Enrollment in Grade 9:</b></td></tr>
     <?php for($i=1; $i <= 4; $i++){ ?>
          <?php
          $schools_attended = "schools_attended".$i;
          $dates_attended = "dates_attended".$i;
	  if($$schools_attended!="")
	  {
          ?>
          <tr align=left style="text-indent: 50px;"><td><b>School:</b> <?php echo $$schools_attended; ?>&nbsp;&nbsp;&nbsp;<b>Dates Attended:</b> <?php echo $$dates_attended; ?></td></tr>
     <?php } } ?>
     <tr align=left><td><b>Grade in School:</b> <?php echo $grade; ?></td></tr>
     <tr align=left><td><b>Total Semesters of Membership Grades 9-12:</b> <?php echo $semesters; ?></td></tr>
     <tr align=left><td><b>Hours of Credit Earned Immediate Preceding Semester:</b> <?php echo $hours_credit_preceding; ?></td></tr>
     <tr align=left><td><b>Total Semester Hours of Credit Earned:</b> <?php echo $hours_credit_total; ?></td></tr>
     <tr align=left><td><b>Activity and seasons of prior participation beginning with Grade 9: </b><br>
	<table style="margin-left:50px;">
	<tr align=center><td>&nbsp;</td><td>FALL</td><td>WINTER</td><td>SPRING</td></tr>
        <tr align=left><td align=right>Freshman:</td>
		<td><?php echo $frfall; ?></td>
		<td><?php echo $frwinter; ?></td>
		<td><?php echo $frspring; ?></td>
        </tr>
        <tr align=left><td align=right>Sophomore:</td>
                <td><?php echo $sofall; ?></td>
                <td><?php echo $sowinter; ?></td>
                <td><?php echo $sospring; ?></td>
        </tr>
        <tr align=left><td align=right>Junior</td>
                <td><?php echo $jrfall; ?></td>
                <td><?php echo $jrwinter; ?></td>
                <td><?php echo $jrspring; ?></td>
        </tr>
        <tr align=left><td align=right>Senior</td>
                <td><?php echo $srfall; ?></td>
                <td><?php echo $srwinter; ?></td>
                <td><?php echo $srspring; ?></td>
        </tr>
	</table>
     </td></tr>
     <tr align=left><td><b>Our school requests this student be declared eligible for: </b></td></tr>
     <tr align=left style="text-indent: 50px;"><td>
     <?php
     $activities = explode(",",$eligible_activities);
     foreach($activities as $act){
        $act_name = GetActivityName($act);
        echo $act_name."&nbsp;";
     }
     ?>
     </td></tr>
     <tr align=left><td><b>NSAA rule(s) that cause this student to be ineligible for interscholastic activities: </b><br>
     <?php
     if (strpos($rules_ineligible, "age_discrepancy") !== FALSE){ echo "Age-discrepancy in the reported Date of Birth.<br>"; }
     if (strpos($rules_ineligible, "domicile_transfer") !== FALSE){ echo "Domicile-transfer rule.<br>"; }
     if (strpos($rules_ineligible, "scholastic") !== FALSE){ echo "Scholastic rule-not passing twenty hours the immediate preceding semester.<br>"; }
     if (strpos($rules_ineligible, "semester") !== FALSE){ echo "Eight or six-semester rule.<br>"; }
     if (strpos($rules_ineligible, "enrollment") !== FALSE){ echo "Did not enroll in some school by the eleventh school day of the current semester.<br>"; }
     if (strpos($rules_ineligible, "others") !== FALSE){ echo "Others: ".$rules_ineligible_others."<br>"; }
     ?>
     </td></tr>
     <?php if(strpos($rules_ineligible, "age_discrepancy") !== FALSE){ ?>
          <tr align="left"><td><b>If there is a discrepancy in the student's reported Date of Birth, what was the nature of that erroneous report?</b><br>
          <?php echo $age_description; ?><br></td></tr>
     <?php } ?>
     <?php if(strpos($rules_ineligible, "scholastic") !== FALSE){ ?>
          <tr align="left"><td><b>In your opinion, why wasn't the student able to perform the necessary requirements in order to obtain the minimum of twenty sememster hours of credit?</b><br>
          <?php echo $semester_hours_reason; ?><br></td></tr>
          <tr align="left"><td><b>During the previous semester, was an academic progress report provided to the student and his/her parents?</b><br>
          <?php echo $progress_report_provided; ?><br></td></tr>
          <tr align="left"><td><b>During the previous semester, were the student's counselor, coach, principal, or teachers aware that the student was not making sufficient academic progress?</b><br>
          <?php echo $authority_aware_progress; ?><br></td></tr>
          <tr align="left"><td><b>What corrective action was taken?</b><br>
          <?php echo $corrective_action_taken; ?><br></td></tr>
          <tr align="left"><td><b>If the immediate Preceding Semester was the Spring Semester, was the student advised to attend Summer School or enroll in Correspondence Courses?</b><br>
          <?php echo $advised_summer_school; ?><br></td></tr>
          <tr align="left"><td><b>Does your school district have a policy requiring a student to meet certain scholastic requirements in order to participate in activities?</b><br>
          <?php echo $scholastic_requirements_policy; ?><br></td></tr>
          <tr align="left"><td><b>Prior to making an application for a Hardship Waiver, has the school agreed to waive the district policy or give the student an opportunity to fulfill the requirements?</b><br>
          <?php echo $waive_fulfill_requirements; ?><br></td></tr>
          <tr align="left"><td><b>Please give rationale for answer</b><br>
          <?php echo $waive_fulfill_rationale; ?><br></td></tr>
          <tr align="left"><td><b>If the student is a transfer student, would he/she have been eligible at his/her previous school?</b><br>
          <?php echo $eligible_previous_school; ?><br></td></tr>
     <?php } ?>
     <?php if(strpos($rules_ineligible, "semester") !== FALSE){ ?>
          <tr align="left"><td><b>Please give the number of days of school membership for each of the semesters in which the student was a member of some school in grades 9 through and including the present semester.</b></td></tr>
          <?php for($i=1; $i <=8; $i++){ ?>
              <?php $value_var = "semester".$i."_days"; ?>
              <tr align="left" style="text-indent: 50px;"><td><b>Semester <?php echo $i; ?></b> <?php echo $$value_var; ?></td></tr>
          <?php } ?>
          <tr align="left"><td><b>Give the reasons why the student was unable to complete grades 9-12 in eight semesters.</b><br>
          <?php echo $unable_8semesters_reason; ?><br></td></tr>
          <tr align="left"><td><b>If the reasons were disciplinary action by the school, was future eligibility to participate in activities taken into consideration prior to taking the action?</b><br>
          <?php echo $future_eligibility_considered; ?><br></td></tr>
          <tr align="left"><td><b>Please give rationale.</b><br>
          <?php echo $future_eligibility_rationale; ?><br></td></tr>
          <tr align="left"><td><b>If the reason was voluntary dropout, and there were extenuating circumstances, please explain the circumstances. </b><br>
          <?php echo $dropout_circumstances; ?><br></td></tr>
          <tr align="left"><td><b>Additional Information</b><br>
          <?php echo $additional_information; ?><br></td></tr>
     <?php } ?>
     <?php if(strpos($rules_ineligible, "domicile_transfer") !== FALSE){ ?>
          <tr align="left"><td><b>Is Student a Ward of a Court?</b><br>
          <?php echo $ward_of_court; ?><br></td></tr>
          <tr align="left"><td><b>If the student had continued to attend the school from which he/she transferred, would the student have been eligible?</b><br>
          <?php echo $previous_school_eligible; ?><br></td></tr>
          <tr align="left"><td><b>If the individual's parents are divorced or separated and the student has previously lived with one of the parents and will live with someone other than either parent when transferring to your school, explain why he/she is unable to live with the other parent.</b><br>
          <?php echo $unable_live_parent; ?><br></td></tr>
     <?php } ?>
     <tr align="left"><td><b>Digital Copy of TRANSCRIPT:</b> <a href="attachments.php?session=<?php echo $session; ?>&filename=<?php echo $transcript; ?>"><?php echo $transcript; ?></a></td></tr>
     <tr align="left"><td><b>DIGITAL COPIES of supporting documentation:</b></td></tr>
     <?php
     $sql="SELECT document FROM hardship_documents WHERE hardship_id='$id'";
     $result=mysql_query($sql);
     while($row=mysql_fetch_array($result)){
        echo "<tr align=\"left\" style=\"text-indent: 50px;\"><td><a href=\"attachments.php?session=$session&filename=$row[document]\" class=small>$row[document] (Click to View)</a></td></tr>";
     }
     ?>
<?php
}

if($level==1 && $edit!=1)
{   
   echo "<tr align=center><td><br><a href=\"hardship.php?session=$session&id=$id&edit=1&header=no\">EDIT</a> &nbsp;&nbsp; <a href=\"javascript:window.print();\">PRINT</a> &nbsp;&nbsp; <a href=\"javascript:window.close();\">CLOSE</a></td></tr>";
}

//Electronic Signature
if($submitted==0)
{
   echo "<tr align=left><td><br><i>The <b>Action of the Executive Director</b> will be shown on the printable version of this form which you will be able to download after the action has been submitted.  You will be able to download this version of the form from your Schools Login Home screen, under the \"Eligiblity\" section.  Thank You!</i></td></tr>";
   echo "<tr align=left><td><br><b>You may click \"Save & Keep Editing\" and come back to finish this form at a later time.<br>";
   echo "<input type=submit name=save value=\"Save & Keep Editing\"></td></tr>";
   echo "<tr align=left><td><br><b>Once you are sure the form is COMPLETE, please sign this form (electronically) below and click \"Submit this Form\":<br>";
   echo "<font style=\"color:red\">Electronic Signature of Principal or Designate:&nbsp;</font>";
   echo "<input type=text name=submitter class=tiny size=30>&nbsp;&nbsp;";
   echo "<input type=submit name=submitfinal value=\"Submit this Form\"><br>";
   echo "<i>&nbsp;&nbsp;&nbsp;(Please type your name in the box above.  This will serve as your \"electronic\" signature.)</i></b></td></tr>";
}
else if($level==1 && $edit==1 && $execsignature=='x')	//LEVEL 1 IS EDITING EXEC ACTION
{
   echo "<tr align=left><td><b>Electronic Signature of Principal or Designate:&nbsp;</b>$submitter</td></tr>";
   echo "<tr align=center><td><hr><font style=\"font-size:9pt;\"><b><br>Action of the Executive Director</b></font></td></tr>";
   echO "<tr align=center><td><table style=\"width:650px;\" cellspacing=2 cellpadding=2>";
   echo "<tr align=left><td colspan=2>The information on the hardship has been reviewed. and on the basis of the information presented, the student is:<br /><input type=radio name=eligible value='y'";
   if($eligible=='y') echo " checked";
   else $eligible='n';
   echo ">Eligible&nbsp;&nbsp;";
   echO "<input type=radio name=eligible value='n'";
   if($eligible=='n') echo " checked";
   echo ">Non-Eligible</td></tr>";
   echo "<tr align=left><td colspan=2><b>Comments:</b><br><textarea rows=3 cols=70 name=\"execcomments\">$execcomments</textarea></td></tr>";
   echo "<tr align=left><td colspan=2><b>Comments for NSAA ONLY:</b><br><textarea rows=3 cols=70 name='nsaacomments'>$nsaacomments</textarea></td></tr>";
   $execm=date("m",$execdate);
   $execd=date("d",$execdate);
   $execy=date("Y",$execdate);
   echO "<tr align=left valign=center><td align=right><b>Date:</b> <input type=text size=3 maxlength=2 name=\"execm\" value=\"$execm\">/<input type=text size=3 maxlength=2 name=\"execd\" value=\"$execd\">/<input type=text size=5 maxlength=4 name=\"execy\" value=\"$execy\">&nbsp;&nbsp;&nbsp;";
   echo "<b>Signature: </b></td><td><input type=radio name=\"execsigfile\" value=\"jay.png\"";
   if($execsigfile=="" || $execsigfile=="blanfordgreensig.png" || $execsigfile=="jay.png") echo " checked";
   echo "><img title=\"Jay Bellar\" src=\"../images/jay.png\" style=\"height:30px;\">";
   echo "&nbsp;&nbsp;<input type=radio name=\"execsigfile\" value=\"dveldersig.png\"";
   if($execsigfile=="dveldersig.png") echo " checked";
   echo "><img title=\"Deb Velder\" src=\"../images/dveldersig.png\" style=\"height:30px;\">";
   echo "</td></tr>";
   echo "<tr align=center><td colspan=2><br><input type=submit name=\"savechanges\" value=\"Save Changes\"></td></tr>";
   echo "</table></td></tr>";
}
else 	//submitted by school
{
   echo "<tr align=left><td><b>Electronic Signature of Principal or Designate:&nbsp;</b>$submitter</td></tr>";
   if($execsignature=='x')	//signed by exec dir, show action of exec dir:
   {
      echo "<tr align=center><td><hr><font style=\"font-size:9pt;\"><b><br>Action of the Executive Director</b></font></td></tr>";
      echo "<tr align=center><td><table width=500 cellspacing=2 cellpadding=2>";
      if($eligible=='y') $eligword="eligible";
      else $eligword="ineligible";
      echo "<tr align=left><td colspan=2>The information on the hardship has been reviewed, and on the basis of the information presented, the student is <b><i>$eligword</b></i>.</td></tr>";
      echo "<tr align=left><td colspan=2><b>Comments:</b> <i>$execcomments</i></td></tr>";
      if($level==1)
	 echo "<tr align=left><td colspan=2><b>Comments for NSAA ONLY:</b> <i>$nsaacomments</i></td></tr>";
      echo "<tr valign=center><td align=right><b>Date:</b> ".date("m/d/Y",$execdate)."&nbsp;&nbsp;&nbsp;";
      echo "<b>Signature:</b></td><td align=left><img src=\"../images/".$execsigfile."\" height=30></td></tr>";
      echo "</table></td></tr>";
   }
   else if($level!=1)
   {
      echo "<tr align=center><td><hr><font style=\"font-size:9pt;\"><b><br>Action of the Executive Director</b></font></td></tr>";
      echO "<tr align=center><td><br>[No action taken yet.]</td></tr>";
   }
   else if($level==1 && $edit!=1)
   {
      echo "<tr align=center><td><hr><font style=\"font-size:9pt;\"><b><br>Action of the Executive Director</b></font></td></tr>";
      echO "<tr align=center><td><table style=\"width:650px;\" cellspacing=2 cellpadding=2>";
      echo "<tr align=left><td colspan=2>The information on the hardship has been reviewed, and on the basis of the information presented, the student is:<br><input type=radio name=eligible value='y'>Eligible&nbsp;&nbsp;";
      echO "<input type=radio name=eligible value='n'>Non-Eligible</td></tr>";
      echo "<tr align=left><td colspan=2><b>Comments:</b><br><textarea rows=3 cols=70 name=\"execcomments\"></textarea></td></tr>";
      echo "<tr align=left><td colspan=2><b>Comments for NSAA ONLY:</b><br><textarea rows=3 cols=70 name=\"nsaacomments\"></textarea></td></tr>";
      echO "<tr align=left><td><b>Date:</b> ".date("m/d/Y")."</td>";
      //echo "<td><b>Signature: _______________________________________</b></td></tr>";
      echo "<td><b>Signature: </b><input type=radio name=\"execsigfile\" value=\"jay.png\" checked";
      echo "><img title=\"Jay Bellar\" src=\"../images/jay.png\" style=\"height:30px;\">";
      echo "&nbsp;&nbsp;<input type=radio name=\"execsigfile\" value=\"dveldersig.png\"";
      echo "><img title=\"Deb Velder\" src=\"../images/dveldersig.png\" style=\"height:30px;\">";
      echo "</td></tr>";
      //echo "<tr align=left><td>&nbsp;</td><td>(Signature will be inserted once \"Submit Action\" is clicked.)</td></tr>";
      echo "<tr align=center><td colspan=2><input type=submit name=submit value=\"Submit Action\"></td></tr>";
   } 
   else if($level==1)	//allow admin to edit without taking action
   {
      echo "<tr align=center><td><hr><input type=submit name=\"savechanges\" value=\"Save Changes\"><br><i>(No executive action will be taken.)</i></td></tr>";
   }
}
echo "</table>";
echo "</form>";

echo $end_html;
?>
