<?php
//forms_query.php: Allows user to do advanced query on forms, such
//	as only pull ones that have not been submitted, etc.

require 'functions.php';
require 'variables.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

//get level of access using session id
$sql="SELECT t2.level FROM sessions AS t1, logins AS t2 WHERE t1.session_id='$session' AND t1.login_id=t2.id"; 
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$level=$row[0];

$header=GetHeader($session);
?>

<html>
<head>
   <title>NSAA Home</title>
   <link href="../css/nsaaforms.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
echo $header;
?>
<br>
<font size=2><b>Advanced Search: Entry Forms</b><br>
<i>Please indicate your search criteria below</font><br></i>
<form method="post" action="forms_query_submit.php">
<input type=hidden name=session value="<?php echo $session; ?>">
<table cellspacing=0 cellpadding=3 width=50%>
<?php
if($level==1)	//NSAA Access
{
   //get list of all schools
   $sql="SELECT school FROM headers ORDER BY school";
   $result=mysql_query($sql);
   $i=0;
   $schools=array();
   while($row=mysql_fetch_array($result))
   {
      $schools[$i]=$row[0];
      $i++;
   }
?>
<tr align=left bgcolor=#D0D0D0><th>Schools:</th>
<td>
<select name=school_array[] MULTIPLE size=4>
   <option selected>All Schools
<?php
   for($i=0;$i<count($schools);$i++)
   {
      echo "<option>$schools[$i]";
   }
?>
</select>
<br><font size=1>Hold down CTRL(PC) or Apple(Mac) to make multiple selections</font>
</td>
</tr>
<?php
}
else	//AD Access
{
   $school=GetSchool($session);
   echo "<tr align=left bgcolor=#D0D0D0><th>School:</th><td>$school</td></tr>";
}
?>
<tr align=left><th>Activities:</th>
<td>
<select name=activity_ch>
   <option selected>All Activities
<?php
   $music=0; $football=0; $tennis=0;	//SPECIAL CIRCUMSTANCES FOR THESE ACTIVITIES
   for($i=0;$i<count($act_long);$i++)
   {
      if(ereg("Music",$act_long[$i]) && $music=='0')
      {
         $music=1;
	 echo "<option>Music</option>";
      }
      else if(ereg("Football",$act_long[$i]) && $football=='0')
      {
         $football=1;
         echo "<option>Football</option>";
      }
      else if(ereg("Tennis",$act_long[$i]) && $tennis=='0')
      {
	 //SHOW GIRLS & BOYS TENNIS, CLASS A AND B SEPARATELY
         $tennis=1;
         echo "<option>Boys Tennis, Class A</option><option>Boys Tennis, Class B</option>";
	 echo "<option>Girls Tennis, Class A</option><option>Girls Tennis, Class B</option>";
      }
      else if(!ereg("Music",$act_long[$i]) && !ereg("Football",$act_long[$i]) && !ereg("Tennis",$act_long[$i]))
         echo "<option>$act_long[$i]</option>";
   }
?>
</select>
<!--<br><font size=1>Hold down CTRL(PC) or Apple(Mac) to make multiple selections</font>-->
<br>
</td></tr>
<tr bgcolor=#D0D0D0 align=center>
<td colspan=2>
   <table>
   <tr align=left>
   <td><input type=radio name=type value=all checked></td>
   <td><b>Forms for All Registered Activities</b></td>
   </tr>
   <tr align=left>
   <td><input type=radio name=type value=unedited></td>
   <td><b>Unedited Forms Only</b></td>
   </tr>
   <tr align=left>
   <td><input type=radio name=type value=edited></td>
   <td><b>Edited Forms with
      <select name=limit1>
	 <option>Any
	 <option value=2><2
	 <option value=3><3
	 <option value=4><4
	 <option value=5><5
	 <option value=6><6
	 <option value=7><7
	 <option value=8><8
	 <option value=9><9
	 <option value=10><10
	 <option value=11>&#8805;10
      </select>
      entries</b>
   </td>
   </tr>
   <tr align=left>
   <td><input type=radio name=type value=both></td>
   <td><b>Unedited Forms AND Forms with
      <select name=limit2>
	 <option>Any
	 <option value=2><2
	 <option value=3><3
	 <option value=4><4
	 <option value=5><5
	 <option value=6><6
	 <option value=7><7
	 <option value=8><8
	 <option value=9><9
	 <option value=10><10
	 <option value=11>&#8805;10
      </select>
      entries</b>
   </td>
   </tr>
   </table>
</td>
</tr>
<tr align=center>
<td colspan=2><br>
   <input type=submit name=submit value="Submit">
   &nbsp;
   <input type=submit name=submit value="Cancel">
</td>
</tr>
</table>
</form>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
