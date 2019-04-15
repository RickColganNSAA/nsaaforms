<?php
//ppstateadmin.php: NSAA Play Production Admin for Entry Forms
//Created 11/4/13, adapted from same report for VB
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
if ($export)
{
	
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=exportdata.csv');
	$output = fopen('php://output', 'w');

	$sport="pp";
	if(!$sort || $sort=='') $sort="t2.school";
    $sql="SELECT DISTINCT t1.school,t2.sid,t2.filename,t2.class FROM $sport AS t1, ".$sport."school AS t2, headers AS t3 WHERE t1.school=t3.school AND t2.mainsch=t3.id "; 
	if($class && $class!='')
    $sql.=" AND t2.class='$class'";
	$sql.=" ORDER BY t1.statequalifier DESC,t1.school";
    $result=mysql_query($sql);
	//echo $sql; exit;
	$result=mysql_query($sql);

    while($row0=mysql_fetch_array($result))
	{
	   $school=GetMainSchoolName($row0[sid],$sport);
	   $sid=$row0[sid];
	   $schoolid=$row0[mainsch];
	   $school2=addslashes($school);
	   $teamphoto=$row0[filename]; 
	   $schoolname=$row0[school]; $class=$row0['class'];
	
	   $sql2="SELECT * FROM pp WHERE school='$school2'";
	   $result2=mysql_query($sql2);
	   $row2=mysql_fetch_array($result2);
	   if(!empty($row2[short_title]))
	   $title=$row2[short_title]; 
	   else
	   $title=$row2[title]; 
	   $playwright=$row2[playwright];
	   $director=$row2[director];
		
		//$csv ="School/Mascot:,".GetSchoolName($row[school],'sb')." $mascot\r\n";
		$csv ="School:,".$schoolname." \r\n";
		$csv.="Title:,$title\r\n";
		$csv.="Written By:,$playwright\r\n";
		$csv.="Class:,$row0[class]\r\n";
		
		//Superintendent
      $sql_s="SELECT name FROM logins WHERE school='$school2' AND sport='Superintendent'";
      $result_s=mysql_query($sql_s);
      $row_s=mysql_fetch_array($result_s);
      if(trim($row_s[name])!='') $csv.="Superintendent:, $row_s[name]\r\n";
       
	   //Principal
      $sql_p="SELECT name FROM logins WHERE school='$school2' AND sport='Principal'";
      $result_p=mysql_query($sql_p);
      $row_p=mysql_fetch_array($result_p);
      $csv.="Principal:, $row_p[name]\r\n";
      $csv.="Director:, $director\r\n";
	  
	  $sql_c="SELECT name, asst_coaches FROM logins WHERE school='$school2' AND sport='Play Production'";
      $result_c=mysql_query($sql_c);
      $row_c=mysql_fetch_array($result_c);
      $coach=$row_c[0];
      $asst=$row_c[1];
	  if(trim($asst)!='') $csv.="Assistant Directors:, $asst\r\n";

	$csv.="Cast:\r\n";
    //GET CAST
    $cast="";
    $sql2_="SELECT DISTINCT part FROM pp_students WHERE (school='$school2' OR co_op='$school2') AND (crew='' OR crew IS NULL) ORDER BY partorder";
    $result2_=mysql_query($sql2_);
  	//CALCULATE "Per column" NUMBER BY number of names, not parts
	//$sql3="SELECT part FROM $studtable WHERE (school='$school2' OR co_op='$school2') AND (crew='' OR crew IS NULL) ORDER BY partorder";
	//$result3=mysql_query($sql3);
	$ct=mysql_num_rows($result2_);
     while($row_2=mysql_fetch_array($result2_))
     {
      $sql3="SELECT * FROM pp_students WHERE (school='$school2' OR co_op='$school2') AND part='".addslashes($row_2[part])."' ORDER BY partorder";
      $result3=mysql_query($sql3);
      $names=""; $namect=0;
      while($row3=mysql_fetch_array($result3))
      {
         $names.=GetStudentInfo($row3[student_id],FALSE).", "; $namect++;
      }
      if($names!='') $names=substr($names,0,strlen($names)-2);
      $names = str_replace (',','  ',$names) ;
      $row_2[part] = str_replace (',','  ',$row_2[part]) ;
      $csv.="$row_2[part]:, $names\r\n";

     }
     $csv.="\r\n";
	 $csv.="\r\n";


	 fwrite($output, $csv);
	
	} 
	
	fclose($output); 
 //citgf_makepublic();
    exit;
}
echo $init_html;
echo $header;

