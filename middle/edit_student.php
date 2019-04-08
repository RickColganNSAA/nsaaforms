<?php
/**************************************************
edit_student.php: 
Displays specifics of student's record
Copied from ../edit_student.php on 12/29/09
Author: Ann Gaffigan
***************************************************/

require '../variables.php';
require '../functions.php';

//connect to database:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

if($cancel)
{
   header("Location:eligibility.php?school_ch=$school_ch&letter=$letter&session=$session");
   exit();
}

$level=GetLevel($session);

//validate user
if(!ValidUser($session) || ($level!=1 && $level!=8))
{
   header("Location:index.php&error=1");
   exit();
}   

$student_id=$id;

//check if submit action was "delete"
if($delete=="Delete Student")
{
   $sql="DELETE FROM middleeligibility WHERE id='$id'";
   $result=mysql_query($sql);
   header("Location:eligibility.php?deleted=$id&session=$session&school_ch=$school_ch&letter=$letter");
   exit();
}
if($hiddensave || $save)
{
   $last=trim(addslashes($last)); $first=trim(addslashes($first)); $nickname=trim(addslashes($nickname));
   if($nickname!='') $first.=" (".$nickname.")";
   $dob=$year."-".$month."-".$day;
   if(IsTooOldM($dob,$semesters))
   {
      $eligible="n"; $eligible_comment="Older than 15 years";
   }
   $eligible_comment=trim(addslashes($eligible_comment));
   $sql="UPDATE middleeligibility SET last='$last', first='$first', middle='$middle', gender='$gender', dob='$dob', semesters='$semesters', eligible='$eligible', eligible_comment='$eligible_comment' WHERE id='$id'";
   $result=mysql_query($sql);
}

//get array of schools
$sql="SELECT school FROM middleschools ORDER BY school";
$result=mysql_query($sql);
$ix=0;
$schools=array();
while($row=mysql_fetch_array($result))
{
   $schools[$ix]=$row[0];
   $ix++;
}

//get student info from db
$sql="SELECT * FROM middleeligibility WHERE id='$student_id'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$school_attending=$row[school];
$last=$row[last];
$first_nick=$row[first];
if(ereg("\(",$first_nick))	//GET NICKNAME
{
   $first_nick=split("\(",$first_nick);
   $first=$first_nick[0];
   $nickname=substr($first_nick[1],0,strlen($first_nick[1])-1);
}
else
{
   $first=$row[first];
   $nickname="";
}
$middle=$row[middle];
$gender=$row[gender];
$dob=$row[dob];
$semesters=$row[semesters];
$eligible=$row[eligible];
$eligible_comment=$row[eligible_comment];

echo $init_html_ajax."</head><body>";
echo GetHeader($session);
?>
<script language="javascript">
function ErrorCheck()
{
   var errors="";
   if(Utilities.getElement('first').value=='' || Utilities.getElement('last').value=='')
   {
      errors+="<li>You must enter the student's first and last name.</li>";
      Utilities.getElement('nameerror').style.display='block';
   }
   else Utilities.getElement('nameerror').style.display='none';
   if(Utilities.getElement('gender').options.selectedIndex==0)
   {
      errors+="<li>You must enter the student's gender.</li>";
      Utilities.getElement('gendererror').style.display='block';
   }
   else Utilities.getElement('gendererror').style.display='none';
   if(Utilities.getElement('month').options.selectedIndex==0 || Utilities.getElement('day').options.selectedIndex==0 || Utilities.getElement('year').options.selectedIndex==0)
   {
      errors+="<li>You must enter the student's date of birth.</li>";
      Utilities.getElement('doberror').style.display='block';
   }
   else Utilities.getElement('doberror').style.display='none';
   if(Utilities.getElement('semesters').options.selectedIndex==0)
   {
      errors+="<li>You must enter the student's semester.</li>";
      Utilities.getElement('semerror').style.display='block';
   }
   else Utilities.getElement('semerror').style.display='none';
   if(errors=="")
   {
      Utilities.getElement('hiddensave').value="submit";
      document.forms.infoform.submit();
   }
   else
   {
      Utilities.getElement('errorBox').style.display="block";
      Utilities.getElement('errorBox').innerHTML="<div class=error>ERROR:</div><ul>"+ errors +"</ul><img src='../../okbutton.png' onclick=\"Utilities.getElement('errorBox').style.display='none';\">";
   }
}
</script>
<br><a href="eligibility.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>&letter=<?php echo $letter; ?>">Return to Eligibility List</a><br><br>
<form method="post" action="edit_student.php" name="infoform">
<input type=hidden name=session value="<?php echo $session; ?>">
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<input type=hidden name=letter value="<?php echo $letter; ?>">
<table><caption><b>Student Information Form</b><br>(Fields marked with a * are required)<hr>
<?php
if($save || $hiddensave)
{
   echo "<div class='alert' style='width:400px;'>Any changes you made have been saved.</div>";
}
?>
</caption>
<tr align=left><td colspan=2><input type=submit name="delete" onClick="return confirm('Are you sure you want to DELETE this student from the database?');" value="Delete Student"></td></tr>
<tr align=left>
<th align=left><div class="errormark" id="nameerror">!</div>Name*:</th>
<td><input type=text id="last" name="last" value="<?php echo $last; ?>" size=15>
    , <input type=text id="first" name="first" value="<?php echo $first; ?>" size=10>
    &nbsp;<input type=text id="middle" name="middle" value="<?php echo $middle; ?>" size=2>
    </td>
