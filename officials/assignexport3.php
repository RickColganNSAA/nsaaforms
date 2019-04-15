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

//Excel Export for District Assignments
$disttimes=$sport."disttimes";
$districts=$sport."districts";
$contracts=$sport."contracts";

$sql="SELECT DISTINCT class,dist FROM $disttimes ORDER BY class,dist";
$result=mysql_query($sql);

$filename=$sport."assignexport.csv";
$open=fopen(citgf_fopen($filename),"w");

while($row=mysql_fetch_array($result))
{
   //FOR EACH CLASS/DIST:
   //column headers
   $line="$row[class]-$row[dist]\r\nOfficial Name,Address,City,State,Zip,Class,Dist,";
   $sql0="SELECT times,day FROM $disttimes WHERE class='$row[class]' AND dist='$row[dist]' ORDER BY day";
   $result0=mysql_query($sql0);
   $dayct=1;
   while($row0=mysql_fetch_array($result0))
   {
      $line.="Day $dayct,";
      $times=split("/",$row0[times]);
      for($i=0;$i<count($times);$i++)
      {
	 $line.=$times[$i].",Partners,";
      }
      $dayct++;
   }
   $line.="District Director,Host School,Site,Schools\r\n";
   fwrite($open,$line);

   $sql2="SELECT DISTINCT t1.offid FROM $contracts AS t1,$disttimes AS t2 WHERE t1.disttimesid=t2.id AND t2.class='$row[class]' AND t2.dist='$row[dist]'";
   $result2=mysql_query($sql2);
   while($row2=mysql_fetch_array($result2))
   {
      //FOR EACH OFFICIAL IN THAT CLASS/DIST, MAKE ROW:
      $line=GetOffName($row2[offid]).",";
      //get contact info for official
      $sql0="SELECT address,city,state,zip FROM officials WHERE id='$row2[offid]'";
      $result0=mysql_query($sql0);
      $row0=mysql_fetch_array($result0);
      $line.="$row0[address],$row0[city],$row0[state],$row0[zip],";
      $line.="$row[class],$row[dist],";
      $sql3="SELECT t1.times,t1.day,t1.id FROM $disttimes AS t1 WHERE t1.class='$row[class]' AND t1.dist='$row[dist]' ORDER BY t1.day";
      $result3=mysql_query($sql3);
      while($row3=mysql_fetch_array($result3))
      {
	 //get this officials time slots checked for each day
	 $sql0="SELECT times FROM $contracts WHERE disttimesid='$row3[id]' AND offid='$row2[offid]'";
	 $result0=mysql_query($sql0);
	 $row0=mysql_fetch_array($result0);
	 $timech=split("/",$row0[times]);

	 //FOR EACH DAY IN THE CLASS/DIST FOR THAT OFFICIAL
         //put column labelling the day
	 $date=split("-",$row3[day]);
         $line.=date("F j",mktime(0,0,0,$date[1],$date[2],$date[0])).",";
	 //GET OTHER OFFICIALS FOR THIS Class/Dist ON THIS DAY
	 $sql4="SELECT times,offid FROM $contracts WHERE offid!='$row2[offid]' AND disttimesid='$row3[id]' ORDER BY offid";
	 $result4=mysql_query($sql4);
	 $ix=0;
	 $otheroffs=array(); $othertimes=array();
	 while($row4=mysql_fetch_array($result4))
	 {
	    $otheroffs[$ix]=$row4[offid]; 
	    $othertimes[$ix]=split("/",$row4[times]);
	    $ix++;
	 }
	 //GO THROUGH EACH TIME SLOT AND ADD 2 COLUMNS: TIME, OTHER OFFS
	 $showtimes=split("/",$row3[0]);
	 for($i=0;$i<count($showtimes);$i++)
	 {
	    if($timech[$i]=='x')	//official is assigned to this time slot
	    {
	       $line.=$showtimes[$i].",";
	       //show other partners
	       $partners="";
	       for($j=0;$j<count($otheroffs);$j++)
	       {
		  if($othertimes[$j][$i]=='x')	//if this partner is checked for this as well
		  {
		     $partners.=GetOffName($otheroffs[$j])."/";
		  }
	       }
	       if($partners!="") $partners=substr($partners,0,strlen($partners)-1);
	       $line.=$partners.",";
	    }
	    else	//leave blank columns
	       $line.=",,";
	 }
      }
      //put district info in record
      $sql0="SELECT * FROM $districts WHERE class='$row[class]' AND district='$row[dist]'";
      $result0=mysql_query($sql0);
      $row0=mysql_fetch_array($result0);
      $line.="$row0[prefix] $row0[first] $row0[last],$row0[hostschool],$row0[site],\"$row0[schools]\"";
      $line.="\r\n";
      fwrite($open,$line);
   }
   fwrite($open,"\r\n");
}
fclose($open); 
 citgf_makepublic($filename);

echo "<a class=small href=\"$filename\">$filename</a>";
?>
