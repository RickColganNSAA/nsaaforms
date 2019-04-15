<?php
require '../../calculate/functions.php';
require 'functions.php';
require 'variables.php';

$seednums[sid]=array(0,1,9,5,13,3,11,7,15,1,9,5,13,3,11,7,15);
$seednums[oppid]=array(0,16,8,12,4,14,6,10,2,16,8,12,4,14,6,10,2);

if ($sport=="bbb"){
    $seednums[sid]=array(0,1,2,3,4,5,6,7,8);
    $seednums[oppid]=array(0,16,15,14,13,12,11,10,9);
}

//get array of schools to choose from ($db_name DB)
mysql_close();
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);
//mysql_select_db($db_name."20122013",$db);       //TESTING
//$database="nsaascores20122013"; 		//TESTING
$database=$db_name;
$offdb=preg_replace("/scores/","officials",$database);

$schools=array(); $i=0;

if(!$sport) $sport="vb";
if(!$class) $class="B";
$sportname=GetSportName($sport);
//FIRST we get all the District Champions
$sql="SELECT * FROM $offdb.".$sport."districts WHERE class='$class' AND type='Subdistrict' ORDER BY district ASC";
$result=mysql_query($sql);
$sids=array();
while($row=mysql_fetch_array($result))
{
    $curdistid=$row[id];
    $sids[$i]=GetDistrictChampion($sport,$curdistid,$database);
    $ptavg[$i]=GetPointAvg($sids[$i],$sport);
    $winloss[$i]=GetWinLoss($sids[$i],$sport);
    $schools[$i]=GetSchoolName($sids[$i],$sport)." (".$ptavg[$i].") ($row[class]-$row[district] Champ)";
    $i++;
}
//echo '<pre>';print_r($schools);
if($class==B||$class==C1 || $class==C2||$class==D1 || $class==D2){
    $lastix=$i;
    //NEXT we get the Top 10 based on Wildcard Points
    $schooltbl=GetSchoolsTable($sport);
    $sql="SELECT * FROM $database.$schooltbl WHERE class='$class' AND outofstate!='1' ";
    for($i=0;$i<count($sids);$i++)
    {
        $sql.="AND sid!='$sids[$i]' ";
    }
    $sql.="ORDER BY school";

    $result=mysql_query($sql);
    $ix=0;
    while($row=mysql_fetch_array($result))
    {
        $ptavg2[$ix]=GetPointAvg($row[sid],$sport);
        $winloss2[$ix]=GetWinLoss($row[sid],$sport);
        $sids2[$ix]=$row[sid];
        $schools2[$ix]=GetSchoolName($sids2[$ix],$sport)." (".$ptavg2[$ix].")";
        $ix++;
    }
    array_multisort($ptavg2,SORT_NUMERIC,SORT_DESC,$winloss2,$schools2,$sids2);
    $ix=$lastix;
    for($i=0;$i<10;$i++)
    {
        $ptavg[$ix]=$ptavg2[$i];
        $winloss[$ix]=$winloss2[$i];
        $sids[$ix]=$sids2[$i];
        $schools[$ix]=$schools2[$i];
        $ix++;
    }
    array_multisort($ptavg,SORT_NUMERIC,SORT_DESC,$winloss,$schools,$sids);
    for($i=0;$i<count($ptavg);$i++)
    {
        $rank=$i+1;
        //$schools[$i]="$rank - ".$schools[$i];
        $schools[$i]=$schools[$i];
    }
}


mysql_close();

//connect to $db_name2 db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);
if(!ValidUser($session))
{
    header("Location:index.php?error=1");
    exit();
}
$header=GetHeader($session,"contractadmin");

