<?php

require '../functions.php';
require '../variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}

if($copy)
{
   $sql="SELECT * FROM mudistricts WHERE id='$copydistid'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $sql2="UPDATE mudistricts SET surchargeAA='$row[surchargeAA]', surchargeA='$row[surchargeA]', surchargeB='$row[surchargeB]', surchargeC='$row[surchargeC]', surchargeD='$row[surchargeD]', nondistfee='$row[nondistfee]' WHERE id='$distid'";
   $result2=mysql_query($sql2);

   $sql="SELECT * FROM mufees WHERE distid='$copydistid'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $sql2="SELECT * FROM mufees WHERE distid='$distid' AND ensembleid='$row[ensembleid]'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)
      {
         $sql3="INSERT INTO mufees (ensembleid,distid,fee) VALUES ('$row[ensembleid]','$distid','$row[fee]')";
      }
      else
      {
    	 $sql3="UPDATE mufees SET fee='$row[fee]' WHERE ensembleid='$row[ensembleid]' AND distid='$distid'";
      }
      $result3=mysql_query($sql3);
   }
}
else if($save)
{
   $sql="UPDATE mudistricts SET surchargeAA='$surchargeAA', surchargeA='$surchargeA', surchargeB='$surchargeB', surchargeC='$surchargeC', surchargeD='$surchargeD', nondistfee='$nondistfee' WHERE id='$distid'";
   $result=mysql_query($sql);

   //SMALL ENSEMBLES:
   $sql="SELECT t1.id FROM muensembles AS t1, mucategories AS t2 WHERE t1.categid=t2.id AND t2.smlg='Small'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $ensid=$row[id];
      $sql2="SELECT * FROM mufees WHERE ensembleid='$ensid' AND distid='$distid'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)
	 $sql3="INSERT INTO mufees (ensembleid,distid,fee) VALUES ('$ensid','$distid','$smallfee')";
      else
	 $sql3="UPDATE mufees SET fee='$smallfee' WHERE ensembleid='$ensid' AND distid='$distid'";
      $result3=mysql_query($sql3);
   } 
	//MISC SMALL VOC & INST ENSEMBLE
   $sql="SELECT id FROM muensembles WHERE categid=0";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $ensid=$row[id];
   $sql2="SELECT * FROM mufees WHERE ensembleid='$ensid' AND distid='$distid'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)         
      $sql3="INSERT INTO mufees (ensembleid,distid,fee) VALUES ('$ensid','$distid','$smallfee')";
   else
      $sql3="UPDATE mufees SET fee='$smallfee' WHERE ensembleid='$ensid' AND distid='$distid'";      
   $result3=mysql_query($sql3);
  
   //SOLOS:
   $sql="SELECT id FROM muensembles WHERE ensemble LIKE '%Solo%'";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $ensid=$row[id];
      $sql2="SELECT * FROM mufees WHERE ensembleid='$ensid' AND distid='$distid'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)===0)
         $sql3="INSERT INTO mufees (ensembleid,distid,fee) VALUES ('$ensid','$distid','$solofee')";
      else
         $sql3="UPDATE mufees SET fee='$solofee' WHERE ensembleid='$ensid' AND distid='$distid'";
      $result3=mysql_query($sql3);
   }

   //LARGE ENSEMBLES
   for($i=0;$i<count($ensembleid);$i++)
   {
      $sql="SELECT * FROM mufees WHERE ensembleid='$ensembleid[$i]' AND distid='$distid'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)
	 $sql2="INSERT INTO mufees (ensembleid,distid,fee) VALUES ('$ensembleid[$i]','$distid','$fee[$i]')";
      else
	 $sql2="UPDATE mufees SET fee='$fee[$i]' WHERE ensembleid='$ensembleid[$i]' AND distid='$distid'";
      $result2=mysql_query($sql2);
   }
}

echo $init_html;
echo $header;

echo "<br>";
echo "<form method=post action=\"feeadmin.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table><caption><b>Music: Manage Fee Schedules</b><br><br>";
echo "<p><a href=\"muadmin.php?session=$session\">Return to Main Music Menu</a></p>";
echo "<br></caption>";
echo "<tr align=center><td colspan=2>";
echo "<b>Select a District: <select name=distnum onchange=\"this.form.distid.value='';submit();\"><option value=''>~</option>";
$sql="SELECT DISTINCT distnum FROM mudistricts ORDER BY id";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option";
   if($distnum==$row[0]) echo " selected";
   echo ">$row[0]</option>";
}
echo "</select>&nbsp;&nbsp;<b>Site: <select name=distid onchange=\"submit();\"><option value=''>~</option>";
if($distnum && $distnum!='')
{
   $sql="SELECT id,classes,site FROM mudistricts WHERE multiplesite!='x' AND distnum='$distnum' ORDER BY classes";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\"";
      if($distid==$row[id]) echo " selected";
      echo ">$row[classes] ($row[site])</option>";
   }
   echo "</select>";
}
echo "</td></tr>";

