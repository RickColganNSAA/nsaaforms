<?php
/*****************************************
updateenrollment.php
Update Enrollment Numbers for schools
yearly via import
Created 7/7/14 by Ann Gaffigan
*****************************************/
require 'functions.php';
require 'variables.php';
require '../calculate/functions.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
$level=GetLevel($session);
if(!ValidUser($session) || $level!=1)
{
   header("Location:index.php");
   exit();
}

echo $init_html;
echo GetHeader($session);

//IMPORT THE UPLOADED FILE, IF ONE
$uploadedfile = $_FILES['importfile']['tmp_name'];
if($import && is_uploaded_file($uploadedfile))
{
   $tempfile="tempenroll.csv";
   $errors="";
   if(!citgf_copy($uploadedfile,"/home/nsaahome/attachments/".$tempfile))
      $errors.="<p>Could not copy import file.</p>";
   else
   {
      $open=fopen(citgf_fopen("/home/nsaahome/attachments/".$tempfile),"r");
      $lines=file(getbucketurl("/home/nsaahome/attachments/".$tempfile));
      fclose($open);
      $ct=0; $nomatches="";
      for($i=0;$i<count($lines);$i++){
        //School ID, School, Enrollment
        $line=explode(",",$lines[$i]);
        $line[0]=trim($line[0],'"');//school id
        $line[1]=trim($line[1],'"');//school name
        $line[2]=trim($line[2],'"');//enrollment
        //code added by robin
        $line[3]=trim($line[3],'"');//boys enrollment
        $line[4]=trim($line[4],'"');//girls enrollment
        //end of robin
        if($line[0]>0 && trim($line[0])!=''){
            $sql="SELECT * FROM headers WHERE id='$line[0]'";
            $result=mysql_query($sql);
            if(mysql_num_rows($result)==0){
                $nomatches.="<p>No matching record found for School ID $line[0] ($line[1]).</p>";
            }
            else{	
                $line[2]=preg_replace("/[^0-9]/","",$line[2]);
                //code added by robin
                $line[3]=preg_replace("/[^0-9]/","",$line[3]);
                $line[4]=preg_replace("/[^0-9]/","",$line[4]);
                if(empty($line[2])&&!empty($line[3])&&!empty($line[4])){
                    $line[2]=(int)$line[3]+(int)$line[4];
                }
                //end of robin
                $sql="UPDATE headers SET enrollment='$line[2]', boysenrollment ='$line[3]',girlsenrollment='$line[4]'  WHERE id='$line[0]'";
                $result=mysql_query($sql);
                $ct++;
            }
        }
    }
   }
}

//GENERATE THE TEMPLATE FILE
$sql="SELECT * FROM headers ORDER BY school";
$result=mysql_query($sql);
// $csv="\"School ID\",\"School\",\"Enrollment\"\r\n,\"Boys Enrollment\"\r\n,,\"Girls Enrollment\"\r\n";
$csv="School ID,School,Enrollment,Boys Enrollment,Girls Enrollment\n";
while($row=mysql_fetch_array($result))
{
   //$csv.="\"$row[id]\",\"$row[school]\",\"\"\r\n,\"\"\r\n,\"\"\r\n";
   $csv.="{$row[id]},{$row[school]}\n";
}
$filename="Enrollment.csv";
$open=fopen(citgf_fopen("/home/nsaahome/attachments/".$filename),"w");
fwrite($open,$csv);
fclose($open); 
 citgf_makepublic("/home/nsaahome/attachments/".$filename);

echo "<form method=post action=\"updateenrollment.php\" enctype=\"multipart/form-data\">";
echo "<input type=hidden name=\"session\" value=\"$session\">";
echo "<br /><h1>Update Enrollment Numbers:</h1>";
echo "<p>Each year, you can update the enrollment numbers for member schools by following the directions below.</p>";
echo "<p>You can view enrollment numbers for schools by using the <a href=\"diradmin.php?session=$session\">Directory Advanced Search</a>.</p>";
echo "<div style=\"width:700px;\">";
if($import && $errors!='')
{
   echo "<div class=\"error\">$errors</div>";
}
else if($import && ($ct>0 || $nomatches!=''))
{
   echo "<div class=\"alert\"><p><b><u>$ct</b></u> schools were successfully updated!</p>";
   if($nomatches!='')
      echo $nomatches;
   echo "</div>";
}
else if($import && !is_uploaded_file($uploadedfile))
{
   echo "<div class=\"error\">Oops! There didn't seem to be a file uploaded.</div>";
}
echo "<ol>";
echo "<li><b>Download the </b> <a href=\"attachments.php?filename=$filename&session=$session\">TEMPLATE FILE</a>.</li><br />";
echo "<li>Open the template file in Excel. <b>Enter the enrollment number</b> for each school in the \"Enrollment\" column. <b>Do NOT edit the School ID or School columns.</b> If you wish to only update SOME of the schools' enrollment figures, remove all other schools from the template file before uploading it below. Only schools included in your file will be updated.</li><br />";
echo "<li><b>Save the file as</b> a <b>\"Comma-Separated (.csv)\"</b> file. (The file name does not matter.)</li><br />";
echo "<li><b>Upload your file here: </b><input type=file name=\"importfile\"></li><br />";
echo "<li><b>Click \"Import:\"</b> <input type=submit name=\"import\" value=\"Import\"></li><br />";
echo "</ol>";
echo "</div>";
echo "</form>";

echo $end_html;
?>
