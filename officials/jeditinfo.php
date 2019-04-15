<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}

$offid=GetJudgeID($session);

//javascript for autoTab
?>
<script language="javascript">
<?php echo $autotab; ?>
</script>
<?php
if($deletephoto)
{
   $sql="SELECT * FROM judges WHERE id='$offid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $photofile=$row[photofile];
   citgf_unlink("photos/$photofile");
   $sql="UPDATE judges SET photofile='',photoapproved='' WHERE id='$offid'";
      $filename.=$offid;
   $result=mysql_query($sql);
}
if($upload)
{
   $filesize=$_FILES['photofile']['size'];
   if($filesize>500000)
   {
      $errormsg="ERROR: Your file is too large.  Please upload a file that is no larger than 300KB (kilobytes).";
   }
   else
   {
      $errormsg="";
      $sql="SELECT * FROM judges WHERE id='$offid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      //file name is first letter of first name then last name
      $origfilename=$_FILES['photofile']['name'];
      $temp=split("[.]",$origfilename);
      $filename=ereg_replace("[^A-Za-z]","",$row[first]);
      $filename=substr($filename,0,1);
      $filename.=ereg_replace("[^A-Za-z]","",$row[last]);
      $filename.=$offid;
      $filename.="J.".$temp[1];
      if(!citgf_copy($_FILES['photofile']['tmp_name'],"photos/$filename"))
         $errormsg="ERROR: File could not be copied to destination directory.";
      else
      {
         //resize photo to a maximum width of 150px
         //set to UNAPPROVED if new photo uploaded
         $sql="UPDATE judges SET photofile='$filename',photoapproved='' WHERE id='$offid'";
         $result=mysql_query($sql);
      }
   }
}
if($save)
{
   //save changes
   $address=addslashes($address);
   $city=addslashes($city);
   $homeph=$homearea.$homepre.$homepost;
   $workph=$workarea.$workpre.$workpost;
   $cellph=$cellarea.$cellpre.$cellpost;
   $sql="UPDATE judges SET address='$address',city='$city',state='$state',zip='$zip',homeph='$homeph',workph='$workph',cellph='$cellph',email='$email' WHERE id='$offid'";
   $result=mysql_query($sql);

   if(trim($address)=="" || trim($city)=="" || trim($state)=="" || trim($zip)=="")
   {
      //don't let them save unless they enter address
      $error=1;
   }

   if($error!=1)
   {
      //send to homepage and tell user the info was submitted
      header("Location:jwelcome.php?session=$session&message=info");
      exit();
   }
}

$sql="SELECT * FROM judges WHERE id='$offid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$address=$row[address];
$city=$row[city];
$state=$row[state];
$zip=$row[zip];
$homeph=$row[homeph];
$workph=$row[workph];
$cellph=$row[cellph];
$email=$row[email];
$photofile=$row[photofile];
$photoapproved=$row[photoapproved];

echo $init_html;
echo GetHeaderJ($session);
echo "<center><br>";
echo "<form method=post action=\"jeditinfo.php\" enctype=\"multipart/form-data\">";
echo "<input type=hidden name=session value=\"$session\">";
if($error==1)
{
   echo "<font style=\"color:red\"><b>You must enter your Address, City, State and Zip.</b></font><br><br>";
}
echo "<table width='100%'><tr valign=top align=center><td align=right>";
echo "<table width=\"160px\"><tr align=left><td width=\"160px\"><b>Profile Picture:</b><br><br>";
echo "<input type=hidden name=\"MAX_FILE_SIZE\" value=\"1000000\">";
if($photofile!='' && citgf_file_exists("photos/$photofile"))      //photo exists
{
   //echo "<iframe style=\"width:150px;height:200px;\" frameborder=0 src=\"photos.php?session=$session&file=$photofile\"></iframe>";
   echo "<img src=\"photos/$photofile\" border=0 width=\"100px\"><br><a href=\"jeditinfo.php?session=$session&deletephoto=1\" onClick=\"return confirm('Are you sure you want to delete this photo?  You cannot undo this action.');\">Delete Photo</a><br>";
   echo "<br>Upload New Profile Picture:<br>";
   echo "<input type=file name=\"photofile\"><input type=submit name=upload value=\"Upload\"><br>";
   echo "<div class=alert style=\"width:160px\">NOTE: The photo you upload must be no larger than 500KB (kilobytes).</div>";
}
else      //no photo
{
   echo "<div class=normal style=\"width:100px;height:100px;\"><br>You have not uploaded a profile picture yet.<br><br></div>";
   echo "Upload Profile Picture:<br>";
   echo "<input type=file name=\"photofile\"><input type=submit name=upload value=\"Upload\"><br>";
   echo "<div class=alert style=\"width:160px\">NOTE: The photo you upload must be no larger than 300KB (kilobytes).</div>";
}
   echo "<iframe style=\"width:250px;height:30px;\" frameborder=0 src=\"uploadphotos.php\" name=\"uploadframe\" id=\"uploadframe\"></iframe>";
