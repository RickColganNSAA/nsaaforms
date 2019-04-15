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

$sportname=GetSportName($sport);
$districts=$sport."districts";

$csv="\"Type\",\"Class\",\"District\",\"Host School\",\"Address1\",\"Address2\",\"City\",\"State\",\"Zip\",\"Site\",\"Director\",\"E-mail\",\"Dates\",\"Schools\"\r\n";
$types=array("District","Subdistrict","District Final","Substate");
for($t=0;$t<count($types);$t++)
{
   $type=$types[$t];
$sql="SELECT * FROM $districts WHERE type='$type' ORDER BY type, class, district";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $temp=split("/",$row[dates]);
   $dates="";
   for($i=0;$i<count($temp);$i++)
   {
      $curday=split("-",$temp[$i]);
      $curday2=mktime(0,0,0,$curday[1],$curday[2],$curday[0]);
      $dates.=date("M j",$curday2).", ";
   }
   $dates.=date("Y",$curday2);
   if($row[dates]=='') $dates="";
   $hostid=$row[hostid];
   $sql2="SELECT t1.*,t2.level FROM $db_name.headers AS t1,$db_name.logins AS t2 WHERE t1.school=t2.school AND t2.id='$hostid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if(mysql_num_rows($result2)==0)	//college host
   {
      $sql2="SELECT * FROM $db_name.logins WHERE id='$hostid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $college=addslashes($row2[school]);
      $sql2="SELECT * FROM $db_name.logins WHERE school='$college' ORDER BY address1 DESC";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
   }
   $temp=split(",",$row2[city_state]);
   $city=trim($temp[0]); $state=trim($temp[1]);
   $csv.="\"$row[type]\",\"$row[class]\",\"$row[district]\",\"$row[hostschool]\",\"$row2[address1]\",\"$row2[address2]\",\"$city\",\"$state\",\"$row2[zip]\",\"$row[site]\",\"$row[director]\",\"$row[email]\",\"$dates\",\"$row[schools]\"\r\n";
}
}//end for each type

$filename=$sport."hostexport.csv";
if(!$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w")) 
{
   echo "Could not open $filename"; exit();
}
if(!fwrite($open,$csv))
{
   echo "Could not write $filename"; exit();
}
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
header("Location:reports.php?session=$session&filename=$filename");
exit();
?>
