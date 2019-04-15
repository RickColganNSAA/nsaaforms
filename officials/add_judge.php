<?php
//add_judge.php: add new judge manually

require 'variables.php';
require 'functions.php';

//validate user
if(!ValidUser($session))
{
   header("Location:jindex.php");
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

if(ereg("Save",$submit) && $lastname!="(last)")
{
   $lastname=ereg_replace("\'","\'",$lastname);
   $first=ereg_replace("\'","\'",$first);
   $first=ereg_replace("\"","\'",$first);
   $middle=ereg_replace("\'","\'",$middle);
   //$class=ereg_replace("\'","\'",$class);
   $address=ereg_replace("\'","\'",$address);
   $address=ereg_replace("\"","\'",$address);
   $city=ereg_replace("\'","\'",$city);
   $city=ereg_replace("\"","\'",$city);
   $payment=ereg_replace("\'","\'",$payment);
   $homeph=$homearea.$homepre.$homepost;
   $workph=$workarea.$workpre.$workpost;
   $cellph=$cellarea.$cellpre.$cellpost;
   $datereg="$year-$month-$day";

   $sql="INSERT INTO judges (last,first,middle,socsec,address,city,state,zip,homeph,workph,cellph,email,payment,play,speech,datereg,firstyr) VALUES ('$lastname','$first','$middle','$socsec','$address','$city','$state','$zip','$homeph','$workph','$cellph','$email','$payment','$play','$speech','$datereg','$firstyr')";
   $result=mysql_query($sql);

   //put this judges into logins table
      //get new off id
      $sql2="SELECT id FROM judges WHERE last='$lastname' AND first='$first' AND socsec='$socsec'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $offid=$row2[0];
   //if no passcode given but Payment field has something in it, generate passcode:
   if(trim($passcode)=="" && trim($payment)!="")
   {
      $lastname2=ereg_replace("\'","",$lastname);
      $lastname2=ereg_replace(" ","",$lastname2);
      $pass=substr($lastname2,0,6);
      $num=rand(1000,9999);
      $passcode=$pass.$num;
      $sql="SELECT * FROM logins_j WHERE passcode='$passcode'";
      $result=mysql_query($sql);
      while(mysql_num_rows($result)>0)
      {
	 $num++;
	 $passcode=$pass.$num;
	 $sql="SELECT * FROM logins_j WHERE passcode='$passcode'";
	 $result=mysql_query($sql);
      }
   }

   $sql2="SELECT * FROM logins_j WHERE offid='$offid'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)
      $sql="INSERT INTO logins_j (name,level,passcode,offid) VALUES ('$first $lastname',2,'$passcode','$offid')";
   else
      $sql="UPDATE logins_j set passcode='$passcode',name='$first $lastname' WHERE offid='$offid'";
   $result=mysql_query($sql);

   if($submit=="Save & Close")
   {
?>
<script language="javascript">
window.close()
window.opener.top.location.replace('edit_judge.php?session=<?php echo $session; ?>&offid=<?php echo $offid; ?>');
</script>
<?php
   }
   else
   {
	//close window and go back to advanced search
?>
<script language="javascript">
window.close();
window.opener.top.location.replace('judge_query.php?session=<?php echo $session; ?>');
</script>
<?php
   }
}

echo $init_html;

echo "<center>";
echo "<form method=post action=\"add_judge.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=sport1 value=\"$sport1\">";
echo "<input type=hidden name=sport2 value=\"$sport2\">";
echo "<input type=hidden name=bool value=\"$bool\">";
echo "<input type=hidden name=last value=$last>";
echo "<table>";
echo "<tr align=center>";
echo "<th colspan=2>Add New Judge:</th></tr>";
echo "<tr><td colspan=2><hr></td></tr>";
echo "<tr align=left><th class=smaller align=left>Passcode:</th>";
echo "<td><input type=text name=passcode size=20></td></tr>";
echo "<tr align=left><th class=smaller align=left>Name:</th>";
echo "<td><input type=text name=lastname value=\"(last)\" onClick=\"this.value='';\" size=15>&nbsp;,&nbsp;&nbsp;";
echo "<input type=text name=first value=\"(first)\" onClick=\"this.value='';\" size=10>&nbsp;";
echo "<input type=text name=middle value=\"(MI)\" onClick=\"this.value='';\" size=3>";
echo "</td></tr>";
echo "<tr align=left><th class=smaller align=left>Soc Sec #:</th>";
echo "<td align=left><input type=text name=socsec   size=10 maxlength=9 onKeyUpp='return autoTab(this,9,event);'></td></tr>";
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
echo "<tr align=left><th class=smaller align=left>E-mail:</th>";
echo "<td align=left><input type=text size=30 name=email></td></tr>";
echo "<tr align=left><th class=smaller align=left>Payment:</th>";
echo "<td align=left><input type=text name=payment size=20></td></tr>";
echo "<tr align=left><th class=smaller align=left>Registration Date:</th>";
echo "<td><select name=month>";
$curmo=date("m",time());
for($i=1;$i<=12;$i++)
{
   echo "<option";
   if($curmo==$i) echo " selected";
   echo ">$i";
}
echo "</select> / ";
echo "<select name=day>";
$curday=date("d",time());
for($i=1;$i<=31;$i++)
{
   echo "<option";
   if($curday==$i) echo " selected";
   echo ">$i";
}
echo "</select> / ";
$curyr=date("Y",time());
echo "<input name=year type=text class=tiny size=4 value=\"$curyr\"></td></tr>";
echo "<tr align=left><td><b>New Judge:</b></td>";
echo "<td><input type=checkbox name=firstyr value='x'></td></tr>";
echo "<tr align=left><th class=smaller align=left>Activities:</th><td>";
echo "<input type=checkbox name=play value='x'>Play Production&nbsp;&nbsp;";
echo "<input type=checkbox name=speech value='x'>Speech</td></tr>";
echo "<tr align=center><td colspan=2><br>";
echo "<input type=submit name=submit value=\"Save & Close\">";
echo "&nbsp;<input type=submit name=submit value=\"Save & Search Again\">";
echo "</td></tr>";
echo "</table></form>";

echo $end_html;
?>
