<?php
//welcome.php: displays welcome page for specified user

//connect to db
//error_reporting(0);

require 'functions.php';
require 'variables.php';

$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php';
//require '/data/public_html/calculate/functions.php'; //Wildcard Functions

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

if($level==1) 
{
   CleanSessions();
   if($savemessage)
   {
      $sql="SELECT * FROM welcomemessage";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
         $sql="UPDATE welcomemessage SET message='".addslashes($message)."'";
      else
         $sql="INSERT INTO welcomemessage (message) VALUES ('".addslashes($message)."')";
      $result=mysql_query($sql);
      echo mysql_error();
   }
}

echo $init_html;
  if($level!=1 && CHANGEPASS==1)
	{
	   $sql="SELECT t2.changepass FROM sessions as t1, logins as t2 WHERE t1.login_id=t2.id AND t1.session_id='$session'";
	  $result=mysql_query($sql);
	  $row=mysql_fetch_array($result);

	  if ($row[changepass]<strtotime ('2018-1-1'))
	  {
			header("Location:/nsaaforms/officials/changepassword.php?session=$session");
			exit();
	  }
	} 
?>
<script type="text/javascript">
tinyMCE.init({
        mode : 'textareas',
        theme : 'advanced',
        skin : 'o2k7',
        skin_variant : 'black',
        convert_urls : false,
        relative_urls : false,
        plugins : 'safari,iespell,preview,media,searchreplace,paste,',
        theme_advanced_buttons1 : 'bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,pastetext,pasteword,|,undo,redo,|,link,unlink,image,media,|,code,preview',
        theme_advanced_buttons2 : '',
        theme_advanced_toolbar_location : 'top',
        theme_advanced_toolbar_align : 'left',
        theme_advanced_statusbar_location : 'bottom',
        theme_advanced_resizing : true,
        // Example content CSS (should be your site CSS)
        content_css : '../css/plain.css',
        // Drop lists for link/image/media/template dialogs
        template_external_list_url : 'lists/template_list.js',
        external_link_list_url : 'lists/link_list.js',
        external_image_list_url : 'lists/image_list.js',
        media_external_list_url : 'lists/media_list.js'
        });
        </script>
<?php
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
   echo "<br><table width=\"800px\" cellspacing=0 cellpadding=0>";
   echo "<caption><b>Welcome, $name!<br>";
   echo "Today's Date is: $date</b><br><br></caption>";
   echo "<tr align=left><td><font style=\"font-size:9pt;color:blue\"><b>NOTE: Click a tab above to go to that section.</b></td></tr>";
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
    	 //echo "<tr align=center><td><a href=\"archive.php?session=$session\" onClick=\"return confirm('Are you sure you want to archive the $year0-$year Officials & Judges Database?\r\n\r\nThis will copy the current database to an archived database and clean out the appropriate tables and settings in the current database.\r\n\r\nThis action cannot be undone or redone.');\">Click Here to ARCHIVE the $year0-$year Officials & Judges Database</a><br><br></td></tr>";
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
   echo "<tr align=center><td><form method=post action=\"welcome.php\">
	<input type=hidden name=\"session\" value=\"$session\">";
   echo "<br><hr><h3>Edit the text shown on officials' login screens:</h3>";
   if($savemessage) echo "<div class='alert'>Your changes have been saved.</div><br>";
   $sql="SELECT * FROM welcomemessage";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<textarea name=\"message\" style=\"width:600px;height:300px;\">$row[message]</textarea>";
   echo "<br><br><input type=submit name=\"savemessage\" value=\"Save Message\" class=\"fancybutton\">";
   echo "</form></td></tr>";
}
else if($level==2)	//Official-Access
{
   $offid=GetOffID($session);

   //get sports this official is registered for
   $spreg_abb=array();
   $spreg_long=array();
   $ix=0; $fboff=0;
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
	 if($activity[$i]=='fb') $fboff=1;
      }
   }

   if($ix==0)	//HAS NOT PAID FOR ANY SPORT--show special screen with link to CC app
   {
      echo "<br><table width=600><tr align=left><td>";
      echo "<b>Welcome, $name!</b><br><br>";
      echo "You have an account in our system, but you have not yet paid to register as an official for a specific sport(s) for this year.<br><br>";
      $sql="SELECT state FROM officials WHERE id='$offid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if($row[0]=="NE") $application="application";
      else $application="affapplication";
      echo "Please complete your application and pay your registration fee online using the <a href=\"https://secure.nsaahome.org/nsaaforms/officials/$application.php?session=$session\" class=small>Online Official's Application Form</a>, which will be available <u><b>June 1</b></u>.<br><br>";
      echo "Thank You!</td></tr></table>";
      echo $end_html;
      exit();
   }

   echo "<br><table width=\"800px\" cellspacing=0 cellpadding=0>";
   echo "<caption><h3>Welcome, $name!</h3>";
   echo "Today's Date is: $date</b><br><br>";
   /***** EVEN YEARS: THIS IS WHERE THE LINKS TO THE FOOTBALL SCHEDULES WILL BE ON RELEASE DATE: *****/
	
   if($fboff==1)
   {
      $temp=explode(";",GetFBYears());
      $year1=$temp[0]; $year2=$temp[1];
      $DATESrelease=GetFBDate("showschedules_date");       //SHOW SCHEDULES (9am)
      $DATESshowdate=GetFBDate("gamedates_startdate");     //CAN START ENTERING DATES
      $DATESduedate=GetFBDate("gamedates_duedate");        //DUE DATE FOR ENTERING DATES
      $temp=explode("-",$DATESrelease);
      $DATESreleaseSEC=mktime(9,0,0,$temp[1],$temp[2],$temp[0]);
      if(PastDue($DATESrelease,-2) && !PastDue($DATESduedate,-1) && time()>=1518501629)  //ADDED THIS LINE TO KEEP IT OPEN UNTIL "ENTER DATES" Due Date
      {
         echo "<div class='alert' style='font-size:13px;width:800px;'>";
         echo "<b>PLEASE READ THE FOLLOWING CAREFULLY. These are the most accurate instructions for accessing the $year1-$year2 Football Schedules:</b><br><br>";
         echo "The following link will allow you to <u><b>DOWNLOAD THE $year1-$year2 FOOTBALL SCHEDULES TO YOUR COMPUTER</b></u> starting at <u><b>9:00AM CST on ".date("l, F j, Y",$DATESreleaseSEC)."</b></u>.<br><br>Clicking these links BEFORE 9:00AM CST will result in a message simply stating that the schedules have not yet been released.<br><br><b>YOU <u>DO NOT NEED TO RELOAD</u> THIS SCREEN AT 9:00AM IN ORDER TO DOWNLOAD THE CORRECT SCHEDULES.</b> Doing so may cause your browser to \"cache\" the current files on this site and <b>prevent you from being able to download the actual schedules</b> at 9:00am CST.<br><br>At 9:00AM CST, click the following link to <b>DOWNLOAD THE SCHEDULES AND VIEW THEM ON YOUR COMPUTER:</b><br><br>";
		 
		  if(time()>=1518534120)
		   {	   
			$urlsss1="reports.php?session=$session&filename=".$year1."fbschedules.txt";
			
		   }
		   else {
			   $urlsss1="#";
			
			   
		   }
         echo "<a href=\"$urlsss1\" target=\"_blank\">All $year1-$year2 FB Schedules (to be released ".date("F j, Y",$DATESreleaseSEC).", at 9:00am CST)</a>";
         echo "</div>";
         echo "<br><br>";
      }
   }
   /***** END FB SCHEDULE LINKS *****/
   echo "</caption>";

   /****** SPECIAL MESSAGE FROM THE NSAA (ADDED 1/11/16) ******/
   $sql="SELECT * FROM welcomemessage";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[message]!='')
   {
      echo "<tr align=center><td><div class='normalwhite' style=\"width:500px;margin:auto auto 20px auto;\">$row[message]</div></td></tr>";
   }
   else echo "!";
   
  
   /******INBOX: REMINDERS, MESSAGES & DOWNLOADS******/
   echo "<tr align=left><td><font style=\"font-size:9pt;color:blue\"><b>NOTE: Click on headings to open/close that section.</b></font></td></tr>";
   if(!$open1 && !$open2 && !$open3 && !$open4 && !$open5 && !$open6 && !$open7 && !$open8 && !$open9) 
      $open1="1";
   if($open1==1) $newopen='not1';
   else $newopen=1;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=1 href=\"welcome.php?s
