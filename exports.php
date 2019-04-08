<?php

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

if($loginas=="jojudge")
{
   if(!ValidJOJudge($session))
   {
      header("Location:index.php?error=1");
      exit();
   }
}
else if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
$level=GetLevel($session);

/*** FOOTBALL SCHEDULES ***/
$temp=explode(";",GetFBYears());
$year1=$temp[0]; $year2=$temp[1];
$DATESrelease=GetFBDate("showschedules_date");       //SHOW SCHEDULES (9am)
if($level!=1 && preg_match("/".$year1."fbschedule/",$filename))
{
   //Make sure it is past DATESrelease at 9:00AM
   $temp=explode("-",$DATESrelease);
   $opentime=mktime(9,0,0,$temp[1],$temp[2],$temp[0]);
   if(time()<$opentime)	//NOT TIME YET
   {
      echo $init_html."<table width='100%'><tr align=center><td><br><div style=\"width:500px;text-align:left;\"><p>It is ".date("g:ia T")." on ".date("F j, Y").". It is not time for the schedules to be downloaded yet. Please check back after ".date("g:ia T",$opentime)." on ".date("F j, Y",$opentime).".</p><p>It is not in your best interest to click refresh before ".date("g:ia T",$opentime)." on ".date("F j, Y",$opentime).". Your browser may save this page in its cache and not give you a fresh page containing your $year1-$year2 Football Schedules when they are available.</p><p>Please be patient and wait until ".date("g:ia T",$opentime).".</p><p><a href=\"javascript:window.close();\">Close Window</a></p></div>".$end_html;
      exit();
   }
   //Make sure they are downloading THEIR football schedule, not someone else's
   if($filename!=$year1."fbschedules.txt")
   {
      $sid=GetSID($session,'fb',$year1);
      $school=GetSchoolName($sid,'fb',$year1);
      $filename=preg_replace("/[^a-zA-Z]/","",$school).$year1."fbschedule.txt";
   }
}

$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));  
if($ext!="html")
{
   if($ext=="xls")
   {
      header("Content-type: application/vnd.ms-excel");
      header("Content-Disposition: attachment; filename=".urlencode($filename)."");
      header("Pragma: no-cache");
      header("Expires: 0");
   }
   else
   {
      header("Content-type: text/css");
      header("Content-Disposition: attachment; filename=".urlencode($filename)."");
   }
   
   readfile(getbucketurl("/home/nsaahome/reports/".$filename.""));
}
else
{
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"r");
//$data=fread($open,citgf_filesize("/home/nsaahome/reports/$filename"));
$data=stream_get_contents($open);
$data=str_replace(array("\r","\n"),"<br>",$data);
echo $data;
}

?>
