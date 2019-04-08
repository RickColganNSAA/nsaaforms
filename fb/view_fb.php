<?php
//view_fb.php: main football entry form page; get to stats form
//	and state form from here
require '../../calculate/functions.php';
require '../functions.php';
require '../variables.php';

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

$level=GetLevel($session);
if($level==1 && $school_ch)
{
   $school=$school_ch;
}
else
{
   $school=GetSchool($session);
}
$school2=ereg_replace("\'","\'",$school);
$schoolid=GetSchoolID2($school);
$sid=GetSID2($school,'fb');
$sport='fb';

if($level==1 && $save)
{
   //SUPER/PRINCIPAL/AD
   for($i=0;$i<count($loginid);$i++)
   {
      $sql="UPDATE logins SET name='".addslashes($name[$i])."' WHERE id='$loginid[$i]'";
      $result=mysql_query($sql);
   }

   //ENROLLMENT
   $sql="UPDATE headers SET enrollment='$enrollment' WHERE id='$schoolid'";
   $result=mysql_query($sql);

   //HISTORICAL INFO
   $sql="UPDATE fbschool SET tripstostate='$tripstostate',mostrecent='$mostrecent',championships='$championships',runnerup='$runnerup' WHERE sid='$sid'";
   $result=mysql_query($sql);

    if(!empty($_FILES["imageUpload"]["name"])){
	$image = $_FILES["imageUpload"]["name"];
	$target_dir = $_SERVER['DOCUMENT_ROOT']."/nsaaforms/downloads/";
	$target_file = $target_dir . basename($image);
	$refFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	citgf_moveuploadedfile($_FILES["imageUpload"]["tmp_name"], $target_file);
	$image = mysql_real_escape_string($image);
	$sql="UPDATE fbschool SET filename='$image'WHERE sid='$sid'";
    $result=mysql_query($sql);		
    }
   //WRITE EXPORTS
   WriteFBExports($school);
}//END IF LEVEL=1

echo $init_html;
echo GetHeader($session);

if($level==1)
   echo "<br><a class=small href=\"../welcome.php?session=$session&toggle=menu3&menu3sport=Football\">Return to Home-->Football Entry Forms</a>";

?>
<br><br>
<form method="POST" action="view_fb.php" enctype='multipart/form-data'>
<input type=hidden name="session" value="<?php echo $session; ?>">
<input type=hidden name="school_ch" value="<?php echo $school_ch; ?>">
<table width=50%>
<caption><b>
<?php
if($level==1) echo "$school ";
?>
NSAA Football Forms:</b><br>
</caption>
<!--
<tr align=left valign=top>
<td><a href="view_fb_stats.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>&type=off">Statistics Report Form</a>
<br><i>You may update this form with <b>cumulative</b> statistics as the season goes on.  It is optional but recommended, as it will allow you to report your team's stats one time, in one place.  The statistics you report will be made available to the public via the NSAA website.
</i></td>
</tr>
-->
<tr align=left valign=top>
<td><br><a href="view_fb_state.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>">State Playoff Roster Form</a>
<br><font size=2 style="color:red"><b><i>This form is required and is due by <u>10 am</u> the day after your team qualifies for State Semifinals, along with your <u>team photo</u>.</font></b>  <b><u>The team photo should be uploaded below</b></u> and should be a <b><u>JPEG file of at least 300dpi</u></b>.  Black-and-white or color photos are acceptable.</i></b></font></td>
</tr>
<?php
/****** TEAM PHOTO UPLOAD ******/
$sql="SELECT * FROM fbschool WHERE sid='$sid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$filename=$row[filename];
?>
<tr align=left><th><br>Team Photo:</th></tr>
<tr align=left><td><!--<p>Please click "Add" to find your file. Then click <b><u>"Upload"</u></b>. When you see full green status lines and the words "Upload has been completed" you can click "Save" at the bottom to preview your uploaded photo.</p>-->
<?php if($filename!=''): ?>
<p><a href="/nsaaforms/downloads/<?php echo $filename; ?>" target="_blank">Preview Team Photo</a></p>
<?php endif; ?>
<!--<iframe style="width:430px;height:175px;" src="simpleupload.php?session=<?php echo $session; ?>&sid=<?php echo $sid; ?>" frameborder='0'></iframe><p><i>Once your file has finished uploading, click "Save" below.</i></p></td></tr>-->
   <input type="file" name="imageUpload" id="imageUpload"></p>
   </td></tr>
