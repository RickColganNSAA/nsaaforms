<?php
require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

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

$tables=array("cc_classd","declaration","eligibility","finance_bb_b","finance_bb_b_exp","finance_bb_g","finance_bb_g_exp","finance_fb","finance_vb","finance_vb_exp","forex","headers","hostapp_ba","hostapp_bb_b","hostapp_bb_g","hostapp_cc","hostapp_go_b","hostapp_go_g","hostapp_pp","hostapp_sb","hostapp_so","hostapp_sp","hostapp_tr","hostapp_vb","hostapp_wr","largeschools","logins","messages","registration");

$error=0;
if($addschool)
{
   $newschool2=addslashes($newschool);
   $newad2=addslashes($newad);
   $sql="SELECT * FROM headers WHERE school='$newschool2'";
   $result=mysql_query($sql);
   $newschoolerror=0; $addmessage="";
   if(mysql_num_rows($result)>0)	//already have a school with this name
   {
      $error=1; $newschoolerror=1;
      $addmessage="<font style=\"color:red\"><b>$newschool</b> is already in the school database.</font>";
   }
   else	//add new school AND generate password for AD
   {
      $sql="INSERT INTO headers (school,color1,color2) VALUES ('$newschool2','black','white')";
      $result=mysql_query($sql);
      $temp=split(" ",$newad);
      $names=count($temp); $names--;
      $passcode=strtolower($temp[$names]);
      $passcode=ereg_replace("\'","",$passcode);
      $num=rand(1000,9999);
      $passcode.=$num;
      $sql="INSERT INTO logins (name,school,level,passcode) VALUES ('$newad2','$newschool2','2','$passcode')";
      $result=mysql_query($sql);
      $addmessage="<font style=\"color:blue\"><b>$newschool</b> has been added to the database.  The passcode for $newschool's AD, <b>$newad</b>, is \"$passcode\".</font>";
      //Go ahead and insert the Football coach fields so they don't get messed up the first time they visit the Directory
      $sql="INSERT INTO logins (school,level,sport) VALUES ('$newschool2','3','Football 6/8')";
      $result=mysql_query($sql);
      $sql="INSERT INTO logins (school,level,sport) VALUES ('$newschool2','3','Football 11')";
      $result=mysql_query($sql);
   } 
} 
else if($merge)
{
   $headsch2=ereg_replace("\'","\'",$headsch);
   $othersch2=ereg_replace("\'","\'",$othersch);
   for($i=0;$i<count($tables);$i++)
   {
         //change rows with head school to new co-op
         $sql="UPDATE $tables[$i] SET school='$headsch2/$othersch2' WHERE school='$headsch2'";
         $result=mysql_query($sql);
         //Eligibility table: also change other school to new co-op;
         //Non-eligibility tables: remove entries from other school
         if($tables[$i]=="eligibility")
            $sql="UPDATE $tables[$i] SET school='$headsch2/$othersch2' WHERE school='$othersch2'";
         else
            $sql="DELETE FROM $tables[$i] WHERE school='$othersch2'";
         $result=mysql_query($sql);
   }
   $change=1;
   $mergemessage="<font style=\"color:blue\"><b>$headsch</b> and <b>$othersch</b> have been merged to form <b>\"$headsch/$othersch\"</b>.  You can edit this new school's name below in the \"Change an existing school's name\" section.</font>";
}
else if($editschool)
{
   $schname2=addslashes($schname);
   $newname2=addslashes($newname);
   for($i=0;$i<count($tables);$i++)
   {
         $sql="UPDATE $tables[$i] SET school='$newname2' WHERE school='$schname2'";
         $result=mysql_query($sql);
   }
   $change=1;
   $changemessage="<font style=\"color:blue\"><b>$schname</b> has been changed to <b>$newname</b>.</font>";
}
else if($delschool)
{
   $delsch2=ereg_replace("\'","\'",$delsch);
   for($i=0;$i<count($tables);$i++)
   {
      $sql="DELETE FROM $tables[$i] WHERE school='$delsch2'";
      $result=mysql_query($sql);
   }
   $change=1;
   $delmessage="<font style=\"color:blue\"><b>$delsch</b> has been deleted from the database.</font>";
}
   
