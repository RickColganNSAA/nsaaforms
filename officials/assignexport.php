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

//State Softball Excel Export 
$disttimes=$sport."disttimes";
$districts=$sport."districts";
$contracts=$sport."contracts";

$open=fopen(citgf_fopen("/home/nsaahome/reports/sbassignexportSTATE.csv"),"w");
$line="Name,Address,City,State,Zip,E-mail,Day1,Day2,Day3\r\n";
fwrite($open,$line);

$sql="SELECT DISTINCT t1.offid FROM $contracts AS t1, $disttimes AS t2 WHERE t1.disttimesid=t2.id AND t2.type='State'";
//get all offs assigned to state
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $offid=$row[offid];
   $line="";
   //get off info
   $sql2="SELECT first,last,middle,address,city,state,zip,email FROM officials WHERE id='$offid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $line.="$row2[first] $row2[middle] $row2[last],$row2[address],$row2[city],$row2[state],$row2[zip],$row2[email],";
   $sql2="SELECT t2.id,t2.day FROM $contracts AS t1,$disttimes AS t2 WHERE t1.disttimesid=t2.id AND t1.offid='$offid' AND t2.type='State' AND t1.times='x' ORDER BY t2.day";
   //get all state assignments for this official
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $date=split("-",$row2[day]);
      $line.=date("F j",mktime(0,0,0,$date[1],$date[2],$date[0])).",";
      /*
      //get other officials for this game
      $sql3="SELECT offid FROM $contracts WHERE times='x' AND disttimesid='$row2[id]' AND offid!='$offid'";
      $result3=mysql_query($sql3);
      while($row3=mysql_fetch_array($result3))
      {
	 $line.=GetOffName($row3[offid]).",";
      }
      $line=substr($line,0,strlen($line)-1);
      $line.="\",";
      */
   }
   if(mysql_num_rows($result2)==0)
      $line.=",";
   $line.="\r\n";
   fwrite($open,$line);
}
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/sbassignexportSTATE.csv");

$filename="sbassignexportSTATE.csv";

echo "<a class=small href=\"reports.php?session=$session&filename=$filename\">$filename</a>";
?>
