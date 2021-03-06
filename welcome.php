<?php
//welcome.php: displays welcome page for specified user

require 'functions.php';
require_once('variables.php');

$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

if(!ValidUser($session))
{
   header("Location:/nsaaforms/index.php?error=1");
   exit();
}

$ismobile=IsMobile();

citgf_exec("/usr/local/bin/php requests/emailreminders.php > requests/output.html 2>&1 &");

$offact=array("ba","bb","di","fb","sb","so","sw","tr","vb","wr");
$offact2=array("Baseball","Basketball","Diving","Football","Softball","Soccer","Swimming","Track & Field","Volleyball","Wrestling");

//Get user's specifics from logins table using $session
$sql="SELECT t2.name, t2.school, t2.sport, t2.level, t2.usertitle,t2.id FROM sessions AS t1, logins AS t2 WHERE t1.session_id='$session' AND t1.login_id=t2.id";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$level=$row[3];
$school=$row[1];
$school2=addslashes($school);
$sport=$row[2];
$name=$row[0];
$userid=$row[id];
$usertitle=$row[usertitle];
if($level!=2 && (trim($name)=="" || $level==4)) $name=$row[school];
if($level==2)	//AD
{
   //if no name, check Act Dir field
   if(trim($name)=="")
   {
      $sql="SELECT name FROM logins WHERE school='$school2' AND sport='Activities Director'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $name=$row[0];
      $actdir="yes";
   }
}

if($level==1) CleanSessions();

if($level!=1)
{
mysql_select_db($db_name2,$db);
//Figure out what the last year archived was.  Will show those rosters below current ones:
$year=date("Y"); $year1=$year+1; $year0=$year-1;
$archivedbroster="$db_name2".$year0.$year;
$sql="SHOW DATABASES LIKE '$archivedbroster'";
$result=mysql_query($sql);   
$archiveroster=0;
if(mysql_num_rows($result)==0)
{
   $year00=$year0-1;
   $archivedbroster="$db_name2".$year00.$year0;  
   $curyear="$year0-$year";
   $lastyear="$year00-$year0";
   $sql="SHOW DATABASES LIKE '$archivedbroster'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) $archiveroster=0;
   else $archiveroster=1;
}
else
{
   $archiveroster=1;
   $curyear="$year-$year1";
   $lastyear="$year0-$year";
}
mysql_select_db($db_name,$db);
}
if ($spexport){
  	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=SchoolList.csv');
	$output = fopen('php://output', 'w');
	
	fputcsv($output, array('Class','District','Event','School','Student1','Student2','Student3','Student4','Student5'));
	$events = array('drama1','drama2','poetry','pers_speak','inform','extemp','ent_speak','duet_acting1','duet_acting2','prose_humor','prose_serious');
	
	for ($i=0;$i<count($events);$i++){ 
	  $sql ="SELECT t2.school, t1.class_dist FROM sp AS t1, eligibility AS t2 WHERE t1.student_id=t2.id   AND t1.$events[$i]='y' group by t2.school ";
	  $rows = mysql_query($sql);
	  
	  while ($row = mysql_fetch_assoc($rows)){
	  $sch[] =$row[school];
	  $dis[] =$row[class_dist];
	  } 
      for ($j=0;$j<count($sch);$j++){
	  //foreach ($sch as $school){
	   $sql1 ="SELECT concat (t2.first,' ' ,t2.last)as fname FROM sp AS t1, eligibility AS t2 WHERE t1.student_id=t2.id   AND t1.$events[$i]='y' AND t2.school='$sch[$j]'"; 
	   $row1 = mysql_query($sql1); 
	   $data[0]=$dis[$j]; 
	   $data[1]=" ";
	   $data[2]=$events[$i];
 	   if ($events[$i]=='drama1')
	   $data[2]='Drama Group 1';
	   elseif ($events[$i]=='drama2')
	   $data[2]='Drama Group 2';
	   elseif ($events[$i]=='poetry')
	   $data[2]='Poetry';
	   elseif ($events[$i]=='pers_speak')
	   $data[2]='Persuasive Speaking';
	   elseif ($events[$i]=='inform')
	   $data[2]='Informative Public Speaking';
	   elseif ($events[$i]=='extemp')
	   $data[2]='Extemporaneous Speaking';
	   elseif ($events[$i]=='ent_speak')
	   $data[2]='Entertainment Speaking';
	   elseif ($events[$i]=='duet_acting1')
	   $data[2]='Duet Acting Group 1';
	   elseif ($events[$i]=='duet_acting2')
	   $data[2]='Duet Acting Group 2';
	   elseif ($events[$i]=='prose_humor')
	   $data[2]='Oral Interpretation of Humorous Prose';
	   elseif ($events[$i]=='prose_serious')
	   $data[2]='Oral Interpretation of Serious Prose';
	   else 
	   $data[2]=$events[$i];
	   $data[3]=$sch[$j]; 
	   
	   while ($row2 = mysql_fetch_assoc($row1)) { 
	     $student[]= $row2[fname];
	   } 
	   for ($k=0;$k<count($student);$k++){
	     $val=4+$k;
	     $data[$val] = $student[$k];
	   } if(!empty($data[4])) fputcsv($output, $data); unset($data);unset($student);//fputcsv($output, $data); 
	  } unset($data);
	} unset($data);exit;
}
if($level==1 || $level==2)
{
   echo $init_html_ajax;
   if($level==2 && CHANGEPASS==1)
		{
		   $sql="SELECT t2.changepass FROM sessions as t1, logins as t2 WHERE t1.login_id=t2.id AND t1.session_id='$session'";
		  $result=mysql_query($sql);
		  $row=mysql_fetch_array($result);

		  if ($row[changepass]<strtotime ('2018-1-1'))
		  {
				header("Location:/nsaaforms/changepassword.php?session=$session");
				exit();
		  }
		}
?>
<script type="text/javascript" src="/javascript/Tree.js"></script>
</head>
<?php
   $header=GetHeader($session);
   echo $header;
}
else
{
   require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
   require $_SERVER['DOCUMENT_ROOT'].'/calculate/variables.php'; //Wildcard Variables
   echo $init_html;
   echo GetHeader($session);
   
     if($level==3 && CHANGEPASS==1)
		{
		   $sql="SELECT t2.changepass FROM sessions as t1, logins as t2 WHERE t1.login_id=t2.id AND t1.session_id='$session'";
		  $result=mysql_query($sql);
		  $row=mysql_fetch_array($result);

		  if ($row[changepass]<strtotime ('2018-1-1'))
		  {
				header("Location:/nsaaforms/changepassword.php?session=$session");
				exit();
		  }
		} 
}
//if($level==1)
//{echo "<a href=\"anthem_list.php?session=$session\" >Schools that Uploaded Anthem Singer</a>";}

//get today's date:
$day=date(l);
$month=date(F);
$num=date(j);
$year=date(Y);
$date="$day, $month $num, $year";

//get array of school names from headers table in db:
$sql="SELECT school FROM headers ORDER BY school";
$result=mysql_query($sql);
$schools=array();
$ix=0;
while($row=mysql_fetch_array($result))
{
   $schools[$ix]=$row[0];
   $ix++;
}

