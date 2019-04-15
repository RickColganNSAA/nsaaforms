<?php
require_once ($_SERVER["DOCUMENT_ROOT"].'/dbfunction.php');

if($_REQUEST)
{
   foreach($_REQUEST as $key => $value)
   {
        $$key=$value;
   }
}

if(!$testsport)
{
   header("Location:welcome.php?session=$session&open=6#6");
   exit();
}

$page=$testsport."test2.php";
header("Location:$page?session=$session");
exit();

?>
