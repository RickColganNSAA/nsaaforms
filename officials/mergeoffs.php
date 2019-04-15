<?php
require 'functions.php';
require_once('variables.php');
//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

echo $init_html_ajax."</head>";
?>
<body onload="UserLookup.initialize('<?php echo $session; ?>','mergeform');">
<?php
echo GetHeader($session,"manageoff");
$space="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

if($confirm)
{
   $last=addslashes($last); $first=addslashes($first);
   $middle=addslashes($middle); $address=addslashes($address);
   $city=addslashes($city);  $notes=addslashes($notes);
   //OFFICIALS table
   $sql="UPDATE officials SET last='$last',first='$first',middle='$middle',socsec='$socsec',address='$address',city='$city',state='$state',homeph='$homeph',workph='$workph',cellph='$cellph',email='$email',notes='$notes',senttofed='$senttofed' WHERE id='$off1id'";
   $result=mysql_query($sql);
   //echo "$sql<br>";
   echo mysql_error();
   $sql="DELETE FROM officials WHERE id='$off2id'";
   $result=mysql_query($sql);
   //echo "$sql<br>";
   echo mysql_error();
   //LOGINS table
   $sql="UPDATE logins SET passcode='$passcode' WHERE offid='$off1id'";
   $result=mysql_query($sql); 
   //echo "$sql<br>";
   echo mysql_error();
   $sql="DELETE FROM logins WHERE offid='$off2id'";
   $result=mysql_query($sql);
   //echo "$sql<br>";
   echo mysql_error();
   //__OFF table
   for($i=0;$i<count($activity);$i++)
   {
      $cursp=$activity[$i]; $app=$cursp."apply"; $test=$cursp."test"; $hist=$cursp."hist";
      $apptable=$app; $testtable=$test."_results"; $offtable=$cursp."off"; $histtable=$offtable."_hist";
      //APPLY table
      if($$app==$off1id)
      {
	 //keep record with off1id the way it is, delete record with off2id
	 $sql="DELETE FROM $apptable WHERE offid='$off2id'";
	 $result=mysql_query($sql);
	 //echo "$sql<br>";
	 echo mysql_error();
      }
      else //$app=$off2id
      {
	 //delete record with off1id, change off2id record to have offid=off1id
	 $sql="DELETE FROM $apptable WHERE offid='$off1id'";
	 $result=mysql_query($sql);
         //echo "$sql<br>";
         echo mysql_error();
	 $sql="UPDATE $apptable SET offid='$off1id' WHERE offid='$off2id'";
	 $result=mysql_query($sql);
         //echo "$sql<br>";
         echo mysql_error();
      }
      //TEST table
      if($$test==$off1id)
      {
         //keep record with off1id the way it is, delete record with off2id
         $sql="DELETE FROM $testtable WHERE offid='$off2id'";
         $result=mysql_query($sql);
         //echo "$sql<br>";
         echo mysql_error();
      }
      else //$app=$off2id
      {
         //delete record with off1id, change off2id record to have offid=off1id
         $sql="DELETE FROM $testtable WHERE offid='$off1id'";
         $result=mysql_query($sql);
         //echo "$sql<br>";
         echo mysql_error();
         $sql="UPDATE $testtable SET offid='$off1id' WHERE offid='$off2id'";
         $result=mysql_query($sql);
         //echo "$sql<br>";
         echo mysql_error();
      }
      //__OFF table
      if($$cursp==$off1id)
      {
         //keep record with off1id the way it is, delete record with off2id
         $sql="DELETE FROM $offtable WHERE offid='$off2id'";
         $result=mysql_query($sql);
         //echo "$sql<br>";
         echo mysql_error();
      }
      else //$app=$off2id
      {
         //delete record with off1id, change off2id record to have offid=off1id
         $sql="DELETE FROM $offtable WHERE offid='$off1id'";
         $result=mysql_query($sql);
         //echo "$sql<br>";
         echo mysql_error();
         $sql="UPDATE $offtable SET offid='$off1id' WHERE offid='$off2id'";
         $result=mysql_query($sql);
         //echo "$sql<br>";
         echo mysql_error();
      }
      //__OFF_HIST table
      if($$hist==$off1id)
      {
         //keep record with off1id the way it is, delete record with off2id
         $sql="DELETE FROM $histtable WHERE offid='$off2id'";
         $result=mysql_query($sql);
         //echo "$sql<br>";
         echo mysql_error();
      }
      else //$app=$off2id
      {
         //delete record with off1id, change off2id record to have offid=off1id
         $sql="DELETE FROM $histtable WHERE offid='$off1id'";
         $result=mysql_query($sql);
         //echo "$sql<br>";
         echo mysql_error();
         $sql="UPDATE $histtable SET offid='$off1id' WHERE offid='$off2id'";
         $result=mysql_query($sql);
         //echo "$sql<br>";
         echo mysql_error();
      }
   }
   header("Location:edit_off.php?session=$session&offid=$off1id");
   exit();
}
if($choose)	//Show what they've chosen to save new official as and ask for confirmation
{
   echo "<form method=post action=\"mergeoffs.php\" id=\"mergeform\" name=\"mergeform\">";
   echo "<input type=hidden name=session value=\"$session\">";
   echo "<input type=hidden name=off1id value=\"$off1id\">";
   echo "<input type=hidden name=off2id value=\"$off2id\">";
   echo "<input type=hidden name=passcode value=\"$passcode\">";
   echo "<input type=hidden name=last value=\"$last\">";
   echo "<input type=hidden name=first value=\"$first\">";
   echo "<input type=hidden name=middle value=\"$middle\">";
   echo "<input type=hidden name=socsec value=\"$socsec\">";
   echo "<input type=hidden name=address value=\"$address\">";
   echo "<input type=hidden name=city value=\"$city\">";
   echo "<input type=hidden name=state value=\"$state\">";
   echo "<input type=hidden name=homeph value=\"$homeph\">";
   echo "<input type=hidden name=workph value=\"$workph\">";
   echo "<input type=hidden name=cellph value=\"$cellph\">";
   echo "<input type=hidden name=email value=\"$email\">";
   echo "<input type=hidden name=notes value=\"$notes\">";
   echo "<input type=hidden name=senttofed value=\"$senttofed\">";
   echo "<br><table width=500 class=nine><caption><b>Merge 2 Officials' Records:</b><hr></caption>";
   echo "<tr align=left><td colspan=2><i>Please review the following information.  If this is the information you would like to keep for the newly merged official's record, please click \"Submit Merge\".  If you need to go back and make changes, click \"Go Back\".</i></td></tr>";
   echo "<tr align=left><td><b>Official's Name:</b></td>";
   echo "<td>$first $middle $last</td></tr>";
   echo "<tr align=left><td><b>Passcode:</b></td><td>$passcode</td></tr>";
   echo "<tr align=left><td><b>Social Security #:</b></td><td>$socsec</td></tr>";
   echo "<tr align=left><td><b>Address:</b></td><td>$address</td></tr>";
   echo "<tr align=left><td><b>City:</b></td><td>$city</td></tr>";
   echo "<tr align=left><td><b>State:</b></td><td>$state</td></tr>";
   echo "<tr align=left><td><b>Home Phone:</b></td><td>$homeph</td></tr>";
   echo "<tr align=left><td><b>Work Phone:</b></td><td>$workph</td></tr>";
   echo "<tr align=left><td><b>Cell Phone:</b></td><td>$cellph</td></tr>";
   echo "<tr align=left><td><b>E-mail:</b></td><td>$email</td></tr>";
   echo "<tr align=left><td><b>Notes:</b></td><td>$notes</td></tr>";
   echo "<tr align=left><td><b>Sent to NFHS:</b><td>";
   if($senttofed==1) echo "YES";
   else echo "NO";
   echo "</td></tr>";
   for($i=0;$i<count($activity);$i++)
   {
      $cursp=$activity[$i]; $app=$cursp."apply"; $test=$cursp."test"; $hist=$cursp."hist";
      echo "<input type=hidden name=\"$cursp\" value=\"".$$cursp."\">";
      echo "<input type=hidden name=\"$app\" value=\"".$$app."\">";
      echo "<input type=hidden name=\"$test\" value=\"".$$test."\">";
      echo "<input type=hidden name=\"$hist\" value=\"".$$hist."\">";
      $offtable=$cursp."off";
      $sql="SELECT t1.$cursp, t2.* FROM officials AS t1, $offtable AS t2 WHERE t1.id=t2.offid AND t2.offid='".$$cursp."'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      echo "<tr align=left valign=top><td colspan=2><b>".GetSportName($cursp).":</b></td></tr>";
      echo "<tr align=left><td colspan=2>";
      if(mysql_num_rows($result)>0)
      {	
	 echo "$space<input type=checkbox disabled=TRUE";
	 if($row[$cursp]=='x') echo " checked";
         echo ">&nbsp;<b>Payment:</b> $row[payment]";
         if($row[payment]=="credit") echo ", App ID: $row[appid]";
         if($row[datepaid]!='') echo " (".date("m/d/y",$row[datepaid]).")";
         echo "<br>$space<b>Class:</b> $row[class]<br>$space<b>Sup Test:</b> $row[suptestdate]<br>";
         echo "$space<b>Mailing:</b> $row[mailing]<br>$space<b>Years:</b> $row[years]<br>";
      }
      else echo "$space [No Record for ".GetSportName($cursp)."]<br>";
      $histtable=$offtable."_hist";
      $sql4="SELECT * FROM $histtable WHERE offid='".$$hist."' ORDER BY regyr";
      $result4=mysql_query($sql4);
      if(mysql_num_rows($result4)>0) echo "$space<b>Registration Years:&nbsp;</b>";
      $string="";
      while($row4=mysql_fetch_array($result4))
      { 
         $string.="$row4[regyr], ";
      }
      $string=substr($string,0,strlen($string)-2);
      if(mysql_num_rows($result4)>0)
      {
         echo "$string<br>";
      }
      else
         echo "$space<b>Registration Years:</b> [No registrations on record for ".GetSportName($activity[$i])."]<br>";
      $table3=$activity[$i]."test_results";
      $sql3="SELECT * FROM $table3 WHERE offid='".$$test."'";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      echo "$space<b>".GetSportName($activity[$i])." Test:</b> ";
      if(mysql_num_rows($result3)>0)
      {
         if($row3[datetaken]!='') echo "Submitted ".date("m/d/y",$row3[datetaken]);
         else echo "In progress";
      }
      else
         echo "Not started yet";
      $table3=$activity[$i]."apply";
      $sql3="SELECT * FROM $table3 WHERE offid='".$$app."'";
      $result3=mysql_query($sql3);
      $row3=mysql_fetch_array($result3);
      echo "<br>$space<b>".GetSportName($activity[$i])." Application:</b> ";
      if(mysql_num_rows($result3)>0)
      {
         if($row3[datetaken]!='') echo "Submitted ".date("m/d/y",$row3[datetaken]);
         else echo "In progress";
      }
      else
         echo "Not started yet"; 
      echo "</td></tr>";
   }
   echo "<tr align=center><td colspan=2><input type=button onclick=\"javascript:history.go(-1)\" value=\"Go Back\">&nbsp;";
   echo "<input type=submit name=confirm value=\"Submit Merge\"></td></tr>";
   echo "</table>";
   echo "</form>";
   exit();
}

