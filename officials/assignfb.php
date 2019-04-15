<?php

require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);
$thisyr=GetFallYear('fb');

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

$sport='fb';
$brackets=$sport."brackets";
$contracts=$sport."contracts";
$zonetbl=$sport."_zones";
$apply=$sport."apply";
$offtable=$sport."off";

if($save || $hiddensave)	
{
   for($i=0;$i<count($bracketid);$i++)
   {
      $newoffid="offname".$i."id";
      $oldoffid=$oldoffids[$i];
      if($$newoffid!="0" && ($oldoffid=="none" || $oldoffid=="0"))	//brand new assignment
      {
	 $gameid=$bracketid[$i];
	 //put new assignment into database
	 $now=time();
	 if($reassign[$i]=='x')
	    $sql="INSERT INTO $contracts (offid,gameid,reassign,post,accept,confirm) VALUES ('".$$newoffid."','$gameid','$now','','','')";
	 else
	    $sql="INSERT INTO $contracts (offid,gameid,post,accept,confirm) VALUES ('".$$newoffid."','$gameid','','','')";
	 $result=mysql_query($sql);
	 $reass=2;
      }
      else if($$newoffid!=$oldoffid && $oldoffid!="none") //replacing old off with new one
      {
	 //replacing old off with new one OR reseting slot
	 if($$newoffid==0)	//reset this slot
	 {
	    $gameid=$bracketid[$i];
  	    $sql="DELETE FROM $contracts WHERE offid='$oldoffid' AND gameid='$gameid'";
	    $result=mysql_query($sql);
	    $reass=2;
	 }
	 else
	 {
	    if($reassign[$i]=='x')	//reassign box was checked
	       $reass=1;
	    else
	       $reass=0;
	 }
      }
      else
      {
	 $reass=2;	//don't change this record at all
      }
      if($reass!=2)
      {
	 $gameid=$bracketid[$i];
	 $now=time();
	 //remove oldoffid's record for this game and replace it with newoffid
	 $sql="UPDATE $contracts SET offid='".$$newoffid."',post='',accept='',confirm=''";
	 if($reass==1) $sql.=",reassign='$now'";
	 $sql.=" WHERE gameid='$gameid'";
	 $result=mysql_query($sql);
      }
   }
}

echo $init_html_ajax;
?>
</head>
<body onload="OffAssign.initialize('<?php echo $session; ?>','fb');">
<?php
echo GetHeader($session,"contractadmin");
echo "<br>";

//allow user to choose sport and then class/dist or state
echo "<div id=\"baselayer\" style=\"position:relative;z-index:1\">";
echo "<a class=small href=\"fbassignreport.php?session=$session\">Football Assignments Report</a>&nbsp;&nbsp;";
echo "<a class=small href=\"fbcontracts.php?session=$session&classch=$classch\">Submitted Football Contracts</a><br><br>";
echo "<form method=post name=assignform action=\"assignfb.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<input type=hidden name=hiddensave>";
echo "<input type=hidden name=zones>";
echo "<input type=hidden name=dates>";
echo "<input type=hidden name=andor>";
echo "<table";
if(!$classch || !$round) echo " width=90%";
echo "><caption>";
echo "<b>Football Playoffs Officials Assignments:<br></b>";
echo "Choose a Class:&nbsp;<select onchange=\"submit();\" name=classch>";
echo "<option>~</option>";
$classes=array("A","B","C1","C2","D1","D2","D6");
for($i=0;$i<count($classes);$i++)
{
   echo "<option";
   if($classch==$classes[$i]) echo " selected";
   echo ">$classes[$i]</option>";
}
echo "</select>";
if($classch && $classch!="~")
{
   if($classch=="A" || $classch=="B" || $classch=="C1" || $classch=="C2" || $classch=="D6")
   {
      $rounds=array("First Round","Quarterfinals","Semifinals","Finals");
   }
   else
   {
      $rounds=array("First Round","Second Round","Quarterfinals","Semifinals","Finals");
   }
   echo "&nbsp;Choose a Round:&nbsp;";
   echo "<select onchange=\"submit();\" name=round>";
   echo "<option>~</option>";
   for($i=0;$i<count($rounds);$i++)
   {
      echo "<option";
      if($round==$rounds[$i]) echo " selected";
      echo ">$rounds[$i]</option>";
   }
   echo "</select>";
}
echo "<br>OR:&nbsp;<a class=small href=\"fbbrackets.php?session=$session\">Edit Football Brackets & Game Information</a><br><br>";
echo "</caption>";

