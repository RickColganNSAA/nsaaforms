<?php
//update_off.php: takes submitted information from officials.php
//	(off_list.php) and updates the db

require 'functions.php';
require 'variables.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

//connect to db:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);

for($i=0;$i<$count;$i++)
{
   $notes[$i]=ereg_replace("\'","\'",$notes[$i]);
   $notes[$i]=ereg_replace("\"","\'",$notes[$i]);
   $sql="UPDATE officials SET di='$di[$i]',fb='$fb[$i]',vb='$vb[$i]',wr='$wr[$i]',bb='$bb[$i]',so='$so[$i]',sb='$sb[$i]',sw='$sw[$i]',ba='$ba[$i]',tr='$tr[$i]',notes='$notes[$i]' WHERE id='$offid[$i]'";
   $result=mysql_query($sql);

   //get current regyr
   $curyr=date("Y");
   $curmo=date("m");
   if($curmo<6)
   {
      $otheryr=$curyr-1;
      $curregyr="$otheryr-$curyr";
   }
   else
   {
      $otheryr=$curyr+1;
      $curregyr="$curyr-$otheryr";
   }

   if($sport && !ereg("All",$sport))
   {
      $table=$sport."off";
      $table2=$table."_hist";
      $sql="SELECT id FROM $table WHERE offid='$offid[$i]'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0)
         $sql="UPDATE $table SET mailing='$mailing[$i]' WHERE offid='$offid[$i]'";
      else
      {
         $mailing[$i]=="-1";
   	 $sql="INSERT INTO $table (offid,mailing) VALUES ('$offid[$i]','$mailing[$i]')";
      }
      $result=mysql_query($sql);
      $sql="SELECT id FROM $table2 WHERE offid='$offid[$i]' AND regyr='$curregyr'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $curid=$row[0];
      if(mysql_num_rows($result)>0)
      {
         $sql="UPDATE $table2 SET rm='$rm[$i]' WHERE id='$curid'";
         $result=mysql_query($sql);
      }
      else if($rm[$i]=='x')
      {
	 $sql="INSERT INTO $table2 (offid,regyr,rm) VALUES ('$offid[$i]' ,'$curregyr','$rm[$i]')";
	 $result=mysql_query($sql);
      }
      //NOW UPDATE THEIR CLASSIFICATION (IF THEY QUALIFY) - ADDED NOV 6 2014
      UpdateRank($offid[$i],$sport);
   }
}
?>
<script language="javascript">
top.location.replace("officials.php?session=<?php echo $session; ?>&lastname=<?php echo $lastname; ?>&sport=<?php echo $sport; ?>&query=<?php echo $query; ?>&last=<?php echo $last; ?>")
</script>
