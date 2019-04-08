<?php
//forms_query_submit.php: display links to results of advanced search
//	for forms

require 'functions.php';
require '../calculate/functions.php';
require 'variables.php';

$tedivs=array("singles1","singles2","doubles1","doubles2");

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

//check if user wants to cancel action
if($submit=="Cancel")
{
   header("Location:welcome.php?session=$session");
   exit();
}

//connect to db
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name",$db);

$level=GetLevel($session);;

$header=GetHeader($session);
/*
echo $school_array[0];
echo "<br>";
echo $activity_array[0];
echo "<br>";
echo $type;
exit();
*/

//if school is not chosen (meaning it is a given)
if(!$school_array[0])
{
   $school_array[0]=GetSchool($session);
}
else	//NSAA-Access, chose list of schools
{
   if($school_array[0]=="All Schools")
   { 
      //get list of schools into an array
      $ix=0;
      $sql="SELECT school FROM headers";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
	 $school_array[$ix]=$row[0];
	 $ix++;
      }
   }
}

$activity_array=array($activity_ch);
if($activity_array[0]=="All Activities")
{
   for($i=0;$i<count($act_long);$i++)
   {
      $activity_array[$i]=$act_long[$i];
   }
}


if($_GET['export']){  
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=data.csv');
	$output = fopen('php://output', 'w');
	//fputcsv($output, array('School','AD','AD Secretary','Coach'));
	fputcsv($output, array('AD','AD Secretary','Coach'));
	$sql="SELECT school,id FROM headers ORDER BY school";
	//$sql="SELECT DISTINCT t1.school FROM headers AS t1,fbschool AS t2,fbsched AS t3 WHERE (t1.id=t2.mainsch OR t1.id=t2.othersch1 OR t1.id=t2.othersch2 OR t1.id=t2.othersch3) AND (t2.sid=t3.sid OR t2.sid=t3.oppid) AND WEEK(t3.received)=34 ORDER BY t1.school";
   
    $total_ct=0;
	$ix=0;
	if (!empty($_GET['school_array1'])) $school_array =explode(" ",$_GET['school_array1']);
	$sql="SELECT school,id FROM headers ORDER BY school";
    $result=mysql_query($sql);
	while($row=mysql_fetch_array($result))
    {
	   if ($row[0]!='All')
         $school_array[]=addslashes($row[0]);
    }
	//$school_array=array_shift($school_array);
	//print_r($school_array); exit;
	//sort($school_array);
	for($i=0;$i<count($school_array);$i++)
	{
	   $num=$ix+1; 
	   $string="";
	   $count=0;
	  for($j=0;$j<count($activity_array);$j++)
	   {
		  //GET SCHOOL ID OF HEAD SCHOOL:
		  $schoolid=GetSchoolID2(GetCoopHeadSchool(GetSchoolID2($school_array[$i]),GetActivityAbbrev2($activity_array[$j])));
		  //GET SCHOOL NAME OF THE HEAD SCHOOL:
		   $school_reg[$i]=GetSchool2($schoolid); //school w/o escapes
	   //if($level==1 && $count>0)
	   if($level==1 )
	   {  
		  //$string.="<td>$school_reg[$i] ".GetADInfo($school_reg[$i])."</td>";
		//GET AD EMAIL FOR $emails TO BE COPIED
		$sql3="SELECT email FROM logins WHERE school='".addslashes($school_reg[$i])."' AND level=2";
		$sql4="SELECT email FROM logins WHERE school='".addslashes($school_reg[$i])."' AND  sport='AD Secretary' ";
		$sql5="SELECT email FROM logins WHERE school='".addslashes($school_reg[$i])."' AND  sport='".$activity_ch."'";
		$result3=mysql_query($sql3);
		$result4=mysql_query($sql4);
		$result5=mysql_query($sql5);
		while($row3=mysql_fetch_array($result3)) {$data1[ad][]=trim($row3[email]); $data1[school][] = addslashes($school_reg[$i]);}
		//if(preg_match("/@/",$row3[email])) $emails.=$row3[email].", ";
		while($row4=mysql_fetch_array($result4)) {$data1[sec][]=trim($row4[email]);}
		//if(preg_match("/@/",$row4[email])) $emails1.=$row4[email].", ";
		while($row5=mysql_fetch_array($result5)) {$data1[co][]=trim($row5[email]);}
		//if(preg_match("/@/",$row5[email])) $emails2.=$row5[email].", ";
		}
		
	   }
   }
   //print_r($data1); exit;
   for($j=1; $j<count($data1[school]);$j++)
   {
   // if (!empty($data1[school][$j]))
	   // {
	   //$data[$j][] = $data1[school][$j];
	   $data[$j][] = $data1[ad][$j];
	   $data[$j][] = $data1[sec][$j];
	   $data[$j][] = $data1[co][$j];
	  // }
   }
   //sort($data);
   foreach ($data as $value){
   fputcsv($output, $value);
   }
   
	 exit;
    }
	
	if(count($school_array)==1 && $school_array[0]!="All Schools" && count($activity_array)==1 && $activity_array[0]!="All Activities")
{
   $dir=GetActivityAbbrev($activity_array[0]);
   $file=GetActivityAbbrev2($activity_array[0]);
   if(ereg("fb",$dir)) $dir="fb";
   if(ereg("fb",$file)) $file="fb";
   $school_ch=$school_array[0];
   header("Location:$dir/view_$file.php?session=$session&school_ch=$school_ch");
   exit();
}

