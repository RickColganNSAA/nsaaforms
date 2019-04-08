<?php
//forex.php: International Transfer Students Eligibility Confirmation Form

require 'functions.php';
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
if($level!=1) $editsettings=0;

if($editsettings==1 && $savesettings)
{
   $sql="UPDATE forexsettings SET formtitle='".addslashes($formtitle)."',formnickname='".addslashes($formnickname)."',introtext='".addslashes($introtext)."',toname='".addslashes($toname)."',toemail='$toemail'";
   $result=mysql_query($sql);
   $saveerrors="";
if(mysql_error()) $saveerrors.=mysql_error();
   if(trim($formtitle)=="") $saveerrors.="<p>ERROR: Please enter the FORM TITLE.</p>";
   else if(trim($formnickname)=="") $saveerrors.="<p>ERROR: Please enter the FORM NICKNAME.</p>";
}
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
   $sql="SELECT school FROM forex WHERE id='$id'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $school=$row[0];
}
else if(!$editsettings)
{
   echo "<br><br>ERROR: No school or form id provided.";
   exit();
}
$school2=ereg_replace("\'","\'",$school);

if(!$id) $id='0';

//GET SETTINGS OF THE FORM FROM forexsettings
$sql="SELECT * FROM forexsettings";
$result=mysql_query($sql);
$forminfo=mysql_fetch_array($result);

if($submit && $level==1)	//action of the executive director
{
   $execcomments=addslashes($execcomments);
   $execdate=time();
   $execsignature='x';
   $sql="SELECT * FROM forex WHERE id='$id'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $oldexeccomments=addslashes($row[execcomments]); $oldexecsignature=$row[execsignature]; $oldeligible=$row[eligible];

   $sql="UPDATE forex SET execcomments='$execcomments', execdate='$execdate', execsignature='$execsignature', eligible='$eligible' WHERE id='$id'";
   $result=mysql_query($sql);

   //update eligibility table with eligible value
   $sql="SELECT studentid FROM forex WHERE id='$id'";
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
      $Subject=$forminfo[formtitle]." Status Notification";
      $sql3="SELECT first,last FROM eligibility WHERE id='$studid'";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      $Html="Action of the Executive Director of the NSAA has been taken regarding your student, $row3[first] $row3[last].<br><br>Please login at <a href=\"https://secure.nsaahome.org/nsaaforms\">https://secure.nsaahome.org/nsaaforms</a> to view the action of the Executive Director.<br><br>Thank You!";
      $Text="Action of the Executive Director of the NSAA has been taken regarding your student, $row3[first] $row3[last].\r\n\r\nPlease login at https://secure.nsaahome.org/nsaaforms to view the action of the Executive Director.\r\n\r\nThank You!";
      $Attm=array();
      if($To!='')
         SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);
   }
}

if($submitfinal)
{
   if(trim($submitter)=="") $error=1;
}

