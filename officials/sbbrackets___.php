<?php
require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$sport='sb';
if($sport=="sb"){
    $sql="SELECt * FROM sbseeds WHERE distid=$distid";
    $result=mysql_query($sql);
    $index=1;
    while ($row=mysql_fetch_array($result)) {
        $point=GetPointAvg($row[sid],$sport,GetFallYear($sport));
        $sql="UPDATE sbseeds SET ptavg='$point' WHERE distid=$distid AND sid=$row[sid]";
        mysql_query($sql);
        $index++;
    }

    $sql="SELECt * FROM sbseeds WHERE distid=$distid ORDER BY ptavg DESC";
    $index=1;
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result)){
        $sql="UPDATE sbseeds SET seed=$index WHERE distid=$distid AND sid=$row[sid]";
        mysql_query($sql);
        $index++;
    }
}
$sportname=GetSportName($sport);
$districts=$sport."districts";
$disttimes=$sport."disttimes";
$width="200";	//width of each "round" in bracket
$height="100";	//height of each "match"
echo   "<div style=\"text-align:center\"><a href=\"/\"><img src=\"/wp-content/uploads/2014/08/nsaalogotransparent250.png\" style=\"height:80;margin:5px;border:0;\"></a></div>";
echo $init_html;
echo "<table><tr align=center><td>";

//get number of teams
$sql="SELECT * FROM sbseeds WHERE distid='$distid'";
$result=mysql_query($sql);
$teamct=mysql_num_rows($result);
if($teamct==0)
{
    $sql="SELECT * FROM $db_name2.$disttimes WHERE distid='$distid' AND day!='0000-00-00'";
    $result=mysql_query($sql);
    $teamct=mysql_num_rows($result);
    $teamct++;
    $teamct=$teamct/2;
}
$gamect=($teamct*2)-1;
$year=GetFallYear('sb');

//get host of district
$sql="SELECT * FROM $db_name2.$districts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$sql2="SELECT school FROM $db_name.logins WHERE id='$row[hostid]'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$homeid=GetSID2($row2[school],'sb');
if(!($row[post]=='y' && $row[accept]=='y' && $row[confirm]=='y')) $row[hostschool]="??";
//else if($row[site]!='') $row[hostschool]=$row[site];
echo "<font style=\"font-size:12pt;\"><b>$sportname $row[type] $row[class]-$row[district] at $row[hostschool]";
if($row[site]!='') echo "<br>Site: $row[site]";
echo "</b></font><br><br><br>";
$seeded=$row[seeded]; $bracketed=$row[bracketed];

if($row[showtimes]!='y' && ($row[seeded]!='y' || $row[bracketed]!='y'))	//not available yet
{
    echo "<b>Information not available at this time.</b>";
    echo $end_html;
    exit();
}

