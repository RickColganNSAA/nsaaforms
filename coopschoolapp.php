<?php
/********************************************************
coopschoolapp.php
Agreement for Cooperative Sponsorship - form for single
school to fill out (each school in coop needs to fill out
this form before entire form can be submitted by the
head school)
Created 7/16/12
Author: Ann Gaffigan
*********************************************************/

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

$level=GetLevel($session);

//verify user
if(!ValidUser($session) || $level>2)
{
   header("Location:index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$schoolid)
{
   $schoolid=GetSchoolID($session);
   if($level==1) $schoolid=1616;        //Test's School
}
else if($level!=1 && $schoolid!=GetSchoolID($session))	//IF THIS IS THE CASE, MUST BE HEAD SCHOOL LOOKING AT COOP SCHOOL'S FORM
{
   $sql="SELECT * FROM coopapp WHERE id='$coopappid' AND schoolid1='".GetSchoolID($session)."' AND (schoolid2='$schoolid' OR schoolid3='$schoolid' OR schoolid4='$schoolid4')";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      echo $init_html;
      echo "<table width='100%'><tr align=center><td><br><br><div class='error' style='width:400px;'>ERROR: Sorry, you don't have permission to access this form.</div>";
         echo "<br><br><a href=\"javascript:window.close();\">Close Window</a>";
      echo $end_html;
      exit();
   }
   else	//THIS IS OK BUT PRINT ONLY AND CHECK TO SEE IF ANYTHING HAS EVEN BEEN FILLED OUT/SUBMITTED YET
   {
      $print=1;
      $sql="SELECT * FROM coopschoolapp WHERE coopappid='$coopappid' AND schoolid='$schoolid' AND datesub>0";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)
      {
         echo $init_html;
         echo "<table width='100%'><tr align=center><td><br><br><div class='error' style='width:400px;'>".GetSchool2($schoolid)." has not yet filled out their school's individual cooperative sponsorship form and resolution.</div>";
         echo "<br><br><a href=\"javascript:window.close();\">Close Window</a>";
         echo $end_html;
         exit();
      }
      else
      {
         $row=mysql_fetch_array($result);
	 $coopschoolappid=$row[id];
      }
   }
}

//GET MAIN APP AND SCHOOL APP INFO
        //SCHOOL APP (if it's been saved already)
if($coopschoolappid)
   $sql="SELECT * FROM coopschoolapp WHERE id='$coopschoolappid'";
else
   $sql="SELECT * FROM coopschoolapp WHERE coopappid='$coopappid' AND schoolid='$schoolid'";
$result=mysql_query($sql);
$schapp=mysql_fetch_array($result);
if(!$coopappid) $coopappid=$schapp[id];
if(!$schoolid) $schoolid=$schapp[schoolid];
        //MAIN APP
$sql="SELECT * FROM coopapp WHERE id='$coopappid'";
$result=mysql_query($sql);
$app=mysql_fetch_array($result);

if((($level==1 && $edit!=1) || $schoolid==GetSchoolID($session)) && $coopappid && $schoolid)	//CHECK AND SEE IF WE CAN GET A $coopschoolappid AND IF IT SHOULD BE READ-ONLY (ALREADY BEEN SUBMITTED)
{
      $sql="SELECT * FROM coopschoolapp WHERE coopappid='$coopappid' AND schoolid='$schoolid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $coopschoolappid=$row[id];
      if($row[datesub]>0 && $schoolid!=$app[schoolid1]) $print=1;
}
if($level==1 || ($print==1 && $saved!=1)) $header="<table width='100%'><tr align=center><td>";
else $header=GetHeader($session);
$school=GetSchool2($schoolid);
$school2=ereg_replace("\'","\'",$school);
//GET SCHOOL YEAR - Since Spring activities due by January 1, $year = date("Y") always, except for ON January 1
$year1=date("Y");
if(date("m")==1 && date("j")==1) $year1--;
$year2=$year1+1;
$year3=$year2+1;

