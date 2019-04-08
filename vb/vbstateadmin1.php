<?php
//vbstateadmin1.php: NSAA Volleyball Admin for State Entry Forms
//Created 2/28/09 because e-mailed forms were not being received, possibly due to spam filter
//Updated Oct 2013 to account for new PDF generation
//Author: Ann Gaffigan

require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
//if(!ValidUser($session) || $level!=1)
if(!ValidUser($session) )
{
   header("Location:../index.php");
   exit();
}

if ($classs)
{
header("Location:vbstateadmin1.php?session=$session&viewdistrict=1&class=$classs");
exit();
}

if ($export)
{
	
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=exportdata.csv');
	$output = fopen('php://output', 'w');
	//fputcsv($output, array('School','Class','Mascot','Color','Coach','Win-Loss'));
	fputcsv($output, array('School','Class','Mascot','Color','Coach'));
	
    $sql="SELECT DISTINCT t1.school,t2.class,t2.mascot,t2.colors,t2.coach,t2.sid FROM vb AS t1, vbschool AS t2, headers AS t3 WHERE t1.school=t3.school AND t2.mainsch=t3.id "; 
    if($class && $class!='')
    $sql.=" AND t2.class='$class'";
    $sql.=" ORDER BY t1.school";
	//echo $sql; exit; 
	$result = mysql_query($sql);
	while($row=mysql_fetch_assoc($result))
	{
	//$row[record]=GetWinLoss($row[sid],'vb');
	unset($row[sid]);
	fputcsv($output, $row);
	}
    /* while($row=mysql_fetch_array($result))
	{
	$csv="";
	if($row[mascot]!='') $mascot=$row[mascot];
    if($row[colors]!='') $colors=$row[colors];
    if($row[coach]!='') $coach=$row[coach];
	$csv.="School/Mascot:,".GetSchoolName($row[school],'vb')." $mascot\r\n";
	$csv.="School Colors:,$colors\r\n";
    $csv.="Class:,$class_dist\r\n";
    $csv.="Team Record:,$record\r\n";
	$csv.="Light Jersey #,Dark OR Libero Jersey #,Name,Grade,Height,Position,Digs,Serve Receptions,Ace Serves,Ace Blocks,Kills,Assists\r\n";
	
	$sql="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM vb_state AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$row[school]' OR t1.co_op='$row[school]') AND t1.checked='y' ORDER BY CAST(t1.jersey_lt AS DECIMAL), t1.libero";
    $result=mysql_query($sql); 
	$count=0;
	while($row=mysql_fetch_array($result))
	{
	  if($row[7]=="y")	//that student was checked to be on the roster
	  {

		 if(trim($row[5])!="")
		 {
			$height=ereg_replace("-","'",$row[5]);
			$height.="\"";
		 }
		 $csv.="$row[3],$row[4],$row[first] $row[last],$year,$height,$row[14],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13]\r\n";

		 $count++;
	  }
	}
		$csv.="Games\r\n\"Opponent\",\"W/L\",\"Score\",\"Opp. Score\"\r\n";
		$sched=GetSchedule($row[school],'vb');
		$gamect=0;
		for($i=0;$i<count($sched[oppid]);$i++)
		{
		  if($sched[oppid][$i]!='0')        //only individual games, not tournament names
			 $gamect++;
		}
	   for($i=0;$i<count($sched[oppid]);$i++)
	   {
		  if($sched[oppid][$i]!='0')        //only individual games, not tournament names
		  {
			 $csv.="\"".GetSchoolName($sched[oppid][$i],'vb')."\",\"";
			 $score=split("-",$sched[score][$i]);
			 //$html.="<tr valign=\"bottom\" align=\"left\"><td width=\"100\">".ConfigureSchoolForProgramSchedule(GetSchoolName($sched[oppid][$i],'vb'),35)."</td>";
			 if($score[0]>$score[1]) $csv.="W\",\"";
			 else if($score[1]>$score[0]) $csv.="L\",\"";
			 else $csv.="\",\"";
			 $csv.="$score[0]\",\"$score[1]\"\r\n";
		  }
	   }
	    $sql="SELECT * FROM vbschool WHERE sid='$sid'";
		$result=mysql_query($sql);
		$row=mysql_fetch_array($result);
		//add coaches,etc info to excel file
		$csv.="\r\nHead Coach:,$coach\r\n";
		$csv.="Assistant Coaches:,\"$asst\"\r\n";
		$csv.="NSAA Enrollment:,$enrollment\r\n";
		$csv.="Conference:,\"$conference\"\r\n";
		$csv.="State Tournament Appearances:,\"$row[tripstostate]\"\r\n";
		$csv.="Most Recent State Tournament:,\"$row[mostrecent]\"\r\n";
		$csv.="State Championship Years:,\"$row[championships]\"\r\n";
		$csv.="State Runner-Up Years:,\"$row[runnerup]\"\r\n";
	fwrite($output, $csv); 
	fclose($output); 
 //citgf_makepublic();
	} */
	
    exit;
}

