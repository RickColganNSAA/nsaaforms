<?php
//echo "NOP";
//exit();
//********************************************************
//	ARCHIVE OFFICIALS/JUDGES DATABASE
//	-Every year on the evening of May 31
//	-Clean out tables, copy to officialsYEAR1YEAR2
//	-Set mailing #'s to -1, payment & checks to null
//	-Keep same passwords, zones
//********************************************************

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

/*
$level=GetLevel($session);

if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php?error=1");
   exit();
}
*/
echo $init_html;
echo GetHeader($session);

$year=date("Y");
$year1=$year-1;
$archivedb=$db_name2.$year1.$year;
$june1="$year-06-01";

//COPY DATABASE to nsaaofficialsYEAR1YEAR2 FIRST, VIA PHPMYADMIN

//NOW run the following via browser, logged in: it's OK, the world will NOT blow up!

   //CLEAN OUT CURRENT DATABASE
   //Clean out tables that have been copied this year but aren't needed in current database anymore

   //Then:
   mysql_select_db($db_name2,$db);

   //RULES MEETINGS
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

	//SWIMMING
   $sql="DELETE FROM sw_zones";
   //$result=mysql_query($sql);
   $sql="DELETE FROM swapply";
   $result=mysql_query($sql);
   $sql="DELETE FROM swtest_results";
   $result=mysql_query($sql);
   $sql="DELETE FROM swtest";
   $result=mysql_query($sql);
   $sql="DELETE FROM swtest_categ";
   $result=mysql_query($sql);
   $sql="DELETE FROM swtest_mchoices";
   $result=mysql_query($sql);
   $sql="DELETE FROM swsched WHERE offdate<'$june1'";
   $result=mysql_query($sql);
   $sql="UPDATE swoff SET years=years+1 WHERE mailing>=100";
   $result=mysql_query($sql);
   $sql="UPDATE swoff SET payment='', datepaid='', appid='', mailing='-1'";
   $result=mysql_query($sql);
	//DIVING
   $sql="DELETE FROM di_zones";
   //$result=mysql_query($sql);
   $sql="DELETE FROM di_votes";
   $result=mysql_query($sql);
   $sql="DELETE FROM ditest_results";
   $result=mysql_query($sql);
   $sql="DELETE FROM ditest";
   $result=mysql_query($sql);
   $sql="DELETE FROM ditest_categ";
   $result=mysql_query($sql);
   $sql="DELETE FROM ditest_mchoices";
   $result=mysql_query($sql);
   $sql="DELETE FROM disched WHERE offdate<'$june1'";
   $result=mysql_query($sql);
   $sql="UPDATE dioff SET years=years+1 WHERE mailing>=100";
   $result=mysql_query($sql);
   $sql="UPDATE dioff SET payment='', datepaid='', appid='', mailing='-1'";
   $result=mysql_query($sql);
	//FOOTBALL
   $sql="SELECT t1.offid FROM fbcontracts AS t1, fbbrackets AS t2 WHERE t1.gameid=t2.id AND t2.round='Finals' AND t1.post='y' AND t1.accept='y' AND t1.confirm='y'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $curyr=date("Y")-1;
      $curyr=substr($curyr,2,2);
      $sql2="SELECT * FROM fboff WHERE offid='$row[offid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $stateyears=$row2[stateyears]; $numstateyears=$row2[numstateyears];
      if(!ereg($curyr,$stateyears))
      {
         $stateyears="$curyr,".$stateyears;
         $numstateyears++;
         $sql2="UPDATE fboff SET stateyears='$stateyears',numstateyears='$numstateyears' WHERE offid='$row[offid]'";
         $result2=mysql_query($sql2);
      }
   }
   $sql="UPDATE fbbrackets SET sid1='',sid2='',school1='',school2='',day='',time='',hostschool='',showoffs=''";
   $result=mysql_query($sql); 
   $sql="DELETE FROM fb_zones";
   //$result=mysql_query($sql);
   $sql="DELETE FROM fbtest_results";
   $result=mysql_query($sql);
   $sql="DELETE FROM fbtest";
   $result=mysql_query($sql);
   $sql="DELETE FROM fbtest_categ";
   $result=mysql_query($sql);
   $sql="DELETE FROM fbtest_mchoices";
   $result=mysql_query($sql);
   $sql="DELETE FROM fbsched WHERE offdate<'$june1'";
   $result=mysql_query($sql);
   $sql="UPDATE fboff SET years=years+1 WHERE mailing>=100";
   $result=mysql_query($sql);
   $sql="UPDATE fboff SET payment='', datepaid='', appid='', mailing='-1'";
   $result=mysql_query($sql);
   $sql="DELETE FROM fbcontracts";
   $result=mysql_query($sql);
   $sql="DELETE FROM fbobserve";
   $result=mysql_query($sql);
   $sql="DELETE FROM fbapply";
   $result=mysql_query($sql);
	//OTHER SPORTS
   $sql0="SHOW TABLES LIKE '%districts'";
   $result0=mysql_query($sql0);
   while($row0=mysql_fetch_array($result0))
   {
      $temp=split("districts",$row0[0]);
      $cursp=$temp[0];
      if($cursp=='trb' || $cursp=='trg')
         $cursp2="tr";
      else $cursp2=$cursp;
      $apply=$cursp2."apply";
      $contracts=$cursp."contracts";
      $districts=$cursp."districts";
      $disttimes=$cursp."disttimes";
      $seeds=$cursp."seeds";
      $observe=$cursp."observe";
      $off=$cursp2."off";
      $sched=$cursp2."sched";
      $test_results=$cursp2."test_results";
      $test=$cursp2."test";
      $test_categ=$test."_categ";
      $test_mchoices=$test."_mchoices";
      $votes=$cursp2."_votes";
      if(ereg("bb",$cursp))
      {	
	 $apply="bbapply";
	 $observe="bbobserve";
	 $off="bboff";
	 $sched="bbsched";
         $test_results="bbtest_results";
         $test="bbtest";
         $test_categ="bbtest_categ";
	 $test_mchoices="bbtest_mchoices";
      }
      if(ereg("so",$cursp))
      { 
         $apply="soapply";
         $observe="soobserve";
         $off="sooff";
         $sched="sosched";
         $test_results="sotest_results";
         $test="sotest";
         $test_categ="sotest_categ";
	 $test_mchoices="sotest_mchoices";
      }

      $sql="UPDATE $off SET years=years+1 WHERE mailing>=100";
      $result=mysql_query($sql);

      $sql="DELETE FROM $apply";
      $result=mysql_query($sql);
      //Check for state contracts; update those offs' years worked state fields
         if($cursp=='ba' || $cursp=='bbb' || $cursp=='bbg' || $cursp=='sb' || $cursp=='sob' || $cursp=='sog' || $cursp=='vb')
 	 {
	    $sql="SELECT t1.offid FROM $contracts AS t1, $disttimes AS t2, $districts AS t3 WHERE t1.disttimesid=t2.id AND t2.distid=t3.id AND t3.type='State' AND t1.post='y' AND t1.accept='y' AND t1.confirm='y'";
	    $result=mysql_query($sql);
	    while($row=mysql_fetch_array($result))
	    {
	       if($cursp=='sb' || $cursp=='vb') $curyr=date("Y")-1;
	       else $curyr=date("Y");
	       $curyr=substr($curyr,2,2);
	       $sql2="SELECT * FROM $off WHERE offid='$row[offid]'";
	       $result2=mysql_query($sql2);
	       $row2=mysql_fetch_array($result2);
	       $stateyears=$row2[stateyears]; $numstateyears=$row2[numstateyears];
	       if($cursp=='bbb') { $stateyears=$row2[bstateyears]; $numstateyears=$row2[bnumstateyears]; }
	       else if($cursp=='bbg') { $stateyears=$row2[gstateyears]; $numstateyears=$row2[gnumstateyears]; }
	       if(!ereg($curyr,$stateyears))
	       {
                  if(trim($stateyears)=="") $stateyears=$curyr;
                  else $stateyears="$curyr,".$stateyears;
	          $numstateyears++;
		  if($cursp=='bbb') { $field="bstateyears"; $field2="bnumstateyears"; }
	  	  else if($cursp=='bbg') { $field="gstateyears"; $field2="gnumstateyears"; }
	          else { $field="stateyears"; $field2="numstateyears"; }
                  $sql2="UPDATE $off SET $field='$stateyears',$field2='$numstateyears' WHERE offid='$row[offid]'";
	          $result2=mysql_query($sql2);
	       }
	    }
            $sql="UPDATE $districts SET seeded='',bracketed=''";
            $result=mysql_query($sql);
	    $sql="DELETE FROM $seeds";
	    $result=mysql_query($sql);
	 }
	 else if($cursp=='wr')
	 {
	    $sql="SELECT t1.offid FROM $contracts AS t1, $districts AS t2 WHERE t1.distid=t2.id AND t2.type='State' AND t1.post='y' AND t1.accept='y' AND t1.confirm='y'";
	    $result=mysql_query($sql);
	    while($row=mysql_fetch_array($result))
	    {
	       $curyr=date("Y");
	       $curyr=substr($curyr,2,2);
               $sql2="SELECT * FROM $off WHERE offid='$row[offid]'";
               $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
               $stateyears=$row2[stateyears]; $numstateyears=$row2[numstateyears];
               if(!ereg($curyr,$stateyears))
               {
                  $stateyears="$curyr,".$stateyears;
                  $numstateyears++;
                  $sql2="UPDATE $off SET stateyears='$stateyears',numstateyears='$numstateyears' WHERE offid='$row[offid]'";
                  $result2=mysql_query($sql2);
               }
	    }
	 }
      $sql="DELETE FROM $contracts";
      $result=mysql_query($sql);
      $sql="DELETE FROM $seeds";
      $result=mysql_query($sql);
      $sql="UPDATE $districts SET showoffs=''";
      $result=mysql_query($sql);
      $sql="UPDATE $districts SET showtimes=''";
      $result=mysql_query($sql);
      $sql="UPDATE $districts SET bracketed=''";
      $result=mysql_query($sql);
      $sql="UPDATE $districts SET seeded=''";
      $result=mysql_query($sql);
      $sql="UPDATE $districts SET showdistinfo=''";
      $result=mysql_query($sql);
      $sql="UPDATE $districts SET teamscores=''";
      $result=mysql_query($sql);
      $sql="UPDATE $districts SET resultssubmitted=''";
      $result=mysql_query($sql);
      
         $sql="UPDATE $districts SET schools='',sids='',hostschool='',hostid='0',dates='',site='',director='',email='',post='',accept='',confirm=''";
         if($cursp=="ba" || $cursp=="sb")
            $sql.=",fieldct='',lightedfieldct=''";
         else if($cursp=='sp')
            $sql.=",submitted='',shuffled=''";
         else if($cursp2=='tr')
	    $sql.=",resultssub_b='',resultssub_g=''";
	 else if(preg_match("/cc/",$cursp))
	    $sql.=",submitted_b='',submitted_g=''";
      $result=mysql_query($sql);
      if(mysql_error())
         echO "$sql<br>".mysql_error()."<br><br>";
      //REMOVE ALL (NON-STATE) ENTRIES IN $disttimes TABLE (leave State entry in because it is a placeholder)
      $sql="DELETE FROM bbbdisttimes WHERE distid!='92'";
      $result=mysql_query($sql);
      $sql="DELETE FROM bbgdisttimes WHERE distid!='93'";
      $result=mysql_query($sql);
      $sql="DELETE FROM badisttimes WHERE day!='0000-00-00'";
      $result=mysql_query($sql);
	$sql="UPDATE timeslot_duedates SET duedate=DATE_ADD(duedate,INTERVAL 1 YEAR) WHERE sport='ba'";
     	$result=mysql_query($sql);
      $sql="DELETE FROM sobdisttimes WHERE day!='0000-00-00'";
      $result=mysql_query($sql);
        $sql="UPDATE timeslot_duedates SET duedate=DATE_ADD(duedate,INTERVAL 1 YEAR) WHERE sport='sob'";
        $result=mysql_query($sql);
      $sql="DELETE FROM sogdisttimes WHERE day!='0000-00-00'";
      $result=mysql_query($sql);
        $sql="UPDATE timeslot_duedates SET duedate=DATE_ADD(duedate,INTERVAL 1 YEAR) WHERE sport='sog'";
        $result=mysql_query($sql);
      $sql="DELETE FROM vbdisttimes WHERE day!='0000-00-00'";
      $result=mysql_query($sql);
        $sql="UPDATE timeslot_duedates SET duedate=DATE_ADD(duedate,INTERVAL 1 YEAR) WHERE sport='vb'";
        $result=mysql_query($sql);

        $sql="UPDATE timeslot_duedates SET duedate=DATE_ADD(duedate,INTERVAL 1 YEAR) WHERE sport='sb'";
        $result=mysql_query($sql);
        $sql="UPDATE timeslot_duedates SET duedate=DATE_ADD(duedate,INTERVAL 1 YEAR) WHERE sport='bbb'";
        $result=mysql_query($sql);
        $sql="UPDATE timeslot_duedates SET duedate=DATE_ADD(duedate,INTERVAL 1 YEAR) WHERE sport='bbg'";
        $result=mysql_query($sql);


      $sql="DELETE FROM $observe";
      $result=mysql_query($sql);
      
      $sql="DELETE FROM $sched WHERE offdate<'$june1'";
      $result=mysql_query($sql);
      $sql="DELETE FROM $test_results";
      $result=mysql_query($sql);
      $sql="DELETE FROM $test";
      $result=mysql_query($sql);
      $sql="DELETE FROM $test_categ";
      $result=mysql_query($sql);
      $sql="DELETE FROM $test_mchoices";
      $result=mysql_query($sql);
      $sql="DELETE FROM $votes";
      $result=mysql_query($sql);
      $sql="UPDATE $off SET payment='', datepaid='', appid='', mailing='-1'";
      $result=mysql_query($sql);
   }//end for each activity

   //PART 2 TESTS
   $sql="SHOW TABLES LIKE '%test2'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $cursp=preg_replace("/test2/","",$row[0]);
      $test2results=$cursp."test2_results";
      $test2=$cursp."test2";
      $test2mchoices=$test2."_mchoices";
      $test2answers=$test2."_answers";
      $sql2="DELETE FROM $test2results";
      $result2=mysql_query($sql2);
      $sql2="DELETE FROM $test2";
      $result2=mysql_query($sql2);
      $sql2="DELETE FROM $test2mchoices";
      $result2=mysql_query($sql2);
      $sql2="DELETE FROM $test2answers";
      $result2=mysql_query($sql2);
   }

   $sql="UPDATE officials SET senttofed=''";
   $result=mysql_query($sql);
   $sql="DELETE FROM yellowcards";
   $result=mysql_query($sql);
   $sql="DELETE FROM ejections";
   $result=mysql_query($sql);
   $sql="DELETE FROM judgesapp";
   $result=mysql_query($sql);
   $sql="DELETE FROM pendingjudges";
   $result=mysql_query($sql);
   $sql="UPDATE mailing SET mailnum='1',mailnum2='99',affmailnum='50'";
   $result=mysql_query($sql);
   $sql="DELETE FROM officialsapp";
   $result=mysql_query($sql);
   $sql="DELETE FROM pendingoffs";
   $result=mysql_query($sql);
   $sql="DELETE FROM pp_votes";
   $result=mysql_query($sql);
   $sql="DELETE FROM ppapply";
   $result=mysql_query($sql);
   $sql="DELETE FROM ppcontracts";
   $result=mysql_query($sql);
   $sql="UPDATE ppdistricts SET showoffs='',sids='',schools='',showdistinfo='',hostschool='',hostid='0',dates='',site='',director='',email='',post='',accept='',confirm='',showresults=''";
   $result=mysql_query($sql);
   $sql="DELETE FROM ppdeclines";
   $result=mysql_query($sql);
   $sql="DELETE FROM sessions WHERE session_id!='$session'";
   $result=mysql_query($sql);
   $sql="DELETE FROM sp_zones";
   //$result=mysql_query($sql);
   $sql="DELETE FROM sp_votes";
   $result=mysql_query($sql);
   $sql="DELETE FROM spapply";
   $result=mysql_query($sql);
   $sql="DELETE FROM spcontracts";
   $result=mysql_query($sql);
   $sql="UPDATE spdistricts SET showoffs='',hostschool='',hostid='0',dates='',sids='',schools='',shuffled='',showdistinfo='',site='',director='',email='',post='',accept='',confirm=''";
   $result=mysql_query($sql);
   $sql="DELETE FROM spdeclines";
   $result=mysql_query($sql);
   $sql="DELETE FROM spstateassign";
   $result=mysql_query($sql);
   $sql="DELETE FROM sptest_results";
   $result=mysql_query($sql);
   $sql="DELETE FROM sptest";
   $result=mysql_query($sql);
   $sql="DELETE FROM sptest_categ";
   $result=mysql_query($sql);
   $sql="DELETE FROM sptest_mchoices";
   $result=mysql_query($sql);
   $sql="DELETE FROM spshuffle";
   $result=mysql_query($sql);
   $sql="DELETE FROM spsuperior";
   $result=mysql_query($sql);

   //judges table:
   $sql="UPDATE judges SET firstyr='',qualified='',speech='',play='',firstyrplay='',firstyrspeech='',payment='',datereg='0000-00-00',amtpaid='',appid='',ppmailing='',spmailing='',ppmeeting='',spmeeting='',sptest='',pptest='',spdatesent='',ppdatesent='',pending=''";
   $result=mysql_query($sql);

   echo "<br><br><h3>The $year1-$year Officials & Judges Database has been successfully archived.  Thank You!</h3><br><br>";
   echo "<a class=small href=\"welcome.php?session=$session\">Home</a>";
echo $end_html;
?>
