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

$level=GetLevel($session);
if($level==4) $level=1;

if(!$givenoffid) $offid=GetOffID($session);
else 
{
   $offid=$givenoffid;
   $header="no";
}
$curryear=date("Y",time());
$offname=GetOffName($offid);

//GET DATES
$sql="SELECT * FROM fbtourndates WHERE offdate='x' ORDER BY tourndate,label,id";
$result=mysql_query($sql);
$fbdates=array(); $i=0;
$stateix=0;
while($row=mysql_fetch_array($result))
{
   $date=explode("-",$row[tourndate]);
   $fbdates[$i]=date("F j",mktime(0,0,0,$date[1],$date[2],$date[0]));
   $i2=$i+1; $field="date".$i2; $field2="yesno".$i2;
   $sql2="SHOW FULL COLUMNS FROM fbapply WHERE Field='$field'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $sql2="ALTER TABLE fbapply ADD `$field` VARCHAR(10) NOT NULL";
      $result2=mysql_query($sql2);
      $sql2="ALTER TABLE fbapply ADD `$field2` VARCHAR(10) NOT NULL";
      $result2=mysql_query($sql2);
   }
   if(preg_match("/Final/",$row[label]) && $stateix==0)
      $stateix=$i;
   $i++;
}

if($save)
{
   $error=0;
   $conflict=addslashes($conflict);
   $otherph=$area.$pre.$post;
   if(strlen($otherph)!=10 && strlen($otherph)>0)
      $error=1;
   $date=time();

   $sql2="SELECT id FROM fbapply WHERE offid='$offid'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $sql="INSERT INTO fbapply (offid,chief,referee,umpire,linesman,linejudge,backjudge,";
      for($i=0;$i<count($fbdates);$i++)
      {
         $i2=$i+1; $field="date".$i2;
         $sql.="$field,";
      }
      $sql.="yesno1,yesno2,yesno3,yesno4,yesno5,yesno6,yesno7,yesno8,yesno9,yesno10,contact,email,otheremail,homeph,workph,cellph,otherph,conflict,appdate) VALUES ('$offid','$chief','$referee','$umpire','$linesman','$linejudge','$backjudge',";
      for($i=0;$i<count($fbdates);$i++)
      {
         $i2=$i+1; $field="date".$i2;
   	 $sql.="'".$$field."',";
      }
      $sql.="'$yesno1','$yesno2','$yesno3','$yesno4','$yesno5','$yesno6','$yesno7','$yesno8','$yesno9','$yesno10','$contact','$useemail','$email','$usehomeph','$useworkph','$usecellph','$otherph','$conflict','$date')";
   }
   else
   {
      $sql="UPDATE fbapply SET chief='$chief',referee='$referee',umpire='$umpire',linesman='$linesman',linejudge='$linejudge',backjudge='$backjudge',";
      for($i=0;$i<count($fbdates);$i++)
      {
         $i2=$i+1; $field="date".$i2;
         $sql.="$field='".$$field."',";
      }
      $sql.="yesno1='$yesno1',yesno2='$yesno2',yesno3='$yesno3',yesno4='$yesno4',yesno5='$yesno5',yesno6='$yesno6',yesno7='$yesno7',yesno8='$yesno8',yesno9='$yesno9',yesno10='$yesno10',contact='$contact',email='$useemail',otheremail='$email',homeph='$usehomeph',workph='$useworkph',cellph='$usecellph',otherph='$otherph',conflict='$conflict',appdate='$date' WHERE offid='$offid'";
   }
   $result=mysql_query($sql);
   //echo "$sql<br>".mysql_error();
   if($save=="Save & Close")
   {
?>
<script language="javascript">
window.close();
</script>
<?php
   }
}

