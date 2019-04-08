<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);


 echo $init_html;
 echo $header;
//$session=$_GET[session];

//echo $end_html;

?>

<br><br><br>
<h3><?php if($_POST['error_hap']!=1) { ?>Thank you for submitting your National Anthem application.<?php } else  {?> An Error Occur . Please Contact With Us.  <?php }?></h3><br>
<a href="anthem.php?session=<?php echo $session; ?>&school=<?php echo $school;?>">Submit Another National Anthem Application</a>&nbsp&nbsp&nbsp&nbsp
<a href="welcome.php?session=<?php echo $session; ?>" >Home</a>

