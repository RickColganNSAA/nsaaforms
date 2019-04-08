<?php
//sp_state_view.php: Speech Dist Results Form (State Qualifiers)

require '../functions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

$level=GetLevel($session);
if($offadmin==1 && $school_ch!='')
{
   $sql="SELECT t2.level,t2.id FROM $db_name2.sessions AS t1, $db_name2.logins_j AS t2 WHERE t1.session_id='$session' AND t1.login_id=t2.id";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $level=$row[0];
}
if($school_ch && GetLevel($session)==1)
{
   $school=$school_ch;
}
else
{
   $school=GetSchool($session);
}
$school2=ereg_replace("\'","\'",$school);

if($distid && $distid!='') $district=$distid;
if(!$district || $district=='')
{
   $sql="SELECT * FROM $db_name2.spdistricts WHERE hostschool='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $district=$row[id];
   if(mysql_num_rows($result)==0)
   {
      echo $init_html;  
      if($level!=1)
         echo GetHeader($session);
      else echo "<table width=100%><tr align=center><td>";
      echo "<br><br>No District Selected.";
      echo $end_html;
      exit();
   }
}

if($final=='y')
{
   //update as sent in database
   $sql="UPDATE $db_name2.spdistricts SET submitted='".time()."' WHERE id='$district'";
   $result=mysql_query($sql);
}

//get coach
$sql="SELECT name FROM logins WHERE level='3' AND sport='Speech' AND school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$coach=$row[0];

$events[short]=array("hum","ser","ext","poet","pers","ent","inf","dram","duet");
$events[long]=array("Humorous Interpretation of Prose Literature","Serious Interpretation of Prose Literature","Extemporaneous Speaking","Oral Interpretation of Poetry","Persuasive Speaking","Entertainment Speaking","Informative Public Speaking","Oral Interpretation of Drama","Duet Acting");

echo $init_html;
if($print!=1 && $level!=1 && $offadmin!=1) echo GetHeader($session);
else echo "<table width=100%><tr align=center><td>";
$string=$init_html;
$csv="";

$sql="SELECT * FROM $db_name2.spdistricts WHERE id='$district'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($print!=1)
{
   echo "<a href=\"sp_state_view.php?session=$session&school_ch=$school_ch&district=$district&print=1\" target=new class=small>E-mail/Printer Friendly Version</a>&nbsp;&nbsp;&nbsp;&nbsp;";
   $submitted=$row[submitted];
   if($row[submitted]=='' || $level==1)
      echo "<a href=\"sp_state_edit.php?session=$session&school_ch=$school_ch&district=$district\" class=small>Edit this Form</a><br><br>";
   if($final=='y')
   {
      echo "<font style=\"color:red\"><b>Your district results have been sent to the NSAA.  Thank you!</font></b><br>";
   }
   //if SUBMITTED, show note that they submitted, on what date, and that they can make changes if necessary:
   if($level!=1) echo "<div class=alert style=\"width:500px;\">";
   if($row[submitted]!='' && $level!=1)
   {
      echo "You submitted this form on <b>".date("F j, Y",$row[submitted])."</b>.<br><br>";
      echo "If you need to make a last-minute change, please contact <a href=\"mailto:dvelder@nsaahome.org?cc=callaway@nsaahome.org\" class=small>Cindy Callaway and Deb Velder</a> at the NSAA office.";
   }
   else if($level!=1)	//if NOT SUBMITTED, show checkbox allowing them to submit their final form
   {
      echo "$row[submitted]<form method=post action=\"sp_state_view.php\">";
      echo "<input type=hidden name=\"session\" value=\"$session\">";
      echo "<input type=hidden name=\"district\" value=\"$district\">"; 
      echo "You have <b><u>NOT</b></u> submitted this form yet.  Click <a class=small href=\"sp_state_edit.php?session=$session&school_ch=$school_ch&district=$district\">Edit this Form</a> to complete your form.  You can Save your form and come back to it later if you wish.  Once you are finished with your form, please review it for accuracy and completeness and then check the box below and click \"Submit Final Results\".<br><br>";
      echo "<input type=checkbox name=\"final\" value=\"y\"> <i>I certify that these results are complete and accurate to the best of my knowledge and wish to submit them as the final results for this district.</i><br>";
      echo "<input type=submit name=\"submitfinal\" value=\"Submit Final Results\"></form>";
   }
   if(!level!=1) echo "</div>";
}
$classdist="$row[class]-$row[district]";
$info="<b><font style=\"font-size:9pt\">DISTRICT SPEECH RESULTS:&nbsp;";
$info.="QUALIFIERS FOR THE STATE SPEECH CONTEST</B></font>";
$info.="<table>";
$info.="<tr align=left><td><br>";
$info.="<b>Class/District:&nbsp;</b>$classdist";
//get info for this district
$sql="SELECT * FROM $db_name2.spdistricts WHERE id='$district'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$info.="<b>&nbsp;&nbsp;Location:</b>$row[site]<br>";
$date=split("-",$row[dates]);
$info.="<b>Date: </b>$date[1]/$date[2]/$date[0]";
$info.="&nbsp;&nbsp;&nbsp;<b>Director:</b>&nbsp;";
$director=$row[director]; $info.="$director<br>";
$info.="<b>E-mail address:</b>&nbsp;$row[email]<br><br>";
$info.="</td></tr></table>";

