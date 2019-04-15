<html>
<head>
<link href="../../css/nsaaforms.css" rel="stylesheet" type="text/css">
<body>
<center>
<b>

<?php
require 'variables.php';
require 'functions.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

$level=GetLevel($session);
?>

<table width="100%" cellspacing=0 cellpadding=5 height="100%">
<tr align=left>
<td width=18%>
   <form method=post action="submit_off.php">
   &nbsp;&nbsp;<input type=submit name=submit value="Save">
   <br>
   &nbsp;&nbsp;<a href="off_query.php?session=<?php echo $session; ?>" target="_top">Advanced Search</a>
   <br>

   &nbsp;&nbsp;<a href="officials.php?sport=<?php echo $sport; ?>&session=<?php echo $session; ?>&last=a" target="_top">View All</a>
   <?php
   if($level==1)	//NSAA user
   {
      echo "&nbsp;".strtoupper($sport);
   }
   ?>

   </form>
</td>
<td>
<p><b>Current Search:</b>
<?php
$sportlong=GetSportName($sport);
if($query && $query!="") 
{
   $search=GetSearchDescription($query);
}
else
{
   if($sportlong) $search="$sportlong Officials";
   else $search="All Officials";
}
if($mailoption==3)
{
   if(trim($search)!="") $search.="; ";
   $search.="Officials with mailing number $mailineq $mailnum3 in ANY SPORT";
}
if($insincedist!='' && $yeardist!='')
{
   if(trim($search)!="") $search.="; ";
   $search.="Officials who've officiated (Sub)Districts in&nbsp;";
   if($sport && $sport!='All Sports') $search.=GetSportName($sport)."&nbsp;";
   else $search.="ANY SPORT&nbsp;";  
   if($yeardist=="$db_name2") $search.="this year";
   else
   {
      $search.=strtolower($insincedist);
      $temp=split("officials",$yeardist);
      $search.="&nbsp;".substr($temp[1],0,4)."-".substr($temp[1],4,4);
   }
}
if($insincestate!='' && $yearstate!='')
{
   if(trim($search)!="") $search.="; ";
   $search.="Officials who've officiated STATE in&nbsp;";
   if($sport && $sport!='All Sports') $search.=GetSportName($sport)."&nbsp;";
   else $search.="ANY SPORT&nbsp;";
   $search.=strtolower($insincestate)." $yearstate";
}
if($stateyearsineq!='' && $numstateyears!='')
{
   if(trim($search)!='') $search.="; ";
   $search.="Officials who've officiated STATE for $stateyearsineq $numstateyears years";
}
echo $search;
echo "</p><p><b>Export this search as:&nbsp;";
if(ereg("mailing",$query))
{
   echo "<a href=\"export.php?type=mail&session=$session&sport=$sport&query=$query&stateyearsineq=$stateyearsineq&numstateyears=$numstateyears&insincestate=$insincestate&yearstate=$yearstate&insincedist=$insincedist&yeardist=$yeardist&mailoption=$mailoption&whichmailnum=$whichmailnum&search=$search\" target=new>Mailing Labels</a>&nbsp;&nbsp;";
}
if(ereg("nhsoa",$query))
{
   echo "<a href=\"export.php?type=nhsoa&session=$session&sport=$sport&query=$query&stateyearsineq=$stateyearsineq&numstateyears=$numstateyears&insincestate=$insincestate&yearstate=$yearstate&insincedist=$insincedist&yeardist=$yeardist&mailoption=$mailoption&whichmailnum=$whichmailnum&search=$search\" target=\"_blank\">NHSOA Export</a>&nbsp;&nbsp;";
}
echo "<a href=\"export.php?session=$session&sport=$sport&query=$query&type=zip&search=$search&stateyearsineq=$stateyearsineq&numstateyears=$numstateyears&insincestate=$insincestate&yearstate=$yearstate&insincedist=$insincedist&yearist=$yeardist&mailoption=$mailoption&whichmailnum=$whichmailnum&mailineq=$mailineq&mailnum3=$mailnum3\" target=new>Zip Summary</a>&nbsp;&nbsp;";

//START EXPORT OF ROSTER IN BACKGROUND (may be very large file)
$filetime=time();
$outfile="rosteroutput".$filetime.".html";
$query=preg_replace("/'/","`",$query);
citgf_exec("/usr/local/bin/php export.php '$filetime' '$session' '$sport' '$query' 'roster' '$search' '$stateyearsineq' '$numstateyears' '$insincestate' '$yearstate' '$insincedist' '$yeardist' '$mailoption' '$whichmailnum' '$mailineq' '$mailnum3' > 'output/$outfile' 2>&1 &");
//LINK TO REPORTS.php
//echo "<a href=\"reports.php?filename=roster".$filetime.".csv&session=$session\" target=\"_blank\">Roster $outfile</a>&nbsp;&nbsp;";
//ON 4/28/15 REVERTED BACK TO REGULAR LINK BECAUSE COULDN'T GET FILE WRITE TO WORK
echo "<a href=\"export.php?session=$session&sport=$sport&query=$query&type=roster&search=$search&insincestate=$insincestate&yearstate=$yearstate&stateyearsineq=$stateyearsineq&numstateyears=$numstateyears&insincedist=$insincedist&yeardist=$yeardist&mailoption=$mailoption&whichmailnum=$whichmailnum&mailineq=$mailineq&mailnum3=$mailnum3&bypass=1\" target=\"_blank\">Roster</a>&nbsp;&nbsp;";

if($sport && $sport!="" && $sport!="All Sports")
{
   echo "<a href=\"export.php?session=$session&sport=$sport&query=$query&type=member&search=$search\" target=new>Membership Cards</a>&nbsp;&nbsp;";
   echo "<a href=\"export.php?session=$session&sport=$sport&query=$query&type=all&search=$search\" target=new>Records Printout</a>&nbsp;&nbsp;";
}

//echo "<a class=small href=\"export.php?session=$session&sport=$sport&query=$query&type=all&search=$search\" target=new>Full Detailed Report</a>&nbsp;&nbsp;";
echo "</p><p>";
echo "<a href='#' onClick=\"window.open('add_off.php?session=$session&sport=$sport&last=$last','add_off','height=550,width=500,menubar=no,scrollbars=yes,toolbar=no,resizable=yes,titlebar=no')\">Add New Official</a>";
//echo "<a target=new href=\"add_off.php?session=$session&sport=$sport&last=$last\">Add Officials Manually</a>";
//echo "&nbsp;&nbsp;
//<a href=\"import_off.php?session=$session&sport=$sport\" target=\"_top\">Import File of Officials</a>";
echo "
&nbsp;
&nbsp;";
echo "<a href=\"welcome.php?session=$session\" target=\"_top\">Home</a></p>";
echo "</td>";
echo "<td align=left><form method=post action=\"officials.php\" target=\"_top\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "Quick Search:&nbsp;&nbsp;";
echo "<select name=sport>";
echo "<option";
if(ereg("All",$sport)) echo " selected";
echo ">All Sports";
for($i=0;$i<count($activity);$i++)
{
   echo "<option value='$activity[$i]'";
   if($activity[$i]==$sport) echo " selected";
   echo ">$act_long[$i]";
}
echo "</select>";
//echo "<input type=submit name=submit value=\"Go\">";
echo "<br>Last Name:&nbsp;&nbsp;<input type=text name=lastname size=10 class=tiny value=\"$lastname\">";
echo "<input type=submit name=submit value=\"Go\">";
echo "</form></td>";
echo "
</tr>
</table>

</center>
</body>
</html>";
?>
