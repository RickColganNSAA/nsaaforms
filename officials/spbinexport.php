<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
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

$sql0="SELECT DISTINCT class FROM spdistricts WHERE class!='' ORDER BY class";
$result0=mysql_query($sql0);
while($row0=mysql_fetch_array($result0))
{
   $class=$row0['class'];
   $csv="";
   $sql="SELECT * FROM $dbscores.spschool WHERE class='$class' ORDER BY school";
   $result=mysql_query($sql);
   $csv="\"Class\",\"School\",\"Code\"\r\n";
   while($row=mysql_fetch_array($result))
   {
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
         $csv.="\"$class\",\"$row[school]\",\"$row[sid]\"\r\n";
   }

   $open=fopen(citgf_fopen("/home/nsaahome/reports/spbinexport".$class.".csv"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/spbinexport".$class.".csv");
   echo "<a href=\"reports.php?session=$session&filename=spbinexport".$class.".csv\">Class $class: spbinexport".$class.".csv</a><br>";
}//end for each class
?>
