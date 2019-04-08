<?php
require 'functions.php';
require 'variables.php';

echo $init_html;

echo "<table class=nine width='100%'><tr align=center><td><b>TIPS FOR PRINTING A PDF CERTIFICATE:</b><br><br>";

      echo "<div class='normalwhite' style='text-align:left;margin:0 25px 25px 25px;padding:0 10px 0 0;'><ol><li style='font-size:9pt;'>When the PDF certificate loads, you may choose to Save it to your computer for your records or in case you want to re-print it at a later time.</li><br><li style='font-size:9pt;'>To print your certificate, select File >> Print.</li><br><li style='font-size:9pt;'>Make sure the <b>orientation</b> is set properly (Landscape or Portrait, depending on the certificate).</li><br><li style='font-size:9pt;'><b>MAC USERS:</b> We've noticed that a default setting in Adobe Acrobat results in the certificate being off-center when printed. Select File >> Print and then select \"Copies & Pages.\" For the \"Page Scaling\" option, make sure \"None\" is selected.</li><br><li style='font-size:9pt;'><b>ALL USERS:</b> Make sure to print your certificate on a regular piece of paper to make sure it is properly centered, before trying to print it on the paper for the final certificate.</li></ul></div>";

echo "<input type=button onClick=\"javascript:window.close();\" value=\"Close Window\">";

echo $end_html;
?>
