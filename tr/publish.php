<?php
require '../functions.php';
require '../variables.php';

echo $init_html."<table width='100%'><tr align=center><td><br>";

if(citgf_file_exists("previews/$filename"))
{
   if(!citgf_copy("previews/$filename","results/$filename"))
      echo "Could Not Copy $filename to NSAA Website.";
   else
      echo "File Successfully Posted!<br><br><a href=\"https://nsaa-static.s3.amazonaws.com/nsaaforms/tr/results/$filename\">Preview this File</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"/tr.php\">Preview Track & Field Page on NSAA Website</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"javascript:window.close();\">Close Window</a>";
}
else
{
   echo "ERROR: $filename does not exist.  Could not copy.";
}

echo $end_html;
exit();
?>

