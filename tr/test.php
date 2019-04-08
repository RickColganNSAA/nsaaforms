<?php
require 'trfunctions.php';
require_once('../../calculate/functions.php');
require_once('../functions.php');

$db1=$db_name;
$gender="g";
$distid=1;
$class="A"; $district=1;
$export=2;
$statedb="nsaastatetrack"; $qualtable="trstatequalifiers";

      $sql="SELECT * FROM $db1.tr_state_extra_".$gender." WHERE district='$distid' ORDER BY eventnum";
      $result=mysql_query($sql);
echo "$sql\r\n";
      while($row=mysql_fetch_array($result))
      {
         $eventcode=$class.$district.strtoupper($gender).strtoupper($row[eventnum]);
         $eventid=GetEventField($row[eventnum],$gender);         
	 $curevent=GetEventLong($row[eventnum],$gender);
         $sql2="SELECT first,last,semesters,school FROM $db1.eligibility WHERE id='$row[student_id]'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);         
         if(ereg("[(]",$row2[first]))      //nickname         
	 {
            $first_nick=explode("(",$row2[first]);
            $first_nick[1]=trim($first_nick[1]);
            $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
            $row2[first]=$first;
         }
         $first=trim($row2[first]);
         $last=trim($row2[last]);
         $row[place]=ereg_replace("[^0-9]","",$row[place]);
         echo "STUDID: $row[student_id]:\r\n";
         if($export==2 && $row[student_id]>0)   //THIS MEANS WE ARE PUTTING DIST RESULTS INTO STATE QUALIFIERS TABLE
         {
            $sql2="INSERT INTO $statedb.$qualtable (extraqual,class,district,eventid,sid,studentid,distplace,distperf1,distperf2) VALUES ('x','$class','$district','$eventid','".GetSID2($row2[school],'tr'.$gender,$year)."','$row[student_id]','$row[place]','$row[perf1]','$row[perf2]')";
            //$result2=mysql_query($sql2);
	    echo "$sql2\r\n";
                if(mysql_error()) echo "$sql2<br>".mysql_error()."<br>";
         }
      }

exit();
$cur_perf="3:24.00/3:23.8";
      if(ereg("/",$cur_perf))
      {
          $temp=explode("/",$cur_perf);
          $cur_perf=$temp[0];
      }
echo $cur_perf;
exit();

?>
