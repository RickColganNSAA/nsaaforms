<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//jwelcome.php: displays welcome page for specified user (split into SP/PP if registered for both)

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

require 'functions.php';
require 'variables.php';

//validate user
if(!ValidUser($session,'judge'))
{
    header("Location:jindex.php?error=1");
    exit();
}

$spreg_abb=array("pp","sp");
$spreg_long=array("Play Production","Speech");

//Figure out what the last year archived was.  Will show those rosters below current ones:
$year=date("Y"); $year1=$year+1; $year0=$year-1;
$archivedb="$db_name2".$year0.$year;
$sql="SHOW DATABASES LIKE '$archivedb'";
$result=mysql_query($sql);
$archive=0;
if(mysql_num_rows($result)==0)
{
    $year00=$year0-1;
    $archivedb="$db_name2".$year00.$year0;
    $curyear="$year0-$year";
    $lastyear="$year00-$year0";
    $sql="SHOW DATABASES LIKE '$archivedb'";
    $result=mysql_query($sql);
    if(mysql_num_rows($result)==0) $archive=0;
    else $archive=1;
}
else
{
    $archive=1;
    $curyear="$year-$year1";
    $lastyear="$year0-$year";
}

//Get user's specifics from logins table using $session
$level=GetLevelJ($session);
$offid=GetJudgeID($session);
$name=GetJudgeName($offid);

$answers=$sport."test_results";
$meeting=$sport."meeting";
$sql_sp="SELECT t1.email, t1.appid,t1.spmeeting, t2.correct FROM judges AS t1, sptest_results AS t2, spapply As t3 WHERE t1.id=t2.offid AND t1.id=t3.offid AND t1.appid!=0 AND t1.id=$offid";
$result_sp=mysql_query($sql_sp);
$row_sp=mysql_fetch_array($result_sp);
if ($row_sp['spmeeting']=='x' && $row_sp['spmeeting']>79 )
    $sp_requirements=1; else $sp_requirements=0;

$sql_pp="SELECT t1.email, t1.appid,t1.ppmeeting, t2.correct FROM judges AS t1, pptest_results AS t2, ppapply As t3 WHERE t1.id=t2.offid AND t1.id=t3.offid AND t1.appid!=0 AND t1.id=$offid";
$result_pp=mysql_query($sql_pp);
$row_pp=mysql_fetch_array($result_pp);
if ($row_pp['spmeeting']=='x' && $row_pp['spmeeting']>79 )
    $pp_requirements=1; else $pp_requirements=0;

echo $init_html;
if($level!=1 && CHANGEPASS==1)
{
    $sql="SELECT t2.changepass FROM sessions as t1, logins_j as t2 WHERE t1.login_id=t2.id AND t1.session_id='$session'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);

    if ($row[changepass]<strtotime ('2018-1-1'))
    {
        header("Location:/nsaaforms/officials/jchangepassword.php?session=$session");
        exit();
    }
}
$header=GetHeaderJ($session,"jwelcome");
echo $header;

//get today's date:
$date=date("l, F j, Y");

