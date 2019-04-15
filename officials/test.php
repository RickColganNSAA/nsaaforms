<?php
require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php';
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db("nsaaofficials", $db);

echo date("r",1445615833);
exit();
$contracts="nsaaofficials20122013.sogcontracts";
$disttimes="nsaaofficials20122013.sogdisttimes";
$districts="nsaaofficials20122013.sogdistricts";
$off="sooff";

$sql="SELECT t1.offid FROM $contracts AS t1, $disttimes AS t2, $districts AS t3 WHERE t1.disttimesid=t2.id AND t2.distid=t3.id AND t3.type='State' AND t1.post='y' AND t1.accept='y' AND t1.confirm='y'";
$result=mysql_query($sql);
echo mysql_error();
echo "$sql\r\n";
while($row=mysql_fetch_array($result))
{
   $curyr="13";
   $sql2="SELECT * FROM $off WHERE offid='$row[offid]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $stateyears=$row2[stateyears]; $numstateyears=$row2[numstateyears];
   if(!ereg($curyr,$stateyears))
   {
     if(trim($stateyears)=="") $stateyears=$curyr;
     else if(substr($stateyears,0,2)=="14")
     {
        $stateyears=preg_replace("/14,/","14,13,",$stateyears);
     }
     else $stateyears="$curyr,".$stateyears;
     $numstateyears++;
     $field="stateyears"; $field2="numstateyears";
     $sql2="UPDATE $off SET $field='$stateyears',$field2='$numstateyears' WHERE offid='$row[offid]'";
     //$result2=mysql_query($sql2);
     echo $sql2."\r\n";
   }
}

exit();

//USE TO UPDATE EVERYONE IN A SPORT:
$sport="sw";
$sql="SELECT * FROM swoff_hist WHERE regyr='2014-2015'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
    $now=$row['class'];
    UpdateRank($row[offid],$sport,"",FALSE);
    $sql2="SELECT * FROM swoff_hist WHERE regyr='2014-2015' AND offid='$row[offid]'";
    $result2=mysql_query($sql2);
    $row2=mysql_fetch_array($result2);
//    if($row2['class']!=$now) 
       echo "$row[offid] was $now and is now $row2[class]\r\n";
}
echo "DONE!";
exit();

//MAKE SURE EVERYONE WITH A RANK HAS MAILING OF 100
$sql="SHOW TABLES LIKE '%off'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo strtoupper($row[0]).":\r\n";
   $sql2="SELECT * FROM ".$row[0]."_hist WHERE regyr='2014-2015' AND class!='' AND class!='AFF'";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
	$sql3="SELECT * FROM $row[0] WHERE offid='$row2[offid]' AND mailing!='100'";
	$result3=mysql_query($sql3);
	if($row3=mysql_fetch_array($result3))
	{
	    echo "$row2[offid]: $row2[class], but mailing $row3[mailing]\r\n";
		$sql4="UPDATE $row[0] SET mailing='100' WHERE offid='$row2[offid]'";
	    	$result4=mysql_query($sql4);
    	}
   }
}

exit();
//Nov 2014: Testing UpdateRank (new function)
if(!$sport) $sport='tr';

$sql="SELECT DISTINCT offid FROM ".$sport."off_hist WHERE regyr='2014-2015'";
$result=mysql_query($sql);
$csv="\"SPORT\",\"OFFICIAL ID\",\"OFFICIAL\",\"RULES MEETING\",\"PART 1 TEST\",\"PART 2 TEST\",\"PART 2 YEAR\",\"NEXT PART 2 YEAR\",\"CONTESTS\",\"2012 RANK\",\"2013 RANK\",\"CURRENT\",\"SHOULD BE\"\r\n";
while($row=mysql_fetch_array($result))
{
   UpdateRank($row[offid],$sport);
}
echo "DONE!";
/*
if(!$open=fopen(citgf_fopen($sport."officialschanges.csv"),"w")) echo "COULD NOT OPEN";
if(!fwrite($open,$csv)) echo "COULD NOT WRITE";
fclose($open); 
 citgf_makepublic($sport."officialschanges.csv");
echo "<br><br>Report: ".$sport."officialschanges.csv";
*/

exit();


$sql="SELECT * FROM troff_hist WHERE regyr='2013-2014' ORDER BY appdate";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $offid=$row[offid];
   $sql2="SELECT appid FROM troff WHERE offid='$offid' ORDER BY datepaid DESC LIMIT 1";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $appid=$row2[appid];
   if(trim($appid)=="" || $appid==0)
   {
      $sql2="SELECT appid FROM officials WHERE id='$offid'";
      $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $appid=$row2[appid];
   }
   $sql2="SELECT * FROM pendingoffs WHERE appid='$appid' AND approved='yes' AND (tr='x' OR tr2='x') ORDER BY tr DESC,tr2 DESC";
   $result2=mysql_query($sql2);
   if($row2=mysql_fetch_array($result2))
   {
      if($row2[tr]=='x') $position="starter";
      else if($row2[tr2]=='x') $position="referee";
      if(mysql_num_rows($result2)>1)
      {
         echo "$offid: $sql2 (>1 tr or tr2)\r\n";
         exit();
      }
      $sql3="UPDATE troff_hist SET position='$position' WHERE id='$row[id]'";
      $result3=mysql_query($sql3);
      //echo "$sql3\r\n";
   }
   else echo "$offid - Couldn't find pendingoffs record for:\r\n$sql2\r\n";
}

exit();

