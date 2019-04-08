<?php

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

//FOOTBALL SCHEDULES
if($level!=1 && ereg("2010fbschedule.txt",$filename))
{
   //Make sure they are downloading THEIR football schedule, not someone else's
   $sid=GetSID($session,'fb');
   $school=GetSchoolName($sid,'fb',date("Y"));
   $filename=ereg_replace("[^a-zA-Z]","",$school)."2010fbschedule.txt";
}

$temp=split("[.]",$filename);
if($temp[1]!="html")
{
header("Content-type: text/css");
header("Content-Disposition: attachment; filename=".urlencode($filename)."");
readfile(getbucketurl("/home/nsaahome/reports/".$filename.""));
}
else
{
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"r");
//$data=fread($open,citgf_filesize("/home/nsaahome/reports/$filename"));
$data=stream_get_contents($open);
$data=ereg_replace("\r\n","<br>",$data);
echo $data;
}

?>
