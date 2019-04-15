<?php
require 'functions.php';
require 'variables.php';
//require '/data/public_html/calculate/functions.php'; //Wildcard Functions
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if($sport=='sp' || $sport=='pp')
    $level=GetLevelJ($session);
else
    $level=GetLevel($session);
if($level==4) $level=1;

if(!ValidUser($session) || $level!=1)
{
    header("Location:index.php?error=1");
    exit();
}

$districts=$sport."districts";
$disttimes=$sport."disttimes";
$seeds=$sport."seeds";
$schtbl=GetSchoolsTable($sport);
$sql="SELECT id,school FROM $db_name.logins WHERE level='2' ORDER BY school";
$result=mysql_query($sql);
$allsids=array(); $i=0;
echo mysql_error();
while($row=mysql_fetch_array($result))
{
    $allschools[$i]=$row[school];
    $allsids[$i]=$row[id]; $i++;
}
$schedtbl=$sport."sched";
$fallyear=GetFallYear($sport);
//Check if this sport has a table of its participating schools and thus can save SID's:
$table=GetSchoolsTable($sport);
$sql2="USE $db_name";
$result2=mysql_query($sql2);
$sql2="SHOW TABLES LIKE '$table'";
$result2=mysql_query($sql2);
if(mysql_num_rows($result2)>0)
    $schoolsids=1;
else
    $schoolsids=0;
$sql2="USE $db_name2";
$result2=mysql_query($sql2);

if(preg_match("/so/",$sport))	//GET (UNIQUE) HOST DATES
{
    if(preg_match("/b/",$sport)) $boygirl="boys";
    else $boygirl="girls";
    $sql2="SELECT DISTINCT tourndate FROM $db_name2.sotourndates WHERE hostdate='x' AND $boygirl='x' ORDER BY tourndate,label";
    $result2=mysql_query($sql2);
    $sohostdates=array(); $i=0;
    while($row2=mysql_fetch_array($result2))
    {
        $sohostdates[$i]=$row2[tourndate];
        $i++;
    }
}
else if($sport=='ba') 	//GET HOST DATES
{
    $sql2="SELECT * FROM $db_name2.batourndates WHERE hostdate='x' ORDER BY tourndate,label";
    $result2=mysql_query($sql2);
    $bahostdates=array(); $i=0;
    while($row2=mysql_fetch_array($result2))
    {
        if($row2[labelonly]=='x') $showdate=$row2[label];
        else
        {
            $date=explode("-",$row2[tourndate]);
            $showdate=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
            $bahostyear=$date[0];
        }
        $bahostdates[$i]=$showdate;
        $i++;
    }
}

if($reset=="yes" && $distid && $distid!='')
{
    $sql="DELETE FROM $db_name.$schedtbl WHERE distid='$distid'";
    $result=mysql_query($sql);
    //echo "$sql<br>";
    $sql="DELETE FROM $db_name2.$seeds WHERE distid='$distid'";
    $result=mysql_query($sql);
    //echo "$sql<br>";
    $sql="UPDATE $db_name2.$districts SET seeded='',bracketed='' WHERE id='$distid'";
    $result=mysql_query($sql);
    //echo "$sql<br>";
}

