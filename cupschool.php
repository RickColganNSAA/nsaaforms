<?php
/*********************************
cupschool.php
NSAA can view/manage school's NSAA Cup points/data
Author: Ann Gaffigan
Created: 8/18/15
*********************************/

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session) || $level!=1)
{
   header("Location:../index.php");
   exit();
}
/* $db_name='nsaascores20172018';
$db=mysql_connect($db_host,$db_user,$db_pass); */

if(!$schoolid || $schoolid==0)
{
   header("Location:cupadmin.php?session=$session");
   exit();
}

if($save)
{
    //First save gender, adjustpts, reason
    $sql="UPDATE cupschools SET cupclass='$cupclass',gender='$gender', adjustpts='$adjustpts', reason='".addslashes($reason)."', adjustptsgirls='$adjustptsgirls', reasongirls='".addslashes($reasongirls)."', adjustptsboys='$adjustptsboys', reasonboys='".addslashes($reasonboys)."' WHERE schoolid='$schoolid'";
    $result=mysql_query($sql);

    //NOW SAVE activities and calculate points
    $regpts=GetCupPointAmount(0);
    foreach($cupact as $key=>$value)
    {
        $sql="SELECT * FROM cupschoolsactivities WHERE schoolid='$schoolid' AND activity='$value'";
        $result=mysql_query($sql);
        if(mysql_num_rows($result)==0)
        {
            $sql="INSERT INTO cupschoolsactivities (schoolid,activity) VALUES ('$schoolid','$value')";
            $result=mysql_query($sql);
        }
        $sql="UPDATE cupschoolsactivities SET participating='$part[$key]' WHERE schoolid='$schoolid' AND activity='$value'";
        $result=mysql_query($sql);
        if($part[$key]=='x')
        {
            $sql="SELECT * FROM cuppoints WHERE schoolid='$schoolid' AND activity='$value' AND class='reg'";
            $result=mysql_query($sql);
            if(mysql_num_rows($result)==0)
                $sql="INSERT INTO cuppoints (schoolid,activity,class,points) VALUES ('$schoolid','$value','reg','$regpts')";
            else
                $sql="UPDATE cuppoints SET points='$regpts' WHERE schoolid='$schoolid' AND activity='$value' AND class='reg'";
            $result=mysql_query($sql);
        }
        else
        {
            $sql="DELETE FROM cuppoints WHERE schoolid='$schoolid' AND activity='$value'";
            $result=mysql_query($sql);
        }
    }
}

/****** VIEW DETAILS FOR THIS SCHOOL ******/

echo $init_html;
echo GetHeader($session)."<br>";

echo "<p style=\"text-align:left;\"><a href=\"cupadmin.php?session=$session\">&larr; Return to NSAA Cup Main Menu</a></p>";

$schoolname=GetSchool2($schoolid);
echo "<h1>NSAA Cup: <u>$schoolname</u></h1>";

if($save) echo "<div class=\"alert\">The participation checkmarks have been saved.</div>";

echo "<form method='post' action=\"cupschool.php\">
	<input type=hidden name=\"session\" value=\"$session\">
	<input type=hidden name=\"schoolid\" value=\"$schoolid\">";

echo "<table cellspacing=0 cellpadding=5 frame='all' rules='all' style='max-width:600px;border:#a0a0a0 1px solid;'><caption>";

//LINK TO REGISTRATION FORM
echo "<p><a target=\"_blank\" href=\"schoolregistration.php?session=$session&print=1&schoolid=$schoolid\">View $schoolname's Registration Form</a></p>";

//GIRLS only or BOYS only school?
$sql="SELECT * FROM cupschools WHERE schoolid='$schoolid'";
$result=mysql_query($sql);
$sch=mysql_fetch_array($result);
echo "<p style=\"text-align:left;\"><input type=radio name=\"gender\" value=\"girls\"";
if($sch[gender]=="girls") echo " checked";
echo "> GIRLS ONLY school</p>";
echo "<p style=\"text-align:left;\"><input type=radio name=\"gender\" value=\"boys\"";
if($sch[gender]=="boys") echo " checked";
echo "> BOYS ONLY school</p>";
echo "<p style=\"text-align:left;\"><input type=radio name=\"gender\" value=\"\"";
if($sch[gender]=="") echo " checked";
echo "> BOYS and GIRLS school</p>";
echo "<p style='text-align: left'><b>CUP CLASS:</b> <input size='45' type='text' name='cupclass' value='$sch[cupclass]' /></p>";
echo "</caption>";

echo "<tr align=center><th rowspan=2>Activity</th><th colspan=2>Participating?</th><th colspan=2>Top 8?</th><th rowspan=2>Girls<br>Points</th><th rowspan=2>Boys<br>Points</th><th rowspan=2>Overall<br>Points</th></tr>
	<tr align=center><td><b>Check</b></td><td><b>Points</b></td><td><b>Place</b></td><td><b>Points</b></td></tr>";

