<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
if(GetLevel($session)==1)
   $offid=$givenoffid;
else
   $offid=GetOffID($session);

for($i=0;$i<count($place);$i++)
{
   //get current category
   $sql="SELECT category FROM fbtest WHERE place='$place[$i]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $curcategid=$row[0];

   $field="ques".$place[$i];
   $sql="SELECT * FROM fbtest_results WHERE offid='$offid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)    //INSERT
   {
      $sql2="INSERT INTO fbtest_results (offid,$field) VALUES ('$offid','$answer[$i]')";
      $result2=mysql_query($sql2);
   }
   else      //UPDATE
   {
      $sql2="UPDATE fbtest_results SET $field='$answer[$i]' WHERE offid='$offid'";
      $result2=mysql_query($sql2);
   }
}

if(!$categid || $categid=="Jump To...")
{
   $categid=$curcategid+1;
}
else if($categid=="Finish Test")
{
   //confirm with user that test is ready to be submitted
}

?>
<script language="javascript">
top.location.replace("fbtest.php?givenoffid=<?php echo $givenoffid; ?>&session=<?php echo $session; ?>&categid=<?php echo $categid; ?>&forcecategid=<?php echo $forcecategid; ?>&home=<?php echo $home; ?>");
</script>
<?php 
exit();
?>
