<?php
$sport='sob';
$disttimes=$sport."disttimes";
$contracts=$sport."contracts";
$districts=$sport."districts";

require 'functions.php';
require 'variables.php';

$sportname=GetSportName($sport);

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
   echo "<font style=\"color:red\"><b>All $sportname Contracts are now posted to the assigned officials.</b></font><br><br>";
}
echo "<a class=small href=\"".$sport."assignreport.php?session=$session\">View Assignments Report</a>&nbsp;&nbsp;";
echo "<a class=small href=\"assign".$sport.".php?session=$session\">Assign $sportname Officials</a>&nbsp;&nbsp;";
echo "<a class=small href=\"assignpost.php?session=$session&sport=$sport&type=&return=".$sport."contracts\">Post DISTRICT Contracts</a>&nbsp;&nbsp;";
echo "<a class=small href=\"assignpost.php?session=$session&sport=$sport&type=state&return=".$sport."contracts\">Post STATE Contracts</a><br>";
echo "<br>";
echo "<table cellspacing=4 cellpadding=4>";
echo "<caption><b>$sportname Officials' Contracts:</b></caption>";
echo "<form method=post action=\"sobcontracts.php\" name=sobcontracts>";
echo "<input type=hidden name=session value=$session>";
echo "<tr align=center><td colspan=2>";
echo "Choose Type:&nbsp;<select name=typech onchange=\"submit();\"><option value=''>~</option>";
$sql2="SELECT DISTINCT type FROM $db_name2.sogdistricts WHERE type!=''";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   echo "<option";
   if($typech==$row2[type]) echo " selected";
   echo ">$row2[type]</option>";
}
echo "</select>&nbsp;&nbsp;";

if($typech!='State')
{
   echo "Choose a District:&nbsp;<select name=distch onchange=\"submit();\"`><option>All</option>";
   $sql="SELECT DISTINCT id,class,district,gender FROM $districts WHERE type='$typech' ORDER BY class,district,gender";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=$row[0]";
      if($row[id]==$distch) echo " selected";
      echo ">$row[gender] $row[class]-$row[district]</option>";
   }
   echo "</select>";
}
echo "</td></tr>";

if($typech && $typech!='')
{
if($typech!='State')
{
   //for each District/Subdistrict, show...
   // 1) if assignments have been posted to AD of host school and 
   // 2) links to post to AD's of host schools that haven't been posted to yet
   echo "<tr valign=top align=left><td>";
   $sql="SELECT * FROM $districts WHERE showoffs!='y' ";
   $sql.="AND type='$typech' ";
   if($distch && !ereg("All",$distch))
      $sql.="AND id='$distch' ";
   $sql.="ORDER BY type,class,district";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>0)
   {
      echo "<b>Click to post to the hosts of the following games";
      echo ":</b><br>";
      echo "<i>(Please confirm all of the officials' contracts for a district before posting to the Host School)</i><br>";
   }
   while($row=mysql_fetch_array($result))
   {
      echo "<a class=small href=\"posttoad.php?session=$session&id=$row[id]&sport=$sport&typech=$typech&distch=$distch\">";
      echo "$row[gender] $row[class]-$row[district], hosted by $row[hostschool]";
      echo "</a><br>";
   }
   echo "</td><td>";
   $sql="SELECT * FROM $districts WHERE showoffs='y' ";
   $sql.="AND type='$typech' ";
   if($distch && !ereg("All",$distch))
      $sql.="AND id='$distch' ";
   $sql.="ORDER BY type,class,district";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
         echo "<b>$row[gender] $row[type] $row[class]-$row[district]</b> assignments have been posted to the host school <b>$row[hostschool]</b>.<br>";
   }
   echo "</td></tr>";
}//end if not district final or state

/****ACCEPTED NOT CONFIRMED****/
echo "<tr align=left valign=top><td>";
echo "<b>Contracts That Have Been ACCEPTED but NOT NSAA-CONFIRMED:</b><br>";
$sql="SELECT DISTINCT t1.offid,t1.accept,t1.confirm,t2.class,t2.district,t2.id,t2.hostschool,t2.gender,t2.type FROM $contracts AS t1,$districts AS t2,$disttimes AS t3 WHERE t3.id=t1.disttimesid AND t3.distid=t2.id AND t1.post='y' AND t1.accept='y' AND t1.confirm='' ";
if($typech && $typech!="")
   $sql.="AND t2.type='$typech' ";
if($distch && !ereg("All",$distch))
   $sql.="AND t2.id='$distch' ";
$sql.="ORDER BY t2.type,t2.class,t2.district";
$result=mysql_query($sql);
$standby=0;
while($row=mysql_fetch_array($result))
{
   echo "<a class=small target=new href=\"".$sport;
   if($row[type]=='State') 
   {
      echo "state";
      //check if stand-by
      $sql2="SELECT t1.time FROM $disttimes AS t1, $contracts AS t2 WHERE t1.id=t2.disttimesid AND t1.distid='$row[id]' AND t2.offid='$row[offid]' AND t1.time='standby'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)	//YES--stand-by
         $standby=1;
   }
   echo "contract.php";
   echo "?session=$session&givenoffid=$row[offid]&distid=$row[id]\">";
   if($row[type]!='State') echo "$row[gender] ";
   echo "$row[type]";
   if($standby==1) echo " (Stand-By)";
   if($row[type]!='State') echo " $row[class]-$row[district] @ $row[hostschool]";
   echo ": ".GetOffName($row[offid])."</a><br>";
} 
echo "</td>";

