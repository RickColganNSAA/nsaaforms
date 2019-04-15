<?php
//echo $zones."<br>".$dates."<br>";
$sport="sb";
$sportname="Softball";

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
      $sql="SELECT t1.id FROM $disttimes AS t1,$districts AS t2 WHERE t1.distid=t2.id AND t2.type='State'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)
      {
 	 $sql2="SELECT id FROM $districts WHERE type='State'";
         $result2=mysql_query($sql2);
         if(mysql_num_rows($result2)==0)
	 {
	    $sql3="INSERT INTO $districts (type) values ('State')";
	    $result3=mysql_query($sql3);
	    $result2=mysql_query($sql2);
         }
         $row2=mysql_fetch_array($result2);
         $sql2="INSERT INTO $disttimes (distid) VALUES ('$row2[0]')";
	 $result2=mysql_query($sql2);
	 $result=mysql_query($sql);
      }
      $row=mysql_fetch_array($result);
      $stateid=$row[id];
   }
   for($i=0;$i<$total;$i++)
   {
      $var1="offname".$i."id"; $var2="offname".$i;
      if($$var2!="[Click to Choose Official]" && $$var2!="[Type all or part of name]" && $$var2!="")
      {
         if($type=='State') $curid=$stateid;
	 else $curid=$timeid[$i];
         if($crewchief[$curid]==$$var1) $chief='x';
         else $chief='';
	 if($type=='State') $curtwoday=$twoday[$i];
	 else $curtwoday='';
         $sql="SELECT * FROM $contracts WHERE offid='".$$var1."' AND disttimesid='$curid'";
         $result=mysql_query($sql);
         if(mysql_num_rows($result)==0)
         {
 	    if($crewchief[$curid]==$$var1) $chief='x';
	    else $chief='';
            $sql2="INSERT INTO $contracts (offid,disttimesid,crewchief,twoday) VALUES ('".$$var1."','$curid','$chief','$curtwoday')";
            $result2=mysql_query($sql2);
         }
	 else
	 {
	    $sql2="UPDATE $contracts SET crewchief='$chief',twoday='$curtwoday' WHERE offid='".$$var1."' AND disttimesid='$curid'";
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
            $curid=$timeid[$i];	$assignedoff="offname".$i."id";  $assignedoffname="offname".$i;
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
	    //echo "$sql2<br>";
	 }
      }
   }
   else
   {
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

   //Now, for non-State, make sure if one time slot has not been accepted, all time slots an off is assigned to for
   //a district are set as not accepted; they must re-accept whole contract for district:
   $sql="SELECT DISTINCT t2.offid FROM $disttimes AS t1, $contracts AS t2 WHERE t1.id=t2.disttimesid AND t1.distid='$distid' ORDER BY t2.offid'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $curoffid=$row[0];
      $sql2="SELECT t1.* FROM $contracts AS t1, $disttimes AS t2 WHERE t1.disttimesid=t2.id AND t2.distid='$distid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $post=$row2[post]; $accept=$row2[accept]; $confirm=$row2[confirm];
      $newpost=$post; $newaccept=$accept; $newconfirm=$confirm;
      while($row2=mysql_fetch_array($result2))
      {
         if($row2[post]!=$post) $newpost="";
         if($row2[accept]!=$accept) $newaccept="";
	 if($row2[confirm]!=$confirm) $newconfirm="";
      }
      if($newpost!=$post || $newaccept!=$accept || $newconfirm!=$confirm)
      {
	 echo "OFFID: $curoffid, DISTID: $distid<br>";
      }
   }
   
}

echo $init_html_ajax;
?>
</head>
<body onload="OffAssign.initialize('<?php echo $session; ?>','sb');">
<?php
echo GetHeader($session,"contractadmin");
echo "<br>";
if($posted=="yes")
{
   echo "<font style=\"color:red\"><b>All $type $sportname Contracts have been posted to the assigned officials.</b></font><br><br>";
}
else if($save || $hiddensave)
{
   echo "<font style=\"color:red\"><b>The assignments for this district have been saved.</b></font><br><br>";
}

