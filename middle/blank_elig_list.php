<?php
/*********************************
blank_elig_list.php
Add students manually to middle school list of students
copied from ../blank_elig_list.php 12/26/09
Author: Ann Gaffigan
**********************************/

require '../functions.php';
require '../variables.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
$level=GetLevel($session);
if($level==8) $school_ch=GetSchool($session);

echo $init_html;
echo GetHeader($session);
?>
<form method="post" action="addto_elig.php">
<input type=hidden name=session value="<?php echo $session; ?>">
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<br>
<table cellspacing=0 cellpadding=4 frame=all rules=all style="border:#a0a0a0 1px solid;">
<caption><b>Enter Students for <?php echo $school_ch; ?>:</b><br>All fields are required.</caption>
<tr align=center>
<th>Name<br>(last, first(alias), MI)</th>
<th title=Gender>Gender<br>(M/F)</th>
<th title="Date of Birth">DOB<br>(mm-dd-yyyy)</th>
<th title="Semesters of Attendance">Semesters<br>(1-4)</th>
</tr>

<?php
for($i=0;$i<10;$i++)
{
   echo "<tr align=center";
   if($i%2==0) echo " bgcolor='#F0F0F0'";
   echo ">";
   echo "<td>";
   echo "<input type=text name=\"last[$i]\" value=\"[Last]\" onFocus=\"this.value='';\" size=25>";
   echo ",&nbsp;<input type=text name=\"first[$i]\" value=\"[First]\" onFocus=\"this.value='';\" size=15>&nbsp;";
   echo "<input type=text name=\"middle[$i]\" size=2></td>";
   echo "<td><select name=\"gender[$i]\"><option value=''>~</option><option value='M'>M</option><option value='F'>F</option></select></td>";
   echo "<td><select name=\"month[$i]\"><option value='00'>MM</option>";
   for($m=1;$m<12;$m++)
   {
      if($m<10) $show="0".$m;
      else $show=$m;
      echo "<option value=\"$show\">$show</option>";
   }
   echo "</select>/<select name=\"day[$i]\"><option value='00'>DD</option>";
   for($d=1;$d<31;$d++)
   {
      if($d<10) $show="0".$d;
      else $show=$d;
      echo "<option value=\"$show\">$show</option>";
   }
   echo "</select>/<select name=\"year[$i]\"><option value='0000'>YYYY</option>";
   $year1=date("Y")-20; $year2=date("Y")-10;
   for($y=$year1;$y<$year2;$y++)
   {
      echo "<option value=\"$y\">$y</option>";
   }
   echo "</select>";
   echo "</td>";
   echo "<td><input type=text name=\"semesters[$i]\" size=2>";
   echo "</tr>";
}
?>

</table>
<br><br>
<input type=submit name=submit value="Save and Add More">
<input type=submit name=submit value="Save and View List">
<input type=submit name=submit value="Cancel">
</form>
<?php echo $end_html; ?>
