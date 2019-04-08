<?php

require '../functions.php';
require '../variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

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
if($sport=="te_b") $gender="Boys";
else $gender="Girls";

if(!$class) $class="A";

$html=$init_html2;
$html.="<table width=100%><tr align=center><td>";
$html.="<table class=nine width='500px' cellspacing=0 cellpadding=3 frames=all rules=all style=\"border:#808080 1px solid;\"><caption><b>Class $class $gender Tennis Team Scores</b><br><i>(as of ".date("m/d/y")." at ".date("h:ia").")</i></caption>";
/*
if($class=="B" && $sport=='te_g') //ALL BOYS CLASSES & GIRLS CLASS A - NO DISTRICTS (AS OF 7/19/11)
{
   $rounds=4;
   $sql0="SELECT DISTINCT t1.sid FROM ".$sport."school AS t1,eligibility AS t2,".$sport."distresults AS t3,headers AS t4 WHERE t3.player1=t2.id AND t2.school=t4.school AND t4.id=t1.mainsch AND t3.player1>0 AND t1.class='$class'";
}
else
{
*/
   $rounds=5;
   $sql0="SELECT DISTINCT t1.sid FROM ".$sport."state AS t1,".$sport."school AS t2 WHERE t1.sid=t2.sid AND t2.class='$class'";
