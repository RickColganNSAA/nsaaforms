<?php
require 'functions.php';
require 'variables.php';

$origsport=$sport;
if(ereg("state",$sport)) $curtype="State";
else $curtype="District";
if(ereg("sp",$sport)) $sport='sp';
else $sport='pp';
if($sport=='sp' AND $curtype=="State")
{
   header("Location:spstatejudges.php?session=$session");
   exit();
}

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}

if(ereg("state",$origsport)) $curtype="State";
else $curtype="District";
if(ereg("sp",$origsport)) $sport='sp';
else $sport='pp';
$contracts=$sport."contracts";
$disttimes=$sport."districts";
$sportname=GetSportName($sport);
echo $init_html;
if($print!=1)
   echo GetHeaderJ($session,"jcontractadmin");
else
   echo "<table width=100%><tr align=center><td>";
echo "<br>";

if($print!=1)
{
   echo "<form method=post action=\"assignreportplay.php\">";
   echo "<input type=hidden name=session value=$session>";
   echo "<input type=hidden name=sport value=$sport>";

   if($sport=='sp') 
      echo "<a href=\"spstatejudges.php?session=$session\">View State Speech Assignments</a><br>";
   echo "<a class=small href=\"assignplay2.php?session=$session&sport=$origsport\">Assign $sportname Judges</a>&nbsp;&nbsp;";
   echo "<a class=small href=\"playcontracts.php?session=$session&sport=$origsport\">Submitted $sportname Contracts</a><br><br>";
}

echo "<table><caption><b>$sportname Assignments (";
if($type=="abc")
   echo "ABC Order by Judge";
else if($curtype=="State")
   echo "State";
else
   echo "Class/District Order";
echo "):</b><br>";
if($type=="abc" && $print!=1 && $curtype!="State")
   echo "<a class=small href=\"assignreportplay.php?session=$session&sport=$sport\">View Assignments in Class/District Order</a>";
else if($print!=1 && $curtype=="State")
   echo "<a class=small href=\"assignreportplay.php?session=$session&sport=$sport\">District Assignments</a>";
else if($print!=1)
   echo "<a class=small href=\"assignreportplay.php?session=$session&type=abc&sport=$sport\">View Assignments in ABC Order</a>";
if($curtype!="State")
   echo "&nbsp;&nbsp;<a class=small href=\"assignreportplay.php?session=$session&sport=$sport-state\">State Assignments</a>";
if($print!=1)
   echo "&nbsp;&nbsp;<a class=small target=new href=\"assignreportplay.php?session=$session&sport=$sport&type=$type&print=1\">Printer-Friendly Version</a>&nbsp;&nbsp;";

if($print!=1)
   echo "<br><a class=small href=\"assignpost.php?session=$session&sport=$sport&all=1\">Post ALL Contracts</a>";
if($posted=="yes")
   echo "<br><font style=\"font-size:8pt;color:blue\"><b>These assignments have been posted to the judges' logins.</b></font>";
echo "<hr>";
if($sport=='sp')
{
   echo "<div class='alert' style=\"text-align:center;\" id=\"exportdiv\"></div>";
}
echo "</caption>";

