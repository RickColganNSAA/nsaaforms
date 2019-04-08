<?php
//tr_state_view_g.php: Track & Field Dist Results Form (State Qualifiers)
//require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php';
require '../functions.php';
require '../variables.php';
require 'trfunctions.php';

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if($school_ch && GetLevel($session))
{
   $school=$school_ch;
}
else
{
   $school=GetSchool($session);
}
$school2=ereg_replace("\'","\'",$school);
if(!ValidUser($session))
{
   header("Location:/nsaaforms/index.php?error=1");
   exit();
}
$level=GetLevel($session);

$db1="nsaascores";
$db2="nsaaofficials";

$sql="SELECT * FROM $db2.trgdistricts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class=$row['class']; 
$district=$row[district];

//get coach
$sql="SELECT name,asst_coaches FROM $db1.logins WHERE level='3' AND sport='Girls Track & Field' AND school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$coach=$row[0]; $asst=$row[1];

echo $init_html;
if($print!=1)
   echo GetHeader($session);

$string=$init_html;
$idst="";	//to be written to dst files
$tdst="";	//(indy and team)
if($print!=1)
{
   echo "<br>";
   if(GetLevel($session)==1)      
      echo "<a href=\"stateadmin.php?session=$session\" class=small>Return to Track & Field District Results MAIN MENU</a>&nbsp;&nbsp;&nbsp;";
   echo "<a class=small href=\"tr_state_edit_g.php?session=$session&school_ch=$school_ch&distid=$distid\">Edit this Form</a>&nbsp;&nbsp;&nbsp;";
   echo "<a class=small href=\"tr_state_view_g.php?session=$session&school_ch=$school_ch&distid=$distid&print=1\" target=new>Printer-Friendly Version</a>";
   if($final=='y')
   {
      echo "<br><br><font style=\"font-size:9pt;color:red\"><b>Your results have been sent to the NSAA.</b></font>";
   }
}
$info="<br><br><table frame=all rules=all style=\"border:#808080 1px solid;\" cellspacing=0 cellpadding=4><caption><b>
DISTRICT TRACK & FIELD RESULTS<BR></b>
";

$sql="SELECT * FROM $db2.trgdistricts WHERE class='$class' AND district='$district'";	//trbdistricts = MASTER TABLE
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($print!=1)
{
   $info.="<div class='alert'>";
   if($row[resultssub_g]!='')	//submitted
   {
      $info.="You submitted the District $row[class]-$row[district] Girls Track & Field Results on <b>".date("m/d/y",$row[resultssub_g])." at ".date("g:ia",$row[resultssub_g])."</b>.<br>";
      $info.="If you need to make changes, <b>PLEASE CONTACT THE NSAA</b>.";
      $submitted=1;
   }
   else
   {
      $info.="TO SUBMIT THIS FORM, please review the results FOR ALL EVENTS as well as the TEAM SCORES entered below.  Then <b>check the box</b> at the bottom of this screen and <b>click \"Submit\"</b>.  The results will then be e-mailed to the NSAA.<br><br>You must then CALL THE NSAA at (402) 489-0386 to confirm that your results were received.";
      $submitted=0;
   }
   $info.="</div><br>";
}
$sql="SELECT * FROM $db2.trgdistricts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class=$row['class']; $classdist="$row[class]-$row[district]";
$district=$row[district];
$info.="<table class=nine><tr align=left><td><b>Class/District:</b> $row[class]-$row[district]<br>";
$info.="<b>Host School:</b> $row[hostschool]<br>";
$date=split("-",$row[dates]);
$info.="<b>Date:</b> $date[1]/$date[2]/$date[0]<br>";
$info.="<b>Site:</b> $row[site]<br>";
$info.="<b>Director(s):</b> $row[director]<br>";
$info.="<b>E-mail(s):</b> $row[email]</td></tr></table>";
$info.="<br></caption>";
$director=$row[director];
$location=$row[site];
$date="$date[1]/$date[2]/$date[0]";
$diremail=$row[email];

   $max_track=$limit[$class][track];
   $max_field=$limit[$class][field];
   $max_relay_sh=$limit[$class][relay_sh];
   $max_relay_lg=$limit[$class][relay_lg];
$info.="<tr align=left>";
for($x=0;$x<count($trevents_g);$x++)
{
   $event=$trevents_g[$x];
   if($x%2==0) $info.="<tr align=left valign=top>";
   $info.="<td><a name=$event href=#$event></a>";
   $info.=GetResults($distid,'g',$event);
   $info.="</td>";
   if(($x+1)%2==0) $info.="</tr>";
}//end for loop (all events)
$info.="<td>&nbsp;</td></tr></table>";