</tr>
<tr align=left>
<th>&nbsp;</th>
<th align=left>(&nbsp;Nickname:&nbsp;<input type=text id="nickname" name=nickname value="<?php echo $nickname; ?>" size=10>&nbsp;)</th>
</tr>
<?php
if($level==1)	//Only NSAA can change school the student attends
{
?>
   <tr align=left>
   <th align=left>School*:</th>
   <td><select id=\"school_attending\" name=\"school_attending\">
   <?php
   for($i=0;$i<count($schools);$i++)
   {
      echo "<option";
      if($school_attending==$schools[$i]) echo " selected";
      echo ">$schools[$i]";
   }
   ?>
       </select>
   </td>
   </tr>
<?php
}
echo "<tr align=left><th><div class=\"errormark\" id=\"gendererror\">!</div>Gender*:</th><td><select id=\"gender\" name=\"gender\"><option value=''>~</option><option value='M'";
if($gender=="M") echo " selected";
echo ">M</option><option value='F'";
if($gender=="F") echo " selected";
echo ">F</option></select></td></tr>";
$dob=split("-",$dob);
echo "<tr align=left><th><div class=\"errormark\" id=\"doberror\">!</div>Date of Birth*</th><td><select id=\"month\" name=\"month\"><option value='00'>MM</option>";
   for($m=1;$m<12;$m++)
   {
      if($m<10) $show="0".$m;
      else $show=$m;
      echo "<option value=\"$show\"";
      if($show==$dob[1]) echo " selected";
      echo ">$show</option>";
   }
   echo "</select>/<select id=\"day\" name=\"day\"><option value='00'>DD</option>";
   for($d=1;$d<31;$d++)
   {
      if($d<10) $show="0".$d;
      else $show=$d;
      echo "<option value=\"$show\"";
      if($show==$dob[2]) echo " selected";
      echo ">$show</option>";
   }
   echo "</select>/<select id=\"year\" name=\"year\"><option value='0000'>YYYY</option>";
   $year1=date("Y")-20; $year2=date("Y")-10;
   for($y=$year1;$y<$year2;$y++)
   {
      echo "<option value=\"$y\"";
      if($y==$dob[0]) echo " selected";
      echo ">$y</option>";
   }
   echo "</select>";
echo "</td></tr>";
echo "<tr align=center><td colspan=2><div id=\"errorBox\" class=\"errbox\" style=\"display:none;\"></div></td></tr>";
echo "<tr align=left valign=top><th><div class=\"errormark\" id=\"semerror\">!</div>Semester*:</th><td><select id=\"semesters\" name=\"semesters\"><option value=''>~</option>";
for($i=1;$i<=4;$i++)
{
   echo "<option value=\"$i\"";
   if($semesters==$i) echo " selected";
   echo ">$i</option>";
}
echo "</select><br><i>(1 = 1st sem 7th grade, 2 = 2nd sem 7th grade, 3 = 1st sem 8th grade, 4 = 2nd sem 8th grade)</i></td></tr>";
echo "<tr align=left>
<th align=left>Eligible:</th>
<td><input type=checkbox id=\"eligible\" name=\"eligible\"";
if($eligible=="y") echo " checked";
echo " value=\"y\">
     <input type=text id=\"eligible_comment\" name=\"eligible_comment\" size=35 value=\"$eligible_comment\"></td></tr>";
echo "<input type=hidden id=\"id\" name=\"id\" value=\"$student_id\">
<tr align=center><td colspan=2><br><input type=button name=\"save\" value=\"Save Changes\" onClick=\"ErrorCheck();\">
   <input type=hidden name=\"hiddensave\" id=\"hiddensave\">
   <input type=submit name=\"cancel\" value=\"Cancel\"></td></tr>
</table>
</form>";
echo $end_html;
?>
