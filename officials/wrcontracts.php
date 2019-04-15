<?php
if(!$distch) $distch="State";
$sport='wr';
$contracts=$sport."contracts";
$districts=$sport."districts";

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

echo $init_html;
echo GetHeader($session,"contractadmin");
echo "<br>";
if($posted=="yes")
{
   echo "<font style=\"color:red\"><b>All Wrestling Contracts are now posted to the assigned officials.</b></font><br><br>";
}
echo "<a class=small href=\"wrassignreport.php?session=$session";
if($distch=='State') echo "&type=state\">View State";
else if($distch=="State Dual") echo "&type=statedual\">View State Dual";
else echo "\">View District";
echo " Wrestling Assignments</a>&nbsp;&nbsp;&nbsp;";
echo "<a class=small href=\"assignpost.php?session=$session&sport=wr&return=wrcontracts\">POST All Wrestling Contracts</a><br>";
echo "<br>";
echo "<table cellspacing=4 cellpadding=4>";
echo "<caption><b>Wrestling Officials' Contracts:</b></caption>";
echo "<form method=post action=\"wrcontracts.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<tr align=center><td colspan=2>";
echo "Choose a District or STATE or STATE DUAL:&nbsp;<select name=distch onchange=submit()>";
echo "<option";
if($distch=="State" || !$distch) echo " selected";
echo ">State</option><option";
if($distch=="State Dual") echo " selected";
echo ">State Dual</option><option";
if($distch=="All Districts") echo " selected";
echo ">All Districts</option>";
$sql="SELECT DISTINCT id,class,district FROM $districts WHERE type NOT LIKE 'State%' ORDER BY class,district";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=$row[0]";
   if($row[id]==$distch) echo " selected";
   echo ">$row[class]-$row[district]</option>";
}
echo "</select>";
echo "</td></tr>";

if($distch && $distch!="State" && $distch!="State Dual")
{
//for each District, show...
// 1) if assignments have been posted to AD of host school and 
// 2) links to post to AD's of host schools that haven't been posted to yet
echo "<tr align=left><td colspan=2>";
$sql="SELECT * FROM $districts WHERE showoffs='y' ";
if($distch && !ereg("All",$distch))
   $sql.="AND id='$distch' ";
else	//All districts
   $sql.="AND type!='State' ";
$sql.="ORDER BY class,district";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
      echo "<b>$row[class]-$row[district]</b> assignments have been posted to the host school <b>$row[hostschool]</b>.<br>";
}
echo "</td></tr>";
echo "<tr align=left><td>";
$sql="SELECT * FROM $districts WHERE showoffs!='y' ";
if($distch && !ereg("All",$distch))
   $sql.="AND id='$distch' ";
else	//ALL Districts
   $sql.="AND type!='State' ";
$sql.="ORDER BY class,district";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<b>Click to post to the hosts of the following games";
   echo ":</b><br>";
   echo "<i>(Please confirm all of the officials' contracts for a district before posting to the Host School)</i><br>";
}
while($row=mysql_fetch_array($result))
{
   echo "<a class=small href=\"posttoad.php?session=$session&id=$row[id]&sport=$sport&distch=$distch\">$row[class]-$row[district], hosted by $row[hostschool]";
   echo "</a><br>";
}
echo "</td></tr>";
}	//END IF NON STATE

//if STATE, get id for state from districst table
if($distch=="State" || $distch=="State Dual")
{
   $sql="SELECT id FROM $districts WHERE type='$distch'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $distch=$row[id];
   $state=1;
}
else
   $state=0;

/****ACCEPTED NOT CONFIRMED****/
echo "<tr align=left valign=top><td>";
echo "<b>Contracts That Have Been ACCEPTED but NOT NSAA-CONFIRMED:</b><br>";
$sql="SELECT DISTINCT t1.offid,t1.accept,t1.confirm,t2.type,t2.class,t2.district,t2.id,t2.hostschool FROM $contracts AS t1,$districts AS t2 WHERE t1.distid=t2.id AND t1.post='y' AND t1.accept='y' AND t1.confirm='' ";
if($distch && !ereg("All",$distch) && distch!='State' && $distch!="State Dual")
{
   $sql.="AND t2.id='$distch' ";
}
else if($distch && $distch!='State' && $distch!="State Dual")
   $sql.="AND t2.type NOT LIKE 'State%' ";
if($distch=='State' || $distch=="State Dual")
   $sql.="AND t2.type LIKE 'State%' ";
$sql.="ORDER BY t2.class,t2.district";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if($row[type]=="State") echo "<a class=small target=\"_blank\" href=\"wrstatecontract.php";
   else if($row[type]=="State Dual") echo "<a class=small target=\"_blank\" href=\"wrstatedualcontract.php";
   else echo "<a class=small target=new href=\"".$sport."contract.php";
   echo "?session=$session&givenoffid=$row[offid]&distid=$row[id]\">";
   if($state==0) echo "$row[class]-$row[district] @ $row[hostschool]: ";
   echo GetOffName($row[offid])."</a><br>";
} 
echo "</td>";