echo $init_html;
echo $header;

//TESTING
//$sql="USE nsaascores20122013";
//$result=mysql_query($sql);

$sport="vb";
$sportname="Volleyball";

$dups=array(); $d=0;



if($save)
{
   $errors="";
   for($i=0;$i<count($sid);$i++)
   {
      if($approvedforprogram[$i]=='x' && $programorder[$i]>0)
      {
	 $sql="UPDATE vbschool SET approvedforprogram='".time()."' WHERE sid='$sid[$i]' AND approvedforprogram=0";
         $result=mysql_query($sql);
         $sql="UPDATE vbschool SET programorder='$programorder[$i]' WHERE sid='$sid[$i]'";
         $result=mysql_query($sql);
      }
      else
      {
         $sql="UPDATE vbschool SET approvedforprogram='0' WHERE sid='$sid[$i]'";
         $result=mysql_query($sql);
	 if($approvedforprogram[$i]=='x')	//Approved it with no order given
	 {
	    $errors.="You approved ".GetSchoolName($sid[$i],$sport)." for the program but did NOT enter an ORDER.<br>";
         }
         $sql="UPDATE vbschool SET programorder='$programorder[$i]' WHERE sid='$sid[$i]'";
         $result=mysql_query($sql);
      }
   }

   $sql="SELECT DISTINCT programorder FROM vbschool WHERE programorder>0";
   $result=mysql_query($sql);
   $ct1=mysql_num_rows($result);
   $sql="SELECT programorder FROM vbschool WHERE programorder>0";
   $result=mysql_query($sql);
   $ct2=mysql_num_rows($result);
   if($ct1!=$ct2)
   {
      $sql="SELECT programorder,COUNT(programorder) FROM vbschool WHERE programorder>0  AND class='$class' GROUP BY programorder";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
	 if($row[1]>1) 
	 {
	    $dups[$d]=$row[0]; $d++;
            $errors.="You have entered <u><b>$row[0]</b></u> under \"PROGRAM ORDER\" for more than one school.<br>";
         }
      }
   }
}
echo "<br><a href=\"../firespring/index.php?session=$session\">&larr; Back to Main Menu</a><br>";
echo "<form method=post action=\"vbstateadmin1.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"sort\" value=\"$sort\">";

echo "<br><table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#a0a0a0 1px solid;\"><caption><b>State $sportname Entry Forms Admin</b><br>";
if($errors && $errors!='')
{
   echo "<div class=\"error\">$errors</div><br><br>";
}
$table=$sport."_state";
if(!$sort) $sort="submitted DESC";
$sql="SELECT DISTINCT school,submitted FROM $table WHERE submitted!=''";
if($sort!="programorder ASC") $sql.=" ORDER BY $sort";
$result=mysql_query($sql);
$statesubmitted=1;
if(mysql_num_rows($result)==0 || $viewdistrict==1)
{
   $statesubmitted=0;
   echo "<br>";
   if(mysql_num_rows($result)==0)
      echo "No STATE forms have been submitted yet.<br><br>";
   echo "Below are schools who have submitted a DISTRICT entry form.";
   if(mysql_num_rows($result)>0) echo "<br>[<a class=small href=\"vbstateadmin1.php?session=$session\">View schools who have submitted a STATE form</a>]";
   echo "<br><br>";
   echo "</caption>";
      //
   echo "<tr><td colspan=3><div class='alert' style=\"text-align:center;\">";
   //echo "<p>Select CLASS to <u>order</u> the teams and <u>approve</u> for the STATE PROGRAM: <select name=\"classs\" onchange=\"submit();\"><option value=\"\">Select Class</option>";
   echo "<p> <select name=\"classs\" onchange=\"submit();\"><option value=\"\">Select Class</option>";
   $sql2="SELECT DISTINCT class FROM vbschool WHERE class!='' ORDER BY class";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      echo "<option value=\"$row2[class]\"";
      if($class==$row2['class']) echo " selected";
      echo ">Class $row2[class]</option>";
   }
   echo "</select>";
   echo "&nbsp;&nbsp;| &nbsp;<a href=\"vbstateadmin1.php?session=$session&class=$class&export=yes\">Download for Excel</a></p></div></td></tr>";
   //    
   $sort="t2.school";
   $sql="SELECT DISTINCT t1.school,t2.sid,t2.filename FROM $sport AS t1, ".$sport."school AS t2, headers AS t3 WHERE t1.school=t3.school AND t2.mainsch=t3.id ORDER BY $sort"; 
   $result=mysql_query($sql);
