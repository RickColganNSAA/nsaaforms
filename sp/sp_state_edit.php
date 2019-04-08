<?php
//sp_state_edit.php: Speech Dist Results Form (State Qualifiers)

require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php';

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

$level=GetLevel($session);
if(!ValidUser($session))
{
   //check if officials
   if(ValidUser($session,"$db_name2"))
   {
      $sql="SELECT t2.level FROM $db_name2.sessions AS t1, $db_name2.logins_j AS t2 WHERE t1.session_id='$session' AND t1.login_id=t2.id";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $level=$row[0];
      if($level!=1)
      {
         header("Location:../index.php?error=1");
         exit();
      }
      else
	 $offadmin=1;
   }
   else
   {
      header("Location:../index.php?error=1");
      exit();
   }
}
if($school_ch && $level==1)
{
   $school=$school_ch;
}
else
{
   $school=GetSchool($session);
}
$school2=addslashes($school);

//get coach
$sql="SELECT name FROM logins WHERE level='3' AND sport='Speech' AND school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$coach=$row[0];

$events[short]=array("hum","ser","ext","poet","pers","ent","inf","dram","duet");
$events[long]=array("Humorous Interpretation of Prose Literature","Serious Interpretation of Prose Literature","Extemporaneous Speaking","Oral Interpretation of Poetry","Persuasive Speaking","Entertainment Speaking","Informative Public Speaking","Oral Interpretation of Drama","Duet Acting");

if($store=="Save")
{
//save district info
$sql="SELECT * FROM sp_state_dist WHERE dist='$district'";
$result=mysql_query($sql);
$location=addslashes($location);
$date=addslashes($date);
if(mysql_num_rows($result)==0)       //INSERT
{
   $sql2="INSERT INTO sp_state_dist (dist,location,date,email) VALUES ('$district','$location','$date','$email')";
}
else                                 //UPDATE
{
   $sql2="UPDATE sp_state_dist SET location='$location',date='$date',email='$email' WHERE dist='$district'";
}
$result2=mysql_query($sql2);

//submit qualifiers to database:
for($x=0;$x<count($events[short]);$x++)
{
   $event=$events[short][$x];
   $sch_list=""; $stud_list="";
   for($i=0;$i<count($sch[$event]);$i++) //for each place, one row:
   {
      $place=$i+1;
      $dram_list=""; $duet_list="";
      if($event!="dram" && $event!="duet")
      {
	 if($sch[$event][$i]=="0") $sch_list.=",";
	 else
            $sch_list.=$sch[$event][$i].",";
	 if($stud[$event][$i]=="Choose Student") $stud_list.=",";
	 else
	    $stud_list.=$stud[$event][$i].",";
      }
      else if($event=="dram")
      {
	 for($k=0;$k<5;$k++)
	 {
	    if($drama_stud[$i][$k]!="Choose Student")
	    {
	       $dram_list.=$drama_stud[$i][$k].",";
	    }
	    else $dram_list.=",";
	 }
	 $dram_sch=$sch[$event][$i];
	 if($dram_sch=="0") $dram_sch="";
	 $dram_list=substr($dram_list,0,strlen($dram_list)-1);
	 $sql="SELECT * FROM sp_state_drama WHERE dist_id='$district' AND place='$place'";
	 $result=mysql_query($sql);
	 if(mysql_num_rows($result)==0)	//INSERT
	 {
	    $sql2="INSERT INTO sp_state_drama (dist_id,place,dram_sch,dram_stud) VALUES ('$district','$place','$dram_sch','$dram_list')";
	 }
	 else		//UPDATE
	 {
	    $sql2="UPDATE sp_state_drama SET dram_sch='$dram_sch', dram_stud='$dram_list' WHERE dist_id='$district' AND place='$place'";
	 }
	 $result2=mysql_query($sql2);
      }
      else if($event=="duet")
      {
         for($k=0;$k<2;$k++)
         {
  	    if($duet_stud[$i][$k]!="Choose Student")
	    {
	       $duet_list.=$duet_stud[$i][$k].",";
	    }
	    else $duet_list.=",";
	 }
	 $duet_sch=$sch[$event][$i];
	 if($duet_sch=="0") $duet_sch="";
	 $duet_list=substr($duet_list,0,strlen($duet_list)-1);
	 $sql="SELECT * FROM sp_state_duet WHERE dist_id='$district' AND place='$place'";
         $result=mysql_query($sql);
         if(mysql_num_rows($result)==0) //INSERT
  	 {
	    $sql2="INSERT INTO sp_state_duet (dist_id,place,duet_sch,duet_stud) VALUES ('$district','$place','$duet_sch','$duet_list')";
         }
	 else				//UPDATE
	 {
	    $sql2="UPDATE sp_state_duet SET duet_sch='$duet_sch', duet_stud='$duet_list' WHERE dist_id='$district' AND place='$place'";
	 }
	 $result2=mysql_query($sql2);
      }
   }
   if($event!="dram" && $event!="duet")
   {
      $eventsch=$event."_sch";
      $eventstud=$event."_stud";
      $sch_list=substr($sch_list,0,strlen($sch_list)-1);
      $stud_list=substr($stud_list,0,strlen($stud_list)-1);
      $sql="SELECT * FROM sp_state_qual WHERE dist_id='$district'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)	//INSERT
      {
	 $sql2="INSERT INTO sp_state_qual (dist_id,$eventsch,$eventstud) VALUES ('$district','$sch_list','$stud_list')";
      }
      else				//UPDATE
      {
	 $sql2="UPDATE sp_state_qual SET $eventsch='$sch_list', $eventstud='$stud_list' WHERE dist_id='$district'";
      }
      $result2=mysql_query($sql2);
   }
}
//update team scores
$teamscores=addslashes($teamscores);
$teamscores=str_replace(array("\r", "\n"),"<br>",$teamscores);
$sql="UPDATE sp_state_dist SET teamscores='$teamscores' WHERE dist='$district'";
$result=mysql_query($sql);
if(mysql_error())
{
   echo mysql_error();
   exit();
}

$sql="SELECT * FROM $db_name2.spdistricts WHERE id='$district'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($row[submitted]!="")	//already submitted; this is an UPDATE; resend to NSAA:
   $final='y';
else
   $final='';

header("Location:sp_state_view.php?session=$session&school_ch=$school_ch&district=$district&final=$final&offadmin=$offadmin");
exit();
}//end if $store

