<?php
require '../functions.php';
require 'functions.php';
require 'variables.php';

if($session)
{
    $header=$init_html.GetHeader($session);
    $level=GetLevel($session);
}
else
   $header=GetMainHeader();

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

if($session)
{
if(!$school_ch)
{
   $school=GetSchool($session);
}
else
{
   $school=$school_ch;
}
$school2=ereg_replace("\'","\'",$school);
}

echo $header;
echo "<br><br><table cellpadding=3 cellspacing=3 class='nine' style=\"width:600px;\"><caption><h2>";
if($type=="caucus") echo "<u>Class Caucus</u>";
else echo "<u>Legislative</u>";
echo " Proposals for Change in NSAA Regulations:</h2></caption>";
$sql="SELECT * FROM proposals WHERE verify='x' ";
if($type=="caucus") $sql.="AND type='caucus' ";
else $sql.="AND type!='caucus' ";
$sql.="ORDER BY district,class,yearbook DESC,article,actman2";
$result=mysql_query($sql);
$ix=0;
$curdist=""; $curclass="";
while($row=mysql_fetch_array($result))
{
   if($type!='caucus')
   {
      if($row[district]!=$curdist)  //new district 
      {
         $curdist=$row[district];
         echo "<tr align=left><td><b>District $curdist:</b></td></tr>";
         $ix=0;
      }
   }
   else
   {
      if($row['class']!=$curclass) //new class
      {
         $curclass=$row['class'];
         echo "<tr align=left><td><b>Class $curclass:</b></td></tr>";
         $ix=0;
      }
   }
   echo "<tr valign=top align=left>";
   $datesub=date("m/d/Y",$row[datesub]);
   echo "<td><a target=new href=\"attachments/$row[filename]\">$row[school] ($datesub)</a>";
   $dealswith="";
   if($row[yearbook]=='x')
   {
      $dealswith.="Yearbook";
      $colon=0;
      if($row[article]!="")
      {
         $colon=1;
         $dealswith.=": Art $row[article]";
      }
      if($row[section]!="")
      {
         if($colon==0)
	 {
  	    $colon=1;
	    $dealswith.=": Sec $row[section]";
         }
         else
            $dealswith.=", Sec $row[section]";
      }
      if($row[ybpage]!="")
      {
         if($colon==0)
            $dealswith.=": Pg $row[ybpage]";
         else
  	    $dealswith.=", Pg $row[ybpage]";
      }
   }
   if($row[actman]=='x')
   {
      if($dealswith!="")
 	 $dealswith.=" & ";
      $dealswith.="$row[actman2] Activities Manual";
      if($row[ampage]!="")
      {
         $dealswith.=": Pg $row[ampage]";
      }
   }
   echo " ($dealswith)";
   echo "</td></tr>";
   $ix++;
}
echo "</table>";
if($session)
{
   echo "<br><br><a href=\"welcome.php?session=$session\" class=small>Home</a>";
   echo $end_html;
}
else
{
   echo "<br><br><a href=\"/leg.php\"><< NSAA Legislative Home</a>";
   echo GetMainFooter();
}
?>