if($save || $submitfinal || $savechanges)
{
   $transfersch=addslashes($transfersch);
   $country=addslashes($country);
   $hostname=addslashes($hostname);
   $hostaddress=addslashes($hostaddress);
   $hostcitystate=addslashes($hostcitystate);
   $hostzip=addslashes($hostzip);
   $grade=addslashes($grade);
   $homeschdate=addslashes($homeschdate);
   $schenrolldate=addslashes($schenrolldate);
   $duration=addslashes($duration);
   $attendprevsch=addslashes($attendprevsch);
   $prevsch=addslashes($prevsch);
   $prevcitystate=addslashes($prevcitystate);
   $prevdates=addslashes($prevdates);
   $pgmname=addslashes($pgmname);
   $pgmaddress=addslashes($pgmaddress);
   $visatype=addslashes($visatype);
   $procedure=addslashes($procedure);
   $involvement=addslashes($involvement);
   $submitter=addslashes($submitter);
   if($submitfinal && ($studentid=="" || $transfersch=="" || $country=="" || $hostname=="" || $hostaddress=="" || $hostcitystate=="" || $hostzip=="" || $grade=="" || $homeschdate=="" || $schenrolldate=="" || $duration=="" || $attendprevsch=="" || ($attendprevsch=='y' && ($prevsch=="" || $prevcitystate=="" || $prevdates=="")) || $pgmname=="" || $pgmaddress=="" || $visatype=="" || $submitter==""))
   {
      $error='1';
   }
   else
   {
      $error='0';
   }
   
   //update database
   if($save || ($error=='1' && $submitfinal))
      $datesub="";
   else $datesub=time();
   if($id=='0')
   {
      $sql="INSERT INTO forex (school,datesub,submitter,studentid,transfersch,country,hostname,hostaddress,hostcitystate,hostzip,grade,homeschdate,schenrolldate,duration,attendprevsch,prevsch,prevcitystate,prevdates,pgmname,pgmaddress,visatype,procedureused,involvement) VALUES ('$school2','$datesub','$submitter','$studentid','$transfersch','$country','$hostname','$hostaddress','$hostcitystate','$hostzip','$grade','$homeschdate','$schenrolldate','$duration','$attendprevsch','$prevsch','$prevcitystate','$prevdates','$pgmname','$pgmaddress','$visatype','$procedure','$involvement')"; 
   }
   else
   {
      $sql="UPDATE forex SET ";
      if(!$savechanges) $sql.="school='$school2',datesub='$datesub',submitter='$submitter',";
      if($savechanges && $level==1 && $edit==1) $sql.="eligible='$eligible',execcomments='$execcomments',";
      $sql.="studentid='$studentid',transfersch='$transfersch',country='$country',hostname='$hostname',hostaddress='$hostaddress',hostcitystate='$hostcitystate',hostzip='$hostzip',grade='$grade',homeschdate='$homeschdate',schenrolldate='$schenrolldate',duration='$duration',attendprevsch='$attendprevsch',prevsch='$prevsch',prevcitystate='$prevcitystate',prevdates='$prevdates',pgmname='$pgmname',pgmaddress='$pgmaddress',visatype='$visatype',procedureused='$procedure',involvement='$involvement' WHERE id='$id'";
   }
   $result=mysql_query($sql);
   //echo $edit."<br>".$sql."<br>".mysql_error();
   //exit();
   if($id=='0')
   {
      $sql="SELECT id FROM forex WHERE school='$school2' AND studentid='$studentid' ORDER BY id DESC LIMIT 1";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $id=$row[0];
   }

   if($submitfinal && $error==0)
   {
      $From="nsaa@nsaahome.org"; $FromName="NSAA";
      $To=$forminfo[toemail]; $ToName=$forminfo[toname];
      $Subject=$forminfo[formtitle]." Submitted";
      $Html="A";
      if(preg_match("#[aeiou]#i",strtolower(substr($forminfo[formtitle],0,1)))) $Html.="n";
      $Html=" ".$forminfo[formtitle]." has been submitted by $school.<br><br>Please login to the <a href=\"https://secure.nsaahome.org/nsaaforms/\">School's Login</a> and go to the ".$forminfo[formnickname]." Admin under the Eligibility Section to view and take action on this form.<br><br>Thank You!";
      $Text=preg_replace("/\<br\>/","\r\n",$Html);
      $Attm=array();
      SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$Attm);
   }

   if($edit==1) $header="no";
   header("Location:forex.php?session=$session&id=$id&error=$error&header=$header");
   exit();
}

echo $init_html;
if($editsettings==1)
{
?>
<script type="text/javascript">
tinyMCE.init({
        mode : 'exact',
	elements: 'introtext',
        theme : 'advanced',
        skin : 'o2k7',
        skin_variant : 'black',
        convert_urls : false,
        relative_urls : false,
        plugins : 'safari,iespell,preview,media,searchreplace,paste,',
        theme_advanced_buttons1 : 'bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,pastetext,pasteword,|,undo,redo,|,link,unlink,image,media,|,code,preview',
        theme_advanced_buttons2 : '',
        theme_advanced_toolbar_location : 'top',
        theme_advanced_toolbar_align : 'left',
        theme_advanced_statusbar_location : 'bottom',
        theme_advanced_resizing : true,
        // Example content CSS (should be your site CSS)
        content_css : '../css/plain.css',
        // Drop lists for link/image/media/template dialogs
        template_external_list_url : 'lists/template_list.js',
        external_link_list_url : 'lists/link_list.js',
        external_image_list_url : 'lists/image_list.js',
        media_external_list_url : 'lists/media_list.js'
        });
        </script>
<?php
}//end if editsettings
if($header=='no' || $edit==1)
   echo "<table width=100%><tr align=center><td>";
