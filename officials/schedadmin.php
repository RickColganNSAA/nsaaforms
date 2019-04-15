<?php
//Admin tool to search officials' schedules

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

echo $init_html;
echo GetHeader($session,"schedadmin");
echo "<br>";

if($submit2=="Search")
{
   //check if user chose a sport
   if($sport=="Choose Sport")
   {
      $error=1;
   }
   else
   {
      $error=0;
      if($zonestr && $zonestr!="")
      {
	 $zonech=split("[|]",$zonestr);
	 for($i=0;$i<count($zonech);$i++)
	 {
	    $zonech[$i]=trim($zonech[$i]);
	 }
      } 
      echo "<a class=small href=\"schedadmin.php?session=$session\">Return to Advanced Search</a><br><br>";
      echo "<table cellspacing=0 cellpadding=3 border=1 bordercolor=#000000>";
      echo "<caption><b>Officials' Schedules Advanced Search Results:</b><br>";
      echo "Sport: ".strtoupper($sport);
      if($month1!="" && $month2!="" && $day1!='' && $day2!='')
      {
	 echo ", Officiating Dates: $month1/$day1/$year1-$month2/$day2/$year2";
      }
      else if($month1!="" && $day1!='')
      {
	 echo ", Officiating On: $month1/$day1/$year1";
      }
      if($monthnot1!='' && $monthnot2!='' && $daynot1!='' && $daynot2!='')
      {
	 echo ", Not Officiating: $monthnot1/$daynot1/$yearnot1-$monthnot2/$daynot2/$yearnot2";
      }
      else if($monthnot1!="" && $daynot1!='')
      {
	 echo ", Not Officiating On: $monthnot1/$daynot1/$yearnot1";
      }
      if($last!="")
      {
	 echo ", Last Name starts w/ \"$last\"";
      }
      if($first!="")
      {
	 echo ", First Name starts w/ \"$first\"";
      }
      if($zonech[0]=="All Zones" || $zonestr=="All Zones")
      {
	 $zonestr="All Zones";
	 echo ", ALL Zones";
      }
      else if(count($zonech)>0)
      {
	 echo ", Zone(s): ";
	 $zonestr="";
	 $zonetbl=$sport."_zones";
	 if(ereg("bb",$sport)) $zonetbl="bb_zones";
	 for($i=0;$i<count($zonech);$i++)
	 {
	    $sql="SELECT zone FROM $zonetbl WHERE zone='$zonech[$i]'";
	    $result=mysql_query($sql);
	    $row=mysql_fetch_array($result);
	    $zonestr.="$row[0] | ";
	 }
	 $zonestr=substr($zonestr,0,strlen($zonestr)-3);
	 echo $zonestr; 
      }
      echo "</caption>";
      //create query based on search criteria
      $table=$sport."sched";
      $fromdate="$year1-$month1-$day1";
      $todate="$year2-$month2-$day2";
      $fromnotdate="$yearnot1-$monthnot1-$daynot1";
      $tonotdate="$yearnot2-$monthnot2-$daynot2";
      $sql="SELECT t1.*,t2.last,t2.first,t2.city FROM $table AS t1,officials AS t2 WHERE t1.offid=t2.id";
      $sqlNOT="";
      if(trim($last)!="")
      {
	 $last=trim($last);
	 $sql.=" AND t2.last LIKE '$last%'";
	 $sqlNOT.=" AND t2.last LIKE '$last%'";
	 if(trim($first)!="")
	 {
	    $first=trim($first);
	    $sql.=" AND t2.first LIKE '$first%'";
	    $sqlNOT.=" AND t2.first LIKE '$first%'";
	 }
      }
      else if(trim($first)!="")
      {
	 $first=trim($first);
	 $sql.=" AND t2.first LIKE '$first%'";
	 $sqlNOT.=" AND t2.first LIKE '$first%'";
      }
      if($month1!="" && $day1!='' && $month2!="" && $day2!='')
      {
	 $sql.=" AND t1.offdate>='$fromdate' AND t1.offdate<='$todate'";
	 $sqlNOT.=" AND t1.offdate>='$fromdate' AND t1.offdate<='$todate'";
      }
      else if($month1!='' && $day1!='')
      {
	 $sql.=" AND t1.offdate='$fromdate'";
	 $sqlNOT.=" AND t1.offdate='$fromdate'";
      }
      else if($monthnot1!='' && $daynot1!='' && $monthnot2!='' && $daynot2!='')
      {
	 $sql.=" AND t1.offdate>='$fromnotdate' AND t1.offdate<='$tonotdate'";
	 $sqlNOT.=" AND t1.offdate>='$fromnotdate' AND t1.offdate<='$tonotdate'";
      }
      else if($monthnot1!='' && $daynot1!='')
      {
	 $sql.=" AND t1.offdate='$fromnotdate'";
	 $sqlNOT.=" AND t1.offdate='$fromnotdate'";
      }
      $sql.=" ORDER BY";
      if($sort=="offdate" || $sort=="location")
         $sql.=" t1.$sort";
      else 
	 $sql.=" t2.last,t2.first,t1.offdate";
      //echo $sql;
      if($monthnot1=="" && $daynot1=="")
      {
         echo "<tr><th class=smaller><a class=small href=\"schedadmin.php?session=$session&sport=$sport&submit2=Search&last=$last&first=$first&month1=$month1&day1=$day1&year1=$year1&month2=$month2&day2=$day2&year2=$year2&zonestr=$zonestr&sort=last\">Official's Name</a><br>(Click for printable schedule)</th>";
	 echo "<th class=smaller><a class=small href=\"schedadmin.php?session=$session&sport=$sport&submit2=Search&last=$last&first=$first&month1=$month1&day1=$day1&year1=$year1&month2=$month2&day2=$day2&year2=$year2&zonestr=$zonestr&sort=zone\">Zone</a></th>";         
	 if($sport=='bb')
	    echo "<th class=smaller><a class=small href=\"schedadmin.php?session=$session&sport=$sport&submit2=Search&last=$last&first=$first&month1=$month1&day1=$day1&year1=$year1&month2=$month2&day2=$day2&year2=$year2&zonestr=$zonestr&sort=girls\">Boys/Girls</a></th>";
	 echo "<th class=smaller><a class=small href=\"schedadmin.php?session=$session&sport=$sport&submit2=Search&last=$last&first=$first&month1=$month1&day1=$day1&year1=$year1&month2=$month2&day2=$day2&year2=$year2&zonestr=$zonestr&sort=offdate\">Date</a></th>";
         echo "<th class=smaller><a class=small href=\"schedadmin.php?session=$session&sport=$sport&submit2=Search&last=$last&first=$first&month1=$month1&day1=$day1&year1=$year1&month2=$month2&day2=$day2&year2=$year2&zonestr=$zonestr&sort=location\">Location</a></th>";
         echo "<th class=smaller>Time</th>";
         echo "<th class=smaller>Schools</th>";
         if($sport=='so') echo "<th class=smaller>Position(s)</th>";
         echo "<th class=smaller>Other Officials</th>";
	 echo "<th class=smaller>Observations</th>";
         echo "</tr>";
      }
      else
      {
	 echo "<tr align=center>";
	 echo "<td><a class=small href=\"schedadmin.php?session=$session&sport=$sport&submit2=Search&last=$last&first=$first&monthnot1=$monthnot1&daynot1=$daynot1&yearnot1=$yearnot1&monthnot2=$monthnot2&daynot2=$daynot2&yearnot2=$yearnot2&zonestr=$zonestr&sort=last\">Official's Name</a><b><br>(Click for Schedule)</b></td>";
	 echo "<td><a class=small href=\"schedadmin.php?session=$session&sport=$sport&submit2=Search&last=$last&first=$first&monthnot1=$monthnot1&daynot1=$daynot1&yearnot1=$yearnot1&monthnot2=$monthnot2&daynot2=$daynot2&yearnot2=$yearnot2&zonestr=$zonestr&sort=zone\">Zone</a></td>";
	 echo "<td><a class=small href=\"schedadmin.php?session=$session&sport=$sport&submit2=Search&last=$last&first=$first&monthnot1=$monthnot1&daynot1=$daynot1&yearnot1=$yearnot1&monthnot2=$monthnot2&daynot2=$daynot2&yearnot2=$yearnot2&zonestr=$zonestr&sort=city\">City</a></td>";
	 echo "<td><b>Phone #(s)</b></td>";
	 echo "</tr>";
      }
      if($monthnot1!="" && $daynot1!='')	//dates NOT officiating selected
      {
	 $sql="SELECT DISTINCT t1.offid,t2.first,t2.last,t2.city,t2.homeph,t2.workph,t2.cellph FROM $table AS t1,officials AS t2 WHERE t1.offid=t2.id ORDER BY ";
	 if($sort!="zone" && $sort!="last" && $sort!="")
	    $sql.="t2.$sort,";
	 $sql.="t2.last,t2.first";
      }
      $result=mysql_query($sql);
//echo $sql;
      $curid='0';
      //arrays to store information and put in zone order later if necessary:
      $ix=0; $zones=array(); $names=array(); $offids=array(); $cities=array(); $phones=array();
      //echo mysql_num_rows($result);
      //echo "$sql<br>$sort<br>".mysql_error();
      while($row=mysql_fetch_array($result))
      {
	 if($monthnot1!='' && $daynot1!='')	//date NOT officiating selected
	 {
	    //if search criteria includes zone, check that official is in specified zone(s)
	    $inzone=1; //"in zone" if "All Zones" selected
	    //echo "Zones: ".$zonech[0];
	    if($zonech[0]!="" && $zonech[0]!="All Zones")
	    {
	       $inzone=0;	//assume NOT in zone
	       $zonetbl=$sport."_zones";
	       if(ereg("bb",$sport)) $zonetbl="bb_zones";
	       $row[city]=trim(addslashes($row[city]));
	       for($i=0;$i<count($zonech);$i++)
	       {
		  $sql3="SELECT id FROM $zonetbl WHERE (cities LIKE '$row[city],%' OR cities LIKE '%, $row[city],%' OR cities LIKE '%, $row[city]') AND zone='$zonech[$i]'";
		  $result3=mysql_query($sql3);
		  if(mysql_num_rows($result3)>0)	//in zone
		     $inzone=1;
	       }
	    }
	    if($inzone==1)
	    {
	    //echo "$row[first] $row[last] $row[offid]<br>";
	    $sqlNOT2="SELECT DISTINCT t1.offid FROM $table AS t1,officials AS t2 WHERE t1.offid=t2.id AND t1.offid=$row[offid]".$sqlNOT;
	    $resultNOT=mysql_query($sqlNOT2);
	    //echo "$sqlNOT2<br>";
	    if(mysql_num_rows($resultNOT)==0)
	    {
	       //if($row[offid]==3427) echo "$sqlNOT2<br>";
	       if($sort!="zone")
	       {
	          echo "<tr valign=top align=left>";
	          echo "<td><a href=\"schedule.php?sport=$sport&session=$session&givenoffid=$row[offid]\" target=new class=small>$row[first] $row[last]</a></td>";
	       }
	       else
	       {
		  $names[$ix]="$row[first] $row[last]";
		  $offids[$ix]=$row[offid];
	       }
	       $zonetable=$sport."_zones";
	       $row[city]=trim(addslashes($row[city]));
	       $sql2="SELECT zone FROM $zonetable WHERE (cities LIKE '$row[city],%' OR cities LIKE '%, $row[city]' OR cities LIKE '%, $row[city],%')";
	       $result2=mysql_query($sql2);
	       $row2=mysql_fetch_array($result2);
	       $curzone=$row2[zone];
	       if($sort!="zone")
	          echo "<td>$curzone</td><td>$row[city]</td>";
	       else
	       {
		  $zones[$ix]=$curzone;
		  $cities[$ix]=$row[city];
	       }
	      
	       if($sort!="zone")
	       {
	          echo "<td>";	//phone nums
	          if($row[homeph]!="")
		     echo "(".substr($row[homeph],0,3).")".substr($row[homeph],3,3)."-".substr($row[homeph],6,4)."<br>";
	          if($row[workph]!="")
		     echo "(".substr($row[workph],0,3).")".substr($row[workph],3,3)."-".substr($row[workph],6,4)."<br>";
	          if($row[cellph]!="")
		     echo "(".substr($row[cellph],0,3).")".substr($row[cellph],3,3)."-".substr($row[cellph],6,4);
	          echo "</td>";
	          echo "</tr>";
	       }
	       else
	       {
		  $phones[$ix]="";
		  if($row[homeph]!="") 
		     $phones[$ix].="(".substr($row[homeph],0,3).")".substr($row[homeph],3,3)."-".substr($row[homeph],6,4)."<br>";
		  if($row[workph]!="")
		     $phones[$ix].="(".substr($row[workph],0,3).")".substr($row[workph],3,3)."-".substr($row[workph],6,4)."<br>";
		  if($row[cellph]!="")
		     $phones[$ix].="(".substr($row[cellph],0,3).")".substr($row[cellph],3,3)."-".substr($row[cellph],6,4);
		  $ix++;
	       }
	    }
	    }//end if in zone
	 }//end if yearnot!=""
	 else	//dates they ARE officiating
	 {
	    if($row[offid]!=$curid)
	    {
	       $curid=$row[offid];
	       $curlast=$row[last];
	       $curfirst=$row[first];
	       $curcity=$row[city];
	    }
	    if($curlast!="")	//if this official fits search criteria
	    {
	       //if fb, check that this is crew chief
	       $chief=1;
	       if($sport=='fb')
	       {
		  $sql3="SELECT * FROM fbapply WHERE (offid='$row[offid]' OR chief='$row[offid]' OR referee='$row[offid]' OR umpire='$row[offid]' OR linesman='$row[offid]' OR linejudge='$row[offid]' OR backjudge='$row[offid]')";
		  $result3=mysql_query($sql3);
		  if(mysql_num_rows($result3)==0)
		     $chief=1;	//Means no application submitted yet, just show their results anyway
                  else
	   	  {
		     $row3=mysql_fetch_array($result3);
		     if($row3[chief]==$row[offid] || $row3[offid]==$row[offid]) $chief=1;
		     else $chief=0;	//APP SUBMITTED AND THIS PERSON IS NOT THE CHIEF
	          }
	       }
	       //get zone
	       $zonestbl=$sport."_zones";
	       if(ereg("bb",$sport)) $zonestbl="bb_zones";
	       $curcity2=addslashes(trim($curcity));
	       $sql2="SELECT zone FROM $zonestbl WHERE (cities LIKE '$curcity2,%' OR cities LIKE '%, $curcity2,%' OR cities LIKE '%, $curcity2')";
	       $result2=mysql_query($sql2);
	       $row2=mysql_fetch_array($result2);
	       $curzone=$row2[0];
	       $inzone=0;
	       if(count($zonech)>0 && $zonech[0]!="All Zones")
	       {
		  for($i=0;$i<count($zonech);$i++)
		  {
		     if($zonech[$i]==$curzone)
			$inzone=1;
		  }
	       }
	       else
		  $inzone=1;
	       if(($chief==1 || trim($first)!='' || trim($last)!='') && $inzone==1)
	       {
		  if($sort!="zone")
		  {
	             echo "<tr align=left>";
	             echo "<td><a href=\"schedule.php?sport=$sport&session=$session&givenoffid=$row[offid]\" target=new class=small>$curfirst $curlast</a></td>";
		     echo "<td>$curzone</td>";
                     if($sport=='bb')
                     {		
			if($row[girls]=='x') $boysgirls="GIRLS";	
			else $boysgirls="BOYS";
                        echo "<td align=center>$boysgirls</td>";
                     }
	             $temp=split("-",$row[offdate]);
	             $curdate=$temp[1]."/".$temp[2]."/".$temp[0];
	             echo "<td>$curdate</td>";
                     echo "<td>$row[location]</td>";
		     if($row[gametime]!="TBA")
		     {
	                $temp=split("-",$row[gametime]);
	                $curtime=$temp[0].":".$temp[1]."&nbsp;".$temp[2];
		     }
		     else
		     {
			$curtime="TBA";
		     }
	             echo "<td>$curtime</td>";
	             echo "<td>$row[schools]</td>";
	             if($sport=='so') echo "<td>$row[positions]</td>";
	             echo "<td>$row[otheroff]</td>";
		  }
		  else
		  {
		     $offids[$ix]=$row[offid];
		     $zones[$ix]=$curzone;
		     $temp=split("-",$row[offdate]);
		     $curdate=$temp[1]."/".$temp[2]."/".$temp[0];
		     $dates[$ix]=$curdate;
		     $locations[$ix]=$row[location];
		     if($row[gametime]!="TBA")
		     {
		        $temp=split("-",$row[gametime]);
		        $curtime=$temp[0].":".$temp[1]."&nbsp;".$temp[2];
		     }
		     else
		     {
			$curtime="TBA";
		     }
		     $times[$ix]=$curtime;
		     $schools[$ix]=$row[schools];
	             if($sport=='so') $positions[$ix]=$row[positions];
		     $otheroffs[$ix]=$row[otheroff];
		  }
	          //get submitted observations and fill out new ones
                  $schtable=$sport."sched";
	          $obstable=$sport."observe";
	          $sql2="SELECT t1.obsid,t1.gameid,t2.first,t2.last,t3.offdate,t1.dateeval FROM $obstable AS t1, observers AS t2,$schtable AS t3 WHERE t1.obsid=t2.id AND t1.gameid=t3.id AND t1.offid='$row[offid]' AND t1.gameid='$row[id]' ORDER by t1.id";
	          $result2=mysql_query($sql2);
		  if($sort!="zone")
	             echo "<td>";
	          $nsaa=0; 
		  $observations[$ix]="";
	          while($row2=mysql_fetch_array($result2))
	          {
                     $temp=split("-",$row2[offdate]);
		     if($sort!="zone")
		     {
		         echo "<a class=small href=\"#\" onclick=\"window.open('".$sport."observe.php?session=$session&offid=$row[offid]&obsid=$row2[0]&gameid=$row2[1]','$sportobserve','menubar=no,scrollbars=yes,resizable=yes');\">$temp[1]/$temp[2] ($row2[first] $row2[last])";
		         if($row2[dateeval]!='')	
		            echo " (Submitted)";
		         else
		            echo " (Saved)";
		         echo "</a><br>";
		     }
		     else
		     {
			 $obervations[$ix].="<a class=small href=\"#\" onclick=\"window.open('".$sport."observe.php?session=$session&offid=$row[offid]&obsid=$row2[0]&gameid=$row2[1]','$sportobserve','menubar=no,scrollbars=yes,resizable=yes');\">$temp[1]/$temp[2] ($row2[first] $row2[last])";
			 if($row2[dateeval]!='')
			    $observations[$ix].=" (Submitted)";
			 else
			    $observations[$ix].=" (Saved)";
			 $observations[$ix].="</a><br>";
		     }
		     if($obsid==1)
		        $nsaa=1;
	          }
	          if(mysql_num_rows($result2)==0 || $nsaa==0)	//allow NSAA to fill out new observation
		  {
		     if($sort!="zone")
	                echo "<a class=small href=\"#\" onclick=\"window.open('".$sport."observe.php?session=$session&gameid=$row[id]&offid=$row[offid]&obsid=1','$sport_observe','menubar=no,scrollbars=yes,resizable=yes');\">Fill Out Evaluation</a>";
		     else
			$observations[$ix].="<a class=small href=\"#\" onclick=\"window.open('".$sport."observe.php?session=$session&gameid=$row[id]&offid=$row[offid]&obsid=1','$sportobserve','menubar=no,scrollbars=yes,resizable=yes');\">Fill Out Evaluation</a>";
		  }
		  if($sort!="zone")
	             echo "</td>";
		  $ix++;
	       }//end if chief=1
	    }
         }
      }
      if($monthnot1!='' && $daynot1!='' && $sort=="zone")	//output DATES NOT OFFICIATING in search results in zone order
      {
	 $zonetable=$sport."_zones";
	 $sql="SELECT zone,cities FROM $zonetable ORDER BY zone";
	 $result=mysql_query($sql);
	 while($row=mysql_fetch_array($result))
	 {
	    for($i=0;$i<count($zones);$i++)
	    {
	       if($zones[$i]==$row[zone])
	       {
		  echo "<tr align=left>";
		  echo "<td><a class=small target=new href=\"schedule.php?session=$session&sport=$sport&givenoffid=$offids[$i]\">$names[$i]</a></td>";
		  echo "<td>$zones[$i]</td>";
		  echo "<td>$cities[$i]</td>";
		  echo "<td>$phones[$i]</td>";
		  echo "</tr>";
	       }
	    }
	 }
      }
      else if($sort=="zone")	//output DATES OFFICIATING in zone order
      {
	 $zonetable=$sport."_zones";
	 $sql="SELECT zone,cities FROM $zonetable ORDER BY zone";
	 $result=mysql_query($sql);
	 while($row=mysql_fetch_array($result))
	 {
	    for($i=0;$i<count($zones);$i++)
	    {
	       //echo "<tr><td>$zones[$i]</td></tr>";
	       if($zones[$i]==$row[zone])
	       {
		  echo "<tr align=left>";
		  echo "<td><a class=small target=new href=\"schedule.php?session=$session&sport=$sport&givenoffid=$offids[$i]\">".GetOffName($offids[$i])."</a></td>";
		  echo "<td>$zones[$i]</td>";
		  echo "<td>$dates[$i]</td>";
		  echo "<td>$locations[$i]</td>";
		  echo "<td>$times[$i]</td>";
		  echo "<td>$schools[$i]</td>";
	    	  if($sport=='so')
		     echo "<td>$positions[$i]</td>";
		  echo "<td>$otheroffs[$i]</td>";
		  echo "<td>$observations[$i]</td>";
		  echo "</tr>";
	       }
	    }
	 }
      }
      echo "</table>";
      echo "<br><a class=small href=\"welcome.php?session=$session\">Home</a>&nbsp;&nbsp;";
      echo "<a class=small href=\"schedadmin.php?session=$session\">Advanced Search</a>";
      exit();
   }
}
echo "<form method=post action=\"schedadmin.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table width=70% cellspacing=3 cellpadding=3><caption><b>Officials' Schedules Advanced Search:</b></caption>";
echo "<tr align=left><td colspan=2><i>To search a range of dates, enter the beginning date and end date.  Make sure to enter a year as well.  To search one day, enter that day as both the \"from\" and \"to\" dates.  You MUST choose a sport in order to search the schedules.  Search for a specific official by entering all or part of his or her name.</i><hr></td></tr>";
if($error==1)
{
   //no sport given
   echo "<tr align=left><td colspan=2><font style=\"color:red\"><b>You must choose a sport.</b></font></td></tr>";
}
echo "<tr align=left><th align=left width=150>Sport: (required)</th>";
echo "<td><select name=sport onchange=submit()><option>Choose Sport</option>";
$sql0="SHOW TABLES LIKE '%sched'";
$result0=mysql_query($sql0);
while($row0=mysql_fetch_array($result0))
{
   $temp=split("sched",$row0[0]);
   echo "<option value=\"$temp[0]\"";
   if($temp[0]==$sport) echo " selected";
   echo ">".GetSportName($temp[0])."</option>";
}
echo "</select></td></tr>";
if($sport!="Choose Sport" && $sport)
{
   echo "<tr align=left><th align=left>Zone(s):</th>";
   echo "<td><select multiple name=zonech[] size=4><option selected>All Zones</option>";
   $zonestbl=$sport."_zones";
   if(ereg("bb",$sport)) $zonestbl="bb_zones";
   $sql="SELECT zone,cities FROM $zonestbl ORDER BY zone";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option>$row[zone]</option>";
   }
   echo "</select></td></tr>";
}
echo "<tr align=left><th align=left>Date(s) Officiating:</th>";
echo "<td>from&nbsp;";
echo "<select class=small name=month1><option value=''>MM</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $x="0".$i;
   else $x=$i;
   echo "<option>$x</option>";
}
echo "</select>&nbsp;/&nbsp;";
echo "<select class=small name=day1><option value=''>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $x="0".$i;
   else $x=$i;
   echo "<option>$x</option>";
}
echo "</select>&nbsp;/&nbsp;";
echo "<input type=text class=tiny size=4 maxlength=4 value=\"".date("Y")."\" name=year1>&nbsp;to&nbsp;";
echo "<select class=small name=month2><option value=''>MM</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $x="0".$i;
   else $x=$i;
   echo "<option>$x</option>";
}
echo "</select>&nbsp;/&nbsp;";
echo "<select class=small name=day2><option value=''>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $x="0".$i;
   else $x=$i;
   echo "<option>$x</option>";
}  
echo "</select>&nbsp;/&nbsp;";
echo "<input type=text class=tiny size=4 maxlength=4 value=\"".date("Y")."\" name=year2></td></tr>";
echo "<tr align=left><th align=left>Date(s) NOT Officiating:</th>";
echo "<td>from&nbsp;";
echo "<select class=small name=monthnot1><option value=''>MM</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $x="0".$i;
   else $x=$i;
   echo "<option>$x</option>";
}
echo "</select>&nbsp;/&nbsp;";
echo "<select class=small name=daynot1><option value=''>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $x="0".$i;
   else $x=$i;
   echo "<option>$x</option>";
}
echo "</select>&nbsp;/&nbsp;";
echo "<input type=text class=tiny size=4 maxlength=4 value=\"".date("Y")."\" name=yearnot1>&nbsp;to&nbsp;";
echo "<select class=small name=monthnot2><option value=''>MM</option>";
for($i=1;$i<=12;$i++)
{
   if($i<10) $x="0".$i;
   else $x=$i;
   echo "<option>$x</option>";
}
echo "</select>&nbsp;/&nbsp;";
echo "<select class=small name=daynot2><option value=''>DD</option>";
for($i=1;$i<=31;$i++)
{
   if($i<10) $x="0".$i;
   else $x=$i;
   echo "<option>$x</option>";
}
echo "</select>&nbsp;/&nbsp;";
echo "<input type=text class=tiny size=4 maxlength=4 value=\"".date("Y")."\" name=yearnot2></td></tr>";
echo "<tr align=left><th align=left>Official's Name:</th>";
echo "<td>last:&nbsp;<input type=text size=20 class=tiny name=last>,&nbsp;";
echo "first:&nbsp;<input type=text size=15 class=tiny name=first></td></tr>";
echo "<tr align=center><td colspan=2><input type=submit name=submit2 value=\"Search\"></td></tr>";
echo "</table>";
echo "</form>";

echo $end_html;
?>
