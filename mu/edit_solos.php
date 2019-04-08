<?php
//edit_solo.php: Edit Page for Music Online Entry Form: V/I Solos (iframe source)

require '../functions.php';
require '../../calculate/functions.php';
require 'mufunctions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name,$db);

$level=GetLevel($session);

if(!ValidUser($session))
{
   header("Location:../index.php?error=1");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch || $level>1)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);
$sql="SELECT id FROM muschools WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schid=$row[id];
$year1=GetFallYear('mu');
$year2=$year1+1;

if($savesolos)	//save solos
{
   $sql="DELETE FROM muentries WHERE ensembleid='$ensembleid' AND schoolid='$schid'";
   $result=mysql_query($sql);

   for($i=0;$i<count($studentid);$i++)
   {
      if($studentid[$i]!="")
      {
         if($noaccomp[$i]=='x') $accompanist[$i]="none";
  	 $accompanist[$i]=addslashes($accompanist[$i]);
         if($piano[$i]=='y') $event[$i]="Piano";
         if($piano[$i]=='s')
	 {
	    $string='x'; $event[$i]=$event0[$i];
	 }
	 else
	    $string='';
  	 if($piano[$i]=='n' && $event1[$i]=="Other")
	    $event[$i]=addslashes($other[$i]);
	 else if($piano[$i]=='n')
	    $event[$i]=$event1[$i];
	 $sql="INSERT INTO muentries (ensembleid,schoolid,studentid,event,accompanist,strings,soloorder,piano) VALUES ('$ensembleid','$schid','$studentid[$i]','$event[$i]','$accompanist[$i]','$string','$soloorder[$i]','$piano[$i]')";
	 $result=mysql_query($sql);
	 //echo "$sql<br>";
         if(($piano[$i]=='s' || $piano[$i]=='n') && trim($event[$i])=="")
	 {
	    $soloerror=1; $soloerr[$i]=1;
	 }
	 else $soloerr[$i]=0;
      }
   }
   unset($piano); unset($event);
}          

if($distid)
{
   $sql="SELECT * FROM muschools WHERE school='$school2'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      $sql2="INSERT INTO muschools (school,distid) VALUES ('$school2','$distid')";
   }
   else
   {
      $sql2="UPDATE muschools SET distid='$distid' WHERE school='$school2'";
   }
   $result2=mysql_query($sql2);
}

echo $init_html_ajax;
?>  
<script type="text/javascript" src="/javascript/Music.js"></script>
</head>
<body onload="Music.initialize('<?php echo $school2; ?>');">
<?php
echo "<table width=100%><tr align=center><td>";

//Check if school is in muschools table yet:
$sql="SELECT * FROM muschools WHERE school='$school2'";
$result=mysql_query($sql);
if(mysql_error())
{
   echo mysql_error();
   exit();
}

