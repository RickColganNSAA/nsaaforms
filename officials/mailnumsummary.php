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

//show summary of mail nums: for each sport, current mail num and list of names who have that mail num
echo $init_html;
echo GetHeader($session);
echo "<a name=\"top\">&nbsp;</a>";
echo "<form method=post action=\"mailnumsummary.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<table><caption><b>Mailing Number Summary:</b></caption>";
echo "<tr align=center><td colspan=3>";
echo "<select class=tiny name=groupby onchange='submit();'>";
echo "<option value='~'>Group By</option>";
echo "<option";
if($groupby=="Sport") echo " selected";
echo ">Sport</option>";
echo "<option";
if($groupby=="Mailing #") echo " selected";
echo ">Mailing #</option></select>&nbsp;&nbsp;&nbsp;<b>OR</b>&nbsp;&nbsp;&nbsp;";
echo "<select class=tiny name=sportch onchange='submit();'>";
echo "<option value='~'>Select a Sport</option>";
for($i=0;$i<count($activity);$i++)
{
   echo "<option value='$activity[$i]'";
   if($sportch==$activity[$i]) echo " selected";
   echo ">$act_long[$i]</option>";
}
echo "</select>&nbsp;&nbsp;&nbsp;<b>OR</b>&nbsp;&nbsp;&nbsp;";
echo "<b>Mailing #:&nbsp;<select class=tiny name=mailineq>";
echo "<option";
if($mailineq=="<=") echo " selected";
echo "><=</option><option";
if($mailineq==">=") echo " selected";
echo ">>=</option><option";
if($mailineq=="=") echo " selected";
echo ">=</option></select>";
echo "<input type=text class=small size=2 name=mailch value='$mailch'> ";
echo "<input type=submit name=go value=\"Go\">";
echo "<hr></td></tr>";
if($groupby && $groupby!='~')
{
   //get file ready to write to:
   $csv="";
   if($groupby=="Sport")
   {
      $csv.="Sport,Mailing Num,First,Last,City,State,Zip\r\n";
      for($i=0;$i<count($activity);$i++)
      {
	 $curact=$activity[$i];
	 $curact_long=$act_long[$i];
	 $curtable=$curact."off";
	 echo "<tr align=left><td colspan=3><b><br>".strtoupper($curact_long).":</b></td></tr>";
	 //get current mailing number for this sport
	 $sql="SELECT mailnum FROM mailing WHERE sport='$curact'";
	 $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 $curmailnum=$row[0];
	 for($j=1;$j<=$curmailnum;$j++)
	 {
	    //get offs for this sport with this mail num
	    echo "<tr align=left><td colspan=3><b>Mailing #: $j<br></b></td></tr>";
	    $sql="SELECT DISTINCT t1.first, t1.last,t1.city,t1.state,t1.zip FROM officials AS t1, $curtable AS t2 WHERE t1.id=t2.offid AND t2.mailing='$j' AND t1.inactive!='x' ORDER BY t1.last,t1.first";
	    $result=mysql_query($sql);
	    while($row=mysql_fetch_array($result))
	    {
	       echo "<tr align=left><td>$row[first] $row[last]</td><td>$row[city], $row[state]</td><td>$row[zip]</td></tr>";
	       $csv.="$curact_long,$j,$row[first],$row[last],$row[city],$row[state],$row[zip]\r\n";
	    }
	    echo "<tr align=left><td colspan=3>(".mysql_num_rows($result)." officials)</td></tr>";
	    //$csv.="(".mysql_num_rows($result)." officials)\r\n";
         }
      }
   }
   else //group by mailing number
   {
      //get highest mailing number from any sport
      $sql="SELECT mailnum FROM mailing ORDER BY mailnum DESC LIMIT 1";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $maxmailnum=$row[0];
      $csv.="Mailing Num,Sport,First,Last,City,State,Zip\r\n";
      for($i=1;$i<=$maxmailnum;$i++)
      {
	 echo "<tr align=left><td colspan=3><b>Mailing # $i:</b></td></tr>";
	 for($j=0;$j<count($activity);$j++)
	 {
	    $curact=$activity[$j];
	    $curact_long=$act_long[$j];
	    $curtable=$curact."off";
	    echo "<tr align=left><td colspan=3><b><br>".strtoupper($curact_long).":</b><br></td></tr>";
	    $sql="SELECT DISTINCT t1.first,t1.last,t1.city,t1.state,t1.zip FROM officials AS t1,$curtable AS t2 WHERE t1.inactive!='x' AND t1.id=t2.offid AND t2.mailing='$i' ORDER BY t1.last,t1.first";
	    $result=mysql_query($sql);
	    while($row=mysql_fetch_array($result))
	    {
	       echo "<tr align=left><td>$row[first] $row[last]</td><td>$row[city], $row[state]</td><td>$row[zip]</td></tr>";
	       $csv.="$i,$curact_long,$row[first],$row[last],$row[city],$row[state],$row[zip]\r\n";
	    }
	    echo "<tr align=left><td colspan=3>(".mysql_num_rows($result)." officials)</td></tr>";
	    //$csv.="(".mysql_num_rows($result)." officials)\r\n";
	 }
      }
   }
   //write to csv file
   $filename="mailnumsummary.csv";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
   echo "<tr align=center><td colspan=3><br><br><a class=small href=\"reports.php?session=$session&filename=$filename\" target=new>Export as a .CSV (Comma-Delimited) File</a></td></tr>";
}
else if($sportch && $sportch!='~')	//user chose a specific sport
{
   $groupby="~";
   $csv="Mailing #,Sport,First,Last,City,State,Zip\r\n";
   for($i=0;$i<count($activity);$i++)
   {
      if($activity[$i]==$sportch) $sportname=$act_long[$i];
   }
   $table=$sportch."off";
   echo "<tr align=left><td colspan=3><b>".strtoupper($sportname).":</b></td></tr>";
   //get max mail num for any official in this sport
   $sql2="SELECT DISTINCT mailing FROM $table WHERE mailing>=0 ORDER BY mailing";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      echo "<tr align=left><td colspan=3><b>Mailing # $row2[0]:</b></td></tr>";
      $sql="SELECT DISTINCT t1.first,t1.last,t1.city,t1.state,t1.zip FROM officials AS t1,$table AS t2 WHERE t1.inactive!='x' AND t1.id=t2.offid AND t2.mailing='$row2[0]' ORDER BY t1.last, t1.first";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
	 echo "<tr align=left><td>$row[first] $row[last]</td><td>$row[city], $row[state]</td><td>$row[zip]</td></tr>";
	 $csv.="$row2[0],".strtoupper($sportch).",$row[first],$row[last],$row[city],$row[state],$row[zip]\r\n";
      }
      echo "<tr align=left><td colspan=3>(".mysql_num_rows($result)." officials)</td></tr>";
   }
   //write to csv file
   $filename="mailnumsummary.csv";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
   echo "<tr align=center><td colspan=3><br><br><a class=small href=\"reports.php?session=$session&filename=$filename\" target=new>Export as
 a .CSV (Comma-Delimited) File</a></td></tr>";
}
else if($mailch && $mailch!="")	//user chose range of mailing numbers
{
   $sportch="~";
   $csv="Mailing #,Sport,First,Last,City,State,Zip\r\n";
   echo "<tr align=left><td colspan=3><b>Mailing # $mailineq $mailch:</b></td></tr>";
   for($i=0;$i<count($activity);$i++)
   {
      echo "<tr align=left><td colspan=3><b>$act_long[$i]:</b></td></tr>";
      $cursp=$activity[$i];
      $curtable=$cursp."off";
      $sql="SELECT DISTINCT t2.mailing,t1.last,t1.first,t1.city,t1.state,t1.zip FROM officials AS t1, $curtable AS t2 WHERE t1.inactive!='x' AND t1.id=t2.offid AND t2.mailing $mailineq $mailch ORDER BY t2.mailing,t1.last,t1.first";
      $result=mysql_query($sql);
      $curmail=-2;
      while($row=mysql_fetch_array($result))
      {
	 if($curmail!=$row[mailing])
	 {
	    $curmail=$row[mailing];
	    echo "<tr align=left><td colspan=3><b>Mailing #$curmail:</b></td></tr>";
	 }
	 echo "<tr align=left><td>$row[first] $row[last]</td><td>$row[city], $row[state]</td><td>$row[zip]</td></tr>";
	 $csv.="$curmail,".strtoupper($cursp).",$row[first],$row[last],$row[city],$row[state],$row[zip]\r\n";
      }
      echo "<tr align=left><td colspan=3>(".mysql_num_rows($result)." officials)</td></tr>";
   }
   //write to csv file
   $filename="mailnumsummary.csv";
   $open=fopen(citgf_fopen("/home/nsaahome/reports/$filename"),"w");
   fwrite($open,$csv);
   fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/$filename");
   echo "<tr align=center><td colspan=3><br><br><a class=small href=\"reports.php?session=$session&filename=$filename\" target=new>Export as
 a .CSV (Comma-Delimited) File</a></td></tr>";
}

echo "</table></form>";
echo "<a href=\"#top\" class=small>Return to Top</a>&nbsp;&nbsp;&nbsp;";
echo "<a href=\"welcome.php?session=$session\" class=small>Home</a>";
echo $end_html;

?>
