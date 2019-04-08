<?php
/****************************************
elig_query.php
Advanced Search Tool for eligibility list
Copied 12/29/09 from ../elig_query.php
Author: Ann Gaffigan
*****************************************/

require '../variables.php';
require '../functions.php';

$level=GetLevel($session);

//validate user
if(!ValidUser($session) || ($level!=1 && $level!=8))
{
   header("Location:index.php?error=1");
   exit();
}

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

echo $init_html;
echo GetHeader($session);
?>

<form method="post" action="eligibility.php">
<br><font size=2>
<i>Please indicate your search criteria below:</i></font><br><br>
<input type=hidden name=session value="<?php echo $session; ?>">
<table cellspacing=0 cellpadding=5>
<tr bgcolor="#E0E0E0" align=left>
<th align=left>Students attending:</th>
<td>
<font size=2>

<?php
//If user is Level 1(NSAA), allow them to choose school(s)
//If user is Level 2 or 3, school is non-editable:
$level=GetLevel($session);
if($level==1)	//Level 1 (NSAA)
{
?>
   <select name=school_array[] multiple size=4>
      <option selected>All Schools
      <?php
      //Get list of schools
      $sql="SELECT school FROM middleschools ORDER BY school";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
	 echo "<option>$row[0]</option>";
      }
      ?>
   </select>
   <br><font size=1>Hold down CTRL(PC) or Apple(Mac) to make multiple selections</font>
<?php
}
else	//School is already known
{
   $school=GetSchool($session);
   echo $school;
}
?>
</td>
</tr>
<tr align=center><td colspan=2>-AND-</td></tr>
<tr bgcolor='#E0E0E0' align=left>
<th align=left>of the Gender:</th>
<td>
   <select name=gender>
      <option>Any</option>
      <option value="M">Male Only</option>
      <option value="F">Female Only</option>
   </select>
</td>
</tr>
<tr align=center><td colspan=2>-AND-</td></tr>
<tr bgcolor="#E0E0E0" align=left>
<th align=left>currently in Grade:</th>
<td>
   <select name=grade>
      <option>Any</option>
      <option>7</option>
      <option>8</option>
   </select>
</td>
</tr>
<tr align=center><td colspan=2>-AND-</td></tr>
<tr bgcolor="#E0E0E0" align=left>
<th colspan=2>
   <p><input type=radio name="eligible" value="y">Is ELIGIBLE&nbsp;&nbsp;<input type=radio name="eligible" value="n">Is INELIGIBLE</p>
   <p><input type=radio name="physical" value="y">Physical Exam DONE&nbsp;&nbsp;<input type=radio name="physical" value="n">Physical Exam NOT DONE</p>
   <p><input type=radio name="parent" value="y">Parent Consent Form ON FILE&nbsp;&nbsp;<input type=radio name="parent" value="n">Parent Consent Form NOT ON FILE</p>
</td>
</tr>
<tr align=center>
<td colspan=2><br>
<input type=submit name="submit" value="Search">
<input type=submit name="submit" value="Cancel">
</td>
</tr>
</table>
</form>
<?php echo $end_html; ?>
