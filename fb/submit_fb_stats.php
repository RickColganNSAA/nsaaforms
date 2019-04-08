<?php
//submit_fb_stats.php: save submitted football stats to fb Db tables

require '../functions.php';
require '../variables.php';

if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

if($submit=="Cancel")
{
   header("Location:view_fb.php?session=$session&school_ch=$school_ch");
}

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

$level=GetLevel($session);
if($level==1)
{
   $school=$school_ch;
}
else
{
   $school=GetSchool($session);
}
$school2=ereg_replace("\'","\'",$school);

//update asst coaches
$asst=ereg_replace("\'","\'",$asst);
$asst=ereg_replace("\"","\'",$asst);
$sql="UPDATE logins SET asst_coaches='$asst' WHERE school='$school2' AND level=3 AND sport LIKE 'Football%'";
$result=mysql_query($sql);

//Update class
   //first get school_id
   $sql="SELECT id FROM headers WHERE school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sch_id=$row[0];
$sql="SELECT t1.id FROM fb_classes AS t1, headers AS t2 WHERE t1.school_id=t2.id AND t2.school='$school2'";
$result=mysql_query($sql);
if($class=="Choose") $class="";
if(mysql_num_rows($result)==0)
{
   $sql2="INSERT INTO fb_classes (school_id, class) VALUES ('$sch_id','$class')";
}
else
{
   $sql2="UPDATE fb_classes SET class='$class' WHERE school_id='$sch_id'";
}
$result2=mysql_query($sql2);

//update offensive statistics
   //erase old data
   $sql="SELECT t1.id FROM fb_stat_off AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2')";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $sql2="DELETE FROM fb_stat_off WHERE id='$row[0]'";
      $result2=mysql_query($sql2);
   }
for($i=0;$i<count($student);$i++)
{
   if($student[$i]!="Choose Player")
   {
      $sql="INSERT INTO fb_stat_off (starter, student_id, jersey_lt, jersey_dk, total_tds, total_pts, rush_carry, rush_yds, rush_tds, rec_catch, rec_yds, rec_tds, co_op) VALUES ('$starter[$i]','$student[$i]','$jersey_lt[$i]','$jersey_dk[$i]','$total_tds[$i]','$total_pts[$i]','$rush_carry[$i]','$rush_yds[$i]','$rush_tds[$i]','$rec_catch[$i]','$rec_yds[$i]','$rec_tds[$i]','$school2')";
      $result=mysql_query($sql);
   }
}

//Update Passing Stats
  //erase old data
  $sql="SELECT t1.id FROM fb_stat_qb AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2')";
  $result=mysql_query($sql);
  while($row=mysql_fetch_array($result))
  {
     $sql2="DELETE FROM fb_stat_qb WHERE id='$row[0]'";
     $result2=mysql_query($sql2);
  }
for($i=0;$i<count($qb_student);$i++)
{
  if($qb_student[$i]!="Choose Player")
  {
     $sql="INSERT INTO fb_stat_qb (student_id, starter, jersey_lt, jersey_dk, comp, attempts, yds, tds, intercepts, co_op) VALUES ('$qb_student[$i]','$qb_starter[$i]','$qb_jersey_lt[$i]','$qb_jersey_dk[$i]','$qb_comp[$i]','$qb_attempts[$i]','$qb_yds[$i]','$qb_tds[$i]','$qb_int[$i]','$school2')";
     $result=mysql_query($sql);
  }
}

//Update Kicking Stats
  //erase old data
  $sql="SELECT t1.id FROM fb_stat_kick AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2')";
  $result=mysql_query($sql);
  while($row=mysql_fetch_array($result))
  {
     $sql2="DELETE FROM fb_stat_kick WHERE id='$row[0]'";
     $result2=mysql_query($sql2);
  }
for($i=0;$i<count($k_student);$i++)
{
  if($k_student[$i]!="Choose Player")
  {
     $sql="INSERT INTO fb_stat_kick (student_id, starter, jersey_lt, jersey_dk, attempts, yds, avg, longest, co_op) VALUES ('$k_student[$i]','$k_starter[$i]','$k_jersey_lt[$i]','$k_jersey_dk[$i]','$k_attempts[$i]','$k_yds[$i]','$k_avg[$i]','$k_longest[$i]','$school2')";
     $result=mysql_query($sql);
  }
}

//Update PlaceKicking Stats
   //erase old data
   $sql="SELECT t1.id FROM fb_stat_pk AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2')";
   $result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="DELETE FROM fb_stat_pk WHERE id='$row[0]'";
   $result2=mysql_query($sql2);
}
for($i=0;$i<count($pk_student);$i++)
{
   if($pk_student[$i]!="Choose Player")
   {
      $sql="INSERT INTO fb_stat_pk (starter, student_id, jersey_lt, jersey_dk, pat_att, pat_good, fg_att, fg_good, longest, co_op) VALUES ('$pk_starter[$i]','$pk_student[$i]','$pk_jersey_lt[$i]','$pk_jersey_dk[$i]','$pk_pat_att[$i]','$pk_pat_good[$i]','$pk_fg_att[$i]','$pk_fg_good[$i]','$pk_longest[$i]','$school2')";
      $result=mysql_query($sql);
   }
}

