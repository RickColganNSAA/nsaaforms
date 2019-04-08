<?php

require '../functions.php';
require '../variables.php';
require 'mufunctions.php';

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

echo $init_html;
echo $header;

if($save && $delete=='x')	//DELETE THIS DISTRICT
{
   $sql="DELETE FROM mudistricts WHERE id='$distid'";
   $result=mysql_query($sql);
   $sql="UPDATE muschools SET distid=0 WHERE distid='$distid'";
   $result=mysql_query($sql);
   $distid=0;
}
else if($save || $hiddensave || $add)
{
   if(!$distid)	//NEW DISTRICT ADDED
   {
      $sql="INSERT INTO mudistricts (distnum) VALUES ('$distnum')";
      $result=mysql_query($sql);
      $distid=mysql_insert_id(); $id=$distid;
   }

      $classstr=addslashes($classstr);
      $thisclasslist="";
      for($j=0;$j<count($classchoices);$j++)
      {
	 if($classlist[$j]==$classchoices[$j])
            $thisclasslist.=$classlist[$j]."/";
      }
      $thisclasslist=substr($thisclasslist,0,strlen($thisclasslist)-1); 
   if($multiplesite=='x')
   {
      $sql="UPDATE mudistricts SET schoolid1='$schoolid1',schoolid2='$schoolid2',loginid1='$loginid1',loginid2='$loginid2',certificates='$certificates',distnum='$distnum',classes='$classstr',classlist='$thisclasslist',multiplesite='x',distid1='$distid1',distid2='$distid2' WHERE id='$distid'";
      $result=mysql_query($sql);
      //now put ensembles into sub table:
      $table="mumultiplesiteensembles";
      $sql="DELETE FROM $table WHERE distid='$distid'";
      $result=mysql_query($sql);
      for($i=0;$i<count($ensembleid);$i++)
      {
	 if($whichdistid[$i]=='1') $curdistid=$distid1;
         else if($whichdistid[$i]=='2') $curdistid=$distid2;
 	 else $curdistid=0;
	 $sql2="INSERT INTO $table (distid,ensembleid,subdistid) VALUES ('$distid','".$ensembleid[$i]."','$curdistid')";
	 $result2=mysql_query($sql2);
      }
   }
   else
   {
      $site=addslashes($site);
      $director=addslashes($director);
      $address1=addslashes($address1);
      $address2=addslashes($address2);
      $city=addslashes($city); $state=addslashes($state);
      $zip=addslashes($zip);
      $feename=addslashes($feename);
      $feeaddress1=addslashes($feeaddress1);
      $feeaddress2=addslashes($feeaddress2);
      $feecity=addslashes($feecity); $feestate=addslashes($feestate);
      $feezip=addslashes($feezip); $checks=addslashes($checks);
      $dates="";
      for($j=0;$j<count($month);$j++)
      {
         if($month[$j]!='00' && $day[$j]!='00' && $year[$j]!='0000')
   	 {
	    $curday=$year[$j]."-".$month[$j]."-".$day[$j];
	    $dates.=$curday."/";
	 }
      }
      $dates=substr($dates,0,strlen($dates)-1);
      
      $sql="UPDATE mudistricts SET schoolid1='$schoolid1',schoolid2='$schoolid2',loginid1='$loginid1',loginid2='$loginid2',certificates='$certificates',multiplesite='',distid1='0',distid2='0',distnum='$distnum',classes='$classstr',classlist='$thisclasslist',dates='$dates',site='$site',director='$director',email='$email',address1='$address1',address2='$address2',city='$city',state='$state',zip='$zip',feename='$feename',feeaddress1='$feeaddress1',feeaddress2='$feeaddress2',feecity='$feecity',feestate='$feestate',feezip='$feezip',checks='$checks' WHERE id='$id'";
      $result=mysql_query($sql);
      //echo "$sql<br>".mysql_error();
   }
}

echo "<br>";
echo "<a class=small href=\"muadmin.php?session=$session\">Main Music Menu</a><br><br>";
echo "<form method=post action=\"siteadmin.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<table";
if(!$distid || $distid=="")
   echo " width=500";