ession=$session&open1=$newopen&open2=$open2&open3=$open3&open4=$open4&open5=$open5&open6=$open6&open7=$open7&open8=$open8&open9=$open9#1\">";
   if($open1==1) echo "[ - ]&nbsp;";
   else echo "[ + ]&nbsp;";
   echo "INBOX: Reminders, Messages & Downloads:&nbsp;</th></tr>";
   if($open1==1)
   {
      echo "<tr align=center><td><table>";

      //REMINDERS:
      echo "<tr bgcolor=#E0E0E0 align=left>";
      echo "<th align=left>&nbsp;&nbsp;Reminders:</th></tr>";
      echO "<tr align=center><td><table width='550px'><!--REMINDERS-->";
      $reminder=0;
    	//APPS TO OFFICIATE
      $appstooff=GetAppsToOffReminders($session);
      if($appstooff) 
      {
	 echo "<tr align=left><td>".$appstooff."</td></tr>";
	 $reminder=1;
      }
	//SCHEDULES
      $scheds=GetSchedReminders($session); 
      if($scheds) 
      {
	 echo "<tr align=left><td>".$scheds."</td></tr>";
	 $reminder=1;
      }
	//CONTRACTS
      $contracts=GetContractReminders($session);
      if($contracts) 
      {
	 echo "<tr align=left><td>".$contracts."</td></tr>";
	 $reminder=1;
      }
 	//ONLINE TESTS
      $tests=GetTestReminders($session);
      if($tests) 
      {
	 echo "<tr align=left><td>".$tests."</td></tr>";
	 $reminder=1;
      }
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
      echo "<th align=left>&nbsp;&nbsp;<a class=black href=\"welcome.php?session=$session&open1=$open1&subopen=$newsubopen&open2=$open2&open3=$open3&open4=$open4&open5=$open5&open6=$open6&open7=$open7&open8=$open8&open9=$open9#1\">";
      if($subopen==3) echo "[ - ]&nbsp;";
      else echo "[ + ]&nbsp;";
      //get number of downloads
      $sql="SELECT DISTINCT filename,doctitle FROM downloads WHERE (";
      for($i=0;$i<count($spreg_abb);$i++)
      {
         $sql.="recipients='$spreg_abb[$i]' OR ";
      }
      $sql=substr($sql,0,strlen($sql)-4);
      $sql.=" OR recipients='All') AND active='y' ORDER BY id DESC";
      $result=mysql_query($sql);
      echo "Downloads (".mysql_num_rows($result)."):</a></th></tr>";
      if($subopen==3)
      {
         echo "<tr align=center><td><table><tr align=left><td><ul>";
         while($row=mysql_fetch_array($result))
         {
            $row[filename]=preg_replace("/(www.)/","",$row[filename]);
            echo "<li><a class=small href=\"$row[filename]\" target=new>$row[doctitle]</a></li>";
         }
         echo "</ul></td></tr></table></td></tr>";
      }
      echo "</table></td></tr>";
   }//end if open1==1
   else
   {
      echO "<tr align=center><td><br>&nbsp;</td></tr>";
   }

   /******LINKS & CONTACTS******/
   if($open2==2) $newopen='not1';
   else $newopen=2;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=2 href=\"welcome.php?session=$session&open1=$open1&open2=$newopen&open3=$open3&open4=$open4&open5=$open5&open6=$open6&open7=$open7&open8=$open8&open9=$open9#2\">";
   if($open2==2) echo "[ - ]&nbsp;";
   else echo "[ + ]&nbsp;";
   echo "Links & Contacts:&nbsp;</th></tr>";
   if($open2==2)
   {
      echo "<tr align=center><td><ul style='width:450px;text-align:left;'>";
      echo "<li>E-mail <a href=\"mailto:jdolliver@nsaahome.org\">Jon Dolliver</a></li>";
      echo "<li><a href=\"adcontactinfo.php?session=$session\">NSAA Member Schools' ATHLETIC & ACTIVITIES DIRECTORS Contact Information</a></li>";
      //Rules PowerPoints:
      for($i=0;$i<count($spreg_abb);$i++)
      {
         $sportname=GetSportName($spreg_abb[$i]);
         if($sportname!='')
	 {
	    //MAKE SURE IT'S OK FOR THEM TO SEE THIS: must be after the last day rules meetings are shown for credit
	    $sql="SELECT * FROM rulesmeetingdates WHERE sport='".$spreg_abb[$i]."'";
	    $result=mysql_query($sql);
	    $row=mysql_fetch_array($result);
	    if((mysql_num_rows($result)>0 && $row[startdate]!='0000-00-00' && $row[enddate]!='0000-00-00' && PastDue($row[enddate],0)) || $offid=='3427')
	    {
               echo "<li><a target=new href=\"$row[ppfile]\">$sportname Rules Meeting PowerPoint</a><br>(You must have Microsoft PowerPoint on your computer to view this document.)</li>";
	    }
	 }
      }
      //Rosters:
      $shown=0; $fboff=0;
      $sql="SELECT * FROM rosters WHERE active='x' AND (";
      for($i=0;$i<count($spreg_abb);$i++)
      {
	 $sql.="sport='".$spreg_abb[$i]."' OR ";
	 if($spreg_abb[$i]=='fb') $fboff=1;
      }
      $sql=substr($sql,0,strlen($sql)-4).") ORDER BY sport";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
         $shown=1;
         echo "<li><form method=post action=\"roster.php\" target=new>";
         echo "<input type=hidden name=session value=\"$session\">";
         echo "<b>Officials' Rosters:</b><br><select name=sport onchange=\"submit();\">";
         while($row=mysql_fetch_array($result))
         {
            for($i=0;$i<count($spreg_abb);$i++)
            {
               if($row[sport]==$spreg_abb[$i])
                  echo "<option value=\"$row[sport]\">".GetSportName($row[sport])."</option>";
            }
         }
         echo "</select><input type=submit name=go value=\"Go\">&nbsp;";
	 if($fboff==1) echo "<a class=small target=new href=\"fbcrewexport.php?session=$session\">Football Crew Information Export</a>";
	 echo "</form></li>";
      }
      if($archiveroster==1)
      { 
         $sql="SELECT * FROM rosters WHERE showold='x' AND (";
         for($i=0;$i<count($spreg_abb);$i++)
            $sql.="sport='".$spreg_abb[$i]."' OR ";
         $sql=substr($sql,0,strlen($sql)-4).") ORDER BY sport";
         $result=mysql_query($sql);
         if(mysql_num_rows($result)>0)
         {
            $shown=1;
            echo "<li><form method=post action=\"roster.php\" target=new>";
            echo "<input type=hidden name=session value=\"$session\">";
            echo "<b>$lastyearroster Rosters:&nbsp;</b>";
            echo "<input type=hidden name=archive value=\"$archivedbroster\">";
            echo "<select name=sport onchange=\"submit();\">";
            while($row=mysql_fetch_array($result))
            {
               for($i=0;$i<count($spreg_abb);$i++)
               {
                  if($row[sport]==$spreg_abb[$i])
                     echo "<option value=\"$row[sport]\">".GetSportName($row[sport])."</option>";
               }
            }
            echo "</select><input type=submit name=go value=\"Go\"></form></li>";
         }
      }
      echo "<li>NFHS Member Login:&nbsp;&nbsp;";
      echo "<a target=new href=\"http://nfhs.org/vango/core/login.aspx\">http://nfhs.org/vango/core/login.aspx</a></li>";
      echo "</ul></td></tr>";
   }//end if open2=2
   else
   {
      echo "<tr align=center><td><br>&nbsp;</td></tr>";
   }

   /******ACCOUNT INFORMATION*****/
   if($open3==3) $newopen='not1';
   else $newopen=3;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=3 href=\"welcome.php?session=$session&open1=$open1&open2=$open2&open3=$newopen&open4=$open4&open5=$open5&open6=$open6&open7=$open7&open8=$open8&open9=$open9#3\">";
   if($open3==3) echo "[ - ]&nbsp;";
   else echo "[ + ]&nbsp;";
   echo "Your Account Information:&nbsp;</th></tr>";
   if($open3==3)
   {
      $sql="SELECT address,city,state,zip,homeph,workph,cellph,email,photofile,photoapproved FROM officials WHERE id='$offid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      echo "<tr align=center><td><table><tr align=left valign=top>";
      echo "<td width=\"130px\"><b>Profile Picture:</b><br>";
      //PROFILE PICTURE
      if($row[photofile]!='' && citgf_file_exists("photos/$row[photofile]"))	//photo exists
      {
	 if($row[photoapproved]!='x')	//photo not approved yet
	 {
	    echo "<div class=normal style=\"width:100px;height:100px;\"><br>Your photo has not been approved by the NSAA yet.<br><br>Please check back later.</div>";
	 }	
	 else	//photo approved; display it
	 {
	    echo "<img border=0 src=\"photos/$row[photofile]\" width=\"100px\">";
	 }
      }
      else	//no photo
      {
	 echo "<div class=normal style=\"width:100px;height:100px;\"><br>You have not uploaded a profile picture yet.<br><br><a class=small href=\"editinfo.php?session=$session\">Upload Your Profile Picture</a></div>";
      }
      echo "</td>";
      echo "<td><table>";
      if($message=="info")		//tell user their info was submitted
      {
         echo "<tr align=center><td colspan=2><font style=\"color:red\">Your contact info has been submitted.  Thank you!</font></td></tr>";
      }
      //CONTACT INFORMATION
      echo "<tr align=left valign=top><th align=left class=smaller><br>Address:</th>";
      echo "<td><br>$row[address]<br>$row[city], $row[state] $row[zip]</td></tr>";
      echo "<tr align=left valign=top><th align=left class=smaller>Phone:</th>";
      echo "<td>";
      echo "Home Phone: (".substr($row[homeph],0,3).")".substr($row[homeph],3,3)."-".substr($row[homeph],6,4)."<br>";
      echo "Work Phone: (".substr($row[workph],0,3).")".substr($row[workph],3,3)."-".substr($row[workph],6,4)."<br>";
      echo "Cell Phone: (".substr($row[cellph],0,3).")".substr($row[cellph],3,3)."-".substr($row[cellph],6,4);
      echo "</td></tr>";
      echo "<tr align=left><th class=smaller align=left>E-mail:</th>";
      echo "<td>$row[email]</td></tr>";
      echo "<tr align=center><td colspan=2><br><a href=\"editinfo.php?session=$session\" class=small>Edit Contact Information</a><br><br></td></tr>";
      echo "</table></td></tr>";
      //HISTORY FOR EACH SPORT:
	 echo "<tr align='center'><td colspan=2><div style='width:400px;'><p><b>Your Officiating History:</b></p><ul>";
	 for($i=0;$i<count($activity);$i++)
	 {
   	    $table2=$activity[$i]."off_hist";
   	    $sql2="SELECT * FROM $table2 WHERE offid='$offid' ORDER BY regyr DESC LIMIT 1";
   	    $result2=mysql_query($sql2);
            if($row2=mysql_fetch_array($result2))
	    {
	       echo "<li><a href='#' onClick=\"window.open('view_sport.php?session=$session&sport=$activity[$i]&id=$offid','$activity[$i]','height=600,width=600,scrollbars=yes,menubar=no,toolbar=no,resizable=yes,titlebar=no')\"> $act_long[$i]</a>";
	       //Registered This Year?
	       for($j=0;$j<count($spreg_abb);$j++)
	       {
	          if($spreg_abb[$j]==$activity[$i])	//SHOW Class, Clinic if applicable
		  {
         	      echo " - Registered for $row2[regyr]";
		      $classif=GetOffClass($offid,$activity[$i]);
		      if($classif!='') echo ", Classification: $classif";
         	  }
	       }
	       echo "</li>";
	    }
	 }
	 echo "</ul></div></td></tr>";
      echo "</table>";
      echo "</td></tr>";
   }
   else
   {
      echo "<tr align=center><td><br>&nbsp;</td></tr>";
   }

   /***CONTRACTS***/
   if($open4==4) $newopen='not1';
   else $newopen=4;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=4 href=\"welcome.php?session=$session&open1=$open1&open2=$open2&open3=$open3&open4=$newopen&open5=$open5&open6=$open6&open7=$open7&open8=$open8&open9=$open9#4\">";
   if($open4==4) echo "[ - ]&nbsp;";
   else echo "[ + ]&nbsp;";
   $contractstr=substr($contractstr,0,strlen($contractstr)-2);
   echo "Contracts:&nbsp;<font style=\"color:blue\">$contractstr</font></a></th></tr>";
   if($open4==4)
   {
      echo "<tr align=center><td><table>";
      $hascontracts=0;
      for($j=0;$j<count($spreg_abb);$j++)
      {
         $sport=$spreg_abb[$j];
         if($sport=='bb') $sport='bbb';
         else if($sport=='so') $sport='sob';
         $contracts=GetOffContracts($sport,$offid,$session);
         $sportname=GetSportName($sport);
	 //if(ereg("Soccer",$sportname)) $sportname="Soccer";
         if(count($contracts[url])>0)
            echo "<tr align=left><td><p><b>$sportname</b></p></td></tr><tr align=left><td>";
         for($i=0;$i<count($contracts[url]);$i++)
         {
            $hascontracts=1;
            echo "<p><a class=small target=new href=\"".$contracts[url][$i]."\">".$contracts[linktitle][$i]."</a>";
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
            echo "</p>";
         }
         if($sport=='bbb' || $sport=='sob')
         {
            if($sport=='bbb') $sport='bbg';
	    else $sport='sog';
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
   }//end if open4==4
   else
   {
      echo "<tr align=center><td><br>&nbsp;</td></tr>";
   }

   /*****ONLINE RULES MEETINGS*****/
   if($open10==10) $newopen='not1';
   else $newopen=10;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=10 href=\"welcome.php?session=$session&open1=$open1&open2=$open2&open3=$open3&open4=$open4&open5=$open5&open6=$open6&open7=$open7&open8=$open8&open9=$open9&open10=$newopen#10\">";
   if($open10==10) echo "[ - ]&nbsp;";
   else echo "[ + ]&nbsp;";
   echo "Online Rules Meetings:&nbsp;</th></tr>";
   if($open10==10)
   {
      echo "<tr align=center><td align=center><br><table width=\"600px\" cellspacing=2 cellpadding=2>";
      $season=GetCurrentSeason();
      for($i=0;$i<count($spreg_abb);$i++)      
      {
	 //echo "<tr align=left><td>".$spreg_abb[$i]."</td></tr>";
         $cursp=$spreg_abb[$i]; 
	 if($cursp=='di') $cursp='sw';
	 $sportname=GetSportName($cursp);
	 $sql0="SELECT * FROM rulesmeetingdates WHERE sport='$cursp'";
         $result0=mysql_query($sql0);
	 if(mysql_num_rows($result0)>0)
	 {
            $rmtable=$cursp."rulesmeetings";
	       $sql2="SELECT * FROM rulesmeetingdates WHERE sport='$cursp'";
	       $result2=mysql_query($sql2);
	       $row2=mysql_fetch_array($result2);
               $fee=$row2[fee]; $latefee=$row2[latefee];
	       $startdate=$row2[startdate]; $latedate=$row2[latedate]; $enddate=$row2[enddate]; $paydate=$row2[paydate];
	       $ppfile=$row2[ppfile];
	       $late=split("-",$latedate); $end=split("-",$enddate); $pay=split("-",$paydate);
	       $start=split("-",$startdate); $year=$start[0]; $month=$start[1];
	       $regyr=GetSchoolYear($year,$month);
	       $sql2="SELECT rm FROM ".$cursp."off_hist WHERE regyr='$regyr' AND offid='$offid'";
	       $result2=mysql_query($sql2);
	       $row2=mysql_fetch_array($result2);
	       $currm=$row2[rm];
               $sql2="SELECT * FROM $rmtable WHERE offid='$offid'";
               $result2=mysql_query($sql2);
	       $row2=mysql_fetch_array($result2);
	       if($cursp!='sp' && $cursp!='pp') $newoff=IsNewOfficial($offid);
	       else $newoff=false;
	       echo "<tr align=left bgcolor=\"#e0e0e0\"><td><b>&nbsp;&nbsp;".strtoupper($sportname)." ONLINE RULES MEETING:</b></td></tr>";
	       if($currm=='x') //SCENARIO #1: Already Attended a Rules Meeting for This Sport
	       {
	          echo "<tr align=left><td>You have already attended a $sportname Rules Meeting and your attendance has been recorded in our system.</td></tr>";
         	  if($ppfile!='')
			echo "<tr align=left><td><a target=\"_blank\" href=\"$ppfile\" class=small>Click Here to Re-Watch the $sportname Rules Meeting Presentation</a></td></tr>";
	       }    
               else if($startdate=="0000-00-00")
		  echo "<tr align=left><td>The Online $sportname Rules Meeting will be available during a time period to be announced at a later date.</td></tr>";
	       else if($offid!='3427' && !PastDue($startdate,-1))	//SCENARIO #2: NOT YET AVAILABLE
                  echo "<tr align=left><td>The Online $sportname Rules Meeting will be available for <b>NO CHARGE</b> from ".date("F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0]))." until ".date("F j, Y",mktime(0,0,0,$pay[1],$pay[2],$pay[0]))." at midnight, after which the fee will be <b>$".number_format($fee,2,'.','')."</b> until ".date("F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0]))." at midnight, after which the fee will be increased to <b>$".number_format($latefee,2,'.','')."</b>.  The rules meeting will not be available online after ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).".</td></tr>";
	       else if(!PastDue($latedate,0))	//SCENARIO #3: AVAILABLE, NO LATE FEE YET
	       {
	 	  if($newoff)	//STARTING 8/14/12 - NEW OFFICIALS DON'T HAVE TO PAY
		     echo "<tr align=left><td>The Online $sportname Rules Meeting will be available to you for <b>NO CHARGE</b>, since you are a NEW Official, from ".date("F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0]))." through ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).", at midnight.</td></tr>";
	  	  else	
                     echo "<tr align=left><td>The Online $sportname Rules Meeting will be available for <b>NO CHARGE</b> from ".date("F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0]))." until ".date("F j, Y",mktime(0,0,0,$pay[1],$pay[2],$pay[0]))." at midnight, after which the fee will be <b>$".number_format($fee,2,'.','')."</b> until ".date("F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0]))." at midnight, after which the fee will be increased to <b>$".number_format($latefee,2,'.','')."</b>.  The rules meeting will not be available online after ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).".</td></tr>";
		  if($row2[datecompleted]>0 && $row2[datepaid]==0)	//COMPLETED BUT NOT PAID
	  	  {
		     echo "<tr align=center><td><div class=alert style=\"width:400px;\"><table width=100% cellspacing=1 cellpadding=1><tr align=left><td>You <b>watched</b> this rules meeting video but <b><u>";
             	     if($row2[datecompleted] < mktime(23,59,59,$pay[1],$pay[2],$pay[0]) || $newoff)        //NO FEE
             	     {
                	echo "did NOT verify your attendance"; $payorverify="Verification";
             	     }
             	     else
             	     {
                	echo "did NOT pay the fee"; $payorverify="Payment";
             	     }
		     echo "</b></u>.</td></tr>";
		     echo "<tr align=center><td><a class=small href=\"rulesmeetingpay.php?session=$session&sport=$cursp\">Click HERE to Complete $payorverify for this Rules Meeting</a></td></tr></table></div></td></tr>";
	 	     echo "<tr align=center><td>[You MUST complete payment to be marked as having attended a $regyr $sportname Rules Meeting.]</td></tr>";
	          }
		  else if($row2[initiated]>0 && $row2[datecompleted]==0)	//STARTED WATCHING BUT DIDN'T FINISH
		  {
	   	     echo "<tr align=left><td>You <b>started watching</b> but <b>did NOT finish</b> the $sportname Rules Meeting Video.</td></tr>";
		     echo "<tr align=center><td><a class=small href=\"rulesmeetingintro.php?session=$session&sport=$cursp\">Click HERE to Watch the $regyr $sportname Rules Meeting Video</a></td></tr>";
		  }
		  else 		//DID NOT START THE PROCESS YET
	 	  {
		     echo "<tr align=center><td><a class=small href=\"rulesmeetingintro.php?session=$session&sport=$cursp\">Click HERE to Watch the $regyr $sportname Rules Meeting Video</a></td></tr>";
		  }
	       }
	       else if(!PastDue($enddate,0))	//SCENARIO #4: AVAILABLE FOR A LATE FEE
	       {
                  if($newoff)   //STARTING 8/14/12 - NEW OFFICIALS DON'T HAVE TO PAY
                     echo "<tr align=left><td>The Online $sportname Rules Meeting will be available to you for <b>NO CHARGE</b>, since you are a NEW Official, from ".date("F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0]))." through ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).", at midnight.</td></tr>";
                  else  
		     echo "<tr align=left><td>The Online $sportname Rules Meeting will be available for the late fee of <b>$".number_format($latefee,2,'.','')."</b> until ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).", after which it will no longer be available.</td></tr>";
                  if($row2[datecompleted]>0 && $row2[datepaid]==0)      //COMPLETED BUT NOT PAID
                  {
                     echo "<tr align=left><td>You <b>watched</b> this rules meeting video but <b><u>";
             	     if($newoff || $row2[datecompleted] < mktime(23,59,59,$pay[1],$pay[2],$pay[0]))        //NO FEE
             	     {
                	echo "did NOT verify your attendance"; $payorverify="Verification";
             	     }
             	     else
             	     {
                 	echo "did NOT pay the fee"; $payorverify="Payment";
             	     }
	 	     echo "</b></u>.</td></tr>";
                     echo "<tr align=center><td><a class=small href=\"rulesmeetingpay.php?session=$session&sport=$cursp\">Click HERE to Complete $payorverify for this Rules Meeting</a></td></tr>";
                     echo "<tr align=left><td>[You MUST complete $payorverify to be marked as having attended a $regyr $sportname Rules Meeting.]</td></tr>";
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
	       else			//SCENARIO #5: NO LONGER AVAILABLE
	       {
	          echo "<tr align=left><td>This rules meeting is no longer available online.</td></tr>";
         	  if($ppfile!='') echo "<tr align=left><td><a href=\"$ppfile\" class=small target=\"_blank\">Click Here to Re-Watch the $sportname Rules Meeting Presentation</a> -- for your own purpose only; does not count as attendance with the NSAA.</td></tr>";
	       }	
	    //}//end if there is online RM for this sport
	 }//end if this sport is in current season
      }//end for each sport registered
      echo "</table></td></tr>";
   }//end if open10
   else   
   {      
      echo "<tr align=center><td><br>&nbsp;</td></tr>";   
   }

   /*****SCHEDULE ENTRY******/
   if($open5==5) $newopen='not1';
   else $newopen=5;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=5 href=\"welcome.php?session=$session&open1=$open1&open2=$open2&open3=$open3&open4=$open4&open5=$newopen&open6=$open6&open7=$open7&open8=$open8&open9=$open9#5\">";
   if($open5==5) echo "[ - ]&nbsp;";
   else echo "[ + ]&nbsp;";
   echo "Schedule Entry:&nbsp;</th></tr>";
   if($open5==5)
   {
      echo "<tr align=center><td align=center><table width=400>";
      if(IsReportCardOff($offid))
      { 
      //GAME REPORT CARDS:
      $reportcardsp=array("bbg","bbb");
      echo "<tr align=left bgcolor=#E0E0E0><td><b>&nbsp;&nbsp;Game Report Cards:</b></td></tr>";
      echo "<tr align=left><td><a class=small href=\"reportcards.php?session=$session&finished=1\">See Game Report Cards you have submitted.</a></td></tr>";
      //first, count number of games for which a report card has not been started:
      $offschedtbl="bbsched";
      $today=date("Y-m-d");
      $now=time(); $feb10=mktime(23,59,59,2,10,2007);
      if($now>$feb10)
         $today="2007-02-10";
      $sql="SELECT * FROM $offschedtbl WHERE offid='$offid' AND offdate<='$today' AND offdate>='2007-01-19' AND scoreid='0'";
      $result=mysql_query($sql);
      $unfinct=mysql_num_rows($result);
      for($i=0;$i<count($reportcardsp);$i++)
      {
         $cursp=$reportcardsp[$i];
         $reporttbl="reportcard_".$cursp;
	 if($cursp=='bbb') $gender='b';
	 else if($cursp=='bbg') $gender='g';
         if($now>$feb10)
            $today="2007-02-10";
         $sql="SELECT t1.* FROM $offschedtbl AS t1 LEFT JOIN $reporttbl AS t2 ON (t1.offid=t2.offid AND t1.scoreid=t2.scoreid) WHERE t1.offid='$offid' AND t1.offdate<='$today' AND t1.offdate>='2007-01-19' AND t2.datesub='' AND t1.gender='$gender'";
         $result=mysql_query($sql);
	 $unfinct+=mysql_num_rows($result);
      }
      echo "<tr align=left><td>You have $unfinct <u>unfinished</u> Basketball Game Report ";
      if($unfinct==1) echo "Card";
      else echo "Cards";
      echo "</td></tr>";
      echo "<tr align=left><td><a class=small href=\"reportcards.php?session=$session&finished=0\">Click Here to Complete Unfinished Report Cards</a><br><br></td></tr>";
      }//end if game report card off
      echo "<form method=post action=\"schedule.php\">";
      echo "<input type=hidden name=session value=\"$session\">";
      echo "<tr align=left bgcolor=#E0E0E0><td><b>&nbsp;&nbsp;<b>Enter your Schedules:</b></td></tr>";
      echo "<tr align=center><td><select name=schedsport>";
      for($i=0;$i<count($spreg_abb);$i++)
      {
         echo "<option value='$spreg_abb[$i]'>$spreg_long[$i]</option>";
      }
      echo "</select>";
      echo "&nbsp;<input type=submit name=go value=\"Go\"></form><br>";
      //echO "<font style=\"color:red\"><b>PLEASE NOTE: The Basketball Schedules are currently under construction.  Please check back later.</b></font>";
      echo "</td></tr></table>";
   }//end if open5==5
   else
   {
      echo "<tr align=center><td><br>&nbsp;</td></tr>";
   }

   /******ONLINE TESTS******/
   if($open6==6) $newopen='not1';
   else $newopen=6;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=6 href=\"welcome.php?session=$session&open1=$open1&open2=$open2&open3=$open3&open4=$open4&open5=$open5&open6=$newopen&open7=$open7&open8=$open8&open9=$open9#6\">";
   if($open6==6) echo "[ - ]&nbsp;";
   else echo "[ + ]&nbsp;";
   echo "Online Tests:&nbsp;</th></tr>";
   if($open6==6)
   {
      echo "<tr align=center><td>";
	 //PART 1 - OPEN BOOK TESTS
	 //For each Sport, the official can possibly:
	 //	1) Take the test or Re-take the test
	 //	2) Print the test
	 //	3) View test results
	echo "<br /><h3><u>Part 1 (OPEN BOOK) Tests:</u></h3><div style=\"max-width:600px;text-align:left;\">";
		//echo'<pre>';print_r($spreg_abb); exit;
        for($i=0;$i<count($spreg_abb);$i++)
        {
	   /*
	   Did the official pass this test? (>=80)
	   YES --> Is the Due Date enough in the past to view results?
		YES --> can VIEW their RESULTS
		NO --> Tell them when they will be able to view them
	   NO --> How many attempts have they completed? (0 or more)
		LESS THAN 3 --> Is the Due Date past?
			YES --> Cannot take the test
			NO --> Can (re)take the Test AND Print the Test
		3 or MORE --> No More Retakes	
	   */
	   $duedate=GetTestDueDate($spreg_abb[$i]);
	   $fakedate=GetTestDueDate($spreg_abb[$i],'fakeduedate');
	   if(preg_match("/-00-/",$fakedate)) $fakedate=$duedate;	//SAFEGUARD
	   $obtest=explode("-",GetPart1TestScore($offid,$spreg_abb[$i],"",TRUE));
	   if($spreg_abb[$i]=='so')
	   $sobtest=explode("-",GetSPart1TestScore($offid,$spreg_abb[$i],"",TRUE));
	   if($obtest[0]>=80)	//PASSED!
	   {  
	      echo "<p><b>$spreg_long[$i]:</b> <i>You passed!</i>&nbsp;";
	      $daystowait=GetTestDueDate($spreg_abb[$i],'daystowait');
	      if(PastDue($duedate,$daystowait))	//can VIEW their RESULTS
            	 echo "<a href=\"viewtest.php?session=$session&sport=$spreg_abb[$i]\" target=\"_blank\">View your $sportname Part 1 Test</a></p>";
	      else				//Tell them when they will be able to view them
		 echo "You will be able to see your test results in full on ".date("F j, Y",strtotime($duedate)+($daystowait*24*60*60)+100).".</p>";	//The 100 is just to be sure we get all the way to the day
	   } //END IF PASSED THIS TEST
	   else if($obtest[1]<3)	//Can potentially (re)take the test
	   {
	      if(PastDue($duedate,0))	//Due Date is PAST - Cannot take the test
		 echo "<p>The <b>$spreg_long[$i]</b> Part 1 Test was due on ".date("F j, Y",strtotime($fakedate))."</p>";
	      else 	//Can (re)take the test and print it too
	      {
	         echo "<p><b>$spreg_long[$i]:</b></p>";
		 if($obtest[1]>0)	//RETAKE
	         {
                    if($obtest[1]==1)
                       echo "<p><i>You've attempted this test once and have failed.";
                    else 
                       echo "<p><i>You've attempted this test $obtest[1] times and have failed.";
	            echo " You can take this test a maximum of 3 times.</i></p>";
		    $sql="SELECT * FROM ".$spreg_abb[$i]."test_results WHERE offid='$offid'";
		    $result=mysql_query($sql);
		    $row=mysql_fetch_array($result);
		    $retake=$row[id]; $take="RETAKE";
	   	 }
	         else 
	         {
		    $retake=0; $take="TAKE";
		 }
	         echo "<ul><li><a href=\"onlinetest.php?testsport=$spreg_abb[$i]&retake=$retake&session=$session\">$take the $spreg_long[$i] Part 1 Test</a> - due ".date("F j, Y",strtotime($fakedate))."</li>
			<li><a target=\"_blank\" href=\"printtest.php?sport=$spreg_abb[$i]&session=$session\">PRINT the $spreg_long[$i] Part 1 Test</a></li>";
	   	 echo "</ul>";
	      }
	   } //END IF THEY'VE TAKEN LESS THAN 3 ATTEMPTS
	   else	//EXHAUSTED ALL ATTEMPTS
	   {
	      echo "<p><b>$spreg_long[$i]:</b></p><p>We're sorry, but you've failed this test 3 times.</p>";
              $daystowait=GetTestDueDate($spreg_abb[$i],'daystowait');
              if(PastDue($duedate,$daystowait)) //can VIEW their RESULTS
                 echo "<a href=\"viewtest.php?session=$session&sport=$spreg_abb[$i]\" target=\"_blank\">View your $sportname Part 1 Test</a></p>";
              else                              //Tell them when they will be able to view them
                 echo "You will be able to see your test results in full on ".date("F j, Y",strtotime($duedate)+($daystowait*24*60*60)+100).".</p>";    //The 100 is just to be sure we get all the way to the day
	   }
	   if($spreg_abb[$i]=='so'){
	   //echo '<pre>'; print_r($sobtest); 
	   if($sobtest[0]>=80)	//PASSED!
	   {  
	      echo "<p><b>$spreg_long[$i]:</b> <i>You passed!</i>&nbsp;";
	      $daystowait=GetTestDueDate($spreg_abb[$i],'daystowait');
	      if(PastDue($duedate,$daystowait))	//can VIEW their RESULTS
            	 echo "<a href=\"viewtest.php?session=$session&sport=sos\" target=\"_blank\">View your $sportname Part 1 Test(Spanish)</a></p>";
	      else				//Tell them when they will be able to view them
		 echo "You will be able to see your test (Spanish) results in full on ".date("F j, Y",strtotime($duedate)+($daystowait*24*60*60)+100).".</p>";	//The 100 is just to be sure we get all the way to the day
	   } //END IF PASSED THIS TEST
	   else if($sobtest[1]<3)	//Can potentially (re)take the test
	   {
	      if(PastDue($duedate,0))	//Due Date is PAST - Cannot take the test
		  echo "<p>The <b>$spreg_long[$i]</b> Part 1 Test (Spanish) was due on ".date("F j, Y",strtotime($fakedate))."</p>";
	      else 	//Can (re)take the test and print it too
	      {
	         echo "<p><b>$spreg_long[$i]:</b></p>";
		 if($sobtest[1]>0)	//RETAKE
	         {
                    if($sobtest[1]==1)
                       echo "<p><i>You've attempted this test(Spanish) once and have failed.";
                    else 
                       echo "<p><i>You've attempted this test(Spanish)$sobtest[1] times and have failed.";
	            echo " You can take this test(Spanish) a maximum of 3 times.</i></p>";
		    $sql="SELECT * FROM sostest_results WHERE offid='$offid'";
		    $result=mysql_query($sql);
		    $row=mysql_fetch_array($result);
		    $retake=$row[id]; $take="RETAKE";
	   	 }
	         else 
	         {
		    $retake=0; $take="TAKE";
		 }
	         echo "<ul><li><a href=\"onlinetest.php?testsport=sos&retake=$retake&session=$session\">$take the $spreg_long[$i] Part 1 Test (Spanish)</a> - due ".date("F j, Y",strtotime($fakedate))."</li>
			<li><a target=\"_blank\" href=\"printtest.php?sport=sos&session=$session\">PRINT the $spreg_long[$i] Part 1 Test (Spanish)</a></li>";
	   	 echo "</ul>";
	      }
	   } //END IF THEY'VE TAKEN LESS THAN 3 ATTEMPTS
	   else	//EXHAUSTED ALL ATTEMPTS
	   {
	      echo "<p><b>$spreg_long[$i]:</b></p><p>We're sorry, but you've failed this test (Spanish) 3 times.</p>";
              $daystowait=GetTestDueDate($spreg_abb[$i],'daystowait');
              if(PastDue($duedate,$daystowait)) //can VIEW their RESULTS
                 echo "<a href=\"viewtest.php?session=$session&sport=sos\" target=\"_blank\">View your $sportname Part 1 Test</a></p>";
              else                              //Tell them when they will be able to view them
                 echo "You will be able to see your test (Spanish) results in full on ".date("F j, Y",strtotime($duedate)+($daystowait*24*60*60)+100).".</p>";    //The 100 is just to be sure we get all the way to the day
	   }
	   }
	} //END FOR EACH SPORT
	echo "</div>";

         //PART 2 - SUPERVISED TESTS
         echo "<div style=\"max-width:600px;text-align:left;\"><hr><form method=post action=\"onlinetest2.php\"><h3 style=\"text-align:center;\"><u>PART 2 (formerly \"SUPERVISED\") Tests:</u></h3>";
         echo "<input type=hidden name=session value=\"$session\">";
         echo "<p><b>Take a PART 2 Test:&nbsp;</b><select name=\"testsport\">";
         $viewtest=array();
         $vx=0; //array to show tests taken
         for($i=0;$i<count($spreg_abb);$i++)
         {
            if($offid==3427 || CanTakeSupTestOnline($offid,$spreg_abb[$i]))
	    {
            //check if official has already taken this test
            $testtable=$spreg_abb[$i]."test2_results";
            $sql="SELECT datetaken FROM $testtable WHERE offid='$offid'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);
            //also check if past due date for this test
            $sql2="SELECT * FROM test2_duedates WHERE test='$spreg_abb[$i]'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            $date=split("-",$row2[duedate]);
            $duedate=mktime(0,0,0,$date[1],$date[2],$date[0]);
            $duedate+=24*60*60; //until midnight on the due date
            $date=split("-",$row2[showdate]);
            $showdate=mktime(0,0,0,$date[1],$date[2],$date[0]);
            $now=time();
            if(($row[0]=="" && $now>=$showdate && $now<$duedate) || $offid=='3427')	//HAVENT'T TAKEN TEST AND WE ARE WITHIN TESTING WINDOW
            {
               echo "<option value='$spreg_abb[$i]'>$spreg_long[$i]</option>";
               $tx++;
            }
            $days1=24*60*60;
            if($row[0]!="" && $now>=($duedate+$days1))  //if taken and 1 days past due date
            {
               $viewtest[$vx]=$spreg_abb[$i];
               $vx++;
            }
	    }
         }
         echo "</select>&nbsp;";
    	 echo "Your 60 minutes to take the test starts when you click GO: <input type=submit name=go value=\"Go\"";
	 if($tx==0) echo " disabled";
	 echo "></p>";
	 if($tx==0) 
	    echo "<p style=\"padding-left:40px;\"><i>(No part 2 tests are currently available for you to take at this time.)</i></p>";
	 echo "</form>";
         if(count($viewtest)>0)
            echo "<p><b>Past Part 2 Test Results:</b></p>";
         for($i=0;$i<count($viewtest);$i++)
         {
            for($j=0;$j<count($spreg_abb);$j++)
            {
               if($viewtest[$i]==$spreg_abb[$j])
                  $sportname=$spreg_long[$j];
            }
            echo "<p><a href=\"viewtest2.php?session=$session&sport=$viewtest[$i]\" target=\"_blank\">$sportname</a></p>";
         }
         
	 //NEXT SUPERVISED TEST DATES:
         $string="";
         for($i=0;$i<count($spreg_abb);$i++)
         {
            //tell official when sup test date is for each sport they're registered for
            $sql="SELECT suptestdate FROM ".$spreg_abb[$i]."off WHERE offid='$offid' AND suptestdate!=''";
            $result=mysql_query($sql);
            if($row=mysql_fetch_array($result))
            {
	       $string.="<li><b>$spreg_long[$i]:</b> $row[0]</li>";
	    }
         }
	 if($string!='')
	    echo "<p><b>Your next Part 2 test dates are:</b></p><ul>$string</ul>";
         echo "</div><br>";
      echo "</td></tr>";
   }//end if open6==6
   else
   {
      echo "<tr align=center><td><br>&nbsp;</td></tr>";
   }

   /******APPLICATIONS TO OFFICIATE******/
   if($open7==7) $newopen='not1';
   else $newopen=7;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=7 href=\"welcome.php?session=$session&open1=$open1&open2=$open2&open3=$open3&open4=$open4&open5=$open5&open6=$open6&open7=$newopen&open8=$open8&open9=$open9#7\">";
   if($open7==7) echo "[ - ]&nbsp;";
   else echo "[ + ]&nbsp;";
   echo "Applications to Officiate:&nbsp;</th></tr>";
   if($open7==7)
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
   }//end if open7==7
   else
   {
      echo "<tr align=center><td><br>&nbsp;</td></tr>";
   }

   /****EJECTION REPORTS****/
   if($open8==8) $newopen='not1';
   else $newopen=8;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=8 href=\"welcome.php?session=$session&open1=$open1&open2=$open2&open3=$open3&open4=$open4&open5=$open5&open6=$open6&open7=$open7&open8=$newopen&open9=$open9#8\">";
   if($open8==8) echo "[ - ]&nbsp;";
   else echo "[ + ]&nbsp;";
   echo "Ejection Reports:&nbsp;</th></tr>";
   if($open8==8)
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
   }//end if open8==8
   else
   {
      echo "<tr align=center><td><br>&nbsp;</td></tr>";
   }

   /****YELLOW CARD REPORTS****/
   $issocceroff=0;
   for($i=0;$i<count($spreg_abb);$i++)
   {
      if(ereg("so",$spreg_abb[$i])) $issocceroff=1;
   }
   if($issocceroff)
   {
   if($open11==11) $newopen='not1';
   else $newopen=11;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=11 href=\"welcome.php?session=$session&open1=$open1&open2=$open2&open3=$open3&open4=$open4&open5=$open5&open6=$open6&open7=$open7&open11=$newopen&open9=$open9#11\">";
   if($open11==11) echo "[ - ]&nbsp;";
   else echo "[ + ]&nbsp;";
   echo "Yellow Card Reports:&nbsp;</th></tr>";
   if($open11==11)
   {
      echo "<tr align=center><td align=center><br>";
      echo "<a class=small href=\"yellowcard.php?session=$session\">Submit a Yellow Card Report</a><br><br>";
      $sql="SELECT * FROM yellowcards WHERE offid='$offid' ORDER BY sport,datesub";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
         echo "<table cellspacing=0 cellpadding=1><tr align=left><td><b>Your Yellow Card Reports:</b></td></tr>";
         while($row=mysql_fetch_array($result))
         {
            $datesub=date("m/d/y",$row[datesub]);
            echo "<tr align=left><td><a class=small href=\"view_yellowcard.php?session=$session&id=$row[id]\">".GetSportName($row[sport])." - ".GetSchoolName($row[sid],$row[sport],date("Y"))." (Submitted $datesub)</a>";
            if($row[verify]=='x')
               echo "&nbsp;&nbsp;(The NSAA has received this report)";
            echo "</td></tr>";
         }
         echo "</table><br>";
      }
      echo "</td></tr>";
   }//end if open11==11
   else
   {
      echo "<tr align=center><td><br>&nbsp;</td></tr>";
   }
   }//end if IS SOCCER OFFICIAL

   /****OBSERVATIONS****/
   if($open9==9) $newopen='not1';
   else $newopen=9;
   echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;<a class=black name=9 href=\"welcome.php?session=$session&open1=$open1&open2=$open2&open3=$open3&open4=$open4&open5=$open5&open6=$open6&open7=$open7&open8=$open8&open9=$newopen#9\">";
   if($open9==9) echo "[ - ]&nbsp;";
   else echo "[ + ]&nbsp;";
   echo "Observations:&nbsp;</th></tr>";
   if($open9==9)
   {
      echo "<tr align=center><td align=center><br>";
      echo "<p><a href=\"viewobs.php?session=$session\">View Observations from Previous Years</a></p>";
      $fb=0; $ba=0;
      for($i=0;$i<count($spreg_abb);$i++)
      {
         if($spreg_abb[$i]=="fb") $fb=1;
	 else if($spreg_abb[$i]=="ba") $ba=1;
      }
      echo "<br><table cellspacing=0 cellpadding=5 style=\"width:600px;\" class='nine'>";
      $caption=0;
      for($i=0;$i<count($spreg_abb);$i++)
      {
         $table=$spreg_abb[$i]."observe";
         $sql="SELECT * FROM $table WHERE offid='$offid' AND dateeval!='' ORDER BY dateeval";
         if($spreg_abb[$i]=='bb')
	 {
            $sql="SELECT * FROM $table WHERE (offid='$offid' OR offid2='$offid' OR offid3='$offid') AND dateeval!='' ORDER BY dateeval";
	    $chiefid=$offid;
	 }
         else if($spreg_abb[$i]=='fb')
         {
	    $chiefid=GetCrewChief($offid);
	    $sql="SELECT * FROM $table WHERE offid='$chiefid' AND dateeval!='' ORDER BY dateeval";
         }
	 else $chiefid=$offid;
         $result=mysql_query($sql);
         if(mysql_num_rows($result)>0)
         {
	    if($caption==0)
	    {
	       	echo "<caption><p><b>Observations submitted THIS YEAR about you and your crew (if applicable) appear below:</b></p>";
	       	echo "</caption>";
	       $caption=1;
	    }
	    echo "<tr align=left><td><b>$spreg_long[$i]:</b></td></tr>";
         }
         while($row=mysql_fetch_array($result))
         {
	    $obsid=$row[obsid];
	    $obsname=GetObsName($obsid);
	    $dateeval=date("m/d/y",$row[dateeval]);
	    echo "<tr align=left><td>";
	    $sport2=$spreg_abb[$i];
	    if($spreg_abb[$i]=='bb' && $row[postseasongame]=='1')
               echo "<a href=\"$table.php?session=$session&sport=$sport2&gameid=$row[gameid]&postseasongame=1&offid=$chiefid&obsid=$row[obsid]\" target=\"_blank\">$row[home] vs. $row[visitor] (Evaluated $dateeval by $obsname)</a>";
            else
            {
               echo "<a href=\"$table.php?session=$session&sport=$sport2&gameid=$row[gameid]&offid=$chiefid&obsid=$row[obsid]\" target=\"_blank\">";
   	       if($spreg_abb[$i]=='wr') echo "$row[event]";
	       else echo "$row[home] vs. $row[visitor]";
	       echo " (Evaluated $dateeval by $obsname)</a>";
	    }
	    echo "</td></tr>";
         }
   	 if($spreg_abb[$i]=='bb')	//CLINIC OBSERVATIONS
	 {
            $sql="SELECT * FROM ".$spreg_abb[$i]."clinicobserve WHERE (offid='$offid' OR offid2='$offid' OR offid3='$offid') AND dateeval!='' ORDER BY dateeval";
            $chiefid=$offid;
	    $result=mysql_query($sql);
            if(mysql_num_rows($result)>0)
            {
               if($caption==0)
               {
                   echo "<caption><p><b>Observations submitted THIS YEAR about you and your crew (if applicable) appear below:</b></p>";
                   echo "</caption>";
                  $caption=1;
               }
               echo "<tr align=left><td><b>$spreg_long[$i]:</b></td></tr>";
            }
            while($row=mysql_fetch_array($result))
            {
               $obsid=$row[obsid];
               $obsname=GetObsName($obsid);
               $dateeval=date("m/d/y",$row[dateeval]);
               echo "<tr align=left><td>";
               $sport2=$spreg_abb[$i];
	       $cdate=explode("-",$row[clinicdate]);
               echo "<a href=\"".$sport2."clinicobserve.php?session=$session&sport=$sport2&offid=$chiefid&obsid=$row[obsid]\" target=\"_blank\">Clinic at $row[location] on $cdate[1]/$cdate[2]/$cdate[0]";
               echo " (Evaluated $dateeval by $obsname)</a>";
               echo "</td></tr>";
            }
	 }//END IF BB
      }
      if($caption==0)
	 echo "<caption><i>No observations have been submitted for you this year.</i></caption>";
      echo "</table><br>";
      if($ba==1)
         echo "<p><b>Baseball Officials:</b> <a href=\"baobserve.php?print=1&session=$session\" target=\"_blank\">Preview the Umpire Evaluation Form</a> (Observers will use this form to evaluate your work as an umpire.)</p>";
      if($fb==1)
         echo "<p><b>Football Officials:</b> <a href=\"fbobserve.php?print=1&session=$session\" target=\"_blank\">Preview the Football Crew Evaluation Form</a> (Observers will use this form to evaluate the work of your crew.)</p>";
      echo "</td></tr>";
   }//end if open9==9
   else
   {
      echo "<tr align=center><td><br>&nbsp;</td></tr>";
   }
}//end if level=2
else if($level==3)	//Observer login
{
?>
   <br>
   <table width=\"800px\" cellspacing=2 cellpadding=2>
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
   $ct=0; $obs_sp=array(); $ix=0;
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
	 $obs_sp[$ix]=$activity[$i]; $ix++;
      }
   }
   echo "</select>&nbsp;<input type=submit name=go value=\"Go\"></form>";
   echo "<font style=\"color:red;font-size:8pt;\"><b>You have selected ".strtoupper($cursport).".<br></b><i>(If you are listed under more than one sport, you may work on a different sport by selecting it from the dropdown menu above.)</i></font><br><br></caption>";
   echo "<tr align=left bgcolor='#e0e0e0'><th>&nbsp;&nbsp;<b>Links:</b></th></tr>";
   echo "<tr align=center><td><table><tr align=left><td><ul>";
   echo "<li><a href=\"downloads/ObserverExpenseReport.doc\">Expense Report (Word Doc)</a>&nbsp;&nbsp;";
   echo "<a href=\"downloads/ObserverExpenseReport.pdf\" target=\"_blank\">Expense Report (PDF)</a></li>";
   echo "<li><a target=\"_blank\" href=\"".$obssport."observe.php?session=$session&print=1\">Printable Version of the $cursport Evaluation Form</a></li>";
   if($obssport=='bb')	//CLINIC EVAL LINK
      echo "<li><a target=\"_blank\" href=\"".$obssport."clinicobserve.php?session=$session&print=1\">Printable Version of the CLINIC Evaluation Form</a></li>";
   $sql="SELECT * FROM rosters WHERE sport='$obssport' AND active='x'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<li><a target=\"_blank\" href=\"roster.php?session=$session&print=1&sport=$obssport\">Printable Version of $cursport Officials Roster</a></li>";
   }
   if($archiveroster==1)
   {
      $sql="SELECT * FROM rosters WHERE showold='x' AND sport='$obssport'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
         echo "<li><a href=\"roster.php?session=$session&print=1&sport=$obssport&archive=$archivedbroster\" target=\"_blank\">$lastyearroster $cursport Roster</a></li>";
      }
   }
   echo "<li><a href=\"photosadmin.php?session=$session&sport=$obssport\">Lookup $cursport Officials' Photos</a></li>";
   if($obssport=='so')
      echo "<li>Confirmed Soccer Officials Assignments for District and State Tournaments: <a href=\"sobassignreport.php?session=$session\">Boys</a>&nbsp;|&nbsp;<a href=\"sogassignreport.php?session=$session\">Girls</a></li>";
   else if($obssport=='fb')
      echo "<li><a target=new href=\"fbcrewexport.php?session=$session\">Football Crew Information Export</a></li>";
   else if ($obssport=='bb')
   {
      echo "<li><b>Basketball Officials Assignments for District, Subdistrict, District Final, and State Tournaments:</b><ul>";
      echo "<li><a href=\"bbbassignreport2.php?session=$session\">BOYS BASKETBALL Officials Assignments</a></li>";
      echo "<li><a href=\"bbgassignreport2.php?session=$session\">GIRLS BASKETBALL Officials Assignments</a></li>";
      echo "</ul></li>";
   }
   else if($obssport=='wr')
   {
      echo "<li><a href=\"vote_wr.php?session=$session&nsaa=1\">View the Wrestling Officials Ballot</a></li>";
   }
   echo "</td></tr></table></td></tr>";
   echo "<tr bgcolor=#E0E0E0 align=left>";
   echo "<th align=left>&nbsp;&nbsp;INBOX: Messages & Downloads:</th></tr>";
   echo "<tr align=center><td><table width='90%'>";
      echo "<tr bgcolor=#E0E0E0 align=left>";
      echo "<th align=left>&nbsp;&nbsp;Messages:</th></tr>";
      echo "<tr align=center><td><br><table><tr align=left><td>";
      //get number of messages from the NSAA
      $sql="SELECT DISTINCT(title) FROM messages WHERE CURDATE()<=end_date AND (";
      for($i=0;$i<count($obs_sp);$i++)
      {
         $sql.="sport='$obs_sp[$i]' OR ";
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
      echo "<th class=small align=left>&nbsp;&nbsp;<a class=black href=\"welcome.php?session=$session&subopen=$newsubopen&obssport=$obssport\">";
      if($subopen==3) echo "[ - ]&nbsp;";
      else echo "[ + ]&nbsp;";
      //get number of downloads
      $sql="SELECT DISTINCT filename,doctitle FROM downloads WHERE (recipients='$obssport' OR recipients='All') AND active='y' ORDER BY doctitle";
      $result=mysql_query($sql);
      echo "Downloads (".mysql_num_rows($result)."):</a></th></tr>";
      //if($subopen==3)
      //{
         echo "<tr align=center><td><table><tr align=left valign=top><td><ul>";
	 $total=mysql_num_rows($result);
	 if($total%3==0) $percol=$total/3;
	 else $percol=ceil($total/3);
	 $curcol=0;
         while($row=mysql_fetch_array($result))
         {
	    if($curcol>=$percol)
	    {
	       echo "</ul></td><td><ul>"; $curcol=0;
	    }
            $row[filename]=preg_replace("/(www.)/","",$row[filename]);
            echo "<li><a class=small href=\"$row[filename]\" target=new>$row[doctitle]</a></li>";
	    $curcol++;
         }
         echo "</ul></td></tr></table></td></tr>";
      //} 
      echo "</table></td></tr>";

   /***** RULES MEETING VIDEOS *****/
   if($obssport=='fb' || $obssport=='vb' || $obssport=='sb' || $obssport=='bb' || $obssport=='wr')
   {
      echo "<tr align=left bgcolor=#E0E0E0><th align=left>&nbsp;&nbsp;Watch the <font style=\"color:red\">$cursport</font> Rules Meeting Video:</th></tr>";
      echo "<tr align=center><td><br><a href=\"rmobserver.php?sport=$obssport&session=$session\">Click HERE to watch the $cursport Rules Meeting Presentation</a><br><br></td></tr>";
   }

   /***** LOOKUP PICTURES *****/

   /***** FILL OUT NEW EVALUATION *****/
   echo "<tr align=left bgcolor=#E0E0E0><th align=left>&nbsp;&nbsp;LOOKUP <font style=\"color:red\">".strtoupper($cursport)."</font> OFFICIALS' SCHEDULES:</th></tr>";
   echo "<tr align=center><td>";
   echo "<form method=post action=\"obssearch.php\">";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<input type=hidden name=sport value=$obssport>";
   echo "<table><tr align=left><td colspan=2><ul>";
   echo "<li><a href=\"photosadmin.php?session=$session&sport=$obssport\">Lookup $cursport Officials' Photos</a></li>";
   if($obssport=='fb')
   {
        //EVALUATION REPORTS:
         echo "<li><b>REPORT: <a class=small href=\"offevalreport.php?session=$session&sport=$obssport\" target=\"_blank\">Year(s) each official has been EVALUATED</a></li>";
  
        //ASSIGNMENT REPORTS:
        echo "<li><b>REPORT: <a class=small href=\"offassignreport.php?session=$session&sport=$assignsport\" target=\"_blank\">Year(s) each official has been ASSIGNED TO POSTSEASON</a></li>";
  
      $sql="SELECT DISTINCT class FROM fbbrackets WHERE showdistinfo='y' ORDER BY class";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
      {
	 echo "<li><b>Playoff Brackets with Crew Chiefs listed:</b> ";
       	 while($row=mysql_fetch_array($result))
      	 {
	    echo "<a class=small href=\"fbbracket.php?class=$row[class]&officials=1\" target=\"_blank\">Class $row[class]</a>&nbsp;&nbsp;";
	 }
         echo "</li>";
      }
   }
   echo "</td></tr>";
   echo "<tr align=left><td colspan=2><div class=alert style='width:400px;'>To lookup an official's schedule and/or fill out an evaluation, please search for the official by name or city OR search for the game by date below.</div></td></tr>";
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
   if($obssport=='vb' || $obssport=='so')
      echo "<tr align=left><td colspan=2><input type=checkbox name=\"applied\" value=\"x\"> <b>Only show me officials who have APPLIED TO OFFICIATE POSTSEASON</b></td></tr>";
   if($obssport=="wr" || $obssport=="vb") $game="Match";
   else if($obssport=="sw" || $obssport=="di" || $obssport=="tr") $game="Meet";
   else $game="Game";
   echo "<tr align=left><td><b>Date of $game:</b></td>";
   echo "<td><select name=\"month\"><option value='00'>MM</option>";
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $m="0".$i;
      else $m=$i;
      echo "<option>$m</option>";
   }
   echo "</select>/<select name=\"day\"><option value='00'>DD</option>";
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $d="0".$i;
      else $d=$i;
      echo "<option>$d</option>";
   }
   echo "</select>/<select name=\"year\">";
   $year1=date("Y")-1; $year2=date("Y"); $year3=date("Y")+1;
   for($i=$year1;$i<=$year3;$i++)
   {
      echo "<option";
      if($i==$year2) echo " selected";
      echo ">$i</option>";
   }
   echo "</select></td></tr>";
   echo "<tr align=center><td colspan=2><input type=submit name=search value=\"Search\"></td></tr>";
   echo "</table></form>";
   echo "</td></tr>";
   echo "<tr align=left bgcolor=#E0E0E0><th align=left>&nbsp;&nbsp;Your <font style=\"color:red\">$cursport</font> Evaluations:</th></tr>";
   echo "<tr align=center><td><br>";
   if($obssport=='bb')
      echo "<a href=\"bbclinicobserve.php?session=$session\" target=\"_blank\">Fill out a $cursport CLINIC Evaluation</a><br>";
   echo "<table cellspacing=0 cellpadding=5 class='nine'>";
   $none=1;	//assume NO evaluations to show
   $obstable=$obssport."observe";
   $schtable=$obssport."sched";
   //first show observations still working on:
   $sql="SELECT * FROM $obstable WHERE obsid='$obsid' AND dateeval='' ORDER BY id";
   $result=mysql_query($sql); 
   if(mysql_num_rows($result)>0)
   {
      echo "<tr align=left><td><b>Evaluations You've SAVED but NOT SUBMITTED: </b><br>(You may view AND edit these evaluations.  These evaluations have NOT been submitted to the NSAA yet.)<br></td></tr>";
      $none=0;
   }
   $obssport2=$obssport;
   while($row=mysql_fetch_array($result))
   {
      $sql2="SELECT first, last FROM officials WHERE id='$row[offid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $offname="$row2[first] $row2[last]";
      echo "<tr align=left><td>";
      if($obssport=='bb' && $row[postseasongame]==1)
      {
         echo "<a class=small target=new href=\"".$obssport2."observe.php?session=$session&sport=$obssport2&postseasongame=1&gameid=$row[gameid]&offid=$row[offid]\">$offname&nbsp;&nbsp;&nbsp;$row[home] vs $row[visitor] (Post Season)</a>";
      }
      else
      {
         echo "<a class=small target=new href=\"".$obssport2."observe.php?session=$session&sport=$obssport2&gameid=$row[gameid]&offid=$row[offid]\">$offname&nbsp;&nbsp;&nbsp;$row[home] vs. $row[visitor]</a>";
      }
      echo "</td></tr>";
   }
   if($obssport=='bb')
   {
      //CLINIC (Saved, not Submitted):
      $sql="SELECT * FROM ".$obssport2."clinicobserve WHERE obsid='$obsid' AND dateeval='' ORDER BY id";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0 && $none==1)
      {
         echo "<tr align=left><td><b>Evaluations You've SAVED but NOT SUBMITTED: </b><br>(You may view AND edit these evaluations.  These evaluations have NOT been submitted to the NSAA yet.)<br></td></tr>";
         $none=0;
      }
      $obssport2=$obssport;
      while($row=mysql_fetch_array($result))
      {
	 if(!($row[offid]==3427 && trim($row[official])!=''))
	 {
            $sql2="SELECT first, last FROM officials WHERE id='$row[offid]'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            $offname="$row2[first] $row2[last]";
	 }
	 else $offname=$row[official];
	 $cdate=explode("-",$row[clinicdate]);
         echo "<tr align=left><td>";
         echo "<a class=small target=new href=\"".$obssport2."clinicobserve.php?session=$session&sport=$obssport2&offid=$row[offid]\">$offname:&nbsp;&nbsp;&nbsp;Clinic at $row[location], $cdate[1]/$cdate[2]/$cdate[0]</a>";
         echo "</td></tr>";
      }  
   }
   //then show submitted evaluations
   $sql="SELECT * FROM $obstable WHERE obsid='$obsid' AND dateeval!='' ORDER BY dateeval DESC";
   $result=mysql_query($sql);
   $none2=1;
   if(mysql_num_rows($result)>0)
   {
      echo "<tr align=left><td><br><b>Evaluations you've SUBMITTED this year:</b><br>(You may only view these evaluations.  These evaluations have been submitted to the NSAA.)</td></tr>";
      $none2=0; $none=0;
   }
   while($row=mysql_fetch_array($result))
   {
      $sql2="SELECT first, last FROM officials WHERE id='$row[offid]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $offname="$row2[first] $row2[last]";
      echo "<tr align=left><td>";
      $obssport2=$obssport;
      if($obssport=='bb' && $row[postseasongame]==1)
      {
          echo "<a class=small target=new href=\"".$obssport2."observe.php?session=$session&sport=$obssport2&gameid=$row[gameid]&postseasongame=1&offid=$row[offid]\">$offname&nbsp;&nbsp;&nbsp;$row[home] vs. $row[visitor] (Post Season)&nbsp;&nbsp;&nbsp;(Evaluated ".date("m/d/y",$row[dateeval]).")</a>";
      }
      else
      {
         echo "<a class=small target=new href=\"".$obssport2."observe.php?session=$session&sport=$obssport2&gameid=$row[gameid]&offid=$row[offid]\">$offname&nbsp;&nbsp;&nbsp;$row[home] vs. $row[visitor]&nbsp;&nbsp;&nbsp;(Evaluated ".date("m/d/y",$row[dateeval]).")</a>";
      }
      echo "</td></tr>";
   }
   if($obssport=='bb')	//CLINIC
   {
      $sql="SELECT * FROM ".$obssport."clinicobserve WHERE obsid='$obsid' AND dateeval!='' ORDER BY dateeval DESC";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0 && $none2==1)
      {
         echo "<tr align=left><td><br><b>Evaluations you've SUBMITTED this year:</b><br>(You may only view these evaluations.  These evaluations have been submitted to the NSAA.)</td></tr>";
         $none2=0; $none=0;
      }
      while($row=mysql_fetch_array($result))
      {
         if(!($row[offid]==3427 && trim($row[official])!=''))
         {
            $sql2="SELECT first, last FROM officials WHERE id='$row[offid]'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            $offname="$row2[first] $row2[last]";
         }
         else $offname=$row[official];
         $cdate=explode("-",$row[clinicdate]);
         echo "<tr align=left><td>";
         echo "<a class=small target=new href=\"".$obssport2."clinicobserve.php?session=$session&sport=$obssport2&offid=$row[offid]\">$offname:&nbsp;&nbsp;&nbsp;Clinic at $row[location], $cdate[1]/$cdate[2]/$cdate[0]&nbsp;&nbsp;&nbsp;(Evaluated ".date("m/d/y",$row[dateeval]).")</a>";
         echo "</td></tr>";
      }
   } //END IF BB
   if($none==1) echo "<tr align=center><td><br>[You have not filled out any evaluations yet.]</td></tr>";
	//Link to PREVIOUS YEARS
   echo "<tr align=center><td><br><a href=\"obsadmin2.php?sportch=$obssport&session=$session\">View Evaluations Submitted in Previous Years</a></td></tr>";
   if($obssport=='bb')
   {
      echo "<tr align=center><td><br><a href=\"obsadmin2.php?sportch=".$obssport."clinic&session=$session\">View CLINIC Evaluations Submitted in Previous Years</a></td></tr>";
   }
   echo "<tr align=center><td><br><a href=\"offevalreport.php?sport=$obssport&session=$session\" target=\"_blank\">View Report of Year(s) Each Official Has Been Evaluated</a></td></tr>";
   echo "</table></td></tr>";
   if($obssport=='so')
   {
   echo "<tr align=left bgcolor=#E0E0E0><th align=left>&nbsp;&nbsp;<font style=\"color:red\">$cursport</font> Evaluations Submitted by Other Observers:</th></tr>";
   echo "<tr align=center><td><br><a href=\"obslist.php?sport=$obssport&session=$session\">$cursport Evaluations Submitted by Other Observers</a></td></tr>";
   }
   echo "</table>";
}
echo $end_html;
?>