//TESTING
//$sql="USE nsaascores20142015";
//$result=mysql_query($sql);

$sport="pp";
$sportname="Play Production";

$dups=array(); $d=0;
if($save)
{
   $errors="";
   for($i=0;$i<count($sid);$i++)
   {
      if($approvedforprogram[$i]=='x' && $programorder[$i]>0)
      {
	 $sql="UPDATE ".$sport."school SET approvedforprogram='".time()."' WHERE sid='$sid[$i]' AND approvedforprogram=0";
         $result=mysql_query($sql);
         $sql="UPDATE ".$sport."school SET programorder='$programorder[$i]' WHERE sid='$sid[$i]'";
         $result=mysql_query($sql);
      }
      else
      {
         $sql="UPDATE ".$sport."school SET approvedforprogram='0' WHERE sid='$sid[$i]'";
         $result=mysql_query($sql);
	 if($approvedforprogram[$i]=='x')	//Approved it with no order given
	 {
	    $errors.="You approved ".GetSchoolName($sid[$i],$sport)." for the program but did NOT enter an ORDER.<br>";
         }
         $sql="UPDATE ".$sport."school SET programorder='$programorder[$i]' WHERE sid='$sid[$i]'";
         $result=mysql_query($sql);
      }
   }

   $sql="SELECT DISTINCT programorder FROM ".$sport."school WHERE programorder>0";
   $result=mysql_query($sql);
   $ct1=mysql_num_rows($result);
   $sql="SELECT programorder FROM ".$sport."school WHERE programorder>0";
   $result=mysql_query($sql);
   $ct2=mysql_num_rows($result);
   if($ct1!=$ct2)
   {
      $sql="SELECT programorder,COUNT(programorder) FROM ".$sport."school WHERE programorder>0  AND class='$class' GROUP BY programorder";
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

echo "<form method=post action=\"".$sport."stateadmin.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"sort\" value=\"$sort\">";

echo "<br><table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#a0a0a0 1px solid;\"><caption><b>State $sportname Entry Forms Admin</b><br>";
if($errors && $errors!='')
{
   echo "<div class=\"error\">$errors</div><br><br>";
}
$table=$sport;
if(!$sort) $sort="statequalifier DESC,school";
$sql="SELECT * FROM $table WHERE statequalifier='x'";
if($sort!="programorder ASC") $sql.=" ORDER BY $sort";
$result=mysql_query($sql);
$statesubmitted=1;
if(mysql_num_rows($result)==0 || $viewdistrict==1)
{
   $statesubmitted=0;
   echo "<br>";
   if(mysql_num_rows($result)==0)
      echo "No schools have been marked as a STATE QUALIFIER yet.<br><br>";
   echo "Below are schools who have entered anything on their $sportname entry form.<br><br>";
   if(!$sort || $sort=='') $sort="t2.school";
   $sql="SELECT DISTINCT t1.school,t2.sid,t2.filename FROM $sport AS t1, ".$sport."school AS t2, headers AS t3 WHERE t1.school=t3.school AND t2.mainsch=t3.id ORDER BY $sort"; 
   $result=mysql_query($sql);
echo mysql_error();
}
else
{
   echo "<i>The following schools have been marked as State $sportname Qualifiers:</i>";
   if($class && $class!='')
      echo "<p><a href=\"".$sport."stateadmin.php?session=$session&viewdistrict=1\">Click Here</a> to view the list of schools with information on their Entry Forms (with the Cast & Crew Photo column included).</p>";
}
   echo "<div class='alert' style=\"text-align:center;\">";
   echo "<p>Select CLASS to <u>order</u> the schools and <u>approve</u> for the STATE PROGRAM: <select name=\"class\" onchange=\"submit();\"><option value=\"\">Select Class</option>";
   $sql2="SELECT DISTINCT class FROM ".$sport."school WHERE class!='' ORDER BY class";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      echo "<option value=\"$row2[class]\"";
      if($class==$row2['class']) echo " selected";
      echo ">Class $row2[class]</option>";
   }
   echo "</select>";
   echo "&nbsp;&nbsp;| &nbsp;<a href=\"ppstateadmin.php?session=$session&class=$class&export=yes\">Download for Excel</a></p></div></td></tr>";   
   echo "</p></div>";
   echo "</caption>";
//COLUMN HEADERS
   echo "<tr align=center><td><a class=small href=\"".$sport."stateadmin.php?session=$session&sport=$sport&sort=t1.school\">School</a>";
   if($sort=="t1.school") echo "<img style='width:15px;float:right' src='../arrowdown.png' border='0'>";
   echo "</td><td><b>State Qualifier</b>";
   if($sort=="statequalifier DESC,school") echo "<img style='width:15px;float:right' src='../arrowup.png' border='0'>";
   echo "</td><td>Name of Play</td><td><a class=small href=\"".$sport."stateadmin.php?session=$session&sport=$sport&sort=t2.filename%20DESC\">Cast & Crew Photo</a>";
   if($sort=="t2.filename DESC") echo "<img style='width:15px;float:right' src='../arrowup.png' border='0'>";
   echo "</td>";
   echo "<td>Preview PDF</td>";
   if($class && $class!='')
   {
      echo "<td><a class=small href=\"".$sport."stateadmin.php?session=$session&sport=$sport&sort=programorder%20ASC&class=$class\">PROGRAM<br>ORDER</a>";
      if($sort=="programorder ASC") echo "<img style='width:15px;float:right' src='../arrowup.png' border='0'>";
      echo "</td><td><b>Approve for<br>STATE PROGRAM</b></td>";
   }
   echo "</tr>";
$ix=0;
$schs=array(); $links=array(); $statequals=array(); $orders=array(); $approved=array(); $photos=array();
while($row=mysql_fetch_array($result))
{
   $sid=GetSID2($row[school],$sport);
   $sql2="SELECT * FROM ".$sport."school WHERE sid='$sid'";
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
   if(!empty($row[short_title]))
   $plays[$ix]=$row[short_title]."<br>written by $row[playwright]";
   else
   $plays[$ix]=$row[title]."<br>written by $row[playwright]";
   $links[$ix]="<a class=small href=\"view_".$sport.".php?school_ch=$row[school]&session=$session\" target=\"_blank\">View Form</a>&nbsp;|&nbsp;<a class=small href=\"view_".$sport.".php?school_ch=$row[school]&session=$session&makepdf=1\" target=\"_blank\">Preview PDF</a>&nbsp;|&nbsp;<a class=small href=\"../attachments.php?session=$session&filename=$filename\">Download for Excel</a>";
   if($statesubmitted==0)
      $links[$ix]="<a class=small href=\"view_".$sport.".php?school_ch=$row[school]&session=$session&viewdistrict=1&makepdf=1\" target=\"_blank\">Preview PDF</a>";
   $statequals[$ix]=$row[statequalifier];
   $photos[$ix]=$row[filename];
   if(mysql_num_rows($result2)==0)
   {
      $orders[$ix]="&nbsp;";
      $approved[$ix]="SCHOOL NOT FOUND IN $sportname SCHOOLS TABLE";
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
   array_multisort($orders,SORT_NUMERIC,SORT_ASC,$schs,$links,$statequals,$approved,$plays);
}

//ELSE ALREADY SORTED
$ix=0;
for($i=0;$i<count($orders);$i++)
{
   $sid=GetSID2($schs[$i],$sport);
   echo "<tr align=left><td>".GetSchoolName($sid,$sport)."</td><td>$statequals[$i]</td><td>$plays[$i]</td>";
   $sql2="SELECT approvedforprogram,programorder,filename FROM ".$sport."school WHERE sid='$sid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $photos[$i]=$row2[filename];
   echo "<td><a class=small href=\"../downloads/".$photos[$i]."\" target=\"_blank\">$photos[$i]</a></td>";
   echo "<td>$links[$i]</td>";
   if($class && $class!='')
   {
      if(mysql_num_rows($result2)==0)
         echo "<td align=center bgcolor='#ff0000'>SCHOOL NOT FOUND IN $sportname SCHOOLS TABLE</td>";
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
   echo "</tr>";
   $ix++;
}
echo "</table>";
if($class && $class!='')
   echo "<input type=submit name=\"save\" value=\"SAVE\">";

echo "</form>";

echo $end_html;
?>
