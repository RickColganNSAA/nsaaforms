<?php

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

$sport="sog";
$school="Omaha Westside";
   $sport=ereg_replace("_","",$sport);  
   $schtbl=$sport."school";
   $school2=addslashes($school);
   $sql="SELECT id FROM $db_name.headers WHERE school='$school2'";
   $result=mysql_query($sql);
echo mysql_error();
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)==0)
   {
      $sql="SELECT mainsch FROM $db_name.$schtbl WHERE school='$school2'";
      $result=mysql_query($sql);
echo mysql_error();
      $row=mysql_fetch_array($result);
      echo $row[0];
   }
   else
   {
   $id=$row[0];
   $table=$sport."school";
   $sql="SELECT sid FROM $db_name.$table WHERE (mainsch='$id' OR othersch1='$id' OR othersch2='$id' OR othersch3='$id')";
   $result=mysql_query($sql);
echo mysql_error();
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)>0)
      echo  $row[0];
   else echo "NO SID FOUND";
   }
echo GetSID2($school,$sport);
?>