//connect to $db_name db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name, $db);
//mysql_select_db($db_name."20122013",$db);       //TESTING
if($hiddensave)
{
//print_r($offdb); print_r($hostidd); print_r($distidd);
    $sql="UPDATE $offdb.".$sport."districts SET hostschool='$hostschooll', hostid='$hostidd' WHERE id='$distidd'";
    $result=mysql_query($sql);
//echo '<pre>'; print_r($_POST); exit;
}
if($save && $save!='Save Changes')	//user clicked Save button
{
//    echo '<pre>'; print_r($_POST); exit;
    for($i=0;$i<count($gameids);$i++)
    {
        $time[$i]=addslashes($time[$i]); $site[$i]=addslashes($site[$i]);
        if($school1[$i]!='' && $hostschool[$i]=='1')
            $hostschool[$i]=$school1[$i];
        else if($school2[$i]!='' && $hostschool[$i]=='2')
            $hostschool[$i]=$school2[$i];
        else if($host[$i]!='')
            $hostschool[$i]=$host[$i];
        else $hostschool[$i]='';
        if(trim($site[$i])=="") $site[$i]=addslashes(GetSchoolName($hostschool[$i],$sport));
        //print_r($gameids[$i]);
        //FIRST UPDATE $sport.sched TABLE:
        if($gameids[$i]!='0')	//Score ID given
        {
            $sql="UPDATE ".$sport."sched SET received='$year[$i]-$month[$i]-$day[$i]',sid='$school1[$i]',oppid='$school2[$i]',homeid='$hostschool[$i]',gamenum='$gamenum[$i]' WHERE scoreid='$gameids[$i]'";
        }
        else
        {
            $sql="INSERT INTO ".$sport."sched (distid,received,sid,oppid,homeid,gamenum) VALUES ('$distid[$i]','$year[$i]-$month[$i]-$day[$i]','$school1[$i]','$school2[$i]','$hostschool[$i]','$gamenum[$i]')";
        }
        // echo $sql; exit;
        // if(!($gameids[$i]=='0' && (($month[$i]=='00' || $day[$i]=='00') || $school1[$i]=="" || $school2[$i]=="")))
        // {
        $result=mysql_query($sql);
        //echo "Game $i - $sql<br>".mysql_error().mysql_insert_id()."<br>";
        // }
        //NOW UPDATE $sport.disttimes TABLE:
        if($disttimesid[$i]!='0')
        {
            $sql="UPDATE $offdb.".$sport."disttimes SET day='$year[$i]-$month[$i]-$day[$i]',time='$time[$i]' WHERE id='$disttimesid[$i]'";
        }
        else
        {
            $sql="INSERT INTO $offdb.".$sport."disttimes (distid,day,time,gamenum) VALUES ('$distid[$i]','$year[$i]-$month[$i]-$day[$i]','$time[$i]','1')";
        }
        if(!($disttimesid[$i]=='0' && ($month[$i]=='00' || $day[$i]=='00')))
        {
            $result=mysql_query($sql);
            //echo "Game $i - $sql<br>".mysql_error().mysql_insert_id()."<br>";
            //ALSO UPDATE $sport.districts TABLE:
            $curgamenum=$i+1;
            $curseed1=$curgamenum; $curseed2=16-$curgamenum+1;
            $curseeds=$school1[$i].",".$school2[$i];
            $curschools=GetSchoolName($school1[$i],$sport)." (#".$curseed1.") VS ".GetSchoolName($school2[$i],$sport)." (#".$curseed2.")";
            $sql="UPDATE $offdb.".$sport."districts SET dates='$year[$i]-$month[$i]-$day[$i]',time='$time[$i]',site='$site[$i]',sids='$curseeds',schools='".addslashes($curschools)."' WHERE id='$distid[$i]'";
            $result=mysql_query($sql);
            //echo "Game $i - $sql<br><br>".mysql_error();
        }
    }
    if($class==B){
        header("Location:substatebrackets.php?session=$session&sport=$sport");
    }else{
        header("Location:substatebrackets.php?session=$session&sport=$sport&class=$class");
    }
}