if($save)	//SAVE SCHOOL FORM
{
   //UPDATE/INSERT INTO DATABASE:
   $sql="SELECT * FROM coopschoolapp WHERE coopappid='$coopappid' AND schoolid='$schoolid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//INSERT
   {
      if($app[renewal]=='x')
         $sql="INSERT INTO coopschoolapp (coopappid,schoolid,boardmember1,datesub) VALUES ('$coopappid','$schoolid','".addslashes($boardmember1)."','".time()."')";
      else	//NON RENEWAL
      {
      $sql="INSERT INTO coopschoolapp (coopappid,schoolid,";
      for($grade=9;$grade<=12;$grade++)
      {
	 for($i=1;$i<=3;$i++)
	 {
            $var1="enrollg".$grade."_";
            $var2="enrollb".$grade."_";
            //$var3="participateg".$grade."_";
            //$var4="participateb".$grade."_";
	    $var1.=$i; $var2.=$i; //$var3.=$i; $var4.=$i;
	    $sql.="$var1,$var2, "; //$var3,$var4, ";
	 }
      }
      $sql.="didnotsponsor,boardmember1,boardmember2,infavor,against,boardchair,boardclerk,datesub) VALUES ('$coopappid','$schoolid',";
      for($grade=9;$grade<=12;$grade++)
      {
         for($i=1;$i<=3;$i++)
         {
            $var1="enrollg".$grade."_";
            $var2="enrollb".$grade."_";
            //$var3="participateg".$grade."_";
            //$var4="participateb".$grade."_";
            $var1.=$i; $var2.=$i; //$var3.=$i; $var4.=$i;
            $sql.="'".$$var1."','".$$var2."', "; //'".$$var3."','".$$var4."', ";
         }
      }
      $sql.="'$didnotsponsor','".addslashes($boardmember1)."','".addslashes($boardmember2)."','".addslashes($infavor)."','".addslashes($against)."','".addslashes($boardchair)."','".addslashes($boardclerk)."','".time()."')";
      }
      $result=mysql_query($sql);
      $coopschoolappid=mysql_insert_id();
   }
   else	//UPDATE
   {
      $row=mysql_fetch_array($result);
      $sql="UPDATE coopschoolapp SET ";
      if($app[renewal]=='x') $sql.="boardmember1='".addslashes($boardmember1)."'";
      else
      {
      for($grade=9;$grade<=12;$grade++)
      {
         for($i=1;$i<=3;$i++)
         {
            $var1="enrollg".$grade."_";
            $var2="enrollb".$grade."_";
            //$var3="participateg".$grade."_";
            //$var4="participateb".$grade."_";
            $var1.=$i; $var2.=$i; //$var3.=$i; $var4.=$i;
            $sql.="$var1='".$$var1."',$var2='".$$var2."', "; //$var3='".$$var3."',$var4='".$$var4."', ";
         }
      }
      $sql.="didnotsponsor='$didnotsponsor',boardmember1='".addslashes($boardmember1)."',boardmember2='".addslashes($boardmember2)."',infavor='".addslashes($infavor)."',against='".addslashes($against)."',boardchair='".addslashes($boardchair)."',boardclerk='".addslashes($boardclerk)."'";
      }	//END NOT RENEWAL
      $sql.=" WHERE id='$row[id]'";
      $result=mysql_query($sql);
      
         //UPDATE datesub IF IT IS CURRENTLY 0
         $sql="UPDATE coopschoolapp SET datesub='".time()."' WHERE datesub=0 AND id='$row[id]'";
         $result=mysql_query($sql);
         $coopschoolappid=$row[id];
   }
   
   //CHECK FOR SQL ERROR:
   if(mysql_error())
   {
      echo $init_html;
      echo $header;
      echo "<br><br>";
      echo "<div class='error'><p>The following query:</p><p>$sql</p><p>had the following ERROR:</p><p>".mysql_error()."</p><p>Please <a href=\"javascript:history.go(-1);\">Go Back</a> and try again or report this problem to the NSAA/programmer.</p></div>";
      echo $end_html;
      exit();
   }
   else
   {
      header("Location:coopschoolapp.php?session=$session&schoolid=$schoolid&coopappid=$coopappid&coopschoolappid=$coopschoolappid&saved=1&edit=$edit");
      exit();
   }
}

echo $init_html;
echo $header;

echo "<form method=post action=\"coopschoolapp.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"edit\" value=\"$edit\">";
echo "<input type=hidden name=\"schoolid\" value=\"$schoolid\">";
echo "<input type=hidden name=\"coopappid\" value=\"$coopappid\">";
echo "<input type=hidden name=\"coopschoolappid\" value=\"$coopschoolappid\">";

if($print!=1 || $saved==1)
{
   if($schoolid==$app[schoolid1] && $level!=1)  //HEAD SCHOOL - LINK TO MAIN FORM
         echo "<br><p><a href=\"coopapp.php?session=$session&schoolid=$schoolid&coopappid=$coopappid\">Return to Main Application Form</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
   else if($level!=1)
      echo "<br><p>";
   if($level!=1)
      echo "<a href=\"coopappindex.php?session=$session\">Return to Coops Main Menu</a></p>";
}

