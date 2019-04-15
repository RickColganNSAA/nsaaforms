<?php
//For BB Observers to see

$sport='bbg';

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

$disttimes=$sport."disttimes";
$contracts=$sport."contracts";
$districts=$sport."districts";

echo $init_html;
if($print!=1)
   echo GetHeader($session);
else
   echo "<table width=100%><tr align=center><td>";
echo "<br>";

if($print!=1)
{
   echo "<form method=post action=\"bbgassignreport2.php\">";
   echo "<input type=hidden name=session value=$session>";
}

if($sport && $sport!="")
{
if($type=="abc" && $print!=1)
{
   echo "<a class=small href=\"bbgassignreport2.php?session=$session&sport=$sport\">View District Assignments in Class/District Order</a>&nbsp;&nbsp;";
   echo "<a class=small href=\"bbbassignreport2.php?session=$session&sport=$sport\">View BOYS District Assignments</a>";
   //echo "<a class=small href=\"bbgassignreport2.php?session=$session&sport=$sport&type=state\">View STATE Assignments</a><br>";
   echo "<br>";
}
else if($type=='state' && $print!=1)
{
   echo "<a class=small href=\"bbgassignreport2.php?session=$session&sport=$sport\">View DISTRICT Assignments</a>&nbsp;&nbsp;";
}
elseif($print!=1)
{
   echo "<a class=small href=\"bbgassignreport2.php?session=$session&sport=$sport&type=abc\">View District Assignments in ABC Order</a>&nbsp;&nbsp;";
   echo "<a class=small href=\"bbbassignreport2.php?session=$session&sport=$sport\">View BOYS District Assignments</a>";
   //echo "<a class=small href=\"bbgassignreport2.php?session=$session&sport=$sport&type=state\">View STATE Assignments</a><br>";
   echo "<br>";
}
if($print!=1)
   echo "<a class=small target=new href=\"bbgassignreport2.php?session=$session&sport=$sport&type=$type&print=1\">Printer-Friendly Version</a>";
echo "<br><br>";

$sportname=GetSportName($sport);
$gender='Girls';
echo "<table><caption><b>$sportname Assignments (";
if($type=="abc")
   echo "ABC Order by Official";
else if($type=='state')
   echo "State";
else
   echo "Class/District Order";
echo "):";
echo "</b><br>";
echo "<hr></caption>";

if($type=="abc")
{
$sql="SELECT DISTINCT t1.offid,t2.first,t2.last FROM $contracts AS t1, officials AS t2 WHERE t1.offid=t2.id ORDER BY t2.last,t2.first";
$result=mysql_query($sql);
$ix=0;
$total=mysql_num_rows($result);
if($total%3==1) $total+=2;
else if($total%3==2) $total++;
echo "<tr align=left valign=top><td><table>";
$percolumn=$total/3;
while($row=mysql_fetch_array($result))
{
   if($ix%$percolumn==0)
   {
      echo "</table></td>";
      echo "<td><table>";
   }
   echo "<tr align=left><td colspan=2><b><u>$row[last], $row[first]</u></td></tr>";
   echo "<tr align=left><td><table>";
   $sql2="SELECT DISTINCT t2.id,t2.class,t2.district,t2.hostschool,t2.site,t2.type FROM $contracts AS t1,$districts AS t2,$disttimes AS t3 WHERE t3.distid=t2.id AND t3.id=t1.disttimesid AND t1.offid='$row[offid]' AND t2.type!='State' AND t1.post='y' AND t1.accept='y' AND t1.confirm='y' ORDER BY t2.class,t2.district,t2.type DESC";
   $result2=mysql_query($sql2);
   echo "<tr align=center>";
   echo "<th class=small align=left colspan=2>District, Date/Time</th></tr>";
   //echO $sql2;
   while($row2=mysql_fetch_array($result2))
   {
      echo "<tr valign=top align=center>";
         echo "<td align=left>$row2[type] $row2[class]-$row2[district]</td>";
         echo "<td align=left>";
         $sql3="SELECT t1.day,t1.time FROM $disttimes AS t1, $contracts AS t2 WHERE t1.id=t2.disttimesid AND t1.distid='$row2[id]' AND t2.offid='$row[offid]' ORDER BY t1.day,t1.time";
         $result3=mysql_query($sql3);
         //echo mysql_error();
         while($row3=mysql_fetch_array($result3))
         {
            $date=split("-",$row3[day]); $day=date("M d",mktime(0,0,0,$date[1],$date[2],$date[0]));
	    echo "$day";
	    if($row3[time]!='' && $row3[time]!=': PM CST') echo ": $row3[time]";
	    echo "<br>";
         }
         echo "</td>";
      echo "</tr>";
   }
   echo "</table></td></tr>";
   $ix++;
}
echo "</table></td></tr>";
echo "</table></td></tr>";
}//end if abc
else if($type!='state')	//class-dist order
{
   //get total # contracts
   $sql="SELECT * FROM $contracts WHERE post='y' AND accept='y' AND confirm='y'";
   $result=mysql_query($sql);
   $total=mysql_num_rows($result);
   $ct=0;
   $sql="SELECT DISTINCT id,class,district,hostschool,type FROM $districts WHERE type!='State' ORDER BY class,district";
   $result=mysql_query($sql);
   echo "<tr align=left valign=top><td><table>";
   while($row=mysql_fetch_array($result))
   {
      $class=$row[1]; $district=$row[2];
      echo "<tr align=left><td><b><u>$row[type] ";
      if($row[type]!='State') 
      {
	 if($row[hostschool]!='') echo "$class-$district: (host: $row[hostschool])";
         else echo "$class-$district:";
      }
      echo "</u></b></td></tr>";
      $sql2="SELECT DISTINCT day FROM $disttimes WHERE distid='$row[id]' ORDER BY day";
      $result2=mysql_query($sql2); 
      while($row2=mysql_fetch_array($result2))
      {
         $date=split("-",$row2[day]); $curday=date("M d",mktime(0,0,0,$date[1],$date[2],$date[0]));
         echo "<tr align=left><td>";
         echo "<table>";
         $sql3="SELECT DISTINCT t1.offid,t2.time FROM $contracts AS t1, $disttimes AS t2 WHERE t1.disttimesid=t2.id AND t2.distid='$row[id]' AND t2.day='$row2[day]' AND t1.post='y' AND t1.accept='y' AND t1.confirm='y' ORDER BY t2.time";
         $result3=mysql_query($sql3);
         if(mysql_num_rows($result3)>0)
         {
            echo "<tr align=center>";
            if($row[type]!='State') echo "<th class=small align=left>$curday</th>";
            echo "<th class=small align=left>Official</th>";
            echo "</tr>";
         }
         //echo $sql3;
         while($row3=mysql_fetch_array($result3))
         {
            echo "<tr align=center>";
            if($row3[post]=='y') $post="X";
            else $post="&nbsp;";
            if($row3[accept]=='y') $accept="Yes";
            else if($row3[accept]=='n') $accept="No";
            else $accept="?";
            if($row3[confirm]=='y') $confirm="Confirm";
            else if($row3[confirm]=='n') $confirm="Reject";
            else $confirm="?";
            if($row[type]!='State' && $row3[time]!=': PM CST') echo "<td align=left>$row3[time]</td>";
	    else if($row[type]!='State') echo "<td>&nbsp;</td>";
            echo "<td align=left>".GetOffName($row3[offid])."</td>";
            echo "</tr>";
            $ct++;
         }
         echo "</table></td></tr>";
      }
      if($ct>=$total/3)
      {
         $ct=0;
         echo "</table></td><td><table>";
      }
   }//end for each class/district
   echo "</table></td></tr>";
}//end if not abc
else if($type=='state')
{
   if($gender=='Boys')
      $nights=array("March 8","March 9","March 10","March 11");
   else if($gender=='Girls')
      $nights=array("March 1","March 2","March 3","March 4");
   echo "<tr align=center><td><table border=1 bordercolor=#000000  cellspacing=1 cellpadding=2>";
   //show summary of state assignemnts: Official, Posted, Accept, NSAA Response,
   //Lodging information
   $sql="SELECT t1.first,t1.middle,t1.last,t2.*,t3.time FROM officials AS t1, $contracts AS t2, $disttimes AS t3,$districts AS t4 WHERE t1.id=t2.offid AND t2.disttimesid=t3.id AND t3.distid=t4.id AND t4.type='State'";
   $sql.="AND t2.accept='y' AND t2.confirm='y' ";
   if(!$sort) $sql.="ORDER BY t1.last, t1.first, t1.middle";
   else if($sort=='standby') $sql.="ORDER BY t3.time DESC,t1.last,t1.first,t1.middle";
   else $sql.="ORDER BY t2.$sort DESC,t1.last,t1.first,t1.middle";
   $result=mysql_query($sql);
   echo "<tr align=center><td align=left><a class=small href=\"bbgassignreport2.php?sport=$sport&sessin=$session&type=state\">Official</a></td>";
   echo "<td><a class=small href=\"bbgassignreport2.php?sport=$sport&session=$session&type=state&sort=standby\">Stand-By</a></td>";
   echo "</tr>";
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=center>";
      echo "<td align=left>$row[first] $row[middle] $row[last]</td><td>";
      if($row[time]=='standby') echo "<b>X</b>";
      else echo "&nbsp;";
      echo "</td></tr>";
   }
   echo "</table></td></tr>";
}
echo "</table>";
echo "<br>";
if($type=="abc" && $print!=1)
{
   echo "<a class=small href=\"bbgassignreport2.php?session=$session&sport=$sport\">View District Assignments in Class/District Order</a>&nbsp;&nbsp;";
   echo "<a class=small href=\"bbbassignreport2.php?session=$session&sport=$sport\">View BOYS District Assignments</a>&nbsp;&nbsp;";
   //echo "<a class=small href=\"bbgassignreport2.php?session=$session&sport=$sport&type=state\">View STATE Assignments</a>&nbsp;&nbsp;";
}
else if($type=='state' && $print!=1)
{
   echo "<a class=small href=\"bbgassignreport2.php?session=$session&sport=$sport\">View District Assignments</a>&nbsp;&nbsp;";
   echo "<a class=small href=\"bbbassignreport2.php?session=$session&sport=$sport\">View BOYS District Assignments</a>&nbsp;&nbsp;";
}
elseif($print!=1)
{
   echo "<a class=small href=\"bbgassignreport2.php?session=$session&sport=$sport&type=abc\">View District Assignments in ABC Order</a>&nbsp;&nbsp;";
   echo "<a class=small href=\"bbbassignreport2.php?session=$session&sport=$sport\">View BOYS District Assignments</a>&nbsp;&nbsp;";
   //echo "<a class=small href=\"bbgassignreport2.php?session=$session&sport=$sport&type=state\">View STATE Assignments</a>&nbsp;&nbsp;";
}
if($print!=1)
   echo "<a class=small href=\"welcome.php?session=$session\">Home</a>";

}//end if sport!=""
echo $end_html;

?>
