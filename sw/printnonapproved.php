<?php
/***************************
printnonapproved.php
NSAA Swimming Verification Forms Admin: Print Non-Approved Verification Forms
Created 2/4/09
Author Ann Gaffigan
****************************/
require '../functions.php';
require '../variables.php';
require 'swfunctions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

if(!$gender) $gender="b";

$sql="SELECT * FROM sw_verify_".$gender." WHERE senttoNSAA='y' AND approved!='y' ORDER BY datesub DESC";
$result=mysql_query($sql);
$string="";
while($row=mysql_fetch_array($result))
{
	$filename="swverify".$row[id]."_".$gender.".html";
 	if(!$open=fopen(citgf_fopen("/home/nsaahome/attachments/".$filename),"r")) $string.="COULD NOT OPEN $fileame<br>";
	//$data=fread($open,citgf_filesize("/home/nsaahome/attachments/".$filename));
	$data=stream_get_contents($open);
	fclose($open);
	$string.="<div style=\"text-align:left;font-size:10pt;font-family:arial;\"><b>".strtoupper($row[school])." (submitted ".date("M j, Y",$row[datesub])."):</b></div>".$data;
	$string.="<div style=\"page-break-after:always;\"><br><br></div>";
}
echo $string;

?>
