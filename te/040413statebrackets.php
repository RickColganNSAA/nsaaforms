<?php

require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require 'tefunctions.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}
if(!$sport) $sport="te_b";
$sportname=GetActivityName($sport);
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

if($pdf && $blankpdf)
{
//CREATE BLANK PDF OF BRACKET
require_once('../../tcpdf_php4/config/lang/eng.php');
require_once('../../tcpdf_php4/tcpdf.php');
// create new PDF document^M
$orientation="P";
$pdf = new TCPDF($orientation, PDF_UNIT, PDF_PAGE_FORMAT, true);
// set document information^M
$pdf->SetCreator("NSAA");
$pdf->SetAuthor("NSAA");
$pdf->SetTitle("$sportname State Bracket: Class $class $showdiv");
$pdf->SetSubject("$sportname State Bracket: Class $class $showdiv");
$pdf->SetKeywords("tennis,state");
$pdf->SetMargins(10,10,10,10);
$pdf->SetAutoPageBreak(TRUE, 1);
//set some language-dependent strings^M
$pdf->setLanguageArray($l);
//initialize document^M
$pdf->AliasNbPages();
// add a page
$pdf->AddPage();
$pdf->SetFont("times","",7);
// output the HTML content^M
$pdf->writeHTML("<b>".date("Y")." $sportname State Bracket: Class $class $showdiv</b>", true, 0, true, 0);
$filename=ereg_replace("[^a-zA-Z]","",$sport)."class".$class.$division;
$pdf->Output("previews/$filename.pdf", "F");
header("Location:publish.php?sport=$sport&session=$session&filename=".urlencode("previews/".$filename).".pdf");
exit();
}

if($saveround1)	//save opponents for round 1
{
   foreach($round as $index => $rnd) 
   {
      if($rnd<=1)
      {
         $curplayers=$players[$rnd][$line[$index]];
	 if(ereg("doubles",$division))
	 {
	    $temp=split(";",$curplayers);
	    $player1=$temp[0]; $player2=$temp[1];
	 }
	 else
	 {
	    $player1=$curplayers; $player2=0;
	 }
         $sql="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND round='$rnd' AND line='$line[$index]'";
         $result=mysql_query($sql);
	 if(mysql_num_rows($result)==0)	//INSERT
	    $sql2="INSERT INTO ".$sport."brackets (class,division,round,line,player1,player2) VALUES ('$class','$division','$rnd','$line[$index]','$player1','$player2')";
	 else
	    $sql2="UPDATE ".$sport."brackets SET player1='$player1',player2='$player2' WHERE class='$class' AND division='$division' AND round='$rnd' AND line='$line[$index]'";
	 $result2=mysql_query($sql2);
//echo $sql2."<br>";
      }
      if($rnd)
      {
         $abbrev2=addslashes($abbreviation[$rnd][$line[$index]]);
         $sql="UPDATE ".$sport."brackets SET abbreviation='$abbrev2' WHERE class='$class' AND division='$division' AND round='$rnd' AND line='$line[$index]'";
//if($rnd==2 && $line[$index]==1) echo "<div style=\"position:absolute;z-index:120;\">$sql</div><br>";
         $result=mysql_query($sql);
      }
   }
}
if($pdf!=1)	//BEGIN SHOW BRACKET ON SCREEN (NOT CREATE PDF)
{
echo $init_html_ajax;
?>
<script type="text/javascript" src="/javascript/TEBrackets.js"></script>
</head>
<body onload="TEBrackets.initialize('<?php echo $sport; ?>','<?php echo $class; ?>','<?php echo $division; ?>');">
<?php
echo $header;
}

/***** GET ENTRIES FOR THIS CLASS/DIVISION *****/
$entries=GetNonSeededEntries($sport,$class,$division);
/*
if($class=="Z") //ALL BOYS CLASSES & GIRLS - NO DISTRICTS (AS OF 10/7/11)
   $sql="SELECT t1.*,t2.school FROM ".$sport."distresults AS t1,eligibility AS t2,$db_name2.tebdistricts AS t3 WHERE t3.id=t1.distid AND t1.player1=t2.id AND t1.player1>0 AND t3.class='$class' AND t1.division='$division' ORDER BY t2.school";
else
   $sql="SELECT t1.* FROM ".$sport."state AS t1,".$sport."school AS t2 WHERE t1.player1>0 AND t1.sid=t2.sid AND t2.class='$class' AND t1.division='$division' ORDER BY t2.school";
$result=mysql_query($sql);
$entryct=mysql_num_rows($result);
$entries=array(); $ix=0; $otherix=0; //not sure what I was using otherix for, but it's not being used now
$delsql="";
while($row=mysql_fetch_array($result))
{
   //IS(ARE) PLAYER(S) SEEDED?
   $sql2="SELECT * FROM ".$sport."seeds WHERE class='$class' AND division='$division' AND player1='$row[player1]' AND player2='$row[player2]'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)	//NOT SEEDED: ADD TO LIST THEY CAN SELECT FROM FOR NON-SEEDED SLOTS
   {
      $entries[name][$ix]=GetStudentInfo($row[player1]);
      $entries[player1][$ix]=$row[player1];
      if($class=="Z") //ALL BOYS CLASSES & GIRLS - NO DISTRICTS (AS OF 10/7/11)
         $entries[school][$ix]=GetSchoolName(GetSID2($row[school],$sport),$sport,date("Y"));
      else
         $entries[school][$ix]=GetSchoolName($row[sid],$sport,date("Y"));
      if(ereg("doubles",$division))
      {
         $entries[name][$ix].="/".GetStudentInfo($row[player2]);
         $entries[player2][$ix]=$row[player2];
      }
      $ix++;
   }//end if not seeded
   else 	//SEEDED
   {
      $otherix++;
   }
}
*/
/***** CHECK THAT SEEDS ARE UPDATED (IN CORRECT SLOTS ON BRACKET) *****/