if($distid && $statesave)	//SAVE CITY AND DATES
{
    //SPEECH - SAVE TWO SEPARATE DAYS
    if($sport=='sp')
    {
        for($i=0;$i<count($stateid);$i++)
        {
            $dates="$distyr[$i]-$distmo[$i]-$distday[$i]";
            $sql="UPDATE $districts SET dates='$dates',city='".addslashes($city)."' WHERE type='State' AND id='$stateid[$i]'";
            $result=mysql_query($sql);
        }
    }
    else
    {
        $dates="";
        for($i=0;$i<count($distmo);$i++)
        {
            if($distmo[$i]!='' && $distday[$i]!='')
                $dates.=$distyr[$i]."-".$distmo[$i]."-".$distday[$i]."/";
        }
        $dates=substr($dates,0,strlen($dates)-1);

        $sql="UPDATE $districts SET dates='$dates', city='".addslashes($city)."' WHERE id='$distid'";
        $result=mysql_query($sql);

    }
    if(mysql_error()) { echo mysql_error()."<br>$sql<br>"; exit(); }
}
if($save && $schch)
{
    $schools="";
    $sids="";
    for($i=0;$i<count($schch);$i++)
    {
        if($schch[$i]>0)
        {
            $cursch=GetSchoolName($schch[$i],$sport,$fallyear);
            $schools.=$cursch.", ";
            $sids.=$schch[$i].",";
        }
    }
    $schools=substr($schools,0,strlen($schools)-2);
    $schools=addslashes($schools);
    $sids=substr($sids,0,strlen($sids)-1);
    $sql="UPDATE $districts SET schools='$schools',sids='$sids' WHERE id='$distid'";
    $result=mysql_query($sql);
}
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
if($seeddist)
{
    $sql = "UPDATE $districts SET seeded='y' WHERE id='$distid'";
    $result = mysql_query($sql);

    if ($sport == "sb") {
        $sids = implode(",", $schch);
        $sql = "UPDATE $districts SET seeded='y',sids='$sid' WHERE id='$distid'";
        $result = mysql_query($sql);
        $index = 1;
        $sql = "DELETE FROM $seeds WHERE  distid='$distid'";
        $result = mysql_query($sql);
        foreach ($schch as $sid) {
            $point=GetPointAvg($sid,$sport,GetFallYear($sport));
            $sql = "INSERT INTO sbseeds(distid, sid, seed, ptavg) VALUES ($distid,$sid,$index,'$point');";
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
}
else if($brackets)	//save overridden brackets
{
    for($i=0;$i<count($sid);$i++)
    {
        $sql="UPDATE $seeds SET seed='$seed[$i]' WHERE sid='$sid[$i]' AND distid='$distid'";
        $result=mysql_query($sql);
    }
}
else if($save || $hiddensave)	//SAVE ALL CHANGES
{
    //check if district has been seeded (bracket sports only)
    if(IsBracketSport($sport))
    {
        $sql="SELECT * FROM $districts WHERE id='$distid'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        $seeded=$row[seeded];
        $bracketed=$row[bracketed];
        $type=$row[type];
    }
    $director=addslashes($director);
    $email=addslashes($email);
    $site=addslashes($site);
    $schools=addslashes($schools);
    if($schoolsids) //make list of schools & sids from dropdown lists(wildcard sports only)
    {
        $schools=""; $sids="";
        for($i=0;$i<count($schch);$i++)
        {
            if($schch[$i]!='0' && $schch[$i]!='')
            {
                $schools.=GetSchoolName($schch[$i],$sport,$fallyear).", ";
                $sids.=$schch[$i].",";
            }
        }
        $schools=substr($schools,0,strlen($schools)-2);
        $sids=substr($sids,0,strlen($sids)-1);
        $schools=addslashes($schools);
    }
    else
    {
        $schools="";
        for($i=0;$i<count($schch);$i++)
        {
            if($schch[$i]!='')
                $schools.=$schch[$i].", ";
        }
        $schools=substr($schools,0,strlen($schools)-2);
        $schools=addslashes($schools);
    }
    $teams=split(",",$schools);
    if(count($teams)>1)
        $teamcount=count($teams);
    if($type=="District Final" || $type=="Substate")
        $teamcount=2;
    if($sport=='sb') $gamecount=($teamcount*2);          //# of games in tournament: SB=Double Elim, other=
    else $gamecount=$teamcount-1;			  //Single Elim
    $hostschool=addslashes($hostschool);
    if($hostschool=="[Click to Choose Host]") $hostschool="";
    $dates=""; $gamedate="0000-00-00";
    for($i=0;$i<count($distmo);$i++)
    {
        if($distmo[$i]!='' && $distday[$i]!='')
        {
            $dates.=$distyr[$i]."-".$distmo[$i]."-".$distday[$i]."/";
            if(($type=="District Final" || $type=="Substate") && $i==0)
                $gamedate=$distyr[$i]."-".$distmo[$i]."-".$distday[$i];
        }
    }
    $dates=substr($dates,0,strlen($dates)-1);

    $sql="UPDATE $districts SET director='$director', email='$email',hostschool='$hostschool', hostid='$hostid', dates='$dates', site='$site',showdistinfo='$showdistinfo',showoffs='$showoffs'";
    if(IsTimeslotSport($sport)) $sql.=",teamcount='$teamcount'";
    if($sport=='pp' && $type=="State") $sql.=",city='".addslashes($city)."'";
    $sql2="DESCRIBE $districts";
    $result2=mysql_query($sql2);
    $timefield=0;
    while($row2=mysql_fetch_array($result2))	//IF THIS TABLE HAS A FIELD FOR time, UPDATE THAT FIELD
    {
        if($row2[0]=="time") $sql.=",time='$time'";
    }
    if(!IsWildcardSport($sport) || ($type!='District Final' && $type!='Substate' && $seeded!='y' && $bracketed!='y'))
    {
        //either not a wildcard sport OR a wildcard that is not seeded or bracketed and not a Dist Final
        $sql.=", schools='$schools'";
    }
    if($schoolsids && $seeded!='y' && $bracketed!='y' && $type!='District Final' && $type!='Substate')
    {
        //is a sport with a school table AND is not seeded, bracketed, or a district final
        $sql.=", sids='$sids'";
    }
    $sql.=" WHERE id='$distid'";
    $result=mysql_query($sql);
    //echo "$sql<br>$schoolsids<br>".mysql_error();
    if($copydates)       //APPLY SAME DATES TO ALL SITES OF THIS $type
    {
        $sql="UPDATE $districts SET dates='$dates' WHERE accept='' AND type='$type'";
        $result=mysql_query($sql);
        //echo "$sql<br>";
    }
    if($copytime)       //APPLY SAME TIME TO ALL SITES OF THIS $type
    {
        $sql="UPDATE $districts SET time='$time' WHERE accept='' AND type='$type'";
        $result=mysql_query($sql);
        //echo "$sql<br>";
    }
    //check that enough slots are in $disttimes
    if(IsTimeslotSport($sport))
    {
        $sql="SELECT * FROM $disttimes WHERE distid='$distid' $gamecount";
        $result=mysql_query($sql);
        $i=mysql_num_rows($result);
        while($i<$gamecount)
        {
            $sql="INSERT INTO $disttimes (distid,day) VALUES ('$distid',$gamedate)";
            $result=mysql_query($sql);
            $i++;
        }
        $sql="DELETE FROM $disttimes WHERE distid='$distid' AND gamenum>'$gamecount'";
        $result=mysql_query($sql);
    }	//END IF TIMESLOT SPORT

    if($oldhostid!=$hostid)	//new host--reset contract
    {
        $sql="UPDATE $districts SET post='',accept='',confirm='' WHERE id='$distid'";
        $result=mysql_query($sql);
    }
    if(true)	//sports with TIMES SLOTS
    {
        for($i=0;$i<count($disttimesid);$i++)
        {
            $curday=$year[$i]."-".$month[$i]."-".$day[$i];
            $curtime=$hour[$i].":".$min[$i]." ".$ampm[$i]." ".$timezone[$i];
            $curnotes=addslashes($notes[$i]);
            $curfield=addslashes($field[$i]);
            $curshowgamenum=addslashes($showgamenum[$i]);
            if($disttimesid[$i]==0)	//INSERT
            {
                if($sport=='sb')
                    $sql2="INSERT INTO $disttimes (distid,day,time,sbfield,gamenum,notes,showgamenum) VALUES ('$distid','$curday','$curtime','$curfield','$gamenum[$i]','$curnotes','$curshowgamenum')";
                else
                    $sql2="INSERT INTO $disttimes (distid,day,time,notes,gamenum,showgamenum) VALUES ('$distid','$curday','$curtime','$curnotes','$gamenum[$i]','$curshowgamenum')";
                $result2=mysql_query($sql2);
                $disttimesid[$i]=mysql_insert_id();
            }
            else			//UPDATE
            {
                if($sport=='sb')
                    $sql2="UPDATE $disttimes SET day='$curday',showgamenum='$curshowgamenum', notes='$curnotes',time='$curtime',sbfield='$curfield',gamenum='$gamenum[$i]' WHERE id='$disttimesid[$i]'";
                else
                    $sql2="UPDATE $disttimes SET day='$curday',showgamenum='$curshowgamenum',notes='$curnotes',time='$curtime',gamenum='$gamenum[$i]' WHERE id='$disttimesid[$i]'";
                $result2=mysql_query($sql2);
            }
            //check for these games in wildcard schedule, update dates
            $sql2="UPDATE $db_name.$schedtbl SET received='$curday' WHERE distid='$distid' AND gamenum='$gamenum[$i]'";
            $result2=mysql_query($sql2);

            $sql2="SELECT * FROM $districts WHERE id='$distid'";
            $result2=mysql_query($sql2);
            $row2=mysql_fetch_array($result2);
            if($sport=='ba' || (preg_match("/bb/",$sport) && $row2['class']=="A"))	//BASEBALL OR CLASS A BASKETBALL - CAN ASSIGN DIFFERENT HOST PER GAME
            {
                if($oldhostid[$i]!=$hostid[$i])      //new host--reset contract
                {
                    $sql="UPDATE $disttimes SET post='',accept='',confirm='' WHERE id='$disttimesid[$i]'";
                    $result=mysql_query($sql);
                }
                //ALSO UPDATE hostid,hostschool,site,director,email,oppid
                $hostschoolname[$i]=addslashes($hostschoolname[$i]);
                $hostdirector[$i]=addslashes($hostdirector[$i]);
                $hostemail[$i]=addslashes($hostemail[$i]);
                $hostsite[$i]=addslashes($hostsite[$i]);
                $sql2="UPDATE $disttimes SET hostid='$hostid[$i]',hostschool='$hostschoolname[$i]',site='$hostsite[$i]', director='$hostdirector[$i]',email='$hostemail[$i]' WHERE id='$disttimesid[$i]'";
                $result2=mysql_query($sql2);
            } //END IF CLASS A BASKETBALL
        }
    }//end if sport with time slots
}

echo $init_html;
if($sport=='sp' || $sport=='pp')
    echo GetHeaderJ($session,"jcontractadmin");
else
    echo GetHeader($session,"contractadmin");

echo "<br><form name=assignform method=post action=\"hostbyhost.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=hostch value=\"$hostch\">";
echo "<input type=hidden name=appch value=\"$appch\">";
echo "<select name=sport onchange=\"submit();\"><option value=''>Choose Sport/Activity</option>";
$sql="SHOW TABLES LIKE '%districts'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
    $temp=split("districts",$row[0]);
    $curact=$temp[0];
    if((($sport=='pp' || $sport=='sp') && ($curact=='sp' || $curact=='pp')) || (!($sport=='pp' || $sport=='sp')
            && !($curact=='sp' || $curact=='pp')))
    {
        echo "<option value=\"$curact\"";
        if($sport==$curact) echo " selected";
        echo ">".GetSportName($curact)."</option>";
    }
    $contractsports[$ix]=$curact;
    $ix++;
}
echo "</select>&nbsp;";
echo "<a class=small href=\"hostcontracts.php?session=$session&sport=$sport\">Main Menu</a><br>";

if($sport && $sport!='')
{
    $sportname=GetSportName($sport);
//print_r($sport); exit;
    if($sport=='vb')
        echo "<br><table class='nine' style=\"max-width:960px;\" cellspacing=0 cellpadding=5><caption><b>$sportname Sub-district Host Contracts:</b><br>";
    else
        echo "<br><table class='nine' style=\"max-width:960px;\" cellspacing=0 cellpadding=5><caption><b>$sportname District Host Contracts:</b><br>";
    echo "<a class=small href=\"hostreport.php?session=$session&sport=$sport\">View Host Contracts in Report Format</a>";
    echo "<hr>";
    //Choose Type
    if($sport=='bbb' || $sport=='bbg') $types=array("District","Subdistrict","District Final","State");
    else if($sport=='vb') $types=array("District","Subdistrict","District Final","State");
    //else if($sport=='vb') $types=array("District","Subdistrict","District Final","Substate","State");
    //else if(preg_match("/so/",$sport)) $types=array("District","Substate");
    else if($sport=='pp') $types=array("District","State");
    //else if($sport=='sog') $types=array("Subdistrict","District Final");
    //else if($sport=='sob') $types=array("Subdistrict","District Final");
    else if(preg_match("/so/",$sport)) $types=array("District","Subdistrict","District Final","State");
    else
    { $types=array("District","State");  }
    echo "<select name=type onchange=\"submit();\"><option value=''>Choose Type</option>";
    for($i=0;$i<count($types);$i++)
    {
        echo "<option";
        if($type==$types[$i]) echo " selected";
        echo ">$types[$i]</option>";
    }
    echo "</select>";
    if($type && $type!='' && ($type!='State' || $sport=='pp' || $sport=='ba'))
    {
        //Choose Class/District
        $sql="SELECT * FROM $districts WHERE class!='' AND type='$type' ORDER BY class,district";
        $result=mysql_query($sql);
        echo "<select name=distid onchange=\"submit();\"><option value=''>Class/Dist</option>";
        $distidfound=0;
        while($row=mysql_fetch_array($result))
        {
            echo "<option value=\"$row[id]\"";
            if($distid==$row[id])
            {
                $distidfound=1;
                echo " selected";
                $class=$row["class"];
                $district=$row[district];
                $showtimes=$row[showtimes];
                $showdistinfo=$row[showdistinfo];
                $showoffs=$row[showoffs];
            }
            echo ">$row[class]";
            if($row[district]!='') echo "-$row[district]";
            echo "</option>";
        }
        echo "</select><input type=submit name=go value=\"Go\"><br>";
        if($save || $hiddensave)
            echo "<font color=\"red\"><b>The information for this $type Site has been saved.</b></font>";
        echo "</caption>";
    }
    else if($type=="State" && $sport!='pp' && $sport!='ba')
    {
        $sql="SELECT * FROM $districts WHERE type='$type' ORDER BY class LIMIT 1";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        if(mysql_num_rows($result)>0)
        {
            $distidfound=1; $distid=$row[id];
        }
        if($statesave)
            echo "<div class='alert'>The information for this State Championship has been saved.</div>";
        echo "</caption>";
    }
    if($distidfound && $distid && $type=="State" && $sport!='pp')	//STATE (NON-PP) - JUST ASK FOR DATES AND HOST CITY
    {
        $sql="SELECT * FROM $districts WHERE id='$distid'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);

        echo "<tr align=left><td colspan=2><br><p><i>Please complete the following information in order for mileage calculations to be made on the schools' Reimbursement Forms for State Championships.</p></td></tr>";
        echo "<tr align=left><td><b>City:</b></td><td><input type=text name='city' value=\"$row[city]\" size=30>, Nebraska<br>Please enter the name of the <b>Host City</b> (NO state), for mileage calculation purposes</td></tr>";
        echo "<tr align=left valign=top><td><b>Date(s):</b></td><td>";
        $year=date("Y");
        $year0=$year-1;
        $year1=$year+1;
        if($sport=='sp')	//THURSDAY AND FRIDAY DATES
        {
            $sql="SELECT * FROM $districts WHERE type='State' ORDER BY id";
            $result=mysql_query($sql);
            $dx=0;
            while($row=mysql_fetch_array($result))
            {
                $days[$dx]=$row[dates]; $stateid[$dx]=$row[id]; $dx++;
            }
        }
        else
        {
            $days=split("/",$row[dates]);
            if($row[dates]=="") $days=array();
        }
        for($i=0;$i<count($days);$i++)
        {
            $curday=split("-",$days[$i]);
            if($sport=='sp')	//STATE ID's
                echo "<input type=\"hidden\" name=\"stateid[$i]\" value=\"$stateid[$i]\">";
            echo "<select name=\"distmo[$i]\"><option value=''>MM</option>";
            for($j=1;$j<=12;$j++)
            {
                if($j<10) $m="0".$j;
                else $m=$j;
                echo "<option";
                if($curday[1]==$m) echo " selected";
                echo ">$m</option>";
            }
            echo "</select>/<select name=\"distday[$i]\"><option value=''>DD</option>";
            for($j=1;$j<=31;$j++)
            {
                if($j<10) $d="0".$j;
                else $d=$j;
                echo "<option";
                if($curday[2]==$d) echo " selected";
                echo ">$d</option>";
            }
            echo "</select>/<select name=\"distyr[$i]\">";
            for($j=$year0;$j<=$year1;$j++)
            {
                echo "<option";
                if($curday[0]==$j) echo " selected";
                echo ">$j</option>";
            }
            echo "</select><br>";
        }
        $season=GetSeason($sport); $thisyr=date("Y"); $nextyr=$thisyr+1; $thismo=date("m");
        if($sport=='so' || $sport=='sog' || $sport=='sob') $maxdays=7;
        else if($sport=='cc' || $sport=='ccg' || $sport=='ccb') $maxdays=1;
        else if($sport=='sp') $maxdays=2;
        else if($sport=='ba') $maxdays=5;
        else $maxdays=3;
        if(count($days)<$maxdays)
        {
            while($i<$maxdays)
            {
                echo "<select name=\"distmo[$i]\"><option value=''>MM</option>";
                for($j=1;$j<=12;$j++)
                {
                    if($j<10) $m="0".$j;
                    else $m=$j;
                    echo "<option>$m</option>";
                }
                echo "</select>/<select name=\"distday[$i]\"><option value=''>DD</option>";
                for($j=1;$j<=31;$j++)
                {
                    if($j<10) $d="0".$j;
                    else $d=$j;
                    echo "<option>$d</option>";
                }
                echo "</select>/<select name=\"distyr[$i]\">";
                for($j=$year0;$j<=$year1;$j++)
                {
                    echo "<option";
                    if(($season=="Fall" && $j==$thisyr) || ($season!="Fall" && $thismo<6 && $j==$thisyr))
                        echo " selected";
                    else if($season!="Fall" && $j==$nextyr) echo " selected";
                    echo ">$j</option>";
                }
                echo "</select><br>";
                $i++;
            }
        }
        echo "<p>The nights for which schools can claim <b>lodging reimbursement</b> will include the dates listed above plus the night before Day 1.</td></tr>";
        echo "<tr align=center><td colspan=2>";
        if($sport!='ba') echo "<input type=hidden name='distid' id='distid' value='$distid'>";
        echo "<input type=submit name='statesave' value=\"Save\"></td></tr>";
    }
    else if($distidfound && $distid && $distid!='' && $type && $type!='')
    {
        //SHOW CURRENT INFORMATION FOR SELECTED DISTRICT/SITE:
        $sql="SELECT * FROM $districts WHERE id='$distid'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        if(!($sport=='ba' || (preg_match("/bb/",$sport) && $class=="A")))	//BA & CLASS A BBG/BBB: ASSIGNING HOST TO EACH GAME
        {
            //NON- CLASS A BASKETBALL/BASEBALL: PROCEED AS USUAL w/ ASSIGNING 1 HOST FOR WHOLE TOURNAMENT
            echo "<input type=hidden name=\"oldhostid\" value=\"$row[hostid]\">";
            if(!preg_match("/cc/",$sport) && !preg_match("/tr/",$sport) && $sport!='sp' && $sport!='pp')
                echo "<tr align=left><td colspan=2><a class=small href=\"assign".$sport.".php?session=$session&type=$type&distid=$distid\">$sportname Officials Assignments for this $type &rarr;</a></td></tr>";
            else if($sport=='pp' || $sport=='sp')
            {
                if($type=="State") $sendsport=$sport."-state";
                else $sendsport=$sport;
                echo "<tr align=left><td colspan=2><a class=small href=\"assignplay2.php?session=$session&sport=$sendsport&type=$type&distid=$distid\">$sportname Judges Assignments for this $type &rarr;</a></td></tr>";
            }
            echo "<tr align=left><td><b>Host School:</b></td><td>";
            if($row[hostid]=='' || $row[hostid]=='0')
            {
                $hostschool="[Click to Choose Host]";
                $hostid=0;
            }
            else
            {
                $hostschool=$row[hostschool];
                $hostid=$row[hostid];
            }
            echo "<input type=hidden name=\"hostid\" value=\"$hostid\">";
            echo "<input type=text name=\"hostschool\" value=\"$hostschool\" onClick=\"window.open('hostpick.php?sport=$sport&distid=$distid&session=$session&hostch=$hostch&appch=$appch','hostpick','resizable=yes,scrollbars=yes');\" readOnly=true class=tiny size=25>";
            if($row[hostschool]!="[Click to Choose Host]")
                echo " (Click to Edit)";
            echo "</td></tr>";
            //Contract Status:
            if($hostid!=0 && $type!='District Final' && $type!='Substate')
            {
                echo "<tr valign=top align=left><td><b>Contract Status:</b></td><td>";
                if($row[post]!='y')
                    echo "Not Posted.&nbsp;&nbsp;<a class=small href=\"posthost.php?type=$type&sport=$sport&session=$session&distid=$row[id]\">Post Contract to Host</a>";
                else	//POSTED
                {
                    echo "Posted.<br>";
                    if($row[accept]=='')
                        echo "No Response Yet.";
                    else
                    {
                        if($row[accept]=='n')	//DECLINED
                            echo "The Host has DECLINED this contract.";
                        else if($row[accept]=='y')	//ACCEPTED
                            echo "The Host has ACCEPTED this contract.";
                        if($row[confirm]=='')
                        {
                            echo "<br><a class=small target=new href=\"hostcontract.php?sport=$sport&session=$session&distid=$row[id]\">";
                            if($row[accept]=='y') echo "Confirm/Reject";
                            else echo "Acknowledge";
                            echo " this Contract</a><br>";
                        }
                        else if($row[confirm]=='y')	//CONFIRMED/ACKNOWLEDGED
                        {
                            if($row[accept]=='y')
                                echo "<br>The NSAA has CONFIRMED this contract.<br>";
                            else
                                echo "<br>The NSAA has ACKNOWLEDGED this contract.<br>";
                            echo "<a class=small target=new href=\"hostcontract.php?session=$session&sport=$sport&distid=$row[id]\">View this Contract</a>";
                        }
                        else				//REJECTED
                        {
                            echo "<br>The NSAA has REJECTED this contract.<br>";
                            echo "<a class=small target=new href=\"hostcontract.php?session=$session&sport=$sport&distid=$row[id]\">View this Contract</a>";
                        }
                    } //END IF ACCEPTED
                } //END IF POSTED
                echo "</td></tr>";
            } //END CONTRACT STATUS
            echo "<tr align=left><td><b>District Director:</b></td>";
            echo "<td><input type=text class=tiny name=director size=20 value=\"$row[director]\"></td></tr>";
            echo "<tr align=left><td><b>Director's E-mail:</b></td>";
            echo "<td><input type=text class=tiny name=email size=20 value=\"$row[email]\"></td></tr>";
            echo "<tr valign=top align=left><td><b>Date(s):</b></td><td>";

            /**********************************
            The host contracts work differently depending on the sport/activity.
            On some of them, the NSAA chooses the date.
            On others, the NSAA chooses multiple dates and the hosts picks the one they'll use.
            This can change from year to year, and often does.
             ***********************************/

            /****** SPEECH & CLASS B/D PLAY: Host selects from dates provided by NSAA ******/
            if(($sport=='sp' || ($sport=='pp' && (preg_match("/D/",$class) || $class=="B" || $class=="A" || $class=="C2"))) && $row[accept]!='y' && $type!="State")
            {
                if($row[dates]=="") echo "<font style=\"color:red\">";
                echo "Please indicate below the dates the district host may choose from on their contract to host:<br>Do NOT post this contract to the host until these dates are entered,<br>as they need to be able to choose from the dates you enter.<br>";
                if($row[dates]=="") echo "</font>";
            }
            else if($sport=='pp' && $row[accept]!='y')	/**** PP non-Class B:  NSAA selects date ****/
                echo "Please indicate the date on which this host should hold this contest.<br>";
            else if(($sport=='pp' || $sport=='sp') && $row[accept]=='y')	//Host has already indicated a date
                echo "It has been indicated that the host will hold this contest on the following date:<br>";
            else if($sport=='ba' && $row[accept]!='y')	/**** BASEBALL ****/
            {
                echo "<font style=\"color:blue\">The host will indicate the date(s) they will host this tournament on their contract.<br>They will be able to choose from: ";
                $hostdates="";
                for($i=0;$i<count($bahostdates);$i++)
                {
                    $hostdates.=$bahostdates[$i].", ";
                }
                echo $hostdates."$bahostyear.<br>Once they Accept, the date(s) they chose will show below:<br></font>";
            }
            else if($sport=='ba' && $row[accept]=='y')
                echo "<font style=\"color:blue\">The host has indicated the following date(s) on their contract<br>as the date(s) on which they will host this tournament:<br></font>";
            else if(($sport=='sog' || $sport=='sob') && $row[accept]!='y')        /**** SOCCER ****/
            {
                echo "<font style=\"color:blue\">The host will indicate the date(s) they will host this tournament on their contract.<br>They will be able to choose from: ";
                $hostdates="";
                for($i=0;$i<count($sohostdates);$i++)
                {
                    $cur=split("-",$sohostdates[$i]);
                    $hostdates.=date("F j",mktime(0,0,0,$cur[1],$cur[2],$cur[0])).", ";
                }
                echo $hostdates."$cur[0].<br>Once they Accept, the date(s) they chose will show below:<br></font>";
            }
            else if(($sport=='sog' || $sport=='sob') && $row[accept]=='y')
                echo "<font style=\"color:blue\">The host has indicated the following date(s) on their contract<br>as the date(s) on which they will host this tournament:<br></font>";
            else if(preg_match("/bb/",$sport) && $row[accept]!='y')	/**** BASKETBALL ****/
                echo "<font style=\"color:blue\">The NSAA must enter the date(s) for this tournament below<br>so that the host will see these dates on their contract.</font><br>";
            else if(preg_match("/bb/",$sport) && $row[accept]=='y')
                echo "<font style=\"color:blue\">The host has already accepted this contract.<br>Please do not change the dates below without notifying the host.</font><br>";
            else if($sport=='cc')				/**** CROSS-COUNTRY ****/
                echo "<font style=\"color:blue\">You can change the date below for ALL the Cross-Country districts<br>by changing it at the bottom of the actual contract.  To edit the contract, <a class=small target=\"_blank\" href=\"hostcontract_cc.php?session=$session&edit=1\">Click Here</a>.</font><br>";
            else if(preg_match("/go/",$sport) && $row[accept]!='y')	/**** GOLF ****/
                echo "<font style=\"color:blue\">The dates entered below will be the dates the host can choose from<br>on their contract to host this tournament.<br>To edit these dates for ALL districts at once, <a class=small target=\"_blank\" href=\"hostcontract_$sport.php?session=$session&edit=1\">Click Here</a> (scroll to the bottom).</font><br>";
            else if(preg_match("/go/",$sport))
                echO "<font style=\"color:blue\">The date below was chosen by the host on their contract.</font><br>";
            $year=date("Y");
            $year0=$year-1;
            $year1=$year+1;
            $days=split("/",$row[dates]);
            if($row[dates]=="") $days=array();
            for($i=0;$i<count($days);$i++)
            {
                $curday=split("-",$days[$i]);
                echo "<select name=\"distmo[$i]\"><option value=''>MM</option>";
                for($j=1;$j<=12;$j++)
                {
                    if($j<10) $m="0".$j;
                    else $m=$j;
                    echo "<option";
                    if($curday[1]==$m) echo " selected";
                    echo ">$m</option>";
                }
                echo "</select>/<select name=\"distday[$i]\"><option value=''>DD</option>";
                for($j=1;$j<=31;$j++)
                {
                    if($j<10) $d="0".$j;
                    else $d=$j;
                    echo "<option";
                    if($curday[2]==$d) echo " selected";
                    echo ">$d</option>";
                }
                echo "</select>/<select name=\"distyr[$i]\">";
                for($j=$year0;$j<=$year1;$j++)
                {
                    echo "<option";
                    if($curday[0]==$j) echo " selected";
                    echo ">$j</option>";
                }
                echo "</select><br>";
            }
            if($sport=='vb') $maxdays=4;
            else if($sport=='sb' || $sport=='bbb' || $sport=='bbg' || $sport=='ba') $maxdays=3;
            else if($sport=='wr' || $sport=='go_g' || $sport=='go_b') $maxdays=2;
            else if($sport=='cc') $maxdays=1;
            else if($sport=='sob' || $sport=='sog') $maxdays=5;
            else if(($sport=='pp' && $type=="State") || ($sport=='sp' && $row[accept]=='y') || ((($class=="A" || $class=="D1" || $class=="D2" || $class=="B") && $sport=='pp') && $row[accept]=='y') || $sport=='tr' || preg_match("/te/",$sport))
                $maxdays=1;
            else if(($sport=='sp' || $sport=='pp') && $row[accept]=='') //pp Class B: host choose b/t 2 dates
                $maxdays=5;
            else $maxdays=3;
            if($type=="District Final" || $type=="Substate") $maxdays=1;
            $season=GetSeason($sport); $thisyr=date("Y"); $nextyr=$thisyr+1; $thismo=date("m");
            if(count($days)<$maxdays)
            {
                while($i<$maxdays)
                {
                    echo "<select name=\"distmo[$i]\"><option value=''>MM</option>";
                    for($j=1;$j<=12;$j++)
                    {
                        if($j<10) $m="0".$j;
                        else $m=$j;
                        echo "<option>$m</option>";
                    }
                    echo "</select>/<select name=\"distday[$i]\"><option value=''>DD</option>";
                    for($j=1;$j<=31;$j++)
                    {
                        if($j<10) $d="0".$j;
                        else $d=$j;
                        echo "<option>$d</option>";
                    }
                    echo "</select>/<select name=\"distyr[$i]\">";
                    for($j=$year0;$j<=$year1;$j++)
                    {
                        echo "<option";
                        if(($season=="Fall" && $j==$thisyr) || ($season!="Fall" && $thismo<6 && $j==$thisyr))
                            echo " selected";
                        else if($season!="Fall" && $j==$nextyr) echo " selected";
                        echo ">$j</option>";
                    }
                    echo "</select><br>";
                    $i++;
                }
            }
            /*** ADDED 1/14/14: Check box to change ALL dates to the selected ones, on same $type (District, etc) ***/
            echo "<p style=\"color:#0000ff;\"><input type=checkbox name=\"copydates\" value=\"x\"> <b>Check here to save these <u>date(s)</u> to ALL $type sites.</b><br />(Will only save to sites for which a host contract has NOT yet been accepted)</p>";
            echo "</td></tr>";
            if($sport=='sp')
            {
                echo "<tr valign=top align=left><td><b>Time:</b></td>";
                echo "<td><input type=text size=8 name=time value=\"$row[time]\">";
                echo " (Note on contract says: All district speech contest must start no later than 12:00 noon.)";
                /*** ADDED 1/14/14: Check box to change ALL times, on same $type (District, etc) ***/
                echo "<p style=\"color:#0000ff;\"><input type=checkbox name=\"copytime\" value=\"x\"> <b>Check here to save this <u>time</u> to ALL $type sites.</b><br />(Will only save to sites for which a host contract has NOT yet been accepted)</p>";
                echo "</td></tr>";
            }
            echo "<tr align=left><td><b>Site:</b></td>";
            echo "<td><input type=text class=tiny name=site size=40 value=\"$row[site]\"></td></tr>";
        } //END IF NOT CLASS A BASKETBALL
        else
        {
            echo "<tr align=left><td colspan=2><a class=small href=\"assign".$sport.".php?session=$session&type=$type&distid=$distid\">$sportname Officials Assignments for this $type &rarr;</a></td></tr>";
            echo "<tr align=left><td colspan=2><p>Please select the <b>Schools</b> assigned to this $type below and click \"Save\" at the bottom of this screen. These schools will then show up in the dropdown lists for the Home and Away opponents for each game under \"Time Slots.\"</p></td></tr>";
        }  //END IF CLASS A BASKETBALL
        if(!IsWildcardSport($sport) && $type!='State')
        {
            echo "<tr align=left valign=top><td colspan=2><b>Schools:</b></td></tr>";
            echo "<tr align=center><td colspan=2><table><tr valign=top align=left><td>";
            if($schoolsids)
                $table="$db_name.".$table;
            else
                $table="$db_name.headers";
            $sql2="USE $db_name2";
            $result2=mysql_query($sql2);
            $sql2="SELECT * FROM $table";
            if($sport=='sp') $sql2.=" WHERE class='$class'";
            $sql2.=" ORDER BY school";
            $result2=mysql_query($sql2);
            $schlist=array(); $i=0;
            while($row2=mysql_fetch_array($result2))
            {
                $schlist[school][$i]=$row2[school];
                if($schoolsids) $schlist[sid][$i]=$row2[sid];
                $i++;
            }
            $schs=split(",",$row[schools]);
            if($schoolsids) $sids=split(",",$row[sids]);
            if($sport=="cc" || $sport=="ccg" || $sport=="ccb") $max=26;
            //else if($sport=="tr") $max=12;
            else if(($sport=="trg" || $sport=="trb") && $class=='D') $max=12;
            else if(preg_match("/go/",$sport)) $max=15;
            else $max=10;
            $i=0;
            for($i=0;$i<count($schs);$i++)
            {
                $schs[$i]=trim($schs[$i]);
                if($i==($max/2)) echo "</td><td>";
                echo "<select name=\"schch[$i]\"><option value=''>~</option>";
                for($j=0;$j<count($schlist[school]);$j++)
                {
                    echo "<option";
                    if($schoolsids)
                    {
                        echo " value=\"".$schlist[sid][$j]."\"";
                        if($schs[$i]==$schlist[school][$j] || $sids[$i]==$schlist[sid][$j]) echo " selected";
                    }
                    else
                    {
                        if($schs[$i]==$schlist[school][$j]) echo " selected";
                    }
                    echo ">".$schlist[school][$j]."</option>";
                }
                echo "</select><br>";
            }
            while($i<$max)
            {
                if($i==($max/2)) echo "</td><td>";
                echo "<select name=\"schch[$i]\"><option value=''>~</option>";
                for($j=0;$j<count($schlist[school]);$j++)
                {
                    echo "<option";
                    if($schoolsids) echo " value=\"".$schlist[sid][$j]."\"";
                    echo ">".$schlist[school][$j]."</option>";
                }
                echo "</select><br>";
                $i++;
            }
            echo "</td></tr></table></td></tr>";
            //echo "<br>Make sure to enter the schools with commas between the names, like \"Adams Central, Omaha North, etc.\"";
            //echo "</td>";
            //echo "<td><textarea class=small name=schools rows=4 cols=50>$row[schools]</textarea></td></tr>";
        }
        else if(IsWildcardSport($sport) && !IsBracketSport($sport))	//wildcard sport but NO brackets
        {
            echo "<tr align=left valign=top><td colspan=2><b>Schools</b>:</td></td></tr>";
            echo "<tr align=center><td colspan=2>";
            if($sport=='wr')
                echo "<table><tr align=left valign=top><td>";
            $sids=split(",",$row[sids]);
            for($i=0;$i<count($sids);$i++)
            {
                if($sport=='wr' && $i==9) echo "</td><td>";
                $cursid=trim($sids[$i]);
                $sql2="SELECT * FROM $db_name.$schtbl WHERE outofstate!='1' AND class='$class' ORDER BY school";
                $result2=mysql_query($sql2);
                echo "<select name=\"schch[$i]\"><option value='0'>~</option>";
                while($row2=mysql_fetch_array($result2))
                {
                    echo "<option value=\"$row2[sid]\"";
                    if($row2[sid]==$cursid) echo " selected";
                    echo ">$row2[school]</option>";
                }
                echo "</select><br>";
            }
            if($sport=='sb' || $sport=='ba' || preg_match("/so/",$sport)) $max=7;
            else if(preg_match("/bb/",$sport)) $max=6;
            else if($sport=='wr') $max=22;
            while($i<$max)
            {
                if($sport=='wr' && $i==9) echo "</td><td>";
                $sql2="SELECT * FROM $db_name.$schtbl WHERE outofstate!='1' AND class='$class' ORDER BY school";
                $result2=mysql_query($sql2);
                echo "<select name=\"schch[$i]\"><option value='0'>~</option>";
                while($row2=mysql_fetch_array($result2))
                {
                    echo "<option value=\"$row2[sid]\">$row2[school]</option>";
                }
                echo "</select><br>";
                $i++;
            }
            if($sport=='wr') echo "</td></tr></table>";
            echo "</td></tr>";
        }
        else if(IsBracketSport($sport))	//bracket sports
        {
            echo "<tr align=left valign=top><td><b>Schools:</b>";
            if($row[seeded]=='y' && $row[bracketed]!='y' && $type!='District Final' && $type!='Substate') //SEEDED district
            {
                echo "</td><td><table style=\"width:400px;\"><tr align=left><td colspan=3><i>To freeze wildcard averages, be sure to click Save Seeds. Once saved, you can then continue to enter scores without these averages changing on the bracket.</i></td></tr>";
                echo "<tr align=left><td><b>School:</b></td><td><b>Point Avg:</b></td><td><b>Seed:</b></td></tr>";
                $sql2="SELECT * FROM $seeds WHERE distid='$distid'";
                $result2=mysql_query($sql2);
                if(mysql_num_rows($result2)==0)
                    GetSeeds($sport,$distid);	//make sure seeds are in seeds table
                $sql2="SELECT * FROM $seeds WHERE distid='$distid' ORDER BY seed";
                $result2=mysql_query($sql2);
                $year=GetFallYear($sport);
                $ix=0;
                while($row2=mysql_fetch_array($result2))
                {
                    echo "<tr align=left><td>";
                    echo "<select name=\"sid[$ix]\"><option value='0'>~</option>";
                    $sql3="SELECT * FROM $db_name.$schtbl WHERE outofstate!='1' AND class='$class' ORDER BY school";
                    $result3=mysql_query($sql3);
                    while($row3=mysql_fetch_array($result3))
                    {
                        echo "<option value=\"$row3[sid]\"";
                        if($row3[sid]==$row2[sid]) echo " selected";
                        echo ">$row3[school]</option>";
                    }
                    echo "</select></td>";
                    echo "<td>".GetPointAvg($row2[sid],$sport,$year)."</td>";
                    echo "<td><select name=\"seed[$ix]\">";
                    for($i=1;$i<=mysql_num_rows($result2);$i++)
                    {
                        echo "<option";
                        if($i==$row2[seed]) echo " selected";
                        echo ">$i</option>";
                    }
                    echo "</select></td></tr>";
                    $sids[$ix]=$row2[sid];
                    $ix++;
                }
                echo "<tr align=right><td colspan=3>If necessary, adjust these seeds and click <input type=submit name=brackets value=\"Save Seeds\"></td></tr>";
                echo "</table></td></tr>";
                echo "<tr align=left><td>&nbsp;</td><td>Once the seeds are correct and the dates below are correct, click:<br><a class=small href=\"postbrackets.php?session=$session&sport=$sport&distid=$distid\">Post District Games to Wildcard Program (Create Bracket)</a></td></tr>";
            }
            else if($row[bracketed]=='y' && $type!='District Final' && $type!='Substate')	//already bracketed too; show non-editable list
            {
                echo "</td><td><table>";
                echo "<tr align=left><td><b>School</b></td><td><b>Point Avg</b></td><td><b>Seed</b></td></tr>";
                $sql2="SELECT * FROM $seeds WHERE distid='$distid' ORDER BY seed";
                $result2=mysql_query($sql2);
                while($row2=mysql_fetch_array($result2))
                {
                    echo "<tr align=left><td>".GetSchoolName($row2[sid],$sport,$fallyear)."</td>";
                    echo "<td>".GetPointAvg($row2[sid],$sport,$fallyear)."</td>";
                    echo "<td>$row2[seed]</td></tr>";
                }
                echo "<tr align=left><td colspan=3><br><a class=small href=\"".$sport."brackets.php?distid=$distid\" target=new>Click Here for $type $class-$district Bracket</a></td></tr>";
                echo "</table></td></tr>";
            }
            else if($type!='District Final' && $type!='Substate')	//not seeded yet
            {
                echo "</td><td>";
                if($sport=='wr') echo "<table><tr align=left><td>";
                $sids=split(",",$row[sids]);
                for($i=0;$i<count($sids);$i++)
                {
                    if($sport=='wr' && $i==9) echo "</td><td>";
                    $cursid=trim($sids[$i]);
                    $sql2="SELECT * FROM $db_name.$schtbl WHERE outofstate!='1' AND class='$class' ORDER BY school";
                    $result2=mysql_query($sql2);
                    echo "<select name=\"schch[$i]\"><option value='0'>~</option>";
                    while($row2=mysql_fetch_array($result2))
                    {
                        echo "<option value=\"$row2[sid]\"";
                        if($row2[sid]==$cursid) echo " selected";
                        echo ">$row2[school]</option>";
                    }
                    echo "</select><br>";
                }
                //code by robin
                if(preg_match("/bb/",$sport) || $sport=='vb') $max=6;
                else if( $sport=='ba' || preg_match("/so/",$sport)|| $sport=='sb') $max=7;
//                else if($sport=='sb' ) $max=$row[teamcount];
                else if($sport=='wr') $max=18;
                while($i<$max)
                {
                    if($sport=='wr' && $i==9) echo "</td><td>";
                    echo "<select name=\"schch[$i]\"><option value='0'>~</option>";
                    $sql2="SELECT * FROM $db_name.$schtbl WHERE outofstate!='1' AND class='$class' ORDER BY school";
                    $result2=mysql_query($sql2);
                    while($row2=mysql_fetch_array($result2))
                    {
                        echo "<option value=\"$row2[sid]\">$row2[school]</option>";
                    }
                    echo "</select><br>";
                    $i++;
                }
                if($sport=='wr') echo "</td></tr></table>";
                echo "</td></tr>";
                if(count($sids)>1 ||$sport=="sb")
                    echo "<tr align=left><td>&nbsp;</td><td><font style=\"color:blue\"><b>When you are ready to seed this $type, click: <input style=\"font-size:12pt;font-weight:bold\" onClick=\"return confirm('Are you sure you want to seed this $type at this time?  You will not be able to undo this action. Also: Make sure you haved SAVED the schools in this district BEFORE seeding it!!');\" type=submit name=seeddist value=\"Seed this District!\"></td></tr>";
            }
            else	//District Final: show opponents in final game of Subdistrict Bracket
            {
                echo "</td><td>$row[schools]";
                if($type=="Substate") echo " <a href=\"substatebrackets.php?session=$session&sport=$sport\">Go to $type Game Info</a>";
                echo "</td></tr>";
            }
            if($row[seeded]=='y')
            {
                //give them option to reset bracket/seeds
                echo "<tr align=left><td>&nbsp;</td><td><div style=\"width:350px\" class=alert><p><b>Make a Mistake?</b></p>";
                echo "<p>To RESET the seeding and/or bracket for this district, <a class=small href=\"hostbyhost.php?sport=$sport&distid=$distid&class=$class&type=$typ&reset=yes&session=$session\">Click Here</a>.</p></div></td></tr>";
            }
        }
        else if($type=="State" && $sport=='pp')
        {
            echo "<tr align=left><td><b>City:</b></td><td><input type=text size=25 name=\"city\" value=\"$row[city]\">, Nebraska<br>Please enter the name of the <b>Host City</b> (NO state), for mileage calculation purposes</td></tr>";
            echo "<tr align=left><td><b>Judges' Reporting Time:</b></td><td><input type=text size=10 name=\"time\" value=\"$row[time]\"></td></tr>";
        }
        if(IsTimeslotSport($sport))	//TIME SLOTS
        {
            if($sport=='sb' || $sport=='vb' || $sport=='fb' || $sport=='go_g' || $sport=='gog' || preg_match("/cc/",$sport) || $sport=='te_b' || $sport=='teb')
            {
                $central="CT"; $mtn="MT";
            }
            else if($sport=='ba')
            {
                $central="CT"; $mtn="MT";
            }
            else
            {
                $central="CT"; $mtn="MT";
            }
            if($type=="Substate" || $type=="District Final")
                echo "<tr align=left><td colspan=2><b>TIME SLOT:</b></td></tr>";
            else
            {
                echo "<tr align=left><td colspan=2><b>Time Slots:</b> <i>Number of time slots will equal the number of schools listed above ";
                if($sport=='sb') echo "multiplied by 2, minus 1 (double elimination).";
                else echo "minus 1 (single elimination).";
                echo "</i></td></tr>";
            }
            $temp=split(",",$row[schools]);
            if(count($temp)>1)
                $teamcount=count($temp);       //# of teams in tournament
            else $teamcount=$row[teamcount];
            if($type!='District Final' && $type!='Substate')
                echo "<tr align=left><td colspan=2><b>IF THE SPECIFIC SCHOOLS ARE UNKNOWN BUT YOU ARE READY TO ENTER TIME SLOTS, please enter the NUMBER OF TEAMS IN THIS ".strtoupper($type).": <input type=text name=\"teamcount\" value=\"$teamcount\" size=2></td></tr>";
            if($sport=='sb' ) $gamecount=($teamcount*2)-1;		//# of games in tournament
            else $gamecount=$teamcount-1;
            if($sport=="sb"){
                $sql2="SELECT * FROM $disttimes WHERE distid='$distid' ORDER BY gamenum ";
                $result2=mysql_query($sql2);
                if (mysql_num_rows($result2)>13){

                }
            }
            $sql2="SELECT * FROM $disttimes WHERE distid='$distid' ORDER BY gamenum ";
            $result2=mysql_query($sql2);
            $ix=0;
            if(mysql_num_rows($result2)>0)
            {
                echo "<tr align=center><td colspan=2><table cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#808080 1px solid;\">";
                if($sport=='ba' || preg_match("/bb/",$sport)) $contest="Game";
                else $contest="Match";
                echo "<tr align=center><td><b>$contest</b></td><td><b>$contest Label<br>on Bracket:</b><br>(Replaces \"$contest X\"<br>on bracket if filled in)</td>";
                if($sport=='ba' || (preg_match("/bb/",$sport) && $class=="A")) 	//CLASS A BASKETBALL OR BASEBALL - HOST INFO PER GAME
                {
                    echo "<td><b>Date/Time</b></td><td><b>Note</b><br>(will show on Bracket)</td><td><b>HOST SCHOOL</b></td><td><b>Opposing Teams</b></td>";
                }
                else
                {
                    echo "<td><b>Date</b></td><td><b>Time</b></td><td><b>Note</b><br>(will show on Bracket)</td>";
                    if($sport=='sb') echo "<td><b>Field</b></td>";
                }
                echo "</tr>";
            }
            $blankdays=0;
            while($row2=mysql_fetch_array($result2))
            {
                $day=split("-",$row2[day]);
                echo "<input type=hidden name=\"disttimesid[$ix]\" value=\"$row2[id]\">";
                echo "<tr align=left>";
                $match=$ix+1;
                echo "<td align=center><b>$match</b><input type=hidden name=\"gamenum[$ix]\" value=\"$match\"></td><td><input type=\"text\" name=\"showgamenum[$ix]\" value=\"$row2[showgamenum]\" size=\"15\"></td>";
                echo "<td><select name=\"month[$ix]\"><option value=''>MM</option>";
                for($i=1;$i<=12;$i++)
                {
                    if($i<10) $m="0".$i;
                    else $m=$i;
                    echo "<option";
                    if($day[1]==$m || ($day[1]=='00' && $curday[1]==$m)) echo " selected";
                    echo ">$m</option>";
                }
                echo "</select>/<select name=\"day[$ix]\"><option value=''>DD</option>";
                for($i=1;$i<=31;$i++)
                {
                    if($i<10) $d="0".$i;
                    else $d=$i;
                    echo "<option";
                    if($day[2]==$d || ($day[2]=='00' && $curday[2]==$d)) echo " selected";
                    echo ">$d</option>";
                }
                echo "</select>/<select name=\"year[$ix]\"><option value=''>YYYY</option>";
                $curyr=date("Y");
                for($i=$curyr-1;$i<=$curyr+1;$i++)
                {
                    echo "<option";
                    if($day[0]==$i || ($day[0]=='0000' && $curday[0]==$i)) echo " selected";
                    echo ">$i</option>";
                }
                echo "</select>";
                if($row2[day]=='0000-00-00')
                {
                    echo " *"; $blankdays++;
                }
                //CHECK IF THIS IS DIFFERENT IN THE SCORE LIST
                if($sport=='vb')
                {
                    $sql3="SELECT * FROM $db_name.".$sport."sched WHERE distid='$row2[distid]' AND gamenum='$row2[gamenum]'";
                    $result3=mysql_query($sql3);
                    if($row3=mysql_fetch_array($result3))
                    {
                        if($row3[received]!=$row2[day])
                            echo "<p style=\"color:red\">$row3[received]/$row2[day] <b>ERROR: Date doesn't match date on wildcard schedule.</b></p>";
                    }
                }
                if($sport=='ba' || (preg_match("/bb/",$sport) && $class=="A")) //CLASS A BASKETBALL/BASEBALL
                    echo "<br>";
                else echo "</td><td>";
                $time=split("[: ]",$row2[time]);
                echo "<input type=text class=tiny maxlength=2 size=3 name=\"hour[$ix]\" value=\"$time[0]\">:";
                echo "<input type=text class=tiny maxlength=2 size=3 name=\"min[$ix]\" value=\"$time[1]\">";
                echo "<select name=\"ampm[$ix]\"><option";
                if($time[2]=="PM") echo " selected";
                echo ">PM</option><option";
                if($time[2]=="AM") echo " selected";
                echo ">AM</option></select>";
                echo "<select name=\"timezone[$ix]\"><option";
                if($time[3]==$central || preg_match("/C/",$time[3])) echo " selected";
                echo ">$central</option><option";
                if($time[3]==$mtn || preg_match("/M/",$time[3])) echo " selected";
                echo ">$mtn</option></select>";
                echo "</td>";
                echo "<td><input type=text size=20 name=\"notes[$ix]\" value=\"$row2[notes]\"></td>";
                if($sport=='sb')	//SOFTBALL: FIELD
                    echo "<td><input type=text class=tiny size=6 name=\"field[$ix]\" value=\"$row2[sbfield]\"></td>";
                else if($sport=='ba' || (preg_match("/bb/",$sport) && $class=="A"))	//CLASS A BASKETBALL/BASEBALL
                {
                    $curhostschool="";
                    echo "<td><input type=hidden name=\"oldhostid[$ix]\" value=\"$row2[hostid]\"><select name=\"hostid[$ix]\"><option value=\"0\">Select Host School</option>";
                    //CHOOSE FROM sids FOR THIS DISTRICT for HOME/AWAY teams
                    for($j=0;$j<count($allsids);$j++)
                    {
                        echo "<option value=\"$allsids[$j]\"";
                        if($row2[hostid]==$allsids[$j])
                        {
                            echo " selected";
                            $curhostschool=$allschools[$j];
                        }
                        echo ">$allschools[$j]</option>";
                    }
                    echo "</select><input type=hidden name=\"hostschoolname[$ix]\" value=\"$curhostschool\">";
                    //HOST INFORMATION:
                    echo "<br>Site: <input type=text name=\"hostsite[$ix]\" value=\"$row2[site]\" size=25><br>
			Director: <input type=text name=\"hostdirector[$ix]\" value=\"$row2[director]\" size=20><br>
			E-mail: <input type=text name=\"hostemail[$ix]\" value=\"$row2[email]\" size=20><p style=\"font-size:90%;\">";
                    //CONTRACT STATUS
                    if($row2[hostid]>0 && $row2[post]!='y')
                        echo "Not Posted.&nbsp;&nbsp;<a class=small href=\"posthost.php?type=$type&sport=$sport&session=$session&distid=$distid&disttimesid=$row2[id]\">Post Contract to Host</a>";
                    else if($row2[hostid]>0)        //POSTED
                    {
                        echo "Posted.<br>";
                        if($row2[accept]=='')
                            echo "No Response Yet. <a class=small target=\"_blank\" href=\"hostcontract.php?sport=$sport&session=$session&distid=$distid&disttimesid=$row2[id]\">View Contract</a>";
                        else
                        {
                            if($row2[accept]=='n') //DECLINED
                                echo "The Host has DECLINED this contract.";
                            else if($row2[accept]=='y')    //ACCEPTED
                                echo "The Host has ACCEPTED this contract.";
                            if($row2[confirm]=='')
                            {
                                echo "<br><a class=small target=new href=\"hostcontract.php?sport=$sport&session=$session&distid=$distid&disttimesid=$row2[id]\">";
                                if($row2[accept]=='y') echo "Confirm/Reject";
                                else echo "Acknowledge";
                                echo " this Contract</a><br>";
                            }
                            else if($row2[confirm]=='y')   //CONFIRMED/ACKNOWLEDGED
                            {
                                if($row2[accept]=='y')
                                    echo "<br>The NSAA has CONFIRMED this contract.<br>";
                                else
                                    echo "<br>The NSAA has ACKNOWLEDGED this contract.<br>";
                                echo "<a class=small target=new href=\"hostcontract.php?session=$session&sport=$sport&distid=$distid&disttimesid=$row2[id]\">View this Contract</a>";
                            }
                            else                          //REJECTED
                            {
                                echo "<br>The NSAA has REJECTED this contract.<br>";
                                echo "<a class=small target=new href=\"hostcontract.php?session=$session&sport=$sport&distid=$distid&disttimesid=$row2[id]\">View this Contract</a>";
                            }
                        } //END IF ACCEPTED
                    } //END IF POSTED
                    echo "</p></td>";
                    //OPPONENTS:
                    $sql3="SELECT * FROM $db_name.".$sport."sched WHERE distid='$distid' AND gamenum='$row2[gamenum]'";
                    $result3=mysql_query($sql3);
                    $row3=mysql_fetch_array($result3);
                    echo "<td>";
                    if($row3[sid]>0) echo GetSchoolName($row3[sid],$sport)." vs. ";
                    else echo "TBD vs. ";
                    if($row3[oppid]>0) echo GetSchoolName($row3[oppid],$sport)."</td>";
                    else echo "TBD</td>";
                    /*
                   else
                   {
                   echo "<select name=\"oppid1[$ix]\"><option value=\"0\">Select Opponent</option>";
                           for($j=0;$j<count($sids);$j++)
                           {
                              echo "<option value=\"$sids[$j]\"";
                              if($row2[sid]==$sids[$j]) echo " selected";
                              echo ">".GetSchoolName($sids[$j],$sport)."</option>";
                           }
                          echo "</select> vs.<br>";
                      echo "<select name=\"oppid[$ix]\"><option value=\"0\">Select Opponent</option>";
                          for($j=0;$j<count($sids);$j++)
                          {
                             echo "<option value=\"$sids[$j]\"";
                             if($row2[oppid]==$sids[$j]) echo " selected";
                             echo ">".GetSchoolName($sids[$j],$sport)."</option>";
                          }
                          echo "</select></td>";
                   }
                */
                }
                echo "</tr>";
                $ix++;
            }
            if($blankdays>0)
            {
                if($sport=='ba' || (preg_match("/bb/",$sport) && $class=="A")) 	 $colspan=6;
                else $colspan=4;
                echo "<tr align=left><td colspan='$colspan'><div class='error' style='width:98%;'><i>* This date has not yet been saved. Please click Save below in order to save the date above and assign officials to this day.</i></div></td></tr>";
            }

            /*
            while($ix<$gamecount)
            {
              echo "<input type=hidden name=\"disttimesid[$ix]\" value='0'>";
               echo "<tr align=left>";
           $match=$ix+1;
           echo "<td align=center><b>$match</b></td>";
           echo "<td><select name=\"month[$ix]\"><option value=''>MM</option>";
               for($i=1;$i<=12;$i++)
               {
                  if($i<10) $m="0".$i;
                  else $m=$i;
                  echo "<option>$m</option>";
               }
               echo "</select>/<select name=\"day[$ix]\"><option value=''>DD</option>";
               for($i=1;$i<=31;$i++)
               {
                  if($i<10) $d="0".$i;
                  else $d=$i;
                  echo "<option>$d</option>";
               }
               echo "</select>/<select name=\"year[$ix]\"><option value=''>YYYY</option>";
               $curyr=date("Y");
               for($i=$curyr-1;$i<=$curyr+1;$i++)
               {
                  echo "<option>$i</option>";
               }
               echo "</select></td>";
               echo "<td><input type=text class=tiny maxlength=2 size=3 name=\"hour[$ix]\">:";
               echo "<input type=text class=tiny maxlength=2 size=3 name=\"min[$ix]\">";
               echo "<select class=small name=\"ampm[$ix]\">";
               echo "<option>PM</option><option>AM</option></select>";
               echo "<select class=small name=\"timezone[$ix]\">";
               echo "<option>$central</option><option>$mtn</option>";
               echo "</td></tr>";
           $ix++;
            }
        */
            echo "</table>";
            if($ix==0)
            {
                if($type=="District Final" || $type=="Substate")
                    echo "<h5 width=500><div class='error' style=\"width:500px;\"><p>Please enter the Date for this $type above and then click \"Save\" in order to create a TIME SLOT to which you can assign officials.</p></div></td></tr>";
                else
                    echo "<hr width=500><div class='error' style=\"width:500px;\"><p>Once you have entered the Schools above and clicked the \"Save\" button below, you will see time slots for you to edit here.</p><p>These time slots are important - they are the time slots to which you will assign OFFICIALS.</p></td></tr>";
            }
            else
            {
                if($type!="District Final" && $type!='Substate')
                {
                    if($showtimes!='y')
                        echo "<a href=\"posttimes.php?session=$session&sport=$sport&class=$class&type=$type&distid=$distid\">Post these times to the $type $class-$district bracket</a></td></tr>";
                    else
                        echo "<a target=new href=\"".$sport."brackets.php?session=$session&distid=$distid\">Preview $type $class-$district bracket</a></td></tr>";
                }
                else
                {
                    echo "<a target=\"_blank\" href=\"vbfinals.php?sport=$sport&type=$type\">Preview $type Page</a></td></tr>";
                }
            }
        }//end if time slots
        echo "<input type=hidden name=hiddensave>";
        echo "<tr align=center><td colspan=2><br><input type=checkbox name=\"showdistinfo\" value='x'";
        if($showdistinfo=='x') echo " checked";
        echo "> Check here to show this $type's <b><u>dates and schools assigned</b></u> on the <a class=small target=\"_blank\" href=\"/distassign.php?sport=$sport\">$sportname District Assignments page</a>.</td></tr>";
        echo "<tr align=center><td colspan=2><br><input type=checkbox name=\"showoffs\" value='y'";
        if($showoffs=='y') echo " checked";
        if($type=="Substate" || $type=="District Final")
            echo "> Check here to show <b><u>host information</b></u> on the Host School AD's page (assigned officials, entry forms and financial report)</td></tr>";
        else
            echo "> Check here to show the officials assigned to this $type to the Host School.</td></tr>";
        echo "<tr align=center><td colspan=2><br><input type=submit name=save value=\"Save\"></td></tr>";
    }
    else echo "<tr align=center><td colspan=2><br><i>Please select a Type and a Class/District.</i></td></tr>";
    echo "</table>";
}//end if sport given
else
{
    echo "<br><br><i>Please select a sport.</i>";
}
echo "</form>";

echo $end_html;
?>