$sql="SELECT * FROM $db_name2.spdistricts WHERE hostschool='$school2'";
$result=mysql_query($sql);
$i=0;
while($row=mysql_fetch_array($result))
{
   $dists[$i]=$row[id];
   $i++;
}
if(!$district) $district=$dists[0];

$sql="SELECT * FROM $db_name2.spdistricts WHERE id='$district'";
$result=mysql_query($sql);
//echo $sql; echo mysql_error();
$row=mysql_fetch_array($result);
$class=$row['class'];
if(preg_match("/A/",$class))
   $max=4;
else if(preg_match("/B/",$class))
   $max=3;
else if(preg_match("/C/",$class) || preg_match("/D/",$class))
   $max=3;
$totalplaces=$max*9;

echo $init_html_ajax;
?>
<script type="text/javascript" src="/javascript/Team2.js"></script>
</head>
<body onload="Team2.initialize('<?php echo $session; ?>','sp','school','student','<?php echo $totalplaces; ?>');">
<?php
if($offadmin==1)
   echo "<table width=100%><tr align=center><td>";
else
   echo GetHeader($session);
?>

<form method=post action="sp_state_edit.php" name=spform>
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=newdistrict value='0'>
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<input type=hidden name=autosubmit value=1>
<input type=hidden name=district value="<?php echo $district; ?>">
<input type=hidden name=offadmin value="<?php echo $offadmin; ?>">

