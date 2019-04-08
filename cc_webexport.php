<?php

require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

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
$sport2=ereg_replace("_","",$sport);
$districts=$sport2."districts";

$html=$init_html."<table><caption>";
if($sport=="cc_g") { $gender="Girls"; $submitvar="submitted_g"; $schooltbl="ccgschool"; }
else { $gender="Boys"; $submitvar="submitted_b"; $schooltbl="ccbschool"; }
$html.="<b>".date("Y")." $gender Class $class District Results</b><br>";
$html.="</caption><tr align=left><td>";

$sql="SELECT DISTINCT t1.district_id,t2.district,t2.site,t2.hostschool FROM ".$sport."_state_indy AS t1, $db_name2.$districts AS t2 WHERE t1.district_id=t2.id AND t2.class='$class' AND t2.$submitvar='x' ORDER BY t2.district";
$result=mysql_query($sql);
//echo $sql;
while($row=mysql_fetch_array($result))
{
   $distid=$row[district_id];
   $html.="<br><font style=\"font-size:9pt;\"><b>District $class-$row[district]:<b><br></font>";
   $sql2="SELECT t1.first,t1.last,t1.semesters,t1.school,t2.* FROM eligibility AS t1,$db_name.".$sport."_state_indy AS t2 WHERE t1.id=t2.student_id AND t2.district_id='$distid' ORDER BY t2.place";
   $result2=mysql_query($sql2);
   $html.="<br><b>Top 15 Individuals:</b><br><table cellspacing=2 cellpadding=2>";
   while($row2=mysql_fetch_array($result2))
   {
      if(ereg("\(",$row2[first]))
      {
         $first_nick=split("\(",$row2[first]);
         $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
      }
      else $first=$row2[first];
      $html.="<tr align=left><td align=right>$row2[place].</td><td>&nbsp;$first&nbsp;$row2[last]&nbsp;(".GetYear($row2[semesters]).")</td><td>".GetSchoolName(GetSID2($row2[school],$sport2),$sport2,date("Y"))."</td><td>$row2[finishtime]</td></tr>";
   }
   $html.="</table><br>";
   $html.="<br><b>Team Scores:</b><br><table cellspacing=2 cellpadding=2>";
   $sql2="SELECT * FROM ".$sport."_state_team WHERE district_id='$distid' ORDER BY place";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      if($row2[place]<=3)
      {
      $studs=split(",",$row2[student_ids]); $times=split(",",$row2[finishtimes]);
      $timesec=array();
      for($i=0;$i<count($times);$i++)
      {
	 $time=split("[:]",$times[$i]);
	 $timesec[$i]=($time[0]*60)+$time[1];
      }
      //sort arrays of studs and times together
      $studs2=array(); $times2=array(); $timesec2=array();
      for($ix=0;$ix<count($timesec);$ix++)
      {
         $lowest=1000000;
         for($i=0;$i<count($timesec);$i++)
         {
	    if($timesec[$i]<$lowest && $timesec[$i]>=0) 
            {
               $lowest=$timesec[$i]; $lowestix=$i;
            }
         } 
         $timesec2[$ix]=$lowest; $timesec[$lowestix]=-1;
         $times2[$ix]=$times[$lowestix];
	 if($lowest==0) $times2[$ix]="DNF";
         $studs2[$ix]=$studs[$lowestix];
      }
      }//end if 1st, 2nd or 3rd team 
      if($row2[sid]>0)
      {
      $html.="<tr align=left><td align=right>$row2[place].</td><td><b>".GetSchoolName($row2[sid],$sport2,date("Y"))."</b>, $row2[score]</td></tr>";
      }
      if($row2[place]<=3)
      {
      $html.="<tr align=left><td>&nbsp;</td><td>Coach:";
      $sql3="SELECT t1.name FROM logins AS t1,headers AS t2,$schooltbl AS t3 WHERE t1.school=t2.school AND t2.id=t3.mainsch AND t3.sid='$row2[sid]' AND t1.sport='$gender Cross-Country'";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      $html.="&nbsp;$row3[name]</td></tr>";
      $html.="<tr align=left valign=top><td>&nbsp;</td><td>";
      for($i=0;$i<count($studs2);$i++)
      {
         $sql3="SELECT * FROM eligibility WHERE id='$studs2[$i]'";
         $result3=mysql_query($sql3);
	 $row3=mysql_fetch_array($result3);
         if(ereg("\(",$row3[first]))
         {
            $first_nick=split("\(",$row3[first]);
            $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
         }
         else $first=$row3[first];
	 $html.="$first&nbsp;$row3[last] (".GetYear($row3[semesters])."), $times2[$i]<br>";
      }
      $html.="</td></tr>";
      }//end if 1st 2nd or 3rd place team
   }
   $html.="</table><br>";
}
$html.=$end_html;
$filename=ereg_replace("_","",$sport)."Class".$class."Results.html";
$open=fopen(citgf_fopen("cc/".$filename),"w");
fwrite($open,$html);
fclose($open); 
 citgf_makepublic("cc/".$filename);
echo ereg_replace("</caption>","<br><a href=\"cc/publish.php?session=$session&filename=$filename\">Publish this Page to the NSAA Website</a></caption>",$html);

exit();
?>