$sql="SELECT * FROM mucategories WHERE id='$categ'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$smlg=$row[smlg]; $vocinst=$row[vocinst];
$category=$row[category];
echo "<table cellspacing=1 cellpadding=2>";
$sql="SELECT * FROM muensembles WHERE categid='$categ'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
echo "<input type=hidden name=ensembleid value=\"$row[id]\">";
//Instructions:
echo "<tr align=left><td colspan=3><font style=\"font-size:9pt;color:blue\"><b>INSTRUCTIONS:</b></font><table width=700><tr align=left><td>";
if(ereg("Instrumental",$category))
{
   echo "1) Please check either <b>Piano, String, or Band</b>.  The screen will then reload.</td></tr><tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a) If you select <b>String or Band</b>, you will then be able to select the <b>specific type of solo</b> OR <b>(for band only) you may select Other and then type in the event.</b></td></tr><tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b) If you select <b>Piano</b>, you may then proceed to step (2).</td></tr>";
   echo "<tr align=left><td>2) Select the <b>name of the Soloist</b>.  If you checked <b>String or Band</b>, you will only be able to select from the students on your school's <b>Instrumental Music</b> eligibility list.  If you checked <b>Piano</b>, you will be able to choose from students on both your school's <b>Instrumental AND Vocal Music</b> eligibility lists.  To <b>jump to a student's name in the dropdown list</b>, click on the dropdown list and type the first letter (or more) of his/her last name.</td></tr>";
   echo "<tr align=left><td>3) Enter the <b>name of the Accompanist</b> OR check <b>\"None\"</b> if there is no accompanist for that solo.</td></tr>";
   echo "<tr align=left><td>4) Click the <b>\"Save Solos\"</b> button at the bottom of the screen.  This will save the solo(s) you've entered and then give you a new line to add another solo.</td></tr>";
   echo "<tr align=left><td><b>NOTE: You may enter 1 new solo at a time</b> (the last row before the \"Save Solos\" button) and you may edit your existing solos at any time.</td></tr>";
   echO "<tr align=left><td><b><u>To REMOVE an existing solo</b></u>, simply select \"Choose Student\" from the dropdown menu of students for that solo and click \"Save Solos\".<a name=\"top\"><br><br></a>";
}
else
{
   echo "1) Please select the <b>Event</b> for the solo you are entering.</td></tr>";
   echo "<tr align=left><td>2) Select the <b>name of the Soloist</b>.  You will be choosing from students on your school's <b>Vocal Music</b> eligibility list.  To <b>jump to a student's name in the dropdown list</b>, click on the dropdown list and type the first letter (or more) of his/her last name.</td></tr>";
   echo "<tr align=left><td>3) Type in the <b>Accompanist</b> OR check <b>\"None\"</b> if there is no accompanist for that solo.</td></tr>";
   echo "<tr align=left><td>4) Click the <b>\"Save Solos\"</b> button at the bottom of the screen.  This will save the solo(s) you've entered and then give you a new line to add another solo.</td></tr>";
   echo "<tr align=left><td><b>NOTE: You may enter 1 new solo at a time</b> (in the last row before the \"Save Solos\" button) and you may edit your existing solos at any time.</td></tr>";
   echo "<tr align=left><td><b><u>To REMOVE an existing solo</b></u>, simply select \"Choose Student\" from the dropdown menu of students for that solo.<a name=\"top\"><br><br></a>";
}
echo "</td></tr></table></td></tr>";
if($soloerror==1)
{
   echo "<tr align=left><td colspan=3><font style=\"color:red\"><b>Where you select \"String\" or \"Band\", you must indicate a specific instrument.  Please do so and click \"Save Solos\" again.</b></font></td></tr>";
}
echo "<tr align=left valign=top>";
echo "<td><b>Event:</b></td>";
echo "<td><b>Soloist:</b></td><td><b>Accompanist:</b><br>(Please type name or check \"None\")</td></tr>";

//get list of mu students for this school:
if(ereg("Public Schools",$school) || ereg("College",$school))
   $school2=addslashes("Test's School");