<b><font style="font-size:9pt;">DISTRICT SPEECH RESULTS:&nbsp;
QUALIFIERS FOR THE STATE SPEECH CONTEST</B></font>

<table>
<tr align=left><td><br>
<?php
echo "Class/District:&nbsp;";
if(count($dists)==1)
{
   $sql="SELECT * FROM $db_name2.spdistricts WHERE id='$dists[0]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<b>$row[class]-$row[district]</b><input type=hidden name=district value=\"$dists[0]\">";
}
else
{
   if($distid) $district=$distid;
   echo "<select name=district onchange=\"this.document.forms.spform.newdistrict.value='1';this.form.submit();\">";
   for($i=0;$i<count($dists);$i++)
   {
      echo "<option value=\"$dists[$i]\"";
      if($district==$dists[$i]) echo " selected";
      $sql="SELECT * FROM $db_name2.spdistricts WHERE id='$dists[$i]'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      echo ">$row[class]-$row[district]</option>";
   }
   echo "</select>";
}
//get info for this district
$sql="SELECT * FROM $db_name2.spdistricts WHERE id='$district'";
$result=mysql_query($sql);
//echo $sql; echo mysql_error();
$row=mysql_fetch_array($result);
$sids=$row[sids];
echo "&nbsp;&nbsp;Site:";
echo "<input type=text size=25 name=site value=\"$row[site]\"><br><br>";
$dates="";
$day=split("/",$row[dates]);
for($i=0;$i<count($day);$i++)
{
   $date=split("-",$day[$i]);
   $dates.=date("F j",mktime(0,0,0,$date[1],$date[2],$date[0])).", ";
}
$dates.=$date[0];
echo "Date: <b>$dates</b>";
echo "&nbsp;&nbsp;&nbsp;Director:&nbsp;";
if($row[director]!="") echo "<b>$row[director]</b><br>";
else echo "<b>$coach</b><br>";
echo "<br>Director's E-mail address:";
echo "<input type=text size=40 name=email value=\"$row[email]\"><br>";
$class=$row['class'];
?>
</td></tr>
</table>

<table width=90%>
<tr align=center><td><div class=alert style="width:700;">
<b>Directions:</b>  Please list all students qualifying for the state contest in each event.
<?php
if(preg_match("/A/",$class))
   $max=4;
else if(preg_match("/B/",$class))
   $max=3;
else if(preg_match("/C/",$class) || preg_match("/D/",$class))
   $max=3;

echo "The top $max students in each event advance to the state contest.<br>";
?>
Choose the school first for each qualifier, and the dropdown list of students' names will automatically be populated with students listed on the district entry for the selected school.  You may choose the qualifying student from this list.  If the student you are looking for is not on the list, please contact the NSAA immediately.
<br><br>
<b>NOTE:</b> If you select a school and a STUDENT IS MISSING or NO STUDENTS APPEAR IN THE DROPDOWN list for that school, the school's AD needs to update their Speech Eligibility List.  Please contact that school's AD to get this issue resolved.
</div>
</td></tr>
</table><br>

<?php
//get speech schools
$sp_sch=array();
$sp_code=array();
$sids=split(",",$sids);
$curyr=GetFallYear('sp');
for($i=0;$i<count($sids);$i++)
{
   $sp_sch[$i]=GetSchoolName($sids[$i],'sp');
   $sp_schid[$i]=trim($sids[$i]);
}

