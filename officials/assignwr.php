<?php
$sport="wr";

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

//get "distid" for state
$sql="SELECT id FROM $districts WHERE type='$type'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$stateid=$row[0];
if($type=="State" || $type=="State Dual") $distid=$stateid;

if($save || $hiddensave)	
{
   for($i=0;$i<$total;$i++)
   {
      $var1="offname".$i."id"; $var2="offname".$i;
      if($$var2!="[Click to Choose Official]" && $$var2!="[Type all or part of name]" && $$var2!='')
      {
         $sql="SELECT * FROM $contracts WHERE offid='".$$var1."' AND distid='$distid'";
         $result=mysql_query($sql);
         if(mysql_num_rows($result)==0)
         {
            $sql2="INSERT INTO $contracts (offid,distid) VALUES ('".$$var1."','$distid')";
            $result2=mysql_query($sql2);
            //echo $sql2."<br>".mysql_error();
         }
      }
   }

   //delete old assignments that were replaced
   $sql2="SELECT offid FROM $contracts WHERE distid='$distid' AND offid!='0'";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      $assigned=0;
      for($i=0;$i<$total;$i++)
      {
         $assignedoff="offname".$i."id"; $assignedoffname="offname".$i;
         if($$assignedoff==$row2[offid] && $$assignedoffname!="")  //yes, they were assigned
            $assigned=1;
      }
      if($assigned==0)
      {
         $sql3="DELETE FROM $contracts WHERE offid='$row2[0]' AND distid='$distid'";
         $result3=mysql_query($sql3);
      }
   }
   $sql3="DELETE FROM $contracts WHERE offid='0'";
   $result3=mysql_query($sql3);
}
echo $init_html_ajax;
?>
</head>
<body onload="OffAssign.initialize('<?php echo $session; ?>','wr');">
<?php
echo GetHeader($session,"contractadmin");
echo "<br>";
if($posted=="yes")
{
   echo "<font style=\"color:red\"><b>All Wrestling Contracts have been posted to the assigned officials.</b></font><br>";
}

//allow user to choose sport and then class/dist or state
echo "<form id=\"assignform\" name=\"assignform\" method=post action=\"assignwr.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=\"zones\" value=\"$zones\">";
echo "<input type=hidden name=\"dates\" value=\"$dates\">";
echo "<input type=hidden name=andor value=\"$andor\">";
echo "<input type=hidden name=hiddensave>";
echo "<table width=75%><caption><b>Assign Wrestling Officials:<br><br></b>";
if($sport && $sport!="~")
{
   echo "Choose a Type:&nbsp;";
   echo "<select onchange=\"dates.value='';zones.value='';submit();\" name=type>";
   echo "<option>~</option>";
   $types=array("District","State");
   $sql="SELECT DISTINCT type FROM $districts WHERE type!='' ORDER BY type";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option";
      if($type==$row[0]) echo " selected";
      echo ">$row[0]</option>";
   }
   echo "</select>";
   if($type && $type!="~" && !preg_match("/State/",$type))
   {
      $type="District";
      echo "&nbsp;Choose a District:&nbsp;";
      echo "<select onchange=\"dates.value='';zones.value='';submit();\" name=distid>";
      echo "<option value='0'>~</option>";
      $sql="SELECT id,class,district FROM $districts WHERE type='$type' ORDER BY class,district";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         echo "<option value=\"$row[id]\"";
         if($distid==$row[id]) echo " selected";
         echo ">$row[class]-$row[district]</option>";
      }
      echo "</select>";
   }
   echo "<br><br>";
   if($type=="State" || $type=="State Dual" || $distid)
   {
      echo "<a class=small href=\"".$sport."assignreport.php?session=$session&type=".strtolower(preg_replace("/ /","",$type))."\">Assignments Report</a>&nbsp;&nbsp;&nbsp;";
      echo "<a class=small href=\"".$sport."contracts.php?session=$session&type=".strtolower(preg_replace("/ /","",$type))."\">Contract Responses</a><br><br>";
      echo "<a class=small href=\"assignpost.php?return=assignwr&session=$session&sport=$sport\">POST All Wrestling Contracts</a>";
   }
   echo "<br><br>";
}
echo "</caption>";

