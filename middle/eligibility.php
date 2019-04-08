<?php
/****************************************
eligibility.php
Eligibility Screen for Middle Schools
Created 12/26/09
Author: Ann Gaffigan
****************************************/

require '../functions.php';

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
<title>NSAA Middle Schools</title>
</head>
<frameset rows="*,100">
   <?php
   //send all info available to elig_list.php & elig_footer.php:
   echo "<frame src=\"elig_list.php?school_ch=$school_ch&session=$session&gender=$gender&grade=$grade&eligible=$eligible&physical=$physical&parent=$parent&last=$last\" name=\"list\" scrolling=\"yes\" marginheight=\"0\"></frame>";
   echo "<frame src=\"elig_footer.php?school_ch=$school_ch&session=$session&gender=$gender&grade=$grade&eligible=$eligible&physical=$physical&parent=$parent\" scrolling=\"auto\" marginheight=\"0\"></frame>";
   ?>
</frameset>
</html>
