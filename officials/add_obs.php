<?php
//add_obs.php: add new observer manually

require 'variables.php';
require 'functions.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}   

//connect to database:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

//get level of user
$level=GetLevel($session);

//javascript for autoTab
?>
<script language="javascript">
<?php echo $autotab; ?>
</script>
<?php

if(ereg("Save",$submit))
{
   $lastname=ereg_replace("\'","\'",$lastname);
   $first=ereg_replace("\'","\'",$first);
   $first=ereg_replace("\"","\'",$first);
   //$middle=ereg_replace("\'","\'",$middle);
   $address=ereg_replace("\'","\'",$address);
   $address=ereg_replace("\"","\'",$address);
   $city=ereg_replace("\'","\'",$city);
   $city=ereg_replace("\"","\'",$city);
   //$notes=ereg_replace("\'","\'",$notes);
   //$notes=ereg_replace("\"","\'",$notes);
   $homeph=$homearea.$homepre.$homepost;
   $workph=$workarea.$workpre.$workpost;
   $cellph=$cellarea.$cellpre.$cellpost;
   $fax=$faxarea.$faxpre.$faxpost;

   $sql="INSERT INTO observers (last,first,address,city,state,zip,homeph,workph,cellph,fax,email";
   for($i=0;$i<count($activity);$i++)
      $sql.=",$activity[$i]";
   $sql.=") VALUES ('$lastname','$first','$address','$city','$state','$zip','$homeph','$workph','$cellph','$fax','$email'";
   for($i=0;$i<count($activity);$i++)
      $sql.=",'$actch[$i]'";
   $sql.=")";
   $result=mysql_query($sql);
   //echo "$sql<br>".mysql_error();

   //put this observer into logins table
      //get new observer id
      $sql2="SELECT id FROM observers WHERE last='$lastname' AND first='$first'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $obsid=$row2[0];
   //if no passcode given, generate passcode:
   if(trim($passcode)=="")
   {
      $lastname2=ereg_replace("\'","",$lastname);
      $lastname2=ereg_replace(" ","",$lastname2);
      $pass=substr($lastname2,0,6);
      $num=rand(1000,9999);
      $passcode=$pass.$num;
      $sql="SELECT * FROM logins WHERE passcode='$passcode'";
      $result=mysql_query($sql);
      while(mysql_num_rows($result)>0)
      {
	 $num++;
	 $passcode=$pass.$num;
	 $sql="SELECT * FROM logins WHERE passcode='$passcode'";
	 $result=mysql_query($sql);
      }
   }
   $sql="INSERT INTO logins (name,level,passcode,obsid) VALUES ('$first $lastname',3,'$passcode','$obsid')";
   $result=mysql_query($sql);
   //echo "$sql<br>".mysql_error();

   if($submit=="Save & View this Observer")
   {
?>
<script language="javascript">
window.close()
window.opener.top.location.replace('edit_obs.php?session=<?php echo $session; ?>&obsid=<?php echo $obsid; ?>');
</script>
<?php
   }
   else if($submit=="Save & Add Another")
   {
      //nop
   }
   else
   {
	//close window and go back to advanced search
?>
<script language="javascript">
window.close();
window.opener.top.location.replace('obs_query.php?session=<?php echo $session; ?>');
</script>
<?php
   }
}

echo $init_html;
echo "<table width=100%><tr align=center><td>";

