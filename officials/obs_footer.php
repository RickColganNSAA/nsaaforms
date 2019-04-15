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

<table width=100% cellspacing=0 cellpadding=0 height=100%>
<tr align=left>
<td width=18%>
   <form method=post action="submit_obs.php">
   &nbsp;&nbsp;<input type=submit name=submit value="Save">
   <br>
   &nbsp;&nbsp;<a href="obs_query.php?session=<?php echo $session; ?>" target="_top">Advanced Search</a>
   <br>

   &nbsp;&nbsp;<a href="observers.php?sport=<?php echo $sport; ?>&session=<?php echo $session; ?>&last=a" target="_top">View All</a>
   <?php
   if($level==1)	//NSAA user
   {
      echo "&nbsp;".strtoupper($sport);
   }
   ?>

   </form>
</td>
<td>
<b>Current Search:</b>
<?php
$sportlong=GetSportName($sport);
if($query && $query!="") 
{
   //echo $query."<br>";
   $temp=split(" WHERE ",$query);
   $fields=split(" AND ",$temp[1]);
   $search="";
   if(ereg("AS t1",$query))
   {
      for($i=0;$i<count($activity);$i++)
      {
	 if($sport==$activity[$i])
	    $search.="$act_long[$i] Observers, ";
      }
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
	    if($curfield=="senttofed")
	    {
	       if($parts[1]=='>')	//sent to fed: yes
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
	 case "email":
	    $search.="E-mail starts w/ <i>$curvalue</i>, ";
	    break;
	 case "payment":
	    $search.="Payment - $curvalue, ";
	    break;
	 case "senttofed":
	    $search.="Sent to NFHS: $curvalue, ";
	    break;
	 case "class":
	    $search.="Class - $curvalue, ";
	    break;
	 case "suptestdate":
	    $search.="Sup Test Date $curvalue, ";
	    break;
	 case "mailing":
	    $search.="Mailing # $curvalue, ";
	    break;
	 case "years":
	    $search.="Years $curvalue, ";
	    break;
	 case "currentst":
	    $search.="Current ST - $curvalue, ";
	    break;
	 case "retaketest":
	    $search.="Retake Test $curvalue, ";
	    break;
	 case "chosen":
	    $search.="Chosen - Yes, ";
	    break;
	 case "patches":
	    $search.="Patches - $curvalue, ";
	    break;
	 default:
	    $search.="";
      }
   }
   $search=substr($search,0,strlen($search)-2);
}
else
{
   if($sportlong && $sportlong!="Sport") $search="$sportlong Observers";
   else $search="All Observers";
   if(trim($lastname)!="") $search.=", Last Name Starts w/ <i>$lastname</i>";
}
echo $search;
echo "<br><b>Export this search as:&nbsp;";
echo "<a class=small href=\"obsexport.php?session=$session&sport=$sport&query=$query&type=mail&mailoption=$mailoption&search=$search\" target=new>Mailing Labels</a>&nbsp;&nbsp;";
echo "<br>";
echo "<a href='#' onClick=\"window.open('add_obs.php?session=$session&sport=$sport&last=$last','add_obs','height=550,width=500,menubar=no,scrollbars=yes,toolbar=no,resizable=yes,titlebar=no')\">Add New Observer</a>";
echo "
&nbsp;
&nbsp;";
echo "<a href=\"welcome.php?session=$session\" target=\"_top\">Home</a>";
echo "</td>";
echo "<td align=left><form method=post action=\"observers.php\" target=\"_top\">";
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
