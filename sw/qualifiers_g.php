<?php
//qualifiers_g.php: get girls state meet qualifiers from verification forms and uploaded TM files
//	write results to nsaahome.org/textfile/swim/gbest.htm

require '../functions.php';
require '../../calculate/functions.php';
require '../variables.php';
require 'swfunctions.php';

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
$qual=array();	//2-D array of qualifiers (top time for each school for relays)
$qual[id]=array();	//student id
$qual[name]=array();	//name
$qual[sch]=array();	//school
$qual[meet]=array();	//meet 
$qual[meetdate]=array();	//meet date
$qual[event]=array();	//event
$qual[mark]=array();	//performance
$qual[qualtype]=array();	//auto or secondary
$qix=0;			//index for qual array

//second array for relays (this array will have 2nd - nth times for each school
$qual2=array();
$qual2[id]=array();
$qual2[name]=array();
$qual2[sch]=array();
$qual2[meet]=array();
$qual2[meetdate]=array();
$qual2[event]=array();
$qual2[mark]=array();
$qual2[qualtype]=array();
$qix2=0;

$today=date("M d, Y g:i T",time());
$info.="<table><caption><b>Girls Swimming Season Best Performances:</b><br>";
$info.="Last Generated on $today<br>";
$info.="<font style=\"font-size:8pt;\"><i>(NOTE: For RELAYS, the rank is noted first as \"by school\" and then as overall time rank in parentheses)</i></font><hr></caption>";

