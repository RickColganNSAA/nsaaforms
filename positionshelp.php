<?php
//positionshelp.php: pop-up window on forms with positions lists: how to use

require 'variables.php';
require 'functions.php';

if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

echo $init_html;
?>
<center>
<table width=90%>
<caption><b>How to Select Positions for your Players:</b></caption>
<tr align=left>
<td>
The "Position" column on your form contains a scrolling list of abbreviated positions that you may choose from for each player.  <b>To select one position</b>, scroll down to that position and click on it once.  The position you selected will be highlighted in blue.  <b>To select multiple positions</b> for one player, scroll to each position and click once on it while holding down the CTRL key (Apple for Mac users).  Each position you select will be highlighted in blue.<br><br>
When you click "Save & Keep Editing", you will notice that the first two positions on the list are all you see for each player, whether you checked those positions for that player or not.  This is OK!  <b>Just scroll down the list to see that the positions you checked are in fact highlighted in blue for that player.</b>  When you click "Save & View Form", you will see that the positions you selected for each player show up correctly, with multiple positions separated by a "/".<br><br>
</td>
</tr>
<tr align=center>
<td>
<input type=button onClick="window.close();" value="Close Window">
</td></tr>
</table>

</center>
</td></tr>
</table>
</body>
</html>
