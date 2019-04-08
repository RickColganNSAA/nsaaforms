<?php
//sp_state_edit.php: Speech Dist Results Form (State Qualifiers)

require '../functions.php';
require '../variables.php';

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

//get coach
$sql="SELECT name FROM logins WHERE level='3' AND sport='Speech' AND school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$coach=$row[0];

$events[short]=array("hum","ser","ext","poet","pers","ent","inf","dram","duet");
$events[long]=array("Humorous Interpretation of Prose Literature","Serious Interpretation of Prose Literature","Extemporaneous Speaking","Oral Interpretation of Poetry","Persuasive Speaking","Entertainment Speaking","Informative Public Speaking","Oral Interpretation of Drama","Duet Acting");

if($store=="Save & Keep Editing" || $store=="Save & View Form")
{
//submit qualifiers to database:
for($x=0;$x<count($events[short]);$x++)
{
   $event=$events[short][$x];
   $sch_list=""; $stud_list="";
   for($i=0;$i<count($sch[$event]);$i++) //for each place, one row:
   {
      $place=$i+1;
      $dram_list=""; $duet_list="";
      if($sch[$event][$i]!="Choose School" && $stud[$event][$i]!="Choose Student" && $event!="dram" && $event!="duet")
      {
         $sch_list.=$sch[$event][$i].",";
	 $stud_list.=$stud[$event][$i].",";
      }
      else if($sch[$event][$i]!="Choose School" && $event=="dram")
      {
	 for($k=0;$k<5;$k++)
	 {
	    if($drama_stud[$i][$k]!="Choose Student")
	    {
	       $dram_list.=$drama_stud[$i][$k].",";
	    }
	 }
	 $dram_sch=$sch[$event][$i];
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
      else if($sch[$event][$i]!="Choose School" && $event=="duet")
      {
         for($k=0;$k<2;$k++)
         {
  	    if($duet_stud[$i][$k]!="Choose Student")
	    {
	       $duet_list.=$duet_stud[$i][$k].",";
	    }
	 }
	 $duet_sch=$sch[$event][$i];
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
if($store=="Save & View Form" || $final=='y')
{
   header("Location:sp_state_view.php?session=$session&school_ch=$school_ch&district=$district&final=$final");
   exit();
}
}//end if $store

echo $init_html;
echo GetHeader($session);

?>

<form method=post action="sp_state_edit.php"><br>
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">

<center><b><font size=2>DISTRICT RESULTS<BR>
QUALIFIERS FOR THE STATE SPEECH CONTEST</B></font>

<table>
<tr align=left><td><br>
<?php
//get district(s) this school hosts
$sql="SELECT districts FROM sp_districts WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$dists=split("/",$row[0]);
if(!$district) $district=$dists[0];

//update info for this district
if($choose=="Go" && $autosubmit!=1)
{
   $sql="SELECT * FROM sp_state_dist WHERE dist='$district'";
   $result=mysql_query($sql);
   $location=ereg_replace("\'","\'",$location);
   $location=ereg_replace("\"","\'",$location);
   $date=ereg_replace("\'","\'",$date);
   $date=ereg_replace("\"","\'",$date);
   $email=ereg_replace("\'","\'",$email);
   $email=ereg_replace("\"","\'",$email);
   if(mysql_num_rows($result)==0)       //INSERT
   {
      $sql2="INSERT INTO sp_state_dist (dist,location,date,email) VALUES ('$district','$location','$date','$email')";
   }
   else                                 //UPDATE
   {
      $sql2="UPDATE sp_state_dist SET location='$location',date='$date',email='$email' WHERE dist='$district'";
   }
   $result2=mysql_query($sql2);
}
echo "Class/District:&nbsp;";
if(count($dists)==1)
{
   echo "<b>$dists[0]</b><input type=hidden name=district value=\"$dists[0]\">";
}
else
{
   echo "<select name=district onchange=\"this.form.submit();\">";
   for($i=0;$i<count($dists);$i++)
   {
      echo "<option";
      if($district==$dists[$i]) echo " selected";
      echo ">$dists[$i]";
   }
   echo "</select>";
}
//get info for this district
$sql="SELECT * FROM sp_state_dist WHERE dist='$district'";
$result=mysql_query($sql);
//echo $sql; echo mysql_error();
$row=mysql_fetch_array($result);
echo "&nbsp;&nbsp;Location:";
echo "<input type=text size=25 name=location value=\"$row[2]\"><br>";
echo "Date: <input type=text size=15 name=date value=\"$row[3]\">";
echo "&nbsp;&nbsp;&nbsp;Director:&nbsp;";
if($row[5]!="") echo "<b>$row[5]</b><br>";
else echo "<b>$coach</b><br>";
echo "E-mail address:";
echo "<input type=text size=40 name=email value=\"$row[4]\"><br>";
?>
</td></tr>
<tr align=center><td>
<input type=submit name=choose value="Go">
</td></tr>
</table>

</form>
<?php
if($choose=="Go" || $autosubmit==1 || count($dists)==1)	
{
?>
<form method=post action="sp_state_edit.php">
<input type=hidden name=session value=<?php echo $session; ?>>
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<input type=hidden name=autosubmit value=1>
<input type=hidden name=district value="<?php echo $district; ?>">

<table width=90%>
<tr align=left><td>
<b>Directions:</b>  Please list all students qualifying for the state contest in each event.
<?php
if(ereg("A",$district) || ereg("D",$district))
   $max=4;
else if(ereg("B",$district))
   $max=3;
else if(ereg("C",$district))
   $max=2;

echo "The top $max students in each event advance to the state contest.<br>";
?>
Choose the school first for each qualifier, and the dropdown list of students' names will automatically be populated with students listed on the district entry for the selected school.  You may choose the qualifying student from this list.  If the student you are looking for is not on the list, please contact the NSAA immediately.
<br>
</td></tr>
</table><br>

<?php
//get speech schools
$sp_sch=array();
$sp_code=array();
$i=0;
$temp=split("-",$district);
$class=$temp[0];
$sql="SELECT * FROM sp_schools WHERE class='$class' ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sp_sch[$i]=trim($row[1]);
   $sp_schid[$i]=$row[0];
   $i++;
}

for($x=0;$x<count($events[short]);$x++)
{
   $event=$events[short][$x];
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
	 if(!$sch[$event][$i] || $sch[$event][$i]=="Choose School")
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
      if(!$sch[$event][$ix] || $sch[$event][$ix]=="Choose School")
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
   echo "<select class=small name=\"sch[$event][$i]\" onchange=\"this.form.action+='#$event';submit();\">";
   echo "<option>Choose School";
   for($j=0;$j<count($sp_sch);$j++)
   {
      echo "<option value=$sp_schid[$j]";
      if($sch[$event][$i]==$sp_schid[$j]) echo " selected";
      echo ">$sp_sch[$j]";
   }
   echo "</select></td>";
   //get students on district roster for selected school
   $temp=$sch[$event][$i];
   $sql="SELECT school FROM sp_schools WHERE id='$temp'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sch2[$event][$i]=ereg_replace("\'","\'",trim($row[0]));
   if(ereg("/",$sch2[$event][$i]))      //co_op school
   {
      $schools=split("/",$sch2[$event][$i]);
      $co_op=1;
      $sql="SELECT DISTINCT t2.id, t2.last, t2.first, t2.middle,t2.semesters FROM sp AS t1,eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$schools[0]' OR t2.school='$schools[1]')";
   }
   else                         //regular school
   {
      $temp=$sch2[$event][$i];
      $sql="SELECT DISTINCT t2.id, t2.last, t2.first, t2.middle,t2.semesters FROM sp AS t1,eligibility AS t2 WHERE t1.student_id=t2.id AND t2.school='$temp' AND t1.checked='y' ORDER BY t2.last";
   }
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
	 echo "<select class=small ";
	 if($num==5) echo "name=\"drama_stud[$i][$k]\"";
	 else echo "name=\"duet_stud[$i][$k]\"";
	 //echo "onsubmit=this.form.action+='#$event';submit();\">";
	 echo ">";
         echo "<option>Choose Student";
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
      echo "<select class=small name=\"stud[$event][$i]\">"; // onchange=\"this.form.action+='#$event';submit();\">";
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
}
echo "</table><br>";
}//end for loop (all events)

//show checkbox for final submission
echo "<table width=90%><tr align=left><th><input type=checkbox name=final value=y>";
echo "Check this box when you have completed the above information and wish to make this your final submission of state qualifiers.  Then click one of the \"Save\" buttons below.<br></th></tr>";
echo "<tr align=center><td>";
echo "<input type=submit name=store value=\"Save & Keep Editing\" onClick=submit
()>&nbsp;";
echo "<input type=submit name=store value=\"Save & View Form\" onClick=submit()>
";
echo "</td></tr></table>";
echo "</form>";

} //end if $choose==Go
?>

</td></tr></table>
</BODY>
</HTML>