//check if already submitted
$sql="SELECT * FROM fbapply WHERE offid='$offid'";
$result=mysql_query($sql);
$duedate=GetDueDate("fb","app");
$date=split("-",$duedate);
$duetime=mktime(0,0,0,$date[1],$date[2],$date[0]);
$year=date("Y");
$june1="$year-06-01";
$june1time=mktime(0,0,0,6,1,$year);
if(PastDue(GetDueDate("fb","app"),0) && $level!=1 && $offid!=3427)
{
   $row=mysql_fetch_array($result);
   echo $init_html;
   if($header!="no") echo GetHeader($session);
   else echo "<table width=100%><tr align=center><td>";
   echo "<br>";
   $sql2="SELECT email FROM app_duedates WHERE sport='fb'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<i>This form is unavailable at this time.</i><br><br>";
   //echo "You must e-mail <a class=small href=\"mailto:$row2[0]\">$row2[0]</a> with any changes.</i><br><br>";
   $year=date("Y");
   if(mysql_num_rows($result)==0 && !(PastDue($june1,0) && $june1time>$duetime))
   {
      echo "[You did not submit an Application to Officiate $curryear Football Playoffs/Finals.]<br><br>";
   }
   else if(!(PastDue($june1,0) && $june1time>$duetime))
   {
   $appdate=date("F d, Y",$row[appdate]);
   echo "<table width=500><caption><b>Application to Officiate $curryear State Football Playoffs and Football Finals:<br>".GetOffName($offid)."<br></b>(This form's due date is ".GetDueDate("fb","app").")<hr></caption>";
   //show crew members
   echo "<tr align=left><td><b>Crew Chief:</b></td>";
   echo "<td>".GetOffName($row[chief])."</td></tr>";
   echo "<tr valign=top align=left><td><b>Crew Members:</b></td>";
   echo "<td><table frame='all' rules='all' style=\"border:#808080 1px solid;\" cellspacing=0 cellpadding=4>";
   echo "<tr align=left><th align=left class=smaller>Referee:</th><td>".GetOffName($row[referee])."</td></tr>";
   $sql2="SELECT first,last FROM officials WHERE id='$row[umpire]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<tr align=left><th align=left class=smaller>Umpire:</th><td>$row2[0] $row2[1]</td></tr>";
   $sql2="SELECT first,last FROM officials WHERE id='$row[linesman]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<tr align=left><th align=left class=smaller>Linesman:</th><td>$row2[0] $row2[1]</td></tr>";
   $sql2="SELECT first,last FROM officials WHERE id='$row[linejudge]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<tr align=left><th align=left class=smaller>Line Judge:</th><td>$row2[0] $row2[1]</td></tr>";
   $sql2="SELECT first,last FROM officials WHERE id='$row[backjudge]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<tr align=left><th align=left class=smaller>Back Judge:</th><td>$row2[0] $row2[1]</td></tr>";
   echo "</table></td></tr>";
   echo "<tr valign=top align=left><th align=left class=smaller>Available Dates:</th><td>";
   //echo "<table frame='all' rules='all' style=\"border:#808080 1px solid;\" cellspacing=0 cellpadding=4><tr align=center><th class=smaller>Playoff Dates</th><th class=smaller>Afternoon Games</th></tr>";
   echo "<table frame='all' rules='all' style=\"border:#808080 1px solid;\" cellspacing=0 cellpadding=4><tr align=center><th class=smaller>Playoff Dates</th></tr>";
   for($i=0;$i<$stateix;$i++)
   {
      $index2=$i+1;
      $index="date".$index2;
      $index3="yesno".$index2;
      echo "<tr align=left>";
      if($row[$index]=='x')
      {
         echo "<td>$fbdates[$i]</td>";
	 // if($row[$index3]=='y') 
	    // echo "<td>YES</td>";
	 // else
	    // echo "<td>NO</td>";
      }
      else echo "<td colspan=2>&nbsp;</td>";
      echo "</tr>";
   }
   echo "</table>";
   //echo "<table frame='all' rules='all' style=\"border:#808080 1px solid;\" cellspacing=0 cellpadding=4><tr align=center><th class=smaller>Finals Dates</th><th class=smaller>10:30 AM Games</th></tr>";
   echo "<table frame='all' rules='all' style=\"border:#808080 1px solid; width:80px\" cellspacing=0 cellpadding=4><tr align=center><th class=smaller>Finals Dates</th></tr>";
   for($i=$stateix;$i<count($fbdates);$i++)
   {
      $index2=$i+1;
      $index="date".$index2;
      $index3="yesno".$index2;
      echo "<tr align=left>";
      if($row[$index]=='x')
      {
         echo "<td>$fbdates[$i]</td>";
         // if($row[$index3]=='y')
            // echo "<td>YES</td>";
         // else
            // echo "<td>NO</td>";
      }
      else echo "<td colspan=2>&nbsp;</td>";
      echo "</tr>";
   }
   echo "</table>";
      
   echo "</td></tr>";
   echo "<tr valign=top align=left><td><b>Main Contact:</b></td>";
   echo "<td><table>";
   echo "<tr align=left><th align=left class=smaller>Name:</th>";
   $sql2="SELECT * FROM officials WHERE id='$row[contact]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   echo "<td>$row2[first] $row2[last]</td></tr>";
   echo "<tr align=left><th align=left class=smaller>E-mail:</th>";
   echo "<td>";
   if($row[email]=='x') echo "$row2[email], ";
   if($row[otheremail]!="") echo "$row[otheremail]";
   echo "</td></tr>";
   echo "<tr align=left><th align=left class=smaller>Phone:</th>";
   echo "<td>";
   if($row[homeph]=='x')
      echo "Home: (".substr($row2[homeph],0,3).")".substr($row2[homeph],3,3)."-".substr($row2[homeph],6,4)."<br>";
   if($row[workph]=='x')
      echo "Work: (".substr($row2[workph],0,3).")".substr($row2[workph],3,3)."-".substr($row2[workph],6,4)."<br>";   
   if($row[cellph]=='x')
      echo "Cell: (".substr($row[cellph],0,3).")".substr($row[cellph],3,3)."-".substr($row[cellph],6,4)."<br>";
   if(trim($row[otherph])!="")
      echo "Other: (".substr($row[otherph],0,3).")".substr($row[otherph],3,3)."-".substr($row[otherph],6,4);
   echo "</td>";
   echo "</tr></table></td></tr>";
   echo "<tr align=left><th align=left class=smaller>Conflict of interest:</th>";
   echo "<td>$row[conflict]</td></tr>";
   echo "</table><br><br>";
   }//end if not after June 1
   echo "<a href=\"welcome.php?session=$session\">Home</a>&nbsp;&nbsp;";
   echo $end_html;
   exit();
}
else
{
   $submitted=1;
   $row=mysql_fetch_array($result);
   $chief=$row[chief];
   $referee=$row[referee];
   $umpire=$row[umpire];
   $linesman=$row[linesman];
   $linejudge=$row[linejudge];
   $backjudge=$row[backjudge];
   for($i=1;$i<=count($fbdates);$i++)
   {
      $index1="date".$i;
      $index2="yesno".$i;
      $$index1=$row[$index1];
      $$index2=$row[$index2];
   }
   $conflict=$row[conflict];
   $contact=$row[contact];
   $usehomeph=$row[homeph];
   $useworkph=$row[workph];
   $usecellph=$row[cellph];
   $useemail=$row[email];
   $email=$row[otheremail];
   $otherph=$row[otherph];
   /*
   if($contact!="" && $homeph=="" && workph=="" && cellph=="" && $email=="")
   {
      //if contact given but no info in table yet, pull from officials table
      $sql2="SELECT homeph,workph,cellph,email FROM officials WHERE id='$contact'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $homeph=$row2[homeph];
      $workph=$row2[workph];
      $cellph=$row2[cellph];
      $email=$row2[email];
   }
   */
}

