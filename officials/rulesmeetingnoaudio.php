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

echo $init_html;
echo GetHeader($session);

echo "<br><br><table width=100%><tr align=center><td>";
if($sport=='fb')
{
?>
<div style="width:600px" id="__ss_4764583">
<object id="__sse4764583" width="600" height="510"><param name="movie" value="http://static.slidesharecdn.com/swf/ssplayer2.swf?doc=2010nsaafbrulesmeeting-100715161232-phpapp01&rel=0&stripped_title=2010-nsaa-football-rules-meeting" /><param name="allowFullScreen" value="true"/><param name="allowScriptAccess" value="always"/><embed name="__sse4764583" src="http://static.slidesharecdn.com/swf/ssplayer2.swf?doc=2010nsaafbrulesmeeting-100715161232-phpapp01&rel=0&stripped_title=2010-nsaa-football-rules-meeting" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="600" height="510"></embed></object>
</div>
<?php
}
echo "</td></tr></table>";
echo $end_html;

?>
