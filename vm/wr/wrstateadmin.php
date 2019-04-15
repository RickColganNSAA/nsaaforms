<?php
//wrstateadmin.php: NSAA Dual Wrestling Admin for State Program Pages
//1/19/16
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
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}
if ($state_export)
{
	
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=exportdata.csv');
	$output = fopen('php://output', 'w');

	$table="wrd";
	if(!$sort) $sort="submitted DESC";
	$sql="SELECT DISTINCT t1.school,t2.class FROM $table t1, wrschool t2 WHERE t1.school=t2.school AND t1.submitted>0";
	
	if($class && $class!='')
    $sql.=" AND t2.class='$class'";
	$sql.=" ORDER BY t1.$sort";
	//echo $sql; exit;
	$result=mysql_query($sql);

    while($row=mysql_fetch_array($result))
	{
	$sid=GetSID2($row[school],'wr');
	//$sid=$row[sid];
	$sql1="SELECT * FROM wrschool WHERE sid='$sid'";
	$result1=mysql_query($sql1);
	$row1=mysql_fetch_array($result1);
	$class_dist=$row1[0];
	
	$sql2="SELECT name,asst_coaches FROM logins WHERE school='$row[school]' AND sport='Wrestling'";
	$result2=mysql_query($sql2);
	$row2=mysql_fetch_array($result2);
	$coach=$row2[0]; $asst=$row2[1];
	
	$sql_22="SELECT * FROM headers WHERE school='$row[school]'";
	$result_22=mysql_query($sql_22);
	$row_22=mysql_fetch_array($result_22);
	
	$schid=$row_22[id];
	$colors=$row_22[color_names];
	$mascot=$row_22[mascot];
	$enrollment=$row_22[enrollment];
	$conference=$row_22[conference];
	
	//$csv ="School/Mascot:,".GetSchoolName($row[school],'sb')." $mascot\r\n";
	$csv ="School/Mascot:,".$row[school]." $mascot\r\n";
	$csv.="School Colors:,$colors\r\n";
    $csv.="Class:,$class_dist\r\n";
	
	$csv.="Name,Grade,Weight,Wins,Losses\r\n";

    $sql_re="SELECT t1.*, t2.last, t2.first, t2.middle, t2.semesters FROM wrd AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t1.school='$row[school]' OR t1.co_op='$row[school]') AND t1.checked='y' ORDER BY t1.weight,t2.last,t2.first";  

	$result_re=mysql_query($sql_re);
	$count=0;
	while($row_re=mysql_fetch_array($result_re))
	{
/* 	  if($row_re[checked]=="y")	//that student was checked to be on the roster
	  { */
		 $last=$row_re[last];
		 if($row_re[nickname]!='') $first=$row_re[nickname];
		 else $first=$row_re[first];
		 $year=GetYear($row_re[semesters]);
		 $rec=explode("-",$row_re[record]);
		 $csv.="$row_re[first] $row_re[last],$year,$row[weight],$row_re[0],$row_re[1]\r\n";		 
		 $count++;
/* 	  } */
	}
/* 	$studs=explode("<result>",GetPlayers('wr',$row[school]));
	for($s=0;$s<count($studs);$s++)
	{
    $stud=explode("<detail>",$studs[$s]);
	$name = explode("(",$stud[1]);
	$gra = str_replace(')','',$name[1]);
    $csv.="$name[0],$gra\r\n";		
    //$csv.="$stud[1],$year,$row[weight],$row_re[0],$row_re[1]\r\n";		
    } */
	$csv.="Games\r\n\"Opponent\",\"W/L\",\"Score\",\"Opp. Score\"\r\n";
    $sql_sid="SELECT * FROM wrdsched WHERE sid='$sid' ORDER BY received"; 
	$result_sid=mysql_query($sql_sid);
	$i=0;
	while($row_sid=mysql_fetch_array($result_sid))
	{
	   if($sid==$row_sid[sid])
	   {
		  $oppname=GetSchoolName($row_sid[oppid],'wr');
		  $oppscore=$row_sid[oppscore];
		  $sidscore=$row_sid[sidscore];
		  $oppsid=$row_sid[oppid];
	   }
	   else
	   {
		  $oppname=GetSchoolName($row_sid[sid],'wr');
		  $oppscore=$row_sid[sidscore];
		  $sidscore=$row_sid[oppscore];
		  $oppsid=$row_sid[sid];
	   }
	   if($sidscore>$oppscore) $winloss="W";
	   else if($oppscore>$sidscore) $winloss="L";
	   $date=explode("-",$row_sid[received]);   
	   $csv.="\"".$oppname."\",\"";
	   $csv.="$winloss\",\"";
	   $csv.="$sidscore\",\"$oppscore\"\r\n";
	} 

	    $sql_12="SELECT * FROM wrschool WHERE sid='$row[sid]'";
		$result_12=mysql_query($sql_12);
		$row_12=mysql_fetch_array($result_12);
		
		//add coaches,etc info to excel file
		$csv.="\r\nHead Coach:,$coach\r\n";
		$csv.="Assistant Coaches:,\"$asst\"\r\n";
		$csv.="NSAA Enrollment:,$enrollment\r\n";
		$csv.="Conference:,\"$conference\"\r\n";
 		$csv.="State Tournament Appearances:,\"$row1[tripstostate]\"\r\n";
		$csv.="Most Recent State Tournament:,\"$row1[mostrecent]\"\r\n";
		$csv.="State Championship Years:,\"$row1[championships]\"\r\n";
		$csv.="State Runner-Up Years:,\"$row1[runnerup]\"\r\n";  

		$csv.="///////////////////////////////////,/////////////////////////////////\r\n";
		$csv.="    \r\n";
	 fwrite($output, $csv);
	
	} 
	
	fclose($output); 
 //citgf_makepublic();
    exit;
}
echo $init_html;
echo $header;