//if there's 1 school and 1 activity, take user directly to that form



echo $init_html; 
echo $header;
echo "<br><a class=small href=\"forms_query.php?session=$session\">Back to Forms Advanced Search</a><br><br>";
echo "<table frame=all rules=all cellspacing=0 cellpadding=5 style=\"border:#808080 1px solid;\">";

//Describe query:
echo "<caption><b>";
if($type=="all")
{
   echo "All Forms, Registered Schools Only:";
}
else if($type=="unedited")
{
   echo "Forms that have not been edited yet (by Registered Schools):";
}
else if($type=="edited")
{
   echo "Forms that have been edited (by Registered Schools)";
   if($limit1!="Any" && $limit1!=11)
   {
      echo " but have less than $limit1 entries";
   }
   else if($limit1==11)
   {
      echo " and have more than 10 entries";
   }
   echo ":";
}
else	//both
{
   echo "Forms that have not been edited (by Registered Schools)";
   if($limit2!="Any" && $limit2!=11)
   {
      echo " or that have less than $limit2 entries";
   }
   else if($limit2==11)
   {
      echo " and have more than 10 entries";
   }
   echo ":";
}
echo "</b><br></caption>";
if(ereg("Tennis",$activity_array[0]))
   echo "<tr align=center><td colspan=2><b>SCHOOL</b></td><td><b>FORM</b></td><td>#1S</td><td>#2S</td><td>#1D</td><td>#2D</td><td><b>CONTACT INFO</b></td></tr>";
 //echo'<pre>';print_r($school_array); //exit;  