if($level==1)	//NSAA user
{
    echo "<br><table width='800px' cellspacing=0 cellpadding=0>";
    echo "<caption><b>Welcome, $name!<br>";
    echo "Today's Date is: $date</b><br><br></caption>";
    echo "<tr align=left><td><font style=\"font-size:9pt;color:blue\"><b>NOTE: Click a tab above to go to that section.</b></td></tr>";
    ?>
    <tr bgcolor=#E0E0E0 align=left>
        <th align=left>&nbsp;&nbsp;Messages:</th>
    </tr>
    <tr align=center><td align=center><br>
            <table>
                <tr align=left><td>
                        <a class=small href="jpost_message.php?session=<?php echo $session; ?>">Post New Message to Judge(s)</a>
                    </td></tr>
                <tr align=left>
                    <td>
                        <a class=small href="jedit_message.php?session=<?php echo $session; ?>">Edit/Delete Messages</a><br><br>
                    </td></tr>
            </table>
        </td></tr>
    <tr bgcolor=#E0E0E0 align=left>
        <th align=left>&nbsp;&nbsp;Downloads:</th>
    </tr>
    <tr align=center><td><br>
            <a class=small href="juploaddoc.php?session=<?php echo $session; ?>">Upload Documents for Judges</a>
            <br><br></td>
    </tr>
    <?php
    echo "</table>";
}
else if($level==2)	//Judges Access
{
    $offid=GetJudgeID($session);

    $sql="SELECT payment,play,speech FROM judges WHERE id='$offid' AND payment!=''";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    if(mysql_num_rows($result)==0)   //HAS NOT PAID THIS YEAR--show special screen with link to CC app
    {
        echo "<br><table width=600><tr align=left><td>";
        echo "<b>Welcome, $name!</b><br><br>";
        echo "You have an account in our system, but you have not yet paid to register as a Speech and/or Play Judge for this year.<br><br>";
        $lockdate=mktime(0,0,0,10,24,2006);
        $opendate=mktime(0,0,0,6,1,2007);
        $now=time();
        if($now>$lockdate && $now<$opendate)	//don't show them link to cc app
            echo "The deadline has passed to registered as a Speech/Play Judge.<br><br>";
        else
        {
            echo "Please complete your application and pay your registration fee online using the <a href=\"https://secure.nsaahome.org/nsaaforms/officials/japplication.php?session=$session\" class=small>Online Judges Application Form</a>, which will be available beginning <u><b>June 1</b></u>.<br><br>";
            //echo "To become a registered judge a person must:<br><ul><li><b>Complete the form below and pay the registration fee</b>.</li><li>COMPLETE ONLINE a <b>Rules Meeting</b>.<br>(Please pay specific attention to the <a href=\"rulesschedule.php?sport=sppp\" target=\"_blank\">rules meeting schedule</a> to avoid additional fees.)</li><li>Score an 80% or higher on the <b>Open Book Test.</b></li></ul><b>PLEASE NOTE: No refunds of registration fees will be made if an individual fails to complete registration.</b><br><br>";
            echo "To become a registered judge a person must:<br><ul><li><b>Complete the form below and pay the registration fee</b>.</li><li>COMPLETE ONLINE a <b>Rules Meeting</b>.<br>(Please pay specific attention to the <a href=\"rulesschedule.php?sport=sppp\" target=\"_blank\">rules meeting schedule</a> to avoid additional fees.)</li><li>Score an 80% or higher on the <b>Open Book Test.</b></li><li>Score an 80% or higher on the <b>Open Book Test.</b></li><li>Complete application to judge District/State form.(If not interested in judging District/State, then please check the \"Not Interested\" box)</li></ul><b>PLEASE NOTE: No refunds of registration fees will be made if an individual fails to complete registration.</b><br><br>";
        }
        echo "Thank You!</td></tr></table>";
        echo $end_html;
        exit();
    }
    $play=$row[play];
    $speech=$row[speech];

    echo "<br><table width=90% cellspacing=0 cellpadding=0>";
    echo "<caption><b><i>Welcome, $name!<br>";
    echo "Today's Date is: $date</b><br><br><br></i></caption>";

    /******ACCOUNT INFORMATION******/

    $sql="SELECT address,city,state,zip,homeph,workph,cellph,email,photofile,photoapproved FROM judges WHERE id='$offid'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    echo "<tr align=center><td><table><caption><h3><u>Your Account Information:</u></h3></caption><tr align=left valign=top>";
    echo "<td width=\"130px\"><b>Profile Picture:</b><br>";
    if($row[photofile]!='' && citgf_file_exists("photos/$row[photofile]"))  //photo exists
    {
        if($row[photoapproved]!='x')   //photo not approved yet
            echo "<div class=normal style=\"width:100px;height:100px;\"><br>Your photo has not been approved by the NSAA yet.<br><br>Please check back later.</div>";
        else   //photo approved; display it
            echo "<img border=0 src=\"photos/$row[photofile]\" width=\"100px\">";
    }
    else      //no photo
        echo "<div class=normal style=\"width:100px;height:100px;\"><br>You have not uploaded a profile picture yet.<br><br><a class=small href=\"jeditinfo.php?s
ession=$session\">Upload Your Profile Picture</a></div>";
    echo "</td><td><table>";
    if($message=="info")              //tell user their info was submitted
    {
        echo "<tr align=center><td colspan=2><font style=\"color:red\">Your contact info has been submitted.  Thank you!</font></td></tr>";
    }
    echo "<tr align=left valign=top><th align=left class=smaller>Address:</th>";
    echo "<td>$row[address]<br>$row[city], $row[state] $row[zip]</td></tr>";
    echo "<tr align=left valign=top><th align=left class=smaller>Phone:</th>";
    echo "<td>";
    if($row[homeph]!="")
        echo "Home Phone: (".substr($row[homeph],0,3).")".substr($row[homeph],3,3)."-".substr($row[homeph],6,4)."<br>";
    if($row[workph]!="")
        echo "Work Phone: (".substr($row[workph],0,3).")".substr($row[workph],3,3)."-".substr($row[workph],6,4)."<br>";
    if($row[cellph]!="")
        echo "Cell Phone: (".substr($row[cellph],0,3).")".substr($row[cellph],3,3)."-".substr($row[cellph],6,4);
    echo "</td></tr>";
    echo "<tr align=left><th class=smaller align=left>E-mail:</th>";
    echo "<td>$row[email]</td></tr>";
    echo "<tr align=center><td colspan=2><a href=\"jeditinfo.php?session=$session\" class=small>Edit Contact Information</a><br><br></td></tr>";
    echo "</table></td></tr>";
    echo "</table><br></td></tr>";


    echo "<tr align=center><td><table cellspacing=5 cellpadding=3 style='width:850px;'>";
    echo "<tr align=center>";
    if($play=='x')
        echo "<td align=center><h2><u>PLAY PRODUCTION:</u></h2></td>";
    if($speech=='x')
        echo "<td align=center><h2><u>SPEECH:</u></h2></td>";
    echo "</tr>";

    /******INBOX: Reminders, Messages & Downloads******/
    //echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;";
    //echo "INBOX: Reminders, Messages & Downloads:&nbsp;</th></tr>";

    echo "<tr align=left bgcolor='#e0e0e0'>";
    if($play=='x')
        echo "<th align=center>INBOX: Reminders, Messages & Downloads:</th>";
    if($speech=='x')
        echo "<th align=center>INBOX: Reminders, Messages & Downloads:</th>";
    echo "</tr>";

    //see if any apps are due soon:
    $sql="SELECT * FROM app_duedates WHERE sport='sp' OR sport='pp'";
    $result=mysql_query($sql);
    $sphtml=""; $pphtml="";
    while($row=mysql_fetch_array($result))
    {
        $cursport=$row[sport];
        if(PastDue($row[duedate],-10) && !PastDue($row[duedate],2))
        {
            if($cursport=="pp") $appname="play";
            else $appname="speech";
            $date=explode("-",$row[duedate]);
            $curhtml="<p>The following applications to officiate are due soon:</p><p><a class='small' href=\"".$appname."app.php?session=$session&sport=$cursport\">".GetSportName($cursport)." Application to Judge</a> (Due $date[1]/$date[2]/$date[0])</p>";
            if($cursport=='sp') $sphtml.=$curhtml;
            else $pphtml.=$curhtml;
        }
    }

    //see if any tests are due soon:
    $sql="SELECT * FROM test_duedates";
    $result=mysql_query($sql);
    $header=0;
    while($row=mysql_fetch_array($result))
    {
        $cursport=$row[test];
        for($i=0;$i<count($spreg_abb);$i++)
        {
            if($cursport==$spreg_abb[$i] && PastDue($row[duedate],-10) && !PastDue($row[duedate],2))
            {
                $curhtml="<p>The $spreg_long[$i] online test is due soon.</p>";

                $duedate=split("-",$row[duedate]);
                $time=mktime(0,0,0,$duedate[1],$duedate[2],$duedate[0]);
                $due_date="$duedate[1]/$duedate[2]/$duedate[0]";
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
                        if($row2[$index]!='')
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
                $curhtml.="<p><a class=small href=\"".$cursport."test.php?session=$session\">$spreg_long[$i] Test</a> (Due $due_date)<br><font style=\"color:$color\">$note</font></p>";
                if($cursport=='sp') $sphtml.=$curhtml;
                else $pphtml.=$curhtml;
            }
        }
    }
    //SHOW REMINDERS:
    if($pphtml!='') $pphtml="<p><b>Reminders:</b></p>".$pphtml;
    if($sphtml!='') $sphtml="<p><b>Reminders:</b></p>".$sphtml;

    if($play=='x' && $pp_requirements==1)
        $pphtml.= '<b>Congratulations, you have completed the requirements to be an NSAA Registered Play Production Judge.<b><br>';
    if($speech=='x' && $sp_requirements==1)
        $sphtml.= '<b>Congratulations!, you have completed the requirements to be an NSAA Registered Speech Judge.<b><br>';

    //Messages:

    $pphtml.="<p><b>Messages:</b></p>";
    $sphtml.="<p><b>Messages:</b></p>";
    if($speech=='x')
    {
        $sql="SELECT DISTINCT(title) FROM messages WHERE sport='sp' AND CURDATE()<=end_date";
        $sql.=" ORDER BY id DESC";
        $result=mysql_query($sql);
        $ct=mysql_num_rows($result);
        if($ct==0)
            $sphtml.="<p>You have no messages from the NSAA.</p>";
        else
        {
            $sphtml.="<p><a class=small href=\"jview_messages.php?session=$session\">You Have $ct";
            if($ct==1) $sphtml.=" Message ";
            else $sphtml.=" Messages ";
            $sphtml.="from the NSAA</a></p>";
        }
    }
    if($play=='x')
    {
        $sql="SELECT DISTINCT(title) FROM messages WHERE sport='pp' AND CURDATE()<=end_date";
        $sql.=" ORDER BY id DESC";
        $result=mysql_query($sql);
        $ct=mysql_num_rows($result);
        if($ct==0)
            $pphtml.="<p>You have no messages from the NSAA.</p>";
        else
        {
            $pphtml.="<p><a class=small href=\"jview_messages.php?session=$session\">You Have $ct";
            if($ct==1) $pphtml.=" Message ";
            else $pphtml.=" Messages ";
            $pphtml.="from the NSAA</a></p>";
        }
    }

    //Downloads:
    $pphtml.="<p><b>Downloads:</b></p>";
    $sphtml.="<p><b>Downloads:</b></p>";
    if($speech=='x')
    {
        $sql="SELECT DISTINCT filename,doctitle FROM downloads_j WHERE (recipients LIKE '%sp%' OR recipients='All') AND active='y' ORDER BY id DESC";
        $result=mysql_query($sql);
        while($row=mysql_fetch_array($result))
        {
            $row[filename]=preg_replace("/(www.)/","",$row[filename]);
            $sphtml.="<li><a class=small href=\"$row[filename]\" target=\"_blank\">$row[doctitle]</a></li>";
        }
        if($sphtml!='') $sphtml="<ul>".$sphtml."</ul>";
    }
    if($play=='x')
    {
        $sql="SELECT DISTINCT filename,doctitle FROM downloads_j WHERE (recipients LIKE '%pp%' OR recipients='All') AND active='y' ORDER BY id DESC";
        $result=mysql_query($sql);
        while($row=mysql_fetch_array($result))
        {
            $row[filename]=preg_replace("/(www.)/","",$row[filename]);
            $pphtml.="<li><a class=small href=\"$row[filename]\" target=\"_blank\">$row[doctitle]</a></li>";
        }
        if($pphtml!='') $pphtml="<ul>".$pphtml."</ul>";
    }

    echo "<tr align=left valign=top>";
    if($play=='x' && $speech=='x')
        echo "<td width='50%'>$pphtml</td><td>$sphtml</td>";
    else if($play=='x')
        echo "<td>$pphtml</td>";
    else if($speech=='x')
        echo "<td>$sphtml</td>";
    echo "</tr>";

    /*** END INBOX SECTION ***/

    /******LINKS & CONTACTS******/
    //echo "<tr align=left><th align=left bgcolor='#E0E0E0'>&nbsp;&nbsp;";
    //echo "Links, Contacts & Rosters:&nbsp;</th></tr>";

    echo "<tr align=center bgcolor='#E0E0E0'>";
    if($play=='x')
        echo "<th>Links, Contacts & Rosters:&nbsp;</th>";
    if($speech=='x')
        echo "<th>Links, Contacts & Rosters:&nbsp;</th>";
    echo "</tr>";
    echo "<tr align=left>";
    if($play=='x')
    {
        echo "<td><p>Debra Velder, Director: <a class=small href=\"mailto:dvelder@nsaahome.org\">dvelder@nsaahome.org</a></p>
		<p>Cindy Callaway, Administrative Assistant: <a class=small href=\"mailto:ccallaway@nsaahome.org\">ccallaway@nsaahome.org</a></p></td>";
    }
    if($speech=='x')
    {
        echo "<td><p>Debra Velder, Director: <a class=small href=\"mailto:dvelder@nsaahome.org\">dvelder@nsaahome.org</a></p>
                <p>Cindy Callaway, Administrative Assistant: <a class=small href=\"mailto:ccallaway@nsaahome.org\">ccallaway@nsaahome.org</a></p></td>";
    }
    echo "</tr>";
    echo "<tr align=left>";
    if($play=='x')
    {
        echo "<td>";
        $sql="SELECT * FROM rosters WHERE sport='pp' AND active='x'";
        $result=mysql_query($sql);
        if($row=mysql_fetch_array($result))
        {
            echo "<p><a href=\"jroster.php?session=$session&list=pp\" target=\"_blank\">$curyear Roster</a></p>";
        }
        else
            echo "<p>The $curyear roster of judges is not available at this time.</p>";
        if($archive==1)
        {
            $sql="SELECT * FROM rosters WHERE sport='pp' AND showold='x'";
            $result=mysql_query($sql);
            if($row=mysql_fetch_array($result))
            {
                echo "<p><a href=\"jroster.php?session=$session&list=pp&archive=$archivedb\" target=\"_blank\">$lastyear Roster</a></p>";
            }
        }
        echo "</td>";
    }
    if($speech=='x')
    {
        echo "<td>";
        $sql="SELECT * FROM rosters WHERE sport='sp' AND active='x'";
        $result=mysql_query($sql);
        if($row=mysql_fetch_array($result))
        {
            echo "<p><a href=\"jroster.php?session=$session&list=sp\" target=\"_blank\">$curyear Roster</a></p>";
        }
        else
            echo "<p>The $curyear roster of judges is not available at this time.</p>";
        if($archive==1)
        {
            $sql="SELECT * FROM rosters WHERE sport='sp' AND showold='x'";
            $result=mysql_query($sql);
            if($row=mysql_fetch_array($result))
            {
                echo "<p><a href=\"jroster.php?session=$session&list=sp&archive=$archivedb\" target=\"_blank\">$lastyear Roster</a></p>";
            }
        }
        echo "</td>";
    }
    echo "</tr>";

    /******ONLINE RULES MEETING******/
    //echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;";
    //echo "Online Rules Meetings:&nbsp;</th></tr>";

    echo "<tr align=center bgcolor='#E0E0E0'>";
    if($play=='x')
        echo "<th>Online Rules Meetings:&nbsp;</th>";
    if($speech=='x')
        echo "<th>Online Rules Meetings:&nbsp;</th>";
    echo "</tr>";

    $pphtml=""; $sphtml="";
    for($i=0;$i<count($spreg_abb);$i++)
    {
        $cursp=$spreg_abb[$i];
        $sql2="SELECT * FROM rulesmeetingdates WHERE sport='$cursp'";
        $result2=mysql_query($sql2);
        $row2=mysql_fetch_array($result2);
        $ppfile=$row2[ppfile];
        $fee=$row2[fee]; $latefee=$row2[latefee];
        $startdate=$row2[startdate]; $latedate=$row2[latedate]; $enddate=$row2[enddate]; $paydate=$row2[paydate];
        $late=split("-",$latedate); $end=split("-",$enddate); $pay=split("-",$paydate);
        $start=split("-",$startdate); $year=$start[0]; $month=$start[1];
        $sql2="SELECT ".$cursp."meeting FROM judges WHERE id='$offid'";
        $result2=mysql_query($sql2);
        $row2=mysql_fetch_array($result2);
        $currm=$row2[0];
        $sql2="SELECT * FROM ".$cursp."rulesmeetings WHERE offid='$offid'";
        $result2=mysql_query($sql2);
        $row2=mysql_fetch_array($result2);
        $sportname=$spreg_long[$i];
        $html="";
//echo $sportname.": ".$currm."<br>";
        if($currm=='x') //SCENARIO #1: Already Attended a Rules Meeting for This Sport
        {
            $html.="<p>You have already attended a $sportname Rules Meeting and your attendance has been recorded in our system.</p>";
            if($ppfile!='')
                $html.="<p><a class=small href=\"$ppfile\" target=\"_blank\">Click HERE to Re-Watch the $sportname Rules Meeting Presentation</a></p>";
        }
        else if($startdate=="0000-00-00")
        {
            $html.="<p>The Online $sportname Rules Meeting will be available during a time period to be announced at a later date.</p>";
        }
        else if($offid!='598' && !PastDue($startdate,-1)) //SCENARIO #2: NOT YET AVAILABLE
        {
            if(IsNewJudge($offid))	//NO FEE
                $html.="<p>The Online $sportname Rules Meeting will be available for <b>NO CHARGE</b>, since you are a NEW JUDGE, from ".date("F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0]))." through ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).", at midnight.</p>";
            else
                $html.="<p>The Online $sportname Rules Meeting will be available for <b>NO CHARGE</b> from ".date("F j, Y",mktime(0,0,0,$start[1],$start[2],$start[0]))." until ".date("F j, Y",mktime(0,0,0,$pay[1],$pay[2],$pay[0]))." at midnight, after which the fee will be <b>$".number_format($fee,2,'.','')."</b> until ".date("F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0]))." at midnight, after which the fee will be increased to <b>$".number_format($latefee,2,'.','')."</b>.  The rules meeting will not be available online after ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).".</p>";
        }
        else if(!PastDue($latedate,0))   //SCENARIO #3: AVAILABLE, NO LATE FEE YET
        {
            $html.="<p>The Online $sportname Rules Meeting will be available for ";
            if(!PastDue($paydate,0)) $html.="<b>NO CHARGE</b> until ".date("F j, Y",mktime(0,0,0,$pay[1],$pay[2],$pay[0]))." at midnight, after which it will be available for ";
            $html.="<b>$".number_format($fee,2,'.','')."</b> until ".date("F j, Y",mktime(0,0,0,$late[1],$late[2],$late[0]))." at midnight, after which the fee will be increased to <b>$".number_format($latefee,2,'.','')."</b>.  The rules meeting will not be available online after ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).".</p>";
            if($row2[datecompleted]>0 && $row2[datepaid]==0)      //COMPLETED BUT NOT PAID
            {
                if($row2[datecompleted]<=mktime(23,59,59,$pay[1],$pay[2],$pay[0]))
                {
                    $html.="<div class=alert style=\"width:400px;\"><p>You <b>watched</b> this rules meeting video but <u><b>did NOT verify your attendance</b></u>.</p>";
                    $html.="<p><a class=small href=\"rulesmeetingpay.php?session=$session&sport=$cursp\">Click HERE to Complete Verification for this Rules Meeting</a></p></div>";
                    $html.="<p>[You MUST complete verification to be marked as having attended a $regyr $sportname Rules Meeting.]</p>";
                }
                else
                {
                    $html.="<div class=alert style=\"width:400px;\"><p>You <b>watched</b> this rules meeting video but <b><u>did NOT pay the fee</b></u>.</p>";
                    $html.="<p><a class=small href=\"rulesmeetingpay.php?session=$session&sport=$cursp\">Click HERE to Complete Payment for this Rules Meeting</a></p></div>";
                    $html.="<p>[You MUST complete payment to be marked as having attended a $regyr $sportname Rules Meeting.]</p>";
                }
            }
            else if($row2[initiated]>0 && $row2[datecompleted]==0)        //STARTED WATCHING BUT DIDN'T FINISH
            {
                $html.="<p>You <b>started watching</b> but <b>did NOT finish</b> the $sportname Rules Meeting Video.</p>";
                $html.="<p><a class=small href=\"rulesmeetingintro.php?session=$session&sport=$cursp\">Click HERE to Watch the $regyr $sportname Rules Meeting Video</a></p>";
            }
            else          //DID NOT START THE PROCESS YET
            {
                $html.="<p><a class=small href=\"rulesmeetingintro.php?session=$session&sport=$cursp\">Click HERE to Watch the $regyr $sportname Rules Meeting Video</a></p>";
            }
        }
        else if(!PastDue($enddate,0))    //SCENARIO #4: AVAILABLE FOR A LATE FEE
        {
            $html.="<p>The Online $sportname Rules Meeting will be available for the late fee of <b>$".number_format($latefee,2,'.','')."</b> until ".date("F j, Y",mktime(0,0,0,$end[1],$end[2],$end[0])).", after which it will no longer be available.</p>";
            if($row2[datecompleted]>0 && $row2[datepaid]==0)      //COMPLETED BUT NOT PAID
            {
                $html.="<p>You <b>watched</b> this rules meeting video but <b><u>did NOT pay</b></u>.</p>";
                $html.="<p><a class=small href=\"rulesmeetingpay.php?session=$session&sport=$cursp\">Click HERE to Complete Payment for this Rules Meeting</a></p>";
                $html.="<p>[You MUST complete payment to be marked as having attended a $regyr $sportname Rules Meeting.]</p>";
            }
            else if($row2[initiated]>0 && $row2[datecompleted]==0)        //STARTED WATCHING BUT DIDN'T FINISH
            {
                $html.="<p>You <b>started watching</b> but <b>did NOT finish</b> the $sportname Rules Meeting Video.</p>";
                $html.="<p><a class=small href=\"rulesmeetingintro.php?session=$session&sport=$cursp\">Click HERE to Watch the $regyr $sportname Rules Meeting Video</a></p>";
            }
            else          //DID NOT START THE PROCESS YET
            {
                $html.="<p><a class=small href=\"rulesmeetingintro.php?session=$session&sport=$cursp\">Click HERE to Watch the $regyr $sportname Rules Meeting Video</a></p>";
            }
        }
        else                     //SCENARIO #5: NO LONGER AVAILABLE
        {
            $html.="<p>This rules meeting is no longer available online.</p>";
        }
        if($cursp=='sp') $sphtml.=$html;
        else $pphtml.=$html;
    }	//END FOR EACH of SP and PP

    echo "<tr align=left>";
    if($play=='x')
        echo "<td width='350px'>$pphtml</td>";
    if($speech=='x')
        echo "<td width='350px'>$sphtml</td>";
    echo "</tr>";


    /******ONLINE TESTS******/
    //echo "<tr align=left><th align=left bgcolor='#E0E0E0'>&nbsp;&nbsp;";
    //echo "Online Tests:&nbsp;</th></tr>";

    echo "<tr align=center bgcolor='#E0E0E0'>";
    if($play=='x')
        echo "<th>Online Tests:</th>";
    if($speech=='x')
        echo "<th>Online Test:</th>";
    echo "</tr>";

    $pphtml=""; $sphtml="";
    for($i=0;$i<count($spreg_abb);$i++)
    {
        $cursp=$spreg_abb[$i]; $html="";
        //get test due date
        $sql="SELECT duedate FROM test_duedates WHERE test='$cursp'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        $date=split("-",$row[0]);
        $testduedate=$row[0];
        if(!PastDue($row[0],0))
            $html.="<p><b>".$spreg_long[$i]." Tests are due: <u>".date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]))."</b></u></p>";
        $duedate=mktime(0,0,0,$date[1],$date[2],$date[0]);
        $duedate+=24*60*60;	//midnight on due date
        $showdate=$duedate+=3*24*60*60;	//4 days after due date (12:01 am): show test results
        $now=time();
        //if not past due date, show links to tests (if not yet taken)
        //else, if not yet 4 days after, say "Check back soon"
        //else if 4 days or more after, show test results link
        $sql="SELECT * FROM ".$cursp."test_results WHERE offid='$offid' AND datetaken!=''";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        if(mysql_num_rows($result)==0 && ($offid==598 || !PastDue($testduedate,0)))	//CAN STILL TAKE THE TEST (HAVENT YET)
            $html.="<p><a href=\"".$cursp."test.php?session=$session\">$spreg_long[$i] Test</a></p>";
        else if(mysql_num_rows($result)==0 && !PastDue($testduedate,90))	//TEST PAST DUE
            $html.="<p>The $spreg_long[$i] Test was due on $date[1]/$date[2]/$date[0].</p>";
        else if(mysql_num_rows($result)==0)	//TEST NOT AVAILABLE
            $html.="<p>The $spreg_long[$i] Test is not available at this time.</p>";
        else	//JUDGE TOOK THIS TEST:
        {
            if($now<=$showdate)
            {
                $html.="<p>You have completed the $spreg_long[$i] Test. Please check back about 4 days after the due date ($date[1]/$date[2]/$date[0]) for your test results.</p>";
            }
            else
            {
                $sql2="SELECT * FROM ".$cursp."test";
                $result2=mysql_query($sql2);
                $total=mysql_num_rows($result2);
                $correct=$row[correct];
                $grade=number_format(($correct/$total)*100,0,'.','');
                $html.="<p>You scored <b>".$grade."%</b> on the $spreg_long[$i] Test.</p><p>";
                $html.="<a href=\"jviewtest.php?session=$session&sport=$cursp\" target=new>View Your $spreg_long[$i] Test Results</a></p>";
            }
        }
        if($cursp=="sp") $sphtml.=$html;
        else $pphtml.=$html;
    }	//FOR EACH of SP AND PP

    echo "<tr align=left>";
    if($play=='x')
        echo "<td>$pphtml</td>";
    if($speech=='x')
        echo "<td>$sphtml</td>";
    echo "</tr>";

    /******CONTRACTS TO JUDGE******/
    //echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;";
    //echo "Contracts to Judge Districts & State:&nbsp;</th></tr>";

    echo "<tr align=center bgcolor='#E0E0E0'>";
    if($play=='x')
        echo "<th>Contracts to Judge Districts & State:&nbsp;</th>";
    if($speech=='x')
        echo "<th>Contracts to Judge Districts & State:&nbsp;</th>";
    echo "</tr>";

    $hascontract=0;
    for($i=0;$i<count($spreg_abb);$i++)
    {
        $html="";
        $cursp=$spreg_abb[$i];
        $contracts=$cursp."contracts";
        $districts=$cursp."districts";
        $sportname=GetSportName($cursp);
        $sql="SELECT t1.distid,t2.type,t2.class,t2.district,t2.dates";
        $sql.=" FROM $contracts AS t1, $districts AS t2 WHERE t1.offid='$offid' AND t1.post='y' AND t1.accept='' AND t1.distid=t2.id ORDER BY t2.type,t2.class,t2.district";
        if($cursp=='sp') $sql.=",t2.dates ASC";
        $result=mysql_query($sql);
        if(mysql_num_rows($result)>0)
            $html.="<p><b>$sportname Contracts You Have NOT Responded To:</b></p>";
        while($row=mysql_fetch_array($result))
        {
            $hascontract=1;
            $date=split("-",$row[dates]);
            $html.="<p><a class=small href=\"playcontract.php?session=$session&sport=$cursp&distid=$row[distid]\" target=\"_blank\">";
            if($row[type]!='State') $html.="$row[type] $row[class]-$row[district] ($date[1]/$date[2])";
            else if($cursp=='pp') $html.="Class $row[class] State ($date[1]/$date[2]/$date[0])";
            else $html.="State Speech Contest - ".date("l",strtotime($row[dates]));
            $html.="</a></p>";
        }
        $sql="SELECT t1.distid,t2.type,t2.class,t2.district,t2.dates,";
        $sql.="t1.accept,t1.confirm FROM $contracts AS t1, $districts AS t2 WHERE t1.offid='$offid' AND t1.post='y' AND t1.accept!='' AND t1.distid=t2.id";
        $result=mysql_query($sql);
        if(mysql_num_rows($result)>0)
            $html.="<p><b>$sportname Contracts You HAVE Responded To:</b></p>";
        while($row=mysql_fetch_array($result))
        {
            $hascontract=1;
            $date=split("-",$row[dates]);
            $html.="<p><a class=small href=\"playcontract.php?session=$session&sport=$cursp&distid=$row[distid]\" target=\"_blank\">";
            if($row[type]!='State') $html.="$row[type] $row[class]-$row[district] ($date[1]/$date[2])</a>&nbsp;";
            else if($cursp=='pp') $html.="Class $row[class] State ($row[dates])</a>&nbsp;";
            else $html.="State Speech Contest - ".date("l",strtotime($row[dates]))."</a>&nbsp;";
            if($row[accept]=='y') $html.="[Accepted]&nbsp;";
            else $html.="[Declined]&nbsp;";
            if($row[confirm]=='y') $html.="[NSAA-Confirmed]&nbsp;";
            else if($row[confirm]=='n') $html.="[NSAA-Rejected]&nbsp;";
            else $html.="[NSAA: Not Responded Yet]&nbsp;";
            $html.="</p>";
        }
        if($html=="") $html.="<p>[You currently have no contracts to judge $spreg_long[$i].]</p>";
        if($cursp=='sp') $sphtml=$html;
        else $pphtml=$html;
    }	//END FOR EACH SPORT

    echo "<tr align=left>";
    if($play=='x')
        echo "<td>$pphtml</td>";
    if($speech=='x')
        echo "<td>$sphtml</td>";
    echo "</tr>";

    /******APPLICATIONS TO JUDGE******/
    //echo "<tr align=left><th align=left bgcolor=#E0E0E0>&nbsp;&nbsp;";
    //echo "Applications to Judge Districts & State:&nbsp;</th></tr>";

    echo "<tr align=center bgcolor='#E0E0E0'>";
    if($play=='x')
        echo "<th>Applications to Judge Districts & State:&nbsp;</th>";
    if($speech=='x')
        echo "<th>Applications to Judge Districts & State:&nbsp;</th>";
    echo "</tr>";

    echo "<tr align=center>";
    if($play=='x')
        echo "<td><p><a href=\"playapp.php?session=$session\">Application to Judge PLAY PRODUCTION</a></p></td>";
    if($speech=='x')
        echo "<td><p><a href=\"speechapp.php?session=$session\">Application to Judge SPEECH</a></p></td>";
    echo "</tr>";

    echo "</table></td></tr>";	//END TABLE OF TWO COLUMNS (ONE FOR PLAY, ONE FOR SPEECH)
}//end if level=2

echo $end_html;
?>
