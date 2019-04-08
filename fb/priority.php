<?php
require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php';

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session) || ($school!="Test's School" && $sid=="NO SID"))
{
   header("Location:../index.php");
   exit();
}

$temp=explode(";",GetFBYears());
$year1=$temp[0];
$year2=$temp[1];
$schooltbl=GetSchoolsTable('fb',$year1,$year2);
if(GetLevel($session)!=1)
   $sid=GetSID($session,'fb',$year1,$year2);
if(GetSchool($session)=="Test's School") $sid="1000000";

$header=GetHeader($session);
$level=GetLevel($session);
if($level!=1) $edit==0;
if($level==1)
{
   $header="<table width=100%><tr align=center><td>";
}

//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch && $level!=1)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);
$sql2="SELECT * FROM $schooltbl WHERE sid='$sid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$class=$row2['class'];

/* if($class=='A') $max=4;
else if($class=='B') $max=6;
else if($class=='C1' || $class=='C2') $max=5;
else $max=4; */
if($class=='A') $max=5;
else if($class=='B') $max=5;
else if($class=='C1' || $class=='C2') $max=5;
else $max=5;

$sql="SELECT duedate FROM misc_duedates WHERE sport='priority'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$duedate=$row[0];
$date=split("-",$duedate);
$duedate2="$date[1]/$date[2]/$date[0]";

if($submit && $submit!="Go to Your Priority List")
{
   if($revise!='x') $revisedate="";
   else $revisedate=time();
   //save to database
   $schools=addslashes($schools); $submitter=addslashes($submitter);
   $sql="SELECT * FROM fbpriority WHERE sid='$sid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $now=time();
      $sql2="INSERT INTO fbpriority (eightgames,sid,stadium,schools,datesub,submitter,revise) VALUES ('$eightgames','$sid','$stadium','$schools','$now','$submitter','$revisedate')";
      $result2=mysql_query($sql2);
   }
   else
   {
      $now=time();
      $sql2="UPDATE fbpriority SET eightgames='$eightgames',stadium='$stadium',schools='$schools',datesub='$now',submitter='$submitter',revise='$revisedate' WHERE sid='$sid'";
      $result2=mysql_query($sql2);
   }
   for($i=0;$i<$max;$i++)
   {
         $rank=$i+1;
	 $oppfield="opp".$rank;
     	 $hafield="homeaway".$rank;
	 $datefield="date".$rank;
         if($month[$i]!='MM' && $day[$i]!='DD')
            $curdate="$year[$i]-$month[$i]-$day[$i]";
   	 else $curdate="0000-00-00";
 	 $schpref2[$i]=addslashes($schpref[$i]);
         $sql="UPDATE fbpriority SET $oppfield='$schpref2[$i]',$hafield='$homeaway[$i]',$datefield='$curdate' WHERE sid='$sid'";
   	 $result=mysql_query($sql);
   } 
   header("Location:priority.php?session=$session&sid=$sid&submitted=1");
   exit();
}

echo $init_html;
echo $header;
$curyear=date("Y");
$month=date("m");

//if checkbox "REVISE" was checked on memo page...
if($revise=='x')
{
   $sql="DELETE FROM fbpriority WHERE sid='$sid'";
   $result=mysql_query($sql);
   //echo $sql;
}

