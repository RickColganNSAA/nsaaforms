<?php
//echo $zones."<br>".$dates."<br>";
$sport="bbg";
$sportname="Girls Basketball";

require '../../calculate/functions.php';
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

$disttimes=$sport."disttimes";
$districts=$sport."districts";
$contracts=$sport."contracts";
$zonetbl=$sport."_zones";
$apply=$sport."apply";
$offtable=$sport."off";

if($save || $hiddensave)	
{
   if($type=="State")	//get 'distid'
   {
      $sql="SELECT t1.id,t1.time FROM $disttimes AS t1,$districts AS t2 WHERE t1.distid=t2.id AND t2.type='State'";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         $stateid=$row[0];
      }
   }
   for($i=0;$i<$total;$i++)
   {
      //$var1="assign".$i; $var2="offname".$i;
      $var2="offname".$i; $var1=$var2."id";
      if($$var2!="[Click to Choose Official]" && $$var2!="[Type all or part of name]" && $$var2!="")
      {
         if($type=='State') $curid=$stateid;
	 else $curid=$timeid[$i];
         $sql="SELECT * FROM $contracts WHERE offid='".$$var1."' AND disttimesid='$curid'";
         $result=mysql_query($sql);
         if(mysql_num_rows($result)==0)
         {
            $sql2="INSERT INTO $contracts (offid,disttimesid) VALUES ('".$$var1."','$curid')";
            $result2=mysql_query($sql2);
            //echo $sql2."<br>".mysql_error();
         }
      }
   }

   //delete old assignments that were replaced
   if($type!='State')
   {
      //first, get all official/time slot pairs in the $contracts table for this district/subdistrict
      $sql="SELECT DISTINCT t1.offid,t1.disttimesid FROM $contracts AS t1, $disttimes AS t2 WHERE t1.disttimesid=t2.id AND t2.distid='$distid'";
      $result=mysql_query($sql); 
      while($row=mysql_fetch_array($result))
      {
         //for each one, if he/she was not saved as an assigned official for this time, erase it
         $assigned=0;	//assume they were NOT assigned this time to this time slot
         for($i=0;$i<$total;$i++)
         {
            $curid=$timeid[$i];	$assignedoff="offname".$i."id"; $assignedoffname="offname".$i;
            if($curid==$row[disttimesid] && $$assignedoffname!="")	//get to this time slot
	    {	
	       if($$assignedoff==$row[offid])	//yes, they were assigned
	          $assigned=1;
            }
         }
         if($assigned==0)	//if NOT assigned, erase this entry
         {
	    $sql2="DELETE FROM $contracts WHERE offid='$row[offid]' AND disttimesid='$row[disttimesid]'";
	    $result2=mysql_query($sql2);
	    //echo "$sql2<br>";
	 }
      }
   }
   else
   {
      //First do Main officials:
      $sql="SELECT offid FROM $contracts WHERE disttimesid='$stateid' AND offid!='0'";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         $assigned=0;
         for($i=0;$i<$total;$i++)
	 {
	    $var1="offname".$i."id";
	    if($$var1==$row[0]) $assigned=1;
   	 }
	 if($assigned==0)
	 {
	    $sql3="DELETE FROM $contracts WHERE offid='$row[0]' AND disttimesid='$stateid'"; 
   	    $result3=mysql_query($sql3);
            //echo $sql3."<br>";
	 }
      }
   }
}

echo $init_html_ajax;
?>
</head>
<body onload="OffAssign.initialize('<?php echo $session; ?>','bb');">
<?php
echo GetHeader($session,"contractadmin");
echo "<br>";
if($posted=="yes")
{
   echo "<font style=\"color:red\"><b>All $sportname Contracts have been posted to the assigned officials.</b></font><br><br>";
}
else if($save || $hiddensave)
{
   echo "<font style=\"color:red\"><b>The assignments for this district have been saved.</b></font><br><br>";
}

