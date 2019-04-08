<?php
/************************************************************
import_students.php: 
Gives intsructions on how to set up the
file to be imported and provides file field w/ browsing
capabilities.
Copied from ../import_students.php 12/28/09
Author: Ann Gaffigan
************************************************************/

require '../functions.php';
require '../variables.php';

$level=GetLevel($session);

//validate user
if(!ValidUser($session) || ($level!=8 && $level!=1))
{
   header("Location:index.php?error=1");
   exit();
}

$school=GetSchool($session);
$school2=ereg_replace("\'","\'",$school);

echo $init_html;
echo GetHeader($session);
?>
<form enctype="multipart/form-data" method="post" action="import_student_file.php">
<input type=hidden name="session" value="<?php echo $session; ?>" />
<input type=hidden name="MAX_FILE_SIZE" value="1000000" />
<?php
//input school as hidden unless user is NSAA/Level 1:
if($school!="All")
   echo "<input type=hidden name=school value=\"$school\">";
?>
<br>
<table width=80%>
<tr align=center>
<th>Import Student File to Eligibility Database</th>
</tr>
<tr><td><hr></td></tr>
<tr align=left><th>Instructions:</th></tr>
<tr align=left><td>
<p><font style="color:red"><b>PLEASE NOTE: If you have just downloaded your export of last year's 7th-Graders, please skip these instructions and go to the bottom of this screen to select your file (downloaded to your Desktop) and "Import" your students.<br><br>
If you need to import more students into your database and you need help creating the file, please FOLLOW THE INSTRUCTIONS BELOW CAREFULLY:</b></font></p>
<p>The file to be imported should include the following fields of information, in this order, and delimited by commas: <i><b>last name, first name (alias), middle initial, gender, date of birth, </i></b>and <i><b>semesters of attendance</b></i>.  Each line should hold a different student's information, as shown in the following example:</p>
<table width=80%>
<tr align=left><td>
&nbsp;Smith&nbsp;,&nbsp;John&nbsp;,&nbsp;T&nbsp;,&nbsp;M&nbsp;,&nbsp;10-18-<?php echo (date("Y")-14);?>&nbsp;,&nbsp;1&nbsp;<br>
&nbsp;Johnson&nbsp;,&nbsp;Kimberly (Jane)&nbsp;,&nbsp;J&nbsp;,&nbsp;F&nbsp;,&nbsp;05-03-<?php echo (date("Y")-14);?>&nbsp;,&nbsp;1&nbsp;<br>
&nbsp;Hanson&nbsp;,&nbsp;Troy&nbsp;,&nbsp;&nbsp;,&nbsp;M&nbsp;,&nbsp;11-12-<?php echo (date("Y")-15);?>&nbsp;,&nbsp;3&nbsp;
</td></tr>
</table> 
</center>
<br>
<p>One way to create such a file is to use <b>Microsoft Excel</b>.  Just put the last names in one column, the first names in the second column, etc.  Then save the file as a <b>"CSV"</b> or <b>"Comma-Delimited" file ("CSV for Windows" on a Mac)</b>, and import it below.</p>
<p>Note the following format specifications for each of the fields:<br>
<ul>
   <li><b>First & Last Name</b>: first letter capitalized
   <li><b>Middle Initial</b>: one capital letter
   <li><b>Gender</b>: capital M or F
   <li><b>Date of Birth</b>: MM-DD-YYYY 
   <li><b>Semesters</b>: 1-4, where 1=1st semester 7th grade, 3=1st semester 8th grade, etc.
</ul>
</p>
<br>
<b><font size=2>NOTES</font></b>:  
<ul>
<li>
   <font color=#FF0000><b>Do not use quotes</b></font> within any of the fields; you may use parentheses instead.  For example, if you wanted to include an <font color=#FF000><b>alias</b></font> or <font color=#FF0000><b>nickname</b></font> along with a student's real first name, you may put the alias or nickname in parentheses <font color=#FF000><b>( )</b></font> after his or her first name in the first-name field, like:  Michelangelo (Mike) .<br><br>
</li>
<li>
   The <font color=#FF0000><b>middle initial field is optional</b></font>.  If you do not include a middle initial for a student/students, however, you must still leave a <font color=#FF0000><b>blank field</b></font> in its place, as in the 3rd example above.<br><br>
</li>
<li>
   Double-check that your file includes all <font color=#FF0000><b>6 fields for each student</b></font> listed.<br><br></li>
<li>
   DO NOT IMPORT A FILE THAT IS IN ALL CAPITAL LETTERS!!!<br><br>
</li>
<li>
   Please make sure the <font style="color:#FF0000"><b>last names are in the 1st column</b></font> and the <font style="color:#FF0000"><b>first names are in the 2nd column</b></font>
</li>
</ul>
</td>
</tr>
<tr align=center>
<th>Please indicate the file to be imported below and click "Import":</th>
</tr>
<tr align=left><th>
<font style="color:red">PLEASE NOTE: The file you are importing will be ADDED to your current list of students in the Eligibility Database; it will NOT replace it.  PLEASE do not upload a file containing students you already have imported.  View your current list of students on your <a href="eligibility.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>&activity_ch=All&last=a">Eligibility Page</a>.</font></th>
</tr>
<tr align=center>
<td>
   <?php
   //if user is NSAA/Level 1, have them choose which school:
   if($school=="All")
   {
      //get array of schools from headers table in db
      $sql="SELECT school FROM headers ORDER BY school";
      $result=mysql_query($sql);
      $ix=0;
      $schools=array();
      while($row=mysql_fetch_array($result))
      {
	 $schools[$ix]=$row[0];
	 $ix++;
      }
   ?>
      <select name="school">
	 <option>Choose a High School</option>
   <?php
      for($i=0;$i<count($schools);$i++)
      {
	 echo "<option";
	 if($school_ch==$schools[$i]) echo " selected";
	 echo ">$schools[$i]</option>";
      }
   ?>
      </select>
   <?php
   }
   echo "<div style=\"width:400px\" class=alert><B><u>MAC USERS:</u></b> Make sure you have saved your file as a <b>\"CSV (Windows)\"</b>, not a <b>\"CSV (Comma-Delimited)\"</b> file.  Otherwise, the file will not import properly.</div><br>";
   echo "&nbsp;<input type=\"file\" name=\"import_file\"></td>";
?>
</tr>
<tr align=center>
<td><input type=submit name="submit" value="Import"></td>
</tr>
<tr align=center>
<td><br><a href="eligibility.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>&last=a">Return to Eligibility Page</a></td>
</tr>
</table>
</form> 
</center>
<?php echo $end_html; ?>
