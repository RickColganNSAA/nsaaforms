<?php
//qualifiers_g.php: get girls state meet qualifiers from verification forms and uploaded TM files
//	write results to nsaahome.org/textfile/swim/gbest.htm

require '../functions.php';
require '../variables.php';

if(!ValidUser($session))
{
   header("Location:../index.php");
   exit();
}

echo $init_html;
echo GetHeader($session);
echo "<center><br><br>";

$info="<html><head><title>Swimming | Girls Swimming Season Best Performances</title>";
$info.="<link rel=stylesheet href=\"../../css/nsaaforms.css\" type=\"text/css\"></head><body>";
$info.="<center><table><tr align=center><td align=center>";

//initialize variables:
$qual=array();	//2-D array of qualifiers
$qual[id]=array();	//student id
$qual[name]=array();	//name
$qual[sch]=array();	//school
$qual[meet]=array();	//meet 
$qual[event]=array();	//event
$qual[mark]=array();	//performance
$qual[qualtype]=array();	//auto or secondary
$qix=0;			//index for qual array

$today=date("M d, Y g:i T",time());
$info.="<table><caption><b>Girls Swimming Season Best Performances:</b><br>";
$info.="Last Generated on $today<hr></caption>";

//get qualifiers from sw_verify_perf_g
for($x=0;$x<count($sw_events);$x++)
{

$sql="SELECT DISTINCT t2.* FROM sw_verify_g AS t1, sw_verify_perf_g AS t2 WHERE ((t1.id=t2.formid AND t1.submitted='y' AND t1.approved='y') OR t2.formid='0') AND t2.event='$sw_events[$x]' ORDER BY t2.performance";
if(ereg("Diving",$sw_events[$x])) $sql.=" DESC";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   $formid=$row[1];
   $qualtype=DoesQualify("Girls $row[3]",$row[5]);
   if(ereg("0:00.0",$row[5]) || $row[5]=="0") $qualtype="no";
   if($qualtype!="no" && !ereg("Relay",$row[3]))	//non-Relay
   {
      $qual[id][$qix]=$row[4];
      
      //get student name
      $sql2="SELECT first, last, semesters FROM eligibility WHERE id='$row[4]'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $qual[name][$qix]="$row2[0] $row2[1] (".GetYear($row2[2]).")";

      $qual[sch][$qix]=$row[2];

      //get meet
      if($row[1]==0)	//meet listed in this table
      {
	 $qual[meet][$qix]=$row[7];
	 if($row[7]=="0") $qual[meet][$qix]="";
	 $formid='0';
	 $host="none";
      }
      else		//get meet from sw_verify_g table
      {
	 $sql2="SELECT meet,school FROM sw_verify_g WHERE id='$row[1]'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $qual[meet][$qix]=$row2[0];
	 $host=$row2[1];
	 $formid='2';
      }

      $qual[event][$qix]=$row[3];
      if(ereg("Diving",$row[3]))
      {
	 $qual[mark][$qix]=$row[5];
	 $qual[marksec][$qix]=$row[5];
      }
      else
      {
	 if(substr($row[5],0,1)==0 && substr($row[5],2,1)==":")
	    $row[5]=substr($row[5],1,strlen($row[5]));
         if($formid!='0' && ereg(":",$row[5]))
         {
            $qual[mark][$qix]=$row[5];
            $qual[marksec][$qix]=ConvertToSec($row[5]);
         }
         else
         {
	    $qual[mark][$qix]=ConvertFromSec($row[5]);
	    $qual[marksec][$qix]=$row[5];
         }
      }
      $qual[qualtype][$qix]=$qualtype;
      //echo $qual[event][$qix]." ".$qual[mark][$qix]." ".$qual[marksec][$qix]."<br>";
      if($host!="Test's School")
         $qix++;
   }
   else if($qualtype!="no")	//Relay
   {
      $qual[id][$qix]=split("/",$row[4]);	//student id's

      //get student names
      $qual[name][$qix]="";
      for($i=0;$i<count($qual[id][$qix]);$i++)
      {
	 $sql2="SELECT first,last,semesters FROM eligibility WHERE id='".$qual[id][$qix][$i]."'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 if(trim("$row2[0]$row2[1]")!="") $qual[name][$qix].="$row2[0] $row2[1] (".GetYear($row2[2])."), ";
      }
      $qual[name][$qix]=substr($qual[name][$qix],0,strlen($qual[name][$qix])-2);
      $qual[sch][$qix]=$row[2];
      //get meet
      if($row[1]==0)	//meet listed in this table
      {
	 if($row[7]=="0") $row[7]="";
	 $qual[meet][$qix]=$row[7];
      }
      else	//get meet from sw_verify_g table
      {
	 $sql2="SELECT meet, school FROM sw_verify_g WHERE id='$row[1]'";
	 $result2=mysql_query($sql2);
	 $row2=mysql_fetch_array($result2);
	 $qual[meet][$qix]=$row2[0];
	 $host=$row2[1];
      }

      $qual[event][$qix]=$row[3];
      if(substr($row[5],0,1)=="0" && substr($row[5],2,1)==":")
      $row[5]=substr($row[5],1,strlen($row[5]));
      if($formid!='0' && ereg(":",$row[5]))
      {
         $qual[mark][$qix]=$row[5];
         $qual[marksec][$qix]=ConvertToSec($row[5]);
      }
      else
      {
         $qual[mark][$qix]=ConvertFromSec($row[5]);
         $qual[marksec][$qix]=$row[5];
      }
      $qual[qualtype][$qix]=$qualtype;
      if($host!="Test's School")
	 $qix++;
   }
}