$csv.="Class/District: $classdist\r\n";
$csv.="Location: $row[site]\r\n";
$csv.="\"Date: $date[1]/$date[2]/$date[0]\"\r\n";
$csv.="Director: $director\r\n";
$csv.="E-mail address: $row[email]\r\n\r\n";
$distnum=split("-",$classdist);
$csv.=$distnum[1].",";

if(ereg("A",$classdist)) $max=4;
else $max=3;

for($x=0;$x<count($events[short]);$x++)
{
   $event=$events[short][$x];
   $info.="<table width=450 border=1 bordercolor=#000000 cellspacing=1 cellpadding=2>";
   $info.="<caption align=left><b>".$events[long][$x]."</b></caption>";
   $info.="<tr align=center><th width=40>Place</th><th>School</th><th>Name</th><th>Grade</th></tr>";
   if($event!="dram" && $event!="duet")
   {
      $sql="SELECT * FROM sp_state_qual WHERE dist_id='$district'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $eventsch=$event."_sch";
      $eventstud=$event."_stud";
      $sch[$event]=split(",",$row[$eventsch]);
      $stud[$event]=split(",",$row[$eventstud]);
   }
   else if($event=="dram" || $event=="duet")
   {
      if($event=="dram")
         $sql="SELECT dram_sch FROM sp_state_drama WHERE dist_id='$district' ORDER BY place";
      else
         $sql="SELECT duet_sch FROM sp_state_duet WHERE dist_id='$district' ORDER BY place";
      $result=mysql_query($sql);
      $ix=0;
      while($row=mysql_fetch_array($result))
      {
	 $sch[$event][$ix]=$row[0];
         $ix++;
      }
   }

   for($i=0;$i<$max;$i++) //for each place, one row:
   {
      $place=$i+1;
      $id=$sch[$event][$i];
      $sql2="SELECT school,code FROM spschool WHERE sid='$id'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $info.="<tr valign=center align=center><th>$place</th>";
      $info.="<td valign=top align=left>";
      $info.=trim($row2[0])."&nbsp;</td>";
      $cur_sch=$row2[1]." ".trim($row2[0]);
      $info.="<td align=left>";
      if($event=="dram" || $event=="duet")	//special cases: Drama & Duet
      {
         $info.="<table>";
         if($event=="dram") 
         {
	    $num=5;
	    $sql2="SELECT * FROM sp_state_drama WHERE dist_id='$district' AND place='$place'";
	    $result2=mysql_query($sql2);
	    $row2=mysql_fetch_array($result2);
	    $ix=$place-1;
	    $drama_stud[$ix]=$row2[4];
	    $drama_stud[$ix]=split(",",$drama_stud[$ix]);
	 }
         else 
         {
	    $num=2;
	    $sql2="SELECT * FROM sp_state_duet WHERE dist_id='$district' AND place='$place'";
	    $result2=mysql_query($sql2);
	    $ix=$place-1;
	    $row2=mysql_fetch_array($result2);
	    $duet_stud[$ix]=$row2[4];
	    $duet_stud[$ix]=split(",",$duet_stud[$ix]);
         }
	 $cur_stud="";
         for($k=0;$k<$num;$k++)
         {
	    $info.="<tr align=left><td>";
	    if($num==5) 
	       $id=$drama_stud[$i][$k];
	    else
	       $id=$duet_stud[$i][$k];
	    $sql2="SELECT first, last, semesters FROM eligibility WHERE id='$id'";
	    $result2=mysql_query($sql2);
	    $row2=mysql_fetch_array($result2);
            $info.="$row2[0] $row2[1]&nbsp;</td></tr>";
	    $cur_stud.=" ".$row2[0]." ".$row2[1]." (".GetYear($row2[2])."),";
	    if($num==5) $dram_yr[$i][$k]=GetYear($row2[2]);
	    else $duet_yr[$i][$k]=GetYear($row2[2]);
         }
	 $cur_stud=substr($cur_stud,0,strlen($cur_stud)-1);
         $info.="</table>";
      }
      else
      {
	 $id=$stud[$event][$i];
	 $sql2="SELECT first, last, semesters FROM eligibility WHERE id='$id'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $info.="$row2[0]&nbsp;$row2[1]";
	 $cur_stud=" ".$row2[0]." ".$row2[1]." (".GetYear($row2[2]).")";
	 $year[$event][$i]=GetYear($row2[2]);
      }
      $info.="</td>";
      $cur_entry="\"".$cur_stud."\",\"".$cur_sch."\"";
      $csv.=$cur_entry.",";
      //get student's grade
      if($event=="dram" || $event=="duet")
      {
         $info.="<td><table>";
         if($event=="dram") $num=5;
         else $num=2;
         for($k=0;$k<$num;$k++)
         {
	    $info.="<tr align=center><td>";
	    if($num==5) $info.=$dram_yr[$i][$k];
	    else $info.=$duet_yr[$i][$k];
	    $info.="&nbsp;</td></tr>";
         }
         $info.="</table></td>";
      }
      else
      {
         $info.="<td>".$year[$event][$i]."&nbsp;</td>";
      }
      $info.="</tr>";
   }
   $info.="</table><br>";
}//end for loop (all events)