if($distid && $distid!='')
{
   echo "<tr align=left><td colspan=2>Copy fees from <select name=copydistid><option value=''>~</option>";
   $sql="SELECT * FROM mudistricts WHERE distnum='$distnum' AND id!='$distid' ORDER BY id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      echo "<option value=\"$row[id]\">$row[distnum] -- $row[classes] ($row[site])</option>";
   }
   echo "</select> to this site. <input type=submit name=\"copy\" value=\"Copy\">";
   echo "<br><br></td></tr>";

   $sql="SELECT * FROM mudistricts WHERE id='$distid' LIMIT 1";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $surchargeAA=number_format($row[surchargeAA],2,'.','');
   $surchargeA=number_format($row[surchargeA],2,'.','');
   $surchargeB=number_format($row[surchargeB],2,'.','');
   $surchargeC=number_format($row[surchargeC],2,'.','');
   $surchargeD=number_format($row[surchargeD],2,'.','');
   $nondistfee=number_format($row[nondistfee],2,'.','');

   //SURCHARGES
   echo "<tr align=left><td colspan=2><b>SURCHARGES:</b></td></tr>";
   echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Class AA:</td>";
   echo "<td>$<input type=text class=tiny size=7 name=surchargeAA value=\"$surchargeAA\"></td></tr>";
   echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Class A:</td>";
   echo "<td>$<input type=text class=tiny size=7 name=surchargeA value=\"$surchargeA\"></td></tr>";
   echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Class B:</td>";
   echo "<td>$<input type=text class=tiny size=7 name=surchargeB value=\"$surchargeB\"></td></tr>";
   echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Class C:</td>";
   echo "<td>$<input type=text class=tiny size=7 name=surchargeC value=\"$surchargeC\"></td></tr>";
   echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Class D:</td>";
   echo "<td>$<input type=text class=tiny size=7 name=surchargeD value=\"$surchargeD\"></td></tr>";

   //SMALL ENSEMBLES
   $sql="SELECT t2.* FROM muensembles AS t1, mufees AS t2, mucategories AS t3 WHERE t1.id=t2.ensembleid AND t1.categid=t3.id AND t2.distid='$distid' AND t3.smlg='Small' LIMIT 1";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $smallfee=number_format($row[fee],2,'.','');
   echo "<tr align=left><td><b><br>SMALL ENSEMBLES:</b></td>";
   echo "<td><br>$<input type=text class=tiny size=7 name=smallfee value=\"$smallfee\"></td></tr>";

   //SOLOS
   $sql="SELECT t2.* FROM muensembles AS t1,mufees AS t2 WHERE t1.id=t2.ensembleid AND t1.ensemble LIKE '%Solo%' AND t2.distid='$distid' LIMIT 1";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $solofee=number_format($row[fee],2,'.','');
   echo "<tr align=left><td><br><b>SOLO FEE:</b></td>";
   echo "<td><br>$<input type=text class=tiny size=7 name=solofee value=\"$solofee\"></td></tr>";

   //LARGE ENSEMBLES:
   echo "<tr align=left><td colspan=2><br><b>LARGE ENSEMBLES:</b></td>";
   $sql="SELECT t1.* FROM muensembles AS t1, mucategories AS t2 WHERE t1.categid=t2.id AND t2.smlg='Large' ORDER BY t1.id";
   $result=mysql_query($sql);
   $ix=0;
   while($row=mysql_fetch_array($result))
   {
      echo "<tr align=left><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      echo "<input type=hidden name=\"ensembleid[$ix]\" value=\"$row[id]\">";
      echo "$row[ensemble]:</td>";
      $sql2="SELECT fee FROM mufees WHERE ensembleid='$row[id]' AND distid='$distid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $curfee=number_format($row2[0],2,'.','');
      echo "<td>$<input type=text class=tiny size=7 name=\"fee[$ix]\" value=\"$curfee\"></td></tr>";
      $ix++;
   }

   //NON-MEMBER FEE:
   echo "<tr align=left valign=top><td><br><b>NON-MEMBER FEE</b><br>(for schools not in District $distnum)</td>";
   echo "<td><br>$<input type=text class=tiny size=7 name=nondistfee value=\"$nondistfee\"></td></tr>";

   //SAVE BUTTON
   echo "<tr align=center><td colspan=2><br><input type=submit name=save value=\"Save\"></td></tr>";
}

echo "</table>";
echo "</form>";
echo $end_html;
?>