//get match & seed info for each game in district:
for($i=1;$i<=$gamect;$i++)
{
    $sql="SELECT day,time,sbfield,notes,showgamenum FROM $db_name2.sbdisttimes WHERE distid='$distid' AND gamenum='$i'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $day=split("-",$row[day]);
    $field="match".$i;
    if($row['showgamenum']=='') $row['showgamenum']="Game $i";
    $$field="<font style=\"font-size:9pt;\">$row[showgamenum]";
    if($i==$gamect) $$field.=" (If needed)";
    $$field.="<br>$day[1]/$day[2]/$day[0]<br>$row[time]<br>Field $row[sbfield]";
    if($row[notes]!='') $$field.="<br>$row[notes]";
    $$field.="</font>";
}
for($i=1;$i<=$teamct;$i++)
{
    $sql="SELECT sid,ptavg FROM $db_name2.sbseeds WHERE distid='$distid' AND seed='$i'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $field="seed".$i;
    if($seeded!='y' || $bracketed!='y')
        $$field="Seed #".$i;
    else
        $$field="<font style=\"font-size:8pt;\">#$i ".GetSchoolName($row[sid],'sb',$year)." $row[ptavg] (".GetWinLoss($row[sid],'sb',$year).")</font>";
    $field="sid".$i;
    if($seeded!='y' || $bracketed!='y')
        $$field='';
    else
        $$field=$row[sid];
}
//code by robin
//if ($teamct==8){
//    //MATCH 1: Seed 4 vs 5
//    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='1'";
//    $result=mysql_query($sql);
//    $row=mysql_fetch_array($result);
//    if($row[oppscore]!='' && $row[sidscore]!='')
//    {
//        //MATCH 4: Seed 1 vs Winner 1
//        if($row[sidscore]>$row[oppscore])
//        {
//            $winner1sid=$row[sid]; $score1="$row[sidscore]-$row[oppscore]";
//            $loser1sid=$row[oppid];
//        }
//        else
//        {
//            $winner1sid=$row[oppid]; $score1="$row[oppscore]-$row[sidscore]";
//            $loser1sid=$row[sid];
//        }
//        $winner1="<font style=\"font-size:8pt;\">".GetSchoolName($winner1sid,'sb',$year)." ($score1)</font>";
//        $loser1="<font style=\"font-size:8pt;\">".GetSchoolName($loser1sid,'sb',$year)."</font>";
//
//        $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='3'";
//        $result=mysql_query($sql);
//        $row=mysql_fetch_array($result);
//        if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
//        {
//            //MATCH 9: Winner 4 vs Winner 5
//            if($row[sidscore]>$row[oppscore])
//            {
//                $winner4sid=$row[sid]; $score4="$row[sidscore]-$row[oppscore]";
//                $loser4sid=$row[oppid];
//            }
//            else
//            {
//                $winner4sid=$row[oppid]; $score4="$row[oppscore]-$row[sidscore]";
//                $loser4sid=$row[sid];
//            }
//            $winner4="<font style=\"font-size:8pt;\">".GetSchoolName($winner4sid,'sb',$year)." ($score4)</font>";
//            $loser4="<font style=\"font-size:8pt;\">".GetSchoolName($loser4sid,'sb',$year)."</font>";
//
//            $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='9'";
//            $result=mysql_query($sql);
//            $row=mysql_fetch_array($result);
//            if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
//            {
//                //MATCH 12: Winner 9 vs Winner 11
//                if($row[sidscore]>$row[oppscore])
//                {
//                    $winner9sid=$row[sid]; $score9="$row[sidscore]-$row[oppscore]";
//                    $loser9sid=$row[oppid];
//                }
//                else
//                {
//                    $winner9sid=$row[oppid]; $score9="$row[oppscore]-$row[sidscore]";
//                    $loser9sid=$row[sid];
//                }
//                $winner9="<font style=\"font-size:8pt;\">".GetSchoolName($winner9sid,'sb',$year)." ($score9)</font>";
//                $loser9="<font style=\"font-size:8pt;\">".GetSchoolName($loser9sid,'sb',$year)."</font>";
//
//                $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='12'";
//                $result=mysql_query($sql);
//                $row=mysql_fetch_array($result);
//                if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
//                {
//                    if($row[sidscore]>$row[oppscore])
//                    {
//                        $winner12sid=$row[sid]; $score12="$row[sidscore]-$row[oppscore]";
//                        $loser12sid=$row[oppid];
//                    }
//                    else
//                    {
//                        $winner12sid=$row[oppid]; $score12="$row[oppscore]-$row[sidscore]";
//                        $loser12sid=$row[sid];
//                    }
//                    $winner12="<font style=\"font-size:8pt;\">".GetSchoolName($winner12sid,'sb',$year)." ($score12)</font>";
//                    $loser12="<font style=\"font-size:8pt;\">".GetSchoolName($loser12sid,'sb',$year)."</font>";
//                    $sql2="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND (sid='$loser12sid' OR oppid='$loser12sid')";
//                    $result2=mysql_query($sql2);
//                    while($row2=mysql_fetch_array($result2))
//                    {
//                        if(($row2[sid]==$loser12sid && $row2[sidscore]<$row2[oppscore]) || ($row2[oppid]==$loser12sid && $row2[oppscore]<$row2[sidscore]))
//                            $losses++;
//                    }
//                    $sql2="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='13'";
//                    $result2=mysql_query($sql2);
//                    $row2=mysql_fetch_array($result2);
//                    if($losses<2 || ($row2[sidscore]!='' && $row2[oppscore]!='' && mysql_num_rows($result2)>0))
//                    {
//                        $game13opp1="<font style=\"font-size:8pt;\">".GetSchoolName($winner12sid,'sb',$year)."</font>";
//                        $game13opp2="<font style=\"font-size:8pt;\">".GetSchoolName($loser12sid,'sb',$year)."</font>";
//                        $winner12.="<br>(See Game 13)";
//                        $match13=ereg_replace("\(If needed\)","",$match13);
//                        if($row2[sidscore]!='' && $row2[oppscore]!='' && mysql_num_rows($result2)>0)
//                        {
//                            if($row2[sidscore]>$row2[oppscore])
//                            {
//                                $winner13sid=$row2[sid]; $score13="$row2[sidscore]-$row2[oppscore]";
//                            }
//                            else
//                            {
//                                $winner13sid=$row2[oppid]; $score13="$row2[oppscore]-$row2[sidscore]";
//                            }
//                            $winner13="<font style=\"font-size:8pt;\">".GetSchoolName($winner13sid,'sb',$year)." ($score13)</font>";
//                        }
//                    }
//                }
//                else
//                {
//                    $winner12="Winner #12"; $loser12="";
//                }
//            }
//            else
//            {
//                $winner9="Winner #9"; $loser9="Loser #9"; $winner12="Winner #12"; $loser12="";
//            }
//        }
//        else
//        {
//            $winner4="Winner #4"; $loser4="Loser #4"; $winner9="Winner #9"; $loser9="Loser #9";
//            $winner12="Winner #12"; $loser12="";
//        }
//    }
//    else
//    {
//        $winner1="Winner #1"; $loser1="Loser #1"; $winner4="Winner #4"; $loser4="Loser #4";
//        $winner9="Winner #9"; $loser9="Loser #9"; $winner12="Winner #12"; $loser12="";
//    }
//
//    //MATCH 2: Seed 3 vs 6
//    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='2'";
//    $result=mysql_query($sql);
//    $row=mysql_fetch_array($result);
//    if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
//    {
//        //MATCH 5: Winner 2 vs Winner 3
//        if($row[sidscore]>$row[oppscore])
//        {
//            $winner2sid=$row[sid]; $score2="$row[sidscore]-$row[oppscore]";
//            $loser2sid=$row[oppid];
//        }
//        else
//        {
//            $winner2sid=$row[oppid]; $score2="$row[oppscore]-$row[sidscore]";
//            $loser2sid=$row[sid];
//        }
//        $winner2="<font style=\"font-size:8pt;\">".GetSchoolName($winner2sid,'sb',$year)." ($score2)</font>";
//        $loser2="<font style=\"font-size:8pt;\">".GetSchoolName($loser2sid,'sb',$year)."</font>";
//
//        $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='5'";
//        $result=mysql_query($sql);
//        $row=mysql_fetch_array($result);
//        if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
//        {
//            //MATCH 9: Winner 4 vs Winner 5
//            if($row[sidscore]>$row[oppscore])
//            {
//                $winner5sid=$row[sid]; $score5="$row[sidscore]-$row[oppscore]";
//                $loser5sid=$row[oppid];
//            }
//            else
//            {
//                $winner5sid=$row[oppid]; $score5="$row[oppscore]-$row[sidscore]";
//                $loser5sid=$row[sid];
//            }
//            $winner5="<font style=\"font-size:8pt\">".GetSchoolName($winner5sid,'sb',$year)." ($score5)</font>";
//            $loser5="<font style=\"font-size:8pt\">".GetSchoolName($loser5sid,'sb',$year)."</font>";
//        }
//        else
//        {
//            $winner5="Winner #5"; $loser5="Loser #5";
//        }
//    }
//    else
//    {
//        $winner2="Winner #2"; $loser2="Loser #2"; $winner5="Winner #5"; $loser5="Loser #5";
//    }
//
//    //MATCH 3: Seed 2 vs 7
//    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='3'";
//    $result=mysql_query($sql);
//    $row=mysql_fetch_array($result);
//    if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
//    {
//        if($row[sidscore]>$row[oppscore])
//        {
//            $winner3sid=$row[sid]; $score3="$row[sidscore]-$row[oppscore]";
//            $loser3sid=$row[oppid];
//        }
//        else
//        {
//            $winner3sid=$row[oppid]; $score3="$row[oppscore]-$row[sidscore]";
//            $loser3sid=$row[sid];
//        }
//        $winner3="<font style=\"font-size:8pt\">".GetSchoolName($winner3sid,'sb',$year)." ($score3)</font>";
//        $loser3="<font style=\"font-size:8pt\">".GetSchoolName($loser3sid,'sb',$year)."</font>";
//    }
//    else
//    {
//        $winner3="Winner #3"; $loser3="Loser #3";
//    }
//
//    //MATCH 3: Seed 2 vs 7
//    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='4'";
//    $result=mysql_query($sql);
//    $row=mysql_fetch_array($result);
//    if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
//    {
//        if($row[sidscore]>$row[oppscore])
//        {
//            $winner4sid=$row[sid]; $score4="$row[sidscore]-$row[oppscore]";
//            $loser4sid=$row[oppid];
//        }
//        else
//        {
//            $winner4sid=$row[oppid]; $score4="$row[oppscore]-$row[sidscore]";
//            $loser4sid=$row[sid];
//        }
//        $winner4="<font style=\"font-size:8pt\">".GetSchoolName($winner4sid,'sb',$year)." ($score4)</font>";
//        $loser4="<font style=\"font-size:8pt\">".GetSchoolName($loser4sid,'sb',$year)."</font>";
//    }
//    else
//    {
//        $winner4="Winner #4"; $loser4="Loser #4";
//    }
//
//    //MATCH 7: Loser 5 vs Loser 1
//    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='7'";
//    $result=mysql_query($sql);
//    $row=mysql_fetch_array($result);
//    if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
//    {
//        //MATCH 10: Winner 7 vs Winner 8
//        if($row[sidscore]>$row[oppscore])
//        {
//            $winner7sid=$row[sid]; $score7="$row[sidscore]-$row[oppscore]";
//            $loser7sid=$row[oppid];
//        }
//        else
//        {
//            $winner7sid=$row[oppid]; $score7="$row[oppscore]-$row[sidscore]";
//            $loser7sid=$row[sid];
//        }
//        $winner7="<font style=\"font-size:8pt\">".GetSchoolName($winner7sid,'sb',$year)." ($score7)</font>";
//        $loser7="<font style=\"font-size:8pt\">".GetSchoolName($loser7sid,'sb',$year)."</font>";
//
//        $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='10'";
//        $result=mysql_query($sql);
//        $row=mysql_fetch_array($result);
//        if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
//        {
//            //MATCH 11: Loser 9 vs Winner 10
//            if($row[sidscore]>$row[oppscore])
//            {
//                $winner10sid=$row[sid]; $score10="$row[sidscore]-$row[oppscore]";
//                $loser10sid=$row[oppid];
//            }
//            else
//            {
//                $winner10sid=$row[oppid]; $score10="$row[oppscore]-$row[sidscore]";
//                $loser10sid=$row[sid];
//            }
//            $winner10="<font style=\"font-size:8pt\">".GetSchoolName($winner10sid,'sb',$year)." ($score10)</font>";
//            $loser10="<font style=\"font-size:8pt\">".GetSchoolName($loser10sid,'sb',$year)."</font>";
//
//            $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='11'";
//            $result=mysql_query($sql);
//            $row=mysql_fetch_array($result);
//            if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
//            {
//                //MATCH 12: Winner 9 vs Winner 11
//                if($row[sidscore]>$row[oppscore])
//                {
//                    $winner11sid=$row[sid]; $score11="$row[sidscore]-$row[oppscore]";
//                    $loser11sid=$row[oppid];
//                }
//                else
//                {
//                    $winner11sid=$row[oppid]; $score11="$row[oppscore]-$row[sidscore]";
//                    $loser11sid=$row[sid];
//                }
//                $winner11="<font style=\"font-size:8pt\">".GetSchoolName($winner11sid,'sb',$year)." ($score11)</font>";
//                $loser11="<font style=\"font-size:8pt\">".GetSchoolName($loser11sid,'sb',$year)."</font>";
//
//                $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='12'";
//                $result=mysql_query($sql);
//                $row=mysql_fetch_array($result);
//                if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
//                {
//                    if($row[sidscore]>$row[oppscore])
//                    {
//                        $winner12sid=$row[sid]; $score12="$row[sidscore]-$row[oppscore]";
//                        $loser12sid=$row[oppid];
//                    }
//                    else
//                    {
//                        $winner12sid=$row[oppid]; $score12="$row[oppscore]-$row[sidscore]";
//                        $loser12sid=$row[sid];
//                    }
//                    $winner12="<font style=\"font-size:8pt\">".GetSchoolName($winner12sid,'sb',$year)." ($score12)</font>";
//                    $loser12="<font style=\"font-size:8pt\">".GetSchoolName($loser12sid,'sb',$year)."</font>";
//                }
//                else
//                {
//                    $winner12="Winner #12"; $loser12="";
//                }
//            }
//            else
//            {
//                $winner11="Winner #11"; $loser11="Loser #11"; $winner12="Winner #12"; $loser12="";
//            }
//        }
//        else
//        {
//            $winner10="Winner #10"; $loser10="Loser #10";
//            $winner11="Winner #11"; $loser11="Loser #11"; $winner12="Winner #12"; $loser12="";
//        }
//    }
//    else
//    {
//        $winner7="Winner #7"; $loser7="Loser #7"; $winner10="Winner #10"; $loser10="Loser #10";
//        $winner11="Winner #11"; $loser11="Loser #11"; $winner12="Winner #12"; $loser12="";$winner13="Winner #13";
//        $winner14="Winner #14";
//    }
//
//    //MATCH 6: Loser 2 vs Loser 3
//    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='6'";
//    $result=mysql_query($sql);
//    $row=mysql_fetch_array($result);
//    if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
//    {
//        //MATCH 8: Loser 4 vs Winner 6
//        if($row[sidscore]>$row[oppscore])
//        {
//            $winner6sid=$row[sid]; $score6="$row[sidscore]-$row[oppscore]";
//            $loser6sid=$row[oppid];
//        }
//        else
//        {
//            $winner6sid=$row[oppid]; $score6="$row[oppscore]-$row[sidscore]";
//            $loser6sid=$row[sid];
//        }
//        $winner6="<font style=\"font-size:8pt\">".GetSchoolName($winner6sid,'sb',$year)." ($score6)</font>";
//        $loser6="<font style=\"font-size:8pt\">".GetSchoolName($loser6sid,'sb',$year)."</font>";
//
//        $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='8'";
//        $result=mysql_query($sql);
//        $row=mysql_fetch_array($result);
//        if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
//        {
//            //MATCH 10: Winner 7 vs Winner 8
//            if($row[sidscore]>$row[oppscore])
//            {
//                $winner8sid=$row[sid]; $score8="$row[sidscore]-$row[oppscore]";
//                $loser8sid=$row[oppid];
//            }
//            else
//            {
//                $winner8sid=$row[oppid]; $score8="$row[oppscore]-$row[sidscore]";
//                $loser8sid=$row[sid];
//            }
//            $winner8="<font style=\"font-size:8pt\">".GetSchoolName($winner8sid,'sb',$year)." ($score8)</font>";
//            $loser8="<font style=\"font-size:8pt\">".GetSchoolName($loser8sid,'sb',$year)."</font>";
//        }
//        else
//        {
//            $winner8="Winner #8"; $loser8="Loser #8";
//        }
//    }
//    else
//    {
//        $winner6="Winner #6"; $loser6="Loser #6"; $winner8="Winner #8"; $loser8="Loser #8";
//    }
//
//    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='11'";
//    $result=mysql_query($sql);
//    $row=mysql_fetch_array($result);
//    if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
//    {
//        //MATCH 8: Loser 4 vs Winner 6
//        if($row[sidscore]>$row[oppscore])
//        {
//            $winner11sid=$row[sid]; $score11="$row[sidscore]-$row[oppscore]";
//            $loser11sid=$row[oppid];
//        }
//        else
//        {
//            $winner11sid=$row[oppid]; $score11="$row[oppscore]-$row[sidscore]";
//            $loser11sid=$row[sid];
//        }
//        $winner11="<font style=\"font-size:8pt\">".GetSchoolName($winner11sid,'sb',$year)." ($score11)</font>";
//        $loser11="<font style=\"font-size:8pt\">".GetSchoolName($loser11sid,'sb',$year)."</font>";
//
//    }
//    else
//    {
//        $winner11="Winner #11"; $loser11="Loser #11";
//    }
//
//    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='13'";
//    $result=mysql_query($sql);
//    $row=mysql_fetch_array($result);
//    if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
//    {
//        //MATCH 8: Loser 4 vs Winner 6
//        if($row[sidscore]>$row[oppscore])
//        {
//            $winner13sid=$row[sid]; $score13="$row[sidscore]-$row[oppscore]";
//            $loser13sid=$row[oppid];
//        }
//        else
//        {
//            $winner13sid=$row[oppid]; $score13="$row[oppscore]-$row[sidscore]";
//            $loser13sid=$row[sid];
//        }
//        $winner13="<font style=\"font-size:8pt\">".GetSchoolName($winner13sid,'sb',$year)." ($score13)</font>";
//        $loser13="<font style=\"font-size:8pt\">".GetSchoolName($loser13sid,'sb',$year)."</font>";
//
//    }
//    else
//    {
//        $winner13="Winner #13"; $loser13="Loser #13";
//    }
//
//    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='14'";
//    $result=mysql_query($sql);
//    $row=mysql_fetch_array($result);
//    if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
//    {
//        //MATCH 8: Loser 4 vs Winner 6
//        if($row[sidscore]>$row[oppscore])
//        {
//            $winner14sid=$row[sid]; $score14="$row[sidscore]-$row[oppscore]";
//        }
//        else
//        {
//            $winner14sid=$row[oppid]; $score14="$row[oppscore]-$row[sidscore]";
//        }
//        $winner14="<font style=\"font-size:8pt\">".GetSchoolName($winner14sid,'sb',$year)." ($score14)</font>";
//
//    }
//    else
//    {
//        $winner14="Winner #14";
//    }
//
//    echo "<table cellspacing=1 cellpadding=1>";
//    echo "<tr align=center valign=top>";
//    echo "<td>";
//    echo "<table cellspacing=0 cellpadding=0 style='margin-top: 0px'>";
//    echo "<tr align=center valign=bottom><td width=$width height=0>$seed1</td></tr>";
//    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match1</b></td></tr>";
//    echo "<tr align=center valign=top><td width=$width height=20>$seed8</td></tr>";
//    echo "<tr align=center valign=bottom><td width=$width height=20>$seed4</td></tr>";
//    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match2</b></td></tr>";
//    echo "<tr align=center valign=top><td width=$width height=20>$seed5</td></tr>";
//    echo "<tr align=center valign=bottom><td width=$width height=20>$seed3</td></tr>";
//    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match3</b></td></tr>";
//    echo "<tr align=center valign=top><td width=$width height=20>$seed6</td></tr>";
//    echo "<tr align=center valign=bottom><td width=$width height=20>$seed2</td></tr>";
//    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match4</b></td></tr>";
//    echo "<tr align=center valign=top><td width=$width height=20>$seed7</td></tr>";
//    echo "<tr align=center valign=bottom><td width=$width height=72>$loser1</td></tr>";
//    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match5</b></td></tr>";
//    echo "<tr align=center valign=top><td width=$width height=20>$loser2</td></tr>";
//    echo "<tr align=center valign=bottom><td width=$width height=20>$loser3</td></tr>";
//    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match6</b></td></tr>";
//    echo "<tr align=center valign=top><td width=$width height=20>$loser4</td></tr>";
//    echo "</table>";
//    echo "</td><td>";
//    echo "<table>";
//    echo "<tr align=center valign=bottom><td width=$width height=72 style='margin-top: '>$winner1</td></tr>";
//    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match7</b></td></tr>";
//    echo "<tr align=center valign=top><td width=$width height=20>$winner2</td></tr>";
//    echo "<tr align=center valign=bottom><td width=$width height=163>$winner3</td></tr>";
//    echo "<tr align=center><td width=$width height=100 class=border bgcolor=#E0E0E0><b>$match8</b></td></tr>";
//    echo "<tr align=center valign=top><td width=$width height=20>$winner4</td></tr>";
//    echo "<tr align=center valign=bottom><td width=$width height=64>$loser8</td></tr>";
//    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match9</b></td></tr>";
//    echo "<tr align=center valign=top><td width=$width height=20>$winner5</td></tr>";
//    echo "<tr align=center valign=bottom><td width=$width height=150>$winner6</td></tr>";
//    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match10</b></td></tr>";
//    echo "<tr align=center valign=top><td width=$width height=20>$loser7</td></tr>";
//    echo "</table>";
//    echo "</td><td>";
//    echo "<table>";
//    $width2=$width*2;
//    echo "<tr align=center valign=bottom><td width=$width2 height=100>$winner7</td></tr>";
//    echo "<tr align=center><td width=$width2 height=328 class=border bgcolor=#E0E0E0><b>$match11</b></td></tr>";
//    echO "<tr align=center valign=top><td width=$width2 height=20>$winner8</td></tr>";
//    echo "<tr align=center valign=top><td width=$width2>";
//    echo "<table width=100% cellspacing=0 cellpadding=0>";
//    echo "<tr align=center><td width=50%><table width=100%>";
//    echo "<tr align=center valign=bottom><td height=170>$winner9</td></tr>";
//    echO "<tr align=center><td height=250 class=border bgcolor=#E0E0E0><b>$match12</b></td></tr>";
//    echo "<tr align=center valign=top><td height=20>$winner10</td></tr>";
//    echo "</table></td>";
//    echo "<td height=230 width=50%><table width=100%>";
//    echo "<tr align=center valign=bottom><td height=20>$loser11</td></tr>";
//    echo "<tr align=center><td height=300 class=border bgcolor=#E0E0E0><b>$match13</b></td></tr>";
//    echo "<tr align=center valign=top><td height=70>$winner12</td></tr>";
//    echo "</table></td></tr>";
//    echo "</table>";
//    echo "</td></tr>";
//    echo "</table>";
//    echo "</td><td>";
//    echo "<table>";
//    echO "<tr align=center valign=bottom><td width=$width height=190>$winner11</td></tr>";
//    echo "<tr align=center><td width=$width height=330 class=border bgcolor=#E0E0E0><b>$match14</b></td></tr>";
//    echo "<tr align=center valign=top><td width=$width height=50>$winner13</td></tr>";
//    echo "</table>";
//    echo "</td><td>";
//    echo "<table>";
//    echo "<tr><td height=330>&nbsp;</td></tr>";
//    echo "<tr align=center><td width=150 height=30 class=border bgcolor=#E0E0E0><b>$winner14</b></td></tr>";
//    echo "<tr align=center valign=bottom><td width=$width height=200>$game13opp1</td></tr>";
//    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match15</b></td></tr>";
//    echO "<tr align=center valign=top><td width=$width height=25>$game13opp2</td></tr>";
//    echo "</table>";
//
//    echo "</td>";
//    echo "</tr></table>";
//}
 if($teamct==7)
{
    //MATCH 1: Seed 4 vs 5
    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='1'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row[oppscore]!='' && $row[sidscore]!='')
    {
        //MATCH 4: Seed 1 vs Winner 1
        if($row[sidscore]>$row[oppscore])
        {
            $winner1sid=$row[sid]; $score1="$row[sidscore]-$row[oppscore]";
            $loser1sid=$row[oppid];
        }
        else
        {
            $winner1sid=$row[oppid]; $score1="$row[oppscore]-$row[sidscore]";
            $loser1sid=$row[sid];
        }
        $winner1="<font style=\"font-size:8pt;\">".GetSchoolName($winner1sid,'sb',$year)." ($score1)</font>";
        $loser1="<font style=\"font-size:8pt;\">".GetSchoolName($loser1sid,'sb',$year)."</font>";

        $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='4'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
        {
            //MATCH 9: Winner 4 vs Winner 5
            if($row[sidscore]>$row[oppscore])
            {
                $winner4sid=$row[sid]; $score4="$row[sidscore]-$row[oppscore]";
                $loser4sid=$row[oppid];
            }
            else
            {
                $winner4sid=$row[oppid]; $score4="$row[oppscore]-$row[sidscore]";
                $loser4sid=$row[sid];
            }
            $winner4="<font style=\"font-size:8pt;\">".GetSchoolName($winner4sid,'sb',$year)." ($score4)</font>";
            $loser4="<font style=\"font-size:8pt;\">".GetSchoolName($loser4sid,'sb',$year)."</font>";

            $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='9'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);
            if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
            {
                //MATCH 12: Winner 9 vs Winner 11
                if($row[sidscore]>$row[oppscore])
                {
                    $winner9sid=$row[sid]; $score9="$row[sidscore]-$row[oppscore]";
                    $loser9sid=$row[oppid];
                }
                else
                {
                    $winner9sid=$row[oppid]; $score9="$row[oppscore]-$row[sidscore]";
                    $loser9sid=$row[sid];
                }
                $winner9="<font style=\"font-size:8pt;\">".GetSchoolName($winner9sid,'sb',$year)." ($score9)</font>";
                $loser9="<font style=\"font-size:8pt;\">".GetSchoolName($loser9sid,'sb',$year)."</font>";

                $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='12'";
                $result=mysql_query($sql);
                $row=mysql_fetch_array($result);
                if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
                {
                    if($row[sidscore]>$row[oppscore])
                    {
                        $winner12sid=$row[sid]; $score12="$row[sidscore]-$row[oppscore]";
                        $loser12sid=$row[oppid];
                    }
                    else
                    {
                        $winner12sid=$row[oppid]; $score12="$row[oppscore]-$row[sidscore]";
                        $loser12sid=$row[sid];
                    }
                    $winner12="<font style=\"font-size:8pt;\">".GetSchoolName($winner12sid,'sb',$year)." ($score12)</font>";
                    $loser12="<font style=\"font-size:8pt;\">".GetSchoolName($loser12sid,'sb',$year)."</font>";
                    $sql2="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND (sid='$loser12sid' OR oppid='$loser12sid')";
                    $result2=mysql_query($sql2);
                    while($row2=mysql_fetch_array($result2))
                    {
                        if(($row2[sid]==$loser12sid && $row2[sidscore]<$row2[oppscore]) || ($row2[oppid]==$loser12sid && $row2[oppscore]<$row2[sidscore]))
                            $losses++;
                    }
                    $sql2="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='13'";
                    $result2=mysql_query($sql2);
                    $row2=mysql_fetch_array($result2);
                    if($losses<2 || ($row2[sidscore]!='' && $row2[oppscore]!='' && mysql_num_rows($result2)>0))
                    {
                        $game13opp1="<font style=\"font-size:8pt;\">".GetSchoolName($winner12sid,'sb',$year)."</font>";
                        $game13opp2="<font style=\"font-size:8pt;\">".GetSchoolName($loser12sid,'sb',$year)."</font>";
                        $winner12.="<br>(See Game 13)";
                        $match13=ereg_replace("\(If needed\)","",$match13);
                        if($row2[sidscore]!='' && $row2[oppscore]!='' && mysql_num_rows($result2)>0)
                        {
                            if($row2[sidscore]>$row2[oppscore])
                            {
                                $winner13sid=$row2[sid]; $score13="$row2[sidscore]-$row2[oppscore]";
                            }
                            else
                            {
                                $winner13sid=$row2[oppid]; $score13="$row2[oppscore]-$row2[sidscore]";
                            }
                            $winner13="<font style=\"font-size:8pt;\">".GetSchoolName($winner13sid,'sb',$year)." ($score13)</font>";
                        }
                    }
                }
                else
                {
                    $winner12="Winner #12"; $loser12="";
                }
            }
            else
            {
                $winner9="Winner #9"; $loser9="Loser #9"; $winner12="Winner #12"; $loser12="";
            }
        }
        else
        {
            $winner4="Winner #4"; $loser4="Loser #4"; $winner9="Winner #9"; $loser9="Loser #9";
            $winner12="Winner #12"; $loser12="";
        }
    }
    else
    {
        $winner1="Winner #1"; $loser1="Loser #1"; $winner4="Winner #4"; $loser4="Loser #4";
        $winner9="Winner #9"; $loser9="Loser #9"; $winner12="Winner #12"; $loser12="";
    }

    //MATCH 2: Seed 3 vs 6
    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='2'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
    {
        //MATCH 5: Winner 2 vs Winner 3
        if($row[sidscore]>$row[oppscore])
        {
            $winner2sid=$row[sid]; $score2="$row[sidscore]-$row[oppscore]";
            $loser2sid=$row[oppid];
        }
        else
        {
            $winner2sid=$row[oppid]; $score2="$row[oppscore]-$row[sidscore]";
            $loser2sid=$row[sid];
        }
        $winner2="<font style=\"font-size:8pt;\">".GetSchoolName($winner2sid,'sb',$year)." ($score2)</font>";
        $loser2="<font style=\"font-size:8pt;\">".GetSchoolName($loser2sid,'sb',$year)."</font>";

        $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='5'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
        {
            //MATCH 9: Winner 4 vs Winner 5
            if($row[sidscore]>$row[oppscore])
            {
                $winner5sid=$row[sid]; $score5="$row[sidscore]-$row[oppscore]";
                $loser5sid=$row[oppid];
            }
            else
            {
                $winner5sid=$row[oppid]; $score5="$row[oppscore]-$row[sidscore]";
                $loser5sid=$row[sid];
            }
            $winner5="<font style=\"font-size:8pt\">".GetSchoolName($winner5sid,'sb',$year)." ($score5)</font>";
            $loser5="<font style=\"font-size:8pt\">".GetSchoolName($loser5sid,'sb',$year)."</font>";
        }
        else
        {
            $winner5="Winner #5"; $loser5="Loser #5";
        }
    }
    else
    {
        $winner2="Winner #2"; $loser2="Loser #2"; $winner5="Winner #5"; $loser5="Loser #5";
    }

    //MATCH 3: Seed 2 vs 7
    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='3'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
    {
        if($row[sidscore]>$row[oppscore])
        {
            $winner3sid=$row[sid]; $score3="$row[sidscore]-$row[oppscore]";
            $loser3sid=$row[oppid];
        }
        else
        {
            $winner3sid=$row[oppid]; $score3="$row[oppscore]-$row[sidscore]";
            $loser3sid=$row[sid];
        }
        $winner3="<font style=\"font-size:8pt\">".GetSchoolName($winner3sid,'sb',$year)." ($score3)</font>";
        $loser3="<font style=\"font-size:8pt\">".GetSchoolName($loser3sid,'sb',$year)."</font>";
    }
    else
    {
        $winner3="Winner #3"; $loser3="Loser #3";
    }

    //MATCH 7: Loser 5 vs Loser 1
    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='7'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
    {
        //MATCH 10: Winner 7 vs Winner 8
        if($row[sidscore]>$row[oppscore])
        {
            $winner7sid=$row[sid]; $score7="$row[sidscore]-$row[oppscore]";
            $loser7sid=$row[oppid];
        }
        else
        {
            $winner7sid=$row[oppid]; $score7="$row[oppscore]-$row[sidscore]";
            $loser7sid=$row[sid];
        }
        $winner7="<font style=\"font-size:8pt\">".GetSchoolName($winner7sid,'sb',$year)." ($score7)</font>";
        $loser7="<font style=\"font-size:8pt\">".GetSchoolName($loser7sid,'sb',$year)."</font>";

        $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='10'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
        {
            //MATCH 11: Loser 9 vs Winner 10
            if($row[sidscore]>$row[oppscore])
            {
                $winner10sid=$row[sid]; $score10="$row[sidscore]-$row[oppscore]";
                $loser10sid=$row[oppid];
            }
            else
            {
                $winner10sid=$row[oppid]; $score10="$row[oppscore]-$row[sidscore]";
                $loser10sid=$row[sid];
            }
            $winner10="<font style=\"font-size:8pt\">".GetSchoolName($winner10sid,'sb',$year)." ($score10)</font>";
            $loser10="<font style=\"font-size:8pt\">".GetSchoolName($loser10sid,'sb',$year)."</font>";

            $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='11'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);
            if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
            {
                //MATCH 12: Winner 9 vs Winner 11
                if($row[sidscore]>$row[oppscore])
                {
                    $winner11sid=$row[sid]; $score11="$row[sidscore]-$row[oppscore]";
                    $loser11sid=$row[oppid];
                }
                else
                {
                    $winner11sid=$row[oppid]; $score11="$row[oppscore]-$row[sidscore]";
                    $loser11sid=$row[sid];
                }
                $winner11="<font style=\"font-size:8pt\">".GetSchoolName($winner11sid,'sb',$year)." ($score11)</font>";
                $loser11="<font style=\"font-size:8pt\">".GetSchoolName($loser11sid,'sb',$year)."</font>";

                $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='12'";
                $result=mysql_query($sql);
                $row=mysql_fetch_array($result);
                if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
                {
                    if($row[sidscore]>$row[oppscore])
                    {
                        $winner12sid=$row[sid]; $score12="$row[sidscore]-$row[oppscore]";
                        $loser12sid=$row[oppid];
                    }
                    else
                    {
                        $winner12sid=$row[oppid]; $score12="$row[oppscore]-$row[sidscore]";
                        $loser12sid=$row[sid];
                    }
                    $winner12="<font style=\"font-size:8pt\">".GetSchoolName($winner12sid,'sb',$year)." ($score12)</font>";
                    $loser12="<font style=\"font-size:8pt\">".GetSchoolName($loser12sid,'sb',$year)."</font>";
                }
                else
                {
                    $winner12="Winner #12"; $loser12="";
                }
            }
            else
            {
                $winner11="Winner #11"; $loser11="Loser #11"; $winner12="Winner #12"; $loser12="";
            }
        }
        else
        {
            $winner10="Winner #10"; $loser10="Loser #10";
            $winner11="Winner #11"; $loser11="Loser #11"; $winner12="Winner #12"; $loser12="";
        }
    }
    else
    {
        $winner7="Winner #7"; $loser7="Loser #7"; $winner10="Winner #10"; $loser10="Loser #10";
        $winner11="Winner #11"; $loser11="Loser #11"; $winner12="Winner #12"; $loser12="";
    }

    //MATCH 6: Loser 2 vs Loser 3
    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='6'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
    {
        //MATCH 8: Loser 4 vs Winner 6
        if($row[sidscore]>$row[oppscore])
        {
            $winner6sid=$row[sid]; $score6="$row[sidscore]-$row[oppscore]";
            $loser6sid=$row[oppid];
        }
        else
        {
            $winner6sid=$row[oppid]; $score6="$row[oppscore]-$row[sidscore]";
            $loser6sid=$row[sid];
        }
        $winner6="<font style=\"font-size:8pt\">".GetSchoolName($winner6sid,'sb',$year)." ($score6)</font>";
        $loser6="<font style=\"font-size:8pt\">".GetSchoolName($loser6sid,'sb',$year)."</font>";

        $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='8'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        if($row[sidscore]!='' && $row[oppscore]!='' && mysql_num_rows($result)>0)
        {
            //MATCH 10: Winner 7 vs Winner 8
            if($row[sidscore]>$row[oppscore])
            {
                $winner8sid=$row[sid]; $score8="$row[sidscore]-$row[oppscore]";
                $loser8sid=$row[oppid];
            }
            else
            {
                $winner8sid=$row[oppid]; $score8="$row[oppscore]-$row[sidscore]";
                $loser8sid=$row[sid];
            }
            $winner8="<font style=\"font-size:8pt\">".GetSchoolName($winner8sid,'sb',$year)." ($score8)</font>";
            $loser8="<font style=\"font-size:8pt\">".GetSchoolName($loser8sid,'sb',$year)."</font>";
        }
        else
        {
            $winner8="Winner #8"; $loser8="Loser #8";
        }
    }
    else
    {
        $winner6="Winner #6"; $loser6="Loser #6"; $winner8="Winner #8"; $loser8="Loser #8";
    }

    echo "<table cellspacing=1 cellpadding=1>";
    echo "<tr align=center valign=top>";
    echo "<td>";
    echo "<table cellspacing=0 cellpadding=0>";
    echo "<tr align=center valign=bottom><td width=$width height=70>$seed4</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match1</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=20>$seed5</td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=20>$seed3</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match2</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=20>$seed6</td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=20>$seed2</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match3</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=20>$seed7</td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=200>$loser2</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match6</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=20>$loser3</td></tr>";
    echo "</table>";
    echo "</td><td>";
    echo "<table>";
    echo "<tr align=center valign=bottom><td width=$width height=20>$seed1</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match4</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=20>$winner1</td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=110>$winner2</td></tr>";
    echo "<tr align=center><td width=$width height=140 class=border bgcolor=#E0E0E0><b>$match5</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=20>$winner3</td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=50>$loser5</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match7</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=20>$loser1</td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=20>$loser4</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match8</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=20>$winner6</td></tr>";
    echo "</table>";
    echo "</td><td>";
    echo "<table>";
    $width2=$width*2;
    echo "<tr align=center valign=bottom><td width=$width2 height=70>$winner4</td></tr>";
    echo "<tr align=center><td width=$width2 height=250 class=border bgcolor=#E0E0E0><b>$match9</b></td></tr>";
    echO "<tr align=center valign=top><td width=$width2 height=20>$winner5</td></tr>";
    echo "<tr align=center valign=top><td width=$width2>";
    echo "<table width=100% cellspacing=0 cellpadding=0>";
    echo "<tr align=center><td width=50%><table width=100%>";
    echo "<tr align=center valign=bottom><td height=170>$winner7</td></tr>";
    echO "<tr align=center><td height=150 class=border bgcolor=#E0E0E0><b>$match10</b></td></tr>";
    echo "<tr align=center valign=top><td height=20>$winner8</td></tr>";
    echo "</table></td>";
    echo "<td height=230 width=50%><table width=100%>";
    echo "<tr align=center valign=bottom><td height=20>$loser9</td></tr>";
    echo "<tr align=center><td height=150 class=border bgcolor=#E0E0E0><b>$match11</b></td></tr>";
    echo "<tr align=center valign=top><td height=20>$winner10</td></tr>";
    echo "</table></td></tr>";
    echo "</table>";
    echo "</td></tr>";
    echo "</table>";
    echo "</td><td>";
    echo "<table>";
    echO "<tr align=center valign=bottom><td width=$width height=190>$winner9</td></tr>";
    echo "<tr align=center><td width=$width height=330 class=border bgcolor=#E0E0E0><b>$match12</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=50>$winner11</td></tr>";
    echo "</table>";
    echo "</td><td>";
    echo "<table>";
    echo "<tr><td height=330>&nbsp;</td></tr>";
    echo "<tr align=center><td width=150 height=30 class=border bgcolor=#E0E0E0><b>$winner12</b></td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=200>$game13opp1</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match13</b></td></tr>";
    echO "<tr align=center valign=top><td width=$width height=25>$game13opp2</td></tr>";
    echo "</table>";
    if($winner13 && $winner13!='')
    {
        echo "</td><td>";
        echo "<table>";
        echo "<tr><td width=$width height=600>&nbsp;</td></tr>";
        echo "<tr align=center><td width=$width height=30 class=border bgcolor=#E0E0E0><b>$winner13</b></td></tr>";
        echo "</table>";
    }
    echo "</td>";
    echo "</tr></table>";
}//end if teamct==7
else if($teamct==6)
{
    //MATCH 1:  Seed 4 vs 5
    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='1'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row[oppscore]!='' && $row[sidscore]!='')
    {
        //MATCH 3: Seed 1 vs Winner 1
        if($row[sidscore]>$row[oppscore])
        {
            $winner1sid=$row[sid]; $score1="$row[sidscore]-$row[oppscore]";
            $loser1sid=$row[oppid];
        }
        else
        {
            $winner1sid=$row[oppid]; $score1="$row[oppscore]-$row[sidscore]";
            $loser1sid=$row[sid];
        }
        $winner1="<font style=\"font-size:8pt;\">".GetSchoolName($winner1sid,'sb',$year)." ($score1)</font>";
        $loser1="<font style=\"font-size:8pt;\">".GetSchoolName($loser1sid,'sb',$year)."</font>";

        $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='3'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);

        if($row[oppscore]!="" && $row[sidscore]!="" && mysql_num_rows($result)>0)
        {
            //MATCH 7:
            if($row[sidscore]>$row[oppscore])
            {
                $winner3sid=$row[sid]; $score3="$row[sidscore]-$row[oppscore]";
                $loser3sid=$row[oppid];
            }
            else
            {
                $winner3sid=$row[oppid]; $score3="$row[oppscore]-$row[sidscore]";
                $loser3sid=$row[sid];
            }
            $winner3="<font style=\"font-size:8pt;\">".GetSchoolName($winner3sid,'sb',$year)." ($score3)</font>";	 $loser3="<font style=\"font-size:8pt;\">".GetSchoolName($loser3sid,'sb',$year)."</font>";

            $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='7'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);

            if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
            {
                //MATCH 10:
                if($row[sidscore]>$row[oppscore])
                {
                    $winner7sid=$row[sid]; $score7="$row[sidscore]-$row[oppscore]";
                    $loser7sid=$row[oppid];
                }
                else
                {
                    $winner7sid=$row[oppid]; $score7="$row[oppscore]-$row[sidscore]";
                    $loser7sid=$row[sid];
                }
                $winner7="<font style=\"font-size:8pt;\">".GetSchoolName($winner7sid,'sb',$year)." ($score7)</font>";
                $loser7="<font style=\"font-size:8pt;\">".GetSchoolName($loser7sid,'sb',$year)."</font>";

                $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='10'";
                $result=mysql_query($sql);
                $row=mysql_fetch_array($result);

                if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
                {
                    if($row[sidscore]>$row[oppscore])
                    {
                        $winner10sid=$row[sid]; $score10="$row[sidscore]-$row[oppscore]";
                        $loser10sid=$row[oppid];
                    }
                    else
                    {
                        $winner10sid=$row[oppid]; $score10="$row[oppscore]-$row[sidscore]";
                        $loser10sid=$row[sid];
                    }
                    $winner10="<font style=\"font-size:8pt;\">".GetSchoolName($winner10sid,'sb',$year)." ($score10)</font>";
                    $sql2="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND (sid='$loser10sid' OR oppid='$loser10sid')";
                    $result2=mysql_query($sql2);
                    while($row2=mysql_fetch_array($result2))
                    {
                        if(($row2[sid]==$loser10sid && $row2[sidscore]<$row2[oppscore]) || ($row2[oppid]==$loser10sid && $row2[oppscore]<$row2[sidscore]))
                            $losses++;
                    }
                    $sql2="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='11'";
                    $result2=mysql_query($sql2);
                    $row2=mysql_fetch_array($result2);
                    if($losses<2 || ($row2[sidscore]!='' && $row2[oppscore]!='' && mysql_num_rows($result2)>0))
                    {
                        $game11opp1="<font style=\"font-size:8pt;\">".GetSchoolName($winner10sid,'sb',$year)."</font>";
                        $game11opp2="<font style=\"font-size:8pt;\">".GetSchoolName($loser10sid,'sb',$year)."</font>";
                        $winner10.="<br>(See Game 11)";
                        $match11=ereg_replace("\(If needed\)","",$match11);
                        if($row2[sidscore]!='' && $row2[oppscore]!='' && mysql_num_rows($result2)>0)
                        {
                            if($row2[sidscore]>$row2[oppscore])
                            {
                                $winner11sid=$row2[sid]; $score11="$row2[sidscore]-$row2[oppscore]";
                            }
                            else
                            {
                                $winner11sid=$row2[oppid]; $score11="$row2[oppscore]-$row2[sidscore]";
                            }
                            $winner11="<font style=\"font-size:8pt;\">".GetSchoolName($winner11sid,'sb',$year)." ($score11)</font>";
                        }
                    }
                    else
                    {
                        $game11opp1=""; $game11opp2="";
                    }
                }
                else
                    $winner10="";
            }
            else
            {
                $winner7="Winner #7"; $winner10="";
            }
        }
        else
        {
            $winner3="Winner #3"; $winner7="Winner #7"; $winner10="";
            $loser3="Loser #3"; $loser7="Loser #7";
        }
    }
    else
    {
        $winner1="Winner #1"; $winner3="Winner #3"; $winner7="Winner #7"; $winner10="";
        $loser1="Loser #1"; $loser3="Loser #3"; $loser7="Loser #7";
    }

    //MATCH 2: Seed 3 vs 6
    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='2'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
    {
        //MATCH 4:
        if($row[sidscore]>$row[oppscore])
        {
            $winner2sid=$row[sid]; $score2="$row[sidscore]-$row[oppscore]";
            $loser2sid=$row[oppid];
        }
        else
        {
            $winner2sid=$row[oppid]; $score2="$row[oppscore]-$row[sidscore]";
            $loser2sid=$row[sid];
        }
        $winner2="<font style=\"font-size:8pt;\">".GetSchoolName($winner2sid,'sb',$year)." ($score2)</font>";
        $loser2="<font style=\"font-size:8pt;\">".GetSchoolName($loser2sid,'sb',$year)."</font>";

        $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='4'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
        {
            //MATCH 7:
            if($row[sidscore]>$row[oppscore])
            {
                $winner4sid=$row[sid]; $score4="$row[sidscore]-$row[oppscore]";
                $loser4sid=$row[oppid];
            }
            else
            {
                $winner4sid=$row[oppid]; $score4="$row[oppscore]-$row[sidscore]";
                $loser4sid=$row[sid];
            }
            $winner4="<font style=\"font-size:8pt;\">".GetSchoolName($winner4sid,'sb',$year)." ($score4)</font>";
            $loser4="<font style=\"font-size:8pt;\">".GetSchoolName($loser4sid,'sb',$year)."</font>";
            //score for Match 7 already taken care of
        }
        else
        {
            $winner4="Winner #4";
            $loser4="Loser #4";
        }
    }
    else
    {
        $winner2="Winner #2"; $winner4="Winner #4";
        $loser2="Loser #2"; $loser4="Loser #4";
    }

    //MATCH 6:
    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='6'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
    {
        //MATCH 8:
        if($row[sidscore]>$row[oppscore])
        {
            $winner6sid=$row[sid]; $score6="$row[sidscore]-$row[oppscore]";
        }
        else
        {
            $winner6sid=$row[oppid]; $score6="$row[oppscore]-$row[sidscore]";
        }
        $winner6="<font style=\"font-size:8pt;\">".GetSchoolName($winner6sid,'sb',$year)." ($score6)</font>";

        $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='8'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
        {
            //MATCH 9:
            if($row[sidscore]>$row[oppscore])
            {
                $winner8sid=$row[sid]; $score8="$row[sidscore]-$row[oppscore]";
            }
            else
            {
                $winner8sid=$row[oppid]; $score8="$row[oppscore]-$row[sidscore]";
            }
            $winner8="<font style=\"font-size:8pt;\">".GetSchoolName($winner8sid,'sb',$year)." ($score8)</font>";

            $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='9'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);
            if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
            {
                //MATCH 10:
                if($row[sidscore]>$row[oppscore])
                {
                    $winner9sid=$row[sid]; $score9="$row[sidscore]-$row[oppscore]";
                }
                else
                {
                    $winner9sid=$row[oppid]; $score9="$row[oppscore]-$row[sidscore]";
                }
                $winner9="<font style=\"font-size:8pt;\">".GetSchoolName($winner9sid,'sb',$year)." ($score9)</font>";
                //Match 10 taken care of already
            }
            else
                $winner9="Winner #9";
        }
        else
        {
            $winner8="Winner #8"; $winner9="Winner #9";
        }
    }
    else
    {
        $winner6="Winner #6"; $winner8="Winner #8"; $winner9="Winner #9";
    }

    //MATCH 5:
    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='5'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
    {
        //MATCH 8:
        if($row[sidscore]>$row[oppscore])
        {
            $winner5sid=$row[sid]; $score5="$row[sidscore]-$row[oppscore]";
        }
        else
        {
            $winner5sid=$row[oppid]; $score5="$row[oppscore]-$row[sidscore]";
        }
        $winner5="<font style=\"font-size:8pt;\">".GetSchoolName($winner5sid,'sb',$year)." ($score5)</font>";
    }
    else
        $winner5="Winner #5";

    echo "<table cellspacing=1 cellpadding=1>";
    echo "<tr align=center valign=top>";
    echo "<td>";
    echo "<table cellspacing=0 cellpadding=0>";
    echo "<tr align=center valign=bottom><td width=$width height=75>$seed4</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match1</b></td></tr
