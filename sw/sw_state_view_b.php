<?php
//sw_state_view_b.php: View SW State Entry Form (Boys)

require '../functions.php';
require '../variables.php';

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

//get swimming school--"school" field in swschool table
$sql="SELECT id FROM headers WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schid=$row[id];
$sql="SELECT school,hytekabbr,stateform_b,sid FROM swschool WHERE mainsch='$schid' OR othersch1='$schid' OR othersch2='$schid' OR othersch3='$schid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$sw_sch=$row[school];
$sw_sch2=addslashes($sw_sch);
$hytekabbr=$row[hytekabbr];
$schoolid=$row[sid];
if($row[othersch1]==0 && $row[othersch2]==0 && $row[othersch3]==0)
   $coops=0;
else
   $coops=1;

echo $init_html;
if($print!=1) echo $header;
$string=$init_html."<center><br>";
$info="";

//for each individual event, show places for 4 entrants
$ix=0;
echo "<center><br>";
if($print!=1)
{
   echo "<a class=small href=\"sw_state_view_b.php?session=$session&school_ch=$school_ch&print=1\" target=new>Printer-Friendly Version</a>";
   if($level==1)
   {
      echo "&nbsp;&nbsp;&nbsp;<a class=small href=\"sw_state_edit_b.php?session=$session&school_ch=$school_ch\">Edit this Form</a>";
      echo "&nbsp;&nbsp;&nbsp;<a class=small href=\"../swstate.php?session=$session\">Return to State Swimming</a>";
   }
   echo "<br><br>";
}
if($final=='y' && $print!=1)
{
   echo "<font style=\"color:red\"><b>The following Boys State Swimming Entry Form has been submitted to the NSAA:<br><br></font></b>";
   $now=time();
   $sql="UPDATE swschool SET stateform_b='$now' WHERE sid='$schoolid'";
   $result=mysql_query($sql);
}
$info.="<table border=1 bordercolor=#000000 cellspacing=0 cellpadding=3>";
$info.="<caption><b>$sw_sch Boys State Swimming Entry Form:</b></caption>";
for($i=0;$i<count($sw_events);$i++)
{
   if(!ereg("Relay",$sw_events[$i]))
   {
      if($ix%3==0) $info.="<tr align=center valign=top>";
      $info.="<td><table><caption align=left><b>$sw_events[$i]:</b>";
      if(ereg("Diving",$sw_events[$i]))
	 $info.="<br><font style=\"font-size:8pt\">(You must fax diver's scoresheet with<br>BOTH coach and divers signature)</font>";
      $info.="</caption>";
      $sql2="SELECT t1.entry,t2.first,t2.last,t2.semesters FROM sw_state_b AS t1,eligibility AS t2 WHERE t1.studs=t2.id AND t1.schoolid='$schoolid' AND event='$sw_events[$i]' ORDER BY entry";
      if(ereg("Diving",$sw_events[$i])) $sql.=" DESC";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
	 $info.="<tr align=left><td align=left>";
	 $info.="$row2[1] $row2[2] (".GetYear($row2[3]).") - ";
	 if(!ereg("Diving",$sw_events[$i])) $info.=ConvertFromSec($row2[0]);
	 else $info.=$row2[0];
	 $qualify=DoesQualify("Boys ".$sw_events[$i],$row2[0]);
	 if($qualify=="Automatic") $info.=" AUTO";
	 else if($qualify=="Secondary") $info.=" SEC";
	 $info.="</td></tr>";
      }
      $info.="</table></td>";
      if(($ix+1)%3==0) $info.="</tr>";
      $ix++;
   }
}
//now show relays
$ix=0;
for($i=0;$i<count($sw_events);$i++)
{
   if(ereg("Relay",$sw_events[$i]))
   {
      if($ix%3==0) $info.="<tr align=center valign=top>";
      $info.="<td><table><caption align=left><b>$sw_events[$i]:</b></caption>";
      $info.="<tr align=left><td>";
      $sql2="SELECT entry FROM sw_state_b WHERE event='$sw_events[$i]' AND schoolid='$schoolid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $entry=$row2[0];
      $entry2=ConvertFromSec($entry);
      $info.=$entry2." - ";
      $qualify=DoesQualify("Boys ".$sw_events[$i],$entry);
      if($qualify=="Automatic") $info.="AUTO";
      else $info.="SEC";
      $info.="</td></tr>";

      //get swimmers on this relay from database, if any
      $sql2="SELECT studs FROM sw_state_b WHERE schoolid='$schoolid' AND event='$sw_events[$i]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $dbstuds=split("/",$row2[0]);
      for($j=0;$j<8;$j++)
      {
	 $info.="<tr align=left><td align=left>";
	 $num=$j+1;
	 if($j==4) $info.="<b>Alternates:</b><br>";
	 $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$dbstuds[$j]'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $info.="$num&nbsp;$row2[0] $row2[1] (".GetYear($row2[2]).")";
	 $info.="</td></tr>";
      }
      $info.="</table></td>";
      if(($ix+1)%3==0) $info.="</tr>";
      $ix++;
   }
}
$info.="</table>";
echo $info;
$filename=$hytekabbr."boys.html";
if($print!=1)
{
   //write to .html file
   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename"),"w");
   $info.=$end_html;
   $string.=$info;
   fwrite($open,$string);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename");

   echo "<br><a class=small target=new href=\"sw_state_view_b.php?session=$session&school_ch=$school_ch&print=1\">Printer-Friendly Version</a>&nbsp;&nbsp;&nbsp;";
   if($level==1)
      echo "<a class=small href=\"sw_state_edit_b.php?session=$session&school_ch=$school_ch\">Edit this Form</a>&nbsp;&nbsp;&nbsp;";
   echo "<a class=small href=\"../welcome.php?session=$session\">Home</a>";
}
else
{
?>
<table>
<tr align=center><th>
<form method=post action="../email_form.php" name=emailform>
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school value="<?php echo $school_ch; ?>">
<input type=hidden name=hytekabbr value="<?php echo $hytekabbr; ?>">
<input type=hidden name=activ value="Boys Swimming">
<input type=hidden name=swfile value="<?php echo $filename; ?>">
<table>
<tr align=left><th>
Your e-mail address:</th>
<td><input type=text name=reply size=40></td>
</tr>
<tr align=left><th>
Recipient(s)' address(es):</th> 
<td>
<textarea name=email class=email cols=50 rows=5><?php echo $recipients; ?>
</textarea>
<?php
//echo "<input type=button name=addressbook value=\"Address Book\" onclick=\"window.open('../addressbook.php?session=$session','addressbook','menubar=no,location=no,resizable=no,scrollbars=yes,width=500.height=600')\">";
?>
</td>
</tr>
<tr align=center><td colspan=2>
<input type=submit name=submit value="Send">
</td></tr>
</table>
<font style="size:8pt;"><?php echo $email_note; ?></font>
</form>
</th></tr>
</table>
<?php
}//end if print=1

if($final=='y')
{
   //e-mail final submission to NSAA
   $From="nsaa@nsaahome.org";
   $FromName="NSAA State Swimming";
   $To=GetEmail("sw");
   $ToName="NSAA";
   $Subject="$sw_sch ($hytekabbr) Boys State Swimming Entry Form";
   $Html="$school has submitted the $sw_sch Boys State Swimming Entry Form.<br><br>Their entry form is attached in .html format.<br><br>Thank You!";
   $Text=ereg_replace("<br>","\r\n",$Html);
   $AttmFiles=array("/home/nsaahome/attachments/$filename");
   SendMail($From,$FromName,$To,$ToName,$Subject,$Html,$Text,$AttmFiles);
   //SendMail($From,$FromName,"run7soccer@aol.com","Ann Gaffigan",$Subject,$Html,$Text,$AttmFiles);
}
echo $end_html;
?>
