<?php
require 'functions.php';
if($sportch1) $sport=$sportch1;
if($sport=="Choose Sport") 
{
   header("Loction:welcome.php?session=$session");
   exit();
}
else
{
   header("Location:assign".$sport.".php?session=$session");
   exit();
}
?>