//get qualifiers from sw_verify_perf_g
for($x=0;$x<count($sw_events);$x++)
{
   if(!preg_match("/Relay/",$sw_events[$x]))	//non-Relay
   {
      $sql="SELECT DISTINCT t2.* FROM sw_verify_g AS t1, sw_verify_perf_g AS t2 WHERE ((t1.id=t2.formid AND t1.submitted='y' AND t1.approved='y') OR t2.formid='0') AND t2.event='$sw_events[$x]' ORDER BY t2.performance";
      if(preg_match("/Diving/",$sw_events[$x])) $sql.=" DESC";
      $result=mysql_query($sql);
      $curct=1;
      while($row=mysql_fetch_array($result))
      {
         $formid=$row[1];
         $qualtype=DoesQualify("Girls $row[3]",$row[5]);
         if(preg_match("/0:00.0/",$row[5]) || $row[5]=="0") $qualtype="no";
         if($qualtype!="no")
         {
            $qual[id][$qix]=$row[4];
            $qual[name][$qix]=GetStudentInfo($row[4]);
            $qual[sch][$qix]=GetSchoolName(GetSID2($row[school],'sw'),'sw');
	    $qual[rank][$qix]=$curct;

            //get meet
	    /*
            if($row[1]==0)	//meet listed in this table
            {
	       $qual[meet][$qix]=$row[7];
	       $qual[meetdate][$qix]=$row[meetdate];
	       if($row[7]=="0") $qual[meet][$qix]="";
	       $formid='0';
	       $host="none";
            }
            else		//get meet from sw_verify_g table
            {
	       $sql2="SELECT meet,meetdate,school FROM sw_verify_g WHERE id='$row[1]'";
	       $result2=mysql_query($sql2);
	       $row2=mysql_fetch_array($result2);
	       $qual[meet][$qix]=$row2[0];
	       $qual[meetdate][$qix]=$row2[1];
	       $host=$row2[2];
	       $formid='2';
            }
	    */
            $sql2="SELECT meetid FROM sw_verify_g WHERE id='$formid'";
	    $result2=mysql_query($sql2);
	    $row2=mysql_fetch_array($result2);
	    $qual[meet][$qix]=GetMeetName($row2[0]);
            $qual[meetdate][$qix]=GetMeetDate($row2[0]);

            $qual[event][$qix]=$row[3];
            if(preg_match("/Diving/",$row[3]))
            {
	       $qual[mark][$qix]=$row[5];
	       $qual[marksec][$qix]=$row[5];
            }
            else
            {
	       if(substr($row[5],0,1)==0 && substr($row[5],2,1)==":")
	       $row[5]=substr($row[5],1,strlen($row[5]));
               if($formid!='0' && preg_match("/:/",$row[5]))
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
	    {
               $qix++; $curct++;
	    }
         }//end $row loop
	 $qual[meet][$qix]=$row[7];
      }//end if not Relay
   }
   else	//RELAY
   {
      $sql="SELECT DISTINCT t2.* FROM sw_verify_g AS t1, sw_verify_perf_g AS t2 WHERE ((t1.id=t2.formid AND t1.submitted='y' AND t1.approved='y') OR t2.formid='0') AND t2.event='$sw_events[$x]' ORDER BY t2.performance";
      $result=mysql_query($sql);
      $overallrank=1; 
      while($row=mysql_fetch_array($result))
      {
	 $formid=$row[1];
	 $qualtype=DoesQualify("Girls $row[3]",$row[5]);
	 if(preg_match("/0:00.0/",$row[5]) || $row[5]=="0") $qualtype=="no";
	 if($qualtype!='no')
	 {
	    //check to see if this school already has a relay in the $qual array:
	    $inarray=0;
	    for($i=0;$i<count($qual[sch]);$i++)
	    {
	       if($qual[sch][$i]==GetSchoolName(GetSID2($row[school],'sw'),'sw'))
	       {
	          $inarray=1; $i=count($qual[sch]);
	       }
	    }
	    if($inarray==0)	//first entry for this school in array
	    {
	       $qual[id][$qix]=split("/",$row[4]);
	       $qual[name][$qix]="";
	       $qual[rank][$qix]=$overallrank;
	       $overallrank++;
	       for($i=0;$i<count($qual[id][$qix]);$i++)
	       {
		  $qual[name][$qix].=GetStudentInfo($qual[id][$qix][$i]).", ";
	       }
	       $qual[name][$qix]=substr($qual[name][$qix],0,strlen($qual[name][$qix])-2);
	       $qual[sch][$qix]=GetSchoolName(GetSID2($row[school],'sw'),'sw');
	       /*
               if($row[1]=="0")
	       {
		  if($row[7]=="0") $row[7]="";
		  $qual[meet][$qix]=$row[7];
	       }
	       else
	       {
		  $sql2="SELECT meet,meetdate,school FROM sw_verify_g WHERE id='$row[1]'";
		  $result2=mysql_query($sql2);
		  $row2=mysql_fetch_array($result2);
		  $qual[meet][$qix]=$row2[0]; $qual[meetdate][$qix]=$row2[1];
		  $host=$row2[2];
	       }
	       */
	       $sql2="SELECT meetid FROM sw_verify_g WHERE id='$row[1]'";
	       $result2=mysql_query($sql2);
	       $row2=mysql_fetch_array($result2);
	       $qual[meet][$qix]=GetMeetName($row2[0]);
	       $qual[meetdate][$qix]=GetMeetDate($row2[0]);
	       $qual[event][$qix]=$row[3];
	       if(substr($row[5],0,1)=="0" && substr($row[5],2,1)==":")
	       $row[5]=substr($row[5],1,strlen($row[5]));
	       if($formid!="0" && preg_match("/:/",$row[5]))
	       {
		  $qual[mark][$qix]=$row[5]; $qual[marksec][$qix]=ConvertToSec($row[5]);
	       }
	       else
	       {
		  $qual[mark][$qix]=ConvertFromSec($row[5]); $qual[marksec][$qix]=$row[5];
	       }
	       $qual[qualtype][$qix]=$qualtype;
	       if($host!="Test's School")
		  $qix++;
	    } //end if inarray==0
	    else	//put in secondary array of relay times
	    {
	       $qual2[id][$qix2]=split("/",$row[4]);
	       $qual2[name][$qix2]="";
	       $qual2[rank][$qix2]=$overallrank;
	       $overallrank++;
	       for($i=0;$i<count($qual2[id][$qix2]);$i++)
	       { 
		  $qual2[name][$qix2].=GetStudentInfo($qual2[id][$qix2][$i]).", ";
	       }
	       $qual2[name][$qix2]=substr($qual2[name][$qix2],0,strlen($qual2[name][$qix2])-2);
	       $qual2[sch][$qix2]=GetSchoolName(GetSID2($row[school],'sw'),'sw');
	       /*
	       if($row[1]=="0")
	       {
		  if($row[7]=="0") $row[7]="";
		  $qual2[meet][$qix2]=$row[7];
	       }
	       else
	       {
		  $sql2="SELECT meet,meetdate,school FROM sw_verify_g WHERE id='$row[1]'";
		  $result2=mysql_query($sql2);
		  $row2=mysql_fetch_array($result2);
		  $qual2[meet][$qix2]=$row2[0]; $qual2[meetdate][$qix2]=$row2[1];
		  $host=$row2[2];
	       }
	       */
               $sql2="SELECT meetid FROM sw_verify_g WHERE id='$row[1]'";
               $result2=mysql_query($sql2);
               $row2=mysql_fetch_array($result2);
               $qual[meet][$qix]=GetMeetName($row2[0]);
               $qual[meetdate][$qix]=GetMeetDate($row2[0]);
	       $qual2[event][$qix2]=$row[3];
	       if(substr($row[5],0,1)=="0" & substr($row[5],2,1)==":")
	       $row[5]=substr($row[5],1,strlen($row[5]));
	       if($formid!="0" && preg_match("/:/",$row[5]))
	       {
		  $qual2[mark][$qix2]=$row[5]; $qual2[marksec][$qix2]=ConvertToSec($row[5]);
	       }
	       else
	       {
		  $qual2[mark][$qix2]=ConvertFromSec($row[5]); $qual2[marksec][$qix2]=$row[5];
	       }
	       $qual2[qualtype][$qix2]=$qualtype;
	       if($host!="Test's School")
		  $qix2++;
	    }//end if inarray==1
	 }//end if qualtype!=no
      }//end $row loop
   }//end if RELAY

   //show this event's qualifiers:
$info.="<tr align=left><th class=smaller align=left colspan=6><br><i>$sw_events[$x]</i>:</th></tr>";
$info.="<tr align=left valign=bottom><th colspan=2 class=smaller align=left>Rank, Name (Grade)</th>";
$info.="<th class=smaller align=left>School</th>";
$info.="<th class=smaller align=left>Mark</th>";
$info.="<th class=smaller align=left>Meet</th>";
$info.="<th class=smaller align=left>Date</th>";
$info.="<th class=smaller align=left>Auto/Sec</th></tr>";
$usednames=array();
$uix=0;
$byschrank=1;	//rank by school (relays)
$rank=1;
   for($i=0;$i<$qix;$i++)
   {
	 $used=0;
	 for($k=0;$k<count($usednames);$k++)	//check that this student isn't already listed
	 {
	    if(!preg_match("/Relay/",$sw_events[$x]) && $usednames[$k]==$qual[name][$i])
	       $used=1;
	    else if(preg_match("/Relay/",$sw_events[$x]) && $usednames[$k]==$qual[sch][$i])
	       $used=1;
	 }
	 if($used==0 || (preg_match("/Relay/",$sw_events[$x]) && $qual[qualtype][$i]=="Automatic"))
	 {
	    //non-relays: show only fastest time
	    //relays: show multiple auto times but only one secondary time (if no auto)
            $info.="<tr valign=top align=left>";
	    if(preg_match("/Relay/",$sw_events[$x]))
	    {
	       //show overall rank and by-school rank for Relays:
	       $info.="<td>$byschrank (".$qual[rank][$i].")</td>";
	       $byschrank++;
	    }
	    else
            {
	       $info.="<td>".$rank.".</td>";
               $rank++;
            }
	    $info.="<td>".$qual[name][$i]."</td><td>".$qual[sch][$i]."</td><td>".$qual[mark][$i]."</td><td>";
            $info.=$qual[meet][$i]."</td><td>".$qual[meetdate][$i]."</td><td>".$qual[qualtype][$i]."</tr>";
	    $usednames[$uix]=$qual[name][$i];
	    if(preg_match("/Relay/",$sw_events[$x]))
	    {
	       $usednames[$uix]=$qual[sch][$i];
	    }
	    $uix++;
	 }
	 $qual[marksec][$i]="";
   }
if(preg_match("/Relay/",$sw_events[$x]))	//put secondary array of performances next
{
   if($qix2>0)
      $info.="<tr align=center><td colspan=7>&nbsp;<br></td></tr>";
   for($i=0;$i<$qix2;$i++)
   {
      $used=0;
      for($k=0;$k<count($usednames);$k++)
      {
	 if($usednames[$k]==$qual2[sch][$i])
	    $used=1;
      }
      if($used==0 || $qual2[qualtype][$i]=="Automatic")
      {
	 //show multiple auto times but only one secondary time if no auto
	 $info.="<tr valign=top align=left><td>- (".$qual2[rank][$i].")</td><td>".$qual2[name][$i]."</td><td>".$qual2[sch][$i]."</td><td>".$qual2[mark][$i]."</td><td>";
	 $info.=$qual2[meet][$i]."</td><td>".$qual2[meetdate][$i]."</td><td>".$qual2[qualtype][$i]."</td></tr>";
	 $usednames[$uix]=$qual2[sch][$i];
	 $uix++;
      }
      $qual2[marksec][$i]="";
   }
}

//RESET VARS
$qix=0; $qix2=0;
unset($qual);
unset($usednames);
}//end for each event loop
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