if($classch && $classch!='~' && $round && $round!='~')
{
   //show sample football contract link:
   $sql="SELECT * FROM $brackets WHERE class='$classch' AND round='$round' ORDER BY id LIMIT 1";
//echo $sql;
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   echo "<tr align=left><td colspan=4>";
   if($round!='Finals')
      echo "<a class=small target=new href=\"fbcontract.php?session=$session&gameid=$row[id]&sample=1\">Sample Class $classch $round Football Contract</a></td></tr>";
   else
      echo "<a class=small target=\"_blank\" href=\"fbstatecontract.php?session=$session&sample=1\">Sample Class $classch $round Football Contract</a></td></tr>";
   echo "<tr align=left><td colspan=4>";
   if($mode=="Switch to Quick Mode" || $hiddenmode=="quick") //in quick mode
   {
      echo "<input type=button name=mode value=\"Switch to Normal Mode\" onclick=\"hiddenmode.value='normal';submit();\"><br><br>";
      echo "<i>NOTE: To remove an official from an assignment in Quick Mode, simply delete the official's<br>name from the text box and click \"Save Changes\".</i>";
      echo "<input type=hidden name=hiddenmode value=\"quick\">";
   }
   else      //in normal mode
   {
      echo "<input type=button name=mode value=\"Switch to Quick Mode\" onclick=\"hiddenmode.value='quick';submit();\"><br><br>";
      echo "<i>NOTE: To remove an official from an assignment in Normal Mode, click on the box with the official's<br>name and then in the window that pops up, click on the \"RESET\" link.</i>";
      echo "<input type=hidden name=hiddenmode value=\"normal\">";
   }
   echo "</td></tr>";
   $sql="SELECT * FROM $brackets WHERE class='$classch' AND round='$round' ORDER BY id";
   $result=mysql_query($sql);
   $ix=0;
   echo "<tr align=left>";
   echo "<td><b><u>Date</u></b></td>";
   echo "<td><b><u>Game Time</u></b></td><td><b><u>Teams</u></b></td><td><b><u>Crew Chief</b></u></td></tr>";
   if($classch=='A' || $classch=='B' || $classch=="C1" || $classch=="C2" || $classch=="D6")
      $rounds=array("First Round","Quarterfinals","Semifinals","Finals");
   else
      $rounds=array("First Round","Second Round","Quarterfinals","Semifinals","Finals");
//echo $sql;
   while($row=mysql_fetch_array($result))
   {
      $roundnum=0;
      for($i=0;$i<count($rounds);$i++)
      {
         if($rounds[$i]==$row[round]) $roundnum=$i+1;
      }
      $sql3="SELECT * FROM $db_name.fbsched WHERE class='$classch' AND round='$roundnum' AND gamenum='$row[gamenum]'";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      $day=$row3[received];
      $sql2="SELECT * FROM $contracts WHERE gameid='$row[id]' ORDER BY offid";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      echo "<tr align=left>";
      $num=$ix+1;
      $date=split("-",$day);
      $curdate=date("F j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      if(substr($day,5,5)=="00-00") $curdate="&nbsp;";
      echo "<td>$num)&nbsp;&nbsp;$curdate</td>";
      echo "<td>$row3[gametime]</td>";
      echo "<td>";
      $school1=GetSchoolName($row3[sid],'fb',$thisyr);
      $school2=GetSchoolName($row3[oppid],'fb',$thisyr);
      if($row[homeid]==$row3[sid])	//school 1 is host
	 echo "$school2 @ <b>$school1</b>";
      else if($row[homeid]==$row3[oppid])	//school 2 is host
	 echo "$school1 @ <b>$school2</b>";
      else
         echo "$school1 VS $school2";
      echo "</td>";
      echo "<input type=hidden name=\"oldoffids[$ix]\" ";
      if(mysql_num_rows($result2)>0) echo "value=\"$row2[offid]\">";
      else echo "value=\"none\">";
      echo "<td>";
      $varname1="offname".$ix."id"; $varname2="offname".$ix;
      echo "<input type=hidden name=\"bracketid[$ix]\" value=\"$row[id]\">";
      if(mysql_num_rows($result2)==0)  
      { 
         $curoffid="0"; 	
	 if($mode=="Switch to Quick Mode" || $hiddenmode=='quick')
	    $curoff="[Type all of part of crew chief`s name]";
	 else
            $curoff="[Click to Choose Crew Chief]"; 
      }
      else 
      { 
	 $curoffid=$row2[offid]; 
         $curoff=GetOffName($row2[offid]); 
      }
      echo "<input type=hidden name=\"$varname1\" id=\"$varname1\" value=\"$curoffid\">";
      if($mode=="Switch to Quick Mode" || $hiddenmode=="quick") //in quick mode
         echo "<table cellspacing=0 cellpadding=0><tr align=left><td><input class=tiny type=text size=30 name=\"$varname2\" id=\"$varname2\" value=\"$curoff\" onkeyup=\"OffAssign.lookupOffs('$varname2',this.value);\" onclick=\"if(this.value='[Type all or part of crew chief`s name]') this.value='';\"></td><td><input type=checkbox name=\"reassign[$ix]\" value='x'>Re-Assignment</td></tr><tr align=left><td colspan=2><div style=\"display:none\" class=\"searchresults\" id=\"".$varname2."List\" name=\"".$varname2."List\"></div></td></tr></table></td>";
      else
         echo "<input type=text name=\"$varname2\" id=\"$varname2\" value=\"$curoff\" onClick=\"window.open('offspick.php?zones=$zones&dates=$dates&andor=$andor&sport=$sport&distid=$row[id]&session=$session&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=30>&nbsp;<input type=checkbox name=\"reassign[$ix]\" value='x'>Re-Assignment</td>";
      echo "</tr>";
      $ix++;
   }
   echo "<input type=hidden name=filteragain value=$filter>";
   echo "<tr align=center><td colspan=4><input type=submit name=save value=\"Save Changes\"></td></tr>";
   echo "</table>";
}
echo "</form>";
echo "</div>";

?>
<div id="loading" style="display:none;"></div>
<?php
echo $end_html;
?>