$allstuds=array(); 
$vmstuds=array(); 
$imstuds=array();
//IM students:
$ix=0;
if(IsCooping($school,"Instrumental"))
{
   $schools=GetMusicCoopSchools($school,"Instrumental");
   $sql2="SELECT DISTINCT id,first,last,school FROM eligibility WHERE eligible='y' AND (";
   for($i=0;$i<count($schools);$i++)
   {
      $cursch=addslashes($schools[$i]);
      $sql2.="school='$cursch' OR ";
   }
   $sql2=substr($sql2,0,strlen($sql2)-4);
   $sql2.=") AND im='x' ORDER BY school,last,first"; 
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $imstuds[name][$ix]="$row2[last], $row2[first] ($row2[school])";
      $imstuds[id][$ix]=$row2[id];
      $ix++;
   }
}
else
{
   $sql2="SELECT DISTINCT id,first,last,school FROM eligibility WHERE eligible='y' AND school='$school2' AND im='x' ORDER BY last,first";
   $result2=mysql_query($sql2); 
   while($row2=mysql_fetch_array($result2))
   {
      $imstuds[name][$ix]="$row2[last], $row2[first]";
      $imstuds[id][$ix]=$row2[id];
      $ix++;
   }
}
//VM students:
$ix=0;
if(IsCooping($school,"Vocal")) 
{
   $schools=GetMusicCoopSchools($school,"Vocal");
   $sql2="SELECT DISTINCT id,first,last,school FROM eligibility WHERE eligible='y' AND (";
   for($i=0;$i<count($schools);$i++)
   {
      $cursch=addslashes($schools[$i]);
      $sql2.="school='$cursch' OR ";
   }
   $sql2=substr($sql2,0,strlen($sql2)-4);
   $sql2.=") AND vm='x' ORDER BY school,last,first";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $vmstuds[name][$ix]="$row2[last], $row2[first] ($row2[school])";
      $vmstuds[id][$ix]=$row2[id];
      $ix++;
   }
}
else
{
   $sql2="SELECT DISTINCT id,first,last,school FROM eligibility WHERE eligible='y' AND school='$school2' AND vm='x' ORDER by last,first";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $vmstuds[name][$ix]="$row2[last], $row2[first]";
      $vmstuds[id][$ix]=$row2[id];
      $ix++;
   }
}
//ALL students:
$ix=0;
if(IsCooping($school,"Vocal") && !IsCooping($school,"Instrumental"))
{
   $schools=GetMusicCoopSchools($school,"Vocal");
   $sql2="SELECT id,first,last,semesters,school FROM eligibility WHERE eligible='y' AND (((";
   for($i=0;$i<count($schools);$i++)
   {
      $cursch2=addslashes($schools[$i]);
      $sql2.="school='$cursch2' OR ";
   }
   $sql2=substr($sql2,0,strlen($sql2)-4);
   $sql2.=") AND vm='x') OR (school='$school2' AND im='x'))";
}
else if(IsCooping($school,"Instrumental") && !IsCooping($school,"Vocal"))
{
   $schools=GetMusicCoopSchools($school,"Instrumental");
   $sql2="SELECT id,first,last,semesters,school FROM eligibility WHERE eligible='y' AND (((";
   for($i=0;$i<count($schools);$i++)
   {
      $cursch2=addslashes($schools[$i]);
      $sql2.="school='$cursch2' OR ";
   }
   $sql2=substr($sql2,0,strlen($sql2)-4);
   $sql2.=") AND im='x') OR (school='$school2' AND vm='x'))";
}
else if(IsCooping($school,"Instrumental") && IsCooping($school,"Vocal"))
{
   $schools=GetMusicCoopSchools($school,"Instrumental"); //would be same schools if used "Vocal"
   $sql2="SELECT id,first,last,semesters,school FROM eligibility WHERE eligible='y' AND (";
   for($i=0;$i<count($schools);$i++)
   {
      $cursch2=addslashes($schools[$i]);
      $sql2.="school='$cursch2' OR ";
   }
   $sql2=substr($sql2,0,strlen($sql2)-4);
   $sql2.=") AND (vm='x' OR im='x')";
}
else //not co-oping
   $sql2="SELECT id,first,last,semesters,school FROM eligibility WHERE eligible='y' AND school='$school2' AND (vm='x' OR im='x')";
$sql2.=" ORDER BY school,last,first";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   $allstuds[name][$ix]="$row2[last], $row2[first]";
   if(IsCooping($school,"Vocal") || IsCooping($school,"Instrumental"))
      $allstuds[name][$ix].=" ($row2[school])";
   $allstuds[id][$ix]=$row2[id];
   $ix++;
}

