<?php
require_once ($_SERVER["DOCUMENT_ROOT"].'/dbfunction.php');

foreach($_REQUEST as $key => $value)
{
   $$key=$value;
}
header("Location:schedule.php?session=$session&sport=tr&givenoffid=$givenoffid&edit=yes");
exit();
?>
