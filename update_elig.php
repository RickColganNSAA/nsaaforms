<?php
require $_SERVER['DOCUMENT_ROOT'].'/nsaaforms/variables.php';
//update_elig.php: takes submitted information from eligibility.php
//	(elig_list.php) and updates the db

require 'functions.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
   exit();
}

//connect to db:
$db=mysql_connect("$db_host","$db_user","$db_pass");
mysql_select_db("$db_name", $db);

//get level of user
$level=GetLevel($session);

$count=count($student_id);

$new_swimmers=array();
$swix=0;
for($i=0;$i<$count;$i++)
{
   //don't let level 2 set foreign exchange student to eligible; NSAA only
   //send e-mail if new swimmer added
   $sql="SELECT eligible, eligible_comment,sw FROM eligibility WHERE id='$student_id[$i]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $old_eligible=$row[0];
   $old_eligible_comment=$row[1];
   $old_sw=$row[2];
   if($old_eligible!="y" && ereg("International Transfer; Missing Paperwork",$old_eligible_comment) && $level!=1)
   {
      $eligible[$i]=$old_eligible;
      $eligible_comment[$i]=$old_eligible_comment;
   }
   else if($old_eligible!="y" && ereg("International Transfer; Missing Paperwork",$old_eligible_comment) && $eligible[$i]=="y" && $level==1)
   {
      $eligible_comment[$i]="";
   }
   else
   {
      $eligible_comment[$i]=$old_eligible_comment;
   }
   if($old_sw!='x' && $sw[$i]=='x')	//new swimmer added
   {
      $new_swimmers[$swix]=$student_id[$i];
      $swix++;
   }

/*hide new eo
   //if EO is checked, check T as well
   if($enroll_option[$i]=='y')
   {
      $transfer[$i]='y';
   }
*/

   //check that student with a sem of 0 is only participating in Music 
   $sql="SELECT semesters FROM eligibility WHERE id='$student_id[$i]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   if($row[0]==0)
   {
      $fb68[$i]=""; $fb11[$i]=""; $vb[$i]=""; $sb[$i]=""; $cc[$i]="";
      $te[$i]=""; $bb[$i]=""; $wr[$i]=""; $sw[$i]=""; $go[$i]="";
      $tr[$i]=""; $ba[$i]=""; $so[$i]=""; $ch[$i]=""; $sp[$i]="";
      $de[$i]=""; $jo[$i]=""; $pp[$i]="";
   }

   //get foreign exchange status before this update:
   $sql="SELECT foreignx FROM eligibility WHERE id='$student_id[$i]'";
   $result=mysql_query($sql);
   $row=mysql_fetch_array($result);
   $old_foreignx=$row[0];

   //if the student is now being updated as a foreign exchange, make ineligible:
   if($old_foreignx!="y" && $foreignx[$i]=="y")
   {
      $eligible[$i]="n";
      $eligible_comment[$i]="International Transfer; Missing Paperwork";
      $forx_flag=1;
   }

   //hide transfer & enroll_option
   $transfer[$i]='';
   $enroll_option[$i]='';

   $sql="UPDATE eligibility SET transfer='$transfer[$i]', eligible='$eligible[$i]', eligible_comment='$eligible_comment[$i]', enroll_option='$enroll_option[$i]', foreignx='$foreignx[$i]', fb68='$fb68[$i]', fb11='$fb11[$i]', vb='$vb[$i]', sb='$sb[$i]', cc='$cc[$i]', te='$te[$i]', bb='$bb[$i]', wr='$wr[$i]', sw='$sw[$i]', go='$go[$i]', tr='$tr[$i]', ba='$ba[$i]', so='$so[$i]', ch='$ch[$i]', sp='$sp[$i]', pp='$pp[$i]', de='$de[$i]', im='$im[$i]', vm='$vm[$i]', jo='$jo[$i]', ubo='$ubo[$i]' WHERE id='$student_id[$i]'";
   $result=mysql_query($sql);
//if($student_id[$i]==1042091) { echo "$sql<br>".mysql_error(); exit(); }
}
$school_ch=ereg_replace("\'","\'",$school_ch);

if($swix>0)
{
   //IF NEW SWIMMERS ADDED TO THE LIST, MARK IN DATABASE SO CINDY CAN CHECK THEM
   for($i=0;$i<$swix;$i++)
   {
      $sql="SELECT * FROM eligibility_sw WHERE studentid='$new_swimmers[$i]'";
      $result=mysql_query($sql);
      if(mysql_fetch_array($result)==0)
         $sql2="INSERT INTO eligibility_sw (studentid,dateadded) VALUES ('$new_swimmers[$i]','".time()."')";
      else
         $sql2="UPDATE eligibility_sw SET dateadded='".time()."' WHERE studentid='$new_swimmers[$i]'";
      $result2=mysql_query($sql2);
   }
}
?>
<script language="javascript">
top.location.replace('eligibility.php?session=<?php echo $session; ?>&activity_ch=<?php echo $activity_ch; ?>&school_ch=<?php echo $school_ch; ?>&last=<?php echo $letter; ?>')
</script>
<?php
//header("Location:elig_list.php?session=$session&activity_ch=$activity_ch&school_ch=$school_ch&last=a");
?>
