<?php

require 'functions.php';
require 'variables.php';
require '../calculate/functions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

if(!$sport) $sport='cc_g';
$schooltbl=GetSchoolsTable($sport);
$sql="DELETE FROM ".$sport."_state_quals";
$result=mysql_query($sql);

//get all schools qualifying runners for this class:
$sql="SELECT t1.* FROM ".$sport."_state_team AS t1,$schooltbl AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' AND t1.student_ids IS NOT NULL AND t1.sid!='0' ORDER BY t2.school";
$result=mysql_query($sql);
$sids="";
//temp table: cc_b_state_quals: sid,studentid1,studentid2,studentid3,studentid4,studentid5,studentid6,studentid7
while($row=mysql_fetch_array($result))
{
   $studs=split(",",$row[student_ids]);
   for($i=0;$i<count($studs);$i++)
   {
      $studs[$i]=trim($studs[$i]);
      if($studs[$i]!='' && $studs[$i]!='0')
      {
         $sql2="SELECT * FROM ".$sport."_state_quals WHERE sid='$row[sid]' AND studentid='$studs[$i]'";   
	 $result2=mysql_query($sql2);
         if(mysql_num_rows($result2)==0)      //student not in table yet; INSERT
         {
            $sql3="INSERT INTO ".$sport."_state_quals (sid,studentid) VALUES ('$row[sid]','$studs[$i]')";
            $result3=mysql_query($sql3);
         }
      }
   }
}
$sql="SELECT t1.* FROM ".$sport."_state_indy AS t1,$schooltbl AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' ORDER By t2.school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $stud=$row[student_id]; $sid=$row[sid];
   $sql2="SELECT * FROM ".$sport."_state_quals WHERE sid='$sid' AND studentid='$stud'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)	//student not in table yet; INSERT
   {
      $sql3="INSERT INTO ".$sport."_state_quals (sid,studentid) VALUES ('$sid','$stud')";
      $result3=mysql_query($sql3);
   }
}

$sql="SELECT t1.*,t2.sid FROM eligibility AS t1,".$sport."_state_quals AS t2,$schooltbl AS t3 WHERE t1.id=t2.studentid AND t2.sid=t3.sid AND t3.class='$class' ORDER BY t3.school,t1.last,t1.first";
$result=mysql_query($sql);
//echo "$sql<br>".mysql_error();

$ix=1;
if ($sport=='cc_b' && $class=='A') $ix = numbering('cc_g','A')+1; 
if ($sport=='cc_g' && $class=='B') $ix = numbering('cc_g','A') + numbering('cc_b','A') + 1 ;  
if ($sport=='cc_b' && $class=='B') $ix = numbering('cc_g','A') + numbering('cc_b','A') + numbering('cc_g','B') +1; 
if ($sport=='cc_g' && $class=='C') $ix = numbering('cc_g','A') + numbering('cc_b','A') + numbering('cc_g','B') + numbering('cc_b','B')+ 1 ; 
if ($sport=='cc_b' && $class=='C') $ix = numbering('cc_g','A') + numbering('cc_b','A') + numbering('cc_g','B') + numbering('cc_b','B')+ numbering('cc_g','C')+ 1 ;
if ($sport=='cc_g' && $class=='D') $ix = numbering('cc_g','A') + numbering('cc_b','A') + numbering('cc_g','B') + numbering('cc_b','B')+ numbering('cc_g','C')+ numbering('cc_b','C')+ 1; 
if ($sport=='cc_b' && $class=='D') $ix = numbering('cc_g','A') + numbering('cc_b','A') + numbering('cc_g','B') + numbering('cc_b','B')+ numbering('cc_g','C')+ numbering('cc_b','C')+ numbering('cc_g','D')+ 1; 

$html=$init_html."<table><tr align=left><td>";
if($sport=="cc_g") $gender="Girls";
else $gender="Boys";
$sport2=ereg_replace("_","",$sport);
$html.="<b>".date("Y")." $gender Class $class</b><br>";
$cursch=0;
while($row=mysql_fetch_array($result))
{   
   //if($ix<10) $num="00".$ix;
   //else if($ix<100) $num="0".$ix;
   $num=$ix;
   if($row[sid]!=$cursch)
   {
      $html.="<br><b>".GetSchoolName($row[sid],$sport2,date("Y"))."</b><br>"; 
      $sql2="SELECT t1.name FROM logins AS t1,headers AS t2,$schooltbl AS t3 WHERE t1.school=t2.school AND t2.id=t3.mainsch AND t1.sport='$gender Cross-Country' AND sid='$row[sid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $html.="Coach: $row2[name]<br>";
      $cursch=$row[sid];
   }
   if(ereg("\(",$row[first]))
   {
      $first_nick=split("\(",$row[first]);
      $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
   }
   else $first=$row[first];
   $html.="$num&nbsp;$first&nbsp;$row[last]&nbsp;(".GetYear($row[semesters]).")<br>";
   $ix++;
}

echo $html.$end_html;

exit();


function numbering($sport,$class)
    {
		$schooltbl=GetSchoolsTable($sport);
		$sql="DELETE FROM ".$sport."_state_quals";
		$result=mysql_query($sql);

		//get all schools qualifying runners for this class:
		$sql="SELECT t1.* FROM ".$sport."_state_team AS t1,$schooltbl AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' AND t1.student_ids IS NOT NULL AND t1.sid!='0' ORDER BY t2.school";
		$result=mysql_query($sql);
		$sids="";
		//temp table: cc_b_state_quals: sid,studentid1,studentid2,studentid3,studentid4,studentid5,studentid6,studentid7
		while($row=mysql_fetch_array($result))
		{
		   $studs=split(",",$row[student_ids]);
		   for($i=0;$i<count($studs);$i++)
		   {
			  $studs[$i]=trim($studs[$i]);
			  if($studs[$i]!='' && $studs[$i]!='0')
			  {
				 $sql2="SELECT * FROM ".$sport."_state_quals WHERE sid='$row[sid]' AND studentid='$studs[$i]'";   
			 $result2=mysql_query($sql2);
				 if(mysql_num_rows($result2)==0)      //student not in table yet; INSERT
				 {
					$sql3="INSERT INTO ".$sport."_state_quals (sid,studentid) VALUES ('$row[sid]','$studs[$i]')";
					$result3=mysql_query($sql3);
				 }
			  }
		   }
		}
		$sql="SELECT t1.* FROM ".$sport."_state_indy AS t1,$schooltbl AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' ORDER By t2.school";
		$result=mysql_query($sql);
		while($row=mysql_fetch_array($result))
		{
		   $stud=$row[student_id]; $sid=$row[sid];
		   $sql2="SELECT * FROM ".$sport."_state_quals WHERE sid='$sid' AND studentid='$stud'";
		   $result2=mysql_query($sql2);
		   if(mysql_num_rows($result2)==0)	//student not in table yet; INSERT
		   {
			  $sql3="INSERT INTO ".$sport."_state_quals (sid,studentid) VALUES ('$sid','$stud')";
			  $result3=mysql_query($sql3);
		   }
		}


		 $sql="SELECT t1.*,t2.sid FROM eligibility AS t1,".$sport."_state_quals AS t2,$schooltbl AS t3 WHERE t1.id=t2.studentid AND t2.sid=t3.sid AND t3.class='$class' ORDER BY t3.school,t1.last,t1.first";
		 $result=mysql_query($sql);
		 return mysql_num_rows($result);
    } 

?>
