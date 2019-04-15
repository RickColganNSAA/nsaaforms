<?php
$sport='fb';

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

//Excel Export for District Assignments
$disttimes=$sport."brackets";
$contracts=$sport."contracts";

echo $init_html;
if($print!=1)
   echo GetHeader($session,"contractadmin");
else
   echo "<table width=100%><tr align=center><td>";
echo "<br>";

if($print!=1)
{
   echo "<form method=post action=\"fbassignreport.php\">";
   echo "<input type=hidden name=session value=$session>";
}

if($sport && $sport!="")
{
if($print!=1)
{
   echo "<p><a href=\"contractadmin.php?session=$session&sportch1=$sport\">&larr; Return to Football Contracts & Assignments</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
   if($type=="abc")
      echo "<a href=\"fbassignreport.php?session=$session&sport=$sport\">View Assignments in Class/District Order</a>";
   else
      echo "<a href=\"fbassignreport.php?session=$session&sport=$sport&type=abc\">View Assignments in ABC Order</a>";
   //echo "&nbsp;&nbsp;<a class=small target=new href=\"fbassignreport.php?session=$session&sport=$sport&type=$type&print=1\">Printer-Friendly Version</a>";
   //echo "&nbsp;&nbsp;<a class=small href=\"assignfb.php?session=$session&sport=$sport\">Assign Football Officials</a>";
   echo "</p>";
}

$sportname=GetSportName($sport);
echo "<h2>$sportname Assignments (";
if($type=="abc")
   echo "ABC Order by Official";
else
   echo "Class/District Order";
echo "):</h2><table><caption>";
if($print!=1)   //QUICK OPTIONS
{
?>
<style>
label.option {
background-color:yellow;
padding:5px;
margin:2px;
border: #a0a0a0 1px dotted;
float:left;
}
label.option a {
font-size:12px;
font-weight:normal;
}
</style>
<?php
   echo "<label class='option'><a target=new href=\"".$sport."assignexport.php?session=$session\">Playoff Officials MAILING EXPORT</a></label>";
   echo "<label class='option'><a target=\"_blank\" href=\"".$sport."assignreport.php?session=$session&sport=$sport&type=$type&print=1\">PRINT this report</a></label><div style=\"clear:both;\"></div>";
   echo "<label class='option'><a href=\"assignfb.php?session=$session\">ASSIGN Football Officials</a></label>";
   echo "<label class='option'><a href=\"assignpost.php?return=".$sport."assignreport&session=$session&sport=$sport&type=$type\">POST All Contracts</a></label>";
   echo "<label class='option'><a href=\"".$sport."contracts.php?session=$session\">View CONTRACTS</a></label><div style=\"clear:both;\"></div>";
} //END IF NOT PRINT
if($posted=="yes")
   echo "<br><font style=\"font-size:8pt;color:blue\"><b>These assignments have been posted to the officials' logins.</b></font>";
echo "<hr></caption>";

if($type=="abc")
{
$sql="SELECT DISTINCT t1.offid,t2.first,t2.last FROM $contracts AS t1, officials AS t2 WHERE t1.offid=t2.id ORDER BY t2.last,t2.first";
$result=mysql_query($sql);
$ix=0;
$total=mysql_num_rows($result);
if($total%2==1) $total++;
while($row=mysql_fetch_array($result))
{
   if($ix==0)
   {
      echo "<tr align=left valign=top><td><table>";
   }
   else if($ix==(($total/2)-1))
   {
      echo "</table></td>";
      echo "<td><table>";
   }
   echo "<tr align=left><td colspan=2><b><u>$row[last], $row[first]</u></td></tr>";
   $types=array("First Round","Second Round","Quarterfinals","Semifinals","Final");
   for($t=0;$t<count($types);$t++)
   {
   $sql2="SELECT DISTINCT t1.post,t1.accept,t1.confirm,t2.class,t2.round,t2.gamenum,t2.school1,t2.school2,t2.hostschool FROM $contracts AS t1,$disttimes AS t2 WHERE t1.gameid=t2.id AND t2.round='$types[$t]' AND t1.offid='$row[offid]' ORDER BY t2.class,t2.gamenum";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0)
      echo "<tr align=left><td>".strtoupper($types[$t]).":</td><td>Post</td><td>Accept</td><td>Confirm</td></tr>";
   while($row2=mysql_fetch_array($result2))
   {
      echo "<tr valign=top align=left>";
      if($row2[hostschool]==$row2[school1]) 
      {
	 $home=$row2[school1]; $away=$row2[school2];
      }
      else if($row2[hostschool]==$row2[school2])
      {
	 $home=$row2[school2]; $away=$row2[school1];
      }
      echo "<td>Class $row2[class] Game #$row2[gamenum]: $away @ $home</td>";
      if($row2[post]=='y') echo "<td>YES</td>";
      else echo "<td>NO</td>";
      if($row2[accept]=='y') echo "<td>YES</td>";
      else if($row2[accept]=='n') echo "<td>NO</td>";
      else echo "<td>???</td>";
      if($row2[confirm]=='y') echo "<td>YES</td>"; 
      else if($row2[confirm]=='n') echo "<td>NO</td>";
      else echo "<td>???</td>";
   }
   }//end for each type
   if($ix==($total-1))
   {
      echo "</table></td></tr>";
   }
   $ix++;
}
}//end if abc
else	//class-dist order
{
   //get total # contracts
   $sql="SELECT DISTINCT class FROM $disttimes ORDER BY class";
   $result=mysql_query($sql);
   $total=mysql_num_rows($result);
   $percol=$total/2;
   $curcol=0;
   echo "<tr align=left valign=top><td><table>";
   while($row=mysql_fetch_array($result))
   {
      $class=$row[0];
      echo "<tr align=left><td colspan=2><u>Class $class</u></b></td></tr>";
      if($class=='A' || $class=='B')
      {
	 $rounds=array("First Round","Quarterfinals","Semifinals","Finals");
      }
      else
      {
	 $rounds=array("First Round","Second Round","Quarterfinals","Semifinals","Finals");
      }
      for($i=0;$i<count($rounds);$i++)
      {
	 echo "<tr align=left><td colspan=2><b>$rounds[$i]:</b></td>";
	 echo "<td>Post</td><td>Accept</td><td>Confirm</td></tr>";
	 $sql2="SELECT t1.offid,t1.post,t1.accept,t1.confirm,t2.gamenum,t2.school1,t2.school2,t2.hostschool FROM $contracts AS t1, $disttimes AS t2 WHERE t1.gameid=t2.id AND t2.round='$rounds[$i]' AND t2.class='$class' ORDER BY t2.gamenum";
	 $result2=mysql_query($sql2);
	 while($row2=mysql_fetch_array($result2))
	 {
	    if($row2[hostschool]==$row2[school1])
	    {
	       $away=$row2[school2]; $home=$row2[school1];
	    }
	    else if($row2[hostschool]==$row2[school2])
	    {
	       $away=$row2[school1]; $home=$row2[school2];
	    }
	    echo "<tr align=left><td>#$row2[gamenum]: $away @ $home</td>";
	    echo "<td>".GetOffName($row2[offid])."</td>";
	    if($row2[post]=='y') echo "<td>YES</td>";
	    else echo "<td>NO</td>"; 
	    if($row2[accept]=='y') echo "<td>YES</td>";
	    else if($row2[accept]=='n') echo "<td>NO</td>";
	    else echo "<td>???</td>";
	    if($row2[confirm]=='y') echo "<td>YES</td>";
	    else if($row2[confirm]=='n') echo "<td>NO</td>";
	    else echo "<td>???</td>";
	    echo "</tr>";
	 }
      }
      $curcol++;
      if($curcol>=$percol)
      {
	 $curcol=0;
	 echo "</table></td><td><table>";
      }
   }
   echo "</table></td></tr>";
}//end if not abc
echo "</table>";
echo "<br>";
if($type=="abc" && $print!=1)
   echo "<a class=small href=\"fbassignreport.php?session=$session&sport=$sport\">View Assignments in Class/District Order</a>&nbsp;&nbsp;&nbsp;";
elseif($print!=1)
   echo "<a class=small href=\"fbassignreport.php?session=$session&sport=$sport&type=abc\">View Assignments in ABC Order</a>&nbsp;&nbsp;&nbsp;";
}//end if sport!=""
echo $end_html;

?>
