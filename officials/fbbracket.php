<?php
/*****************************************
DISPLAY AUTO-ADVANCING FB PLAYOFF BRACKETS
CREATED 10/10/12
AUTHOR: ANN GAFFIGAN
******************************************/
echo   "<div style=\"text-align:center\"><a href=\"/\"><img src=\"/wp-content/uploads/2014/08/nsaalogotransparent250.png\" style=\"height:80;margin:5px;border:0;\"></a></div>";
require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

//connect to db
$db=mysql_connect($db_host,$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(ValidUser($session))
{
   $level=GetLevel($session);
   $validuser=1;
}
else
{
   $level=0; $validuser=0;
}

$sport='fb';
$sportname=GetSportName($sport);
if(!$class) $class="A";

$html=explode("</head>",$init_html);
echo $html[0];
?>
<style>
div
{
   padding:0px !important;
   margin:0px !important;
}
</style>
<?php
echo "</head>".$html[1];

//get number of teams
if($class=="A" || $class=="B" || $class=="C1" || $class=="C2" || $class=="D6") $teamct=16;
else $teamct=32;
$gamect=$teamct/2;
$year=GetFallYear($sport);

      $width="180px"; $r1width="250px";
      $game1=30; $space1=15; $team1=15;
      $gameheight1=$game1."px";
      $spaceheight1=$space1."px";
      $teamheight1=$team1."px";

if($database && $database!='')
{
   $db1=$database;
   $year=substr($db1,10,4);
}
else
   $db1=$db_name;
$db2=preg_replace("/scores/","officials",$db1);

$sql="USE $db2";
$result=mysql_query($sql);
if($publish)
{
   $sql="UPDATE fbbrackets SET showdistinfo='$showdistinfo' WHERE class='$class'";
   $result=mysql_query($sql);
}
$sql="SELECT * FROM fbbrackets WHERE class='$class'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(!$validuser && $row[showdistinfo]!='y')	//not available yet
{
   echo "<br><b>Information not available at this time.</b>";
   echo $end_html;
   $sql="USE $db_name2";
   $result=mysql_query($sql);
   exit();
}

if($level==1 && !$officials)	//ABILITY to POST or UN-POST to WEBSITE
{
   echo "<form method='post' action='fbbracket.php'>";
   echo "<input type=hidden name='session' value='$session'>";
   echo "<input type=hidden name='class' value='$class'>";
   echo "<div class='alert' style='width:400px'><p><input type=checkbox name=\"showdistinfo\" value=\"y\"";
   if($row[showdistinfo]=='y') echo " checked";
   echo "> Check here to PUBLISH this bracket to the NSAA Website.</p>"; 
   echo "<p><input type=submit name='publish' value='Save Checkmark'></p>";
   if($publish)
      echo "<p style='color:#8b0000;'>(The checkmark has been saved.)</p>";
   echo "<p><a href=\"fbbracket.php?class=$class\" target=\"_blank\">Preview Public Bracket</a></p>";
   echo "</div>";
   echo "</form>";
}

$sql="USE $db1";
$result=mysql_query($sql);

echo "<table cellspacing=0 cellpadding=0><caption><b>$year Class $class $sportname Playoff Bracket</b></caption>";
if($teamct==16)	//8-TEAM BRACKET (CLASSES A/B/C1/C2)
{
      //COLUMN 1: 
      echo "<tr valign=top align=center><td>";
      for($g=1;$g<=8;$g++)
      {
         //GAME 
         //"$gamesite<info>$gamedate<info>$gametime<info>$row[scoreid]<info>$chiefid";
         $game=explode("<info>",FBGetGameInfo($class,1,$g,$year));
	 if($game[4]>0) $chief=GetOffName($game[4]);
         else $chief="";
         $gameinfo="<table cellspacing=0 cellpadding=0 style='height:100%;'><tr align=center><td>$game[1] $game[2]";
	 if($officials) $gameinfo.="<br>$chief";
	 else $gameinfo.="<br>$game[0]";
	 $gameinfo.="</td></tr></table>";
         echo "<div id='SID1".$g."' style='height:$teamheight1;width:$r1width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=left><td>".FBGetOpponent($class,1,$g,'sid')."</td></tr></table></div>";
         echo "<div class='bracketgame' id='GAME1".$g."' style='height:$gameheight1;width:$r1width;'>$gameinfo</div>";
         echo "<div id='OPPID1".$g."' style='height:$teamheight1;width:$r1width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=left><td>".FBGetOpponent($class,1,$g,'oppid')."</td></tr></table></div>";
         //SPACE
	 if($g<8)
           echo "<div style='height:$spaceheight1;width:$r1width;'>&nbsp;</div>";
      }
      //COLUMN 2: 
      echo "</td><td>";
        //SPACE
        $curheight=$game1/2;
      echo "<div style='height:".$curheight."px;width:$width;'>&nbsp;</div>";
      for($g=1;$g<=4;$g++)
      {
         //GAME 
         //"$gamesite<info>$gamedate<info>$gametime<info>$row[scoreid]";
         $game=explode("<info>",FBGetGameInfo($class,2,$g,$year));
         if($game[4]>0) $chief=GetOffName($game[4]);
         else $chief="";
         $curheight=$game1+$space1+(2*$team1)+1; 
         $space2=$space1+$game1;
         $spaceheight=$space2."px";	
	 $gameinfo="<table cellspacing=0 cellpadding=0 style='height:100%;'><tr align=center><td>$game[1] $game[2]";
	 if($officials) $gameinfo.="<br>$chief";
         else $gameinfo.="<br>$game[0]";
	 $gameinfo.="</td></tr></table>";
         echo "<div id='SID2".$g."' style='height:$teamheight1;width:$width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=center><td>".FBGetOpponent($class,2,$g,'sid')."</td></tr></table></div>";
	 $game2=$curheight;
         echo "<div class='bracketgame' id='GAME2".$g."' style='height:".$curheight."px;width:$width;'>$gameinfo</div>";
         echo "<div id='OPPID2".$g."' style='height:$teamheight1;width:$width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=center><td>".FBGetOpponent($class,2,$g,'oppid')."</td></tr></table></div>";
         //SPACE
         if($g<4) echo "<div style='height:$spaceheight;width:$width;'>&nbsp;</div>";
      }
      //COLUMN 3:
      echo "</td><td>";
        //SPACE
        $curheight=($game1/2)+($game2/2);
      echo "<div style='height:".$curheight."px;width:$width;'>&nbsp;</div>";
      for($g=1;$g<=2;$g++)
      {
         //GAME
         //"$gamesite<info>$gamedate<info>$gametime<info>$row[scoreid]";
         $game=explode("<info>",FBGetGameInfo($class,3,$g,$year));
         $curheight=2+$game2+$space2+(2*$team1);
         $game3=$curheight;
         $space3=$space2+($game2);
         $spaceheight=$space3."px";
         if($game[4]>0) $chief=GetOffName($game[4]);
         else $chief="";
         $gameinfo="<table cellspacing=0 cellpadding=0 style='height:100%;'><tr align=center><td>$game[1] $game[2]";
         if($officials) $gameinfo.="<br>$chief";
         else $gameinfo.="<br>$game[0]";
	 $gameinfo.="</td></tr></table>";
         echo "<div id='SID3".$g."' style='height:$teamheight1;width:$width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=center><td>".FBGetOpponent($class,3,$g,'sid')."</td></tr></table></div>";
         echo "<div class='bracketgame' id='GAME3".$g."' style='height:".$curheight."px;width:$width;'>$gameinfo</div>";
         echo "<div id='OPPID3".$g."' style='height:$teamheight1;width:$width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=center><td>".FBGetOpponent($class,3,$g,'oppid')."</td></tr></table></div>";
         //SPACE
         if($g<2) echo "<div style='height:$spaceheight;width:$width;'>&nbsp;</div>";
      }
      //COLUMN 4:
      echo "</td><td>";
        //SPACE
        $curheight=($game1/2)+($game2/2)+($game3/2);
      echo "<div style='height:".$curheight."px;width:$width;'>&nbsp;</div>";
      for($g=1;$g<=1;$g++)
      {
         //GAME
         //"$gamesite<info>$gamedate<info>$gametime<info>$row[scoreid]";
         $game=explode("<info>",FBGetGameInfo($class,4,$g,$year));
         if($game[4]>0) $chief=GetOffName($game[4]);
         else $chief="";
         $curheight=2+$game3+$space3+(2*$team1);
         $game4=$curheight;
         $space4=$space3+($game3);
         $spaceheight=$space4."px";
         $gameinfo="<table cellspacing=0 cellpadding=0 style='height:100%;'><tr align=center><td>$game[1] $game[2]";
	 if($officials) $gameinfo.="<br>$chief";
         else $gameinfo.="<br>$game[0]";
	 $gameinfo.="</td></tr></table>";
         echo "<div id='SID4".$g."' style='height:$teamheight1;width:$width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=center><td>".FBGetOpponent($class,4,$g,'sid')."</td></tr></table></div>";
         echo "<div class='bracketgame' id='GAME4".$g."' style='height:".$curheight."px;width:$width;'>$gameinfo</div>";
         echo "<div id='OPPID4".$g."' style='height:$teamheight1;width:$width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=center><td>".FBGetOpponent($class,4,$g,'oppid')."</td></tr></table></div>";
         //SPACE
         //echo "<div style='height:$spaceheight;width:$width;'>&nbsp;</div>";
      }
      //WINNER
      echo "</td><td>";
        //SPACE
        $curheight=($game1/2)+($game2/2)+($game3/2)+($game4/2);
      echo "<div style='height:".$curheight."px;width:$width;'>&nbsp;</div>";
      echo "<div style='margin-right:15px;height:$teamheight1;width:$width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=center><td><div style=\"width:100%;border-bottom:#000000 1px solid;\"><b>".FBGetChampion($class)."</b></div><br><b>CLASS $class CHAMPION</b></td></tr></table></div>";
      
      echo "</td>";
      echo "</tr>";
}//END IF TEAMCT==16
else	//32
{
      //COLUMN 1:
      echo "<tr valign=top align=center><td>";
      for($g=1;$g<=16;$g++)
      {
         //GAME
         //"$gamesite<info>$gamedate<info>$gametime<info>$row[scoreid]";
         $game=explode("<info>",FBGetGameInfo($class,1,$g,$year));
         if($game[4]>0) $chief=GetOffName($game[4]);
         else $chief="";
         $gameinfo="<table cellspacing=0 cellpadding=0 style='height:100%;'><tr align=center><td>$game[1] $game[2]";
	 if($officials) $gameinfo.="<br>$chief";
         else $gameinfo.="<br>$game[0]";
	 $gameinfo.="</td></tr></table>";
         echo "<div id='SID1".$g."' style='height:$teamheight1;width:$r1width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=left><td>".FBGetOpponent($class,1,$g,'sid')."</td></tr></table></div>";
         echo "<div class='bracketgame' id='GAME1".$g."' style='height:$gameheight1;width:$r1width;'>$gameinfo</div>";
         echo "<div id='OPPID1".$g."' style='height:$teamheight1;width:$r1width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=left><td>".FBGetOpponent($class,1,$g,'oppid')."</td></tr></table></div>";
         //SPACE
         if($g<16) echo "<div style='height:$spaceheight1;width:$r1width;'>&nbsp;</div>";
      }
      //COLUMN 2:
      echo "</td><td>";
        //SPACE
        $curheight=$game1/2;
      echo "<div style='height:".$curheight."px;width:$width;'>&nbsp;</div>";
      for($g=1;$g<=8;$g++)
      {
         //GAME
         //"$gamesite<info>$gamedate<info>$gametime<info>$row[scoreid]";
         $game=explode("<info>",FBGetGameInfo($class,2,$g,$year));
         if($game[4]>0) $chief=GetOffName($game[4]);
         else $chief="";
         $curheight=$game1+$space1+(2*$team1)+1;
         $space2=$space1+$game1;
         $spaceheight=$space2."px";
         $gameinfo="<table cellspacing=0 cellpadding=0 style='height:100%;'><tr align=center><td>$game[1] $game[2]";
	 if($officials) $gameinfo.="<br>$chief";
         else $gameinfo.="<br>$game[0]";
	 $gameinfo.="</td></tr></table>";
         echo "<div id='SID2".$g."' style='height:$teamheight1;width:$width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=center><td>".FBGetOpponent($class,2,$g,'sid')."</td></tr></table></div>";
         $game2=$curheight;
         echo "<div class='bracketgame' id='GAME2".$g."' style='height:".$curheight."px;width:$width;'>$gameinfo</div>";
         echo "<div id='OPPID2".$g."' style='height:$teamheight1;width:$width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=center><td>".FBGetOpponent($class,2,$g,'oppid')."</td></tr></table></div>";
         //SPACE
         if($g<8) echo "<div style='height:$spaceheight;width:$width;'>&nbsp;</div>";
      }
      //COLUMN 3:
      echo "</td><td>";
        //SPACE
        $curheight=($game1/2)+($game2/2);
      echo "<div style='height:".$curheight."px;width:$width;'>&nbsp;</div>";
      for($g=1;$g<=4;$g++)
      {
         //GAME
         //"$gamesite<info>$gamedate<info>$gametime<info>$row[scoreid]";
         $game=explode("<info>",FBGetGameInfo($class,3,$g,$year));
         if($game[4]>0) $chief=GetOffName($game[4]);
         else $chief="";
         $curheight=2+$game2+$space2+(2*$team1);
         $game3=$curheight;
         $space3=$space2+($game2);
         $spaceheight=$space3."px";
         $gameinfo="<table cellspacing=0 cellpadding=0 style='height:100%;'><tr align=center><td>$game[1] $game[2]";
	 if($officials) $gameinfo.="<br>$chief";
         else $gameinfo.="<br>$game[0]";
	 $gameinfo.="</td></tr></table>";
         echo "<div id='SID3".$g."' style='height:$teamheight1;width:$width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=center><td>".FBGetOpponent($class,3,$g,'sid')."</td></tr></table></div>";
         echo "<div class='bracketgame' id='GAME3".$g."' style='height:".$curheight."px;width:$width;'>$gameinfo</div>";
         echo "<div id='OPPID3".$g."' style='height:$teamheight1;width:$width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=center><td>".FBGetOpponent($class,3,$g,'oppid')."</td></tr></table></div>";
         //SPACE
         if($g<4) echo "<div style='height:$spaceheight;width:$width;'>&nbsp;</div>";
      }
      //COLUMN 4:
      echo "</td><td>";
        //SPACE
        $curheight=($game1/2)+($game2/2)+($game3/2);
      echo "<div style='height:".$curheight."px;width:$width;'>&nbsp;</div>";
      for($g=1;$g<=2;$g++)
      {
         //GAME
         //"$gamesite<info>$gamedate<info>$gametime<info>$row[scoreid]";
         $game=explode("<info>",FBGetGameInfo($class,4,$g,$year));
         if($game[4]>0) $chief=GetOffName($game[4]);
         else $chief="";
         $curheight=2+$game3+$space3+(2*$team1);
         $game4=$curheight;
         $space4=$space3+($game3);
         $spaceheight=$space4."px";
         $gameinfo="<table cellspacing=0 cellpadding=0 style='height:100%;'><tr align=center><td>$game[1] $game[2]";
	 if($officials) $gameinfo.="<br>$chief";
         else $gameinfo.="<br>$game[0]";
	 $gameinfo.="</td></tr></table>";
         echo "<div id='SID4".$g."' style='height:$teamheight1;width:$width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=center><td>".FBGetOpponent($class,4,$g,'sid')."</td></tr></table></div>";
         echo "<div class='bracketgame' id='GAME4".$g."' style='height:".$curheight."px;width:$width;'>$gameinfo</div>";
         echo "<div id='OPPID4".$g."' style='height:$teamheight1;width:$width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=center><td>".FBGetOpponent($class,4,$g,'oppid')."</td></tr></table></div>";
         //SPACE
	 if($g<2)
	 {
            //echo "<div style='height:$spaceheight;width:$width;'>&nbsp;</div>";
      	    //WINNER - PUT ON INSIDE OF COLUMN 4
      	    echo "<div style='height:$spaceheight;width:$width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=center><td><div style=\"border-bottom:#000000 1px solid;width:100%;\"><b>".FBGetChampion($class)."</b></div><br><b>CLASS $class CHAMPION</b></td></tr></table></div>";
         }
      }
      //COLUMN 5:
      echo "</td><td>";
        //SPACE
        $curheight=($game1/2)+($game2/2)+($game3/2)+($game4/2);
      echo "<div style='height:".$curheight."px;width:$width;'>&nbsp;</div>";
      for($g=1;$g<=1;$g++)
      {
         //GAME
         //"$gamesite<info>$gamedate<info>$gametime<info>$row[scoreid]";
         $game=explode("<info>",FBGetGameInfo($class,5,$g,$year));
         if($game[4]>0) $chief=GetOffName($game[4]);
         else $chief="";
         $curheight=2+$game4+$space4+(2*$team1);
         $game5=$curheight;
         $space5=$space4+($game4);
         $spaceheight=$space5."px";
         $gameinfo="<table style='width:100%;height:100%;' cellspacing=0 cellpadding=0><tr align=center><td><div style=\"padding:0;margin:0;width:100%;border-left:#000000 1px solid;height:100px;\"><table cellspacing=0 cellpadding=0 style='height:100%;'><tr align=center><td>$game[1] $game[2]";
	 if($officials) $gameinfo.="<br>$chief";
         else $gameinfo.="<br>$game[0]";
	 $gameinfo.="</td></tr></table></div></td></tr></table>";
         echo "<div id='SID5".$g."' style='height:$teamheight1;width:$width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=center><td>".FBGetOpponent($class,5,$g,'sid')."</td></tr></table></div>";
         echo "<div class='bracketgame' id='GAME5".$g."' style='height:".$curheight."px;width:$width;'>$gameinfo</div>";
         echo "<div id='OPPID5".$g."' style='height:$teamheight1;width:$width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=center><td>".FBGetOpponent($class,5,$g,'oppid')."</td></tr></table></div>";
         //SPACE
         //echo "<div style='height:$spaceheight;width:$width;'>&nbsp;</div>";
      }
      //WINNER 
      //echo "</td><td>";
        //SPACE
	/*
        $curheight=($game1/2)+($game2/2)+($game3/2)+($game4/2)+($game5/2);
      echo "<div style='height:".$curheight."px;width:$width;'>&nbsp;</div>";
      echo "<div id='WINNER' style='height:$teamheight1;width:$width;'><table cellspacing=0 cellpadding=0 style='width:100%;height:100%;'><tr align=center><td><b>".FBGetChampion($class)."</b></td></tr></table></div>";
	*/
      echo "</td>";
      echo "</tr>";
}

echo "</table>";
echo $end_html;

$sql="USE $db_name2";
$result=mysql_query($sql);
?>