if($type=="abc")
{
$sql="SELECT DISTINCT t1.offid,t2.first,t2.middle,t2.last FROM $contracts AS t1, judges AS t2 WHERE t1.offid=t2.id ORDER BY t2.last,t2.first";
$result=mysql_query($sql);
$ix=0;
$total=mysql_num_rows($result);
if($total%2==1) $total++;
echo "<tr align=left valign=top><td><table>";
$csv="";
while($row=mysql_fetch_array($result))
{
   //for each judge...
   if($ix==(($total/2)-2))
   {
      echo "</table></td>";
      echo "<td><table>";
   }
   echo "<tr align=left><td colspan=4><br><b><u>$row[last], $row[first] $row[middle]</u></td></tr>";
   $sql2="SELECT DISTINCT t1.distid,t2.type,t2.class,t2.district,t1.confirm,t1.accept,t1.post";
   if($sport=='sp') $sql2.=",t1.mileage";
   $sql2.=",t3.first,t3.last FROM $contracts AS t1,$disttimes AS t2,judges AS t3 WHERE t1.distid=t2.id AND t1.offid=t3.id AND t1.offid='$row[offid]' AND t2.type!='State' ORDER BY t2.type,t2.class,t2.district,t3.last,t3.first";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)>0)
   {
      echo "<tr align=center><td>&nbsp;</td><th class=small>Posted</th><th class=small>Accept</th><th class=small>NSAA-Confirm</th>";
      if($sport=='sp') echo "<th class=small>Mileage</th>";
      echo "</tr>";
   }
   while($row2=mysql_fetch_array($result2))
   {
      //find what they're assigned to...
      echo "<tr valign=top align=center>";
      echo "<td align=left>$row2[type]";
      if($row2[type]!="State") echo " $row2[class]-$row2[district]";
      else if($sport=='pp') echo " Class $row2[class]";
      else if($sport=='sp') echo "-".date("l",strtotime($row2[dates]));
      echo "</td>";
      echo "<td>";
      if($row2[post]=='y') echo "X";
      else echo "&nbsp;";
      echo "</td><td>";
      if($row2[accept]=='y') echo "Y";
      else if($row2[accept]=='n') echo "N";
      else echo "?";
      echo "</td><td>";
      if($row2[confirm]=='y') echo "Y";
      else if($row2[confirm]=='n') echo "N";
      else echo "?"; 
      echo "</td>";
      if($sport=='sp') echo "<td>$row2[mileage]</td>";
      if($row2[mileage]=="Yes") $csv.="\"$row2[class]-$row2[district]\",\"$row2[first]\",\"$row2[last]\"\r\n";
      echo "</tr>";
   }
   $ix++;
}
   if($sport=='sp' && $csv!='')
   {
      $filename="spdistrictmileage.csv";
      if(!$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w")) echo "COULD NOT OPEN $filename";
      if(!fwrite($open,$csv)) echo "COULD NOT WRITE TO $filename $csv";
      fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
?>
<script language="javascript">
document.getElementById('exportdiv').style.display='';
document.getElementById('exportdiv').innerHTML="<a class=small href='reports.php?session=<?php echo $session; ?>&filename=<?php echo $filename; ?>'>Export Judges Needing Mileage</a>";
</script>
<?php
   } //END IF SPEECH
echo "</table></td></tr>";
}//end if abc
else if($curtype!="State")  //class-dist order
{
   $x=0;
   $sql="SELECT id,type,class,district FROM $disttimes WHERE type!='State' ORDER BY type,class,district";
   $result=mysql_query($sql);
   $total=mysql_num_rows($result);
   if($total%2==1) $total++;
   echo "<tr align=left valign=top><td><table>";
   $csv="";
   while($row=mysql_fetch_array($result))
   {
      if($x==($total/2))
      {
         echo "</table></td>";
         echo "<td><table>";
      }
      echo "<tr align=left><td colspan=4><br><b><u>$row[type]";
      if($row[type]!="State") echo " $row[class]-$row[district]";
      else if($sport=='pp') echo " Class $row[class]";
      else if($sport=='sp') echo "-".date("l",strtotime($row[dates]));
      echo "</u></td></tr>";
      $sql2="SELECT t1.*,t2.first,t2.last FROM $contracts AS t1,judges AS t2 WHERE t1.offid=t2.id AND t1.distid='$row[id]' ORDER BY t2.last,t2.first";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0)
      {
         echo "<tr align=center><td>&nbsp;</td><th class=small>Posted</th><th class=small>Accept</th><th class=small>NSAA-Confirm</th>";
         if($sport=='sp') echo "<th class=small>Mileage</th>";
	 echo "</tr>";
      }
      while($row2=mysql_fetch_array($result2))
      {
         if($row2[mileage]=="Yes") $csv.="\"$row[class]-$row[district]\",\"$row2[first]\",\"$row2[last]\"\r\n";
         echo "<tr align=center><td align=left>".GetJudgeName($row2[offid])."</td>";
	 echo "<td>";
	 if($row2[post]=='y') echo "X";
	 else echo "&nbsp;";
         echo "</td><td>";
	 if($row2[accept]=='y') echo "Y";
	 else if($row2[accept]=='n') echo "N";
	 else echo "?";
	 echo "</td><td>";
	 if($row2[confirm]=='y') echo "Y";
	 else if($row2[confirm]=='n') echo "N";
	 else echo "?";
  	 echo "</td>";
	 if($sport=='sp') echo "<td>$row2[mileage]</td>";
	 echo "</tr>";
      }
      $x++;
   }
   echo "</table></td></tr>";
   if($sport=='sp' && $csv!='')
   {
      $filename="spdistrictmileage.csv";
      if(!$open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w")) echo "COULD NOT OPEN $filename";
      if(!fwrite($open,$csv)) echo "COULD NOT WRITE TO $filename $csv";
      fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
?>
<script language="javascript">
document.getElementById('exportdiv').style.display='';
document.getElementById('exportdiv').innerHTML="<a class=small href='reports.php?session=<?php echo $session; ?>&filename=<?php echo $filename; ?>'>Export Judges Needing Mileage</a>";
</script>
<?php
   } //END IF SPEECH
}//end if not abc OR state
else	//STATE
{
   echo "<tr align=center><td><table cellspacing=1 cellpadding=2 border=1 bordercolor=#000000>";
   echo "<tr align=center><td><b>Class</b></td><td><b>Judge</b></td><td><b>Posted</b></td><td><b>Accept</b></td><td><b>Lodging</b></td>";
   echo "<td><b>NSAA-Confirm</b></td></tr>";
   $sql="SELECT t1.class,t2.* FROM $disttimes AS t1,$contracts AS t2 WHERE t1.id=t2.distid AND t1.type='State' AND t2.offid!='0' ORDER BY t1.class";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=center><td>$row[class]</td>";
      echo "<td align=left>".GetJudgeName($row[offid])."</td>";
      if($row[post]=='y') echo "<td>X</td>";
      else echo "<td>NO</td>";
      if($row[accept]=='y') echo "<td>YES</td>";
      else if($row[accept]=='n') echo "<td>NO</td>";
      else echo "<td>???</td>";
      echo "<td>$row[lodging]</td>";
      if($row[confirm]=='y') echo "<td>YES</td>";
      else if($row[confirm]=='n') echo "<td>NO</td>";
      else echo "<td>???</td>";
      echo "</tr>";
   }
   echo "</table>";
   echo "</td></tr>";
}
echo "</table>";
echo "<br>";
if($type=="abc" && $print!=1)
   echo "<a class=small href=\"assignreportplay.php?sport=$sport&session=$session\">View Assignments in Class/District Order</a>&nbsp;&nbsp;&nbsp;";
elseif($print!=1 && $curtype!="State")
   echo "<a class=small href=\"assignreportplay.php?sport=$sport&session=$session&type=abc\">View Assignments in ABC Order</a>&nbsp;&nbsp;&nbsp;";
if($print!=1)
   echo "<a class=small href=\"jwelcome.php?session=$session\">Home</a>";

echo $end_html;

?>
