<?php
require '../variables.php';
require '../functions.php';
require '../../calculate/functions.php';

$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name,$db);

$temp=explode(";",GetFBYears());
$year1=$temp[0];
$year2=$temp[1];
$year0=$year1-1;
$pshowdate=GetMiscDueDate("priority","showdate");
$pduedate=GetMiscDueDate("priority");
$pduedate2=date("F j, Y",strtotime($pduedate));
$rdate=GetFBDate("showschedules_date");
$rdate2=date("F j, Y",strtotime($rdate));

echo $init_html;
echo GetHeader($session);
echo "<br>";
echo "<form method=post action=\"priority.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<table width=700 cellspacing=3 cellpadding=3 class=nine><tr align=center><td><b>
NEBRASKA SCHOOL ACTIVITIES ASSOCIATION<br>
500 Charleston Street<br>
Lincoln, Nebraska 68508-1119</td></tr>
<tr align=left><td><b>
TO:</b>	Athletic Director and Head Football Coach</td></tr><tr align=left><td><b>
FROM:</b>	Nate Neuhaus, Assistant Director</td></tr><tr align=left><td><b>
SUBJECT:</b>	$year1 and $year2 Football Schedules - Non-District Opponents</td></tr><tr align=left><td><b>
DATE:</b>		December $year0</td></tr>
<tr align=left><td><br>
The NSAA is asking all schools who plan to play football during the $year1 and $year2 seasons, to submit a prioritized list of non-district schools they would like the NSAA to consider for their $year1 and $year2 football schedules.  The $year1 and $year2 NSAA Football Classifications and the $year1 and $year2 NSAA Football District Assignments are posted on your school's secure page on the NSAA web page (www.nsaahome.org).
<br><br>
Please rank in priority order the non-district schools that you would like the NSAA to consider for your football schedule.  The NSAA encourages your school to visit with all schools you are considering for your non-district games, so as to make sure that a school you're considering is also interested in a possible game.  Also, please look at the adjacent Districts to your District for possible non-district schools, as the NSAA will do this to help fill all schedules.  
<br><br>
If you are interested in playing an out-of-state school, please indicate the school, date and Home/Away for each game.  The NSAA makes no guarantees that you will receive the location of the out of state game as you request.  The number of schools (odd or even) in each class will determine the availability of playing out-of-state schools.  You do not need to indicate a date for all non-district games against Nebraska schools, since the NSAA will assign the week each game will be played.  The Home and Away will also be assigned by the NSAA for all games between Nebraska schools.  
<br><br>
The NSAA will not make any guarantees that your school will get to play the non-district schools that you list on the form.  The NSAA will make every attempt possible to accommodate your request for as many non-district games in your football schedule as possible.
<br><br>
Also, please be sure to indicate whether or not you share a stadium with another school or schools.  If you do share a stadium, please list the names of the schools who you share the stadium with, so as to assist the NSAA in the football scheduling process.
<br><br>
It is our plan to release the completed two-year schedules ($year1 and $year2) along with the two-year Home and Away designations for all Classes to the member schools through their NSAA School Login at 9:00am Central Time on $rdate2.  Each school is responsible for contracting officials for all of the NSAA assigned, home varsity football games.
<br><br>
The form needs to be completed by MIDNIGHT ON $pduedate2.  Once you click \"Submit\" on your form, it will automatically be sent to the NSAA.
<br><br>
Thanks for your help!  	
</td></tr><tr align=center><td><br>";
echo "<input type=\"submit\" name=\"submit\" value=\"Go to Your Priority List\"></td></tr></table>";
echo "</form>";
//echo "<a href=\"priority.php?session=$session&sid=$sid\">Click HERE to Enter Form</a>";
echo $end_html;
?>
