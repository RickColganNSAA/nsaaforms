<?php
if($save=="Go to Next Section" || $jump==1 || $savehome=="Home")
{
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
<form method=post action="batest_footer.php" name="footer_form">
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=jump>
<table width=100% cellspacing=0 cellpadding=0 height=100%>
<tr align=left>
<td>
&nbsp;&nbsp;<font style="color:blue"><b>
If necessary, make sure to SCROLL DOWN and answer ALL of the questions above on the screen.</b></font><br>
&nbsp;&nbsp;<input type=submit name=save value="Go to Next Section">&nbsp;&nbsp;&nbsp;<b>OR</b><br>
<?php
//get number answered for each section and total answered
$sql="SELECT * FROM batest_results WHERE offid='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$totalanswered=0;
$answered=array(); $possible=array();
$sql2="SELECT * FROM batest_categ";
$result2=mysql_query($sql2);
$i=1;
while($row2=mysql_fetch_array($result2))
{
   $answered[$i]=0; $possible=array();
   $i++;
}
$sql2="SELECT * FROM batest ORDER BY place";
$result2=mysql_query($sql2);
$ix=0; $curcategid=0;
while($row2=mysql_fetch_array($result2))
{
   if($row2[category]!=$curcategid)
   {
      $curcategid=$row2[category];  $ix++;
   }
   $index="ques".$row2[place];
   if($row[$index]=='t' || $row[$index]=='f')
   {
      $totalanswered++;
      $answered[$ix]++;
   }
   $possible[$ix]++;
}
echo "&nbsp;&nbsp;<select class=small name=categid onchange='jump.value=\"1\";submit();'><option>Jump To...";
//get category list from db
$sql="SELECT id,category,place FROM batest_categ ORDER BY place";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\">$row[category] (".$answered[$row[place]]." of ".$possible[$row[place]]." answered)";
}
echo "<option>Finish Test";
echo "</select>";
echo "</td>";
echo "<td><input type=submit name=savehome value=\"Home\">";
//echo "<td><a onClick=\"top.location.replace('welcome.php?session=$session');\" href='#'>Home</a></td>";
echo "</form></td>
</tr>
</table>

</center>
</body>
</html>";
?>
