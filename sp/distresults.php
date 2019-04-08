<?php

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if($offadmin==1)
{
   if(!ValidUser($session,"$db_name2"))
   {
      header("Location:../index.php");
      exit();
   }
}
else if(!ValidUser($session) || GetLevel($session)!=1)
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

if($offadmin==1)
{
   echo $init_html;
   echo "<table width=100%><tr align=center><td>";
}
else
{
   echo $init_html;
   echo $header;
}

echo "<br><table><caption><b>Speech District Results:</b><br><br><i>The results that are BLUE LINKS have also been published to the <a target='_blank' href='https://nsaahome.org/sp.php'>Speech Page on the NSAA Website</a>. (They are published to the site when the director marks them as FINAL.)</i></caption>";
$sql0="SELECT DISTINCT class FROM $db_name2.spdistricts WHERE type='District' ORDER BY class";
$result0=mysql_query($sql0);
while($row0=mysql_fetch_array($result0))
{
   $class=$row0[0];
   echo "<tr align=left><th align=left colspan=3>Class $class:</th></tr>";
   $sql="SELECT * FROM $db_name2.spdistricts WHERE class='$class' AND type='District' ORDER BY class,district";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $curdist=$row[district];
      $distid=$row[id];
      if($row[submitted]=='')
      {
         echo "<tr align=left><th align=left><font style=\"color:#A0A0A0\">$class-$curdist Speech Results (.html)</font></td>";
	 echo "<td width=25>&nbsp;</td>";
	 echo "<th align=left><font style=\"color:#A0A0A0\">$class-$curdist Speech Results (.csv)</font></td></tr>";
      }
      else
      {
         echo "<tr align=left><td><a target=\"_blank\" href=\"sp_state_view.php?session=$session&school_ch=$row[hostschool]&district=$row[id]&offadmin=$offadmin\">$class-$curdist Speech Results (.html)</a></td>";
         echo "<td width=25>&nbsp;</td>";
         $filename="spstate".$class.$curdist.".csv";
    	 echo "<td><a target=\"_blank\" href=\"../attachments.php?filename=$filename&session=$session\">$class-$curdist Speech Results (.csv)</a></td></tr>";
      }
   }
}
echo "</table>";

echo $end_html;
?>