else
{
   echo GetHeader($session);
   if($level!=1)
      echo "<br><a class=small href=\"forexforms.php?session=$session\">".$forminfo[formnickname]."s HOME</a>";
   else
      echo "<br><a class=small href=\"forexadmin.php?session=$session\">".$forminfo[formnickname]."s ADMIN</a>";
}
if($error=='0' && $level!=1)	//just submitted
{
   echo "<br><table width=600><tr align=left><td><font style=\"color:red\"><b>The following form has been submitted to the NSAA.<br>You may print this form off for your records OR view it at any time using the <a class=small href=\"forexforms.php?session=$session\">".$forminfo[formntitle]."s</a> link under the Eligibility section on your welcome page.</b></font></td></tr></table>";
}
echo "<form method=post action=\"forex.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=id value=\"$id\">";
echo "<input type=hidden name=edit value=\"$edit\">";
echo "<input type=hidden name=\"editsettings\" value=\"$editsettings\">";
echo "<input type=hidden name=header value=\"$header\">";
if($level==1 && $id>0 && $edit!=1)
{
   echo "<br><a href=\"forex.php?session=$session&id=$id&edit=1&header=no\">EDIT</a><br>";
}
else if($level==1 && !$id && $editsettings!=1)	//EDIT SETTINGS
{
   echo "<br /><a href=\"forex.php?session=$session&editsettings=1\">EDIT FORM SETTINGS</a><br>";
}
echo "<br><table class=nine style=\"max-width:800px\"><caption>";
if($editsettings==1)
{
   echo "<br /><h1>Edit Form Settings:</h1>";
   if($saveerrors && $saveerrors!='')
      echo "<div class='error'>$saveerrors</div>";
   else if($savesettings)
      echo "<div class='alert'>Your changes have been saved.</div>";
   echo "<p><a href=\"forex.php?session=$session&school_ch=Test's School\">Return to Preview Form</a></p>";
   echo "<div class='help' style=\"text-align:center;font-size:15px;width:700px;\"><b>FORM TITLE:</b> <input type=text size=70 name=\"formtitle\" value=\"".$forminfo[formtitle]."\"><br />
	<b>FORM NICKNAME (shortened title):</b> <input type=text size=40 name=\"formnickname\" value=\"".$forminfo[formnickname]."\"></div>";
}
else
{
   echo "<b>".$forminfo[formtitle]."</b>";
}
echo "</caption>";

//Instructions/Notes:
if($editsettings==1)
{
   echo "<tr align=center><td>";
   echo "<div class='help' style=\"width:700px;\"><h2>FORM DETAILS & INSTRUCTIONS:</h2>
	<textarea id=\"introtext\" name=\"introtext\" style=\"width:100%;height:400px;\">".$forminfo[introtext]."</textarea>
	</div><br />
	<div class='help' style=\"width:700px;\"><h2>E-mail Notifications of Submitted Forms to:</h2>
	<p><b>NAME:</b> <input type=text name=\"toname\" value=\"".$forminfo[toname]."\" size=\"40\"></p>
	<p><b>E-MAIL:</b> <input type=text name=\"toemail\" value=\"".$forminfo[toemail]."\" size=\"40\"></p>";
   echo "</td></tr></table>";
   echo "<input type=submit value=\"Save Form Settings\" name=\"savesettings\" class=\"fancybutton\"></form>";
   echo $end_html;
   exit();
}

//ELSE CONTINUE.....
echo "<tr align=left><td>";
echo $forminfo[introtext];
echo "</td></tr>";
echo "<tr align=left><td>ALL FIELDS IN <font style=\"color:red\">RED</font> ARE REQUIRED.<br><br></td></tr>";
if($error==1)
{
?>
<script language="javascript">
alert('You have not completed one or more REQUIRED fields (in red). Please complete these fields and submit your form again.');
</script>
<?php
}

