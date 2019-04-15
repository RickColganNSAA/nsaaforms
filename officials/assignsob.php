<?php
//echo $zones."<br>".$dates."<br>";
$sport="sob";
$sportname="Boys Soccer";

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
      $sql="SELECT t1.id FROM $disttimes AS t1,$districts AS t2 WHERE t1.distid=t2.id AND t2.type='State' AND t1.time!='standby'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $stateid=$row[id];
      $sql="SELECT id FROM $disttimes WHERE time='standby'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $standbyid=$row[0];
   }
   for($i=0;$i<$total;$i++)
   {
      $var1="offname".$i."id"; $var2="offname".$i;
      if($$var1>0 && $$var2!="[Click to Choose Official]" && $$var2!="[Type all or part of the name]" && $$var2!='')
      {
         if($type=='State' && $standby[$i]==0) $curid=$stateid;
	 else if($type=='State' && $standby[$i]=='1') $curid=$standbyid;
	 else $curid=$timeid[$i];
         $sql="SELECT * FROM $contracts WHERE offid='".$$var1."' AND disttimesid='$curid'";
         $result=mysql_query($sql);
         if(mysql_num_rows($result)==0)
         {
	    if($type!="State")
               $sql2="INSERT INTO $contracts (offid,disttimesid,position) VALUES ('".$$var1."','$curid','$position[$i]')";
	    else
	       $sql2="INSERT INTO $contracts (offid,disttimesid) VALUES ('".$$var1."','$curid')";
            $result2=mysql_query($sql2);
         }
         else if($type!="State")	//else it's already in there; no need to update
         {
            $sql2="UPDATE $contracts SET position='$position[$i]' WHERE offid='".$$var1."' AND disttimesid='$curid'";
	    $result2=mysql_query($sql2);
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
            if($curid==$row[disttimesid])	//get to this time slot
	    {	
	       if($$assignedoff==$row[offid] && $$assignedoffname!="")	//yes, they were assigned
	          $assigned=1;
            }
         }
         if($assigned==0)	//if NOT assigned, erase this entry
         {
	    $sql2="DELETE FROM $contracts WHERE offid='$row[offid]' AND disttimesid='$row[disttimesid]'";
	    $result2=mysql_query($sql2);
	    echo "$sql2<br>";
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
	    if($$var1==$row[0] && $standby[$i]==0) $assigned=1;
   	 }
	 if($assigned==0)
	 {
	    $sql3="DELETE FROM $contracts WHERE offid='$row[0]' AND disttimesid='$stateid'"; 
   	    $result3=mysql_query($sql3);
            //echo $sql3."<br>";
	 }
      }
      //Next do Stand-By officials:
      $sql="SELECT offid FROM $contracts WHERE disttimesid='$standbyid' AND offid!='0'";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
	 $assigned=0;
	 for($i=0;$i<$total;$i++)
	 {
	    $var1="offname".$i."id";
	    if($$var1==$row[0] && $standby[$i]=='1') $assigned=1;
         }
	 if($assigned==0)
	 {
	    $sql3="DELETE FROM $contracts WHERE offid='$row[0]' AND disttimesid='$standbyid'";
	    $result3=mysql_query($sql3);
	    //echo $sql3."<br>";
	 }
      }
   }
}

echo $init_html_ajax;
?>
</head>
<body onload="OffAssign.initialize('<?php echo $session; ?>','so');">
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
echo "<form name=assignform method=post action=\"assignsob.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=zones value=\"$zones\">";
echo "<input type=hidden name=dates value=\"$dates\">";
echo "<input type=hidden name=andor value=\"$andor\">";
echo "<input type=hidden name=hiddensave id=\"hiddensave\">";
echo "<table width=75%><caption><b>Assign $sportname Officials:<br><font style=\"font-size:8pt;\">";
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
      echo "&nbsp;Choose $type:&nbsp;";
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
      $type2=strtolower($type);
      echo "<a class=small href=\"".$sport."assignreport.php?session=$session&type=$type2\">View Assignments Report</a>&nbsp;&nbsp;";
      echo "<a class=small href=\"".$sport."contracts.php?session=$session\">View Submitted Contracts</a>&nbsp;&nbsp;";
      if($type=="State")
         echo "<a class=small href=\"assignpost.php?return=assign".$sport."&session=$session&type=$type&sport=$sport\">POST State $sportname Contracts</a><br><br>";
      else
         echo "<a class=small href=\"assignpost.php?return=assign".$sport."&session=$session&type=$type&sport=$sport\">POST District $sportname Contracts</a><br><br>";

      //if STATE, get stateid and standbyid (contracts.disttimesid)
      if($type=="State")
      {
	 $sql="SELECT t1.id,t1.time FROM $disttimes AS t1, $districts AS t2 WHERE t1.distid=t2.id AND t2.type='State'";
	 $result=mysql_query($sql);
	 while($row=mysql_fetch_array($result))
  	 {
	    if($row[time]=='standby') $standbyid=$row[id];
	    else $stateid=$row[id];
	 }
      }
   }
}
echo "</caption>";
$curoffs=array();
$ix=0;