$sql="SELECT * FROM cupactivities";
$result=mysql_query($sql);
$i=0; $total=0; $girls=0; $boys=0;
$girlsreg=0; $boysreg=0; $totalreg=0;
while($row=mysql_fetch_array($result))
{
   $sport=$row[activity];
   $points=0;
   echo "<tr align=center><td width='150px' align='left'><a href=\"cupplaces.php?session=$session&sport=$sport&class=".GetClass(GetSID2(GetSchool2($schoolid),$sport),$sport)."\">".GetActivityName($sport)."</a></td>";
	//REGISTRATION - EXCLUDE WRD
   if($sport!='wrd')
   {
/*       if ($sport=='mu')
	  echo $sql2="SELECT t1.participating,t2.points FROM cupschoolsactivities AS t1, cuppoints AS t2 WHERE t1.activity=t2.activity AND t1.schoolid=t2.schoolid AND (t2.class='reg' OR t2.class!='') AND t1.schoolid='$schoolid' AND t1.activity='$sport'";
	  else */
	  $sql2="SELECT t1.participating,t2.points FROM cupschoolsactivities AS t1, cuppoints AS t2 WHERE t1.activity=t2.activity AND t1.schoolid=t2.schoolid AND t2.class='reg' AND t1.schoolid='$schoolid' AND t1.activity='$sport'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(mysql_num_rows($result2)==0) $row2[points]=0;
      echo "<td><input type=hidden name=\"cupact[$i]\" value=\"$row[activity]\"><input type=checkbox name=\"part[$i]\" value=\"x\"";
      if($row2[participating]=='x')  echo " checked";
      echo "></td><td>$row2[points]</td>";
      $regpoints=$row2[points];
   }
   else 
   {
      $regpoints=0;
      echo "<td>n/a</td><td>n/a</td>";
   }
	//TOP 8 (OR FOR MUSIC, SPECIAL CASE):
/*       if($sport=='mu')
      {
         //Need at least 2 IM entries to get 5 points; another 5 for >=2 VM entries
         $school2=GetSchool2($schoolid);
	 $mustr="";

	 //We are NOT pulling real-time points here. Just checked the cup database.
	 //The NSAA pulls Music points in after the district contest is over, to ensure accuracy
         $sql2="SELECT * FROM cuppoints WHERE activity='mu' AND schoolid='$schoolid' AND class='$sch[cupclass]'";
         $result2=mysql_query($sql2);
	 if(!$row2=mysql_fetch_array($result2)) $row2[points]=0;
        //SHOW YOUR WORK:
	 if($row2[points]>0) $mupoints="<b>$row2[points]</b>";
	 else $mupoints=$row2[points];
	 echo "<td>-</td><td>$mupoints</td>";
      } //end if Music
      else */
      {
        //TOP 8
         $sql2="SELECT * FROM cuppoints WHERE schoolid='$schoolid' AND activity='$sport' AND class!='reg' AND ignorepts!='x'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         if(mysql_num_rows($result2)==0) $row2[points]=0;
         $points+=$row2[points];
    	//SHOW YOUR WORK: 
         $sid=GetSID2(GetSchool2($schoolid),$sport);
   	  $sql3="SELECT place FROM cupplaces WHERE activity='$sport' AND sid='$sid'";
   	 $result3=mysql_query($sql3);
   	 $row3=mysql_fetch_array($result3);
    	 if($row2[points]>0) $row2[points]="<b>$row2[points]</b>";
   	 else if($row3[place]>0 && $row2[points]==0) $row2[points]="<label style=\"background-color:yellow;padding:5px;\">$row2[points]</label>";
   	 if(mysql_num_rows($result3)==0) $row3[place]="-";
         echo "<td>$row3[place]</td><td>$row2[points]</td>";
      } //end if NOT Music
   //GIRLS POINTS
   $curgirls=0;
   if($sch[gender]!='boys')	//NOT A BOYS ONLY SCHOOL
   {
      if($row[gender]=="Girls") 
      {
	 $curgirls=$regpoints+$points;	//GET ALL THE POINTS FOR A GIRLS SPORT
	 $girlsreg+=$regpoints;
      }
      else if($row[gender]=='') 
      {
	 //if($sch[gender]=="girls")	//GIRLS ONLY SCHOOL GETS ALL POINTS FOR GENDER NEUTRAL SPORT
	    $curgirls=$regpoints+$points;
	 //else				//GENDER NEUTRAL SCHOOL GETS 1/2 PTS FOR GENDER NEUTRAL SPORT
	   // $curgirls=$regpoints+($points/2);
	 $girlsreg+=$regpoints;
      }
      //ELSE THIS IS A BOYS ONLY SPORT
   }
   $girls+=$curgirls;
   if($curgirls>0)
      echo "<th>$curgirls</th>";
   else echo "<td>$curgirls</td>";
   //BOYS POINTS
   $curboys=0;
   if($sch[gender]!='girls')     //NOT A GIRLS ONLY SCHOOL
   {
      if($row[gender]=="Boys") 
      {
         $curboys=$regpoints+$points;        //GET ALL THE POINTS FOR A BOYS SPORT
	 $boysreg+=$regpoints;
      }
      else if($row[gender]=='')
      {
         //if($sch[gender]=="boys")      //BOYS ONLY SCHOOL GETS ALL POINTS FOR GENDER NEUTRAL SPORT
            $curboys=$regpoints+$points;
         //else                           //GENDER NEUTRAL SCHOOL GETS 1/2 PTS FOR GENDER NEUTRAL SPORT
           // $curboys=$regpoints+($points/2);
         $boysreg+=$regpoints;
      }
   }
   $boys+=$curboys;
   if($curboys>0)
       echo "<th>$curboys</th>";
   else echo "<td>$curboys</td>";
   //TOTAL POINTS
   $points+=$regpoints;
   $totalreg+=$regpoints;
/*          if($sport=='mu'){
    	 $sql_class="SELECT * FROM cupschools WHERE schoolid='$schoolid'"; 
         $result_class=mysql_query($sql_class);
         $mu_class=mysql_fetch_array($result_class); 
		 
		 $sql_point="SELECT * FROM cuppoints WHERE activity='mu' AND schoolid='$schoolid' AND class='$mu_class[cupclass]'";
         $result_point=mysql_query($sql_point);
	     $mu_points=mysql_fetch_array($result_point);  
		 if($mu_points[points]>0) $points=$points + $mu_points[points];
		 } */
   if($points>0)
      echo "<th>$points</th></tr>";
   else echo "<td>$points</td></tr>";
   $total+=$points;
   $i++;
}
//ADD OR DEDUCT POINTS (OVERRIDE OPTION) - For TOTAL, GIRLS and BOYS Divisions
echo "<tr><td colspan=8>&nbsp;</td></tr><tr align='left'><td colspan=8><h3>ADJUST POINTS:</h3>The NSAA can override the point total for any school. To DEDUCT points from a school's total, use a - sign in the Pts box (for example: -5). To ADD points to a school's total, simply enter the number of points to add in the Pts box.</td></tr>
	<tr align='center'><th>Division</th><th colspan=6>Reason</th><th>Points</th></tr>";
