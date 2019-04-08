<?php
require_once ($_SERVER["DOCUMENT_ROOT"].'/dbfunction.php');

citgf_exec("/usr/local/bin/php filtersw.php $session > swoutput.html &");
header("Location:swoutput.html");
exit();
?>
