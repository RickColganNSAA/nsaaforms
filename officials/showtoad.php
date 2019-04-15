<?php
require_once ($_SERVER["DOCUMENT_ROOT"].'/dbfunction.php');

header("Location: ".$_REQUEST['sport']."showtoad.php?session=".$_REQUEST['session']."&id=".$_REQUEST['id']);
?>
