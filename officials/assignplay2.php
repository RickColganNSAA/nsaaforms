<?php
//play production district assignments
//Nov 18, 2005: only functional for state right now:
require 'functions.php';
require 'variables.php';

if(preg_match("/state/",$sport)) { $sport=substr($sport,0,2); $distid="State"; }

$districts=$sport."districts";
$contracts=$sport."contracts";

$sportname=GetSportName($sport);

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}

echo $init_html;
echo GetHeaderJ($session,"jcontractadmin");
echo "<br>";

if($sport=='sp' && $distid=='State' && $deleteoff && $deleteoff!='' && $deleteoff>0)	
{
   //delete selected offical from State Speech
   //get state speech distid
   $sql="SELECT id FROM $districts WHERE type='State' AND id='$statedate'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   //delete contract
   $sql2="DELETE FROM $contracts WHERE distid='$row[0]' AND offid='$deleteoff'";
   $result2=mysql_query($sql2);
   $delete=1;
}
else 
   $delete=0;

if($save || $hiddensave)
{
   if(!$statedate || $statedate!='Room Assignments')
   {
      //REGULAR DISTRICT/STATE ASSIGNMENTS:
      if(!$newstart || $newstart=='') $newstart=0;	//default is to start at 0
      if($sport=='sp' && $distid=='State')
      {
         //State speech: get distid (there is only one)
         $sql="SELECT id FROM $districts WHERE type='State' AND id='$statedate'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         $curdistid=$row[0]; 
      }
      for($i=$newstart;$i<$total;$i++)
      {
         $var1="assign".$i; $var2="offname".$i; $var3="oldassign".$i;
         if($$var2!="[Click to Pick Judge]")
         {
	    if($distid!='State')
	    {
               $sql="SELECT * FROM $contracts WHERE offid='".$$var1."' AND distid='$distid'";
               $result=mysql_query($sql);
               if(mysql_num_rows($result)==0)	//insert
               {
	          $sql2="INSERT INTO $contracts (offid,distid) VALUES ('".$$var1."','$distid')";
	          $result2=mysql_query($sql2);
               }
	    }
	    elseif($sport=='pp')	//State Play
	    {
               $sql="SELECT id FROM $districts WHERE class='$curclass[$i]' AND district=''";
               $result=mysql_query($sql);
               $row=mysql_fetch_array($result);
               $curdistid=$row[0];
	       $sql="SELECT * FROM $contracts WHERE offid='".$$var1."' AND distid='$curdistid'";
	       $result=mysql_query($sql);
	       if(mysql_num_rows($result)==0)
	       {
	          $sql2="INSERT INTO $contracts (offid,distid) VALUES ('".$$var1."','$curdistid')";
	          $result2=mysql_query($sql2);
	       }
	    }
	    else			//State Speech
	    {
	       $sql="SELECT * FROM $contracts WHERE offid='".$$var1."' AND distid='$curdistid'";
	       $result=mysql_query($sql);
	       if(mysql_num_rows($result)==0)
	       {
	          $sql2="INSERT INTO $contracts (offid,distid) VALUES ('".$$var1."','$curdistid')";
	          $result2=mysql_query($sql2);
	       }
	    }
         }
	 else if($sport=='pp' && $distid=="State" && $$var3!='0')
	 {
	    $sql="SELECT id FROM $districts WHERE class='$curclass[$i]' AND district=''";
	    $result=mysql_query($sql);
	    $row=mysql_fetch_array($result);
	    $curdistid=$row[0];
	    $sql2="DELETE FROM $contracts WHERE offid='".$$var3."' AND distid='$curdistid'";
	    $result2=mysql_query($sql2);
         }
      }
      if($distid!="State")
      {
         $curdistid=$distid;
         $sql2="SELECT offid FROM $contracts WHERE distid='$curdistid' AND offid!='0'";
         $result2=mysql_query($sql2);
         while($row2=mysql_fetch_array($result2))
         {
            $assigned=0;
            for($i=0;$i<$total;$i++)
            {
               $var1="assign".$i;
               if($$var1==$row2[0]) $assigned=1;
            }
            if($assigned==0)
            {
               $sql3="DELETE FROM $contracts WHERE offid='$row2[0]' AND distid='$curdistid'";
               $result3=mysql_query($sql3);
	    }
         }
      }
   }//end if not ROOM ASSIGNMENTS
   else	//ROOM ASSIGNMENTS
   {
      if($edit!=1)
      {
         //reset current assignments for this class, insert what's entered on screen
         $sql="SELECT t1.id FROM spstateassign AS t1, spstaterooms AS t2, spstaterounds AS t3 WHERE t1.roomid=t2.id AND t2.roundid=t3.id AND t3.class='$classch'";
         $result=mysql_query($sql);
         while($row=mysql_fetch_array($result))
         {
	    $sql2="DELETE FROM spstateassign WHERE id='$row[id]'";
            $result2=mysql_query($sql2);
         }
         for($i=0;$i<$total;$i++)
         {
   	    $var1="assign".$i;
            if($$var1!='0')
	    {
               $sql="INSERT INTO spstateassign (roomid,offid) VALUES ('$roomid[$i]','".$$var1."')";
	       $result=mysql_query($sql);
	       //echo "$sql<br>".mysql_error()."<br>";
	    }
         }
      }
      else	//room list saved
      {
	 for($i=0;$i<count($prefs_sm);$i++)
         {
            $sql="SELECT * FROM spstaterounds WHERE event='$prefs_lg[$i]' AND class='$classch' ORDER BY round";
	    $result=mysql_query($sql);
	    while($row=mysql_fetch_array($result))
	    {
	       $roundid=$row[id];
	       $curdate=$date[$i][$roundid];
	       $curtime=$hour[$i][$roundid].":".$min[$i][$roundid]." ".$ampm[$i][$roundid];	
	       $sql2="UPDATE spstaterounds SET rounddate='$curdate',time='$curtime' WHERE id='$roundid'";
	       $result2=mysql_query($sql2);
	       //echo "$sql2  ".mysql_error()."<br>";
	       for($j=0;$j<count($roomid[$i][$roundid]);$j++)
	       {
	          $curroomid=$roomid[$i][$roundid][$j];
	          $curroom=$room[$i][$roundid][$j];
	          $sql2="UPDATE spstaterooms SET room='$curroom' WHERE id='$curroomid'";
	    	  $result2=mysql_query($sql2);
	 	  //echo "$sql2  ".mysql_error()."<br>";
	       }
	    }
	 }      
      }
   }
}

