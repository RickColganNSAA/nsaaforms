<?php
if($home=="Home")
{
   header("Location:jwelcome.php?session=$session");
   exit();
}

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevelJ($session);

if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}

//see if due date has come yet
$date=split("-",GetTestDueDate('sp'));
$duedate=mktime(0,0,0,$date[1],$date[2],$date[0]);
$duedate+=24*60*60;
$now=time();
if($now>$duedate && $level!=1)
{
   echo $init_html;
   echo GetHeader($session);
   echo "<br><br>";
   echo "The Speech & Play Production tests are past due.  You may no longer take these tests.  Thank You!";
   echo "<br><br><a class=small href=\"jwelcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}
$sql="SELECT * FROM sptest";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
   echo $init_html;
   echo GetHeader($session);
   echo "<br><br>";
   echo "The Speech & Play Production tests are currently unavailable.  Please check back at a later date in order to take these tests.  Thank You!";
   echo "<br><br><a class=small href=\"jwelcome.php?session=$session\">Home</a>";
   echo $end_html;
   exit();
}

//show 2 frames: main frame with test questions, footer frame with navigation tools
if($forcecategid && $forcecategid!="Jump To...")
{
   $categid=$forcecategid;
}
else
{
   if($categid) $categid++;
   else 
   {
      $sql="SELECT id FROM sptest_categ WHERE place='1'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $categid=$row[0];
   }
}

//get number of categories
$sql="SELECT id FROM sptest_categ WHERE id='$categid'";
$result=mysql_query($sql);
if($categid=="Finish Test" || mysql_num_rows($result)==0)
{
   header("Location:sptest_submit.php?session=$session&test=$test&givenoffid=$givenoffid");
   exit();
}

echo "<html><head><title>NSAA Home</title><link rel=\"stylesheet\" href=\"../../css/nsaaforms.css\" type=\"text/css\"></head>";
echo "<frameset border=0 rows=\"*,100\">";
if(!$categid) $categid='1';
echo "<frame src=\"sptest_main.php?test=$test&categid=$categid&givenoffid=$givenoffid&session=$session\" name=main scrolling=yes marginheight=0>";
echo "<frame src=\"sptest_footer.php?test=$test&givenoffid=$givenoffid&session=$session&categid=$categid\" name=footer scrolling=auto marginheight=0>";
echo "</html>";

?>
