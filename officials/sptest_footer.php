<?php
if($save=="Go to Next Section" || $jump==1 || $savehome=="Home" || $save=="Save & Finish Test")
{
   if($save=="Save & Finish Test")
      $categid="Finish Test";
?>
<script language="javascript">
parent.main.document.test_form.forcecategid.value="<?php echo $categid; ?>";
parent.main.document.test_form.home.value="<?php echo $savehome; ?>";
parent.main.document.test_form.givenoffid.value="<?php echo $givenoffid; ?>";
parent.main.document.test_form.submit();
</script>
<?php
exit();
}
?>

<html>
<head>
<link href="../../css/nsaaforms.css" rel="stylesheet" type="text/css">
<body>
<center>
<b>

<?php
require 'variables.php';
require 'functions.php';

//validate user
if(!ValidUser($session))
{
   header("Location:jindex.php");
   exit();
}

$offid=GetJudgeID($session);
$level=GetLevelJ($session);
if($level==1)
   $offid=$givenoffid;

?>
<form method=post action="sptest_footer.php" name="footer_form">
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=test value=<?php echo $session; ?>>
<input type=hidden name=givenoffid value=<?php echo $givenoffid; ?>>
<input type=hidden name=jump>
<table width=100% cellspacing=0 cellpadding=0 height=100%>
<tr align=left>
<td>
<?php
if($test=="combo")
{
?>
&nbsp;&nbsp;<font style="color:blue"><b>
If necessary, make sure to SCROLL DOWN and answer ALL of the questions above on the screen.</b></font><br>
&nbsp;&nbsp;<input type=submit name=save value="Go to Next Section">&nbsp;&nbsp;&nbsp;<b>OR</b><br>
<?php
//get number answered for each section and total answered
$sql="SELECT * FROM sptest_results WHERE offid='$offid'";
$result=mysql_query($sql);
$answered=0;
$counts=array();
for($i=1;$i<=2;$i++)
{
   $counts[$i]=0;
}
$row=mysql_fetch_array($result);
for($i=1;$i<=100;$i++)
{
   $index="ques".$i;
   if($row[$index]=='t' || $row[$index]=='f')
   {
      $answered++;
      if($i<=50) $counts[1]++;
      else $counts[2]++;
  }
}

echo "&nbsp;&nbsp;<select class=small name=categid onchange='jump.value=\"1\";submit();'><option>Jump To...";
//get category list from db
$sql="SELECT id,category,place FROM sptest_categ ORDER BY place";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if($row[place]==1)
      $questotal=50;
   else
      $questotal=10;
   echo "<option value=\"$row[id]\">$row[category] (".$counts[$row[place]]." of $questotal answered)";
}
echo "<option>Finish Test";
echo "</select>";
}
else if($test=="speech" || $test=="play")	//only show Finish Test button
{
   echo "&nbsp;&nbsp;<font style=\"color:blue\"><b>
   If necessary, make sure to SCROLL DOWN and answer ALL of the questions above on the screen.</b></font><br>";
   echo "<input type=submit name=save value=\"Save & Finish Test\">";
}
echo "</td>";
echo "<td><input type=submit name=savehome value=\"Home\">";
echo "</form></td>
</tr>";
if($test=="speech")
   echo "<tr align=center><td colspan=2><font style=\"font-size:8pt;\"><b>NOTE: Make sure to SCROLL DOWN to see and answer all 50 questions on the Speech portion of this test.</font></td></tr>";
echo "</table>

</center>
</body>
</html>";
?>
