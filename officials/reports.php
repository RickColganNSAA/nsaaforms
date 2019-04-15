<?php
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php';
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   if(preg_match("/(officialsroster)/",$filename))
   {
      $sql="USE $db_name";
      $result=mysql_query($sql);
      if(!ValidUser($session))
      {
         header("Location:../index.php?error=1");
         exit();
      }
      $sql="USE $db_name2";
      $result=mysql_query($sql);
   }
   else
   {
      header("Location:index.php?error=1");
      exit();
   }
}
$level=GetLevel($session);
$temp=split("[.]",$filename);

//FOOTBALL SCHEDULES
$temp=explode(";",GetFBYears());
$year1=$temp[0]; $year2=$temp[1];
$DATESrelease=GetFBDate("showschedules_date");       //SHOW SCHEDULES (9am)
if($level!=1 && preg_match("/".$year1."fbschedule/",$filename))
{
   //Make sure it is past DATESrelease at 9:00AM
   $temp=explode("-",$DATESrelease);
   $opentime=mktime(9,0,0,$temp[1],$temp[2],$temp[0]);
   if(time()<$opentime) //NOT TIME YET
   {
      echo $init_html."<table width='100%'><tr align=center><td><br><div style=\"width:500px;text-align:left;\"><p>It is ".date("g:ia T")." on ".date("F j, Y").". It is not time for the schedules to be downloaded yet. Please check back after 9:00am CST on ".date("F j, Y",$opentime).".</p><p>It is not in your best interest to click refresh before 9:00am CST. Your browser may save this page in its cache and not give you a fresh page containing your $year1-$year2 Football Schedules when they are available.</p><p>Please be patient until 9:00am CST.</p><p><a href=\"javascript:window.close();\">Close Window</a></p>".$end_html;
      exit();
   }
}

if($bypass!=1 && substr($temp[0],0,6)=="roster" && $temp[1]=="csv")	//Officials Roster Export
{
   //CleanupFiles();	//get rid of old rosters
   $temp2=split("roster",$temp[0]);
   $outfile="output/rosteroutput".$temp2[1].".html";
   if(!$open=fopen(citgf_fopen($outfile),"r")) echo "Could not open $outfile<br>";
   //$data=fread($open,citgf_filesize($outfile));
   $data=stream_get_contents($open);
   if(preg_match("/ERROR/",$data))
   {
      echo "<html><head><meta http-equiv=\"Refresh\" content=\"5\">";
      echo "<title>Nebraska School Activities Association</title><link href=\"/css/nsaaforms.css\" rel=stylesheet type=\"text/css\">";
      echo "</head><body><center><br><br>";
      echo "<table width=400><tr align=center><td><div class='error'>There were some errors creating this export.</div>".$data;
      echo "</td></tr></table></body></html>";
   }
   else if(!preg_match("/DONE/",$data))
   {
      echo "<html><head><meta http-equiv=\"Refresh\" content=\"5\">";
      echo "<title>Nebraska School Activities Association</title><link href=\"/css/nsaaforms.css\" rel=stylesheet type=\"text/css\">";
      echo "</head><body><center><br><br>";
      echo "<table width=400><tr align=center><td>";
      echo "<b>Officials Export in Progress...Please be patient...<br>";
      echo "<img src=\"../pleasewait.gif\">";
      echo "</td></tr></table></body></html>";
   }
   else
   {
      echo "<html><head>";
      echo "<title>Nebraska School Activities Association</title><link href=\"/css/nsaaforms.css\" rel=stylesheet type=\"text/css\">";
      echo "</head><body><center><br><br>";
      echo "<table width=400><tr align=center><td>";
      echo "<b>Export Complete!<br><br>";
      echo "<a href=\"reports.php?session=$session&bypass=1&filename=$filename\">Download Export</a>";
      echo "</td></tr></table></body></html>";
   }
}
else if($temp[1]!="html")
{
header("Content-type: text/css");
header("Content-Disposition: attachment; filename=".urlencode($filename)."");
//readfile(getbucketurl("/home/nsaahome/reports/".$filename.""));
}
else
{
$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"r");
$data=fread($open,citgf_filesize("/home/nsaahome/reports/$filename"));
//$data=stream_get_contents($open);
$data=str_replace(array("\r","\n"),"<br>",$data);
echo $data;
}
?>
