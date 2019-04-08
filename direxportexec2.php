<?php
require 'functions.php';
require 'variables.php';

$level=GetLevel($session);

//verify user
if(!ValidUser($session) || $level!=1)
   exit();

//shell_citgf_exec("cd ../cgi-bin;");
citgf_exec("php direxport2.php $session > diroutput2.html 2>&1 &");
header("Location:diroutput2.html");
exit();
?>
