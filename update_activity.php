<?php
//update_activity.php: allows user (usually AD, Level2 access)
//	to choose an activity and get a list of girls or boys
//	which they can check off if that person is participating
//	in the specified activity.  This is a speedier alternative
//	to scrolling through a list of all the kids in the school
//	and then moving across all the checkboxes on the screen
//	to get the right one, as is done with eligibility.php

require 'variables.php';
require 'functions.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if($submit=="Cancel")
{
  header("Location:eligibility.php?session=$session&activity_ch=$activity_ch&school_ch=$school_ch&last=a");
  exit();
}

echo $init_html.GetHeader($session)."<br>";
echo "<p><a href=\"eligibility.php?session=$session&activity_ch=$activity_ch&school_ch=$school_ch&last=a\">&larr; Return to Eligibility List</a></p><br>";

if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

if($submit=="Save")	
{
//input list of students into database as participants of said activity
   echo "<center><font size=2><br>You have submitted the following participants";
   echo " for <b>$activity_name</b>:<br><br><table cellpadding=5>";
   $ct=1;
   $new_swimmers=array();
   $swix=0;
   for($i=0;$i<count($id);$i++)
   {
      if($student[$i]=="x")
      {
	 //if Swimming, check if new student is being added
	 if(ereg("Swimming",$activity_name))
	 {
	    $sql="SELECT sw FROM eligibility WHERE id='$id[$i]'";
	    $result=mysql_query($sql);
	    $row=mysql_fetch_array($result);
	    $old_sw=$row[0];
	    if($old_sw!='x')	//new addition
	    {
	       $new_swimmers[$swix]=$id[$i];
	       $swix++;
	    }
	 }
	 $sql="UPDATE eligibility SET $activity_abbrev='x' WHERE id='$id[$i]'";
	 $result=mysql_query($sql);
	 if(mysql_error()) echo "$sql<br>".mysql_error()."<br><br>";
	 $sql="SELECT last, first, middle, semesters FROM eligibility WHERE id='$id[$i]'";
	 $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
         echo "<tr align=left><td>$ct. </td><td>$row[0], $row[1] $row[2] (".GetYear($row[semesters]).")</td></tr>";
	 $ct++;
      }
      else	//student is unchecked
      {
	 $sql="UPDATE eligibility SET $activity_abbrev='' WHERE id='$id[$i]'";
	 $result=mysql_query($sql);
      }
   }
   echo "</table><br>";
?>

<table width=60%><tr><td><p>*** Don't forget to mark if the student is ineligible or is a foreign exchange student, if that applies to them.  You can do this by going to the eligibility page and checking the appropriate boxes next to the students' names.</p></td></tr>
</table>

<?php
   echo "<a href=\"update_activity.php?session=$session&activity_ch=$activity_ch&school_ch=$school_ch\">Update by Activity Again</a>&nbsp;&nbsp;";
   echo "<a href=\"eligibility.php?session=$session&activity_ch=$activity_ch&school_ch=$school_ch&last=a\">Return to Eligibility Page</a>&nbsp;&nbsp;";
   echo "<a href=\"welcome.php?session=$session\">Return to Home</a>";
   if($swix>0)
   {
      //IF NEW SWIMMERS ADDED TO THE LIST, MARK IN DATABASE SO CINDY CAN CHECK THEM
      for($i=0;$i<$swix;$i++)
      {
         $sql="SELECT * FROM eligibility_sw WHERE studentid='$new_swimmers[$i]'";
         $result=mysql_query($sql);
         if(mysql_fetch_array($result)==0)
            $sql2="INSERT INTO eligibility_sw (studentid,dateadded) VALUES ('$new_swimmers[$i]','".time()."')";
         else
            $sql2="UPDATE eligibility_sw SET dateadded='".time()."' WHERE studentid='$new_swimmers[$i]'";
         $result2=mysql_query($sql2);
      }
   }
}
else if($submit=="Go")
{
//Display list of students with checkboxes next to them; lable the top
//of the list with the activity name
   //get abbreviation of activity_name
   $activity_abbrev=GetActivityAbbrev($activity_name);
?>
   <center>
	<table width='100%'><tr align=center><td>
	<h2>Update Eligibility List:</h2>
   <form method="post" action="update_activity.php">
   <input type=hidden name=session value=<?php echo $session; ?>>
   <input type=hidden name=activity_ch value="<?php echo $activity_ch; ?>">
   <input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
   <input type=hidden name=activity_abbrev value=<?php echo $activity_abbrev; ?>>
   <input type=hidden name=activity_name value="<?php echo $activity_name; ?>">
   <table cellspacing=0 cellpadding=5 frame=all rules=all style="border:#808080 1px solid;">
   <caption>Please check the box next to the students who are participating in <b>
   <?php echo $activity_name; ?></b> at <b><?php echo $school_ch; ?></b>
   <?php
   if($grade!="All") echo " and who are <b>$grade</b>";
   ?>
   :</caption>
   <tr align=center><th colspan=2>Name</th><th>Grade</th></tr>
   <?php
   //get gender of activity, if there is one:
   if(ereg("Girls",$activity_name) || $activity_name=="Volleyball" || $activity_name=="Softball")
      $gender="F";
   else if(ereg("Boys",$activity_name) || ereg("Football",$activity_name) || $activity_name=="Wrestling" || $activity_name=="Baseball")
      $gender="M";
   else	//any gender can participate in this activity
      $gender="all";

   $sql="SELECT id, first, last, middle, semesters, $activity_abbrev,eligible FROM eligibility WHERE school='$school2'";
   if(!ereg("Music",$activity_name))
   {
      $sql.=" AND semesters!=0";
   }
   if($grade!="All")
   {
      if($grade=="Freshmen")
	 $sql.=" AND (semesters=1 OR semesters=2)";
      else if($grade=="Sophomores")
	 $sql.=" AND (semesters=3 OR semesters=4)";
      else if($grade=="Juniors")
	 $sql.=" AND (semesters=5 OR semesters=6)";
      else if($grade=="Seniors")
	 $sql.=" AND (semesters=7 OR semesters=8)";
   }
   if($gender!="all")
   {
      $sql.=" AND gender='$gender'";
   }
   $sql.=" ORDER BY last";
   $result=mysql_query($sql);
   echo mysql_error();
   $ix=0; $ct=0;
   while($row=mysql_fetch_array($result))
   {
      echo "<tr";
      if($row[eligible]!='y') echo " bgcolor='#ff0000'";
      else if($ix%2==0) echo " bgcolor=#D0D0D0";
      echo "><td align=center><input type=checkbox name=\"student[$ix]\" value=\"x\" ";
      if($row[5]=="x")	//student is already listed as a participant
      {
	 echo "checked"; $ct++;
      }
      echo "><td align=left>&nbsp;$row[2], $row[1] $row[3]";
      echo "<input type=hidden name=\"id[$ix]\" value=\"$row[0]\"></td>";

      //get year in school of student:
      if($row[4]==1 || $row[4]==2) $year=9;
      else if($row[4]==3 || $row[4]==4) $year=10;
      else if($row[4]==5 || $row[4]==6) $year=11;
      else if($row[4]==7 || $row[4]==8) $year=12;
      else $year="<9";	//semester=0 indicates not in hs yet

      echo "<td align=center>$year</td></tr>";
      $ix++;
   }
?>
   </table>
   <br>
   <input type=submit name=submit value="Save">
   &nbsp;&nbsp;
   <input type=submit name=submit value="Cancel">
   </form>
<?php
}
else
{
//Display dropdown list of activities to choose from
//Also Display list of schools to choose from (if NSAA user)
?>
   <center>
   <form method="post" action="update_activity.php">
   <br><font size=2><i>Please choose the activity you wish to update, specify the grade if necessary,  and click "Go":</i><br><br></font>
   <input type=hidden name=session value=<?php echo $session; ?>>
   <input type=hidden name=activity_ch value="<?php echo $activity_ch; ?>">
   <input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<?php
   //get level of user
   $level=GetLevel($session);

   if($level==1)
   {
      $sql="SELECT school FROM headers ORDER BY school";
      $result=mysql_query($sql);
?>
      <select name=school_ch>
<?php
      while($row=mysql_fetch_array($result))
      {
	 echo "<option>$row[0]";
      }
?>
      </select>
      &nbsp;
<?php
   }
?>
   <select name=activity_name>
   <?php
   for($i=0;$i<count($act_long);$i++)
   {
      echo "<option>$act_long[$i]";
   }
   ?>
   </select>
   <select name=grade>
      <option>All Grades
      <option>Freshmen
      <option>Sophomores
      <option>Juniors
      <option>Seniors
   </select>
   <input type=submit name=submit value="Go">
   <input type=submit name=submit value="Cancel">
   </form>
<?php
}
?>
</td></tr></table>
</td><!--End Main Body-->
</tr>
</table>
</body>
</html>


