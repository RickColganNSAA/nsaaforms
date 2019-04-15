<?php
$sport='wr';

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
$disttimes=$sport."districts";
$contracts=$sport."contracts";

echo $init_html;
if($print!=1)
   echo GetHeader($session,"contractadmin");
else
   echo "<table width=100%><tr align=center><td>";
echo "<a name='top'><br></a>";

if($print!=1)
{
   echo "<form method=post action=\"wrassignreport.php\">";
   echo "<input type=hidden name=session value=$session>";
}

if($sport && $sport!="")
{
if($posted=="yes" && $print!=1)
{
   echo "<font style=\"color:red\"><b>All Wrestling Contracts have been posted to the assigned officials.</b></font><br><br>";
}
$sportname=GetSportName($sport);
if($print!=1)
{
   echo "<p><a href=\"contractadmin.php?session=$session&sportch1=$sport\">&larr; Return to $sportname Contracts & Assignments</a>";
   if($type=="abc")
      echo "&nbsp;|&nbsp;<a href=\"".$sport."assignreport.php?session=$session&sport=$sport\">View District Assignments in CLASS/DISTRICT Order</a>";
   else if($type=="state")
      echo "&nbsp;|&nbsp;<a href=\"".$sport."assignreport.php?session=$session&sport=$sport&type=statedual\">View STATE DUAL Assignments</a>";
   else if($type=="statedual")
      echo "&nbsp;|&nbsp;<a href=\"".$sport."assignreport.php?session=$session&sport=$sport&type=state\">View STATE Assignments</a>";
   else 
      echo "&nbsp;|&nbsp;<a href=\"".$sport."assignreport.php?session=$session&sport=$sport&type=abc\">View District Assignments in ABC Order</a>";
   echo "</p>";
}

echo "<h2>$years $sportname Assignments (";
if($type=="abc")
   echo "ABC Order by Official";
else if($type=='state')
   echo "State";
else if($type=="statedual")
   echo "State Dual";
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
   if($type=="state")
      echo "<label class='option'><a target=new href=\"".$sport."assignexport.php?session=$session&type=$type\">State Officials MAILING EXPORT</a></label>";
   else if($type=="statedual")
      echo "<label class='option'><a target=new href=\"".$sport."assignexport.php?session=$session&type=$type\">State Dual Officials MAILING EXPORT</a></label>";
   echo "<label class='option'><a target=\"_blank\" href=\"".$sport."assignreport.php?session=$session&sport=$sport&type=$type&print=1\">PRINT this report</a></label><div style=\"clear:both;\"></div>";
   echo "<label class='option'><a href=\"assign".$sport.".php?session=$session";
   if($type=='state') echo "&type=State";
   else if($type=='statedual') echo "&type=State Dual";
   else echo "&type=District";
   echo "\">ASSIGN $sportname Officials</a></label>";
   echo "<label class='option'><a href=\"assignpost.php?return=".$sport."assignreport&session=$session&sport=$sport&type=$type\">POST All ";
   if($type=='state') echo "STATE";
   else echo "DISTRICT";
   echo " Contracts</a></label>";
   echo "<label class='option'><a href=\"".$sport."contracts.php?session=$session";
   if($type=='state') echo "&distch=State";
   else if($type=='statedual') echo "&distch=State Dual";
   else echo "&distch=District";
   echo "\">View CONTRACTS</a></label><div style=\"clear:both;\"></div>";
   if(($type=='statedual' || $type=='state') && $confirmed!=1)
      echo "<p>[<a class=small href=\"".$sport."assignreport.php?session=$session&sport=$sport&type=$type&confirmed=1\">Only Show Accepted & Confirmed Contracts</a>]</p>";
   else if(($type=='statedual' || $type=='state') && $confirmed==1)
      echo "<p>[<a class=small href=\"".$sport."assignreport.php?session=$session&sport=$sport&type=$type\">Show ALL Contracts</a>]</p>";
} //END IF NOT PRINT
echo "</caption>";

