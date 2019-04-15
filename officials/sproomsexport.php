<?php
/******************************************
EXPORT ROOM ASSIGNMENTS FOR STATE SPEECH
1 .CSV FILE FOR EACH CLASS
******************************************/

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session) || GetLevelJ($session)!=1)
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
echo GetHeaderJ($session,"jcontractadmin");
echo "<br><br>";
echo "<b>State Speech Room Assignment Exports:<br><br>";

for($i=0;$i<count($classes);$i++)
{
   $csv="Event,Round1Sect1Room,Round1Sect1Judge,Round1Sect2Room,Round1Sect2Judge,Round1Sect3Room,Round1Sect3Judge,Round2Sect1Room,Round2Sect1Judge,Round2Sect2Room,Round2Sect2Judge,Round2Sect3Room,Round2Sect3Judge\r\n";
   $curclass=$classes[$i];
   for($j=0;$j<count($sp_export2);$j++)
   {
      $curevent=$sp_export2[$j];
      $eventnum=$j+1;
      $csv.="$eventnum--$sp_export1[$j],";
      for($round=1;$round<=2;$round++)
      {
	 $sql="SELECT id FROM spstaterounds WHERE round='$round' AND event='$curevent' AND class='$curclass'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
	 $roundid=$row[0];
         for($section=1;$section<=3;$section++)
	 {
	    $sql="SELECT id,room FROM spstaterooms WHERE roundid='$roundid' AND section='$section'";
	    $result=mysql_query($sql);
	    $row=mysql_fetch_array($result);
	    $roomid=$row[0]; $room=$row[1];
	    $csv.="$room,";
	    $sql="SELECT offid FROM spstateassign WHERE roomid='$roomid'";
	    $result=mysql_query($sql);
	    $row=mysql_fetch_array($result);
	    $offid=$row[0];
	    if($offid!=0)
	       $csv.=GetJudgeName($offid).","; 
	    else $csv.=",";
	 }
      }
      $csv.="\r\n";
   }
   
   //write to CSV file for this class:
   $filename="sproomsClass".$curclass.".csv";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
   fwrite($open,$csv);
   echo "<a target=new class=small href=\"reports.php?session=$session&filename=$filename\">Class $curclass Room Assignments</a><br>";
}

echo $end_html;
?>
