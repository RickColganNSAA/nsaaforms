<?php
//elig_header.php: top header for eligibility.php
require 'variables.php';

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

require 'functions.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}
?>

<html>
<head>
<link rel="stylesheet" href="../css/nsaaforms.css" type="text/css">
</head>
<body>

<?php
$header=GetEligHeader($session);
echo $header;
?>

</table>

</body>
</html>

