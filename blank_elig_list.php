<html>
<head>
   <title>NSAA Home</title>
   <link href="../css/nsaaforms.css" rel="stylesheet" type="text/css">
</head>

<body style="margin: 0in 0in;">
<?php
require 'functions.php';
require 'variables.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

$header=GetEligHeader($session,1);
echo $header;
?>
<form method="post" action="addto_elig.php">
<input type=hidden name=session value="<?php echo $session; ?>">
<input type=hidden name=activity_ch value="<?php echo $activity_ch; ?>">
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<tr align=center>
<th class=small width=25%>Name<br>(last, first(alias), MI)</th>
<th class=small width=4% title=Gender>M/F</th>
<th class=small width=7% title="Date of Birth">DOB<br>(mm-dd-yyyy)</th>
<th class=small width=4% title="Semesters of Attendance">Sem</th>
<th class=small width=2% title="Eligible">E</th>
<!--<th class=small width=2% title="Transfer">T</th>-->
<th class=small width=2% title="International Transfer">iT</th>
<!--<th class=small width=2% title="Enrollment Option">New<br>EO</th>-->
<th width=2% class=small title="Football 6/8">FB<br>6/8</th>
<th width=2% class=small title="Football 11">FB<br>11</th>
<th width=2% class=small title="Volleyball">VB</th>
<th width=2% class=small title="Softball">SB</th>
<th width=2% class=small title="Cross-Country">CC</th>
<th width=2% class=small title="Tennis">TE</th>
<th width=2% class=small title="Basketball">BB</th>
<th width=2% class=small title="Wrestling">WR</th>
<th width=2% class=small title="Swimming">SW</th>
<th width=2% class=small title="Golf">GO</th>
<th width=2% class=small title="Track & Field">TR</th>
<th width=2% class=small title="Baseball">BA</th>
<th width=2% class=small title="Soccer">SO</th>
<th width=2% class=small title="Cheerleading/Spirit">CH</th>
<th width=2% class=small title="Speech">SP</th>
<th width=2% class=small title="Play Production">PP</th>
<th width=2% class=small title="Debate">DE</th>
<th width=2% class=small title="Instrumental Music">IM</th>
<th width=2% class=small title="Vocal Music">VM</th>
<th width=2% class=small title="Journalism">JO</th>
<th width=2% class=small title="Unified Bowling">UBO</th>
</tr>

<?php
echo "<tr align=left><td colspan=27>";
echo "You are entering new students for <b>$school_ch</b>:";
echo "</td></tr>";
for($i=0;$i<10;$i++)
{
   echo "<tr align=center";
   if($i%2==0) echo " bgcolor=#D0D0D0";
   echo ">";
   echo "<td width=25%>";
   echo "<input type=text class=tiny name=last[$i] size=15>";
   echo ",&nbsp;<input type=text class=tiny name=first[$i] size=10>&nbsp;";
   echo "<input type=text class=tiny name=middle[$i] size=2></td>";
   echo "<td width=4%><input type=text class=tiny name=gender[$i] size=2></td>";
   echo "<td width=7%><input type=text class=tiny name=dob[$i] size=11></td>";
   echo "<td width=4%><input type=text class=tiny name=semesters[$i] size=2>";
   echo "<td width=2%><input type=checkbox value=\"y\" name=eligible[$i] checked></td>";
   //echo "<td width=2%><input type=checkbox value=\"y\" name=transfer[$i]></td>";
   echo "<td width=2%><input type=checkbox value=\"y\" name=foreignx[$i]></td>";
   //echo "<td width=2%><input type=checkbox value=\"y\" name=enroll_option[$i]></td>";
   for($j=0;$j<count($activity);$j++)
   {
      echo "<td width=2%><input type=checkbox value=\"x\" name=$activity[$j][$i]></td>";
   }
   echo "</tr>";
}
?>

</table>
<center><br><br>
<input type=submit name=submit value="Save and Add More">
<input type=submit name=submit value="Save and View List">
<input type=submit name=submit value="Cancel">
</form>

</body>
</html>



