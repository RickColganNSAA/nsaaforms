<?php
//view_messages.php: AD's and coaches can view the messages
//	posted to them by NSAA or AD's

require 'functions.php';
require 'variables.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

//get level of user
$level=GetLevel($session);

$school=GetSchool($session);
$school2=ereg_replace("\'","\'",$school);
$activity=GetActivity($session);
$header=GetHeader($session);

?>
<html>
<head>
   <title>NSAA Home</title>
   <link href="../css/nsaaforms.css" rel="stylesheet" type="text/css">
</head>
<?php 
echo $header;

//get messages for this user from db table and display:
$today=date("Y-m-d");
if($level==2) //AD
{
   //$sql="SELECT * FROM messages WHERE (sportreg!='' OR school='All' OR school='All Schools' OR school='$school2') AND sport IS NULL AND poster='NSAA' AND CURDATE()<=end_date ORDER BY post_date DESC";
   $sql="SELECT * FROM messages WHERE (sportreg!='' OR school='All' OR school='All Schools' OR school='$school2') AND sport IS NULL AND (poster='NSAA' OR poster LIKE '%Public Schools') AND end_date>='$today' ORDER BY poster,post_date DESC";
}
else if($level==3) //coach
{
   $sql="SELECT * FROM messages WHERE (school='$school2' OR school='All') AND sport='$activity' AND end_date>='$today' ORDER BY school,post_date DESC";
}
$result=mysql_query($sql);
$x=0;
$message_array=array();
while($row=mysql_fetch_array($result))
{
   $show=1;
   if(ereg("Public Schools",$row[poster]) && $level==2)
   {
      $sql2="SELECT * FROM largeschools WHERE school='$school2' AND schgroup='$row[poster]'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0) $show=0;
   }
   if($row[sportreg]!='')
   {
      if(!IsRegistered($school,$row[sportreg])) $show=0;
   }
   if($show==1)
   {
   for($i=0;$i<count($row);$i++)
   {
      $message_array[$x][$i]=$row[$i];
   }
   $x++;
   }
}

//erase old messages:
if($today==date("Y-m-d"))
{
   $sql="DELETE FROM messages WHERE end_date<'$today'";
   $result=mysql_query($sql);
}

$ct=$x;
?>
<br>
<h2>You have <b><?php echo $ct; ?></b>
   <?php if($ct==0) echo " messages.";
	 else if($ct==1) echo " message:";
	 else echo " messages:";
   ?>
   </h2>
<?php
for($x=0;$x<$ct;$x++)
{
   echo "<div class=\"normalwhite\" style=\"width:750px;\">";
   $title=$message_array[$x][1];
   $enddate=split("-",$message_array[$x][4]);
   $message=$message_array[$x][5];
   $filename=$message_array[$x][6];
   $linkname=$message_array[$x][7];
   $poster=$message_array[$x][8];
   $showdate=split("-",$message_array[$x][9]);
   echo "<h3 style=\"margin:0px 0px 5px 0px;padding:0px 0px 0px 0px;\"><i>$title:</i></h3><p style=\"margin-bottom:10px;font-size:11px;\"><label style=\"color:#555555;\">Posted $showdate[1]/$showdate[2]/$showdate[0] by: $poster (to show until $enddate[1]/$enddate[2]/$enddate[0])</label></p>";
   echo "<p>".trim($message)."</p>";
   if(citgf_file_exists("messagefiles/$filename") && $filename!='')
      echo "<p><a href=\"messagefiles/$filename\" target=new>$linkname</a></p>";
   echo "</div><br>";
}
?>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
