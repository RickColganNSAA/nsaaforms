<?php
/********************************************************
tourndates.php
NSAA can edit the dates for each activity (for app to host,
app to officiate, lodging)
Created 7/16/14
Author: Ann Gaffigan
*********************************************************/

require 'functions.php';
require 'variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php");
   exit();
}

echo $init_html;
echo GetHeader($session,"duedates");

echo "<form method=post action=\"tourndates.php\">
	<input type=hidden name=\"session\" value=\"$session\">";
echo "<br /><h2>Manage Postseason Dates</h2><p><b><i>for Applications to Host and Applications to Officiate</i></b></p>";

//SELECT SPORT
echo "<h3>Select Activity: <select name=\"sport\" onChange=\"submit();\"><option value=\"\">Select an Activity</option>";
$sql="SHOW TABLES LIKE '%tourndates'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $cursport=preg_replace("/(tourndates)/","",$row[0]);
   if($cursport!='sp' && $cursport!='pp')
   {
      echo "<option value=\"$cursport\"";
      if($sport==$cursport) echo " selected";
      echo ">".GetSportName($cursport)."</option>";
   }
}
echo "</select></h3>";

if($sport && $sport!='')
{
   $table=$sport."tourndates";
   $sql="DESCRIBE $table";
   $result=mysql_query($sql);
   $usinggender=0;	//DO WE CARE ABOUT GENDER FOR THIS SPORT?
   while($row=mysql_fetch_array($result))
   {
      if($row['Field']=="girls") $usinggender=1;
   }
   if($resethost==1)	//RESET HOST APP "interested" field
   {
      $reseterror="";
      if($usinggender==1 && $sport!='so')
      {
         $sql="UPDATE ".$db_name.".hostapp_".$sport."_g SET interested=''";
	 $result=mysql_query($sql);
	 if(mysql_error()) $reseterror="ERROR: ".mysql_error()."<br>"; 
         $sql="UPDATE ".$db_name.".hostapp_".$sport."_b SET interested=''";
         $result=mysql_query($sql);
         if(mysql_error()) $reseterror="ERROR: ".mysql_error()."<br>"; 
      }
      else
      {
         $sql="UPDATE ".$db_name.".hostapp_".$sport." SET interested=''";
         $result=mysql_query($sql);
         if(mysql_error()) $reseterror="ERROR: ".mysql_error()."<br>"; 
      }
   }
   if($adddate)
   {
      $errors="";
      if($newm=="00" || $newd=="00")
         $errors.="<p>Please enter the Month, Day and Year.</p>";
      if($usinggender==1 && $newgirls!="x" && $newboys!="x")
	 $errors.="<p>Please check Girls Tournament Date and/or Boys Tournament Date.</p>";
      if($newhostdate!='x' && $newoffdate!='x' && $newlodgingdate!='x')
         $errors.="<p>Please select at least one of: Include on Application to Host, Include on Application to Officiate or This is a Lodging Date for the State Officials' Contract.</p>";
      $tourndate="$newy-$newm-$newd";
      $sql="SELECT * FROM $table WHERE tourndate='$tourndate' AND label='".addslashes($newlabel)."'";
      if($usinggender==1)
         $sql.=" AND girls='$newgirls' AND boys='$newboys'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
         $errors.="<p>This date is already in the system for ".GetSportName($sport)."</p>";
      if($errors=="")
      {
         $sql="INSERT INTO $table (tourndate,";
         if($usinggender==1) $sql.="girls,boys,";
	 $sql.="label,hostdate,offdate,lodgingdate,labelonly) VALUES ('$tourndate',";
         if($usinggender==1) $sql.="'$newgirls','$newboys',";
	 $sql.="'".addslashes($newlabel)."','$newhostdate','$newoffdate','$newlodgingdate','$newlabelonly')";
         $result=mysql_query($sql);
	 echo mysql_error();
      } 
   }
   else if($save)
   {
      for($i=0;$i<count($id);$i++)
      {
	 if($delete[$i]=='x')
            $sql="DELETE FROM $table WHERE id='$id[$i]'";
         else
	 {
            $tourndate="$year[$i]-$mo[$i]-$day[$i]";
            $sql="UPDATE $table SET tourndate='$tourndate',";
            if($usinggender==1)
	       $sql.="girls='$girls[$i]',boys='$boys[$i]',";
	    $sql.="label='".addslashes($label[$i])."',hostdate='$hostdate[$i]',offdate='$offdate[$i]',lodgingdate='$lodgingdate[$i]',labelonly='$labelonly[$i]' WHERE id='$id[$i]'";
	 }
	 $result=mysql_query($sql);
echo mysql_error();
      }
   }

   //SHOW TABLE OF EXISTING DATES:
   if($save)
      echo "<div class=\"alert\" style=\"width:400px;\">Your changes have been saved.</div><br />";
   $sql="SELECT * FROM $table ORDER BY tourndate ASC,id";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
   {
      echo "<p><i>No ".GetSportName($sport)." dates have been entered at this time.</i></p>";
   }
   else
   {
      echo "<table cellspacing=0 cellpadding=3 frame=\"all\" rules=\"all\" style=\"border:#808080 1px solid;\">";
      echo "<caption><p style=\"text-align:left;\"><a onClick=\"return confirm('Are you sure you want to reset the applications to host?');\" href=\"tourndates.php?resethost=1&session=$session&sport=$sport\">RESET ".GetSportName($sport)." Applications to Host</a> (This will reset the <i>\"Interested? Yes or No\"</i> field, not the other fields on the form)</p><br>";
      if($resethost==1 && $reseterror!='') echo "<div class='error'>$reseterror</div>";
      else if($resethost==1) echo "<div class='alert'>The ".GetSportName($sport)." applications to host have been reset.</div>";
      echo "</caption>";
      echo "<tr align=center><td><b>Date</b></td>";
      if($usinggender==1)
      {
         echo "<td><b>Girls</b></td><td><b>Boys</b></td>";
      }
      echo "<td><b>Label</b></td><td><b>App to Host</b><br>";
      if($usinggender==1 && $sport!='so')
      {
         echo "<a href=\"../hostapp_".$sport."_g.php?sample=1&session=$session&nsaa=1\" target=\"_blank\" class=\"small\">Preview Girls</a><br>"; 
         echo "<a href=\"../hostapp_".$sport."_b.php?sample=1&session=$session&nsaa=1\" target=\"_blank\" class=\"small\">Preview Boys</a>";
      }
      else
         //echo "<a href=\"../hostapp_".$sport.".php?sample=1&session=$session&nsaa=1\" target=\"_blank\" class=\"small\">Preview</a>";
         echo "<a href=\"../hostapp_".$sport.".php?sample=1&session=$session&nsaa=1\" target=\"_blank\" class=\"small\">Preview</a>";
      echo "</td><td><b>App to Off</b>";
      if($sport=='pp') $sport2="play";
      else if($sport=='sp') $sport2="speech";
      else $sport2=$sport;
      echo "<br><a href=\"".$sport2."app.php?session=$session\" target=\"_blank\" class=\"small\">Preview</a>";
      echo "</td><td><b>Lodging</b><br>";
      if($usinggender==1)
      {
         echo "<a href=\"".$sport."gstatecontract.php?session=$session&sample=1\" target=\"_blank\" class=\"small\">Preview Girls State Contract</a><br>";
         echo "<a href=\"".$sport."bstatecontract.php?session=$session&sample=1\" target=\"_blank\" class=\"small\">Preview Boys State Contract</a>";
      }
      else
      {
         echo "<a href=\"".$sport."statecontract.php?session=$session&sample=1\" target=\"_blank\" class=\"small\">Preview State Contract</a>";
	 if($sport=='wr')
            echo "<br><a href=\"".$sport."statedualcontract.php?session=$session&sample=1\" target=\"_blank\" class=\"small\">Preview State Dual Contract</a>";
      }
      echo "</td><td><b>Label<br>Overrides<br>Date</b></td><td><b>Delete</b></td></tr>";
      $ix=0;
      while($row=mysql_fetch_array($result))
      {
         $date=explode("-",$row[tourndate]);
         echo "<tr align=center><td";
         if($date[1]=="00" || $date[2]=="00" || $date[0]=="0000") echo " bgcolor='#ff0000'";
         echo "><input type=hidden name=\"id[$ix]\" value=\"$row[id]\"><select name=\"mo[$ix]\"><option value=\"00\">MM</option>";
   	 for($i=1;$i<=12;$i++)
   	 {
      	    if($i<10) $m="0".$i;
      	    else $m=$i;
      	    echo "<option value=\"$m\"";
      	    if($date[1]==$m) echo " selected";
      	    echo ">$m</option>";
   	 }
         echo "</select>/<select name=\"day[$ix]\"><option value=\"00\">DD</option>";
         for($i=1;$i<=31;$i++)
         {
            if($i<10) $d="0".$i;
            else $d=$i;
            echo "<option value=\"$d\"";
            if($date[2]==$d) echo " selected";
            echo ">$d</option>";
         }
         echo "</select>/<select name=\"year[$ix]\"><option value=\"0000\">YYYY</option>";
         $year1=date("Y")-1; $year2=date("Y")+1;
         for($i=$year1;$i<=$year2;$i++)
         {
            echo "<option value=\"$i\"";
            if($date[0]==$i) echo " selected";
            echo ">$i</option>";
         }
	 echo "</select></td>";
         if($usinggender==1)
	 {
	    echo "<td";
	    if($row[girls]!='x' && $row[boys]!='x') echo " bgcolor='#ff0000'";
	    echo "><input type=checkbox name=\"girls[$ix]\" value=\"x\"";
	    if($row[girls]=='x') echo " checked";
	    echo "></td>";
            echo "<td";
   	    if($row[girls]!='x' && $row[boys]!='x') echo " bgcolor='#ff0000'";
	    echo "><input type=checkbox name=\"boys[$ix]\" value=\"x\"";
            if($row[boys]=='x') echo " checked";
            echo "></td>";
	 }
         echo "<td><input type=text size=25 name=\"label[$ix]\" value=\"$row[label]\"></td>";
         echo "<td";
         if($row[hostdate]!='x' && $row[offdate]!='x' && $row[lodgingdate]!='x') echo " bgcolor='#ff0000'";
	 echo "><input type=checkbox name=\"hostdate[$ix]\" value=\"x\"";
         if($row[hostdate]=='x') echo " checked";
         echo "></td>";
         echo "<td";
         if($row[hostdate]!='x' && $row[offdate]!='x' && $row[lodgingdate]!='x') echo " bgcolor='#ff0000'";
    	 echo "><input type=checkbox name=\"offdate[$ix]\" value=\"x\"";
         if($row[offdate]=='x') echo " checked";
         echo "></td>";
         echo "<td";
         if($row[hostdate]!='x' && $row[offdate]!='x' && $row[lodgingdate]!='x') echo " bgcolor='#ff0000'";
	 echo "><input type=checkbox name=\"lodgingdate[$ix]\" value=\"x\"";
         if($row[lodgingdate]=='x') echo " checked";
         echo "></td>";
         echo "<td><input type=checkbox name=\"labelonly[$ix]\" value=\"x\"";
	 if($row[labelonly]=='x') echo " checked";
	 echo "></td>";
         echo "<td><input type=checkbox name=\"delete[$ix]\" value=\"x\"></td></tr>";
	 $ix++;
      }
      echo "</table>";
      echo "<br /><input type=submit name=\"save\" value=\"SAVE and DELETE CHECKED\"><br />";
   }	//END IF THERE ARE DATES ENTERED

   //ADD A NEW DATE
   echo "<div style=\"text-align:left;width:650px;\">";
   echo "<h3>Add a Date:</h3>";
   if($adddate && $errors!='')
   {
      echo "<div class=\"error\"><p><b>ERROR:</b></p>".$errors."</div>";
   }
   else if($save || $adddate)	//reset vars
   {
      foreach($_REQUEST as $key => $value)
      {
	 unset($$key);
      }
   }
   echo "<p><b>Date:</b> <select name=\"newm\"><option value=\"00\">MM</option>";
   for($i=1;$i<=12;$i++)
   {
      if($i<10) $m="0".$i;
      else $m=$i;
      echo "<option value=\"$m\"";
      if($newm==$m) echo " selected";
      echo ">$m</option>";
   }
   echo "</select>/<select name=\"newd\"><option value=\"00\">DD</option>";
   for($i=1;$i<=31;$i++)
   {
      if($i<10) $d="0".$i;
      else $d=$i;
      echo "<option value=\"$d\"";
      if($newd==$d) echo " selected";
      echo ">$d</option>";
   }
   echo "</select>/<select name=\"newy\"><option value=\"0000\">YYYY</option>";
   $year1=date("Y"); $year2=date("Y")+1;
   for($i=$year1;$i<=$year2;$i++)
   {
      echo "<option value=\"$i\"";
      if($newy==$i) echo " selected";
      echo ">$i</option>";
   }
   echo "</select></p>";
   if($usinggender==1)
   {
      echo "<p><input type=checkbox name=\"newgirls\" value=\"x\"";
      if($newgirls=='x') echo " checked";
      echo "> <b>Girls</b> Tournament Date";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=\"newboys\" value=\"x\"";
      if($newboys=='x') echo " checked";
      echo "> <b>Boys</b> Tournament Date</p>";
   }
   echo "<p><b>Label:</b> <input type=text size=40 name=\"newlabel\" value=\"$newlabel\"> (ex: \"A/B Districts\")
	<ul>
	<li>If left blank, this date will just be labeled with the date itself, e.g. \"May 3\")</li>
	<li>Please label <b>STATE</b> dates (including lodging dates) with \"State\" (or \"State Dual\" for Wrestling).</li>";
   echo "<li><input type=checkbox name=\"newlabelonly\" value=\"x\"";
   if($newlabelonly=='x') echo " checked";
   echo "> <b>Label <u>overrides</u> the date</b> (i.e., show the label on the form instead of the date) - HOST APP ONLY<br />(If left unchecked, date AND label will be shown.)</li></ul>";
   echo "<p><input type=checkbox name=\"newhostdate\" value=\"x\"";
   if($newhostdate=='x') echo " checked";
   echo "> Include on <b>Application to Host</b>";
   echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=\"newoffdate\" value=\"x\"";
   if($newoffdate=='x') echo " checked";
   echo "> Include on <b>Application to Officiate</b></p>";
   echo "<p><input type=checkbox name=\"newlodgingdate\" value=\"x\"";
   if($newlodgingdate=='x') echo " checked";
   echo "> This is a <b>Lodging Date</b> for the State Officials' Contract.</p>";
   echo "<p><input type=submit name=\"adddate\" value=\"Add Date\"></p>";
   echo "</div>";
}	//END IF $sport
else
{
   echo "<br /><p><i>Please select an activity above.</i></p>";
}

echo "</form>";

echo $end_html;
?>