//allow user to choose sport and then class/dist or state
echo "<div id=\"baselayer\" style=\"position:relative;z-index:1\">";
echo "<form name=assignform method=post action=\"assignsb.php#timeslots\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=zones value=\"$zones\">";
echo "<input type=hidden name=dates value=\"$dates\">";
echo "<input type=hidden name=andor value=\"$andor\">";
echo "<input type=hidden name=hiddensave>";
echo "<table";
if(!$type || $type=='~' || !$distid || $distid=='~')
   echo " width=90%";
echo "><caption><b>";
echo "<a class=small target=new href=\"sbcontract.php?session=$session&sample=1\">Sample District Softball Contract</a>&nbsp;&nbsp;";
echo "<a class=small target=new href=\"sbstatecontract.php?session=$session&sample=1\">Sample State Softball Contract</a><br><br>";
if($sport && $sport!="~")
{
   echo "Choose Type:&nbsp;";
   echo "<select onchange=\"dates.value='';zones.value='';submit();\" name=type>";
   echo "<option>~</option>";
   $types=array("District","State");
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
      $type2=strtolower($type);
      echo "<a class=small href=\"".$sport."assignreport.php?session=$session&type=$type2\">View Assignments Report</a>&nbsp;&nbsp;";
      echo "<a class=small href=\"".$sport."contracts.php?session=$session\">View Submitted Contracts</a>&nbsp;&nbsp;";
      if($type=="State")
         echo "<a class=small href=\"assignpost.php?return=assign".$sport."&session=$session&type=$type&sport=$sport\">POST State $sportname Contracts</a><br><br>";
      else
         echo "<a class=small href=\"assignpost.php?return=assign".$sport."&session=$session&type=$type&sport=$sport\">POST District $sportname Contracts</a><br><br>";
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
      echo "<tr align=left><td colspan=5><table>";
      echo "<tr align=left><td colspan=2><b>District Information:<hr></b></td></tr>";
      $sql="SELECT * FROM $districts WHERE id='$distid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      echo "<tr align=left><td><b>$type:</b></td><td>$row[class]-$row[district]</td></tr>";
      if($row[director]=="")	//pull AD's name from logins table
      {
         $hostid=$row[hostid];
	 $sql2="SELECT name FROM $db_name.logins WHERE id='$hostid' AND level=2";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $director=$row2[0];
      }
      else
         $director="$row[director]";
      $director.=" ($row[hostschool])";
      echo "<tr align=left><td><b>Director:</b></td><td>$director</td></tr>";
      echo "<tr align=left><td><b>Host School:</b></td><td>$row[hostschool]</td></tr>";
      //echo "<tr align=left><td><b>Site:</b></td><td>$row[site]</td></tr>";
      echo "<tr align=left valign=top><td><b>Dates/Times:</b></td><td>";
      echo "<table>";
      //get time/date slots for this dist
      $sql2="SELECT DISTINCT day FROM $disttimes WHERE distid='$distid' AND day!='0000-00-00' ORDER BY day";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
 	 echo "<tr align=left>";
	 $sql3="SELECT DISTINCT time, COUNT(time) FROM $disttimes WHERE distid='$distid' AND day='$row2[day]' GROUP BY time"; 
         $result3=mysql_query($sql3);
	 $day=split("-",$row2[day]);
         echo "<td>$day[1]/$day[2]: </td><td>"; 
	 $times="";
         while($row3=mysql_fetch_array($result3))
         {
	    $times.=$row3[0]."($row3[1])/";
	 }
	 $times=substr($times,0,strlen($times)-1);
	 echo "$times</td></tr>";
      }
      echo "</table>";
      echo "</td></tr>";
      echo "<tr align=left><td><b>Schools:</b></td><td>$row[schools]</td></tr>";
      echo "</table><a name=\"timeslots\"><hr>&nbsp;</a>";
      if($mode=="Switch to Quick Mode" || $hiddenmode=="quick") //in quick mode
      {
         echo "<input type=button name=mode value=\"Switch to Normal Mode\" onclick=\"hiddenmode.value='normal';submit();\"><br><br>";
         echo "<i>NOTE: To remove an official from an assignment in Quick Mode, simply delete the official's name from the text box and click \"Save Changed\".</i>";
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
      $sql="SELECT t1.id FROM $disttimes AS t1,$districts AS t2 WHERE t1.distid=t2.id AND t2.type='State'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $stateid=$row[id];
	   if($mode=="Switch to Quick Mode" || $hiddenmode=="quick") //in quick mode
      {
         echo "<input type=button name=mode value=\"Switch to Normal Mode\" onclick=\"hiddenmode.value='normal';submit();\"><br><br>";
         //echo "<i>NOTE: To remove an official from an assignment in Quick Mode, simply delete the official's name from the text box and click \"Save Changed\".</i>";
         echo "<input type=hidden name=hiddenmode value=\"quick\">";
      }
      else      //in normal mode
      {
         echo "<input type=button name=mode value=\"Switch to Quick Mode\" onclick=\"hiddenmode.value='quick';submit();\"><br><br>";
         //echo "<i>NOTE: To remove an official from an assignment in Normal Mode, click on the box with the official's name and then in the window that pops up, click on the \"RESET\" link.</i>";
         echo "<input type=hidden name=hiddenmode value=\"normal\">";
      }

   }
   
   //show textboxes to click on to choose officials
   if($type!="State")
   {
   //group by class-dist/day/time:
   $sql2="SELECT DISTINCT day FROM $disttimes WHERE distid='$distid' AND day!='0000-00-00' ORDER BY day";
   $result2=mysql_query($sql2);
   $ix=0; $game=1;
   while($row2=mysql_fetch_array($result2))
   {
      if($ix==0)
         echo "<tr valign=top align=center><td><table>";
      else echo "</td><td><table>";
      $date=split("-",$row2[0]);
      $curday=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      echo "<tr align=left><td><b><u>$curday:</u></b></td></tr>";
      echo "<tr align=center><td><table>";
      $sql3="SELECT id,time,sbfield,gamenum FROM $disttimes WHERE distid='$distid' AND day='$row2[day]' ORDER BY gamenum";
      $result3=mysql_query($sql3);
      $curct=0;
      while($row3=mysql_fetch_array($result3))
      {
	 $curdisttimesid=$row3[id];
	 echo "<tr align=left valign=top><td><b>$row3[gamenum]) $row3[time]";
	 $game++;
         if($row3[sbfield]!='') echo " (Field $row3[sbfield])";
         echo "</b></td></tr><tr align=left><td><table>";
         //show officials already assigned to this time slot
         $sql="SELECT * FROM $contracts WHERE disttimesid='$curdisttimesid' ORDER BY id";
         $result=mysql_query($sql);
         $curct=0;
         while($row=mysql_fetch_array($result))
         {
            $varname2="offname".$ix; $varname1=$varname2."id";
            echo "<input type=hidden name=\"timeid[$ix]\" value=\"$curdisttimesid\">";
            echo "<input type=hidden name=\"$varname1\" id=\"$varname1\" value=\"$row[offid]\">";
            echo "<tr align=left>";
            if($mode=="Switch to Quick Mode" || $hiddenmode=="quick") //in quick mode
               echo "<td><table cellspacing=0 cellpadding=0><tr align=left><td><input class=tiny type=text size=25 name=\"$varname2\" id=\"$varname2\" value=\"".GetOffName($row[offid])."\" onkeyup=\"OffAssign.lookupOffs('$varname2',this.value);\"></td></tr><tr align=left><td><div style=\"display:none\" class=\"searchresults\" id=\"".$varname2."List\" name=\"".$varname2."List\"></div></td></tr></table></td>";
            else
               echo "<td><input type=text name=\"$varname2\" value=\"".GetOffName($row[offid])."\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$curdisttimesid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=20></td>";
    	    echo "<td><input type=radio name=\"crewchief[$curdisttimesid]\" value=\"$row[offid]\"";
	    if($row[crewchief]=='x') echo " checked";
	    echo ">Crew Chief</td></tr>";
            $curct++; $ix++;
         }
         while($curct<3)
         {
            $varname2="offname".$ix; $varname1=$varname2."id";
            echo "<input type=hidden name=\"timeid[$ix]\" value=\"$curdisttimesid\">";
            echo "<input type=hidden name=\"$varname1\" id=\"$varname1\" value=\"0\">";
            echo "<tr align=left>";
            if($mode=="Switch to Quick Mode" || $hiddenmode=="quick")   //in quick mode
               echo "<td colspan=2><table cellspacing=0 cellpadding=0><tr align=left><td><input class=tiny type=text size=25 name=\"$varname2\" id=\"$varname2\" onclick=\"this.value='';\" value=\"[Type all or part of name]\" onkeyup=\"OffAssign.lookupOffs('$varname2',this.value);\"></td></tr><tr alig=left><td><div style=\"display:none\" class=\"searchresults\" id=\"".$varname2."List\" name=\"".$varname2."List\"></div></td></tr></table></td>";
            else
               echo "<td colspan=2><input type=text name=\"$varname2\" value=\"[Click to Choose Official]\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$curdisttimesid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=20></td>";
	    echo "</tr>";
            $ix++; $curct++;
         }
	 echo "</table></td></tr>";
      }
      echo "</table></td></tr></table>";
   }
   echo "</td></tr>";
   }//end if NOT STATE
   else 		//STATE
   {
      echo "<tr align=center><td><table><tr align=left valign=top>";
      //first show current state assignments
      $sql3="SELECT offid,twoday FROM $contracts WHERE disttimesid='$stateid'";
      $result3=mysql_query($sql3);

      $ix=0;
      while($row3=mysql_fetch_array($result3))
      { 
         if($ix%10==0) echo "<td>";
		 $varname2="offname".$ix; $varname1=$varname2."id";
            echo "<input type=hidden name=\"timeid[$ix]\" value=\"$curdisttimesid\">";
            echo "<input type=hidden name=\"$varname1\" id=\"$varname1\" value=\"$row3[offid]\">"; 
            //echo "<tr align=left>";
            if($mode=="Switch to Quick Mode" || $hiddenmode=="quick") //in quick mode
               {
			   echo "<table cellspacing=0 cellpadding=0><tr align=left><td><input class=tiny type=text size=20 name=\"$varname2\" id=\"$varname2\" value=\"".GetOffName($row3[offid])."\" onkeyup=\"OffAssign.lookupOffs('$varname2',this.value);\"></td><td><input type=checkbox name=\"twoday[$ix]\" value='x'";
			   if($row3[twoday]=='x') echo " checked";
			   echo ">2 Days Only&nbsp;";
			   echo "</td></tr><tr align=left><td><div style=\"display:none\" class=\"searchresults\" id=\"".$varname2."List\" name=\"".$varname2."List\"></div></td></tr></table>";
            }
			else
			{
               echo "<input type=text name=\"$varname2\" value=\"".GetOffName($row3[offid])."\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$curdisttimesid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=20>";
            }
            if($mode=="Switch to Quick Mode" || $hiddenmode=="quick")
             {echo ""; }
             else			 
			{		echo "<input type=checkbox name=\"twoday[$ix]\" value='x'";
			 if($row3[twoday]=='x') echo " checked";
			 echo ">2 Days Only&nbsp;";
			 }
			 echo "&nbsp;&nbsp;&nbsp;<br>";
/*       $varname1="offname".$ix."id"; $varname2="offname".$ix;
		 echo "<input type=hidden name=\"timeid[$ix]\" value=\"$curdisttimesid\">";
         echo "<input type=hidden name=\"$varname1\" value=\"$row3[offid]\">";
		 if($mode=="Switch to Quick Mode" || $hiddenmode=="quick")   //in quick mode
		 echo "<td colspan=2><table cellspacing=0 cellpadding=0><tr align=left><td><input class=tiny type=text size=25 name=\"$varname2\" id=\"$varname2\" onclick=\"this.value='';\" value=\"[Type all or part of name]\" onkeyup=\"OffAssign.lookupOffs('$varname2',this.value);\"></td></tr><tr alig=left><td><div style=\"display:none\" class=\"searchresults\" id=\"".$varname2."List\" name=\"".$varname2."List\"></div></td></tr></table></td>";
         else
		 echo "<input type=text name=\"$varname2\" value=\"".GetOffName($row3[offid])."\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$stateid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=25>";
         echo "<input type=checkbox name=\"twoday[$ix]\" value='x'";
         if($row3[twoday]=='x') echo " checked";
         echo ">2 Days Only&nbsp;";
         echo "&nbsp;&nbsp;&nbsp;<br>"; */ 
         if(($ix+1)%10==0) echo "</td>";
         $ix++;
      }
      while($ix<20)
      {
         if($ix%10==0) echo "<td>";
		 		 $varname2="offname".$ix; $varname1=$varname2."id";
            echo "<input type=hidden name=\"timeid[$ix]\" value=\"$curdisttimesid\">";
            echo "<input type=hidden name=\"$varname1\" id=\"$varname1\" value=\"0\">";
            //echo "<tr align=left>";
            if($mode=="Switch to Quick Mode" || $hiddenmode=="quick") //in quick mode
               echo "<table cellspacing=0 cellpadding=0><tr align=left><td><input class=tiny type=text size=20 name=\"$varname2\" id=\"$varname2\" value=\"\"  placeholder=\"[Type all or part of name]\"onkeyup=\"OffAssign.lookupOffs('$varname2',this.value);\"></td><td><input type=checkbox name=\"twoday[$ix]\" value='x'>2 Days Only&nbsp</td></tr><tr align=left><td><div style=\"display:none\" class=\"searchresults\" id=\"".$varname2."List\" name=\"".$varname2."List\"></div></td></tr></table>";
            else
               echo "<input type=text name=\"$varname2\" value=\"[Click to Choose Official]\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$curdisttimesid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=20>";
             if($mode=="Switch to Quick Mode" || $hiddenmode=="quick")
             {echo ""; }
             else			 
			{		echo "<input type=checkbox name=\"twoday[$ix]\" value='x'";
			 if($row3[twoday]=='x') echo " checked";
			 echo ">2 Days Only&nbsp;";
			 }
			 echo "&nbsp;&nbsp;&nbsp;<br>";
/*           $varname1="offname".$ix."id"; $varname2="offname".$ix;
         echo "<input type=hidden name=\"$varname1\" value=\"0\">";
         echo "<input type=text name=\"$varname2\" value=\"[Click to Choose Official]\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&disttimesid=$stateid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=25>";
         echo "<input type=checkbox name=\"twoday[$ix]\" value='x'>2 Days Only&nbsp;";
         echo "&nbsp;&nbsp;&nbsp;<br>";   */
         if(($ix+1)%10==0) echo "</td>";
         $ix++;
      }
      echo "</tr></table></td></tr>";
   } 
   echo "<input type=hidden name=total value=$ix>";
   echo "<input type=hidden name=filteragain value=$filter>";
   echo "<tr align=center><td colspan=5><input type=submit name=save value=\"Save Changes\"></td></tr>";
   echo "</table>";
}
echo "</form></div>";
?>
<div id="loading" style="display:none;"></div>
<?php
echo $end_html;
?>