$sql="SELECT * FROM ".$sport."seeds WHERE class='$class' AND division='$division' ORDER BY seed";
$result=mysql_query($sql);
$seedct=mysql_num_rows($result);
while($row=mysql_fetch_array($result))
{
   $seed=$row[seed];
   $index=$seed-1;
   if($class=="Z")	//16-person Bracket; ALL BOYS CLASSES & GIRLS CLASSES - NO DISTRICTS (AS OF 10/7/11)
      $line=$seedpos[$entryct][16][$seedct][$index]; //LINE THIS SEED SITS ON
   else			//32-person Bracket
      $line=$seedpos[$entryct][$seedct][$index]; //LINE THIS SEED SITS ON

   //ROUND 1:
   $curround=1;
   
   $sql2="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND seed='$seed'";
   $result2=mysql_query($sql2);
   if(mysql_num_rows($result2)==0)	//INSERT (THIS SEED WAS NOT ENTERED ON BRACKET YET)
   {
      $sql3="INSERT INTO ".$sport."brackets (class,division,round,line,player1,player2,seed) VALUES ('$class','$division','$curround','$line','$row[player1]','$row[player2]','$seed')";
   }
   else	//UPDATE (MAKE SURE SEED IS ON CORRECT LINE WITH CORRECT PLAYER ON BRACKET)
   {
      $sql3="UPDATE ".$sport."brackets SET round='$curround',line='$line',player1='$row[player1]',player2='$row[player2]' WHERE class='$class' AND division='$division' AND seed='$seed'";
   }
   $result3=mysql_query($sql3);
	
   //MAKE SURE THIS PLAYER IS NOT ENTERED ELSEWHERE ON THIS BRACKET
      /*
   $sql3="DELETE FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND round='$curround' AND player1='$row[player1]' AND player2='$row[player2]' AND seed!='$seed'";
   $result3=mysql_query($sql3);
   //ALSO MAKE SURE THIS LINE IS RESERVED FOR THIS SEEDED PLAYER ONLY
   $sql3="DELETE FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND round='$curround' AND line='$line' AND seed!='$seed'";
   $result3=mysql_query($sql3);
	*/

   //DOES THIS SEED GET A BYE IN FIRST ROUND?
   if($line%2==0) $otherline=$line-1;
   else $otherline=$line+1;
   //$otherline is where opponent of $line (this seed) sits
   $sql3="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND round='1' AND line='$otherline'";
   $result3=mysql_query($sql3);
   if(mysql_num_rows($result3)==0 && $row[bye]=='x')	//INSERT a BYE on $otherline if this seed is supposed to have a bye
   {
      $sql4="INSERT INTO ".$sport."brackets (class,division,round,line,bye) VALUES ('$class','$division','1','$otherline','x')";
      $result4=mysql_query($sql4);
   }
   else if(mysql_num_rows($result3)>0)	//UPDATE whether or not $otherline is a bye
   {
      $sql4="UPDATE ".$sport."brackets SET bye='$row[bye]' WHERE class='$class' AND division='$division' AND round='1' AND line='$otherline'";
      $result4=mysql_query($sql4);
   }  

   //IF THERE WERE SEEDS ON THIS BRACKET THAT NO LONGER EXIST, REMOVE THEM (MAYBE THEY WENT FROM 12 TO 8 SEEDS, FOR EX.)
   $sql2="UPDATE ".$sport."brackets SET seed=0 WHERE seed>'$seedct'";
   $result2=mysql_query($sql2);
}

/***** CHECK THAT NON-SEEDS ARE RANDOMIZED *****/
/*
for($i=0;$i<count($entries[name]);$i++)
{
   //YOU ARE NOT SEEDED, SO TAKE OFF THE BRACKET IF YOU ARE
   $sql="DELETE FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND round='1' AND player1='".$entries[player1][$i]."'";
   if(ereg("doubles",$division))
      $sql.=" AND player2='".$entries[player2][$i]."'";
   $sql.=" AND seed>0";
   $result=mysql_query($sql);
}
*/
for($i=0;$i<count($entries[name]);$i++)
{
   //echo "$i: ";
   $sql="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND ";
   if($entryct==33) $sql.="(round='1' OR round='0')";
   else $sql.="round='1'";
   $sql.=" AND player1='".$entries[player1][$i]."'";
   if(ereg("doubles",$division))
      $sql.=" AND player2='".$entries[player2][$i]."'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)	//IF PLAYER(S) NOT ON BRACKET YET, GIVE THEM A RANDOM SPOT IN ROUND 1
   {
      //GET ARRAY OF AVAILABLE LINE SLOTS   
      $lines=range(1,$entryct);		//FOR entryct=33, Line 14 = Line 1 for Round 0, Line 33 = Line 2 for Round 0
      $availlines=array(); $a=0;	//ARRAY OF AVAILABLE LINES
      foreach($lines as $key => $value)
      {
         $curline=$value;
	 $useround=1; $useline=$curline;
	 if($entryct==33)	//CONVERT line 14 to Round 0, Line 1 and line 33 to Round 0, Line 2
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
	 if(mysql_num_rows($result2)==0)	//NOT FILLED YET, ADD TO LIST
	 {
		//echo "Round $useround Line $useline ($curline) IS AVAILABLE<br>";
	    $availlines[$a]=$curline; $a++;
         }
      } 
   
      //IF NO AVAILABLE LINES, SHOW ERROR
      if(count($availlines)==0)
      {
         echo "$sql2<br>Can't find an open slot for ".GetStudentInfo($entries[player1][$i])." #".$entries[player1][$i]."<br>";
         exit();
      }

      //NOW GET RANDOM VALUE FROM ARRAY OF AVAILABLE LINES
      $index=rand(0,count($availlines)-1);
      $randline=$availlines[$index];

      //WHEN WE GET HERE, $randline IS THE LINE WE PUT THIS NON-SEEDED PLAYER(S) ON:
      if(ereg("doubles",$division)) $player2=$entries[player2][$i];
      else $player2=0;
      $useround=1;	//DEFAULT: ROUND 1
      if($entryct==33)	//CONVERT line 14 to Round 0, Line 1 and line 33 to Round 0, Line 2
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
      $sql2="INSERT INTO ".$sport."brackets (class,division,round,line,player1,player2) VALUES ('$class','$division','$useround','$randline','".$entries[player1][$i]."','$player2')";
      $result2=mysql_query($sql2);
	//echo $sql2."<br>";
   }
}

//echo "CHECK BRACKET NOW";
//exit();

/***** DISPLAY BRACKET (HTML FORM OR PDF) *****/

if($pdf!=1)
{
   echo "<form method=\"post\" action=\"statebrackets.php\">";
   echo "<input type=hidden name=\"session\" value=\"$session\">";
   echo "<input type=hidden name=\"sport\" value=\"$sport\">";
   echo "<input type=hidden name=\"classdiv\" value=\"$classdiv\">";
   echo "<br><table width=100% cellspacing=0 cellpadding=2><caption><b>$sportname State Bracket: Class $class $showdiv</b>&nbsp;&nbsp;";
   echo "<a href=\"stateseeds.php?sport=$sport&session=$session&classdiv=$classdiv\" class=small>Return to $sportname Seeding</a>";
}

