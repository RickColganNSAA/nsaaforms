<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
$temp=split("[.]",$filename);
/*
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
*/

if($bypass!=1 && substr($temp[0],0,6)=="roster" && $temp[1]=="csv")	//Officials Roster Export
{
   //CleanupFiles();	//get rid of old rosters
   $temp2=split("roster",$temp[0]);
   $outfile="output/rosteroutput".$temp2[1].".html";
   $open=fopen(citgf_fopen($outfile),"r");
   //$data=fread($open,citgf_filesize($outfile));
   $data=stream_get_contents($open);
   if(!ereg("DONE!",$data))
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
