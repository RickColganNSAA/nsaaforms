<?php
//NSAA Admin View for Class D CC Survey (cc/cc_survey.php)

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
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
echo $header;

//show 3 different lists, for boys and girls each
echo "<center><br><br>";
echo "<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
echo "<caption><b>Class D Cross-Country Survey Results:</b></caption>";
echo "<tr align=center><th class=smaller colspan=2>Register AND Full Team</th><th class=smaller colspan=2>Register AND Not Full Team</th><th class=smaller colspan=2>Not Registering</th></tr>";
echo "<tr align=center><th class=smaller>Boys</th><th class=smaller>Girls</th><th class=smaller>Boys</th><th class=smaller>Girls</th><th class=smaller>Boys</th><th class=smaller>Girls</th></tr>";
echo "<tr valign=top align=left>";
   //#1: Registered AND Full Team:
//$sql="SELECT school FROM cc_classd WHERE reg_b='y' AND full_b='y' ORDER BY school";
$sql="SELECT school FROM cc_classd WHERE full_b='y' ORDER BY school";
$result=mysql_query($sql);
echo "<td>";
while($row=mysql_fetch_array($result))
{
   echo $row[0]."<br>";
}
echo "</td><td>";
$sql="SELECT school FROM cc_classd WHERE reg_g='y' AND full_g='y' ORDEr BY school";
$sql="SELECT school FROM cc_classd WHERE  full_g='y' ORDEr BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo $row[0]."<br>";
}
echo "</td><td>";
   //#2: Registered AND Not Full Team:
$sql="SELECT school FROM cc_classd WHERE reg_b='y' AND full_b='n' ORDER BY school";
$sql="SELECT school FROM cc_classd WHERE  full_b='n' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo $row[0]."<br>";
}
echo "</td><td>";
$sql="SELECT school FROM cc_classd WHERE reg_g='y' AND full_g='n' ORDER BY school";
$sql="SELECT school FROM cc_classd WHERE  full_g='n' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo $row[0]."<br>";
}
echo "</td><td>";
   //#3: Not Registering
$sql="SELECT school FROM cc_classd WHERE reg_b='n' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo $row[0]."<br>";
}
echo "</td><td>";
$sql="SELECT school FROM cc_classd WHERE reg_g='n' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo $row[0]."<br>";
}
echo "</td></tr></table><br>";

//show schools who have not finished their survey:
echo "<table width=400 border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
echo "<caption align=left><b>BOYS Class D Schools that have NOT completed the Class D Cross-Country Survey:</b></caption>";
echo "<tr align=center><th width=\"50%\" class=smaller>Partially<br>Completed</th><th width=\"50%\" class=smaller>No Questions<br>Completed</th></tr>";
$sql="SELECT * FROM ccbschool WHERE class='D' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT school FROM headers WHERE id='$row[mainsch]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $school=addslashes($row2[school]);
   $sql3="SELECT * FROM cc_classd WHERE school='$school'";
   $result3=mysql_query($sql3);
   $row3=mysql_fetch_array($result3);
   //if(mysql_num_rows($result3)>0 && ($row3[reg_b]=='' || $row3[reg_g]=='' || $row3[full_b]=='' || $row3[full_g]==''))	//PARTIAL
   if(mysql_num_rows($result3)>0 && ( $row3[full_b]=='' || $row3[full_g]==''))	//PARTIAL
      echo "<tr align=center><td>$row2[school]</td><td>&nbsp;</td></tr>";
   //else if(mysql_num_rows($result3)==0 || ($row3[reg_b]=='' && $row3[reg_g]=='' && $row3[full_b]=='' && $row3[full_g]==''))
   else if(mysql_num_rows( $row3[full_b]=='' && $row3[full_g]==''))
     echo "<tr align=center><td>&nbsp;</td><td>$row2[school]</td></tr>";
}
echo "</table>";
echo "<br><table width=400 border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
echo "<caption align=left><b>GIRLS Class D Schools that have NOT completed the Class D Cross-Country Survey:</b></caption>";
echo "<tr align=center><th width=\"50%\" class=smaller>Partially<br>Completed</th><th width=\"50%\" class=smaller>No Questions<br>Completed</th></tr>";
$sql="SELECT * FROM ccgschool WHERE class='D' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT school FROM headers WHERE id='$row[mainsch]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $school=addslashes($row2[school]);
   $sql3="SELECT * FROM cc_classd WHERE school='$school'";
   $result3=mysql_query($sql3);
   $row3=mysql_fetch_array($result3);
   //if(mysql_num_rows($result3)>0 && ($row3[reg_b]=='' || $row3[reg_g]=='' || $row3[full_b]=='' || $row3[full_g]==''))   //PARTIAL
   if(mysql_num_rows($result3)>0 && ( $row3[full_b]=='' || $row3[full_g]==''))   //PARTIAL
      echo "<tr align=center><td>$row2[school]</td><td>&nbsp;</td></tr>";
   //else if(mysql_num_rows($result3)==0 || ($row3[reg_b]=='' && $row3[reg_g]=='' && $row3[full_b]=='' && $row3[full_g]==''))
   else if(mysql_num_rows($result3)==0 || ( $row3[full_b]=='' && $row3[full_g]==''))
     echo "<tr align=center><td>&nbsp;</td><td>$row2[school]</td></tr>";
}
echo "</table>";
echo "<br><a href=\"welcome.php?session=$session\">Home</a>";

echo $end_html;
?>
