<?php
//judge_header.php: top header for judges.php

require 'variables.php';

//connect to db:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

require 'functions.php';

?>

<html>
<head>
<link rel="stylesheet" href="../../css/nsaaforms.css" type="text/css">
</head>
<body>

<?php
$header=GetOffHeader($session);
echo $header;
?>

</table>

</body>
</html>

