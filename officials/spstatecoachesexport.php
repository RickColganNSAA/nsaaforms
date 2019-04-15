<?php
/*******************************************
spstatecoachesexport.php
Excel export of qualifying school and coach
Created 3/9/10
Author Ann Gaffigan
********************************************/
require 'functions.php';
require 'variables.php';
require '../../calculate/functions.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevelJ($session);
if($level==4) $level=1;

if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php?error=1");
   exit();
}

$dbscores="$db_name";
$dboffs="$db_name2";

   $csv="";
   $sql="SELECT * FROM $dbscores.spschool ORDER BY class,school";
   $result=mysql_query($sql);
   $csv="\"Class\",\"School\",\"Coach\"\r\n";
   while($row=mysql_fetch_array($result))
   {
      $class=$row['class'];
      //check if school has any qualifiers for state:
      $qual=0;
      for($i=0;$i<count($spevents);$i++)
      {
	 $event=$spevents[$i]; $field=$event."_sch";
	 if($event!='dram' && $event!='duet')
	 {
            $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE ($field LIKE '$row[sid],%' OR $field LIKE '%,$row[sid],%' OR $field LIKE '%,$row[sid]')";
	    $result2=mysql_query($sql2);
	    if(mysql_num_rows($result2)>0)
	    {
	       $qual=1; $i=count($spevents);
	    }
	 }
	 else
	 {
	    if($event=='dram') $table="sp_state_drama";
	    else $table="sp_state_duet";
	    $sql2="SELECT * FROM $dbscores.$table WHERE $field='$row[sid]'";
	    $result2=mysql_query($sql2);
	    if(mysql_num_rows($result2)>0)
	    {
	       $qual=1; $i=count($spevents);
	    }
	 }
      }
      if($qual==1)
      {
	 //Get Coach
	 $sql2="SELECT name FROM $dbscores.logins WHERE school='".addslashes(GetMainSchoolName($row[sid],'sp'))."' AND sport='Speech'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $coach=$row2[0];
	 //check if special co-op coach for this sport
	 $sql2="SELECT * FROM $dbscores.spschool WHERE sid='$row[sid]'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 if($row2[coach]!='') $coach=$row2[coach];
         $csv.="\"$class\",\"".GetSchoolName($row[sid],'sp',date("Y"))."\",\"$coach\"\r\n";
      }      
   }

   $open=fopen(citgf_fopen("/home/nsaahome/reports/spcoachesexport.csv"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/spcoachesexport.csv");
   header("Location:reports.php?session=$session&filename=spcoachesexport.csv");
?>