//BRACKET DISPLAY VARIABLES (NON-PDF)
if($entryct==16) $rounds=4; //ALL BOYS CLASSES & GIRLS - NO DISTRICTS (AS OF 10/7/11)
else $rounds=5; 
$height=60;
if(ereg("doubles",$division))
{
   if($entryct==33)	//BUNNY TAIL ROUND (ROUND 0)
   {
      $width0=370;
      $width1=350; $width2=250; $size=35;
   }
   else
   {
      $width1=350; $width2=250; $size=35; $width0=0;
   }
}
else
{
   if($entryct==33)	//BUNNY TAIL ROUND 0
   {
      $width0=320;
      $width1=300; $width2=200; $size=25;
   }
   else
   {
      $width1=250; $width0=0;
      $width2=200;
      $size=25;
   }
}
$top=370;
$left=5;

//BRACKET DISPLAY VARIABLES (PDF)
$heightpdf=45;
if(ereg("doubles",$division))
{
   if($entryct==33)
   {
      $width0pdf=250;
      $width1pdf=250; $width2pdf=150;
   }
   else
   {
      $width1pdf=350; $width2pdf=180; $width0pdf=0;
   }
}
else
{
   if($entryct==33)
   {
      $width0pdf=200; $width1pdf=200; $width2pdf=150;
   }
   else
   {
      $width0pdf=0; $width1pdf=250; $width2pdf=150;
   }
}
$toppdf=50;

