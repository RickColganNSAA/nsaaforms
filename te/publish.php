<?php
require_once ($_SERVER["DOCUMENT_ROOT"].'/dbfunction.php');

$filename=$_REQUEST['filename'];
$filename=preg_replace("/previews\//","",$filename);
if(citgf_file_exists("previews/$filename"))
{
   $filename2=end(explode("/",$filename));
   if(!citgf_copy("previews/$filename","../../textfile/tennis/$filename2"))
      echo "Could Not Copy $filename to NSAA Website.";
   else
   {
      echo "File Successfully Posted!<br><br>";
      //echo "<a href=\"previews/$filename\" target=\"_blank\">$filename</a> was copied to ";
      echo "<a href=\"https://nsaa-static.s3.amazonaws.com/textfile/tennis/$filename2\">Preview</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"javascript:window.close();\">Close Window</a>";
   }
}
else
{
   echo "ERROR: $filename does not exist.  Could not copy.";
}
exit();
?>

