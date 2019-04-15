<?php
if($home=="Home")
{
   header("Location:welcome.php?session=$session");
   exit();
}

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);
$level=GetLevel($session);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
if(GetLevel($session)==1)
{
   $offid=$givenoffid;
   if(!$givenoffid)
   {
      echO $init_html;
      echo "<br><br>ERROR: no official specified.";
      echo $end_html;
      exit();
   }
}     
else
   $offid=GetOffID($session);

//see if due date has come yet
$date=split("-",GetTestDueDate('so'));
$duedate=mktime(0,0,0,$date[1],$date[2],$date[0]);
$duedate+=24*60*60;
$now=time();
if($now>$duedate && $level!=1 && $offid!='3427')
{
   echo $init_html;
   echo GetHeader($session);
   echo "<br><br>";
   echo "The ".GetSportName("so")." test is past due.  You may no longer take this test.";
   //Check to see if this official has taken the test and can view his/her results.
   //Must be at least 3 days after the due date.
   $sql="SELECT datetaken FROM sotest_results WHERE offid='".GetOffID($session)."'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $days3=3*24*60*60;
   if($row[0]!='' && $now>=($duedate+$days3))
   {
      echo "<br><br><a class=small href=\"viewtest.php?session=$session&sport=so\">Click Here to view your "; 
      echo GetSportName("so")." Test Results</a>";
   }   
   else
      echo "  Thank You!";
   echo "<br><br><a class=small href=\"welcome.php?session=$session\">Home</a>";
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
      $sql="SELECT * FROM sotest_categ WHERE place='1'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $categid=$row[id];
   }
}

//get number of categories
$sql="SELECT id FROM sotest_categ ORDER BY place DESC LIMIT 1";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if($categid=="Finish Test" || $categid>$row[0])
{
   header("Location:sotest_submit.php?givenoffid=$givenoffid&session=$session");
   exit();
}

echo "<html><head><title>NSAA Home</title><link rel=\"stylesheet\" href=\"../../css/nsaaforms.css\" type=\"text/css\"></head>";
echo "<frameset border=0 rows=\"*,100\">";
if(!$categid) $categid='1';
echo "<frame src=\"sotest_main.php?categid=$categid&givenoffid=$givenoffid&session=$session\" name=main scrolling=yes marginheight=0>";
echo "<frame src=\"sotest_footer.php?givenoffid=$givenoffid&session=$session&categid=$categid\" name=footer scrolling=auto marginheight=0>";
echo "</html>";

?>
