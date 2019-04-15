<?php
//require_once "Mail.php";
require 'functions.php';
require 'variables.php';
$attachment=array();
$attachment[]='nsaacontract2.png';

var_dump(SendMail2222('dd','saif@primtechs.com', 'saif@primtechs.com', 'Gabbar sing' ,' with attachement test confirm  attachment '.date("F j, Y, H:i:s ").' unix time=' .time(), 'Amazon SES SMTP interface using the PHPMa', 'Amazon SES SMTP interface using the PHPMa'  ,$attachment ));

echo ' why yoiu two attachment '.date("F j, Y, H:i:s ").' unix time=' .time();


 exit;
require($_SERVER['DOCUMENT_ROOT']."/nsaaforms/PHPMailer/class.phpmailer.php");
   // Instantiate your new class  
   $mail = new PHPMailer;
   // Now you only need to add the necessary stuff  
   $mail->AddAddress('saif@primtechs.com', 'Saif');
   $mail->SMTPAuth = true;
   $mail->SMTPDebug = 3;
   $mail->SMTPSecure = 'tls';
   $mail->Port = 587;
   $mail->Host = 'email-smtp.us-east-1.amazonaws.com';
   $mail->Username = 'AKIAIAW365GLM626GB3A';
//Password to use for SMTP authentication
	$mail->Password = 'ApJmfV8svBn0op5PJIYKREpp8nSevkQYNNzgpBd5ZpHH';
   $mail->setFrom("nsaa@nsaahome.org", "NSAA");
   $mail->AddReplyTo("nsaa@nsaahome.org", "NSAA");
   $mail->Subject = 'Amazon SES test (SMTP interface accessed using PHP) '.date("F j, Y, H:i:s ").' unix time=' .time();
   $mail->IsHTML(true);
   $mail->AltBody = "Email Test\r\nThis email was sent through the 
    Amazon SES SMTP interface using the PHPMailer class.";
   $mail->Body = '<h1>Email Test</h1>
    <p>This email was sent through the 
    <a href="https://aws.amazon.com/ses">Amazon SES</a> SMTP
    interface using the <a href="https://github.com/PHPMailer/PHPMailer">
    PHPMailer</a> class.</p> '.time();
   
	if(!$mail->send()) {
    echo "Email not sent sdf dfsdaf adsfsd. " , $mail->ErrorInfo , PHP_EOL;
	} else {
    echo "Email sent! to saif@primtechs.com ".$mail->Subject  , PHP_EOL;
	}
	
	
	



echo "dfadfads" ; 


?>