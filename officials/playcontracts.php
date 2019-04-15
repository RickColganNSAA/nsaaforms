<?php
require 'functions.php';
require 'variables.php';

$origsport=$sport;
if(ereg("state",$sport)) $classdist="State";
if(ereg("sp",$sport)) $sport='sp';
else $sport='pp';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}

$districts=$sport."districts";
$contracts=$sport."contracts";
$sportname=GetSportName($sport);

if($confirmall)
{
   $sql="SELECT DISTINCT t1.offid,t1.accept,t1.confirm,t1.distid,";
   if($sport=='sp')
      $sql.="t2.dates,";
   $sql.="t2.type,t2.class,t2.district FROM $contracts AS t1,$districts AS t2 WHERE t1.distid=t2.id AND t1.post='y' AND t1.accept='y' AND t1.confirm='' ";
   if($classdist && $classdist!='')
   {
      if($classdist=="State")
         $sql.="AND t2.type='State' ";
      else
         $sql.="AND t2.id='$classdist' ";
   }
   $sql.="ORDER BY t2.type,t2.class,t2.district,t1.offid";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $sql2="UPDATE $contracts SET confirm='y' WHERE offid='$row[offid]' AND distid='$row[distid]'";
      $result2=mysql_query($sql2);
   }
}

echo $init_html;
echo GetHeaderJ($session,"jcontractadmin");
echo "<br>";
echo "<a class=small href=\"assignreportplay.php?session=$session&sport=$origsport\">$sportname Assignments</a>&nbsp;&nbsp;";
echo "<a class=small href=\"assignplay2.php?session=$session&sport=$origsport\">Assign $sportname Judges</a>&nbsp;&nbsp;";
echo "<table cellspacing=4 cellpadding=4>";
echo "<form method=post action=\"playcontracts.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<tr align=center><td colspan=2>";
echo "<select name=sport onchange=\"submit();\">";
echo "<option value=''>Choose Sport</option>";
echo "<option value='pp'";
if($sport=='pp') echo " selected";
echo ">Play</option><option value='sp'";
if($sport=='sp') echo " selected";
echo ">Speech</select>&nbsp;&nbsp;";
echo "<b>Choose District or STATE:&nbsp;";
echo "<select name=classdist onchange=submit()><option value=''>All Districts</option>";
echo "<option";
if($classdist=='State') echo " selected";
echo ">State</option>";
$sql="SELECT DISTINCT id,class,district FROM $districts WHERE type='District' ORDER BY class,district";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value='$row[id]'";
   if($classdist==$row[id]) echo " selected";
   echo ">$row[class]-$row[district]</option>";
}
echo "</select>";
echo "</td></tr>";


//for each District
// 1) if assignments have been posted to AD of host school and 
// 2) links to post to AD's of host schools that haven't been posted to yet
if($classdist!="State")
{
   echo "<tr align=left><td colspan=2><div class=alert><b>PLEASE NOTE:</b> Judges' information will be posted to each District's HOST as the judges' contracts are CONFIRMED.</div></td></tr>";
}//end if not state


echo "<tr align=left valign=top><td>";
echo "<b>Contracts That Have Been ACCEPTED but NOT NSAA-CONFIRMED:</b><br>";
echo "<table cellspacing=0 cellpadding=0>";
$sql="SELECT DISTINCT t1.offid,t1.accept,t1.confirm,t1.distid,";
if($sport=='sp')
   $sql.="t2.dates,";
$sql.="t2.type,t2.class,t2.district FROM $contracts AS t1,$districts AS t2 WHERE t1.distid=t2.id AND t1.post='y' AND t1.accept='y' AND t1.confirm='' ";
if($classdist && $classdist!='')
{
   if($classdist=="State")
      $sql.="AND t2.type='State' ";
   else
      $sql.="AND t2.id='$classdist' ";
}
$sql.="ORDER BY t2.type,t2.class,t2.district,t1.offid";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<input type=submit class=fancybutton name=\"confirmall\" value=\"Confirm ALL of these Accepted Contracts\"><br>";
}
while($row=mysql_fetch_array($result))
{
   echo "<tr align=left>";
   if($row[type]!="State")
      echo "<td><a class=small target=new href=\"playcontract.php?sport=$sport&session=$session&givenoffid=$row[offid]&distid=$row[distid]\">$row[type] $row[class]-$row[district]: ".GetJudgeName($row[offid])."</a></td></tr>";
   else if($sport=='pp')
      echo "<td><a class=small target=new href=\"playcontract.php?sport=$sport&distid=$row[distid]&session=$session&givenoffid=$row[offid]\">Class $row[class] State: ".GetJudgeName($row[offid])."</a></td></tr>";
   else
      echo "<td><a class=small target=new href=\"playcontract.php?sport=$sport&distid=$row[distid]&session=$session&givenoffid=$row[offid]\">".GetJudgeName($row[offid])." (".date("l",strtotime($row[dates])).")</a></td></tr>";
}
echo "</table>";

