<?php
require 'functions.php';
require 'variables.php';

$level=GetLevel($session);

//verify user
if(!ValidUser($session) || $level!=1)
   exit();

citgf_exec("/usr/local/bin/php printdirexport.php $session $online > diroutput2.html 2>&1 &");
header("Location:diroutput.php");
exit();
?>
