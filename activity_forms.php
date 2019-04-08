<html>
<head>
  <title>NSAA Home</title>
  <link href="../css/nsaaforms.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php
require 'functions.php';
$session="1055989390";
$header=GetHeader($session);
echo $header;
?>

<center>
<b><font size=2>District Forms:</b><br>
<a href="golf/view_go_b.php">Boys Golf</a>
<br>
<a href="golf/view_go_g.php">Girls Golf</a>
<br>
<a href="xc/view_xc_b.php">Boys XC</a>
<br>
<a href="xc/view_xc_g.php">Girls XC</a>
<br>
<a href="volleyball/view_vb.php">Volleyball</a>
<br>
<br>
<b><a href="eligibility.php">Eligibility List</a></b>

</td>
</tr>
</table>
</body>
</html>
