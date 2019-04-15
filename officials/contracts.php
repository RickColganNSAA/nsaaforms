<?php
require_once ($_SERVER["DOCUMENT_ROOT"].'/dbfunction.php');

if($sportch3) $sport=$sportch3;
if($sport=="Choose Sport" || !$sport)
{
   echo "ERROR: No Sport Chosen";
   exit();
}
else 
{
   header("Location:".$sport."contracts.php?session=$session");
   exit();
}
?>
