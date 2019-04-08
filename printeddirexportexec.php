<?php
require 'functions.php';
require 'variables.php';

$level=GetLevel($session);

//verify user
if(!ValidUser($session) || $level!=1)
   exit();

$filename="Directory".time().".pdf";
citgf_exec("/usr/local/bin/php printeddirexport.php $session $filename > diroutput3.html 2>&1 &");
header("Location:printeddiroutput.php?filename=$filename");
exit();
?>
