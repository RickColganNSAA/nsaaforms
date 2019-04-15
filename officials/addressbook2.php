<?php
//OFFICIALS ADDRESS BOOK TOOL
//11/09/09
//COPIED FROM ../addressbook2.php for similar functionality

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name2, $db);

//verify user
if(!ValidUser($session) || GetLevel($session)!=1)
{
   header("Location:index.php");
   exit();
}

$notfound="";
$missingemail=0;
if($addrecip)
{
   $csv="\"Name\",\"City\",\"E-mail\"\r\n";
   $recipients="";
   if($sport_array[0]=="All Officials")
   {
      $empty=array();
      $sport_array=array_merge($empty,$activity);
   }
   for($i=0;$i<count($sport_array);$i++)
   {
      $table=$sport_array[$i]."off";
      //GET OFFICIALS FOR THIS SPORT WHO HAVE PAID THIS YEAR:
      $sql="SELECT * FROM officials AS t1,$table AS t2 WHERE t1.id=t2.offid AND t2.payment!='' AND t1.inactive!='x'";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         if(!ereg($row[email].",",$recipients))
	 {
	    $recipients.=$row[email].", ";
	    $csv.="\"$row[first] $row[last]\",\"$row[city]\",\"$row[email]\"\r\n";
	 }
      }
   }
   $recipients=substr($recipients,0,strlen($recipients)-2);
   $open=fopen(citgf_fopen("/home/nsaahome/reports/recipients.csv"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/recipients.csv");
   $temp=split(",",$recipients);
   $recipct=count($temp);
   //if(substr($recipients,strlen($recipients)-2,1)==',') $recipients=substr($recipients,0,strlen($recipients)-2);
}
else if($save)
{
  $recipients=substr($recipients,0,strlen($recipients)-2); 
?>
<script language="javascript">
window.opener.document.forms.emailform.email.value = "<?php echo $recipients; ?>";
window.close()
</script>
<?php
   exit();
}

echo $init_html;
echo GetHeader($session);
echo "<br><form method=post action=\"addressbook2.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=form value=\"$form\">";
echo "<table><caption><b>NSAA Officials Address Book<hr></b></caption>";
echo "<tr align=left valign=top><th width='200px' align=left>Sport(s):<br><br><font size=1>(Hold down CTRL(PC) or Apple(Mac) to make multiple selections)</th><td>";
echo "<select name=sport_array[] MULTIPLE size=5>";
echo "<option>All Officials</option>";
for($i=0;$i<count($activity);$i++)
{
   echo "<option value='$activity[$i]'>$act_long[$i]</option>";
}
echo "</select></td></tr>";
/*
echo "<tr align=left valign=top><th>Filter:</th>";
echo "<td>Show ONLY officials who have <input type=radio name=\"paid\" value=\"yes\"";
if($paid=="yes") echo " checked";
echo "><b>PAID</b>&nbsp;&nbsp;";
echo "<input type=radio name=\"paid\" value=\"no\"";
if($paid=="no") echo " checked";
echo "><b>NOT PAID</b>&nbsp;&nbsp;";
echo "<input type=radio name=\"paid\" value=\"either\"";
if(!$paid || $paid=="either") echo " checked";
echo "><b>EITHER</b> for registration.</td></tr>";
*/
echo "<tr align=center><td colspan=2><input type=submit value=\"Add Recipient(s)\" name=addrecip></td></tr><tr align=center><td colspan=2>(Clicking this button will add the email address of the officials you selected above to the textbox below.)</td></tr>";
echo "<tr align=center><td colspan=2><table width=400><tr align=left><th>Recipient(s): ";
if($recipct && $recipients!='') 
{
   echo "($recipct Total)";
   echo "<br><font style=\"font-size:8pt;font-weight:normal\">(Copy and paste the list of e-mails below into the Recipients box in your e-mail OR <a class=small href=\"reports.php?session=$session&filename=recipients.csv\">Download an Excel file with these Names and E-mails</a>)</font><br><br>";
   echo "<a href=\"#\" class=small onclick=\"recipients.value='';\">Reset Recipients List</a><br>";
}
echo "<textarea cols=70 rows=20 name=recipients>$recipients</textarea></th></tr>";
if($missingemail==1)
{
   echo "<tr align=left><td><b>The following e-mail addresses were not found in our system:</b><br>$notfound</td></tr>";
}
else
{
   echo "<tr align=left><td><b>[All e-mail addresses were found for the school(s) and staff member(s) specified.]</b></td></tr>";
}
echo "</table>";
echo "</td></tr>";
echo "<tr align=center><td colspan=2><a href=\"welcome.php?session=$session\">Home</a></td></tr>";

echo "</table></td></tr></table></form>";

echo $end_html;
?>