//allow user to choose sport and then class/dist or state
echo "<div id=\"baselayer\" style=\"position:relative;z-index:1\">";
echo "<table width=75%><caption>";
echo "<form method=post action=\"assignbydistrict.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<select onchange=\"submit();\" name=\"sport\"><option value=''>Choose Sport</option>";
$sql="SHOW TABLES LIKE '%contracts'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $temp=split("contracts",$row[0]);
   $cursport=$temp[0];
   echo "<option value='$cursport'";
   if($sport==$cursport) echo " selected";
   echo ">".GetSportName($cursport)."</option>";
}
echo "</select><input type=submit name=go value=\"Go\"></form>";
echo "<form name=assignform method=post action=\"assignbbg.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=zones value=\"$zones\">";
echo "<input type=hidden name=dates value=\"$dates\">";
echo "<input type=hidden name=andor value=\"$andor\">";
echo "<input type=hidden name=hiddensave>";

if($sport && $sport!="~")
{
   echo "Choose Type:&nbsp;";
   echo "<select onchange=\"dates.value='';zones.value='';submit();\" name=type>";
   echo "<option>~</option>";
   $types=array("District","Subdistrict","District Final","State");
   for($i=0;$i<count($types);$i++)
   {
      echo "<option";
      if($type==$types[$i]) echo " selected";
      echo ">$types[$i]</option>";
   }
   echo "</select>";
   if($type && $type!="~" && $type!="State")
   {
      echo "&nbsp;Choose District:&nbsp;";
      echo "<select onchange=\"dates.value='';zones.value='';submit();\" name=distid>";
      echo "<option>~</option>";
      $sql="SELECT DISTINCT class,district,id FROM $districts WHERE type='$type' ORDER BY class,district";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         echo "<option value='$row[id]'";
         if($distid=="$row[id]") echo " selected";
         echo ">$row[class]-$row[district]";
         echo "</option>";
      }
      echo "</select>";
   }
   echo "<br><br>";
   if(($distid && $distid!='~') || $type=="State")
   {
      echo "<a class=small href=\"".$sport."assignreport.php?session=$session\">VIEW Report of All $sportname Assignments</a>&nbsp;&nbsp;&nbsp;";
      echo "<a class=small href=\"".$sport."contracts.php?session=$session\">VIEW All $sportname Contracts That Have Been Accepted/Declined</a><br>";
      if($type=="State")
         echo "<a class=small href=\"assignpost.php?return=assign".$sport."&session=$session&type=$type&sport=$sport\">POST State $sportname Contracts</a><br><br>";
      else
         echo "<a class=small href=\"assignpost.php?return=assign".$sport."&session=$session&type=$type&sport=$sport\">POST (Non-State) $sportname Contracts</a><br><br>";
   }
}
echo "</caption>";
$curoffs=array();
$ix=0;

