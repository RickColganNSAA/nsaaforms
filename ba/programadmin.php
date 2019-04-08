<?php
/****************************************
programadmin.php
Cornerstone Admin for BA
9/26/13
Ann Gaffigan
*****************************************/

require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//TESTING
$sql="USE nsaascores";
$result=mysql_query($sql);

//verify user
if(!ValidUser($session))
{
   //header("Location:../index.php");
   echo $session;
   exit();
}

$sport="ba";
$sportname=GetActivityName($sport);

echo $init_html;
echo $header;

//TESTING
//$sql="USE nsaascores20122013";
//$result=mysql_query($sql);

$dups=array(); $d=0;
if($save)
{
   $errors="";
   for($i=0;$i<count($sid);$i++)
   {
      if($approvedforprogram[$i]=='x' && $programorder[$i]>0)
      {
	 $sql="UPDATE baschool SET approvedforprogram='".time()."' WHERE sid='$sid[$i]' AND approvedforprogram=0";
         $result=mysql_query($sql);
         $sql="UPDATE baschool SET programorder='$programorder[$i]' WHERE sid='$sid[$i]'";
         $result=mysql_query($sql);
      }
      else
      {
         $sql="UPDATE baschool SET approvedforprogram='0' WHERE sid='$sid[$i]'";
         $result=mysql_query($sql);
	 if($approvedforprogram[$i]=='x')	//Approved it with no order given
	 {
	    $errors.="You approved ".GetSchoolName($sid[$i],$sport)." for the program but did NOT enter an ORDER.<br>";
         }
         $sql="UPDATE baschool SET programorder='$programorder[$i]' WHERE sid='$sid[$i]'";
         $result=mysql_query($sql);
      }
   }

   $sql="SELECT DISTINCT programorder FROM baschool WHERE programorder>0";
   $result=mysql_query($sql);
   $ct1=mysql_num_rows($result);
   $sql="SELECT programorder FROM baschool WHERE programorder>0";
   $result=mysql_query($sql);
   $ct2=mysql_num_rows($result);
   if($ct1!=$ct2)
   {
      $sql="SELECT programorder,COUNT(programorder) FROM baschool WHERE programorder>0  AND class='$class' GROUP BY programorder";
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

echo "<br><a href=\"../cornerstone/index.php?session=$session\">&larr; Back to Main Menu</a><br>";
echo "<form method=post action=\"programadmin.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"sort\" value=\"$sort\">";

echo "<br><table class='nine' cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#a0a0a0 1px solid;\"><caption><b>State $sportname Entry Forms Admin</b><br>";
if($errors && $errors!='')
{
   echo "<div class=\"error\">$errors</div><br><br>";
}
$table=$sport."_state";
if(!$sort) $sort="submitted DESC";
$sql="SELECT DISTINCT school,submitted FROM $table WHERE submitted!=''";
if($sort!="programorder ASC") $sql.=" ORDER BY $sort";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
   echo "</caption><tr align=center><td><br>No forms have been submitted yet.<br><br></td></tr>";
else
{
   echo "<i>The following State ".GetActivityName($sport)." Entry Forms have been Approved by the NSAA:</i>";
   echo "<div class='alert' style=\"text-align:center;\">";
   echo "<p>Select CLASS to Download PDF's for the STATE PROGRAM: <select name=\"class\" onchange=\"submit();\"><option value=\"\">Select Class</option>";
   $sql2="SELECT DISTINCT class FROM baschool WHERE class!='' ORDER BY class";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      echo "<option value=\"$row2[class]\"";
      if($class==$row2['class']) echo " selected";
      echo ">Class $row2[class]</option>";
   }
   echo "</select>";
   echo "</p></div>";
   echo "</caption>";
   echo "<tr align=center><td><a class=small href=\"programadmin.php?session=$session&sport=$sport&sort=school&class=$class\">School</a>";
   if($sort=="school") echo "<img style='width:15px;float:right' src='../arrowdown.png' border='0'>";
   echo "</td>";
   if($class && $class!='') echo "<td><b>Submitted Roster</b></td>";
   echo "<td><a class=small href=\"programadmin.php?session=$session&sport=$sport&sort=submitted%20DESC&class=$class\">Date Submitted</a>";
   if($sort=="submitted DESC") echo "<img style='width:15px;float:right' src='../arrowup.png' border='0'>";
   echo "</td>";
   if($class && $class!='')
   {
      echo "<td><a class=small href=\"programadmin.php?session=$session&sport=$sport&sort=programorder%20ASC&class=$class\">PROGRAM<br>ORDER</a>";
      if($sort=="programorder ASC") echo "<img style='width:15px;float:right' src='../arrowup.png' border='0'>";
      echo "</td>";
   }
   echo "<td><b>Approve for<br>STATE PROGRAM</b></td>";
   echo "</tr>";
}
$ix=0;
$schs=array(); $links=array(); $subtimes=array(); $orders=array(); $approved=array();
while($row=mysql_fetch_array($result))
{
   $sid=GetSID2($row[school],$sport);
   $sql2="SELECT * FROM baschool WHERE sid='$sid'";
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
   $links[$ix]="<a href=\"view_ba.php?school_ch=$row[school]&session=$session&makepdf=1\" target=\"_blank\">Preview PDF</a>";
   $subtimes[$ix]=date("m/d/y",$row[submitted])." at ".date("g:ia T",$row[submitted]);
   if(mysql_num_rows($result2)==0)
   {
      $orders[$ix]="&nbsp;";
      $approved[$ix]="SCHOOL NOT FOUND IN BA SCHOOLS TABLE";
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
   $sql2="SELECT approvedforprogram,programorder FROM baschool WHERE sid='$sid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if($row2[approvedforprogram]>0)
   {
   echo "<tr align=left><td>".GetSchoolName($sid,$sport)."</td>";
   if($class && $class!='') echo "<td>$links[$i]</td>";
   echo "<td>$subtimes[$i]</td>";
   if(mysql_num_rows($result2)==0)
   {
      echo "<td align=center bgcolor='#ff0000'>SCHOOL NOT FOUND IN BA SCHOOLS TABLE</td>";
   }
   else
   {
      if($class && $class!='')
      {
      echo "<td align=center";
      for($d=0;$d<count($dups);$d++)
      {
         if($dups[$d]==$row2[programorder]) echo " bgcolor='#ff0000'";
      }
      echo "><input type=text maxlength=3 size=3 name=\"programorder[$ix]\" value=\"$row2[programorder]\"></td>";
      }	//END IF CLASS
      echo "<td align=center";
      if($row2[approvedforprogram]>0) echo " bgcolor='yellow'";
      if($row2[approvedforprogram]>0)
         echo ">Approved ".date("m/d/y",$row2[approvedforprogram])." at ".date("g:ia",$row2[approvedforprogram]);
      else echo ">Not Yet Approved";
      echo "</td>";
   }
   echo "</tr>";
   $ix++;
   }
}
echo "</table>";
   //echo "<input type=submit name=\"save\" value=\"SAVE\">";

echo "</form>";

echo "<br><a href=\"../cornerstone/index.php?session=$session\">&larr; Back to Main Menu</a><br>";

echo $end_html;
?>
