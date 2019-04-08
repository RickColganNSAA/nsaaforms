<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

function GetMeetName($meetid,$site='0')
{
   $sql="SELECT * FROM swsched WHERE id='$meetid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $meetname="";
   if($row[meetname]!='')
      $meetname=$row[meetname];
   else if($row[opponent]!='')
      $meetname=$row[opponent];
   else if($row[oppid]>0)
   {
      $sql2="SELECT school FROM swschool WHERE sid='$row[oppid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $sql3="SELECT school FROM swschool WHERE sid='$row[sid]'";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      $meetname="$row3[0] VS $row2[0]";
   }
   if($row[site]!='' && $site==1)
      $meetname.=" @ $row[site]";
   if(trim($meetname)=="") 
   {
      $meetname="[Please Select Meet]";
      if($meetid!=0) $meetname.=" Meet ID: $meetid";
   }
   return $meetname;
}
function GetMeetDate($meetid)
{
   $sql="SELECT meetdate FROM swsched WHERE id='$meetid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $date=split("-",$row[0]);
   $meetdate="$date[1]/$date[2]";
   if($row[0]=="" || $row[0]=='0000-00-00')
      $meetdate="-";
   return $meetdate;
}

?>
