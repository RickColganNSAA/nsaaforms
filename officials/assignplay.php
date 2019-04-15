<?php
//speech & play production district assignments

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}

if($distid=='State')
{
   header("Location:assignplay2.php?session=$session");
   exit();
}

   $ppdist=array(); $i=0;
   $ppdist2=array(); $ppdist_sm=array();
   $sql="SELECT * FROM pptourndates WHERE offdate='x' AND label NOT LIKE '%State%' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $ppdist[$i]=date("l, F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $ppdist2[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $ppdist_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i++;
   }
   $ppstate=array(); $i=0;
   $ppstate2=array(); $ppstate_sm=array();
   $sql="SELECT * FROM pptourndates WHERE offdate='x' AND label LIKE '%State%' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $class=trim(preg_replace("/State/","",$row[label]));
      $ppstate[$i]=date("l, F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]))." $class";
      $ppstate2[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]))." $class";
      $ppstate_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]))." $class";
      $i++;
   }

echo $init_html;
echo GetHeaderJ($session);
echo "<br>";

if($save)
{
   //get offs in this district before Save was clicked
   $sql="SELECT offid FROM ppcontracts WHERE distid='$distid' ORDER BY id";
   $result=mysql_query($sql);
   $oldoffs=array(); $i=0;
   while($row=mysql_fetch_array($result))
   {
      $oldoffs[$i]=$row[0];
      $i++;
   }
   $oldct=$i;

   for($i=0;$i<count($assign);$i++)
   {
      if($assign[$i]!='~')
      {
         $sql="SELECT * FROM ppcontracts WHERE offid='$assign[$i]' AND distid='$distid'";
         $result=mysql_query($sql);
         if(mysql_num_rows($result)==0)	//insert
         {
	    $sql2="INSERT INTO ppcontracts (offid,distid) VALUES ('$assign[$i]','$distid')";
	    $result2=mysql_query($sql2);
         }
      }
   }
   //delete old ones that got reset
   for($i=0;$i<count($oldoffs);$i++)
   {
      $reset=1;	//assume reset
      for($j=0;$j<count($assign);$j++)
      {
	 if($assign[$j]==$oldoffs[$i])
	    $reset=0;
      }
      if($reset==1)
      {
	 $sql="DELETE FROM ppcontracts WHERE distid='$distid' AND offid='$oldoffs[$i]'";
	 $result=mysql_query($sql);
      }
   }
}

