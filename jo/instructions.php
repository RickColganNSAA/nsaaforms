<style>
    div.alert {
        width: 500px;
        text-align: left;
        font-size: 9pt;
        margin: 2px 2px 2px 2px;
        padding: 4px 4px 4px 4px;
        background-color: #FAFAD2;
        border: 1px solid #808080;
    }
</style>
<?php
require '../functions.php';
require '../variables.php';
require '../../calculate/functions.php';

$header = GetHeader($session);
$level = GetLevel($session);

//connect to db:
$db = mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db($db_name, $db);

//verify user
if (!ValidUser($session) || $level != 1) {
    header("Location:../index.php");
    exit();
}
if (isset($_POST['update_instruction'])) {
    //update query
    $sql = "UPDATE instructions  SET submitted_instruction='$submitted_instruction',un_submitted_instruction='$un_submitted_instruction',general_instruction='$general_instruction',school_entry_form='$school_entry_form' WHERE type='$type'";
    mysql_query($sql);

    header("Location: instructions.php?session=$session&update=1&type=$type");
    exit();
}
$type = 'preliminary';
$preliminary_selected = "";
$state_selected = "";

if (isset($_GET['type'])) {
    $type = $_GET['type'];
    $sql = "SELECT * FROM instructions WHERE type='$type'";
    if ($type == 'preliminary') {
        $preliminary_selected = "selected='selected'";
    } else {
        $state_selected = "selected='selected'";
    }
} else {
    $sql = "SELECT * FROM instructions WHERE type='preliminary'";
    $preliminary_selected = "selected='selected'";
}
$result = mysql_query($sql);
$row = mysql_fetch_array($result);

echo $header;
if ($update == 1) {
    echo "<br><div class='alert' style='width:400px;text-align:center;'>Instructions has been updated.</div>";
}
echo "<br/>";
echo "<form id='form' action='instructions.php' method='post'>";
echo "<input type=hidden name='session' value='$session'>";
echo "<table width='400px'>";
echo "<tr>";
echo "<td>Instruction Type:</td>";
echo "</tr>";
echo "<tr>";
echo "<td><select id='type' onchange='changeType()' name='type' style='width: 100%'><option value='preliminary' $preliminary_selected>Preliminary</option><option value='state' $state_selected>State</option></select></td>";
echo "</tr>";
echo "<tr>";
echo "<td align='top'>General Instruction:</td>";
echo "</tr>";
echo "<tr>";
echo "<td><textarea name='general_instruction' cols='80' rows='6'>$row[general_instruction]</textarea></td>";
echo "</tr>";
echo "<tr><td align='top'>Ranked Instruction:</td></tr>";
echo "<tr>";
echo "<td><textarea name='submitted_instruction' cols='80' rows='6'>$row[submitted_instruction]</textarea></td>";
echo "</tr>";
echo "<tr>";
echo "<td align='top'>Un Ranked Instruction:</td>";
echo "</tr>";
echo "<tr>";
echo "<td><textarea name='un_submitted_instruction' cols='80' rows='6'>$row[un_submitted_instruction]</textarea></td>";
echo "</tr>";
echo "<tr>";
echo "<td align='top'>School Entry Form Instruction:</td>";
echo "</tr>";
echo "<tr>";
echo "<td><textarea name='school_entry_form' cols='80' rows='6'>$row[school_entry_form]</textarea></td>";
echo "</tr>";
echo "<tr><td align='right'><button name='update_instruction'>Update Instruction</button></td></td></tr>";
echo "</table>";
echo "</form>";
echo $end_html;
?>
<script>
    function changeType() {
        var type = document.getElementById("type").value;
        window.location = 'https://secure.nsaahome.org/nsaaforms/jo/instructions.php?session='+<?php echo $session?>+"&type=" + type;
        //window.location = 'https://secure.nsaahome.org/nsaaforms/jo/instructions.php?session='+<?php //echo $session?>+
        //"&type=" + type;
    }
</script>
<script src="https://cdn.ckeditor.com/4.10.0/full/ckeditor.js"></script>
<script>
    CKEDITOR.config.width = 850;
    CKEDITOR.replace('un_submitted_instruction');
    CKEDITOR.replace('submitted_instruction');
    CKEDITOR.replace('general_instruction');
    CKEDITOR.replace('school_entry_form');
</script>