sort($school_array);
$total_ct=0;
$ix=0;
$emails="";
$emails1="";
$emails2="";
for($i=0;$i<count($school_array);$i++)
{
   $num=$ix+1; 
   $string="";
   $string.="<tr align=left><td>$num.</td>";
   $count=0;
   if($level==1)
      $string.="<td><b>$school_array[$i]:</b></td>";
   $string.="<td>";
   for($j=0;$j<count($activity_array);$j++)
   {
      //GET SCHOOL ID OF HEAD SCHOOL:
      $schoolid=GetSchoolID2(GetCoopHeadSchool(GetSchoolID2($school_array[$i]),GetActivityAbbrev2($activity_array[$j])));
      //GET SCHOOL NAME OF THE HEAD SCHOOL:
      $school_reg[$i]=GetSchool2($schoolid); //school w/o escapes
      $school_array[$i]=addslashes($school_reg[$i]);
      if(ereg("Tennis",$activity_array[$j]))
      {
         if(ereg("Boys",$activity_array[$j])) $form_name="te_b";
         else $form_name="te_g";
         if(ereg("Class A",$activity_array[$j])) $form_name.="_a";
         else $form_name.="_b";
      }
      else $form_name=GetActivityAbbrev2($activity_array[$j]);
      if($form_name=='mu')	//MUSIC
      {
	 $sql="SELECT * FROM muschools WHERE school='$school_array[$i]'";
      }
      else if(ereg("te_",$form_name))	//TENNIS
      {
	 $temp=explode("_",$form_name);
	 if(is_array($temp)) $class=end($temp);
	 else $class="";
         $form_name=substr($form_name,0,strlen($form_name)-2);
         $schooltable=GetSchoolsTable($form_name);

	 //if($class=="a")	//STATE FORM te_gstate or te_bstate - AS OF 2012, NO MORE DISTRICTS FOR EITHER CLASS
            $form_name.="state";
	 //ELSE, DISTRICT FORM te_b or te_g

         $sql="SELECT t1.*,t2.class FROM ".$form_name." AS t1, ".$schooltable." AS t2,headers AS t3 WHERE t1.sid=t2.sid AND t2.mainsch=t3.id AND t3.school='$school_array[$i]'";
      }
      else if($form_name=="pp")	//PLAY PRODUCTION
      {
	 $sql="SELECT t1.* FROM pp_students AS t1, eligibility AS t2 WHERE t1.student_id=t2.id AND (t2.school='$school_array[$i]' OR t1.co_op='$school_array[$i]')";
      }
      else if(ereg("tr_",$form_name))	//TRACK & FIELD
      {
	 $sql="SELECT * FROM $form_name WHERE school='$school_array[$i]'";
      }
      else if(ereg("sw_",$form_name))	//SWIMMING
      {
         $newform=ereg_replace("sw_","sw_verify_",$form_name);
	 $sql="SELECT * FROM $newform WHERE school='$school_array[$i]'";
      }
      else if($form_name=='de') //DEBATE
      {
	 $sql="SELECT * FROM de WHERE school='$school_array[$i]'";
      }
      else	//ALL OTHERS
      {
         $sql="SELECT * FROM $form_name WHERE (school='$school_array[$i]' OR co_op='$school_array[$i]') AND checked='y'";
      }
      $result=mysql_query($sql);
      $ct=mysql_num_rows($result);
      if($form_name=='mu' && $ct>0)	//if edited music form
      {
         $sql="SELECT t1.id FROM muentries AS t1, muschools AS t2 WHERE t2.school='$school_array[$i]' AND t2.id=t1.schoolid";
	 $result=mysql_query($sql);
	 $ct=mysql_num_rows($result);
      }
      if($schoolid>0 && ($type=="all" || ($type=="unedited" && $ct==0) || ($type=="edited" && $ct>0 && ($limit1=="Any" || ($limit1!="Any" && $limit1!="11" && $ct<$limit1) || ($limit1==11 && $ct>=10))) || ($type=="both" && ($ct==0 || ($limit2=="Any" || ($limit2!="Any" && $limit2!=11 && $ct<$limit2) || ($limit2==11 && $ct>=10))))))
      {
	 if(ereg("fb",$form_name)) $form="fb";
         else if(ereg("te",$form_name)) $form=ereg_replace("state","",$form_name);
	 else $form=$form_name;
         if(ereg("te",$form))
	 {
	    $sid=GetSID2($school_reg[$i],$form,date("Y"));
	    $sql="SELECT * FROM ".$form."school WHERE sid='$sid'";
	    $result=mysql_query($sql);
	    $row=mysql_fetch_array($result);
	    if(!IsHeadSchool(GetSchoolID2($school_reg[$i],$form),$form)) $row['class']="";
	 }
	 //check if school is registered for this activity:
	 if((!ereg("te",$form) || $row['class']==strtoupper($class)))
	 {
	    $dir=GetActivityAbbrev($activity_array[$j]);
	    if(ereg("fb",$dir)) $dir="fb";
	    if(ereg("te_",$form_name))
	    {
                $dir="te";
                if($class=="a") $formbase="state";
                else $formbase="edit";
                $string.="<a target=\"_blank\" class=small href=\"$dir/".$formbase."_".$form.".php?session=$session&school_ch=$sid\">$activity_array[$j]</a>";

	        for($t=0;$t<count($tedivs);$t++)
		{
		   $string.="</td><td";
	    	   $sql="SELECT * FROM $form_name WHERE sid='$sid' AND division='".$tedivs[$t]."'";
	    	   $result=mysql_query($sql);
	           if(mysql_num_rows($result)==0)	//Check for "No entry" checkmark
	           {
	   	      $sql2="SELECT * FROM ".$form_name."noentries WHERE sid='$sid' AND division='".$tedivs[$t]."'";
	  	      $result2=mysql_query($sql2);
		      if(mysql_num_rows($result2)==0)  $string.=" bgcolor='#ff0000'>&nbsp;";
	 	      else $string.=">No Entry";
	 	   }
		   else $string.=">X";
	        }
	    }
            else
	       $string.="<a target=new class=small href=\"$dir/view_$form_name.php?header=no&session=$session&school_ch=$school_reg[$i]\">$activity_array[$j]</a>";
	    if(IsRegistered2011($schoolid,$form))
		$string.="</td><td><b>REGISTERED</b>";
	    else $string.="</td><td bgcolor='red'><b><u>NOT</b></u> REGISTERED";
	    if($form_name=='mu')
            {
	       $sql2="SELECT submitted FROM muschools WHERE school='$school_array[$i]'";
	       $result2=mysql_query($sql2);
	       $row2=mysql_fetch_array($result2);
	       if($row2[0]!='') 
		  $string.="</td><td>Submitted ".date("m/d",$row2[0]);
	       else
	          $string.="</td><td><font style=\"color:red\">Not Submitted</font>";
	    }
	    $count++;
	 }
      }
   }
   $string.="</td>";
   if($level==1 && $count>0)
   {
      $string.="<td>$school_reg[$i] ".GetADInfo($school_reg[$i])."</td>";
	//GET AD EMAIL FOR $emails TO BE COPIED
	$sql3="SELECT email FROM logins WHERE school='".addslashes($school_reg[$i])."' AND level=2";
	$sql4="SELECT email FROM logins WHERE school='".addslashes($school_reg[$i])."' AND  sport='AD Secretary' ";
	$sql5="SELECT email FROM logins WHERE school='".addslashes($school_reg[$i])."' AND  sport='".$activity_ch."'";
	$result3=mysql_query($sql3);
	$result4=mysql_query($sql4);
	$result5=mysql_query($sql5);
	while($row3=mysql_fetch_array($result3))
	if(preg_match("/@/",$row3[email])) $emails.=$row3[email].", ";
		while($row4=mysql_fetch_array($result4))
	if(preg_match("/@/",$row4[email])) $emails1.=$row4[email].", ";
		while($row5=mysql_fetch_array($result5))
	if(preg_match("/@/",$row5[email])) $emails2.=$row5[email].", ";
   }
   $string.="</tr>";
   if($count>0) 
   {
      echo $string;
      $total_ct+=$count;
      $ix++;
   }
   //if(($ix+1)%4==0) echo "</tr>";  end row of schools
}
?>
</td>
</tr><!--End Row of Schools-->
</table>
<br><font size=2>
<b>Your search returned <?php echo $total_ct; ?>
<?php
if($total_ct==1) echo " result.";
else echo " results.";