if($level==1)	//NSAA user
{
   if(!$toggle) $toggle='menu1';
?>
<body onload="Tree.initialize('<?php echo $session; ?>'); Tree.toggle('<?php echo $toggle; ?>');">
<?php
   echo "<table width=100%><tr align=center><td>";

   echo "<br><table width=90% cellspacing=0 cellpadding=0>";
   echo "<caption><b>Welcome, NSAA!<br>";
   echo "Today's Date is: ".date("l, F j, Y")."</b><br><br></caption>";
   /***** SHOW URGENT ALERTS TO NSAA ADMIN *****/
   echo GetUrgentAlerts($session);
   /***** END URGENT ALERTS *****/
  
   echo "<tr align=left><th align=left><a href=\"/calculate/wildcard/wildcard.php?session=$session\">Go to Wildcard Program</a><br><br></th></tr>";
   echo "<tr align=left><td><font style=\"font-size:9pt;color:blue\"><b>NOTE: Click a heading to open/close that section.</b></td></tr>";
   echo "<tr align=center><td>";
   echo "<div id=\"menu1header\" class=\"menuheader\" onclick=\"javascript:Tree.toggle('menu1');\">";
   echo "[ + ] OUTBOX: Messages, Uploads, etc.</div>";
   echo "<div id=\"menu1\"><br></div>";
   echo "<div id=\"menu2header\" class=\"menuheader\" onclick=\"javacript:Tree.toggle('menu2');\">";
   echo "[ + ] Eligibility:</div>";
   echo "<div id=\"menu2\"><br><table width=500><tr align=left><td>";
   echo "The \"Eligibility\" section contains the Advanced Search on Students, Eligibility List Due Dates, Check on Registered Teams' Eligibility Lists, the Eligibility Report, Participant Surveys, and Transfer Forms Admin.<br><br></td></tr></table></div>";
   echo "<div id=\"menu3header\" class=\"menuheader\" onclick=\"javascript:Utilities.getElement('menu3sport').value='';Tree.toggle('menu3');\">";
   echo "[ + ] Activity Select:</div>";
   echo "<form method=post action=\"welcome.php\"><input type=hidden name=session value=\"$session\"><input type=hidden name=toggle value=\"menu3\">";
   echo "<select name=\"menu3sport\" id=\"menu3sport\"><option value=''>Select an Activity</option>";
   $empty=array();
   $sortact=array_merge($empty,$act_long2);
   sort($sortact);
   if($menu3sport=="Track") $menu3sport="Track & Field";
   for($i=0;$i<count($sortact);$i++)
   {
      if(preg_match("/6\/8/",$sortact[$i]))       //only show Football, not 6/8 or 11
      {
         echo "<option value=\"Football\"";
         if($menu3sport=="Football") echo " selected";
         echo ">Football</option>";
      }
      else if(preg_match("/Instrumental/",$sortact[$i])) //only show Music, not Instrumental or Vocal
      {
         echo "<option value=\"Music\"";
         if($menu3sport=="Music") echo " selected";
         echo ">Music</option>";
      }
      else if(!preg_match("/ 11/",$sortact[$i]) && !preg_match("/Vocal/",$sortact[$i]))
      {
         echo "<option value=\"$sortact[$i]\"";
         if($menu3sport==$sortact[$i]) echo " selected";
         echo ">$sortact[$i]</option>";
      }
   }
   echo "</select>&nbsp;<input type=submit name=menu3go id=\"menu3go\" value=\"Go\"></form>";
   echo "<div id=\"menu3\"><table width=500><tr align=left><td>The \"Activity Select\" section contains the Advanced Search on Entry Forms & Manage Entry Form Due Dates features AS WELL AS any forms/reports/etc. related to the activity you select.  Examples: District & State Entry Forms, Swimming Season Bests, Financial Forms, Football Priority Lists, Football Stats & Records Reports, etc.<br><br></td></tr></table></div>";
   //SANCTIONS
   echo "<div id=\"menu5header\" class=\"menuheader\" onclick=\"javascript:Tree.toggle('menu5');\">";
   echo "[ + ] Sanctions:</div>";
   echo "<div id=\"menu5\"><br><table width=500><tr align=left><td>The \"Sanctions\" section is where Applications for Sanctions of Interstate, International and Fine Arts Events are managed and approved.<br><br></td></tr></table></div>";
   //OTHER TOOLS
   echo "<div id=\"menu4header\" class=\"menuheader\" onclick=\"javascript:Tree.toggle('menu4');\">";
   echo "[ + ] Other Tools:</div>";
   echo "<div id=\"menu4\"><br><table width=500><tr align=left><td>The \"Other Tools\" section contains Password Lookup, Declarations & Registration, Manage School Names & Colleges, School Directory, Address Book, Proposals, Requests for Coaches/Contests/Equipment/Officialsand Due Dates.<br><br></td></tr></table></div>";
   echO "</td></tr></table>";
?>
<div id="loading" style="display:none;"></div>
<?php
}
else if($level==2)	//AD-Access
{
   if(!$toggle) $toggle='menu1';
?>
<body onload="Tree.initialize('<?php echo $session; ?>'); Tree.toggle('<?php echo $toggle; ?>');">
<?php
   echo "<table width=100%><tr align=center><td>";

   //if on or after Jan 1, increment all students' semester
   $year=date("Y");
   $month=date("m");
   $day=date("d");
   $today="$year-$month-$day";
   if($month>=1 && $month<6)
   {
      $sql="SELECT sem_inc FROM headers WHERE school='$school2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $sem_inc=$row[0];	//=1 if semesters have been incremented, else =0
      if($sem_inc==0)
      {
	 $sql2="UPDATE eligibility SET semesters=semesters+1 WHERE school='$school2' AND semesters!=0";
	 $result2=mysql_query($sql2);

	 //also reset declaration form
	 $sql2="DELETE FROM declaration WHERE school='$school2'";
	 $result2=mysql_query($sql2);

	 $sql2="UPDATE headers SET sem_inc='1' WHERE school='$school2'";
	 $result2=mysql_query($sql2);
      }
   }

   echo "<br><table width=80% cellspacing=0 cellpadding=0><caption><b>Welcome, $name!<br>";
   if($actdir=="yes") echo "Activites Director";
   else echo "Athletic Director";
   echo "<br>Today's Date is $date</b><br><br>";

   //WILDCARD SCORES DASHBOARD
   echo GetScoresDashboard($session);

   //OTHER ALERTS
   echo GetUrgentAlerts($session);

   /**** CHECK TO SEE IF THEY'VE CHANGED THEIR PASSWORD (7/15/10) ****/
   $sql="SELECT t1.* FROM logins AS t1, sessions AS t2 WHERE t1.id=t2.login_id AND t2.session_id='$session'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[changepass]==0 && ($school=="Test's School" || PastDue("2010-07-14",0)))
   {
      echo "<div class='normalwhite' style='font-size:9pt;padding:20px;width:300px;'><p><b>Your password has expired.</b> You must create a new password in order to continue using this system. This will ensure the security and confidentiality of the information you have access to in this system.</p><p style='text-align:center;'><a href=\"changepassword.php?session=$session\">Click Here to Change your Password</a></p></div>";
      echo $end_html;
      exit();
   }

   /***** EVEN YEARS: THIS IS WHERE THE LINKS TO THE FOOTBALL SCHEDULES WILL BE ON RELEASE DATE: *****/
   require_once('../calculate/functions.php');
   $temp=explode(";",GetFBYears());
   $year1=$temp[0]; $year2=$temp[1];
   $DATESrelease=GetFBDate("showschedules_date");	//SHOW SCHEDULES (9am)
   $DATESshowdate=GetFBDate("gamedates_startdate");	//CAN START ENTERING DATES
   $DATESduedate=GetFBDate("gamedates_duedate");	//DUE DATE FOR ENTERING DATES
   $temp=explode("-",$DATESrelease);
   $DATESreleaseSEC=mktime(9,0,0,$temp[1],$temp[2],$temp[0]);
   if(PastDue($DATESrelease,-2) && !PastDue($DATESduedate,-1) && time()>=1518501629)	//ADDED THIS LINE TO KEEP IT OPEN UNTIL "ENTER DATES" Due Date
   {
   /*** SECOND RELEASE: LINK TO SELECT DATE FOR EACH GAME: ***/
   if(PastDue($DATESshowdate,-1))
   {
      $date=explode("-",$DATESduedate);
      $nicedate=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
      echo "<div class='alert' style='font-size:13px;width:800px;'>";
      echo "<b>IT IS NOW TIME TO INDICATE THE SPECIFIC DATE FOR EACH GAME ON YOUR $year1-$year2 FOOTBALL SCHEDULES.<br><br></b>";
      echo "You can enter your dates and make changes up until <u><b>$nicedate</u></b> here: <a href=\"../calculate/wildcard/fbschedules.php?session=$session\">$year1-$year2 Football Schedule Dates</a></div>";
      echo "<br>";
   }

   /*** FIRST RELEASE: DOWNLOAD SCHEDULES ***/
   $fbsid=GetSID($session,'fb',$year1); $fbschool=GetSchoolName($fbsid,'fb',$year1);
   $filename=preg_replace("/[^a-zA-Z]/","",$fbschool).$year1."fbschedule.txt";
   echo "<div class='alert' style='font-size:13px;width:800px;'>";
   echo "<b>PLEASE READ THE FOLLOWING CAREFULLY. These are the most accurate instructions for accessing your $year1-$year2 Football Schedules:</b><br><br>";
   echo "The following links will allow you to <u><b>DOWNLOAD YOUR $year1-$year2 FOOTBALL SCHEDULES TO YOUR COMPUTER</b></u> starting at <u><b>9:00AM CST on ".date("l, F j",$DATESreleaseSEC).", $year1</b></u>.<br><br>Clicking these links BEFORE 9:00AM CST will result in a message simply stating that the schedules have not yet been released.<br><br><b>YOU <u>DO NOT NEED TO RELOAD</u> THIS SCREEN AT 9:00AM IN ORDER TO DOWNLOAD THE CORRECT SCHEDULE.</b> Doing so may cause your browser to \"cache\" the current files on this site and <b>prevent you from being able to download the actual schedules</b> at 9:00am CST.<br><br>At 9:00AM CST, click one of the following links to <b>DOWNLOAD YOUR SCHEDULE AND VIEW IT ON YOUR COMPUTER:</b><br><br>";
   if(time()>=1518534120)
   {	   
	$urlsss1="exports.php?session=$session&filename=$filename";
	$urlsss2="exports.php?session=$session&filename=".$year1."fbschedules.txt";
   }
   else {
	   $urlsss1="#";
		$urlsss2="#";
	   
   }
   
   echo "<a target=\"_blank\" href=\"$urlsss1\">Your $year1-$year2 FB Schedule (to be released ".date("F j",$DATESreleaseSEC).", $year1, at 9:00am CST)</a><br><br>";
   echo "<a target=\"_blank\" href=\"$urlsss2\">All $year1-$year2 FB Schedules (to be released ".date("F j",$DATESreleaseSEC).", $year1, at 9:00am CST)</a>";
   echo "</div><br>";
   }
   /***** END FB SCHEDULE LINKS *****/

   echo "</caption>";
   echo "<tr align=left><td><font style=\"font-size:9pt;color:blue\"><b>NOTE: Click a heading to open/close that section.</b></td></tr>";

   /******INBOX/OUTBOX: Reminders, Messages, Downloads******/
   echo "<tr align=center><td>";
   echo "<div id=\"menu1header\" class=\"menuheader\" onclick=\"javascript:Tree.toggle('menu1');\">";
   echo "[ + ] INBOX/OUTBOX: Reminders, Messages, Downloads, etc.</div>";
   echo "<div id=\"menu1\"><br></div>";

   /******AD RULES MEETING******/
	/* DEACTIVATED IN FALL 2012
   echo "<div id=\"menu11header\" class=\"menuheader\" onClick=\"javascript:Tree.toggle('menu11');\">";
   $sql="SELECT * FROM $db_name2.rulesmeetingdates WHERE sport='ad'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $start=split("-",$row[startdate]); $end=split("-",$row[enddate]);
   $startsec=mktime(0,0,0,$start[1],$start[2],$start[0]); $endsec=mktime(0,0,0,$end[1],$end[2],$end[0]);
   echo "[ + ] AD Rules Meeting <i>(Available ".date("F j",$startsec)." through ".date("F j, Y",$endsec).")</i></div>";
   echo "<div id=\"menu11\"><br><table width='500px'><tr align=left><td>The \"AD Rules Meeting\" section contains the link to view the AD Rules Meeting online for credit and to review later for reference.<br><br></td></tr></table></div>";
	*/
 
   /******DISTRICT HOST INFORMATION******/ 
   echo "<div id=\"menu2header\" class=\"menuheader\" onclick=\"javacript:Tree.toggle('menu2');\">";
   echo "[ + ] District Host Information:&nbsp;".GetHostHeader($session)."</div>";
   echo "<div id=\"menu2\"><br><table width=500><tr align=left><td>The \"District Host Information\" section contains information (including the host contract) on any district competition you are hosting.<br><br></td></tr></table></div>";

   /*****RULES MEETING HOST INFORMATION*****/
	/*
   echo "<div id=\"menu11header\" class=\"menuheader\" onclick=\"javascript:Tree.toggle('menu11');\">";
   echo "[ + ] Rules Meeting Host Information:&nbsp;".GetRulesHeader($session)."</div>";
   echo "<div id=\"menu11\"><br><table width=500><tr align=left><td>The \"Rules Meeting Information\" section contains information (including the host contract) on any rules meetings you are hosting.<br><br></td></tr></table></div>";
	*/

   /*****SUPERVISED TEST HOST INFORMATION*****/
	/* NOT IN USE AS OF FALL 2011
   echo "<div id=\"menu10header\" class=\"menuheader\" onclick=\"javascript:Tree.toggle('menu10');\">";
   echo "[ + ] Supervised Test Host Information:&nbsp;".GetSupTestHeader($session)."</div>";
   echo "<div id=\"menu10\"><br><table width=500><tr align=left><td>The \"Supervised Test Host Information\" section contains information (including the host contract) on any supervised tests you are hosting.<br><br></td></tr></table></div>";
	*/

   /*****ELIGIBILITY*****/
   echo "<div id=\"menu4header\" class=\"menuheader\" onclick=\"javascript:Tree.toggle('menu4');\">";
   echo "[ + ] Eligibility:</div>";
   echo "<div id=\"menu4\"><br><table width=500><tr align=left><td><br>";
   echo "The \"Eligibility\" section contains the Advanced Search on your students as well as the Search-by-Sport feature.<br><br></td></tr></table></div>";

   /*****ACTIVITY SELECT*****/
   echo "<div id=\"menu3header\" class=\"menuheader\" onclick=\"javascript:Utilities.getElement('menu3sport').value='';Tree.toggle('menu3');\">";
   echo "[ + ] Activity Select (Entry Forms):</div>";
   echo "<form method=post action=\"welcome.php\"><input type=hidden name=session value=\"$session\"><input type=hidden name=toggle value=\"menu3\">";
   echo "<select name=\"menu3sport\" id=\"menu3sport\"><option value=''>Select an Activity</option>";
   if($menu3sport=="Track") $menu3sport="Track & Field";
   for($i=0;$i<count($act_long);$i++)
   {
      if(preg_match("/6\/8/",$act_long[$i]))       //only show Football, not 6/8 or 11
      {
         echo "<option value=\"Football\"";
         if($menu3sport=="Football") echo " selected";
         echo ">Football</option>";
      }
      else if(preg_match("/Instrumental/",$act_long[$i])) //only show Music, not Instrumental or Vocal
      {
         echo "<option value=\"Music\"";
         if($menu3sport=="Music") echo " selected";
         echo ">Music</option>";
      }
      else if(!preg_match("/ 11/",$act_long[$i]) && !preg_match("/Vocal/",$act_long[$i]) && !preg_match("/Debate/",$act_long[$i]))
      {
         echo "<option value=\"$act_long[$i]\"";
         if($menu3sport==$act_long[$i]) echo " selected";
         echo ">$act_long[$i]</option>";
      }
   }
   echo "</select>&nbsp;<input type=submit name=menu3go id=\"menu3go\" value=\"Go\"></form>";
   echo "<div id=\"menu3\"><table width=500><tr align=left><td>The \"Activity Select\" section contains any forms related to the activity you select.  Examples include Wildcard Schedules, District Entry Forms, Financial Reports, and Applications to Host District/Subdistrict Events.<br><br></td></tr></table></div>";

   /*****NSAA OFFICIALS & JUDGES*****/
   echo "<div id=\"menu5header\" class=\"menuheader\" onclick=\"javascript:Tree.toggle('menu5');\">[ + ] NSAA Officials & Judges:</div>";
   echo "<div id=\"menu5\"><table width=500><tr align=left><td><br>The \"NSAA Officials & Judges\" section contains Rosters of NSAA Officials & Judges as well as Officials & Judges Ballot forms.<br><br></td></tr></table></div>";

   /*****SANCTIONS*****/
   echo "<div id=\"menu12header\" class=\"menuheader\" onclick=\"javascript:Tree.toggle('menu12');\">[ + ] Application for Sanction of Events:<a name=\"sanctions\">&nbsp;</a></div>";
   echo "<div id=\"menu12\"><table width=500><tr align=left><td><br>The \"Applications for Sanction of Events\" section is where you submit applications for sanction of Interstate/International Athletic and Fine Arts Events. You will be able to monitor the status of your application until final action is made by the NSAA.<br><br></td></tr></table></div>";

   /*****REQUESTS*****/
   echo "<div id=\"menu9header\" class=\"menuheader\" onclick=\"javascript:Tree.toggle('menu9');\">[ + ] Requests for Coaches, Contests, Equipment and Officials:<a name=\"requests\">&nbsp;</a></div>";
   echo "<div id=\"menu9\"><table width=500><tr align=left><td><br>The \"Requests for Coaches, Contests, Equipment and Officials\" section is where you may enter new requests for your school, request renewal for your ads that are about to expire, and view submitted requests.<br><br></td></tr></table></div>";

   /*****PROPOSALS*****/
   echo "<div id=\"menu6header\" class=\"menuheader\" onclick=\"javascript:Tree.toggle('menu6');\">[ + ] Proposals for Change in NSAA Regulations:</div>";
   echo "<div id=\"menu6\"><table width=500><tr align=left><td><br>The \"Proposals for Change in NSAA Regulations\" section contains (if available) the link to submit a proposal, the link to view all proposals submitted to the NSAA, as well as the proposals your school has submitted.<br><br></td></tr></table></div>";

   /*****EJECTION REPORTS*****/
   echo "<div id=\"menu7header\" class=\"menuheader\" onclick=\"javascript:Tree.toggle('menu7');\">[ + ] Ejection Reports:</div>";
   echo "<div id=\"menu7\"><table width=500><tr align=left><td><br>The \"Ejection Reports\" section contains the link to submit and Ejection Report as well as any ejection reports your school has submitted to the NSAA.<br><br></td></tr></table></div>";

   /*****OTHER FORMS*****/
   echo "<div id=\"menu8header\" class=\"menuheader\" onclick=\"javascript:Tree.toggle('menu8');\">[ + ] Other Forms:</div>";
   echo "<div id=\"menu8\"><table width=500><tr align=left><td><br>The \"Other Forms\" section contains links to your School Directory, the Fall Declaration Form (if applicable) and the NCPA Academic All-State Nomination Form.<br><br></td></tr></table></div>";
    
   echo "</td></tr>";
   echo "</table>";
?>
<div id="loading" style="display:none;"></div>
<?php
}//end if level==2
else if($level==3)	//Coach (Level 3) Access
{
   //get form due date
   $sport=GetActivity($session);
   $abbrev=GetActivityAbbrev2($sport);
   if(preg_match("/Music/",$sport) || $sport=="Orchestra")
      $abbrev="mu";
   if(preg_match("/te/",$abbrev))
   {
      $class=GetClass(GetSID2($school,$abbrev,date("Y")),$abbrev,date("Y"));
      if($class=="A")
      {
         $form_date=GetDueDate($abbrev."state");
         $formtype="State";
      }
      else
      {
	 $form_date=GetDueDate($abbrev);
	 $formtype="District";
      }
   }
   else if($abbrev=='wr')
   {
      $form_date=GetDueDate('wrd');
   }
   else if($abbrev=='jo')
      $form_date=GetDueDate('jo_contest');
   else
   {
      $form_date=GetDueDate($abbrev);
      $formtype="District";
   }
   echo "<br><table width=75% cellspacing=0 cellpadding=0>";
   echo "<caption><b>Welcome, $name!<br>";
   if(preg_match("/Football/",$sport)) echo "Football";
   else echo $sport; 
   if(preg_match("/Music/",$sport) || $sport=="Orchestra" || $sport=="Journalism" || $sport=="Debate" || $sport=="Speech" || $sport=="Play Production")
      echo " Director";
   else echo " Coach";
   if($main!="") 
      echo "<br>($main)";
   echo "<br>Today's Date is $date</b><br><br>";

   //WILDCARD SCORES DASHBOARD
   $abbrev=GetActivityAbbrev2($sport);
   $abbrev=preg_replace("/(_)/","",$abbrev);
   if(preg_match("/fb/",$abbrev)) $abbrev="fb";
   echo GetScoresDashboard($session,$abbrev);

   echo "</caption>";

   /******INBOX: Reminders, Messages, Downloads******/
   echo "<tr bgcolor='#E0E0E0' align=left><th align=left>&nbsp;&nbsp;";
   echo "INBOX: Reminders, Messages, Downloads:</a></th></tr>";

      echo "<tr align=center><td><table>";
      echo "<tr align=left><td><a class=small href=\"changepassword.php?session=$session\">Change your Password</a><br><br></td></tr>";
  
      //Reminders:
      $reminder=0;
      echo "<tr align=left><th align=left bgcolor=#E0E0E0>";
      echo "&nbsp;&nbsp;Reminders:</th></tr>";
      //if it is <= 1 week before their form's due date, give them a reminder:
      $act=GetActivityAbbrev2($sport);
      if(DueSoon($form_date) && !PastDue($form_date,0) && $act!="pp" && $act!="sp" || $act=="jo")
      {
         $reminder=1;
         $num=CountDistEntries($school,$act);
         echo "<tr align=center><td><br><table>";
         echo "<tr align=left><td><font size=2>";
         if(PastDue($form_date,0))
         {
            $date=split("-",$form_date);
            echo "Your entry form was due on <b>$date[1]/$date[2]/$date[0]</b>.<br>";
            echo "Make sure the information you submitted was correct";
            echo " by clicking on \"View/Edit your Form\" below.";
         }
         else if($month==$cur_mo && $day==$cur_day && $year==$cur_yr)
         {
            echo "Your entry form is due by <b>midnight</b> tonight!!<br>".GetFormsReminder($session);
            //echo "You have entered <b>$num</b> students on your roster.<br>";
            echo "Please complete all required information by the deadline.";
         }
         else if(DueSoon($form_date))
         {
	    $date=split("-",$form_date);
	    $duedate="$date[1]/$date[2]/$date[0]";
            echo "Your $formtype entry form is due <b>$duedate</b>!<br>".GetFormsReminder($session);
            //echo "You have entered <b>$num</b> students on your roster.<br>";
            echo "Please make sure all information is complete by the deadline.<br>";
         }
         echo "</font></td></tr></table><br>";
         echo "</td></tr>";
      }//end if close to due date
      $curmo=date("n",time());
      if($curmo>=8 && $curmo<12 && preg_match("/Football/",$sport))	//remind to update football stats
      {
         $reminder=1;
         echo "<tr align=center><th class=smaller><br>";
         echo "Don't forget to <a href=\"fb/view_fb_stats.php?session=$session\" class=small>Update Your Team Statistics!</a><br><br></td></tr>";
      }
      if($reminder==0)
         echo "<tr align=center><td>[You have no reminders.]</td></tr>";

      //Messages:
      echO "<tr bgcolor=#E0E0E0 align=left>";
      echo "<th align=left>&nbsp;&nbsp;Messages:</th></tr>";
	  //if(PastDue("2018-02-7",-1)){
	 /*	
	  if(time()>1518512400){
	  echo "<tr align=left><td><a class=small href=\"../calculate/wildcard/fbschedules.php?session=$session\">Your 2018 & 2019 Football Schedules</a></td></tr>";
	  echo "<tr align=left><td><a class=small href=\"../calculate/wildcard/fbschedules.php?session=$session&file=remaining\" target=\"_blank\"><div>2018 & 2019 Football Schedules</div></a></td></tr>"; 
      }else{
	  echo "<tr align=left><td><a class=small href=\"#\">Your 2018 & 2019 Football Schedules</a></td></tr>";
	  echo "<tr align=left><td><a class=small href=\"#\"><div>2018 & 2019 Football Schedules</div></a></td></tr>"; 
      }
	  
	  */
      echo "<tr align=center><td>";
      //get number of messages from the AD
      $sql="SELECT * FROM messages WHERE school='$school2' AND sport='$sport'";
      $result=mysql_query($sql);
      $ct=mysql_num_rows($result);
      echo "<br><a class=small href=\"view_messages.php?session=$session\">You have $ct";
      if($ct==1) echo " Message ";
      else echo " Messages ";
      echo "from your AD";
      //get number of messages from NSAA
      $sql="SELECT * FROM messages WHERE school='All' AND sport='$sport'";
      $result=mysql_query($sql);
      $ct=mysql_num_rows($result);
      echo " and $ct ";
      if($ct==1) echo "Message";
      else echo "Messages";
      echo " from the NSAA";
      echo "</a><br><br></td></tr>";
    
      //Downloads:
      echo "<tr bgcolor=#E0E0E0 align=left><th align=left>&nbsp;&nbsp;";
      echo "Downloads & Links:</a></th></tr>";
         echo "<tr align=center><td><table><tr align=left><td>";
         if($sport=="Wrestling")
	 {
	    echo "<a href=\"wrassessor/wrassessors.php?session=$session\" target=\"_blank\">List of Registered Wrestling Assessors</a><br><br>";
	 }
      
         if(preg_match("/Football/",$sport)) $sport2="Football";
         else $sport2=$sport;
         $sql="SELECT * FROM downloads WHERE active='y' AND recipients LIKE '%$sport2%' ORDER BY doctitle";
         $result=mysql_query($sql);
         if(preg_match("/Swimming/",$sport))
         {
            $sql2="SELECT * FROM sw_hy3files";
            $result2=mysql_query($sql2);
            if(mysql_num_rows($result2)>0)
               echo "<a class=small href=\"downloadhy3.php?session=$session\">Download Swimming Hytek Roster Files</a><br><br>";
         }
         while($row=mysql_fetch_array($result))
         {
	    $row[filename]=preg_replace("/(www.)/","",$row[filename]);
            echo "<a class=small target=_\"blank\" href=\"$row[filename]\">$row[doctitle]</a><br>"; 
         }
         if($sport=="Speech" || $sport=="Play Production")
         {
            $sql="SELECT * FROM $db_name2.rosters WHERE sport='$abbrev'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);
            if($row[active]=='x')	//show this year's roster
            {
               echo "<a class=small target=new href=\"officials/jroster.php?session=$session&list=$abbrev&ad=1\">Roster of $curyear $sport Judges</a><br>";
            }
            if($row[showold]='x')	//show last year's roster
            {
	       echo "<a class=small target=new href=\"officials/jroster.php?session=$session&list=$abbrev&ad=1&archive=$archivedbroster\">Roster of $lastyear $sport Judges</a><br>";
            }
         }
         else 
         {
            $abbrev2=GetActivityAbbrev($sport);
            if(preg_match("/fb/",$abbrev2)) { $sport2="Football"; $abbrev2="fb"; }
            else $sport2=$sport;
            $sql="SELECT * FROM $db_name2.rosters WHERE sport='$abbrev2'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);
            if($row[active]=='x')
            {
 	       echo "<a class=small target=new href=\"officials/roster.php?session=$session&sport=$abbrev2&ad=1\">Roster of $curyear $sport2 Officials</a><br>";
            }
            if($row[showold]=='x')
            {
	       echo "<a class=small target=new href=\"officials/roster.php?session=$session&sport=$abbrev2&ad=1&archive=$archivedbroster\">Roster of $lastyear $sport2 Officials</a><br>";
            }
         } 
         if(preg_match("/Music/",$sport) || $sport=="Orchestra")
	 {
            echo "<a target=new href=\"mu/mujudges.php?session=$session\">List of NSAA Music Judges</a><br><a href=\"https://nsaahome.org/textfile/music/manual.pdf\" target=\"_blank\">CURRENT NSAA MUSIC MANUAL</a>";
    	 }
         echo "</td></tr></table></td></tr>";
         echo "</table></td></tr>";

         if(IsReportCardSchool($school) && preg_match("/Basketball/",$sport))
         {
            //GAME REPORT CARDS
            if($sport=="Boys Basketball") $abbrev="bbb";
            else if($sport=="Girls Basketball") $abbrev="bbg";
            $schedtbl=$abbrev."sched";
            $tourntbl=$abbrev."tourn";
            $reportcard="reportcard_".$abbrev;
            $year=GetFallYear($abbrev);
            $sid=GetSID($session,$abbrev);
            if($sid!="NO SID FOUND")
            {
               echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;Game Report Cards:</th></tr>";
	       echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;<b>NOTE:</b> Report cards will be filled out for games between January 19, 2007 and February 10, 2007.</td></tr>";
               echo "<tr align=center><td><table>";
               echo "<tr align=left><td colspan=4><a class=small href=\"reportcards.php?sport=$abbrev&session=$session&finished=1\">See Your School's Submitted Report Cards</a></td></tr>";
               echo "<tr align=left><td colspan=4><br><b><u>Unfinished</u> Game Report Cards:</b></td></tr>";
               $today=date("Y-m-d");
               $now=time(); $feb10=mktime(23,59,59,2,10,2007);
               if($feb10<$now)
                  $today="2007-02-10";
               $sql="SELECT t1.*,t2.scoreid as gameid FROM $schedtbl AS t1 LEFT JOIN $reportcard AS t2 ON t1.scoreid=t2.scoreid WHERE ((t2.school='$school2' AND t2.datesub='') OR t2.id IS NULL) AND t1.received<='$today' AND t1.received>='2007-01-19' AND (t1.sid='$sid' OR t1.oppid='$sid') AND t1.oppid!='0' ORDER BY t1.received";
               $result=mysql_query($sql);
               if(mysql_num_rows($result)>0)
               {
                  echo "<tr align=left><td>&nbsp;</td><td><b>Date</b></td><td><b>Opponent & Site</b></td><td><b>Score</b></td></tr>";
                  while($row=mysql_fetch_array($result))
                  {
                     if($sid==$row[sid])
                     {
                        $oppid=$row[oppid];
                        $oppvargame=$row[oppvargame];
                        $score="$row[sidscore]-$row[oppscore]";
                     }
                     else
                     {
                        $oppid=$row[sid];
                        $oppvargame=$row[sidvargame];
                        $score="$row[oppscore]-$row[sidscore]";
                     }
                     $oppname=GetSchoolName($oppid,$abbrev,$year);
                     $host=GetSchoolName($row[homeid],$abbrev,$year);
                     if($row[tid]!='0')
                     {
                        $sql2="SELECT name FROM $tourntbl WHERE tid='$row[tid]'";
                        $result2=mysql_query($sql2);
                        $row2=mysql_fetch_array($result2);
                        $host=$row2[0];
                     }
                     $temp=split("-",$row[received]);
                     $date="$temp[1]/$temp[2]/$temp[0]";
                     if($oppid!='0')
                     {   
                        echo "<tr align=left><td>";
                        if($row[gameid])
                           echo "<a class=small href=\"reportcard.php?session=$session&scoreid=$row[gameid]\">Edit</a>";
                        else
                        {
                           if($finished==1) $word="View";
                           else $word="Begin";
                           echo "<a class=small href=\"reportcard.php?session=$session&scoreid=$row[scoreid]\">$word</a>";
                        }
                        echo "</td><td>$date</td><td>vs. $oppname @ $host</td><td>$score</td></tr>";
                     }   
                  }//end for each game
               }//end if games found
	       else echo "<tr align=center><td colspan=4>[You have no unfinished game report cards.]</td></tr>";
               echo "</table></td></tr>";
            }//end if sid found
         }//end game report cards

   /********MUSIC SITE DIRECTORS OR MUSIC DISTRICT COORDINATORS************/
   $schoolid=GetSchoolID($session); $loginid=GetUserID($session);
   $musiteid=GetMusicSiteID($schoolid);
   $mudistid=GetMusicDistrictID($schoolid,$loginid);
   if((preg_match("/Music/",$sport) || $sport=="Orchestra") && ($musiteid>0 || $mudistid>0))
   {
      echo "<tr bgcolor=#E0E0E0 align=left><th align=left>&nbsp;&nbsp;District Music Contest Host & Coordinator Information:</th></tr>";
      echo "<tr align=center><td><br><table><tr align=left><th>";
      if($musiteid>0)	//Site DIRECTOR
      {
         $sql="SELECT * FROM mudistricts WHERE id='$musiteid'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         echo "You are the Site Director for District $row[distnum] -- $row[classes] at $row[site]:<br><br>";
         echo "<a href=\"mu/musiteadmin.php?session=$session\">District $row[distnum] -- $row[classes] Submitted Entry Forms</a><br><br>";
	 echo "<a href=\"mu/scertsadmin.php?session=$session&name=$name\">District $row[distnum] -- $row[classes] Superior Award Certificate Generation<br><br>";
	 if($row[certificates]=='x')
	    echo "<a href=\"mu/viewawardwinners.php?session=$session\">Reporting and Printing Outstanding Performance and Honorable Mention Awards</a><br><br>";
         echo "<a target=new href=\"mu/mujudges.php?session=$session\">List of NSAA Music Judges</a><br><br><a href=\"https://nsaahome.org/textfile/music/manual.pdf\" target=\"_blank\">CURRENT NSAA MUSIC MANUAL</a><br><br>";
	 //FINANCIAL REPORT 
	    $sql2="SELECT * FROM finrpt_entry WHERE district_id='$musiteid'";
	    $result2=mysql_query($sql2);
	    if(mysql_num_rows($result2)==0)
	    {
	       echo "<a href=\"mu/dmcfinrptform.php?session=$session\">Begin working on your NSAA District Music Contest Financial Report</a>";
	    }
	    else
	    {
	       $row2=mysql_fetch_array($result2);
	       if($row2[datesub]==0)
	          echo "<a href=\"mu/dmcfinrptform.php?session=$session&editid=$row2[id]\">Continue working on your NSAA District Music Contest Financial Report</a><br><p style=\"font-weight:normal;\"><i>Your financial report has NOT been submitted to the NSAA or the District Music Coordinator yet.<br>Click the link above to finish your report and submit it as final.</i></p>";
	       else	//SUBMITTED
	          echo "<p style=\"font-weight:normal;\">You submitted your <b>NSAA District Music Contest Financial Report</b> on ".date("F j, Y",$row2[datesub]).".</i><ul><li><a target=\"_blank\" href=\"mu/dmcfinrptform.php?session=$session&editid=$row2[id]&print=1\">View/Print your Submitted NSAA District Music Contest Financial Report</a></li></ul></p>";
	    }
      }
      if($mudistid>0)
      {
         $sql="SELECT * FROM mubigdistricts WHERE id='$mudistid'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
	 if($musiteid>0) echo "<hr>";
	 $distnum = $row[distnum];
	 echo "You are the District Coordinator for District $row[distnum]:<br><br>";
         echo "<a href=\"mu/mudistadmin.php?session=$session\">District $row[distnum] Submitted Entry Forms</a><br><br>";
	 if($row[certificates]=='x')
	 {
            echo "<form method=\"post\" action=\"mu/viewawardwinners.php\"><input type=hidden name=\"session\" value=\"$session\">";
            echo "<font style=\"font-size:9pt;\"><b>Report & Print NSAA District Music Contest Outstanding Performance Award Certificates:</b><br></font>";
            echo "<select name=\"siteid\"><option value='0'>Select District Site</option>";
            $sql="SELECT t1.* FROM mudistricts AS t1,mubigdistricts AS t2 WHERE t1.distnum=t2.distnum AND t2.id='$mudistid' ORDER BY t1.classes";
            $result=mysql_query($sql);
            while($row=mysql_fetch_array($result))
            {
               echo "<option value=\"$row[id]\"";
               if($siteid==$row[id]) echo " selected";
               echo ">$row[distnum] -- $row[classes] at $row[site]</option>";
            }
            echo "</select>&nbsp;<input type=submit name=\"go\" value=\"Go\">";
            echo "</form>";
	 }
         if(!$musiteid)
	    echo "<a target=new href=\"mu/mujudges.php?session=$session\">List of NSAA Music Judges</a><br><br><a href=\"https://nsaahome.org/textfile/music/manual.pdf\" target=\"_blank\">CURRENT NSAA MUSIC MANUAL</a><br><br>";
	 //SUBMITTED FINANCIAL REPORTS FOR THIS BIG DISTRICT:
            echo "<a href=\"mu/dmcfinrptadmin.php?session=$session\">District $distnum Submitted Financial Reports</a>";
      }
      echo "<hr>To complete YOUR SCHOOL'S District Music Contest Entry Form, use the link below, under \"Entry Form(s).\"<br><br>";
      echo "</th></tr></table></td></tr>";
   }

   /******ELIGIBILITY******/
   echo "<tr bgcolor=#E0E0E0 align=left><th align=left>&nbsp;&nbsp;Eligibility:</th></tr>";
   
      echo "<tr align=center><td><br><table><tr align=left><td>";
      if($sport=="Orchestra" || preg_match("/Music/",$sport)) $team="Program";
      else $team="Team";
      echo "<a href=\"elig_view.php?session=$session\">View your $team's Eligibility List</a><br><br>";
      echo "</td></tr></table></td></tr>";

   /******GOLF COACH - REGULAR SEASON TOURNAMENT RESULTS******/
   $sid=GetSID($session,GetActivityAbbrev2($sport));
   $class=GetClass($sid,GetActivityAbbrev2($sport));
   if(preg_match("/Golf/",$sport) && $sid!="NO SID FOUND")
   {
      if($class=="A" || $school=="Test's School")
      {

         echo "<tr bgcolor=#E0E0E0 align=left><th align=left>&nbsp;&nbsp;$sport Regular Season Tournament Results:</th></tr>";
          $sport_ch=GetActivityAbbrev2($sport);
          if($sport_ch=="go_g"){
              $sport_ch="gog";
          }else if ($sport_ch=="go_b"){
              $sport_ch="gob";
          }
          echo "<tr bgcolor=#E0E0E0 align=left><th align=left>&nbsp;&nbsp;$sport Regular Season Tournament Results:</th></tr>";
          echo "<tr align=center><td><br>".GetGolfSeasonReportDash($sport_ch,$session,$sid)."</td></tr>";
   	echo "<tr align=center><td><p style=\"border-top:#808080 1px dotted;margin-top:20px;padding:10px;;\"><a href=\"go/teamrankings.php?sport=".GetActivityAbbrev2($sport)."\" target=\"_blank\">$sport Differential</a></p></td></tr>";
      }
      if($distid=SchoolDoesHost($session,GetActivityAbbrev2($sport)))
      {
         echo "<tr bgcolor=#E0E0E0 align=left><th align=left>&nbsp;&nbsp;$sport District Tournament Results:</th></tr>";
	 $sql="SELECT * FROM $db_name2.".GetActivityAbbrev2($sport)."districts WHERE id='$distid'";
	 $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
         $date=$row[dates];   //ONE DAY FOR GO DISTRICTS
         if(PastDue($date,-5) && $date!="" && !preg_match("/00-00/",$date))
         {
             echo "<tr align=center><td><br><a href=\"go/districtresults.php?distid=$row[id]&sport=".GetActivityAbbrev2($sport)."&session=$session\">District $row[class]-$row[district] Results Form</a></td></tr>";
         }
         else if($date!="" && !preg_match("/00-00/",$date) && !PastDue($date,-5))
         {
             $date2=explode("-",$date);
             $datesec=mktime(0,0,0,$date2[1],$date2[2],$date2[0]);
             $seesec=$datesec-(4*24*60*60);
             echo "<tr align=center><td><br>Your <b>District $row[class]-$row[district] Results Forms</b> will be available on <b>".date("F j, Y",$seesec)."</b>.<br><br></td></tr>";
         }
      }
   }

   /******WILDCARD - SCORES******/
   $abbrev=preg_replace("/_/","",GetActivityAbbrev2($sport));
   if(preg_match("/fb/",$abbrev)) $abbrev="fb";
   $sportname=GetActivityName($abbrev);
   $sid=GetSID($session,$abbrev);
   if($sid!="NO SID FOUND" && IsWildcardSport($abbrev) && $abbrev!='wr')
   {
	//echo "<tr bgcolor='#e0e0e0' align=left><th align=left>&nbsp;&nbsp;$sportname Score Entry:</th></tr>";
	//echo "<tr align=center><td><br><a href=\"../calculate/wildcard/editscores.php?session=$session&sport=".$abbrev."\">Enter $sportname Scores for This Season</a><br><br></td></tr>";
   }
   //else echo "$sid - $abbrev";
   /******ENTRY FORMS******/
      echo "<tr bgcolor=#E0E0E0 align=left><th align=left>&nbsp;&nbsp;Entry Form(s):</th></tr>";
      echo "<tr align=center>";
      //get abbrev of activity
      $dir=GetActivityAbbrev($sport);
      $file=GetActivityAbbrev2($sport);
      if(preg_match("/Music/",$sport) || $sport=="Orchestra") { $file="mu"; $dir="mu"; }
      if(preg_match("/fb/",$dir)) $dir="fb";
      if(preg_match("/fb/",$file)) $file="fb";
      echo "<td><br>";
      if((preg_match("/Swimming/",$sport) && !PastDue(GetDueDate('sw'),0)) || !preg_match("/Swimming/",$sport))
      {
      if($file=='mu') echo "<a href=\"$dir/muhome.php?session=$session\">";
      else if($sport=="Journalism") echo "<a href=\"jo/stateentry.php?session=$session\">";
      else if(preg_match("/Tennis/",$sport)) echo "<a href=\"$dir/main_$file.php?session=$session\">";
      else if($sport=="Wrestling") echo "<a href=\"wr/view_wrd.php?session=$session\">";
      else echo "<a href=\"$dir/view_$file.php?session=$session\">";
      if(preg_match("/Tennis/",$sport)) echo "View/Edit your Forms";
      else if(preg_match("/Swimming/",$sport))
         echo "Verification Forms";
      else if(preg_match("/Music/",$sport))
	 echo "District Music Contest Entry Form";
      else if($sport=="Journalism")
         echo "NSAA Journalism Contest Entry Submission (Preliminaries)";
      else if($sport=="Wrestling")
         echo "Dual Wrestling Roster & Schedule Form";
      else
         echo "View/Edit your Form";
      echo "</a>";
      }
      if(!preg_match("/Swimming/",$sport))
      {
         $date=split("-",$form_date);
         $duedate="$date[1]/$date[2]/$date[0]";
         $date=split("-",$form_date);   //DUE DATE OF FORM
         echo "<br><font style=\"font-size:10pt;\">due date:<b>&nbsp;$date[1]/$date[2]/$date[0]</b><br></font>";
         if(preg_match("/Music/",$sport))
            echO "<i>(You may make changes to this form until submitted or up until midnight on this day.)</i>";
         else
            echo "<i>(If you make changes to your form after this day, please notify your district director.)</i>";
      }
      if($sport=="Journalism")
      {
	 //if(PastDue(GetDueDate('jo_contest'),30) && date("m")<6)
            //echo "<br><br><a href=\"jo/createcertificate.php?session=$session\">Download Certificates (Top 3 in Preliminaries AND State Qualifiers)</a>";
	 echo "<br><br><a href=\"jo/view_jo.php?session=$session\">Journalism State Entry Form (Finals)</a>";
      }
      else if(preg_match("/Swimming/",$sport))
      {
	 //check if already submitted form
	 if(preg_match("/Girls/",$sport)) $field="stateform_g";
	 else $field="stateform_b";
         $sql="SELECT id FROM headers WHERE school='$school2'";
         $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);	
	 $schid=$row[id];
	 $sql="SELECT $field FROM swschool WHERE mainsch='$schid' OR othersch1='$schid' OR othersch2='$schid' OR othersch3='$schid'";
	 $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 if($row[0]!="")
	 {
	    echo "<br><br><a class=small href=\"sw/sw_state_view_";
            if(preg_match("/Girls/",$sport)) echo "g";
	    else echo "b";
	    echo ".php?session=$session\">State Swimming Entry Form</u> (submitted ";
	    echo date("M d, Y",$row[0]);
	    echo ")</a>";
	 }
	 else if(mysql_num_rows($result)>0)
	 {
	    $date=split("-",GetDueDate("sw_state"));
	    $swdue="$date[1]/$date[2]/$date[0]";
            if(PastDue($date,0))
	    {
	       echo "<br><label style=\"font-size:12px;color:#666666;\"><b><u>State Swimming Entry Form</u></b></label> (locked $swdue)";
	    }
	    else
	    {
	       echo "<br><a href=\"sw/sw_state_edit_";
	       if(preg_match("/Girls/",$sport)) echo "g";
	       else echo "b";
	       echo ".php?session=$session\">State Swimming Entry Form</a> (due $swdue by NOON)";
	    }
	 }
         echo "<br><br><a href=\"statepartcerts.php?session=$session\">State Participation Certificates</a>";
      }
      if($sport=="Play Production")
      {
         echo "<br><br><a href=\"pp/createcertificate.php?session=$session\">Create & Print DISTRICT PLAY PRODUCTION AWARD CERTIFICATES</a><br><br>";
         echo "<a href=\"statepartcerts.php?session=$session\">Create & Print STATE PLAY PRODUCTION PARTICIPATION CERTIFICATES</a>";
      }
      else if($sport=="Speech")
      {
	 echo "<br><br><b>Create & Print <u>SPEECH AWARD CERTIFICATES</u> for YOUR STUDENTS:</b> ";
	 echo "<a href=\"sp/createcertificate.php?session=$session\">District</a>&nbsp;|&nbsp;";
         echo "<a href=\"sp/createcertificate.php?session=$session&state=1\">State</a>";
	 echo "<br><br><b>Create & Print <u>STATE SPEECH PARTICIPATION CERTIFICATES</u> for YOUR STUDENTS: <a href=\"statepartcerts.php?session=$session\">State Speech PARTICIPATION Certificates</a>";
      }
      else if(preg_match("/Music/",$sport))
      {
	echo "<br><br><a href=\"anthem.php?school=".$school."&session=$session\">Upload National Anthem Singer</a>";
	echo "<br><br><a href=\"anthem_list1.php?school=".$school."&session=$session\">Submitted Anthem list</a>";
	 echo "<br><br><a href=\"mu/superiorcerts.php?session=$session\">District Music Contest Superior Award Certificates</a>";
      }
      echo "<br><br>";

      //if coach's school is hosting speech districts, show host info & link to state form
      $sql="SELECT * FROM $db_name2.spdistricts WHERE hostschool='$school2' AND post='y' AND accept='y' AND confirm='y'";
      $spresult=mysql_query($sql);
      while($row=mysql_fetch_array($spresult))
      {
         if(preg_match("/00-00/",$row[dates])) $row[dates]="";
         if($sport=="Speech" && PastDue($row[dates],-2) && $row[dates]!='')
 	 {
            echo "<a href=\"sp/sp_state_edit.php?distid=$row[id]&session=$session\">$row[class]-$row[district] Speech District Results Form</a><br><br>";
	 }
      } 

      //if coach's school is hosting track districts, show link to forms
      if(preg_match("/Track/",$sport))
      {
	 $sql="SELECT * FROM $db_name2.trdistricts WHERE hostschool='$school2' AND post='y' AND accept='y' AND confirm='y' ORDER BY class,district";
	 $result=mysql_query($sql);
	 while($row=mysql_fetch_array($result))
	 {
	    $date=split("-",$row[dates]);
	    $showdate="$date[0]-04-06";
	    if(PastDue($showdate,-1) && $row[dates]!='')
	    {
	       echo "<a href=\"tr/tr_state_edit_b.php?session=$session&distid=$row[id]\">Boys District $row[class]-$row[district] Track & Field Results</a><br>";
	       echO "<a href=\"tr/tr_state_edit_g.php?session=$session&distid=$row[id]\">Girls District $row[class]-$row[district] Track & Field Results</a><br><br>";
	    }
	 }
      }
      echo "</td></tr>";

   /*****HOST INFORMATION*****/
   if($sport=="Speech" && mysql_num_rows($spresult)>0)
   {
      echo "<tr bgcolor=#e0e0e0 align=left><th align=left>&nbsp;&nbsp;Speech District Host Information:</th></tr>";         
      echo "<tr align=center><td><table cellspacing=0 cellpadding=5>";
      $sql="SELECT * FROM $db_name2.spdistricts WHERE hostschool='$school2' AND post='y' AND accept='y' AND confirm='y'";               
      $result=mysql_query($sql);      
      while($row=mysql_fetch_array($result))
      {
         echo "<tr align=left><td><br><b><u>DISTRICT $row[class]-$row[district]:</b></u><br><br><a class=small target=\"_blank\" href=\"officials/hostcontract_sp.php?session=$session&distid=$row[id]\">Speech District Host Contract</a></td></tr>";               
	 echo "<tr align=left><td><a class=small target=\"_blank\" href=\"officials/spshowtoad.php?session=$session&id=$row[id]&sport=sp\">Speech Judges assigned to your District</a></td></tr>";
         $showdate=GetDueDate('sp'.'showentries');                           
	 if(PastDue($showdate,0))
                echo "<tr align=left><td><a class=small href=\"entryforms.php?distid=$row[id]&session=$session&sport=sp\">Speech District Entry Forms submitted by the schools in your district</a></td></tr>";
         $dates=split("/",$row[dates]);
         $date=$dates[0];
         if(PastDue($date,-5) && $row[dates]!='' && !preg_match("/00-00/",$date))
	 {
              echo "<tr align=left><td><a class=small href=\"sp/sp_state_view.php?distid=$row[id]&session=$session\">Speech District Results Entry Form</a></td></tr>";
              echo "<tr align=left><td><a class=small href=\"sp/createdistcert.php?distid=$row[id]&session=$session\">Create & Print District Award Certificates</a></td></tr>";
     	 }
      }
      echo "</table></td></tr>";
   }
	//PLAY HOST:
   if($sport=="Play Production")
   {
      $sql="SELECT * FROM $db_name2.ppdistricts WHERE hostschool='$school2' AND post='y' AND accept='y' AND confirm='y'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
         echo "<tr bgcolor=#e0e0e0 align=left><th align=left>&nbsp;&nbsp;Play Production District Host Information:</th></tr>";
         echo "<tr align=center><td><br><table cellspacing=0 cellpadding=5>";
      }
      while($row=mysql_fetch_array($result))
      {
         echo "<tr align=left><th>Your School is Hosting the <u>$row[type] $row[class]-$row[district]</u>  Play Production Contest</th></tr><tr align=left><td><ul>";
         $duedate=GetDueDate('pp');
         if($duedate!='')
         {
            $showdate=GetDueDate('ppshowentries');
            if(PastDue($showdate,0))
               echo "<li><a href=\"entryforms.php?distid=".$row['id']."&session=$session&sport=pp\">Play Production District Entry Forms submitted by the schools in your district</a></li>";
            echo "<li><a target=\"_blank\" href=\"officials/ppshowtoad.php?session=$session&id=$row[id]&sport=pp\">Judges assigned to $row[type] $row[class]-$row[district]</a></li>";
            echo "<li><a href=\"pp/districtresults.php?session=$session&distid=$row[id]\">Enter District $row[class]-$row[district] Results</a></li>";
         }
         echo "</ul></td></tr>";
      }
      if(mysql_num_rows($result)>0)
         echo "</table></td></tr>";
   } //END IF PLAY

   /*****ONLINE RULES MEETINGS *****/
   $cursp=GetActivityAbbrev($sport); $sportname=$sport;
   if(preg_match("/Football/",$sport))
   {
      $cursp="fb"; $sportname="Football"; //take out 6/8 or 11 designation
   }
   else if(preg_match("/Golf/",$sport))
      $cursp=GetActivityAbbrev2($sport);	//need golf to be gender specific
	/*
   else if($sport=="Speech" || $sport=="Play Production")
   {
      $cursp="sppp"; $sportname="Speech/Play Production";
   }
	*/
   $rmtable=$cursp."rulesmeetings";
   $sql="SHOW TABLES LIKE '$rmtable'";
   $result=mysql_query($sql);
   $sql="SELECT * FROM $db_name2.rulesmeetingdates WHERE sport='$cursp' AND officialsonly!='x'";
   
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)	
   {
      echo "<tr bgcolor=#e0e0e0 align=left><th align=left>&nbsp;&nbsp;Online Rules Meeting:</th></tr>";
      echo "<tr align=center><td><br><a href=\"/powerpoint/Coaches.pps\">Online Rules Meeting Instructions (PowerPoint Slideshow)</a><br><br><table width=\"450px\">";
      $coachid=GetUserID($session);
      $sql2="SELECT * FROM $db_name2.rulesmeetingdates WHERE sport='$cursp'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $ppfile=$row2[ppfile];
      $ct=mysql_num_rows($result2);
      $fee=$row2[fee]; $latefee=$row2[latefee];
      $startdate=$row2[startdate]; $latedate=$row2[latedate]; $enddate=$row2[enddate]; $paydate=$row2[paydate];
      $late=split("-",$latedate); $end=split("-",$enddate); $pay=split("-",$paydate);
      $start=split("-",$startdate); $year=$start[0]; $month=$start[1];
      $sql2="SELECT t1.* FROM $db_name.$rmtable AS t1,logins AS t2 WHERE t1.coachid=t2.id AND t1.coachid='$coachid'";
      $result2=mysql_query($sql2); 
      $row2=mysql_fetch_array($result2);
      if($row2[datepaid]>0) //SCENARIO #1: Already Attended a Rules Meeting for This Sport
      {
         echo "<tr align=left><td>You have already attended a $sportname Rules Meeting and your attendance has been recorded in our system.</td></tr>";
         echo "<tr align=left><td><a href=\"$ppfile\" class=small>Click Here to Download the $sportname Rules Meeting Powerpoint (no audio)</a></td></tr>";
      }   
      else if($startdate=="0000-00-00" || $ct==0)
      {
	 echo "<tr align=left><td>The Online $sportname Rules Meeting will be available during a time period to be announced at a later date.</td></tr>";
      }
      else if($school!="Test's School" && !PastDue($startdate,-1))        //SCENARIO #2: NOT YET AVAILABLE
      {
         echo "<tr align=left><td>The Online $sportname Rules Meeting will be available for <b>NO CHARGE</b> from ".date("F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0]))." until ".date("F j, Y",mktime(0,0,0,$pay[1],$pay[2],$pay[0]))." at midnight, after which the fee will be <b>$".number_format($fee,2,'.','')."</b> until ".date("F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0]))." at midnight, after which the fee will be increased to <b>$".number_format($latefee,2,'.','')."</b>.  The rules meeting will not be available online after ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).".</td></tr>";
         //echo "<tr align=left><td>The <u>Online</u> $sportname Rules Meeting will be available ".date("F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0])).".</td></tr>";
      }
      else if(!PastDue($latedate,0))   //SCENARIO #3: AVAILABLE, NO LATE FEE YET
      {
         echo "<tr align=left><td>The Online $sportname Rules Meeting will be available for <b>NO CHARGE</b> from ".date("F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0]))." until ".date("F j, Y",mktime(0,0,0,$pay[1],$pay[2],$pay[0]))." at midnight, after which the fee will be <b>$".number_format($fee,2,'.','')."</b> until ".date("F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0]))." at midnight, after which the fee will be increased to <b>$".number_format($latefee,2,'.','')."</b>.  The rules meeting will not be available online after ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).".</td></tr>";
         //echo "<tr align=left><td>The Online $sportname Rules Meeting will be available for <b>$".number_format($fee,2,'.','')."</b> until ".date("F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0]))." at midnight, after which the fee will be increased to <b>$".number_format($latefee,2,'.','')."</b>.  The rules meeting will not be available online after ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).".</td></tr>";
         if($row2[datecompleted]>0 && $row2[datepaid]==0)      //COMPLETED BUT NOT PAID
         {
             echo "<tr align=center><td><div class=alert style=\"width:400px;\"><table width=100% cellspacing=1 cellpadding=1><tr align=left><td>You <b>watched</b> this rules meeting video but <b><u>";
	     if($row2[datecompleted] < mktime(23,59,59,$pay[1],$pay[2],$pay[0]))	//NO FEE
	     {
		echo "did NOT verify your attendance"; $payorverify="Verification";
	     }
	     else
	     {
		echo "did NOT pay the fee"; $payorverify="Payment";
	     }
	     echo "</b></u>.</td></tr>";
             echo "<tr align=center><td><a class=small href=\"rulesmeetingpay.php?session=$session&sport=$cursp\">Click HERE to Complete $payorverify for this Rules Meeting</a></td></tr></table></div></td></tr>";
         }
         else if($row2[initiated]>0 && $row2[datecompleted]==0)        //STARTED WATCHING BUT DIDN'T FINISH
         {
            echo "<tr align=left><td>You <b>started watching</b> but <b>did NOT finish</b> the $sportname Rules Meeting Video.</td></tr>";
            echo "<tr align=center><td><a class=small href=\"rulesmeetingintro.php?session=$session&sport=$cursp\">Click HERE to Watch the $regyr $sportname Rules Meeting Video</a></td></tr>";
         }
         else          //DID NOT START THE PROCESS YET
         {
            echo "<tr align=center><td><a class=small href=\"rulesmeetingintro.php?session=$session&sport=$cursp\">Click HERE to Watch the $regyr $sportname Rules Meeting Video</a></td></tr>";
         }
      }
      else if(!PastDue($enddate,0))    //SCENARIO #4: AVAILABLE FOR A LATE FEE
      {
         echo "<tr align=left><td>The Online $sportname Rules Meeting will be available for the late fee of <b>$".number_format($latefee,2,'.','')."</b> until ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).", after which it will no longer be available.</td></tr>";
         if($row2[datecompleted]>0 && $row2[datepaid]==0)      //COMPLETED BUT NOT PAID
         {
            echo "<tr align=left><td>You <b>watched</b> this rules meeting video but <b><u>";
            if($row2[datecompleted] < mktime(23,59,59,$pay[1],$pay[2],$pay[0]))        //NO FEE             
	    {                
		echo "did NOT verify your attendance"; $payorverify="Verification";             
	    }             
	    else             
	    {                
		echo "did NOT pay the fee"; $payorverify="Payment";             
	    }
            echo "</b></u>.</td></tr>";
            echo "<tr align=center><td><a class=small href=\"rulesmeetingpay.php?session=$session&sport=$cursp\">Click HERE to Complete $payorverify for this Rules Meeting</a></td></tr>";
         }
         else if($row2[initiated]>0 && $row2[datecompleted]==0)        //STARTED WATCHING BUT DIDN'T FINISH
         {
            echo "<tr align=left><td>You <b>started watching</b> but <b>did NOT finish</b> the $sportname Rules Meeting Video.</td></tr>";
            echo "<tr align=center><td><a class=small href=\"rulesmeetingintro.php?session=$session&sport=$cursp\">Click HERE to Watch the $regyr $sportname Rules Meeting Video</a></td></tr>";
         }
         else          //DID NOT START THE PROCESS YET
         {
            echo "<tr align=center><td><a class=small href=\"rulesmeetingintro.php?session=$session&sport=$cursp\">Click HERE to Watch the $regyr $sportname Rules Meeting Video</a></td></tr>";
         }
      }
      else                     //SCENARIO #5: NO LONGER AVAILABLE
      {
	 if($sport=="Play Production")
	    echo "<tr align=left><td><p><b>The $sport rules meeting is no longer available to view online and have it count as attendance with the NSAA.</b> If you wish to REVIEW the rules meeting slides, for your own purposes only, the presentation is now available on the <a href=\"/play-production\" target=\"_blank\">Play Production page</a> of the NSAA website.</p></td></tr>";
	 else
	 {
            echo "<tr align=left><td><b>The $sport rules meeting is no longer available to view online.</b></td></tr>";
            if($ppfile!='') echo "<tr align=left><td><a href=\"$ppfile\" class=small>Click Here to Download the $sportname Rules Meeting Powerpoint (no audio)</a> -- for your own purpose only; does not count as attendance with the NSAA.</td></tr>";
         }
      }
      echo "</table></td></tr>";
   }//end if there is online RM for this sport

   /******BALLOTS******/
   echo "<tr align=left bgcolor=#E0E0E0><th align=left>&nbsp;&nbsp;Ballots:</th></tr>";

   //if softball coach, show officials ballot linnk (if not voted yet)
   if($sport=="Softball")
   {
      $votesp='sb'; $votesp2="Softball"; $official="Umpires";
   }
   else if(preg_match("/Swimming/",$sport))
   {
      $votesp="di"; $votesp2="Diving"; $officials="Judges";
   }
   else if($sport=="Volleyball")
   {
      $votesp='vb'; $votesp2="Volleyball"; $official="Officials";
   }
   else if($sport=="Play Production")
   {
      $votesp='pp'; $votesp2="Play Production"; $official="Judges";
   }
   else if($sport=="Speech")
   {
      $votesp='sp'; $votesp2="Speech"; $official="Judges";
   }
   else if($sport=="Wrestling")
   {
      $votesp='wr'; $votesp2="Wrestling"; $official="Officials";
   }
   else if($sport=="Boys Basketball")
   {
      $votesp='bbb'; $votesp2="Boys Basketball"; $official="Officials";
   }
   else if($sport=="Girls Basketball")
   {
      $votesp='bbg'; $votesp2="Girls Basketball"; $official="Officials";
   }
   else if(preg_match("/Swimming/",$sport))
   {
      $votesp='di'; $votesp2="State Diving"; $official="Judges";
   }
   else if($sport=="Baseball")
   {
      $votesp='ba'; $votesp2="Baseball"; $official="Umpires";
   }
   else if(preg_match("/Soccer/",$sport))
   {
      $votesp='so'; $votesp2="Soccer"; $official="Officials";
   }
   //show link if have not voted yet...
   $today=time();
   $sql="SELECT * FROM $db_name2.vote_duedates WHERE sport='$votesp'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $start=split("-",$row[startdate]);
   $end=split("-",$row[enddate]);
   $startdate=mktime(0,0,0,$start[1],$start[2],$start[0]);
   $enddate=mktime(0,0,0,$end[1],$end[2],$end[0]);
   $enddate+=24*60*60;
   if($school=="Test's School" || ($today>=$startdate && $today<=$enddate && IsRegistered($school,$votesp)))
   {
      if($votesp=='so' && preg_match("/Girls/",$sport)) $coach="gcoach";
      else if($votesp=="so" && preg_match("/Boys/",$sport)) $coach="bcoach";
      else $coach="coach";
      $sql="SELECT id FROM $db_name2.".$votesp."_votes WHERE school='$school2' AND ad_coach='$coach'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)
      {
         echo "<tr align=center><td><br><a href=\"officials/vote_".$votesp.".php?session=$session\">$votesp2 $official Ballot Form</a></td></tr>";
      }
      else
      { 
         echo "<tr align=center><td><br>You have already submitted your $votesp2 $official Ballot</a></td></tr>";
      }
   }
   else if($today<$startdate && IsRegistered($school,$votesp))
   {
      echo "<tr align=center><td><br>$votesp2 $official Ballots available: $start[1]/$start[2]/$start[0] to $end[1]/$end[2]/$end[0]</td></tr>";
   }
   else if(IsRegistered($school,$votesp))
   {
      echo "<tr align=center><td><br>$votesp2 $official Ballots are not available at this time.</td></tr>";
   }   
   echo "<tr><td><br><br></td></tr>";

   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;Requests for Coaches, Contests, Equipment & Officials:</th></tr>";
   echo "<tr align=center><td><br><table width=600>";
   echo "<tr align=center><td>";
   echo "<a class=small href=\"requests/request.php?session=$session&type=coaches\">Submit a New Coaches Request</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
   echo "<a class=small href=\"requests/request.php?session=$session&type=contest\">Submit a New Contest Request</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
   echo "<a class=small href=\"requests/request.php?session=$session&type=equipment\">Submit a New Equipment Request</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
   echo "<a class=small href=\"requests/request.php?session=$session&type=officials\">Submit a New Officials Request</a></td></tr>";
   $expiredate=mktime(0,0,0,date("m"),date("d"),date("Y"));
   $approvedate=$expiredate-(30*24*60*60);
   $reminderdate=$approvedate+(6*24*60*60);
   $tables=array("coaches","contest","equipment","officials");
   $expiring=0;
   $abbrev=GetActivityAbbrev($sport);
   for($i=0;$i<count($tables);$i++)
   {
      $table="request_".$tables[$i];
      $sql="SELECT * FROM $db_name.$table WHERE activity LIKE '%$abbrev%' AND ((approved>='$approvedate' AND approved<='$reminderdate') OR renew='x')";
      $result=mysql_query($sql);
      if($expiring==0 && mysql_num_rows($result)>0)
      {
         $expiring=1; 
         echo "<tr align=center><td><br>";
      }
      if(mysql_num_rows($result)>0)
         echo "<table width=600><tr align=left><td colspan=4><b><u>".strtoupper($tables[$i])." requests about to expire:</b></u></td></tr>";
      while($row=mysql_fetch_array($result))
      {
         echo "<tr valign=top align=left><td width=50>".strtoupper(preg_replace("/_/"," ",$row[activity])).":</td>";
         echo "<td width=200>";
         if($tables[$i]=="officials")
         {
            $thisyear=GetFallYear($row[activity]);
            $opponent1=GetSchoolName($row[sid1],$row[activity],$thisyear);
            $opponent2=GetSchoolName($row[sid2],$row[activity],$thisyear);
            echo "$opponent1 vs. $opponent2";
         }
         else
            echo substr($row[comments],0,250);
         echo "</td>";
         $enddate=$row[approved]+(30*24*60*60);
         echo "<td><font style=\"color:red\">Expires ".date("m/d/y",$enddate)." at midnight</font></td>";
         echo "<td><a class=small href='#requests' onclick=\"window.open('requests/viewrequest.php?session=$session&type=$tables[$i]&requestid=$row[id]','$table','width=700,height=300');\">View</a>&nbsp;|&nbsp;";
         if($row[renew]=='') //allow user to request renewal
            echo "<a class=small href='#requests' onclick=\"window.open('requests/renewrequest.php?session=$session&requestid=$row[id]&type=$tables[$i]','$table','width=700,height=300');\">Renew for 30 more days</a></td></tr>";
         else if($row[renew]=='no')   //NSAA has denied renewal request
            echo "<font style=\"color:red\">Renewal Denied</font></td></tr>";
         else //NSAA has not responded to renewal request yet
            echo "<font style=\"color:green\">Renewal Pending Approval</font></td></tr>";
      }      
      if(mysql_num_rows($result)>0) echo "</table><br>";
   }
   echo "</td></tr>";
   if($expiring==1) 
      echo "<tr align=left><td width=500>PLEASE NOTE: If you requested to renew your ad and the NSAA approved the renewal, your ad will automatically show on the <a class=small target=\"_blank\" href=\"/nsaaforms/requests/requests.php\">NSAA Website</a> for an additional 30 days.</td></tr>";
   echo "<tr align=center><td><a class=small href=\"requests/index.php?session=$session\">Requests for Coaches, Contests, Equipmemt & Officials Main Menu</a><br>(View all of your submitted requests here.)";
   echo "</td></tr></table></td></tr>";

   echo "</table>";
}//end if level==3
else if($level==4)	//colleges hosting tournaments, etc.
{
   echo "<br>";
   echo "<table cellspacing=0 cellpadding=0 width=90%>";
   echo "<caption><b>Welcome, $name!<br>Today's Date is $date</b><br><br></caption>";

   if(!preg_match("/Music/",$usertitle))
   {
   echo "<tr bgcolor=#E0E0E0 align=left><th align=left>&nbsp;&nbsp;Applications to Host</th></tr>";
   echo "<tr align=center><td><br>";
   $curryear=date("Y",time());
   $curryear1=$curryear+1;
   echo "<a href=\"hostapps.php?session=$session\">Apps to Host $curryear-$curryear1 District/Sub-District Events</a>";
   echo "<br><br></td></tr>";

   //check to see if college is hosting any districts
   //switch to $db_name2 DB
   mysql_close();
   $db=mysql_connect("$db_host",$db_user2,$db_pass2);
   mysql_select_db($db_name2,$db);

   $hosting=0;
   for($i=0;$i<count($hostsports);$i++)
   {
      $districts=$hostsports[$i]."districts";
      if($hostsports[$i]=='fb') $districts="fbbrackets";
      $name2=addslashes($name);
      $sql="SELECT * FROM $districts WHERE hostschool='$name2'";
      if(preg_match("/bb/",$hostsports[$i])) $sql.=" OR hostid2='$userid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
         $hosting=1;
         $i=count($hostsports);
      }
   }
   if($hosting==1)
   {
   ?>
   <tr bgcolor=#E0E0E0 align=left>
   <th align=left>&nbsp;&nbsp;District Host Information:</th>
   </tr>   
   <?php
   echo "<tr align=center><td><br><table>";
   for($i=0;$i<count($hostsports);$i++)
   {
      $districts=$hostsports[$i]."districts";
      if($hostsports[$i]=='fb') $districts="fbbrackets";
      $sql="SELECT * FROM $districts WHERE (hostschool='$name2'";
      if(preg_match("/bb/",$hostsports[$i])) $sql.=" OR hostid2='$userid'";
      $sql.=") AND (type='District Final' OR post='y')";
      $result=mysql_query($sql); 
      while($row=mysql_fetch_array($result))
      {
	    if(!$row[hostid2] && $row[accept]=='' && $row[post]=='y' && $row[type]!='District Final')	//host has not responded to contract yet
	    {
	       echo "<tr align=center><td><table border=1 bordercolor=#000000 cellspacing=0 cellpadding=4>";
	       echo "<tr align=left><td><b><u>You have been selected to host the ";
	       if($hostsports[$i]=='fb')
                  echo "Class $row[class] $row[round] Playoff Game ($row[school1] VS $row[school2])</b></u><ul>";
	       else if($hostsports[$i]=='tr')
	          echo "$row[type] $row[class]-$row[district] $hostsports2[$i] Meet:</b></u><ul>";
	       else
	          echo "$row[gender] $row[type] $row[class]-$row[district] $hostsports2[$i] Tournament:</b></u><ul>";
	       echo "<li><a class=small target=new href=\"officials/hostcontract.php?session=$session&sport=$hostsports[$i]&distid=$row[id]\">Click Here to Accept/Decline this Contract to Host</a></li>";
 	       echo "</ul></td></tr>";
	       echo "</table></td></tr>";
	    }
	    else if($row[accept]=='y' || $row[type]=="District Final")
	    {
               echo "<tr align=center><td><br><table border=1 bordercolor=#000000 cellspacing=0 cellpadding=4 rules=none frame=box>";
               echo "<tr align=left><td><b><u>You have agreed to host the ";
               if($hostsports[$i]=='fb')
                  echo "Class $row[class] $row[round] Playoff Game ($row[school1] VS $row[school2]):</u></b><ul>";
	       else if($hostsports[$i]=='tr')
	          echo "$row[type] $row[class]-$row[district] $hostsports2[$i] Meet</u>:</b><ul>";
	       else
                  echo "$row[gender] $row[type] $row[class]-$row[district] $hostsports2[$i] Tournament</u>:</b><ul>";
               if($row[confirm]=='y' && !$row[hostid2])
               {
                  echo "<li>The NSAA has <i><b>confirmed</b></i> your contract.";
                  if($hostsports[$i]=='vb' || $hostsports[$i]=='so' || $hostsports[$i]=='ba' || preg_match("/bb/",$hostsports[$i]))
	    	     echo "<li>Please enter the date and time for each game in your tournament <a class=small target=new href=\"officials/hostslots.php?session=$session&ad=1&sport=$hostsports[$i]&distid=$row[id]\">Here</a>";
               }
               else if($row[confirm]=='n' && !$row[hostid2])
                  echo "<li>The NSAA has <i>declined</i> your contract.";
               else if($row[type]!="District Final" && !$row[hostid2])
                  echo "<li>The NSAA has not responded to your contract.  Please check back later.";
               if($row[type]!="District Final" && !$row[hostid2])
                  echo "<li><a class=small target=new href=\"officials/hostcontract.php?session=$session&sport=$hostsports[$i]&distid=$row[id]\">Click Here to View your Contract to Host</a></li>";
               if(($hostsports[$i]=='sp' || $hostsports[$i]=='pp' || $row[showoffs]=='y') && ($row[type]=='District Final' || $row[confirm]=='y'))
               {
	          echo "<li>";
                  if($hostsports[$i]=='pp' || $hostsports[$i]=='sp')
                     echo "<a class=small target=new href=\"officials/ppshowtoad.php?session=$session&id=$row[id]&sport=$hostsports[$i]\">$hostsports2[$i] Judges assigned to ";
                  else
                     echo "<a class=small href=\"officials/showtoad.php?session=$session&sport=$hostsports[$i]&id=$row[id]\" target=new>$hostsports2[$i] Officials assigned to ";
                  if($hostsports[$i]=='fb')
                     echo "Class $row[class], $row[round] ($row[school1] VS $row[school2])";
                  else if(preg_match("/bb/",$hostsports[$i]) || $hostsports[$i]=='so')
                     echo "$row[gender] $row[type] $row[class]-$row[district]";
                  else
                     echo "$row[type] $row[class]-$row[district]";
                  echo "</a></li>";
               }
	       if($row[type]=="District Final" || $row[confirm]=='y')
	       {
                  //check if due date for this district entry form is past; if so, show link to forms
                  $duedate=GetDueDate($hostsports[$i]);
	          if($hostsports[$i]=='sp' || $hostsports[$i]=='pp')
		     $duedate=GetDueDate($hostsports[$i].'showentries');
                  if(PastDue($duedate,0) && $duedate!='')
                  {
                     echo "<li><a class=small href=\"entryforms.php?distid=$row[id]&session=$session&sport=$hostsports[$i]\">$hostsports2[$i] District Entry Forms submitted by the schools in your district</a></li>";
                  }
                  if($hostsports[$i]=='pp')
                  {
                     echo "<li><a class=small href=\"pp/createdistcert.php?session=$session&distid=$row[id]\">Create & Print Play Production District Award Certificates</a></li>";
                  }
  	       }
               if($row[confirm]=='y' && $hostsports[$i]=='cc')  //for CC: show link to results
               {
                  $date=$row[dates];    //1 day for CC districts
                  if(PastDue($date,-3) || $school=="Test College")
                  {
                     echo "<li><a class=small href=\"cc/state_cc_b_edit.php?session=$session&dist_select=$row[id]\">BOYS Cross-Country District Results Form</a></li>";
                     echo "<li><a class=small href=\"cc/state_cc_g_edit.php?dist_select=$row[id]&session=$session\">GIRLS Cross-Country District Results Form</a></li>";
                  }
               }
               else if($row[confirm]=='y' && $hostsports[$i]=='sp')     //SP: show link to results
               {
                  $dates=split("/",$row[dates]);
                  $date=$dates[0];
                  if(PastDue($date,-1) && $row[dates]!='' && !preg_match("/00-00/",$date))
                  {
                     echo "<li><a class=small href=\"sp/sp_state_edit.php?session=$session\">Speech District Results Entry Form</a></li>";
                  }
               }
	       else if($row[confirm]=='y' && $hostsports[$i]=='pp')	//PP: show link to results
	       {
                     echo "<li><a class=\"small\" href=\"pp/districtresults.php?session=$session&distid=$row[id]\">Enter District $row[class]-$row[district] Results</a></li>";
	       }
                     if($hostsports[$i]=='bbb') $page="bb_bfinance";
                     else if($hostsports[$i]=='bbg') $page="bb_gfinance";
                     else $page=$hostsports[$i]."finance";
	       if(citgf_file_exists("financialreports/$page.php") && ($row[type]=="District Final" || $row[confirm]=='y'))
                     echo "<li><a class=small href=\"financialreports/$page.php?session=$session&distid=$row[id]\">Click Here for your $hostsports2[$i] Financial Report</a></li>";
               echo "</ul></td></tr>";
               echo "</table></td></tr>";
	    }
      }
   }
   echo "</table><br></td></tr>";
   } //end if hosting a district

   echo "<tr bgcolor=#E0E0E0 align=left><th align=left>&nbsp;&nbsp;Rules Meeting Host Information:</th></tr>";
   echo "<tr align=center><td><br>";
   $sql="SHOW TABLES LIKE '%ruleshosts'";
   $result=mysql_query($sql);
   $hosting=0;
   while($row=mysql_fetch_array($result))
   {
      $temp=split("ruleshosts",$row[0]);
      $cursp=$temp[0];
      $curtbl=$row[0];
      $sql2="SELECT * FROM $db_name2.$curtbl WHERE hostname='$school2' AND post='y'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)
      {
         $hosting=1;
         while($row2=mysql_fetch_array($result2))
         {
            echo "<table width=500 border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
            echo "<tr align=left><td><b>You have been selected to host a ".GetActivityName($cursp)." Rules Meeting";
            if($row2[type]!='Regular') echo " ($row2[type] Site)";
            $date=split("-",$row2[mtgdate]);
            echo " on $date[1]/$date[2]/$date[0]</b>:<br><ul>";
            if($row2[accept]=='')
               echo "<li><a class=small target=\"_blank\" href=\"officials/rulescontract.php?session=$session&sport=$cursp&siteid=$row2[id]\">Click Here to Respond to your Contract to Host</a></li>";
            else
            {
               if($row2[accept]=='y')
               {
                  echo "<li>You have ACCEPTED your <a class=small target=\"_blank\" href=\"officials/rulescontract.php?session=$session&sport=$cursp&siteid=$row2[id]\">Contract to Host</a></li>";
                  if($row2[confirm]=='')
                     echo "<li>The NSAA has not responded to your contract yet.</li>";
                  else if($row2[confirm]=='y')
                     echo "<li>The NSAA has CONFIRMED your contract.</li>";
                  else
                     echo "<li>The NSAA has REJECTED your contract.</li>";
               }
               else     //accept==n
               {
                  echo "<li>You have DECLINED your <a class=small target=\"_blank\" href=\"officials/rulescontract.php?session=$session&sport=$cursp&siteid=$row2[id]\">Contract to Host</a></li>";
                  if($row2[confirm]=='y')
                     echo "<li>The NSAA has ACKNOWLEDGED your contract.</li>";
                  else
                     echo "<li>The NSAA has not responded to your contract yet.</li>";
               }
            }
            echo "</ul></td></tr></table><br>";
         }
      }//end if contracted to host $cursp rules meeting(s)
   }//end for each sport
   if($hosting==0)
   {
      echo "[You have no contracts to host a rules meeting at this time.]<br>";
   }
   echo "<br></td></tr>";

   //SUPERVISED TEST HOST INFO
	/* NOT IN USE AS OF FALL 2011
   echo "<tr bgcolor=#E0E0E0 align=left><th align=left>&nbsp;&nbsp;Supervised Test Host Information:</th></tr>";
   echo "<tr align=center><td><br>";
   $hosting=0;
   $sql2="SELECT * FROM $db_name2.suptesthosts WHERE hostname='$school2' AND post='y'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0)
   {
      $hosting=1;  
      while($row2=mysql_fetch_array($result2))
      {
         echo "<table width=500 border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
         echo "<tr align=left><td><b>You have been selected to host a Supervised Test";
         $date=split("-",$row2[mtgdate]);
         echo " on $date[1]/$date[2]/$date[0]</b>:<br><ul>";
         if($row2[accept]=='')
            echo "<li><a class=small target=\"_blank\" href=\"officials/suptestcontract.php?session=$session&siteid=$row2[id]\">Click Here to Respond to your Contract to Host</a></li>";
         else
         {
            if($row2[accept]=='y')
            {
               echo "<li>You have ACCEPTED your <a class=small target=\"_blank\" href=\"officials/suptestcontract.php?session=$session&siteid=$row2[id]\">Contract to Host</a></li>";
               if($row2[confirm]=='')
                  echo "<li>The NSAA has not responded to your contract yet.</li>";
               else if($row2[confirm]=='y')
                  echo "<li>The NSAA has CONFIRMED your contract.</li>";
               else
                  echo "<li>The NSAA has REJECTED your contract.</li>";
            }
            else     //accept==n
            {
               echo "<li>You have DECLINED your <a class=small target=\"_blank\" href=\"officials/suptestcontract.php?session=$session&siteid=$row2[id]\">Contract to Host</a></li>";
               if($row2[confirm]=='y')
                  echo "<li>The NSAA has ACKNOWLEDGED your contract.</li>";
               else
                  echo "<li>The NSAA has not responded to your contract yet.</li>";
            }
         }
         echo "</ul></td></tr></table><br>";
      }
   }//end if contracted to host supervised test(s)
   if($hosting==0)
   {
      echo "[You have no contracts to host a supervised test at this time.]<br>";
   }
   echo "<br></td></tr>";
	*/

   /***** APPLICATIONS FOR SANCTION OF EVENTS *****/
   echo "<tr bgcolor=#E0E0E0 align=left><th align=left>&nbsp;&nbsp;Applications for Sanction of Events:</th></tr>";
   echo "<tr align=center><td><br><a href=\"sanctions/sanctionslist.php?session=$session\">Go to your Applications for Sanction Main Menu</a><br><br>";             
   echo "<table width='80%' cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#a0a0a0 1px solid;\"><caption><b>Your Applications for Sanction that have been APPROVED by the NSAA:</b></caption><tr bgcolor='#f0f0f0' align=center><td><b>Interstate Athletic Events</b></td><td><b>Interstate Fine Arts Events</b></td><td><b>International Events</b></td></tr>";
   echo "<tr align=left valign=top>";
   $sql1="SELECT * FROM interstatesanctions WHERE school='$school2' AND NSAAapproved>1 ORDER BY NSAAapproved DESC";
   $result1=mysql_query($sql1);
   if(mysql_num_rows($result1)==0)
      echo "<td width='33%'>[No Interstate Athletic Events have been sanctioned for you by the NSAA.]<br><br>";
   else
   {
      echo "<td width='33%'>";
      while($row1=mysql_fetch_array($result1))
      {
         echo "<a class=small href=\"sanctions/interstatesanction.php?session=$session&appid=$row1[id]&pdf=1\" target=\"_blank\">$row1[eventname]</a><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(approved ".date("m/d/y",$row1[NSAAapproved]).")<br>";
      }
      echo "<br>";
   }
   echo "<a class=small href=\"sanctions/sanctionslist.php?session=$session&eventtype=interstatesanctions\">Go to Sanctions for Interstate Athletic Events</a></td>";
   $sql2="SELECT * FROM interstatefasanctions WHERE school='$school2' AND NSAAapproved>1 ORDER BY NSAAapproved DESC";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
      echo "<td width='33%'>[No Interstate Fine Arts Events have been sanctioned for you by the NSAA.]<br><br>";
   else
   {
      echo "<td width='33%'>";
      while($row2=mysql_fetch_array($result2))
      {
         echo "<a class=small href=\"sanctions/interstatefasanction.php?session=$session&appid=$row2[id]&pdf=1\" target=\"_blank\">$row2[eventname]</a><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(approved ".date("m/d/y",$row2[NSAAapproved]).")<br>";
      }                
      echo "<br>";
   }
   echo "<a class=small href=\"sanctions/sanctionslist.php?session=$session&eventtype=internationalsanctions\">Go to Sanctions for International Athletic Events</a></td>";
   $sql3="SELECT * FROM internationalsanctions WHERE school='$school2' AND NSAAapproved>1 ORDER BY NSAAapproved DESC";
   $result3=mysql_query($sql3);
   if(mysql_num_rows($result3)==0)
       echo "<td width='33%'>[No International Events have been sanctioned for you by the NSAA.]<br><br>";
   else
   {
       echo "<td width='33%'>";
       while($row3=mysql_fetch_array($result3))
       {
          echo "<a class=small href=\"sanctions/internationalsanction.php?session=$session&appid=$row3[id]&pdf=1\" target=\"_blank\">$row3[eventname]</a><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(approved ".date("m/d/y",$row3[NSAAapproved]).")<br>";
       }
       echo "<br>";
   }
   echo "<a class=small href=\"sanctions/sanctionslist.php?session=$session&eventtype=interstatefasanctions\">Go to Sanctions for Interstate Fine Arts Events</a></td>";
   echo "</tr></table><br></td></tr>";

   echo "<tr bgcolor=#E0E0E0 align=left><th align=left>&nbsp;&nbsp;Officials/Judges Rosters:</th></tr>";
   echo "<tr align=center><td>";
   $shown=0;
   $sql="SELECT * FROM $db_name2.rosters WHERE active='x' AND (sport!='sp' AND sport!='pp') ORDER BY sport
";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
   {
      $shown=1;
      echo "<form method=post action=\"officials/roster.php\" target=new>";
      echo "<input type=hidden name=session value=\"$session\">";
      echo "<input type=hidden name=ad value=\"1\">";
      echo "<table><tr align=left><td><b>Officials' Rosters:</b><br><select name=sport onchange=\"submit();\">
";
      while($row=mysql_fetch_array($result))
      {
         echo "<option value=\"$row[sport]\">".GetActivityName($row[sport]);
         echo "</option>";
      }
      echo "</select><input type=submit name=go value=\"Go\"></td></tr></table></form>";
   }
   echo "<form method=post action=\"officials/jroster.php\" target=new>";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<input type=hidden name=ad value=\"1\">";
   echo "<table><tr alignm=left><td><b>Judges' Rosters:</b><br><select name=list onchange=\"submit();\">";
   $sql="SELECT * FROM $db_name2.rosters WHERE active='x' AND (sport='sp' OR sport='pp') ORDER BY sport";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[sport]\">".GetActivityName($row[sport])."</option>";
   }
   echo "</select>";
   echo "<input type=submit name=go value=\"Go\"></td></tr></table></form>";
   if($archiveroster==1)
   {
      $sql="SELECT * FROM $db_name2.rosters WHERE showold='x' ORDER BY sport";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
         $shown=1;
         echo "<form method=post action=\"officials/roster.php\" target=new>";
         echo "<input type=hidden name=session value=\"$session\">";
         echo "<input type=hidden name=ad value=\"1\">";
         echo "<table><tr align=left><td><b>$lastyear Officials & Judges Rosters:&nbsp;</b>";
         echo "<input type=hidden name=archive value=\"$archivedbroster\">";
         echo "<select name=sport onchange=\"submit();\">";
         while($row=mysql_fetch_array($result))
         {
            echo "<option value=\"$row[sport]\">".GetActivityName($row[sport]);
            echo "</option>";
         }
         echo "<option value=\"pp\">Play Production</option>";
         echo "<option value=\"sp\">Speech</option>";
         echo "</select><input type=submit name=go value=\"Go\"></td></tr></table></form>";
      }
   }
   if($shown!=1) echo "Please check back soon!";
   echo "</td></tr>";

   //re-connect to $db_name
   mysql_close();
   $db=mysql_connect("$db_host",$db_user,$db_pass);
   mysql_select_db($db_name,$db);