if($sport && $sport!='~' && $type && $type!="~" && ($type=="State" || ($distid && $distid!='~')))
{
   /****DISTRICT INFO****/
   if($type!="State" && $type!='District Final')
   {
      echo "<tr align=left><td colspan=2><table>";
      echo "<tr align=left><td colspan=2><b>District Information:<hr></b></td></tr>";
      $sql="SELECT * FROM $districts WHERE id='$distid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      echo "<tr align=left><td><b>$row[gender] $type:</b></td><td>$row[class]-$row[district]</td></tr>";
      if($row[first]=="" && $row[last]=="")	//pull AD's name from logins table
      {
	 $hostid=$row[hostid];
	 $sql2="SELECT name FROM $db_name.logins WHERE id='$hostid' AND level=2";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $director=$row2[0];
      }
      else
         $director="$row[first] $row[last]";
      $director.=" ($row[hostschool])";
      echo "<tr align=left><td><b>Director:</b></td><td>$director</td></tr>";
      echo "<tr align=left><td><b>Host School:</b></td><td>$row[hostschool]</td></tr>";
      //echo "<tr align=left><td><b>Site:</b></td><td>$row[site]</td></tr>";
      echo "<tr align=left valign=top><td><b>Dates/Times:</b></td><td>";
      echo "<table>";
      //get time/date slots for this dist
      $sql2="SELECT DISTINCT day FROM $disttimes WHERE distid='$distid' AND day!='0000-00-00' ORDER BY day,time";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
 	 echo "<tr align=left>";
	 $sql3="SELECT time FROM $disttimes WHERE distid='$distid' AND day='$row2[day]' ORDER BY time"; 
         $result3=mysql_query($sql3);
	 $day=split("-",$row2[day]);
         echo "<td>$day[1]/$day[2]: </td><td>"; 
	 $times="";
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
      echo "<tr align=left><td colspan=2><a class=small href=\"hostbyhost.php?sport=$sport&distid=$distid&session=$session\">Edit Above Details for this District</a></td></tr>";
      echo "</table><a name=\"timeslots\">&nbsp;</a>";
      echo "</td></tr><tr align=left><td><hr>";
      if($mode=="Switch to Quick Mode" || $hiddenmode=="quick") //in quick mode
      {
         echo "<input type=button name=mode value=\"Switch to Normal Mode\" onclick=\"hiddenmode.value='normal';submit();\"><br><br>";
         echo "<i>NOTE: To remove an official from an assignment in Quick Mode, simply delete the official's name from the text box and click \"Save Changes\"</i>";
         echo "<input type=hidden name=hiddenmode value=\"quick\">";
      }
      else      //in normal mode
      {
         echo "<input type=button name=mode value=\"Switch to Quick Mode\" onclick=\"hiddenmode.value='quick';submit();\"><br><br>";
         echo "<i>NOTE: To remove an official from an assignment in Normal Mode, click on the box with the official's name and then in the window that pops up, click on the \"RESET\" link.</i>";
         echo "<input type=hidden name=hiddenmode value=\"normal\">";
      }
      echo "</td></tr>";
   }
   else if($type=="State")
   {
      $sql="SELECT t1.id FROM $disttimes AS t1,$districts AS t2 WHERE t1.distid=t2.id AND t2.type='State' AND t1.time!='standby'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $stateid=$row[id];
      $sql="SELECT id FROM $disttimes WHERE time='standby'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $standbyid=$row[0];
      //echo "$stateid<br>$standbyid";
	  	        if($mode=="Switch to Quick Mode" || $hiddenmode=="quick") //in quick mode
      {
         echo "<input type=button name=mode value=\"Switch to Normal Mode\" onclick=\"hiddenmode.value='normal';submit();\"><br><br>";
         echo "<i>NOTE: To remove an official from an assignment in Quick Mode, simply delete the official's name from the text box and click \"Save Changes\"</i>";
         echo "<input type=hidden name=hiddenmode value=\"quick\">";
      }
      else      //in normal mode
      {
         echo "<input type=button name=mode value=\"Switch to Quick Mode\" onclick=\"hiddenmode.value='quick';submit();\"><br><br>";
         echo "<i>NOTE: To remove an official from an assignment in Normal Mode, click on the box with the official's name and then in the window that pops up, click on the \"RESET\" link.</i>";
         echo "<input type=hidden name=hiddenmode value=\"normal\">";
      }
   }
   
   //show textboxes to click on to choose officials
   if($type!="State")
   {
   //group by class-dist/day/time:
   $sql2="SELECT DISTINCT day FROM $disttimes WHERE distid='$distid' AND day!='0000-00-00' ORDER BY day";
   $result2=mysql_query($sql2);
   $ix=0; 
   while($row2=mysql_fetch_array($result2))
   {
      echo "<tr align=center><td><table>";
      $date=split("-",$row2[0]);
      $curday=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      echo "<tr align=left><td><b><u>$curday:</u></b></td></tr>";
      echo "<tr align=center><td><table>";
      $sql3="SELECT id,time FROM $disttimes WHERE distid='$distid' AND day='$row2[day]' ORDER BY time";
      $result3=mysql_query($sql3);
      while($row3=mysql_fetch_array($result3))
      {
	 $curdisttimesid=$row3[id];
	 echo "<tr valign=top align=left><td><b>$row3[time]</b></td>";
         for($i=0;$i<count($sopositions);$i++)
	 {
            //show officials already assigned to this time slot
            $sql="SELECT * FROM $contracts WHERE disttimesid='$curdisttimesid' AND position='$sopositions[$i]'";
            $result=mysql_query($sql);
            $curct=0;
            if($row=mysql_fetch_array($result))
            {
	       echo "<input type=hidden name=\"position[$ix]\" value=\"$sopositions[$i]\">";
               $varname2="offname".$ix; $varname1=$varname2."id";
               echo "<input type=hidden name=\"timeid[$ix]\" value=\"$curdisttimesid\">";
               echo "<input type=hidden name=\"$varname1\" id=\"$varname1\" value=\"$row[offid]\">";
               if($mode=="Switch to Quick Mode" || $hiddenmode=="quick") //in quick mode
                  echo "<td><table cellspacing=0 cellpadding=0><tr align=left><td>$sopositions[$i]:&nbsp;<input class=tiny type=text size=25 name=\"$varname2\" id=\"$varname2\" value=\"".GetOffName($row[offid])."\" onkeyup=\"OffAssign.lookupOffs('$varname2',this.value);\"></td></tr><tr align=left><td><div style=\"display:none;\" class=\"searchresults\" id=\"".$varname2."List\" name=\"".$varname2."List\"></div></td></tr></table></td>";
               else
                  echo "<td>$sopositions[$i]:<input type=text name=\"$varname2\" value=\"".GetOffName($row[offid])."\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$curdisttimesid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=20></td>";
               $curct++; $ix++;
            }
            else
            {
               echo "<input type=hidden name=\"position[$ix]\" value=\"$sopositions[$i]\">";
               $varname2="offname".$ix; $varname1=$varname2."id";
               echo "<input type=hidden name=\"timeid[$ix]\" value=\"$curdisttimesid\">";
               echo "<input type=hidden name=\"$varname1\" id=\"$varname1\" value=\"0\">";
               if($mode=="Switch to Quick Mode" || $hiddenmode=="quick")   //in quick mode
                  echo "<td><table cellspacing=0 cellpadding=0><tr align=left><td>$sopositions[$i]:&nbsp;<input class=tiny type=text size=25 name=\"$varname2\" id=\"$varname2\" onclick=\"this.value='';\" value=\"[Type all or part of name]\" onkeyup=\"OffAssign.lookupOffs('$varname2',this.value);\"></td></tr><tr align=left><td><div style=\"display:none;\" class=\"searchresults\" id=\"".$varname2."List\" name=\"".$varname2."List\"></div></td></tr></table></td>";
               else
                  echo "<td>$sopositions[$i]:&nbsp;<input type=text name=\"$varname2\" value=\"[Click to Choose Official]\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$curdisttimesid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=20></td>";
               $ix++; $curct++;
	    }
         }
	 echo "</tr>";
      }
      echo "</table></td></tr></table></td></tr>";
   }
   }//end if NOT STATE
   else 		//STATE
   {
      echo "<tr align=center><td><table><tr align=left valign=top>";
      //first show current state assignments
      $sql3="SELECT offid FROM $contracts WHERE disttimesid='$stateid'";
      $result3=mysql_query($sql3);
      $ix=0;
      while($row3=mysql_fetch_array($result3))
      { 
         if($ix%13==0) echo "<td>";
         /* $varname1="offname".$ix."id"; $varname2="offname".$ix;
         echo "<input type=hidden name=\"$varname1\" value=\"$row3[offid]\">";
	 echo "<input type=hidden name=\"standby[$ix]\" value=\"0\">";
         echo "<input type=text name=\"$varname2\" value=\"".GetOffName($row3[offid])."\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$stateid&standby=0&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=25>";
          */
		  $varname2="offname".$ix; $varname1=$varname2."id";
               echo "<input type=hidden name=\"timeid[$ix]\" value=\"$curdisttimesid\">";
               echo "<input type=hidden name=\"$varname1\" id=\"$varname1\" value=\"$row3[offid]\">";
               if($mode=="Switch to Quick Mode" || $hiddenmode=="quick") //in quick mode
               echo "<table cellspacing=0 cellpadding=0><tr align=left><td><input class=tiny type=text size=25 name=\"$varname2\" id=\"$varname2\" value=\"".GetOffName($row3[offid])."\" onkeyup=\"OffAssign.lookupOffs('$varname2',this.value);\"></td></tr><tr align=left><td><div style=\"display:none;\" class=\"searchresults\" id=\"".$varname2."List\" name=\"".$varname2."List\"></div></td></tr></table>";
               else
               echo "<input type=text name=\"$varname2\" value=\"".GetOffName($row3[offid])."\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$curdisttimesid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=20>";
               
		  echo "<br>";
         if(($ix+1)%13==0) echo "</td>";
         $ix++;
      }
      while($ix<52)
      {
         if($ix%13==0) echo "<td>";
         /* $varname1="offname".$ix."id"; $varname2="offname".$ix;
         echo "<input type=hidden name=\"$varname1\" value=\"0\">";
	 echo "<input type=hidden name=\"standby[$ix]\" value=\"0\">";
         echo "<input type=text name=\"$varname2\" value=\"[Click to Choose Official]\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$stateid&standby=0&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=25>";
          */
		  $varname2="offname".$ix; $varname1=$varname2."id";
          echo "<input type=hidden name=\"timeid[$ix]\" value=\"$curdisttimesid\">";
          echo "<input type=hidden name=\"$varname1\" id=\"$varname1\" value=\"0\">";
          if($mode=="Switch to Quick Mode" || $hiddenmode=="quick")   //in quick mode
          echo "<table cellspacing=0 cellpadding=0><tr align=left><td><input class=tiny type=text size=25 name=\"$varname2\" id=\"$varname2\" onclick=\"this.value='';\" value=\"[Type all or part of name]\" onkeyup=\"OffAssign.lookupOffs('$varname2',this.value);\"></td></tr><tr align=left><td><div style=\"display:none;\" class=\"searchresults\" id=\"".$varname2."List\" name=\"".$varname2."List\"></div></td></tr></table>";
          else
          echo "<input type=text name=\"$varname2\" value=\"[Click to Choose Official]\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$curdisttimesid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=20>";    
		  
		  echo "<br>";
         if(($ix+1)%13==0) echo "</td>";
         $ix++;
      }
      echo "</tr></table></td></tr>";
 
      //STAND-BY officials:
      echo "<tr align=center><td><b>Stand-By Officials:<table><tr align=left valign=top>";
      //first show current standby official assignments:
      $sql3="SELECT offid FROM $contracts WHERE disttimesid='$standbyid'";
      $result3=mysql_query($sql3);
      $standbyct=0;
      while($row3=mysql_fetch_array($result3))
      {
	 if($standbyct%2==0) echo "<td>";
	 /* $varname1="offname".$ix."id"; $varname2="offname".$ix;
	 echo "<input type=hidden name=\"$varname1\" value=\"$row3[offid]\">";
         echo "<input type=hidden name=\"standby[$ix]\" value=\"1\">";
	 echo "<input type=text name=\"$varname2\" value=\"".GetOffName($row3[offid])."\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$standbyid&$standby=1&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=25>";
	  */
	  $varname2="offname".$ix; $varname1=$varname2."id";
	   echo "<input type=hidden name=\"timeid[$ix]\" value=\"$curdisttimesid\">";
	   echo "<input type=hidden name=\"$varname1\" id=\"$varname1\" value=\"$row3[offid]\">";
	   if($mode=="Switch to Quick Mode" || $hiddenmode=="quick") //in quick mode
	   echo "<table cellspacing=0 cellpadding=0><tr align=left><td><input class=tiny type=text size=25 name=\"$varname2\" id=\"$varname2\" value=\"".GetOffName($row3[offid])."\" onkeyup=\"OffAssign.lookupOffs('$varname2',this.value);\"></td></tr><tr align=left><td><div style=\"display:none;\" class=\"searchresults\" id=\"".$varname2."List\" name=\"".$varname2."List\"></div></td></tr></table>";
	   else
	   echo "<input type=text name=\"$varname2\" value=\"".GetOffName($row3[offid])."\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$curdisttimesid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=20>";
	   
	  echo "<br>";
	 if(($standbyct+1)%2==0) echo "</td>";
	 $ix++; $standbyct++;
      }
      while($standbyct<6)
      {
	 if($standbyct%2==0) echo "<td>";
	 /* $varname1="offname".$ix."id"; $varname2="offname".$ix;
	 echo "<input type=hidden name=\"$varname1\" value=\"0\">";
         echo "<input type=hidden name=\"standby[$ix]\" value=\"1\">";
	 echo "<input type=text name=\"$varname2\" value=\"[Click to Choose Official]\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$standbyid&$standby=1&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=25>";
        */ 
    $varname2="offname".$ix; $varname1=$varname2."id";
          echo "<input type=hidden name=\"timeid[$ix]\" value=\"$curdisttimesid\">";
          echo "<input type=hidden name=\"$varname1\" id=\"$varname1\" value=\"0\">";
          if($mode=="Switch to Quick Mode" || $hiddenmode=="quick")   //in quick mode
          echo "<table cellspacing=0 cellpadding=0><tr align=left><td><input class=tiny type=text size=25 name=\"$varname2\" id=\"$varname2\" onclick=\"this.value='';\" value=\"[Type all or part of name]\" onkeyup=\"OffAssign.lookupOffs('$varname2',this.value);\"></td></tr><tr align=left><td><div style=\"display:none;\" class=\"searchresults\" id=\"".$varname2."List\" name=\"".$varname2."List\"></div></td></tr></table>";
          else
          echo "<input type=text name=\"$varname2\" value=\"[Click to Choose Official]\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$curdisttimesid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=20>";    
		  
		echo "<br>";
	 if(($standbyct+1)%2==0) echo "</td>";
	 $ix++; $standbyct++;
      }
   } 
   echo "<input type=hidden name=total value=$ix>";
   echo "<input type=hidden name=filteragain value=$filter>";
   if($type=="State" || ($type!="State" && mysql_num_rows($result2)>0))
      echo "<tr align=center><td colspan=2><input type=submit name=save value=\"Save Changes\"></td></tr>";
   else
      echo "<tr align=center><td colspan=2>[No time slots have been entered for this district yet.  Please <a class=small href=\"hostbyhost.php?sport=sob&distid=$distid&session=$session\">Click Here</a> to add time slots for this district.]</td></tr>";
   echo "</table>";
}
echo "</form>";
echo "<div id=\"loading\" style=\"display:none;\"></div>";
echo $end_html;
?>
