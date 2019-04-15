<?php

require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}
$thisyr=GetFallYear('fb');

echo $init_html;
echo GetHeader($session,"contractadmin");
echo "<br>";
echo "<a class=small href=\"fbassignreport.php?session=$session\">Football Assignments Report</a>&nbsp;&nbsp;";
echo "<a class=small href=\"assignfb.php?session=$session&classch=$classch&round=First Round\">Assign Football Officials</a><br><br>";
echo "<table cellspacing=4 cellpadding=4>";
echo "<caption><b>Football Playoffs Officials' Contracts:</b></caption>";
$sport='fb';
echo "<form method=post action=\"fbcontracts.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<tr align=center><td colspan=2>";
echo "Choose a Class:&nbsp;<select name=classch onchange=\"submit();\"><option>All Classes</option>";
$classes=array("A","B","C1","C2","D1","D2");
for($i=0;$i<count($classes);$i++)
{
   echo "<option";
   if($classes[$i]==$classch) echo " selected";
   echo ">$classes[$i]</option>";
}
echo "</select>&nbsp;";
echo "<select name=round onchange=\"submit();\"><option>All Rounds</option>";
if($classch=='A' || $classch=='B' || $classch=="C1" || $classch=="C2")
   $rounds=array("First Round","Quarterfinals","Semifinals","Finals");
else
   $rounds=array("First Round","Second Round","Quarterfinals","Semifinals","Finals");
for($i=0;$i<count($rounds);$i++)
{
   echo "<option";
   if($round==$rounds[$i]) echo " selected";
   echo ">$rounds[$i]</option>";
}
echo "</select>";
echo "</td></tr>";

$contracts=$sport."contracts";
$brackets=$sport."brackets";

//for each District/Subdistrict, show...
// 1) if assignments have been posted to AD of host school and 
// 2) links to post to AD's of host schools that haven't been posted to yet
echo "<tr align=left><td colspan=2>";
$sql="SELECT t1.*,t2.homeid FROM fbbrackets AS t1,$db_name.fbsched AS t2 WHERE t1.class=t2.class AND t1.roundnum=t2.round AND t1.gamenum=t2.gamenum AND t1.showoffs='y' AND ";
if($classch && !ereg("All",$classch))
   $sql.="t1.class='$classch' AND ";
if($round && !ereg("All",$round))
   $sql.="t1.round='$round' AND ";
$sql=substr($sql,0,strlen($sql)-5);
$sql.=" ORDER BY class,round,gamenum";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $hostschool=GetSchoolName($row[homeid],'fb',$thisyr);
   echo "<b>$row[class] $row[round], Game #$row[gamenum]</b> assignments have been posted to the host school <b>$hostschool</b>.<br>";
}
echo "</td></tr>";
echo "<tr align=left><td>";
$sql="SELECT * FROM fbbrackets WHERE showoffs!='y' AND ";
if($classch && !ereg("All",$classch))
   $sql.="class='$classch' AND ";
if($round && !ereg("All",$round))
   $sql.="round='$round' AND ";
$sql=substr($sql,0,strlen($sql)-5);
$sql.=" ORDER BY class,round,gamenum";
$result=mysql_query($sql);
if(mysql_num_rows($result)>0)
{
   echo "<b>Click to post to the hosts of the following games";
   echo ":</b><br>";
}
while($row=mysql_fetch_array($result))
{
   //GET OPPONENT AND OTHER GAME INFO FROM fbsched
   $sql2="SELECT * FROM $db_name.fbsched WHERE class='$row[class]' AND round='$row[roundnum]' AND gamenum='$row[gamenum]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);

   echo "<a class=small href=\"posttoad.php?session=$session&id=$row[id]&sport=$sport&round=$row[round]&classch=$classch\">$row[class] $row[round]";
   if($row[round]!='Finals') echo ", Game #$row[gamenum]";
   if($row[round]=="Finals")  
   {
      $school1=GetSchoolName($row2[sid],'fb',$thisyr);
      $school2=GetSchoolName($row2[oppid],'fb',$thisyr);
      echo " ($school1 VS $school2)";
   }
   else if($row2[homeid]>0)
      echo " (Host School: ".GetSchoolName($row2[homeid],'fb',$thisyr).")";
   else echo " (host unknown)";
   if($row2[gamesite]!='') echo " at $row2[gamesite]";
   echo "</a><br>";
}

