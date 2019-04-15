<?php
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

if($type=="State") $classdist="";

if($save || $hiddensave)	
{
   for($i=0;$i<$total;$i++)
   {
      $var1="assign".$i; $var2="offname".$i;
      if($$var2!="[Click to Choose Official]")
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
         $var1="assign".$i;
         if($$var1==$row2[0]) $assigned=1;
      }
      if($assigned==0)
      {
         $sql3="DELETE FROM $contracts WHERE offid='$row2[0]' AND distid='$distid'";
         $result3=mysql_query($sql3);
      }
   }
}

echo $init_html;
echo GetHeader($session);

echo "<br><form method=post action=\"assignhost.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<select name=sport onchange=\"submit();\"><option value=''>Choose Sport</option>";
for($i=0;$i<count($activity);$i++)
{
   if(TableExists($activity[$i]."contracts"))
   {
      echo "<option value=\"$activity[$i]\"";
      if($sport==$activity[$i]) echo " selected";
      echo ">".GetSportName($activity[$i])."</option>";
   }
}
echo "</select>&nbsp;";
echo "<a class=small href=\"hostcontracts.php?session=$session&sport=$sport\">Main Menu</a><br><br>";

if($sport && $sport!="~")
{
   $sportname=GetSportName($sport);
   echo "<table width=500><caption><b>Assign $sportname Hosts:&nbsp;";
   echo "<select onchange=\"submit();\" name=type>";
   echo "<option value='~'>Type</option>";
   if($sport=='vb') $types=array("District","Subdistrict");
   for($i=0;$i<count($types);$i++)
   {
      echo "<option";
      if($type==$types[$i]) echo " selected";
      echo ">$types[$i]</option>";
   }
   echo "</select>";
   if($type && $type!="~")
   {
      echo "<select onchange=\"submit();\" name=distid>";
      echo "<option value='~'>Choose $type</option>";
      $sql="SELECT DISTINCT id,class,district FROM $districts WHERE type='$type' ORDER BY class,district";
echo $sql;
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         echo "<option value='$row[id]'";
         if($distid==$row[id]) echo " selected";
         echo ">$row[class]-$row[district]</option>";
      }
      echo "</select>";
   }
   echo "<hr>";
   echo "</caption>";
}

if($distid && $distid!='~') //specific District/Subdistrict chosen:
{
   /****DISTRICT INFO****/
   echo "<tr align=left><td colspan=2><table>";
   echo "<tr align=left><td colspan=2><b>District Information:<hr></b></td></tr>";
   $temp=split("-",$classdist);
   $sql="SELECT * FROM $districts WHERE type='$type' AND class='$temp[0]' AND district='$temp[1]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $class=$temp[0]; $dist=$temp[1];
   echo "<tr align=left><td><b>$type:</b></td><td>$classdist</td></tr>";
   echo "<tr align=left><td><b>Director:</b></td><td>$row[first] $row[last]</td></tr>";
   echo "<tr align=left><td><b>Host School:</b></td><td>$row[hostschool]</td></tr>";
   echo "<tr align=left><td><b>Site:</b></td><td>$row[site]</td></tr>";
   echo "<tr align=left><td><b>Dates:</b></td><td>$row[dates]</td></tr>";
   echo "<tr align=left><td><b>Schools:</b></td><td>$row[schools]</td></tr>";
   echo "</table><br>";
   echo "</td></tr>";
   $max=4;

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
      $varname1="assign".$ix; $varname2="offname".$ix;
      echo "<input type=hidden name=\"$varname1\" value=\"$row[offid]\">";
      echo "<td align=center>";
      echo "<input type=text name=\"$varname2\" value=\"".GetOffName($row[offid])."\" onClick=\"window.open('offspick.php?sport=$sport&distid=$distid&session=$session&andor=$andor&zones=$zones&dates=$dates&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=25>";
      echo "</td>";
      if(($ix+1)%2==0) echo "</tr>";
      $ix++;
   }
   while($ix<$max)
   {
      if($ix%2==0) echo "<tr align=center>";
      $varname1="assign".$ix; $varname2="offname".$ix;
      echo "<input type=hidden name=\"$varname1\" value=\"0\">";
      echo "<td align=center>";
      echo "<input type=text name=\"$varname2\" value=\"[Click to Choose Official]\" onClick=\"window.open('offspick.php?sport=$sport&distid=$distid&session=$session&andor=$andor&zones=$zones&dates=$dates&ix=$ix','offspick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=25>";
      echo "</td>";
      if(($ix+1)%2==0) echo "</tr>";
      $ix++;
   }
   echo "</table></td></tr>";
   echo "<input type=hidden name=total value=$ix>";
   echo "<input type=hidden name=distid value=$distid>";
   echo "<input type=hidden name=filteragain value=$filter>";
   echo "<tr align=center><td colspan=2><br><input type=submit name=save value=\"Save Changes\"></td></tr>";
   echo "</table>";
}
echo "</form>";

echo $end_html;
?>