if($emails!='')
{
   $emails=substr($emails,0,strlen($emails)-2);
   $emails1=substr($emails1,0,strlen($emails1)-2);
   $emails2=substr($emails2,0,strlen($emails2)-2);
   echo "<p><b>Copy the email addresses listed above:</b></p><textarea name='emails' style='font-size:11px;width:600px;height:150px;'>$emails</textarea>";
   echo "<p><b>Copy the email addresses of AD Secretary:</b></p><textarea name='emails' style='font-size:11px;width:600px;height:150px;'>$emails1</textarea>";
   echo "<p><b>Copy the email addresses of $activity_ch Coaches:</b></p><textarea name='emails' style='font-size:11px;width:600px;height:150px;'>$emails2</textarea>";
}
foreach ($school_array as $sc_array)
{
 if (!empty($sc_array))$school_ar[]=$sc_array;
}
$school_arr= implode(",",$school_ar);
//echo '<pre>';print_r($school_arr); 
echo "<br><br><a href=\"forms_query_submit.php?session=$session&export=export&submit=submit&activity_ch=$activity_ch&school_array1=$school_arr\">Export Email</a>";
echo "<br><br><a class=small href=\"forms_query.php?session=$session\">Back to Forms Advanced Search</a>";
?>
</b></font>

</td><!--End Main Body-->
</tr>
</table>
</body>
</html>
