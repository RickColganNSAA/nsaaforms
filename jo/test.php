<?php
require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

exit();

$catid=9; $sid=5;

        //Check to see if any student has >1 entry per event      
$sql="SELECT * FROM joentries WHERE studentid>0 AND catid='$catid' AND sid='$sid'";
echo "$sql\r\n";
      $result=mysql_query($sql);
      $usedstudids=array(); $u=0;
      while($row=mysql_fetch_array($result))
      {
         $curstudlist=$row[studentid].",";
         $curstuds=array($row[studentid]); $c=1;
         for($j=2;$j<=6;$j++)
         {
            $var="studentid".$j;
            if($row[$var]>0) 
	    {
	       $curstuds[$c]=$row[$var]; $c++;
	    }
         }
	 asort($curstuds);
	 $curstudlist=implode(",",$curstuds);
         if(in_array($curstudlist,$usedstudids) && $curstudlist!='')
         {
            $errors.="<p>".GetStudentInfo($row[studentid],FALSE)." $curstudlist has more than one entry in this event.</p>";
echo "$curstudlist already used\r\n";
         }
         else
         {
            $usedstudids[$u]=$curstudlist; $u++;
	echo $curstudlist."\r\n";
         }
      }
?>
