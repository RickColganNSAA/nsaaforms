<html>
<head>
<script type="text/javascript" src="http://static.ak.facebook.com/js/base.js?1:30618"></script>
<script type="text/javascript" src="http://static.ak.facebook.com/js/captcha.js?1:24359"></script>
<script type="text/javascript" src="http://static.ak.facebook.com/js/ajax.js?1:30520"></script>
<script type="text/javascript" src="http://static.ak.facebook.com/js/typeahead_ns.js?1:29634"></script>
<script type="text/javascript" src="http://static.ak.facebook.com/js/mailbox.js?1:28144"></script>
<script type="text/javascript" src="http://static.ak.facebook.com/js/typeaheadpro.js?1:30618"></script>
<script type="text/javascript" src="http://static.ak.facebook.com/js/editor.js?1:30013"></script>
</head>
<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

echo $offid."<br>";
echo "<form method=post action=\"lookup.php\" name=lookup>";
echo "<input type=text name=official onchange=\"offselect.value=this.value;\">";
echo "<select name=offselect><option value=''>~</option>";
$sql="SELECT * FROM officials WHERE bb='x' ORDER BY last,first";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $curname="$row[first] $row[last]";
   echo "<option";
   if($offselect==$curname) echo " selected";
   echo ">$curname</option>";
}
echo "</select><br>";
?>
<input type="input" name="to_name" id="to_name" class="inputtext typeahead_placeholder" value="Start typing a friend's name" maxlength="100" size="25" autocomplete="off" onfocus="var source=new friend_source('632385178-1169738134-1');var ta=new typeaheadpro(this, source);ta.onfound=composeFound;ta.onsubmit=composeSubmit;" />
<?php
echo "</form><br><br>";
?>
