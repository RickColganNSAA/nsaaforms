<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

//set showoffs to 'y' in __districts table
$districts=$sport."districts";
if($sport=='fb') $districts="fbbrackets";
$sql="UPDATE $districts SET showoffs='y' WHERE id='$id'";
$result=mysql_query($sql);

if($sport=='fb')
   header("Location:fbcontracts.php?session=$session&classch=$classch&round=$round&posted=1");
else if($sport=='pp' || $sport=='sp')
   header("Location:playcontracts.php?session=$session&classdist=$classdist&sport=$sport");
else if(citgf_file_exists($sport."contracts.php") || ($return && $return!='' && citgf_file_exists($return))) //$sport=='wr' || $sport=='bb' || $sport=='so' || $sport=='ba' || $sport=='sb' || $sport=='vb')
{
   if($return)
      header("Location:$return?session=$session&sport=$sport");
   else
      header("Location:".$sport."contracts.php?session=$session&distch=$distch&typech=$typech&posted=1");
}
else
   header("Location:contracts.php?sport=$sport&session=$session&type=$type&classdist=$classdist&posted=1");
exit();

?>