echo "<form method=post action=\"add_obs.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=sport value=\"$sport\">";
echo "<input type=hidden name=last value=$last>";
echo "<table>";
echo "<tr align=center>";
echo "<th colspan=2>Add New Observer:</th></tr>";
echo "<tr><td colspan=2><hr></td></tr>";
echo "<tr align=left><th class=smaller align=left>Passcode:</th>";
echo "<td><input type=text name=passcode size=20></td></tr>";
echo "<tr align=left><th class=smaller align=left>Name:</th>";
echo "<td><input type=text name=lastname value=\"(last)\" onClick=\"this.value='';\" size=15>&nbsp;,&nbsp;&nbsp;";
echo "<input type=text name=first value=\"(first)\" onClick=\"this.value='';\" size=10>&nbsp;";
//echo "<input type=text name=middle value=\"(MI)\" onClick=\"this.value='';\" size=3>";
echo "</td></tr>";
echo "<tr align=left><th class=smaller align=left>Address:</th>";
echo "<td align=left><input type=text name=address size=30></td></tr>";
echo "<tr align=left><th class=smaller align=left>City, State Zip:</th>";
echo "<td align=left><input type=text name=city size=20>&nbsp;,&nbsp;&nbsp;";
echo "<input type=text name=state size=3>&nbsp;&nbsp;";
echo "<input type=text name=zip size=10></td></tr>";
echo "<tr align=left><th class=smaller align=left>Home Phone:</th>";
$homearea=substr($row[homeph],0,3);
$homepre=substr($row[homeph],3,3);
$homepost=substr($row[homeph],6,4);
echo "<td align=left>(<input type=text maxlength=3 size=4 name=homearea onKeyUp='return autoTab(this,3,event);'>)";
echo "<input type=text maxlength=3 size=4 name=homepre onKeyUp='return autoTab(this,3,event);'>-";
echo "<input type=text maxlength=4 size=5 name=homepost onKeyUp='return autoTab(this,4,event);'></td></tr>";
echo "<tr align=left><th class=smaller align=left>Work Phone:</th>";
$workarea=substr($row[workph],0,3);
$workpre=substr($row[workph],3,3);
$workpost=substr($row[workph],6,4);
echo "<td align=left>(<input type=text maxlength=3 size=4 name=workarea onKeyUp='return autoTab(this,3,event);'>)";
echo "<input type=text maxlength=3 size=4 name=workpre onKeyUp='return autoTab(this,3,event);'>-";
echo "<input type=text maxlength=4 size=5 name=workpost onKeyUp='return autoTab(this,4,event);'></td></tr>";
echo "<tr align=left><th class=smaller align=left>Cell Phone:</th>";
$cellarea=substr($row[cellph],0,3);
$cellpre=substr($row[cellph],3,3);
$cellpost=substr($row[cellph],6,4);
echo "<td align=left>(<input type=text maxlength=3 size=4 name=cellarea onKeyUp='return autoTab(this,3,event);'>)";
echo "<input type=text maxlength=3 size=4 name=cellpre onKeyUp='return autoTab(this,3,event);'>-";
echo "<input type=text maxlength=4 size=5 name=cellpost onKeyUp='return autoTab(this,4,event);'></td></tr>";
echo "<tr align=left><th class=smaller align=left>Fax:</th>";
$faxarea=substr($row[fax],0,3);
$faxpre=substr($row[fax],3,3);
$faxpost=substr($row[fax],6,4);
echo "<td align=left>(<input type=text maxlength=3 size=4 name=faxarea onKeyUp='return autoTab(this,3,event);'>)";
echo "<input type=text maxlength=3 size=4 name=faxpre onKeyUp='return autoTab(this,3,event);'>-";
echo "<input type=text maxlength=4 size=5 name=faxpost onKeyUp='return autoTab(this,4,event);'></td></tr>";
echo "<tr align=left><th class=smaller align=left>E-mail:</th>";
echo "<td align=left><input type=text size=30 name=email></td></tr>";
echo "<tr align=left><th class=smaller colspan=2 align=left>Sports:</th></tr>";
echo "<tr align=left><td colspan=2 align=left><i>Check the box next to the sport(s) this observer will be dealing with.</i></td></tr>";
echo "<tr align=center><td colspan=2 align=center><table>";
for($i=0;$i<count($activity);$i++)
{
   if($i%2==0)
      echo "<tr align=left>";
   echo "<td align=left>";
   echo "<input type=checkbox name=\"actch[$i]\" value='x'";
   echo ">&nbsp;$act_long[$i]";
   echo "</td>";
   if(($i+1)%2==0)
      echo "</tr>";
}
echo "</table></td></tr>";
echo "<tr align=center><td colspan=2><br>";
echo "<input type=submit name=submit value=\"Save & View this Observer\">&nbsp;";
echo "<input type=submit name=submit value=\"Save & Add Another\">";
echo "&nbsp;<input type=submit name=submit value=\"Save & Search Again\">";
echo "</td></tr>";
echo "</table></form>";

echo $end_html;
?>
