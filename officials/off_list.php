<?php

require 'variables.php';
require 'functions.php';

//connect to db:
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}
?>

<html>
<head>
<script language="javascript">
function Color(element)
{
   while(element.tagName.toUpperCase() != 'TD' && element != null)
      element = document.all ? element.parentElement : element.parentNode;
   if(element)
   {
      element.bgColor="FFFF33";
   }
}
<?php
/*
win_progressbar=window.open('progressbar.htm','Searching','channelmode=no, directories=no, toolbar=no,titlebar=no,left=300,top=500,width=300,height=100,status=no,scrollbars=no,resizable=no,menubar=no');
win_progressbar.opener=self;
function close_progressbar()
{
   if(!win_progressbar.closed)  win_progressbar.close();
}
*/
?>
</script>
<link rel="stylesheet" href="../../css/nsaaforms.css" type="text/css">
</head>
<!--<body onLoad="close_progressbar();">-->
<body>
<table width="100%" cellspacing="0" cellpadding="3" frame="all" rules="all" style="border:#a0a0a0 1px solid;">
<form method="post" name="off_form" action="update_off.php">
<input type=hidden name=session value="<?php echo $session; ?>">
<?php
//get sport name
for($i=0;$i<count($activity);$i++)
{
   if($activity[$i]==$sport)
   {
      $sportname=$act_long[$i];
   }
}

$sql="SELECT * FROM officials";
if($sport && $sport!="" && !ereg("All",$sport))	//if specific sport chosen
{
   $sql.=" WHERE $sport='x'";
   $sportch='y';
}
else
   $sportch='n';
if($lastname && $lastname!="")	//if last name given in quick search
{
   if($sportch=='y')
      $sql.=" AND last LIKE '$lastname%'";
   else
      $sql.=" WHERE last LIKE '$lastname%'";
   $lastch='y';
}
else
   $lastch='n';

//***DISPLAY OFFICIALS***//
$ix=0;	//ix is used to see if row is even or odd
if(substr(trim($query),0,6)!="SELECT" || !$query || $query=="")	//if query not sent from Advanced Search
   $result=mysql_query($sql);
else 
{
   $sql=$query;
   $result=mysql_query($sql);
}
$tot_ct=mysql_num_rows($result);
//echo $sql;

if($tot_ct==1 && !ereg("mailing",$sql) && $findone=='1')
{
   //go straight to that officials edit_off page
   $row=mysql_fetch_array($result);
?>
<script language="javascript">
top.location.replace('edit_off.php?session=<?php echo $session; ?>&sport=<?php echo $sport; ?>&last=<?php echo $last; ?>&id=<?php echo $row[id]; ?>');
</script>
<?php
   exit();
}

if($mailoption!=3 && !($insincedist!='' && $yeardist!='') && !($insincestate!='' && $yearstate!='') && !($stateyearsineq!='' && $numstateyears!=''))
   echo "<tr align=left><td colspan=28>Your search returned <b>$tot_ct</b> results,";
else 
   echo "<tr align=left><td colspan=28>";

if(!$last && $yearstate=='' && $tot_ct>=100) $last='a';
else if(!$last) $last="All";
if($last!="All" && ereg("WHERE",$sql) && $lastch=='n') $sql.=" AND last LIKE '$last%'";
else if($last!="All" && $lastch=='n') $sql.=" WHERE last LIKE '$last%'";
$sql.=" ORDER BY last,first";
$query2=$query;
if($last && $last!="All" && $query && $query!="")
{
   if(ereg(" AS ",$query) && !ereg("last",$query))
      $query2.=" AND t1.last LIKE '$last%'";
   else if(!ereg("last",$query))
   {
      if(ereg("WHERE",$query2))
         $query2.=" AND last LIKE '$last%'";
      else
	 $query2.=" WHERE last LIKE '$last%'";
   }
}
if($query && $query!="")
{
   if(ereg(" AS ",$query))
      $query2.=" ORDER BY t1.last,t1.first";
   else
      $query2.=" ORDER BY last,first";
}
if(!$query || $query=="")	//if query not sent from Advanced Search
   $result=mysql_query($sql);
else
{
   $result=mysql_query($query2);
}
echo mysql_error();
$ct=mysql_num_rows($result);
if($mailoption!=3 && !($insincedist!='' && $yeardist!='') && !($insincestate!='' && $yearstate!='') && !($stateyearsineq!='' && $numstateyears!=''))
   echo " <b>$ct</b> of which are showing: ";

