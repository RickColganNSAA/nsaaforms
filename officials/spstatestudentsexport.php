<?php
/******************************************
spstatestudentsexport.php
Export by Class, School
List students entered, their event, total #
Created 3/8/10
Author Ann Gaffigan
*******************************************/

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

$dboffs="$db_name2";
$dbscores="$db_name";
$string="\"Class\",\"School\",\"Total\",";
for($i=1;$i<=25;$i++)
{
   $string.="\"Student ".$i."\",";
}
$string.="\r\n";
//generate file of schools with # unique qualifiers for each school
$sql="SELECT * FROM $dbscores.spschool ORDER BY class,school";
$result=mysql_query($sql);
$class='';
while($row=mysql_fetch_array($result))
{
   $school=$row[school];
   $school2=addslashes($row[school]);
   $code=$row[code];
   $sid=$row[sid];
   $qualifiers="";
   if($class!=$row['class'])
      $class=$row['class'];
   $sql2="SELECT * FROM $dbscores.sp_state_drama WHERE dram_sch='$sid'";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $qualifiers.=$row2[dram_stud];
      if(substr($qualifiers,strlen($qualifiers)-1,1)!=",")
	 $qualifiers.=",";
   }
   $qualifiers=ereg_replace(",,",",",$qualifiers); 
   $sql2="SELECT * FROM $dbscores.sp_state_duet WHERE duet_sch='$sid'";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $qualifiers.=$row2[duet_stud];
      if(substr($qualifiers,strlen($qualifiers)-1,1)!=",")
         $qualifiers.=",";
   }
   $qualifiers=ereg_replace(",,",",",$qualifiers);
   $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (hum_sch LIKE '$sid,%' OR hum_sch LIKE '%,$sid,%' OR hum_sch LIKE '%,$sid')";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $sch=split(",",$row2[hum_sch]);
      $stud=split(",",$row2[hum_stud]);
      for($i=0;$i<count($sch);$i++)
      {
         if($sch[$i]==$sid)
         {
	    $qualifiers.=$stud[$i].",";
	 }
      }
   }
   $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (ser_sch LIKE '$sid,%' OR ser_sch LIKE '%,$sid,%' OR ser_sch LIKE '%,$sid')";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $sch=split(",",$row2[ser_sch]);
      $stud=split(",",$row2[ser_stud]);
      for($i=0;$i<count($sch);$i++)
      {
         if($sch[$i]==$sid)
         {
            $qualifiers.=$stud[$i].",";
         }
      }
   } 
   $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (ext_sch LIKE '$sid,%' OR ext_sch LIKE '%,$sid,%' OR ext_sch LIKE '%,$sid')";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $sch=split(",",$row2[ext_sch]);
      $stud=split(",",$row2[ext_stud]);
      for($i=0;$i<count($sch);$i++)
      {
         if($sch[$i]==$sid)
         {
            $qualifiers.=$stud[$i].",";
         }
      }
   }
   $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (poet_sch LIKE '$sid,%' OR poet_sch LIKE '%,$sid,%' OR poet_sch LIKE '%,$sid')";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $sch=split(",",$row2[poet_sch]);
      $stud=split(",",$row2[poet_stud]);
      for($i=0;$i<count($sch);$i++)
      {
         if($sch[$i]==$sid)
         {
            $qualifiers.=$stud[$i].",";
         }
      }
   }
   $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (pers_sch LIKE '$sid,%' OR pers_sch LIKE '%,$sid,%' OR pers_sch LIKE '%,$sid')";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $sch=split(",",$row2[pers_sch]);
      $stud=split(",",$row2[pers_stud]);
      for($i=0;$i<count($sch);$i++)
      {
         if($sch[$i]==$sid)
         {
            $qualifiers.=$stud[$i].",";
         }
      }
   }
   $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (ent_sch LIKE '$sid,%' OR ent_sch LIKE '%,$sid,%' OR ent_sch LIKE '%,$sid')";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $sch=split(",",$row2[ent_sch]);
      $stud=split(",",$row2[ent_stud]);
      for($i=0;$i<count($sch);$i++)
      {
         if($sch[$i]==$sid)
         {
            $qualifiers.=$stud[$i].",";
         }
      }
   }
   $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (inf_sch LIKE '$sid,%' OR inf_sch LIKE '%,$sid,%' OR inf_sch LIKE '%,$sid')";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $sch=split(",",$row2[inf_sch]);
      $stud=split(",",$row2[inf_stud]);
      for($i=0;$i<count($sch);$i++)
      {
         if($sch[$i]==$sid)
         {
            $qualifiers.=$stud[$i].",";
         }
      }
   }
   $uniquequals=Unique($qualifiers);
   $temp=$uniquequals;
   if(trim($uniquequals)=="," || trim($uniquequals)=='') 
      $count=0;
   else
   {
      $uniquequals=split(",",$uniquequals);
      $count=0; $studlist="";
      for($c=0;$c<count($uniquequals);$c++)
      {
	 if(trim($uniquequals[$c])!='') 
         {
            $count++;
	       $sql2="SELECT first,last FROM $dbscores.eligibility WHERE id='$uniquequals[$c]'";
	       $result2=mysql_query($sql2);
	       $row2=mysql_fetch_array($result2);
	       $studlist.="\"$row2[first] $row2[last], ";
	       $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (hum_stud LIKE '$uniquequals[$c],%' OR hum_stud LIKE '%,$uniquequals[$c],%' OR hum_stud LIKE '%,$uniquequals[$c]')";
	       $result2=mysql_query($sql2);
	       if(mysql_num_rows($result2)>0) $studlist.="Hum, ";
               $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (ser_stud LIKE '$uniquequals[$c],%' OR ser_stud LIKE '%,$uniquequals[$c],%' OR ser_stud LIKE '%,$uniquequals[$c]')";
               $result2=mysql_query($sql2);
               if(mysql_num_rows($result2)>0) $studlist.="Ser, ";
               $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (ext_stud LIKE '$uniquequals[$c],%' OR ext_stud LIKE '%,$uniquequals[$c],%' OR ext_stud LIKE '%,$uniquequals[$c]')";
               $result2=mysql_query($sql2);
               if(mysql_num_rows($result2)>0) $studlist.="Ext, ";
               $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (poet_stud LIKE '$uniquequals[$c],%' OR poet_stud LIKE '%,$uniquequals[$c],%' OR poet_stud LIKE '%,$uniquequals[$c]')";
               $result2=mysql_query($sql2);
               if(mysql_num_rows($result2)>0) $studlist.="Poet, ";
               $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (pers_stud LIKE '$uniquequals[$c],%' OR pers_stud LIKE '%,$uniquequals[$c],%' OR pers_stud LIKE '%,$uniquequals[$c]')";
               $result2=mysql_query($sql2);
               if(mysql_num_rows($result2)>0) $studlist.="Pers, ";
               $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (ent_stud LIKE '$uniquequals[$c],%' OR ent_stud LIKE '%,$uniquequals[$c],%' OR ent_stud LIKE '%,$uniquequals[$c]')";
               $result2=mysql_query($sql2);
               if(mysql_num_rows($result2)>0) $studlist.="Ent, ";
               $sql2="SELECT * FROM $dbscores.sp_state_qual WHERE (inf_stud LIKE '$uniquequals[$c],%' OR inf_stud LIKE '%,$uniquequals[$c],%' OR inf_stud LIKE '%,$uniquequals[$c]')";
               $result2=mysql_query($sql2);
               if(mysql_num_rows($result2)>0) $studlist.="Inf, ";
               $sql2="SELECT * FROM $dbscores.sp_state_drama WHERE (dram_stud LIKE '$uniquequals[$c],%' OR dram_stud LIKE '%,$uniquequals[$c],%' OR dram_stud LIKE '%,$uniquequals[$c]')";
               $result2=mysql_query($sql2);
               if(mysql_num_rows($result2)>0) $studlist.="OID, ";
               $sql2="SELECT * FROM $dbscores.sp_state_duet WHERE (duet_stud LIKE '$uniquequals[$c],%' OR duet_stud LIKE '%,$uniquequals[$c],%' OR duet_stud LIKE '%,$uniquequals[$c]')";
               $result2=mysql_query($sql2);
               if(mysql_num_rows($result2)>0) $studlist.="Duet, ";
	       $studlist=substr($studlist,0,strlen($studlist)-2)."\",";
	 }
      }
      //$count=count($uniquequals);
   }
   if($count>0)
   {
      $string.="\"$class\",\"$school\",\"$count\",$studlist\r\n"; 
   }
}//end for each school
   $open=fopen(citgf_fopen("/home/nsaahome/reports/spstatestudentslabels.csv"),"w");
   fwrite($open,$string);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/spstatestudentslabels.csv");
   header("Location:reports.php?session=$session&filename=spstatestudentslabels.csv");
exit();

function GetYear($semester)
{
  //return year in school, given the semester
  if(!$semester) return "";
  if($semester==1 || $semester==2)
    return 9;
  else if($semester==3 || $semester==4)
    return 10;
  else if($semester==5 || $semester==6)
    return 11;
  else if($semester==7 || $semester==8)
    return 12;
  else if($semester<1)
    return "<9";
  else if($semester>8)
    return ">12";
  else return "";
}
?>