//BEGIN BRACKET DISPLAY:
$pdfhtml="<table cellspacing=0 cellpadding=0>"; $ix=0;
$pdfcells=array();
if($entryct==16) $lines=35; //ALL BOYS CLASSES & GIRLS CLASS A - NO DISTRICTS (AS OF 10/7/11)
else $lines=64;	//FOR entryct==33, 14 and 33 will be the Round 0 game opponents
for($i=0;$i<$lines;$i++)
{
   for($j=0;$j<=$rounds;$j++)
   {
      $pdfcells[$i][$j]="";
   }
}
if($entryct==33) { $rstart=0; $earlyroundwidth=$width0+$width1; $earlyroundwidthpdf=$width0pdf+$width1pdf; }
else { $rstart=1; $earlyroundwidth=$width1; $earlyroundwidthpdf=$width1pdf; }
for($r=$rstart;$r<=$rounds;$r++)
{
   if($r==0) { $width=$width0; $widthpdf=$width0pdf; }
   else if($r==1) { $width=$width1; $widthpdf=$width1pdf; }	//ROUND 1 IS WIDER TO ACCOMODATE <select> BOXES
   else { $width=$width2; $widthpdf=$width2pdf; }

   if($r==0) $games=1;
   else $games=pow(2,($rounds-($r-1)))/2;	//# OF GAMES IN EACH ROUND = 2 to the power of (TOTAL ROUNDS - (CURRENT RND - 1)), DIVIDED BY 2

   if($r==$rstart) { $curleft=$left; $curleftpdf=$left; }	//START ALL THE WAY TO THE LEFT FOR ROUND 1, ADD PADDING FOR OTHER ROUNDS
   else if($r==1 && $rstart==0)	//(IF ROUND=1 AND WE STARTED WITH ROUND=0)
   {
      $curleft=$width0+$left; $curleftpdf=$width0pdf+$left;
   }
   else { $curleft=$earlyroundwidth+(($r-2)*$width2)+$left; $curleftpdf=$earlyroundwidthpdf+(($r-2)*$width2pdf)+$left; }

   $curheight=(pow(2,($r-1)))*$height;	//CALCULATES HOW TALL THIS ROUNDS's GAMES ARE (SPACE BETWEEN OPPONENTS IN EACH GAME)
   $curheightpdf=(pow(2,($r-1)))*$heightpdf;

   $curtop=$top+(($height/2)*(pow(2,($r-1))-1));	//$top IS WHERE TO START FIRST GAME OF THIS ROUND
   $curtoppdf=$toppdf+(($heightpdf/2)*(pow(2,($r-1))-1));

   $zindex=$rounds-$r;	//FIRST ROUND ON TOP OF SUBSEQUENT ROUNDS, IN CASE OF OVERLAP
   for($g=0;$g<$games;$g++)
   {	
      //THIS IS WHERE IT GETS KIND OF UGLY...BUT HEY, IT WORKS!
      $pdf1="";
      if($g>0) { $curtop+=($curheight*2); $curtoppdf+=($curheightpdf*2); } //FOR $g=0, $curtop(pdf) is already the right value
      else if($r==0)	//ROUND 0 - MOVE TO ???
      {
	 $curtop+=$curheight*26; $curtoppdf+=$curheightpdf*26;	//GUESSED TO GET 26 on 3/28/13
      }

      $opp1top=$curtop-30; $opp2top=$curtop+$curheight+5;		//CALCULATE POSITIONING OF THIS GAME ON FORM & PDF
      if($r==0) $opp1top-=15;		//BECAUSE WINNER RADIO BOX IS ON SECOND LINE TO SAVE HORIZONTAL SPACE
      $opp1line=(($g+1)*2)-1; $opp2line=($g+1)*2; 
      $oppleft=$curleft+5;
      $opp1toppdf=$curtoppdf-20; $opp2toppdf=$curtoppdf+$curheightpdf+5;
      $oppleftpdf=$curleftpdf+5;

      if($pdf!=1) echo "<div class=plaineight style=\"z-index:100;top:$opp1top;left:$oppleft;position:absolute;\">";

      //GET THIS GAME'S OPPONENTS
      $sql="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND round='$r' AND line='$opp1line' AND seed>0";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)>0 && $r<=1)	//SEEDED PLAYER/PAIR
      {
         $row=mysql_fetch_array($result);
         //GET PLAYER 1's SCHOOL
         $sql2="SELECT school FROM eligibility WHERE id='$row[player1]'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
	 $cursch=$row2[school];
         //SHOW SEED
	 if($pdf!=1) echo "#".$row[seed]."&nbsp;";
	 $pdf1.="#".$row[seed]."&nbsp;";
         //IF PLAYER NOT FOUND, SHOW "First Last, School"; ELSE SHOW STUDENT AME & GRADE
	 if(mysql_num_rows($result2)==0) 
	 { 
	    if($pdf!=1) echo "First Last"; 
	    $pdf1.="First Last"; 
	 }
	 else 
	 { 
	    if($pdf!=1) 
	       echo GetStudentInfo($row[player1]); 
	    $pdf1.=GetStudentInfo($row[player1]); 
	 }
         //IF DOUBLES, DO SAME FOR PLAYER 2
         if(ereg("doubles",$division))
	 {
            $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$row[player2]'";
            $result2=mysql_query($sql2);
	    if(mysql_num_rows($result2)==0) { if($pdf!=1) echo "/First Last"; $pdf1.="/First Last"; }
            else { if($pdf!=1) echo "/".GetStudentInfo($row[player2]); $pdf1.="/".GetStudentInfo($row[player2]); }
	 }
         //SHOW SCHOOL & RECORD OF PLAYER/PAIR
	 if(mysql_num_rows($result2)==0)
	 {
	    if($pdf!=1) echo ", School W-L"; 
	    $pdf1.=", School W-L";
	 }
	 else
	 {
	    $schoolname=GetSchoolName(GetSID2($cursch,$sport),$sport,date("Y"));
	    $record=GetRecord($sport,$division,'Varsity',$row[player1],$row[player2]); 
            if($record=="0-0") $record="";
	    if($pdf!=1) echo ", $schoolname $record"; $pdf1.=", $schoolname $record";
	 }
      }	
      else if($r<=1) 	//<SELECT> BYE OR NON-SEEDED PLAYERS
      {
	 if($pdf!=1) 
         {
	    echo "<input type=hidden name=\"round[$ix]\" value=\"$r\">";
	    echo "<input type=hidden name=\"line[$ix]\" value=\"$opp1line\">";
         }
         //GET CURRENT PLAYER/PAIR SELECTED FOR THIS SLOT --> $thisid
	 $sql="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND round='$r' AND line='$opp1line'";
	 $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
	 $thisid=$row[player1];
	 if(ereg("doubles",$division)) $thisid.=";".$row[player2];
	 //SHOW SELECT BOX (non-PDF) OR SHOW SELECTED PLAYER/PAID (PDF)
         if($pdf!=1) echo "<select name=\"players[$r][$opp1line]\" id=\"players".$r.$opp1line."\"><option value='BYE'>BYE</option>";
	 $found=0;
	 for($e=0;$e<count($entries[name]);$e++)
	 {
	    $curid=$entries[player1][$e];
	    if(ereg("doubles",$division)) $curid.=";".$entries[player2][$e];
	    if($pdf!=1) echo "<option value=\"$curid\"";
	    if($thisid==$curid) 
	    {
	       if($pdf!=1) echo " selected"; $found=1;
	       $pdf1.=$entries[name][$e].", ".$entries[school][$e];
	    }
	    if($pdf!=1) echo ">".$entries[name][$e].", ".$entries[school][$e]."</option>";
	 }
	 if($found==0) $pdf1.="BYE";
	 if($pdf!=1) echo "</select>";
      }
      else	//NOT Round 1: Check if winner advanced from previous round:
      {
         if($pdf!=1) 
	 {
	    echo "<input type=hidden name=\"round[$ix]\" value=\"$r\">";
	    echo "<input type=hidden name=\"line[$ix]\" value=\"$opp1line\">";
	 }	
	 //CHECK TO SEE IF THIS SLOT IS FILLED:
	 $sql="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND round='$r' AND line='$opp1line'";
         $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
         if(trim($row[abbreviation])=="" || mysql_num_rows($result)==0)	//IF NOT FILLED, PUT NOTE TO CHECK WINNER IN PREVIOUS ROUND
	 {
	    $prevrnd=$r-1;
	    if($pdf!=1) 
	    {
	       echo "<input type=text size=$size name=\"abbreviation[$r][$opp1line]\" id=\"abbreviation".$r.$opp1line."\" value=\"[Check winner in Round $prevrnd]\">";
	       echo "<input type=hidden name=\"players[$r][$opp1line]\" id=\"players".$r.$opp1line."\" value=\"0\">";
     	    }
	    $pdf1.="&nbsp;";
	 }
	 else	//IF FILLED, SHOW ADVANCED PLAYER/PAIR
	 {
	    if($pdf!=1) echo "<input type=text size=$size name=\"abbreviation[$r][$opp1line]\" id=\"abbreviation".$r.$opp1line."\" value=\"$row[abbreviation]\">";
	    $pdf1.=$row[abbreviation];
            $thisid=$row[player1];
            if(ereg("doubles",$division)) $thisid.=";".$row[player2];
	    if($pdf!=1) echo "<input type=hidden name=\"players[$r][$opp1line]\" id=\"players".$r.$opp1line."\" value=\"$thisid\">";
	 }
      }
      /****** NOW WE'RE ACTUALLY SHOWING OPPONENT 1, THEN THE MATCH NUM, THEN OPPONENT 2: ******/
      //SET UP RADIO BUTTON FOR EACH OPPONENT IN ORDER FOR WINNER TO BE CHOSEN FROM THE TWO:
//echo $sql."<br>";
      $winnerid=$row[player1];
      if(ereg("doubles",$division)) $winnerid.=";".$row[player2];
      if($pdf!=1) 
      {
	 if($r==0 && $entryct==33) echo "<br>";
	 else echo "&nbsp;";
	 echo "<input type=radio name=\"winner[$r][$g]\" id=\"winner".$r.$opp1line."\" value=\"$winnerid\" onClick=\"TEBrackets.UpdateGame($r,$opp1line);\"";
         if($row[winner]=='x') echo " checked";
         if($row[bye]=='x' && $pdf!=1) echo " disabled";
         if($pdf!=1) echo "><font style=\"font-size:11px;\">WINNER</font></div>";
      }
      //CALCULATE THE LINES IN THE PDF WE WANT THIS GAME's OPPONENTS ON
      if($r==0)
      {
	 $pdfline1=25; $pdfline2=27;	//TOALLY GUESSED TO GET THESE LINE #'s
      }
      else
      {
         $pdfline1=($g*(pow(2,$r+1)))+pow(2,($r-1))-1;	//IT WORKS, TRUST ME: ($g x 2^($r+1)) + 2^($r-1) - 1...so for game #2, round 3: 35
         $pdfline2=$pdfline1+pow(2,$r);	//for game #2, round 3: 35 + 2^3 = 43...43-35 = 8 (in Rnd 1, 2 lines b/t opponents, Rnd2:4 lines, Rnd3:8)
      }
      if($rstart==0)
	 $r2=$r;
      else
         $r2=$r-1;
      $pdfcells[$pdfline1][$r2]=$pdf1;	//STORE OPPONENT 1 FOR PDF
      $ix++;	//$ix denotes the LINE
      //SHOW MATCH NUMBER
      if($pdf!=1) 
         echo "<div class=bracket style=\"z-index:$zindex;width:$width;height:$curheight;top:$curtop;left:$curleft;\"><table width=\"$width\" height=\"$curheight\"><tr align=center valign=center><td>";
      if($r==$rounds) 
         $matchnum=GetMatchNum($sport,$class,$division,$rounds+1,$opp2line);//FINAL game for match numbering is considered round 5(CLASS B)/6(CLASS A)
      else $matchnum=GetMatchNum($sport,$class,$division,$r,$opp2line);	//MATCHES ARE NUMBERED USING SERPENTINE METHOD, SEE FUNCTION DEFINITION
      $pdfmatchline=floor(($pdfline1+$pdfline2)/2); //LINE TO PUT MATCH # ON IN PDF
      if($matchnum>0) 
         $pdfcells[$pdfmatchline][$r2]="Match $matchnum";
      if($matchnum>0) { if($pdf!=1) echo "<b>Match $matchnum</b>"; $pdfhtml.="<b>Match $matchnum</b>"; }
      else { if($pdf!=1) echo "&nbsp;"; $pdfhtml.="&nbsp;"; }
      /*** SHOW OPPONENT 2 ***/
      //FOR entryct==33, special exception for opp2line=14
      if($pdf!=1) 
	 echo "</td></tr><tr align=center valign=top><td><div id=\"querystatus".$r.$g."\" style=\"display:none;\"></div></td></tr></table></div>";
      if($pdf!=1) 
	 echo "<div class=plaineight style=\"z-index:100;top:$opp2top;left:$oppleft;position:absolute;\">";
      $pdf2="";
      //if($r==1) { if($pdf!=1) echo "$opp2line.&nbsp;"; $pdf2.="$opp2line.&nbsp;"; }
      $sql="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND round='$r' AND line='$opp2line' AND seed>0";      
      $result=mysql_query($sql);      
      if(mysql_num_rows($result)>0 && $r<=1)     //SEEDED TEAM      
      {         
         $row=mysql_fetch_array($result);         
         $sql2="SELECT first,last,semesters,school FROM eligibility WHERE id='$row[player1]'";
         $result2=mysql_query($sql2);
         $row2=mysql_fetch_array($result2);
         $cursch=$row2[school];
	 if($pdf!=1)echo "#".$row[seed]."&nbsp;"; $pdf2.="#".$row[seed]."&nbsp;";
	 if(mysql_num_rows($result2)==0) { if($pdf!=1) echo "First Last"; $pdf2.="First Last"; }
         else { if($pdf!=1) echo GetStudentInfo($row[player1]); $pdf2.=GetStudentInfo($row[player1]); }
         if(ereg("doubles",$division))
         {
            $sql2="SELECT first,last,semesters FROM eligibility WHERE id='$row[player2]'";
            $result2=mysql_query($sql2);
            if(mysql_num_rows($result2)==0) { if($pdf!=1) echo "/First Last"; $pdf2.="/First Last"; }
            else { if($pdf!=1) echo "/".GetStudentInfo($row[player2]); $pdf2.="/".GetStudentInfo($row[player2]); }
         }
         if(mysql_num_rows($result2)==0)
         {
            if($pdf!=1) echo ", School W-L"; $pdf2.=", School W-L";
         }
         else
         {
            $schoolname=GetSchoolName(GetSID2($cursch,$sport),$sport,date("Y"));
            $record=GetRecord($sport,$division,'Varsity',$row[player1],$row[player2]);
    	    if($record=="0-0") $record="";
	    if($pdf!=1) echo ", $schoolname $record"; $pdf2.=", $schoolname $record";
	 }
      }      
      else if($r<=1 && !($entryct==33 && $opp2line==14))      //SELECT BYE OR PLAYERS (UNLESS entryct==33 and opp2line=14)
      {
         if($pdf!=1) echo "<input type=hidden name=\"round[$ix]\" value=\"$r\">";
         if($pdf!=1) echo "<input type=hidden name=\"line[$ix]\" value=\"$opp2line\">";
         $sql="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND round='$r' AND line='$opp2line'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         $thisid=$row[player1];
         if(ereg("doubles",$division)) $thisid.=";".$row[player2];
         if($pdf!=1) echo "<select name=\"players[$r][$opp2line]\" id=\"players".$r.$opp2line."\"><option value='BYE'>BYE</option>";
	 $found=0;
         for($e=0;$e<count($entries[name]);$e++)
         {
            $curid=$entries[player1][$e];
            if(ereg("doubles",$division)) $curid.=";".$entries[player2][$e];
            if($pdf!=1) echo "<option value=\"$curid\"";
            if($thisid==$curid) 
	    {
	       if($pdf!=1) echo " selected"; $found=1;
	       $pdf2.=$entries[name][$e].", ".$entries[school][$e];
	    }
            if($pdf!=1) echo ">".$entries[name][$e].", ".$entries[school][$e]."</option>";
         }
	 if($found==0) $pdf2.="BYE";
         if($pdf!=1) echo "</select>";
      }
      else      //NOT Round 1 (OR Round 1 && entryct==33 && oppline==14): Check if winner advanced from previous round:
      {
         if($pdf!=1) echo "<input type=hidden name=\"round[$ix]\" value=\"$r\">";
         if($pdf!=1) echo "<input type=hidden name=\"line[$ix]\" value=\"$opp2line\">";
         $sql="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND round='$r' AND line='$opp2line'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
         if(trim($row[abbreviation])=="" || mysql_num_rows($result)==0)	//NO WINNER FROM PREVIOUS ROUND ENTERED YET
         {
            $prevrnd=$r-1;            
	    if($pdf!=1) echo "<input type=text size=$size name=\"abbreviation[$r][$opp2line]\" id=\"abbreviation".$r.$opp2line."\" value=\"[Check winner in Round $prevrnd]\">";
            if($pdf!=1) echo "<input type=hidden name=\"players[$r][$opp2line]\" id=\"players".$r.$opp2line."\" value=\"0\">";         
	    $pdf2.="&nbsp;";
 	 }         
	 else         			//WINNER FROM PREVIOUS ROUND HAS BEEN ENTERED
	 {            
	    if($pdf!=1) echo "<input type=text size=$size name=\"abbreviation[$r][$opp2line]\" id=\"abbreviation".$r.$opp2line."\" value=\"$row[abbreviation]\">";
	    $pdf2.=$row[abbreviation];
	    $thisid=$row[player1];            
	    if(ereg("doubles",$division)) $thisid.=";".$row[player2];            
	    if($pdf!=1) echo "<input type=hidden name=\"players[$r][$opp2line]\" id=\"players".$r.$opp2line."\" value=\"$thisid\">";         
	 }      
      }
      $winnerid=$row[player1];         
      if(ereg("doubles",$division)) $winnerid.=";".$row[player2];         
      if($pdf!=1) 
      {
         if($r==0 && $entryct==33) echo "<br>";
         else echo "&nbsp;";
	 echo "<input type=radio name=\"winner[$r][$g]\" id=\"winner".$r.$opp2line."\" value=\"$winnerid\" onClick=\"TEBrackets.UpdateGame($r,$opp2line);\"";
      }
      if($row[winner]=='x') if($pdf!=1) echo " checked";
      if($row[bye]=='x') if($pdf!=1) echo " disabled";
      if($pdf!=1) echo "><font style=\"font-size:11px;\">WINNER</font>";      
      $ix++; 
      if($pdf!=1) echo "</div>"; 
      $pdfcells[$pdfline2][$r2]=$pdf2;
      //ROUND 4(CLASS B)/5(CLASS A) INCLUDES CONSOLATION GAME:
      if($r==$rounds)
      {
         $curtop+=($curheight+350);      $curtoppdf+=($curheightpdf+250);
         $opp1top=$curtop-30; 		 $opp1toppdf=$curtoppdf-25;
	 $curheight=75;	   	         $curheightpdf=50;
         $opp2top=$curtop+$curheight+5;      $opp2toppdf=$curtoppdf+$curheightpdf+5;
         $oppleft=$curleft+5;      $oppleftpdf=$curleftpdf+5;
         if($pdf!=1) echo "<div class=plaineight style=\"z-index:100;top:$opp1top;left:$oppleft;position:absolute;\">";
         if($pdf!=1) echo "<input type=hidden name=\"round[$ix]\" value=\"$r\">";
         if($pdf!=1) echo "<input type=hidden name=\"line[$ix]\" value=\"3\">";
         $sql="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND round='$r' AND line='3'";
         $result=mysql_query($sql);
         $row=mysql_fetch_array($result);
	 $pdf1="";
         if(trim($row[abbreviation])=="" || mysql_num_rows($result)==0)
         {
            $prevrnd=$r-1;
            if($pdf!=1) echo "<input type=text size=$size name=\"abbreviation[$r][3]\" id=\"abbreviation".$r."3\" value=\"[Loser from Round $prevrnd]\">";
	    $pdf1.="&nbsp;";
            if($pdf!=1) echo "<input type=hidden name=\"players[$r][3]\" id=\"players".$r."3\" value=\"0\">";
         }
         else
         {
            if($pdf!=1) echo "<input type=text size=$size name=\"abbreviation[$r][3]\" id=\"abbreviation".$r."3\" value=\"$row[abbreviation]\">";
	    $pdf1.=$row[abbreviation];
            $thisid=$row[player1];
            if(ereg("doubles",$division)) $thisid.=";".$row[player2];
            if($pdf!=1) echo "<input type=hidden name=\"players[$r][3]\" id=\"players".$r."3\" value=\"$thisid\">";
         }
         $winnerid=$row[player1];      
         if(ereg("doubles",$division)) $winnerid.=";".$row[player2];      
	 if($pdf!=1) 
	 {
	    echo "&nbsp;<input type=radio name=\"winner[$r][1]\" id=\"winner".$r."3\" value=\"$winnerid\" onClick=\"TEBrackets.UpdateGame($r,3);\"";
	 }
         if($row[winner]=='x') if($pdf!=1) echo " checked";      
         if($pdf!=1) echo "><font style=\"font-size:11px;\">WINNER</font>";      
	 if($pdf!=1) echo "</div>";  
         $ix++;      
         if($pdf!=1) echo "<div class=bracket style=\"z-index:$zindex;width:$width;height:$curheight;top:$curtop;left:$curleft;\"><table width=\"$width\" height=\"$curheight\"><tr align=center valign=center><td>";      
         $matchnum=GetMatchNum($sport,$class,$division,$r,4);//for match numbering, consolation game is round 4(CLASS B)/5(CLASS A), final game is 5/6
	 if($matchnum>0) { if($pdf!=1) echo "<b>Match $matchnum</b>"; }
	 else { if($pdf!=1) echo "&nbsp;"; }
	 if($pdf!=1) echo "</td></tr><tr align=center valign=top><td><div id=\"querystatus".$r."1\" style=\"display:none;\"></div></td></tr></table></div>";
         if($pdf!=1) echo "<div class=plaineight style=\"z-index:100;top:$opp2top;left:$oppleft;position:absolute;\">";
         if($pdf!=1) echo "<input type=hidden name=\"round[$ix]\" value=\"$r\">";         
         if($pdf!=1) echo "<input type=hidden name=\"line[$ix]\" value=\"4\">";         
	 $pdf2="";
	 $sql="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND round='$r' AND line='4'";         
	 $result=mysql_query($sql);         
	 $row=mysql_fetch_array($result);         
	 if(trim($row[abbreviation])=="" || mysql_num_rows($result)==0)         
	 {            
	    $prevrnd=$r-1;            
	    if($pdf!=1) echo "<input type=text size=$size name=\"abbreviation[$r][4]\" id=\"abbreviation".$r."4\" value=\"[Loser from Round $prevrnd]\">";   
	    $pdf2.="&nbsp;";
            if($pdf!=1) echo "<input type=hidden name=\"players[$r][4]\" id=\"players".$r."4\" value=\"0\">";         
	 }         
	 else         
	 {            
	    if($pdf!=1) echo "<input type=text size=$size name=\"abbreviation[$r][4]\" id=\"abbreviation".$r."4\" value=\"$row[abbreviation]\">"; 
	    $pdf2.=$row[abbreviation];
	    $thisid=$row[player1];            
	    if(ereg("doubles",$division)) $thisid.=";".$row[player2];            
	    if($pdf!=1) echo "<input type=hidden name=\"players[$r][4]\" id=\"players".$r."4\" value=\"$thisid\">";         
	 }           
         if($class=="Z") $pdfline1=30; //ALL BOYS CLASSES & GIRLS - NO DISTRICTS (AS OF 10/7/11)
	 else if($rstart==0) $pdfline1=59;
   	 else $pdfline1=57;
         $pdfline2=$pdfline1+4;
         $r2=$r-1;
         $pdfcells[$pdfline1][$r2]=$pdf1;
	 $pdfcells[$pdfline2][$r2]=$pdf2;
         $pdfmatchline=floor(($pdfline1+$pdfline2)/2);
         if($matchnum!=0)
            $pdfcells[$pdfmatchline][$r2]="Match $matchnum";
         $winnerid=$row[player1];      
         if(ereg("doubles",$division)) $winnerid.=";".$row[player2];      
         if($pdf!=1) 
	 {
	    echo "&nbsp;<input type=radio name=\"winner[$r][1]\" id=\"winner".$r."4\" value=\"$winnerid\" onClick=\"TEBrackets.UpdateGame($r,4);\"";
	 }
         if($row[winner]=='x') if($pdf!=1) echo " checked";      
         if($pdf!=1) echo "><font style=\"font-size:11px;\">WINNER</font>";           
         $ix++;         
         if($pdf!=1) echo "</div>"; 
	 //CONSOLATION GAME WINNER:
	 $curtop=$curtop-10+($curheight/2);	$curtoppdf=$curtoppdf-10+($curheightpdf/2);
         $curleft+=$width;	$curleftpdf+=$widthpdf;
         if($pdf!=1) echo "<div class=bracket style=\"z-index:10;width:$width2;top:$curtop;left:$curleft;\">";
         $finalrnd=$rounds+1;
         if($pdf!=1) echo "<input type=hidden name=\"round[$ix]\" value=\"$finalrnd\">";
         if($pdf!=1) echo "<input type=hidden name=\"line[$ix]\" value=\"2\">";
         $sql="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND round='$finalrnd' AND line='2'";
         $result=mysql_query($sql);
	 $row=mysql_fetch_array($result);
         $pdf1="";
	 if(trim($row[abbreviation])=="" || mysql_num_rows($result)==0)
	 {
    	    $prevrnd=$finalrnd-1;
            if($pdf!=1) echo "<input type=text size=$size name=\"abbreviation[$finalrnd][2]\" id=\"abbreviation".$finalrnd."2\" value=\"[Check winner of 3rd Place Match]\">";
	    $pdf1.="&nbsp;";
            if($pdf!=1) echo "<input type=hidden name=\"players[$finalrnd][2]\" id=\"players".$finalrnd."2\" value=\"0\">";
         }
	 else
	 {
            if($pdf!=1) echo "<input type=text size=$size name=\"abbreviation[$finalrnd][2]\" id=\"abbreviation".$finalrnd."2\" value=\"$row[abbreviation]\">";
	    $pdf1.=$row[abbreviation];
    	    $thisid=$row[player1];
    	    if(ereg("doubles",$division)) $thisid.=";".$row[player2];
    	    if($pdf!=1) echo "<input type=hidden name=\"players[$finalrnd][2]\" id=\"players".$finalrnd."2\" value=\"$thisid\">";
	 }
	 if($pdf!=1) echo "</div>"; 
	 if($class=="Z") $pdfline1=33; //ALL BOYS CLASSES & GIRLS- NO DISTRICTS (AS OF 10/7/11)
	 else if($rstart==0) $pdfline1=63;
	 else $pdfline1=61;
	 $pdfline2=$pdfline1-2;
	 $pdfcells[$pdfline1][$rounds]="3rd Place";
	 $pdfcells[$pdfline2][$rounds]=$pdf1;
    	 $ix++;
      }//end if round==5(Class A)/4(Class B)
   }	//for each game
   if($r==1) $tophigh=$curtop;
} 
//final WINNER:
$curtop=$top+(($height/2)*(pow(2,($r-1))-1));	//don't ask where I got this but it works :)
$curtoppdf=$toppdf+(($heightpdf/2)*(pow(2,($r-1))-1));
//$curleft doesn't need to be changed; using same as for consolation game
if($pdf!=1) echo "<div class=bracket style=\"z-index:10;width:$width2;top:$curtop;left:$curleft;\">";
$finalrnd=$rounds+1;
if($pdf!=1) echo "<input type=hidden name=\"round[$ix]\" value=\"$finalrnd\">";
if($pdf!=1) echo "<input type=hidden name=\"line[$ix]\" value=\"1\">";
$sql="SELECT * FROM ".$sport."brackets WHERE class='$class' AND division='$division' AND round='$finalrnd' AND line='1'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$pdffinal="";
if(trim($row[abbreviation])=="" || mysql_num_rows($result)==0)
{
    $prevrnd=$finalrnd-1;
    if($pdf!=1) echo "<input type=text size=$size name=\"abbreviation[$finalrnd][1]\" id=\"abbreviation".$finalrnd."1\" value=\"[Check winner in Round $rounds]\">";
    $pdffinal.="&nbsp;";
    if($pdf!=1) echo "<input type=hidden name=\"players[$finalrnd][1]\" id=\"players".$finalrnd."1\" value=\"0\">";
}
else
{
    if($pdf!=1) echo "<input type=text size=$size name=\"abbreviation[$finalrnd][1]\" id=\"abbreviation".$finalrnd."1\" value=\"$row[abbreviation]\">";     
    $pdffinal.=$row[abbreviation];
    $thisid=$row[player1];
    if(ereg("doubles",$division)) $thisid.=";".$row[player2];
    if($pdf!=1) echo "<input type=hidden name=\"players[$finalrnd][1]\" id=\"players".$finalrnd."1\" value=\"$thisid\">";
}
if($class=="Z") $pdfline1=15; //ALL BOYS CLASSES & GIRLS - NO DISTRICTS (AS OF 10/7/11)
else $pdfline1=31;
$pdfline2=$pdfline1+2;
$pdfcells[$pdfline1][$rounds]=$pdffinal;
$pdfcells[$pdfline2][$rounds]="CHAMPION";
if($pdf!=1) echo "</div>"; 
$curtop=$tophigh+($height*2)-30;
if($pdf!=1) echo "<div style=\"top:$curtop;left:25px;position:absolute;\"><input type=submit name=\"saveround1\" value=\"Save Round 1 Opponents & All Abbreviations\"></div></td></tr>";
if($pdf!=1) echo "</table>";
if($pdf!=1)echo "</form>";
$filename=ereg_replace("[^a-zA-Z]","",$sport)."class".$class.$division;
if($pdf==1)
{
//CREATE PDF OF BRACKET
require_once('../../tcpdf_php4/config/lang/eng.php');
require_once('../../tcpdf_php4/tcpdf.php');
// create new PDF document^M
$orientation="P";
$pdf = new TCPDF($orientation, PDF_UNIT, PDF_PAGE_FORMAT, true);
// set document information^M
$pdf->SetCreator("NSAA");
$pdf->SetAuthor("NSAA");
$pdf->SetTitle("$sportname State Bracket: Class $class $showdiv");
$pdf->SetSubject("$sportname State Bracket: Class $class $showdiv");
$pdf->SetKeywords("tennis,state");
//$pdf->SetMargins(10,17,20,10);
$pdf->SetMargins(10,10,10,10);
$pdf->SetAutoPageBreak(TRUE, 1);
//set some language-dependent strings^M
$pdf->setLanguageArray($l); 
//initialize document^M
$pdf->AliasNbPages();
// add a page
$pdf->AddPage();
//$pdf->SetFont("times","",7);
$pdf->SetFont("freesans","",6);
// output the HTML content^M
$pdf->writeHTML("<b>".date("Y")." $sportname State Bracket: Class $class $showdiv</b>", true, 0, true, 0);

      $x=$pdf->getX();
      $y=$pdf->getY()-2;
$rowheight=4;
$lastplayer1=array();  $lastborder=array();
for($j=0;$j<=$rounds;$j++)
   $lastplayer1[$j]=0;
for($i=0;$i<$lines;$i++)	//FOR EACH LINE
{
   $pdf->setX('0');
   $pdf->setY($y+$rowheight);
   for($j=0;$j<=$rounds;$j++)	//FOR EACH ROUND
   {
      $x=$pdf->getX();
      $y=$pdf->getY();
      if($rstart==0 && $j==0)
      {
	 $pdf->SetFont("freesans","",5); 
	 if(ereg("doubles",$division)) $colwidth=45;
	 else $colwidth=35;
      }
      else
      {
	/*	
         if($j==0 || ($rstart==0 && $j==1)) 
    	    $pdf->SetFont("freesans","",5);
         else
	*/
            $pdf->SetFont("freesans","",6);
	 if(ereg("doubles",$division))
	 {
             if($rstart==0 && $j==1) 
	     {
	        $colwidth=50; 
	        $pdf->SetFont("freesans","",5);
	     }
	     else if($j==0)
	     {
	        $colwidth=70;
                $pdf->SetFont("freesans","",6);
	     }
	     else $colwidth=25;
         }
         else if(($rstart==0 && $j==1) || $j==0) $colwidth=50;				//1st Round, Singles (2nd widest)
         else $colwidth=25;					//Other rounds, narrower
      }
      $lineabove=$i-1;
      if($j==$rounds && $pdfcells[$i][$j]=="") $border="0";
      else if($j==$rounds && ($pdfcells[$i][$j]=="CHAMPION" || $pdfcells[$i][$j]=="3rd Place")) $border="0";
      else if($j==$rounds) $border="B";
      else if($pdfcells[$lineabove][$j]!="" && $lastplayer1[$j]==0 && $lineabove>=0 && !ereg("Match",$pdfcells[$lineabove][$j]))
	 { $border="RT";  $lastplayer1[$j]=($lastplayer1[$j]+1)%2;  }
      else if($pdfcells[$i][$j]!="" && $lastplayer1[$j]==1 && !ereg("Match",$pdfcells[$i][$j]))
         { $border="RB"; }
      else if($lastplayer1[$j]==1 && $lastborder[$j]=="RB") { $border="0"; if($lineabove>0) $lastplayer1[$j]=($lastplayer1[$j]+1)%2;}
      else if($lastplayer1[$j]==1) $border="R";
      else { $border="0";  }
      if(ereg("Match",$pdfcells[$i][$j]))
      {
	 $pdfcells[$i][$j]="<b>".$pdfcells[$i][$j]."</b>"; $align="C";
      }
      else $align="L";
      $temp=split("/",$pdfcells[$i][$j]);
	$pdfcells[$i][$j]="";
      for($t=0;$t<count($temp);$t++)
      {
         $pdfcells[$i][$j].=$temp[$t]."/ ";
      }
      $pdfcells[$i][$j]=substr($pdfcells[$i][$j],0,strlen($pdfcells[$i][$j])-2);
      if($rstart==0 && $j==($rounds-1) && $i>=57)	//Move to the side a bit
      {
	 $x+=5;
      }
      $pdf->writeHTMLCell($colwidth,$rowheight,$x,$y,trim($pdfcells[$i][$j]),$border,0,0,true,$align);
      $lastborder[$j]=$border;
   }
}
//Close and output PDF document^M
$pdf->Output("previews/$filename.pdf", "F");
header("Location:previews/$filename.pdf"); exit();
}
if($pdf!=1) 
{
	echo "<div class='alert' style='width:800px'><b>INSTRUCTIONS:</b><p>The first round of the bracket has been filled in based on the Seedings & Byes you entered on the <a class=small href=\"stateseeds.php?session=$session&classdiv=$classdiv&sport=$sport\">previous page</a>.  The non-seeded entries were then randomly entered into the first round.</p><p>You can edit the non-seeded opponents' placements and click the Save button at the bottom of this screen.</p><p>As the tournament progresses, you can select the winner of each Match and then edit their school name field to include the match score.  Make sure to click the Save button at the bottom of this screen.</p><p>When you are satisfied with the current bracket, click \"Publish to Website\" below to publish the latest version of this bracket on the $sportname page on the NSAA Website.</p></div><br>";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a target=\"_blank\" href=\"statebrackets.php?sport=$sport&session=$session&classdiv=$classdiv&pdf=1\">Preview Bracket</a>&nbsp;&nbsp;<a target=\"_blank\" href=\"publish.php?sport=$sport&session=$session&filename=".urlencode("previews/".$filename).".pdf\">Publish to Website</a></caption>";
}
if($pdf!=1)
{
echo "<div id=\"loading\" style=\"display:none;\"></div>";
echo $end_html;
}
?>
