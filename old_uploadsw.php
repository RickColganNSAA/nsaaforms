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

if($submit)
{
   $filename=$_FILES["sqlfile"]["name"];
   $now=time();
   $filename=split("[.]",$filename);
   $name=ereg_replace(" ","",$filename[0]);
   $name.=$now;
   $newfilename=$name.".".$filename[1];
   if(!citgf_copy($sqlfile,"sw/hytek/$newfilename")) echo "No copy made: $sqlfile";
   $open=fopen(citgf_fopen("sw/hytek/$newfilename"),"r");
   $line=file(getbucketurl("sw/hytek/$newfilename"));
   fclose($open);
   $open=fopen(citgf_fopen("sw/hytek/$newfilename"),"w");
   for($i=1;$i<(count($line)-1);$i++)
   {
      fwrite($open,$line[$i]);
   }
   fclose($open); 
 citgf_makepublic("sw/hytek/$newfilename");

   echo $init_html;
   echo "<center><br><br>";
   echo "The new SQL database file has been successfully uploaded.<br><br>";
   echo "Please <a href=\"updatesw.php?session=$session&file=$newfilename\">Click Here</a> to update the existing database.";
   echo $end_html;
   //header("Location:updatesw.php?session=$session&file=$newfilename");
   exit();
}

echo $init_html;
echo $header;

echo "<br><br>";
echo "<form method=post action=\"uploadsw.php\" enctype=\"multipart/form-data\">";
echo "<input type=hidden name=session value=$session>";
echo "<center>";
echo "<table><caption><b>Upload Swimming Team Manager Database in .SQL Format:</b><hr></caption>";
echo "<tr align=center><td><input type=file name=sqlfile></td></tr>";
echo "<tr align=center><td><input type=submit name=submit value=\"Upload\"></td></tr>";
echo "</table>";
echo "</form>";

echo $end_html;
?>
