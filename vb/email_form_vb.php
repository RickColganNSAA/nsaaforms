<?php 
require '../functions.php';
require '../variables.php';

$sch=ereg_replace(" ","",$school);
$sch=ereg_replace("-","",$sch);
$sch=ereg_replace("\.","",$sch);
$sch=strtolower($sch);
$activ_lower=strtolower($activ);
$activ_lower=ereg_replace(" ","",$activ_lower);

$html_name="$sch$activ_lower.html";
$csv_name="$sch$activ_lower.csv";

$From="no-reply@nsaahome.org";
$FromName="$school $activ";
$To=$email;
$ToName="$activ District Director";
$Subject="$school $activ Entry Form";
$Text="The $activ entry form for $school is attached as both a .csv file and as a .html file.";
$Html="<html><b>";
$Html.="The $activ entry form for $school is attached in 2 formats:<br>";
$Html.="<br>Comma-Separated (CSV) and .HTML<br><br>Thank you!</b></html>";
$AttmFiles=array($csv_name,$html_name);

SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles);

unlink($csv_name);
unlink($html_name);
?>
<script language="javascript">
window.close();
</script>
