<?php
require 'functions.php';
require_once('variables.php');
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

if(!ValidUser($session))
{
   header("Location:/nsaaforms/index.php?error=1");
   exit();
}

echo $init_html_ajax;
?>
</head>
<?php
echo GetHeader($session);

?>
<body onload="Tree.initialize('<?php echo $session; ?>');">
<?php
//STUFF
?>
<div id="loading" style="display:none;"></div>
<?php
echo $end_html;
?>
