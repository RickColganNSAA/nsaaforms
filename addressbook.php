<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$schoolch || $level==5)
{
   $school=GetSchool($session);
}
else
{
   $school=$schoolch;
}
$school2=ereg_replace("\'","\'",$school);

//get array of schools
$sql="SELECT school FROM headers ORDER BY school";
if($level==5)
   $sql="SELECT school FROM largeschools WHERE schgroup='$school2' ORDER BY school";
$result=mysql_query($sql);
$ix=0;
$schools=array();
while($row=mysql_fetch_array($result))
{
   $schools[$ix]=$row[0]; $ix++;
}

$notfound="";
$missingemail=0;
if($addrecip)
{
   if($school_list[0]=="All")
   {
      $school_list=$schools;
   }
   for($i=0;$i<count($school_list);$i++)
   {
      $school3=ereg_replace("\'","\'",$school_list[$i]);
      //$notfound.=$school_list[$i].":<br>";
      if($staff_list[0]=="All")
      {
	 $staff_list=$staffs;
      }
      for($j=0;$j<count($staff_list);$j++)
      {
	 if($staff_list[$j]!="Home Page" && $staff_list[$j]!="Sup Fax" && $staff_list[$j]!="Orchestra")
	 {
	    if($staff_list[$j]=="Athletic Director") 
	       $sql="SELECT email, name, sport, school, level FROM logins WHERE school='$school3' AND sport IS NULL AND level=2";
	    else
	       $sql="SELECT email,name,sport,school,level FROM logins WHERE school='$school3' AND sport LIKE '$staff_list[$j]%'";
	    $result=mysql_query($sql);
	    $row=mysql_fetch_array($result);
	    if($row[0]=="none" || trim($row[0])=="")
	    {
	       if($row[2]=="" && $row[4]==2) $row[2]="Athletic Director";
	       if($row[2]!="")
	       {
		  $missingemail=1;
	          $notfound.=$school_list[$i].": ".$row[2]."<br>";
	       }
	    }
	    else
	    {
	       if(!ereg($row[0],$recipients)) $recipients.=$row[0].", ";
	    }
	 }
      }
      //$notfound=substr($notfound,0,strlen($notfound)-2);
      //$notfound.="<br>";
   }
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
?>

<center><br>
<form method=post action="addressbook.php">
<input type=hidden name=session value="<?php echo $session; ?>">
<input type=hidden name=form value="<?php echo $form; ?>">
<input type=hidden name=school value="<?php echo $school; ?>">
<table><caption><b>NSAA Address Book<hr></b></caption>
<?php
echo "<tr align=left valign=top><th width=150 align=left>School(s):<br><br><font size=1>(Hold down CTRL(PC) or Apple(Mac) to make multiple selections)</th><td>";
echo "<select name=school_list[] multiple size=5>";
echo "<option value='All' selected>All Schools";
for($i=0;$i<count($schools);$i++)
{
   echo "<option>$schools[$i]";
}
echo "</select></td></tr>";
echo "<tr align=left valign=top><th width=150 align=left>Staff Member(s):<br><br><font size=1>(Hold down CTRL(PC) or Apple(Mac) to make multiple selections)</th><td>";
echo "<select name=staff_list[] multiple size=5>";
echo "<option value='All' selected>All Staff Members";
for($i=0;$i<count($staffs);$i++)
{
   if($staffs[$i]!="Home Page" && $staffs[$i]!="Sup Fax" && $staffs[$i]!="Orchestra")
   {
      $staffmem=$staffs[$i];
      if($staffs[$i]=="") $staffmem="Athletic Director";
      echo "<option value=\"$staffmem\">";
      if($staffs[$i]=="Debate" || ereg("Music",$staffs[$i]) || $staffs[$i]=="Journalism" || $staffs[$i]=="Play Production" || $staffs[$i]=="Speech")
	 $staffmem.=" Director";
      else if($staffs[$i]!="Trainer" && $staffs[$i]!="Superintendent" && $staffs[$i]!="Student Council Sponsor" && $staffs[$i]!="Principal" && $staffs[$i]!="Board President" && $staffs[$i]!="Activities Director" && $staffmem!="Athletic Director")
	 $staffmem.=" Coach";
      echo "$staffmem";
      if($staffmem=="Athletic Director") echo "<option>Activities Director";
   }
}
echo "</select></td></tr>";
echo "<tr align=left><td>&nbsp;</td><td><input type=submit value=\"Add Recipient(s)\" name=addrecip></td></tr>";
echo "<tr align=center><td colspan=2><table width=400><tr align=left><th>Recipient(s):<br><textarea cols=70 rows=8 name=recipients>$recipients</textarea></th></tr>";
if($missingemail==1)
{
   echo "<tr align=left><td>The following e-mail addresses were not found in our system:<br><i>$notfound</i></td></tr>";
}
echo "</table>";
echo "</td></tr>";
echo "<tr align=center><td colspan=2><input type=submit name=save value=\"Save & Close\"></td></tr>";

echo "</table></td></tr></table></form>";

echo $end_html;
?>
