<?php
echo "NOP";
exit();
//*******************************************************
//	ARCHIVE SCHOOLS DATABASE
//	-For NSAA Office Use every year
//	 around June 1
//	-Clean out tables, copy to $db_nameYEAR1YEAR2
//	-Keep same passwords
//*******************************************************

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

$level=GetLevel($session);

if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html;
echo GetHeader($session);

$year=date("Y");
$year1=$year-1;
$year2=$year+1;
$archivedb="$db_name".$year1.$year;

//COPY DATABASE to $archivedb FIRST USING PHPMYADMIN

//NOW run the following VIA BROWSER LOGGED IN --it's OK, the world will NOT blow up!

//2016: transfer __school20xx20yy to __school each year
$sql="SHOW TABLES LIKE '%school".$year.$year2."'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $table=$row[0];			       //__school20xx20yy
   $maintbl=preg_replace("/[0-9]/","",$table); //Now we have just __school
   $sql2="DELETE FROM $maintbl";	//CLEAR OUT CURRENT TABLE
   $result2=mysql_query($sql2);
   $sql2="INSERT INTO $maintbl SELECT * FROM $table";
   $result2=mysql_query($sql2);
   if(mysql_error()) { echo "ERROR:\r\n$sql2\r\n".mysql_error()."\r\nDid the columns in the table change?\r\n"; exit(); }
   //otherwise, we can drop the $year.$year2 table
   $sql2="DROP TABLE $table";
   $result2=mysql_query($sql2);
}

//CLEAN OUT CURRENT DATABASE
//In addition to running this script, now is a good time to clean out tables that have been copied throughout the year but are not needed in current DB

/*****IF THIS IS AN EVEN YEAR AND FB SCHEDULES WERE INPUT THIS YEAR: *****/
	//YOU'LL NEED TO COPY fbscheduling TO fbsched AFTER THE BELOW ARCHIVE IS RUN
	//USE: INSERT INTO fbsched (received,sid,oppid,homeid) SELECT received,sid,oppid,homeid FROM fbscheduling
	//...THEN UPDATE ../calculate/wildcard/totalupdate.php - update the years (2010, 2011 ==> 2012, 2013 for example)
	//...THEN RUN THE POINTS UPDATER FROM THE WILDCARD PROGRAM AND MAKE SURE LINKS ARE UNHIDDEN ON FOOTBALL WEBPAGE (Jeff can do this)
    	//Finally, change $table from fbscheduling to fbsched in /calculate/wildcard/fbschedules.php

//Then:
   mysql_select_db($db_name,$db);
   $june1=date("Y")."-06-01";
   echo "<br /><br /><h3>June 1: $june1</h3>";

//NSAA CUP
$sql="UPDATE cupregistrationtable SET tablename='schoolregistration'";
$result=mysql_query($sql);

	/* As of 6/1/2011, I'm not archiving these here because I want to wait until Bud gives the OK
   $sql="DELETE FROM allstatenom";
   $result=mysql_query($sql);
	*/
   
   //APPS TO HOST