//get list of fb officials to choose from for crew members
$sql="SELECT t1.id,t1.first,t1.last,t1.middle,t2.class FROM officials AS t1,fboff AS t2 WHERE t1.fb='x' AND t1.id=t2.offid ORDER BY t1.last,t1.first";
$result=mysql_query($sql);
$ix=0;
$fboffs=array();
while($row=mysql_fetch_array($result))
{
   $fboff[id][$ix]=$row[0];
   $fboff[name][$ix]="$row[2], $row[1] $row[3]";
   $fboff[rank][$ix]=$row['class'];
   $ix++;
}

echo $init_html;
if($header!="no") echo GetHeader($session);
else echo "<table width=100%><tr align=center><td>";
echo "<br>";
echo "<form name=appform method=post action=\"fbapp.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=givenoffid value=\"$givenoffid\">";
echo "<input type=hidden name=sort value=$sort>";
echo "<input type=hidden name=searchquery value=\"$searchquery\">";
echo "<input type=hidden name=header value=$header>";
$duedate=GetDueDate("fb","app");
$date=split("-",$duedate);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
if($save && $level!=1 && $level!=4)
{
   echo "<font style=\"color:blue\"><b>Your application has been saved.  ";
   echo "You may make updates to your application until the due date listed below.</b></font><br><br>";
}
else if($save)
{
   echo "<font style=\"color:blue\"><b>The application has been saved.";
}
else if($level!=1 && $level!=4)
   echo "<font style=\"color:blue\"><b>The following application to officiate is currently posted to the NSAA 
   by you.  You may make updates to this application until the due date listed below.</b></font><br><br>";

