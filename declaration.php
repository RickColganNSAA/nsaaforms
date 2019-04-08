<?php
//declaration.php: allows schools to declare participation in fall activities

require 'functions.php';
require 'variables.php';

$level=GetLevel($session);

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

echo $init_html;
$header=GetHeader($session);
echo $header;

//get due date of form
$sql="SELECT duedate FROM misc_duedates WHERE sport='declaration'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$row[0]);
$curyear=$date[0];
$curyear1=$curyear+1;
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
?>
<br>
<form method=post action="declaration.php">
<input type=hidden name=session value=<?php echo $session; ?>>
<table width=500>
<caption><b>Declaration of Participation in <?php echo $curyear; ?> Fall Activities:<br><font style="font-size:9pt">Due June 1, <?php echo $date[0]; ?></font></b><hr>
<?php

//array of fall activities:
$fallact=array("fb11","vb","fb8","go_g","fb6","te_b","cc_b","sb","cc_g","pp");
$fallactlong=array("Football 11-Man","Volleyball","Football 8-Man","Girls Golf","Football 6-Man","Boys Tennis","Boys Cross-Country","Softball","Girls Cross-Country","Play Production");

   //if user has clicked "Save"
   if($submit=="Save")
   {
     //enter updated info into database table declaration
     $sql="SELECT * FROM declaration WHERE school='$school2'";
     $result=mysql_query($sql);
     if(mysql_num_rows($result)==0)	//INSERT
     {
	$sql2="INSERT INTO declaration (school,fb11,fb8,fb6,vb,cc_b,cc_g,go_g,te_b,sb,pp) VALUES ('$school2','$check[fb11]','$check[fb8]','$check[fb6]','$check[vb]','$check[cc_b]','$check[cc_g]','$check[go_g]','$check[te_b]','$check[sb]','$check[pp]')";
     }
     else				//UPDATE
     {
	$sql2="UPDATE declaration SET fb11='$check[fb11]',fb8='$check[fb8]',fb6='$check[fb6]',vb='$check[vb]',cc_b='$check[cc_b]',cc_g='$check[cc_g]',go_g='$check[go_g]',te_b='$check[te_b]',sb='$check[sb]',pp='$check[pp]' WHERE school='$school2'";
     }
     $result2=mysql_query($sql2);
   }

   echo "<tr align=left><td>This form is used by the NSAA staff to prepare for fall activities for the $curyear-$curyear1 school year.  Schools must complete this form by the due date shown above.<br><br><b>THIS IS NOT THE REGISTRATION FORM.<br></b>You can complete the Registration Form and pay the registration fees when you complete the Registration Form in the NSAA's Official Registration Form, which can be downloaded from our website after July 27, $curyear.<br><br><b>Check where appropriate.  Leave blank if your school will not participate in that activity.</b></td></tr>";
   //Get info from database
   $sql="SELECT * FROM declaration WHERE school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);

   echo "<tr align=center><td>";
   echo "<table>";
   for($i=0;$i<count($fallact);$i++)
   {
      $cur_value=$fallact[$i];
      if($i%2==0)
      {
	 echo "<tr align=left>";
      }
      echo "<td><input type=checkbox name=\"check[$cur_value]\" value='y'";
      if($row[$cur_value]=='y') echo " checked";
      echo ">&nbsp;$fallactlong[$i]</td>";
      if(($i+1)%2==0)
      {
	 echo "</tr>";
      }
   }
   echo "</table></td></tr>";
   echo "<tr align=center><td>";
   echo "<input type=submit name=submit value=Save>";
?>

</table>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
