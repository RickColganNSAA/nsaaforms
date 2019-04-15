<?php
$sport='bbb';
$sport2="bbg";

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if($level==3)	//observer
{
   header("Location:bbbassignreport2.php?session=$session");
   exit();
}
if($level==4) $level=1;

if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php?error=1");
   exit();
}

if($testing==1)
{
   //BOYS
   $disttimes="nsaaofficials20132014.".$sport."disttimes";      //TESTING
   $contracts="nsaaofficials20132014.".$sport."contracts";
   $districts="nsaaofficials20132014.".$sport."districts";
   //GIRLS
   $disttimes2="nsaaofficials20132014.".$sport2."disttimes";         //TESTING
   $contracts2="nsaaofficials20132014.".$sport2."contracts";
   $districts2="nsaaofficials20132014.".$sport2."districts";
}
else
{
   //BOYS
   $disttimes=$sport."disttimes";
   $contracts=$sport."contracts";
   $districts=$sport."districts";
   //GIRLS
   $disttimes2=$sport2."disttimes";
   $contracts2=$sport2."contracts";
   $districts2=$sport2."districts";
}

echo $init_html;
if($print!=1)
   echo GetHeader($session,"contractadmin");
else
   echo "<table width=100%><tr align=center><td>";
echo "<br>";

if($database && $database!='')
{
   $sql="USE $database";
   $result=mysql_query($sql);
   $years=preg_replace("/[^0-9]/","",$database);
   $years=substr($years,0,4)."-".substr($years,4,4);
}

if($print!=1)
{
   echo "<form method=post action=\"bbbassignreport.php\">";
   echo "<input type=hidden name=session value=$session>";
}

$sportname="Basketball";
if($print!=1)
{
   echo "<p><a href=\"contractadmin.php?sportch1=$sport&session=$session\">&larr; Return to $sportname Contracts & Assignments</a></p>";
}

$gender='Boys';
echo "<h2>$years Boys AND Girls $sportname Assignments (ABC Order by Official):";
echo "</h2><table><caption>";

//FIRST SHOW ANY GAMES THAT HAVE NO ONE ASSIGNED YET
        //BOYS
$sql="SELECT t1.* FROM $disttimes AS t1 LEFT JOIN $contracts AS t2 ON t1.id=t2.disttimesid WHERE t2.disttimesid IS NULL ORDER BY day,gamenum";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT * FROM $districts WHERE id='$row[distid]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if($row2[type]!="State")
   {
      $date=explode("-",$row[day]);
      echo "<p><b><u>NO ASSIGNED OFFICIALS FOR:</b></u> BOYS $row2[type] $row2[class]-$row2[district] on $date[1]/$date[2] at $row[time]</p>";
   }
}
        //GIRLS
$sql="SELECT t1.* FROM $disttimes2 AS t1 LEFT JOIN $contracts2 AS t2 ON t1.id=t2.disttimesid WHERE t2.disttimesid IS NULL ORDER BY day,gamenum";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT * FROM $districts WHERE id='$row[distid]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if($row2[type]!="State" && $row[day]!="0000-00-00")
   {
      $date=explode("-",$row[day]);
      echo "<p><b><u>NO ASSIGNED OFFICIALS FOR:</b></u> GIRLS $row2[type] $row2[class]-$row2[district] on $date[1]/$date[2] at $row[time]</p>";
   }
}

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
   echo "<label class='option'><a target=new href=\"bbbassignreport.php?database=$database&session=$session&sport=$sport&type=$type&print=1\">PRINT this Report</a></label><div style=\"clear:both;\"></div>";
}
echo "</caption>";

$sql="SELECT DISTINCT t1.offid,t2.first,t2.last FROM $contracts AS t1, officials AS t2 WHERE t1.offid=t2.id ORDER BY t2.last,t2.first";
$result=mysql_query($sql);
$offs[offid]=array();
$offs[last]=array();
$offs[first]=array();
$o=0;
while($row=mysql_fetch_array($result))
{
   $offs[offid][$o]=$row[offid];
   $offs[first][$o]=$row[first];
   $offs[last][$o]=$row[last];
   $o++;
}
$sql="SELECT DISTINCT t1.offid,t2.first,t2.last FROM $contracts2 AS t1, officials AS t2 WHERE t1.offid=t2.id ORDER BY t2.last,t2.first";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $found=0;
   for($i=0;$i<count($offs[offid]);$i++)
   {
      if($offs[offid][$i]==$row[offid]) $found=1;
   }
   if($found==0)
   {
      $offs[offid][$o]=$row[offid];
      $offs[first][$o]=$row[first];
      $offs[last][$o]=$row[last];
      $o++;
   }
}
//NOW WE HAVE AN ARRAY OF (UNIQUE) OFFICIALS
//Sort it by name:
array_multisort($offs[last],SORT_STRING,SORT_ASC,$offs[first],SORT_STRING,SORT_ASC,$offs[offid]);

