<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

require 'variables.php';
require 'functions.php';

if(!ValidUser($session) || GetLevel($session)!=1)
{
   header("Location:index.php");
   exit();
}
    if($_GET['file']){  
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=data.csv');
	$output = fopen('php://output', 'w');
	fputcsv($output, array('School','AD','AD Secretary','Coach'));
	$abbrev=GetActivityAbbrev($act_ch);
	$sql="SELECT school,id FROM headers ORDER BY school";
	//$sql="SELECT DISTINCT t1.school FROM headers AS t1,fbschool AS t2,fbsched AS t3 WHERE (t1.id=t2.mainsch OR t1.id=t2.othersch1 OR t1.id=t2.othersch2 OR t1.id=t2.othersch3) AND (t2.sid=t3.sid OR t2.sid=t3.oppid) AND WEEK(t3.received)=34 ORDER BY t1.school";
    $result=mysql_query($sql);
    $ix=0;
    $schools=array(); $schools2=array();
    while($row=mysql_fetch_array($result))
    {
      if((IsRegistered($row[0],GetActivityAbbrev2($act_ch)) || IsRegistered($row[0],GetActivityAbbrev2($act_ch),'w')) && IsHeadSchool($row[id],GetActivityAbbrev2($act_ch)))
      {
         $schools[$ix]=$row[0]; $schools2[$ix]=addslashes($row[0]);
         $ix++;
      }
    }
	if($abbrev!="fb" && $fbweek0!='y')
       $sql1="SELECT * FROM eligibility WHERE school='$schools2[0]' AND $abbrev='x'";
    else
      $sql1="SELECT * FROM eligibility WHERE school='$schools2[0]' AND (fb68='x' OR fb11='x')";
    if($gender=='F' || $gender=='M') $sql1.=" AND gender='$gender'";
    $num=1;

   for($i=1;$i<=count($schools);$i++)
   {  
      $temp=$i-1;
      if($abbrev!="fb" && $fbweek0!='y')
      $sql11="SELECT * FROM eligibility WHERE school='".addslashes($schools[$temp])."' AND $abbrev='x'";
      else
      $sql11="SELECT * FROM eligibility WHERE school='".addslashes($schools[$temp])."' AND (fb68='x' OR fb11='x')";
      if($gender=='F' || $gender=='M') $sql1.=" AND gender='$gender'";
	
	  $result11=mysql_query($sql11); if(mysql_error()) echo "$sql1<br>";
      $ct=mysql_num_rows($result11);
      $temp=$i-1;
       if(($equality=="<=" && $ct<=$number)||($equality==">=" && $ct>=$number))
       {   
	  $data1[school][]= addslashes($schools[$temp]);
	  
	 //GET JUST EMAIL
	 //$sql3="SELECT email FROM logins WHERE school='".addslashes($schools[$temp])."' AND level=2";
	 $sql3="SELECT email FROM logins WHERE school='".addslashes($schools[$temp])."' AND level=2 ";
	 $sql4="SELECT email FROM logins WHERE school='".addslashes($schools[$temp])."' AND sport='AD Secretary' ";
	 //$sql5="SELECT email FROM logins WHERE school='".addslashes($schools[$temp])."' AND sport='".$act_ch."'";
	 $sql5="SELECT DISTINCT email FROM logins WHERE school='".addslashes($schools[$temp])."' AND sport  LIKE '".$act_ch."%' ";
	 $result3=mysql_query($sql3);
	 $result4=mysql_query($sql4);
	 $result5=mysql_query($sql5);
	 while($row3=mysql_fetch_array($result3)) {
	 //if(trim($row3[email])!='') 
	 $data1[ad][]=trim($row3[email]);//$emailstr.=trim($row3[email])." ";
	 }
	 while($row4=mysql_fetch_array($result4)) {
	 //if(trim($row4[email])!='') 
	 $data1[sec][]=trim($row4[email]);//$emailstr1.=trim($row4[email])." ";
	 }
	 while($row5=mysql_fetch_array($result5)) {
	 //if(trim($row5[email])!='') 
	 $data1[co][]=trim($row5[email]);//$emailstr2.=trim($row5[email])." ";
	 }
	 $num++;
	 //unset($ct);
	 //fputcsv($output, $data1[school]);
      }
   
   }
   // 
   for($j=0; $j<count($data1[school]);$j++)
   {
   $data[$j][] = $data1[school][$j];
   $data[$j][] = $data1[ad][$j];
   $data[$j][] = $data1[sec][$j];
   $data[$j][] = $data1[co][$j];
   }
  // print_r($data); exit;
   foreach ($data as $value){
   fputcsv($output, $value);
   }
	 exit;
    }	

