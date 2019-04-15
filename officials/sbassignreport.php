<?php
$sport='sb';

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$level=GetLevel($session);
if($level==3)	//observer
{
    header("Location:sbassignreport2.php?session=$session");
    exit();
}
if($level==4) $level=1;

if(!ValidUser($session) || $level!=1)
{
    header("Location:index.php?error=1");
    exit();
}

if($database && $database!='')
{
    $sql="USE $database";
    $result=mysql_query($sql);
    $years=preg_replace("/[^0-9]/","",$database);
    $years=substr($years,0,4)."-".substr($years,4,4);
}

$disttimes=$sport."disttimes";
$contracts=$sport."contracts";
$districts=$sport."districts";

echo $init_html;
if($print!=1)
    echo GetHeader($session,"contractadmin");
else
    echo "<table width=100%><tr align=center><td>";
echo "<br>";

if($print!=1)
{
    echo "<form method=post action=\"sbassignreport.php\">";
    echo "<input type=hidden name=session value=$session>";
}

$sportname=GetSportName($sport);

if($sport && $sport!="")
{
    if($posted=="yes" && $print!=1)
    {
        echo "<font style=\"color:red\"><b>All $type Softball Contracts have been posted to the assigned officials.</b></font><br><br>";
    }
    if($print!=1)
    {
        echo "<p><a href=\"contractadmin.php?session=$session&sportch1=$sport\">&larr; Return to $sportname Contracts & Assignments</a>";
        if($type=="abc")
            echo "&nbsp;|&nbsp;<a href=\"".$sport."assignreport.php?session=$session&sport=$sport\">View District Assignments in CLASS/DISTRICT Order</a>";
        else if($type!='state')
            echo "&nbsp;|&nbsp;<a href=\"".$sport."assignreport.php?session=$session&sport=$sport&type=abc\">View District Assignments in ABC Order</a>";
        echo "</p>";
    }

    echo "<h2>$years $sportname Assignments (";
    if($type=="abc")
        echo "ABC Order by Official";
    else if($type=='state')
        echo "State";
    else
        echo "Class/District Order";
    echo "):</h2><table><caption>";
    if($print!=1)   //QUICK OPTIONS
    {
        ?>
        <style>
            label.option {
                background-color:yellow;
                padding:5px;
                margin:2px;
                border: #a0a0a0 1px dotted;
                float:left;
            }
            label.option a {
                font-size:12px;
                font-weight:normal;
            }
        </style>
        <?php
        if($type=="state")
            echo "<label class='option'><a target=new href=\"".$sport."assignexport.php?session=$session\">State Officials MAILING EXPORT</a></label>";
        echo "<label class='option'><a target=\"_blank\" href=\"".$sport."assignreport.php?session=$session&sport=$sport&type=$type&print=1\">PRINT this report</a></label>";
        echo "<label class='option'><a target=\"_blank\" href=\"".$sport."offexport.php?session=$session&sport=$sport&class=A\">Class A Officials Export</a></label>";
        echo "<label class='option'><a target=\"_blank\" href=\"".$sport."offexport.php?session=$session&sport=$sport\">All Officials Export</a></label><div style=\"clear:both;\"></div>";
        echo "<label class='option'><a href=\"assign".$sport.".php?session=$session";
        if($type=='state') echo "&type=State";
        else echo "&type=District";
        echo "\">ASSIGN $sportname Officials</a></label>";
        echo "<label class='option'><a href=\"assignpost.php?return=".$sport."assignreport&session=$session&sport=$sport&type=$type\">POST All ";
        if($type=='state') echo "STATE";
        else echo "DISTRICT";
        echo " Contracts</a></label>";
        echo "<label class='option'><a href=\"".$sport."contracts.php?session=$session";
        if($type=='state') echo "&typech=State";
        else echo "&typech=District";
        echo "\">View CONTRACTS</a></label><div style=\"clear:both;\"></div>";
        if($type=='state' && $confirmed!=1)
            echo "<p>[<a class=small href=\"".$sport."assignreport.php?session=$session&sport=$sport&type=$type&confirmed=1\">Only Show Accepted & Confirmed Contracts</a>]</p>";
        else if($type=='state' && $confirmed==1)
            echo "<p>[<a class=small href=\"".$sport."assignreport.php?session=$session&sport=$sport&type=$type\">Show ALL Contracts</a>]</p>";
    } //END IF NOT PRINT
    echo "</caption>";

    if($type=="abc")
    {
        $sql="SELECT DISTINCT t1.offid,t2.first,t2.last FROM $contracts AS t1, officials AS t2 WHERE t1.offid=t2.id ORDER BY t2.last,t2.first";
        $result=mysql_query($sql);
        $ix=0;
        $total=mysql_num_rows($result);
        if($total%2==1) $total++;
        while($row=mysql_fetch_array($result))
        {
            if($ix==0)
                echo "<tr align=left valign=top><td><table>";
            else if($ix==(($total/2)))
                echo "</table></td><td><table>";
            echo "<tr align=left><td colspan=2><b><u>$row[last], $row[first]</u></td></tr>";
            echo "<tr align=center><td><table>";
            $sql2="SELECT DISTINCT t2.id,t1.post,t1.accept,t1.confirm,t2.class,t2.district,t2.hostschool,t2.site,t2.type FROM $contracts AS t1,$districts AS t2,$disttimes AS t3 WHERE t3.distid=t2.id AND t3.id=t1.disttimesid AND t1.offid='$row[offid]' AND t2.type!='State' ORDER BY t2.class,t2.district";
            $result2=mysql_query($sql2);
            echo "<tr align=center>";
            echo "<th class=small align=left colspan=2>District, Date/Time</th>";
            echo "<td><b>Posted</b></td><td><b>Accept</b></td><td><b>NSAA</b></td></tr>";
            while($row2=mysql_fetch_array($result2))
            {
                echo "<tr valign=top align=center>";
                if($row2[post]=='y') $post="X";
                else $post="&nbsp;";
                if($row2[accept]=='y') $accept="Yes";
                else if($row2[accept]=='n') $accept="No";
                else $accept="?";
                if($row2[confirm]=='y') $confirm="Confirm";
                else if($row2[confirm]=='n') $confirm="Reject";
                else $confirm="?";
                if($row2[type]!='State')
                {
                    echo "<td align=left>$row2[class]-$row2[district]</td>";
                    echo "<td align=left>";
                    $sql3="SELECT t1.day,t1.time,t2.crewchief,t1.sbfield,t1.gamenum FROM $disttimes AS t1, $contracts AS t2 WHERE t1.id=t2.disttimesid AND t1.distid='$row2[id]' AND t2.offid='$row[offid]' ORDER BY t1.day,t1.time";
                    $result3=mysql_query($sql3);
                    echo mysql_error();
                    while($row3=mysql_fetch_array($result3))
                    {
                        $date=split("-",$row3[day]); $day=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
                        echo "$day: Game #$row3[gamenum]: $row3[time]";
                        if($row3[sbfield]!='') echo ", Field $row3[sbfield]";
                        if($row3[crewchief]=='x') echo "(crew chief)";
                        echo "<br>";
                    }
                    echo "</td>";

                }
                else
                    echo "<td align=left colspan=2>STATE</td>";
                echo "<td>$post</td><td>$accept</td><td>$confirm</td></tr>";
            }
            echo "</table></td></tr>";
            if($ix==($total-1))
                echo "</table></td></tr>";
            $ix++;
        }
        echo "</table></td></tr>";
    }//end if abc
    else if($type!='state')	//class-dist order (district assignments)
    {
        //get total # contracts
        $sql="SELECT * FROM $contracts";
        $result=mysql_query($sql);
        $total=mysql_num_rows($result);
        if($total%2==1) $total++;
        $ct=0;
        $sql="SELECT DISTINCT id,class,district,hostschool,type FROM $districts WHERE type!='State' ORDER BY class,district";
        $result=mysql_query($sql);
        echo "<tr align=left valign=top><td><table>";
        while($row=mysql_fetch_array($result))
        {
            $class=$row[1]; $district=$row[2];
            echo "<tr align=left><td><b><u>$row[type] ";
            echo "$class-$district: (host: $row[hostschool])";
            echo "</u></b></td></tr>";
            $sql2="SELECT DISTINCT day FROM $disttimes WHERE distid='$row[id]' ORDER BY day";
            $result2=mysql_query($sql2);
            while($row2=mysql_fetch_array($result2))
            {
                $date=split("-",$row2[day]); $curday=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
                echo "<tr align=left><td>";
                echo "<table>";
                $sql3="SELECT DISTINCT t1.offid,t1.post,t1.accept,t1.confirm,t2.time,t1.crewchief,t2.sbfield,t2.gamenum FROM $contracts AS t1, $disttimes AS t2 WHERE t1.disttimesid=t2.id AND t2.distid='$row[id]' AND t2.day='$row2[day]' ORDER BY t2.time";
                $result3=mysql_query($sql3);
                if(mysql_num_rows($result3)>0)
                {
                    echo "<tr align=center valign=top>";
                    echo "<th class=small align=left>$curday</th>";
                    echo "<th class=small align=left>Official</th><th class=small>Crew<br>Chief</th>";
                    echo "<th class=small>Posted</th><th class=small>Accept</th><th class=small>NSAA</th></tr>";
                }
                //echo $sql3;
                while($row3=mysql_fetch_array($result3))
                {
                    echo "<tr align=center>";
                    if($row3[post]=='y') $post="X";
                    else $post="&nbsp;";
                    if($row3[accept]=='y') $accept="Yes";
                    else if($row3[accept]=='n') $accept="No";
                    else $accept="?";
                    if($row3[confirm]=='y') $confirm="Confirm";
                    else if($row3[confirm]=='n') $confirm="Reject";
                    else $confirm="?";
                    echo "<td align=left>Game #$row3[gamenum]) $row3[time]";
                    if($row3[sbfield]!='') echo " (Field $row3[sbfield])";
                    echo "</td>";
                    echo "<td align=left>".GetOffName($row3[offid])."</td>";
                    if($row3[crewchief]=='x') echo "<td>X</td>";
                    else echo "<td>&nbsp;</td>";
                    echo "<td>$post</td><td>$accept</td><td>$confirm</td></tr>";
                    if($ct!=$total/2) $ct++;
                }
                echo "</table></td></tr>";
            }
            if($ct==$total/2)
            {
                $ct++;
                echo "</table></td><td><table>";
            }
        }//end for each class/district
        echo "</table></td></tr>";
    }//end if not abc
    else if($type=='state')
    {
        echo "<tr align=center><td><table cellspacing=0 cellpadding=4 frame='all' rules='all' style=\"border:#808080 1px solid;\">";
        //show summary of state assignemnts: Official, Posted, Accept, NSAA Response,
        //Lodging information
        $sql="SELECT t1.socsec,t1.first,t1.middle,t1.last,t1.city,t2.*,t3.time FROM officials AS t1, $contracts AS t2, $disttimes AS t3,$districts AS t4 WHERE t1.id=t2.offid AND t2.disttimesid=t3.id AND t3.distid=t4.id AND t4.type='State' ";
        if($confirmed==1) $sql.="AND t2.accept='y' AND t2.confirm='y' ";
        if(!$sort) $sql.="ORDER BY t1.last, t1.first, t1.middle";
        else $sql.="ORDER BY t2.$sort DESC,t1.last,t1.first,t1.middle";
        $result=mysql_query($sql);
        echo "<tr align=center><td rowspan=2 align=left><a class=small href=\"sbassignreport.php?sport=$sport&database=$database&session=$session&type=state&confirmed=$confirmed\">Official</a></td>";
        echo "<td rowspan=2><b>SSN</b></td><td rowspan=2><b>City</b></td>";
        echo "<td rowspan=2><a class=small href=\"sbassignreport.php?sport=$sport&database=$database&session=$session&type=state&confirmed=$confirmed&sort=post\">Posted</a></td>";
        echo "<td rowspan=2><a class=small href=\"sbassignreport.php?sport=$sport&database=$database&session=$session&type=state&confirmed=$confirmed&sort=accept\">Accept</a></td>";
        echo "<td rowspan=2><a class=small href=\"sbassignreport.php?sport=$sport&database=$database&session=$session&type=state&confirmed=$confirmed&sort=confirm\">NSAA</a></td>";
        echo "<td rowspan=2><a class=small href=\"sbassignreport.php?sport=$sport&database=$database&session=$session&type=state&confirm=$confirmed&sort=twoday\">2 Days Only</a></td>";

        echo "<td colspan=4><b>Dates Needing Lodging</b></td>";
        echo "<td rowspan=2><a class=small href=\"sbassignreport.php?sport=$sport&database=$database&session=$session&type=state&confirmed=$confirmed&sort=arrive\">Arrival<br>Time</a></td>";
        echo "<td rowspan=2><a class=small href=\"sbassignreport.php?sport=$sport&database=$database&session=$session&type=state&confirmed=$confirmed&sort=special\">Special Requests</a></td>";
        echo "</tr><tr align=center>";
        $sql2="SELECT * FROM sbtourndates WHERE lodgingdate='x' ORDER BY tourndate";
        $result2=mysql_query($sql2);
        while($row2=mysql_fetch_array($result2))
        {
            $date=explode("-",$row2[tourndate]);
            $ldate=date("D m/j",mktime(0,0,0,$date[1],$date[2],$date[0]));
            echo "<td><a class=small href=\"sbassignreport.php?sport=$sport&database=$database&session=$session&type=state&confirmed=$confirmed&sort=date1\">$ldate</a></td>";
        }
        echo "</tr>";

        while($row=mysql_fetch_array($result))
        {
            echo "<tr align=center>";
            echo "<td align=left width=150>$row[first] $row[middle] $row[last]</td>";
            echo "<td>$row[socsec]</td><td align=left>$row[city]</td>";
            echo "<td>";
            if($row[post]=='y') echo "X";
            else echo "&nbsp;";
            echo "</td><td>";
            if($row[accept]=='y') echo "Yes";
            else if($row[accept]=='n') echo "No";
            else echo "?";
            echo "</td><td>";
            if($row[confirm]=='y') echo "Confirmed";
            else if($row[confirm]=='n') echo "Rejected";
            else echo "?";
            echo "</td><td>";
            if($row[twoday]=='x') echo "X";
            else echo "&nbsp;";
            echo "</td><td>";
            if($row[date1]=='x') echo "X";
            else echo "&nbsp;";
            echo "</td><td>";
            if($row[date2]=='x') echo "X";
            else echo "&nbsp;";
            echo "</td><td>";
            if($row[date3]=='x') echo "X";
            else echo "&nbsp;";
            echo "</td><td>";
            if($row[date4]=='x') echo "X";
            else echo "&nbsp;";
            echo "</td><td align=left>";
            echo "$row[arrive]&nbsp;";
            echo "</td><td align=left width=\"250px\">";
            echo "$row[special]&nbsp;";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table></td></tr>";
    }
    echo "</table>";
    echo "<br>";
    if($type=="abc" && $print!=1)
    {
        echo "<a class=small href=\"sbassignreport.php?database=$database&session=$session&sport=$sport\">View DISTRICT Assignments in Class/District Order</a>&nbsp;&nbsp;";
        echo "<a class=small href=\"sbassignreport.php?database=$database&session=$session&sport=$sport&type=state\">View STATE Assignments</a>&nbsp;&nbsp;";
    }
    else if($type=='state' && $print!=1)
    {
        echo "<a class=small href=\"sbassignreport.php?database=$database&session=$session&sport=$sport\">View DISTRICT Assignments</a>&nbsp;&nbsp;";
    }
    elseif($print!=1)
    {
        echo "<a class=small href=\"sbassignreport.php?database=$database&session=$session&sport=$sport&type=abc\">View DISTRICT Assignments in ABC Order</a>&nbsp;&nbsp;";
        echo "<a class=small href=\"sbassignreport.php?database=$database&session=$session&sport=$sport&type=state\">View STATE Assignments</a>&nbsp;&nbsp;";
    }
    if($print!=1)
        echo "<a class=small href=\"welcome.php?session=$session\">Home</a>";

}//end if sport!=""
echo $end_html;

if($database && $database!='')
{
    $sql="USE $db_name2";
    $result=mysql_query($sql);
}

?>
