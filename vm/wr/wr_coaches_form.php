<?php
require $_SERVER['DOCUMENT_ROOT'] . '/calculate/functions.php'; //Wildcard Functions
require '../functions.php';
require '../variables.php';

//connect to db
$db = mysql_connect("$db_host", $db_user, $db_pass);
mysql_select_db($db_name, $db);

$level = GetLevel($session);

//verify user
if (!ValidUser($session)) {
    header("Location:../index.php");
    exit();
}

//get school user chose (Level 1) or belongs to (Level 2, 3)
 if ($level!=1){
     $school = GetSchool($session);
     $schoolid = GetSchoolID($session);
 }

echo GetHeader($session);
if ($save) {
    $sql = "SELECT * FROM wr_coaches_form WHERE schoolid='$schoolid'";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) == 0) {
        $sql="INSERT INTO wr_coaches_form( schoolid, head_coach, assistant_1, assistant_2, assistant_3, assistant_4, assistant_5, assistant_6, assistant_7, assistant_8, school, class, principal_or_athletic_director)"
            ."VALUES ('$schoolid','$head_coach','$assistant_1','$assistant_2','$assistant_3','$assistant_4','$assistant_5','$assistant_6','$assistant_7','$assistant_8','$school','$class','$principal_or_athletic_director');";
    }else{
        $sql="UPDATE wr_coaches_form SET head_coach='$head_coach',assistant_1='$assistant_1',assistant_2='$assistant_2',assistant_3='$assistant_3',assistant_4='$assistant_4',assistant_5='$assistant_5',assistant_6='$assistant_6',assistant_7='$assistant_7',assistant_8='$assistant_8',school= '$school',class='$class',principal_or_athletic_director='$principal_or_athletic_director' WHERE schoolid='$schoolid';";
    }

    mysql_query($sql);

    echo "<div style='padding: 10px;background: #ccc9a4;width: 300px'>Form has been Saved Successfully</div>";
}

$sql = "SELECT * FROM wr_coaches_form WHERE schoolid='$schoolid'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
echo "<p style='text-align: center;'>List below the Wrestling Coach(es) who will attend the event</p>";
echo "<h3 style='text-align: center;'><u>ONLY THOSE NAMES ON THIS FORM WILL BE ALLOWED TO HAVE A WRISTBAND OR LANYARD.</u></h3>";
echo "<form action='wr_coaches_form.php' method='post'>";
$form = '<table width="700px" cellspacing="0" cellpadding="5">';
$form .= '<tr><td>Head Coach:</td><td><input type="text" name="head_coach" size="50" value="' . $row['head_coach'] . '"></td></tr>';
$form .= '<tr><td>Assistant Coach 1:</td><td><input type="text" name="assistant_1" size="50" value="' . $row['assistant_1'] . '"></td></tr>';
$form .= '<tr><td>Assistant Coach 2:</td><td><input type="text" name="assistant_2" size="50" value="' . $row['assistant_2'] . '"></td></tr>';
$form .= '<tr><td>Assistant Coach 3:</td><td><input type="text" name="assistant_3" size="50" value="' . $row['assistant_3'] . '"></td></tr>';
$form .= '<tr><td>Assistant Coach 4:</td><td><input type="text" name="assistant_4" size="50" value="' . $row['assistant_4'] . '"></td></tr>';
$form .= '<tr><td>Assistant Coach 5:</td><td><input type="text" name="assistant_5" size="50" value="' . $row['assistant_5'] . '"></td></tr>';
$form .= '<tr><td>Assistant Coach 6:</td><td><input type="text" name="assistant_6" size="50" value="' . $row['assistant_6'] . '"></td></tr>';
$form .= '<tr><td>Assistant Coach 7:</td><td><input type="text" name="assistant_7" size="50" value="' . $row['assistant_7'] . '"></td></tr>';
$form .= '<tr><td>Assistant Coach 8:</td><td><input type="text" name="assistant_8" size="50" value="' . $row['assistant_8'] . '"></td></tr>';
$form .= '<tr><td>School:</td><td><input type="text" name="school" size="50" value="' . $row['school'] . '"></td></tr>';
$form .= '<tr><td>Class:</td><td><input type="text" name="class" size="50" value="' . $row['class'] . '"></td></tr>';
$form .= '<tr><td>Principal/Athletic Director:</td><td><input type="text" name="principal_or_athletic_director" size="50" value="' . $row['principal_or_athletic_director'] . '"></td></tr>';
$form .= '<table>';
$form .= "<input type='hidden' value='$session' name='session'/>";
$form .= "<input type='hidden' value='$schoolid' name='schoolid'/>";
echo $form;
echo "<br/><br/>";
echo "<input style='margin-left: 408px' type='submit' value='Save' name='save'/>";
echo "</form>";
?>