if($type=='state' || $type=='statedual')
{
   if($type=="state") $typech="State";
   else $typech="State Dual";
   echo "<tr align=center><td><table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">";
   //show summary of state assignemnts: Official, Posted, Accept, NSAA Response, 
   //Child in Tournament, Lodging information
   $sql="SELECT t1.socsec,t1.city,t1.first,t1.middle,t1.last,t2.* FROM officials AS t1, $contracts AS t2, $disttimes AS t3 WHERE t1.id=t2.offid AND t2.distid=t3.id AND t3.type='$typech' ";
   if($confirmed==1) $sql.="AND t2.accept='y' AND t2.confirm='y' ";
   if(!$sort) $sql.="ORDER BY t1.last, t1.first, t1.middle";
   else if($sort=='child') $sql.="ORDER BY t2.accept DESC,t2.child DESC,t1.last,t1.first,t1.middle";
   else $sql.="ORDER BY t2.$sort DESC,t1.last,t1.first,t1.middle";
   $result=mysql_query($sql); 
   echo "<tr align=center><td rowspan=2 align=left><a class=small href=\"wrassignreport.php?sport=$sport&session=$session&type=$type&confirmed=$confirmed\">Official</a></td>";
   echo "<td rowspan=2><b>SSN</b></td><td rowspan=2><b>City</b></td>";
   echo "<td rowspan=2><a class=small href=\"wrassignreport.php?sport=$sport&session=$session&type=$type&confirmed=$confirmed&sort=post\">Posted</a></td>";
   echo "<td rowspan=2><a class=small href=\"wrassignreport.php?sport=$sport&session=$session&type=$type&confirmed=$confirmed&sort=accept\">Accept</a></td>";
   echo "<td rowspan=2><a class=small href=\"wrassignreport.php?sport=$sport&session=$session&type=$type&confirmed=$confirmed&sort=confirm\">NSAA</a></td>";
   echo "<td rowspan=2><a class=small href=\"wrassignreport.php?sport=$sport&session=$session&type=$type&confirmed=$confirmed&sort=child\">Child in<br>Tournament</a></td>";
   $sql2="SELECT * FROM wrtourndates WHERE lodgingdate='x' AND label = '$typech' ORDER BY tourndate";
   $result2=mysql_query($sql2);
   $colspan=mysql_num_rows($result2);
   echo "<td colspan=$colspan><b>Dates Needing Lodging</b></td>";
   echo "<td rowspan=2><a class=small href=\"wrassignreport.php?sport=$sport&session=$session&type=$type&confirmed=$confirmed&sort=arrive\">Arrival<br>Time</a></td>";
   echo "<td rowspan=2 width=300><a class=small href=\"wrassignreport.php?sport=$sport&session=$session&type=$type&confirmed=$confirmed&sort=special\">Special Requests</a></td>";
   echo "</tr><tr align=center>";
   $num=1;
   while($row2=mysql_fetch_array($result2))
   {
      $date=explode("-",$row2[tourndate]);
      $var="date".$num;
      echo "<td><a class=small href=\"wrassignreport.php?sport=$sport&session=$session&type=$type&confirmed=$confirmed&sort=$var\">$date[1]/$date[2]</a></td>";
      $num++;
   }
   echo "</tr>";
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=center>";
         echo "<td align=left>";
         if($type=="state") echo "<a class=small target=\"_blank\" href=\"wrstatecontract.php";
         else if($type=="statedual") echo "<a class=small target=\"_blank\" href=\"wrstatedualcontract.php";
         else echo "<a class=small target=new href=\"wrcontract.php";
         echo "?session=$session&givenoffid=$row[offid]&distid=$row[distid]\">";
         echo "".GetOffName($row[offid])."</a></td><td>$row[socsec]</td><td>$row[city]</td>";
      //echo "<td align=left>$row[first] $row[middle] $row[last]</td>";
      echo "<td>";
      if($row[post]=='y') echo "X";
      else echo "&nbsp;";
      echo "</td><td>";
      if($row[accept]=='y') echo "Yes";
      else if($row[accept]=='n') echo "No";
      else echo "?";
      echo "</td><td>";
      if($row[confirm]=='y') echo "Confirmed";
      else if($row[confirm]=='n') echo "Rejected"; 
      else echo "?";
      echo "</td><td>";
      if($row[child]=='y' && $row[accept]=='y') echo "YES";
      else if($row[child]=='n' && $row[accept]=='y') echo "No";
      else echo "&nbsp;";
      echo "</td>";
      for($x=1;$x<=$num;$x++)
      {
	  $var="date".$x;
          echo "<td>";
          if($row[$var]=='x') echo "X";
          else echo "&nbsp;";
          echo "</td>";
      }
      echo "<td align=left>";
      echo "$row[arrive]&nbsp;";
      echo "</td><td align=left width='250px'>";
      echo "$row[special]&nbsp;";
      echo "</td></tr>";
   }
   echo "</table></td></tr>";
}
elseif($type=="abc")
{
$sql="SELECT DISTINCT t1.offid,t2.first,t2.last FROM $contracts AS t1, officials AS t2, $disttimes AS t3 WHERE t1.offid=t2.id AND t1.distid=t3.id AND t3.type!='State' ORDER BY t2.last,t2.first";
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
   else if($ix==(($total/2)))
   {
      echo "</table></td>";
      echo "<td><table>";
   }
   echo "<tr align=left><td colspan=2><b><u>$row[last], $row[first]</u></td></tr>";
   echo "<tr align=center><td><table>";
   echo "<tr align=center><td align=left>&nbsp;</td>";
   echo "<td><b>Posted</b></td><td><b>Accept</b></td><td><b>NSAA</b></td></tr>";
   $sql2="SELECT DISTINCT t1.post,t1.accept,t1.confirm,t2.class,t2.district,t2.dates,t2.hostschool,t2.site,t2.type FROM $contracts AS t1,$disttimes AS t2 WHERE t1.distid=t2.id AND t1.offid='$row[offid]' AND t2.type!='State' ORDER BY t2.class,t2.district";
   $result2=mysql_query($sql2);
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
      echo "<td align=left>";
      if($row2[type]=="State") echo "STATE";
      else echo "$row2[type] $row2[class]-$row2[district]";
      echo "</td>";
      echo "<td>$post</td><td>$accept</td><td>$confirm</td></tr>";
   }
   echo "</table></td></tr>";
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
   $sql="SELECT * FROM $contracts";
   $result=mysql_query($sql);
   $total=mysql_num_rows($result);
   if($total%2==1) $total++;
   $ct=0;
   $sql="SELECT DISTINCT id,class,district,dates,hostschool,type FROM $disttimes WHERE type NOT LIKE 'State%' ORDER BY class,district";
   $result=mysql_query($sql);
   echo "<tr align=left valign=top><td><table>";
   while($row=mysql_fetch_array($result))
   {
      $class=$row[1]; $district=$row[2]; $type=$row[type];
      echo "<tr align=left><td><b><u>";
      $dates="";
      $date=split("/",$row[dates]);
      for($i=0;$i<count($date);$i++)
      {
	 $curdate=split("-",$date[$i]);
 	 $dates.=date("n/j",mktime(0,0,0,$curdate[1],$curdate[2],$curdate[0])).", ";
      }
      $dates=substr($dates,0,strlen($dates)-2);
      if($type=="State") echo "STATE";    
      else if($type=="State Dual") echo "STATE DUAL";
      else echo "District $class-$district: $dates (host: $row[hostschool])";
      echo "</u></b></td></tr>";
      $sql2="SELECT t1.* FROM $contracts AS t1, $disttimes AS t2 WHERE t1.distid=t2.id AND t2.id='$row[id]'";
      $result2=mysql_query($sql2);
      echo "<tr align=cemter><td><table>";
      echo "<tr align=center><td align=left><b>Official</b></td>";
      echo "<td><b>Posted</b></td><td><b>Accept</b></td><td><b>NSAA</b></td></tr>";
      while($row2=mysql_fetch_array($result2))
      {
	 echo "<tr align=center>";
         if($row2[post]=='y') $post="X";
         else $post="&nbsp;";
         if($row2[accept]=='y') $accept="Yes";
         else if($row2[accept]=='n') $accept="No";
         else $accept="?";
         if($row2[confirm]=='y') $confirm="Confirm";
         else if($row2[confirm]=='n') $confirm="Reject";
         else $confirm="?";
	 echo "<td align=left>";
         if($row2[type]=="State") echo "<a class=small target=\"_blank\" href=\"wrstatecontract.php";
         else if($row2[type]=="State Dual") echo "<a class=small target=\"_blank\" href=\"wrstatedualcontract.php";
         else echo "<a class=small target=new href=\"wrcontract.php";
         echo "?session=$session&givenoffid=$row2[offid]&distid=$row[id]\">";
         echo "".GetOffName($row2[offid])."</a></td>";
         echo "<td>$post</td><td>$accept</td><td>$confirm</td></tr>";
	 if($ct!=$total/2)
	    $ct++;
      }
      echo "</table></td></tr>";
      if($ct==$total/2)
      {
	 $ct++;
	 echo "</table></td><td><table>";
      }
   }
   echo "</table></td></tr>";
}//end if not abc
echo "</table>";
echo "<br>";

