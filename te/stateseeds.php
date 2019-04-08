<?php
require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require 'tefunctions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
if(!$sport) $sport="te_b";

$sportname=GetActivityName($sport);

if($savelabels)
{
   for($i=0;$i<count($labelp1);$i++)
   {
      $sql="SELECT * FROM ".$sport."playerlabels WHERE division='$division' AND player1='$labelp1[$i]' AND player2='$labelp2[$i]'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)
      {
	 $sql2="INSERT INTO ".$sport."playerlabels (division,player1,player2,label) VALUES ('$division','$labelp1[$i]','$labelp2[$i]','".addslashes($label[$i])."')";
	 $result2=mysql_query($sql2);
      }
      else
      {
	 $row=mysql_fetch_array($result);
	 $sql2="UPDATE ".$sport."playerlabels SET label='".addslashes($label[$i])."' WHERE id='$row[id]'";
	 $result2=mysql_query($sql2);
      }
   }
}
if($saveseeds)
{
   $sql="DELETE FROM ".$sport."seeds WHERE class='$class' AND division='$division'";
   $result=mysql_query($sql);

   $temp=split(";",$classdiv);
   $class=$temp[0]; $division=$temp[1];
   $thisbyect=0;
   for($i=0;$i<count($seed);$i++)
   {
      if(ereg("doubles",$division))
      {
         $player=split(";",$players[$i]);
         $sql="SELECT * FROM ".$sport."seeds WHERE seed='$seed[$i]' AND class='$class' AND division='$division'";
         $result=mysql_query($sql);
         if(mysql_num_rows($result)==0)
	    $sql2="INSERT INTO ".$sport."seeds (class,division,seed,player1,player2,bye,hideseed) VALUES ('$class','$division','$seed[$i]','$player[0]','$player[1]','$bye[$i]','$hideseed[$i]')";
	 else
	    $sql2="UPDATE ".$sport."seeds SET player1='$player[0]',player2='$player[1]',bye='$bye[$i]',hideseed='$hideseed[$i]' WHERE seed='$seed[$i]' AND class='$class' AND division='$division'";
         $result2=mysql_query($sql2);
echo mysql_error();
      }
      else
      {
         $sql="SELECT * FROM ".$sport."seeds WHERE seed='$seed[$i]' AND class='$class' AND division='$division'";
         $result=mysql_query($sql);
         if(mysql_num_rows($result)==0)
            $sql2="INSERT INTO ".$sport."seeds (class,division,seed,player1,bye,hideseed) VALUES ('$class','$division','$seed[$i]','$players[$i]','$bye[$i]','$hideseed[$i]')";
         else
            $sql2="UPDATE ".$sport."seeds SET player1='$players[$i]',bye='$bye[$i]',hideseed='$hideseed[$i]' WHERE seed='$seed[$i]' AND class='$class' AND division='$division'";
         $result2=mysql_query($sql2);
      }
      if($bye[$i]=='x') $thisbyect++;
   }
   $sql="DELETE FROM ".$sport."seeds WHERE player1='0'";
   $result=mysql_query($sql);
   //check for duplicates
   $error=0;
   $sql="SELECT player1,COUNT(player1) FROM ".$sport."seeds WHERE class='$class' AND division='$division' GROUP BY player1";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      if($row[1]>1) $error=1;
   }
   //check # of byes
   if($class=="Z") //ALL BOYS CLASSES & GIRLS CLASSES - NO DISTRICTS (AS OF 10/7/11)
   {
      $sql="SELECT t1.*,t2.school FROM ".$sport."distresults AS t1, eligibility AS t2 WHERE t1.player1=t2.id AND t1.player1>0 AND t1.division='$division' ORDER BY t2.school";
      $lines=16;
   }
   else
   {
      $sql="SELECT t1.* FROM ".$sport."state AS t1,".$sport."school AS t2 WHERE t1.player1>0 AND t1.sid=t2.sid AND t2.class='$class' AND t1.division='$division' ORDER BY t2.school";
      $lines=32;
   }
   $result=mysql_query($sql);
   $entryct=mysql_num_rows($result);
   $byect=$lines-$entryct; 
   if($entryct==33) $byect=0;
   if($thisbyect>$byect) $error=2;
   else if($thisbyect<$byect) $error=3;
   if($error) unset($saveseeds);
   RandomizeNonSeededEntries($sport,$class,$division);
}

echo $init_html;
echo $header;

if($class=="Z")
{
   $lines=16;
}
else 
{
   $lines=32;
}

