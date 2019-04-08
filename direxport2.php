<?php
if($_REQUEST['get_argv']==1){
	$argv=array();
	if(isset($_GET['var1']))	$argv[1]=$_GET['var1'];
	if(isset($_GET['var2']))	$argv[2]=$_GET['var2'];
	if(isset($_GET['var3']))	$argv[3]=$_GET['var3'];
	if(isset($_GET['var4']))	$argv[4]=$_GET['var4'];
	if(isset($_GET['var5']))	$argv[5]=$_GET['var5'];
	if(isset($_GET['var6']))	$argv[6]=$_GET['var6'];
	if(isset($_GET['var7']))	$argv[7]=$_GET['var7'];
	if(isset($_GET['var8']))	$argv[8]=$_GET['var8'];
	if(isset($_GET['var9']))	$argv[9]=$_GET['var9'];
	if(isset($_GET['var10']))	$argv[10]=$_GET['var10'];
	if(isset($_GET['var11']))	$argv[11]=$_GET['var11'];
	if(isset($_GET['var12']))	$argv[12]=$_GET['var12'];
}
//diradmin.php: NSAA Directory Admin Page
set_time_limit(0);

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//verify user
if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

echo $init_html;

echo "<center><br>";
$x=0; $csv2="School,Last Update,Phone,";
for($i=0;$i<count($act_long);$i++)
{
   if(!ereg("Cheerleading",$act_long[$i]))
   {
      if(ereg("6/8",$act_long[$i])) 
      {
         $act_list[$x]="Football"; 
	 $csv2.=$act_list[$x].",E-mail,";
	 $x++;
      }
      else if(!ereg(" 11",$act_long[$i]))
      {
         $act_list[$x]=$act_long[$i]; 
         $csv2.=$act_list[$x].",E-mail,";
	 $x++;
      }
   }
}
$csv2.="\r\n";

$sql="SELECT school,dirupdate,phone FROM headers ORDER BY school";
$result=mysql_query($sql);
$showsch=array(); $ix=0; $ct=0;
$csv=array();
while($row=mysql_fetch_array($result))
{
   $temp=ereg_replace("\'","\'",$row[0]);
   $showsch[$ix]="";
   $cur_sch[$ix]=$row[0];
   $cur_date[$ix]=date("m/d/Y",$row[1]);
   $cur_ph[$ix]=$row[2];
   $csv[$ix]="$row[0] ,$cur_date[$ix],$cur_ph[$ix],";
   for($i=0;$i<count($act_list);$i++)
   {
      $abbrev=GetActivityAbbrev2($act_list[$i]);
      if(IsRegistered($row[0],$abbrev))
      {
         $sql2="SELECT name,email FROM logins WHERE school='$temp' AND sport LIKE '$act_list[$i]%' AND (name='' OR email='')";
         $result2=mysql_query($sql2);
         if(mysql_num_rows($result2)>0) 
         {
  	    $row2=mysql_fetch_array($result2);
  	    if(trim($row2[0])=='') 
	    {
	       $csv[$ix].="X,";
	    }
	    else 
	    {
	       $csv[$ix].=",";
	    }
	    if(trim($row2[1])=='') 
	    {
	       $csv[$ix].="X,";
	    }
	    else 
	    {
	       $csv[$ix].=",";
	    }
	 }
	 else
	 {
	    $csv[$ix].=",,";
	 }
      }
      else
      {
	 $csv[$ix].=",,";
      }
   }
   if(ereg("X,",$csv[$ix]))
   {
      $csv2.=$csv[$ix]."\r\n";
      $ct++;
   }
   $ix++;
} 
   
$cols=1+2*(count($act_list));
$open=fopen(citgf_fopen("dircsv.csv"),"w");
fwrite($open,$csv2);
fclose($open); 
 citgf_makepublic("dircsv.csv");
echo "<a href=\"dircsv.csv\" target=new>dircsv.csv</a>";

echo $end_html;
?>