if($continue)	//SHOW CONFIRMATION OF SUCCESSFULY SAVE FOR HEAD SCHOOL
{
   echo "<br><div class='alert' style=\"width:600px;\">The main application form was successfully saved. Please complete the following form, which is required of each school in the proposed cooperative agreement.</div>";
}
else if($saved)
{
   echo "<br><div class='alert' style=\"width:450px;\">The Cooperative Sponsorship form for your school (below) has been saved.</div>";
}

//ARE THERE ERRORS?
if(GetCoopSchoolAppErrors($coopappid,$schoolid)!='')
{
   //YES:
   echo "<div class='error' style='width:650px;'>The following errors must be fixed before you can submit this resolution:<br><br>".GetCoopSchoolAppErrors($coopappid,$schoolid)."</div>";
   $highlighterrors=1;
   $edit=1; $print=0;
}

echo "<br><table style=\"width:700px;\" class='nine' cellspacing=0 cellpadding=3><caption><b>COOPERATIVE SPONSORSHIP:</b><br><p><i>The following information is to be provided by each school before the application form can be submitted to the NSAA.</i></p>";
if(($level==1 || $schoolid==$app[schoolid1]) && $edit!=1 && $schapp[datesub]>0)
   echo "<p><a href=\"coopschoolapp.php?session=$session&coopschoolappid=$coopschoolappid&coopappid=$coopappid&schoolid=$schoolid&edit=1\">EDIT THIS FORM</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"javascript:window.close();\">CLOSE WINDOW</a></p>";
echo "</caption>";

echo "<tr align=left><td><h2 style='margin-top:10px;'><b>SCHOOL:</b> <u>$school</u></h2>";
if($coopschoolappid && $schapp[datesub]>0)
   echo "<p><b>DATE SUBMITTED:</b> ". date("F j, Y",$schapp[datesub])."</p>";
echo "</td></tr>";