//Update Defensive Stats
   //erase old data
   $sql="SELECT t1.id FROM fb_stat_def AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school2' OR t1.co_op='$school2')";
   $result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="DELETE FROM fb_stat_def WHERE id='$row[0]'";
   $result2=mysql_query($sql2);
}
for($i=0;$i<count($d_student);$i++)
{
   if($d_student[$i]!="Choose Player")
   {
      $sql="INSERT INTO fb_stat_def (starter, student_id, jersey_lt, jersey_dk, tackles_solo, tackles_asst, tackles_totl, sacks, intercepts, blocks, fumbles, co_op) VALUES ('$d_starter[$i]','$d_student[$i]','$d_jersey_lt[$i]','$d_jersey_dk[$i]','$d_tackles_solo[$i]','$d_tackles_asst[$i]','$d_tackles_totl[$i]','$d_sacks[$i]','$d_intercepts[$i]','$d_blocks[$i]','$d_fumbles[$i]','$school2')";
      $result=mysql_query($sql);
   }
}

//Update Team Stats
if(!($pts=="" && $r_yds=="" && $p_yds=="" && $total=="" && $opp_pts=="" && $opp_r_yds=="" && $opp_p_yds=="" && $opp_total==""))
{
$sql="SELECT * FROM fb_team WHERE school_id='$sch_id'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)	//UPDATE
{
   $sql2="UPDATE fb_team SET pts='$pts', r_yds='$r_yds', p_yds='$p_yds', total='$total', opp_pts='$opp_pts', opp_r_yds='$opp_r_yds', opp_p_yds='$opp_p_yds', opp_total='$opp_total' WHERE school_id='$sch_id'";
}
else				//INSERT
{
   $sql2="INSERT INTO fb_team (school_id ,pts, r_yds, p_yds, total, opp_pts, opp_r_yds, opp_p_yds, opp_total) VALUES ('$sch_id','$pts','$r_yds','$p_yds','$total','$opp_pts','$opp_r_yds','$opp_p_yds','$opp_total')";
}
$result=mysql_query($sql2);
}//end if not all fields are empty

//update Records Broken (fb_records)
for($i=0;$i<count($opp);$i++)
{
   $sql="SELECT * FROM fb_records WHERE id='$recordid[$i]'";
   $result=mysql_query($sql);
   $now=time();
   while($row=mysql_fetch_array($result))
   {
      $date[$i]=mktime(0,0,0,$month[$i],$day[$i],$year[$i]);
      if($opp[$i]!=$row[2] || $date[$i]!=$row[3] || $record[$i]!=$row[4])
      {
	 //UPDATE and MARK AS NEW EDIT
         $record[$i]=ereg_replace("\'","\'",$record[$i]);
	 $record[$i]=ereg_replace("\"","\"",$record[$i]);
	 $sql2="UPDATE fb_records SET opp_id='$opp[$i]', date='$date[$i]', record='$record[$i]', edited='$now' WHERE id='$recordid[$i]'";
	 $result2=mysql_query($sql2);
      }
   }
   if(mysql_num_rows($result)==0)	//INSERT
   {
      if($opp[$i]!="Choose Opponent")
      {
         $record[$i]=ereg_replace("\'","\'",$record[$i]);
         $record[$i]=ereg_replace("\"","\"",$record[$i]);
         $date[$i]=mktime(0,0,0,$month[$i],$day[$i],$year[$i]);
         $sql2="INSERT INTO fb_records (school_id, opp_id, date, record,edited) VALUES ('$sch_id','$opp[$i]','$date[$i]','$record[$i]','$now')";
         $result2=mysql_query($sql2);
      }
   }
}

//store today's date as last update for this school in fb_stat_updates
$today=time();
$sql="SELECT * FROM fb_stat_updates WHERE school_id='$sch_id'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   $sql2="INSERT INTO fb_stat_updates (school_id,date) VALUE ('$sch_id','$today')";
}
else
{
   $sql2="UPDATE fb_stat_updates SET date='$today' WHERE school_id='$sch_id'";
}
$result2=mysql_query($sql2);

if($submit=="Save & Keep Editing")
{
   header("Location:edit_fb_stats.php?session=$session&school_ch=$school_ch");
}
else if($submit=="Save & View Form")
{
   header("Location:view_fb_stats.php?session=$session&school_ch=$school_ch");
}
?>
