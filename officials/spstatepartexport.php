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

$sql="SELECT * FROM $dbscores.spschool ORDER BY school";
$result=mysql_query($sql);
$csv="\"Name\"\t\"School\"\t\"Event\"\r\n";
while($row=mysql_fetch_array($result))
{
   //check if school has any qualifiers for state:
   $curlist="";
   for($i=0;$i<count($spevents);$i++)
   {
      $event=$spevents[$i]; $field=$event."_sch"; $field2=$event."_stud";
      if($event!='dram' && $event!='duet')
      {
         $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE ($field LIKE '$row[sid],%' OR $field LIKE '%,$row[sid],%' OR $field LIKE '%,$row[sid]')";
         $result2=mysql_query($sql2);
         while($row2=mysql_fetch_array($result2))
         {
	    $schlist=split(",",$row2[$field]); $studlist=split(",",$row2[$field2]);
	    for($j=0;$j<count($schlist);$j++)
	    {
	       if(trim($schlist[$j])==$row[sid])
	          $curlist.=$studlist[$j].",";
	    }
         }
      }
      else
      {
         if($event=='dram') $table="sp_state_drama";
         else $table="sp_state_duet";
         $sql2="SELECT * FROM $dbscores.$table WHERE $field='$row[sid]'";
         $result2=mysql_query($sql2);
	 while($row2=mysql_fetch_array($result2))
	 {
	    $curlist.=$row2[$field2].",";
	 }
      }
   }
   if($curlist!='')
   {
      $curlist=substr($curlist,0,strlen($curlist)-1);
      $curlist=Unique($curlist);
      $list=split(",",$curlist);
      $sql2="SELECT id,first,last,semesters FROM $dbscores.eligibility WHERE (";
      for($i=0;$i<count($list);$i++)
      {
	 if($list[$i]!='')
	 {
            $sql2.="id='$list[$i]' OR ";
	 }
      }
      $sql2=substr($sql2,0,strlen($sql2)-4).") ORDER BY last,first";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         if(ereg("[(]",$row2[first]))
         {
            $temp=split("[(]",$row2[first]);
            $nickname=$temp[1];
            $nickname=ereg_replace("[)]","",$nickname);
            $name="$nickname $row2[last]";
         }
         else
            $name="$row2[first] $row2[last]";
	 $curevents=split(",",GetSpeechEventsParticipatingIn($row2[id]));
         for($k=0;$k<count($curevents);$k++)
	 {
	    $curevent=trim($curevents[$k]);
	    if(ereg("Drama",$curevent) || $curevent=="Duet Acting")
            {
	       $names=""; 
 	       if(ereg("Drama",$curevent))
	       {
		  $field1="dram_sch"; $field2="dram_stud"; $table="sp_state_drama";
	       }
	       else
	       {
		  $field1="duet_sch"; $field2="duet_stud"; $table="sp_state_duet";
	       }
	       $sql3="SELECT $field2 FROM $dbscores.$table WHERE $field1='$row[sid]' AND ($field2 LIKE '$row2[id],%' OR $field2 LIKE '%,$row2[id],%' OR $field2 LIKE '%,$row2[id]')";
	       $result3=mysql_query($sql3);
	       $row3=mysql_fetch_array($result3);
	       $studlist=split(",",$row3[0]);
	       for($s=0;$s<count($studlist);$s++)
	       {
		  $studlist[$s]=trim($studlist[$s]);
	          $sql4="SELECT id,first,last,semesters FROM $dbscores.eligibility WHERE id='$studlist[$s]'";
	          $result4=mysql_query($sql4);
	          $row4=mysql_fetch_array($result4);
                  if(ereg("[(]",$row4[first]))
                  {
            	     $temp=split("[(]",$row4[first]);
            	     $nickname=$temp[1];
            	     $nickname=ereg_replace("[)]","",$nickname);
            	     $name="$nickname $row4[last]";
         	  }
         	  else
            	     $name="$row4[first] $row4[last]";
	          if(trim($name)!='')
	             $names.=$name.", ";
 	       }
	       $names=trim($names);
	       $names=substr($names,0,strlen($names)-1);
	       $name=$names;
            }  
            $csv.="\"$name\"\t\"$row[school]\"\t\"".$curevent."\"\r\n";
	 }
      }
   }
}

   $open=fopen(citgf_fopen("/home/nsaahome/reports/spstatepartexport.txt"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/spstatepartexport.txt");
   echo "<a href=\"reports.php?session=$session&filename=spstatepartexport".$class.".txt\">spstatepartexport.txt</a><br>";

function GetSpeechEventsParticipatingIn($studid)
{
   require 'variables.php';
   $dbscores="$db_name";
   $dboffs="$db_name2";
   $eventlist="";
   for($e=0;$e<count($spevents);$e++)
   {
      $event=$spevents[$e]; $field=$event."_sch"; $field2=$event."_stud";
      if($event!='dram' && $event!='duet')
      {
         $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE ($field2 LIKE '$studid,%' OR $field2 LIKE '%,$studid,%' OR $field2 LIKE '%,$studid')";
         $result2=mysql_query($sql2);
         if(mysql_num_rows($result2)>0)
         {
	    $eventlist.=$spevents2[$e].",";
         }
      }
      else
      {
         if($event=='dram') $table="sp_state_drama";
         else $table="sp_state_duet";
         $sql2="SELECT * FROM $dbscores.$table WHERE ($field2 LIKE '$studid,%' OR $field2 LIKE '%,$studid,%' OR $field2 LIKE '%,$studid')";
         $result2=mysql_query($sql2);
         if(mysql_num_rows($result2)>0)
         {
            $eventlist.=$spevents2[$e].",";
         }
      }
   }
   if($eventlist!='')
      $eventlist=substr($eventlist,0,strlen($eventlist)-1);
   return $eventlist;
}
?>