$sql="UPDATE hostapp_bb_b SET interested='',dateschecked=''";
$result=mysql_query($sql);
$sql="UPDATE hostapp_bb_g SET interested='',dateschecked=''";
$result=mysql_query($sql);
$sql="UPDATE hostapp_sp SET interested=''";
$result=mysql_query($sql);
$sql="SHOW FULL COLUMNS FROM hostapp_sp WHERE Field LIKE 'date%'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $field=$row[0];
   $sql2="UPDATE hostapp_sp SET $field=''";
   $result2=mysql_query($sql2);
}
$sql="UPDATE hostapp_wr SET interested=''";
$result=mysql_query($sql);
$sql="SHOW FULL COLUMNS FROM hostapp_wr WHERE Field LIKE 'date%'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $field=$row[0];
   $sql2="UPDATE hostapp_wr SET $field=''";
   $result2=mysql_query($sql2);
}
$sql="UPDATE hostapp_so SET interested=''";
$result=mysql_query($sql);
$sql="SHOW FULL COLUMNS FROM hostapp_so WHERE Field LIKE 'date%'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $field=$row[0];
   $sql2="UPDATE hostapp_so SET $field=''";
   $result2=mysql_query($sql2);
}
$sql="UPDATE hostapp_ba SET interested=''";
$result=mysql_query($sql);
$sql="SHOW FULL COLUMNS FROM hostapp_ba WHERE Field LIKE 'date%'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $field=$row[0];
   $sql2="UPDATE hostapp_ba SET $field=''";
   $result2=mysql_query($sql2);
}
$sql="UPDATE hostapp_go_b SET interested=''";
$result=mysql_query($sql);
$sql="SHOW FULL COLUMNS FROM hostapp_go_b WHERE Field LIKE 'date%'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $field=$row[0];
   $sql2="UPDATE hostapp_go_b SET $field=''";
   $result2=mysql_query($sql2);
}
$sql="UPDATE hostapp_tr SET interested=''";
$result=mysql_query($sql);
$sql="SHOW FULL COLUMNS FROM hostapp_tr WHERE Field LIKE 'date%'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $field=$row[0];
   $sql2="UPDATE hostapp_tr SET $field=''";
   $result2=mysql_query($sql2);
}

   //HIGH SCHOOLS
   $sql="UPDATE headers SET sem_inc='0',statefb='',statevb='',statewr='',statebbb='',statebbg='',statepp='',statesp='',statesb='',stategog='',statecc=''";
   $result=mysql_query($sql);
   //MIDDLE SCHOOLS
   $sql="UPDATE middleschools SET sem_inc='0'";
   $result=mysql_query($sql);
   
   //RULES MEETINGS
   $sql="UPDATE logins SET rulesmeeting=''";
   $result=mysql_query($sql);
   $sql="DELETE FROM rulesmeetingattendance";
   $result=mysql_query($sql);
   $sql="DELETE FROM rulesmeetingtransactions";
   $result=mysql_query($sql);
   $sql="SHOW TABLES LIKE '%rulesmeetings'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $sql2="DELETE FROM $row[0]";
      $result2=mysql_query($sql2);
   }

   $sql="DELETE FROM ba";
   $result=mysql_query($sql);
   $sql="DELETE FROM ba_state";
   $result=mysql_query($sql);
   $sql="DELETE FROM basched WHERE received<='$june1'";
   $result=mysql_query($sql);
   $sql="DELETE FROM batourn WHERE received<='$june1'";
   $result=mysql_query($sql);
   $sql="UPDATE baschool SET oosfinal='',shortsched='0',filename='',programorder=0,approvedforprogram=0";
   $result=mysql_query($sql);
   
   $sql="DELETE FROM bb_b";
   $result=mysql_query($sql);
   $sql="DELETE FROM bb_bstate";
   $result=mysql_query($sql);
   $sql="DELETE FROM bb_g";
   $result=mysql_query($sql);
   $sql="DELETE FROM bb_gstate";
   $result=mysql_query($sql);
   $sql="DELETE FROM bbbsched WHERE received<='$june1'";
   $result=mysql_query($sql);
   $sql="DELETE FROM bbbtourn WHERE received<='$june1'";
   $result=mysql_query($sql);
   $sql="UPDATE bbbschool SET oosfinal='',shortsched='0',filename='',programorder=0,approvedforprogram=0";
   $result=mysql_query($sql);
   $sql="DELETE FROM bbgsched WHERE received<='$june1'";
   $result=mysql_query($sql);
   $sql="DELETE FROM bbgtourn WHERE received<='$june1'";
   $result=mysql_query($sql);
   $sql="UPDATE bbgschool SET oosfinal='',shortsched='0',filename='',programorder=0,approvedforprogram=0";
   $result=mysql_query($sql);

   $sql="UPDATE ccbschool SET filename='',programorder=0,approvedforprogram=0";
   $result=mysql_query($sql);
   $sql="UPDATE ccgschool SET filename='',programorder=0,approvedforprogram=0";
   $result=mysql_query($sql);
   $sql="UPDATE ppschool SET filename='',programorder=0,approvedforprogram=0";
   $result=mysql_query($sql);
   
   $sql="DELETE FROM sobsched WHERE received<='$june1'";
   $result=mysql_query($sql);
   $sql="DELETE FROM sobtourn WHERE received<='$june1'";
   $result=mysql_query($sql);
   $sql="UPDATE sobschool SET oosfinal='',shortsched='0',filename='',programorder=0,approvedforprogram=0";
   $result=mysql_query($sql);
   $sql="DELETE FROM sogsched WHERE received<='$june1'";
   $result=mysql_query($sql);
   $sql="DELETE FROM sogtourn WHERE received<='$june1'";
   $result=mysql_query($sql);
   $sql="UPDATE sogschool SET oosfinal='',shortsched='0',filename='',programorder=0,approvedforprogram=0";
   $result=mysql_query($sql);
   $sql="DELETE FROM so_b";
   $result=mysql_query($sql);
   $sql="DELETE FROM so_bstate";
   $result=mysql_query($sql);
   $sql="DELETE FROM so_g";
   $result=mysql_query($sql);
   $sql="DELETE FROM so_gstate";
   $result=mysql_query($sql);

   $sql="DELETE FROM cc_b";
   $result=mysql_query($sql);
   $sql="DELETE FROM cc_b_state_indy";
   $result=mysql_query($sql);
   $sql="DELETE FROM cc_b_state_team";
   $result=mysql_query($sql);
   $sql="DELETE FROM cc_b_state_quals";
   $result=mysql_query($sql);
   $sql="DELETE FROM cc_b_state_teamquals";
   $result=mysql_fetch_array($result);
   $sql="DELETE FROM cc_classd";
   $result=mysql_query($sql);
   $sql="UPDATE cc_districts SET school_id='0',location='',date=''";
   $result=mysql_query($sql);
   $sql="DELETE FROM cc_g";
   $result=mysql_query($sql);
   $sql="DELETE FROM cc_g_state_indy";
   $result=mysql_query($sql);
   $sql="DELETE FROM cc_g_state_team";
   $result=mysql_query($sql);
   $sql="DELETE FROM cc_g_state_quals";
   $result=mysql_query($sql);
   $sql="DELETE FROM cc_g_state_teamquals";
   $result=mysql_fetch_array($result);

   $sql="DELETE FROM de";
   $result=mysql_query($sql);
   $sql="DELETE FROM de_coop";
   $result=mysql_query($sql);
   