echo $init_html;
echo $header;
echo "<br>";
echo "<a class=small href=\"assign".$sport.".php?session=$session\">Return to $sportname Officials Assignments</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class=small href=\"vbfinals.php?type=District Final&sport=$sport&class=$class\" target=\"_blank\">Preview Class $class District Final Page</a><br><br>";
//echo "<form method=post action=\"substatebrackets.php\">";
echo "<br><form name=assignform method=post action=\"substatebrackets.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=hostchh value=\"$hostchh\">";
echo "<input type=hidden name=appch value=\"$appch\">";
echo "<input type=hidden name=distidd value=\"$distidd\">";
echo "<input type=hidden name=hostschooll value=\"$hostschooll\">";
echo "<input type=hidden name=hostidd value=\"$hostidd\">";
echo "<input type=hidden name=hiddensave>";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"sport\" value=\"$sport\">";
//echo "<input type=hidden name=\"class\" value=\"$class\">";
echo "<table cellspacing=0 cellpadding=5 class='nine'><caption><b>$sportname District Final Brackets:</b><br>";
echo "Choose Class:&nbsp;";
if( preg_match("/bb/",$sport))
    //code by robin
    $classes=array("B","C1","C2","D1","D2");
else if ($sport=="vb")
    $classes=array("B","C1","C2","D1","D2");
else
    $classes=array("B","C1","C2");