//if $id given, check if submitted or not and get entered info
if($id)
{
   $sql="SELECT * FROM forex WHERE id='$id'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[datesub]=='') $submitted=0;
   else $submitted=1;
   $school=$row[school];
   $school2=addslashes($school);
   $datesub=$row[datesub]; $submitter=$row[submitter];
   if($edit==1 && !$studentid) $studentid=$row[studentid];
   else if($edit!=1 && ($submitted || !$studentid)) $studentid=$row[studentid];
   $transfersch=$row[transfersch]; $country=$row[country];
   $hostname=$row[hostname]; $hostaddress=$row[hostaddress];
   $hostcitystate=$row[hostcitystate]; $hostzip=$row[hostzip];
   $grade=$row[grade]; $homeschdate=$row[homeschdate]; $schenrolldate=$row[schenrolldate];
   $duration=$row[duration]; $attendprevsch=$row[attendprevsch];
   $prevsch=$row[prevsch]; $prevcitystate=$row[prevcitystate]; $prevdates=$row[prevdates];
   $pgmname=$row[pgmname]; $pgmaddress=$row[pgmaddress]; $visatype=$row[visatype];
   $procedure=$row[procedureused]; $involvement=$row[involvement];
   $execcomments=$row[execcomments]; $execdate=$row[execdate];
   $execsignature=$row[execsignature]; $eligible=$row[eligible];
} 

//School Name, Date Submitted, Person Submitting Information:
if($submitted==0) $color="red";
else $color="black";
echo "<tr align=left><td><font style=\"color:$color\"><b>School:</b></font>&nbsp;$school</td></tr>";
echo "<tr align=left><td><font style=\"color:$color\"><b>Today's Date:</b></font>&nbsp;".date("m/d/Y")."</td></tr>";
if($submitted==1)
{
   echo "<tr align=left><td colspan=2><b>$school submitted this form to the NSAA on:</b> ".date("F j, Y",$row[datesub])."</td></tr>";
}

//Section I: Student Record Information
echo "<tr align=center><td><br><b>Section I -- Student Record Information</b></td></tr>";
echo "<tr align=center><td><table width=600>";
if($submitted==0 || ($level==1 && $edit==1))
{
echo "<tr align=left><td><font style=\"color:red\">Name of Student:&nbsp;</font>";
echo "<select name=studentid onchange=\"submit();\"><option value=''>~</option>";
$sql="SELECT * FROM eligibility WHERE school='$school2' AND foreignx='y' ";
if(!($level==1 && $edit==1))	
   $sql.="AND eligible!='y' ";
$sql.="ORDER BY last,first";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\"";
   if($studentid==$row[id]) 
   {
      echo " selected";
      $dob=$row[dob]; $semesters=$row[semesters];
   }
   echo ">$row[first] $row[last]</option>";
}
echo "</select>";
echo "&nbsp;&nbsp;&nbsp;<font style=\"color:red\">Date of Birth:&nbsp;</font>$dob&nbsp;&nbsp;&nbsp;";
echo "<font style=\"color:red\">Current Year in School:&nbsp;</font>".GetYear($semesters)."<br>";
echo "&nbsp;&nbsp;&nbsp;<i>(If your student is not listed or the Date of Birth and/or Year in School is incorrect,<br>&nbsp;&nbsp;&nbsp;&nbsp;you must go to your eligibility list and make the necessary additions/corrections.)</i>";
echo "</td></tr>";
echo "<tr align=left><td class=red>Name of School Transferring From:&nbsp;";
echo "<input type=text class=tiny size=30 name=transfersch value=\"$transfersch\"><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Country Where School is Located:&nbsp;";
echo "<input type=text class=tiny size=20 name=country value=\"$country\"></td></tr>";
echo "<tr align=left><td class=red>Person With Whom Student Resides:&nbsp;";
echo "<input type=text class=tiny size=30 name=hostname value=\"$hostname\"><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Address:&nbsp;";
echo "<input type=text class=tiny size=40 name=hostaddress value=\"$hostaddress\"><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;City, State:&nbsp;";
echo "<input type=text class=tiny size=25 name=hostcitystate value=\"$hostcitystate\">&nbsp;&nbsp;";
echo "Zip:&nbsp;<input type=text class=tiny size=8 name=hostzip value=\"$hostzip\"></td></tr>";
}
else	//submitted
{
   echo "<tr align=left><td><b>Name of Student:</b>&nbsp;";
   $sql="SELECT * FROM eligibility WHERE id='$studentid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "$row[first] $row[last]&nbsp;&nbsp;&nbsp;<b>Date of Birth:</b>&nbsp;$row[dob]&nbsp;&nbsp;&nbsp;";
   echo "<b>Current Year in School:</b>&nbsp;".GetYear($row[semesters])."</td></tr>";
   echo "<tr align=left><td><b>Name of School Transferring From:&nbsp;</b>$transfersch<br>";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Country Where School is Located:&nbsp;</b>$country</td></tr>";
   echo "<tr align=left><td><b>Person With Whom Student Resides:&nbsp;</b>$hostname<br>";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Address:</b>&nbsp;$hostaddress<br>";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>City, State:&nbsp;</b>$hostcitystate&nbsp;&nbsp;";
   echo "<b>Zip:</b>&nbsp;$hostzip</td></tr>";
}
echo "</table></td></tr>";

