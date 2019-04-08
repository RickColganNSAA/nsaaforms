<?php
require_once ($_SERVER["DOCUMENT_ROOT"].'/dbfunction.php');

$filename=ereg_replace("previews/","",$filename);
if(citgf_file_exists("previews/$filename"))
{
   $filename2=end(explode("/",$filename));
   if(!citgf_copy("previews/$filename","../../textfile/tennis/$filename2"))
      echo "Could Not Copy $filename to NSAA Website.";
   else
      echo "File Successfully Posted!<br><br><a href=\"https://nsaa-static.s3.amazonaws.com/textfile/tennis/$filename2\">Preview</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"javascript:window.close();\">Close Window</a>";
}
else
{
   echo "ERROR: $filename does not exist.  Could not copy.";
}
exit();
?>

