<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';

//connect to db:
mysql_close();
$db=mysql_connect("$db_host","$db_user","$db_pass");
if(!mysql_select_db("$db_name2", $db)) echo "NO DB";
$sql="SELECT * FROM wrofforig";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT id FROM officials WHERE socsec='$row[offid]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $curid=$row2[0];

   if(mysql_num_rows($result2)==0)
   {
      echo "$row[offid]<br>";
      $curid=$row[offid];
   }
   for($i=1;$i<=7;$i++)
   {
      $ix=4+(7*($i-1));
      $regyr=$row[$ix];
      $fall=substr($regyr,0,2);
      $spring=substr($regyr,2,2);
      if($fall>10) $fall="19".$fall;
      else $fall="20".$fall;
      if($spring>10) $spring="19".$spring;
      else $spring="20".$spring;
      $ix2=$ix+1;
      $appdate=$row[$ix2];
      $mo=substr($appdate,0,2);
      $day=substr($appdate,2,2);
      if($mo>=6) $newappdate=$fall."-".$mo."-".$day;
      else $newappdate=$spring."-".$mo."-".$day;
      $ix3=$ix2+1;
      $ix4=$ix3+1;
      $ix5=$ix4+1;
      $ix6=$ix5+1;
      $ix7=$ix6+1;
      if(trim($regyr)!="")
      {
         $sql2="INSERT INTO wroff_hist (offid,regyr,appdate,contest,rm,obtest,suptest,class) VALUES ('$curid','$fall-$spring','$newappdate','$row[$ix3]','$row[$ix4]','$row[$ix5]','$row[$ix6]','$row[$ix7]')";
         $result2=mysql_query($sql2);
         if(mysql_error()) echo mysql_error()."<br>";
      }
   }
}

?>