if($print!=1 && ($submitted==0 || $level==1))
{
   $info.="<br><form method=post action=\"tr_state_view_g.php\">";
   $info.="<input type=hidden name=session value=\"$session\">";
   $info.="<input type=hidden name=school_ch value=\"$school_ch\">";
   $info.="<input type=hidden name=distid value=\"$distid\">";
   $info.="<div class='alert' style='width:700px;font-size:9pt;'><b><input type=checkbox name=final value=y>";
   $info.="Check this box when you have completed ALL of the events as well as any extra field event qualifiers and the team scores.<br><br>Then click \"Submit\" and your final entry will be sent to the NSAA.<br><i>(You must then CALL THE NSAA at (402) 489-0386 to confirm that your results have been received.)</i><br>";
   $info.="<input type=submit name=submit value=\"Submit\"></b></div></form>";
   $info.="<br>";
   if(GetLevel($session)==1)      
      $info.="<a href=\"stateadmin.php?session=$session\" class=small>Return to Track & Field District Results MAIN MENU</a>&nbsp;&nbsp;&nbsp;";
   $info.="<a href=\"tr_state_edit_g.php?session=$session&school_ch=$school_ch&distid=$distid\" class=small>Edit this Form</a>&nbsp;&nbsp;&nbsp;";
   $info.="<a href=\"tr_state_view_g.php?session=$session&school_ch=$school_ch&distid=$distid&print=1\" class=small target=new>Printer-Friendly Version</a>&nbsp;&nbsp;&nbsp;";
   $info.="<a href=\"../welcome.php?session=$session\" class=small>Home</a>";
}

//write file to be sent in e-mail attachment
$activ="Girls Track & Field";
$dist=ereg_replace("-","",$classdist);
$filename="trgirlsstate".$dist.".html";
echo $info;
$string.=$info;
$string.="</td></tr></table></body></html>";

$open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename"),"w");
fwrite($open,$string);
fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename");

//now write files to be sent to NSAA
   //individuals file first:
//$filename="trgirls".$dist."indiv.dst";
$filename=$dist."girls.dst";
$open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename"),"w");
$idst.="\"END\"";
fwrite($open,$idst);
fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename");
   //then teams file:
$filename2=$dist."girlstmsco.dst";
$open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename2"),"w");
$tdst.="END,END";
fwrite($open,$tdst);
fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename2");

if($final=='y')
{
   $now=time();
   $sql="UPDATE $db2.trgdistricts SET resultssub_g='$now' WHERE class='$class' AND district='$district'";
   $result=mysql_query($sql);

   $From="nsaa@nsaahome.org";
   $FromName="NSAA";
   $To="nneuhaus@nsaahome.org";
   $ToName="NSAA";
   $Subject="District $classdist Girls Track & Field Results";
   $Text="District $classdist\r\n\r\nNumber of teams participating: $teams\r\nTotal number of individuals entered: $indys\r\nName of Meet Director: $director\r\nE-mail: $diremail\r\nLocation: $location\r\nDate: $date\r\n\r\nThank You!";
   $Html="District $classdist:<br><br>Number of teams participating: $teams<br>Total number of individuals entered: $indys<br>Name of Meet Director: $director<br>E-mail: $diremail<br>Location: $location<br>Date: $date<br><br>Thank You!";
   $AttmFiles=array("/home/nsaahome/attachments/$filename","/home/nsaahome/attachments/$filename2");
   SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles);
   SendMail($From,$FromName,"run7soccer@aim.com","Ann Gaffigan",$Subject,$Text,$Html,$AttmFiles);
}

if($print==1)
{
   echo "<form method=post action=\"../email_form.php\">";
   echo "<input type=hidden name=session value=$session>";
   echo "<input type=hidden name=district value=\"$classdist\">";
   echo "<input type=hidden name=activ value=\"$activ\">";
   echo "<table><tr align=left><th>Your E-mail:</th><td><input type=text name=reply size=25></td></tr>";
   echo "<tr align=left><th>Recipient(s) E-mail:</th><td><input typ=text name=email size=25></td></tr>";
   echo "<tr align=center><td colspan=2><input type=submit name=submit value=Send></td></tr>";
   echo "<tr align=center><th colspan=2>$email_note</th></tr>";
   echo "</table></form>";
}
echo "
</td></tr></table>
</BODY>
</HTML>";
?>