/* As of Jun 2016, they will reset ejections themselves
   $sql="DELETE FROM ejections";
   $result=mysql_query($sql);
*/
   $sql="DELETE FROM eligibility";
   $result=mysql_query($sql);
   $sql="DELETE FROM transfers";
   $result=mysql_query($sql);
   $sql="DELETE FROM middleeligibility";
   $result=mysql_query($sql);
  
   $sql="DELETE FROM fb_classes";
   $result=mysql_query($sql); 
   $sql="DELETE FROM fb_coop";
   $result=mysql_query($sql);
   $sql="DELETE FROM fb_history";
   $result=mysql_query($sql);
   $sql="DELETE FROM fb_playoff";
   $result=mysql_query($sql);
   $sql="DELETE FROM fb_records";
   $result=mysql_query($sql);
   $sql="DELETE FROM fb_stat_def";
   $result=mysql_query($sql);
   $sql="DELETE FROM fb_stat_kick";
   $result=mysql_query($sql);
   $sql="DELETE FROM fb_stat_off";
   $result=mysql_query($sql);
   $sql="DELETE FROM fb_stat_pk";
   $result=mysql_query($sql);
   $sql="DELETE FROM fb_stat_qb";
   $result=mysql_query($sql);
   $sql="DELETE FROM fb_state";
   $result=mysql_query($sql);
   $sql="DELETE FROM fb_staff";
   $result=mysql_query($sql);
   $sql="DELETE FROM fb_team";
   $result=mysql_query($sql);
   $sql="DELETE FROM fbdist";
   $result=mysql_query($sql);
   $sql="DELETE FROM fbdista";
   $result=mysql_query($sql);
   $sql="DELETE FROM fbpriority";
   $result=mysql_query($sql);
   $sql="DELETE FROM fbsched WHERE received<='$june1'";
   $result=mysql_query($sql);
   $sql="UPDATE fbschool SET oosfinal='',filename='',programorder=0,approvedforprogram=0";
   $result=mysql_query($sql);
   $sql="DELETE FROM fbweeks WHERE received<='$june1'";
   $result=mysql_query($sql);
   
   //$sql="DELETE FROM reimbursements";
   //$result=mysql_query($sql);
   $sql="DELETE FROM finance_bb_b";
   $result=mysql_query($sql);
   $sql="DELETE FROM finance_bb_b_exp";
   $result=mysql_query($sql);
   $sql="DELETE FROM finance_bb_g";
   $result=mysql_query($sql);
   $sql="DELETE FROM finance_bb_g_exp";
   $result=mysql_query($sql);
   $sql="DELETE FROM finance_fb";
   $result=mysql_query($sql);
   $sql="DELETE FROM finance_vb";
   $result=mysql_query($sql);
   $sql="DELETE FROM finance_vb_exp";
   $result=mysql_query($sql);
   $sql="DELETE FROM finance_sb";
   $result=mysql_query($sql);
   $sql="DELETE FROM finance_wr";
   $result=mysql_query($sql);
   $sql="DELETE FROM finance_wr_exp";
   $result=mysql_query($sql);

   $sql="DELETE FROM forex";
   $result=mysql_query($sql);
   
   $sql="DELETE FROM go_b";
   $result=mysql_query($sql);
   $sql="DELETE FROM gobdistresults_indy";
   $result=mysql_query($sql);
   $sql="DELETE FROM gobdistresults_team";
   $result=mysql_query($sql);
   $sql="DELETE FROM gobtourn";
   $result=mysql_query($sql);
   $sql="DELETE FROM gobtournindy";
   $result=mysql_query($sql);
   $sql="DELETE FROM gobtournteam";
   $result=mysql_query($sql);
   $sql="DELETE FROM go_g";
   $result=mysql_query($sql);
   $sql="DELETE FROM gogdistresults_indy";
   $result=mysql_query($sql);
   $sql="DELETE FROM gogdistresults_team";
   $result=mysql_query($sql);
   $sql="DELETE FROM gogtourn";
   $result=mysql_query($sql);
   $sql="DELETE FROM gogtournindy";
   $result=mysql_query($sql);
   $sql="DELETE FROM gogtournteam";
   $result=mysql_query($sql);
 	/*	AS OF JUNE 2015 WE DO NOT GET RID OF THIS RIGHT AWAY
   $sql="DELETE FROM jo";
   $result=mysql_query($sql);
   $sql="DELETE FROM joassignments";
   $result=mysql_query($sql);
   $sql="DELETE FROM joentries";
   $result=mysql_query($sql);
   $sql="UPDATE jocategories SET webapproved=0,webapproved2=0";
   $result=mysql_query($sql);
   $sql="DELETE FROM joqualifiers";
   $result=mysql_query($sql);
   $sql="UPDATE jojudges SET datesub=0,session=0";
   $result=mysql_query($sql);
	*/

   $sql="DELETE FROM maillog";
   $result=mysql_query($sql); 

	/*	LEAVE MUSIC IN, BUD USUALLY NEEDS TO DOUBLE-CHECK THINGS AFTER JUNE 1
   $sql="DELETE FROM mucoops";
   $result=mysql_query($sql);
   $sql="DELETE FROM muentries";
   $result=mysql_query($sql);
   $sql="DELETE FROM muschools";
   $result=mysql_query($sql);
   $sql="DELETE FROM mustudentries";
   $result=mysql_query($sql);
   $sql="DELETE FROM mumultiplesiteensembles";
   $result=mysql_query($sql);
	*/
   
   $sql="DELETE FROM pp";
   $result=mysql_query($sql);
   $sql="DELETE FROM pp_coop";
   $result=mysql_query($sql);
   $sql="DELETE FROM pp_state";
   $result=mysql_query($sql);
   $sql="DELETE FROM pp_state_students";
   $result=mysql_query($sql);
   $sql="DELETE FROM pp_students";
   $result=mysql_query($sql);
   $sql="DELETE FROM contentreviews";
   $result=mysql_query($sql);

   //proposals:
   $sql="DELETE FROM proposals";
   $result=mysql_query($sql);
   $sql="DELETE FROM proposaltables";
   $result=mysql_query($sql);
   
   $sql="DELETE FROM registration";
   $result=mysql_query($sql);

   $sql="DELETE FROM reportcard_bbb";
   $result=mysql_query($sql);
   $sql="DELETE FROM reportcard_bbg";
   $result=mysql_query($sql);
   
   $sql="DELETE FROM sb";
   $result=mysql_query($sql);
   $sql="DELETE FROM sb_state";
   $result=mysql_query($sql);
   $sql="DELETE FROM sbsched WHERE received<='$june1'";
   $result=mysql_query($sql);
   $sql="DELETE FROM sbtourn WHERE received<='$june1'";
   $result=mysql_query($sql);
   $sql="UPDATE sbschool SET oosfinal='',shortsched='0',filename='',programorder=0,approvedforprogram=0";
   $result=mysql_query($sql);
 
   $sql="DELETE FROM sessions WHERE session_id!='$session'";
   $result=mysql_query($sql);

   //soccer schedules are taken care of above 

   $sql="DELETE FROM sp";
   $result=mysql_query($sql); 
   $sql="DELETE FROM spshuffle";
   $result=mysql_query($sql);
   $sql="DELETE FROM sp_districts";
   $result=mysql_query($sql); 
   $sql="DELETE FROM sp_state_dist";
   $result=mysql_query($sql);
   $sql="DELETE FROM sp_state_drama";
   $result=mysql_query($sql);
   $sql="DELETE FROM sp_state_duet";
   $result=mysql_query($sql);
   $sql="DELETE FROM sp_state_qual";
   $result=mysql_query($sql);
  
   //TENNIS 
   $sql="DELETE FROM te_b";
   $result=mysql_query($sql);
   $sql="DELETE FROM te_g";
   $result=mysql_query($sql);
   $sql="DELETE FROM te_bbrackets";
   $result=mysql_query($sql);
   $sql="DELETE FROM te_gbrackets";
   $result=mysql_query($sql);
   $sql="DELETE FROM te_bdistresults";
   $result=mysql_query($sql);
   $sql="DELETE FROM te_bmeetresults";
   $result=mysql_query($sql);
   $sql="DELETE FROM te_bmeets";
   $result=mysql_query($sql);
   $sql="DELETE FROM te_bseeds";
   $result=mysql_query($sql);
   $sql="DELETE FROM te_bstate";
   $result=mysql_query($sql);
   $sql="DELETE FROM te_bteamscores";
   $result=mysql_query($sql);
   $sql="DELETE FROM te_gdistresults";
   $result=mysql_query($sql);
   $sql="DELETE FROM te_gmeetresults";
   $result=mysql_query($sql);
   $sql="DELETE FROM te_gmeets";
   $result=mysql_query($sql);
   $sql="DELETE FROM te_gseeds";
   $result=mysql_query($sql);
   $sql="DELETE FROM te_gstate";
   $result=mysql_query($sql);
   $sql="DELETE FROM te_gteamscores";
   $result=mysql_query($sql);

   $sql="UPDATE swschool SET stateform_b='',stateform_g=''";
   $result=mysql_query($sql);
   $sql="DELETE FROM sw_hy3files";
   $result=mysql_query($sql);
   $sql="DELETE FROM sw_state_b";
   $result=mysql_query($sql);
   $sql="DELETE FROM sw_state_g";
   $result=mysql_query($sql);
   $sql="DELETE FROM sw_verify_b";
   $result=mysql_query($sql);
   $sql="DELETE FROM sw_verify_g";
   $result=mysql_query($sql);
   $sql="DELETE FROM sw_verify_perf_b";
   $result=mysql_query($sql);
   $sql="DELETE FROM sw_verify_perf_g";
   $result=mysql_query($sql);
   $sql="DELETE FROM swsched WHERE meetdate<='$june1'";
   $result=mysql_query($sql);
   $sql="DELETE FROM eligibility_sw";
   $result=mysql_query($sql);

   $sql="DELETE FROM tr_b";
   $result=mysql_query($sql);
   $sql="DELETE FROM tr_b_coop";
   $result=mysql_query($sql);
   $sql="DELETE FROM tr_g";
   $result=mysql_query($sql);
   $sql="DELETE FROM tr_g_coop";
   $result=mysql_query($sql);
   $sql="DELETE FROM tr_state_dist_b";
   $result=mysql_query($sql);
   $sql="DELETE FROM tr_state_dist_g";
   $result=mysql_query($sql);
   $sql="DELETE FROM tr_state_extra_b";
   $result=mysql_query($sql);
   $sql="DELETE FROM tr_state_extra_g";
   $result=mysql_query($sql);
   $sql="DELETE FROM tr_state_place_b";
   $result=mysql_query($sql);
   $sql="DELETE FROM tr_state_place_g";
   $result=mysql_query($sql);
   $sql="DELETE FROM tr_state_qual_b";
   $result=mysql_query($sql);
   $sql="DELETE FROM tr_state_qual_g";
   $result=mysql_query($sql);
   $sql="DELETE FROM tr_state_relays_b";
   $result=mysql_query($sql);
   $sql="DELETE FROM tr_state_relays_g";
   $result=mysql_query($sql);

   $sql="DELETE FROM vb";
   $result=mysql_query($sql);
   $sql="DELETE FROM vb_state";
   $result=mysql_query($sql);
   $sql="DELETE FROM vbsched WHERE received<='$june1'";
   $result=mysql_query($sql);
   $sql="DELETE FROM vbtourn WHERE received<='$june1'";
   $result=mysql_query($sql);
   $sql="UPDATE vbschool SET oosfinal='',shortsched='0',filename='',programorder=0,approvedforprogram=0";
   $result=mysql_query($sql);

   $sql="DELETE FROM wr";
   $result=mysql_query($sql);
   $sql="DELETE FROM wrsched WHERE received<='$june1'";
   $result=mysql_query($sql);
   $sql="DELETE FROM wrtourn WHERE received<='$june1'";
   $result=mysql_query($sql);
   $sql="UPDATE wrschool SET oosfinal='',shortsched='0',filename='',programorder=0,approvedforprogram=0";
   $result=mysql_query($sql);
   $sql="DELETE FROM wrassessorsapp";
   $result=mysql_query($sql);
   $sql="UPDATE wrassessors SET appid='0'";
   $result=mysql_query($sql);
   $sql="UPDATE wrassessors SET dateinitiated=0,datecompleted=0,datepaid=0";
   $result=mysql_query($sql);

echo "<br>DATABASE SUCCESSFULLY CLEANED OUT!";
echo $end_html;
?>