//see if this school has already submitted a priority list:
$sql="SELECT * FROM fbpriority WHERE sid='$sid'";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0 && !($level==1 && $edit==1))	//if submitted already, show what they submitted
{
   while($row=mysql_fetch_array($result))
   {
      $sql2="SELECT * FROM $schooltbl WHERE sid='$sid'";
      $result2=mysql_query($sql2);
      if($row2=mysql_fetch_array($result2)) // || $school=="Test's School")
      {
	 //Have to hide the "or Test's School" above for this to work or else
	 //The $row2 doesn't like that for some reason
         echo "<br><font style=\"color:red\"><b>";
         if($level==1) echo "$row2[school]";
         else echo "You";
         if($row[revise]!="" && $row[revise]!='0')
            echo " REVISED";
         else echo " submitted";
         echo " the following Priority List on ".date("m/d/Y",$row[datesub]).":</b></font><br>";
         echo "<br><table width=500><caption><b><i>Non-District Football Team Opponents Priority List:</b></i>";
         if($level==1)
            echo "<br><a class=small href=\"priority.php?edit=1&sid=$sid&session=$session\">Edit this Priority List</a>";
         echo "<hr></caption>";
         echo "<tr align=left><td class=bigger>";
         echo "<b>$row2[school]</b>, Class <b>$row2[class]</b>, District <b>$row2[district]</b>,";
         echo " Type: <b>$row2[type]<br><br></td></tr>";
         echo "<tr align=left><td><b>Do You Share a Stadium With Another School:</b>&nbsp;";
         if($row[stadium]=='y') echo "YES";
         else if($row[stadium]=='n') echo "NO";
         echo "</td></tr>";
         echo "<tr align=left><td><b>School(s) You Share the Stadium With:&nbsp;</b>";
         echo "$row[schools]</td></tr>";
         echo "<tr align=left><td><b>Name of Person Submitting this Form:</b>&nbsp;&nbsp;$row[submitter]</td></tr>";
         echo "<tr align=center><td><table cellspacing=0 cellpadding=3 style=\"border:#808080 1px solid;\" frame=all rules=all>";
         echo "<tr align=center><th class=small>Priority<br>Rank Order</th>";
         echo "<td><b>Non-District Opponents</b></td><td><b>Class</b></td>";
         echo "<td><b>H/A</b></td><td><b>Date of Contest</b></td></tr>";
         for($i=1;$i<=$max;$i++)
         {
            $oppfield="opp".$i;
            $hafield="homeaway".$i;
            $datefield="date".$i;
            if($row[$oppfield]!="")
            { 
               $sql3="SELECT school,class FROM $schooltbl WHERE sid='$row[$oppfield]'";
  	       $result3=mysql_query($sql3);
	       $row3=mysql_fetch_array($result3);
               echo "<tr align=center><td><b>$i</b>";
               echo "<td align=left>$row3[school]</td><td>$row3[class]</td><td>".strtoupper($row[$hafield])."</td>";
	       echo "<td>";
   	       if($row[$datefield]!="0000-00-00")
	       {
	          $cur=split("-",$row[$datefield]);
	          echo "$cur[1]/$cur[2]/$cur[0]";
	       }
	       else echo "&nbsp;";
	       echo "</td></tr>";
            }
         }
         echo "</table>";
	 if($row2['class']=="C1" || $row2['class']=="A" || $row2['class']=="B"|| $row2['class']=="C2"|| $school=="Test's School" )
	 {
	    echo "<p><input type=\"checkbox\" disabled=TRUE value=\"x\"";
	    if($row['eightgames']=='x') echo " checked";
	    echo "> Check here if you are interested in playing an 8-game schedule.</p>";
	 }
   	 echo "</td></tr>";
         echo "</table>";
        
      }
   }//end for each year submitted
   echo $end_html;
   exit();
}
   
echo "<form method=post action=\"priority.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"sid\" value=\"$sid\">";
if($revise=='x')
{
   echo "<font style=\"color:blue\"><b>You are REVISING your priority list.</b></font>";
   echo "<input type=hidden name=revise value='x'>";
}

