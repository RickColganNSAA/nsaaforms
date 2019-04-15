<?php
require 'functions.php';
require 'variables.php';
require $_SERVER['DOCUMENT_ROOT'].'/calculate/functions.php';
$sport='fb';
$thisyear=GetSchoolYear(date("Y"),date("m"));
$thisyr=GetFallYear('fb');

//make sure AD is logged
//connect to $db_name db
mysql_close();
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

$sql="SELECT * FROM sessions WHERE session_id='$session'";
$result=mysql_query($sql);
if(mysql_num_rows($result)==0)
{
    //CHECK IF NSAA USER
    $sql="USE $db_name2";
    $result=mysql_query($sql);
    if(!ValidUser($session) || GetLevel($session)!=1)
    {
        header("Location:index.php?error=1");
        exit();
    }
    else
    {
        $level=1;
    }
}
//get AD's name
$sql="SELECT t2.name, t2.school, t2.sport, t2.level FROM sessions AS t1, logins AS t2 WHERE t1.session_id='$se
ssion' AND t1.login_id=t2.id";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$name=$row[0];

//connect to $db_name2
mysql_close();
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

$districts="fbbrackets";
$contracts="fbcontracts";

if($level==1 && $save)
{
    $sql="UPDATE showtoad SET body='$body' WHERE sport='$sport'";
    $result=mysql_query($sql);
}

//get this district's info
$sql="SELECT * FROM $db_name.fbsched WHERE scoreid='$id'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$class=$row['class'];
$roundnum=$row[round];
if($class=='A' || $class=='B' || $class=="C1" || $class=="C2"|| $class=="D6")
    $rounds=array("First Round","Quarterfinals","Semifinals","Finals");
else
    $rounds=array("First Round","Second Round","Quarterfinals","Semifinals","Finals");
for($i=0;$i<count($rounds);$i++)
{
    if($roundnum==($i+1)) $round=$rounds[$i];
}
$time=$row[gametime];
$day=split("-",$row[received]);
$date=date("F j, Y",mktime(0,0,0,$day[1],$day[2],$day[0]));
if($row[sid]==$row[homeid])
{
    $home=GetSchoolName($row[sid],'fb',$thisyr);
    $away=GetSchoolName($row[oppid],'fb',$thisyr);
}
else
{
    $home=GetSchoolName($row[oppid],'fb',$thisyr);
    $away=GetSchoolName($row[sid],'fb',$thisyr);
}
$sql2="SELECT * FROM fbbrackets WHERE round='$round' AND class='$class' AND gamenum='$row[gamenum]'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
$bracketid=$row2[id];

echo $init_html."<table style=\"width:100%;\"><tr align=center><td><img src=\"/images/NSAAlogo.gif\">";
if($edit==1 && $level==1)
{
    ?>
    <script type="text/javascript">
        tinyMCE.init({
            mode : 'textareas',
            theme : 'advanced',
            skin : 'o2k7',
            skin_variant : 'black',
            convert_urls : false,
            relative_urls : false,
            plugins : 'safari,iespell,preview,media,searchreplace,paste,',
            theme_advanced_buttons1 : 'bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,pastetext,pasteword,|,undo,redo,|,link,unlink,image,media,|,code,preview',
            theme_advanced_buttons2 : '',
            theme_advanced_toolbar_location : 'top',
            theme_advanced_toolbar_align : 'left',
            theme_advanced_statusbar_location : 'bottom',
            theme_advanced_resizing : true,
            // Example content CSS (should be your site CSS)
            content_css : '../css/plain.css',
            // Drop lists for link/image/media/template dialogs
            template_external_list_url : 'lists/template_list.js',
            external_link_list_url : 'lists/link_list.js',
            external_image_list_url : 'lists/image_list.js',
            media_external_list_url : 'lists/media_list.js'
        });
    </script>
    <?php
}
if($level==1) echo "<p style='color:#8b0000;'><b>SAMPLE</b></p>";
if($round!='Finals' && $level==1)
    echo "<p><a href=\"".$sport."showtoad.php?session=$session&edit=1#editbody\">Edit this Page</a></p>";
echo "<div style=\"width:600px;text-align:left;\">";
echo "<p>Congratulations to your school for qualifying for the $thisyear Football Playoffs.  Below is the information about the football crew contracted to officiate your contest.</p>";
echo "<h3>Class $class $round</h3>";
echo "<p><b>$home vs. $away</b></p>";
echo "<p><b>Date:</b> $date</p>";
echo "<p><b>Time:</b> $time</p>";
$sql="SELECT t1.* FROM fbapply AS t1,fbcontracts AS t2 WHERE t1.offid=t2.offid AND t2.gameid='$bracketid' AND t2.accept='y' AND t2.confirm='y'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$sql2="SELECT * FROM officials WHERE id='$row[chief]'";
$result2=mysql_query($sql2);
$row2=mysql_fetch_array($result2);
echo "<p><b>Crew Chief: ".GetOffName($row[chief])."</b></p>";
if($round!='Finals')
{
    echo "<p>Contact Information:</p><p>";
    if($row2[homeph]!='')
    {
        echo "Home Phone: ";
        echo "(".substr($row2[homeph],0,3).")".substr($row2[homeph],3,3)."-".substr($row2[homeph],6,4);
        echo "<br>";
    }
    if($row2[workph]!='')
    {
        echo "Work Phone: ";
        echo "(".substr($row2[workph],0,3).")".substr($row2[workph],3,3)."-".substr($row2[workph],6,4);
        echo "<br>";
    }
    if($row2[cellph]!='')
    {
        echo "Cell Phone: ";
        echo "(".substr($row2[cellph],0,3).")".substr($row2[cellph],3,3)."-".substr($row2[cellph],6,4);
        echo "<br>";
    }
    if($row2[email]!='')
    {
        echo "E-mail: <a href=\"mailto:$row2[email]\">$row2[email]</a><br>";
    }
    echo "</p>";
}
echo "<p><b>Crew Members:</b></p>";
echo "<p>Referee: ".GetOffName($row[referee])."<br>";
echo "Umpire: ".GetOffName($row[umpire])."<br>";
echo "Linesman: ".GetOffName($row[linesman])."<br>";
echo "Linejudge: ".GetOffName($row[linejudge])."<br>";
echo "Backjudge: ".GetOffName($row[backjudge])."</p>";
if($round!='Finals')
{
    echo "<p><b>INFORMATION FOR THE HOST SCHOOL:</b><a name='editbody'><br><br></a>";

    $sql="SELECT * FROM showtoad WHERE sport='$sport'";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($level==1 && $edit==1)
    {
        echo "<form method=post action='".$sport."showtoad.php'><input type=hidden name='session' value='$session'><textarea cols=80 rows=25 name='body'>$row[body]</textarea><br><input type=submit name='save' value='Save Changes'></form><br>";
    }
    else
    {
        echo $row[body]."</p>";
    }
}
echo "<br><p>Thank you and Good Luck!</p></div>";
echo $end_html;
?>