//show this event's qualifiers:

$curct=1;
$info.="<tr align=left><th class=smaller align=left colspan=6><br><i>$sw_events[$x]</i>:</th></tr>";
$info.="<tr align=left valign=bottom><th colspan=2 class=smaller align=left>Name (Grade)</th>";
$info.="<th class=smaller align=left>School</th>";
$info.="<th class=smaller align=left>Mark</th>";
$info.="<th class=smaller align=left>Meet</th>";
$info.="<th class=smaller align=left>Automatic/<br>Secondary</th></tr>";
$usednames=array();
$uix=0;
   for($i=0;$i<$qix;$i++)
   {
	 $used=0;
	 for($k=0;$k<count($usednames);$k++)	//check that this student isn't already listed
	 {
	    if(!ereg("Relay",$sw_events[$x]) && $usednames[$k]==$qual[name][$i])
	       $used=1;
	    else if(ereg("Relay",$sw_events[$x]) && $usednames[$k]==$qual[sch][$i])
	       $used=1;
	 }
	 if($used==0 || (ereg("Relay",$sw_events[$x]) && $qual[qualtype][$i]=="Automatic"))
	 {
	    //non-relays: show only fastest time
	    //relays: show multiple auto times but only one secondary time (if no auto)
            $info.="<tr valign=top align=left><td>$curct.</td><td>".$qual[name][$i]."</td><td>".$qual[sch][$i]."</td><td>".$qual[mark][$i]."</td><td>";
            $info.=$qual[meet][$i]."</td><td>".$qual[qualtype][$i]."</tr>";
	    $usednames[$uix]=$qual[name][$i];
	    if(ereg("Relay",$sw_events[$x]))
	    {
	       $usednames[$uix]=$qual[sch][$i];
	    }
	    $uix++;
            $curct++;
	 }
	 $qual[marksec][$i]="";
   }
unset($usednames);
$qix=0;
}//end for loop
$info.="</table>";

$info.=$end_html;

$open=fopen(citgf_fopen("../../textfile/swim/gbest.htm"),"w");
fwrite($open,$info);
fclose($open); 
 citgf_makepublic("../../textfile/swim/gbest.htm");

echo "The <i>Girls'</i> Swimming Season Best Performances file has been generated!<br><br>";
echo "<a target=new href=\"../../textfile/swim/gbest.htm\">Click Here to Preview Girls' File</a>";
echo "<br><br><a href=\"qualifiers_b.php?session=$session\">Click Here to Generate Boys' File</a>";
echo $end_html;
exit();

?>