$sql="SELECT teamscores FROM sp_state_dist WHERE dist='$district'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$info.="<table width=250 border=1 bordercolor=#000000 cellspacing=1 cellpadding=5
frame=all rules=none><caption align=left><b>Team Scores:</b></caption><tr align=
center><th><table><tr align=left><td>$row[0]</td></tr></table></th></tr></table><br>";

$string.=$info;
echo $info;
if($print!=1 && $level!=1)
{
   echo "<a target=new class=small href=\"sp_state_view.php?session=$session&school_ch=$school_ch&district=$district&print=1\">E-mail/Printer Friendly Version</a>&nbsp;&nbsp;&nbsp;&nbsp;";
   if($submitted=='' || $level==1)
      echo "<a class=small href=\"sp_state_edit.php?session=$session&school_ch=$school_ch&district=$district\">Edit this Form</a>&nbsp;&nbsp;&nbsp;&nbsp;";
   if($offadmin==1)
      echo "<a class=small href=\"javascript:window.close();\">Close</a>";
   else
      echo "<a class=small href=\"../welcome.php?session=$session\">Return to Home</a>";
}
//Allow user to e-mail form (write html file-->will also be the file pulled for display on website)
   $activ="Speech";
   $activ_lower=strtolower($activ);
   $activ_lower=ereg_replace(" ","",$activ_lower);
   $filename="spstate";
   $dist2=ereg_replace("-","",$classdist);
   $filename.=$dist2;
   $filename1=$filename.".html";
   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename1"),"w");
   $string.="</td></tr></table></body></html>";
   fwrite($open,$string);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename1");
   $filename2=$filename.".csv";
   $open=fopen(citgf_fopen("/home/nsaahome/attachments/$filename2"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/$filename2");
   unset($filename);
if($print==1)
{
?>
<table>
<tr align=center><th>
<form method=post action="../email_form.php">
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=district value="<?php echo $dist2; ?>">
<input type=hidden name=class_dist value="<?php echo $classdist; ?>">
<input type=hidden name=activ value="<?php echo $activ; ?>">
<table>
<tr align=left><th>
Your e-mail address:</th>
<td><input type=text name=reply size=25></td>
</tr>
<tr align=left><th>
Recipient(s)' address(es):</th>
<td><input type=text name=email size=50></td>
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
}  //end if print=1
if($final=='y')	//final submission: put on WEBSITE & send to NSAA
{
   //1) SEND E-MAILS TO NSAA:
   $From="nsaa@nsaahome.org";
   $FromName="NSAA";
   $Subject="District $classdist Speech Results Have Been Submitted";
   $Text="The results for the District $classdist Speech Tournament have been submitted.  They have approved this as their final submission.\r\n\r\nThe link to these results is on the NSAA website at https://nsaahome.org/speech.html.\r\n\r\nYou can also login and view and/or edit this district's results under Judges Login-->State Speech-->View Submitted District Speech Results.\r\n\r\nThank you!";
   $Html="The results for the District $classdist Speech Tournament have been submitted.  They have approved this as their final submission.<br><br>The link to these results is on the NSAA website at <a href='https://nsaahome.org/speech.html'>https://nsaahome.org/speech.html</a>.  You can also login and view and/or edit this district's results under <a href='https://secure.nsaahome.org/nsaaforms/officials/jlogin.php'>Judges Login</a>-->State Speech-->View Submitted District Speech Results.<br><br>Thank you!";
   $AttmFiles=array();
   //SendMail($From,$FromName,"run7soccer@aim.com","Ann Gaffigan",$Subject,$Text,$Html,$AttmFiles);
   SendMail($From,$FromName,"dvelder@nsaahome.org","Deb Velder",$Subject,$Text,$Html,$AttmFiles);
   SendMail($From,$FromName,"callaway@nsaahome.org","Cindy Callaway",$Subject,$Text,$Html,$AttmFiles);
}
?>

</td></tr></table>
</BODY>
</HTML>
