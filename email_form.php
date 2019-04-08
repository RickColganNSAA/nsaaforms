<?php 
require 'functions.php';
require 'variables.php';

$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

$sch=ereg_replace(" ","",$school);
$sch=ereg_replace("-","",$sch);
$sch=ereg_replace("\.","",$sch);
$sch=ereg_replace("\'","",$sch);
$sch=strtolower($sch);
$activ_lower=strtolower($activ);
$activ_lower=ereg_replace(" ","",$activ_lower);
$activ_lower=ereg_replace("&","",$activ_lower);

if($state==1)
{
   $html_name="/home/nsaahome/attachments/$sch$activ_lower";
   $html_name.="state.html";
   $csv_name="/home/nsaahome/attachments/$sch$activ_lower";
   $csv_name.="state.csv";
}
else
{
   $html_name="/home/nsaahome/attachments/$sch$activ_lower.html";
   $csv_name="/home/nsaahome/attachments/$sch$activ_lower.csv";
}
/*
if($reply) 
{
   $From=$reply;
}
else $From="nsaa@nsaahome.org";
*/
$From="nsaa@nsaahome.org";
//$FromName=$From;
$FromName="NSAA";
$To=split(",",$email);
$ToName=split(",",$email);
$Html="<html><b>";
$Text="";
if(trim($comments)!="")
{
   $Html.=$comments."<br>---------------------------------<br>"; $Text.=$comments."\r\n-----------------------------\r\n";
}
if($state==1)
{
   $tourn="State";
}
else
{
   $tourn="District";
}
if($fb==1)	//Football Stats From
{
   $Subject="$school $activ Statistics Form";
   $Text.="The $activ Statistics form for $school is attached as both a .csv file and as a .html file";
   $type="Statistics";
}
else
{
   $Subject="$school $activ Entry Form";
   $Text.="The $tourn $activ entry form for $school is attached as both a .csv file and as a .html file.";
   $type="Entry";
}
if($fb==2)
{
   $Html.="The Football Playoff Roster for $school is attached in .html format<br><br>Thank you!</b>";
}
else
{
   $Html.="The $tourn $activ $type form for $school is attached in 2 formats:<br>";
   $Html.="<br>Comma-Separated (.csv) and .html<br><br>Thank you!</b>";
}
if($fb==2)	//state fb form
{
   $AttmFiles=$html_name;
}
else if(ereg("Track",$activ))
{
   $csv_name=ereg_replace(".csv",".txt",$csv_name);
   $Html=ereg_replace("Comma-Separated","Text",$Html);
   $Html=ereg_replace(".csv",".txt",$Html);
   $AttmFiles="$teamlist.txt,$teamlist.html,$csv_name,$html_name";
   $Html.="<br><br><b>$school's district roster for Track & Field is also attached in 2 formats (.CSV and .HTML).</b>";
}
else $AttmFiles="$csv_name,$html_name";

if($district)	//state qualifiers
{
   $district=ereg_replace("-","",$district);
   $filename="/home/nsaahome/attachments/";
   if(ereg("Cross",$activ)) $filename.="cc";
   else if(ereg("Speech",$activ)) $filename.="sp";
   else if(ereg("Track",$activ))
   {
      $filename.="tr";
      if(ereg("Boys",$activ)) $filename.="boys";
      else $filename.="girls";
   }
   $filename.="state";
   $filename.=$district;
   $filename.=".html";
   $Subject="$class_dist $activ State Qualifiers";
   $Text="The state qualifiers for the $activ $class_dist district are listed in the attachment.  Thank you!";
   $Html="<font style=\"font-family:Arial; font-size:10pt;\">The state qualifiers for the $activ $class_dist district are listed in the attachment.<br><br>Thank you!</font>";
   $AttmFiles=$filename;
}
if(ereg("Swimming",$activ) && !$hytekabbr)
{
   $Subject="$school $activ Verification Form";
   $Html="The $activ Verification Form for $school is attached in .html format.<br><br>Thank You!";
   $Text=ereg_replace("<br>","\r\n",$Html);
   $AttmFiles="/home/nsaahome/attachments/$swfile";
}
else if(ereg("Swimming",$activ))
{ 
   $sql="SELECT school FROM swschool WHERE hytekabbr='$hytekabbr'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $Subject="$row[0] State $activ Entry Form";
   $Html="The $row[0] State $activ Entry Form is attached in .html format.<br><br>Thank You!";
   $Text=ereg_replace("<br>","\r\n",$Html);
   $AttmFiles="/home/nsaahome/attachments/$swfile";
}

for($i=0;$i<count($To);$i++)
{
   $To[$i]=trim($To[$i]);
   $cur_to=$To[$i];
   $Subject=ereg_replace("\'","`",$Subject);
   $Html=ereg_replace("\'","`",$Html);
   //citgf_exec("/usr/local/bin/php sendemail.php '$session' '$cur_to' '$cur_to' '$Subject' '$Html' '$AttmFiles' > sendemailsoutput.html 2>&1 &");
   
   if(SendMail("nsaa@nsaahome.org","NSAA",$cur_to,$cur_to,$Subject,$Html,$Html,$AttmFiles)){
	   
	   writefile('sendemailsoutput.html', "Sent to $cur_to\r\n"."DONE!");
   }
   else writefile('sendemailsoutput.html', "Could not send to $cur_to\r\n"."DONE!");
			
	
   //SendMail("nsaa@nsaahome.org","NSAA",$cur_to,$cur_to,$Subject,$Html,$Html,$AttmFiles);
}

?>
<script language="javascript">
window.close();
</script>
<html>
<body onLoad="window.close();">
<head>
<title>NSAA Home</title>
<link rel=stylesheet href="/css/nsaaforms.css" type="text/css">
</head>
<table width=100%><tr align=center><td><br><br>
Your message has been sent to <?php echo $i; ?> recipients.  If this window does not close automatically, you may do so yourself by clicking the 'X' in the upper right- or left- hand corner of this window.
</td></tr></table>
</body>
</html>
