<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//welcome.php: displays welcome page for specified user

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

//Get user's specifics from logins table using $session
$sql="SELECT t2.name, t2.level, t2.observesp,t2.offid, t2.obsid FROM sessions AS t1, logins AS t2 WHERE t1.session_id='$session' AND t1.login_id=t2.id";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$level=$row[1];
if($level==2)	//off
{ $name=GetOffName($row[offid]); }
else if($level==3)	//observer
{
  $name=GetObsName($row[obsid]);
}
else
{
   $name=$row[0];
}
//$sport=$row[2];

echo $init_html;
$header=GetHeader($session,"welcome");
echo $header;

//get today's date:
$day=date(l);
$month=date(F);
$num=date(j);
$year=date(Y);
$date="$day, $month $num, $year";

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
   $curyearroster="$year0-$year";
   $lastyearroster="$year00-$year0";
   $sql="SHOW DATABASES LIKE '$archivedbroster'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0) $archiveroster=0;
   else $archiveroster=1;
}
else
{
   $archiveroster=1;
   $curyearroster="$year-$year1";
   $lastyearroster="$year0-$year";
}

if($level==1 || $level==4)	//NSAA user (main or observer admin)
{
   echo "<br><table width=90% cellspacing=0 cellpadding=0>";
   echo "<caption><b>Welcome, $name!<br>";
   echo "Today's Date is: $date</b><br><br></caption>";
   if($level==1)
   {
      //After May 25th: ALLOW NSAA USER TO ARCHIVE (once)
      $year0=$year-1; $archivedb="$db_name2".$year0.$year;
      $may26=mktime(0,0,0,5,26,$year);
      $today=time();
      $sql="SHOW DATABASES";
      $result=mysql_query($sql);
      $dbexists=0;
      while($row=mysql_fetch_array($result)) 
      {
	 if($archivedb==$row[0]) $dbexists=1;
      }
      if($dbexists==0 && $today>=$may26)	//if NOT archived yet and today is AFTER May 25th
      {
    	 echo "<tr align=center><td><a href=\"archive.php?session=$session\" onClick=\"return confirm('Are you sure you want to archive the $year0-$year Officials & Judges Database?\r\n\r\nThis will copy the current database to an archived database and clean out the appropriate tables and settings in the current database.\r\n\r\nThis action cannot be undone or redone.');\">Click Here to ARCHIVE the $year0-$year Officials & Judges Database</a><br><br></td></tr>";
      }
   }
   echo "<tr align=left bgcolor=#E0E0E0>";
   echo "<th align=left>&nbsp;&nbsp;OUTBOX: Messages & Uploads:</th></tr>";
   echo "<tr align=center><td><br><table>";
   echo "<tr align=left bgcolor=#E0E0E0><th align=left>&nbsp;&nbsp;Messages:</th></tr>";
   echo "<tr align=left><td><br><a class=small href=\"post_message.php?session=$session\">Post New Message to Official(s)</a></td></tr>";
   echo "<tr align=left><td><a class=small href=\"edit_message.php?session=$session\">Edit/Delete Messages</a><br><br></td></tr>";
   echo "<tr align=left bgcolor=#E0E0E0><th align=left>&nbsp;&nbsp;Downloads:</th></tr>";
   echo "<tr align=center><td><br>";
   echo "<a class=small href=\"uploaddoc.php?session=$session\">Upload Documents for Officials</a><br></td></tr>";
   echo "</table></td></tr>";
}
else if($level==2)	//Official-Access
{
   $offid=GetOffID($session);

   //get sports this official is registered for
   $spreg_abb=array();
   $spreg_long=array();
   $ix=0;
   for($i=0;$i<count($activity);$i++)
   {
      $table=$activity[$i]."off";
      $sql="SELECT * FROM $table WHERE offid='$offid' AND payment!=''";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)	//Official has paid for this sport
      {
         $spreg_abb[$ix]=$activity[$i];
         $spreg_long[$ix]=$act_long[$i];
 	 $ix++;
      }
   }

   if($ix==0)	//HAS NOT PAID FOR ANY SPORT--show special screen with link to CC app
   {
      echo "<br><table width=600><tr align=left><td>";
      echo "<b>Welcome, $name!</b><br><br>";
      echo "You have an account in our system, but you have not yet paid to register as an official for a specific sport(s) for this year.<br><br>";
      echo "Please complete your application and pay your registration fee online using the <a href=\"application.php?session=$session\" class=small>Online Official's Application Form</a>, which will be available <u><b>June 1</b></u>.<br><br>";
      echo "Thank You!</td></tr></table>";
      echo $end_html;
      exit();
   }

   echo "<br><table width=90% cellspacing=0 cellpadding=0>";
   echo "<caption><b>Welcome, $name!<br>";
   echo "Today's Date is: $date</b><br><br></caption>";
   echo "<tr align=left><td>NOTE: Click on headings to open/close that section.</td></tr>";
  
   /******INBOX: REMINDERS, MESSAGES & DOWNLOADS******/
   if(!$open || $open=='') $open="1";
   if($open==1) $newopen='not1';
   else $newopen=1;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=1 href=\"welcome2.php?s
