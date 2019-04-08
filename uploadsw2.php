<?php
require_once ($_SERVER["DOCUMENT_ROOT"].'/dbfunction.php');

copy($sqlfile,"sw/hytek/$newfilename");
$open=fopen(citgf_fopen("sw/hytek/$newfilename"),"r");
$line=file(getbucketurl("sw/hytek/$newfilename"));
fclose($open);
$open=fopen(citgf_fopen("sw/hytek/$newfilename"),"w");
for($i=1;$i<(count($line)-1);$i++)
{
   fwrite($open,$line[$i]);
}
fclose($open); 
 citgf_makepublic("sw/hytek/$newfilename");

//echo $init_html;
//echo "<center><br><br>";
echo "The new SQL database file has been successfully uploaded.<br><br>";
echo "Please <a href=\"updatesw.php?session=$session&file=$newfilename\">Click Here</a> to update the existing database.";
//echo $end_html;
//header("Location:updatesw.php?session=$session&file=$newfilename");
exit();

?>
