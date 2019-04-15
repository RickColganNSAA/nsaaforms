<script>
	 /*   $("#mysport option[value='go_b']").hide();
		$("#mysport option[value='go_g']").hide(); */
</script>
<style>
select#mysport > option[value='go_b'],select#mysport > option[value='go_b']{
 display:none;   
}
</style>
<?php
//rulesmeetingattendance.php: officials & coaches attendance for online rules meetings

require 'functions.php';
require 'variables.php';

$header=GetHeader($session,"rulesmeetingadmin");
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}


echo $init_html.$header;
echo "<br><form method=post action=\"rulesmeetingdata_testing.php?session=1471635820\">";
echo "<input type=hidden name=session value=\"$session\">";
if(!$database) $database=$db_name2;
echo "<h1><select name=\"database\" id=\"database\" onchange=\"submit();\">";
echo "<option value=\"$db_name2\"";
if($database==$db_name2) echo " selected";
echo ">Officials & Judges</option><option value=\"$db_name\"";
if($database==$db_name) echo " selected";
echo ">Coaches & AD's</option></select> ";
echo "<select onchange=\"submit();\" id=\"mysport\" name=\"sport\"><option value=''>All Sports + AD's</option>";
if($database==$db_name) 
{
   echo "<option class='test2' value=\"ad\"";
   if($sport=='ad') echo " selected";
   echo ">AD's</option>";
}
$sql2="SHOW TABLES LIKE '%rulesmeetings'";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   if($database=="nsaascores" && $row2[0]=="trrulesmeetings")
   {
      echo "<option class='test' value=\"te\"";
      if($sport=='te') echo " selected";
      echo ">Tennis</option>";
   }
   $temp=split("rulesmeetings",$row2[0]);
      if($_POST['database'] == "nsaaofficials"){
		  if($temp[0] != 'go_b' && $temp[0] != 'go_g'){
		   echo "<option class='test3' value=\"$temp[0]\"";
		   if($sport==$temp[0]) echo " selected";
		   echo ">".GetSportName($temp[0])."</option>";
		  }
	  } else{
		   echo "<option class='test3' value=\"$temp[0]\"";
   if($sport==$temp[0]) echo " selected";
   echo ">".GetSportName($temp[0])."</option>";	  
	  }
}
echo "</select> ";
echo "Online Rules Meeting Data:</h1>";
if(!$sport || !$database)
{
   echo "<p><i>Please select \"Officials & Judges\" or \"Coaches & AD's\" AND a sport.</i></p>";
   echo $end_html;
   exit();
}
echo "<table frame='all' rules='all' style='border:#808080 1px solid;' cellspacing=0 cellpadding=5>";
echo "<tr align=center>";
if($database==$db_name2) $namelabel="Name (Last, First)";
else $namelabel="School & Name";
if($sort=="name")
{
   if($database==$db_name2) $sort="t2.last,t2.first";
   else $sort="t2.school,t2.name";
   echo "<th>".strtoupper($namelabel)."</th>";
}
else
{
   echo "<td><a href=\"rulesmeetingdata.php?session=$session&database=$database&sport=$sport&sort=name\">$namelabel</a>";
   if($database==$db_name) echo "<br>(sorts by School)";
   echo "</td>";
}
if($sort=="dateinitiated")
   echo "<th>BEGAN WATCHING</th>";
else
   echo "<td><a href=\"rulesmeetingdata.php?session=$session&database=$database&sport=$sport&sort=dateinitiated DESC\">Began Watching</a></td>";
if($sort=="datecompleted")
   echo "<th>FINISHED WATCHING</th>";
else
   echo "<td><a href=\"rulesmeetingdata.php?session=$session&database=$database&sport=$sport&sort=datecompleted\">Finished Watching</a></td>";
if($sort=="datepaid")
   echo "<th>COMPLETED VERIFICATION/PAYMENT</th>";