//}
$result0=mysql_query($sql0);
//echo mysql_num_rows($result0);
while($row0=mysql_fetch_array($result0))
{
   $sid=$row0[sid]; $score=0;
   if($session=="1445004954") echo "<br><br><b>$sid ".GetSchoolName($sid,'teb')."<br></b>";
   //$html.="<tr align=left><td colspan=2>$sid</td></tr>";
   $sql="SELECT t1.* FROM ".$sport."brackets AS t1,eligibility AS t2,headers AS t3,".$sport."school AS t4 WHERE t1.player1=t2.id AND t2.school=t3.school AND (t3.id=t4.mainsch OR t3.id=t4.othersch1 OR t3.id=t4.othersch2 OR t3.id=t4.othersch3) AND t4.sid='$sid' AND t1.class='$class' AND t1.winner='x' AND t1.bye!='x' ORDER BY t1.division,t1.round";
if($session=="1445004954") echo "$sql<br>";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))	//GET ALL THE MATCHES THIS TEAM WON
   {
   if($session=="1445004954") echo "$row[division]: ";
      if(ereg("1",$row[division]))	//4 or 1 (consolation) points for win in #1 Singles or Doubles
      {
         //if(($row[round]==$rounds+1 && $row[line]==2) || ($row[round]==$rounds && $row[line]>2)) 
          if (($row[round] == $rounds + 1 && $row[line] > 1)  ) {
              $score+=1;

              if ($session == "1445004954") echo "R" . $row[round] . " Cons W - 1, ";
          }    //CONSOLATION
          else if(($row[round] == $rounds  && $row[line] > 2)|| ($row[round] == $rounds - 1 && $row[line] > 4)){
              $score+=0.5;
          }	//CONSOLATION
         else 
	 {
	    //check if bye:
            if($row[line]%2==0) $otherline=$row[line]-1;
            else $otherline=$row[line]+1;
            $sql2="SELECT * FROM ".$sport."brackets WHERE round='$row[round]' AND line='$otherline' AND division='$row[division]' AND class='$class' AND bye='x'";
            $result2=mysql_query($sql2);
            if(mysql_num_rows($result2)==0)	//NOT A BYE
	    {
	       $score+=4;	//REGULAR WIN		
   if($session=="1445004954") 
   {
        echo "R".$row[round]." Reg W - 4, ";
	if(GetSchoolName($sid,'teb')=="Gretna")
	   echo "<br>$sql2<br>";
   }
	    }
	 }
	 if($row[round]==2)	//check if had a bye previously
	 {
	    $sql2="SELECT * FROM ".$sport."brackets WHERE player1='$row[player1]' AND player2='$row[player2]' AND class='$class' AND division='$row[division]' AND round='1'";
	    $result2=mysql_query($sql2);
	    $row2=mysql_fetch_array($result2);
	    if($row2[line]%2==0) $otherline=$row2[line]-1;
	    else $otherline=$row2[line]+1;
	    $sql2="SELECT * FROM ".$sport."brackets WHERE round='1' AND line='$otherline' AND division='$row[division]' AND class='$class' AND bye='x'";
	    $result2=mysql_query($sql2);
	    if(mysql_num_rows($result2)>0)
	    {
	       $score+=4;	//4 for previous round with bye
   if($session=="1445004954") echo "BYE - 4, ";
	    }
	 }
      }
      else	//2 or 0.5
      {
          if (  ($row[round] == $rounds + 1 && $row[line] > 1) ) {
//                if (preg_match("/singles/",$row[division])){

              $score+=0.5;

              if ($session == "1445004954") echo "R" . $row[round] . " Cons W - 0.5, ";
          } //CONSOLATION
          else if(($row[round] == $rounds && $row[line] > 2)|| ($row[round] == $rounds - 1 && $row[line] > 4)){
              $score+=0.25;
          } //CONSOLATION
         else 
         {
            //check if bye:
            if($row[line]%2==0) $otherline=$row[line]-1;
            else $otherline=$row[line]+1;
            $sql2="SELECT * FROM ".$sport."brackets WHERE round='$row[round]' AND line='$otherline' AND division='$row[division]' AND class='$class' AND bye='x'";
            $result2=mysql_query($sql2);
            if(mysql_num_rows($result2)==0)     //NOT A BYE
	    {
               $score+=2;       //REGULAR WIN
   if($session=="1445004954") echo "R".$row[round]." Reg W - 2, ";
	    }
         }
         if($row[round]==2)     //check if had a bye previously
         {
            $sql2="SELECT * FROM ".$sport."brackets WHERE player1='$row[player1]' AND player2='$row[player2]' AND class='$class' AND division='$row[division]' AND round='1'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            if($row2[line]%2==0) $otherline=$row2[line]-1;
            else $otherline=$row2[line]+1;
            $sql2="SELECT * FROM ".$sport."brackets WHERE round='1' AND line='$otherline' AND class='$class' AND division='$row[division]' AND bye='x'";
            $result2=mysql_query($sql2);
            if(mysql_num_rows($result2)>0)
	    {
               $score+=2;       //4 for previous round with bye
   if($session=="1445004954") echo "Bye - 2, ";
	    }
         }
      }
      //$html.="<tr align=left><td colspan=2>$row[division] Round $row[round] Line $row[line]: $score</td></tr>";
   }
   $sql="SELECT * FROM ".$sport."teamscores WHERE sid='$sid'";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)==0)
      $sql="INSERT INTO ".$sport."teamscores (sid,score) VALUES ('$sid','$score')";
   else
      $sql="UPDATE ".$sport."teamscores SET score='$score' WHERE sid='$sid'";
   $result=mysql_query($sql);
}
$sql="SELECT t1.*,t2.school FROM ".$sport."teamscores AS t1,".$sport."school AS t2 WHERE t1.sid=t2.sid AND t2.class='$class' ORDER BY t1.score DESC,t2.school ASC";
$result=mysql_query($sql);
$html.="<tr align=center><td><b>TEAM</b></td><td><b>SCORE</b></td></tr>";
while($row=mysql_fetch_array($result))
{
   $html.="<tr align=left><td>$row[school]</td><td align=center>$row[score]</td></tr>";
}
$html.="</table>";
$html.="</td></tr></table>";
$html.=$end_html2;
$filename=ereg_replace("_","",$sport)."Class".$class."TeamScores.html";
$open=fopen(citgf_fopen("previews/".$filename),"w");
fwrite($open,$html);
fclose($open); 
 citgf_makepublic("previews/".$filename);
echo ereg_replace("</caption>","<br><a href=\"publish.php?session=$session&filename=previews/$filename\">Publish Class $class Team Scores to NSAA Website</a></caption>",$html);
?>
