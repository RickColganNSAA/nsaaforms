<?php
//State Speech Round Info: Room @'s, Date, Time
$sport="sp";
$districts=$sport."districts";
$contracts=$sport."contracts";

require 'functions.php';
require 'variables.php';

$sportname=GetSportName($sport);

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevelJ($session);
if(!ValidUser($session) || $level!=1)
{
   header("Location:jindex.php?error=1");
   exit();
}

echo $init_html;
echo GetHeaderJ($session,"statespeech");
echo "<br>";

if($save)
{
   for($i=0;$i<count($prefs_sm);$i++)
   {
      $sql="SELECT * FROM spstaterounds WHERE event='$prefs_lg[$i]' AND class='$class' ORDER BY round";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
         $roundid=$row[id];
         $curdate=$date[$i][$roundid];
		 $curdate=$date_year.'-'.$date_month.'-'.$date_day;
	 if(strlen($hour[$i][$roundid])==1) 
	    $hour[$i][$roundid]="0".$hour[$i][$roundid];
         if(strlen($min[$i][$roundid])==1)  
            $min[$i][$roundid]="0".$min[$i][$roundid];
	 if($ampm[$i][$roundid]=="PM" && $hour[$i][$roundid]<12) 
	    $hour[$i][$roundid]+=12;
         $curtime=$hour[$i][$roundid].":".$min[$i][$roundid].":00";	
         $sql2="UPDATE spstaterounds SET rounddate='$curdate',time='$curtime' WHERE id='$roundid'";
         $result2=mysql_query($sql2);
         //echo "$sql2  ".mysql_error()."<br>";
         for($j=0;$j<count($roomid[$i][$roundid]);$j++)
         {
            $curroomid=$roomid[$i][$roundid][$j];
            $curroom=$room[$i][$roundid][$j];
            $sql2="UPDATE spstaterooms SET room='$curroom' WHERE id='$curroomid'";
      	    $result2=mysql_query($sql2);
	    //echo "$sql2  ".mysql_error()."<br>";
	 }
      }
   }
}

echo "<form name=assignform method=post action=\"sprooms.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<br><table width=90%>";
echo "<caption>";
echo "<a class=small href=\"spshuffle.php?session=$session&class=$class\">Return to State Speech Student & Judge Assignments</a><br>";
echo "<b>State Speech Round & Room Information for Class: <select name=class onchange=\"submit()\">";
echo "<option value=''>Class</option>";
for($i=0;$i<count($classes);$i++)
{
   echo "<option";
   if($class==$classes[$i]) echo " selected";
   echo ">$classes[$i]</option>";
} 
echo "</select>";
if($save)
   echo "<br><font style=\"color:red;font-size:8pt;\"><b>The Rounds & Room Info on this screen has been saved.</b></font>";
   
     $sql_date="SELECT DISTINCT rounddate FROM spstaterounds WHERE  class='$class' ";
   $result_date=mysql_query($sql_date);
   $dates=mysql_fetch_array($result_date);
   $temp=split("-",$dates[rounddate]);
    echo "<table align=centre><tr align=centre><td><b><br>Date:&nbsp&nbsp&nbsp</b>";
	echo "<select style=\"margin-left:5px\" name=\"date_month\" >";
	 for($month=1;$month<13;$month++){
	echo "<option value=\"$month\"";
	if($month==$temp[1]) echo "selected";
	echo ">$month</option>";	
	 }	
	echo "</select>";	
	echo "<select style=\"margin-left:5px\" name=\"date_day\" >";
	 for($day=1;$day<32;$day++){
	echo "<option value=\"$day\"";
	if($day==$temp[2]) echo "selected";
	echo ">$day</option>";	
	 }	
	echo "</select>";	
	echo "<select style=\"margin-left:5px\" name=\"date_year\" >";
	 for($year=2005;$year<2025;$year++){
	echo "<option value=\"$year\"";
	if($year==$temp[0]) echo "selected";
	echo ">$year</option>";	
	 }	
	echo "</select>";
	echo "</td></tr></table>";

