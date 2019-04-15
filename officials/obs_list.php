<?php
require 'variables.php';
require 'functions.php';

//connect to db:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

?>

<html>
<head>
<script language="javascript">
function Color(element)
{
   while(element.tagName.toUpperCase() != 'TD' && element != null)
      element = document.all ? element.parentElement : element.parentNode;
   if(element)
   {
      element.bgColor="FFFF33";
   }
}
</script>
<link rel="stylesheet" href="../../css/nsaaforms.css" type="text/css">
</head>
<body>
<table width=100% bordercolor=#000000 border=1 cellspacing="0" cellpadding="2">
<form method="post" name="obs_form" action="update_obs.php">
<input type=hidden name=session value="<?php echo $session; ?>">
<?php
//get sport name
for($i=0;$i<count($activity);$i++)
{
   if($activity[$i]==$sport)
   {
      $sportname=$act_long[$i];
   }
}

$sql="SELECT * FROM observers";
if($sport && $sport!="" && !ereg("All",$sport))	//if specific sport chosen
{
   $sql.=" WHERE $sport='x'";
   $sportch='y';
}
else
   $sportch='n';
if($lastname && $lastname!="")	//if last name given in quick search
{
   if($sportch=='y')
      $sql.=" AND last LIKE '$lastname%'";
   else
      $sql.=" WHERE last LIKE '$lastname%'";
   $lastch='y';
}
else
   $lastch='n';

//***DISPLAY OBSERVERS***//
$ix=0;	//ix is used to see if row is even or odd
if(!$query || $query=="")	//if query not sent from Advanced Search
   $result=mysql_query($sql);
else 
{
   $sql=$query;
   $result=mysql_query($sql);
}
//echo $sql;
$tot_ct=mysql_num_rows($result);

if($mailoption!=3)
   echo "<tr align=left><td colspan=28>Your search returned <b>$tot_ct</b> results,";
else 
   echo "<tr align=left><td colspan=28>";

if(!$last && $tot_ct>=100) $last='a';
else if(!$last) $last="All";
if($last!="All" && ereg("WHERE",$sql) && $lastch=='n') $sql.=" AND last LIKE '$last%'";
else if($last!="All" && $lastch=='n') $sql.=" WHERE last LIKE '$last%'";
$sql.=" ORDER BY last,first";
$query2=$query;
if($last && $last!="All" && $query && $query!="")
{
   if(ereg(" AS ",$query) && !ereg("last",$query))
      $query2.=" AND t1.last LIKE '$last%'";
   else if(!ereg("last",$query))
   {
      if(ereg("WHERE",$query2))
         $query2.=" AND last LIKE '$last%'";
      else
	 $query2.=" WHERE last LIKE '$last%'";
   }
}
if($query && $query!="")
{
   if(ereg(" AS ",$query))
      $query2.=" ORDER BY t1.last,t1.first";
   else
      $query2.=" ORDER BY last,first";
}
if(!$query || $query=="")	//if query not sent from Advanced Search
   $result=mysql_query($sql);
else
{
   $result=mysql_query($query2);
}
$ct=mysql_num_rows($result);
if($mailoption!=3)
   echo " <b>$ct</b> of which are showing: ";

//show links to letters of alphabet for navigation:
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$alphabet=array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
for($i=0;$i<count($alphabet);$i++)
{
   $upper=strtoupper($alphabet[$i]);
   if($last==$alphabet[$i])
   {
      echo "<b><font size=2>$upper&nbsp;</font></b>";
   }
   else
   {
      echo "<a href=\"obs_list.php?last=$alphabet[$i]&sport=$sport&query=$query&session=$session\">$upper</a>&nbsp;";
   }
}
if(!$last || $last=="All")   echo "<b><font size=2>All</font></b>";
else
   echo "<a href=\"obs_list.php?last=All&sport=$sport&query=$query&session=$session\">All</a>";
echo "</td></tr>";

while($row=mysql_fetch_array($result))
{
   //get observer id and submit as hidden to form
   echo "<input type=hidden name=\"obsid[$ix]\" value=\"$row[id]\">";
   if($ix%15==0)
   {
?>
<tr height=27 align=center>
<th class=small>Name<br>(last, first MI)</th>
<th class=small>Passcode</th>
<th class=small>Address</th>
<th class=small>E-mail</th>
<th class=small>Phone</th>
<?php
for($i=0;$i<count($activity);$i++)
{
   echo "<th class=small>".strtoupper($activity[$i])."</th>";
}
?>
</tr>
<?php
   }
   echo "<tr title=\"$row[last], $row[first]\" align=center";
   if($ix%2==0)
   {
      $color="#D0D0D0";
      echo " bgcolor=#D0D0D0";
   }
   else $color="#FFFFFF";
   echo ">";
   echo "<td align=left";
   echo "> <a class=small style=\"color:black\" target=\"_top\" href=\"edit_obs.php?session=$session&id=$row[0]&sport=$sport&query=$query&last=$last\">";
   echo "$row[last], $row[first]</a></td>";
   $sql2="SELECT passcode FROM logins WHERE level='3' AND obsid='$row[id]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $passcode=$row2[0];
   echo "<td>$passcode</td><td align=left>$row[address] $row[city] $row[state] $row[zip]</td>";
   echo "<td align=left><a class=small href=\"mailto:$row[email]\">$row[email]</a></td>";
   echo "<td>";
   if($row[homeph]!="")
      echo "[H] (".substr($row[homeph],0,3).")".substr($row[homeph],3,3)."-".substr($row[homeph],6,4)."<br>";
   if($row[workph]!='')
      echo "[W] (".substr($row[workph],0,3).")".substr($row[workph],3,3)."-".substr($row[workph],6,4)."<br>";
   if($row[cellph]!='')
      echo "[C] (".substr($row[cellph],0,3).")".substr($row[cellph],3,3)."-".substr($row[cellph],6,4)."<br>";
   if($row[fax]!='')
      echo "[F] (".substr($row[fax],0,3).")".substr($row[fax],3,3)."-".substr($row[fax],6,4);
   echo "</td>";
   for($i=0;$i<count($activity);$i++)
   {
      echo "<td><input type=\"checkbox\" onClick=\"Color(this)\" name=\"$activity[$i][$ix]\" value=\"x\"";      
      if($row[$activity[$i]]=="x") echo " checked";
      echo "></td>";
   }
   echo "</tr>";
   $ix++;
}
?>
</table>
<br>
<input type=hidden name=count value=<?php echo $ix; ?>>
<input type=hidden name=last value="<?php echo $last; ?>">
<input type=hidden name=sport value=<?php echo $sport; ?>>
<input type=hidden name=lastname value="<?php echo $lastname; ?>">
<input type=hidden name=query value="<?php echo $query; ?>">
</form>
</body>
</html>
