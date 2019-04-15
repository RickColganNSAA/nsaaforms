<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if($level==4) $level=1;

if(!$sport || $sport=='')
{
   echo "No sport given";
   exit();
}
$ruleshosts=$sport."ruleshosts";
$sportname=GetSportName($sport);

echo $init_html;
echo GetHeader($session,"contractadmin");
echo "<br><br>$sportname Rules Meeting Reminder E-mails:";
echo "<table width=500><tr align=left><td><hr><br>";

//get info on this rules meeting:
$sql="SELECT * FROM $ruleshosts";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $html="";
$type=$row[type];
$origsiteid=$row[origsiteid];
$hostname=$row[hostname];
$date=split("-",$row[mtgdate]);
$year1=$date[0];
if($date[1]<=6) $year1--;
$year2=$year1+1;
$mtgtime=$row[mtgtime];
$location=$row[location];
$contactname=$row[contactname]; $contacttitle=$row[contacttitle];
$contactphone=$row[contactphone];
$equipment=$row[equipment];
$post=$row[post]; $accept=$row[accept]; $confirm=$row[confirm];

//get info on the host:
$hostname2=addslashes($hostname);
$sql2="SELECT * FROM $db_name.logins WHERE school='$hostname2' AND (level='2' OR level='4' OR level='6')";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$hostlevel=$row2[level];
$name=$row2[name];
$email=$row2[email];
if($hostlevel==2 && $name=='')
{
   $sql3="SELECT * FROM $db_name.logins WHERE school='$hostname2' AND sport='Activities Director'";
   $result3=mysql_query($sql3);
   $row3=mysql_fetch_array($result3);
   $name=$row3[name];
}
if($hostlevel=='4' || $hostlevel=='6')
   $sql2="SELECT * FROM $db_name.logins WHERE school='$hostname2'";
else
   $sql2="SELECT * FROM $db_name.headers WHERE school='$hostname2'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$address=$row2[address1];
if($row2[address2]!='') $address.="<br>$row2[address2]";
$city_state=$row2[city_state]; $zip=$row2[zip];

$html.="NEBRASKA SCHOOL ACTIVITIES ASSOCIATION<br>";
$html.="8230 Beechwood Drive, P.O. Box 5447<br>";
$html.="Lincoln, Nebraska  68505-0447<br>(402) 489-0386<br><br>";
$html.="TO:  ";
$html.="$hostname<br>";
$html.="FROM:  Larry Mollring, Assistant Director<br>";
$html.="SUBJECT:  $year1-$year2 $sportname Rules Meeting Reminder<br>";
$html.="DATE:  ".date("F j, Y")."<br><br>";
$html.="This is a reminder of the rules meeting scheduled ar your school/service unit:<br>";
$html.="Meeting:  $sportname<br>";
$html.="Date:  ".date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]))."<br>";
$html.="Location:  $location<br>";
$html.="Starting Time:  $mtgtime<br>";
if($type=="Receiving")
{
   $html.="HOST'S CHECKLIST:<br>";
   $html.="1) The meeting should start promptly and last around an hour.<br>";
   $html.="2) The distance learning lab should be open and staffed approximately 1/2 hour before the scheduled starting time.<br>";
   $html.="3) The connection with the originating site should be up and running approximately 15 minutes before the starting time.<br>";
   $html.="Besides having the facility ready for this meeting, we would appreciate any publicity you could give this meeting in your area prior to the meeting date.<br>";
   $html.="4) Please post signs directing coaches and officials to the meeting room.<br>";
   $html.="5) Keep accurate account of head coaches and officials on the sign-up sheets.  Return sign-up sheets and all extra handouts IMMEDIATELY (the next day) in the postage paid container.<br>";
   $html.="<br>The NSAA appreciates your assistance in hosting this rules meeting.<br>";
   $html.="<br><a href=\"https://secure.nsaahome.org/nsaaforms/officials/rulesschedule.php?sport=$sport\">$year1-$year2 NSAA $sportname Rules Meeting Schedule</a>";
}
else if($type=="Originating")
{
   $html.="The basic needs for the meeting will be a room large enough to accommodate the expected attendance, equipped with a screen. All presentations will be PowerPoint presentations. The NSAA will provide the equipment necessary for this.<br>";
   $html.="If your site is the originating site for distance learning, arrangements will need to be made with your distance learning coordinator for the receiving sites to be up and running for the $mtgtime meeting.<br>";
   $html.="The meeting will start promptly and and should last about 1 1/2 hours.<br>";
   $html.="The interpreter will arrive about one-half hour before the scheduled starting time.<br>";
   $html.="Besides having the facility ready for this meeting, we would appreciate any publicity you could give this meeting in your area prior to the meeting date.<br>";
   $html.="<br><a href=\"https://secure.nsaahome.org/nsaaforms/officials/rulesschedule.php?sport=$sport\">$year1-$year2 NSAA $sportname Rules Meeting Schedule</a>";
}
else	//Regular
{
   $html.="The basic needs for Basketball, Wrestling, and Swimming meetings will be a room large enough to accommodate the expected attendance, equipped with a screen. All presentations will be PowerPoint presentations. The NSAA will provide the equipment necessary for this.<br>";
   $html.="The meeting will start promptly and and should last about 1 1/2 hours.<br>";
   $html.="The interpreter will arrive about one-half hour before the scheduled starting time.<br>";
   $html.="Besides having the facility ready for this meeting, we would appreciate any publicity you could give this meeting in your area prior to the meeting date.<br>";
   $html.="<br><a href=\"https://secure.nsaahome.org/nsaaforms/officials/rulesschedule.php?sport=$sport\">$year1-$year2 NSAA $sportname Rules Meeting Schedule</a>";
}

$text=ereg_replace("<br>","\r\n",$html);
$attm=array();
$html2=$email."<br><br>".$html;
$email=trim($email);
if($email!='')
{
   $subject="$year1-$year2 $sportname Rules Meeting Reminder";
    if(SendMail("nsaa@nsaahome.org","NSAA",$email,$ToName,$Subject,$html,$html,$attm)){
	   
					writefile('sendemailsoutput.html', "Sent to $email\r\n"."DONE!");
			 }
			   else writefile('sendemailsoutput.html', "Could not send to $email\r\n"."DONE!");
		
   $html=ereg_replace("\'","`",$html);
   //citgf_exec("/usr/local/bin/php sendemail.php '$session' '$email' '$email' '$subject' '$html' '$attm' > sendemailsoutput.html 2>&1 &");
   echo "Sending to $email...($type)<br>";
}
else
{
   echo "No e-mail on record for $hostname<br>";
}
}//end for each host
echo "<br>DONE!<br><br>";
echo "<a href=\"rulescontracts.php?session=$session&sport=$sport\">$sportname Rules Meeting Hosts MAIN MENU</a></td></tr></table>";
echo $end_html;

exit();
?>