if($app[renewal]=='x')	//RENEWAL
{ 
   echo "<tr align=left><td><p>Please enter the name of your school's Superintendent below as the electronic signature approving the renewal of this Cooperative Sponsorship Agreement.</p>";
   if($print==1) echo "<u>&nbsp;$schapp[boardmember1]&nbsp;</u>";
   else
   {
      echo "<input type=text size=30 name=\"boardmember1\" id=\"boardmember1\" value=\"$schapp[boardmember1]\"";
      if($highlighterrors==1 && trim($schapp[boardmember1])=='') echo " style=\"background-color:#ff0000;color:#ffffff;\"";
      echo ">";
   }
   echo "<br>Superintendent, ".GetSchool2($schoolid);
}
else	//NOT A RENEWAL
{

echo "<tr align=left><td><ul>";

//ENROLLMENT
echo "<li><p>Please list the number of students enrolled in your high school.</p>";
echo "<table cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#808080 1px solid;\">";
echo "<tr align=center><td rowspan=2>&nbsp;</td><td colspan=2><b>GRADE 9</b></td><td colspan=2><b>GRADE 10</b></td><td colspan=2><b>GRADE 11</b></td><td colspan=2><b>GRADE 12</b></td></tr>";
echo "<tr align=center><td><b>Girls</b></td><td><b>Boys</b></td><td><b>Girls</b></td><td><b>Boys</b></td><td><b>Girls</b></td><td><b>Boys</b></td><td><b>Girls</b></td><td><b>Boys</b></td></tr>";
echo "<tr align=center><td align=right><b>Current School Year:</b></td>";
for($grade=9;$grade<=12;$grade++)
{
   $var1="enrollg".$grade."_1";
   $var2="enrollb".$grade."_1";
   if($print==1)
      echo "<td>".$schapp[$var1]."</td><td>".$schapp[$var2]."</td>";
   else
   {
      echo "<td><input type=text size=4 name=\"$var1\" id=\"$var1\" value=\"".$schapp[$var1]."\"></td>
	<td><input type=text size=4 name=\"$var2\" id=\"$var2\" value=\"".$schapp[$var2]."\"></td>";
   }
}
echo "</tr>";
echo "<tr align=center><td align=right><b>Anticipated Next Year:</b></td>";
for($grade=9;$grade<=12;$grade++)
{       
   $var1="enrollg".$grade."_2";
   $var2="enrollb".$grade."_2";
   if($print==1)
      echo "<td>".$schapp[$var1]."</td><td>".$schapp[$var2]."</td>";
   else
   {
      echo "<td><input type=text size=4 name=\"$var1\" id=\"$var1\" value=\"".$schapp[$var1]."\"></td>
        <td><input type=text size=4 name=\"$var2\" id=\"$var2\" value=\"".$schapp[$var2]."\"></td>";
   }
}
echo "</tr>";
echo "<tr align=center><td align=right><b>Anticipated Two Years Hence:</b></td>";
for($grade=9;$grade<=12;$grade++)
{       
   $var1="enrollg".$grade."_3";
   $var2="enrollb".$grade."_3";
   if($print==1)
      echo "<td>".$schapp[$var1]."</td><td>".$schapp[$var2]."</td>";
   else
   {
      echo "<td><input type=text size=4 name=\"$var1\" id=\"$var1\" value=\"".$schapp[$var1]."\"></td>
        <td><input type=text size=4 name=\"$var2\" id=\"$var2\" value=\"".$schapp[$var2]."\"></td>";
   }
}
echo "</tr>";
echo "</table></li><br>";

//PARTICIPANTS
/*
echo "<li><p>Please list the number of students in your high school who participated in this activity. If the school did not offer this activity during the year indicated, check the box next to \"Did Not Sponsor.\"</p>";
echo "<blockquote><input type=checkbox name=\"didnotsponsor\" id=\"didnotsponsor\" value=\"x\"";
if($print==1) echo " disabled";
if($schapp[didnotsponsor]=='x') echo " checked";
echo "> Did Not Sponsor</blockquote>";
echo "<table cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#808080 1px solid;\">";
echo "<tr align=center><td rowspan=2>&nbsp;</td><td colspan=2><b>GRADE 9</b></td><td colspan=2><b>GRADE 10</b></td><td colspan=2><b>GRADE 11</b></td><td colspan=2><b>GRADE 12</b></td></tr>";
echo "<tr align=center><td><b>Girls</b></td><td><b>Boys</b></td><td><b>Girls</b></td><td><b>Boys</b></td><td><b>Girls</b></td><td><b>Boys</b></td><td><b>Girls</b></td><td><b>Boys</b></td></tr>";
echo "<tr align=center><td align=right><b>Current School Year:</b></td>";
for($grade=9;$grade<=12;$grade++)
{
   $var1="participateg".$grade."_1";
   $var2="participateb".$grade."_1";
   if($print==1)
      echo "<td>".$schapp[$var1]."</td><td>".$schapp[$var2]."</td>";
   else
   {
      echo "<td><input type=text size=4 name=\"$var1\" id=\"$var1\" value=\"".$schapp[$var1]."\"></td>
        <td><input type=text size=4 name=\"$var2\" id=\"$var2\" value=\"".$schapp[$var2]."\"></td>";
   }
}
echo "</tr>";
echo "<tr align=center><td align=right><b>Anticipated Next Year:</b></td>";
for($grade=9;$grade<=12;$grade++)
{
   $var1="participateg".$grade."_2";
   $var2="participateb".$grade."_2";
   if($print==1)
      echo "<td>".$schapp[$var1]."</td><td>".$schapp[$var2]."</td>";
   else
   {
      echo "<td><input type=text size=4 name=\"$var1\" id=\"$var1\" value=\"".$schapp[$var1]."\"></td>
        <td><input type=text size=4 name=\"$var2\" id=\"$var2\" value=\"".$schapp[$var2]."\"></td>";
   }
}
echo "</tr>";
echo "<tr align=center><td align=right><b>Anticipated Two Years Hence:</b></td>";
for($grade=9;$grade<=12;$grade++)
{
   $var1="participateg".$grade."_3";
   $var2="participateb".$grade."_3";
   if($print==1)
      echo "<td>".$schapp[$var1]."</td><td>".$schapp[$var2]."</td>";
   else
   {
      echo "<td><input type=text size=4 name=\"$var1\" id=\"$var1\" value=\"".$schapp[$var1]."\"></td>
        <td><input type=text size=4 name=\"$var2\" id=\"$var2\" value=\"".$schapp[$var2]."\"></td>";
   }
}
echo "</tr>";
echo "</table></li>";
*/
echo "</ul>";

//RESOLUTION
echo "<br><p>Board Member ";
if($print==1) echo "<u>&nbsp;$schapp[boardmember1]&nbsp;</u>";
else 
{
   echo "<input type=text size=30 name=\"boardmember1\" id=\"boardmember1\" value=\"$schapp[boardmember1]\"";
   if($highlighterrors==1 && trim($schapp[boardmember1])=='') echo " style=\"background-color:#ff0000;color:#ffffff;\"";
   echo ">";
}
echo " introduced the following resolution and moved its adoption:</p>";
echo "<h3 style='text-align:center;'>Resolution Approving Cooperative Sponsorship Agreement</h3>";
echo "<p>WHEREAS, a proposed Agreement has been negotiated and drafted regarding the cooperative sponsorship of a joint high school ";
$actlist="";
for($i=0;$i<count($coopsports);$i++)
{
   if($app[$coopsports[$i]]=='x') 
   {
      $actlist.=GetActivityName($coopsports[$i]).", ";
   }
}
if($actlist!='') 
   $actlist=substr($actlist,0,strlen($actlist)-2);
echo "<u>&nbsp;&nbsp;$actlist&nbsp;&nbsp;</u> program.</p>";
echo "<p>WHEREAS, a copy of the proposed draft is attached and incorporated by reference.</p>";
echo "<p>NOW, THEREFORE, BE IT RESOLVED by the School Board of School District No. <u>&nbsp;";
for($i=1;$i<=4;$i++)
{
   $distvar="dist".$i; $schvar="schoolid".$i;
   if($schoolid==$app[$schvar])
      echo $app[$distvar];
}
echo "&nbsp;</u> as follows:</p>";
echo "<ol><li><p>That the attached Cooperative Sponsorship Agreement do and hereby is approved;</p></li>
	<li><p>That the Chair and Clerk are hereby authorized to execute the attached Cooperative Sponsorship Agreement and to make the required application to the Board of Directors of the Nebraska School Activities Association; and</p></li>
	<li><p>That this resolution shall be effective only upon the adoption of a similar resolution by the Governing Board or School Board of the cooperating school(s) or school district(s).</p></li></ol>";
echo "<p>The motion for adoption of the foregoing resolution was duly seconded by Board Member ";
if($print==1)
   echo "<u>&nbsp;$schapp[boardmember2]&nbsp;</u>";
else
{
   echo "<input type=text size=30 name=\"boardmember2\" id=\"boardmember2\" value=\"".$schapp[boardmember2]."\"";
   if($highlighterrors==1 && trim($schapp[boardmember2])=='') echo " style=\"background-color:#ff0000;color:#ffffff;\"";
   echo ">";
}
echo " and upon vote being taken thereon, the following voted in favor thereof:</p>";
if($print==1)
   echo "<blockquote>$schapp[infavor]</blockquote>";
else
{
   echo "<p><textarea name=\"infavor\" id=\"infavor\" rows=3 cols=90";
   if($highlighterrors==1 && trim($schapp[infavor])=='') echo " style=\"background-color:#ff0000;color:#ffffff;\"";
   echo ">$schapp[infavor]</textarea></p>";
}
echo "<p>and the following voted against the same:</p>";
if($print==1)
   echo "<blockquote>$schapp[against]</blockquote>";
else
   echo "<p><textarea name=\"against\" id=\"against\" rows=3 cols=90>$schapp[against]</textarea></p>";
echo "<p>whereupon said resolution was declared duly passed and adopted.</p>";

echo "</td></tr><tr align=right><td><br>";
if($print==1) 
   echo "<u>&nbsp;$schapp[boardchair]&nbsp;</u>";
else 
{
   echo "<input type=text size=40 name=\"boardchair\" id=\"boardchair\" value=\"$schapp[boardchair]\"";
   if($highlighterrors==1 && trim($schapp[boardchair])=='') echo " style=\"background-color:#ff0000;color:#ffffff;\"";
   echo ">";
}
echo "<br>Chair, Board of Education<br><br>";
if($print==1)
   echo "<u>&nbsp;$schapp[boardclerk]&nbsp;</u>";
else 
{
   echo "<input type=text size=40 name=\"boardclerk\" id=\"boardclerk\" value=\"$schapp[boardclerk]\"";
   if($highlighterrors==1 && trim($schapp[boardclerk])=='') echo " style=\"background-color:#ff0000;color:#ffffff;\"";
   echo ">";
}
echo "<br>Clerk, Board of Education<br><br>";
}//END IF NOT RENEWAL

if(!$print)
   echo "<input type=submit class=\"fancybutton2\" name=\"save\" value=\"Save\" style=\"float:right;\"><div style='clear:both;'></div>";

echo "</td></tr></table>";

echo "</form>";

echo $end_html;
?>