//}
echo "</td></tr></table></td><td align=left width='50%'>";
echo "<table><caption><b>Edit Contact Information:</b><hr>";
if($errormsg && $errormsg!='')
  echo "<tr align=center><td colspan=2><div class=error>$errormsg</div></td></tr>";
else if($upload)
   echo "<tr align=center><td colspan=2><div class=alert>Your profile picture was uploaded successfully.</div></td></tr>";
else if($save)
   echo "<tr align=center><td colspan=2><div class=alert>Your changes have been saved.</div></td></tr>";
echo "</caption>";
echo "<tr align=left><th align=left>Address:</th>";
echo "<td><input type=text size=30 name=address value=\"$address\"></td></tr>";
echo "<tr align=left><th align=left>";
echo "City:</th><td><input type=text size=20 name=city value=\"$city\"></td></tr>";
echo "<tr align=left><th align=left>State:</th><td><input type=text size=3 name=state value=\"$state\"></td></tr>";
echo "<tr align=left><th align=left>Zip:</th><td><input type=text size=6 name=zip value=\"$zip\"></td></tr>";
echo "<tr align=left><th align=left>Home Phone:</th>";
$homearea=substr($homeph,0,3); 
$homepre=substr($homeph,3,3);
$homepost=substr($homeph,6,4);
echo "<td>(<input type=text size=4 maxlength=3 name=homearea value=\"$homearea\" onKeyUp='return autoTab(this,3,event);'>)";
echo "<input type=text size=4 maxlength=3 name=homepre value=\"$homepre\" onKeyUp='return autoTab(this,3,event);'> - ";
echo "<input type=text size=5 maxlength=4 name=homepost value=\"$homepost\" onKeyUp='return autoTab(this,4,event);'><br>";
echo "<tr align=left><th align=left>Work Phone:</th>";
$workarea=substr($workph,0,3);
$workpre=substr($workph,3,3);
$workpost=substr($workph,6,4);
echo "<td>(<input type=text size=4 maxlength=3 name=workarea value=\"$workarea\" onKeyUp='return autoTab(this,3,event);'>)";
echo "<input type=text size=4 maxlength=3 name=workpre value=\"$workpre\" onKeyUp='return autoTab(this,3,event);'> - ";
echo "<input type=text size=5 maxlength=4 name=workpost value=\"$workpost\" onKeyUp='return autoTab(this,4,event);'><br>";
echo "<tr align=left><th align=left>Cell Phone:</th>";
$cellarea=substr($cellph,0,3);
$cellpre=substr($cellph,3,3);
$cellpost=substr($cellph,6,4);
echo "<td>(<input type=text size=4 maxlength=3 name=cellarea value=\"$cellarea\" onKeyUp='return autoTab(this,3,event);'>)";
echo "<input type=text size=4 maxlength=3 name=cellpre value=\"$cellpre\" onKeyUp='return autoTab(this,3,event);'> - ";
echo "<input type=text size=5 maxlength=4 name=cellpost value=\"$cellpost\" onKeyUp='return autoTab(this,4,event);'></td></tr>";
echo "<tr align=left><th align=left>E-mail:</th>";
echo "<td><input type=text size=30 name=email value=\"$email\"></td></tr>";
echo "</table></td></tr></table>";
echo "<input type=submit name=\"save\" value=\"Save Changes\">";
echo "</form>";
echo "<a href=\"jwelcome.php?session=$session\" class=small>Home</a>";
echo $end_html;

?>
