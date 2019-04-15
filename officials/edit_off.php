<?php
//edit_off.php: displays specifics of official's
//	record.  Changes can be made here as well.

require 'variables.php';
require 'functions.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}   

if(!$offid) $offid=$id;

//check if submit action was "delete"
if($submit=="Delete Official")
{
   header("Location:delete_confirm.php?id=$id&session=$session&sport=$sport&query=$query&last=$last");
}

//connect to database:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

//get level of user
$level=GetLevel($session);
?>
<script language="javascript">
<?php echo $autotab; ?>
</script>
<?php

if($submit=="Save Changes")
{
   $lastname=ereg_replace("\'","\'",$lastname);
   $first=ereg_replace("\'","\'",$first);
   $first=ereg_replace("\"","\'",$first);
   $middle=ereg_replace("\'","\'",$middle);
   $address=ereg_replace("\'","\'",$address);
   $address=ereg_replace("\"","\'",$address);
   $city=ereg_replace("\'","\'",$city);
   $city=ereg_replace("\"","\'",$city);
   $notes=ereg_replace("\'","\'",$notes);
   $notes=ereg_replace("\"","\'",$notes);
   $homeph=$homearea.$homepre.$homepost;
   $workph=$workarea.$workpre.$workpost;
   $cellph=$cellarea.$cellpre.$cellpost;
   $convictionexplain=addslashes(preg_replace("/\r\n/","<br>",$convictionexplain));

   $sql="UPDATE officials SET last='$lastname',first='$first',middle='$middle',socsec='$socsec',address='$address',city='$city',state='$state',zip='$zip',homeph='$homeph',workph='$workph',cellph='$cellph',email='$email',nhsoa='$nhsoa',gender='$gender',minority='$minority',conviction='$conviction',convictionexplain='$convictionexplain',notes='$notes',senttofed='$senttofed',blockreg='$blockreg',inactive='$inactive' WHERE id='$offid'";
   $result=mysql_query($sql);

   //put official in sport table for each sport checked if not already in there
   //also set to current mailing num for those sports they've paid for (if this hasn't been done yet)
   $paid=0;	//will be 1 if this official has paid for ANY sport (and this needs a passcode)
   for($i=0;$i<count($activity);$i++)
   {
      $table=$activity[$i]."off"; $table2=$table."_hist";
      
      if($payment[$i]!='')
      {
         $sql="SELECT * FROM $table WHERE offid='$offid'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         if($row[mailing]<0 || mysql_num_rows($result)==0)	//get current mailing number for this sport
	 {
	    $sql2="SELECT mailnum FROM mailing WHERE sport='$activity[$i]'";
 	    $result2=mysql_query($sql2);
	    $row2=mysql_fetch_array($result2);
	    $mailing=$row2[0];
	 }
	 else
	    $mailing=$row[mailing];
	 $payment2[$i]=addslashes($payment[$i]);
         $today=time();
         if(mysql_num_rows($result)==0)
         {
	    $sql2="INSERT INTO $table (offid,mailing,payment) VALUES ('$offid','$mailing','$payment2[$i]')";
	    $result2=mysql_query($sql2);
         }
         else
	 {
	    $sql2="UPDATE $table SET mailing='$mailing',payment='$payment2[$i]' WHERE offid='$offid'";
	    $result2=mysql_query($sql2);
	 }

	 //HISTORY TABLE: update if no entry for this year yet
	 $curyr=date("Y"); $curmo=date("m");
	 $curregyr=GetSchoolYear($curyr,$curmo);
	 $sql="SELECT * FROM $table2 WHERE offid='$offid' AND regyr='$curregyr'";
	 $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 $appdate=date("Y-m-d");
	 $theregyr=explode("-",$curregyr);
	 $lastregyr=$theregyr[0]-1;
	 $contest=CountVarsityContests($offid,$activity[$i],$lastregyr);
	 if(mysql_num_rows($result)==0)	//INSERT NEW ROW FOR THIS SCHOOL YEAR
	 {
	    $sql2="INSERT INTO $table2 (offid,regyr,appdate,contest) VALUES ('$offid','$curregyr','$appdate','$contest')";
	    $result2=mysql_query($sql2);
	 }
	 else if($row[appdate]=='0000-00-00')
	 {
	    $sql2="UPDATE $table2 SET appdate='$appdate',contest='$contest' WHERE offid='$offid' AND regyr='$curregyr'";
	    $result2=mysql_query($sql2);
         }
         //MAKE SURE nhsoa CHECK IS CORRECT
         $sql="UPDATE $table2 SET nhsoa='$nhsoa' WHERE offid='$offid' AND regyr='$curregyr'";
         $result=mysql_query($sql);
	 //make sure this sport is checked in officials table
	 $sql="UPDATE officials SET ".$activity[$i]."='x' WHERE id='$offid'";
	 $result=mysql_query($sql);
	 $paid=1;
      }
      else	//NOT PAID
      {
	 $sql="UPDATE officials SET ".$activity[$i]."='$actch[$i]' WHERE id='$offid'";
         $result=mysql_query($sql);

	 $sql="UPDATE $table SET payment='',mailing='-1' WHERE offid='$offid'";
	 $result=mysql_query($sql);

         $curyr=date("Y"); $curmo=date("m");
         $curregyr=GetSchoolYear($curyr,$curmo);
	 $sql="UPDATE $table2 SET appdate='0000-00-00' WHERE offid='$offid' AND regyr='$curregyr'";
         $result=mysql_query($sql);
      }
   }

   //if no passcode but there is something in payment field, get new passcode
   if(trim($passcode)=="" && $paid==1)
   {
      $lastname2=ereg_replace("\'","",$lastname);
      $lastname2=ereg_replace(" ","",$lastname2);
      $pass=substr($lastname2,0,6);
      $num=rand(1000,9999);
      $passcode=$pass.$num;
      $sql="SELECT * FROM logins WHERE passcode='$passcode'";
      $result=mysql_query($sql);
      while(mysql_num_rows($result)>0)
      {
	 $num++;
	 $passcode=$pass.$num;
	 $sql="SELECT * FROM logins WHERE passcode='$passcode'";
	 $result=mysql_query($sql);
      }
   }

   $sql2="SELECT * FROM logins WHERE offid='$offid'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
   {
      $name="$first $lastname";
      $name=ereg_replace("\'","\'",$name);
      $sql="INSERT INTO logins (name,level,passcode,offid) VALUES ('$name','2','$passcode','$offid')";
   }
   else
   {
      $sql="UPDATE logins SET passcode='$passcode' WHERE offid='$offid'";
   }
   $result=mysql_query($sql);
}