//Section II: Eligibility Verification
echo "<tr align=center><td><br><b>Section II -- Eligibility Verification</b></td></tr>";
echo "<tr align=center><td><table width=600>";
if($submitted==0 || ($level==1 && $edit==1))
{
echo "<tr align=left><td class=red>Grade in School:&nbsp;";
echo "<input type=text class=tiny size=3 name=grade value=\"$grade\">&nbsp;&nbsp;";
echo "Last Date Previously Attended Home School:&nbsp;";
echo "<input type=text class=tiny size=20 name=homeschdate value=\"$homeschdate\"></td></tr>";
echo "<tr align=left><td class=red>Date of Enrollment in Present School:&nbsp;";
echo "<input type=text class=tiny size=20 name=schenrolldate value=\"$schenrolldate\"></td></tr>";
echo "<tr align=left><td class=red>Length of Time Student Will Be in Your School:&nbsp;";
echo "<input type=text class=tiny size=20 name=duration value=\"$duration\"></td></tr>";
echo "<tr align=left><td><font style=\"color:red\">Has Student Previously Attended High School in the United States?&nbsp;</font>";
echo "<input type=radio name=attendprevsch value='y'";
if($attendprevsch=='y') echo " checked";
echo ">Yes&nbsp;";
echo "<input type=radio name=attendprevsch value='n'";
if($attendprevsch=='n') echo " checked";
echo ">No</td></tr>";
echo "<tr align=left><td class=red>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "If YES, please indicate School, City & State, and Dates Attended (all 3 required):</td></tr>";
echo "<tr align=left><td class=red>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "U.S. School:&nbsp;<input type=text class=tiny size=30 name=prevsch value=\"$prevsch\"><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "City, State:&nbsp;<input type=text class=tiny size=20 name=prevcitystate value=\"$prevcitystate\"><br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "Dates Attended:&nbsp;<input type=text class=tiny size=30 name=prevdates value=\"$prevdates\"></td></tr>";
}
else
{
   echo "<tr align=left><td><b>Grade in School:</b>&nbsp;$grade&nbsp;&nbsp;";
   echo "<b>Last Date Previously Attended Home School:</b>&nbsp;$homeschdate</td></tr>";
   echo "<tr align=left><td><b>Date of Enrollment in Present School:</b>&nbsp;$schenrolldate</td></tr>";
   echO "<tr align=left><td><b>Length of Time Student Will Be in Your School:</b>&nbsp;$duration</td></tr>";
   echo "<tr align=left><td><b>Has Student Previously Attended High School in the United States?&nbsp;</b>";
   if($attendprevsch=='y') echo "Yes";
   else if($attendprevsch=='n') echo "No";
   echo "</td></tr>";
   echO "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>If YES, please indicate School, City & State, and Dates Attended (all 3 required):</b></td></tr>";
   echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
   echo "<b>U.S. School:</b>&nbsp;$prevsch<br>";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>City, State:&nbsp;</b>$prevcitystate<br>";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Dates Attended:</b>&nbsp;$prevdates</td></tr>";
} 
echo "</table></td></tr>";