echo "<table class='nine' width='600px' cellspacing=3 cellpadding=3><caption><b>Application to Officiate $curryear State Football Playoffs and Football Finals</b><br> Due $duedate2";
if($error==1)
   echo "<br><font style=\"color:red\"><b>Please make sure to include an area code with your contact's phone number.</b></font>";
echo "<hr></caption>";
echo "<tr align=left><td><b>Crew Chief: &nbsp;&nbsp;&nbsp;";
echo "<select name=chief>";
if($chief==0) $chief=$offid;
for($i=0;$i<count($fboff[id]);$i++)
{
   echo "<option value=\"".$fboff[id][$i]."\"";
   if($chief==$fboff[id][$i]) echo " selected";
   echo ">".$fboff[name][$i];
   if($level==1)
      echo " (".$fboff[rank][$i].")";
   echo "</option>";
}
echo "</select>";
echo "</td></tr>";
echo "<tr align=left><td><b>Crew Members:</td></tr>";
echo "<tr align=center><td><table frame='all' rules='all' style=\"border:#808080 1px solid;\" cellspacing=0 cellpadding=4>";
echo "<tr align=left><th class=smaller align=left>Referee:</th>";
echo "<td><select class=small name=referee><option>Choose Official</option>";
for($i=0;$i<count($fboff[id]);$i++)
{
   echo "<option value=\"".$fboff[id][$i]."\"";
   if($referee==$fboff[id][$i]) echo " selected";
   echo ">".$fboff[name][$i];
   if($level==1)
      echo " (".$fboff[rank][$i].")";
   echo "</option>";
}
echo "</select></td></tr>";
echo "<tr align=left><th class=smaller align=left>Umpire:</th>";
echo "<td><select class=small name=umpire><option>Choose Official</option>";
for($i=0;$i<count($fboff[id]);$i++)
{
   echo "<option value=\"".$fboff[id][$i]."\"";
   if($umpire==$fboff[id][$i]) echo " selected";
   echo ">".$fboff[name][$i];
   if($level==1)
      echo " (".$fboff[rank][$i].")";
   echo "</option>";
}
echo "</select></td></tr>";
echo "<tr align=left><th class=smaller align=left>Linesman:</th>";
echo "<td><select class=small name=linesman><option>Choose Official</option>";
for($i=0;$i<count($fboff[id]);$i++)
{
   echo "<option value=\"".$fboff[id][$i]."\"";
   if($linesman==$fboff[id][$i]) echo " selected";
   echo ">".$fboff[name][$i];
   if($level==1)
      echo " (".$fboff[rank][$i].")";
   echo "</option>";
}
echo "</select></td></tr>";
echo "<tr align=left><th class=smaller align=left>Line Judge:</th>";
echo "<td><select class=small name=linejudge><option>Choose Official</option>";
for($i=0;$i<count($fboff[id]);$i++)
{
   echo "<option value=\"".$fboff[id][$i]."\"";
   if($linejudge==$fboff[id][$i]) echo " selected";
   echo ">".$fboff[name][$i];
   if($level==1)
      echo " (".$fboff[rank][$i].")";
   echo "</option>";
}
echo "</select></td></tr>";
echo "<tr align=left><th class=smaller align=left>Back Judge:</th>";
echo "<td><select class=small name=backjudge><option>Choose Official</option>";
for($i=0;$i<count($fboff[id]);$i++)
{
   echo "<option value=\"".$fboff[id][$i]."\"";
   if($backjudge==$fboff[id][$i]) echo " selected";
   echo ">".$fboff[name][$i];
   if($level==1)
      echo " (".$fboff[rank][$i].")";
   echo "</option>";
}
echo "</select></td></tr>";
echo "</table>";
echo "<p style=\"text-align:left;\">Any change to the makeup of this crew prior to accepting an assignment must be approved by the NSAA.<br>If you use a five-man crew, please list all five.</p></td></tr>";
echo "<tr align=left><td>";
echo "<p style=\"text-align:left;\"><b>Check dates your crew will be available and also check if you are available for afternoon games (Playoffs)/early games (Finals):</b></p></td></tr>";
echo "<tr align=center><td><table frame='all' rules='all' style=\"border:#808080 1px solid;\" cellspacing=0 cellpadding=4>";
//echo "<tr align=center><th class=smaller>Playoff Dates</th><th class=smaller>Afternoon Games</th></tr>";
echo "<tr align=center><th class=smaller>Playoff Dates</th></tr>";
for($i=0;$i<$stateix;$i++)
{
   $index2=$i+1;
   $index="date".$index2;
   echo "<tr align=left>";
   echo "<td><input type=checkbox name=\"$index\" value='x'";
   if($$index=='x') echo " checked";
   echo ">&nbsp;$fbdates[$i]</td>";
   $index3="yesno".$index2;
   //echo "<td><input type=radio name=\"$index3\" value='y'";
   //if($$index3=='y') echo " checked";
   //echo ">Yes&nbsp;&nbsp;";
   //echo "<input type=radio name=\"$index3\" value='n'";
   //if($$index3=='n') echo " checked";
   //echo ">No</td>";
   echo "</tr>";
}
echo "</table><br>";
echo "<table frame='all' rules='all' style=\"border:#808080 1px solid;\" cellspacing=0 cellpadding=4>";
//echo "<tr align=center><td><b>Finals Dates</b></td><td><b>10:30 AM Games</b></td></tr>";
echo "<tr align=center><td><b>Finals Dates</b></td></tr>";
for($i=$stateix;$i<count($fbdates);$i++)
{
   $index2=$i+1;
   $index="date".$index2;
   echo "<tr align=left>";
   echo "<td><input type=checkbox name=\"$index\" value='x'";
   if($$index=='x') echo " checked";
   echo ">&nbsp;$fbdates[$i]</td>";
   $index3="yesno".$index2;
   //echo "<td><input type=radio name=\"$index3\" value='y'";
   //if($$index3=='y') echo " checked";
   //echo ">Yes&nbsp;&nbsp;";
   //echo "<input type=radio name=\"$index3\" value='n'";
   //if($$index3=='n') echo " checked";
   //echo ">No</td>";
   echo "</tr>";
}
echo "</table>";
echo "</td></tr>";
echo "<tr align=left><td><p><b>Indicate which official in your crew should be the main contact.  Then click \"Go\" and indicate the contact information we should use for the selected official:</b></p>";
echo "<p>(If you have selected your 5 crew members and crew chief but <b>do not see them in the dropdown list below</b>, click \"Save & Submit\" at the bottom of this screen.  Your crew members should then show up in the dropdown list.)</p>";
echo "<table><tr align=left><td>Name:</td>";
echo "<td><select name=contact><option>Choose Official</option>";
echo "<option value=\"$chief\"";
if($contact==$chief) echo " selected";
echo ">".GetOffName($chief)."</option>";
echo "<option value=\"$referee\"";
if($contact==$referee) echo " selected";
echo ">".GetOffName($referee)."</option>";
echo "<option value=\"$umpire\"";
if($contact==$umpire) echo " selected";
echo ">".GetOffName($umpire)."</option>";
echo "<option value=\"$linesman\"";
if($contact==$linesman) echo " selected";
echo ">".GetOffName($linesman)."</option>";
echo "<option value=\"$linejudge\"";
if($contact==$linejudge) echo " selected";
echo ">".GetOffName($linejudge)."</option>";
echo "<option value=\"$backjudge\"";
if($contact==$backjudge) echo " selected";
echo ">".GetOffName($backjudge)."</option>";
echo "</select><input type=submit name=save value=\"Go\"></td></tr>";
//if official selected, get contact info in database
if($contact!="Choose Official" && $contact)
{
$sql="SELECT * FROM officials WHERE id='$contact'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(trim($row[email])!="")
{
   echo "<tr align=left><td colspan=2><input type=checkbox name=useemail value='x'";
   if($useemail=='x') echo " checked";
   echo ">Use e-mail address on file: $row[email]</td></tr>";
}
echo "<tr align=left><td colspan=2>Use this e-mail address: <input type=text name=email size=30 class=tiny value=\"$email\"></td></tr>";
if(trim($row[homeph])!="" || trim($row[workph])!="" || trim($row[cellph])!="")
   echo "<tr align=left><td colspan=2>Use phone numbers on file:<br>";
$homearea=substr($row[homeph],0,3);
$homepre=substr($row[homeph],3,3);
$homepost=substr($row[homeph],6,4);
$workarea=substr($row[workph],0,3);
$workpre=substr($row[workph],3,3);
$workpost=substr($row[workph],6,4);
$cellarea=substr($row[cellph],0,3);
$cellpre=substr($row[cellph],3,3);
$cellpost=substr($row[cellph],6,4);
if(trim($row[homeph])!="")
{
   echo "<input type=checkbox name=usehomeph value='x'";
   if($usehomeph=='x') echo " checked";
   echo ">(H) ($homearea)$homepre-$homepost<br>";
}
if(trim($row[workph])!="")
{
   echo "<input type=checkbox name=useworkph value='x'";
   if($useworkph=='x') echo " checked";
   echo ">(W) ($workarea)$workpre-$workpost<br>";
}
if(trim($row[cellph])!="")
{
   echo "<input type=checkbox name=usecellph value='x'";
   if($usecellph=='x') echo " checked";
   echo ">(C) ($cellarea)$cellpre-$cellpost";
}
echo "</td></tr>";
$area=substr($otherph,0,3);
$pre=substr($otherph,3,3);
$post=substr($otherph,6,4);
echo "<tr align=left><td colspan=2>Use this phone number: (<input type=text name=area size=4 maxlength=3 value=$area>)";
echo "<input type=text size=4 maxlength=3 name=pre value=$pre> - ";
echo "<input type=text size=5 maxlength=4 name=post value=$post></td></tr>";
}//end if Go
echo "</table></td></tr>";
echo "<tr align=left><td>Schools with which I have a conflict of interest:<br>";
echo "<textarea style=\"height:100px;width:550px;\" name=conflict>$conflict</textarea></td></tr>";
echo "</table><br>";
if($header!="no" || ($level!=1 || $level!=4))
{
   echo "<input type=submit name=save value=\"Save & Submit\">";
   if(($givenoffid && ($level==1 || $level==4)) || $header=="no")
   {
      echo "&nbsp;<input type=submit name=save value=\"Save & Close\">";
   }
}
echo "</form>";
if($header!="no")
   echo "<a class=small href=\"welcome.php?session=$session\">Home</a>";
if($header=="no" && ($level==1 || $level==4))
   echo "<a class=small href='#' onclick=\"window.close();\">Close</a>";
echo $end_html;
?>
