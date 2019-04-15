<?php
require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
    header("Location:index.php?error=1");
    exit();
}

$level=GetLevel($session);
if(!$givenoffid) $offid=GetOffID($session);
else
{
    $offid=$givenoffid;
    $header="no";
}
$curryear=GetFallYear('wr')+1;

//GET DATES
$sql="SELECT DISTINCT tourndate,label FROM wrtourndates WHERE offdate='x' ORDER BY tourndate,label";
$result=mysql_query($sql);
$wrdates=array(); $i=0;
$distdates = array();
$statedates = array();
$dualdates = array();
$stateix=0; $statedualix=0;
while($row=mysql_fetch_array($result))
{
    $date=explode("-",$row[tourndate]);
    if ($row['label'] == 'State') {
//        $statedates[$i]=date("M j", mktime(0, 0, 0, $date[1], $date[2], $date[0]));
        array_push($statedates,date("M j", mktime(0, 0, 0, $date[1], $date[2], $date[0])));
    } elseif ($row['label'] == 'State Dual') {
        array_push($dualdates,date("M j", mktime(0, 0, 0, $date[1], $date[2], $date[0])));
    } else {
        array_push($distdates,date("M j", mktime(0, 0, 0, $date[1], $date[2], $date[0])));
    }
    $wrdates[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
    $i2=$i+1; $field="date".$i2;
    $sql2="SHOW FULL COLUMNS FROM wrapply WHERE Field='$field'";
    $result2=mysql_query($sql2);
    if(mysql_num_rows($result2)==0)
    {
        $sql2="ALTER TABLE wrapply ADD `$field` VARCHAR(10) NOT NULL";
        $result2=mysql_query($sql2);
    }
    //FIRST state DATE?
    $sql2="SELECT label FROM wrtourndates WHERE tourndate='$row[tourndate]' AND label='State'";
    $result2=mysql_query($sql2);
    if(mysql_num_rows($result2)>0 && $stateix==0)
        $stateix=$i;
    //FIRST statedual DATE?
    $sql2="SELECT label FROM wrtourndates WHERE tourndate='$row[tourndate]' AND label='State Dual'";
    $result2=mysql_query($sql2);
    if(mysql_num_rows($result2)>0 && $statedualix==0)
        $statedualix=$i;
    $i++;
}


if($submit)
{
    $conflict=addslashes($conflict);
    $date=time();

    $sql2="SELECT id FROM wrapply WHERE offid='$offid'";
    $result2=mysql_query($sql2);
    if(mysql_num_rows($result2)==0)
    {
        $sql="INSERT INTO wrapply (offid,";
        for($i=1;$i<=count($wrdates);$i++)
            $sql.="date".$i.", ";
        $sql.="conflict,appdate) VALUES ('$offid',";
        for($i=1;$i<=count($wrdates);$i++)
        {
            $var="date".$i;
            $sql.="'".$$var."',";
        }
        $sql.="'$conflict','$date')";
    }
    else
    {
        $sql="UPDATE wrapply SET ";
        for($i=1;$i<=count($wrdates);$i++)
        {
            $var="date".$i;
            $sql.="$var='".$$var."',";
        }
        $sql.=" conflict='$conflict',appdate='$date' WHERE offid='$offid'";
    }
    $result=mysql_query($sql);
    if($submit=="Save & Close")
    {
        ?>
        <script language="javascript">
            window.close();
            window.opener.location="https://secure.nsaahome.org/nsaaforms/officials/apptooff.php?session=<?php echo $session; ?>&sport=wr&sort=<?php echo $sort; ?>&searchquery=<?php echo $searchquery; ?>";
        </script>
        <?php
    }
}

//check if already submitted
$sql="SELECT * FROM wrapply WHERE offid='$offid'";
$result=mysql_query($sql);
$duedate=GetDueDate("wr","app");
$date=split("-",$duedate);
$duetime=mktime(0,0,0,$date[1],$date[2],$date[0]);
$year=date("Y");
$june1="$year-06-01";
$june1time=mktime(0,0,0,6,1,$year);
if(PastDue(GetDueDate("wr","app"),1) && $level!=1 && $offid!=3427)
//if (true)
{
    $row=mysql_fetch_array($result);
    echo $init_html;
    echo GetHeader($session);
    echo "<br>";
    $sql2="SELECT email FROM app_duedates WHERE sport='wr'";
    $result2=mysql_query($sql2);
    $row2=mysql_fetch_array($result2);
    echo "<i>This form is currently unavailable.</i><br><br>";
    //echo "You must e-mail <a class=small href=\"mailto:$row2[0]\">$row2[0]</a> with any changes.</i><br><br>";
    if(mysql_num_rows($result)==0 && !(PastDue($june1,0) && $june1time>$duetime))
    {
        echo "[You did not submit an Application to Officiate $curryear Wrestling Tournaments.]<br><br>";
    }
    else if(!(PastDue($june1,0) && $june1time>$duetime))
    {
        echo "<i>You have submitted the following information:</i><br><br>";
        $appdate=date("F d, Y",$row[appdate]);
        $date=split("-",GetDueDate("wr","app"));
        $duedate="$date[1]/$date[2]/$date[0]";
        echo "<table width=500><caption><b>Application to Officiate $curryear District and/or State Wrestling Tournament:<br></b>(This form's due date is $duedate)";
        echo "<hr></caption>";
        echo "<tr valign=top align=left><th align=left class=smaller>Available Dates:</th>";
        echo "<td>Districts:&nbsp;";
        for($i=0;$i<count($distdates);$i++)
        {
            $index2=$i+1;
            $index="date".$index2;
            if($row[$index]=='x') echo $distdates[$i]."&nbsp;&nbsp;";
        }
        echo "<br>State (CenturyLink Center in Omaha):&nbsp;";
        for($i=0;$i<count($statedates);$i++)
        {
            $index2=$i+1;
            $index="date".$index2;
            if($row[$index]=='x') echo $statedates[$i]."&nbsp;&nbsp;";
        }
        echo "<br>State Dual Tournament:&nbsp;";
        for($i=0;$i<count($dualdates);$i++)
        {
            $index2=$i+1;
            $index="date".$index2;
            if($row[$index]=='x') echo $dualdates[$i]."&nbsp;&nbsp;";
        }
        echo "</td></tr>";
        echo "<tr align=left><th align=left class=smaller>Conflict of interest:</th>";
        echo "<td>$row[conflict]</td></tr>";
        echo "</table><br><br>";
    }
    echo "<a href=\"welcome.php?session=$session\">Home</a>";
    echo $end_html;
    exit();
}
else
{
    $submitted=1;
    $row=mysql_fetch_array($result);
    for($i=1;$i<=count($wrdates);$i++)
    {
        $var="date".$i;
        $$var=$row[$var];
    }
    $conflict=$row[conflict];
}

echo $init_html;
if($level!=1) echo GetHeader($session);
else echo "<table width=100%><tr align=center><td>";
echo "<br>";
echo "<form method=post action=\"wrapp.php\">";
echo "<input type=hidden name=session value=\"$session\">";
echo "<input type=hidden name=givenoffid value=\"$givenoffid\">";
echo "<input type=hidden name=sort value=$sort>";
echo "<input type=hidden name=searchquery value=\"$searchquery\">";
$duedate=GetDueDate("wr","app");
$date=split("-",$duedate);
$duedate2=date("F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
if($submit && $level!=1 && $level!=4)
{
    echo "<font style=\"color:blue\"><b>Your application has been saved.  ";
    echo "You may make updates to your application until the due date listed below.</b></font><br><br>";
}
else if($submit)
    echo "<font style=\"color:blue\"><b>The application has been saved";
else if($level!=1 && $level!=4)
    echo "<font style=\"color:blue\"><b>The following application to officiate is currently posted to the NSAA 
   by you.  You may make updates to this application until the due date listed below.</b></font><br><br>";

echo "<table cellspacing=3 cellpadding=3 class='nine'><caption><b>Application to Officiate $curryear District and/or State Wrestling Tournament<br>".GetOffName($offid)."</b><br> Due $duedate2";
if($submit)
    echo "<div class='alert'>The information below has been saved.</div>";
echo "<hr></caption>";
echo "<tr align=left><td>";
echo "I am applying to officiate the following tournament(s): (Please check available dates)</td></tr>";
echo "<tr align=left><td><table>";
echo "<tr align=left><td><b>Districts:</b></td><td>";
$count=0;
for($i=0;$i<count($distdates);$i++)
{
    $index2=$count+1;
    $index="date".$index2;
    echo "<input type=checkbox name=\"$index\" value='x'";
    if($$index=='x') echo " checked";
    echo ">$distdates[$i]&nbsp;&nbsp;";
    $count++;
}
echo "</td></tr><tr align=left><td><b>State (Omaha):</b></td><td>";
for($i=0;$i<count($statedates);$i++)
{
    $index2=$count+1;
    $index="date".$index2;
    echo "<input type=checkbox name=\"$index\" value='x'";
    if($$index=='x') echo " checked";
    echo ">$statedates[$i]&nbsp;&nbsp;";
    $count++;
}
echo "</td></tr><tr align=left><td><b>State Dual Tournament:</b></td><td>";
for($i=0;$i<count($dualdates);$i++)
{
    $index2=$count+1;
    $index="date".$index2;
    echo "<input type=checkbox name=\"$index\" value='x'";
    if($$index=='x') echo " checked";
    echo ">$dualdates[$i]&nbsp;&nbsp;";
    $count++;
}
echo "</td></tr></table></td></tr>";
echo "<tr align=left><td>Schools with which I have a conflict of interest:<br>";
echo "<textarea rows=5 cols=60 name=conflict>$conflict</textarea></td></tr>";
echo "</table><br>";
echo "<input type=submit name=submit value=\"Save & Submit\">";
if($givenoffid && $level==1)
{
    echo "&nbsp;<input type=submit name=submit value=\"Save & Close\">";
}
echo "</form>";
echo "<A class=small href=\"welcome.php?session=$session\">Home</a>";
echo $end_html;
?>