echo " class='nine'><caption><b>Music District Sites Admin:</b></caption>";

echo "<tr align=left><td colspan=2>";
echo "<select name=distid onchange=\"submit();\">";
echo "<option value='0'>Select District Site</option>";
$sql="SELECT DISTINCT id,distnum,classes,site FROM mudistricts ORDER BY distnum";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\"";
   if($distid==$row[id]) echo " selected";
   echo ">District $row[distnum] -- $row[classes] ($row[site])</option>";
}
echo "</select><input type=submit name=go value=\"Go\">   or   <a href=\"siteadmin.php?session=$session&addnew=1\">Add a New Site</a>";
echo "<hr>";
if($delete=='x')
{
   echo "<div class='alert'>The district site has been removed.</div>";
}
echo "</td></tr>";

$sql="SELECT * FROM mudistricts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$ix=0; $curbigdist="";
$year=date("Y");
$year0=$year-1; $year1=$year+1;

if($distid || $addnew)
{
   echo "<input type=hidden name=\"id\" value=\"$distid\">";
   echo "<tr align=left valign=top><td><b>District - Classes<br></b>(ex: I - C, D West):</b>";
   echo "<th align=left><input type=text name=\"distnum\" value=\"$row[distnum]\" size=3\"> - <input type=text name=\"classstr\" class=tiny size=40 value=\"$row[classes]\"></td></tr>";
   $curclasslist=split("/",$row[classlist]);
   echo "<tr align=left><td><b>Check Classes Included:</b></td>";
   echo "<td align=left>";
   for($i=0;$i<count($classchoices);$i++)
   {
      echo "<input type=checkbox name=\"classlist[$i]\" value=\"$classchoices[$i]\"";
      for($j=0;$j<count($curclasslist);$j++)
      {
	 if($curclasslist[$j]==$classchoices[$i]) echo " checked";
      }
      echo ">$classchoices[$i]&nbsp;&nbsp;";
   }
   echo "</td></tr>";
   echo "<tr align=left><td colspan=2><input type=checkbox onclick=\"hiddensave.value='Save';submit();\" value='x'";
   if($row[multiplesite]=='x') echo " checked";
   echo " name=\"multiplesite\"> CHECK HERE IF THIS IS A DISTRICT IN WHICH SCHOOLS WILL SEND ENSEMBLES TO 2 SITES</td></tr>";
   if($row[multiplesite]=='x')	//select 2 districts schools will send ensembles to:
   {
      echo "<tr align=center><td colspan=2><table>";
      echo "<tr align=left valign=top><td width=50%>Please select the first site:<br>";
      echo "<select name=\"distid1\"><option value='0'>Select Site #1</option>";
      $sql2="SELECT * FROM mudistricts WHERE id!='$distid' AND distnum='$row[distnum]' ORDER BY distnum,classlist";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         echo "<option value=\"$row2[id]\"";
         if($row[distid1]==$row2[id]) echo " selected";
	 echo ">$row2[distnum]--$row2[classes] ($row2[site])</option>";
      }
      echo "</select>";
      echo "</td><td width=50%>Please select the second site:<br>";
      echo "<select name=\"distid2\"><option value='0'>Select Site #2</option>";
      $sql2="SELECT * FROM mudistricts WHERE id!='$distid' AND distnum='$row[distnum]' ORDER BY distnum,classlist";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         echo "<option value=\"$row2[id]\"";
         if($row[distid2]==$row2[id]) echo " selected";
         echo ">$row2[distnum]--$row2[classes] ($row2[site])</option>";
      }
      echo "</select>";
      echo "</td></tr>";
      echo "<tr align=left><td colspan=2>Please check the circle under the site at which each ensemble will be contested:</td></tr>";
      $sql2="SELECT * FROM muensembles ORDER BY categid,orderby";
      $result2=mysql_query($sql2);
      $i=0;
      while($row2=mysql_fetch_array($result2))
      {
         echo "<input type=hidden name=\"ensembleid[$i]\" value=\"$row2[id]\">";
         $sql3="SELECT * FROM mumultiplesiteensembles WHERE ensembleid='$row2[id]' AND distid='$distid'";
	 $result3=mysql_query($sql3);
	 $row3=mysql_fetch_array($result3);
         echo "<tr align=left><td width=50%><input type=radio name=\"whichdistid[$i]\" value=\"1\"";
	 if($row3[subdistid]==$row[distid1]) echo " checked";
	 echo ">$row2[ensemble]</td>";
         echo "<td width=50%><input type=radio name=\"whichdistid[$i]\" value=\"2\"";
         if($row3[subdistid]==$row[distid2]) echo " checked";
         echo ">$row2[ensemble]</td></tr>";
	 $i++;
      }
      echo "</table></td></tr>";
   }
   else
   {
   $dates=split("/",$row[dates]);
   echo "<tr valign=top align=left><td><b>Dates:</b></td>";
   echo "<td>";
   for($i=0;$i<count($dates);$i++)
   {
      $curdate=split("-",$dates[$i]);
      echo "<select name=\"month[$i]\"><option value='00'>MM</option>";
      for($m=1;$m<=12;$m++)
      {
         if($m<10) $num="0".$m;
         else $num=$m;
     	 echo "<option";
	 if($curdate[1]==$num) echo " selected";
         echo ">$num</option>";
      }
      echo "</select>/<select name=\"day[$i]\"><option value='00'>DD</option>";
      for($d=1;$d<=31;$d++) 
      {
	 if($d<10) $num="0".$d;
	 else $num=$d;
	 echo "<option";
	 if($curdate[2]==$num) echo " selected";
	 echo ">$num</option>";
      }
      echo "</select>/<select name=\"year[$i]\"><option value='0000'>YYYY</option>";
      for($y=$year0;$y<=$year1;$y++)
      {
	 echo "<option";
	 if($curdate[0]==$y) echo " selected";
	 echo ">$y</option>";
      }
      echo "</select><br>";
   } 
   while($i<4)
   {
      echo "<select name=\"month[$i]\"><option value='00'>MM</option>";
      for($m=1;$m<=12;$m++) 
      {
         if($m<10) $num="0".$m;
         else $num=$m;
         echo "<option";
         echo ">$num</option>";
      }
      echo "</select>/<select name=\"day[$i]\"><option value='00'>DD</option>";
      for($d=1;$d<=31;$d++) 
      {
         if($d<10) $num="0".$d;
         else $num=$d;
         echo "<option";
         echo ">$num</option>";
      }
      echo "</select>/<select name=\"year[$i]\"><option value='0000'>YYYY</option>";
      for($y=$year0;$y<=$year1;$y++)
      {
         echo "<option";
         echo ">$y</option>";
      }
      echo "</select><br>";
      $i++;
   }
   echo "</td></tr>";
   echo "<tr align=left><td><b>Site:</b></td>";
   echo "<td><input type=text class=tiny size=30 name=\"site\" value=\"$row[site]\"></td></tr>";
   echo "<tr align=left><td><b>Director:</b></td>";
   echo "<td><input type=text class=tiny size=30 name=\"director\" value=\"$row[director]\"></td></tr>";
   echo "<tr align=left><td><b>Director's E-mail:</b></td>";
   echo "<td><input type=text class=tiny size=30 name=\"email\" value=\"$row[email]\"></td></tr>";
   echo "<tr align=left valign=top><td><b>Address:</b></td>";
   echo "<td><input type=text class=tiny size=20 name=\"address1\" value=\"$row[address1]\"><br>";
   echo "<input type=text class=tiny size=20 name=\"address2\" value=\"$row[address2]\"></td></tr>";
   echo "<tr align=left><td><b>City, State Zip:</b></td>";
   echo "<td><input type=text class=tiny size=20 name=\"city\" value=\"$row[city]\">, ";
   if($row[state]=='') $row[state]="NE";
   echo "<input type=text class=tiny size=3 name=\"state\" value=\"$row[state]\"> ";
   echo "<input type=text class=tiny size=10 name=\"zip\" value=\"$row[zip]\"></td></tr>";
   echo "<tr align=left><td colspan=2><input type=checkbox name=\"same\"";
   if($row[feename]=='' && $row[feeaddress1]=='') echo " checked";
   echo "> Have schools send their <b>fees</b> to the address above.</td></tr>";
   echo "<tr align=left><td colspan=2><b>Have schools send their fees to this address:</b></td></tr>";
   echo "<tr align=left><td><b>Addressee:</b></td>";
   echo "<td><input type=text class=tiny size=30 name=\"feename\" value=\"$row[feename]\"></td></tr>";
   echo "<tr align=left valign=top><td><b>Address:</b></td>";
   echo "<td><input type=text class=tiny size=20 name=\"feeaddress1\" value=\"$row[feeaddress1]\"><br>";
   echo "<input type=text class=tiny size=20 name=\"feeaddress2\" value=\"$row[feeaddress2]\"></td></tr>";
   echo "<tr align=left><td><b>City, State Zip:</b></td>";
   echo "<td><input type=text class=tiny size=20 name=\"feecity\" value=\"$row[feecity]\">, ";
   if($row[feestate]=='') $row[feestate]="NE";
   echo "<input type=text class=tiny size=3 name=\"feestate\" value=\"$row[feestate]\"> ";
   echo "<input type=text class=tiny size=10 name=\"feezip\" value=\"$row[feezip]\"></td></tr>";
   echo "<tr align=left><td><b>Make Checks Payable to:</b></td>";
   echo "<td><input type=text class=tiny size=30 name=\"checks\" value=\"$row[checks]\"></td></tr>";
   echo "<tr align=center><td colspan=2><hr></td></tr>";
   }//end if multiplesite not checked
   echo "<tr align=left><td colspan=2><input type=checkbox name=\"certificates\" value=\"x\"";
   if($row[certificates]=='x') echo " checked";
   echo "> Grant access to PDF Music Certificate Generation Form for THIS SITE'S PARTICIPANTS</td></tr>";
   echo "<tr align=left><td colspan=2><b>Who can access this entry form, the certificate generation and the financial report through their NSAA login?</b></td></tr>";
   echo "<tr align=left valign=top><td>Select School(s):</td><td><select name=schoolid1><option value='0'>Select School</option>";
      $sql2="SELECT * FROM headers ORDER BY school";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
	 echo "<option value=\"$row2[id]\"";
	 if($row2[id]==$row[schoolid1]) echo " selected";
	 echo ">$row2[school]</option>";
      }
   echo "</select><br><select name=schoolid2><option value='0'>Select School</option>";
      $sql2="SELECT * FROM headers ORDER BY school";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         echo "<option value=\"$row2[id]\"";
         if($row2[id]==$row[schoolid2]) echo " selected";
         echo ">$row2[school]</option>";
      }
   echo "</select></td></tr>";
   echo "<tr valign=top align=left><td>AND/OR Select Person(People):</td><td><select name='loginid1'><option value='0'>Select Person from Non-NSAA HS</option>";
      $sql2="SELECT * FROM logins WHERE level=4 OR level=5 ORDER BY school";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
	 echo "<option value=\"$row2[id]\"";
	 if($row2[id]==$row[loginid1]) echo " selected";
	 echo ">$row2[school]: $row2[name]</option>";
      }
   echo "</select><br><select name='loginid2'><option value='0'>Select Person from Non-NSAA HS</option>";
      $sql2="SELECT * FROM logins WHERE level=4 OR level=5 ORDER BY school";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
         echo "<option value=\"$row2[id]\"";
         if($row2[id]==$row[loginid2]) echo " selected";
         echo ">$row2[school]: $row2[name]</option>";
      }
   echo "</select></td></tr>";
   $ix++;
   if($distid>0)
   {
      echo "<tr align=center><td colspan=2><hr><div class='help'><input type=checkbox name=\"delete\" value=\"x\"> <b>Check this box to DELETE this site from the list of District Music Contest Sites.</b></div></td></tr>";
      echo "<tr align=center><td colspan=2><input type=submit name=\"save\" value=\"Save District Info\"></td></tr>";
   }
   else
      echo "<tr align=center><td colspan=2><hr><input type=submit name=\"add\" value=\"Add District\"></td></tr>";
   echo "<input type=hidden name=\"hiddensave\" id=\"hiddensave\">";
}
echo "</table></form>";
echo $end_html;
?>
