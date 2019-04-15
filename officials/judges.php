<?php
require 'variables.php';
require 'functions.php';

//validate user
if(!ValidUser($session))
{
   header("Location:jindex.php?page=judges&session=$session");
   exit();
}

?>

<html>
<head>
<title>NSAA Home</title>
<link rel="stylesheet" href="../../css/nsaaforms.css" type="text/css">
</head>

<frameset border=0 rows="100,*,75" name=officials>
   <frame src="judge_header.php?session=<?php echo $session;?>" marginheight=0 scrolling=auto>
<?php
//send all info available to off_list.php:

echo "<frame src=\"judge_list.php?quickquery=$quickquery&setquery=$setquery&lastname=$lastname&findone=$findone&last=$last&sport1=$sport1&bool=$bool&sport2=$sport2&session=$session&all=$all&query=$query\" name=list scrolling=yes marginheight=0>";

echo "<frame src=\"judge_footer.php?quickquery=$quickquery&setquery=$setquery&lastname=$lastname&sport1=$sport1&bool=$bool&sport2=$sport2&session=$session&datesent=$datesent&all=$all&query=$query\" scrolling=auto marginheight=0>";

?>
</frameset>
</html>