//$classes=array("B");
if (empty($class)) $class=$_GET['class'];
echo "<select name=class onchange=\"submit();\"><option>~</option>";
for($i=0;$i<count($classes);$i++)
{
    echo "<option";
    if($class==$classes[$i]) echo " selected";
    echo ">$classes[$i]</option>";
}
echo "</select>&nbsp;";
echo "<br><br>";
echo "</caption>";
if(($class=='B'||$class=='C1' || $class=='C2'||$class=='D1' || $class=='D2') && $class!='~')
//if($class!='' && $class!='~')
{
    $teamct=16; $gamect=8;
    /*
       echo "<tr align=center><td colspan=6><input type=checkbox name=\"showdistinfo\" value='x'";
       if($showdistinfo=='x') echo " checked";
       echo "> Check here to show the date, time and opponents on the <a class=small target=\"_blank\" href=\"/distassign.php?sport=$sport\">$sportname District Assignments page</a>.</td></tr>";
    */

    echo "<tr align=left><td><b>Game #</b></td><td><b>Date</b></td>";
    echo "<td><b>Time</b></td><td><b>Site</b></td><td><b>School 1</b></td><td><b>School 2</b></td></tr>";
    $ix=0;
    //Get Substate District ID's
    $sql="SELECT * FROM $offdb.".$sport."districts WHERE type='District Final' AND class='$class' ORDER BY class,district";
    $result=mysql_query($sql);
    $g=1;
    while($row=mysql_fetch_array($result))
    {
        //GRAB INFO FROM $sport.sched TABLE
        $sql2="SELECT * FROM ".$sport."sched WHERE distid='$row[id]'";
        $result2=mysql_query($sql2);
        $row2=mysql_fetch_array($result2); //echo '<pre>';print_r($row2);
        if(mysql_num_rows($result2)==0) $row2[scoreid]=0;
        echo "<tr align=left";
        if($ix%2==0) echo " bgcolor='#f0f0f0'";
        echo ">";
        echo "<input type=hidden name=\"distid[$ix]\" value=\"$row[id]\">";
        echo "<input type=hidden name=\"gameids[$ix]\" value=\"$row2[scoreid]\">";
        echo "<input type=hidden name=\"gamenum[$ix]\" value=\"1\">";

        //GRAB INFO FROM $sport.disttimes TABLE
        $sql3="SELECT * FROM $offdb.".$sport."disttimes WHERE distid='$row[id]'";
        $result3=mysql_query($sql3);
        $row3=mysql_fetch_array($result3);
        if(mysql_num_rows($result3)==0) $row3[id]=0;
        echo "<input type=hidden name=\"disttimesid[$ix]\" value=\"$row3[id]\">";

        //CONTINUE:
        echo "<td align=center><b>$g</b></td>";
        if(mysql_num_rows($result2)>0) $date=split("-",$row2[received]);
        else $date=split("-",$row3[day]);
        $curmo=$date[1]; $curday=$date[2]; $curyear=$date[0];
        echo "<td><select name=\"month[$ix]\"><option value='00'>MM</option>";
        for($i=1;$i<=12;$i++)
        {
            if($i<9) $value="0".$i;
            else $value=$i;
            echo "<option value=\"$value\"";
            if($curmo==$value) echo " selected";
            echo ">$i</option>";
        }
        echo "</select>/<select name=\"day[$ix]\"><option value='00'>DD</option>";
        for($i=1;$i<=31;$i++)
        {
            if($i<9) $value="0".$i;
            else $value=$i;
            echo "<option value=\"$value\"";
            if($curday==$value) echo " selected";
            echo ">$i</option>";
        }
        echo "</select>/<select name=\"year[$ix]\">";
        $thisyr=date("Y");
        $thisyr1=$thisyr+1;
        for($i=$thisyr;$i<=$thisyr1;$i++)
        {
            echo "<option";
            if($curyear==$i) echo " selected";
            echo ">$i</option>";
        }
        echo "</select>";
        echo "</td>";
        echo "<td><input type=text class=tiny size=10 name=\"time[$ix]\" value=\"$row3[time]\"></td>";
        echo "<td><input type=text size=20 name=\"site[$ix]\" value=\"$row[site]\"></td>";
        echo "<td>";
        if($round==1) echo "<b>#".$seednums[sid][$g]."</b>&nbsp;";
        echo "<select name=\"school1[$ix]\"><option value=''>Choose School</option>";
        $nextgroup=0;
        for($i=0;$i<count($schools);$i++)
        {
            echo "<option value=\"$sids[$i]\"";
            if($row2[sid]==$sids[$i]&&isset($row2[sid])) echo " selected";
            echo ">$schools[$i]</option>";
        }
        echo "</select><br>";
        echo "<input type=radio name=\"hostschool[$ix]\" value='1'";
        if($row2[homeid]==$row2[sid]||!isset($row2[homeid])) echo " checked";
        echo ">Host School $row2[homeid] $row2[sid]";
        echo "</td>";
        echo "<td>";
        if($round==1) echo "<b>#".$seednums[oppid][$g]." bb</b>&nbsp;";
        echo "<select name=\"school2[$ix]\"><option value=''>Choose School</option>";
        for($i=0;$i<count($schools);$i++)
        {
            echo "<option value=\"$sids[$i]\"";
            if($row2[oppid]==$sids[$i]&&isset($row2[oppid])) echo " selected";
            echo ">$schools[$i]</option>";
        }
        echo "</select><br>";
        echo "<input type=radio name=\"hostschool[$ix]\" value='2'";
        if($row2[homeid]==$row2[oppid]&&isset($row2[homeid])) echo " checked";
        echo ">Host School";
        echo "</td>";
        echo "</tr>";
        $ix++; $g++;
    }	//END FOR EACH GAME IN THIS ROUND
    echo "<tr align=center><td colspan=6>";
    echo "<input type=submit class='fancybutton2' name=save value=\"Save\"></td></tr>";
}

echo "</table><br>";
echo "<a href=\"assign".$sport.".php?session=$session\">Return to ".$sportname." Officials Assignments</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\"vbfinals.php?type=District Final&sport=$sport&class=$class\" target=\"_blank\">Preview Class $class District Final Page</a><br>";
echo "</form>";
echo "</form>";
echo $end_html;
/*
 * baseball query
 * INSERT INTO `nsaaofficials`.`bbgdistricts` (`id`, `gender`, `hostid`, `hostid2`, `hostschool`, `class`, `district`, `dates`, `site`, `schools`, `sids`, `type`, `showoffs`, `standby`, `director`, `email`, `post`, `accept`, `confirm`, `bracketed`, `seeded`, `showtimes`, `showdistinfo`, `teamcount`, `city`, `time`) VALUES (NULL, 'Boys', '0', '0', '', 'D2', '8', '', '', '', '', 'District Final', '', '', '', '', '', '', '', '', '', '', '', NULL, '', '')
 *
 *
 * */
?>