echo $init_html;
$header2=GetHeader($session);
if($header!="no") echo $header2;

//get official's info from db
$sql="SELECT * FROM officials WHERE id='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$nhsoa=$row[nhsoa];
$ssn = str_replace($sosec,"*****",$row[socsec]);
echo "<form name=\"myForm\" method=post action=\"edit_off.php\" onsubmit=\"return validateForm()\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=sport value=\"$sport\">";
echo "<input type=hidden name=query value=\"$query\">";
echo "<input type=hidden name=last value=$last>";
echo "<input type=hidden name=header value=$header>";
if($header!="no")
{
   echo "<br><a href=\"officials.php?session=$session&sport=$sport&last=$last&query=$query\">Return to Officials List</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"off_query.php?session=$session&sport=$sport&last=$last&query=$query\">Return to Advanced Search</a><br><br>";
}
else
{
   echo "<a href=\"#\" onClick=\"window.close()\">Close this Window</a><br><br>";
}
echo "<table class='nine' cellspacing=0 cellpadding=4>";
echo "<tr align=center>";
echo "<th colspan=2>Official #$offid:<br><br>";
echo "<a href=\"mergeoffs.php?off1id=$offid&session=$session\">Merge this Official's Record with Another Record</a>";
echo "<hr>";
echo "</th></tr>";
//get off's passcode
$sql2="SELECT passcode FROM logins WHERE offid='$offid'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$passcode=$row2[0];
echo "<tr align=left><th align=left>Passcode:</th>";
echo "<td><input type=text name=passcode value=\"$passcode\" size=15></td></tr>";
echo "<tr align=left><th align=left>Name: (last, first, M)</th>";
echo "<td><input type=text name=lastname value=\"$row[last]\" size=15>&nbsp;,&nbsp;&nbsp;";
echo "<input type=text name=first value=\"$row[first]\" size=10>&nbsp;";
echo "<input type=text name=middle value=\"$row[middle]\" size=2>";
echo "</td></tr>";
echo "<tr align=left><th align=left>Soc Sec #:</th>";
echo "<td align=left><input type=password name=socsec onfocus='select();' value=\"$row[socsec]\" size=10 maxlength=9 onKeyUp='return autoTab(this,9,event);'>&nbsp<span id=\"ssn1\">$row[socsec]</span>&nbsp<input type=\"button\" name=\"Show\" value=\"Show\" \ id=\"ssn_button\"/>&nbsp<input type=\"button\" name=\"Hide\" value=\"Hide\" \ id=\"ssn_button_hide\"/></td></tr>";
//echo "<td align=left><input type=text name=socsec  value=\"$row[socsec]\" size=10 maxlength=9 ></td></tr>";
echo "<tr align=left><th align=left>Address:</th>";
echo "<td align=left><input type=text name=address value=\"$row[address]\" size=30></td></tr>";
echo "<tr align=left><th align=left>City, State Zip:</th>";
echo "<td align=left><input type=text name=city value=\"$row[city]\" size=20>&nbsp;,&nbsp;&nbsp;";
echo "<input type=text name=state value=\"$row[state]\" size=2>&nbsp;&nbsp;";
echo "<input type=text name=zip value=\"$row[zip]\" size=10></td></tr>";
echo "<tr align=left><th align=left>Home Phone:</th>";
$homearea=substr($row[homeph],0,3);
$homepre=substr($row[homeph],3,3);
$homepost=substr($row[homeph],6,4);
echo "<td align=left>(<input onfocus='select();' type=text maxlength=3 size=4 name=homearea value='$homearea' onKeyUp='return autoTab(this,3,event);'>)";
echo "<input type=text maxlength=3 size=4 name=homepre value='$homepre' onfocus='select();' onKeyUp='return autoTab(this,3,event);'>-";
echo "<input type=text maxlength=4 size=5 onfocus='select();' name=homepost value='$homepost' onKeyUp='return autoTab(this,4,event);'></td></tr>";
echo "<tr align=left><th align=left>Work Phone:</th>";
$workarea=substr($row[workph],0,3);
$workpre=substr($row[workph],3,3);
$workpost=substr($row[workph],6,4);
echo "<td align=left>(<input type=text onfocus='select();' maxlength=3 size=4 name=workarea value='$workarea' onKeyUp='return autoTab(this,3,event);'>)";
echo "<input type=text maxlength=3 size=4 name=workpre value='$workpre' onfocus='select();' onKeyUp='return autoTab(this,3,event);'>-";
echo "<input type=text maxlength=4 size=5 name=workpost onfocus='select();' value='$workpost' onKeyUp='return autoTab(this,4,event);'></td></tr>";
echo "<tr align=left><th align=left>Cell Phone:</th>";
$cellarea=substr($row[cellph],0,3);
$cellpre=substr($row[cellph],3,3);
$cellpost=substr($row[cellph],6,4);
echo "<td align=left>(<input type=text maxlength=3 size=4 name=cellarea onfocus='select();' value='$cellarea' onKeyUp='return autoTab(this,3,event);'>)";
echo "<input type=text maxlength=3 size=4 name=cellpre value='$cellpre' onfocus='select();' onKeyUp='return autoTab(this,3,event);'>-";
echo "<input type=text maxlength=4 size=5 name=cellpost value='$cellpost' onfocus='select();' onKeyUp='return autoTab(this,4,event);'></td></tr>";
echo "<tr align=left><th align=left>E-mail:</th>";
echo "<td align=left><input type=text size=30 name=email value=\"$row[email]\"></td></tr>";
echo "<tr align=left><th align=left>Gender:</th><td><input type=radio name=\"gender\" value=\"M\"";
if($row[gender]=="M") echo " checked";
echo "> Male&nbsp;&nbsp;<input type=radio name=\"gender\" value=\"F\"";
if($row[gender]=="F") echo " checked";
echo "> Female</td></tr>";
echo "<tr align=left><th align=left>Minority:</th><td><input type=checkbox name=\"minority\" value=\"x\"";
if($row[minority]=='x') echo " checked";
echo "> Check here if this official's race is non-White/non-Caucasian</td></tr>";
echo "<tr align=left valign=top><th align=left>Convicted of a Misdemeanor or Felony:</th>";
echo "<td>";
echo "<input type=radio name='conviction' value='yes' onClick=\"document.getElementById('convictiondiv').style.display='';\"";
if($row[conviction]=="yes") echo " checked";
echo "> Yes&nbsp;&nbsp;&nbsp;";
echo "<input type=radio name='conviction' value='no' onClick=\"document.getElementById('convictiondiv').style.display='none';\"";
if($row[conviction]=="no") echo " checked";
echo "> No&nbsp;&nbsp;&nbsp;";
echo "<input type=radio name='conviction' value='' onClick=\"document.getElementById('convictiondiv').style.display='none';\"";
if($row[conviction]!='yes' && $row[conviction]!='no') echo " checked";
echo "> Unknown<br>";
echo "<div id='convictiondiv'";
if($row[conviction]!='yes')
   echo " style=\"display:none;\"";
