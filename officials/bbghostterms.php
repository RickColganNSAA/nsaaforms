<?php
require 'variables.php';
require 'functions.php';
//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

if($session) $level=GetLevel($session);

$sport='bbg';

echo $init_html;
if($save)
{
   $body=addslashes($body);
   $sql="SELECT * FROM hostterms WHERE sport='$sport'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
      $sql="INSERT INTO hostterms (sport,body) VALUES ('$sport','$body')";
   else
      $sql="UPDATE hostterms SET body='$body' WHERE sport='$sport'";
   $result=mysql_query($sql);
   if(mysql_error()) echo "<div class=error>ERROR: ".mysql_error()."</div>";
   else echo "<div class=alert>Your changes have been saved.</div>";
}

$sql="SELECT * FROM hostterms WHERE sport='$sport'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($edit==1 && $level==1)
{
?>
<script type="text/javascript">
tinyMCE.init({
        mode : 'textareas',
        theme : 'advanced',
        skin : 'o2k7',
        skin_variant : 'black',
        convert_urls : false,
        relative_urls : false,
        plugins : 'safari,iespell,preview,media,searchreplace,paste,',
        theme_advanced_buttons1 : 'bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,pastetext,pasteword,|,undo,redo,|,link,unlink,image,media,|,code,preview',
        theme_advanced_buttons2 : '',
        theme_advanced_toolbar_location : 'top',
        theme_advanced_toolbar_align : 'left',
        theme_advanced_statusbar_location : 'bottom',
        theme_advanced_resizing : true,
        // Example content CSS (should be your site CSS)
        content_css : '../css/plain.css',
        // Drop lists for link/image/media/template dialogs
        template_external_list_url : 'lists/template_list.js',
        external_link_list_url : 'lists/link_list.js',
        external_image_list_url : 'lists/image_list.js',
        media_external_list_url : 'lists/media_list.js'
        });
        </script>
<form method=post action="<?php echo $sport?>hostterms.php">
<input type=hidden name=session value="<?php echo $session; ?>">
<?php
}
echo "<table width=100%><tr align=center><td><table width=500>";

$space="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

echo "<tr align=center><td><img src=\"nsaacontract.png\"></td></tr>";
echo "<tr align=center><th align=center>Terms & Conditions--".GetSportName($sport);
if($level==1 && $edit!=1) echo "&nbsp;&nbsp;[<a href=\"".$sport."hostterms.php?session=$session&edit=1\">Edit</a>]";
echo "</th></tr>";
if($edit==1 && $level==1)
{
   echo "<tr align=center><td>";
   echo "<textarea cols=80 rows=30 name=\"body\">$row[body]</textarea>";
   echo "<br><input type=submit name=\"save\" value=\"Save Changes\">";
   echo "</td></tr>";
}
else
{
   echo "<tr align=left><td>";
   echo $row[body];
   echo "</td></tr>";
}
echo "</table>";
if($edit==1 && $level==1) echo "</form>";
echo $end_html;
?>
