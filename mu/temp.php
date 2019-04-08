<?php

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);
/*
$sql="SELECT * FROM mubigdistricts WHERE school!='' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $school=addslashes($row[school]);
   $school=ereg_replace(" High School","",$school);
   if(ereg("/",$school))
   {
      $schs=split("/",$school);
      $sql2="SELECT * FROM headers WHERE (school='$schs[0]' OR school='$schs[1]')";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)<2)
         echo "$row[school] not found\r\n";
      else
      {
	 $num=1;
	 while($row2=mysql_fetch_array($result2))
	 {
	    $field="schoolid".$num;
	    $sql3="UPDATE mubigdistricts SET $field='$row2[id]' WHERE id='$row[id]'";
	    //$result3=mysql_query($sql3);
	    //echo "$sql3\r\n";
	    $num++;
	 } 
      }
   }
   else
   {
   $sql2="SELECT * FROM headers WHERE school='$school'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
      echo "$row[school] not found\r\n";
   else
   {
      $row2=mysql_fetch_array($result2);
      $sql3="UPDATE mubigdistricts SET schoolid='$row2[id]' WHERE id='$row[id]'";
      $result3=mysql_query($sql3);
      echo "$sql3\r\n";
   }
   }
}
*/
?>