echo "<tr align='center'><th align='right'>GIRLS:</th><td colspan=6><input type=text name=\"reasongirls\" size=45 value=\"$sch[reasongirls]\"></td><td><input type=text name=\"adjustptsgirls\" size=5 value=\"$sch[adjustptsgirls]\"></td></tr>";
$girls+=$sch[adjustptsgirls];
echo "<tr align='center'><th align='right'>BOYS:</th><td colspan=6><input type=text name=\"reasonboys\" size=45 value=\"$sch[reasonboys]\"></td><td><input type=text name=\"adjustptsboys\" size=5 value=\"$sch[adjustptsboys]\"></td></tr>";
$boys+=$sch[adjustptsboys];
echo "<tr align='center'><th align='right'>OVERALL:</th><td colspan=6><input type=text name=\"reason\" size=45 value=\"$sch[reason]\"></td><td><input type=text name=\"adjustpts\" size=5 value=\"$sch[adjustpts]\"></td></tr>";
$total+=$sch[adjustpts];

//SHOW TOTAL POINTS IN ALL DIVISIONS
echo "<tr><td colspan=8>&nbsp;</td></tr><tr align='left'><td colspan=8><h3>TOTALS:</h3></th></tr>";
echo "<tr align=center><th colspan=7 align='right'>GIRLS DIVISION:</th><th>$girls</th></tr>";
   echo "<tr><td colspan=8 align='right'>Girls Registration Points: <b>$girlsreg</b></td></tr>";
echo "<tr align=center><th colspan=7 align='right'>BOYS DIVISION:</th><th>$boys</th></tr>";
   echo "<tr><td colspan=8 align='right'>Boys Registration Points: <b>$boysreg</b></td></tr>";
echo "<tr align=center><th colspan=7 align='right'>OVERALL:</th><th>$total</th></tr>";
   echo "<tr><td colspan=8 align='right'>Total Registration Points: <b>$totalreg</b></td></tr>";
echo "</table>";
echo "<input type=submit name=\"save\" class=\"fancybutton\" value=\"SAVE\">";
echo "</form>";

//UPDATE POINT TOTALS IN ALL DIVISIONS
$sql="UPDATE cupschools SET girlspoints='$girls',boyspoints='$boys',allpoints='$total' WHERE schoolid='$schoolid'";
$result=mysql_query($sql);
echo mysql_error();

?>
