<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
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

$sql="SELECT DISTINCT school, class_dist FROM $form ORDER BY class_dist,school";
$result=mysql_query($sql);
$genderbig=strtoupper($gender);
if($genderbig=="GIRLS") $schooltbl="ccgschool";
else $schooltbl="ccbschool";
$ix=0; $csv="CLASS,SCHOOL,$genderbig COACH\r\n";
while($row=mysql_fetch_array($result))
{
   $sch2=ereg_replace("\'","\'",$row[0]);
   $sql2="SELECT t1.id,t2.name FROM headers AS t1, logins AS t2 WHERE t1.school=t2.school AND t1.school='$sch2' AND t2.sport='$gender Cross-Country'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $mainsch=$row2[id]; $coach=$row2[name];
   $sql2="SELECT * FROM $schooltbl WHERE (mainsch='$mainsch' OR othersch1='$mainsch' OR othersch2='$mainsch' OR othersch3='$mainsch')";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $cursch=$row2[school];
   $csv.=$row[1].",$cursch,$coach";
   $csv.="\r\n";
}
$open=fopen(citgf_fopen("cc/coaches.csv"),"w");
if(!fwrite($open,$csv)) echo "COULD NOT WRITE";
fclose($open); 
 citgf_makepublic("cc/coaches.csv");
echo $init_html;
echo "<table width=100%><tr align=center><td><br><br>";
echo "<b>$genderbig Coaches:</b><br><br>";
echo "<a href=\"cc/coaches.csv\">Click Here to Download \"coaches.csv\" (for Excel)</a>";
echo $end_html;
?>
