<?php

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session) || $level!='1')
{
   header("Location:../index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

echo $init_html;
echo $header;

$sql="SELECT sid,school,code FROM spschool ORDER BY school";
$result=mysql_query($sql);
$spsch=array();
$spsch[name]=array();
$spsch[part]=array();
$spsch[code]=array();
$spsch[coach]=array();
$spids=array();
$ix=0;
while($row=mysql_fetch_array($result))
{
   $spsch[name][$row[0]]=$row[1];
   $spsch[part][$row[0]]=0;
   $spsch[code][$row[0]]=$row[2];

   //get speech coach
   $sch2=ereg_replace("\'","\'",$row[1]);
   if(ereg("/",$sch2))
   {
      $coops=split("/",$sch2);
      $sch2=$coops[0];
   }
   $sql2="SELECT name FROM logins WHERE school='$sch2' AND sport='Speech'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $spsch[coach][$row[0]]=$row2[0];

   $spsch[dist][$row[0]]="";
   $spids[$ix]=$row[0];
   $ix++;
}

$sql="SELECT DISTINCT dist FROM sp_state_dist WHERE dist!='' ORDER BY dist";
$result=mysql_query($sql);
$dists=array(); $dix=0;
while($row=mysql_fetch_array($result))
{
   $curdist=$row[0];
   $sql2="SELECT dram_sch,dram_stud FROM sp_state_drama WHERE dist_id='$curdist'";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $dramstuds=split(",",$row2[1]);
      for($i=0;$i<count($dramstuds);$i++)
      {
	 if(trim($dramstuds[$i])!="")
	 {
	    //$spsch[part][$row2[0]]++;
	    $spsch[part][$row2[0]].=$dramstuds[$i]."/";
	    $spsch[dist][$row2[0]]=$curdist;
	 }
      }
   }
   $sql2="SELECT duet_sch,duet_stud FROM sp_state_duet WHERE dist_id='$curdist'";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $duetstuds=split(",",$row2[1]);
      for($i=0;$i<count($duetstuds);$i++)
      {
         if(trim($duetstuds[$i])!="")
  	 {
	    //$spsch[part][$row2[0]]++;
	    $spsch[part][$row2[0]].=$duetstuds[$i]."/";
            $spsch[dist][$row2[0]]=$curdist;
	 }
      }
   }
   $sql2="SELECT hum_sch,hum_stud,ser_sch,ser_stud,ext_sch,ext_stud,poet_sch,poet_stud,pers_sch,pers_stud,ent_sch,ent_stud,inf_sch,inf_stud FROM sp_state_qual WHERE dist_id='$curdist'";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      for($i=0;$i<count($row2);$i++)
      {
	 $sch=split(",",$row2[$i]);
	 $i++;
	 $stud=split(",",$row2[$i]);
	 for($j=0;$j<count($sch);$j++)
	 {
	    //$spsch[part][$sch[$j]]++;
	    $spsch[part][$sch[$j]].=$stud[$j]."/";
            $spsch[dist][$sch[$j]]=$curdist;
	 }
      }
   }
   $dists[$dix]=$curdist;
   $dix++;
}

echo "<br>";
echo "<table cellspacing=0 cellpadding=2 border=1 bordercolor=#000000 width=400><caption><b>State Speech Participation Numbers:<br></b><i>(If a school is not listed under it's district, it had 0 participants.)</i></caption>";
$classes=array("A","B","C1","C2","D1","D2");
$A=0; $B=0; $C1=0; $C2=0; $D1=0; $D2=0; //indexes for $sprow[A][], $sprow[B][], etc...
$sprow=array();
//Go through each district and store entry in that class's array
for($k=0;$k<count($dists);$k++)
{
   $sql="SELECT class,district FROM $db_name2.spdistricts WHERE id='$dists[$k]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $curclass=$row['class'];
   for($i=0;$i<count($spids);$i++)
   {
      $curid=$spids[$i];
      if($spsch[dist][$curid]==$dists[$k])
      {
	 //make sure each student is only counted once
	 $spsch[part][$curid]=substr($spsch[part][$curid],0,strlen($spsch[part][$curid])-1);
	 $curstuds=ereg_replace("/",",",$spsch[part][$curid]);
	 $curstuds=Unique($curstuds);
	 $curstuds=split(",",$curstuds);
	 $curct=count($curstuds);
	 $row=$spsch[name][$curid]." - ".$spsch[code][$curid]."!".$spsch[coach][$curid]."&nbsp;!".$curct; //$spsch[part][$curid];
	 $sprow[$curclass][$$curclass]=$row;
	 $$curclass++;
         //echo "<tr align=left><td align=left>".$spsch[name][$curid]."</td><td align=left>".$spsch[part][$curid]."</td></tr>";
      }
   }
}
//Display by class alphabetically
for($i=0;$i<count($classes);$i++)
{
   sort($sprow[$classes[$i]]);
   echo "<tr align=left><th align=left class=smaller colspan=3>".$classes[$i]."</th></tr>";
   echo "<tr align=left><td><b>School - Code</b></td><td><b>Coach</b></td><td><b>Count</b></td></tr>";
   for($j=0;$j<count($sprow[$classes[$i]]);$j++)
   {
      $temp=split("!",$sprow[$classes[$i]][$j]);
      echo "<tr align=left><td align=left>".$temp[0]."</td><td align=left>".$temp[1]."</td><td align=left>".$temp[2]."</td></tr>";
   }
}
echo "</table>";
echo "<br><a href=\"../welcome.php?session=$session&open1=not1&curactivity=Speech&open2=&open3=3&open4=#1\">Home-->Speech</a>";

echo $end_html;
?>