echo mysql_error();
   echo "<tr align=center><td><a class=small href=\"vbstateadmin1.php?session=$session&sport=$sport&sort=t1.school\">School</a>";
   if($sort=="t1.school") echo "<img style='width:15px;float:right' src='../arrowdown.png' border='0'>";
   echo "</td><td><a class=small href=\"vbstateadmin1.php?session=$session&sport=$sport&sort=t2.filename%20DESC\">Team Photo</a>";
   if($sort=="t2.filename DESC") echo "<img style='width:15px;float:right' src='../arrowup.png' border='0'>";
   echo "</td>";
   echo "<td>Preview PDF</td>";
   echo "</tr>";
}
else
{
   echo "<i>The following State ".GetActivityName($sport)." Entry Forms have been Submitted:</i>";
   echo "<div class='alert' style=\"text-align:center;\">";
   echo "<p>Select CLASS to <u>order</u> the teams and <u>approve</u> for the STATE PROGRAM: <select name=\"class\" onchange=\"submit();\"><option value=\"\">Select Class</option>";
   $sql2="SELECT DISTINCT class FROM vbschool WHERE class!='' ORDER BY class";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      echo "<option value=\"$row2[class]\"";
      if($class==$row2['class']) echo " selected";
      echo ">Class $row2[class]</option>";
   }
   echo "</select>";
   echo "</p></div>";
   echo "<p><a href=\"vbstateadmin1.php?session=$session&viewdistrict=1\">Click Here</a> to view the list of schools with District Entry Forms (with the Team Photo column included).</p>";
   echo "</caption>";
   echo "<tr align=center><td><a class=small href=\"vbstateadmin1.php?session=$session&sport=$sport&sort=school&class=$class\">School</a>";
   if($sort=="school") echo "<img style='width:15px;float:right' src='../arrowdown.png' border='0'>";
   echo "</td><td><b>Submitted Roster</b></td><td><a class=small href=\"vbstateadmin1.php?session=$session&sport=$sport&sort=submitted%20DESC&class=$class\">Date Submitted</a>";
   if($sort=="submitted DESC") echo "<img style='width:15px;float:right' src='../arrowup.png' border='0'>";
   echo "</td>";
   if($class && $class!='')
   {
      echo "<td><a class=small href=\"vbstateadmin1.php?session=$session&sport=$sport&sort=programorder%20ASC&class=$class\">PROGRAM<br>ORDER</a>";
      if($sort=="programorder ASC") echo "<img style='width:15px;float:right' src='../arrowup.png' border='0'>";
      echo "</td><td><b>Approve for<br>STATE PROGRAM</b></td>";
   }
   echo "<td><b>Team Photo</b></td>";
   echo "</tr>";
}
$ix=0;
$schs=array(); $links=array(); $subtimes=array(); $orders=array(); $approved=array(); $photos=array();
while($row=mysql_fetch_array($result))
{
   $sid=GetSID2($row[school],$sport);
   $sql2="SELECT * FROM vbschool WHERE sid='$sid'";
   if($class && $class!='')
      $sql2.=" AND class='$class'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0)
   {
   $row2=mysql_fetch_array($result2);

   $activ=$sportname;
   $activ_lower=strtolower($activ);
   $sch=ereg_replace(" ","",$row[school]);
   $sch=ereg_replace("-","",$sch);
   $sch=ereg_replace("\.","",$sch);
   $sch=ereg_replace("\'","",$sch);
   $sch=strtolower($sch);
   $filename="$sch$activ_lower";
   $filename.="state.csv";

   $schs[$ix]=$row[school];
   $links[$ix]="<a class=small href=\"view_vb.php?school_ch=$row[school]&session=$session\" target=\"_blank\">View Form</a>&nbsp;|&nbsp;<a class=small href=\"view_vb.php?school_ch=$row[school]&session=$session&makepdf=1\" target=\"_blank\">Preview PDF</a>&nbsp;|&nbsp;<a class=small href=\"../attachments.php?session=$session&filename=$filename\">Download for Excel</a>";
   if($statesubmitted==0)
      $links[$ix]="<a class=small href=\"view_vb.php?school_ch=$row[school]&session=$session&viewdistrict=1&makepdf=1\" target=\"_blank\">Preview PDF</a>";
   $subtimes[$ix]=date("m/d/y",$row[submitted])." at ".date("g:ia T",$row[submitted]);
   $photos[$ix]=$row[filename];
   if(mysql_num_rows($result2)==0)
   {
      $orders[$ix]="&nbsp;";
      $approved[$ix]="SCHOOL NOT FOUND IN VB SCHOOLS TABLE";
   }
   else
   {
      $orders[$ix]=$row2[programorder];
      $approved[$ix]=$row2[approvedforprogram];
   }
   $ix++;
   }	//END IF SCHOOL FOUND FOR THIS QUERY
}