echo "<form method=post action=\"assignplay.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<table width=80%>";
echo "<caption><b>Play Production Judges Assignments:<br>";
echo "<select name=distid onchange=\"submit();\"><option value=''>Choose Class/Dist or State</option>";
echo "<option";
if($distid=='State') echo " selected";
echo ">State</option>";
$sql="SELECT id,class,dist FROM ppdistricts WHERE dist!='' ORDER BY class,dist";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value='$row[id]'";
   if($distid=="$row[id]")
      echo " selected";
   echo ">$row[class]-$row[dist]</option>";
}
echo "</select>";
echo "</caption>";
if($distid && $distid!='')
{
   if($distid!='State')
   {
   //show district info:
   $sql="SELECT * FROM ppdistricts WHERE id='$distid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $class=$row['class']; $dist=$row[dist];
   echo "<tr align=center><td>";
   echo "<table>";
   echo "<tr align=left><td colspan=2><b><u>District Information:</u></b></td></tr>";
   echo "<tr align=left><td><b>Host School:</b></td><td>$row[hostschool]</td></tr>";
   echo "<tr align=left><td><b>District Director:</b></td><td>$row[first] $row[last]</td></tr>";
   echo "<tr align=left><td><b>Site:</b></td><td>$row[site]</td></tr>";
   echo "<tr align=left><td><b>Date:</b></td><td>$row[distdate]</td></tr>";
   $curdistdate=$row[distdate];
   echo "<tr align=left><td><b>Time:</b></td><td>$row[time]</td></tr>";
   echo "<tr align=left><td><b>Schools:</b></td><td>$row[schools]</td></tr>";
   echo "</table>";
   echo "</td></tr>";
   }

   //judges' filter
   echo "<tr align=center><td>";
   echo "<table>";
   echo "<tr align=left><td colspan=2><b><u>Judges' Filter:</u> ";
   if($filter || $filteragain)
      echo "<font style=\"color:red\"><b>ON</b></font>";
   else
      echo "<font style=\"color:red\"><b>OFF</b></font>";
   echo "</td></tr>";
   echo "<tr align=left valign=top><td><b>Zones:</b></td>";
   echo "<td><select multiple size=4 name=zonech[]>";
   echo "<option";
   if($zonech[0]=="All Zones" || !$zonech[0]) echo " selected";
   echo ">All Zones</option>";
   $sql="SELECT zone FROM pp_zones ORDER BY zone";
   $result=mysql_query($sql);
   $z=0;
   while($row=mysql_fetch_array($result))
   {
      echo "<option";
      if($row[zone]==$zonech[$z])
      {
	 echo " selected"; $z++;
      }
      echo ">$row[zone]</option>";
   }
   echo "</select></td></tr>";
   echo "<tr valign=top align=left><td><b>Dates Available:</b></td>";
   echo "<td>";
   if($distid!='State')
   {
      for($i=0;$i<count($ppdist2);$i++)
      {
         $index=$i+1;
         $var="dist".$index;
         echo "<input type=checkbox name=$var value='x'";
         if($$var=='x') echo " checked";
         echo ">$ppdist2[$i]&nbsp;&nbsp;";
      }
   }
   else
   {
      for($i=0;$i<count($ppstate2);$i++)
      {
	 $index=$i+1;
	 $var="dist".$index;
	 echo "<input type=checkbox name=$var value='x'";
	 if($$var=='x') echo " checked";
	 echo ">$ppstate2[$i]&nbsp;&nbsp;";
      }
   }
   echo "</td></tr>";
   echo "<tr align=right><td colspan=2 align=right>";
   echo "<input type=submit name=filter value=\"Filter\"></td></tr>";
   echo "</table></td></tr>";

   //get list of judges meeting the above criteria that have also registered
   $sql="SELECT id,first,middle,last,city FROM judges ORDER BY last,first,middle";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      $inzone=1; $indates=1; $registered=1;
      if($filter || $filteragain)
      {
         if($zonech[0] && $zonech[0]!="All Zones")
         {
	    $inzone=0;
	    for($i=0;$i<count($zonech);$i++)
	    {
	       $zonech2[$i]=addslashes($zonech[$i]);
	       $row[city]=addslashes($row[city]);
               $sql2="SELECT * FROM pp_zones WHERE zone='$zonech2[$i]' AND (cities LIKE '$row[city],%' OR cities LIKE '%, $row[city],%' OR cities LIKE '%, $row[city]')";
	       $result2=mysql_query($sql2);
	       if(mysql_num_rows($result2)>0)
	       {
		  $inzone=1;
		  $i=count($zonech);
	       }
	    }
	 }
	 $indates=0;
	 $dateschecked=0;
	 $sql2="SELECT * FROM ppapply WHERE offid='$row[id]' AND (";
	 for($i=0;$i<count($ppdist2);$i++)
	 {
	    $index=$i+1; $var="dist".$index;
	    if($$var=='x')
	    {
	       $sql2.="$var='x' OR ";
	       $dateschecked=1;
	    }
	 }
	 $sql2=substr($sql2,0,strlen($sql2)-4);
	 $sql2.=")";
	 if($dateschecked==0) $indates=1;
	 else
	 {
	    $result2=mysql_query($sql2);
	    //echo mysql_error();
	    if(mysql_num_rows($result2)>0)
	       $indates=1;
	 }
      }
      //check if registered
      $sql2="SELECT t1.* FROM judges AS t1,sptest_results AS t2 WHERE t1.id=t2.offid AND t1.payment!='' AND ((t2.ppscore>=8 AND t2.play!='' AND t2.speech='') OR (t2.ppscore + t2.spscore >=48 AND ((t2.speech!='' AND t2.play!='') OR t2.combo!=''))) AND t1.meeting='x' AND t1.id='$row[id]'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)
	 $registered=0;
      //now check if submitted a play app
      $sql2="SELECT * FROM ppapply WHERE offid='$row[id]'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)
	 $registered=0;
      //echo "inzone: $inzone, indates: $indates, registered: $registered<br>";
      if($inzone==1 && $indates==1 && $registered==1)
      {
	 $offs[id][$ix]=$row[id];
	 $offs[city][$ix]=$row[city];
	 //get other assignments
	 $sql2="SELECT t2.class,t2.dist,t2.distdate FROM ppcontracts AS t1,ppdistricts AS t2 WHERE t1.distid=t2.id AND t1.offid='$row[id]' AND t2.distdate='$curdistdate'";
	 $result2=mysql_query($sql2);
	 $otherass="";
	 while($row2=mysql_fetch_array($result2))
	 {
	    $otherass.="$row2[class]-$row2[dist], ";
	 }
	 $otherass=substr($otherass,0,strlen($otherass)-2);
	 if(trim($otherass)!="")
	    $offs[otherass][$ix]=$otherass;
	 $ix++;
      }
   }

   //first show judges that have already been assigned to this class/dist:
   $sql="SELECT t1.offid FROM ppcontracts AS t1, ppdistricts AS t2 WHERE t1.distid=t2.id AND t2.id='$distid' ORDER BY t1.id";
   $result=mysql_query($sql);
   $curct=0;
   echo "<tr align=center><td>";
   while($row=mysql_fetch_array($result))
   {
      $num=$curct+1;
      echo "<b>$num)&nbsp;&nbsp;";
      echo "<select name=\"assign[$curct]\">";
      echo "<option>~</option>";
      $found=0;
      for($i=0;$i<count($offs[id]);$i++)
      {
	 echo "<option value=\"".$offs[id][$i]."\"";
	 if($offs[id][$i]==$row[offid]) 
	 {
	    echo " selected"; $found=1;
	 }
	 echo ">".GetJudgeName($offs[id][$i]).", ".$offs[city][$i];
	 if(trim($offs[otherass][$i])!="")
	    echo " (".$offs[otherass][$i].")";
	 echo "</option>";
      }
      if($found==0)	//official assigned not found in current officials' list (not in filtered list)
      {
	 echo "<option value=\"$row[offid]\" selected>".GetJudgeName($row[offid]);
	 $sql2="SELECT city FROM judges WHERE id='$row[offid]'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $city=$row2[0];
	 echo ", $city";
	 $sql2="SELECT t2.class,t2.dist,t2.distdate FROM ppcontracts AS t1,ppdistricts AS t2 WHERE t1.distid=t2.id AND t1.offid='$row[offid]' AND t2.distdate='$curdistdate'";
	 $result2=mysql_query($sql2);
	 $otherass="";
	 while($row2=mysql_fetch_array($result2))
	 {
	    $otherass.="$row2[class]-$row2[dist], ";
	 }
	 $otherass=substr($otherass,0,strlen($otherass)-2);
	 if(trim($otherass)!="")
	    echo " ($otherass)"; 
	 echo "</option>";
      }
      echo "</select><br>";
      $curct++;
   }
   $max=3;
   for($j=$curct;$j<$max;$j++)
   {
      $num=$j+1;
      echo "<b>$num)&nbsp;&nbsp;";
      echo "<select name=\"assign[$j]\">";
      echo "<option>~</option>";
      for($i=0;$i<count($offs[id]);$i++)
      {
	 echo "<option value=\"".$offs[id][$i]."\"";
	 echo ">".GetJudgeName($offs[id][$i]);
         if(trim($offs[otherass][$i])!="")
            echo " (".$offs[otherass][$i].")";
	 echo "</option>";
      }
      echo "</select><br>";
   }
   echo "</td></tr>";
   echo "<input type=hidden name=filteragain value=$filter>";
   echo "<tr align=center><td><br><br><input type=submit name=save value=\"Save\"></td></tr>";
   echo "</table>";
   echo "</form>";
}
echo $end_html;
?>
