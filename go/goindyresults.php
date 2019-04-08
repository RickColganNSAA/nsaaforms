<?php
/***********************************************
goindyresults.php
Enter Individual Results for Team for GO Tourn
Created 7/11/12
Author Ann Gaffigan
 ************************************************/

require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
if(!ValidUser($session))
{
    header("Location:../index.php");
    exit();
}
//get school user chose (Level 1) or belongs to (Level 2, 3)
if(!$school_ch)
    $school=GetSchool($session);
else
    $school=$school_ch;
$school2=ereg_replace("\'","\'",$school);

if(!$sport) $sport='gob';
$sport=preg_replace("/_/","",$sport);
$sportname=GetActivityName($sport);
$tourntbl=$sport."tourn";
$teamtbl=$tourntbl."team";
$indytbl=$tourntbl."indy";
$schooltbl=GetSchoolTable($sport);

if($delete && $sid && $tournid)	//DELETE SCORES
{
    $sql="DELETE FROM $teamtbl WHERE tournid='$tournid' AND sid='$sid'";
    $result=mysql_query($sql);
    $sql="DELETE FROM $indytbl WHERE tournid='$tournid' AND sid='$sid'";
    $result=mysql_query($sql);
}

if($save && $sid && $tournid)	//SAVE SCORES
{
    $sql="DELETE FROM $indytbl WHERE tournid='$tournid' AND sid='$sid'";
    $result=mysql_query($sql);


    for($i=0;$i<count($studentid);$i++)
    {
        if($studentid[$i]>0)
        {
            $sql="INSERT INTO $indytbl (tournid,sid,studentid,score) VALUES ('$tournid','$sid','$studentid[$i]','$score[$i]')";
            $result=mysql_query($sql);
        }
    }

    $sql="SELECT * FROM $teamtbl WHERE tournid='$tournid' AND sid='$sid'";
    $result=mysql_query($sql);
    if(mysql_num_rows($result)>0)
        $sql="UPDATE $teamtbl SET score='$teamscore' WHERE tournid='$tournid' AND sid='$sid'";
    else if($teamscore>0)
        $sql="INSERT INTO $teamtbl (tournid,sid,score) VALUES ('$tournid','$sid','$teamscore')";
    else $sql="";
    if($sql!='')
        $result=mysql_query($sql);

    if ($sport == "gob") $sport = "go_b";
    if ($sport == "gog") $sport = "go_g";

    //update go_bsched/go_gsched table for update after adding individual team score
    $sql="SELECT * FROM $sport"."sched"." WHERE tid=$tournid AND sid=$sid";
    $result=mysql_query($sql);

    if (mysql_num_rows($result)>1){
        echo  $sql="UPDATE $sport"."sched"." SET sidscore=$teamscore WHERE tid=$tournid AND sid=$sid ";
        mysql_query($sql);
    }else{
        $sql="UPDATE $sport"."sched"." SET oppscore=$teamscore WHERE tid=$tournid AND oppid=$sid";
        mysql_query($sql);
    }
    ?>
    <script language='javascript'>
        window.opener.location.href="gotournresults.php?sid=<?php echo $reportsid?>&session=<?php echo $session?>&sport=<?php echo $sport?>&tournid=<?php echo $tournid?>";
        window.close();
    </script>
    <?php
}

echo $init_html."<table class=\"nine\" style=\"width:100%;\"><tr align=center><td>";
?>
<script language='javascript'>
    function CalculateTeamScore()
    {
        var i=0;
        var highscore=0;
        var totalscore=0;
        var ct=0;
        for(i=0;i<5;i++)
        {
            var score="score"+ i;
            var curscore=document.getElementById(score).value;
            if(curscore!=''){
                if(parseFloat(curscore)>highscore)
                    highscore=parseFloat(curscore);
                totalscore=totalscore+parseFloat(curscore);
                if(parseFloat(curscore)>0)
                    ct=ct+1;
            }

        }

        if(ct>4) {
            totalscore=totalscore-highscore;
            document.getElementById('teamscore').value=totalscore;
        }else if (ct==4){
            document.getElementById('teamscore').value=totalscore;
        }
        else{
            document.getElementById('teamscore').value=0;
        }
    }
