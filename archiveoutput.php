<?php
require 'functions.php';
require 'variables.php';

$open=fopen(citgf_fopen("archiveoutput.html"),"r");
//$data=fread($open,citgf_filesize("archiveoutput.html"));
$data=stream_get_contents($open);
fclose($open);

$new_init_html=ereg_replace("</head>","<meta http-equiv=\"Refresh\" content=\"10\"></head>",$init_html);
echo $new_init_html;
echo "<br><table width=100%><tr align=center><td>";

if($dumping==1)
{
  // citgf_exec("ls -al /home/nsaahome/databasebackups > archiveoutput2.html 2>&1");
   echo "<a target=new href=\"archiveoutput2.html\">Output</a><br>";
   echo "<a target=new2 href=\"archivedebug.txt\">Debugging</a><br>";
}
else
{
echo "<b>Archive in Progress...Please be patient...<br>";
echo "<img src=\"pleasewait.gif\"><br>";
$year=date("Y"); $year0=$year-1;
$archivedb="$db_name".$year0.$year;
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($archivedb,$db);
$sql="SHOW TABLES";
$result=mysql_query($sql);
$copied=mysql_num_rows($result);
mysql_select_db("$db_name",$db);
$sql="SHOW TABLES";
$result=mysql_query($sql);
$total=mysql_num_rows($result);
echo "$copied of $total tables archived...<br><br>";
if($copied==$total)
{
?>
<script language="javascript">
window.opener.location="archive.php?finish=1&session=<?php echo $session; ?>";
window.close();
</script>
<?php
}
}
echo $end_html;

?>