ession=$session&open=$newopen#1\">";
   if($open==1) echo "&nabla&nbsp;";
   else echo "&Delta;&nbsp;";
   echo "INBOX: Reminders, Messages & Downloads:&nbsp;</th></tr>";
   if($open==1)
   {
      echo "<tr align=center><td><table>";

      //REMINDERS:
      echo "<tr bgcolor=#E0E0E0 align=left>";
      echo "<th align=left>&nbsp;&nbsp;Reminders:</th></tr>";
      echO "<tr align=center><td><table>";
      $reminder=0;
      //$reminder=1;
      //echo "<tr align=left><td><font style=\"color:red\"><br><b>OFFICIALS: Please check the \"Schedule Entry\" section below to see if you have any basketball games for which you need to fill out a <u>Game Report Card</u>.<br><br>";

      //see if any apps are due soon:
      $sql="SELECT * FROM app_duedates";
      $result=mysql_query($sql);
      $header=0;
      while($row=mysql_fetch_array($result))
      {
         $cursport=$row[sport];
         for($i=0;$i<count($spreg_abb);$i++)
         {
	    if($cursport==$spreg_abb[$i] && DueSoon($row[duedate],10) && !PastDue($row[duedate],2))
	    {
	       $reminder=1;
               if($header==0)
	       {
	          echo "<tr align=left><th align=left class=smaller>";
	          echo "The following applications to officiate are due soon:</th></tr>";
	          echo "<tr align=left><td>";
	          $header=1;
	       }
               if($cursport=='di') $appsport='sw';
	       else $appsport=$cursport;
	       echo "<a class=small href=\"".$appsport."app.php?session=$session&sport=$cursport\">$spreg_long[$i]";
	       if($spreg_abb[$i]=='tr')
	       {
	          //Track: No app, just checkbox on schedule:
	          echo " (Indicate on your Schedule if you would like to be a State Track & Field Starter)";
	       }
	       else
	          echo " Application to Officiate";
	       echo "</a> (Due $row[duedate])<br>";
	    }
         }
      }
      if($header==1) echo "</td></tr>";
      //if they've registered for any sport, remind them to enter their schedule
      $header=0; $curseason=GetCurrentSeason();
      for($i=0;$i<count($spreg_abb);$i++)
      {
         $table=$spreg_abb[$i]."apply";
	 if($header==0 && $curseason==GetSeason($spreg_abb[$i]))
	 {
	    echo "<tr align=left><th align=left class=smaller>";
	    echo "Don't forget to fill out your $curseason schedule(s):<br>";
            echo "<font style=\"color:red\"><b>***Please check your schedules to make sure you did not<br>accidentally enter dates that are already past, such as<br>01/07/2006 instead of 01/07/2007.</b></th></tr>";
	    echo "<tr align=left><td>";
	    $header=1;
	 }
         if($curseason==GetSeason($spreg_abb[$i]))
         {
	    $reminder=1;
	    echo "<a class=small href=\"schedule.php?session=$session&sport=".$spreg_abb[$i]."\">$spreg_long[$i] Schedule</a><br>";
	 }
      }
      if($header==1) echo "</td></tr>";
      //see if any tests are due soon:
      $sql="SELECT * FROM test_duedates";
      $result=mysql_query($sql);
      $header=0;
      while($row=mysql_fetch_array($result))
      {
         $cursport=$row[test];
         for($i=0;$i<count($spreg_abb);$i++)
         {
	    if($cursport==$spreg_abb[$i] && DueSoon($row[duedate],10) && !PastDue($row[duedate],2))
	    {
	       if($header==0)
	       {
	          echo "<tr align=left><th align=left class=smaller>";
	          echo "The following online tests are due soon:</th></tr>";
	          echo "<tr align=left><td>";
	       }
	       $duedate=split("-",$row[duedate]);
               $time=mktime(0,0,0,$duedate[1],$duedate[2],$duedate[0]);
	       $due_date=date("F d, Y",$time);
	       //get num of questions on this test
	       $testtable=$spreg_abb[$i]."test";
	       $sql2="SELECT id FROM $testtable";
	       $result2=mysql_query($sql2);
	       $questotal=mysql_num_rows($result2);
	       //see if they have submitted test yet
	       $testtable=$spreg_abb[$i]."test_results";
	       $sql2="SELECT * FROM $testtable WHERE offid='$offid'";
	       $result2=mysql_query($sql2);
	       $row2=mysql_fetch_array($result2);
	       if($row2[datetaken]=="")	//not submitted, get number answered
	       {
	          $answered=0;
	          for($j=1;$j<=$questotal;$j++)
	          {
		     $index="ques".$j;
		     if($row2[$index]=='t' || $row2[$index]=='f')
		        $answered++;
	          }
	          $note="You have answered $answered of $questotal questions and have NOT submitted this test.";
	          $color="red";
	       }
	       else	//submitted
	       {
	          $date=date("F d, Y",$row2[datetaken]);
	          $note="You completed and submitted this test on $date.";
	          $color="blue";
	       }
	       echo "$spreg_long[$i] Test (Due $due_date)<br><font style=\"color:$color\">$note</font><br>";
	       //echo "<a class=small href=\"".$cursport."test.php?session=$session\">$spreg_long[$i] Test</a> (Due $due_date)<br><font style=\"color:$color\">$note</font><br>";
	       $reminder=1;
	    }   
         }
      }
      if($header==1) echo "</td></tr>";
      if($reminder==0) echo "<tr align=center><td><br>[You currently have no reminders.]<br><br></td></tr>";
      echo "</table></td></tr>";

      //MESSAGES: 
      echo "<tr bgcolor=#E0E0E0 align=left>";
      echo "<th align=left>&nbsp;&nbsp;Messages:</th></tr>";
      echo "<tr align=center><td><br><table><tr align=left><td>";
      //get number of messages from the NSAA
      $sql="SELECT DISTINCT(title) FROM messages WHERE CURDATE()<=end_date AND (";
      for($i=0;$i<count($spreg_abb);$i++)
      {
         $sql.="sport='$spreg_abb[$i]' OR ";
      }
      $sql=substr($sql,0,strlen($sql)-4);
      $sql.=") ORDER BY id DESC";
      $result=mysql_query($sql);  
      $ct=mysql_num_rows($result);
      echo "<a class=small href=\"view_messages.php?session=$session\">You Have $ct";
      if($ct==1) echo " Message ";
      else echo " Messages ";
      echo "from the NSAA</a></td></tr>";
      echo "</table><br></td></tr>";

      //DOWNLOADS:
      if($subopen==3) $newsubopen='not1';
      else $newsubopen='3';
      echo "<tr bgcolor=#E0E0E0 align=left>";
      echo "<th align=left>&nbsp;&nbsp;<a class=black href=\"welcome2.php?session=$session&open=$open&subopen=$newsubopen#1\">";
      if($subopen==3) echo "&nabla;&nbsp;";
      else echo "&Delta;&nbsp;";
      echo "Downloads:</a></th></tr>";
      if($subopen==3)
      {
         echo "<tr align=center><td><table><tr align=left><td>";
         $sql="SELECT DISTINCT filename,doctitle FROM downloads WHERE (";
         for($i=0;$i<count($spreg_abb);$i++)
         {
            $sql.="recipients='$spreg_abb[$i]' OR ";
         }
         $sql=substr($sql,0,strlen($sql)-4);
         $sql.=" OR recipients='All') AND active='y' ORDER BY id DESC";
         $result=mysql_query($sql);
         while($row=mysql_fetch_array($result))
         {
            echo "<a class=small href=\"$row[filename]\" target=new>$row[doctitle]</a><br>";
         }
         echo "<br></td></tr></table></td></tr>";
      }
      echo "</table></td></tr>";
   }//end if open==1
   else
   {
      echO "<tr align=center><td><br>&nbsp;</td></tr>";
   }

   /******LINKS & CONTACTS******/
   if($open==2) $newopen='not1';
   else $newopen=2;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=2 href=\"welcome2.php?session=$session&open=$newopen#2\">";
   if($open==2) echo "&nabla&nbsp;";
   else echo "&Delta;&nbsp;";
   echo "Links & Contacts:&nbsp;</th></tr>";
   if($open==2)
   {
      echo "<tr align=center><td><table>";
      echo "<tr align=left>";
      echo "<td>E-mail <a class=small href=\"mailto:jdolliver@nsaahome.org\">Jon Dolliver</a></td></tr>";
      echo "</table></td></tr>";
      //Rosters:
      $shown=0;
      $sql="SELECT * FROM rosters WHERE active='x' ORDER BY sport";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
         $shown=1;
         echo "<form method=post action=\"roster.php\" target=new>";
         echo "<input type=hidden name=session value=\"$session\">";
         echo "<tr align=left><td><b>Officials' Rosters:</b><br><select name=sport onchange=\"submit();\">";
         while($row=mysql_fetch_array($result))
         {
            for($i=0;$i<count($spreg_abb);$i++)
            {
               if($row[sport]==$spreg_abb[$i])
               {
                  echo "<option value=\"$row[sport]\">".GetSportName($row[sport]);
                  echo "</option>";
               }
            }
         }
         echo "</select><input type=submit name=go value=\"Go\"></form>";
      }
      if($archiveroster==1)
      { 
         $sql="SELECT * FROM rosters WHERE showold='x' ORDER BY sport";
         $result=mysql_query($sql);
         if(mysql_num_rows($result)>0)
         {
            $shown=1;
            echo "<form method=post action=\"roster.php\" target=new>";
            echo "<input type=hidden name=session value=\"$session\">";
            echo "<b>$lastyearroster Rosters:&nbsp;</b>";
            echo "<input type=hidden name=archive value=\"$archivedbroster\">";
            echo "<select name=sport onchange=\"submit();\">";
            while($row=mysql_fetch_array($result))
            {
               for($i=0;$i<count($spreg_abb);$i++)
               {
                  if($row[sport]==$spreg_abb[$i])
                  {
                     echo "<option value=\"$row[sport]\">".GetSportName($row[sport]);
                     echo "</option>";
                  }
               }
            }
            echo "</select><input type=submit name=go value=\"Go\"></form>";
         }
      }
      if($shown!=1) echo "Please check back soon!";
      echo "</td></tr>";
      echo "<tr align=left><td>NFHS Member Login:&nbsp;&nbsp;";
      echo "<a class=small target=new href=\"https://www.nfhs.org/source/Security/Member-Logon.cfm\">https://www.nfhs.org/source/Security/Member-Logon.cfm</a></td></tr>";
      echo "</table></td></tr>";
   }//end if open=2
   else
   {
      echo "<tr align=center><td><br>&nbsp;</td></tr>";
   }

   /******CONTACT INFORMATION*****/
   if($open==3) $newopen='not1';
   else $newopen=3;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=3 href=\"welcome2.php?session=$session&open=$newopen#3\">";
   if($open==3) echo "&nabla&nbsp;";
   else echo "&Delta;&nbsp;";
   echo "Your Contact Information:&nbsp;</th></tr>";
   if($open==3)
   {
      $sql="SELECT address,city,state,zip,homeph,workph,cellph,email FROM officials WHERE id='$offid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      echo "<tr align=center><td><table>";
      if($message=="info")		//tell user their info was submitted
      {
         echo "<tr align=center><td colspan=2><font style=\"color:red\">Your contact info has been submitted.  Thank you!</font></td></tr>";
      }
      echo "<tr align=left valign=top><th align=left class=smaller>Address:</th>";
      echo "<td>$row[address]<br>$row[city], $row[state] $row[zip]</td></tr>";
      echo "<tr align=left valign=top><th align=left class=smaller>Phone:</th>";
      echo "<td>";
      echo "Home Phone: (".substr($row[homeph],0,3).")".substr($row[homeph],3,3)."-".substr($row[homeph],6,4)."<br>";
      echo "Work Phone: (".substr($row[workph],0,3).")".substr($row[workph],3,3)."-".substr($row[workph],6,4)."<br>";
      echo "Cell Phone: (".substr($row[cellph],0,3).")".substr($row[cellph],3,3)."-".substr($row[cellph],6,4);
      echo "</td></tr>";
      echo "<tr align=left><th class=smaller align=left>E-mail:</th>";
      echo "<td>$row[email]</td></tr>";
      echo "<tr align=center><td colspan=2><a href=\"editinfo.php?session=$session\" class=small>Edit Contact Information</a><br><br></td></tr>";
      echo "</table></td></tr>";
   }
   else
   {
      echo "<tr align=center><td><br>&nbsp;</td></tr>";
   }

   /***CONTRACTS***/
   if($open==4) $newopen='not1';
   else $newopen=4;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=4 href=\"welcome2.php?session=$session&open=$newopen#4\">";
   if($open==4) echo "&nabla&nbsp;";
   else echo "&Delta;&nbsp;";
   echo "Contracts:&nbsp;</th></tr>";
   if($open==4)
   {
      echo "<tr align=center><td><table>";
      $hascontracts=0;
      for($j=0;$j<count($spreg_abb);$j++)
      {
         $sport=$spreg_abb[$j];
         if($sport=='bb')
            $sport='bbb';
         $contracts=GetOffContracts($sport,$offid,$session);
         $sportname=GetSportName($sport);
         if(count($contracts[url])>0)
            echo "<tr align=left><td><b>$sportname</b></td></tr><tr align=left><td>";
         for($i=0;$i<count($contracts[url]);$i++)
         {
            $hascontracts=1;
            echo "<a class=small target=new href=\"".$contracts[url][$i]."\">".$contracts[linktitle][$i]."</a>";
            echo "&nbsp;&nbsp;";
            $accept=$contracts[accept][$i];
            $confirm=$contracts[confirm][$i];
            if($accept=='') echo "[You have not responded yet]";
            else if($accept=='n') 
            {
               echo "[Declined]&nbsp;";
               if($confirm=='y') echo "[NSAA-Acknowledged]";
               else echo "[NSAA-No Response Yet]";
            }
            else if($accept=='y') 
            {
               echo "[Accepted]&nbsp;";
               if($confirm=='y') echo "[NSAA-Confirmed]";
               else if($confirm=='n') echo "[NSAA-Rejected]";
               else echo "[NSAA-No Response Yet]";
            }
            echo "<br>";
         }
         if($sport=='bbb')
         {
            $sport='bbg';
            $contracts=GetOffContracts($sport,$offid,$session);
            $sportname=GetSportName($sport);
            if(count($contracts[url])>0)
               echo "<tr align=left><td><b>$sportname</b></td></tr><tr align=left><td>";
            for($i=0;$i<count($contracts[url]);$i++)
            {
	       $hascontract=1;
               echo "<a class=small target=new href=\"".$contracts[url][$i]."\">".$contracts[linktitle][$i]."</a>";
               echo "&nbsp;&nbsp;";
               $accept=$contracts[accept][$i];
               $confirm=$contracts[confirm][$i];
               if($accept=='') echo "[You have not responded yet]";
               else if($accept=='n')
               {
                  echo "[Declined]&nbsp;";
                  if($confirm=='y') echo "[NSAA-Acknowledged]";
                  else echo "[NSAA-No Response Yet]";
               }
               else if($accept=='y')
               {
                  echo "[Accepted]&nbsp;";
                  if($confirm=='y') echo "[NSAA-Confirmed]";
                  else if($confirm=='n') echo "[NSAA-Rejected]";
                  else echo "[NSAA-No Response Yet]";
               }
            }
         }
      }
      if($hascontracts==0)
         echo "<tr align=cented><td>(You currently have no contracts in any sport).<br><br></td></tr>";
      echo "</table></td></tr>";
   }//end if open==4
   else
   {
      echo "<tr align=center><td><br>&nbsp;</td></tr>";
   }

   /*****SCHEDULE ENTRY******/
   if($open==5) $newopen='not1';
   else $newopen=5;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=5 href=\"welcome2.php?session=$session&open=$newopen#5\">";
   if($open==5) echo "&nabla&nbsp;";
   else echo "&Delta;&nbsp;";
   echo "Schedule Entry:&nbsp;</th></tr>";
   if($open==5)
   {
      echo "<tr align=center><td align=center>";
      /*
      //GAME REPORT CARDS:
      $reportcardsp=array("bbg","bbb");
      echo "<table width=400>";
      for($i=0;$i<count($reportcardsp);$i++)
      {
         $cursp=$reportcardsp[$i];
         $offschedtbl="bbsched";
         $reporttbl="reportcard_".$cursp;
         $today=date("Y-m-d");
         $sql="SELECT t1.*,t2.scoreid AS gameid FROM $offschedtbl AS t1 LEFT JOIN $reporttbl AS t2 ON (t1.offid=t2.offid AND t1.scoreid=t2.scoreid) WHERE t1.offid='$offid' AND t1.offdate<='$today' AND (t2.datesub='' OR t2.scoreid IS NULL)";
         $result=mysql_query($sql);
         if($i==0)
         {
            echo "<tr align=left><td><b>Game Report Cards:</b><br>";
            echo "<a class=small href=\"reportcards.php?session=$session&finished=1\">See Game Report Cards you have submitted.</a><br></td></tr>";
         }
         echo "<tr align=left><td>You have ".mysql_num_rows($result)." <u>unfinished</u> ".GetSportName($cursp)." Game Report ";
         if(mysql_num_rows($result)==1) echo "Card";
         else echo "Cards";
         echo "</td></tr>";
      }
      echo "<tr align=left><td><a class=small href=\"reportcards.php?session=$session&finished=0\">Click Here to Complete Unfinished Report Cards</a></td></tr>";
      echo "</table>";
      */
      echo "<form method=post action=\"schedule.php\">";
      echo "<input type=hidden name=session value=\"$session\">";
      echo "<br><b>Enter your Schedules:</b> <select name=schedsport>";
      for($i=0;$i<count($spreg_abb);$i++)
      {
         echo "<option value='$spreg_abb[$i]'>$spreg_long[$i]</option>";
      }
      echo "</select>";
      echo "&nbsp;<input type=submit name=go value=\"Go\"></form><br>";
      echo "</td></tr>";
   }//end if open==5
   else
   {
      echo "<tr align=center><td><br>&nbsp;</td></tr>";
   }

   /******ONLINE TESTS******/
   if($open==6) $newopen='not1';
   else $newopen=6;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=6 href=\"welcome2.php?session=$session&open=$newopen#6\">";
   if($open==6) echo "&nabla&nbsp;";
   else echo "&Delta;&nbsp;";
   echo "Online Tests:&nbsp;</th></tr>";
   if($open==6)
   {
      echo "<tr align=center><td>";
      echo "<form method=post action=\"onlinetest.php\">";
      echo "<input type=hidden name=session value=\"$session\">";
      echo "<b>Take Test:&nbsp;&nbsp;</b><select name=testsport>";
      $viewtest=array();
      $vx=0;	//array to show tests taken
      for($i=0;$i<count($spreg_abb);$i++)
      {
         //check if official has already taken this test
         $testtable=$spreg_abb[$i]."test_results";
         $sql="SELECT datetaken FROM $testtable WHERE offid='$offid'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         //also check if past due date for this test
         $sql2="SELECT duedate FROM test_duedates WHERE test='$spreg_abb[$i]'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $date=split("-",$row2[0]);
         $duedate=mktime(0,0,0,$date[1],$date[2],$date[0]);
         $duedate+=24*60*60;	//until midnight on the due date
         $now=time();
         if($row[0]=="" && $duedate>$now)
            echo "<option value='$spreg_abb[$i]'>$spreg_long[$i]</option>";
         $days4=3*24*60*60;
         if($spreg_abb[$i]=='so' || $spreg_abb[$i]=='ba' || $spreg_abb[$i]=='tr')
            $days4=7*60*60;	//7am the next day for soccer and baseball
         if($row[0]!="" && $now>=($duedate+$days4))	//if taken and 3 days past due date
         {
	    $viewtest[$vx]=$spreg_abb[$i];
	    $vx++;
         }
      }
      echo "</select>";
      echo "&nbsp;<input type=submit name=go value=\"Go\"><br>(NOTE: Only tests that are not past due and that you have not already taken will show in the dropdown list above.)</form>";
      echo "<table><tr align=left><td>";
      if(count($viewtest)>0)
         echo "<b>View Test Results:</b><br>";
      for($i=0;$i<count($viewtest);$i++)
      {
         for($j=0;$j<count($spreg_abb);$j++)
         {
	    if($viewtest[$i]==$spreg_abb[$j])
	       $sportname=$spreg_long[$j];
         }
         echo "<a href=\"viewtest.php?session=$session&sport=$viewtest[$i]\" class=small>$sportname</a><br>";
      }
      echo "</td></tr></table>";
      echo "<table><tr align=left><td colspan=2><b>Your next supervised test ";
      if(count($spreg_abb)==1) echo " date is...";
      else if(count($spreg_abb)>0) echo " dates are...";
      echo "</td></tr>";
      for($i=0;$i<count($spreg_abb);$i++)
      {
         //tell official when sup test date is for each sport they're registered for
         $table=$spreg_abb[$i]."off";
         $sql="SELECT suptestdate FROM $table WHERE offid='$offid'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         $supdate=$row[0];
         echo "<tr align=left><td><b>$spreg_long[$i]:</b></td>";
         echo "<td>$supdate</td></tr>";
      }
      echo "</table><br>";
      echo "</td></tr>";
   }//end if open==6
   else
   {
      echo "<tr align=center><td><br>&nbsp;</td></tr>";
   }

   /******APPLICATIONS TO OFFICIATE******/
   if($open==7) $newopen='not1';
   else $newopen=7;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=7 href=\"welcome2.php?session=$session&open=$newopen#7\">";
   if($open==7) echo "&nabla&nbsp;";
   else echo "&Delta;&nbsp;";
   echo "Applications to Officiate:&nbsp;</th></tr>";
   if($open==7)
   {
      echo "<tr align=center><td align=center>";
      echo "<form method=post action=\"apptooff.php\">";
      echo "<input type=hidden name=session value=\"$session\">";
      echo "<select name=appsport>";
      $football=0; $swim=0;
      for($i=0;$i<count($spreg_abb);$i++)
      {
         if(!($swim==1 && ($spreg_abb[$i]=='sw' || $spreg_abb[$i]=='di')))	//don't show swim/dive twice
	    echo "<option value='$spreg_abb[$i]'>$spreg_long[$i]</option>";
         if($spreg_abb[$i]=='sw' || $spreg_abb[$i]=='di')
	    $swim=1;
         if($spreg_abb[$i]=='fb')
	    $football=1;
      }
      echo "</select>";
      echo "&nbsp;<input type=submit name=go value=\"Go\"></form>";
      //if this official is a fb official, put note that only crew chief needs to fill this out
      if($football==1)
         echo "<font style=\"color:red\"><b>Football Officials: Only the Crew Chief needs to complete this application.</b></font>";
      echo "</td></tr>";
   }//end if open==7
   else
   {
      echo "<tr align=center><td><br>&nbsp;</td></tr>";
   }

   /****EJECTION REPORTS****/
   if($open==8) $newopen='not1';
   else $newopen=8;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=8 href=\"welcome2.php?session=$session&open=$newopen#8\">";
   if($open==8) echo "&nabla&nbsp;";
   else echo "&Delta;&nbsp;";
   echo "Ejection Reports:&nbsp;</th></tr>";
   if($open==8)
   {
      echo "<tr align=center><td align=center><br>";
      echo "<a class=small href=\"ejection.php?session=$session\">Submit an Ejection Report</a><br><br>";
      $sql="SELECT * FROM ejections WHERE offid='$offid' ORDER BY sport,datesub";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
         echo "<table cellspacing=0 cellpadding=1><tr align=left><td><b>Your Ejection Reports:</b></td></tr>";
         while($row=mysql_fetch_array($result))
         {
	    $datesub=date("m/d/y",$row[datesub]);
	    echo "<tr align=left><td><a class=small href=\"view_ejection.php?session=$session&id=$row[id]\">".GetSportName($row[sport])." (Submitted $datesub)</a>";
	    if($row[verify]=='x')
	       echo "&nbsp;&nbsp;(The NSAA has received this report)";
	    echo "</td></tr>";
         }
         echo "</table><br>";
      }
      echo "</td></tr>";
   }//end if open==8
   else
   {
      echo "<tr align=center><td><br>&nbsp;</td></tr>";
   }

   /****OBSERVATIONS****/
   if($open==9) $newopen='not1';
   else $newopen=9;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=9 href=\"welcome2.php?session=$session&open=$newopen#9\">";
   if($open==9) echo "&nabla&nbsp;";
   else echo "&Delta;&nbsp;";
   echo "Observations:&nbsp;</th></tr>";
   if($open==9)
   {
      echo "<tr align=center><td align=center>";
      echo "<br>Any observations submitted THIS YEAR about you and your crew (if applicable) appear below.<br>";
      echo "<a class=small href=\"viewobs.php?session=$session\">View Observations from Previous Years</a><br>";
      $fb=0;
      for($i=0;$i<count($spreg_abb);$i++)
      {
         if($spreg_abb[$i]=="fb") $fb=1;
      }
      if($fb==1)
         echo "Football Officials: Please share these observations with your crew members, as they will not receive a copy.<br>";
      echo "<br><table>";
      for($i=0;$i<count($spreg_abb);$i++)
      {
         $table=$spreg_abb[$i]."observe";
         $sql="SELECT * FROM $table WHERE offid='$offid' AND dateeval!='' ORDER BY dateeval";
         if($spreg_abb[$i]=='bb')
            $sql="SELECT * FROM $table WHERE (offid='$offid' OR offid2='$offid' OR offid3='$offid') AND dateeval!='' ORDER BY dateeval";
         $result=mysql_query($sql);
         if(mysql_num_rows($result)>0)
	    echo "<tr align=left><td><b>$spreg_long[$i]:</b></td></tr>";
         while($row=mysql_fetch_array($result))
         {
	    $obsid=$row[obsid];
	    $obsname=GetObsName($obsid);
	    $dateeval=date("F d, Y",$row[dateeval]);
	    echo "<tr align=left><td>";
	    if($sport=='bb' && $row[postseasongame]=='1')
               echo "<a href='#' class=small onclick=\"window.open('$table.php?session=$session&sport=$spreg_abb[$i]&gameid=$row[gameid]&postseasongame=1&offid=$offid&obsid=$row[obsid]','$table','menubar=no,titlebar=no,resizable=yes,width=800,height=600,scrollbars=yes');\">$row[home] vs. $row[visitor] (Evaluated $dateeval by $obsname)</a>";
            else
            {
               echo "<a href='#' class=small onclick=\"window.open('$table.php?session=$session&sport=$spreg_abb[$i]&gameid=$row[gameid]&offid=$offid&obsid=$row[obsid]','$table','menubar=no,titlebar=no,resizable=yes,width=800,height=600,scrollbars=yes');\">";
   	       if($sport=='wr') echo "$row[event]";
	       else echo "$row[home] vs. $row[visitor]";
	       echo " (Evaluated $dateeval by $obsname)</a>";
	    }
	    echo "</td></tr>";
         }
      }
      echo "</table></td></tr>";
   }//end if open==9
   else
   {
      echo "<tr align=center><td><br>&nbsp;</td></tr>";
   }
}//end if level=2
else if($level==3)	//Observer login
{
?>
   <br>
   <table width=90% cellspacing=0 cellpadding=0>
   <caption><b>Welcome, <?php echo "$name"; ?>!<br>
   Today's Date is: <?php echo $date; ?></b><br>
<?php
   $obsid=GetObsID($session);

   //get sport(s) this observer is listed for
   $sql="SELECT * FROM observers WHERE id='$obsid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<br><form method=post action=\"welcome.php\">";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<select name=obssport onchange=\"submit();\">";
   $ct=0;
   for($i=0;$i<count($activity);$i++)
   {
      if($row[$activity[$i]]=='x')
      {
	 $sportname=$act_long[$i];
         if($sportname=="Swimming") $sportname.="/Diving";
         if($ct==0 && !$obssport) { $ct++; $obssport=$activity[$i]; $cursport=$sportname; }
	 echo "<option value=\"$activity[$i]\"";
         if($obssport==$activity[$i]) { echo " selected"; $cursport=$sportname; }
         echo ">$sportname</option>";
      }
   }
   echo "</select>&nbsp;<input type=submit name=go value=\"Go\"></form>";
   echo "<font style=\"color:red;font-size:8pt;\"><b>You have selected ".strtoupper($cursport).".<br></b><i>(If you are listed under more than one sport, you may work on a different sport by selecting it from the dropdown menu above.)</i></font><br><br></caption>";
   echo "<tr align=center><td>";
   echo "<a class=small href=\"downloads/BUSEXP.doc\">Expense Report (Word Doc)</a>&nbsp;&nbsp;";
   echo "<a class=small href=\"downloads/BUSEXP.pdf\">Expense Report (PDF)</a><br><br>";
   echo "<a class=small target=new href=\"".$obssport."observe.php?session=$session&print=1\">Printable Version of the $cursport Evaluation Form</a><br><br></td></tr>";
   $sql="SELECT * FROM rosters WHERE sport='$obssport' AND active='x'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=center><td>";
      echo "<a class=small target=new href=\"roster.php?session=$session&print=1&sport=$obssport\">Printable Version of $cursport Officials Roster</a><br><br></td></tr>";
   }
   if($obssport=='so')
      echo "<tr align=center><td><a class=small href=\"soassignreport.php?session=$session\">Confirmed Soccer Officials Assignments for District and State Tournaments</a><br><br></td></tr>";
   /*
   else if($obssport=='fb')
      echo "<tr align=center><td><a class=small target=new href=\"fbobs_contact.html\">Football Observers Names & Addresses</a><br><br></td></tr>";
   */
   else if ($obssport=='bb')
   {
      echo "<tr align=center><td><a class=small href=\"bbassignreport2.php?session=$session\">Basketball Officials Assignments for District, Subdistrict, District Final and State Tournaments</a><br><br></td></tr>";
   }
   echo "<tr align=left bgcolor=#E0E0E0><th align=left>&nbsp;&nbsp;Fill Out a New <font style=\"color:red\">$cursport</font> Evaluation:</th></tr>";
   echo "<tr align=center><td>";
   echo "<form method=post action=\"obs_search.php\">";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<input type=hidden name=sport value=$obssport>";
   echo "<table>";
   echo "<tr align=left><th align=left class=smaller colspan=2>Search for a <font style=\"color:red\">$cursport</font> Official:</th></tr>";
   echo "<tr align=left><td><b>Last Name:</b> (starts with)</td>";
   echo "<td><input type=text class=tiny size=20 name=last></td></tr>";
   echo "<tr align=left><td><b>First Name:</b> (starts with)</td>";
   echo "<td><input type=text class=tiny size=20 name=first></td></tr>";
   echo "<tr align=left><td><b>City:</b> (where official resides)</td>";
   echo "<td><select class=small name=city><option>~</option>";
   $sql="SELECT DISTINCT city FROM officials WHERE city!='' ORDER BY city";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option>$row[city]</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=center><td colspan=2><input type=submit name=search value=\"Search\"></td></tr>";
   echo "</table></form>";
   echo "</td></tr>";
   echo "<tr align=left bgcolor=#E0E0E0><th align=left>&nbsp;&nbsp;Your <font style=\"color:red\">$cursport</font> Evaluations:</th></tr>";
   echo "<tr align=center><td><br><table>";
   $none=1;	//assume NO evaluations to show
   $obstable=$obssport."observe";
   $schtable=$obssport."sched";
   //first show observations still working on:
   $sql="SELECT * FROM $obstable WHERE obsid='$obsid' AND dateeval='' ORDER BY id";
   $result=mysql_query($sql); 
   if(mysql_num_rows($result)>0)
      echo "<tr align=left><td><b>Evaluations you've SAVED but NOT SUBMITTED: </b><br>(You may view AND edit these evaluations.  These evaluations have NOT been submitted to the NSAA yet.)<br></td></tr>";
   while($row=mysql_fetch_array($result))
   {
      $sql2="SELECT first, last FROM officials WHERE id='$row[offid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $offname="$row2[first] $row2[last]";
      echo "<tr align=left><td>";
      if($obssport=='bb' && $row[postseasongame]==1)
      {
         echo "<a class=small target=new href=\"".$obssport."observe.php?session=$session&sport=$obssport&postseasongame=1&gameid=$row[gameid]&offid=$row[offid]\">$offname&nbsp;&nbsp;&nbsp;$row[home] vs $row[visitor] (Post Season)</a>";
      }
      else
      {
         echo "<a class=small target=new href=\"".$obssport."observe.php?session=$session&sport=$obssport&gameid=$row[gameid]&offid=$row[offid]\">$offname&nbsp;&nbsp;&nbsp;$row[home] vs. $row[visitor]</a>";
      }
      echo "</td></tr>";
      $none=0;	
   }
   //then show submitted evaluations
   $sql="SELECT * FROM $obstable WHERE obsid='$obsid' AND dateeval!='' ORDER BY dateeval DESC";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
      echo "<tr align=left><td><br><b>Evaluations you've SUBMITTED:</b><br>(You may only view these evaluations.  These evaluations have been submitted to the NSAA.)</td></tr>";
   while($row=mysql_fetch_array($result))
   {
      $sql2="SELECT first, last FROM officials WHERE id='$row[offid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $offname="$row2[first] $row2[last]";
      echo "<tr align=left><td>";
      if($obssport=='bb' && $row[postseasongame]==1)
      {
          echo "<a class=small target=new href=\"".$obssport."observe.php?session=$session&sport=$obssport&gameid=$row[gameid]&postseasongame=1&offid=$row[offid]\">$offname&nbsp;&nbsp;&nbsp;$row[home] vs. $row[visitor] (Post Season)&nbsp;&nbsp;&nbsp;(Evaluated ".date("m/d/y",$row[dateeval]).")</a>";
      }
      else
      {
         echo "<a class=small target=new href=\"".$obssport."observe.php?session=$session&sport=$obssport&gameid=$row[gameid]&offid=$row[offid]\">$offname&nbsp;&nbsp;&nbsp;$row[home] vs. $row[visitor]&nbsp;&nbsp;&nbsp;(Evaluated ".date("m/d/y",$row[dateeval]).")</a>";
      }
      echo "</td></tr>";
      $none=0;
   }
   if($none==1) echo "<tr align=center><td><br>[You have not filled out any evaluations yet.]</td></tr>";
   echo "</table></td></tr>";
   echo "</table>";
}
?>
</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