//NOW THEY ARE SORTED. SHOW THEIR ASSIGNMENTS:
$ix=0;
$total=$o;
if($total%2==1) $total++;
for($o=0;$o<count($offs[offid]);$o++)
{
   if($ix==0)
   {
      echo "<tr align=left valign=top><td width='50%'><table cellspacing=0 cellpadding=3>";
   }
   else if($ix==(($total/2)))
   {
      echo "</table></td>";
      echo "<td><table>";
   }
   echo "<tr align=left><td colspan=2><b><u>".$offs[last][$o].", ".$offs[first][$o]."</u></td></tr>";
   echo "<tr align=left><td><table>";
	//BOYS ASSIGNMENTS FIRST
   $sql2="SELECT DISTINCT t2.id,t1.post,t1.accept,t1.confirm,t2.class,t2.district,t2.hostschool,t2.site,t2.type FROM $contracts AS t1,$districts AS t2,$disttimes AS t3 WHERE t3.distid=t2.id AND t3.id=t1.disttimesid AND t1.offid='".$offs[offid][$o]."' AND t2.type!='State' ORDER BY t2.class,t2.district";
   $result2=mysql_query($sql2);
   echo "<tr align=center>";
   echo "<td align=left colspan=2><b>District, Date/Time</b></td>";
   echo "<td><b>Posted</b></td><td><b>Accept</b></td><td><b>NSAA</b></td></tr>";
   //echO $sql2;
   while($row2=mysql_fetch_array($result2))
   {
      echo "<tr valign=top align=center>";
      if($row2[post]=='y') $post="X";
      else $post="&nbsp;";
      if($row2[accept]=='y') $accept="Yes";
      else if($row2[accept]=='n') $accept="No";
      else $accept="?";
      if($row2[confirm]=='y') $confirm="Confirm";
      else if($row2[confirm]=='n') $confirm="Reject";
      else $confirm="?";
         echo "<td align=left>$row2[type] $row2[class]-$row2[district]</td>";
         echo "<td align=left>";
         $sql3="SELECT t1.gender,t1.day,t1.time FROM $disttimes AS t1, $contracts AS t2 WHERE t1.id=t2.disttimesid AND t1.distid='$row2[id]' AND t2.offid='".$offs[offid][$o]."' ORDER BY t1.gender DESC,t1.day,t1.time";
         $result3=mysql_query($sql3);
         //echo mysql_error();
         while($row3=mysql_fetch_array($result3))
         {
            $date=split("-",$row3[day]); $day=date("M d",mktime(0,0,0,$date[1],$date[2],$date[0]));
	    echo "Boys, $day: $row3[time]<br>";
         }
         echo "</td>";
      echo "<td>$post</td><td>$accept</td><td>$confirm</td></tr>";
   }	//END FOR EACH BOYS ASSIGNMENT
        //GIRLS ASSIGNMENTS NEXT
   $sql2="SELECT DISTINCT t2.id,t1.post,t1.accept,t1.confirm,t2.class,t2.district,t2.hostschool,t2.site,t2.type,t2.gender FROM $contracts2 AS t1,$districts2 AS t2,$disttimes2 AS t3 WHERE t3.distid=t2.id AND t3.id=t1.disttimesid AND t1.offid='".$offs[offid][$o]."' AND t2.type!='State' ORDER BY t2.class,t2.district,t2.gender";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      if($row2[confirm]=='y' || $level!=3)
      {
         echo "<tr valign=top align=center>";
         if($row2[post]=='y') $post="X";
         else $post="&nbsp;";
         if($row2[accept]=='y') $accept="Yes";
         else if($row2[accept]=='n') $accept="No";
         else $accept="?";
         if($row2[confirm]=='y') $confirm="Confirm";
         else if($row2[confirm]=='n') $confirm="Reject";
         else $confirm="?";
         echo "<td align=left>$row2[class]-$row2[district]</td>";
         echo "<td align=left>";
         $sql3="SELECT t1.day,t1.time FROM $disttimes2 AS t1, $contracts2 AS t2 WHERE t1.id=t2.disttimesid AND t1.distid='$row2[id]' AND t2.offid='".$offs[offid][$o]."' ORDER BY t1.day,t1.time";
         $result3=mysql_query($sql3);
echo mysql_error();
         while($row3=mysql_fetch_array($result3))
         {
            $date=split("-",$row3[day]); $day=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
            echo "Girls, $day: ".trim($row3[time])."<br>";
         }
         echo "</td>";
         if($level!=3)
            echo "<td>$post</td><td>$accept</td><td>$confirm</td>";
         echo "</tr>";
      }//end if confirmed or user is not an observer
   } //END FOR EACH GIRLS ASSIGNMENT
   echo "</table></td></tr>";
   if($ix==($total-1))
   {
      echo "</table></td></tr>";
   }
   $ix++;
}
echo "</table></td></tr>";

echo "</table>";
echo "<br>";
echo $end_html;

if($database && $database!='')
{
   $sql="USE $db_name2";
   $result=mysql_query($sql);
}

?>
