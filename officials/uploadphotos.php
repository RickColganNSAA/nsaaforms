<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   //header("Location:index.php?error=1");
   exit();
}

$offid=GetOffID($session);

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
      $sql="SELECT * FROM officials WHERE id='$offid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      //file name is first letter of first name then last name
      $origfilename=$_FILES['photofile']['name'];
      $temp=split("[.]",$origfilename);
      $filename=ereg_replace("[^A-Za-z]","",$row[first]);
      $filename=substr($filename,0,1);
      $filename.=ereg_replace("[^A-Za-z]","",$row[last]);
      $filename.=$offid;
      $filename.=".".$temp[1];
      if(!citgf_copy($_FILES['photofile']['tmp_name'],"photos/$filename"))
         $errormsg="ERROR: File could not be copied to destination directory.";
      else
      {
         //resize photo to a maximum width of 150px	
/*
         $src = imagecreatefromjpeg("photos/$filename");
         // Capture the original size of the uploaded image
         list($width,$height)=getimagesize(getbucketurl("photos/$filename"));
         if($width>150)
         {
            $newwidth=150;
            $newheight=($height/$width)*150;
         }
         else
         {
            $newwidth=$width;
            $newheight=$height;
         }
         $tmp=imagecreatetruecolor($newwidth,$newheight);
         // this line actually does the image resizing, copying from the original
         // image into the $tmp image
         imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
         imagejpeg($tmp,"photos/$filename",100);
         imagedestroy($tmp); // NOTE: PHP will clean up the temp file it created when the request has completed
         imagedestroy($src);
*/
         //set to UNAPPROVED if new photo uploaded
         $sql="UPDATE officials SET photofile='$filename',photoapproved='' WHERE id='$offid'";
         $result=mysql_query($sql);
      }
   }
}
if($errormsg!='') echo $errormsg;
?>
<script type="text/javascript">
window.parent.location.href = window.parent.location.href;
</script>
