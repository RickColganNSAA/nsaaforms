<?php
require 'functions.php';
require 'variables.php';

$level=GetLevel($session);

//verify user
if(!ValidUser($session) || $level!=1)
   exit();

citgf_exec("/usr/local/bin/php direxport.php $session > diroutput.html 2>&1 &");
header("Location:diroutput.html");
exit();
?>
