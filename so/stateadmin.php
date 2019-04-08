<?php
//stateadmin.php: NSAA Soccer Admin for State Entry Forms
//Created 5/6/09 for NSAA admin because e-mailed forms from schools
//	were not being received
//Updated 2015 to redirect to the new version - sostateadmin.php
//Author: Ann Gaffigan
require '../functions.php';
if(!$sport || $sport=='') $sport="so_b";
if($sport=="so_b") $sport="sob";
else $sport="sog";
header("Location:".$sport."stateadmin.php?session=$session");
?>
