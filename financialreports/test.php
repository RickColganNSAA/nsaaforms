<?php
require '../functions.php';
require_once('../variables.php');
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name,$db);

//echo "NOP";
//exit();

/*
$open=fopen(citgf_fopen("finance_vb_exp110509.csv"),"r");
$lines=file("finance_vb_exp110509.csv");
fclose($open);

for($i=0;$i<count($lines);$i++)
{
   $lines[$i]=ereg_replace("\"","",$lines[$i]);
   $line=split(";",$lines[$i]);
   $class=$line[1]; $district=$line[2];
   $sql="SELECT * FROM $db_name2.vbdistricts WHERE class='$class' AND district='$district' AND type='Subdistrict";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);   
   if($line[0]=="919" || $line[0]=="982" || $line[0]=="1061")
   {
   $sql="INSERT INTO finance_vb_exp (distid,school,miles1way,trips,miles,mileagedue,mileagepaid,matches,bonus,totalpaid) VALUES ('$row[id]','".addslashes($line[4])."','$line[5]','$line[6]','$line[7]','$line[8]','$line[9]','$line[10]','$line[11]','$line[12]')";
   $result=mysql_query($sql);
   echo "$sql<br>".mysql_error()."<br>";
   }
}
*/

$sql="SELECT * FROM finance_vb";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
    $offtotal=$row[offfees]+$row[offmilespaid];
    $offtotal=number_format($offtotal,2,'.','');
    $sql2="UPDATE finance_vb SET offtotal='$offtotal' WHERE id='$row[id]'";
   $result2=mysql_query($sql2);
   echo "$sql2\r\n";
}
echo "DONE\r\n";
?>
