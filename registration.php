<?php
//registration.php: allows NSAA to enter information
//	about which schools have registered for
//	which activities

require 'functions.php';
require 'variables.php';

$level=GetLevel($session);

//validate user
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php");
   exit();
}

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

//get array of schools
$sql="SELECT school FROM headers ORDER BY school";
$result=mysql_query($sql);
$schools=array();
$i=0;
while($row=mysql_fetch_array($result))
{
   $schools[$i]=$row[0];
   $i++;
}

echo $init_html;
$header=GetHeader($session);
echo $header."<br>";

//get array of schools
$sql="SELECT school FROM headers ORDER BY school";
$result=mysql_query($sql);
$schools=array();
$i=0;
while($row=mysql_fetch_array($result))
{
   $schools[$i]=$row[0];
   $i++;
}
?>
<br>
<form method=post action="registration.php">
<input type=hidden name=session value=<?php echo $session; ?>>
<table class=nine>
<caption><b>Registration Information</b><br>
   <font size=2>Please choose a school:</font>
<select name=school onchange="submit();">
       <option>Generate Report</option>
<?php
for($i=0;$i<count($schools);$i++)
{
   echo "<option";
   if($schools[$i]==$school) echo " selected";
   echo ">$schools[$i]";
}
?>
    </select>
    <input type=submit name=go value="Go">
    <br><br>
</caption>
<?php
if($school)	//a school has been chosen
{
   $school2=ereg_replace("\'","\'",$school);
   //if user has clicked "Save"
   if($save=="Save")
   {
     //enter updated info into database table registration
     $sql="SELECT * FROM registration WHERE school='$school2'";
     $result=mysql_query($sql);
     if(mysql_num_rows($result)==0)
     {
	$sql="INSERT INTO registration (school) VALUES ('$school2')";
	$result=mysql_query($sql);
     }
     for($i=0;$i<count($act_regi);$i++)
     {
        $var=ereg_replace(" ","_",$act_regi[$i]);
        $value=$$var;
	if($all=='y')	//check all as 'x'
	{
	   $value='x';
	}
	$sql="UPDATE registration SET $var='$value' WHERE school='$school2'";
        $result=mysql_query($sql);
     }
   }
   if($school=="Generate Report")	//user wants to get a report
   {
      //generate totals
      for($i=0;$i<count($act_regi);$i++)
      {
	 $sql="SELECT * FROM registration WHERE $act_regi[$i]='x' OR $act_regi[$i]='w'";
	 $result=mysql_query($sql);
	 $ct=mysql_num_rows($result);
	 $totals[$i]=$ct;
      }
      echo "<tr align=center><td>";
      echo "<table bordercolor=#000000 frame=lhs border=1 cellspacing=0 cellpadding=2>";
      echo "<tr align=center><th></th>";
      for($i=0;$i<count($act_regi);$i++)
      {
	 $temp=ereg_replace("_"," ",$act_regi[$i]);
	 $abbrev=GetActivityAbbrev2($temp);
	 $abbrev=strtoupper($abbrev);
	 echo "<th class=smaller>$abbrev</th>";
      }
      echo "</tr>";
      echo "<tr align=left><th>Totals:</th>";
      for($i=0;$i<count($act_regi);$i++)
      {
	 $temp=$totals[$i];
	 echo "<td>$temp</td>";
      }
      echo "</tr>";
      for($i=0;$i<count($schools);$i++)
      {
	 $temp=addslashes($schools[$i]);
	 $sql="SELECT * FROM registration WHERE school='$temp'";
	 $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 echo "<tr align=left>";
	 echo "<td>$schools[$i]</td>";
	 $actct=count($act_regi);
	 for($j=2;$j<$actct+2;$j++)
	 {
	     echo "<td width=10>$row[$j]</td>";
	 }
	 echo "</tr>";
      }
      echo "<tr align=left><td>Totals:</td>";
      for($i=0;$i<count($act_regi);$i++)
      {
         $temp=$totals[$i];
         echo "<td>$temp</td>";
      }
      echo "</tr>";
   }
   else
   {
   echo "<tr align=center><td>";
   echo "<table>";
   echo "<tr align=left><td>";
   echo "<input type=checkbox name=all value=y>Check All as Registered<br>";
   echo "</td></tr>";
   for($i=0;$i<count($act_regi);$i++)
   {
      $cur_value=ereg_replace(" ","_",$act_regi[$i]);
      $sql="SELECT $cur_value FROM registration WHERE school='$school2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if($i%3==0)
      {
	 echo "<tr align=left>";
      }
      echo "<td><input type=text name=\"$act_regi[$i]\" size=1";
      echo " value=$row[0]>"; 
      echo "&nbsp;$act_regi2[$i]</td>";
      if(($i+1)%3==0)
      {
	 echo "</tr>";
      }
   }
   echo "</table></td></tr>";
   echo "<tr align=center><td>";
   echo "<input type=submit name=save value=Save>";
   }//end else (if not "Generate Report")
}
?>

</table>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