>";
    echo "<tr align=center valign=top><td width=$width height=25>$seed5</td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=50>$seed3</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match2</b></td></tr
>";
    echo "<tr align=center valign=top><td width=$width height=25>$seed6</td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=25>$loser3</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match6</b></td></tr
>";
    echo "<tr align=center valign=top><td width=$width height=25>$loser2</td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=25>$loser1</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match5</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=25>$loser4</td></tr>";
    echo "</table>";
    echo "</td><td>";
    echo "<table>";
    echo "<tr align=center valign=bottom><td width=$width height=20>$seed1</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match3</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=25>$winner1</td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=45>$seed2</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match4</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=25>$winner2</td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=120>$winner6</td></tr>";
    echo "<tr align=center><td width=$width height=150 class=border bgcolor=#E0E0E0><b>$match8</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=25>$winner5</td></tr>";
    echo "</table>";
    echo "</td><td>";
    echo "<table>";
    echo "<tr align=center valign=bottom><td width=$width height=70>$winner3</td></tr>";
    echo "<tr align=center><td width=$width height=175 class=border bgcolor=#E0E0E0><b>$match7</b></td></tr>";
    echO "<tr align=center valign=top><td width=$width height=75>$winner4</td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=45>$loser7</td></tr>";
    echo "<tr align=center><td width=$width height=150 class=border bgcolor=#E0E0E0><b>$match9</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=25>$winner8</td></tr>";
    echo "</table>";
    echo "</td><td>";
    echo "<table>";
    echO "<tr align=center valign=bottom><td width=$width height=150>$winner7</td></tr>";
    echo "<tr align=center><td width=$width height=300 class=border bgcolor=#E0E0E0><b>$match10</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=50>$winner9</td></tr>";
    echo "</table>";
    echo "</td><td>";
    echo "<table>";
    echo "<tr><td height=300>&nbsp;</td></tr>";
    echo "<tr align=center><td width=150 height=30 class=border bgcolor=#E0E0E0><b>$winner10</b></td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=200>$game11opp1</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match11</b></td></tr>";
    echO "<tr align=center valign=top><td width=$width height=25>$game11opp2</td></tr>";
    echo "</table>";
    if($winner11 && $winner11!='')
    {
        echo "</td><td>";
        echo "<table>";
        echo "<tr><td width=$width height=570>&nbsp;</td></tr>";
        echo "<tr align=center><td width=$width height=30 class=border bgcolor=#E0E0E0><b>$winner11</b></td></tr>
";
        echo "</table>";
    }
    echo "</td>";
    echo "</tr></table>";
}//end if teamct=6
else if($teamct==5)
{
    //MATCH 1:  Seed 4 vs 5
    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='1'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row[oppscore]!='' && $row[sidscore]!='')
    {
        //MATCH 3: Seed 1 vs Winner 1
        if($row[sidscore]>$row[oppscore])
        {
            $winner1sid=$row[sid]; $score1="$row[sidscore]-$row[oppscore]";
            $loser1sid=$row[oppid];
        }
        else
        {
            $winner1sid=$row[oppid]; $score1="$row[oppscore]-$row[sidscore]";
            $loser1sid=$row[sid];
        }
        $winner1="<font style=\"font-size:9pt;\">".GetSchoolName($winner1sid,'sb',$year)." ($score1)</font>";
        $loser1="<font style=\"font-size:9pt;\">".GetSchoolName($loser1sid,'sb',$year)."</font>";

        $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='3'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);

        if($row[oppscore]!="" && $row[sidscore]!="" && mysql_num_rows($result)>0)
        {
            //MATCH 5:
            if($row[sidscore]>$row[oppscore])
            {
                $winner3sid=$row[sid]; $score3="$row[sidscore]-$row[oppscore]";
                $loser3sid=$row[oppid];
            }
            else
            {
                $winner3sid=$row[oppid]; $score3="$row[oppscore]-$row[sidscore]";
                $loser3sid=$row[sid];
            }
            $winner3="<font style=\"font-size:9pt;\">".GetSchoolName($winner3sid,'sb',$year)." ($score3)</font>";
            $loser3="<font style=\"font-size:9pt;\">".GetSchoolName($loser3sid,'sb',$year)."</font>";

            $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='5'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);

            if($row[oppscore]!="" && $row[sidscore]!="" && mysql_num_rows($result)>0)
            {
                //MATCH 8:
                if($row[sidscore]>$row[oppscore])
                {
                    $winner5sid=$row[sid]; $score5="$row[sidscore]-$row[oppscore]";
                    $loser5sid=$row[oppid];
                }
                else
                {
                    $winner5sid=$row[oppid]; $score5="$row[oppscore]-$row[sidscore]";
                    $loser5sid=$row[sid];
                }
                $winner5="<font style=\"font-size:9pt;\">".GetSchoolName($winner5sid,'sb',$year)." ($score5)</font>";
                $loser5="<font style=\"font-size:9pt;\">".GetSchoolName($loser5sid,'sb',$year)."</font>";

                $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='8'";
                $result=mysql_query($sql);
                $row=mysql_fetch_array($result);

                if($row[oppscore]!="" && $row[sidscore]!="" && mysql_num_rows($result)>0)
                {
                    if($row[sidscore]>$row[oppscore])
                    {
                        $winner8sid=$row[sid]; $score8="$row[sidscore]-$row[oppscore]";
                    }
                    else
                    {
                        $winner8sid=$row[oppid]; $score8="$row[oppscore]-$row[sidscore]";
                    }
                    $winner8="<font style=\"font-size:9pt;\">".GetSchoolName($winner8sid,'sb',$year)." ($score8)</font>";
                }
                else
                    $winner8="";
            }
            else
            {
                $winner5="Winner #5"; $winner8="";
            }
        }//end if Match 3 score
        else
        {
            $winner3="Winner #3"; $winner5="Winner #5"; $winner8="";
            $loser3="Loser #3"; $loser5="Loser #5";
        }
    }//end if Match 1 score
    else
    {
        $winner1="Winner #1"; $winner3="Winner #3"; $winner5="Winner #5"; $winner8="";
        $loser1="Loser #1"; $loser3="Loser #3"; $loser5="Loser #5";
    }

    //MATCH 2
    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='2'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);

    if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
    {
        //MATCH 5
        if($row[sidscore]>$row[oppscore])
        {
            $winner2sid=$row[sid]; $score2="$row[sidscore]-$row[oppscore]";
            $loser2sid=$row[oppid];
        }
        else
        {
            $winner2sid=$row[oppid]; $score2="$row[oppscore]-$row[sidscore]";
            $loser2sid=$row[sid];
        }
        $winner2="<font style=\"font-size:9pt;\">".GetSchoolName($winner2sid,'sb',$year)." ($score2)</font>";
        $loser2="<font style=\"font-size:9pt;\">".GetSchoolName($loser2sid,'sb',$year)."</font>";

        $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='5'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);

        if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
        {
            //MATCH 8
            if($row[sidscore]>$row[oppscore])
            {
                $winner5sid=$row[sid]; $score5="$row[sidscore]-$row[oppscore]";
                $loser5sid=$row[oppid];
            }
            else
            {
                $winner5sid=$row[oppid]; $score5="$row[oppscore]-$row[sidscore]";
                $loser5sid=$row[sid];
            }
            $winner5="<font style=\"font-size:9pt;\">".GetSchoolName($winner5sid,'sb',$year)." ($score5)</font>";
            $loser5="<font style=\"font-size:9pt;\">".GetSchoolName($loser5sid,'sb',$year)."</font>";

            $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='8'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);

            if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
            {
                if($row[sidscore]>$row[oppscore])
                {
                    $winner8sid=$row[sid]; $winner8="$row[sidscore]-$row[oppscore]";
                    $loser5sid=$row[oppid];
                }
                else
                {
                    $winner8sid=$row[oppid]; $score8="$row[oppscore]-$row[sidscore]";
                    $loser8sid=$row[sid];
                }
                $winner8="<font style=\"font-size:9pt;\">".GetSchoolName($winner8sid,'sb',$year)." ($score8)</font>";
                $loser8="<font style=\"font-size:9pt;\">".GetSchoolName($loser8sid,'sb',$year)."</font>";
            }
            else
                $winner8="";
        }//end if Match 5 scored
        else
        {
            $winner5="Winner #5"; $winner8="";
            $loser5="Loser #5";
        }
    }//end if Match 2 scored
    else
    {
        $winner2="Winner #2"; $winner5="Winner #5"; $winner8="";
        $loser2="Loser #2"; $loser5="Loser #5";
    }

    //MATCH 4
    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='4'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);

    if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
    {
        //MATCH 6
        if($row[sidscore]>$row[oppscore])
        {
            $winner4sid=$row[sid]; $score4="$row[sidscore]-$row[oppscore]";
        }
        else
        {
            $winner4sid=$row[oppid]; $score4="$row[oppscore]-$row[sidscore]";
        }
        $winner4="<font style=\"font-size:9pt;\">".GetSchoolName($winner4sid,'sb',$year)." ($score4)</font>";

        $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='6'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);

        if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
        {
            //MATCH 7
            if($row[sidscore]>$row[oppscore])
            {
                $winner6sid=$row[sid]; $score6="$row[sidscore]-$row[oppscore]";
            }
            else
            {
                $winner6sid=$row[oppid]; $score6="$row[oppscore]-$row[sidscore]";
            }
            $winner6="<font style=\"font-size:9pt;\">".GetSchoolName($winner6sid,'sb',$year)." ($score6)</fomt>";

            $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='7'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);

            if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
            {
                //MATCH 8
                if($row[sidscore]>$row[oppscore])
                {
                    $winner7sid=$row[sid]; $score7="$row[sidscore]-$row[oppscore]";
                }
                else
                {
                    $winner7sid=$row[oppid]; $score7="$row[oppscore]-$row[sidscore]";
                }
                $winner7="<font style=\"font-size:9pt;\">".GetSchoolName($winner7sid,'sb',$year)." ($score7)</font>";

                $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='8'";
                $result=mysql_query($sql);
                $row=mysql_fetch_array($result);

                if($row[oppscore]!='' && $row[sidscore]!='' && mysql_num_rows($result)>0)
                {
                    if($row[sidscore]>$row[oppscore])
                    {
                        $winner8sid=$row[sid]; $score8="$row[sidscore]-$row[oppscore]";
                        $loser8sid=$row[oppid];
                    }
                    else
                    {
                        $winner8sid=$row[oppid]; $score8="$row[oppscore]-$row[sidscore]";
                        $loser8sid=$row[sid];
                    }
                    $winner8="<font style=\"font-size:9pt;\">".GetSchoolName($winner8sid,'sb',$year)." ($score8)</font>";
                    $sql2="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND (sid='$loser8sid' OR oppid='$loser8sid')";
                    $result2=mysql_query($sql2);
                    while($row2=mysql_fetch_array($result2))
                    {
                        if(($row2[sid]==$loser8sid && $row2[sidscore]<$row2[oppscore]) || ($row2[oppid]==$loser8sid && $row2[oppscore]<$row2[sidscore]))
                            $losses++;
                    }
                    $sql2="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='9'";
                    $result2=mysql_query($sql2);
                    $row2=mysql_fetch_array($result2);
                    if($losses<2 || ($row2[sidscore]!='' && $row2[oppscore]!='' && mysql_num_rows($result2)>0))
                    {
                        $game9opp1="<font style=\"font-size:9pt;\">".GetSchoolName($winner8sid,'sb',$year)."</font>";
                        $game9opp2="<font style=\"font-size:9pt;\">".GetSchoolName($loser8sid,'sb',$year)."</font>";
                        $winner8.="<br>(See Game 9)";
                        $match9=ereg_replace("\(If needed\)","",$match9);
                        if($row2[sidscore]!='' && $row2[oppscore]!='' && mysql_num_rows($result2)>0)
                        {
                            if($row2[sidscore]>$row2[oppscore])
                            {
                                $winner9sid=$row2[sid]; $score9="$row2[sidscore]-$row2[oppscore]";
                            }
                            else
                            {
                                $winner9sid=$row2[oppid]; $score9="$row2[oppscore]-$row2[sidscore]";
                            }
                            $winner9="<font style=\"font-size:9pt;\">".GetSchoolName($winner9sid,'sb',$year)." ($score9)</font>";
                        }
                    }
                    else
                    {
                        $game9opp1=""; $game9opp2="";
                    }
                }
                else
                    $winner8="";
            }
            else
            {
                $winner7="Winner #7"; $winner8="";
            }
        }//end if Match 6 scored
        else
        {
            $winner6="Winner #6"; $winner7="Winner #7"; $winner8="";
        }
    }//end if Match 4
    else
    {
        $winner4="Winner #4"; $winner6="Winner #6"; $winner7="Winner #7"; $winner8="";
        $loser4="Loser #4";
    }

    echo "<table cellspacing=1 cellpadding=1>";
    echo "<tr align=center valign=top>";
    echo "<td>";
    echo "<table cellspacing=0 cellpadding=0>";
    echo "<tr align=center valign=bottom><td width=$width height=75>$seed4</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match1</b></td></tr