echo "<form method=post action=\"mergeoffs.php\" id=\"mergeform\" name=\"mergeform\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<br><table cellspacing=0 cellpadding=4";
echo "><caption><b>Merge 2 Officials' Records:</b><hr></caption>";
echo "<tr align=center valign=top><td>";
echo "<b>Type all or part of official's name:<br>";
echo "<input type=hidden name=\"off1id\" id=\"off1id\" value=\"$off1id\">";
echo "<input type=text name=\"off1\" id=\"off1\" value=\"$off1\" size=30 onkeyup=\"UserLookup.lookup('off1',this.value,'','official');\">";
echo "<div id=\"off1List\" name=\"off1List\" class=\"searchresults\" style=\"display:none;\"></div></td><td bgcolor=#E0E0E0>&nbsp;</td>";
echo "<td><b>Find the second record to merge with:&nbsp;</b><br>";
echo "<input type=hidden name=\"off2id\" id=\"off2id\" value=\"$off2id\">";
echo "<input type=text name=\"off2\" id=\"off2\" value=\"$off2\" size=30 onkeyup=\"UserLookup.lookup('off2',this.value,'','official');\">";
echo "<div id=\"off2List\" name=\"off2List\" class=\"searchresults\" style=\"display:none;\"></div></td></tr>";
$space="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
if($off1id!='' || $off2id!='')
{
   //get first record:
   $sql="SELECT t2.passcode,t1.* FROM officials AS t1, logins AS t2 WHERE t1.id='$off1id' AND t1.id=t2.offid";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   //get second record:
   $sql2="SELECT t2.passcode,t1.* FROM officials AS t1, logins AS t2 WHERE t1.id='$off2id' AND t1.id=t2.offid";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);

   echo "<tr align=left>";
   if($off1id!='') echo "<td>$space<input type=radio name=\"passcode\" value=\"$row[passcode]\" checked>$row[passcode]</td>"; 
   else echo "<td>&nbsp;</td>";
   echo "<td bgcolor=#E0E0E0><b>Passcode</b></td>";
   if($off2id!='') echo "<td>$space<input type=radio name=\"passcode\" value=\"$row2[passcode]\">$row2[passcode]</td>";
   else echo "<td>&nbsp;<td>";
   echo "</tr>";
   echo "<tr align=left>";
   if($off1id!='') echo "<td>$space<input type=radio name=\"last\" value=\"$row[last]\" checked>$row[last]</td>"; 
   else echo "<td>&nbsp;</td>";
   echo "<td bgcolor=#E0E0E0><b>Last Name</b></td>";
   if($off2id!='') echo "<td>$space<input type=radio name=\"last\" value=\"$row2[last]\">$row2[last]</td>";
   else echo "<td>&nbsp;<td>";
   echo "</tr>";
   echo "<tr align=left>";
   if($off1id!='') echo "<td>$space<input type=radio name=\"first\" value=\"$row[first]\" checked>$row[first]</td>";
   else echo "<td>&nbsp;</td>";
   echo "<td bgcolor=#E0E0E0><b>First Name</b></td>";
   if($off2id!='') echo "<td>$space<input type=radio name=\"first\" value=\"$row2[first]\">$row2[first]</td>";
   else echo "<td>&nbsp;<td>";
   echo "</tr>";
   echo "<tr align=left>";
   if($off1id!='') echo "<td>$space<input type=radio name=\"middle\" value=\"$row[middle]\" checked>$row[middle]</td>";
   else echo "<td>&nbsp;</td>";
   echo "<td bgcolor=#E0E0E0><b>Middle Name</b></td>";
   if($off2id!='') echo "<td>$space<input type=radio name=\"middle\" value=\"$row2[middle]\">$row2[middle]</td>";
   else echo "<td>&nbsp;<td>";
   echo "</tr>";
   echo "<tr align=left>";
   if($off1id!='') echo "<td>$space<input type=radio name=\"socsec\" value=\"$row[socsec]\" checked>$row[socsec]</td>";
   else echo "<td>&nbsp;</td>";
   echo "<td bgcolor=#E0E0E0><b>SS#</b></td>";
   if($off2id!='') echo "<td>$space<input type=radio name=\"socsec\" value=\"$row2[socsec]\">$row2[socsec]</td>";
   else echo "<td>&nbsp;<td>";
   echo "</tr>";
   echo "<tr align=left>";
   if($off1id!='') echo "<td>$space<input type=radio name=\"address\" value=\"$row[address]\" checked>$row[address]</td>";
   else echo "<td>&nbsp;</td>";
   echo "<td bgcolor=#E0E0E0><b>Address</b></td>";
   if($off2id!='') echo "<td>$space<input type=radio name=\"address\" value=\"$row2[address]\">$row2[address]</td>";
   else echo "<td>&nbsp;<td>";
   echo "</tr>";
   echo "<tr align=left>";
   if($off1id!='') echo "<td>$space<input type=radio name=\"city\" value=\"$row[city]\" checked>$row[city]</td>";
   else echo "<td>&nbsp;</td>";
   echo "<td bgcolor=#E0E0E0><b>City</b></td>";
   if($off2id!='') echo "<td>$space<input type=radio name=\"city\" value=\"$row2[city]\">$row2[city]</td>";
   else echo "<td>&nbsp;<td>";
   echo "</tr>";
   echo "<tr align=left>";
   if($off1id!='') echo "<td>$space<input type=radio name=\"state\" value=\"$row[state]\" checked>$row[state]</td>";
   else echo "<td>&nbsp;</td>";
   echo "<td bgcolor=#E0E0E0><b>State</b></td>";
   if($off2id!='') echo "<td>$space<input type=radio name=\"state\" value=\"$row2[state]\">$row2[state]</td>";
   else echo "<td>&nbsp;<td>";
   echo "</tr>";
   $homeph1="(".substr($row[homeph],0,3).")".substr($row[homeph],3,3)."-".substr($row[homeph],6,4);
   if($homeph1=="()-") $homeph1="";
   $homeph2="(".substr($row2[homeph],0,3).")".substr($row2[homeph],3,3)."-".substr($row2[homeph],6,4);
   if($homeph2=="()-") $homeph2="";
   echo "<tr align=left>";
   if($off1id!='') echo "<td>$space<input type=radio name=\"homeph\" value=\"$row[homeph]\" checked>$homeph1</td>";
   else echo "<td>&nbsp;</td>";
   echo "<td bgcolor=#E0E0E0><b>Home Phone</b></td>";
   if($off2id!='') echo "<td>$space<input type=radio name=\"homeph\" value=\"$row2[homeph]\">$homeph2</td>";
   else echo "<td>&nbsp;<td>";
   echo "</tr>";
   $workph1="(".substr($row[workph],0,3).")".substr($row[workph],3,3)."-".substr($row[workph],6,4);
   if($workph1=="()-") $workph1="";
   $workph2="(".substr($row2[workph],0,3).")".substr($row2[workph],3,3)."-".substr($row2[workph],6,4);
   if($workph2=="()-") $workph2="";
   echo "<tr align=left>";
   if($off1id!='') echo "<td>$space<input type=radio name=\"workph\" value=\"$row[workph]\" checked>$workph1</td>";
   else echo "<td>&nbsp;</td>";
   echo "<td bgcolor=#E0E0E0><b>Work Phone</b></td>";
   if($off2id!='') echo "<td>$space<input type=radio name=\"workph\" value=\"$row2[workph]\">$workph2</td>";
   else echo "<td>&nbsp;<td>";
   echo "</tr>";
   $cellph1="(".substr($row[cellph],0,3).")".substr($row[cellph],3,3)."-".substr($row[cellph],6,4);
   if($cellph1=="()-") $cellph1="";
   $cellph2="(".substr($row2[cellph],0,3).")".substr($row2[cellph],3,3)."-".substr($row2[cellph],6,4);
   if($cellph2=="()-") $cellph2="";
   echo "<tr align=left>";
   if($off1id!='') echo "<td>$space<input type=radio name=\"cellph\" value=\"$row[cellph]\" checked>$cellph1</td>";
   else echo "<td>&nbsp;</td>";
   echo "<td bgcolor=#E0E0E0><b>Cell Phone</b></td>";
   if($off2id!='') echo "<td>$space<input type=radio name=\"cellph\" value=\"$row2[cellph]\">$cellph2</td>";
   else echo "<td>&nbsp;<td>";
   echo "</tr>";
   echo "<tr align=left>";
   if($off1id!='') echo "<td>$space<input type=radio name=\"email\" value=\"$row[email]\" checked>$row[email]</td>";
   else echo "<td>&nbsp;</td>";
   echo "<td bgcolor=#E0E0E0><b>E-mail</b></td>";
   if($off2id!='') echo "<td>$space<input type=radio name=\"email\" value=\"$row2[email]\">$row2[email]</td>";
   else echo "<td>&nbsp;<td>";
   echo "</tr>";
   echo "<tr align=left>";
   if($off1id!='') echo "<td>$space<input type=radio name=\"notes\" value=\"$row[notes]\" checked>$row[notes]</td>";
   else echo "<td>&nbsp;</td>";
   echo "<td bgcolor=#E0E0E0><b>Notes</b></td>";
   if($off2id!='') echo "<td>$space<input type=radio name=\"notes\" value=\"$row2[notes]\">$row2[notes]</td>";
   else echo "<td>&nbsp;<td>";
   echo "</tr>";
   echo "<tr align=left>";
   if($row[senttofed]=='1') $senttofed1="YES";   
   else $senttofed1="NO";
   if($row[senttofed]=='2') $senttofed2="YES";   
   else $senttofed2="NO";
   if($off1id!='') echo "<td>$space<input type=radio name=\"senttofed\" value=\"$row[senttofed]\" checked>$senttofed1</td>";
   else echo "<td>&nbsp;</td>";
   echo "<td bgcolor=#E0E0E0><b>Sent to NFHS</b></td>";
   if($off2id!='') echo "<td>$space<input type=radio name=\"senttofed\" value=\"$row2[senttofed]\">$senttofed2</td>";
   else echo "<td>&nbsp;<td>";
   echo "</tr>";
   for($i=0;$i<count($activity);$i++)
   {
      echo "<tr align=left>";
      if($off1id!='')
      {
         echo "<td><table><tr valign=top align=left><td>$space<input type=radio name=\"$activity[$i]\" value=\"$off1id\" checked></td><td width=300>";
         echo "<input type=checkbox disabled=TRUE";
         if($row[$activity[$i]]=='x') echo " checked";
         echo ">&nbsp;<b>".GetSportName($activity[$i])."</b><br>";
         $table=$activity[$i]."off";
         $sql3="SELECT * FROM $table WHERE offid='$off1id'";
         $result3=mysql_query($sql3);
         $row3=mysql_fetch_array($result3);
         if(mysql_num_rows($result3))
	 {
            echo "<b>Payment: </b>$row3[payment]";
   	    if($row3[payment]=="credit") echo ", App ID: $row3[appid]";
            if($row3[datepaid]!='') echo " (".date("m/d/y",$row3[datepaid]).")";
	    echo "<br><b>Class:</b> $row3[class]<br><b>Sup Test:</b> $row3[suptestdate]<br>";
	    echo "<b>Mailing:</b> $row3[mailing]<br><b>Years:</b> $row3[years]</td></tr>";
	    $table2=$table."_hist";
	    $sql4="SELECT * FROM $table2 WHERE offid='$off1id' ORDER BY regyr";
	    $result4=mysql_query($sql4);
	    if(mysql_num_rows($result4)>0) echo "<tr align=left><td>$space<input type=radio name=\"".$activity[$i]."hist\" value=\"$off1id\" checked></td><td width=300><b>Registration Years:&nbsp;</b>";
	    $string="";
	    while($row4=mysql_fetch_array($result4))
	    {
	       $string.="$row4[regyr], ";
	    }
	    $string=substr($string,0,strlen($string)-2);
            if(mysql_num_rows($result4)>0)
            {
	       echo "$string<br>";
	    }
	 }
	 else
	    echo "[No Record for ".GetSportName($activity[$i])."]";
         echo "</td></tr>";
	 echo "<tr align=left><td>$space<input type=radio name=\"".$activity[$i]."test\" value=\"$off1id\" checked></td><td>";
	 $table3=$activity[$i]."test_results";
	 $sql3="SELECT * FROM $table3 WHERE offid='$off1id'";
	 $result3=mysql_query($sql3);
	 $row3=mysql_fetch_array($result3);
	 echo "<b>".GetSportName($activity[$i])." Test:</b> ";
	 if(mysql_num_rows($result3)>0)
	 {
	    if($row3[datetaken]!='') echo "Submitted ".date("m/d/y",$row3[datetaken]);
	    else echo "In progress";
	 }
	 else
 	    echo "Not started yet";
	 echo "</td></tr>";
         echo "<tr align=left><td>$space<input type=radio name=\"".$activity[$i]."apply\" value=\"$off1id\" checked></td><td>";
	 $table4=$activity[$i]."apply";
	 $sql3="SELECT * FROM $table4 WHERE offid='$off1id'";
	 $result3=mysql_query($sql3);
	 $row3=mysql_fetch_array($result3);
	 echo "<b>".GetSportName($activity[$i])." App:</b> ";
	 if(mysql_num_rows($result3)>0)
	    echo "Submitted"; 
	 else
	    echo "Not Submitted";
	 echo "</td></tr>";
	 echo "</table></td>";
      }  
      else echo "<td>&nbsp;</td>";
      echo "<td bgcolor=#E0E0E0><b>".GetSportName($activity[$i])."</b></td>";
      if($off2id!='')
      {
         echo "<td><table><tr valign=top align=left><td>$space<input type=radio name=\"$activity[$i]\" value=\"$off2id\"></td><td width=300>";
         echo "<input type=checkbox disabled=TRUE";
         if($row2[$activity[$i]]=='x') echo " checked";
         echo ">&nbsp;<b>".GetSportName($activity[$i])."</b><br>";
         $sql3="SELECT * FROM $table WHERE offid='$off2id'";
         $result3=mysql_query($sql3);
         $row3=mysql_fetch_array($result3);
         if(mysql_num_rows($result3))
         {
            echo "<b>Payment: </b>$row3[payment]";
            if($row3[payment]=="credit") echo ", App ID: $row3[appid]";
            if($row3[datepaid]!='') echo " (".date("m/d/y",$row3[datepaid]).")";
            echo "<br><b>Class:</b> $row3[class]<br><b>Sup Test:</b> $row3[suptestdate]<br>";
            echo "<b>Mailing:</b> $row3[mailing]<br><b>Years:</b> $row3[years]<br>";
            $table2=$table."_hist";
            $sql4="SELECT * FROM $table2 WHERE offid='$off2id' ORDER BY regyr";
            $result4=mysql_query($sql4);
            if(mysql_num_rows($result4)>0) echo "<tr align=left><td>$space<input type=radio name=\"".$activity[$i]."hist\" value=\"$off2id\"></td><td width=300><b>Registration Years:&nbsp;</b>";
            $string="";
            while($row4=mysql_fetch_array($result4))
            {
               $string.="$row4[regyr], ";
            }
            $string=substr($string,0,strlen($string)-2);
	    if(mysql_num_rows($result4)>0)
               echo "$string<br>";
         }
         else
            echo "[No Record for ".GetSportName($activity[$i])."]";
         echo "</td></tr>";
         echo "<tr align=left><td>$space<input type=radio name=\"".$activity[$i]."test\" value=\"$off2id\"></td><td>";
         $table3=$activity[$i]."test_results";
         $sql3="SELECT * FROM $table3 WHERE offid='$off2id'";
         $result3=mysql_query($sql3);
         $row3=mysql_fetch_array($result3);
         echo "<b>".GetSportName($activity[$i])." Test:</b> ";
         if(mysql_num_rows($result3)>0)
         {
            if($row3[datetaken]!='') echo "Submitted ".date("m/d/y",$row3[datetaken]);
            else echo "In progress";
         }
         else
            echo "Not started yet";
         echo "</td></tr>";
         echo "<tr align=left><td>$space<input type=radio name=\"".$activity[$i]."apply\" value=\"$off2id\"></td><td>";
         $table4=$activity[$i]."apply";
         $sql3="SELECT * FROM $table4 WHERE offid='$off2id'";
         $result3=mysql_query($sql3);
         $row3=mysql_fetch_array($result3);
         echo "<b>".GetSportName($activity[$i])." App:</b> ";
         if(mysql_num_rows($result3)>0)
            echo "Submitted";
         else
            echo "Not Submitted";
         echo "</td></tr>";
	 echo "</table></td>";
      }
      else echo "<td>&nbsp;</td>";
      echo "</tr>";
   }
}
if($off1id!='' && $off2id!='')
   echo "<tr align=center><td colspan=3><br><input type=submit name=choose value=\"Confirm Merge\"></td></tr>";
echo "</table>";
echo "</form>";
?>
<div id="loading" style="display:none;"></div>
<?php
echo $end_html;
?>

