<?php
//elig_query.php: Advanced Search Tool for eligibility list

require 'variables.php';
require 'functions.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

?>

<html>
<head>
   <title>NSAA Home</title>
   <link href="../css/nsaaforms.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php
$header=GetHeader($session);
echo $header; 
?>

<form method="post" action="eligibility.php">
<br><font size=2>
<i>Please indicate your search criteria below:</i></font><br><br>
<input type=hidden name=session value=<?php echo $session; ?>>
<table cellspacing=0 cellpadding=5>
<tr bgcolor=#D0D0D0 align=left>
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
      $sql="SELECT school FROM headers ORDER BY school";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
	 echo "<option>$row[0]";
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
<tr bgcolor=#D0D0D0 align=left>
<th align=left>of the Gender:</th>
<td>
   <select name=gender>
      <option>All
      <option value="M">Male Only
      <option value="F">Female Only
   </select>
</td>
</tr>
<tr align=center><td colspan=2>-AND-</td></tr>
<tr bgcolor=#D0D0D0 align=left>
<th align=left>currently in Grade:</th>
<td>
   <select name=grade>
      <option>All
      <option><9
      <option>9
      <option>10
      <option>11
      <option>12
   </select>
</td>
</tr>
<tr align=center><td colspan=2>-AND-</td></tr>
<tr bgcolor=#D0D0D0 align=center>
<td colspan=2>
   <font size=3>(</font>
   <!--
   <input type=checkbox name=transfer value="y"><b>Transfer</b>
   &nbsp;-OR-&nbsp;
   -->
   <input type=checkbox name=ineligible value="y"><b>Ineligible</b>
   &nbsp;-OR-&nbsp;
   <input type=checkbox name=foreign_x value="y"><b>International Transfer</b>
   <!--
   &nbsp;-OR-&nbsp;
   <input type=checkbox name=enroll_option value="y"><b>Enrollment Option</b>
   -->
   <font size=3>)</font>
</th>
</tr>
<tr align=center><td colspan=2>-AND-</td></tr>
<tr bgcolor=#D0D0D0 valign=top align=left>
<th align=left>Participating in the Activities:</th>
<td>
   <select name=activity_array[] multiple size=8>
      <option selected>All Activities
      <option>Sports Only
      <option>Non-Athletic Only
   <?php
   //get list of activity names
   for($i=0;$i<count($act_long);$i++)
   {
      echo "<option>$act_long[$i]";
   }
   ?>
   </select>
   <br><font size=1>Hold down CTRL(PC) or Apple(Mac) to make multiple selections</font>
</td>
</tr>
<tr align=center>
<td colspan=2><br>
<input type=submit name=submit value="Search">
<input type=submit name=submit value="Cancel">
</td>
</tr>
</table>
</form>
</center>

</td>
</tr>
</table>
</body>
</html>