echo "<table cellspacing=3 cellpadding=3 width=790><tr align=left valign=top><td>";	//start new column at Drama
$overallix=0;
for($x=0;$x<count($events[short]);$x++)
{
   $event=$events[short][$x];
   if($event=="dram") echo "</td><td>";
   echo "<a name=$event href=#$event></a>";
?>
<table border=1 bordercolor=#000000 cellspacing=1 cellpadding=2>
<caption align=left><b><?php echo $events[long][$x]; ?></b></caption>
<tr>
<th>Place</th>
<th>School</th>
<th>Name</th>
</tr>
<?php
//get info already in database
if($newdistrict==1) 
{
   unset($sch[$event]);
   unset($stud[$event]);
   unset($drama_stud);
   unset($duet_stud);
}
if($event!="dram" && $event!="duet")
{
   $sql="SELECT * FROM sp_state_qual WHERE dist_id='$district'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if(mysql_num_rows($result)>0)
   {
      $eventsch=$event."_sch";
      $eventstud=$event."_stud";
      $tempsch[$event]=split(",",$row[$eventsch]);
      $tempstud[$event]=split(",",$row[$eventstud]);
      for($i=0;$i<count($tempsch[$event]);$i++)
      {
	 if(!$sch[$event][$i] || $sch[$event][$i]=="0")
	    $sch[$event][$i]=$tempsch[$event][$i];
      }
      for($i=0;$i<count($tempstud[$event]);$i++)
      {
         if(!$stud[$event][$i] || $stud[$event][$i]=="Choose Student")
       	    $stud[$event][$i]=$tempstud[$event][$i];
      }
   }
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
      if(!$sch[$event][$ix] || $sch[$event][$ix]=="0")
	 $sch[$event][$ix]=$row[0];
      $ix++;
   }
}