echo "<tr align=left valign=top><td>";
echo "<b>Contracts That Have Been ACCEPTED but NOT NSAA-CONFIRMED:</b><br>";

for($i=0;$i<count($rounds);$i++)
{
   if(($round && !ereg("All",$round) && $round==$rounds[$i]) || !$round || ereg("All",$round))
   {
      $sql="SELECT DISTINCT t1.offid,t1.accept,t1.confirm,t2.class,t2.round,t3.sid,t3.oppid,t2.id,t2.gamenum FROM $contracts AS t1,$brackets AS t2,$db_name.fbsched AS t3 WHERE t1.gameid=t2.id AND t2.class=t3.class AND t2.roundnum=t3.round AND t2.gamenum=t3.gamenum AND t1.post='y' AND t1.accept='y' AND t1.confirm='' AND t2.round='$rounds[$i]' ";
      if($classch && !ereg("All",$classch))
      {
	 $sql.="AND t2.class='$classch' ";
      }
      $sql.="ORDER BY t2.class,t2.gamenum";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         echo "<a class=small target=new href=\"";
	 echo "fbcontract.php";
	 echo "?session=$session&givenoffid=$row[offid]&gameid=$row[id]\">$row[round] $row[class] #$row[gamenum], ".GetSchoolName($row[sid],'fb',$thisyr)." VS ".GetSchoolName($row[oppid],'fb',$thisyr).": ".GetOffName($row[offid])."</a><br>";
      }
   }
}//end for each round 
echo "</td><td>";
echo "<b>Contracts That Have Been DECLINED but NOT NSAA-ACKNOWLEDGED:</b><br>";

//DECLINED NOT CONFIRMED
for($i=0;$i<count($rounds);$i++)
{
   if(($round && !ereg("All",$round) && $round==$rounds[$i]) || !$round || ereg("All",$round))
   {
      $sql="SELECT DISTINCT t1.offid,t1.accept,t1.confirm,t2.class,t2.round,t2.sid1,t2.sid2,t2.id,t2.gamenum FROM $contracts AS t1,$brackets AS t2 WHERE t1.gameid=t2.id AND t1.post='y' AND t1.accept='n' AND t1.confirm='' AND t2.round='$rounds[$i] ";
      $sql="SELECT DISTINCT t1.offid,t1.accept,t1.confirm,t2.class,t2.round,t3.sid,t3.oppid,t2.id,t2.gamenum FROM $contracts AS t1,$brackets AS t2,$db_name.fbsched AS t3 WHERE t1.gameid=t2.id AND t2.class=t3.class AND t2.roundnum=t3.round AND t2.gamenum=t3.gamenum AND t1.post='y' AND t1.accept='n' AND t1.confirm='' AND t2.round='$rounds[$i]' ";
      if($classch && !ereg("All",$classch))
      {
	 $sql.="AND t2.class='$classch' ";
      }
      $sql.="ORDER BY t2.class,t2.gamenum";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         echo "<a class=small target=new href=\"";
	 echo "fbcontract.php";
	 echo "?session=$session&givenoffid=$row[offid]&gameid=$row[id]\">$row[round] $row[class] #$row[gamenum], ".GetSchoolName($row[sid],'fb',$thisyr)." VS ".GetSchoolName($row[oppid],'fb',$thisyr).": ".GetOffName($row[offid])."</a><br>";
      }
   }
}
echo "</td></tr>";
echo "<tr align=left>";

