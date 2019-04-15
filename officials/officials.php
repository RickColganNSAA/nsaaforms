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

<frameset border=0 rows="100,*,75" name=officials>
   <frame src="off_header.php?session=<?php echo $session;?>" marginheight=0 scrolling=auto>
<?php
//send all info available to off_list.php:
$query=ereg_replace(";",",",$query);
echo "<frame src=\"off_list.php?stateyearsineq=$stateyearsineq&numstateyears=$numstateyears&insincestate=$insincestate&yearstate=$yearstate&insincedist=$insincedist&yeardist=$yeardist&mailineq=$mailineq&mailoption=$mailoption&whichmailnum=$whichmailnum&mailnum3=$mailnum3&findone=$findone&last=$last&sport=$sport&lastname=$lastname&session=$session&query=$query\" name=list scrolling=yes marginheight=0>";

echo "<frame src=\"off_footer.php?sport=$sport&session=$session&lastname=$lastname&stateyearsineq=$stateyearsineq&numstateyears=$numstateyears&insincestate=$insincestate&yearstate=$yearstate&insincedist=$insincedist&yeardist=$yeardist&mailoption=$mailoption&whichmailnum=$whichmailnum&query=$query&mailnum3=$mailnum3&mailineq=$mailineq\" scrolling=auto marginheight=0>";

?>
</frameset>
</html>
