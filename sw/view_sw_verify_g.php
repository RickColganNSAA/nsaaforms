<?php
//view_sw_verify.php: SW Verification Form: view submitted info/e-mail to NSAA

require '../functions.php';
require '../variables.php';
require 'swfunctions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

echo $init_html;
if($print!=1) echo $header;
$string=$init_html."<center>";
echo "<br>";
if($print!=1) 
{
   if($level==1)
      echo "<a href=\"edit_sw_verify_g.php?session=$session&school_ch=$school_ch&formid=$formid\" class=small>Edit This Form</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"view_sw_g.php?session=$session&school_ch=$school_ch\" class=small>$school Swimming Home</a>";
   echo "&nbsp;&nbsp;&nbsp;<a href=\"verifyadmin.php?session=$session\" class=small>Verification Forms Admin</a>";
   echo "&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"view_sw_verify_g.php?session=$session&school_ch=$school_ch&formid=$formid&print=1\" target=new class=small>Printer-Friendly Version</a>";
}

//get meet info out of database
$sql="SELECT t1.id,t1.submitter,t1.email,t1.referee,t1.meetid,t1.approved,t2.* FROM sw_verify_g AS t1,swsched AS t2 WHERE t1.meetid=t2.id AND t1.id='$formid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$meetid=$row[meetid]; 
$meet=GetMeetName($meetid,'1');
$meetdate=$row[meetdate];
$temp=split("-",$meetdate);
$meetdate=date("m/d/Y",mktime(0,0,0,$temp[1],$temp[2],$temp[0]));
$submitter=$row[submitter]; $email=$row[email]; $referee=$row[referee];
$approved=$row[approved];

if($approved!='y')
   echo "<br><br><font style=\"color:red\"><b>These qualifying times will be posted after the NSAA receives the Meet Score Sheet.</b></font>";

$info="<br><br><table><caption><b>GIRLS VERIFICATION FORM<br>of State Swimming and Diving Qualifying Performances</b><hr></caption>";
$info.="<tr align=left valign=top><th class=smaller align=left>Person Submitting (Name):</th>";
$info.="<td>$submitter</td></tr>";
$info.="<tr align=left valign=top><th class=smaller align=left>Person Submitting (E-mail):</th>";
$info.="<td>$email</td></tr>";
$info.="<tr align=left valign=top><th class=smaller align=left>Meet:</th>";
$info.="<td>$meet</td></tr>";
$info.="<tr align=left><th class=smaller align=left>Date of Meet:</th>";
$info.="<td align=left>$meetdate</td></tr>";
$info.="<tr align=left><th class=smaller align=left>Meet Referee:</th>";
$info.="<td align=left>$referee</td></tr>";

//individual performances table:
   //get info already in database
   $sql="SELECT * FROM sw_verify_perf_g WHERE formid='$formid'";
   $result=mysql_query($sql);
$info.="<tr align=center><td colspan=2>";
$info.="<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=2>";
$info.="<caption align=left>The following students met or exceeded the state qualifying standards in the following events:</caption>";
$info.="<tr align=center><th class=smaller rowspan=2>";
$info.="School</th><th rowspan=2 class=smaller>Event</th><th rowspan=2 class=smaller>Name/Grade</th><th class=smaller colspan=2>Performance</th></tr>";
$info.="<tr align=center><th class=smaller>";
$info.="Swimming</th><th class=smaller>Diving</th></tr>";

//first put results into arrays
$sch=array(); $event=array(); $stud=array(); $perf=array();
$ix=0;
while($row=mysql_fetch_array($result))
{
   //get hytek abbr for this student's school
   $temp=ereg_replace("\'","\'",$row[2]);
   $sql2="SELECT hytekabbr FROM swschool WHERE school='$temp'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $sch[$ix]="$row[2] -- $row2[0]";
   $event[$ix]=$row[3];
   //get student info
   if(!ereg("Relay",$row[3]))
   {
      $sql2="SELECT id,last,first,middle,semesters FROM eligibility WHERE id='$row[4]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $stud[$ix]="$row2[2] $row2[1] (".GetYear($row2[4]).")";
   }
   else
   {
      $studs=split("/",$row[4]);
      $splits=split("/",$row[6]);
      $leadoff=$splits[0];
      $stud[$ix]="";
      for($i=0;$i<count($studs);$i++)
      {
	 $sql2="SELECT id,last,first,middle,semesters FROM eligibility WHERE id='$studs[$i]'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $stud[$ix].="$row2[2] $row2[1] (".GetYear($row2[4]).")";
	 if($i==0 && trim($leadoff)!=":." && ereg("200 Free Relay",$event[$ix]))
	    $stud[$ix].="&nbsp;&nbsp;0$leadoff";
	 $stud[$ix].="<br>";
      }
   }
   $perf[$ix]=$row[5];
   if(!ereg(":",$perf[$ix]) && !ereg("Diving",$event[$ix]))
      $perf[$ix]=ConvertFromSec($perf[$ix]);
   $ix++;
}