echo $init_html;
echo $header;

echo "<form method=post action=\"manageschools.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<br><br><table width=500><caption><b>Manage NSAA Schools (Eligibility Database):</b><hr></caption>";
echo "<tr align=center><td colspan=2><b>I want to:&nbsp;</b>";
echo "<select name=function onchange=\"submit();\"><option value=''>Select Option</option>";
echo "<option value=\"preview\"";
if($function=="preview") echo " selected";
echo ">Preview an Existing School</option><option value=\"addschool\"";
if($function=="addschool") echo " selected";
echo ">Add a New School</option><option value=\"merge\"";
if($function=="merge") echo " selected";
echo ">Merge 2 Existing Schools</option><option value=\"editschool\"";
if($function=="editschool") echo " selected";
echo ">Change an Existing School's Name</option><option value=\"delschool\"";
if($function=="delschool") echo " selected";
echo ">Delete an Existing School</option></select></td></tr>";
$schools=array(); $ix=0;
$sql="SELECT school FROM headers ORDER BY school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $schools[$ix]=$row[0];
   $ix++;
}
if($function=="preview")
{
   echo "<tr align=left><th align=left colspan=2>Preview Information for a School:</th></tr>";
   echO "<tr align=left><td colspan=2>Select a school: <select name=schid><option value='0'>~</option>";
   $sql="SELECT * FROM headers ORDER BY school";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\"";
      if($schid==$row[id]) echo " selected";
      echo ">$row[school]</option>";
   }
   echo "</select><input type=submit name=preview value=\"Go\"></td></tr>";
   if($schid)
   {
      $sql="SELECT * FROM headers WHERE id='$schid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      echo "<tr align=left><td colspan=2><b>Main School Name:</b>&nbsp;&nbsp;$row[school]</td></tr>";
      $school=$row[school];
      $school2=addslashes($school);
      echo "<tr align=left><td colspan=2><b>Wildcard Sports:</b></td></tr>";
      echo "<tr align=center><td colspan=2><table cellspacing=0 cellpadding=1 border=1 bordercolor=#000000>";
      echo "<tr align=center><td><b>Sport</b></td><td><b>Team Name</b></td><td><b>Main School</b></td><td><b>Other School(s)</b></td></tr>";
      $sql="SHOW TABLES LIKE '%tourn'";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         $temp=split("tourn",$row[0]);
         $cursp=$temp[0];
         $schooltbl=GetSchoolsTable($cursp);
         $sql2="SELECT * FROM $schooltbl WHERE (mainsch='$schid' OR othersch1='$schid' OR othersch2='$schid' OR othersch3='$schid')";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         echo "<tr align=left><td><b>".GetActivityName($cursp)."</b></td>";
         echo "<td>$row2[school]&nbsp;</td>";
         $sql3="SELECT school FROM headers WHERE id='$row2[mainsch]'";
         $result3=mysql_query($sql3);
         $row3=mysql_fetch_array($result3);
         $mainsch=$row3[0];
         $sql3="SELECT school FROM headers WHERE (id='$row2[othersch1]' OR id='$row2[othersch2]' OR id='$row2[othersch3]')";
         $result3=mysql_query($sql3);
         $otherschs="";
         while($row3=mysql_fetch_array($result3))
         {
	    $otherschs.=$row3[school].", ";
         }
         $otherschs=substr($otherschs,0,strlen($otherschs)-2);
         if(trim($mainsch)=="") $mainsch="&nbsp;";
         if(trim($otherschs)=="") $otherschs="&nbsp;";
         echo "<td>$mainsch</td><td>$otherschs</td>";
         echo "</tr>";
      }
      echo "</table>";
      echo "<tr align=left><td colspan=2>(You can edit the Wildcard Schools in the <a href=\"../calculate/wildcard/wildcard.php?session=$session\" class=small>Wildcard Program</a>)</td></tr>";
   }//end if school chosen for preview
}//end if preview
else if($function=='addschool')
{
   echo "<tr align=left><th align=left colspan=2>Add a new school to the Main School Database:</th></tr>";
   if($addmessage!='')
      echo "<tr align=left><td colspan=2>$addmessage</td></tr>";
   echo "<tr align=left><td width=150><b>New School's Name:</b></td><td><input name=newschool type=text class=tiny size=30></td></tr>";
   echo "<tr align=left><td width=150><b>AD's Name:</b></td><td><input type=text class=tiny name=newad size=30></td></tr>";
   echo "<tr align=left><td colspan=2>A passcode will be generated for the AD of this new school.  Please give this passcode to the AD.  He or she will then need to login and go to the School Directory, where he/she can enter the school's address, colors, mascot, and logo.  The AD will also need to fill out the name, e-mail, phone, and passcode for each staff member.</td></tr>";
   echo "<tr align=left><td colspan=2><input type=submit name=addschool value=\"Add School\"></td></tr>";
}//end if addschool
else if($function=='merge')
{
   echo "<tr align=left><th align=left colspan=2>Merge 2 existing schools into one school:</th></tr>";
   echo "<tr align=left><td colspan=2>(NOTE: If you need to merge more than 2 schools, merge them 1 at a time.  For example: To merge schools 1, 2, and 3, first merge schools 1 and 2.  Then merge that new school with school 3.)</td></tr>";
   if($mergemessage!='') echo "<tr align=left><td colspan=2>$mergemessage</td></tr>";
   echo "<tr align=left><td><b>Head school:</b></td>";
   echo "<td><select name=headsch><option>Choose School";
   for($i=0;$i<count($schools);$i++)
   {
      echo "<option>$schools[$i]";
   }
   echo "</select></td></tr><tr align=left><td><b>Other School:</b></td><td>";
   echo "<select name=othersch><option>Choose School";
   for($i=0;$i<count($schools);$i++)
   {
      echo "<option>$schools[$i]";
   }
   echo "</select></td></tr>";
   echo "<tr align=center><td colspan=2><input type=submit name=merge value=\"Merge\"></td></tr>";
   echo "<tr align=left><td colspan=2>(The new name of your school will be \"[Head School]/[Other School]\".  You may edit this name by selecting \"Change an Existing School's Name\" above.)</td></tr>";
}//end if merge
else if($function=="editschool")
{
   //Change a school name:
   echo "<tr align=left><th colspan=2 align=left>Change an existing school's name:</th></tr>";
   if($changemessage!='') echo "<tr align=left><td colspan=2>$changemessage</td></tr>";
   echo "<tr align=left><td>Current Name:</td><td><select name=schname><option>Choose School";
   for($i=0;$i<count($schools);$i++)
   {
      echo "<option>$schools[$i]";
   }
   echo "</select></td></tr>";
   echo "<tr align=left><td>New Name:</td><td><input type=text name=newname size=40></td></tr>";
   echo "<tr align=center><td colspan=2><input type=submit name=editschool value=\"Change Name\"></td></tr>";
}//end if editschool
else if($function=='delschool')
{
   //Delete a school:
   echo "<tr align=left><th align=left colspan=2>Delete an existing school from the NSAA database:</th></tr>";
   if($delmessage!='') echo "<tr align=left><td colspan=2>$delmessage</td></tr>";
   echo "<tr align=left><td colspan=2><select name=delsch><option>Choose School";
   for($i=0;$i<count($schools);$i++)
   {
      echo "<option>$schools[$i]</option>";
   }
   echo "</select>&nbsp;<input type=submit name=delschool onclick=\"return confirm('Are you sure you want to delete this school?');\" value=\"Delete School\"></td></tr>";
}//end if delschool
echo $end_html;
?>