/*
   echo "<tr bgcolor=#E0E0E0 align=left><th align=left>&nbsp;&nbsp;Financial Forms</th></tr>";
   echo "<tr align=center><td><br><br>";
   echo "<a href=\"financialreports/vbindex.php?session=$session&school_ch=$name\">Volleyball Financial Reports</a><br>";
   echo "<a href=\"financialreports/bb_bindex.php?session=$session&school_ch=$name\">Boys Basketball Financial Reports</a><br>";
   echo "<a href=\"financialreports/bb_gindex.php?session=$session&school_ch=$name\">Girls Basketball Financial Reports</a><BR><BR></td></tr>";
*/
   }//end if not MUSIC in title
   /*********** MUSIC ****************/
   if(preg_match("/Music/",$usertitle))
   {
      echo "<tr align=left bgcolor=#E0E0E0>";
      echo "<th align=left>&nbsp;&nbsp;Music:</th></tr>";
      echo "<tr align=center><td><br>";
      echo "<a class=small href=\"mu/muhome.php?session=$session\">Sample NSAA Music District Entry Form</a><br><br>";
      echo "</td></tr>";
      /********MUSIC SITE DIRECTORS OR MUSIC DISTRICT COORDINATORS************/
      $loginid=GetUserID($session);
      $musiteid=GetMusicSiteID(0,$loginid);
      $mudistid=GetMusicDistrictID(0,$loginid);
      if($musiteid>0 || $mudistid>0)
      {
         echo "<tr bgcolor=#E0E0E0 align=left><th align=left>&nbsp;&nbsp;District Music Contest Host & Coordinator Information:</th></tr>";
         echo "<tr align=center><td><br><table><tr align=left><td>";
         if($musiteid>0)
         {
            $sql="SELECT * FROM mudistricts WHERE id='$musiteid'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);
            echo "<a href=\"mu/musiteadmin.php?session=$session\">District $row[distnum] -- $row[classes] Submitted Entry Forms</a><br><br>";
            echo "<a href=\"mu/scertsadmin.php?session=$session&name=$name\">District $row[distnum] -- $row[classes] Superior Award Certificate Generation<br><br>";
            if($row[certificates]=='x')
               echo "<a href=\"mu/viewawardwinners.php?session=$session\">Reporting and Printing Outstanding Performance Awards</a><br><br>";
            echo "<a target=new href=\"mu/mujudges.php?session=$session\">List of NSAA Music Judges</a><br><br><a href=\"https://nsaahome.org/textfile/music/manual.pdf\" target=\"_blank\">CURRENT NSAA MUSIC MANUAL</a><br><br>";
            $sql2="SELECT * FROM finrpt_entry WHERE district_id='$musiteid'";
            $result2=mysql_query($sql2);
            if(mysql_num_rows($result2)==0)
               echo "<a href=\"mu/dmcfinrptform.php?session=$session\">Begin working on your NSAA District Music Contest Financial Report</a>";
            else
            {
               $row2=mysql_fetch_array($result2);
               if($row2[datesub]==0)
                  echo "<a href=\"mu/dmcfinrptform.php?session=$session&editid=$row2[id]\">Continue working on your NSAA District Music Contest Financial Report</a><br><p style=\"font-weight:normal;\"><i>Your financial report has NOT been submitted to the NSAA or the District Music Coordinator yet.<br>Click the link above to finish your report and submit it as final.</i></p>";
               else     //SUBMITTED
                  echo "<p style=\"font-weight:normal;\">You submitted your <b>NSAA District Music Contest Financial Report</b> on ".date("F j, Y",$row2[datesub]).".</i><ul><li><a href=\"mu/dmcfinrptform.php?session=$session&editid=$row2[id]\">View/Print your Submitted NSAA District Music Contest Financial Report</a></li></ul></p>";
            }
         }
         if($mudistid>0)
         {
            $sql="SELECT * FROM mubigdistricts WHERE id='$mudistid'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);
    	    $distnum=$row[distnum];
            echo "<a href=\"mu/mudistadmin.php?session=$session\">District $row[distnum] Submitted Entry Forms</a><br><br>";
            if($row[certificates]=='x')
            {
               echo "<form method=\"post\" action=\"mu/viewawardwinners.php\"><input type=hidden name=\"session\" value=\"$session\">";
               echo "<font style=\"font-size:9pt;\"><b>Report & Print NSAA District Music Contest Outstanding Performance Award Certificates:</b><br></font>";
               echo "<select name=\"siteid\"><option value='0'>Select District Site</option>";
               $sql="SELECT t1.* FROM mudistricts AS t1,mubigdistricts AS t2 WHERE t1.distnum=t2.distnum AND t2.id='$mudistid' ORDER BY t1.classes";
               $result=mysql_query($sql);
               while($row=mysql_fetch_array($result))
               {
                  echo "<option value=\"$row[id]\"";
                  if($siteid==$row[id]) echo " selected";
                  echo ">$row[distnum] -- $row[classes] at $row[site]</option>";
               }
               echo "</select>&nbsp;<input type=submit name=\"go\" value=\"Go\">";
               echo "</form>";
            }
            echo "<a target=new href=\"mu/mujudges.php?session=$session\">List of NSAA Music Judges</a><br><br><a href=\"https://nsaahome.org/textfile/music/manual.pdf\" target=\"_blank\">CURRENT NSAA MUSIC MANUAL</a><br><br><a href=\"mu/dmcfinrptadmin.php?session=$session\">District $distnum Submitted Financial Reports</a>";
            echo "</td></tr></table></td></tr>";
         }
      }
   }

   echo "</table>";
}
else if($level==5)	//Omaha Public Schools, Lincoln Public Schools, etc.
{
   echo "<table width=90% cellspacing=0 cellpadding=0>";
   echo "<caption><b>Welcome, $name!<br>";
   echo "Today's Date is: $date</b><br><br></caption>";
   echo "<tr bgcolor=#E0E0E0 align=left>";
   if(!preg_match("/Music/",$usertitle))
   {
   echo "<th align=left>&nbsp;&nbsp;Messages:</th></tr>";
   echo "<tr align=center><td><br><table><tr align=left><td>";
   echo "<a class=small href=\"post_message.php?session=$session\">Post New Message to AD(s)</a></td></tr>";
   echo "<tr align=left></td>";
   echo "<a class=small href=\"edit_message.php?session=$session\">Edit/Delete Messages</a><br><br></td></tr></table><br></td></tr>";
   //Officials Rosters Section
   echo "<tr align=left bgcolor=#E0E0E0>";
   echo "<th align=left>&nbsp;&nbsp;NSAA Officials & Judges Roster:</th>";
   echo "</tr>";
   echo "<tr align=center><td>";
   echo "<table>";
   echo "<tr align=center><td><b>Officials & Judges Rosters:</b></td>";
   echo "<tr align=left><td>";
   $shown=0;
   $sql="SELECT * FROM $db_name2.rosters WHERE active='x' AND (sport!='sp' AND sport!='pp') ORDER BY sport
";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
   {
      $shown=1;
      echo "<form method=post action=\"officials/roster.php\" target=new>";
      echo "<input type=hidden name=session value=\"$session\">";
      echo "<input type=hidden name=ad value=\"1\">";
      echo "<table><tr align=left><td><b>Officials' Rosters:</b><br><select name=sport onchange=\"submit();\">
";
      while($row=mysql_fetch_array($result))  
      {
         echo "<option value=\"$row[sport]\">".GetActivityName($row[sport]);
         echo "</option>";
      }
      echo "</select><input type=submit name=go value=\"Go\"></td></tr></table></form>";
   }
   echo "<form method=post action=\"officials/jroster.php\" target=new>";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<input type=hidden name=ad value=\"1\">";
   echo "<table><tr alignm=left><td><b>Judges' Rosters:</b><br><select name=list onchange=\"submit();\">";
   $sql="SELECT * FROM $db_name2.rosters WHERE active='x' AND (sport='sp' OR sport='pp') ORDER BY sport";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[sport]\">".GetActivityName($row[sport])."</option>";
   }
   echo "</select>";
   echo "<input type=submit name=go value=\"Go\"></td></tr></table></form>";
   if($archiveroster==1)
   {
      $sql="SELECT * FROM $db_name2.rosters WHERE showold='x' ORDER BY sport";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
         $shown=1;
         echo "<form method=post action=\"officials/roster.php\" target=new>";
         echo "<input type=hidden name=session value=\"$session\">";
         echo "<input type=hidden name=ad value=\"1\">";
         echo "<table><tr align=left><td><b>$lastyear Officials & Judges Rosters:&nbsp;</b>";
         echo "<input type=hidden name=archive value=\"$archivedbroster\">";
         echo "<select name=sport onchange=\"submit();\">";
         while($row=mysql_fetch_array($result))
         {
            echo "<option value=\"$row[sport]\">".GetActivityName($row[sport]);
            echo "</option>";
         }
         echo "<option value=\"pp\">Play Production</option>";
         echo "<option value=\"sp\">Speech</option>";
         echo "</select><input type=submit name=go value=\"Go\"></td></tr></table></form>";
      }
   }
   if($shown!=1) echo "Please check back soon!";
   echo "</td></tr>";
   echo "</td></tr></table><br>";
   echo "</td></tr>";
   //Apps to Host Section