//now put results in table in event order:
$ix=0;
$curevent=$sw_events[$ix];
while($ix<count($sw_events))
{
   for($i=0;$i<count($sch);$i++)
   {
      if($curevent==$event[$i])	//if result's event matches current event in ordered list
      {
	 //...then show it
	 $info.="<tr align=left valign=top>";
	 $info.="<td align=left>$sch[$i]</td><td align=left>$event[$i]</td>";
	 $info.="<td align=left>$stud[$i]</td>";
	 if(ereg("Diving",$event[$i]))
	    $info.="<td>&nbsp;</td><td>$perf[$i]</td>";
	 else
	    $info.="<td>$perf[$i]</td><td>&nbsp;</td>";
	 $info.="</tr>";
      }
   }
   $ix++;
   $curevent=$sw_events[$ix];
}

$info.="</table></td></tr>";
$info.="</table>";
echo $info;
$string.=$info;
$string.=$end_html;

if($print!=1)
{
   echo "<br>";
   if($level==1)
      echo "<a href=\"edit_sw_verify_g.php?session=$session&school_ch=$school_ch&formid=$formid\" class=small>Edit This Form</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"view_sw_g.php?session=$session&school_ch=$school_ch\" class=small>$school Swimming Home</a>";
   echo "&nbsp;&nbsp;&nbsp;<a href=\"verifyadmin.php?session=$session\" class=small>Verification Forms Admin</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"../welcome.php?session=$session\" class=small>Return to Home</a>";
   echo "&nbsp;&nbsp;&nbsp;";
   echo "<a class=small href=\"view_sw_verify_g.php?session=$session&school_ch=$school_ch&formid=$formid&print=1\" target=new>Printer-Friendly Version</a>";
}

//write to .html file
$filename="swverify".$formid."_g.html";
$open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename"),"w");
fwrite($open,$string);
fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename");

if($print==1)   //printer-friendly version: form for user to e-mail file
{
?>
   <table>
   <tr align=center><th><br><br>
   <form method=post action="../email_form.php" name=emailform>
   <input type=hidden name=state value=<?php echo $state; ?>>
   <input type=hidden name=session value=<?php echo $session; ?>>
   <input type=hidden name=school value="<?php echo $school; ?>">
   <input type=hidden name=activ value="Girls Swimming">
   <input type=hidden name=swfile value="<?php echo $filename; ?>">
   <table>
   <tr align=left><th>
   Your e-mail address:</th>
   <td><input type=text name=reply size=30></td>
   </tr>
   <tr align=left><th>
   Recipient(s)' address(es):</th>
   <td>
   <textarea name=email class=email cols=50 rows=5><?php echo $recipients; ?></textarea>
   <?php
   //echo "<input type=button name=addressbook value=\"Address Book\" onclick=\"window.open('../addressbook.php?session=$session','addressbook','menubar=no,location=no,resizable=no,scrollbars=yes,width=500,height=600')\">";
   ?>
   </td>
   </tr>
   <tr align=center><td colspan=2>
   <input type=submit name=submit value="Send">
   </td></tr>
   </table>
   <font style="font-size:8pt">
   <?php echo $email_note; ?>
   </font>
   </form>
   </th></tr></table>
   <?php
}  //end if print=1

if($send=='y')	//e-mail to NSAA
{
   $sql="SELECT senttoNSAA FROM sw_verify_g WHERE id='$formid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[0]!='y')
   {
      $From=GetEmail("main");
      $FromName="NSAA";
      $To=GetEmail("sw");
      $ToName="NSAA";
      $To2="run7soccer@aim.com"; $ToName2="Ann Gaffigan";
      $Subject="Swimming Verification Form from $school";
      $Html="Attached is $school's Girls Swimming Verification Form.<br><br>";
      $Html.="Meet: $meet<br>Date: $meetdate<br><br>";
      $Html.="Thank You!";
      $Text=ereg_replace("<br>","\r\n",$Html);
      $AttmFiles=array("/home/nsaahome/attachments/$filename");
      SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles);
      //SendMail($From,$FromName,$To2,$ToName2,$Subject,$Text,$Html,$AttmFiles);
      $sql="UPDATE sw_verify_g SET senttoNSAA='y' WHERE id='$formid'";
      $result=mysql_query($sql);
   }
}

echo $end_html;
?>