/****DECLINED NOT ACKNOWLEDGED****/
echo "<td><b>Contracts That Have Been DECLINED but NOT NSAA-ACKNOWLEDGED:</b><br>";
$sql="SELECT DISTINCT t1.offid,t1.accept,t1.confirm,t2.class,t2.district,t2.id,t2.hostschool,t2.gender,t2.type FROM $contracts AS t1,$districts AS t2,$disttimes AS t3 WHERE t3.id=t1.disttimesid AND t3.distid=t2.id AND t1.post='y' AND t1.accept='n' AND t1.confirm='' ";
if($typech && $typech!="")
   $sql.="AND t2.type='$typech' ";
if($distch && !ereg("All",$distch))
   $sql.="AND t2.id='$distch' ";
$sql.="ORDER BY t2.type,t2.class,t2.district";
$result=mysql_query($sql);
$standby=0;
while($row=mysql_fetch_array($result))
{
   echo "<a class=small target=new href=\"".$sport;
   if($row[type]=='State')
   {
      echo "state";
      //check if stand-by
      $sql2="SELECT t1.time FROM $disttimes AS t1, $contracts AS t2 WHERE t1.id=t2.disttimesid AND t1.distid='$row[id]' AND t2.offid='$row[offid]' AND t1.time='standby'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)    //YES--stand-by
         $standby=1;
   }
   echo "contract.php";
   echo "?session=$session&givenoffid=$row[offid]&distid=$row[id]\">";
   if($row[type]!='State') echo "$row[gender] ";
   echo "$row[type]";
   if($standby==1) echo " (Stand-By)";
   if($row[type]!='State') echo " $row[class]-$row[district] @ $row[hostschool]";
   echo ": ".GetOffName($row[offid])."</a><br>";
} 
echo "</td></tr>";

/****ACCEPTED AND CONFIRMED****/
echo "<tr align=left>";
echo "<td><b>Contracts That Have Been ACCEPTED and NSAA-CONFIRMED:</b><br>";
$sql="SELECT DISTINCT t1.offid,t1.accept,t1.confirm,t2.class,t2.district,t2.id,t2.hostschool,t2.gender,t2.type FROM $contracts AS t1,$districts AS t2,$disttimes AS t3 WHERE t3.id=t1.disttimesid AND t3.distid=t2.id AND t1.post='y' AND t1.accept='y' AND t1.confirm='y' ";
if($typech && $typech!="")
   $sql.="AND t2.type='$typech' ";
if($distch && !ereg("All",$distch))
   $sql.="AND t2.id='$distch' ";
$sql.="ORDER BY t2.type,t2.class,t2.district";
$result=mysql_query($sql); 
$standby=0;
while($row=mysql_fetch_array($result))
{
   echo "<a class=small target=new href=\"".$sport;
   if($row[type]=='State')
   {
      echo "state";
      //check if stand-by
      $sql2="SELECT t1.time FROM $disttimes AS t1, $contracts AS t2 WHERE t1.id=t2.disttimesid AND t1.distid='$row[id]' AND t2.offid='$row[offid]' AND t1.time='standby'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)    //YES--stand-by
         $standby=1;
   }
   echo "contract.php";
   echo "?session=$session&givenoffid=$row[offid]&distid=$row[id]\">";
   if($row[type]!='State') echo "$row[gender] ";
   echo "$row[type]";
   if($standby==1) echo " (Stand-By)";
   if($row[type]!='State') echo " $row[class]-$row[district] @ $row[hostschool]";
   echo ": ".GetOffName($row[offid])."</a><br>";
} 
echo "</td>";

/****ACCEPTED AND REJECTED****/
echo "<td><b>Contracts That Have Been ACCEPTED but NSAA-REJECTED:</b><br>";
$sql="SELECT DISTINCT t1.offid,t1.accept,t1.confirm,t2.class,t2.district,t2.id,t2.hostschool,t2.gender,t2.type FROM $contracts AS t1,$districts AS t2,$disttimes AS t3 WHERE t3.id=t1.disttimesid AND t3.distid=t2.id AND t1.post='y' AND t1.accept='y' AND t1.confirm='n' ";
if($typech && $typech!="")
   $sql.="AND t2.type='$typech' ";
if($distch && !ereg("All",$distch))
   $sql.="AND t2.id='$distch' ";
$sql.="ORDER BY t2.type,t2.class,t2.district";
$result=mysql_query($sql); echo mysql_error();
$standby=0;
while($row=mysql_fetch_array($result))
{
   echo "<a class=small target=new href=\"".$sport;
   if($row[type]=='State')
   {
      echo "state";
      //check if stand-by
      $sql2="SELECT t1.time FROM $disttimes AS t1, $contracts AS t2 WHERE t1.id=t2.disttimesid AND t1.distid='$row[id]' AND t2.offid='$row[offid]' AND t1.time='standby'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)    //YES--stand-by
         $standby=1;
   }
   echo "contract.php";
   echo "?session=$session&givenoffid=$row[offid]&distid=$row[id]\">";
   if($row[type]!='State') echo "$row[gender] ";
   echo "$row[type]";
   if($standby==1) echo " (Stand-By)";
   if($row[type]!='State') echo " $row[class]-$row[district] @ $row[hostschool]";
   echo ": ".GetOffName($row[offid])."</a><br>";
} 
echo "</td></tr>";

} //END IF TYPE CHOSEN
echo "</table>";
echo "</form>";
echo $end_html;
?>