//show links to letters of alphabet for navigation:
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$alphabet=array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
for($i=0;$i<count($alphabet);$i++)
{
   $upper=strtoupper($alphabet[$i]);
   if($last==$alphabet[$i])
   {
      echo "<b><font size=2>$upper&nbsp;</font></b>";
   }
   else
   {
      echo "<a href=\"off_list.php?last=$alphabet[$i]&sport=$sport&query=$query&session=$session&stateyearsineq=$stateyearsineq&numstateyears=$numstateyears&insincestate=$insincestate&yearstate=$yearstate&insincedist=$insincedist&yeardist=$yeardist&mailineq=$mailineq&mailoption=$mailoption&whichmailnum=$whichmailnum&mailnum3=$mailnum3&findone=$findone&lastname=$lastname\">$upper</a>&nbsp;";
   }
}
if(!$last || $last=="All")   echo "<b><font size=2>All</font></b>";
else
{
   echo "<a href=\"off_list.php?last=All&sport=$sport&query=$query&session=$session&stateyearsineq=$stateyearsineq&numstateyears=$numstateyears&insincestate=$insincestate&yearstate=$yearstate&insincedist=$insincedist&yeardist=$yeardist&mailineq=$mailineq&mailoption=$mailoption&whichmailnum=$whichmailnum&mailnum3=$mailnum3&findone=$findone&lastname=$lastname\">All</a>";
}
echo "</td></tr>";
$string="";
//echo $sql;
while($row=mysql_fetch_array($result))
{
   $inrange=1;	//inrange is 1 unless mailoption=3
   if($mailoption==3)
   {
      //SHOW OFFICIALS WITH GIVEN MAILING # RANGE IN ANY SPORT (sport not specified):
      $inrange=0;	//assume official has no mailing number in any sport matching the range
      $showmailing="";
      for($i=0;$i<count($activity);$i++)
      {
         $cursp=$activity[$i];
	 $table=$cursp."off";
         $sql2="SELECT mailing FROM $table WHERE offid='$row[0]' AND mailing $mailineq '$mailnum3'";
	 $result2=mysql_query($sql2);
    	 $row2=mysql_fetch_array($result2);
	 if(mysql_num_rows($result2)>0)	//official has mailing # in given range
         {
	    $inrange=1;
            $showmailing.=strtoupper($cursp).": $row2[0]/";
         }
      }
   }
   $offdist=1;	//offdist is 1 unless insincedist and yeardist have values
   if($insincedist!='' && $yeardist!='')
   { 
      $offdist=0;       //assume official does not meet district contract criteria
      $showcontract="";
      for($i=0;$i<count($activity);$i++)
      {
         $cursp=$activity[$i];
	 if(!$sport || $sport=='All Sports' || $sport==$cursp)
	 {
	    if(OfficiatedDistricts($row[0],$insincedist,$yeardist,$cursp))
	       $offdist=1;
         }
      } 
   }
   $offstate=1;
   if($insincestate!='' && $yearstate!='')
   {
      $offstate=0;       //assume official does not meet district contract criteria
      $showcontract="";
      for($i=0;$i<count($activity);$i++)
      {
         $cursp=$activity[$i];
         if(!$sport || $sport=='All Sports' || $sport==$cursp)
         {
            if(OfficiatedState($row[0],$insincestate,$yearstate,$cursp))
               $offstate=1;
         }
      }
	//echo "$row[0] $offstate<br>";
   }
   $offyears=1;
   if($stateyearsineq!='' && $numstateyears!='')
   {
      $offyears=0;       //assume official does not meet criteria
      for($i=0;$i<count($activity);$i++)
      {
         $cursp=$activity[$i];
         if(!$sport || $sport=='All Sports' || $sport==$cursp)
         {
            if(($stateyearsineq==">=" && YearsOfficiatedState($row[0],$cursp) >= $numstateyears) || ($stateyearsineq=="<=" && YearsOfficiatedState($row[0],$cursp) <= $numstateyears) || ($stateyearsineq=="=" && YearsOfficiatedState($row[0],$cursp) == $numstateyears))
               $offyears=YearsOfficiatedState($row[0],$cursp);
         }
      }
   }
   if($inrange==1 && $offdist==1 && $offstate==1 && $offyears>0)
   {
      $showmailing=substr($showmailing,0,strlen($showmailing)-1);
   //get student id and submit as hidden to form
   $string.="<input type=hidden name=\"offid[$ix]\" value=\"$row[0]\">";
   if($ix%15==0)
   {
      $string.="<tr align=center>
      <th>Name<br>(last, first MI)</th>
      <th>Soc Sec #</th>
      <th>City, State</th>
      <th>Sent to NFHS</th>";
      for($i=0;$i<count($activity);$i++)
      {
         $string.="<th>".strtoupper($activity[$i])."</th>";
      }
      //if specific sport chosen, showing mailing field
      if($sport && !ereg("All",$sport))
      {
         $string.="<th>".strtoupper($sport)."<br>Mailing</th>";
         $string.="<th>".strtoupper($sport)."<br>Meetings</th>";
      }
      $string.="<th>Notes</th></tr>";
   }
   $string.="<tr title=\"$row[2], $row[3] $row[4]\" align=center";
   if($ix%2==0)
   {
      $color="#E0E0E0";
      $string.=" bgcolor=#E0E0E0";
   }
   else $color="#FFFFFF";
   $string.=">";
   $string.="<td align=left";
   $string.="> <a name=\"$row[0]\"><a class=small style=\"color:black\" target=\"_top\" title=\"$row[1]\" href=\"edit_off.php?session=$session&id=$row[0]&sport=$sport&query=$query&last=$last\">";
   $string.="$row[2], $row[3] $row[4]</a></a>";
   if($sportch=='y')	//if specific sport chosen, show link to that subform for current official
   {
      $string.="<br>&nbsp;&nbsp;&rarr;";
      $query3=ereg_replace("\'","\'",$query);
      if($row[id]=='3427') $page="edit_sport.php";
      else $page="edit_sport.php";
      $string.="<a href='#$row[0]' class=small onclick=\"window.open('$page?session=$session&id=$row[0]&sport=$sport&query=$query3&last=$last','$sport','width=600,height=600,menubar=no,resizable=yes,scrollbars=yes');\">$sportname Subform</a>";
   }
   $string.="</td>";
   
   /*-------Soc Sec # column replaceed by *********---------*/
   //$string.="<td>$row[1]</td>";
   if( $row[1]!= '' ) {
	   $Soc_Sec = '*********';
   } else{
	   $Soc_Sec = '';
   }
   
   $string.="<td>$Soc_Sec</td>";
   $string.="<td align=left>$row[city], $row[state]</td>";
   $string.="<td><input type=checkbox name=\"senttofed[$ix]\" value='x'";
   if($row[senttofed]>0) $string.=" checked";
   $string.="></td>";
   for($i=0;$i<count($activity);$i++)
   {
      $string.="<td><input type=\"checkbox\" onClick=\"Color(this)\" name=\"$activity[$i][$ix]\" value=\"x\"";      
      if($row[$activity[$i]]=="x") $string.=" checked";
      $string.="></td>";
   }
   if($sport && !ereg("All",$sport))
   {
      $table=$sport."off";
      //get mailing #
      $sql2="SELECT mailing FROM $table WHERE offid='$row[id]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $curmail=$row2[0];
      //get 'meetings' (rm value)
      $curyr=date("Y");
      $curmo=date("m");
      if($curmo<6)
         $curyr--;
      $curyr1=$curyr+1;
      $regyr="$curyr-$curyr1";
      $table2=$table."_hist";
      $sql2="SELECT * FROM $table2 WHERE offid='$row[id]' AND regyr='$regyr'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $curmeet=$row2[rm];
      $string.="<td align=center><input type=text onchange=\"Color(this)\" name=\"mailing[$ix]\" value=\"$curmail\" size=4></td>";
      $string.="<td align=center><input type=checkbox onchange=\"Color(this)\" name=\"rm[$ix]\" value='x'";
      if($curmeet=='x') $string.=" checked";
      $string.="></td>";
   }
   if($mailoption==3)
      $string.="<td align=center>$showmailing</td>";
   else
      $string.="<td align=center><input type=text onchange=\"Color(this)\" name=\"notes[$ix]\" style=\"width:90%;\" value=\"$row[notes]\"></td>";
   $string.="</tr>";
   $ix++;
   }//end if inrange==1
}
if($ix==1) 
{
   $isare="is"; $results="result";
}
else
{
   $isare="are"; $results="results";
}
if($last!="All")
   echo "<tr align=left><td colspan=28><b>$ix</b> of your results $isare showing. Click \"All\" to see all of your results, along with the overall total count.</td></tr>";
else
   echo "<tr align=left><td colspan=28>Your search returned <b>$ix total $results</b>.</td></tr>";
echo $string;
?>
</table>
<br>
<input type=hidden name=count value=<?php echo $ix; ?>>
<input type=hidden name=last value="<?php echo $last; ?>">
<input type=hidden name=sport value=<?php echo $sport; ?>>
<input type=hidden name=lastname value="<?php echo $lastname; ?>">
<input type=hidden name=query value="<?php echo $query; ?>">
</form>
</body>
</html>
