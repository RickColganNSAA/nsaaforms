<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:index.php?error=1");
   exit();
}

//get next senttofed number
$sql="SELECT senttofed FROM officials ORDER BY senttofed DESC LIMIT 1";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$senttofed=$row[0];
$senttofed++;
$sql="SELECT * FROM officials WHERE senttofed='0'";
$result=mysql_query($sql);
$csv="\"ArbiterUserId\",\"StateUserId\",\"FirstName\",\"LastName\",\"Email\",\"Address1\",\"Address2\",\"City\",\"State\",\"Zip\",\"DateOfBirth\",\"Password\",\"HomePhone\",\"WorkPhone\",\"Fax\",\"CellularPhone\",\"OtherPhone\"\r\n";
while($row=mysql_fetch_array($result))
{
   if(HasPaid($row[id],"any")) //check to see if official has paid for ANY sport
   {
      $csv.="\"\",\"\",\"$row[first]\",\"$row[last]\",\"$row[email]\",\"$row[address]\",\"\",\"$row[city]\",\"$row[state]\",\"$row[zip]\",\"\",\"\",\"$row[homeph]\",\"$row[workph]\",\"\",\"$row[cellph]\",\"\"\r\n";
      //$csv.="\"$row[socsec]\",\"\",\"\",\"\",\"$row[first]\",\"$row[middle]\",\"$row[last]\",\"\",\"\",\"$row[address]\",\"\",\"$row[city]\",\"$row[state]\",\"$row[zip]\",\"$row[homeph]\",\"$row[email]\"\r\n"; 
      $sql2="UPDATE officials SET senttofed='$senttofed' WHERE id='$row[id]'";
      $result2=mysql_query($sql2);
   }
}

//write to file
$open=fopen(citgf_fopen("/home/nsaahome/reports/fedexport".$senttofed.".csv"),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/reports/fedexport".$senttofed.".csv");
header("Location:reports.php?session=$session&filename=fedexport".$senttofed.".csv");

exit();

?>
