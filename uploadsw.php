<?php
//uploadsw.php: NSAA uploads .sql file that was converted from a Team Mgr file
//	The .sql file appends the $db_name_tm database

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

   $filename="swimming.sql";
   $now=time();
   $newfilename="swimming$now.sql";
   citgf_copy("sw/hytek/$filename","sw/hytel/$newfilename");
   $open=fopen(citgf_fopen("sw/hytek/$filename"),"r");
   $line=file(getbucketurl("sw/hytek/$filename"));
   fclose($open);
   $open=fopen(citgf_fopen("sw/hytek/$filename"),"w");
   for($i=1;$i<(count($line)-1);$i++)
   {
      fwrite($open,$line[$i]);
   }
   fclose($open); 
 citgf_makepublic("sw/hytek/$filename");

   echo $init_html;
   echo $header;
   echo "<center><br><br>";
   //echo "<a href=\"sw/hytek/$filename\" target=new>$filename</a><br><br>";
   echo "The new SQL database file has been successfully uploaded.<br><br>";
   echo "Please <a href=\"updatesw.php?session=$session&file=$filename\">Click Here</a> to update the existing database.";
   echo $end_html;
   //header("Location:updatesw.php?session=$session&file=$newfilename");
   exit();
?>