//now show contracts ACCEPTED and CONFIRMED:
echo "<td><b>Contracts That Have Been ACCEPTED and NSAA-CONFIRMED:</b><br>";
for($i=0;$i<count($rounds);$i++)
{
   if(($round && !ereg("All",$round) && $round==$rounds[$i]) || !$round || ereg("All",$round))
   {
      $sql="SELECT DISTINCT t1.offid,t1.accept,t1.confirm,t2.class,t2.round,t2.sid1,t2.sid2,t2.id,t2.gamenum FROM $contracts AS t1, $brackets AS t2 WHERE t1.gameid=t2.id AND t1.post='y' AND t1.accept='y' AND t1.confirm='y' AND t2.round='$rounds[$i]' ";
      $sql="SELECT DISTINCT t1.offid,t1.accept,t1.confirm,t2.class,t2.round,t3.sid,t3.oppid,t2.id,t2.gamenum FROM $contracts AS t1,$brackets AS t2,$db_name.fbsched AS t3 WHERE t1.gameid=t2.id AND t2.class=t3.class AND t2.roundnum=t3.round AND t2.gamenum=t3.gamenum AND t1.post='y' AND t1.accept='y' AND t1.confirm='y' AND t2.round='$rounds[$i]' ";
      if($classch && !ereg("All",$classch))
      {
	 $sql.="AND t2.class='$classch' ";
      }
      $sql.="ORDER BY t2.class,t2.gamenum";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
	 echo "<a class=small target=new href=\"";
	 echo "fbcontract.php";
	 echo "?session=$session&givenoffid=$row[offid]&gameid=$row[id]\">$row[round] $row[class] #$row[gamenum], ".GetSchoolName($row[sid],'fb',$thisyr)." VS ".GetSchoolName($row[oppid],'fb',$thisyr).": ".GetOffName($row[offid])."</a><br>";
      }
   }
}
echo "</td><td>";

//ACCEPTED and REJECTED:
echo "<b>Contracts That Have Been ACCEPTED but NSAA-REJECTED:</b><br>";
for($i=0;$i<count($rounds);$i++)
{
   if(($round && !ereg("All",$round) && $round==$rounds[$i]) || !$round || ereg("All",$round))
   {
      $sql="SELECT DISTINCT t1.offid,t1.accept,t1.confirm,t2.class,t2.round,t2.sid1,t2.sid2,t2.id,t2.gamenum FROM $contracts AS t1, $brackets AS t2 WHERE t1.gameid=t2.id AND t1.post='y' AND t1.accept='y' AND t1.confirm='n' AND t2.round='$rounds[$i]' ";
      $sql="SELECT DISTINCT t1.offid,t1.accept,t1.confirm,t2.class,t2.round,t3.sid,t3.oppid,t2.id,t2.gamenum FROM $contracts AS t1,$brackets AS t2,$db_name.fbsched AS t3 WHERE t1.gameid=t2.id AND t2.class=t3.class AND t2.roundnum=t3.round AND t2.gamenum=t3.gamenum AND t1.post='y' AND t1.accept='y' AND t1.confirm='n' AND t2.round='$rounds[$i]' ";
      if($classch && !ereg("All",$classch))
      {
	 $sql.="AND t2.class='$classch' ";
      }
      $sql.="ORDER BY t2.class,t2.gamenum";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
	 echo "<a class=small target=new href=\"";
	 echo "fbcontract.php";
	 echo "?session=$session&givenoffid=$row[offid]&gameid=$row[id]\">$row[round] $row[class] #$row[gamenum], ".GetSchoolName($row[sid],'fb',$thisyr)." VS ".GetSchoolName($row[oppid],'fb',$thisyr).": ".GetOffName($row[offid])."</a><br>";
      }
   }
}
echo "</td></tr>";
echo "</table>";
echo "</form>";
echo $end_html;
?>
