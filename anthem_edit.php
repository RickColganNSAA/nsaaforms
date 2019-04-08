<?php
//echo $_GET[school]; exit;
require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
/* if(!ValidUser($session) )
{
   header("Location:index.php?error=3");
   exit();
} */


 if ($_SERVER["REQUEST_METHOD"] == "POST") {
	 
	 foreach($_POST as $key=>$value) 
       if(!is_array($value))
			$_POST[$key]=mysql_real_escape_string($value);
	
        $sql="SELECT * FROM anthem_dates"; 
        $result=mysql_query($sql); 
        $row=mysql_fetch_array($result);
		
		if (empty($row['id'])){
		$sql="INSERT INTO anthem_dates (id,year,dan_master,between_date,cross_courntry,volleyball,football,unified_bowling,duel_wrestling,wrestling,swimming,girls_basketball,boys_basketball,soccer,boys_baseball,track_field) VALUES ('1','$year','$dan_master','$between_date','$cross_courntry','$volleyball','$football','$unified_bowling','$duel_wrestling','$wrestling','$swimming','$girls_basketball','$boys_basketball','$soccer','$boys_baseball','$track_field')"; 
	    $result=mysql_query($sql);
		}else{
		$sql="UPDATE anthem_dates SET year='$year',dan_master='$dan_master',between_date='$between_date',cross_courntry='$cross_courntry',volleyball='$volleyball',football='$football',unified_bowling='$unified_bowling',duel_wrestling='$duel_wrestling',wrestling='$wrestling',swimming='$swimming',girls_basketball='$girls_basketball',boys_basketball='$boys_basketball',soccer='$soccer',boys_baseball='$boys_baseball',track_field='$track_field' WHERE id=1";
        $result=mysql_query($sql);
		}
		header("Location:anthem_edit.php?session=$session");
	    exit();
	
}	



 echo $init_html;
 echo $header;
        $sql="SELECT * FROM anthem_dates"; 
        $result=mysql_query($sql); 
        $row=mysql_fetch_array($result);
		$year=$row['year'];
		$dan_master=$row['dan_master'];
		$between_date=$row['between_date'];
		$cross_courntry=$row['cross_courntry'];
		$volleyball=$row['volleyball'];
		$football=$row['football'];
		$unified_bowling=$row['unified_bowling'];
		$duel_wrestling=$row['duel_wrestling'];
		$wrestling=$row['wrestling'];
		$swimming=$row['swimming'];
		$girls_basketball=$row['girls_basketball'];
		$boys_basketball=$row['boys_basketball'];
		$soccer=$row['soccer'];
		$boys_baseball=$row['boys_baseball'];
		$track_field=$row['track_field'];

//echo $end_html;
?>
<form method="post" action="anthem_edit.php" enctype="multipart/form-data">
   <br>
   <h3>NATIONAL ANTHEM DATE MODIFICATION OPTIONS</h3>
   <a href="anthem_list.php?session=<?php echo $session;?>">Back</a><br><br>


   <!--Email Address: <input type="email" name="email" value="<?php echo $email;?>" placeholder="email"><br><br>-->
   <table>
   <tr><td><b>Year:</b> </td><td><input type="text" name="year" value="<?php echo $year;?>" style="width:145%;"></td></tr>
   <tr><td><b>Please submit the following to Dan Masters before:</b></td><td><input type="text" name="dan_master" value="<?php echo $dan_master;?>" style="width:145%;"></td></tr> 
   <tr><td><b>NSAA Championship performers will be selected<br> based on auditions submitted and notified between:</b></td><td><input type="text" name="between_date" value="<?php echo $between_date;?>"style="width:145%;"></td></tr> 
   <tr><td><b>NSAA CROSS COUNTRY CHAMPIONSHIPS:</b></td><td><input type="text" name="cross_courntry" value="<?php echo $cross_courntry;?>"style="width:145%;"></td></tr> 
   <tr><td><b>NSAA VOLLEYBALL CHAMPIONSHIP FINALS:</b></td><td><input type="text" name="volleyball" value="<?php echo $volleyball;?>"style="width:145%;"></td></tr> 
   <tr><td><b>NSAA FOOTBALL CHAMPIONSHIP FINALS:</b></td><td><input type="text" name="football" value="<?php echo $football;?>"style="width:145%;"></td></tr> 
   <tr><td><b>NSAA UNIFIED SPORTS, BOWLING CHAMPIONSHIPS:</b></td><td><input type="text" name="unified_bowling" value="<?php echo $unified_bowling;?>"style="width:145%;"></td></tr> 
   <tr><td><b>NSAA DUAL WRESTLING CHAMPIONSHIPS:</b></td><td><input type="text" name="duel_wrestling" value="<?php echo $duel_wrestling;?>"style="width:145%;"></td></tr> 
   <tr><td><b>NSAA WRESTLING CHAMPIONSHIPS:</b></td><td><input type="text" name="wrestling" value="<?php echo $wrestling;?>"style="width:145%;"></td></tr> 
   <tr><td><b>NSAA SWIMMING & DIVING CHAMPIONSHIP FINALS:</b></td><td><input type="text" name="swimming" value="<?php echo $swimming;?>"style="width:145%;"></td></tr> 
   <tr><td><b>NSAA GIRLS BASKETBALL CHAMPIONSHIP FINALS:</b></td><td><input type="text" name="girls_basketball" value="<?php echo $girls_basketball;?>"style="width:145%;"></td></tr> 
   <tr><td><b>NSAA BOYS BASKETBALL CHAMPIONSHIP FINALS:</b></td><td><input type="text" name="boys_basketball" value="<?php echo $boys_basketball;?>"style="width:145%;"></td></tr> 
   <tr><td><b>NSAA SOCCER CHAMPIONSHIP FINALS:</b></td><td><input type="text" name="soccer" value="<?php echo $soccer;?>"style="width:145%;"></td></tr> 
   <tr><td><b>NSAA BOYS BASEBALL CHAMPIONSHIP FINALS:</b></td><td><input type="text" name="boys_baseball" value="<?php echo $boys_baseball;?>"style="width:145%;"></td></tr> 
   <tr><td><b>NSAA TRACK & FIELD CHAMPIONSHIPS:</b></td><td><input type="text" name="track_field" value="<?php echo $track_field;?>"style="width:145%;"></td></tr> 
   


  
  <tr><td></td><td colspan="2"><input type="submit" value="Update" id="submit"></td></tr> </table>
  <input type="hidden" name="session" value="<?php echo $session; ?>" >
</form>