echo "<form name=assignform method=post action=\"assignplay2.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=edit value=\"$edit\">";
echo "<input type=hidden name=\"hiddensave\" id=\"hiddensave\">";
echo "<input type=hidden name=distdates value=\"$distdates\">";
echo "<input type=hidden name=statedates value=\"$statedates\">";
echo "<input type=hidden name=zones value=\"$zones\">";
echo "<table width=90%>";
echo "<caption>";
if($sport)
{
   if($distid=="State") $sport2=substr($sport,0,2)."-state";
   else $sport2=substr($sport,0,2);
   $sport=substr($sport,0,2);
   echo "<a class=small href=\"assignreportplay.php?session=$session&sport=$sport2\">$sportname Assignments</a>&nbsp;&nbsp;";
   echo "<a class=small href=\"playcontracts.php?session=$session&sport=$sport2\">Submitted $sportname Contracts</a>&nbsp;&nbsp;";
   if($distid!="State")
      echo "<a class=small href=\"hostreport.php?session=$session&sport=$sport2\">$sportname Hosts</a><br><br>";
   else echo "<br><br>";
}

echo "<select name=sport onchange=\"distdates.value='';statedates.value='';zones.value='';submit();\"><option value=''>Activity</option>";
echo "<option value='pp'";
if($sport=='pp') echo " selected";
echo ">Play</option><option value='sp'";
if($sport=='sp') echo " selected";
echo ">Speech</option></select>&nbsp;&nbsp;";
echo "<select name=distid onchange=\"distdates.value='';statedates.value='';zones.value='';submit();\"><option value=''>Class/Dist or State</option>";
if($sport && $sport!='')
{
   echo "<option";
   if($distid=='State') echo " selected";
   echo ">State</option>";
   $sql="SELECT id,class,district FROM $districts WHERE type!='State' ORDER BY class,district";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value='$row[id]'";
      if($distid=="$row[id]")
         echo " selected";
      echo ">$row[class]-$row[district]</option>";
   }
}
echo "</select>";
if($sport=='sp' && $distid=='State')
{
   //get statedates
      $sql="SELECT id,dates FROM $districts WHERE type='State' ORDER BY dates";
      $result=mysql_query($sql);
   echo "&nbsp;&nbsp;<select name=\"statedate\" onchange=\"submit()\"><option value=''>Select DAY or Room Assignments</option>";
   while($row=mysql_fetch_array($result))
   {
      $date=split("-",$row[dates]);
      $day=date("l",mktime(0,0,0,$date[1],$date[2],$date[0]));
      echo "<option value='$row[id]'";
      if($statedate==$row[id]) echo " selected";
      echo ">$day $date[1]/$date[2]</option>";
   }
   echo "<option";
   if($statedate=="Room Assignments") echo " selected";
   echo ">Room Assignments</option>";
   echo "</select>";
   if($statedate=='Room Assignments')
   {
      echo "&nbsp;&nbsp;<select name=classch onchange=\"submit()\">";
      echo "<option value=''>Class</option>";
      for($i=0;$i<count($classes);$i++)
      {
   	 echo "<option";
	 if($classch==$classes[$i]) echo " selected";
	 echo ">$classes[$i]</option>";
      } 
      echo "</select>";
   }
}
if($sport && $sport!='')
   echo "<h3>$sportname Judges Assignments:</h3>";
