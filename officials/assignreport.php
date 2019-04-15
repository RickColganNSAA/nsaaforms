<?php
if($sportch2) $sport=$sportch2;
if($sport=="Choose Sport")
{
   header("Location:welcome.php?session=$session");
   exit();
}
else if($sport=='fb')
{
   header("Location:fbassignreport.php?session=$session&type=$type&posted=$posted");
   exit();
}
else if($sport=='wr')
{
   header("Location:wrassignreport.php?session=$session&type=$type&posted=$posted");
   exit();
}
else if($sport=='bb')
{
   header("Location:bbassignreport.php?session=$session&type=$type&posted=$posted");
   exit();
}
else if($sport=='so')
{
   header("Location:soassignreport.php?session=$session&type=$type&posted=$posted");
   exit();
}
else if($sport=='ba')
{
   header("Location:baassignreport.php?session=$session&type=$type&posted=$posted");
   exit();
}
else if($sport=='vb')
{
   header("Location:vbassignreport.php?session=$session&type=$type&posted=$posted");
   exit();
}


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
$disttimes=$sport."disttimes";
$districts=$sport."districts";
$contracts=$sport."contracts";

echo $init_html;
if($print!=1)
   echo GetHeader($session);
else
   echo "<table width=100%><tr align=center><td>";
echo "<center><br>";

if($print!=1)
{
   echo "<form method=post action=\"assignreport.php\">";
   echo "<input type=hidden name=session value=$session>";
   echo "<select onchange=submit() name=sport><option value=''>Choose Sport</option>";
   $sports=array("fb","sb","vb","wr");
   for($i=0;$i<count($sports);$i++)
   {
      echo "<option value=\"$sports[$i]\"";
      if($sport==$sports[$i]) echo " selected";
      echo ">".GetSportName($sports[$i])."</option>";
   }
   echo "</select><br><br>";
}

