<?php
if($save=="Save & Keep Working" || $save=="Finish Test" || $savehome=="Home")
{
   if($save=="Save & Keep Working") $categid=1;
   else $categid="Finish Test";
?>
<script language="javascript">
parent.main.document.test_form.forcecategid.value="<?php echo $categid; ?>";
parent.main.document.test_form.home.value="<?php echo $savehome; ?>";
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
   header("Location:index.php");
   exit();
}
if(GetLevel($session)==1)
{
   if($givenoffid) $offid=$givenoffid;
   else
      exit();
}
else
   $offid=GetOffID($session);

?>
<form method=post action="ditest_footer.php" name="footer_form">
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=jump>
<table width=100% cellspacing=0 cellpadding=0 height=100%>
<tr align=left>
<td>
&nbsp;&nbsp;<font style="color:blue"><b>
If necessary, make sure to SCROLL DOWN and answer ALL of the questions above on the screen.</b></font><br>
&nbsp;&nbsp;<input type=submit name=save value="Save & Keep Working">
&nbsp;&nbsp;<input type=submit name=save value="Finish Test">
<?php
//get number answered for each section and total answered
$sql="SELECT * FROM ditest_results WHERE offid='$offid'";
$result=mysql_query($sql);
$answered=0;
$row=mysql_fetch_array($result);
for($i=1;$i<=16;$i++)
{
   $index="ques".$i;
   if($row[$index]=='t' || $row[$index]=='f')
   {
      $answered++;
  }
}
/*
echo "&nbsp;&nbsp;<select class=small name=categid onchange='jump.value=\"1\";submit();'><option>Jump To...";
//get category list from db
$sql="SELECT category,place FROM ditest_categ ORDER BY place";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $questotal=16;
   echo "<option value=\"$row[place]\">$row[category] (".$counts[$row[place]]." of $questotal answered)";
}
echo "<option>Finish Test";
echo "</select>";
*/
echo "</td>";
echo "<td align=right><input type=submit name=savehome value=\"Home\">";
//echo "<td><a onClick=\"top.location.replace('welcome.php?session=$session');\" href='#'>Home</a></td>";
echo "</form></td>
</tr>
</table>

</center>
</body>
</html>";
?>
