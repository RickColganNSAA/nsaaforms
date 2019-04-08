<?php
//uploadlogo.php: allow user to upload new school logo from Directory page

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

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
if($submit=="Upload")
{
   $sql="SELECT logo FROM headers WHERE school='$school2'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $logofile=$row[0];
   citgf_copy($logo,"/images/$logofile");
?>
<script language="javascript">
window.close();
</script>
<?php
   exit();
}

echo $init_html;
echo "<center>";
echo "<form enctype=\"multipart/form-data\" method=post action=\"uploadlogo.php\">";
echo "<table width=300><tr align=left><td>";
echo "<input type=hidden name=session value=\"$session\">";
echo "<font size=2><b>Please click 'Browse' to find the new logo image you wish to upload:</font></b><br><font style=\"font-size=8pt;\">NOTE: The uploaded image must fit inside a 100 pixel by 100 pixel square in order for your page headers to display properly!!</td></tr>";
echo "<tr align=center><td>";
echo "<input type=file name=logo><br>";
echo "<input type=submit name=submit value=\"Upload\">";
echo "</td></tr></table></form>";
echo $end_html;
?>
