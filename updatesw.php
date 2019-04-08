<?php
//updatesw.php: take $file, upload it to $db_name_tm, and update sw_verify_perf

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

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

//connect to $db_name_tm
mysql_close();
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("nsaaswimming_tm",$db);

//read uploaded .sql file
$open=fopen(citgf_fopen("sw/hytek/$file"),"r");
//$data=fread($open,citgf_filesize("sw/hytek/$file"));
$data=stream_get_contents($open);
fclose($open);

foreach(explode(";","$data") as $sql_line)
{
   if(!ereg("DATABASE",$sql_line))
   {
      $result=mysql_query($sql_line);
   }
}

echo $init_html;
echo $header;
echo "<center><br><br>";
echo "The existing database has been updated.<br><br>";
echo "Please ";
//echo "<a href=\"filtersw.php?session=$session\">Click Here</a>";
echo "<a href=\"execfiltersw.php?session=$session\">Click Here</a>";
echo " to filter the results so that only Automatic and Secondary qualifiers are shown on the Season Best lists.";
echo $end_html;
exit();
//header("Location:filtersw.php?session=$session");
?>