echo $init_html;
echo GetHeader($session);
//echo '<pre>'; print_r($_POST); exit;
if($submit)
{
   if(($act_ch=="" && $fbweek0!='y') || $number=="")
   {
      echo "<br><br>You must select an activity AND enter a number.<br><br>";
      echo "<a class=small href=\"welcome.php?session=$session&toggle=menu2\">Try Again</a>";
      echo $end_html;
      exit();
   }
   if($fbweek0!='y')
   {
   $abbrev=GetActivityAbbrev($act_ch);
   if(ereg("Girls",$act_ch)) $gender="F";
   else if(ereg("Boys",$act_ch)) $gender="M";
   else $gender="ALL";
   if(ereg("Cross",$act_ch) && ereg("Boys",$act_ch))
	$sportreg="Boys_CC";
   else if(ereg("Cross",$act_ch) && ereg("Girls",$act_ch))
	$sportreg="Girls_CC";
   else if(ereg("Track",$act_ch) && ereg("Boys",$act_ch))
	$sportreg="Boys_Track";
   else if(ereg("Track",$act_ch) && ereg("Girls",$act_ch))
	$sportreg="Girls_Track";
   else $sportreg=ereg_replace(" ","_",$act_ch);
   $sql="SELECT school,id FROM headers ORDER BY school";
   $result=mysql_query($sql);
   $ix=0;
   $schools=array(); $schools2=array();
   while($row=mysql_fetch_array($result))
   {
      if((IsRegistered($row[0],GetActivityAbbrev2($act_ch)) || IsRegistered($row[0],GetActivityAbbrev2($act_ch),'w')) && IsHeadSchool($row[id],GetActivityAbbrev2($act_ch)))
      {
         $schools[$ix]=$row[0]; $schools2[$ix]=addslashes($row[0]);
         $ix++;
      }
   }
   echo "<br><p><b>All schools registered for $act_ch who have checked $equality $number participants:</b></p><table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">";
   }
   else
   {
      $sql="SELECT DISTINCT t1.school FROM headers AS t1,fbschool AS t2,fbsched AS t3 WHERE (t1.id=t2.mainsch OR t1.id=t2.othersch1 OR t1.id=t2.othersch2 OR t1.id=t2.othersch3) AND (t2.sid=t3.sid OR t2.sid=t3.oppid) AND WEEK(t3.received)=34 ORDER BY t1.school";
      $result=mysql_query($sql);
      $ix=0;
      $schools=array(); $schools2=array();
      while($row=mysql_fetch_array($result))
      {
         $schools[$ix]=$row[0]; $schools2[$ix]=addslashes($row[0]);
         $ix++;
      }
      echo "<br><p><b>All schools with a Week 0 Football game and who have checked $equality $number participants:</b></p><table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#808080 1px solid;\">";
   }
   
   
   if($abbrev!="fb" && $fbweek0!='y')
      $sql="SELECT * FROM eligibility WHERE school='$schools2[0]' AND $abbrev='x'";
   else
      $sql="SELECT * FROM eligibility WHERE school='$schools2[0]' AND (fb68='x' OR fb11='x')";
   if($gender=='F' || $gender=='M') $sql.=" AND gender='$gender'";
   $num=1;
   echo "<tr align=center><td colspan=2><b>School</b></td><td><b>Count</b></td><td><b>Contact</b></td></tr>";
   $emailstr="";
   $emailstr1="";
   $emailstr2="";
   for($i=1;$i<=count($schools);$i++)
   {  
      $result=mysql_query($sql); if(mysql_error()) echo "$sql<br>";
      $ct=mysql_num_rows($result);
      $temp=$i-1;
      if(($equality=="<=" && $ct<=$number)||($equality==">=" && $ct>=$number))
      {
	 $sql2="SELECT t1.name,t1.phone,t1.email,t2.phone AS schphone FROM logins AS t1, headers AS t2 WHERE t1.school=t2.school AND t1.school='".$schools2[$temp]."' AND t1.level='2'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
         $adphone=GetADInfo(addslashes($schools[$temp]));
         echo "<tr align=left><td align=right>$num)</td><td>$schools[$temp]</td><td align=center>$ct</td><td>$adphone</td></tr>";
	 //GET JUST EMAIL
	 //$sql3="SELECT email FROM logins WHERE school='".addslashes($schools[$temp])."' AND level=2";
	 $sql3="SELECT email FROM logins WHERE school='".addslashes($schools[$temp])."' AND level=2 ";
	 $sql4="SELECT email FROM logins WHERE school='".addslashes($schools[$temp])."' AND sport='AD Secretary' ";
	 //$sql5="SELECT email FROM logins WHERE school='".addslashes($schools[$temp])."' AND sport='".$act_ch."'";
	 $sql5="SELECT DISTINCT email FROM logins WHERE school='".addslashes($schools[$temp])."' AND sport  LIKE '".$act_ch."%' ";
	 $result3=mysql_query($sql3);
	 $result4=mysql_query($sql4);
	 $result5=mysql_query($sql5);
	 while($row3=mysql_fetch_array($result3)) {
	 if(trim($row3[email])!='') $emailstr.=trim($row3[email]).", ";
	 }
	 while($row4=mysql_fetch_array($result4)) {
	 if(trim($row4[email])!='') $emailstr1.=trim($row4[email]).", ";
	 }
	 while($row5=mysql_fetch_array($result5)) {
	 if(trim($row5[email])!='') $emailstr2.=trim($row5[email]).", ";
	 }
	 $num++;
      }
      if($i!=count($schools))
      {
	 if($abbrev!="fb" && $fbweek0!='y')
	    $sql="SELECT * FROM eligibility WHERE school='$schools2[$i]' AND $abbrev='x'";  
	 else
	    $sql="SELECT * FROM eligibility WHERE school='$schools2[$i]' AND (fb68='x' OR fb11='x')";
	 if($gender=='F' || $gender=='M') $sql.=" AND gender='$gender'";
      }
	  
  
   }
   echo "</table>";
   if($emailstr!='') $emailstr=substr($emailstr,0,strlen($emailstr)-2);
   echo "<br><p>Use the box below to copy all of the email addresses listed above:</p><textarea style=\"width:500px;height:150px;\" name=\"emailstr\">$emailstr</textarea>";
   echo "<br><p>Use the box below to copy all of the email addresses of AD Secretary:</p><textarea style=\"width:500px;height:150px;\" name=\"emailstr1\">$emailstr1</textarea>";
   echo "<br><p>Use the box below to copy all of the email addresses of $act_ch Coaches:</p><textarea style=\"width:500px;height:150px;\" name=\"emailstr2\">$emailstr2</textarea>";
   echo "<br><br><a href=\"eliglistsquery.php?session=$session&file=remaining&submit=search&act_ch=$act_ch&number=$number&equality=$equality\">Export Email</a>";
   echo "<br><br><a href=\"welcome.php?session=$session&toggle=menu2\">Search Again</a>";
   echo "</td></tr></table></body></html>";
   //exit();
}
else
{
   echo "<br><br>Error";
}

?>