echo "</td><td>";
echo "<b>Contracts That Have Been DECLINED but NOT NSAA-ACKNOWLEDGED:</b><br>";

//DECLINED NOT CONFIRMED
$sql="SELECT DISTINCT t1.distid,t1.offid,t1.accept,t1.confirm,t2.type,t2.class,t2.district FROM $contracts AS t1, $districts AS t2 WHERE t1.distid=t2.id AND t1.post='y' AND t1.accept='n' AND t1.confirm='' ";
if($classdist && $classdist!='')
{
   if($classdist=="State")
      $sql.="AND t2.type='State' ";
   else
      $sql.="AND t2.id='$classdist' ";
}
$sql.="ORDER BY t2.type,t2.class,t2.district,t1.offid";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if($row[type]!="State")
      echo "<a class=small target=new href=\"playcontract.php?sport=$sport&session=$session&givenoffid=$row[offid]&distid=$row[distid]\">$row[type] $row[class]-$row[district]: ".GetJudgeName($row[offid])."</a><br>";
   else
   {
      echo "<a class=small target=new href=\"playcontract.php?sport=$sport&distid=$row[distid]&session=$session&givenoffid=$row[offid]\">";
      if($sport=='pp') echo "Class $row[class] State: ";
      echo GetJudgeName($row[offid])."</a><br>";
   }
}
echo "</td></tr>";
echo "<tr align=left valign=top>";

//now show contracts ACCEPTED and CONFIRMED:
echo "<td><b>Contracts That Have Been ACCEPTED and NSAA-CONFIRMED:</b><br>";
echo "<table cellspacing=0 cellpadding=0>";
$sql="SELECT DISTINCT t1.distid,t1.offid,t1.accept,t1.confirm,";
$sql.="t2.type,t2.class,t2.district FROM $contracts AS t1, $districts AS t2 WHERE t1.distid=t2.id AND t1.post='y' AND t1.accept='y' AND t1.confirm='y' ";
if($classdist && $classdist!='')
{
   if($classdist=="State")
      $sql.="AND t2.type='State' ";
   else
      $sql.="AND t2.id='$classdist' ";
}
$sql.="ORDER BY t2.type,t2.class,t2.district,t1.offid";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if($row[type]!="State")
      echo "<tr align=left><td><a class=small target=new href=\"playcontract.php?sport=$sport&session=$session&givenoffid=$row[offid]&distid=$row[distid]\">$row[class]-$row[district]: ".GetJudgeName($row[offid])."</a></td></tr>";
   else
   {
      echo "<tr align=left><td><a class=small target=new href=\"playcontract.php?sport=$sport&distid=$row[distid]&session=$session&givenoffid=$row[offid]\">";
      if($sport=='pp') echo "Class $row[class] State: ";
      echo GetJudgeName($row[offid])."</a></td></tr>";
   }
}
echo "</table>";
echo "</td><td>";

//ACCEPTED and REJECTED:
echo "<b>Contracts That Have Been ACCEPTED but NSAA-REJECTED:</b><br>";
$sql="SELECT DISTINCT t1.distid,t1.offid,t1.accept,t1.confirm,t2.type,t2.class,t2.district FROM $contracts AS t1, $districts AS t2 WHERE t1.distid=t2.id AND t1.post='y' AND t1.accept='y' AND t1.confirm='n' ";
if($classdist && $classdist!='')
{
   if($classdist=="State")
      $sql.="AND t2.type='State' ";
   else
      $sql.="AND t2.id='$classdist' ";
}
$sql.="ORDER BY t2.type,t2.class,t2.district,t1.offid";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if($row[type]!="State")
      echo "<a class=small target=new href=\"playcontract.php?sport=$sport&session=$session&givenoffid=$row[offid]&distid=$row[distid]\">$row[class]-$row[district]: ".GetJudgeName($row[offid])."</a><br>";
   else
   {
      echo "<a class=small target=new href=\"playcontract.php?sport=$sport&distid=$row[distid]&session=$session&givenoffid=$row[offid]\">";
      if($sport=='pp') echo "Class $row[class] State: ";
      echo GetJudgeName($row[offid])."</a><br>";
   }
}
echo "</td></tr>";
echo "</table>";
echo "</form>";
echo $end_html;
?>