//SORT
if($sort=="programorder ASC")
{
   array_multisort($orders,SORT_NUMERIC,SORT_ASC,$schs,$links,$subtimes,$approved);
}

//ELSE ALREADY SORTED
$ix=0;
for($i=0;$i<count($orders);$i++)
{
   $sid=GetSID2($schs[$i],$sport);
   echo "<tr align=left><td>".GetSchoolName($sid,$sport)."</td>";
   if($statesubmitted==1)
   {
   echo "<td>$links[$i]</td><td>$subtimes[$i]</td>";
   $sql2="SELECT approvedforprogram,programorder,filename FROM vbschool WHERE sid='$sid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $photos[$i]=$row2[filename];
   if($class && $class!='' && $statesubmitted==1)
   {
   $sid=GetSID2($schs[$i],$sport);
   $sql2="SELECT approvedforprogram,programorder,filename FROM vbschool WHERE sid='$sid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if(mysql_num_rows($result2)==0)
   {
      echo "<td align=center bgcolor='#ff0000'>SCHOOL NOT FOUND IN VB SCHOOLS TABLE</td>";
   }
   else
   {
      echo "<td align=center";
      for($d=0;$d<count($dups);$d++)
      {
         if($dups[$d]==$row2[programorder]) echo " bgcolor='#ff0000'";
      }
      echo "><input type=text maxlength=3 size=3 name=\"programorder[$ix]\" value=\"$row2[programorder]\"></td>";
      echo "<td align=center";
      if($row2[approvedforprogram]>0) echo " bgcolor='yellow'";
      echo "><input type=hidden name=\"sid[$ix]\" value=\"$sid\"><input type=checkbox name=\"approvedforprogram[$ix]\" value=\"x\"";
      if($row2[approvedforprogram]>0) echo " checked";
      echo ">";
      if($row2[approvedforprogram]>0)
         echo "<br>Approved ".date("m/d/y",$row2[approvedforprogram])." at ".date("g:ia",$row2[approvedforprogram]);
      echo "</td>";
   }
   }	//END IF CLASS SELECTED
   }//end if State forms have been submitted
   echo "<td><a class=small href=\"../downloads/".$photos[$i]."\" target=\"_blank\">$photos[$i]</a></td>";
   if($statesubmitted==0)
      echo "<td>$links[$i]</td>";
   echo "</tr>";
   $ix++;
}
echo "</table>";
if($class && $class!='')
   echo "<input type=submit name=\"save\" value=\"SAVE\">";

echo "</form>";
echo "<br><a href=\"../firespring/index.php?session=$session\">&larr; Back to Main Menu</a><br>";
echo $end_html;
?>