//Section III: Exchange Program/Sponsorship
echo "<tr align=center><td><br><b>Section III -- Exchange Program/Sponsorship</b></td></tr>";
echo "<tr align=center><td><table width=600>";
if($submitted==0 || ($level==1 && $edit==1))
{
echo "<tr align=left><td class=red>Name of Exchange Program:&nbsp;";
echo "<input type=text class=tiny size=40 name=pgmname value=\"$pgmname\"></td></tr>";
echo "<tr align=left><td class=red>Address of Exchange Program:&nbsp;";
echo "<input type=text class=tiny size=40 name=pgmaddress value=\"$pgmaddress\"></td></tr>";
echo "<tr align=left><td class=red>Type of Visa (Example: J-1):&nbsp;";
echo "<input type=text class=tiny size=6 maxlength=5 name=visatype value=\"$visatype\"></td></tr>";
echo "<tr align=left><td>Explain the procedure used by the sponsoring organization to select your school as the school for the student to attend:<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<textarea name=procedure rows=6 cols=70>$procedure</textarea></td></tr>";
echo "<tr align=left><td>Explain your school's involvement in the selection process:<br>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<textarea name=involvement rows=6 cols=70>$involvement</textarea></td></tr>";
}
else
{
   echo "<tr align=left><td><b>Name of Exchange Program:</b>&nbsp;$pgmname</td></tr>";
   echo "<tr align=left><td><b>Address of Exchange Program:</b>&nbsp;$pgmaddress</td></tr>";
   echo "<tr align=left><td><b>Type of VISA:</b>&nbsp;$visatype</td></tr>";
   echo "<tr align=left><td><b>Explain the procedure used by the sponsoring organization to select your school as the school for the student to attend:</b><br>";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$procedure</td></tr>";
   echo "<tr align=left><td><b>Explain your school's involvement in the selection process:<br></b>";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$involvement</td></tr>";
}
echo "</table></td></tr>";
if($level==1 && $edit!=1 && $id>0)
{   
   echo "<tr align=center><td><br><a href=\"forex.php?session=$session&id=$id&edit=1&header=no\">EDIT</a></td></tr>";
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
else if($level==1 && $edit==1 && $execsignature=='x')
{
   echo "<tr align=left><td><b>Electronic Signature of Principal or Designate:&nbsp;</b>$submitter</td></tr>";
   echo "<tr align=center><td><hr><font style=\"font-size:9pt;\"><b><br>Action of the Executive Director</b></font></td></tr>";
   echO "<tr align=center><td><table width=500 cellspacing=2 cellpadding=2>";
   echo "<tr align=left><td colspan=2>The information on the exchange student has been reviewed. and on the basis of the information presented, the student is: <input type=radio name=eligible value='y'";
   if($eligible=='y') echo " checked";
   else $eligible='n';
   echo ">Eligible&nbsp;&nbsp;";
   echO "<input type=radio name=eligible value='n'";
   if($eligible=='n') echo " checked";
   echo ">Non-Eligible</td></tr>";
   echo "<tr align=left><td colspan=2><b>Comments:</b><br><textarea rows=3 cols=70 name=execcomments>$execcomments</textarea></td></tr>";
   echO "<tr align=left valign=center><td align=right><b>Date:</b> ".date("m/d/Y",$execdate)."&nbsp;&nbsp;&nbsp;";
   echo "<b>Signature: </b></td><td><img src=\"../images/tenopirsig.png\" height=30></td></tr>";
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
      echo "<tr align=left><td colspan=2>The information on the exchange student has been reviewed, and on the basis of the information presented, the student is <b><i>$eligword</b></i>.</td></tr>";
      echo "<tr align=left><td colspan=2><b>Comments:</b> <i>$execcomments</i></td></tr>";
      echo "<tr valign=center><td align=right><b>Date:</b> ".date("m/d/Y",$execdate)."&nbsp;&nbsp;&nbsp;";
      echo "<b>Signature:</b></td><td align=left><img src=\"../images/tenopirsig.png\" height=30></td></tr>";
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
      echO "<tr align=center><td><table width=500 cellspacing=2 cellpadding=2>";
      echo "<tr align=left><td colspan=2>The information on the exchange student has been reviewed. and on the basis of the information presented, the student is: <input type=radio name=eligible value='y'>Eligible&nbsp;&nbsp;";
      echO "<input type=radio name=eligible value='n'>Non-Eligible</td></tr>";
      echo "<tr align=left><td colspan=2><b>Comments:</b><br><textarea rows=3 cols=70 name=execcomments></textarea></td></tr>";
      echO "<tr align=left><td><b>Date:</b> ".date("m/d/Y")."</td>";
      echo "<td><b>Signature: _______________________________________</b></td></tr>";
      echo "<tr align=left><td>&nbsp;</td><td>(Signature will be inserted once \"Submit Action\" is clicked.)</td></tr>";
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