//show existing solos:
$sql2="SELECT t1.*,t2.first,t2.last FROM muentries AS t1, eligibility AS t2 WHERE t1.studentid=t2.id AND t1.ensembleid='$row[id]' AND t1.schoolid='$schid' ORDER BY t1.soloorder";
//echo $sql2;
$result2=mysql_query($sql2);
$ix=0;
while($row2=mysql_fetch_array($result2))
{
   $place=$ix+1;
   echo "<tr valign=top align=left><td width=450>$place) ";
   echo "<input type=hidden name=\"soloorder[$ix]\" value=\"$place\">";
   if(!$event[$ix]) $event[$ix]=$row2[event];
   if(ereg("Instrumental",$category))
   {
      $isother=1;
      for($i=0;$i<count($stringens);$i++)
      {
         if($stringens[$i]==$event[$ix]) $isother=0;
      }
      for($i=0;$i<count($bandens);$i++)
      {
         if($bandens[$i]==$event[$ix]) $isother=0;
      }
      if($event[$ix]=="Piano") $isother=0;
      if($isother==1 && $event[$ix]!='')
      {
         $other[$ix]=$event[$ix]; $event[$ix]="Other";
      }
   }
   if(!$studentid[$ix]) $studentid[$ix]=$row2[studentid];
   if(!$accompanist[$ix]) $accompanist[$ix]=$row2[accompanist];
   if(ereg("Instrumental",$category))
   {
      if($piano[$ix]=='n' && $event[$ix]=="Piano")       //Other checked, reset event to "" if Piano
         $event[$ix]="";
      else if($piano[$ix]=='y')
         $event[$ix]="Piano";
      else if(!$piano[$ix] && $row2[strings]=='x') $piano[$ix]='s';
      else if(!$piano[$ix] && $event[$ix]!='Piano' && $row2[strings]!='x') $piano[$ix]='n';

      echo "<input type=radio id=\"piano".$ix."0\" name=\"piano[$ix]\" value='y' onclick=\"Music.setupISolo('$ix');\"";
      if($event[$ix]=='Piano') echo " checked";
      echo ">Piano&nbsp;&nbsp;";
      echo "<input type=radio name=\"piano[$ix]\" id=\"piano".$ix."1\" value='s' onclick=\"Music.setupISolo('$ix');\"";
      if($piano[$ix]=='s') echo " checked";
      echo ">String";
      echo "&nbsp;<select name=\"event0[$ix]\" id=\"event".$ix."0\"";
      if($piano[$ix]=='s')
         echo " style=\"display:'';\"";
      else
         echo " style=\"display:none;\""; 
      echo "><option value=''>Please Select</option>";
      for($i=0;$i<count($stringens);$i++)
      {
         echo "<option";
         if($event[$ix]==$stringens[$i]) echo " selected";
         echo ">$stringens[$i]</option>";
      }
      echo "</select>";
      echo "&nbsp;&nbsp;";
      echo "<input type=radio name=\"piano[$ix]\" id=\"piano".$ix."2\" value='n' onclick=\"Music.setupISolo('$ix');\"";
      if($piano[$ix]=='n') echo " checked";
      echo ">Band";
      echo " <select name=\"event1[$ix]\" id=\"event".$ix."1\" onchange=\"Music.setupISolo('$ix');\"";
      if($piano[$ix]=='n')
         echo " style=\"display:'';\"";
      else
         echo " style=\"display:none;\"";
      echo "><option value=''>Please Select</option>";
      for($i=0;$i<count($bandens);$i++)
      {
         echo "<option";
         if($event[$ix]==$bandens[$i]) echo " selected";
         echo ">$bandens[$i]</option>";
      }
      echo "</select>";
      echo " <input type=text class=tiny size=15 name=\"other[$ix]\" id=\"other".$ix."\" value=\"$other[$ix]\"";
      if($piano[$ix]=='n' && $event[$ix]=='Other')
      echo " style=\"display:'';\"";
      else
         echo " style=\"display:none;\"";
      echo ">";
      if($soloerr[$ix]==1)
         echo "<br><font style=\"color:red\"><b>You must select a specific instrument!</b></font>";
      echo "</td>";
   }//end if instrumental
   else	//vocal
   {
      //echo "<input type=text class=tiny size=20 name=\"event[$ix]\" value=\"$event[$ix]\"></td>";  
      echo "<select name=\"event[$ix]\"><option value=''>Choose Event</option>";
      for($i=0;$i<count($vocalsolos);$i++)
      {
         echo "<option";
         if($event[$ix]==$vocalsolos[$i]) echo " selected";
         echo ">$vocalsolos[$i]</option>";
      }
      echo "</select>";
      if(!$event[$ix] || $event[$ix]=='') 
         echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font style=\"color:red\"><b>Please select an event!</b></font>";
      echo "</td>";
   }
   echo "<td><select name=\"studentid[$ix]\" id=\"studentid".$ix."\"><option value=''>Choose Student</option>";
   if(ereg("Instrumental",$category) && $piano[$ix]=='y')	//all students
   {
      for($i=0;$i<count($allstuds[id]);$i++)
      {
         echo "<option value=\"".$allstuds[id][$i]."\"";
         if($studentid[$ix]==$allstuds[id][$i]) echo " selected";
         echo ">".$allstuds[name][$i]."</option>";
      }
   }
   else if(ereg("Instrumental",$category))	//instrumental students only
   {
      for($i=0;$i<count($imstuds[id]);$i++)
      {
          echo "<option value=\"".$imstuds[id][$i]."\"";
          if($studentid[$ix]==$imstuds[id][$i]) echo " selected";
          echo ">".$imstuds[name][$i]."</option>";
      }
   }
   else //vocal students only
   {
      for($i=0;$i<count($vmstuds[id]);$i++)
      {
          echo "<option value=\"".$vmstuds[id][$i]."\"";
          if($studentid[$ix]==$vmstuds[id][$i]) echo " selected";
          echo ">".$vmstuds[name][$i]."</option>";
      }
   }
   echo "</select></td>";
   if($accompanist[$ix]!='none')  $accomp=$accompanist[$ix];	
   else $accomp="";
   echo "<td><input type=text class=tiny size=20 name=\"accompanist[$ix]\" value=\"$accomp\">&nbsp;";
   echo "<input type=checkbox name=\"noaccomp[$ix]\" value='x'";
   if($accompanist[$ix]=='none') echo " checked";
   echo ">None</td>";
   echo "</tr>";
   $ix++;
}
//add one blank spot:
$place=$ix+1;
echo "<tr valign=top align=left><td>$place) ";
echo "<input type=hidden name=\"soloorder[$ix]\" value=\"$place\">";
if(ereg("Instrumental",$category))
{
   echo "<input type=radio name=\"piano[$ix]\" id=\"piano".$ix."0\" onclick=\"Music.setupISolo('$ix');\" value='y'";
   if($piano[$ix]=='y') echo " checked";
   echo ">Piano&nbsp;&nbsp;<input type=radio name=\"piano[$ix]\" id=\"piano".$ix."1\" onclick=\"Music.setupISolo('$ix');\" value='s'";
   if($piano[$ix]=='s') echo " checked";
   echo ">String";
   echo " <select name=\"event0[$ix]\" id=\"event".$ix."0\"";
   if($piano[$ix]=='s')
   echo " style=\"display:'';\"";
   else
      echo " style=\"display:none;\"";
   echo "><option value=''>Please Select</option>";
   for($i=0;$i<count($stringens);$i++)
   {
      echo "<option";
      if($event[$ix]==$stringens[$i]) echo " selected";
      echo ">$stringens[$i]</option>";
   }
   echo "</select>";
   echo "&nbsp;&nbsp;<input type=radio name=\"piano[$ix]\" id=\"piano".$ix."2\" onclick=\"Music.setupISolo('$ix');\" value='n'";
   if($piano[$ix]=='n') echo " checked";
   echo ">Band";
   echo " <select onchange=\"Music.setupISolo('$ix');\" name=\"event1[$ix]\" id=\"event".$ix."1\"";
   if($piano[$ix]=='n')
      echo " style=\"display:'';\"";
   else
     echo " style=\"display:none;\"";
   echo "><option value=''>Please Select</option>";
   for($i=0;$i<count($bandens);$i++)
   {
      echo "<option";
      if($event[$ix]==$bandens[$i]) echo " selected";
      echo ">$bandens[$i]</option>";
   }
   echo "</select>";
   echo " <input type=text class=tiny name=\"other[$ix]\" id=\"other".$ix."\" value=\"$other[$ix]\" size=15";
   if($event[$ix]=='Other')
      echo " style=\"display:'';\"";
   else
      echo " style=\"display:none;\"";
   echo ">";
   echo "</td>";
}
else	//vocal
{
   //echo "<input type=text class=tiny size=20 name=\"event[$ix]\"></td>";
   echo "<select name=\"event[$ix]\"><option value=''>Choose Event</option>";
   for($i=0;$i<count($vocalsolos);$i++)
   {
      echo "<option";
      echo ">$vocalsolos[$i]</option>";
   }
   echo "</select>";
   echo "</td>";
}
echo "<td><select id=\"studentid".$ix."\" name=\"studentid[$ix]\"><option value=''>Choose Student</option>";
if(ereg("Instrumental",$category) && $piano[$ix]=='y')      //all students
{
   for($i=0;$i<count($allstuds[id]);$i++)
   {
      echo "<option value=\"".$allstuds[id][$i]."\"";
      echo ">".$allstuds[name][$i]."</option>";
   }
}
else if(ereg("Instrumental",$category))     //instrumental students only
{
   for($i=0;$i<count($imstuds[id]);$i++)
   {
      echo "<option value=\"".$imstuds[id][$i]."\"";
      echo ">".$imstuds[name][$i]."</option>";
   }
}
else //vocal students only
{
   for($i=0;$i<count($vmstuds[id]);$i++)
   {
      echo "<option value=\"".$vmstuds[id][$i]."\"";
      echo ">".$vmstuds[name][$i]."</option>";
   }
}
echo "</select></td>";
echo "<td><input type=text name=\"accompanist[$ix]\" size=20 class=tiny>&nbsp;";
echo "<input type=checkbox name=\"noaccomp[$ix]\" value='x'>None</td>";
echo "</tr>";
echo "<tr align=center><td colspan=3><font style=\"color:blue\"><b>(Click \"Save Solos\" to save your entries above AND to be able to add another solo)</b></font><br><input type=submit name=savesolos style=\"font-size:16pt\" value=\"Save Solos\"></td></tr>";
echo "</table>";

echo "<div class=alert name=debug id=debug style=\"display:none;\"></div>";
?>
<div id="loading" style="display:none;"></div>
<?php
echo $end_html;
?>