if($sport=='sp' && $statedate=='Room Assignments' && $classch!='' && $edit!=1)
   echo "<a class=small href=\"assignplay2.php?session=$session&sport=sp&distid=State&statedate=Room Assignments&classch=$classch&edit=1\">Edit Class $classch Round & Room Information</a>";
else if($sport=='sp' && $statedate=='Room Assignments' && $classch!='')
   echo "<a class=small href=\"assignplay2.php?session=$session&sport=sp&distid=State&statedate=Room Assignments&classch=$classch\">Make Room Assignments for Class $classch</a>";
else 
   echo "<a class=small href=\"hostbyhost.php?sport=$sport&session=$session\">Edit Host Information (Dates, Sites, Times)</a>";
if($save || $hiddensave)
{
   if($edit==1)
      echo "<br><font style=\"color:red;font-size:8pt;\"><b>The Rounds & Room Info on this screen has been saved.</b></font>";
   else
      echo "<br><font style=\"color:red;font-size:8pt;\"><b>The assignments on this screen have been saved.</b></font>";
}
else if($posted=='yes')
   echo "<br><font style=\"color:red;font-size:8pt;\"><b>The assignments on this screen have been posted.</b></font>";
else if($delete==1)
   echo "<br><font style=\"color:red;font-size:8pt;\"><b>".GetJudgeName($deleteoff)."'s State Speech assignment has been removed.</b></font>";
echo "</caption>";