else
   echo "<td><a href=\"rulesmeetingdata.php?session=$session&database=$database&sport=$sport&sort=datepaid\">Completed Verification/Payment</a></td>";
echo "<th>Invoice ID</th><th>Signature</th></tr>";
$colheaders.="<tr align=center><th>$namelabel</th><th>Began Watching</th><th>Finished Watching</th><th>Completed Payment/Verification</th><th>Invoice ID</th><th>Signature</th></tr>";
if($database==$db_name2) //OFFICIALS & JUDGES
{
   $sql="SELECT t1.*,t2.first,t2.last FROM ".$database.".".$sport."rulesmeetings AS t1, ";
   if($sport=='sp' || $sport=='pp') $sql.="judges";
   else $sql.="officials";
   $sql.=" AS t2 WHERE t1.offid=t2.id";
}
else
{
   $sql="SELECT t1.*,t2.name,t2.school FROM ".$database.".".$sport."rulesmeetings AS t1, ".$database.".logins AS t2 WHERE t1.coachid=t2.id";
}
if(!$sort || $sort=='') $sort="t1.dateinitiated DESC";
$sql.=" ORDER BY $sort";
$result=mysql_query($sql);
$ix=0;
//echo $sql;
echo mysql_error();
while($row=mysql_fetch_array($result))
{
   if($ix%15==0 && $ix>0) echo $colheaders;
   echo "<tr align=left><td>";
   if($database==$db_name2) //OFFICIALS & JUDGES
      echo "$row[first] $row[last]";
   else
      echo "$row[name] ($row[school])";
   echo "</td>";
   if($row[dateinitiated]==0) $date="N/A";
   else $date=date("D, M j, Y",$row[dateinitiated])." at ".date("g:i:sa T",$row[dateinitiated]);
   echo "<td>$date</td>";
   if($row[datecompleted]==0) $date="N/A";
   else $date=date("D, M j, Y",$row[datecompleted])." at ".date("g:i:sa T",$row[datecompleted]);
   echo "<td";
   if($row[datecompleted]==0 && $row[timewatched]>0) 
   {
      $sec=$row[timewatched]%60;
      $min=floor($row[timewatched]/60);
      echo " bgcolor='#ff0000'>$date (watched $min min $sec sec)";
   }
   else echo ">$date";
   echo "</td>";
   if($row[datepaid]==0) $date="N/A";
   else $date=date("D, M j, Y",$row[datepaid])." at ".date("g:i:sa T",$row[datepaid]);
   echo "<td>$date";
   if($row[datepaid]==0 && $row[datecompleted]>0)	//GIVE LINK TO "PUSH THROUGH" TRANSACTION
   {
      //Sometimes when a transaction is approved, the connection times out before
      //the customer returns from the Authorize.net gateway. Let NSAA push them through
      echo "<br>";
      if($database==$db_name)	//COACHES & AD's
      {
         echo "<a href=\"../rulesmeetingapproval.php?session=$session&secret=46D5431FF61CD7EC47478FB32A52A&ssl_invoice_number=$row[invoiceid]\" target=\"_blank\">Push Transaction Through*</a>";
      }
      else	//JUDGES & OFFICIALS
      {
         echo "<a href=\"rulesmeetingapproval.php?session=$session&secret=46D5431FF61CD7EC47478FB32A52A&ssl_invoice_number=$row[invoiceid]\" target=\"_blank\">Push Transaction Through*</a>";
      }
   }
   echo "</td>";
   echo "<td>$row[invoiceid]&nbsp;</td><td>$row[signature]&nbsp;</td></tr>";
   $ix++;
}
if(mysql_num_rows($result)>0)
   echo "</table></form>";
else
   echo "<tr align=center><td>[No online rules meetings for this sport have been initiated at this time.]</td></tr></table></form>";
echo "<p>* If you see a link to \"Push Transaction Through,\" only click it if an official/judge/coach has paid but is not showing as having paid in the table above.</p>";

echo $end_html;
?>
