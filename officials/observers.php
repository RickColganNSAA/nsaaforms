<?php
require 'variables.php';
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
<title>NSAA Home</title>
<link rel="stylesheet" href="../../css/nsaaforms.css" type="text/css">
</head>

<frameset border=0 rows="100,*,75" name=observers>
   <frame src="off_header.php?session=<?php echo $session;?>" marginheight=0 scrolling=auto>
<?php
//send all info available to obs_list.php:
$query=ereg_replace(";",",",$query);

echo "<frame src=\"obs_list.php?last=$last&sport=$sport&lastname=$lastname&session=$session&query=$query\" name=list scrolling=yes marginheight=0>";

echo "<frame src=\"obs_footer.php?sport=$sport&session=$session&lastname=$lastname&query=$query\" scrolling=auto marginheight=0>";

?>
</frameset>
</html>
