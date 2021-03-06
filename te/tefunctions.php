<?php
/*******************************************
tefunctions.php
Tennis Functions
Created 10/1/08
Author: Ann Gaffigan
********************************************/
require "../variables.php";
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);


function GetPlayerLabel($sport,$division,$player1,$player2)
{
   require_once("../functions.php");
   require_once("../../calculate/functions.php");
   //GetStudentInfo($row[player1]), GetSchoolName(GetSID2($row[school],$sport);   
   $sql="SELECT school FROM eligibility WHERE id='$player1'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $school=$row[school];
   $sid=GetSID2($school,$sport);
   $default=GetStudentInfo($player1);
   if(ereg("doubles",$division)) $default.="/".GetStudentInfo($player2);
   $default.=", ".GetSchoolName($sid,$sport);

   $sql="SELECT * FROM ".$sport."playerlabels WHERE division='$division' AND player1='$player1' AND player2='$player2'";
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result))
   {
      if($row[label]!='') return $row[label];
      else return $default;
   }
   else return $default;
}
function GetEntryCount($sport,$class,$division)
{
   $sql="SELECT t1.* FROM ".$sport."state AS t1,".$sport."school AS t2 WHERE t1.player1>0 AND t1.sid=t2.sid AND t2.class='$class' AND t1.division='$division' ORDER BY t2.school";
   $result=mysql_query($sql);
   $entryct=mysql_num_rows($result);
   if($entryct<32) $entryct=32;
   return $entryct;
}
function RandomizeNonSeededEntries($sport,$class,$division)
{
   require '../variables.php';
   if($class=="Z") //ALL BOYS CLASSES & GIRLS - NO DISTRICTS (AS OF 10/7/11)
      $sql="SELECT t1.*,t2.school FROM ".$sport."distresults AS t1,eligibility AS t2,$db_name2.tebdistricts AS t3 WHERE t3.id=t1.distid AND t1.player1=t2.id AND t1.player1>0 AND t3.class='$class' AND t1.division='$division' ORDER BY t2.school";
   else
      $sql="SELECT t1.* FROM ".$sport."state AS t1,".$sport."school AS t2 WHERE t1.player1>0 AND t1.sid=t2.sid AND t2.class='$class' AND t1.division='$division' ORDER BY t2.school";
   $result=mysql_query($sql);
   $entryct=mysql_num_rows($result);
   if($entryct<32) $entryct=32;

   $sql="SELECT * FROM ".$sport."seeds WHERE class='$class' AND division='$division' ORDER BY seed";
   $result=mysql_query($sql);
   $seedct=mysql_num_rows($result);
   while($row=mysql_fetch_array($result))
   {
      $seed=$row[seed];
      $index=$seed-1;
      if($class=="Z")      //16-person Bracket; ALL BOYS CLASSES & GIRLS CLASSES - NO DISTRICTS (AS OF 10/7/11)
         $line=$seedpos[$entryct][16][$seedct][$index]; //LINE THIS SEED SITS ON
      else                 //32-person Bracket
         $line=$seedpos[$entryct][$seedct][$index]; //LINE THIS SEED SITS ON
      //ROUND 1:
      $curround=1;
      $sql2="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND seed='$seed'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)      //INSERT (THIS SEED WAS NOT ENTERED ON BRACKET YET)
         $sql3="INSERT INTO ".$sport."brackets (class,division,round,line,player1,player2,seed,hideseed) VALUES ('$class','$division','$curround','$line','$row[player1]','$row[player2]','$seed','$row[hideseed]')";
      else //UPDATE (MAKE SURE SEED IS ON CORRECT LINE WITH CORRECT PLAYER ON BRACKET)
         $sql3="UPDATE ".$sport."brackets SET hideseed='$row[hideseed]',round='$curround',line='$line',player1='$row[player1]',player2='$row[player2]' WHERE class='$class' AND division='$division' AND seed='$seed'";
      $result3=mysql_query($sql3);
echo mysql_error();
      //DOES THIS SEED GET A BYE IN FIRST ROUND?
      if($line%2==0) $otherline=$line-1;
      else $otherline=$line+1;
      //$otherline is where opponent of $line (this seed) sits
      $sql3="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND round='1' AND line='$otherline'";
      $result3=mysql_query($sql3);
      if(mysql_num_rows($result3)==0 && $row[bye]=='x')    //INSERT a BYE on $otherline if this seed is supposed to have a bye
      {
         $sql4="INSERT INTO ".$sport."brackets (class,division,round,line,bye) VALUES ('$class','$division','1','$otherline','x')";
         $result4=mysql_query($sql4);
      }
      else if(mysql_num_rows($result3)>0)  //UPDATE whether or not $otherline is a bye
      {
         $sql4="UPDATE ".$sport."brackets SET bye='$row[bye]' WHERE class='$class' AND division='$division' AND round='1' AND line='$otherline'";
         $result4=mysql_query($sql4);
      }
      //IF THERE WERE SEEDS ON THIS BRACKET THAT NO LONGER EXIST, REMOVE THEM (MAYBE THEY WENT FROM 12 TO 8 SEEDS, FOR EX.)
      $sql2="UPDATE ".$sport."brackets SET seed=0 WHERE seed>'$seedct'";
      $result2=mysql_query($sql2);
   }

   $entries=GetNonSeededEntries($sport,$class,$division);
   $entries=explode("<entry>",$entries);
   for($i=0;$i<count($entries);$i++)
   {
      if(ereg("doubles",$division))
      {
         $players=explode(",",$entries[$i]);
	 $player1=$players[0]; $player2=$players[1];
      }
      else
      {
  	 $player1=$entries[$i]; $player2=0;
      }
      $sql="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND ";
      if($entryct==33) $sql.="(round='1' OR round='0')";
      else $sql.="round='1'";
      $sql.=" AND player1='$player1'";
      if(ereg("doubles",$division))
         $sql.=" AND player2='$player2'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)       //IF PLAYER(S) NOT ON BRACKET YET, GIVE THEM A RANDOM SPOT IN ROUND 1
      {
         //GET ARRAY OF AVAILABLE LINE SLOTS
         $lines=range(1,$entryct);         //FOR entryct=33, Line 14 = Line 1 for Round 0, Line 33 = Line 2 for Round 0
         $availlines=array(); $a=0;        //ARRAY OF AVAILABLE LINES
         foreach($lines as $key => $value)
         {
            $curline=$value;
            $useround=1; $useline=$curline;
            if($entryct==33)       //CONVERT line 14 to Round 0, Line 1 and line 33 to Round 0, Line 2
            {
               if($curline==14)
               {
                  $useround=0; $useline=1;
               }
               else if($curline==33)
               {
                  $useround=0; $useline=2;
               }
            }
            //IF ALREADY A PLAYER1 ON THIS LINE OR IT IS MARKED AS A BYE, DO NOT ADD IT TO $linestr
            $sql2="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND round='$useround' AND line='$useline' AND (player1>0 OR bye='x')";
            $result2=mysql_query($sql2);
            if(mysql_num_rows($result2)==0)        //NOT FILLED YET, ADD TO LIST
            {
               $availlines[$a]=$curline; $a++;
            }
         }
         if(count($availlines)==0) //SHOW ERROR
         {
            echo "$sql2<br>Can't find an open slot for ".GetStudentInfo($player1)." #".$player1."<br>";
            exit();
         }
         //NOW GET RANDOM VALUE FROM ARRAY OF AVAILABLE LINES
         $index=rand(0,count($availlines)-1);
         $randline=$availlines[$index];

         //WHEN WE GET HERE, $randline IS THE LINE WE PUT THIS NON-SEEDED PLAYER(S) ON:
         $useround=1;      //DEFAULT: ROUND 1
         if($entryct==33)  //CONVERT line 14 to Round 0, Line 1 and line 33 to Round 0, Line 2
         {
            if($randline==14)
            {
               $useround=0; $randline=1;
            }
            else if($randline==33)
            {
               $useround=0; $randline=2;
            }
            //ELSE KEEP EVERYTHING AS IS
         }
	 if($player1)
	 {
            $sql2="INSERT INTO ".$sport."brackets (class,division,round,line,player1,player2) VALUES ('$class','$division','$useround','$randline','$player1','$player2')";
            $result2=mysql_query($sql2);
	    //echo "$sql2<br>";
         }
      }
   } 
   return;
}
function GetNonSeededEntries($sport,$class,$division)
{
   if($class=="Z") //ALL BOYS CLASSES & GIRLS - NO DISTRICTS (AS OF 10/7/11)
      $sql="SELECT t1.*,t2.school FROM ".$sport."distresults AS t1,eligibility AS t2,$db_name2.tebdistricts AS t3 WHERE t3.id=t1.distid AND t1.player1=t2.id AND t1.player1>0 AND t3.class='$class' AND t1.division='$division' ORDER BY t2.school";
   else
      $sql="SELECT t1.* FROM ".$sport."state AS t1,".$sport."school AS t2 WHERE t1.player1>0 AND t1.sid=t2.sid AND t2.class='$class' AND t1.division='$division' ORDER BY t2.school";
   $result=mysql_query($sql);
   $entryct=mysql_num_rows($result);
   $entries="";
   while($row=mysql_fetch_array($result))
   {
      //IS(ARE) PLAYER(S) SEEDED?
      $sql2="SELECT * FROM ".$sport."seeds WHERE class='$class' AND division='$division' AND player1='$row[player1]' AND player2='$row[player2]'";
      $result2=mysql_query($sql2);
      if(mysql_num_rows($result2)==0)      //NOT SEEDED: ADD TO LIST THEY CAN SELECT FROM FOR NON-SEEDED SLOTS
      {
         $entries.=$row[player1];
         if(ereg("doubles",$division))
         {
	    $entries.=",$row[player2]";
         }
	 $entries.="<entry>";
      }//end if not seeded
   }
   if($entries!='') $entries=substr($entries,0,strlen($entries)-7);
   return $entries;
}
function AreBunnyMatches($sport,$class)
{
   $divs=array("singles1","singles2","doubles1","doubles2");
   for($i=0;$i<count($divs);$i++)
   {
      $division=$divs[$i];
   $sql="SELECT t1.* FROM ".$sport."state AS t1,".$sport."school AS t2 WHERE t1.player1>0 AND t1.sid=t2.sid AND t2.class='$class' AND t1.division='$division'";
   $result=mysql_query($sql);
   $entryct=mysql_num_rows($result);
	if($entryct==33) return TRUE;
   }
}
function GetMatchNum($sport,$class,$division,$round,$line)
{
   //UPDATED 4/2/13 TO HANDLE ROUND 0 (33 ENTRIES)
   $sql="SELECT t1.* FROM ".$sport."state AS t1,".$sport."school AS t2 WHERE t1.player1>0 AND t1.sid=t2.sid AND t2.class='$class' AND t1.division='$division'";
   $result=mysql_query($sql);
   $entryct=mysql_num_rows($result);
/*
   if($entryct==33 && ($round==0 || $round==1))
   {
      $sql="SELECT * FROM tematchnums WHERE entryct='$entryct' AND division='$division' AND round='$round' AND (line1='$line' OR line2='$line')";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      return $row[matchnum];
   }
   else if($entryct==33)	//IF ROUNDS 2 OR LATER, START MATCHNUM at 1 + HIGHEST MATCHNUM LISTED FOR ROUND 1
   {
      $sql="SELECT * FROM tematchnums WHERE entryct='$entryct' AND round=1 ORDER BY matchnum DESC LIMIT 1";
      $result=mysql_query($sql);
      $row=mysql_fetch_array($result);
      $matchnum=$row[matchnum]; $rstart=2;
   }
   else
   {
*/
      $matchnum=0; $rstart=1;
//   }
   if(AreBunnyMatches($sport,$class)) $rstart=0;
   $divisions=array("singles1","singles2","doubles1","doubles2");          //use for MATCH NUMBERING
   $roundsplus1=6; //ALL BOYS CLASSES & GIRLS CLASS A - NO DISTRICTS (AS OF 7/19/11)
   for($i=$rstart;$i<=$roundsplus1;$i++)
   {
      for($d=0;$d<count($divisions);$d++)
      {
         if($i==0)
	 {
            $sql="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$divisions[$d]' AND round='$i'";                
            $result=mysql_query($sql);
	    if($row=mysql_fetch_array($result))
	    {
               $matchnum++;
               if($round==$i && $division==$divisions[$d])
                   return $matchnum;
	    }
	 }
	 else if($i==1)
	 {
	    $lines=pow(2,$roundsplus1-$i);
            for($j=2;$j<=$lines;$j+=2)
            {
	       $otherline=$j-1;
	       $sql="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$divisions[$d]' AND round='$i'";
	       $sql.=" AND (line='$j' OR line='$otherline')";
               $result=mysql_query($sql);
	       $bye="";
               while($row=mysql_fetch_array($result))
	       {
	          if($row[bye]=='x') 
	             $bye='x';
	       }
	       if($bye!='x') 
	       {
                  $matchnum++;
                  if($round==$i && $line==$j && $division==$divisions[$d])
                     return $matchnum;
               }  	
	       else if($round==$i && $line==$j && $division==$divisions[$d]) //BYE 
 	             return 0;
            }
         }//end if not round 0 or 1
         else if($i<($roundsplus1-2))	//regular rounds
   	 {
	    $games=pow(2,(($roundsplus1-1)-$i));
	    for($g=1;$g<=$games;$g++)
	    {
   
		   $line2=$g*2; $line1=$line2-1;
	       $matchnum++;
	       if($round==$i && ($line==$line1 || $line==$line2) && $division==$divisions[$d])
	          return $matchnum;
		  
	    }
	 }
    	 else if($i==4)	//ROUND 5: reverse order; consolation game counted first (lines 3-4), then final game (lines 1-2)
	{
		 	$matchnum++;
	       if($round==$i && ($line==6 || $line==5) && $division==$divisions[$d])
	       return $matchnum;
		   
            $matchnum++;
	       if($round==$i && ($line==8 || $line==7) && $division==$divisions[$d])
	       return $matchnum;
		   
		   $matchnum++;
	       if($round==$i && ($line==2 || $line==1) && $division==$divisions[$d])
	       return $matchnum;
		   
		   $matchnum++;
	       if($round==$i && ($line==4 || $line==3) && $division==$divisions[$d])
	       return $matchnum;
	}
    	 else if($i==($roundsplus1-1))	//ROUND 5: reverse order; consolation game counted first (lines 3-4), then final game (lines 1-2)
	 {
	    $matchnum++;
	    if($round==$i && ($line==6 || $line==5) && $division==$divisions[$d])
	       return $matchnum;
		   
   
		
		$matchnum++;
	    if($round==$i && ($line==3 || $line==4) && $division==$divisions[$d])
	       return $matchnum;

         $matchnum++;
         if($round==$i && ($line==7 || $line==8) && $division==$divisions[$d])
             return $matchnum;
		   

	 }
	 else	//ROUND $roundsplus1 for this purpose means the final game
	 {
            $matchnum++;
            if($round==$i && ($line==1 || $line==2) && $division==$divisions[$d])
               return $matchnum;
	 }
      }
   }
   return 0;
}
function GetRecord($sport,$division,$varsityjv,$player1,$player2,$database='')
{
   require '../variables.php';
   if($database=="") $database=$db_name;
   if(ereg("doubles",$division))      
      $sql="SELECT t1.id AS meetid,t1.meetname,t1.startdate,t1.enddate,t1.meetsite,t2.* FROM $database.".$sport."meets AS t1, $database.".$sport."meetresults AS t2 WHERE t1.id=t2.meetid AND ((((t2.player1='$player1' AND t2.player2='$player2') OR (t2.player1='$player2' AND t2.player2='$player1')) AND t2.varsityjv1='$varsityjv') OR (((t2.player3='$player1' AND t2.player4='$player2') OR (t2.player4='$player1' AND t2.player3='$player2')) AND t2.varsityjv2='$varsityjv')) AND t2.division='$division' ORDER BY t1.startdate";
   else      
      $sql="SELECT t1.id AS meetid,t1.meetname,t1.startdate,t1.enddate,t1.meetsite,t2.* FROM $database.".$sport."meets AS t1, $database.".$sport."meetresults AS t2 WHERE t1.id=t2.meetid AND ((t2.varsityjv1='$varsityjv' AND t2.player1='$player1') OR (t2.varsityjv2='$varsityjv' AND t2.player3='$player1')) AND t2.division='$division' ORDER BY t1.startdate";
   $wins=0; $losses=0;
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      if(($player1==$row[player1] || $player1==$row[player2]) && $row[winnerid]==$row[oppid1])
         $wins++;
      else if(($player1==$row[player3] || $player1==$row[player4]) && $row[winnerid]==$row[oppid2])
         $wins++;
      else
         $losses++;
   }
   return "$wins-$losses";
}
function GetResults($sport,$division,$varsityjv,$player1,$player2='0',$red='0',$database='')
{
   if(ereg("doubles",$division))
      $sql="SELECT t1.id AS meetid,t1.meetname,t1.startdate,t1.enddate,t1.meetsite,t2.* FROM $database.".$sport."meets AS t1, $database.".$sport."meetresults AS t2 WHERE t1.id=t2.meetid AND ((((t2.player1='$player1' AND t2.player2='$player2') OR (t2.player1='$player2' AND t2.player2='$player1')) AND t2.varsityjv1='$varsityjv') OR (((t2.player3='$player1' AND t2.player4='$player2') OR (t2.player4='$player1' AND t2.player3='$player2')) AND t2.varsityjv2='$varsityjv')) AND t2.division='$division' ORDER BY t1.startdate";
   else
      $sql="SELECT t1.id AS meetid,t1.meetname,t1.startdate,t1.enddate,t1.meetsite,t2.* FROM $database.".$sport."meets AS t1, $database.".$sport."meetresults AS t2 WHERE t1.id=t2.meetid AND ((t2.varsityjv1='$varsityjv' AND t2.player1='$player1') OR (t2.varsityjv2='$varsityjv' AND t2.player3='$player1')) AND t2.division='$division' ORDER BY t1.startdate"; 
   $result=mysql_query($sql);
   if(ereg("doubles",$division))      
   {         
      $temp=split("doubles",$division);         
      $divshow="#".$temp[1]." Doubles";      
   }      
   else      
   {         
      $temp=split("singles",$division);         
      $divshow="#".$temp[1]." Singles";      
   }
   $string="";
   //GET DUE DATE OF THE STATE FORM, GRAY-SHADE MEETS WITHIN 5 DAYS OF THAT DAY (CONFERENCE CHAMPS)
   $sql2="SELECT * FROM $database.form_duedates WHERE form='".$sport."state'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   $duedate=split("-",$row2[duedate]);
   $duedatesec=mktime(23,59,59,$duedate[1],$duedate[2],$duedate[0]);
   while($row=mysql_fetch_array($result))
   {
      if(($player1==$row[player1] || $player1==$row[player2]) && $row[winnerid]==$row[oppid1])
         $winloss="WIN"; 
      else if(($player1==$row[player3] || $player1==$row[player4]) && $row[winnerid]==$row[oppid2])
         $winloss="WIN";
      else
         $winloss="LOSS";
      if($player1==$row[player1] || $player1==$row[player2])
      {
	 $thisp1=$row[player1]; $thisp2=$row[player2]; $thisoppid=$row[oppid1]; $thisvjv=$row[varsityjv1];
	 $otherp1=$row[player3]; $otherp2=$row[player4]; $otheroppid=$row[oppid2]; $othervjv=$row[varsityjv2];
      }
      else
      {
         $thisp1=$row[player3]; $thisp2=$row[player4]; $thisoppid=$row[oppid2]; $thisvjv=$row[varsityjv2];
         $otherp1=$row[player1]; $otherp2=$row[player2]; $otheroppid=$row[oppid1]; $othervjv=$row[varsityjv1];
      }
      $start=split("-",$row[startdate]);
      $end=split("-",$row[enddate]);
      $string.="<tr align=left";
      if($winloss=="LOSS" && $red==1) $string.=" bgcolor='#dd3333'";
      else if(mktime(0,0,0,$start[1],$start[2],$start[0])>$duedatesec) $string.=" bgcolor='#e0e0e0'";
      $string.="><td align=left>$divshow</td><td>$row[meetname]&nbsp;";
      if($row[startdate]!=$row[enddate]) $string.="($start[1]/$start[2]-$end[1]/$end[2])";
      else $string.="($start[1]/$start[2])";
      $string.="</td>";
      if($otheroppid!="1000000000")
         $opponent=GetSchoolName($otheroppid,$sport,GetFallYear($sport));
      else
         $opponent=$row[oosschool];
      $string.="<td>$thisvjv</td><td align=left>$opponent</td><td>";
      if($otheroppid!="1000000000")
      {
         $sql2="SELECT first,last,semesters FROM $database.eligibility WHERE id='$otherp1'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         if(ereg("\(",$row2[first]))
         {
            $first_nick=split("\(",$row2[first]);
            $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
         }
         else $first=$row2[first];
         $name="$first $row2[last] (".GetYear($row2[semesters]).")";
         $string.=$name;
         if(ereg("doubles",$division))
         {
            $sql2="SELECT first,last,semesters FROM $database.eligibility WHERE id='$otherp2'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            if(ereg("\(",$row2[first]))
            {
               $first_nick=split("\(",$row2[first]);
               $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
            }
            else $first=$row2[first];
            $name="$first $row2[last] (".GetYear($row2[semesters]).")";
            $string.=", $name";
         }
      }
      else
      {
         $string.=$row[oosplayer1];
         if(ereg("Doubles",$division))
            $string.="<br>".$row[oosplayer2];
      }
      $string.="</td><td>$othervjv</td>";
      $string.="<td>$winloss</td><td align=left>".$row[score]."</td></tr>";
      $ix++;
   }
   return $string;
}
function GetAllResults($sport,$class,$division,$sid,$player1,$player2='0',$red='0',$database='')
{
   //get all results for player(s), starting with basedivision first
   //VARSITY FIRST:         
   require_once('../functions.php');
   require '../variables.php';
   if($database=='') $database=$db_name;
   $string="<table frame=box rules=cols style=\"border:#808080 1px solid;\" cellspacing=0 cellpadding=3>";
   //first put results for THIS SPECIFIC DIVISION:
   if(ereg("singles",$division))
   {         
      $temp=split("singles",$division);         
      $curdivshow="#".$temp[1]." Singles";
   }
   else
   {
      $temp=split("doubles",$division);
      $curdivshow="#".$temp[1]." Doubles";
   }
   $recordV=GetRecord($sport,$division,'Varsity',$player1,$player2,$database);
   $header="<font style=\"font-size:9pt\">Class $class $curdivshow (Varsity), ";
   $sql2="SELECT * FROM $database.eligibility WHERE id='$player1'";
   $result2=mysql_query($sql2);
   $row2=mysql_fetch_array($result2);
   if(ereg("\(",$row2[first]))
   {
      $first_nick=split("\(",$row2[first]);
      $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
   }
   else $first=$row2[first];
   $header.="$row2[school]: $first $row2[last] (".GetYear($row2[semesters]).")";
   if(ereg("doubles",$division))
   {
      $sql2="SELECT * FROM $database.eligibility WHERE id='$player2'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      if(ereg("\(",$row2[first]))
      {
         $first_nick=split("\(",$row2[first]);
         $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
      }
      else $first=$row2[first];
      $header.=" and $first $row2[last] (".GetYear($row2[semesters]).")";
   }
   if($recordV!="0-0")
      $header.=", Record: $recordV";
   $header.="</font>";
   $string.="<tr align=center bgcolor='#E0E0E0'><td colspan=8><b>$header</b></td></tr>";
   $string.="<tr align=center><td><b>Division</b></td><td><b>Meet</b></td><td><b>Varsity/JV</b></td><td colspan=2><b>Opponent(s)</b></td><td><b>Varsity/JV</b></td><td><b>W/L</b></td><td><b>Score</b></td></tr>";
   $string.=GetResults($sport,$division,'Varsity',$player1,$player2,$red,$database);
   
   $levels=array("Varsity","JV");
   for($i=0;$i<count($levels);$i++)
   {
      if(ereg("singles",$division))            
      {
         $sql="SELECT DISTINCT division FROM $database.".$sport."meetresults WHERE division LIKE '%singles%' ";
         if($levels[$i]=="Varsity") $sql.="AND division!='$division' ";
         $sql.="AND ((varsityjv1='$levels[$i]' AND player1='$player1') OR (varsityjv2='$levels[$i]' AND player3='$player1')) ORDER BY division"; 
      }
      else            
      {
         $sql="SELECT DISTINCT division FROM $database.".$sport."meetresults WHERE division LIKE '%doubles%' ";
   	 if($levels[$i]=="Varsity") $sql.="AND division!='$division' ";
  	 $sql.="AND ((varsityjv1='$levels[$i]' AND ((player1='$player1' AND player2='$player2') OR (player1='$player2' AND player2='$player1'))) OR (varsityjv2='$levels[$i]' AND ((player3='$player1' AND player4='$player2') OR (player3='$player2' AND player4='$player1')))) ORDER BY division";  
      }
      $result=mysql_query($sql);             
      while($row=mysql_fetch_array($result))         //for each varsity division for current player(s)          
      {            
         if(ereg("singles",$row[division]))                 
         {               
            $temp=split("singles",$row[division]);               
            $curdivshow="#".$temp[1]." Singles";                 
         }                 
         else                 
         {               
	    $temp=split("doubles",$row[division]);               
     	    $curdivshow="#".$temp[1]." Doubles";                 
         }            
         $recordV=GetRecord($sport,$row[division],$levels[$i],$player1,$player2,$database); 
         $header="<font style=\"font-size:9pt\">Class $class $curdivshow ($levels[$i]), ";            
         $sql2="SELECT * FROM $database.eligibility WHERE id='$player1'";            
         $result2=mysql_query($sql2);            
         $row2=mysql_fetch_array($result2);            
         if(ereg("\(",$row2[first]))
         {
            $first_nick=split("\(",$row2[first]);
            $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
         }
         else $first=$row2[first];
         $header.="$row2[school]: $first $row2[last] (".GetYear($row2[semesters]).")";            
         if(ereg("doubles",$division)) 
         {               
            $sql2="SELECT * FROM $database.eligibility WHERE id='$player2'";               
            $result2=mysql_query($sql2);               
            $row2=mysql_fetch_array($result2);               
            if(ereg("\(",$row2[first]))
            {
               $first_nick=split("\(",$row2[first]);
               $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
            }
            else $first=$row2[first];
            $header.=" and $first $row2[last] (".GetYear($row2[semesters]).")";            
         }            
         if($recordV!="0-0")
            $header.=", Record: $recordV";
         $header.="</font>";            
         $string.="<tr align=center bgcolor='#E0E0E0'><td colspan=8><b>$header</b></td></tr>";            
         $string.="<tr align=center><td><b>Division</b></td><td><b>Meet</b></td><td><b>Varsity/JV</b></td><td colspan=2><b>Opponent(s)</b></td><td><b>Varsity/JV</b></td><td><b>W/L</b></td><td><b>Score</b></td></tr>";            
         $string.=GetResults($sport,$row[division],$levels[$i],$player1,$player2,$red,$database);
      }
      //Show DOUBLES RESULTS (with different partners)
      //PLAYER 1: 
      $sql="SELECT * FROM $database.".$sport."meetresults WHERE division LIKE '%doubles%' AND ((varsityjv1='$levels[$i]' AND ((player1='$player1' AND player2!='$player2') OR (player2='$player1' AND player1!='$player2'))) OR (varsityjv2='$levels[$i]' AND ((player3='$player1' AND player4!='$player2') OR (player4='$player1' AND player3!='$player2')))) ORDER BY division";
      $result=mysql_query($sql);
      $usedpairs=array(); $u=0; $curdiv="doubles1";
      while($row=mysql_fetch_array($result))         //for each varsity division for current player(s) 
      {
         if($curdiv!=$row[division])
         {
	    $usedpairs=array(); $u=0; $curdiv=$row[division];
         }
         $temp=split("doubles",$row[division]);
         $curdivshow="#".$temp[1]." Doubles";
         if($row[oppid1]==$sid)
         {
            $curplayer1=$row[player1]; $curplayer2=$row[player2];
         }
         else
         {
            $curplayer1=$row[player3]; $curplayer2=$row[player4];
         }
         $used=0;      //check if this pair of players has already been shown
         for($x=0;$x<count($usedpairs);$x++)
         {
            if($usedpairs[$x]=="$curplayer2,$curplayer1" || $usedpairs[$x]=="$curplayer1,$curplayer2") $used=1;
         }
         if($used==0)  //go ahead and show this pair but add to array of used pairs
         {
            $usedpairs[$u]="$curplayer1,$curplayer2"; $u++;
            $recordV=GetRecord($sport,$row[division],$levels[$i],$curplayer1,$curplayer2,$database);
            $header="Class $class $curdivshow ($levels[$i]), ";
            $sql2="SELECT * FROM $database.eligibility WHERE id='$curplayer1'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            $header.="$row2[school]: ";
            if($player1==$curplayer1) $header.="<font style=\"font-size:9pt;\">";
      	    if(ereg("\(",$row2[first]))
            {
               $first_nick=split("\(",$row2[first]);
               $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
            }
            else $first=$row2[first];
            $header.="$first $row2[last] (".GetYear($row2[semesters]).")";
            if($player1==$curplayer1) $header.="</font>";
            $sql2="SELECT * FROM $database.eligibility WHERE id='$curplayer2'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            $header.=" and ";
            if($player1==$curplayer2) $header.="<font style=\"font-size:9pt;\">";
            if(ereg("\(",$row2[first]))
            {
               $first_nick=split("\(",$row2[first]);
               $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
            }
            else $first=$row2[first];
            $header.="$first $row2[last] (".GetYear($row2[semesters]).")";
            if($player1==$curplayer2) $header.="</font>";
            if($recordV!="0-0")
               $header.=", Record: $recordV";
            $string.="<tr align=center bgcolor='#E0E0E0'><td colspan=8><b>$header</b></td></tr>";
            $string.="<tr align=center><td><b>Division</b></td><td><b>Meet</b></td><td><b>Varsity/JV</b></td><td colspan=2><b>Opponent(s)</b></td><td><b>Varsity/JV</b></td><td><b>W/L</b></td><td><b>Score</b></td></tr>";
            $string.=GetResults($sport,$row[division],$levels[$i],$curplayer1,$curplayer2,$red,$database);
         }//end if pair not shown yet
      }//end for each division
      //PLAYER 2: OTHER DOUBLES MATCHES
      if(ereg("doubles",$division))	//OTHERWISE, player2=0
      {
         $sql="SELECT * FROM $database.".$sport."meetresults WHERE division LIKE '%Doubles%' AND ((varsityjv1='$levels[$i]' AND ((player1='$player2' AND player2!='$player1') OR (player2='$player2' AND player1!='$player1'))) OR (varsityjv2='$levels[$i]' AND ((player3='$player2' AND player4!='$player1') OR (player4='$player2' AND player3!='$player1')))) ORDER BY division";
         $result=mysql_query($sql);
         $usedpairs=array(); $u=0; $curdiv="doubles1";
         while($row=mysql_fetch_array($result))         //for each varsity division for current player(s) 
         {
            if($curdiv!=$row[division])
            {
	       $usedpairs=array(); $u=0; $curdiv=$row[division];
            }
            $temp=split("doubles",$row[division]);                  
            $curdivshow="#".$temp[1]." Doubles";
            if($row[oppid1]==$sid)
            {
               $curplayer1=$row[player1]; $curplayer2=$row[player2];
            }
            else
            {
               $curplayer1=$row[player3]; $curplayer2=$row[player4];
            }
            $used=0;      //check if this pair of players has already been shown
            for($x=0;$x<count($usedpairs);$x++)
            {
               if($usedpairs[$x]=="$curplayer2,$curplayer1" || $usedpairs[$x]=="$curplayer1,$curplayer2") $used=1;
            }
            if($used==0)  //go ahead and show this pair but add to array of used pairs
            {
               $usedpairs[$u]="$curplayer1,$curplayer2"; $u++;
               $recordV=GetRecord($sport,$row[division],$levels[$i],$curplayer1,$curplayer2,$database);
               $header="Class $class $curdivshow ($levels[$i]), ";
               $sql2="SELECT * FROM $database.eligibility WHERE id='$curplayer1'";
               $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
               $header.="$row2[school]: ";
               if($player2==$curplayer1) $header.="<font style=\"font-size:9pt;\">";
               if(ereg("\(",$row2[first]))
      	       {
                  $first_nick=split("\(",$row2[first]);
                  $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
               }
               else $first=$row2[first];
               $header.="$first $row2[last] (".GetYear($row2[semesters]).")";
               if($player2==$curplayer1) $header.="</font>";
               $sql2="SELECT * FROM $database.eligibility WHERE id='$curplayer2'";
               $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
               $header.=" and ";
               if($player2==$curplayer2) $header.="<font style=\"font-size:9pt;\">";
      	       if(ereg("\(",$row2[first]))
               {
                  $first_nick=split("\(",$row2[first]);
                  $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
               }
               else $first=$row2[first];
               $header.="$first $row2[last] (".GetYear($row2[semesters]).")";
               if($player2==$curplayer2) $header.="</font>";
	       if($recordV!="0-0")
                  $header.=", Record: $recordV";
               $string.="<tr align=center bgcolor='#E0E0E0'><td colspan=8><b>$header</b></td></tr>";
               $string.="<tr align=center><td><b>Division</b></td><td><b>Meet</b></td><td><b>Varsity/JV</b></td><td colspan=2><b>Opponent(s)</b></td><td><b>Varsity/JV</b></td><td><b>W/L</b></td><td><b>Score</b></td></tr>";
               $string.=GetResults($sport,$row[division],$levels[$i],$curplayer1,$curplayer2,0,$database);
            }//end if pair not shown yet
         }
      }//end if DOUBLES

      //IF basedivision=="DOUBLES", get singles results for each player:
      if(ereg("doubles",$division))
      {
         //PLAYER 1 SINGLES RESULTS:
         $sql="SELECT DISTINCT division FROM $database.".$sport."meetresults WHERE division LIKE '%singles%' AND ((varsityjv1='$levels[$i]' AND player1='$player1') OR (varsityjv2='$levels[$i]' AND player3='$player1')) ORDER BY division";
         $result=mysql_query($sql);
         while($row=mysql_fetch_array($result))
         {
            $temp=split("singles",$row[division]);
            $curdivshow="#".$temp[1]." Singles";
            $record=GetRecord($sport,$row[division],$levels[$i],$player1,0,$database);
            $header="Class $class $curdivshow ($levels[$i]), ";
            $sql2="SELECT * FROM $database.eligibility WHERE id='$player1'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            $header.="$row2[school]: ";
            $header.="<font style=\"font-size:9pt;\">";
            if(ereg("\(",$row2[first]))
            {
               $first_nick=split("\(",$row2[first]);
               $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
            }
            else $first=$row2[first];
            $header.="$first $row2[last] (".GetYear($row2[semesters]).")";
            $header.="</font>";
	    if($record!="0-0")
               $header.=", Record: $record";
            $string.="<tr align=center bgcolor='#E0E0E0'><td colspan=8><b>$header</b></td></tr>";
            $string.="<tr align=center><td><b>Division</b></td><td><b>Meet</b></td><td><b>Varsity/JV</b></td><td colspan=2><b>Opponent(s)</b></td><td><b>Varsity/JV</b></td><td><b>W/L</b></td><td><b>Score</b></td></tr>";
	    $string.=GetResults($sport,$row[division],$levels[$i],$player1,0,0,$database);
         }
         //PLAYER 2 SINGLES RESULTS:
         $sql="SELECT DISTINCT division FROM $database.".$sport."meetresults WHERE division LIKE '%singles%' AND ((varsityjv1='$levels[$i]' AND player1='$player2') OR (varsityjv2='$levels[$i]' AND player3='$player2')) ORDER BY division";
         $result=mysql_query($sql);
         while($row=mysql_fetch_array($result))
         {
            $temp=split("singles",$row[division]);
            $curdivshow="#".$temp[1]." Singles";
            $record=GetRecord($sport,$row[division],$levels[$i],$player2,0,$database);
            $header="Class $class $curdivshow ($levels[$i]), ";
            $sql2="SELECT * FROM $database.eligibility WHERE id='$player2'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            $header.="$row2[school]: ";
            $header.="<font style=\"font-size:9pt;\">";
            if(ereg("\(",$row2[first]))
            {
               $first_nick=split("\(",$row2[first]);
               $first=substr($first_nick[1],0,strlen($first_nick[1])-1);
            }
            else $first=$row2[first];
            $header.="$first $row2[last] (".GetYear($row2[semesters]).")";
            $header.="</font>";
	    if($record!="0-0")
               $header.=", Record: $record";
            $string.="<tr align=center bgcolor='#E0E0E0'><td colspan=8><b>$header</b></td></tr>";
            $string.="<tr align=center><td><b>Division</b></td><td><b>Meet</b></td><td><b>Varsity/JV</b></td><td colspan=2><b>Opponent(s)</b></td><td><b>Varsity/JV</b></td><td><b>W/L</b></td><td><b>Score</b></td></tr>";
            $string.=GetResults($sport,$row[division],$levels[$i],$player2,0,0,$database);
         }
      }
   }//end for ech LEVEL (V/JV)
   $string.="</table>";
   return $string;
}
?>