<?php
if($level==1)
{
       $sql_id="SELECT * FROM headers WHERE school='$school2'";
      $result_id=mysql_query($sql_id);
      $row_id=mysql_fetch_array($result_id);
      
	  $sql_coop="SELECT * FROM fbschool WHERE mainsch='$row_id[id]' AND (othersch1!='' OR othersch2!='' OR othersch3!='') ";
      $result_coop=mysql_query($sql_coop);
      $row_coop=mysql_fetch_array($result_coop);
	  if (!empty($row_coop[mainsch])) $coop_info[]=$row_coop[mainsch];
	  if (!empty($row_coop[othersch1])) $coop_info[]=$row_coop[othersch1];
	  if (!empty($row_coop[othersch2])) $coop_info[]=$row_coop[othersch2];
	  if (!empty($row_coop[othersch3])) $coop_info[]=$row_coop[othersch3];
	  //echo '<pre>'; print_r($coop_info); 
	  //$enroll=0;
	  foreach ($coop_info as $info)
	  {
	  $sql_school="SELECT * FROM headers WHERE id='$info'";
      $result_school=mysql_query($sql_school);
      $row_school=mysql_fetch_array($result_school);
	  
	  $sql="SELECT name FROM logins WHERE school='$row_school[school]' AND sport='Superintendent'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
	  $super[] = $row[name];
	  
	  $sql="SELECT id,name FROM logins WHERE school='$row_school[school]' AND sport='Principal'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
	  $prin[] = $row[name];
	  
	  $sql="SELECT id,name FROM logins WHERE school='$row_school[school]' AND level='2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
	  $ad[]= $row[name];
	  
	  $sql="SELECT * FROM headers WHERE school='$row_school[school]' ";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
	  $enroll=$enroll+$row[enrollment];
	  
	  }
	  
	  $super=implode(", ",$super);
	  $prin=implode(", ",$prin);
	  $ad=implode(", ",$ad);
	  //HISTORICAL INFO:
      $ix=0;
        //Superintendent
      $sql="SELECT id,name FROM logins WHERE school='$school2' AND sport='Superintendent'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
	  if (!empty($super))
      echo "<tr align=\"left\"><td><b>Superintendent:</b> <input type=hidden name=\"loginid[$ix]\" value=\"$row[id]\"><input type=text name=\"name[$ix]\" value=\"$super\" size=30></td></tr>";
      else
      echo "<tr align=\"left\"><td><b>Superintendent:</b> <input type=hidden name=\"loginid[$ix]\" value=\"$row[id]\"><input type=text name=\"name[$ix]\" value=\"$row[name]\" size=30></td></tr>";
        //Principal
      $ix++;
      $sql="SELECT id,name FROM logins WHERE school='$school2' AND sport='Principal'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if (!empty($prin))
      echo "<tr align=\"left\"><td><b>Principal:</b> <input type=hidden name=\"loginid[$ix]\" value=\"$row[id]\"><input type=text name=\"name[$ix]\" value=\"$prin\" size=30></td></tr>";
      else
      echo "<tr align=\"left\"><td><b>Principal:</b> <input type=hidden name=\"loginid[$ix]\" value=\"$row[id]\"><input type=text name=\"name[$ix]\" value=\"$row[name]\" size=30></td></tr>";
        //AD
      $ix++;
      $sql="SELECT id,name FROM logins WHERE school='$school2' AND level='2'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if (!empty($ad))
      echo "<tr align=\"left\"><td><b>Athletic Director:</b> <input type=hidden name=\"loginid[$ix]\" value=\"$row[id]\"><input type=text name=\"name[$ix]\" value=\"$ad\" size=30></td></tr>";
      else
      echo "<tr align=\"left\"><td><b>Athletic Director:</b> <input type=hidden name=\"loginid[$ix]\" value=\"$row[id]\"><input type=text name=\"name[$ix]\" value=\"$row[name]\" size=30></td></tr>";
        //Enrollment
      $sql="SELECT * FROM headers WHERE id='$schoolid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $enrollment=$row[enrollment];
	  if (!empty($enroll))
      echo "<tr align=\"left\"><td><b>NSAA Enrollment:</b> <input type=text name=\"enrollment\" value=\"$enroll\" size=5></td></tr>";
      else
      echo "<tr align=\"left\"><td><b>NSAA Enrollment:</b> <input type=text name=\"enrollment\" value=\"$enrollment\" size=5></td></tr>";
      $sql="SELECT * FROM ".GetSchoolsTable($sport)." WHERE sid='$sid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
        //Trips to State: 4
      echo "<tr align=\"left\"><td><b>Playoff Appearances:</b> <input type=text name=\"tripstostate\" size=10 value=\"$row[tripstostate]\"></td></tr>";
        //Most Recent: 2012
      echo "<tr align=\"left\"><td><b>Most Recent Playoff Appearance:</b> <input type=text name=\"mostrecent\" size=10 value=\"$row[mostrecent]\"></td></tr>";
        //Championships: None
      echo "<tr align=\"left\"><td><b>Championships:</b> <input type=text name=\"championships\" size=40 value=\"$row[championships]\"></td></tr>";
        //Runner-up: B/2008, B/2010
      echo "<tr align=\"left\"><td><b>Runner-up:</b> <input type=text name=\"runnerup\" size=40 value=\"$row[runnerup]\"></tr>";
}
echo "<tr align=left><td><input type=submit name=\"save\" value=\"Save\"></td></tr>";
?>
</form>
<form method=post action="programpdf.php" target="_blank">
<input type=hidden name=session value="<?php echo $session; ?>">
<input type=hidden name=school_ch value="<?php echo $school_ch; ?>">
<tr align=left><td><br/><input type=submit name="generate" value="Generate PDF"> <i>PDF not working? Check the school logo in the <a class=small href="../directory.php?session=<?php echo $session; ?>&school_ch=<?php echo $school_ch; ?>" target="_blank"><?php echo $school_ch; ?> School Directory</a></i></td></tr>
</form>
</table>

</td>
</tr>
</table>
</body>
</html>
