<?php
require '../functions.php';
require '../../calculate/functions.php';
require '../variables.php';
require 'mufunctions.php';

//connect to db
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

if(!ValidUser($session))
{
   header("Location:../index.php?error=1");
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
//get school id
$sql="SELECT * FROM muschools WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$schid=$row[id];
$year1=GetFallYear('mu');
$year2=$year1+1;

//get category info
$sql="SELECT * FROM mucategories WHERE id='$categ'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$smlg=$row[smlg]; $vocinst=$row[vocinst];
$category=$row[category];
//get ensemble info
$sql="SELECT ensemble,categid FROM muensembles WHERE id='$ensembleid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$ensemble=$row[ensemble];
if($row[categid]==0) //MISCELLANEOUS Vocal & Inst ENSEMBLE
   $vocinst="";

if($done)
{
   $openid=$ensembleid;
?>
<script language="javascript">
window.close();
window.opener.location.replace('edit_categ.php?session=<?php echo $session; ?>&categ=<?php echo $categ; ?>&school_ch=<?php echo $school2; ?>&open=<?php echo $openid; ?>');
</script>
<?php
}
if($savepercinst)	//SAVE PERCUSSION INSTRUMENTS TO mupercinsts
{
   $sql="DELETE FROM mupercinsts WHERE entryid='$entryid'";
   $result=mysql_query($sql);

   for($i=0;$i<count($percinst);$i++)
   {
      if($percinst[$i]=="Other")
      {
	 $other=addslashes($other);
	 $sql2="INSERT INTO mupercinsts (entryid,instid,other) VALUES ('$entryid','$i','$other')";
	 $result2=mysql_query($sql2);
      }
      else
      {
         $sql2="INSERT INTO mupercinsts (entryid,instid,isusing,isproviding) VALUES ('$entryid','$i','$isusing[$i]','$isproviding[$i]')";
         $result2=mysql_query($sql2);
      }
   }

   echo $init_html;
   echo "<form method=post action=\"mupick.php\">";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<input type=hidden name=categ value=\"$categ\">";
   echo "<input type=hidden name=ensembleid value=\"$ensembleid\">";
   echo "<input type=hidden name=entryid value=\"$entryid\">";
   echo "<input type=hidden name=school_ch value=\"$school_ch\">";
   echo "<table width=100%><tr align=center><td>";
   echo "<table><tr align=left><td>";
   echo "<b>Thank you for entering your percussion instruments. You may now:<br><br>";
   echo "<input type=submit name=edit value=\"Edit this Entry\">&nbsp;&nbsp;OR&nbsp;&nbsp;";
   echo "<input type=submit name=done value=\"Close this Window\">";
   echo "</td></table>";
   echo $end_html;
   exit();
}
if($save)
{
   //add this ensemble to muentries if current entryid=0
   $titleerror=0; $accerror=0;
   if(ereg("Misc",$ensemble) && trim($misctitle)=="")
      $titleerror=1;
   if(!($smlg=="Large" && $vocinst=="Instrumental"))    //all but Large Inst: accompanist
   {
      if($noaccompanist=="x") $accompanist="none";
      if(trim($accompanist)=="") $accerror=1;
   }
   if($titleerror!=1)
   {
   if($accerror!=1)
   {
   if($entryid==0)
   {
      $sql="INSERT INTO muentries (schoolid,ensembleid,misc) VALUES ('$schid','$ensembleid','$misc')";
      $result=mysql_query($sql);
      $entryid=mysql_insert_id(); 
   }
   if(ereg("Misc",$ensemble) || $smlg=="Large")	//Misc Ensembles & Large Ensembles
   {
      $sql="UPDATE muentries SET groupsize='$groupsize' WHERE id='$entryid'";
      $result=mysql_query($sql);
   }
   if(!($smlg=="Large" && $vocinst=="Instrumental"))	//all but Large Inst: accompanist
   {
      $accompanist=addslashes($accompanist);
      $sql="UPDATE muentries SET accompanist='$accompanist' WHERE id='$entryid'";
      $result=mysql_query($sql);
   }
   if(ereg("Misc",$ensemble))	//Misc: Strings
   {
      $misctitle=addslashes($misctitle);
      $sql="UPDATE muentries SET event='$misctitle',strings='$strings' WHERE id='$entryid'";
      $result=mysql_query($sql);
   }
   }
   
   echo $init_html;
   echo "<form method=post action=\"mupick.php\">";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<input type=hidden name=categ value=\"$categ\">";
   echo "<input type=hidden name=ensembleid value=\"$ensembleid\">";
   echo "<input type=hidden name=entryid value=\"$entryid\">";
   echo "<input type=hidden name=school_ch value=\"$school_ch\">";
   echo "<table width=100%><tr align=center><td>";
   echo "<table><tr align=left><td>";
   $studs=""; $studct=0;
   if($accerror!=1)
   {
   for($i=0;$i<count($studentid);$i++)
   {
      $sql="SELECT * FROM mustudentries WHERE entryid='$entryid' AND studentid='$studentid[$i]'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0 && ($checkall=='x' || $check[$i]=='x'))		//INSERT
      {
         $sql2="INSERT INTO mustudentries (entryid,studentid) VALUES ('$entryid','$studentid[$i]')";
         $result2=mysql_query($sql2);
         //echo $sql2."<br>";
      }
      else if($check[$i]!='x' && $checkall!='x')	//student was UN-checked
      {
	 $sql2="DELETE FROM mustudentries WHERE entryid='$entryid' AND studentid='$studentid[$i]'";
	 $result2=mysql_query($sql2);
      }
      if($check[$i]=='x' || $checkall=='x')	//Get all checked, whether new or existing
      {
         $sql="SELECT first,last,semesters,school FROM eligibility WHERE id='$studentid[$i]'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         $studs.="<li>$row[first] $row[last] (".GetYear($row[semesters]).")</li>";
	 $studct++;
      }
   }
   }
   else 
   {
      echo "<font style=\"color:red\"><b>You must enter your accompanist for this entry or check \"none\" to allow proper scheduling by your contest maager.</b></font><br><br>";
      echo "Please <a href=\"javascript:history.go(-1)\">Go Back and correct this error.</a><br><br>";
      echo "Thank You!";
      exit();
   }
   if($studct>0)
   {
      if($studct>24 && $smlg=="Small")
      {
	 echo "<font style=\"color:red\"><b>You have entered too many students!<br><br></font>";
	 echo "You have entered <b>$studct</b> students.  You may enter a maximum of 24 students.<br><br>";
	 echo "Please <a href=\"javascript:history.go(-1)\">Go Back and correct this error.</a><br><br>";
         echo "Thank You!";
	 exit();
      }
      else
      {
         echo "<b>You have entered the following <u>$studct</u> students in a $ensemble:</b><ol>";
         echo "$studs</ol><br>";
	 if($ensemble!="Percussion Ensemble")
	 {
            echo "<input type=submit name=edit value=\"Edit these Entries\">&nbsp;&nbsp;OR&nbsp;&nbsp;";
            echo "<input type=submit name=done value=\"Close this Window\">";
	 }
      }
   }
   else		//0 entries entered
   {
      echo "<b>You have saved a $ensemble with no students listed.</b><br><br>";
      echo "<input type=submit name=edit value=\"List Students\">&nbsp;&nbsp;OR&nbsp;&nbsp;";
      if($ensemble!='Percussion Ensemble') echo "<input type=submit name=done value=\"Close this Window\">";
   }
   //If we get here, there were no errors
   if($ensemble=="Percussion Ensemble")	//PERCUSSION ENSEMBLE: enter instruments
   {
      echo "<br><table cellspacing=0 cellpadding=5 frame='all' rules='all' style=\"border:#d0d0d0 1px solid;\"><caption>For each instrument below:<ul><li>Check the <b><u>first box</u></b> to indicate you will be <b><u>using the instrument for this percussion ensemble</b></u></li><br><li>Check the <b><u>second box</b></u> to indicate that <b><u>your school will provide that (larger) instrument</b></u>.</li></ul><br></caption>";
      echo "<tr align=center><td><b>Instrument</b></td><td><b>We will USE this instrument</b></td><td><b>We will PROVIDE this instrument</b></td></tr>";
      for($i=0;$i<count($percinst);$i++)
      {
	 if($percinst[$i]=="Other")
	 {
            $sql="SELECT * FROM mupercinsts WHERE entryid='$entryid' AND instid='$i'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);
	    echo "<tr valign=top align=left><td>Other:</td><td colspan=2>Please list the instrument(s) and how many of each will be used:<br><textarea name=\"other\" rows=10 cols=40>$row[other]</textarea></td></tr>";
	 }
	 else
	 {
            $sql="SELECT * FROM mupercinsts WHERE entryid='$entryid' AND instid='$i'";
	    $result=mysql_query($sql);
	    $row=mysql_fetch_array($result);
	    echo "<tr align=center><td align=left>$percinst[$i]</td>";
	    echo "<td><input type=checkbox name=\"isusing[$i]\" value='x'";
	    if($row[isusing]=='x') echo " checked";
	    echo "></td><td><input type=checkbox name=\"isproviding[$i]\" value='x'";
	    if($row[isproviding]=='x') echo " checked";
	    echo "></td></tr>";
   	 }
      }
      echo "</table><br><input type=submit name=\"savepercinst\" value=\"Save\">";
   }
   echo "</td></tr></table></form>";
   echo $end_html;
   exit();
   }//end if no titleerror
}

//GIVEN: $categ (ex: Small Vocal Ensemble), $ensembleid (from muensembles.id, $entryid (from muentries.id)
$sql="SELECT * FROM muensembles WHERE id='$ensembleid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$studcount=$row[number];
$ensemble=$row[ensemble];
if($entryid!=0)	//get students already entered in this ensemble for this school
{
   $sql="SELECT studentid FROM mustudentries WHERE entryid='$entryid'";
   $result=mysql_query($sql);
   $studs="/";
   while($row=mysql_fetch_array($result))
   {
      $studs.=$row[studentid]."/";
   }
}
else $studs="";

echo $init_html;
echo "<table width=100%><tr align=center><td>";

echo "<form method=post action=\"mupick.php\">";
echo "<input type=hidden name=school_ch value=\"$school_ch\">";
echo "<input type=hidden name=categ value=\"$categ\">";
echo "<input type=hidden name=entryid value=\"$entryid\">";
echo "<input type=hidden name=ensembleid value=\"$ensembleid\">";
echo "<input type=hidden name=session value=$session>";
echo "<table cellspacing=0 cellpadding=5 frame='all' rules='all' style='width:400px;border:#d0d0d0 1px solid;'>";
echo "<caption align=center><table><tr align=left><td>";
if(ereg("Misc",$ensemble))
{
   echo "<p>You are entering a <u>$ensemble</u></p>";
   $sql2="SELECT event,strings FROM muentries WHERE id='$entryid'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if($titleerror==1)
      echo "<br><div class=error>You MUST enter a descriptive TITLE for your miscellaneous ensemble.</div>";
   echo "<br><b>Please give your ensemble a descriptive TITLE:</b><br>";
   if($ensemble=="Miscellaneous Small Vocal Ensemble")
      //echo "(Male, Female, etc)<br>";
      echo "<br>";
   else if($ensemble=="Miscellaneous Small Instrumental Ensemble")
      echo "(Brass, Percussion, String, Woodwinds, etc)<br>";
   else
//      echo "(Male, Female, Mixed, Brass, String, etc)<br>";
      echo "( Brass, String, etc)<br>";
   echo "<input type=text ";
   echo "class=tiny size=30 value=\"$row2[event]\" name=\"misctitle\"><br>";
   if($ensemble=="Miscellaneous Small Instrumental Ensemble")
   {
      echo "<input type=checkbox name=\"strings\" value='x'";
      if($row2[strings]=='x') echo " checked";
      else if($strings=='x') echo " checked";
      echo "> <b>Check Here if this is an <u>ALL STRINGS</u> Ensemble<br></b>";
   }
}
else
   echo "<p>You are entering a <u>$category</u>:</p><p><b><i>$ensemble</i></b></p>";
if(ereg("Misc",$ensemble))
{
   $sql="SELECT groupsize FROM muentries WHERE id='$entryid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $groupsize=$row[0];
   echo "<input type=hidden name=misc value=\"1\">";
}
else
{
   if($smlg=="Large" && $ensemble!="Jazz Band" && $ensemble!="Madrigal" && $ensemble!="Show Choir")
   {
      $sql="SELECT groupsize FROM muentries WHERE id='$entryid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $groupsize=$row[groupsize];
      echo "No. of students in this group: <input type=text class=tiny size=3 name=\"groupsize\" value=\"$groupsize\"><br>";
   }
   echo "<input type=hidden name=misc value=\"0\">";
}
if(!($smlg=="Large" && $vocinst=="Instrumental"))	//everything but Large Inst: accompanist box
{
   $sql="SELECT accompanist FROM muentries WHERE id='$entryid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[accompanist]=="none") { $accompanist=""; $none="x"; }
   else { $accompanist=$row[accompanist]; $none=""; }
   echo "<b>Accompanist:</b>&nbsp;<input type=text name=\"accompanist\" class=tiny size=30 value=\"$accompanist\">&nbsp;&nbsp;";
   echo "<input type=checkbox name=\"noaccompanist\" value='x'";
   if($none=='x') echo " checked";
   echo ">&nbsp;None<br>(You must enter the accompanist's name OR check \"none\" if there is none.)";
   echo "<br>";
}
if($ensemble=="Jazz Band" || $ensemble=="Madrigal" || $ensemble=="Show Choir" || $smlg=="Small")
   echo "<br><b>Please check the names of the students in this $ensemble.</b><br>";
else
   echo "<br><b>It is optional to check the names of the students in this $ensemble,  but you must include the number of students in this group.</b><br>";
if($entryid>0 && CountStudentsInEntry($entryid)>0)
   echo "<i>(You currently have ".CountStudentsInEntry($entryid)." students checked below for this $ensemble.)</i><br>";
if($studcount=='0')	//unspecified number of students: note that max is 24
{
   if($smlg=="Small")
      echo "<br><font style=\"color:red\"><b>NOTE:</b> you may enter a maximum of <u>24 students</u> in this ensemble.</font>";
}
echo "<br><font style=\"font-size:8pt;\"><i>(Make sure to click \"Save Student Entries\"!)</i></font>";
echo "</td></tr></table></caption>";
$results=array(); $ix=0;	//create array of results to put in correct order according to $sort

//MAIN SQL:
if(ereg("Public Schools",$school) || ereg("College",$school))
   $school2=addslashes("Test's School");
if(IsCooping($school,$vocinst))
{  
   $schools=GetMusicCoopSchools($school,$vocinst);
   $schoolsql="(";
   for($i=0;$i<count($schools);$i++)
   {
      $cursch=addslashes($schools[$i]);
      $schoolsql.="school='$cursch' OR ";
   }
   $schoolsql=substr($schoolsql,0,strlen($schoolsql)-4);
   $schoolsql.=")";
}
else
   $schoolsql="school='$school2'";
if($vocinst=="Vocal")
{
   $sql="SELECT id,first,last,semesters,school,vm,im FROM eligibility WHERE $schoolsql AND eligible='y' AND vm='x'";
   $studentlist="Vocal";
}
else if($vocinst=="Instrumental" && !ereg("Piano",$ensemble))
{
   $sql="SELECT id,first,last,semesters,school,vm,im FROM eligibility WHERE $schoolsql AND eligible='y' AND im='x'";
   $studentlist="Instrumental";
}
else if(ereg("Misc",$ensemble))
{
   $sql="SELECT id,first,last,semesters,school,vm,im FROM eligibility WHERE $schoolsql AND eligible='y' AND (im='x' OR vm='x')";
   $studentlist="Instrumental";
}
else	//Piano
{
   if(IsCooping($school,"Vocal") && !IsCooping($school,"Instrumental"))
   {
      $schools=GetMusicCoopSchools($school,"Vocal");
      $sql="SELECT id,first,last,semesters,school FROM eligibility WHERE eligible='y' AND (((";
      for($i=0;$i<count($schools);$i++)
      {
         $cursch2=addslashes($schools[$i]);
         $sql.="school='$cursch2' OR ";
      }
      $sql=substr($sql,0,strlen($sql)-4);
      $sql.=") AND vm='x') OR (school='$school2' AND im='x'))";
   }
   else if(IsCooping($school,"Instrumental") && !IsCooping($school,"Vocal"))
   {
      $schools=GetMusicCoopSchools($school,"Instrumental");
      $sql="SELECT id,first,last,semesters,school FROM eligibility WHERE eligible='y' AND (((";
      for($i=0;$i<count($schools);$i++)
      {
         $cursch2=addslashes($schools[$i]);
         $sql.="school='$cursch2' OR ";
      }
      $sql=substr($sql,0,strlen($sql)-4);
      $sql.=") AND im='x') OR (school='$school2' AND vm='x'))";      
   }
   else if(IsCooping($school,"Instrumental") && IsCooping($school,"Vocal"))
   {
      $schools=GetMusicCoopSchools($school,"Instrumental"); //would be same schools if used "Vocal"
      $sql="SELECT id,first,last,semesters,school FROM eligibility WHERE eligible='y' AND (";
      for($i=0;$i<count($schools);$i++)
      {
         $cursch2=addslashes($schools[$i]);
         $sql.="school='$cursch2' OR ";
      }
      $sql=substr($sql,0,strlen($sql)-4);
      $sql.=") AND (vm='x' OR im='x')";
   }
   else	//not co-oping
      $sql="SELECT id,first,last,semesters,school FROM eligibility WHERE school='$school2' AND eligible='y' AND (vm='x' OR im='x')";
   $studentlist="Vocal & Instrumental";
}
if(ereg("Boys",$ensemble)) 
{
   $sql.=" AND gender='M'"; $gender="boys";
}
else if(ereg("Girls",$ensemble)) 
{
   $sql.=" AND gender='F'"; $gender="girls";
}
//else $gender="boys and girls";
else $gender="Students";
$sort="semesters";
if(!$sort || $sort=='') $sql.=" ORDER BY school,last,first,semesters";
else if($sort=='semesters') $sql.=" ORDER BY school,semesters,last,first";
$result=mysql_query($sql);
$ix=0;
echo "<tr align=left><td colspan=4>The <b>$gender</b> from the <b>$studentlist Music</b> eligibility list are listed below, ordered by grade first and then last name:</td></tr>";
echo "<tr align=center><td><b>Check</b><br /><input type='checkbox' name='checkall' value='x'> Check All</td>";
if(IsCooping($school,"Vocal") || IsCooping($school,"Instrumental"))
   echo "<td><b>School</b></td>";
echo "<td><b>Student Name</b><td><b>Grade</b></td></tr>";
while($row=mysql_fetch_array($result))
{
   //GET CURRENT MU ASSIGNMENTS FOR THIS STUDENT, IF ANY

   //CHECK IF THIS STUDENT IS ALREADY ENTERED IN THIS ENSEMBLE:
   $sql2="SELECT * FROM mustudentries WHERE entryid='$entryid' AND studentid='$row[id]'";
   $result2=mysql_query($sql2);
   echo "<tr align=left>";
   echo "<input type=hidden name=\"studentid[$id]\" value=\"$row[id]\">";
   echo "<td align=center><input type='checkbox' name=\"check[$ix]\" value='x'";
   if(mysql_num_rows($result2)>0) echo " checked";
   else if($check[$ix]=='x') echo " checked";
   echo "></td>";
   if(IsCooping($school,"Vocal") || IsCooping($school,"Instrumental"))
      echo "<td>$row[school]</td>";
   echo "<td>$row[first] $row[last]</td>";
   echo "<td align=center>".GetYear($row[semesters])."</td>";
   echo "</tr>";
   $ix++;
   if($ix%10==0 && mysql_num_rows($result)>=15) 
      echo "<tr align=center><td colspan=4><input type=submit name=save value=\"Save Student Entries\"></td></tr>";
}
echo "</table>";
echo "<br><input type=submit name=save value=\"Save Student Entries\">";
echo "</form>";
echo $end_html;
?>