if($type=="State" || $type=="State Dual" || $distid)
{
   /****DISTRICT INFO****/
   if(!preg_match("/State/",$type))
   {
      echo "<tr align=center><td colspan=2><table width=600>";
      echo "<tr align=left><td colspan=2><b>District Information:<hr></b></td></tr>";
      $sql="SELECT * FROM $districts WHERE id='$distid'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $class=$row['class']; $dist=$row[district];
      echo "<tr align=left><td width=100><b>$type:</b></td><td>$class-$dist</td></tr>";
      echo "<tr align=left><td width=100><b>Director:</b></td><td>$row[director]</td></tr>";
      echo "<tr align=left><td><b>Host School:</b></td><td>$row[hostschool]</td></tr>";
      echo "<tr align=left><td><b>Site:</b></td><td>$row[site]</td></tr>";
      $distdates="";
      $date=split("/",$row[dates]);
      for($i=0;$i<count($date);$i++)
      {
         $temp=split("-",$date[$i]);
         $distdates.=date("F j",mktime(0,0,0,$temp[1],$temp[2],$temp[0])).", ";
      }
      $distdates.=$temp[0]; 
      echo "<tr align=left><td><b>Dates:</b></td><td>$distdates</td></tr>";
      echo "<tr align=left><td><b>Schools:</b></td><td>$row[schools]</td></tr>";
      echo "</table></td></tr><tr align=left><td colspan=2><a name=\"timeslots\"><hr>&nbsp;</a>";
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
      $max=4;
   }
   else	//STATE AND STATE DUALS
   {
      if($mode=="Switch to Quick Mode" || $hiddenmode=="quick") //in quick mode
      {
         echo "<input type=button name=mode value=\"Switch to Normal Mode\" onclick=\"hiddenmode.value='normal';submit();\"><br><br>";
         //echo "<i>NOTE: To remove an official from an assignment in Quick Mode, simply delete the official's name from the text box and click \"Save Changes\"</i>";
         echo "<input type=hidden name=hiddenmode value=\"quick\">";
      }
      else      //in normal mode
      {
         echo "<input type=button name=mode value=\"Switch to Quick Mode\" onclick=\"hiddenmode.value='quick';submit();\"><br><br>";
         //echo "<i>NOTE: To remove an official from an assignment in Normal Mode, click on the box with the official's name and then in the window that pops up, click on the \"RESET\" link.</i>";
         echo "<input type=hidden name=hiddenmode value=\"normal\">";
      }
	  $max=24;
   }

   if($save || $hiddensave)
   {
      echo "<tr align=center><td><font style=\"color:red\"><b>The assignments below have been saved.</b></font></td></tr>";
   }

   //show textboxes to click on to choose officials
   //1) show officials already assigned to this class/dist
   echo "<tr align=center><td><table>";
   $sql="SELECT * FROM $contracts WHERE distid='$distid' ORDER BY id";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      if($ix%2==0) echo "<tr align=center>";
      $varname2="offname".$ix; $varname1=$varname2."id";
      echo "<input type=hidden name=\"$varname1\" id=\"$varname1\" value=\"$row[offid]\">";
      if($mode=="Switch to Quick Mode" || $hiddenmode=="quick") //in quick mode
         echo "<td><table cellspacing=0 cellpadding=0><tr align=left><td><input class=tiny type=text size=25 name=\"$varname2\" id=\"$varname2\" value=\"".GetOffName($row[offid])."\" onkeyup=\"OffAssign.lookupOffs('$varname2',this.value);\"></td></tr><tr align=left><td><div style=\"display:none;\" class=\"searchresults\" id=\"".$varname2."List\" name=\"".$varname2."List\"></div></td></tr></table></td>";
      else
         echo "<td><input type=text name=\"$varname2\" value=\"".GetOffName($row[offid])."\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&distid=$distid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=20></td>";
      if(($ix+1)%2==0) echo "</tr>";
      $curct++; $ix++;
   }
   while($ix<$max)
   {
      $varname2="offname".$ix; $varname1=$varname2."id";
      echo "<input type=hidden name=\"$varname1\" id=\"$varname1\" value=\"0\">";
      if($ix%2==0) echo "<tr align=left>";
      if($mode=="Switch to Quick Mode" || $hiddenmode=="quick")   //in quick mode
         echo "<td><table cellspacing=0 cellpadding=0><tr align=left><td><input class=tiny type=text size=25 name=\"$varname2\" id=\"$varname2\" onclick=\"this.value='';\" value=\"[Type all or part of name]\" onkeyup=\"OffAssign.lookupOffs('$varname2',this.value);\"></td></tr><tr align=left><td><div style=\"display:none;\" class=\"searchresults\" id=\"".$varname2."List\" name=\"".$varname2."List\"></div></td></tr></table></td>";
      else
         echo "<td><input type=text name=\"$varname2\" value=\"[Click to Choose Official]\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&distid=$distid&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=20></td>";
      if(($ix+1)%2==0) echo "</tr>";
      $ix++; $curct++;
   }
   echo "</table></td></tr>";
   echo "<input type=hidden name=total value=$ix>";
   echo "<input type=hidden name=filteragain value=$filter>";
   echo "<tr align=center><td colspan=2><br><input type=submit name=save value=\"Save Changes\"></td></tr>";
   echo "</table>";
}
echo "</form>";
?>
<div id="loading" style="display:none;"></div>
<?php
echo $end_html;
?>