echo ">";
   echo "<p><b>If you checked \"Yes\" above, please explain:</b></p>
        <textarea name=\"convictionexplain\" style=\"height:200px;width:600px;\">".preg_replace("/<br>/","\r\n",$row[convictionexplain])."</textarea>";
echo "</div>";
echo "</td></tr>";
echo "<tr align=left valign=top><th align=left>Notes:</th>";
echo "<td><textarea style=\"width:600px;height:75px;\" name=notes>$row[notes]</textarea></td></tr>";
echo "<tr align=left><th align=left>Sent to NFHS:</th>";
echo "<td><input type=checkbox name=senttofed value='1'";
if($row[senttofed]>0) echo " checked";
echo "></td></tr>";
echo "<tr align=left><th align=left>Block Official from Registering:</th>";
echo "<td><input type=checkbox name=blockreg value='x'";
if($row[blockreg]=='x') echo " checked";
echo "> While this is checked, the official will received the notice, \"In order to register you will first need to contact the NSAA,\" when trying to register online.</td></tr>";
echo "<tr align=left><th align=left>Mark Officials as INACTIVE:</th>";
echo "<td><input type=checkbox name=inactive value='x'";
if($row[inactive]=='x') echo " checked";
echo "> While this is checked, the official will be considered inactive and will be excluded by default from reports, mailings, etc.</td></tr>";
echo "<tr align=left><th colspan=2 align='left'><br><b>Sports:</b></th></tr>";
echo "<tr align=center><td colspan=2><p>";
echo "<i>Check the box next to each sport this official is eligible for and click the name of a sport to view/edit the subform for this official for that sport.</i></p><p><b>NOTE:</b> To register an official for the current year for a sport, in addition to checking that sport's box, enter the <b>method of payment</b> in the textbox next to that sport and click \"Save Changes.\"</p></td></tr>";
echo "<tr align=center><td colspan=2 align=center><table>";
for($i=0;$i<count($activity);$i++)
{
   if($i%2==0)
      echo "<tr align=left>";
   echo "<td align=left>&nbsp;&nbsp;&nbsp;";
   echo "<input type=checkbox name=\"actch[$i]\" value='x'";
   if($row[$activity[$i]]=='x') echo " checked";
   $width=600; $height=600;
   $query2=ereg_replace("[\]","",$query);
   $query2=ereg_replace("\'","\'",$query2);
   echo ">&nbsp;<a href='#' onClick=\"window.open('edit_sport.php?individual=1&querysport=$sport&session=$session&sport=$activity[$i]&query=$query2&id=$offid&last=$last','$activity[$i]','height=$height,width=$width,scrollbars=yes,menubar=no,toolbar=no,resizable=yes,titlebar=no')\"> $act_long[$i]</a>";
   echo "</td>";
   $table2=$activity[$i]."off";
   $sql2="SELECT payment,datepaid FROM $table2 WHERE offid='$offid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $curpayment=$row2[0];
   //store original payment value for each sport as well
   echo "<input type=hidden name=\"oldpayment[$i]\" value=\"$curpayment\">";
   echo "<td><input type=text class=tiny size=10 name=\"payment[$i]\" value=\"$curpayment\"></td>";
   echo "<td>";
   if($curpayment!='')
   {
      echo "(paid ";
      $table3=$table2."_hist";
      $sql3="SELECT appdate FROM $table3 WHERE offid='$offid' ORDER BY appdate DESC LIMIT 1";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      $appdate=split("-",$row3[0]);
      echo "$appdate[1]/$appdate[2]/$appdate[0])";
   }
   else echo "&nbsp;";
   echo "</td>";
   if(($i+1)%2==0)
      echo "</tr>";
}
echo "</table></td></tr>";
echo "<tr align=center><td colspan=2><input type=checkbox name=\"nhsoa\" value=\"x\"";
if($nhsoa=='x') echo " checked";
echo "> <b>NHSOA Membership ($30.00)</b> - checking here (and clicking Save Changes) will also put a checkmark in each sport for which this official is registered.</td></tr>";
echo "<input type=hidden name=id value=\"$offid\">";
echo "<tr align=center><td colspan=2><br><input type=submit name=submit tabindex='1' value=\"Save Changes\">&nbsp;&nbsp;";
//echo "<input type=submit name=submit value=\"Cancel\">";
echo "<input type=submit name=submit value=\"Delete Official\"></td></tr>";
echo "</table></form>";
if($header!="no")
{
   echo "<a href=\"welcome.php?session=$session\">Return Home</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"officials.php?session=$session&sport=$sport&query=$query&last=$last\">Return to Officials List</a>&nbsp;&nbsp;&nbsp;";
   echo "<a href=\"off_query.php?session=$session&sport=$sport&query=$query&last=$last\">Return to Advanced Search</a>";
}
else
{
   echo "<a href=\"#\" onClick=\"window.close()\">Close this Window</a>";
}

echo $end_html;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
$("#ssn1").hide();
$("#ssn_button_hide").hide();
$( "#ssn_button" ).click(function() {
  $("#ssn1").show();
  $("#ssn_button_hide").show();
  $("#ssn_button").hide();
});  
$( "#ssn_button_hide" ).click(function() {
  $("#ssn1").hide();
  $("#ssn_button_hide").hide();
  $("#ssn_button").show();
});  
function validateForm() {
    var x = document.forms["myForm"]["passcode"].value;

	var y = x.match(/[a-z]/i);
	var z = x.match(/\d+/g);

    if (x == "") {
        alert("Passcode must be filled out");
        return false;
    }
	else if (x.length<8 || y==null   || z==null) {
	   alert("Passcode length must be at least 8 charecters long and should contain a letter and a digit");
	   return false;
	}
}
</script>