echo "<br><table width=80%>";
echo "<caption><b>Non-District Football Team Opponents Priority List:</b><br><br>";
echo "<table><tr align=left><td>";
echo "<i>NEEDED BY THE NSAA OFFICE IN ORDER TO ESTABLISH THE FOOTBALL SCHEDULES</i><br><br>";
echo "<b><u>PLEASE COMPLETE IMMEDIATELY</u></b> - This form needs to be completed by <b><u>midnight on $duedate2</u></b><br><br>";
echo "PLEASE READ INSTRUCTIONS CAREFULLY BEFORE SUBMITTING THIS FORM.  You MAY NOT MAKE CHANGES to this form after submitting it.<br><br>";
echo "<b>INSTRUCTIONS:</b>  The Nebraska School Activities Association is asking all schools who plan to play football during the $year1 and $year2 seasons, to submit a prioritized list of non-district schools they would like the NSAA to consider for their $year1 and $year2 football schedules.  The NSAA will use this prioritized list for each school for non-district games when establishing the $year1 and $year2 football schedules.
<br><br>
Please <b><u>rank in priority order</b></u> the non-district schools that you would like the NSAA to consider for your football schedule. If you interested in playing an out-of-state school, please indicate the school, date and Home/Away for each game.  The NSAA makes no guarantees that you will receive the location of the out of state game as you request. The number of schools (odd or even) in each class will determine the availability of playing out-of-state schools.  You do not need to indicate a date for all non-district games against Nebraska schools, since the NSAA will assign the week each game will be played.  Also, the Home and Away will be assigned by the NSAA for all games between Nebraska schools.
<br><br><b>
If you don't see an out-of-state school listed, contact the NSAA office BEFORE SUBMITTING THIS FORM.
</b><br><br>
The NSAA will not make any guarantees that your school will get to play the non-district schools that you have listed on this form.  The NSAA will make every attempt possible to accommodate your request for as many non-district schools as possible for your $year1 and $year2 football schedules.<br><br>
If you are putting a school on your priority list, it would be advisable for you to contact that school so they can reciprocate.
";
echo "</td></tr></table><hr>";
echo "<table>";	//school info
$sql="SELECT * FROM $schooltbl WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class=$row['class']; $district=$row[district]; $type=$row[type];
echo "<tr align=left><td><b>School Name:</b>&nbsp;&nbsp;$row[school]&nbsp;&nbsp;&nbsp;";
echo "<b>Class:&nbsp;</b>$row[class]&nbsp;&nbsp;<b>District Number:</b>&nbsp;$row[district]";
echo "&nbsp;&nbsp;<b>Type:</b>&nbsp;$row[type]</td></tr>";
$sql2="SELECT * FROM fbpriority WHERE sid='$sid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
echo "<tr align=left><td><b>Do You Share a Stadium With Another School?</b>&nbsp;&nbsp;";
echo "<input type=radio name=stadium value='y'";
if($edit==1 && $row2[stadium]=='y') echo " checked";
echo ">YES&nbsp;&nbsp;";
echo "<input type=radio name=stadium value='n'";
if($edit==1 && $row2[stadium]=='n') echo " checked";
echo ">NO</td></tr>";
echo "<tr align=left><td><b>Name of School(s) That You Share The Stadium With:<br>";
echo "<input type=text class=tiny size=60 name=schools value=\"$row2[schools]\"></td></tr>";
echo "<tr align=left><td><b>Name of Person Submitting This Form:</b>&nbsp;&nbsp;";
echo "<input type=text class=tiny size=30 name=submitter value=\"$row2[submitter]\"></td></tr>";
echo "<tr align=left><td><b>Today's Date:</b>&nbsp;&nbsp;".date("m/d/Y")."</td></tr>";
echo "</table><br></caption>";
$ix=0;
echo "<tr align=center><td><table cellspacing=0 cellpadding=3 style=\"border:#808080 1px solid;\" frame=all rules=all>";
echo "<caption><b><i>Priority List:</b></i></caption>";
echo "<tr align=center><td><b>Priority<br>Rank Order</b></td>";
echo "<td><b>List Non-District Opponents</b></td>";
echo "<th class=small><b>Home/Away<br>(Out-of-State)</b></th>";
echo "<td><b>Date of Contest<br>(Out-of-State)</b></td></tr>";
//get list of schools not in this school's district but are same type as this school
$sql="SELECT * FROM $schooltbl WHERE (class!='$class' OR district!='$district') AND (outofstate='1' OR type='$type') ORDER BY school";
$result=mysql_query($sql);
//echo $sql;
$fbsid=array(); $fbsch=array(); $f=0;
while($row=mysql_fetch_array($result))
{
   if(!($row['class']==$class && $row[district]==$district) || $row[outofstate]=='1')
   {
      $fbsch[$f]=$row[school];
      $fbsid[$f]=$row[sid];
      $f++;
   }
}
for($i=0;$i<$max;$i++)
{
   echo "<tr align=center>";
   $rank=$i+1;
   echo "<td><b>$rank</b></td>";
   $oppfield="opp".$rank;
   echo "<td><select name=\"schpref[$ix]\"><option value=''>Choose School</option>";
   for($j=0;$j<count($fbsch);$j++)
   {
      echo "<option value='$fbsid[$j]'";
      if($row2[$oppfield]==$fbsid[$j]) echo " selected";
      echo ">$fbsch[$j]</option>";
   }
   echo "</select></td>";
   $hafield="homeaway".$rank;
   echo "<td><input type=radio name=\"homeaway[$ix]\" value='h'";
   if($row2[$hafield]=='h') echo " checked";
   echo ">H&nbsp;";
   echo "<input type=radio name=\"homeaway[$ix]\" value='a'";
   if($row2[$hafield]=='a') echo " checked";
   echo ">A</td>";
   $datefield="date".$rank;
   $date=split("-",$row2[datefield]);
   echo "<td><select name=\"month[$ix]\"><option>MM</option>";
   for($m=1;$m<=12;$m++)
   {
      if($m<10) $mo="0".$m;
      else $mo=$m;
      echo "<option";
      if($date[1]==$mo) echo " selected";
      echo ">$mo</option>";
   }
   echo "</select>/<select name=\"day[$ix]\"><option>DD</option>";
   for($d=1;$d<=31;$d++)
   {
      if($d<10) $da="0".$d;
      else $da=$d;
      echo "<option";
      if($date[2]==$da) echo " selected";
      echo ">$da</option>";
   }
   echo "</select>/<select name=\"year[$ix]\">";
   echo "<option selected>$year1</option>";
   echo "</select></td></tr>";
   $ix++;
}
echo "</table></td></tr>";
echo "</table>";
//if($school=="Test's School" || $class=="C1")	//CHECKBOX FOR 8-GAME SCHEDULE
if($class=="C1" || $class=="A" || $class=="B"|| $class=="C2"|| $school=="Test's School" )
{
   echo "<p><input type=\"checkbox\" name=\"eightgames\" value=\"x\"";
   if($row2[eightgames]=='x') echo " checked";
   echo "> Check here if you are interested in playing an <b><u>8-game schedule</b></u>.</p>";
}
echo "<br><input type=\"submit\" name=\"submit\" value=\"Submit\" class=\"fancybutton\" onclick=\"return confirm('Are you sure you are ready to submit this form?  You will not be able to make changes after you submit this form.');\">";
echo "</form>";
echo $end_html;
?>
