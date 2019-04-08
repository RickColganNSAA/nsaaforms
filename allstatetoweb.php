<?php
/**************************************
allstatetoweb.php
NSAA edits and approves export of
NCPA Academic All-State Award winners to
the NSAA website
Created 8/10/10
Author Ann Gaffigan
***************************************/

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);

if(PastDue(date("Y")."-05-31",0) && !PastDue(date("Y")."-07-01",0))       //IF IT IS PAST END OF SCHOOL YEAR, NEED TO REFER TO ARCHIVED DB
{
   $year1=date("Y")-1; $year2=date("Y");
   mysql_select_db($db_name.$year1.$year2, $db);
   $fallyear=$year1;
}
else if(date("m")>=6) $fallyear=date("Y");
else $fallyear=date("Y")-1;


echo $init_html;
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
<?php
echo $header;

echo "<form method=post action=\"allstatetoweb.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<br><br>";
echo "<p style='width:700px;text-align:left;font-size:10pt;'><i><b>Preview the PDF</b> to be published to the website of all awards that have been confirmed and released to the schools this year. You can edit the text that shows at the top of the PDF below, and then click \"Publish\" to publish the document on the website.</i></p>";
echo "<div class='normalwhite' style='text-align:center;padding:10px;font-size:10pt;width:700px;'>";

echo "<img src=\"../images/nsaalogocolor.png\" style=\"width:250px;border:none;margin:10px;\"><br>";
echo "<b>NSAA ACADEMIC ALL STATE AWARDS</b><br>";
$sql="SELECT * FROM allstatetoweb";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
echo "<br><i>Edit the introductory text for the document below:<br></i>";
echo "<textarea name=\"introtext\" rows=20 style='width:680px'>$row[introtext]</textarea>";

echo "</div>";
echo "</form>";

echo $end_html;
?>
