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
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2,$db);

$level=GetLevel($session);
$header=GetHeader($session);
if($level==3) $obsid=GetObsID($session);
else $offid=GetOffID($session);

echo $init_html;
echo $header;

//get sports this official is registered for
$spreg_abb=array();
$spreg_long=array();
$ix=0;
$sql="SELECT * FROM officials WHERE id='$offid'";
if($level==3)
   $sql="SELECT * FROM observers WHERE id='$obsid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
for($i=0;$i<count($activity);$i++)
{
   if($row[$activity[$i]]=='x')
   {
      $spreg_abb[$ix]=$activity[$i];
      $spreg_long[$ix]=$act_long[$i];
      $ix++;
   }
}

//get messages for this user from db table and display:
if($level==2 || $level==3)
{
   $sql2="SELECT DISTINCT(title) FROM messages WHERE (";
   for($i=0;$i<count($spreg_abb);$i++)
   {
      $sql2.="sport='$spreg_abb[$i]' OR ";
   }
   $sql2=substr($sql2,0,strlen($sql2)-4);
   $sql2.=") ORDER BY id DESC";
}
$result2=mysql_query($sql2);
$x=0;
$message_array=array();
$y=0;
$garbage_array=array();
while($row2=mysql_fetch_array($result2))
{
   $row2[0]=ereg_replace("\'","\'",$row2[0]);
   $sql="SELECT * FROM messages WHERE title='$row2[0]'";
   $result=mysql_query($sql);
   $showsports="(Recipients: ";
   while($row=mysql_fetch_array($result))
   {
      for($i=0;$i<count($activity);$i++)
      {
	 if($activity[$i]==$row[sport])
	 {
	    $showsports.="$act_long[$i], ";
	    $i=count($activity);
	 }
      }
   }
   $showsports=substr($showsports,0,strlen($showsports)-2);
   $showsports.=" Officials)";

   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);	//get message info again
      //get date message is to show until and see if it is past
      $date=$row[end_date];
      $date=split("-", $date);
      $cur_month=date(M);
      $cur_month=GetMonthNum($cur_month);
      $cur_day=date(j);
      $cur_year=date(Y);
      if($cur_year<$date[0] || ($cur_month<$date[1] && $cur_year==$date[0]) || ($cur_month==$date[1] && $cur_day<=$date[2] && $cur_year==$date[0]))
      {
         for($i=0;$i<count($row);$i++)
         {
	    $message_array[$x][$i]=$row[$i];
         }
	 $recipients[$x]=$showsports;
         $x++;
      }
      else			//get array of old messages
      {
         $garbage_array[$y]=$row[0];
         $y++;
      }
}

//erase old messages:
for($i=0;$i<$y;$i++)
{
   $sql="DELETE FROM messages WHERE id='$garbage_array[$i]'";
   $result=mysql_query($sql);
}
$ct=$x;
?>
<br>
<table width=75% frame=hsides cellspacing=0 cellpadding=3 bordercolor=#000000>
<caption>You have <b><?php echo $ct; ?></b>
   <?php if($ct==0) echo " messages.";
	 else if($ct==1) echo " message:";
	 else echo " messages:";
   ?>
   </caption>
<?php
for($x=0;$x<$ct;$x++)
{
   echo "<tr><td><table>";
   $title=$message_array[$x][1];
   $date=split("-",$message_array[$x][3]);
   $enddate=$date[1]."/".$date[2]."/".$date[0];
   $message=ereg_replace("\r\n","<br>",$message_array[$x][4]);
   $filename=$message_array[$x][5];
   $linkname=$message_array[$x][6];
   echo "<tr align=left><th align=left>$title:</th></tr>";
   echo "<tr align=left><td><i>$recipients[$x]</i></td></tr>";
   echo "<tr align=left><td><p>$message</p></td></tr>";
   if(citgf_file_exists("messagefiles/$filename") && $filename!='')
   {
      echo "<tr align=left><td><a href=\"messagefiles/$filename\" target=new>$linkname</a></td></tr>";
	//echo "<tr align=left><td><a href=\"attachments.php?session=$session&filename=".urlencode($filename)."\" target=new>$linkname</a></td></tr>";
   }
   echo "<tr align=left><td>(This message will be displayed until $enddate)</td></tr>";
   echo "</table><hr></td></tr>";
}

echo "</table>";
echo "<br><br><a href=\"welcome.php?session=$session\" class=small>Home</a>";
echo $end_html;
?>
