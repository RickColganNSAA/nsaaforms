<?php
require 'functions.php';
require 'variables.php';

sleep(1);

$open=fopen(citgf_fopen("diroutput2.html"),"r");
//$data=fread($open,citgf_filesize("diroutput2.html"));
$data=stream_get_contents($open);
fclose($open);

$html=split("</head>",$init_html);
$newhtml=$html[0];
if(!ereg("END",$data)) $newhtml.="<meta http-equiv=\"Refresh\" content=\"10\">";
$newhtml.="</head>";
echo $newhtml;
echo "<br><table width=100%><tr align=center><td>";
echo "<b>Directory Export in Progress...Please be patient...<br>";
if(!ereg("END",$data)) echo "<img src=\"pleasewait.gif\">";
echo $data;

?>