$sql="SELECT * FROM pprulesmeetings WHERE datecompleted>0 AND datepaid>0 order by datepaid";
$result=mysql_query($sql);
$errors=0;
$others=0;
$newest=0;
while($row=mysql_fetch_array($result))
{
   $sql2="SELECT * FROM judges WHERE id='$row[offid]'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if($row2[ppmeeting]!='x')
   {
	echo "$row2[first] $row2[last] - $row[offid]\r\n";
      $sql2="UPDATE judges SET ppmeeting='x' WHERE id='$row[offid]'";
      //$result2=mysql_query($sql2);
	echo mysql_error();
      if($row[school]!='')
      {
	 $sql2="UPDATE nsaascores.logins SET rulesmeeting='x' WHERE school='".addslashes($row[school])."' AND sport='Play Production'";
	 //$result2=mysql_query($sql2);
	 echo $sql2.mysql_error()."\r\n";
      }
      $errors++;
   }
   else 
   {
      $others++;
      if($row[datepaid]>$newest) $newest=$row[datepaid];
   }
}
echo "ERRORS: $errors, OTHERS: $others\r\n";
echo date("r",$newest)."\r\n";
exit();

$dbold="nsaaofficials_20130914";
$dbnew="nsaaofficials";

$sql2="SHOW TABLES LIKE '%off_hist'";
$result2=mysql_query($sql2);
while($row2=mysql_fetch_array($result2))
{
   $table=$row2[0];
   $sql3="SELECT * FROM $table WHERE nhsoa='x' AND regyr='2013-2014'";
   $result3=mysql_query($sql3);
   while($row3=mysql_fetch_array($result3))
   {
      $sql="SELECT * FROM officials WHERE id='$row3[offid]'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if($row[nhsoa]!='x')
      {
         $sql="UPDATE officials SET nhsoa='x' WHERE id='$row3[offid]'";
         $result=mysql_query($sql);
         echo $table." - ".GetOffName($row3[offid])." - ".$sql."\r\n";
      }
      $sql="SHOW TABLES LIKE '%off_hist'";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         $sql4="SELECT * FROM $row[0] WHERE offid='$row3[offid]' AND regyr='2013-2014'";
         $result4=mysql_query($sql4);
   	 $row4=mysql_fetch_array($result4);
	 if(mysql_num_rows($result4)>0 && $row4[nhsoa]!='x')
	 {
            $sql4="UPDATE $row[0] SET nhsoa='x' WHERE offid='$row3[offid]' AND regyr='2013-2014'";
            $result4=mysql_query($sql4);
	    echo $table." - ".GetOffName($row3[offid])." - $sql4\r\n";
	 }
      }
   }
}
exit();

$sql="SELECT offid,COUNT(offid) FROM pendingoffs WHERE approved='yes' AND offid>0 GROUP BY offid";
$result=mysql_query($sql);
$fields=array("FB","VB","SB","BB","WR","SW","DI","SO","BA","TR","TR2");
$ct=0;
      for($j=0;$j<40;$j++)
         echo " ";
echo "FB VB SB BB WR SW DI SO BA TR TR2\r\n";
while($row=mysql_fetch_array($result))
{
      $offid=$row[offid];
      $sql2="SELECT nhsoa,first,last FROm officials WHERE id='$offid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $nhsoa=$row2[0]; $first=trim($row2[first]); $last=trim($row2[last]);
   if($row[1]>1 && $nhsoa!='x')
   {
      $splist="";
      $sql2="SELECT * FROM pendingoffs WHERE approved='yes' AND offid='$offid' ORDER BY datesub ASC";
      $result2=mysql_query($sql2);
      while($row2=mysql_fetch_array($result2))
      {
      echo "$offid $last, $first ($nhsoa)";
      $space=40-strlen($offid." $last, $first ($nhsoa)");
      for($j=0;$j<$space;$j++)
         echo " ";
	 for($i=0;$i<count($fields);$i++)
	 {
            $var=trim($row2[strtolower($fields[$i])]);
	    if($var=="") $var="-";
	    echo trim($var);
            if($var=='x') 
	       $splist.=$fields[$i]." "; 
	    $space=3-strlen($var);
	    for($j=0;$j<$space;$j++) 
	       echo " ";
         }
	 echo "\r\n";
      }
     echo "         $splist\r\n";
      $ct++;
   }
}
echo "$ct\r\n";
exit();

/*
$sql="SELECT DISTINCT offid FROM pendingoffs WHERE approved='yes' AND offid>0 ORDER BY offid";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $offid=$row[offid];
   $sql2="SELECT * FROM pendingoffs WHERE approved='yes' AND offid='$offid' ORDER BY datesub ASC";
   $result2=mysql_query($sql2);
   $nhsoa="START";
   while($row2=mysql_fetch_array($result2))
   {
      if($nhsoa!=$row2[nhsoa])
      {
         if($nhsoa!="START") 
     	 {
	    echo "OFFID $offid WAS $nhsoa...NOW $row2[nhsoa]\r\n";
         }
         $nhsoa=$row2[nhsoa];
      }
   }
}

$sql="select * from $dbold.officials WHERE nhsoa='x' ORDER BY id";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $offid=$row[id]; $nhsoa=$row[nhsoa];
   $sql2="SELECT * FROM $dbnew.officials WHERE id='$offid'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result)>0)
   {
      $row2=mysql_fetch_array($result2);
      $curnhsoa=$row2[nhsoa];
      if($nhsoa!=$curnhsoa) echo "OFFID $offid: WAS $nhsoa, NOW $curnhsoa\r\n";
   }
}
*/

exit();

?>