$sport="wr";
$sportname="Dual Wrestling";

$dups=array(); $d=0;
if($save)
{
   $errors="";
   for($i=0;$i<count($sid);$i++)
   {
      if($approvedforprogram[$i]=='x' && $programorder[$i]>0)
      {
	 $sql="UPDATE wrschool SET approvedforprogram='".time()."' WHERE sid='$sid[$i]' AND approvedforprogram=0";
         $result=mysql_query($sql);
         $sql="UPDATE wrschool SET programorder='$programorder[$i]' WHERE sid='$sid[$i]'";
         $result=mysql_query($sql);
      }
      else
      {
         $sql="UPDATE wrschool SET approvedforprogram='0' WHERE sid='$sid[$i]'";
         $result=mysql_query($sql);
	 if($approvedforprogram[$i]=='x')	//Approved it with no order given
	 {
	    $errors.="You approved ".GetSchoolName($sid[$i],$sport)." for the program but did NOT enter an ORDER.<br>";
         }
         $sql="UPDATE wrschool SET programorder='$programorder[$i]' WHERE sid='$sid[$i]'";
         $result=mysql_query($sql);
      }
   }

   $sql="SELECT DISTINCT programorder FROM wrschool WHERE programorder>0";
   $result=mysql_query($sql);
   $ct1=mysql_num_rows($result);
   $sql="SELECT programorder FROM wrschool WHERE programorder>0";
   $result=mysql_query($sql);
   $ct2=mysql_num_rows($result);
   if($ct1!=$ct2)
   {
      $sql="SELECT programorder,COUNT(programorder) FROM wrschool WHERE programorder>0  AND class='$class' GROUP BY programorder";
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

echo "<form method=post action=\"wrstateadmin.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"sort\" value=\"$sort\">";

echo "<br><table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#a0a0a0 1px solid;\"><caption><b>$sportname Roster Forms Admin</b><br>";
if($errors && $errors!='')
{
   echo "<div class=\"error\">$errors</div><br><br>";
}
$table=$sport."d";
if(!$sort) $sort="submitted DESC";
$sql="SELECT DISTINCT school,submitted FROM $table WHERE submitted>0";
if($sort!="programorder ASC") $sql.=" ORDER BY $sort";
$result=mysql_query($sql);
$statesubmitted=1;

   echo "<div class='alert' style=\"text-align:center;\">";
   echo "<p>Select CLASS to <u>order</u> the teams and <u>approve</u> for the STATE PROGRAM: <select name=\"class\" onchange=\"submit();\"><option value=\"\">Select Class</option>";
   $sql2="SELECT DISTINCT class FROM wrschool WHERE class!='' ORDER BY class";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      echo "<option value=\"$row2[class]\"";
      if($class==$row2['class']) echo " selected";
      echo ">Class $row2[class]</option>";
   }
   echo "</select>";
   echo "&nbsp;&nbsp;| &nbsp;<a href=\"wrstateadmin.php?session=$session&class=$class&state_export=yes\">Download for Excel</a></p></div></td></tr>";   
   echo "</p></div>";
   echo "</caption>";
   echo "<tr align=center><td><a class=small href=\"wrstateadmin.php?session=$session&sport=$sport&sort=school&class=$class\">School</a>";
   if($sort=="school") echo "<img style='width:15px;float:right' src='../arrowdown.png' border='0'>";
   echo "</td><td><b>Submitted Roster</b></td><td><a class=small href=\"wrstateadmin.php?session=$session&sport=$sport&sort=submitted%20DESC&class=$class\">Date Submitted (or Updated)</a>";
   if($sort=="submitted DESC") echo "<img style='width:15px;float:right' src='../arrowup.png' border='0'>";
   echo "</td>";
   if($class && $class!='')
   {
      echo "<td><a class=small href=\"wrstateadmin.php?session=$session&sport=$sport&sort=programorder%20ASC&class=$class\">PROGRAM<br>ORDER</a>";
      if($sort=="programorder ASC") echo "<img style='width:15px;float:right' src='../arrowup.png' border='0'>";
      echo "</td><td><b>Approve for<br>STATE PROGRAM</b></td>";
   }
   echo "<td><b>Team Photo</b></td>";
   echo "</tr>";

$ix=0;
$schs=array(); $links=array(); $subtimes=array(); $orders=array(); $approved=array(); $photos=array();
while($row=mysql_fetch_array($result))
{
   $sid=GetSID2($row[school],$sport);
   $sql2="SELECT * FROM wrschool WHERE sid='$sid'";
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
   $links[$ix]="<a class=small href=\"view_wrd.php?school_ch=$row[school]&session=$session\" target=\"_blank\">View Form</a>&nbsp;|&nbsp;<a class=small href=\"view_wrd.php?school_ch=$row[school]&session=$session&makepdf=1\" target=\"_blank\">Preview PDF</a>&nbsp;|&nbsp;<a class=small href=\"../attachments.php?session=$session&filename=$filename\">Download for Excel</a>";
   if($statesubmitted==0)
      $links[$ix]="<a class=small href=\"view_wrd.php?school_ch=$row[school]&session=$session&viewdistrict=1&makepdf=1\" target=\"_blank\">Preview PDF</a>";
   $subtimes[$ix]=date("m/d/y",$row[submitted])." at ".date("g:ia T",$row[submitted]);
   $photos[$ix]=$row[filename];
   if(mysql_num_rows($result2)==0)
   {
      $orders[$ix]="&nbsp;";
      $approved[$ix]="SCHOOL NOT FOUND IN WR SCHOOLS TABLE";
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
   $sql2="SELECT approvedforprogram,programorder,filename FROM wrschool WHERE sid='$sid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $photos[$i]=$row2[filename];
   if($class && $class!='' && $statesubmitted==1)
   {
   $sid=GetSID2($schs[$i],$sport);
   $sql2="SELECT approvedforprogram,programorder,filename FROM wrschool WHERE sid='$sid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if(mysql_num_rows($result2)==0)
   {
      echo "<td align=center bgcolor='#ff0000'>SCHOOL NOT FOUND IN WR SCHOOLS TABLE</td>";
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

echo $end_html;
?>
