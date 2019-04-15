<html>
<head>
<link href="../../css/nsaaforms.css" rel="stylesheet" type="text/css">
<body>
<center>
<b>

<?php
require 'variables.php';
require 'functions.php';

$level=GetLevelJ($session);
$sport='judge';
?>

<table width=100% cellspacing=0 cellpadding=0 height=100%>
<tr align=left>
<td width=18%>
   <form method=post action="submit_judge.php">
   <input type=hidden name=session value="<?php echo $session; ?>">
   <input type=hidden name=lastname value="<?php echo $lastname; ?>">
   &nbsp;&nbsp;<input type=submit name=submit value="Save">
   <br>
   &nbsp;&nbsp;<a href="judge_query.php?session=<?php echo $session; ?>" target="_top">Advanced Search</a>
   <br>

   &nbsp;&nbsp;<a href="judges.php?sport=<?php echo $sport; ?>&session=<?php echo $session; ?>&last=a" target="_top">View All</a>
   </form>
</td>
<td>
<b>Current Search:</b>
<?php
if($query && $query!="") 
{
   //echo $query."<br>";
   $temp=split(" WHERE ",$query);
   $fields=split(" AND ",$temp[1]);
   $search="";
   if($sport1 && $sport1!='~' && $bool=='~' && $sport2=='~')
   {
      $search="All ".strtoupper($sport1)." Judges: ";
   }
   elseif($sport1 && $sport1!='~' && $bool!='~' && $sport2 && $sport2!='~')
   {
      $search=strtoupper($sport1)." Judges $bool ".strtoupper($sport2)." Judges: ";
   }
   for($i=0;$i<count($fields);$i++)
   {
      if(!ereg("OR",$fields[$i]))
      {
	 if(ereg("LIKE",$fields[$i]))
	 {
	    $parts=split(" LIKE ",$fields[$i]);
	    $curfield=ereg_replace("t2.","",$parts[0]);
	    $curfield=ereg_replace("t1.","",$curfield);
	    $curvalue=ereg_replace("\'","",$parts[1]);
	    $curvalue=ereg_replace("%","",$curvalue);
	 }
	 else	//inequality
	 { 
	    $parts=split(" ",$fields[$i]);
	    $curfield=ereg_replace("t2.","",$parts[0]);
	    $curfield=ereg_replace("t1.","",$curfield);
	    $curvalue=$parts[1]." ".$parts[2];
	    if($curfield=="firstyr" || $curfield=="qualified" || $curfield=="meeting")
	    {
	       if(ereg("x",$parts[2]))
		  $curvalue="YES";
	       else
		  $curvalue="NO";
	    }
	 }
      }
      else
      {
	 $fields[$i]=substr($fields[$i],1,strlen($fields[$i])-2);
	 $parts=split(" OR ",$fields[$i]);
	 $parts2=split(" LIKE ",$parts[0]);
	 $curfield="area";
	 $curvalue=ereg_replace("\'","",$parts2[1]);
	 $curvalue=ereg_replace("%","",$curvalue);
      }
      switch($curfield)
      {
	 case "socsec":
	    $search.="Soc Sec # starts w/ <i>$curvalue</i>, ";
	    break;
	 case "last":
	    $search.="Last Name starts w/ <i>$curvalue</i>, ";
	    break;
	 case "first":
	    $search.="First Name starts w/ <i>$curvalue</i>, ";
	    break;
	 case "city":
	    $search.="City - $curvalue, ";
	    break;
	 case "zip":
	    $search.="Zip - $curvalue, ";
	    break;
	 case "area":
	    $search.="Area Code - $curvalue, ";
	    break;
	 case "firstyr":
	    $search.="New Judge - $curvalue, ";
	    break;
	 case "qualified":
	    $search.="LD Qualified - $curvalue, ";
	    break;
	 case "meeting":
	    $search.="Attended Meeting - $curvalue, ";
	    break;
	 case "datereg":
	    $search.="Registration Date $curvalue, ";
	 case "payment":
	    $search.="Payment starts w/ <i>$curvalue</i>, ";
	    break;
	 default:
	    $search.="";
      }
   }
   $search=substr($search,0,strlen($search)-2);
}
if($search=="" || $query=="SELECT * FROM judges") $search="All Judges";
if($setquery=='meeting')
   $search="Judges who have PAID and PASSED THEIR TEST(S) but have NOT ATTENDED A RULES MEETING";
else if($setquery=='test')
   $search="Judges who have PAID and ATTENDED A RULES MEETING but have NOT PASSED THEIR TEST(S) or HAVEN'T TAKEN THEIR TEST(S)";
else if($setquery=='apply')
   $search="Judges who have PAID & ATTENDED A RULES MEETING, but REGISTERED for Speech AND Play and ONLY TOOK ONE TEST";
else if($setquery=='all')
   $search="Judges who have PAID, ATTENDED A RULES MEETING, and PASSED THEIR TEST(S)";
else if($setquery=='pins')
   $search="Judges who should receive a PIN (those who have paid, attended a rules meeting, passed their test(s), and are FIRST-YEAR judges)";
echo $search;
echo "<br><b>Export this search as:&nbsp;";
if(ereg("datesent",$query))
{
   echo "<a class=small href=\"export.php?session=$session&sport=$sport&query=$query&type=mail&datesent=$datesent&search=$search\" target=new>Mailing Labels</a>&nbsp;&nbsp;";
}
echo "<a class=small href=\"export.php?session=$session&sport=$sport&query=$query&type=zip&search=$search\" target=new>Zip Summary</a>&nbsp;&nbsp;";
echo "<a class=small href=\"export.php?session=$session&sport=$sport&query=$query&type=roster&search=$search\" target=new>Roster</a>&nbsp;&nbsp;";
if($sport=='judge')
   echo "<a class=\"small\" href=\"export.php?session=$session&sport=$sport&query=$query&type=quick&search=$search\" target=\"_blank\">Name, Email, Address</a>";
echo "<br>";
echo "<a href='#' onClick=\"window.open('add_judge.php?session=$session&sport=$sport&last=$last','add_judge','height=550,width=500,menubar=no,scrollbars=yes,toolbar=no,resizable=yes,titlebar=no')\">Add New Judge</a>";
echo "
&nbsp;
&nbsp;";
echo "<a href=\"jwelcome.php?session=$session\" target=\"_top\">Home</a>";
echo "</td>";
echo "<td align=left><form method=post action=\"judges.php\" target=\"_top\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<td align=right><table>";
echo "<tr align=left><td>Quick Search:<br>Last Name: <input type=text name=lastname value=\"$lastname\" size=20>";
echo "<input type=submit name=submit value=\"Go\"></form></td></tr></table></td>";
echo "
</tr>
</table>

</center>
</body>
</html>";
?>
