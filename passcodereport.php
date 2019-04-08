<?php
//passcodereport.php: generate passcodes for all schools
// in format that is friendly with Access

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

$level=GetLevel($session);
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php");
   exit();
}

$sql="SELECT school FROM headers ORDER BY school";
$result=mysql_query($sql);
$ix=0;
$schools=array();
while($row=mysql_fetch_array($result))
{
   $schools[$ix]=$row[0];
   $ix++;
}

//open file to write to
$open=fopen(citgf_fopen("/home/nsaahome/reports/passcodes.csv"),"w");

$string=",AD,Baseball,Boys Basketball,Boys Cross-Country,Boys Golf,Boys Soccer,Boys Swimming,Boys Tennis, Boys Track & Field, Cheerleading/Spirit, Debate, Football 11, Football 6/8, Girls Basketball, Girls Cross-Country, Girls Golf, Girls Soccer, Girls Swimming, Girls Tennis, Girls Track & Field, Journalism, Music, Play Production, Softball, Speech, Volleyball, Wrestling";

fwrite($open,$string);
fwrite($open,"\n");
for($i=0;$i<count($schools);$i++)
{
   $schools[$i]=ereg_replace("\'","\'",$schools[$i]);
   $sql="SELECT * FROM logins WHERE school='$schools[$i]' ORDER BY sport";
   $result=mysql_query($sql);
   $string="$schools[$i],";
   while($row=mysql_fetch_array($result))
   {
      $string.="$row[6],";
   }
   $string=substr($string,0,strlen($string)-1);
   $string.="\n";
   fwrite($open, $string);
}

fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/passcodes.csv");

$filename="passcodes.csv";
/*
$fd=fopen(citgf_fopen($filename),"r");
$content=fread($fd,citgf_filesize($filename));
fclose($fd); 
 citgf_makepublic($filename);
header("Content-Type: application/download\n");
header("Content-Disposition: attachment; filename=$filename");
echo $content;
*/
header("Location:exports.php?session=$session&filename=$filename");
exit();

exit();
