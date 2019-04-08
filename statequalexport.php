<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

$schooltbl=GetSchoolTable($activitych);
$sql="SELECT t1.class,t2.* FROM $schooltbl AS t1,headers AS t2 WHERE t1.mainsch=t2.id ORDER BY t1.class,t2.school";
//$sql="SELECT * FROM headers ORDER BY school";
$result=mysql_query($sql);
$csv="\"School\",\"Class\",\"Contact\",\"Address 1\",\"Address 2\",\"City\",\"State\",\"Zip\",\"AD E-mail\"\r\n";
while($row=mysql_fetch_array($result))
{
   if(IsRegistered($row[school],$activitych))
   {
      $field="state".$activitych;
      if($row[$field]=='x') 
      {
     	 $csv.="\"$row[school]\",\"$row[class]\",";
	 $school2=addslashes($row[school]);
         $sql2="SELECT name FROM logins WHERE school='$school2' AND maincontact='y'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
         $temp=split(",",$row[city_state]);
         $temp[0]=trim($temp[0]); $temp[1]=trim($temp[1]);
	 $csv.="\"$row2[name]\",\"$row[address1]\",\"$row[address2]\",\"$temp[0]\",\"$temp[1]\",\"$row[zip]\",";
         $sql2="SELECT email FROM logins WHERE school='$school2' AND level='2'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $csv.="\"$row2[email]\"\r\n";
      }
   }
}
$open=fopen(citgf_fopen("/home/nsaahome/reports/statequalexport.csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/statequalexport.csv");
header("Location:exports.php?session=$session&filename=statequalexport.csv");
exit();
?>