if($sport && $sport!='~' && $type && $type!="~" && ($type=="State" || ($distid && $distid!='~')))
{
   /****DISTRICT INFO****/
   if($type!="State")
   {
      echo "<tr align=left><td colspan=2><table>";
      echo "<tr align=left><td colspan=2><b>District Information:&nbsp;&nbsp;";
      echo "<a class=small href=\"hostbyhost.php?session=$session&type=$type&distid=$distid&sport=$sport\">Click Here to Edit this District's Information</a>";
      echo "<hr></b></td></tr>";
      $sql="SELECT * FROM $districts WHERE id='$distid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      echo "<tr align=left><td><b>$type:</b></td><td>$row[class]-$row[district]</td></tr>";
      if(!($row['class']=="A" && preg_match("/bb/",$sport))) //CLASS A BASKETBALL
      {
         echo "<tr align=left><td><b>Director:</b></td><td>$row[director] (<a class=small href=\"mailto:$row[email]\">$row[email]</a>)</td></tr>";
         echo "<tr align=left><td><b>Host School:</b></td><td>$row[hostschool]</td></tr>";
         echo "<tr align=left><td><b>Site:</b></td><td>$row[site]</td></tr>";
      }
      echo "<tr align=left valign=top><td><b>Dates/Times:</b></td><td>";
      echo "<table>";
      //get time/date slots for this dist
      $sql2="SELECT DISTINCT day FROM $disttimes WHERE distid='$distid' AND day!='0000-00-00' ORDER BY day,time";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
 	 echo "<tr align=left><td>&nbsp;</td>";
	 $sql3="SELECT time FROM $disttimes WHERE distid='$distid' AND day='$row2[day]' ORDER BY time"; 
         $result3=mysql_query($sql3);
	 $day=split("-",$row2[day]);
         echo "<td>$day[1]/$day[2]</td><td>"; $times="";
         while($row3=mysql_fetch_array($result3))
         {
	    $times.=$row3[0]."/";
	 }
	 $times=substr($times,0,strlen($times)-1);
	 echo "$times</td></tr>";
      }
      echo "</table>";
      echo "</td></tr>";
      echo "<tr align=left><td><b>Schools:</b></td><td>$row[schools]</td></tr>";
      echo "</table>";
      echo "</td></tr>";
      $temp=split(",",$row[schools]);
      $teamcount=count($temp);
   }
   else if($type=="State")
   {
      $sql="SELECT t1.id,t1.time,t1.distid FROM $disttimes AS t1,$districts AS t2 WHERE t1.distid=t2.id AND t2.type='State'";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         $stateid=$row[0];
         $distid=$row[2];
      }
   }
   
   //show textboxes to click on to choose officials
   if($type!="State")
   {
      $gamecount=$teamcount-1; //(single elimination)
      if($gamecount<=0)
      {
         if($type=="District Final")
	 {
	    $teamcount=2; $gamecount=1;
         }
         else 
	 {
	    $teamcount="?"; $gamecount="?";
	 }
      }
      if($gamecount==1) $game="game";
      else $game="games";
      echo "<tr align=left><td>&nbsp;<b>NOTE: </b>$sportname is currently a <u>Single Elimination</u> sport.  Thus, since there are <b>$teamcount</b> teams, there will be <b>$gamecount</b> $game:</td></tr>";
      echo "<tr align=left><td>";
      if($mode=="Switch to Quick Mode" || $hiddenmode=="quick") //in quick mode
      {
         echo "<input type=button name=mode value=\"Switch to Normal Mode\" onclick=\"hiddenmode.value='normal';submit();\"><br><br>";
         echo "<i>NOTE: To remove an official from an assignment in Quick Mode, simply delete the official's name from the text box and click \"Save Changes\".</i>";
         echo "<input type=hidden name=hiddenmode value=\"quick\">";
      }
      else      //in normal mode
      {
         echo "<input type=button name=mode value=\"Switch to Quick Mode\" onclick=\"hiddenmode.value='quick';submit();\"><br><br>";
         echo "<i>NOTE: To remove an official from an assignment in Normal Mode, click on the box with the official's name and then in the window that pops up, click on the \"RESET\" link.</i>";
         echo "<input type=hidden name=hiddenmode value=\"normal\">";
      }
      echo "</td></tr>";
   //group by class-dist/day/time:
   $sql2="SELECT DISTINCT day FROM $disttimes WHERE distid='$distid' ORDER BY day";
   $result2=mysql_query($sql2);
   if($type=="District Final" && mysql_num_rows($result2)==0)
   {
      $sql3="INSERT INTO $disttimes (distid) VALUES ('$distid')";
      $result3=mysql_query($sql3);
      //echo $sql3;
   }
   $result2=mysql_query($sql2);
   $ix=0; 
   while($row2=mysql_fetch_array($result2))
   {
      echo "<tr align=center><td><table>";
      $date=split("-",$row2[0]);
      $curday=date("M d",mktime(0,0,0,$date[1],$date[2],$date[0]));
      if(date("Y",mktime(0,0,0,$date[1],$date[2],$date[0]))=="1969")
	 $curday="(Date?)";
      echo "<tr align=left><td><b><u>$curday ";
      echo ":</u></b></td></tr>";
      echo "<tr align=center><td><table>";
      $sql3="SELECT * FROM $disttimes WHERE distid='$distid' AND day='$row2[day]' ORDER BY gamenum,time";
      $result3=mysql_query($sql3);
      $curct=0;
      while($row3=mysql_fetch_array($result3))
      {
	 $curdisttimesid=$row3[id];
	 echo "<tr align=left><td><b>$row3[time]</b></td><td>";
	 $sql4="SELECT * FROM $db_name.".$sport."sched WHERE distid='$distid' AND gamenum='$row3[gamenum]'";
	 $result4=mysql_query($sql4);
	 $row4=mysql_fetch_array($result4);
         if(preg_match("/bb/",$sport) && $row['class']=="A" && $row4[sid]>0 && $row4[oppid]>0)     //CLASS A BASKETBALL
         {
            echo GetSchoolName($row4[sid],$sport)." vs ".GetSchoolName($row4[oppid],$sport);
            if($row3[hostid]>0)
	    {
	       echo " at ";
	       if(trim($row3[site])!='') echo $row3[site];
               else echo $row3[hostschool];
	    }
         }
         echo "<table><tr align=left valign=top>";
         //show officials already assigned to this time slot
         $sql="SELECT * FROM $contracts WHERE disttimesid='$curdisttimesid' ORDER BY id";
         $result=mysql_query($sql);
         $curct=0;
         while($row=mysql_fetch_array($result))
         {
            $varname2="offname".$ix; $varname1=$varname2."id";
	    echo "<input type=hidden name=\"timeid[$ix]\" value=\"$curdisttimesid\">";
	    echo "<input type=hidden name=\"$varname1\" id=\"$varname1\" value=\"$row[offid]\">";
	    if($mode=="Switch to Quick Mode" || $hiddenmode=="quick") //in quick mode
               echo "<td><table cellspacing=0 cellpadding=0><tr align=left><td><input class=tiny type=text size=25 name=\"$varname2\" id=\"$varname2\" value=\"".GetOffName($row[offid])."\" onkeyup=\"OffAssign.lookupOffs('$varname2',this.value);\"></td></tr><tr align=left><td><div style=\"display:none;\" class=\"searchresults\" id=\"".$varname2."List\" name=\"".$varname2."List\"></div></td></tr></table></td>";
    	    else
               echo "<td><input type=text name=\"$varname2\" value=\"".GetOffName($row[offid])."\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$curdisttimesid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=25></td>";
            echo "&nbsp;&nbsp;";
            $curct++; $ix++;
         }
         while($curct<3)
         {
            $varname2="offname".$ix; $varname1=$varname2."id";
	    echo "<input type=hidden name=\"timeid[$ix]\" value=\"$curdisttimesid\">";
            echo "<input type=hidden name=\"$varname1\" id=\"$varname1\" value=\"0\">";
	    if($mode=="Switch to Quick Mode" || $hiddenmode=="quick")	//in quick mode
               echo "<td><table cellspacing=0 cellpadding=0><tr align=left><td><input class=tiny type=text size=25 name=\"$varname2\" id=\"$varname2\" onclick=\"this.value='';\" value=\"[Type all or part of name]\" onkeyup=\"OffAssign.lookupOffs('$varname2',this.value);\"></td></tr><tr align=left><td><div style=\"display:none;\" class=\"searchresults\" id=\"".$varname2."List\" name=\"".$varname2."List\"></div></td></tr></table></td>";
	    else
	       echo "<td><input type=text name=\"$varname2\" value=\"[Click to Choose Official]\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$curdisttimesid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=25></td>";
            echo "&nbsp;&nbsp;";
            $ix++; $curct++;
         }
	 echo "</tr></table></td></tr>";
      }
      echo "</table></div></td></tr></table></td></tr>";
   }
   }//end if NOT STATE
   else 		//STATE
   {
      if($mode=="Switch to Quick Mode" || $hiddenmode=="quick") //in quick mode
      {
         echo "<input type=button name=mode value=\"Switch to Normal Mode\" onclick=\"hiddenmode.value='normal';submit();\"><br><br>";
         echo "<i>NOTE: To remove an official from an assignment in Quick Mode, simply delete the official's name from the text box and click \"Save Changes\".</i>";
         echo "<input type=hidden name=hiddenmode value=\"quick\">";
      }
      else      //in normal mode
      {
         echo "<input type=button name=mode value=\"Switch to Quick Mode\" onclick=\"hiddenmode.value='quick';submit();\"><br><br>";
         echo "<i>NOTE: To remove an official from an assignment in Normal Mode, click on the box with the official's name and then in the window that pops up, click on the \"RESET\" link.</i>";
         echo "<input type=hidden name=hiddenmode value=\"normal\">";
      }
	  echo "<tr align=center><td><table><tr align=left>";
      //first show current state assignments
      $sql3="SELECT offid FROM $contracts WHERE disttimesid='$stateid'";
      $result3=mysql_query($sql3);
      $ix=0;
      while($row3=mysql_fetch_array($result3))
      { 
	 $num=$ix+1;
         if($ix%12==0) echo "<td>";
        /*  $varname1="offname".$ix."id"; $varname2="offname".$ix;
         echo "<input type=hidden name=\"$varname1\" value=\"$row3[offid]\">";
         echo "$num) <input type=text name=\"$varname2\" value=\"".GetOffName($row3[offid])."\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&distid=$distid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=25>";
          */
		  $varname2="offname".$ix; $varname1=$varname2."id";
	    echo "<input type=hidden name=\"timeid[$ix]\" value=\"$curdisttimesid\">";
	    echo "<input type=hidden name=\"$varname1\" id=\"$varname1\" value=\"$row3[offid]\">";
	    if($mode=="Switch to Quick Mode" || $hiddenmode=="quick") //in quick mode
               echo "<table cellspacing=0 cellpadding=0><tr align=left><td>$num)<input class=tiny type=text size=25 name=\"$varname2\" id=\"$varname2\" value=\"".GetOffName($row3[offid])."\" onkeyup=\"OffAssign.lookupOffs('$varname2',this.value);\"></td></tr><tr align=left><td><div style=\"display:none;\" class=\"searchresults\" id=\"".$varname2."List\" name=\"".$varname2."List\"></div></td></tr></table>";
    	    else
               echo "$num)<input type=text name=\"$varname2\" value=\"".GetOffName($row3[offid])."\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$curdisttimesid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=25>";
         
		  echo "<br>";
         if(($ix+1)%12==0) echo "</td>";
         $ix++;
      }
      while($ix<36)
      {
	 $num=$ix+1;
         if($ix%12==0) echo "<td>";
         /* $varname1="offname".$ix."id"; $varname2="offname".$ix;
         echo "<input type=hidden name=\"$varname1\" value=\"0\">";
         echo "$num) <input type=text name=\"$varname2\" value=\"[Click to Choose Official]\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&distid=$distid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=25>";
          */
		  $varname2="offname".$ix; $varname1=$varname2."id";
	    echo "<input type=hidden name=\"timeid[$ix]\" value=\"$curdisttimesid\">";
            echo "<input type=hidden name=\"$varname1\" id=\"$varname1\" value=\"0\">";
	    if($mode=="Switch to Quick Mode" || $hiddenmode=="quick")	//in quick mode
               echo "<table cellspacing=0 cellpadding=0><tr align=left><td>$num)<input class=tiny type=text size=25 name=\"$varname2\" id=\"$varname2\" onclick=\"this.value='';\" value=\"[Type all or part of name]\" onkeyup=\"OffAssign.lookupOffs('$varname2',this.value);\"></td></tr><tr align=left><td><div style=\"display:none;\" class=\"searchresults\" id=\"".$varname2."List\" name=\"".$varname2."List\"></div></td></tr></table>";
	    else
	       echo "$num)<input type=text name=\"$varname2\" value=\"[Click to Choose Official]\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$curdisttimesid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=25>";
        
		  echo "<br>";
         if(($ix+1)%12==0) echo "</td>";
         $ix++;
      }
      echo "</tr></table></td></tr>";
   } 
   echo "<input type=hidden name=total value=$ix>";
   echo "<input type=hidden name=filteragain value=$filter>";
   if($type=="State" || ($type!="State" && mysql_num_rows($result2)>0))
      echo "<tr align=center><td colspan=2><input type=submit name=save value=\"Save Changes\"></td></tr>";
   else if($type!="State")
      echo "<tr align=center><td colspan=2>[No time slots have been entered for this district yet.  Please <a class=small href=\"hostbyhost.php?sport=$sport&type=$type&distid=$distid&session=$session\">Click Here</a> to add time slots for this district.]</td></tr>";
   echo "</table>";
}
echo "</form></div>";
?>
<div id="loading" style="display:none;"></div>
<?php
echo $end_html;
?>
