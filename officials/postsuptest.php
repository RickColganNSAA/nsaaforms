<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$suptesthosts="suptesthosts";

if($siteid!='all')
   $sql="SELECT * FROM $suptesthosts WHERE id='$siteid'";
else
   $sql="SELECT * FROM $suptesthosts WHERE hostname!=''";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $curpost=$row[post];
   $sql2="UPDATE $suptesthosts SET post='y' WHERE id='$row[id]'";
   $result2=mysql_query($sql2);
   if($curpost!='y')	//newly posted to this host, send e-mail
   {
      $hostname2=addslashes($row[hostname]); $hostname=$row[hostname];
      $date=split("-",$row[mtgdate]);
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
         $Subject="$hostname has been selected to host a Supervised Test";
         $Text="$hostname has been selected to be a site for a Supervised Test on $date[1]/$date[2]/$date[0].\r\n\r\nPlease login at https://secure.nsaahome.org/nsaaforms/index.php to view and respond to the contract.\r\n\r\nThank You!";
         $Html="$hostname has been selected to be a site for a Supervised Test on $date[1]/$date[2]/$date[0].<br><br>Please login at <a href=\"https://secure.nsaahome.org/nsaaforms/index.php\">https://secure.nsaahome.org/nsaaforms/index.php</a> to view and respond to the contract.<br><br>Thank You!";
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
   header("Location:suptesthostbyhost.php?posted=yes&email=$row2[email]&session=$session&siteid=$siteid");
else
   header("Location:suptesthostreport.php?posted=yes&session=$session");

exit();
?>
