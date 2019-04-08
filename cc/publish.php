<?php
require_once ($_SERVER["DOCUMENT_ROOT"].'/dbfunction.php');

$filename= $_GET['filename'];
if(citgf_file_exists('../../textfile/cc/'.$filename))
{
   if($blank)
   {
      $open=fopen(citgf_fopen("../../textfile/cc/".$filename),"w");
      fwrite($open,"<b>Information not available at this time.</b>");
      fclose($open); 
 citgf_makepublic("../../textfile/cc/".$filename);
      echo "\"Information not available at this time\" was posted to <a href=\"../../textfile/cc/$filename\">$filename</a> on the <a href=\"/cc.php\">Cross-Country page</a>.<br><br><a href=\"javascript:window.close();\">Close Window</a>";
   }
   else if(!citgf_copy($filename,"../../textfile/cc/".$filename))
      echo "Could Not Copy". $filename."to NSAA Website.";
   else
      echo "File Successfully Posted! <a href=\"../../textfile/cc/$filename\">Preview $filename</a><br><br><a href=\"javascript:window.close();\">Close Window</a>";
}
else
{
   echo "ERROR: $filename does not exist.  Could not copy.";
}
exit();
?>