if($distid && $distid!='' && $statedate!='Room Assignments')
{
   if($distid!='State')
   {
      //show district info:
      $sql="SELECT * FROM $districts WHERE id='$distid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $class=$row['class']; $dist=$row[district];
      $distname="$class-$dist";
      echo "<tr align=center><td>";
      echo "<table>";
      echo "<tr align=left><td colspan=2><b><u>District $distname Information:</u></b></td></tr>";
      echo "<tr align=left><td><b>Host School:</b></td><td>$row[hostschool]</td></tr>";
      echo "<tr align=left><td><b>District Director:</b></td><td>$row[director] <<a class=small href=\"mailto:$row[email]\">$row[email]</a>></td></tr>";
      echo "<tr align=left><td><b>Site:</b></td><td>$row[site]</td></tr>";
      $date=split("-",$row[dates]);
      $dates=date("F d, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
      echo "<tr align=left><td><b>Date:</b></td><td>$dates</td></tr>";
      $curdates=$row[dates];
      echo "<tr align=left><td><b>Time:</b></td><td>$row[time]</td></tr>";
      echo "<tr align=left><td><b>Schools:</b></td><td>$row[schools]</td></tr>";
      echo "</table>";
      echo "</td></tr>";
   }
   else
   {
      $distname="State";
      $sql="SELECT id FROM $districts WHERE type='State' AND id='$statedate'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $stateid=$row[id];
   }

   echo "<tr align=center><td colspan=2><br>";
   echo "<a class=small href=\"assignpost.php?distid=$distid&session=$session&sport=$sport&return=assignplay2\">Post $distname Contracts</a><br><br>";
   echo "</td></tr>";

   if($distid=='State' && $sport=='pp')
   {
      $classes=array("A","B","C1","C2","D1","D2");
      $ix=0;
      for($c=0;$c<count($classes);$c++)
      {
	 if($c%2==0)
	    echo "<tr align=center>";
	 echo "<td><table>";
         $sql2="SELECT * FROM $districts WHERE class='$classes[$c]' AND type='State'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $date=split("-",$row2[dates]);
	 echo "<tr align=left><td><b>Class $classes[$c]</b><br>".date("l, F j",mktime(0,0,0,$date[1],$date[2],$date[0])).", Report at $row2[time]<br>$row2[site]</td></tr>";
	 $sql="SELECT t1.offid FROM $contracts AS t1,$districts AS t2 WHERE t1.distid=t2.id AND t2.class='$classes[$c]' AND t2.district=''";
	 $result=mysql_query($sql);
	 $curct=0;
	 echo "<tr align=left><td>";
	 while($row=mysql_fetch_array($result))
	 {
	    echo "<input type=hidden name=\"curclass[$ix]\" value=\"$classes[$c]\">";
	    echo "<input type=hidden name=\"assign$ix\" value=\"$row[0]\">";
	    echo "<input type=hidden name=\"oldassign$ix\" value=\"$row[0]\">";
	    echo "<input type=text class=tiny size=25 name=\"offname$ix\" value=\"".GetJudgeName($row[0])."\" onClick=\"window.open('judgespick.php?distid=$distid&sport=$sport&session=$session&zones=$zones&distdates=$distdates&statedates=$statedates&ix=$ix','judgespick','resizable=yes,scrollbars=yes');\"><br>";
	    $ix++; $curct++;
	 }
	 while($curct<3)
	 {
	    echo "<input type=hidden name=\"curclass[$ix]\" value=\"$classes[$c]\">";
	    echo "<input type=hidden name=\"assign$ix\">";
	    echo "<input type=hidden name=\"oldassign$ix\" value='0'>";
	    echo "<input type=text class=tiny size=25 name=\"offname$ix\" value=\"[Click to Pick Judge]\" onClick=\"window.open('judgespick.php?distid=$distid&sport=$sport&session=$session&zones=$zones&distdates=$distdates&statedates=$statedates&ix=$ix','judgespick','resizable=yes,scrollbars=yes');\"><br>";
	    $ix++; $curct++;
	 }
	 echo "</td></tr></table></td>";
	 if(($c+1)%2==0) echo "</tr>";
      }
      $total=$ix;
   } //end if PP State
   else if($sport=='sp' && $distid=="State")
   {
      if($statedate)
      {
      //65 slots for each day (Th & Fri) for State Speech
      //first show saved assignments for state and selected day, with link to delete
      $sql="SELECT t1.offid,t3.first,t3.middle,t3.last FROM $contracts AS t1,$districts AS t2,judges AS t3 WHERE t1.offid=t3.id AND t1.distid=t2.id AND t2.id='$stateid' ORDER BY t3.last, t3.first, t3.middle";
      $result=mysql_query($sql);
      $curct=mysql_num_rows($result);
      echo "<tr align=center><td><table>";
      $percol=$curct/5; $curcolct=0;
      echo "<tr valign=top align=left>";	//one column with $curct/5 names per column
      $ix=0;
      while($row=mysql_fetch_array($result))
      {
	 if($curcolct==0) echo "<td>";
	 $num=$ix+1;
         echo "<b>$num)&nbsp;</b>";
         echo "[<a href=\"assignplay2.php?session=$session&sport=$sport&distid=$distid&statedate=$statedate&zones=$zones&distdates=$distdates&statedates=$statedates&deleteoff=$row[offid]\">X</a>]&nbsp;";
         echo GetJudgeName($row[offid])."<br>";
         $curcolct++; $ix++;
         if($percol<=$curcolct)
         {
	    $curcolct=0; echo "</td>";
	 }
      }
      echo "</tr></table></td></tr>";
      //now show the rest of the 100 assignments slots as textboxes to click on
      $left=65-$ix;
      $newstart=$ix;
      $percol=$left/2; $curcolct=0;
      echo "<tr align=center><td><table><tr align=left valign=top>";
      while($ix<65)
      {
	 if($curcolct==0) echo "<td>";
         $num=$ix+1;
	 echo "<input type=hidden name=\"assign$ix\">";
	 echo "<b>$num)</b>&nbsp;<input type=text class=tiny size=25 name=\"offname$ix\" value=\"[Click to Pick Judge]\" onClick=\"window.open('judgespick.php?distid=$distid&stateday=$statedate&sport=$sport&session=$session&zones=$zones&distdates=$distdates&statedates=$statedates&ix=$ix','judgespick','resizable=yes,scrollbars=yes');\"><br>";
         $ix++; $curcolct++;
         if($percol<=$curcolct)
         {
	    $curcolct=0; echo "</td>";
	 }
      }
      echo "</tr></table></td></tr>";
      $total=65;
      }//end if state day chosen
      else $dontshowsave=1;
   } //end if State Speech
   else		//NON-STATE Speech & Play
   {
      //first show judges that have already been assigned to this class/dist:
      $sql="SELECT t1.offid FROM $contracts AS t1, $districts AS t2 WHERE t1.distid=t2.id AND t2.id='$distid' ORDER BY t1.id";
      $result=mysql_query($sql);
      $curct=0;
      echo "<tr align=center><td>";
      while($row=mysql_fetch_array($result))
      {
         $num=$curct+1;
         echo "<b>$num)&nbsp;&nbsp;";
         echo "<input type=hidden name=\"assign$curct\" value=\"$row[0]\">";
         echo "<input type=text class=tiny size=25 name=\"offname$curct\" value=\"".GetJudgeName($row[0])."\" onClick=\"window.open('judgespick.php?distid=$distid&sport=$sport&session=$session&zones=$zones&distdates=$distdates&statedates=$statedates&ix=$curct','judgespick','resizable=yes,scrollbars=yes');\">";
         echo "<br>";
         $curct++;
      }
      while($curct<15)
      {
         $num=$curct+1;
         echo "<b>$num)&nbsp;&nbsp;";
	 echo "<input type=hidden name=\"assign$curct\">";
	 echo "<input type=text class=tiny size=25 name=\"offname$curct\" value=\"[Click to Pick Judge]\" onClick=\"window.open('judgespick.php?distid=$distid&sport=$sport&session=$session&zones=$zones&distdates=$distdates&statedates=$statedates&ix=$curct','judgespick','resizable=yes,scrollbars=yes');\"><br>";
         $curct++;
      }
      echo "</td></tr>";
      $total=$curct;
   }//end if not State
   echo "<input type=hidden name=total value=$total>";
   echo "<input type=hidden name=newstart value=$newstart>";  //where state speech starts with new assignments
   echo "<input type=hidden name=filteragain value=$filter>";
   if(!$dontshowsave)
      echo "<tr align=center><td colspan=2><br><input type=submit name=save value=\"Save\"></td></tr>";
   echo "</table>";
   echo "</form>";
}//end if NOT STATE ROOM ASSIGNMENTS
else if($statedate=='Room Assignments' && $classch!='')		//STATE ROOM ASSIGNMENTS
{
   $ix=0;
   for($i=0;$i<count($prefs_sm);$i++)
   {
      if($i%3==0) echo "<tr align=left valign=top>";
      echo "<td><br><table>";
      echo "<tr align=left><td colspan=2><b><u>$prefs_lg[$i]:</u></b></td></tr>";
      $sql="SELECT * FROM spstaterounds WHERE event='$prefs_lg[$i]' AND class='$classch' ORDER BY round";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
	 $roundid=$row[id];
         if($row[round]<3)
         {
	    $date=split("-",$row[rounddate]);
            if($edit!=1)
	       echo "<tr align=left><td colspan=2><b>Round $row[round]:</b> $date[1]/$date[2] @ $row[time]</td></tr>";
	    else
 	    {
	       echo "<tr align=left><td><b>ROUND $row[round]:<br>Date:</b>";
	       $sql2="SELECT dates FROM spdistricts WHERE type='State' ORDER BY dates";
	       $result2=mysql_query($sql2);
	       while($row2=mysql_fetch_array($result2))
	       {
	          $temp=split("-",$row2[0]);
		  echo "<input type=radio name=\"date[$i][$roundid]\" value=\"$row2[0]\"";
	          if($row2[0]==$row[rounddate]) echo " checked";
	          echo ">$temp[1]/$temp[2]&nbsp;&nbsp;";
	       }
	       echo "</td></tr>";
	       $temp=split("[: ]",$row[time]);
	       echo "<tr align=left><td><b>Time:</b> <input type=text size=2 class=tiny name=\"hour[$i][$roundid]\" value=\"$temp[0]\"> : <input type=text size=2 class=tiny name=\"min[$i][$roundid]\" value=\"$temp[1]\"> <select name=\"ampm[$i][$roundid]\">";
	       echo "<option";
	       if($temp[2]=='AM') echo " selected";
	       echo ">AM</option><option";
	       if($temp[2]=='PM') echo " selected";
	       echo ">PM</option></select></td></tr>";
	    }
	    $sql2="SELECT * FROM spstaterooms WHERE roundid='$row[id]' ORDER BY section";
	    $result2=mysql_query($sql2); 
	    $ix2=0;
	    while($row2=mysql_fetch_array($result2))
	    {
	       if($edit!=1)
	       {
	          echo "<tr align=left><td>$row2[section]) Rm $row2[room]:&nbsp;";
	          $sql3="SELECT offid FROM spstateassign WHERE roomid='$row2[id]'";
	          $result3=mysql_query($sql3);
                  $row3=mysql_fetch_array($result3);
	          if(mysql_num_rows($result3)>0)	//spot filled, show judge
	          {
                     echo "<input type=hidden name=\"roomid[$ix]\" value=\"$row2[id]\">";
                     echo "<input type=hidden name=\"assign$ix\" value=\"$row3[0]\">";
                     echo "<input type=text class=tiny size=20 name=\"offname$ix\" value=\"".GetJudgeName($row3[0])."\" onClick=\"window.open('judgespick3.php?session=$session&ix=$ix&roomid=$row2[id]','judgespick','resizable=yes,scrollbars=yes');\">";
		     echo "</td></tr>";
                  }
	          else
	          {
		     echo "<input type=hidden name=\"roomid[$ix]\" value=\"$row2[id]\">";
		     echo "<input type=hidden name=\"assign$ix\" value='0'>";
		     echo "<input type=text class=tiny size=20 name=\"offname$ix\" value=\"[Click to Pick Judge]\" onClick=\"window.open('judgespick3.php?session=$session&ix=$ix&roomid=$row2[id]','judgespick','resizable=yes,scrollbars=yes');\">";
		     echo "</td></trr>";
	          }
	       }
	       else	//allow user to edit rooms
	       {
	          echo "<input type=hidden name=\"roomid[$i][$roundid][$ix2]\" value=\"$row2[id]\">";
		  echo "<tr align=left><td>$row2[section]) Room: <input type=text class=tiny size=8 name=\"room[$i][$roundid][$ix2]\" value=\"$row2[room]\"></td></tr>";
	       }
               $ix++; $ix2++;
	    }
	 }
	 else	//FINALS
	 {
            if($edit!=1)
	    {
	       $date=split("-",$row[rounddate]);
	       echo "<tr align=left><td colspan=2><b>Finals:</b> $date[1]/$date[2] @ $row[time]";
	       $sql2="SELECT * FROM spstaterooms WHERE roundid='$row[id]'";
	       $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
	       echo ", Rm $row2[room]</td></tr>";
	    }
            else
            {
               echo "<tr align=left><td><b>FINALS:<br>Date:</b>";
               $sql2="SELECT dates FROM spdistricts WHERE type='State' ORDER BY dates";
               $result2=mysql_query($sql2);
               while($row2=mysql_fetch_array($result2))
               {
                  $temp=split("-",$row2[0]);
                  echo "<input type=radio name=\"date[$i][$roundid]\" value=\"$row2[0]\"";
                  if($row2[0]==$row[rounddate]) echo " checked";
                  echo ">$temp[1]/$temp[2]&nbsp;&nbsp;";
               }
               echo "</td></tr>";
               $temp=split("[: ]",$row[time]);
               echo "<tr align=left><td><b>Time:</b> <input type=text size=2 class=tiny name=\"hour[$i][$roundid]\" value=\"$temp[0]\"> : <input type=text size=2 class=tiny name=\"min[$i][$roundid]\" value=\"$temp[1]\"> <select name=\"ampm[$i][$roundid]\">";
               echo "<option";
               if($temp[2]=='AM') echo " selected";
               echo ">AM</option><option";
               if($temp[2]=='PM') echo " selected";
               echo ">PM</option></select></td></tr>";
               $sql2="SELECT * FROM spstaterooms WHERE roundid='$row[id]'";
               $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
	       echo "<input type=hidden name=\"roomid[$i][$roundid][0]\" value=\"$row2[id]\">";
               echo "<tr align=left><td>Room: <input type=text class=tiny size=8 name=\"room[$i][$roundid][0]\" value=\"$row2[room]\"></td></tr>";
            }
  	    if($edit!=1)
	    {
	       $sql3="SELECT * FROM spstateassign WHERE roomid='$row2[id]'";
	       $result3=mysql_query($sql3);
	       $curct=0; $ix2=0;
	       while($row3=mysql_fetch_array($result3))
	       {
	          echo "<tr align=left><td>";
	          echo "<input type=hidden name=\"roomid[$ix]\" value=\"$row2[id]\">";
	          echo "<input type=hidden name=\"assign$ix\" value=\"$row3[offid]\">";
	          echo "<input type=text class=tiny size=20 name=\"offname$ix\" value=\"".GetJudgeName($row3[offid])."\" onClick=\"window.open('judgespick3.php?session=$session&ix=$ix&roomid=$row2[id]','judgespick','resizable=yes,scrollbars=yes');\">";
	          echo "</td></tr>";
	          $ix++; $curct++;
 	       }
	       while($curct<3)
	       {
	          echo "<tr align=left><td>";
	          echo "<input type=hidden name=\"roomid[$ix]\" value=\"$row2[id]\">";
	          echo "<input type=hidden name=\"assign$ix\" value='0'>";
	          echo "<input type=text class=tiny size=20 name=\"offname$ix\" value=\"[Click to Pick Judge]\" onClick=\"window.open('judgespick3.php?session=$session&ix=$ix&roomid=$row2[id]','judgespick','resizable=yes,scrollbars=yes');\">";
	          echo "</td></tr>";
	          $ix++; $curct++;
	       }
	    }
	 }
      }
      echo "</table></td>";
      if(($i+1)%3==0) echo "</tr>";
   }
   echo "<input type=hidden name=total value=$ix>";
   echo "<tr align=center><td colspan=3><br><input type=submit name=save value=\"Save\"></td></tr>";
   echo "</table>";
   echo "</form>";
}
echo $end_html;
?>