/****DECLINED NOT ACKNOWLEDGED****/
echo "<td><b>Contracts That Have Been DECLINED but NOT NSAA-ACKNOWLEDGED:</b><br>";
$sql="SELECT DISTINCT t1.offid,t1.accept,t1.confirm,t2.type,t2.class,t2.district,t2.id,t2.hostschool FROM $contracts AS t1,$districts AS t2 WHERE t1.distid=t2.id AND t1.post='y' AND t1.accept='n' AND t1.confirm='' ";
if($distch && !ereg("All",$distch) && $distch!="State" && $distch!="State Dual")
{
   $sql.="AND t2.id='$distch' ";
}
else if($distch && $distch!='State' && $distch!="State Dual")
   $sql.="AND t2.type NOT LIKE 'State%' ";
if($distch=='State' || $distch=="State Dual")
   $sql.="AND t2.type='$distch' ";
$sql.="ORDER BY t2.class,t2.district";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if($row[type]=="State") echo "<a class=small target=\"_blank\" href=\"wrstatecontract.php";
   else if($row[type]=="State Dual") echo "<a class=small target=\"_blank\" href=\"wrstatedualcontract.php";
   else echo "<a class=small target=new href=\"".$sport."contract.php";
   echo "?session=$session&givenoffid=$row[offid]&distid=$row[id]\">";
   if($state==0) echo "$row[class]-$row[district] @ $row[hostschool]: ";
   echo GetOffName($row[offid])."</a><br>";
} 
echo "</td></tr>";

/****ACCEPTED AND CONFIRMED****/
echo "<tr align=left>";
echo "<td><b>Contracts That Have Been ACCEPTED and NSAA-CONFIRMED:</b><br>";
$sql="SELECT DISTINCT t1.offid,t1.accept,t1.confirm,t2.type,t2.class,t2.district,t2.id,t2.hostschool FROM $contracts AS t1,$districts AS t2 WHERE t1.distid=t2.id AND t1.post='y' AND t1.accept='y' AND t1.confirm='y' ";
if($distch && !ereg("All",$distch) && $distch!="State" && $distch!="State Dual")
{
   $sql.="AND t2.id='$distch' ";
}
else if($distch && $distch!='State' && $distch!="State Dual")
   $sql.="AND t2.type NOT LIKE 'State%' ";
if($distch=='State' || $distch=="State Dual")
   $sql.="AND t2.type='$distch' ";
$sql.="ORDER BY t2.class,t2.district";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if($row[type]=="State") echo "<a class=small target=\"_blank\" href=\"wrstatecontract.php";
   else if($row[type]=="State Dual") echo "<a class=small target=\"_blank\" href=\"wrstatedualcontract.php";
   else echo "<a class=small target=new href=\"".$sport."contract.php";
   echo "?session=$session&givenoffid=$row[offid]&distid=$row[id]\">";
   if($state==0) echo "$row[class]-$row[district] @ $row[hostschool]: ";
   echo GetOffName($row[offid])."</a><br>";
} 
echo "</td>";

/****ACCEPTED AND REJECTED****/
echo "<td><b>Contracts That Have Been ACCEPTED but NSAA-REJECTED:</b><br>";
$sql="SELECT DISTINCT t1.offid,t1.accept,t1.confirm,t2.type,t2.class,t2.district,t2.id,t2.hostschool FROM $contracts AS t1,$districts AS t2 WHERE t1.distid=t2.id AND t1.post='y' AND t1.accept='y' AND t1.confirm='n' ";
if($distch && !ereg("All",$distch) && $distch!="State" && $distch!="State Dual")
{
   $sql.="AND t2.id='$distch' ";
}
else if($distch && $distch!='State' && $distch!="State Dual")
   $sql.="AND t2.type NOT LIKE 'State%' ";
if($distch=='State' || $distch=="State Dual")
   $sql.="AND t2.type='$distch' ";
$sql.="ORDER BY t2.class,t2.district";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   if($row[type]=="State") echo "<a class=small target=\"_blank\" href=\"wrstatecontract.php";
   else if($row[type]=="State Dual") echo "<a class=small target=\"_blank\" href=\"wrstatedualcontract.php";
   else echo "<a class=small target=new href=\"".$sport."contract.php";
   echo "?session=$session&givenoffid=$row[offid]&distid=$row[id]\">";
   if($state==0) echo "$row[class]-$row[district] @ $row[hostschool]: ";
   echo GetOffName($row[offid])."</a><br>";
} 
echo "</td></tr>";

echo "</table>";
echo "</form>";
echo $end_html;
?>
