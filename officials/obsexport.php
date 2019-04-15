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

if(!$type) $type="mail";

if($type=="mail") //export mailing label data
{
   //1) generate passcodes for observers without one yet
   //2) create report with mailing info and passcode as .CSV
   if($query && $query!="")
   {
      if(ereg("AS",$query))
         $sql=$query." ORDER BY t1.last";
      else
         $sql=$query." ORDER BY last";
   }
   else if($sport && !ereg("All",$sport))
   {
      $sql="SELECT * FROM observers WHERE $sport='x' ORDER BY last";
   }
   else
   {
      $sql="SELECT * FROM observers ORDER BY last";
   }

   echo $init_html;
   echo "<table width=100%><tr align=center><td><br>";
   echo "<table>";
   //get full sport name
   for($i=0;$i<count($activity);$i++)
   {
      if($activity[$i]==$sport)
	 $sportname=$act_long[$i];
   }
   if(ereg("All",$sport) || !$sport) $sportname="All Sports";
   $today=date("F j, Y",time());

   echo "<tr align=left><td align=left>";
   echo "<b>Sport:</b> $sportname</td>";
   echo "<td align=left><b>Date:</b> $today</td>";
   echo "</tr>";
   echo "<tr align=center><td colspan=2><hr></td></tr>";

   $result=mysql_query($sql);
   $ct=mysql_num_rows($result);
   //echo "<tr><td colspan=2>$sql</td></tr>";
   echo "<tr align=left><td colspan=2>Number of Records Exported: <b>$ct</b></td></tr>";
   $ix=0; 
   $csv="\"Date\",\"Last\",\"First\",\"Address\",\"City\",\"State\",\"Zip\",";
   $csv.="\"E-mail\",\"Home Phone\",\"Work Phone\",\"Cell Phone\",\"Fax\",\"Passcode\"\r\n";
   $today=date("m/d/Y",time());
   while($row=mysql_fetch_array($result))
   {
      $curid=$row[0];
      //get passcode or generate one
      $sql2="SELECT passcode FROM logins WHERE level='3' AND obsid='$curid'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)	//need to generate passcode
      {
	 $curlast=ereg_replace("\'","",$row[last]);
	 $curlast=ereg_replace(" ","",$curlast);
	 $passcode=ereg_replace(" ","",substr($curlast,0,6));
	 $passcode=ereg_replace("\'","",$passcode);
	 $passcode=ereg_replace("[.]","",$passcode);
	 $num=rand(100,999);
	 $passcode.=$num;
	 //check that this passcode is not already in use
	 $sql3="SELECT id FROM logins WHERE passcode='$passcode'";
	 $result3=mysql_query($sql3);
	 while(mysql_num_rows($result3)>0)
	 {
	    $oldnum=$num;
	    $num=rand(100,999);
	    $passcode=ereg_replace($oldnum,$num,$passcode);
	    $sql3="SELECT id FROM logins WHERE passcode='$passcode'";
	    $result3=mysql_query($sql3);
	 }
	 //now passcode is unique; enter into logins table for current off
	 $sql3="INSERT INTO logins (name,level,passcode,obsid) VALUES ('$row[first] $row[last]','3','$passcode','$curid')";
	 $result3=mysql_query($sql3);
	 $curpasscode=$passcode;
      }
      else
      {
	 $row2=mysql_fetch_array($result2);
	 $curpasscode=$row2[0];
      }

      $homeph="(".substr($row[homeph],0,3).")".substr($row[homeph],3,3)."-".substr($row[homeph],6,4);
      $workph="(".substr($row[workph],0,3).")".substr($row[workph],3,3)."-".substr($row[workph],6,4);
      $cellph="(".substr($row[cellph],0,3).")".substr($row[cellph],3,3)."-".substr($row[cellph],6,4);
      $fax="(".substr($row[fax],0,3).")".substr($row[fax],3,3)."-".substr($row[fax],6,4);
      if($homeph=="()-") $homeph="";
      if($workph=="()-") $workph="";
      if($cellph=="()-") $cellph="";
      if($fax=="()-") $fax="";

      $csv.="\"$today\",\"$row[last]\",\"$row[first]\",\"$row[address]\",\"$row[city]\",\"$row[state]\",\"$row[zip]\",\"$row[email]\",\"$homeph\",\"$workph\",\"$cellph\",\"$fax\",\"$curpasscode\"\r\n";
      echo "<tr align=left><td colspan=3>$row[last], $row[first]</td></tr>";

      $ix++;
   }
   echo "</table>";
   //write to csv file
   $filename="observers.csv";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
   echo "<br>Open File: <a href=\"reports.php?session=$session&filename=$filename\" target=new2>$filename</a>";
   echo $end_html;
}
?>