for($i=0;$i<$max;$i++) //for each place, one row:
{
   $schools=""; $co_op=0;
   $place=$i+1;
   echo "<tr valign=center align=center><th>$place</th>";
   echo "<td valign=top>";
   if($event=="dram")
      echo "<select class=small id=\"school".$overallix."\" name=\"sch[$event][$i]\" onMouseDown=\"Team2.currentPlace=$overallix;Team2.currentDuplicateStuds=5;\">";
   else if($event=="duet")                
      echo "<select class=small id=\"school".$overallix."\" name=\"sch[$event][$i]\" onMouseDown=\"Team2.currentPlace=$overallix;Team2.currentDuplicateStuds=2;\">";
   else
      echo "<select class=small id=\"school".$overallix."\" name=\"sch[$event][$i]\" onMouseDown=\"Team2.currentPlace=$overallix;\">";
   echo "<option value='0'>Choose School</option>";
   for($j=0;$j<count($sp_sch);$j++)
   {
      echo "<option value=$sp_schid[$j]";
      if($sch[$event][$i]==$sp_schid[$j]) echo " selected";
      echo ">$sp_sch[$j]</option>";
   }
   echo "</select></td>";
   //get students on sp elig list for selected school
   $temp=$sch[$event][$i];
   if($temp!="0")
   {
   $sql="SELECT * FROM spschool WHERE sid='$temp'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $schools=array(); $ix=0;
      //main school:
      $sql2="SELECT school FROM headers WHERE id='$row[mainsch]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $schools[$ix]=addslashes($row2[0]); $ix++;
      //other schools:
      if($row[othersch1]>0)
      {
         $sql2="SELECT school FROM headers WHERE id='$row[othersch1]'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $schools[$ix]=addslashes($row2[0]); $ix++;
      }
      if($row[othersch2]>0)
      {
         $sql2="SELECT school FROM headers WHERE id='$row[othersch2]'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $schools[$ix]=addslashes($row2[0]); $ix++;
      }
      if($row[othersch3]>0)
      {
         $sql2="SELECT school FROM headers WHERE id='$row[othersch3]'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $schools[$ix]=addslashes($row2[0]); $ix++;
      }
   $sql="SELECT DISTINCT id,last,first,middle,semesters FROM eligibility WHERE (";
   for($j=0;$j<count($schools);$j++)
   {
      $sql.="school='$schools[$j]' OR ";
   }
   $sql=substr($sql,0,strlen($sql)-4).") AND sp='x' ORDER BY last,first";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      $sp_studs[0][$ix]=$row[0];
      $sp_studs[1][$ix]=$row[1];
      $sp_studs[2][$ix]=$row[2];
      $sp_studs[3][$ix]=$row[3];
      $sp_studs[4][$ix]=GetYear($row[4]);
      $ix++;
   }
   $numstuds=$ix;
   }
   echo "<td align=left>";
   if($event=="dram" || $event=="duet")	//special cases: Drama & Duet
   {
      echo "<table><tr align=left><td>";
      if($event=="dram") 
      {
	 $num=5;
	 $sql="SELECT * FROM sp_state_drama WHERE dist_id='$district' ORDER BY place";
	 $result=mysql_query($sql);
	 $ix=0;
	 while($row=mysql_fetch_array($result))
	 {
	    $temp=$row[4];
	    $temp=split(",",$temp);
	    for($k=0;$k<count($temp);$k++)
	    {
	       if(!$drama_stud[$ix][$k] || $drama_stud[$ix][$k]=="Choose Student")
		  $drama_stud[$ix][$k]=$temp[$k];
            }
	    $ix++;
	 }
      }
      else 
      {
	 $num=2;
	 $sql="SELECT * FROM sp_state_duet WHERE dist_id='$district' ORDER BY place";
	 $result=mysql_query($sql);
	 $ix=0;
	 while($row=mysql_fetch_array($result))
	 {
	    $temp=$row[4];
	    $temp=split(",",$temp);
	    for($k=0;$k<count($temp);$k++)
	    {
	       if(!$duet_stud[$ix][$k] || $duet_stud[$ix][$k]=="Choose Student")
		  $duet_stud[$ix][$k]=$temp[$k];
	    }
	    $ix++;
	 }
      }
      for($k=0;$k<$num;$k++)
      {
	 if($num==5) echo "<select id=\"student".$overallix.$k."\" class=small name=\"drama_stud[$i][$k]\">";
	 else echo "<select id=\"student".$overallix.$k."\" name=\"duet_stud[$i][$k]\">";
         echo "<option>Choose Student</option>";
	 for($l=0;$l<$numstuds;$l++)
         {
	    $id=$sp_studs[0][$l]; 
	    $name=$sp_studs[1][$l].", ".$sp_studs[2][$l]." ".$sp_studs[3][$l];
            echo "<option value=$id";
            if($num==5 && $drama_stud[$i][$k]==$id) echo " selected";
	    else if($num==2 && $duet_stud[$i][$k]==$id) echo " selected";
            echo ">$name (".$sp_studs[4][$l].")";
         }
         echo "</select><br>";
      }
      echo "</td></tr></table>";
   }
   else
   {
      echo "<select class=small id=\"student".$overallix."\" name=\"stud[$event][$i]\">";
      echo "<option>Choose Student";
      for($l=0;$l<$numstuds;$l++)
      {
         $id=$sp_studs[0][$l];
	 $name=$sp_studs[1][$l].", ".$sp_studs[2][$l]." ".$sp_studs[3][$l];
         echo "<option value=$id";
	 if($stud[$event][$i]==$id) echo " selected";
         echo ">$name (".$sp_studs[4][$l].")";
      }
      echo "</select>";
   }
   echo "</td></tr>";
   $overallix++;
}//end for each place in this event
echo "</table><br>";
}//end for loop (all events)

//text box for team scores:
$sql="SELECT teamscores FROM sp_state_dist WHERE dist='$district'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$teamscores=preg_replace("/\<br\>/","",$row[0]);
echo "<table>
<tr align=left>
<th>Team Scores:</th>
</tr>
<tr align=center>
<td>
<textarea name=teamscores rows=12 cols=50>$teamscores</textarea>
</td>
</tr>
</table>";

echo "</td></tr></table><br>";

//show checkbox for final submission
/*
echo "<tr align=left><th><input type=checkbox name=final value=y>";
echo "Check this box when you have completed the above information and wish to make this your final submission of state qualifiers.  Then click the \"Save\" button below.<br></th></tr>";
*/
echo "<input type=submit name=store value=\"Save\">";
//echo "<input type=submit name=store value=\"Save & View Form\">";
echo "</form>";
?>
<div id="loading" style=\"display:none;\"></div>
<?php 
echo $end_html;
?>
