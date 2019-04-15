<?php
require_once("class.phpmailer.php");  
  
class MyMailer extends PHPMailer {  
    // Set default variables for all new objects  
    //var $From     = "agaffigan@gazelleincorporated.com";  
    //var $FromName = "Ann Gaffigan";  
    var $Host     = "mail.gazelleincorporated.com";  
    var $Mailer   = "smtp";                         // Alternative to IsSMTP()  
    var $WordWrap = 75;  
  
    // Replace the default error_handler  
    function error_handler($msg) {  
        print("My Site Error");  
        print("Description:");  
        printf("%s", $msg);  
        exit;  
    }  
  
    // Create an additional function  
    function do_something($something) {  
        // Place your new code here  
    }  
}  
?>
