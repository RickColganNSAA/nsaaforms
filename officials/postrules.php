<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   if($sport=='pp' || $sport=='sp')
      header("Location:jindex.php?error=1");
   else
      header("Location:index.php?error=1");
   exit();
}

$ruleshosts=$sport."ruleshosts";

if($siteid!='all')
   $sql="SELECT * FROM $ruleshosts WHERE id='$siteid'";
else
   $sql="SELECT * FROM $ruleshosts WHERE hostname!=''";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $curpost=$row[post];
   $sql2="UPDATE $ruleshosts SET post='y' WHERE id='$row[id]'";
   $result2=mysql_query($sql2);
   if($curpost!='y')
   {
      $hostname2=addslashes($row[hostname]); $hostname=$row[hostname];
      $type=$row[type];
      $date=split("-",$row[mtgdate]);
      if($type=="Originating") $type="an ".$type;
      else $type="a ".$type;
      $sql2="SELECT name,email FROM $db_name.logins WHERE school='$hostname2' AND (level='2' OR level='4' OR level='6')";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if($row2[level]=='2' && $row2[email]=='')
      {
         $sql2="SELECT name,email FROM $db_name.logins WHERE school='$hostname2' AND sport='Activities Director'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
      }
      if($row2[email]!='')
      {
         $From="nsaa@nsaahome.org";
         $FromName="NSAA";
         $To=$row2[email];
         $ToName=$row2[name];
         $Subject="$hostname has been selected to host a ".GetSportName($sport)." Rules Meeting";
         $Text="$hostname has been selected to be $type Site for ".GetSportName($sport)." Rules Meetings on $date[1]/$date[2]/$date[0].\r\n\r\nPlease login at https://secure.nsaahome.org/nsaaforms/index.php to view and respond to the contract.\r\n\r\nThank You!";
         $Html="$hostname has been selected to be $type Site for ".GetSportName($sport)." Rules Meetings on $date[1]/$date[2]/$date[0].<br><br>Please login at <a href=\"https://secure.nsaahome.org/nsaaforms/index.php\">https://secure.nsaahome.org/nsaaforms/index.php</a> to view and respond to the contract.<br><br>Thank You!";
         $Attm=array();
		 if(SendMail("nsaa@nsaahome.org","NSAA",$To,$ToName,$Subject,$Html,$Html,$Attm)){
	   
					writefile('sendemailsoutput.html', "Sent to $To\r\n"."DONE!");
			 }
			   else writefile('sendemailsoutput.html', "Could not send to $To\r\n"."DONE!");
		
	 //citgf_exec("/usr/local/bin/php sendemail.php '$session' '$To' '$ToName' '$Subject' '$Html' '$Attm' > sendemailsoutput.html 2>&1 &");
      }
   }//end if curpost!='y'
}
if($siteid!='all')
   header("Location:ruleshostbyhost.php?sport=$sport&posted=yes&email=$row2[email]&session=$session&siteid=$siteid");
else
   header("Location:ruleshostreport.php?sport=$sport&posted=yes&session=$session");

exit();
?>