if($print!=1)   //LINKS TO OTHER $types
{
   if($type=="state")
   {
      echo "<a class=small href=\"wrassignreport.php?session=$session&sport=$sport\">District Assignments</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class=small href=\"wrassignreport.php?session=$session&sport=$sport&type=statedual\">State Dual Assignments</a>&nbsp;&nbsp;";
   }
   else if($type=="statedual")
   {
      echo "<a class=small href=\"wrassignreport.php?session=$session&sport=$sport\">District Assignments</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class=small href=\"wrassignreport.php?session=$session&sport=$sport&type=state\">State Assignments</a>&nbsp;&nbsp;";
   }
   else         //DISTRICTS
   {
      if($type=="abc")
         echo "<a class=small href=\"wrassignreport.php?session=$session&sport=$sport\">District Assignments in Class/District Order</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
      else
         echo "<a class=small href=\"wrassignreport.php?session=$session&sport=$sport&type=abc\">District Assignments in ABC Order</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
      echo "<a class=small href=\"wrassignreport.php?session=$session&sport=$sport&type=state\">State Assignments</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class=small href=\"wrassignreport.php?session=$session&sport=$sport&type=statedual\">State Dual Assignments</a>&nbsp;&nbsp;";
   }
   echo "|&nbsp;&nbsp;<a class=small target=new href=\"wrassignreport.php?session=$session&sport=$sport&type=$type&print=1&confirm=$confirmed\">Printer-Friendly Version</a>";
}//END IF NOT PRINT

echo "<br><br><a class=small href=\"#top\">Top</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class=small href=\"welcome.php?session=$session\">Home</a>";

}//end if sport!=""
echo $end_html;

?>