if($class && $class!='')
{
   $ix=0;
   for($i=0;$i<count($prefs_sm);$i++)
   {
      if($i%3==0) echo "<tr align=left valign=top>";
      echo "<td><br><table>";
      echo "<tr align=left><td colspan=2><b><u>$prefs_lg[$i]:</u></b></td></tr>";
      $sql="SELECT *,TIME_FORMAT(time,'%h:%i:%p') AS curtime FROM spstaterounds WHERE event='$prefs_lg[$i]' AND class='$class' ORDER BY round";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
	 $roundid=$row[id];
         if($row[round]<3)
         {
	    $date=split("-",$row[rounddate]);
	    echo "<tr align=left><td><b>ROUND $row[round]:<br>Date:</b>";
		$temp=split("-",$row[rounddate]);
		echo $temp[1].'-'.$temp[2].'-'.$temp[0];
		//echo "<input type=text size=1 style=\"margin-left:5px\" class=tiny name=\"date[$i][$roundid][]\" value=\"$temp[1]\"> - <input type=text size=1 class=tiny name=\"date[$i][$roundid][]\" value=\"$temp[2]\"> - <input type=text size=2 class=tiny name=\"date[$i][$roundid][]\" value=\"$temp[0]\">";
		// echo "<select style=\"margin-left:5px\" name=\"date[$i][$roundid][]\" >";
         // for($month=1;$month<13;$month++){
        // echo "<option value=\"$month\"";
		// if($month==$temp[1]) echo "selected";
		// echo ">$month</option>";	
         // }	
        // echo "</select>";	
        // echo "<select style=\"margin-left:5px\" name=\"date[$i][$roundid][]\" >";
         // for($day=1;$day<32;$day++){
        // echo "<option value=\"$day\"";
		// if($day==$temp[2]) echo "selected";
		// echo ">$day</option>";	
         // }	
        // echo "</select>";	
        // echo "<select style=\"margin-left:5px\" name=\"date[$i][$roundid][]\" >";
         // for($year=2005;$year<2025;$year++){
        // echo "<option value=\"$year\"";
		// if($year==$temp[0]) echo "selected";
		// echo ">$year</option>";	
         // }	
        // echo "</select>";
	    // $sql2="SELECT dates FROM spdistricts WHERE type='State' ORDER BY dates";
	    // $result2=mysql_query($sql2);
	    // while($row2=mysql_fetch_array($result2))
	    // {
	       // $temp=split("-",$row2[0]);
	       // echo "<input type=radio name=\"date[$i][$roundid]\" value=\"$row2[0]\"";
	       // if($row2[0]==$row[rounddate]) echo " checked";
	       // echo ">$temp[1]/$temp[2]&nbsp;&nbsp;";
	    // }
	    echo "</td></tr>";
	    $temp=split(":",$row[curtime]);
	    echo "<tr align=left><td><b>Time:</b> <input type=text size=2 class=tiny name=\"hour[$i][$roundid]\" value=\"$temp[0]\"> : <input type=text size=2 class=tiny name=\"min[$i][$roundid]\" value=\"$temp[1]\"> <select name=\"ampm[$i][$roundid]\">";
	    echo "<option";
	    if($temp[2]=='AM') echo " selected";
	    echo ">AM</option><option";
	    if($temp[2]=='PM') echo " selected";
	    echo ">PM</option></select></td></tr>";
	
	    $sql2="SELECT * FROM spstaterooms WHERE roundid='$row[id]' ORDER BY section LIMIT 3";
            //echo $sql2;
	    $result2=mysql_query($sql2); 
	    $ix2=0;
	    while($row2=mysql_fetch_array($result2))
	    {
	       echo "<input type=hidden name=\"roomid[$i][$roundid][$ix2]\" value=\"$row2[id]\">";
	       echo "<tr align=left><td>$row2[section]) Room: <input type=text class=tiny size=8 name=\"room[$i][$roundid][$ix2]\" value=\"$row2[room]\"></td></tr>";
               $ix++; $ix2++;
	    }
	 }
	 else	//FINALS
	 {
            echo "<tr align=left><td><b>FINALS:<br>Date:</b>";
		$temp=split("-",$row[rounddate]);
		echo $temp[1].'-'.$temp[2].'-'.$temp[0];		
		// echo "<select style=\"margin-left:5px\" name=\"date[$i][$roundid][]\" >";
         // for($month=1;$month<13;$month++){
        // echo "<option value=\"$month\"";
		// if($month==$temp[1]) echo "selected";
		// echo ">$month</option>";	
         // }	
        // echo "</select>";	
        // echo "<select style=\"margin-left:5px\" name=\"date[$i][$roundid][]\" >";
         // for($day=1;$day<32;$day++){
        // echo "<option value=\"$day\"";
		// if($day==$temp[2]) echo "selected";
		// echo ">$day</option>";	
         // }	
        // echo "</select>";	
        // echo "<select style=\"margin-left:5px\" name=\"date[$i][$roundid][]\" >";
         // for($year=2005;$year<2025;$year++){
        // echo "<option value=\"$year\"";
		// if($year==$temp[0]) echo "selected";
		// echo ">$year</option>";	
         // }	
        // echo "</select>";
            // $sql2="SELECT dates FROM spdistricts WHERE type='State' ORDER BY dates";
            // $result2=mysql_query($sql2);
            // while($row2=mysql_fetch_array($result2))
            // {
               // $temp=split("-",$row2[0]);
               // echo "<input type=radio name=\"date[$i][$roundid]\" value=\"$row2[0]\"";
               // if($row2[0]==$row[rounddate]) echo " checked";
               // echo ">$temp[1]/$temp[2]&nbsp;&nbsp;";
            // }
            echo "</td></tr>";
            $temp=split(":",$row[curtime]);
            echo "<tr align=left><td><b>Time:</b> <input type=text size=2 class=tiny name=\"hour[$i][$roundid]\" value=\"$temp[0]\"> : <input type=text size=2 class=tiny name=\"min[$i][$roundid]\" value=\"$temp[1]\"> <select name=\"ampm[$i][$roundid]\">";
            echo "<option";
            if($temp[2]=='AM') echo " selected";
            echo ">AM</option><option";
            if($temp[2]=='PM') echo " selected";
            echo ">PM</option></select></td></tr>";
            $sql2="SELECT * FROM spstaterooms WHERE roundid='$row[id]'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
	    echo "<input type=hidden name=\"roomid[$i][$roundid][0]\" value=\"$row2[id]\">";
            echo "<tr align=left><td>Room: <input type=text class=tiny size=8 name=\"room[$i][$roundid][0]\" value=\"$row2[room]\"></td></tr>";
	 }
      }
      echo "</table></td>";
      if(($i+1)%3==0) echo "</tr>";
   }
   echo "<input type=hidden name=total value=$ix>";
   echo "<tr align=center><td colspan=3><br><input type=submit name=save value=\"Save\"></td></tr>";
   echo "</table>";
   echo "</form></td></tr>";
}//end if class chosen
else
   echo "<tr align=center><td colspan=3><font style=\"font-size:9pt\"><i>Please select a class to edit the dates, times, and room numbers for each event in that class.</i></font></td></tr>";
echo $end_html;
?>
