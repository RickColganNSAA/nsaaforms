<?php
require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);
if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
$sport='te';
$coachid=GetUserID($session);
$school=GetSchool($session);
$sportname=GetActivityName($sport);
$sql="SELECT * FROM $db_name2.rulesmeetingdates WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$temp=split("-",$row[startdate]);
$year=$temp[0];
$start=split("-",$row[startdate]);
$late=split("-",$row[latedate]);
$end=split("-",$row[enddate]);
if($school!="Test's School" && (!PastDue($row[startdate],-1) || PastDue($row[enddate],0)))	//UNAVAILABLE
{
   header("Location:rulesmeetingintro.php?sport=$sport&session=$session");
   exit();
}
//ELSE MARK TIME MOVIE IS INITIATED:
$table=$sport."rulesmeetings";
$sql="SELECT * FROM $table WHERE coachid='$coachid'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)	//INSERT
{
   $sql="INSERT INTO $table (coachid,offid,dateinitiated) VALUES ('$coachid','$offid','".time()."')";
   $result=mysql_query($sql);
}
else
{
   $sql="UPDATE $table SET offid='$offid',dateinitiated='".time()."',datecompleted='0' WHERE coachid='$coachid'";
   $result=mysql_query($sql);
}

echo $init_html_ajax;
?>
<script language="javascript">AC_FL_RunContent = 0;</script>
<script src="/javascript/AC_RunActiveContent.js" language="javascript"></script>
</head>
<body bgcolor="#cccccc">
<?php
echo GetHeader($session);
echo "<br><table width=100%><caption><b>$year NSAA Online $sportname Rules Meeting Video:</b><br>(The video, once loaded, will begin automatically below.)<tr align=center valign=top><td>";
//instructions & warnings on each side of the video:
echo "<br><br><br><div class=alert style=\"width:150px\"><b>DO NOT EXIT THIS SCREEN!<br><br>";
echo "At the END of this video, your browser will be RE-DIRECTED to the verification form.<br><br>";
echo "</div></td><td>";
$meeting="2010TERulesMeeting";
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
echo "</td><td>";
echo "<br><br><br><div class=alert style=\"width:150px\"><b>DO NOT EXIT THIS SCREEN!<br><br>";
echo "At the END of this video, your browser will be RE-DIRECTED to the verification form.<br><br>";
echo "</div></td>";
echo "</tr></table>";
echo $end_html;
?>