if($sport && $sport!="")
{

if($type=="abc" && $print!=1)
   echo "<a class=small href=\"assignreport.php?session=$session&sport=$sport\">View Assignments in Class/District Order</a>";
elseif($print!=1)
   echo "<a class=small href=\"assignreport.php?session=$session&sport=$sport&type=abc\">View Assignments in ABC Order</a>";
if($print!=1)
   echo "&nbsp;&nbsp;<a class=small target=new href=\"assignreport.php?session=$session&sport=$sport&type=$type&print=1\">Printer-Friendly Version</a>";
echo "<br><br>";

$sportname=GetSportName($sport);
echo "<table><caption><b>$sportname Assignments (";
if($type=="abc")
   echo "ABC Order by Official";
else
   echo "Class/District Order";
echo "):</b><br>";
if($sport!='sb' && $print!=1)
   echo "<a class=small href=\"assignpost.php?session=$session&sport=$sport\">Post ALL Contracts</a><br>";
if($posted=="yes")
   echo "<br><font style=\"font-size:8pt;color:blue\"><b>These assignments have been posted to the officials' logins.</b></font>";
if($print!=1)
   echo "<a class=small target=new href=\"".$sport."assignexport.php?session=$session\">MAILING EXPORT: State $sportname Officials</a>";
echo "<hr></caption>";

if($type=="abc")
{
$sql="SELECT DISTINCT t1.offid,t2.first,t2.last,t1.accept,t1.confirm,t1.post FROM $contracts AS t1, officials AS t2 WHERE t1.offid=t2.id ORDER BY t2.last,t2.first";
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
   if($sport=='sb')
   {
      $types=array("District","State");
   }
   else
   {
      $types=array("District","Subdistrict","District Final","State");
   }
   for($t=0;$t<count($types);$t++)
   {
   $sql2="SELECT DISTINCT t2.class,t2.dist,t2.type FROM $contracts AS t1,$disttimes AS t2 WHERE t1.disttimesid=t2.id AND t2.type='$types[$t]' AND t1.offid='$row[offid]' ORDER BY t2.class,t2.dist";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      echo "<tr valign=top align=left>";
      if($row2[type]=="State")
	 echo "<td colspan=2><b>State</b></td></tr>";
      else
      {
         echo "<td><b>$row2[class]-$row2[dist]:";
         echo "</td><td><table>";
         $sql3="SELECT t1.times,t2.times,t1.day FROM $disttimes AS t1,$contracts AS t2 WHERE t1.id=t2.disttimesid AND t1.class='$row2[class]' AND t1.dist='$row2[dist]' AND t2.offid='$row[offid]' ORDER BY t1.day";
         $result3=mysql_query($sql3);
         while($row3=mysql_fetch_array($result3))
         {
	    $date=split("-",$row3[day]);
	    echo "<tr align=left><td>".date("F j",mktime(0,0,0,$date[1],$date[2],$date[0]))."</td>";
	    echo "<td>";
	    $showtimes=split("/",$row3[0]);
	    $timech=split("/",$row3[1]);
	    $timestr="";
	    for($i=0;$i<count($showtimes);$i++)
	    {
	       if($timech[$i]=='x')
	       {
	          $timestr.=$showtimes[$i].", ";
	       }
	    }
	    $timestr=substr($timestr,0,strlen($timestr)-2);
	    echo $timestr."</td></tr>";
         }
         echo "</table></td></tr>";
      }
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
   if($sport=='sb')
   {
      $types=array("District","State");
   }
   else if($sport=='vb')
   {
      $types=array("District","Subdistrict","District Final","State");
   }
   $x=0;
   $sql="SELECT distinct class,dist,type FROM $disttimes";
   $result=mysql_query($sql);
   $total=mysql_num_rows($result);
   if($total%2==1) $total++;
   for($t=0;$t<count($types);$t++)
   {
   echo "<tr align=left><td colspan=2><br><b>".strtoupper($types[$t])." ASSIGNMENTS:</b></td></tr>";
   $sql="SELECT DISTINCT class,dist FROM $disttimes WHERE type='$types[$t]' ORDER BY class,dist";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      if($x==0)
      {
         echo "<tr align=left valign=top><td><table>";
      }
      else if($x==(($total/2)-7))
      {
         echo "</table></td>";
         echo "<td><table>";
      }
      if($types[$t]!="State")
         echo "<tr align=left><td colspan=2><br><b><u>$row[class]-$row[dist]</u></td></tr>";
      $sql2="SELECT id,day,times FROM $disttimes WHERE class='$row[class]' AND dist='$row[dist]' AND type='$types[$t]' ORDER BY day";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
	 $date=split("-",$row2[day]);
	 if($types[$t]!="State")
	    echo "<tr align=left><td colspan=2><b>".date("F j",mktime(0,0,0,$date[1],$date[2],$date[0]))."</b></td></tr>";
	 //get officials for this day
	 $sql3="SELECT offid,times";
	 if($sport!="sb")
	    $sql3.=",post,accept,confirm";
	 $sql3.=" FROM $contracts WHERE disttimesid='$row2[id]'";
	 $result3=mysql_query($sql3);
	 $offs=array(); $offtimes=array(); $ix=0;
	 $offstat[post]=array();
	 $offstat[accept]=array();
	 $offstat[confirm]=array();
	 while($row3=mysql_fetch_array($result3))
	 {
	    $offs[$ix]=$row3[offid];
	    $offtimes[$ix]=split("/",$row3[times]);
	    if($sport!='sb')
	    {
	       $offstat[post]=$row3[post];
	       $offstat[accept]=$row3[accept];
	       $offstat[confirm]=$row3[confirm];
	    }
	    $ix++;
	 }

	 $showtimes=split("/",$row2[times]);
	 for($i=0;$i<count($showtimes);$i++)
	 {
	    echo "<tr align=left><td>$showtimes[$i]</td>";
	    echo "<td>";
	    $curoffs="";
	    for($j=0;$j<count($offs);$j++)
	    {
	       if($offtimes[$j][$i]=='x')
		  $curoffs.=GetOffName($offs[$j]).", ";
	    }
	    if($curoffs!="") $curoffs=substr($curoffs,0,strlen($curoffs)-2);
	    echo $curoffs."</td>";
	    if($sport!='sb')
	    {
	       echo "<td>";
	       if($offstat[confirm][$j]=='y')
	          echo "Confirmed";
	       else if($offstat[confirm][$j]=='n')
	          echo "Rejected";
	       else if($offstat[accept][$j]=='y')
	          echo "Accepted";
	       else if($offstat[accept][$j]=='n')
	          echo "Declined";
	       else if($offstat[post][$j]=='y')
	          echo "Posted";
	       else
	          echo "&nbsp;";
	       echo "</td>";
	    }
	    echo "</tr>";
	 }
      }
      if($x==($total-1))
      {
	 echo "</table></td></tr>";
      }
      $x++;
   }
   }
}//end if not abc
echo "</table>";
echo "<br>";
if($type=="abc" && $print!=1)
   echo "<a class=small href=\"assignreport.php?session=$session&sport=$sport\">View Assignments in Class/District Order</a>&nbsp;&nbsp;&nbsp;";
elseif($print!=1)
   echo "<a class=small href=\"assignreport.php?session=$session&sport=$sport&type=abc\">View Assignments in ABC Order</a>&nbsp;&nbsp;&nbsp;";
if($print!=1)
   echo "<a class=small href=\"welcome.php?session=$session\">Home</a>";

}//end if sport!=""
echo $end_html;

?>
