<?php
 if($_SERVER['HTTP_X_FORWARDED_PROTO']=='http') { 
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
} 
//echo '<pre>'; print_r($_SERVER['HTTP_X_FORWARDED_PROTO']); 

?>
