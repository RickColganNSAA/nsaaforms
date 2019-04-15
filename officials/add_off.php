<?php
//add_off.php: add new official manually

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
$ccappsp=array("fb","vb","sb","bb","wr","sw","di","so","ba","tr");

if(ereg("Save",$submit))
{
   $lastname=ereg_replace("\'","\'",$lastname);
   $first=ereg_replace("\'","\'",$first);
   $first=ereg_replace("\"","\'",$first);
   $middle=ereg_replace("\'","\'",$middle);
   $address=ereg_replace("\'","\'",$address);
   $address=ereg_replace("\"","\'",$address);
   $city=ereg_replace("\'","\'",$city);
   $city=ereg_replace("\"","\'",$city);
   $notes=ereg_replace("\'","\'",$notes);
   $notes=ereg_replace("\"","\'",$notes);
   $homeph=$homearea.$homepre.$homepost;
   $workph=$workarea.$workpre.$workpost;
   $cellph=$cellarea.$cellpre.$cellpost;

   $sql="INSERT INTO officials (last,first,middle,socsec,address,city,state,zip,homeph,workph,cellph,email,notes) VALUES ('$lastname','$first','$middle','$socsec','$address','$city','$state','$zip','$homeph','$workph','$cellph','$email','$notes')";
   $result=mysql_query($sql);

   //put this official into logins table
      //get new off id
      $sql2="SELECT id FROM officials WHERE last='$lastname' AND first='$first' AND socsec='$socsec'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $offid=$row2[0];
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
   $sql="INSERT INTO logins (name,level,passcode,offid) VALUES ('$first $lastname',2,'$passcode','$offid')";
   $result=mysql_query($sql);

   //put official into each individual sport table for which he/she is checked
   //also put correct mailing number in that table if payment field is not blank
   for($i=0;$i<count($ccappsp);$i++)
   {
      if($actch[$i]=='x' || $payment[$i]!='')
      {
	 $table=$ccappsp[$i]."off";
	 $table2=$ccappsp[$i]."off_hist";
	 $sql="SELECT mailnum,mailnum2 FROM mailing WHERE sport='$ccappsp[$i]'";
	 $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 $curmail=$row[0]; $curmail2=$row[1];
	 if(trim($payment[$i])=="") $curmail='0';
	 if(trim($payment[$i])!="")	//if paid
	 {
            //First check if they already have a primary number for another sport before it in the list
            $usemailnum=$curmail;
            for($j=0;$j<$i;$j++)
            {
               $curtable=$ccappsp[$j]."off";
               $sql2="SELECT mailing FROM $curtable WHERE offid='$offid' AND payment!=''";
               $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
               if($row2[0]<50 && $row2[0]>0 && mysql_num_rows($result2)>0)
               {
                  $usemailnum=$curmail2;
                  $j=$i;
               }
            }
  	    $appdate=date("Y-m-d");
   	    $payment2[$i]=addslashes($payment[$i]);
	    $sql="INSERT INTO $table (offid,mailing,payment) VALUES ('$offid','$usemailnum','$payment2[$i]')";
	    $result=mysql_query($sql);
	    //echo $sql;
	    //HISTORY TABLE:
	    $curryr=date("Y"); $currmo=date("m");
	    $regyr=GetSchoolYear($curryr,$currmo);
            $sql="INSERT INTO $table2 (offid,regyr,appdate) VALUES ('$offid','$regyr','$appdate')";
	    $result=mysql_query($sql);
	 }
   	 $sql="UPDATE officials SET ".$ccappsp[$i]."='x' WHERE id='$offid'";
	 $result=mysql_query($sql);
      }
   }

   if($submit=="Save & Close" || $submit=="Save & Keep Editing")
   {
?>
<script language="javascript">
window.close()
window.opener.top.location.replace('edit_off.php?session=<?php echo $session; ?>&offid=<?php echo $offid; ?>');
</script>
<?php
   }
   else
   {
	//close window and go back to advanced search
?>
<script language="javascript">
window.close();
window.opener.top.location.replace('off_query.php?session=<?php echo $session; ?>');
</script>
<?php
   }
}

echo $init_html;
echo "<table><tr align=center><td>";

echo "<form name=\"myForm\" method=post action=\"add_off.php\" onsubmit=\"return validateForm()\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=sport value=\"$sport\">";
echo "<input type=hidden name=last value=$last>";
echo "<table>";
echo "<tr align=center>";
echo "<th colspan=2>Add New Official:</th></tr>";
echo "<tr><td colspan=2><hr></td></tr>";
echo "<tr align=left><th class=smaller align=left>Passcode:</th>";
echo "<td><input type=text name=passcode size=20></td></tr>";
echo "<tr align=left><th class=smaller align=left>Name:</th>";
echo "<td><input type=text name=lastname value=\"(last)\" onClick=\"this.value='';\" size=15>&nbsp;,&nbsp;&nbsp;";
echo "<input type=text name=first value=\"(first)\" onClick=\"this.value='';\" size=10>&nbsp;";
echo "<input type=text name=middle value=\"(MI)\" onClick=\"this.value='';\" size=3>";
echo "</td></tr>";
echo "<tr align=left><th class=smaller align=left>Soc Sec #:</th>";
echo "<td align=left><input type=text name=socsec size=10 maxlength=9 onKeyUp='return autoTab(this,9,event);'></td></tr>";
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
echo "<tr align=left><th class=smaller align=left colspan=2>Notes:</th></tr>";
echo "<tr align=center><td align=center colspan=2><textarea rows=5 cols=50 name=notes>$row[notes]</textarea></td></tr>";
echo "<tr align=left><th class=smaller colspan=2 align=left>Sports:</th></tr>";
echo "<tr align=left><td colspan=2 align=left><i>Check the box next to the sports this official is eligible for and indicate their method of payment.</i></td></tr>";
echo "<tr align=center><td colspan=2 align=center><table>";
for($i=0;$i<count($ccappsp);$i++)
{
   if($i%2==0)
      echo "<tr align=left>";
   echo "<td align=left>";
   echo "<input type=checkbox name=\"actch[$i]\" value='x'";
   echo ">&nbsp;".GetSportName($ccappsp[$i]);
   echo "</td>";
   echo "<td><input type=text name=\"payment[$i]\" size=10 class=tiny></td>";
   if(($i+1)%2==0)
      echo "</tr>";
}
echo "</table></td></tr>";
echo "<tr align=center><td colspan=2><br>";
echo "<input type=submit name=submit value=\"Save & Keep Editing\">&nbsp;";
echo "<input type=submit name=submit value=\"Save & Close\">";
echo "&nbsp;<input type=submit name=submit value=\"Save & Search Again\">";
echo "</td></tr>";
echo "</table></form>";

echo $end_html;
?>
<script>
<script>
function validateForm() {
    var x = document.forms["myForm"]["passcode"].value;

	var y = x.match(/[a-z]/i);
	var z = x.match(/\d+/g);

    if (x == "") {
        alert("Passcode must be filled out");
        return false;
    }
	else if (x.length<8 || y==null   || z==null) {
	   alert("Passcode length must be at least 8 charecters long and should contain a letter and a digit");
	   return false;
	}
}
</script>
</script>