</script>
<?php

echo "<form method=post action=\"goindyresults.php\">";
echo "<input type=hidden name=\"tournid\" value=\"$tournid\">";
echo "<input type=hidden name=\"reportsid\" value=\"$reportsid\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<input type=hidden name=\"sport\" value=\"$sport\">";

echo "<p style=\"text-align:left;\">Please enter the scores for the top 5 individuals for each team. The low 4 scores for each team will become the Team Score for that school and will be automatically added to the \"Team Results\" table at the bottom of the main tournament report.</p>";
if($delete)
{
    echo "<div class='alert'>The results have been deleted for ".GetSchoolName($sid,$sport).".  <a href=\"javascript:window.opener.location.href='gotournresults.php?session=$session&sport=$sport&tournid=$tournid';window.close();\">Close</a></div>";
}

echo "<br><table cellspacing=0 cellpadding=3 class=\"nine\" frame=all rules=all style=\"border:#808080 1px solid;\">";
echo "<caption><b>Individual Results for <select name=\"sid\" onChange=\"submit();\"><option value='0'>Select Team</option>";
$sql="SELECT * FROM $schooltbl WHERE class='A' ORDER BY outofstate,school";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
    echo "<option value=\"$row[sid]\"";
    if($sid==$row[sid]) echo " selected";
    echo ">$row[school]</option>";
}
echo "</select>:</b><br><br></caption>";
//NOTE: Need way to delete results for a team
echo "<tr align=center><td>Player</td><td>9 or 18 Meet Score</td></tr>";
$sql2="SELECT * FROM $indytbl WHERE tournid='$tournid' AND sid='$sid' ORDER BY score";
$result2=mysql_query($sql2);
//GetPlayers for this team
$players=explode("<result>",GetPlayers($sport,GetMainSchoolName($sid,$sport)));
$ix=0;
while($row2=mysql_fetch_array($result2))
{
    echo "<tr align=center><td><select name=\"studentid[$ix]\"><option value=\"0\">Choose Player</option>";
    for($i=0;$i<count($players);$i++)
    {
        $stud=explode("<detail>",$players[$i]);
        echo "<option value=\"".$stud[0]."\"";
        if($row2[studentid]==$stud[0]) echo " selected";
        echo ">".$stud[1]."</option>";
    }
    echo "</select></td><td><input type=text name=\"score[$ix]\" id=\"score".$ix."\" value=\"$row2[score]\" onKeyUp=\"CalculateTeamScore();\"></td></tr>";
    $ix++;
}
while($ix<5)
{
    echo "<tr align=center><td><select name=\"studentid[$ix]\"><option value=\"0\">Choose Player</option>";
    for($i=0;$i<count($players);$i++)
    {
        $stud=explode("<detail>",$players[$i]);
        echo "<option value=\"".$stud[0]."\">".$stud[1]."</option>";
    }
    echo "</select></td><td><input type=text name=\"score[$ix]\" id=\"score".$ix."\" onKeyUp=\"CalculateTeamScore();\"></td></tr>";
    $ix++;
}
//TEAM SCORE
$sql="SELECT * FROM $teamtbl WHERE sid='$sid' AND tournid='$tournid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
echo "<tr align=center><td colspan=2>Team Score: <input type=text readOnly=true size=4 value=\"$row[score]\" name=\"teamscore\" id=\"teamscore\"> (Low four scores)</td></tr>";
echo "</table>";

echo "<br><input type=submit name=\"save\" value=\"Save & Close\"><br><br><b>OR</b><br><br><input type=submit name=\"delete\" value=\"Delete these Results\" onClick=\"return confirm('Are you sure you want to delete these results? This will delete the individual results and team score for this team.');\">";

echo "</form>";

echo $end_html;
?>
