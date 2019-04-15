<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
if(!$sport) $sport='fb';
$sportname=GetSportName($sport);

$sql="SELECT * FROM rulesmeetingdates WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$ext=end(explode(".",$row[ppfile]));

if($ext=="html")
{
   echo $init_html."<table width='100%'><tr align=center><td><a href=\"welcome.php?obssport=$sport&session=$session\">Return Home</a><br>";
   echo "<iframe style='width:100%;height:600px;valign:top;background-color:#ffffff;border:none;' src=\"$row[ppfile]\"></iframe>";
   echo $end_html;
   exit();
}
else
{
echo $init_html_ajax;
?>
<script language="javascript">AC_FL_RunContent = 0;</script>
<script src="/javascript/AC_RunActiveContent.js" language="javascript"></script>
</head>
<body bgcolor="#e0e0e0">
<?php
echo GetHeader($session);
echo "<br><table width=100%><caption><b>$year NSAA Online $sportname Rules Meeting Video:</b><br>(The video, once loaded, will begin automatically below.)<tr align=center valign=top><td>";
//instructions & warnings on each side of the video:
$meeting="2010".strtoupper($sport)."RulesMeetingObserver";
?>
<script language="javascript">
	if (AC_FL_RunContent == 0) {
		alert("This page requires AC_RunActiveContent.js.");
	} else {
		AC_FL_RunContent(
			'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0',
			'width', '500',
			'height', '420',
			'src', '/<?php echo $meeting; ?>?sessionid=<?php echo $session; ?>',
			'quality', 'low',
			'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
			'align', 'middle',
			'play', 'true',
			'loop', 'true',
			'scale', 'showall',
			'wmode', 'window',
			'devicefont', 'false',
			'id', '<?php echo $meeting; ?>',
			'bgcolor', '#cccccc',
			'name', '<?php echo $meeting; ?>',
			'menu', 'true',
			'allowFullScreen', 'false',
			'allowScriptAccess','sameDomain',
			'movie', '/<?php echo $meeting; ?>?sessionid=<?php echo $session; ?>',
			'salign', ''
			); //end AC code
	}
</script>
<noscript>
	<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="500" height="420" id="<?php echo $meeting; ?>" align="middle">
	<param name="allowScriptAccess" value="sameDomain" />
	<param name="allowFullScreen" value="false" />
	<param name="movie" value="/<?php echo $meeting; ?>.swf?sessionid=<?php echo $session; ?>" />
        <param name="quality" value="low" />
        <param name="bgcolor" value="#cccccc" />	
        <embed src="/<?php echo $meeting; ?>.swf?sessionid=<?php echo $session; ?>" quality="low" bgcolor="#cccccc" width="500" height="420" name="<?php echo $meeting; ?>" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
	</object>
</noscript>
<?php
}
echo "<br><div class='alert' style='width:500px;text-align:center;'>When the video is finished, <a href=\"welcome.php?obssport=$sport&session=$session\" class=small>Click Here to Return Home</a></td>";
echo "</tr></table>";
echo $end_html;
?>
