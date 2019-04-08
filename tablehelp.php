<?php

require 'functions.php';
require 'variables.php';

echo $init_html;
echo "<table width=\"600px\"><tr align=center><td>";

echo "<h2>Instructions for inserting a table:</h2>";
echo "<ol>
	<li>The \"insert table\" button is the first button in the second row (see below, highlighted orange). Click it to insert a new table.<br><img src=\"table0.jpg\" style=\"border:0;margin:10px;\"></li>
	<li>A window will pop up, asking you for the basic information about the table, such as the number of rows and columns. The settings in the image below are the recommended settings (of course the number of rows and columns is up to you):<br><img src=\"table1.jpg\" style=\"border:0;margin:10px;\"></li>
	<li>It may be necessary to scroll down in the pop up windo to see the \"Insert\" button. If necessary, scroll down, and click the \"Insert\" button.<br><img src=\"table2.jpg\" src=\"border:0;margin:10px;\"></li>
	<li>When you click \"Insert\" the blank table will be inserted into the text box. Click in a table cell to begin typing. Move between cells with the TAB button. To ADD A ROW, put your cursor in the last cell of the last row and hit the TAB button on the keyboard.<br><img src=\"table3.jpg\" style=\"border:0;margin:10px;\"></li>
</ol>";
echo "<br><input type=button name=\"close\" onClick=\"window.close();\" value=\"Close\"><br><br>";

echo $end_html;
?>