echo "<br><a href=\"".$sport."main.php?session=$session\">Return to State Tennis MAIN MENU</a>";
echo "<form method=\"post\" action=\"stateseeds.php\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"sport\" value=\"$sport\">";
echo "<br><table cellspacing=0 class=nine cellpadding=4><caption><b>$sportname State Seeds & Brackets</b><br><i>Please select a Class/Division:</i>&nbsp;";
echo "<select onchange=\"submit();\" name=\"classdiv\"><option value=\"\">Select Class & Division</option>";
$classes=GetClasses($sport);
for($i=0;$i<count($classes);$i++)
{
   $sql="SELECT DISTINCT division FROM ".$sport."state WHERE division!='substitute' ORDER BY division";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      if(ereg("singles",$row[division]))
      {
	 $temp=split("singles",$row[division]);
	 $showdiv="#".$temp[1]." Singles";
      }
      else
      {
         $temp=split("doubles",$row[division]);
         $showdiv="#".$temp[1]." Doubles";
      }
      echo "<option value=\"$classes[$i];$row[division]\"";
      if($classdiv=="$classes[$i];$row[division]") echo " selected";
      echo ">Class $classes[$i] $showdiv</option>";
   }
}
echo "</select>".mysql_error()."</caption>";
if($classdiv && $classdiv!='')
{
   $temp=split(";",$classdiv);
   $class=$temp[0]; $division=$temp[1];
   if(ereg("singles",$division))
   {
      $temp=split("singles",$division);
      $showdiv="#".$temp[1]." Singles";
   }
   else
   {
      $temp=split("doubles",$division);
      $showdiv="#".$temp[1]." Doubles";
   }
   
   if($reset==1)	//RESEET THIS BRACKET
   {
      $sql="DELETE FROM ".$sport."brackets WHERE class='$class' AND division='$division'";
      $result=mysql_query($sql);
      RandomizeNonSeededEntries($sport,$class,$division);
   }
   echo "<tr align=center valign=top><td>";
   echo "<input type=hidden name=\"division\" value=\"$division\">";
   echo "<table cellspacing=0 cellpadding=3 frames=all rules=all style=\"border:#808080 1px solid;\">";
   echo "<caption><b>Class $class $showdiv Entries:</b> (in school order)";
   //if($class=="B" && $sport=='te_g') //ALL BOYS CLASSES & GIRLS CLASS A - NO DISTRICTS (AS OF 7/19/11)
  //    $sql="SELECT t1.*,t2.school FROM ".$sport."distresults AS t1, eligibility AS t2 WHERE t1.player1=t2.id AND t1.player1>0 AND t1.division='$division' ORDER BY t2.school";
   //else
      $sql="SELECT t1.* FROM ".$sport."state AS t1,".$sport."school AS t2 WHERE t1.player1>0 AND t1.sid=t2.sid AND t2.class='$class' AND t1.division='$division' ORDER BY t2.school";
   $result=mysql_query($sql);
   $totalentries=mysql_num_rows($result);
   echo "<br>$totalentries Total Entries";
   if($savelabels)
      echo "<br><div class=alert style=\"text-align:center;width:350px;\"><i>The labels below have been saved.</i></div>";
   echo "</caption>";
   echo "<tr align=center><td><b>Name, Grade, School</b></td><td><b>How to Show on Bracket</b></td></tr>";
   $entries=array(); $ix=0;
   $delsql="";
   while($row=mysql_fetch_array($result))
   {
      $delsql.="player1!='$row[player1]' AND ";
      echo "<tr align=left valign=top><td>";
      $sql2="SELECT * FROM ".$sport."brackets WHERE player1='$row[player1]' AND class='$class' AND division='$division'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)>0) 
      {
	  //echo "X<br>";
	  //MAKE SURE THEY'RE ONLY ENTERED ON ONE LINE
	  $row2=mysql_fetch_array($result2);
	  //$sql2="DELETE FROM ".$sport."brackets WHERE player1='$row[player1]' AND class='$class' AND division='$division' AND id!='$row2[id]'";
	  //$result2=mysql_query($sql2);
      }
      $entries[name][$ix]=GetStudentInfo($row[player1]);
      $entries[player1][$ix]=$row[player1];
      echo $entries[name][$ix];
      //if($class=="B" && $sport=='te_g') //ALL BOYS CLASSES & GIRLS CLASS A - NO DISTRICTS (AS OF 7/19/11)
        // $entries[school][$ix]=GetSchoolName(GetSID2($row[school],$sport),$sport,date("Y"));
      //else
         $entries[school][$ix]=GetSchoolName($row[sid],$sport,date("Y"));
      if(ereg("doubles",$division))
      {
         $name=GetStudentInfo($row[player2]);
         echo "<br>".$name;
         $entries[name][$ix].=", $name";
         $entries[player2][$ix]=$row[player2];
      }
      echo ", ";
      //if($class=="B" && $sport=='te_g') //ALL BOYS CLASSES & GIRLS CLASS A - NO DISTRICTS (AS OF 7/19/11)
        // echo GetSchoolName(GetSID2($row[school],$sport),$sport,date("Y"))."</td></tr>";
      //else
         echo GetSchoolName($row[sid],$sport,date("Y"))."</td>";
      echo "<td><input type=hidden name=\"labelp1[$ix]\" value=\"$row[player1]\"><input type=hidden name=\"labelp2[$ix]\" value=\"$row[player2]\"><input type=text size=40 name=\"label[$ix]\" value=\"".GetPlayerLabel($sport,$division,$row[player1],$row[player2])."\"></td>";
      echo "</tr>";
      $ix++;
   }
   $sql="DELETE FROM ".$sport."brackets WHERE ".$delsql." class='$class' AND division='$division' AND bye!='x'";
   //$result=mysql_query($sql);
   $sql="DELETE FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND bye='x' AND player1>0";
   //$result=mysql_query($sql);
   echo "</table>";
   echo "<input type=submit name=\"savelabels\" value=\"Save Bracket Labels\" class=\"fancybutton2\">";
   echo "</td><td>";
   echo "<table cellspacing=0 cellpadding=3 frames=all rules=all style=\"border:#808080 1px solid;\">";
   echo "<caption>";
   echo "<b>Class $class $showdiv Seeded Entries:</b>";
   echo "<br><div class='alert'><p>Enter/Edit the seeded players for this bracket below and click <b>\"Save Seeds\"</b></p>";
   //CAN ONLY GENERATE BRACKET IF THE SEEDS BELOW HAVE BEEN ADDED
   $sql="SELECT * FROM ".$sport."seeds WHERE seed>0 AND class='$class' AND division='$division'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>=10 && !$error)
   {
      echo "<p>OR <a href=\"statebrackets.php?session=$session&classdiv=$classdiv&sport=$sport\">Go to Class $class $showdiv Bracket &rarr;</a></p>";
   }
   echo "<p>OR <a href=\"statebrackets.php?session=$session&classdiv=$classdiv&sport=$sport&pdf=1&blankpdf=1\" target=\"_blank\">Publish Blank Bracket (to reset bracket on the website)</a></p></div>";
   if($saveseeds)
      echo "<br><div class=alert style=\"text-align:center;width:350px;\"><i>The seeds below have been saved.</i></div>";
   if($error==1)
      echo "<br><div class=error style=\"text-align:center;width:350px;\">ERROR: You have entered a seed MORE THAN ONCE below.</div>";
   else if($error==2)
      echo "<br><div class=error style=\"text-align:center;width:400px;\">ERROR: You have entered TOO MANY byes. (There should be $byect)</div>";
   else if($error==3)
      echo "<br><div class=error style=\"text-align:center;width:400px;\">ERROR: You have entered TOO FEW byes. (There should be $byect)</div>";
   echo "</caption>";
   echo "<tr align=center><td><b>Seed</b></td><td><b>Select Player(s)</b></td><td><b>BYE<br>1st Rnd</b></td><td><b>Hide Seed<br>on Bracket</b></td></tr>";
   for($i=0;$i<12;$i++)
   {
      $curseed=$i+1;
      echo "<tr align=center><td>#".$curseed."</td>";
      echo "<input type=hidden name=\"seed[$i]\" value=\"$curseed\">";
      echo "<td><select name=\"players[$i]\"><option value='0'>Select Player(s)</option>";
      for($j=0;$j<count($entries[name]);$j++)
      {
	 if(ereg("doubles",$division))
	    $curid=$entries[player1][$j].";".$entries[player2][$j];
	 else $curid=$entries[player1][$j];
         echo "<option value=\"$curid\"";
	 $sql="SELECT * FROM ".$sport."seeds WHERE seed='$curseed' AND class='$class' AND division='$division'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
	 if(ereg("doubles",$division))
	    $thisid=$row[player1].";".$row[player2];
         else $thisid=$row[player1]; 
	 if($thisid==$curid) echo " selected";
         echo ">".$entries[school][$j]."--".$entries[name][$j]."</option>";
      }
      echo "</select></td>";
      echo "<td><input type=checkbox name=\"bye[$i]\" value='x'";
      $sql="SELECT * FROM ".$sport."seeds WHERE seed='$curseed' AND class='$class' AND division='$division'";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      if($row[bye]=='x' || (mysql_num_rows($result)==0 && $curseed<=($lines-$totalentries))) echo " checked";
      echo ">";
      echo "</td><td><input type=checkbox name=\"hideseed[$i]\" value='x'";
      if($row[hideseed]=='x') echo " checked";
      echo "></tr>";
      $sql2="DELETE FROM ".$sport."brackets WHERE player1='$row[player1]' AND class='$class' AND division='$division' AND seed=0";
      //$result2=mysql_query($sql2);
   }
   echo "</table>";
   //SEE IF NON SEEDED PLAYERS ARE ON THE BRACKET YET
   $sql="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND player1>0 AND bye!='x' AND seed=0 AND round<=1 ORDER BY round,line";
   $result=mysql_query($sql);
   //if(mysql_num_rows($result)==0)
     // $buttonlabel="Save Seeds & Randomly Place Non-Seeded Players";
   //else
      $buttonlabel="Save Seeds";
   echo "<input type=submit name=\"saveseeds\" value=\"$buttonlabel\" class=\"fancybutton2\">";
   echo "</td></tr>";
}
echo "</table>";
echo "</form>";

echo $end_html;
?>
