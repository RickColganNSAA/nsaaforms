<?php
require 'functions.php';
require 'variables.php';

sleep(1);

$html=split("</head>",$init_html);
$newhtml=$html[0];
$newhtml.="<meta http-equiv=\"Refresh\" content=\"10\">";
$newhtml.="</head>";
echo $newhtml;
echo "<table style=\"width:100%;\" class='nine'><tr align=center><td>";
if(citgf_file_exists("downloads/".$filename))
{
   echo "<br><br><div class='alert' style=\"width:400px;\">The directory export is complete!<br><br><a href=\"downloads/".$filename."\" target=\"_blank\">Download PDF File: $filename</a></div>";
}
else
{
   echo "<b>Directory Export in Progress...Please be patient...<br>";
   if(!ereg("END",$data)) echo "<img src=\"pleasewait.gif\">";
}
echo $end_html;
?>
