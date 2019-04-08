<?php
/***************************************
eligexport.php
Export school's list of students to .csv
Copied 12/29/09 from ../eligexport.php
Author: Ann Gaffigan
****************************************/
require '../functions.php';
require '../variables.php';

if(!$dbname || $dbname=="")
   $dbname=$db_name;

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

$level=GetLevel($session);

if(!ValidUser($session) || ($level!=1 && $level!=8))
{
   header("Location:index.php?error=1");
   exit();
}
$school=GetSchool($session);
$school2=addslashes($school);

   $sql="SELECT * FROM $dbname.middleeligibility WHERE school='$school2' ORDER BY last,first,middle";
   $result=mysql_query($sql);
   $csv="\"First\",\"Middle\",\"Last\",\"Gender\",\"DOB\",\"Semester\",\"Eligible\"\r\n";
   while($row=mysql_fetch_array($result))
   {
      $csv.="\"$row[first]\",\"$row[middle]\",\"$row[last]\",\"$row[gender]\",\"$row[dob]\",\"$row[semesters]\",\"$row[eligible]\",\"$row[foreignx]\",\"$row[fb68]\",\"$row[fb11]\",\"$row[vb]\",\"$row[sb]\",\"$row[cc]\",\"$row[te]\",\"$row[bb]\",\"$row[wr]\",\"$row[sw]\",\"$row[go]\",\"$row[tr]\",\"$row[ba]\",\"$row[so]\",\"$row[ch]\",\"$row[sp]\",\"$row[pp]\",\"$row[de]\",\"$row[im]\",\"$row[vm]\",\"$row[jo]\"\r\n";
   }
   $sch=ereg_replace("[^a-zA-Z]","",$school);
   $sch=strtolower($sch);
   $filename=$sch."eligibility.csv";
   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename");
   header("Content-type: text/css");
   header("Content-Disposition: attachment; filename=".urlencode($filename)."");
   readfile(getbucketurl("/home/nsaahome/attachments/".$filename.""));
   exit();
?>