/*
   echo "<tr align=left bgcolor=#E0E0E0>";
   echo "<th align=left>&nbsp;&nbsp;Applications to Host:</th></tr>";
   echo "<tr align=center><td><br>";
   echo "<a class=small href=\"hostappsearch.php?session=$session\">Applications to Host</a><br>";
   echo "<br></td></tr>";
*/
   //PROPOSALS
   $sql="SELECT * FROM proposaladmin";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(PastDue($row[showstart],-1) && !PastDue($row[showend],0))
   {
      //if between "show" and "take down" dates
      echo "<tr align=left bgcolor=#E0E0E0>";
      echo "<th align=left>&nbsp;&nbsp;Proposals for Change in NSAA Regulations:</th></tr>";
      echo "<tr align=center><td><br>";
      echo "<a href=\"proposaladmin2.php?session=$session\">Proposals for Change in NSAA Regulations</a>";
      echo "<br><br></td></tr>";
   }
 
   //DISTRICT RESULTS
   /*
   echo "<tr align=left bgcolor=#E0E0E0>";
   echo "<th align=left>&nbsp;&nbsp;District Results:</th></tr>";
   echo "<tr align=center><td><br>";
   echo "<a class=small href=\"tr/tr_state_edit_b.php?session=$session\">Boys Track & Field District Results Form</a><br><a class=small href=\"tr/tr_state_edit_g.php?session=$session\">Girls Track &Field District Results Form</a><br><br>";
   echo "</td></tr>";
   */
   }//end if not music
  
   //All users (music included): Access to music form sample
   echo "<tr align=left bgcolor=#E0E0E0>";
   echo "<th align=left>&nbsp;&nbsp;Music:</th></tr>";
   echo "<tr align=center><td><br>";
   echo "<a href=\"mu/muhome.php?session=$session\">Sample NSAA Music District Entry Form</a><br><br>";

      /********MUSIC SITE DIRECTORS OR MUSIC DISTRICT COORDINATORS************/
      $loginid=GetUserID($session);
      $musiteid=GetMusicSiteID(0,$loginid);
      $mudistid=GetMusicDistrictID(0,$loginid);
      if($musiteid>0 || $mudistid>0)
      {
         echo "<tr align=center><td><br><table><tr align=left><td>";
         if($musiteid>0)
         {
            $sql="SELECT * FROM mudistricts WHERE id='$musiteid'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);
            echo "<a href=\"mu/musiteadmin.php?session=$session\">District $row[distnum] -- $row[classes] Submitted Entry Forms</a><br><br>";
            echo "<a href=\"mu/scertsadmin.php?session=$session&name=$name\">District $row[distnum] -- $row[classes] Superior Award Certificate Generation<br><br>";
            if($row[certificates]=='x')
               echo "<a href=\"mu/viewawardwinners.php?session=$session\">Reporting and Printing Outstanding Performance Awards</a><br><br>";
            echo "<a target=new href=\"mu/mujudges.php?session=$session\">List of NSAA Music Judges</a><br><br><a href=\"https://nsaahome.org/textfile/music/manual.pdf\" target=\"_blank\">CURRENT NSAA MUSIC MANUAL</a><br><br>";
            $sql2="SELECT * FROM finrpt_entry WHERE district_id='$musiteid'";
            $result2=mysql_query($sql2);
            if(mysql_num_rows($result2)==0)
               echo "<a href=\"mu/dmcfinrptform.php?session=$session\">Begin working on your NSAA District Music Contest Financial Report</a>";
            else
            {
               $row2=mysql_fetch_array($result2);
               if($row2[datesub]==0)
                  echo "<a href=\"mu/dmcfinrptform.php?session=$session&editid=$row2[id]\">Continue working on your NSAA District Music Contest Financial Report</a><br><p style=\"font-weight:normal;\"><i>Your financial report has NOT been submitted to the NSAA or the District Music Coordinator yet.<br>Click the link above to finish your report and submit it as final.</i></p>";
               else     //SUBMITTED
                  echo "<p style=\"font-weight:normal;\">The <b>NSAA District Music Contest Financial Report</b> was submitted on ".date("F j, Y",$row2[datesub]).".</i><ul><li><a href=\"mu/dmcfinrptform.php?session=$session&editid=$row2[id]\">View/Print your Submitted NSAA District Music Contest Financial Report</a></li></ul></p>";
            }
         }
         if($mudistid>0)
         {
            $sql="SELECT * FROM mubigdistricts WHERE id='$mudistid'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);
            $distnum=$row[distnum];
            echo "<a href=\"mu/mudistadmin.php?session=$session\">District $row[distnum] Submitted Entry Forms</a><br><br>";
            if($row[certificates]=='x')
            {
               echo "<form method=\"post\" action=\"mu/viewawardwinners.php\"><input type=hidden name=\"session\" value=\"$session\">";
               echo "<font style=\"font-size:9pt;\"><b>Report & Print NSAA District Music Contest Outstanding Performance Award Certificates:</b><br></font>";
               echo "<select name=\"siteid\"><option value='0'>Select District Site</option>";
               $sql="SELECT t1.* FROM mudistricts AS t1,mubigdistricts AS t2 WHERE t1.distnum=t2.distnum AND t2.id='$mudistid' ORDER BY t1.classes";
               $result=mysql_query($sql);
               while($row=mysql_fetch_array($result))
               {
                  echo "<option value=\"$row[id]\"";
                  if($siteid==$row[id]) echo " selected";
                  echo ">$row[distnum] -- $row[classes] at $row[site]</option>";
               }
               echo "</select>&nbsp;<input type=submit name=\"go\" value=\"Go\">";
               echo "</form>";
            }
            echo "<a target=new href=\"mu/mujudges.php?session=$session\">List of NSAA Music Judges</a><br><br><a href=\"https://nsaahome.org/textfile/music/manual.pdf\" target=\"_blank\">CURRENT NSAA MUSIC MANUAL</a><br><br><a href=\"mu/dmcfinrptadmin.php?session=$session\">District $distnum Submitted Financial Reports</a>";
            echo "</td></tr></table></td></tr>";
         }
      }

   //RAY LOWTHER OF LPS: CAN SEE ALL SUBMITTED LINCOLN HS FORMS
   if(GetUserID($session)==22402)
   {
	/* ALLOW TO VIEW: */
	$schs=array("Lincoln High",
		"Lincoln East",
		"Lincoln Northeast",
		"Lincoln North Star",
		"Lincoln Southwest",
		"Lincoln Southeast",
		"Lincoln Pius X");
      $sql="SELECT school FROM headers WHERE (";
      for($i=0;$i<count($schs);$i++)
      {
	 $sql.="school='$schs[$i]' OR ";
      }
      $sql=substr($sql,0,strlen($sql)-4).") ORDER BY school"; 
      $result=mysql_query($sql);
      echo "<table class='nine' width='700px' cellspacing=0 cellpadding=4>";
      while($row=mysql_fetch_array($result))
      {
         $sql2="SELECT * FROM muschools WHERE school='".addslashes($row[school])."'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 if(mysql_num_rows($result2)>0 && $row2[submitted]>0)
         {
   		echo "<tr align=left bgcolor='#E0E0E0'>";
   		echo "<td><a href=\"view_mu.php?session=$session&school_ch=$row2[school]\">$row2[school]</a></td>";
   		echo "<td align=center>$row2[studcount] Students</td>";
   		echo "<td align=center>Submitted ".date("m/d/y",$row2[submitted])." @ ".date(" h:i a",$row2[submitted])."</td></tr>";
   		//show links to attachments that were sent with form:
   		echo "<tr align=center><td colspan=3><table width=95%>";
      		//Get file names of attachments:
      		$summary=strtolower($row2[school]);
      		$summary=preg_replace("/[^0-9a-zA-Z]/","",$summary);
      		$summary.="summary";
      		$full=preg_replace("/summary/","full",$summary);
      		$eliglist=preg_replace("/summary/","eliglist",$summary);
      		$payment=preg_replace("/summary/","payment",$summary);
   		echo "<tr align=left>";
   		echo "<td><a class=small target=\"_blank\" href=\"attachments.php?session=$session&filename=".$summary.".html\">Summary (.html)</a></td>";
   		echo "<td><a class=small target=\"_blank\" href=\"attachments.php?session=$session&filename=".$full.".html\">Full Version (.html)</a></td>";
   		echo "<td><a class=small target=\"_blank\" href=\"attachments.php?session=$session&filename=".$eliglist.".html\">Eligibility List (.html)</a></td>";
   		echo "<td><a class=small target=\"_blank\" href=\"attachments.php?session=$session&filename=".$payment.".html\">Payment Summary (.html)</a></td></tr>";
   		echo "<tr align=left>";
   		echo "<td><a class=small target=\"_blank\" href=\"attachments.php?session=$session&filename=".$summary.".csv\">Summary (.csv)</a></td>";
   		echo "<td><a class=small target=\"_blank\" href=\"attachments.php?session=$session&filename=".$full.".csv\">Full Version (.csv)</a></td>";
   		echo "<td><a class=small target=\"_blank\" href=\"attachments.php?session=$session&filename=".$eliglist.".csv\">Eligibility List (.csv)</a></td>";
   		echo "<td><a class=small target=\"_blank\" href=\"attachments.php?session=$session&filename=".$payment.".csv\">Payment Summary (.csv)</a></td>";
   		echo "</td></table></td></tr>";
  	 }
	 else
	 {
	    echo "<tr align=left bgcolor='#E0E0E0'><td colspan=3><b>$row[school]</b></td></tr><tr align=center><td colspan=3>$row[school] has not submitted their NSAA District Music Entry Form yet.</td></tr>";
         }
      }
      echo "</table>";
   }
   echo "</td></tr>";
}//end if level=5
else if($level==6)
{
   echo "<table width=90% cellspacing=0 cellpadding=0>";
   echo "<caption><b>Welcome, $name!<br>";
   echo "Today's Date is: $date</b><br><br></caption>"; 
   //check if picked to host a rules meeting:
   echo "<tr align=left bgcolor=#E0E0E0>";
   echo "<th align=left>&nbsp;&nbsp;Rules Meeting Host Information:</th></tr>";
   echo "<tr align=center><td><br>";
   $db=mysql_select_db($db_name2,$db);
   $sql="SHOW TABLES LIKE '%ruleshosts'";
   $result=mysql_query($sql);
   $hosting=0;
   while($row=mysql_fetch_array($result))
   {
      $temp=split("ruleshosts",$row[0]);
      $cursp=$temp[0];
      $curtbl=$row[0];
      $sql2="SELECT * FROM $db_name2.$curtbl WHERE hostname='$school2' AND post='y'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)
      {
         $hosting=1;
	 while($row2=mysql_fetch_array($result2))
	 {
	    echo "<table width=500 border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
            echo "<tr align=left><td><b>You have been selected to host a ".GetActivityName($cursp)." Rules Meeting";
 	    if($row2[type]!='Regular') echo " ($row2[type] Site)";
     	    $date=split("-",$row2[mtgdate]);
            echo " on $date[1]/$date[2]/$date[0]</b>:<br><ul>";
	    if($row2[accept]=='')
	       echo "<li><a class=small target=\"_blank\" href=\"officials/rulescontract.php?session=$session&sport=$cursp&siteid=$row2[id]\">Click Here to Respond to your Contract to Host</a></li>";
	    else
	    {
	       if($row2[accept]=='y')
	       {
		  echo "<li>You have ACCEPTED your <a class=small target=\"_blank\" href=\"officials/rulescontract.php?session=$session&sport=$cursp&siteid=$row2[id]\">Contract to Host</a></li>";
	          if($row2[confirm]=='')
		     echo "<li>The NSAA has not responded to your contract yet.</li>";
		  else if($row2[confirm]=='y')
		     echo "<li>The NSAA has CONFIRMED your contract.</li>";
		  else
		     echo "<li>The NSAA has REJECTED your contract.</li>";
	       }
	       else 	//accept==n
	       {
		  echo "<li>You have DECLINED your <a class=small target=\"_blank\" href=\"officials/rulescontract.php?session=$session&sport=$cursp&siteid=$row2[id]\">Contract to Host</a></li>";
	      	  if($row2[confirm]=='y')
		     echo "<li>The NSAA has ACKNOWLEDGED your contract.</li>";
	    	  else
		     echo "<li>The NSAA has not responded to your contract yet.</li>";
	       }
	    }
	    echo "</ul></td></tr></table><br>";
         }
      }//end if contracted to host $cursp rules meeting(s)
   }//end for each sport
   if($hosting==0)
   {
      echo "<br>[You have no contracts to host a rules meeting at this time.]<br><br>";
   }

   //check if picked to host a sup test
	/* NOT IN USE AS OF FALL 2011
   echo "<tr align=left bgcolor=#E0E0E0>";
   echo "<th align=left>&nbsp;&nbsp;Supervised Test Host Information:</th></tr>";
   echo "<tr align=center><td><br>";
   $db=mysql_select_db($db_name2,$db);
   $hosting=0;
   $sql2="SELECT * FROM $db_name2.suptesthosts WHERE hostname='$school2' AND post='y'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0)
   {
      $hosting=1;
      while($row2=mysql_fetch_array($result2))
      {
         echo "<table width=500 border=1 bordercolor=#000000 cellspacing=1 cellpadding=3>";
         echo "<tr align=left><td><b>You have been selected to host a Supervised Test"; 
         $date=split("-",$row2[mtgdate]);
         echo " on $date[1]/$date[2]/$date[0]</b>:<br><ul>";
         if($row2[accept]=='')
            echo "<li><a class=small target=\"_blank\" href=\"officials/suptestcontract.php?session=$session&siteid=$row2[id]\">Click Here to Respond to your Contract to Host</a></li>";
         else
         {
            if($row2[accept]=='y')
            {
               echo "<li>You have ACCEPTED your <a class=small target=\"_blank\" href=\"officials/suptestcontract.php?session=$session&siteid=$row2[id]\">Contract to Host</a></li>";
               if($row2[confirm]=='')
                  echo "<li>The NSAA has not responded to your contract yet.</li>";
               else if($row2[confirm]=='y')
                  echo "<li>The NSAA has CONFIRMED your contract.</li>";
               else
                  echo "<li>The NSAA has REJECTED your contract.</li>";
            } 
            else     //accept==n
            {
               echo "<li>You have DECLINED your <a class=small target=\"_blank\" href=\"officials/suptestcontract.php?session=$session&siteid=$row2[id]\">Contract to Host</a></li>";
               if($row2[confirm]=='y')
                  echo "<li>The NSAA has ACKNOWLEDGED your contract.</li>";
               else
                  echo "<li>The NSAA has not responded to your contract yet.</li>";
            }
         }
         echo "</ul></td></tr></table><br>";
      }
   }//end if contracted to host sup test(s)
   if($hosting==0)
   {
      echo "<br>[You have no contracts to host a supervised test at this time.]<br><br>";
   }
   echo "</td></tr>";
	*/
}//end if level=6
else if($level==8)
{
}
echo $end_html;
?>
