<?php
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
   echo "<center><br><br>";
   $open=fopen(citgf_fopen("uploadsw2_output.html"),"r");
   //$data=fread($open,citgf_filesize("uploadsw2_output.html"));
   $data=stream_get_contents($open);
   echo $data;
   echo $end_html;
   //header("Location:updatesw.php?session=$session&file=$newfilename");
   exit();
?>
