<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!ValidUser($session))
{
   //check if officials LEVEL 1 only
   if(ValidUser($session,"$db_name2"))
   {
      $sql="SELECT t2.level FROM $db_name2.sessions AS t1, $db_name2.logins_j AS t2 WHERE t1.session_id='$session' AND t1.login_id=t2.id";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $level=$row[0];
      if($level!=1)
      {
         header("Location:../index.php?error=1");
         exit();
      }
   }
   else
   {
      header("Location:../index.php?error=1");
      exit();
   }
}

$temp=split("[.]",$filename);
if($temp[1]!="html")	// force download

{
header("Content-type: text/css");
header("Content-Disposition: attachment; filename=".urlencode($filename)."");
readfile(getbucketurl("/home/nsaahome/attachments/".$filename.""));
}
else 
{
$open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename"),"r");
//$data=fread($open,citgf_filesize("/home/nsaahome/attachments/$filename"));
$data=stream_get_contents($open);
$data=ereg_replace("\r\n","<br>",$data);
echo $data;
}
?>