>";
    echo "<tr align=center valign=top><td width=$width height=25>$seed5</td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=25>$seed2</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match2</b></td></tr
>";
    echo "<tr align=center valign=top><td width=$width height=25>$seed3</td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=75>$loser1</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match4</b></td></tr
>";
    echo "<tr align=center valign=top><td width=$width height=60>$loser2</td></tr>";
    echo "</table>";
    echo "</td><td>";
    echo "<table cellspacing=0 cellpadding=0>";
    echo "<tr align=center valign=bottom><td width=$width height=25>$seed1</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match3</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=25>$winner1</td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=135>$winner2<hr></td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=90>$loser3</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match6</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=25>$winner4</td></tr>";
    echo "</table>";
    echo "</td><td width=$width align=center>";
    echo "<table cellspacing=0 cellpadding=0>";
    echo "<tr align=center valign=bottom><td width=$width height=75>$winner3</td></tr>";
    echo "<tr align=center><td width=$width height=200 class=border bgcolor=#E0E0E0><b>$match5</b></td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=50>$loser5</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match7</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=50>$winner6</td></tr>";
    echo "</table>";
    echo "</td><td>";
    echo "<table cellspacing=0 cellpadding=0>";
    echo "<tr align=center valign=bottom><td width=$width height=175>$winner5</td></tr>";
    echo "<tr align=center><td width=$width height=200 class=border bgcolor=#E0E0E0><b>$match8</b></td></tr>";
    echO "<tr align=center valign=top><td width=$width height=50>$winner7</td></tr>";
    echo "</table>";
    echo "</td><td>";
    echo "<table>";
    echo "<tr><td height=260>&nbsp;</td></tr>";
    echo "<tr align=center><td width=$width height=30 class=border bgcolor=#E0E0E0><b>$winner8</b></td>";
    echo "<tr align=center valign=bottom><td width=$width height=150>$game9opp1</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match9</b></td></tr>";
    echO "<tr align=center valign=top><td width=$width height=25>$game9opp2</td></tr>";
    echo "</table>";
    if($winner9 && $winner9!='')
    {
        echo "</td><td>";
        echo "<table>";
        echo "<tr><td width=$width height=475>&nbsp;</td></tr>";
        echo "<tr align=center><td width=$width height=30 class=border bgcolor=#E0E0E0><b>$winner9</b></td></tr>";
        echo "</table>";
    }
    echo "</td>";
    echo "</tr>";
    echo "</table>";
}//end if teamct==5
else if($teamct==4)
{
    //MATCH 1:
    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='1'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row[oppscore]!="" && $row[sidscore]!="") //get Match 4 info
    {
        //MATCH 4:
        if($row[sidscore]>$row[oppscore])
        {
            $winner1sid=$row[sid]; $score1="$row[sidscore]-$row[oppscore]";
            $loser1sid=$row[oppid];
        }
        else
        {
            $winner1sid=$row[oppid]; $score1="$row[oppscore]-$row[sidscore]";
            $loser1sid=$row[sid];
        }
        $winner1="<font style=\"font-size:9pt;\">".GetSchoolName($winner1sid,'sb',$year)." ($score1)</font>";
        $loser1="<font style=\"font-size:9pt;\">".GetSchoolName($loser1sid,'sb',$year)."</font>";

        $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='4'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);

        if($row[oppscore]!="" && $row[sidscore]!="" && mysql_num_rows($result)>0)
        {
            //MATCH 6:
            if($row[sidscore]>$row[oppscore])
            {
                $winner4sid=$row[sid]; $score4="$row[sidscore]-$row[oppscore]";
                $loser4sid=$row[oppid];
            }
            else
            {
                $winner4sid=$row[oppid]; $score4="$row[oppscore]-$row[sidscore]";
                $loser4sid=$row[sid];
            }
            $winner4="<font style=\"font-size:9pt;\">".GetSchoolName($winner4sid,'sb',$year)." ($score4)</font>";
            $loser4="<font style=\"font-size:9pt;\">".GetSchoolName($loser4sid,'sb',$year)."</font>";

            $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='6'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);

            if($row[oppscore]!="" && $row[sidscore]!="" && mysql_num_rows($result)>0)
            {
                if($row[sidscore]>$row[oppscore])
                {
                    $winner6sid=$row[sid]; $score6="$row[sidscore]-$row[oppscore]";
                    $loser6sid=$row[oppid];
                }
                else
                {
                    $winner6sid=$row[oppid]; $score6="$row[oppscore]-$row[sidscore]";
                    $loser6sid=$row[sid];
                }
                $winner6="<font style=\"font-size:9pt;\">".GetSchoolName($winner6sid,'sb',$year)." ($score6)</font>";
                $sql2="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND (sid='$loser6sid' OR oppid='$loser6sid')";
                $result2=mysql_query($sql2);
                while($row2=mysql_fetch_array($result2))
                {
                    if(($row2[sid]==$loser6sid && $row2[sidscore]<$row2[oppscore]) || ($row2[oppid]==$loser6sid && $row2[oppscore]<$row2[sidscore]))
                        $losses++;
                }
                $sql2="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='7'";
                $result2=mysql_query($sql2);
                $row2=mysql_fetch_array($result2);
                if($losses<2 || ($row2[sidscore]!='' && $row2[oppscore]!='' && mysql_num_rows($result2)>0))
                {
                    $game7opp1="<font style=\"font-size:9pt;\">".GetSchoolName($winner6sid,'sb',$year)."</font>";
                    $game7opp2="<font style=\"font-size:9pt;\">".GetSchoolName($loser6sid,'sb',$year)."</font>";
                    $winner6.="<br>(See Game 7)";
                    $match7=ereg_replace("\(If needed\)","",$match7);
                    if($row2[sidscore]!='' && $row2[oppscore]!='' && mysql_num_rows($result2)>0)
                    {
                        if($row2[sidscore]>$row2[oppscore])
                        {
                            $winner7sid=$row2[sid]; $score7="$row2[sidscore]-$row2[oppscore]";
                        }
                        else
                        {
                            $winner7sid=$row2[oppid]; $score7="$row2[oppscore]-$row2[sidscore]";
                        }
                        $winner7="<font style=\"font-size:9pt;\">".GetSchoolName($winner7sid,'sb',$year)." ($score7)</font>";
                    }
                }
                else
                {
                    $game7opp1=""; $game7opp2="";
                }
            }
            else $winner6="";
        }
        else
        {
            $winner4="Winner #4"; $winner6="";
            $loser4="Loser #4";
        }
    }
    else
    {
        $winner1="Winner #1"; $winner4="Winner #4"; $winner6="";
        $loser1="Loser #1"; $loser4="Loser #4";
    }

    //MATCH 2:
    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='2'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row[oppscore]!="" && $row[sidscore]!="") //get Match 4 info
    {
        //MATCH 4:
        if($row[sidscore]>$row[oppscore])
        {
            $winner2sid=$row[sid]; $score2="$row[sidscore]-$row[oppscore]";
            $loser2sid=$row[oppid];
        }
        else
        {
            $winner2sid=$row[oppid]; $score2="$row[oppscore]-$row[sidscore]";
            $loser2sid=$row[sid];
        }
        $winner2="<font style=\"font-size:9pt;\">".GetSchoolName($winner2sid,'sb',$year)." ($score2)</font>";
        $loser2="<font style=\"font-size:9pt;\">".GetSchoolName($loser2sid,'sb',$year)."</font>";
    }
    else
    {
        $winner2="Winner #2"; $loser2="Loser #2";
    }

    //MATCH 3:
    $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='3'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row[oppscore]!="" && $row[sidscore]!="")
    {
        //MATCH 5
        if($row[sidscore]>$row[oppscore])
        {
            $winner3sid=$row[sid]; $score3="$row[sidscore]-$row[oppscore]";
        }
        else
        {
            $winner3sid=$row[oppid]; $score3="$row[oppscore]-$row[sidscore]";
        }
        $winner3="<font style=\"font-size:9pt;\">".GetSchoolName($winner3sid,'sb',$year)." ($score3)</font>";

        $sql="SELECT * FROM $db_name.sbsched WHERE distid='$distid' AND gamenum='5'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        if($row[oppscore]!='' && $row[sidscore]!='')
        {
            //MATCH 6
            if($row[sidscore]>$row[oppscore])
            {
                $winner5sid=$row[sid]; $score5="$row[sidscore]-$row[oppscore]";
            }
            else
            {
                $winner5sid=$row[oppid]; $score5="$row[oppscore]-$row[sidscore]";
            }
            $winner5="<font style=\"font-size:9pt;\">".GetSchoolName($winner5sid,'sb',$year)." ($score5)</font>";
        }
        else
        {
            $winner5="Winner #5"; $loser5="Loser #5";
        }
    }
    else
    {
        $winner3="Winner #3"; $winner5="Winner #5";
        $loser3="Loser #3"; $loser5="Loser #5";
    }

    echo "<table cellspacing=1 cellpadding=1>";
    echo "<tr align=center valign=top>";
    echo "<td>";
    echo "<table cellspacing=0 cellpadding=0>";
    echo "<tr align=center valign=bottom><td width=$width height=25>$seed1</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match1</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=25>$seed4</td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=25>$seed2</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match2</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=25>$seed3</td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=25>$loser1</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match3</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=60>$loser2</td></tr>";
    echo "</table>";
    echo "</td><td>";
    echo "<table cellspacing=0 cellpadding=0>";
    echo "<tr align=center valign=bottom><td width=$width height=75>$winner1</td></tr>";
    echo "<tr align=center><td width=$width height=150 class=border bgcolor=#E0E0E0><b>$match4</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=$height>$winner2</td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=50>$winner3</td></tr>";
    echo "<tr align=center><td width=$width height=150 class=border bgcolor=#E0E0E0><b>$match5</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=$height>$loser4</td></tr>";
    echo "</table>";
    echo "</td><td width=$width align=center>";
    echo "<table cellspacing=0 cellpadding=0>";
    echo "<tr align=center valign=bottom><td width=$width height=150>$winner4</td></tr>";
    echo "<tr align=center><td width=100% height=300 class=border bgcolor=#E0E0E0><b>$match6</b></td></tr>";
    echo "<tr align=center valign=top><td width=$width height=100>$winner5</td></tr>";
    echo "</table>";
    echo "</td><td>";
    echo "<table cellspacing=0 cellpadding=0>";
    echo "<tr align=center><td width=$width height=285>&nbsp;</td></tr>";
    echo "<tr align=center><td width=$width height=30 class=border bgcolor=#E0E0E0><b>$winner6</b></td></tr>";
    echo "<tr align=center valign=bottom><td width=$width height=200>$game7opp1</td></tr>";
    echo "<tr align=center><td width=$width height=$height class=border bgcolor=#E0E0E0><b>$match7</b></td></tr>";
    echO "<tr align=center valign=top><td width=$width height=25>$game7opp2</td></tr>";
    echo "</table>";
    if($winner7 && $winner7!='')
    {
        echo "</td><td>";
        echo "<table>";
        echo "<tr><td width=$width height=545>&nbsp;</td></tr>";
        echO "<tr align=center><td width=$width height=30 class=border bgcolor=#E0E0E0><b>$winner7</b></td></tr>";
        echo "</table>";
    }
    echo "</td>";
    echo "</tr>";
    echo "</table>";
}//end if teamct=4
echo $end_html;

?>
