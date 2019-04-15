<?php
/*
This link will populate the links on the MAIN NSAA WEBSITE with the official room assignments. 
*/

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

echo $init_html;
echo GetHeaderJ($session,"statespeech");

$dboffs="$db_name2";
$dbscores="$db_name";

$sql="SELECT rounddate FROM spstaterounds WHERE rounddate!='0000-00-00' LIMIT 1";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$date=split("-",$row[0]);
$year=$date[0];

echO "<br><br><table width=400><caption><b>State Speech Entries/Room Assignments</b><hr>(Be sure these links are added to the Speech Page on the NSAA website once they are correct and complete.)<br><br></caption>";
echO "<tr align=center><td><ul>";

for($c=0;$c<count($classes);$c++)
{
   $class=$classes[$c];
   
   $no=0;
   if($class=="A") $no=1;
   $html=$init_html."<table width=100%><tr align=center><td><table><caption><b>CLASS $class STATE SPEECH ROOM ASSIGNMENTS<br></b>";
   $html.="OFFICIAL (Last Updated ".date("m/d/y")." at ".date("g:ia").")";
   //$html.="(Unofficial)";
   $html.="<hr><br></caption>";
for($i=0;$i<count($spevents2);$i++)
{
   $event=$spevents2[$i];
   if($i>0) $html.="</td>";
   if($i%3==0)
   {
      if($i>0) 
         $html.="</tr>";
      $html.="<tr align=left valign=top>";
   }
   if(($i-1)%3==0) $width="40%";
   else $width="25%";
   $html.="<td width=$width><u><b>".strtoupper($event)."</b></u><br><br>";
   $sql="SELECT id,round,TIME_FORMAT(time,'%l:%i %p') AS time FROM spstaterounds WHERE (round='1' OR round='2') AND class='$class' AND event='$event' ORDER BY round";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $html.="<b>ROUND $row[round]&nbsp;&nbsp;$row[time]<br><br></b>";
      $sql2="SELECT * FROM spstaterooms WHERE roundid='$row[id]' ORDER BY section";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
		  
		  $dono=1;
		if($no==1 && $row2[section]==4){$dono=0;  }
			if($dono==1){
						 $html.="<b>Section $row2[section]</b><br>Room: $row2[room]<br>";
						 $sql3="SELECT offid FROM spstateassign WHERE roomid='$row2[id]'";
						 $result3=mysql_query($sql3);
						 $row3=mysql_fetch_array($result3);
						 if(GetJudgeName($row3[offid])!="NSAA") $judgename=GetJudgeName($row3[offid]);
						 else $judgename="";
							 $html.="Judge $judgename<br>";
						 $sql3="SELECT studentids FROM spshuffle WHERE roomid='$row2[id]'";
							 $result3=mysql_query($sql3);
							 $row3=mysql_fetch_array($result3);
							 $studs=split("/",$row3[0]);
							 for($j=0;$j<count($studs);$j++)
						 {
							if(ereg(",",$studs[$j]))	//drama or duet
							{
							   $dstuds=split(",",$studs[$j]);
							   $studstr="\t"; $studstr2="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							   for($k=0;$k<count($dstuds);$k++)
							   {
							  $sql4="SELECT first,last,semesters FROM $dbscores.eligibility WHERE id='$dstuds[$k]'";
							  $result4=mysql_query($sql4);
							  $row4=mysql_fetch_array($result4);
									  if(ereg("[(]",$row4[first]))
									  {
										 $temp=split("[(]",$row4[first]);
										 $nickname=$temp[1];
										 $nickname=ereg_replace("[)]","",$nickname);
										 $studstr.="$nickname $row4[last], "; $studstr2.="$nickname $row4[last], ";
									  }
									  else
								  {
										 $studstr.="$row4[first] $row4[last], "; $studstr2.="$row4[first] $row4[last], ";
							  }
							   }
							   $studstr=substr($studstr,0,strlen($studstr)-2);
							   $studstr2=substr($studstr2,0,strlen($studstr2)-2);
							}
							else
							{
							   $sql4="SELECT first,last,semesters FROM $dbscores.eligibility WHERE id='$studs[$j]'";
							   $result4=mysql_query($sql4);
							   $row4=mysql_fetch_array($result4);
								   if(ereg("[(]",$row4[first]))
								   {
									  $temp=split("[(]",$row4[first]);
									  $nickname=$temp[1];
									  $nickname=ereg_replace("[)]","",$nickname);
									  $studstr="\t$nickname $row4[last] "; $studstr2="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nickname $row4[last]";
								   }
								   else
							   {
								  $studstr="\t$row4[first] $row4[last]";
								  $studstr2="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$row4[first] $row4[last]";
							   }
							}
							$html.=$studstr2."<br>";
						 }
						 $html.="<br>";
			}		 
      }
   }
}	

$html.="</td></tr></table>".$end_html;
$open=fopen(citgf_fopen("/home/nsaahome/reports/spstateentries$class.html"),"w");
fwrite($open,$html);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/spstateentries$class.html");
echo "<li><a target=\"_blank\" href=\"reports.php?session=$session&filename=spstateentries$class.html\">Class $class Entries/Room Assignments</a><br><br></li>";
}//end for each class
echo "</ul></td></tr></table>";
echo $end_html;

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
