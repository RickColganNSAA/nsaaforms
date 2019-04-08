<?php
/*********************************************
superiorcerts.php
Main Menu to Dynamically Create PDF for Music Small/Large Emsemble Award Certificate
Created 8/27/14
Author Ann Gaffigan
**********************************************/
require '../functions.php';
require '../../calculate/functions.php';
require '../variables.php';
require 'mufunctions.php';

//connect to db:
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);
if(!ValidUser($session))
{
   header("Location:../index.php?error=1");
   exit();
}
$level=GetLevel($session);
if(empty($school) && !empty($customschool)) $school=$customschool;
if(($level==2 || $level==3) && ($school=="" || !$school))
{
   $school=GetSchool($session);
}
else if(!$school || $school=="")
{
   echo "ERROR: No School Indicated.";
   exit();
}
if($level==3)
{
   $schoolid=GetSchoolID($session); $loginid=0;
}
else if($level==4)
{
   $schoolid=0; $loginid=GetUserID($session);
}

//if(IsCooping($school,"Vocal")) $school=GetHeadCoopSchool($school,"Vocal");
//if(IsCooping($school,"Instrumental")) $school=GetHeadCoopSchool($school,"Instrumental");
$school2=addslashes($school);

$sql="SELECT * FROM muschools WHERE school='$school2'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
if(mysql_num_rows($result)==0)
{
   echo "ERROR: Can't find school $school";
   exit();
}
$schid=$row[id];
$class=$row[classch];
$distid=$row[distid];
$homedist=$row[homedistrict];
$sql="SELECT * FROM mudistricts WHERE id='$distid'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$district="$row[distnum] -- $row[classes]";
$director=preg_replace("/, District Music Contest/","",$row[director]);
$director=trim($director);

if(PastDue(date("Y")."-05-31",0) && !PastDue(date("Y")."-07-01",0))       //IF IT IS PAST END OF SCHOOL YEAR, NEED TO REFER TO ARCHIVED DB
{
   $year1=date("Y")-1; $year2=date("Y");
   mysql_select_db($db_name.$year1.$year2, $db);
   $fallyear=$year1;
}
else if(date("m")>=6) $fallyear=date("Y");
else $fallyear=date("Y")-1;

$sport='mu';

if($generatesolo)	//PUT $entryids list together and send to snsemblecert.php
{
   $list="";
   for($i=0;$i<count($entryids);$i++)
   {
      if($checks[$i]=='x') $list.=$entryids[$i].",";
   }
   if($list!='')
   {
      $list=substr($list,0,strlen($list)-1);
      header("Location: ensemblecert.php?session=$session&schoolid=$schid&entryids=$list&customschool=$customschool&name=$name&selectschool=yes");
   }
}
if($generateens)       //PUT $studentids list together and send to snsemblecert.php
{
   $list="";
   for($i=0;$i<count($studentids);$i++)
   {
      if($checks[$i]=='x') $list.=$studentids[$i].",";
   }
   if($list!='')
   {
      $list=substr($list,0,strlen($list)-1);
      header("Location: ensemblecert.php?session=$session&schoolid=$schid&ensemblestuds=1&entryid=$entryid&studentids=$list&customtitle=$customtitle&name=$name&selectschool=yes");
   }
}

echo $init_html;
echo GetHeader($session);
echo "<br />";

$musiteid=GetMusicSiteID($schoolid,$loginid);           //SITE DIRECTORS
$mudistid=GetMusicDistrictID($schoolid,$loginid);       //COORDINATORS
if($musiteid>0 || $mudistid>0)
   echo "<p><a class=small href=\"scertsadmin.php?session=$session\">&larr; Generate Award Certificates for Other Schools</a></p>";

echo "<form method=post action=\"superiorcerts.php\">
	<input type=hidden name=\"name\" value=\"$name\">
	<input type=hidden name=\"session\" value=\"$session\"><input type=hidden name=\"school\" value=\"$school\">";

echo "<h2>$school NSAA District Music Contest Superior Award Certificates:</h2>";

echo "<p>To generate a PDF certificate for ensembles, individual members of ensembles and soloists, please select from the options below.</p>";

//ENSEMBLE OR SOLOIST?

echo "<p><select name=\"categoryid\" onChange=\"submit();\"><option value=\"0\">Select Ensemble/Solo Category</option>";
$sql="SELECT * FROM mucategories WHERE category NOT LIKE '%Solo%' ORDER BY vieworder";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[id]\"";
   if($categoryid==$row[id]) { echo " selected"; $category=$row[category]; }
   echo ">$row[category]</option>";
}
echo "<option value=\"1000\"";
if($categoryid==1000) echo " selected";
echo ">Miscellaneous Small Vocal & Instrumental Ensemble</option>";
echo "<option value=\"1000000\"";
if($categoryid==1000000) echo " selected";
echo ">All Vocal & Instrumental Soloists</option>";
echo "</select> ";
if($categoryid>0)
{
   if($categoryid==1000)
      $sql="SELECT * FROM muensembles WHERE categid='0'";
   else
      $sql="SELECT * FROM muensembles WHERE categid='$categoryid' ORDER BY orderby";
   $result=mysql_query($sql);
   if(mysql_num_rows($result)>1)	//CHOOSE SPECIFIC ENSEMBLE
   {
      echo "<select name=\"ensembleid\" onChange=\"submit();\"><option value=\"0\">Select Ensemble/Solo</option>";
      while($row=mysql_fetch_array($result))
      {
         echo "<option value=\"$row[id]\"";
         if($ensembleid==$row[id]) { echo " selected"; $ensemble=$row[ensemble]; }
	 echo ">$row[ensemble]</option>";
      }
      echo "</select> ";
   }
   else if($row=mysql_fetch_array($result))
   {
      $ensembleid=$row[id]; $ensemble=$row[ensemble];
      echo "<input type=hidden name=\"ensembleid\" value=\"$row[id]\">";
   }
   else if($categoryid==1000000) $ensembleid=1000000; //SOLOS
   if($ensembleid>0)
   {
      //SOLOS: We just want to list all soloists in this category
      //ENSEMBLES: We want to select a specific ensemble and then list the members
      if($ensembleid==1000000)	//SOLO
      { 
	 echo "</form><form target=\"_blank\" method=post action=\"superiorcerts.php\">
	       <input type=hidden name=\"name\" value=\"$name\">
           <input type=hidden name=\"schid\" value=\"$schid\">
           <input type=hidden name=\"session\" value=\"$session\">
           <input type=hidden name=\"school\" value=\"$school\">";
         echo "<div style=\"width:600px;\">";
         $sql="SELECT t1.first,t1.last,t1.school,t2.* FROM eligibility AS t1,muentries AS t2,muensembles AS t3 WHERE t2.ensembleid=t3.id AND t1.id=t2.studentid AND t2.schoolid='$schid' AND t3.ensemble LIKE '%Solo%' ORDER BY t1.last,t1.first";
         $result=mysql_query($sql);
//echo $sql;
         if(mysql_num_rows($result)>0)
         {
	 $allids=""; $list=""; $i=0;
         while($row=mysql_fetch_array($result))
         {
	    $allids.=$row[id].",";
	    $list.="<p><input type=checkbox name=\"checks[$i]\" value=\"x\"><input type=hidden name=\"entryids[$i]\" value=\"$row[id]\"> $row[first] $row[last] ($row[event])</p>";
	    $i++;
         }
     	 echo "<p style=\"text-align:left;\"><b>When you use one of the options below to generate a certificate or multiple certificates, you can then Print. For help, click: <input type=button name='printingtips' value='Printing Tips' onClick=\"window.open('../printingtips.php','Printing_Tips','width=500,height=350');\"></b></p>";
         echo "<ul>";
	 if($allids!='')
         {
	    $allids=substr($allids,0,strlen($allids)-1);
	    //echo "<li><a href=\"ensemblecert.php?session=$session&schoolid=$schid&entryids=$allids\" target=\"_blank\">Generate Certificates for ALL ".mysql_num_rows($result)." Soloists (2 per page)</a></li>";
 	 }
         if($list!='')
         {
		 
	    echo "<li>Check the box next to the soloist(s) you want to generate a certificate for, and click \"Generate Certificates.\"";
	    echo $list;
 	    echo "</li>";
   	 }
         if(substr($school,strlen($school)-5,5)==" High")
            $theschool=substr($school,0,strlen($school)-5);
         else $theschool=$school;
	 $theschool.=" High School";
	 echo "<li>Custom School (default: <b>\"$theschool\"</b>) <input type=\"text\" size=30 name=\"customschool\" value=\"$theschool\"></li>";
         echo "</ul>";
	 echo "<input type=submit name=\"generatesolo\" value=\"Generate Certificates\">";
         } //end if more than 0 results
         else echo "<p><i>No Soloists Found.</i></p>";
         echo "</div>";
      } //END IF SOLO
      else 	//ENSEMBLE
      {
	 if($savetitle && $entryid)
	 {
	    $sql="UPDATE muentries SET customtitle='".addslashes($customtitle)."' WHERE id='$entryid'";
	    $result=mysql_query($sql);
	 }
         $sql="SELECT * FROM muentries WHERE schoolid='$schid' AND ensembleid='$ensembleid' ORDER BY id";
         $result=mysql_query($sql);
         if(mysql_num_rows($result)==0)
         {
	     echo "<i>None found</i>";
         }
	 else
	 {
         echo "<select name=\"entryid\" onChange=\"submit();\"><option value=\"0\">Select Specific Ensemble</option>";
	 while($row=mysql_fetch_array($result))
	 {
	    echo "<option value=\"$row[id]\"";
	    if($entryid==$row[id]) 
	    {
	       echo " selected"; $customtitle=$row[customtitle];
	    }
	    $name="";
	    if($row[event]!='') $name.="$row[event]: ";
	    $sql2="SELECT * FROM mustudentries WHERE entryid='$row[id]'";
	    $result2=mysql_query($sql2);
	    if(mysql_num_rows($result2)>0)
	    {
	       $studs=""; $ct=0;
	       while($row2=mysql_fetch_array($result2))
	       {
	          if($ct<3) $studs.=GetStudentInfo($row2[studentid],FALSE).", ";
		  $ct++;
	       }
	       $studs=substr($studs,0,strlen($studs)-2);
	       if($ct>3) $studs.=" and more...";
	       $name.="$studs";
	    }
	    else if($row[groupsize]>0) $name.=" ($row[groupsize] in group)";
	    echo ">$name</option>";
	 }
	 echo "</select>";
	 }
	 if($entryid>0)	//LINKS: PRINT BIG ENSEMBLE ONE AND INDY ENSEMBLE MEMBER ONES
	 {
	    echo "<div style=\"width:600px;\">";
    	    $sql2="SELECT t1.* FROM eligibility AS t1, mustudentries AS t2 WHERE t1.id=t2.studentid AND t2.entryid='$entryid' ORDER BY t1.last,t1.first,t1.middle";
	    $result2=mysql_query($sql2);
	    $allids=""; $list=""; $i=0;
	    while($row2=mysql_fetch_array($result2))
	    {
	       if($i%4==0)
	       {
	          if($i==0) $list.="<table style=\"width:100%;\">";
	   	  else $list.="</tr>";
	          $list.="<tr align=left>";
	       }
	       $allids.=$row2[id].",";
               $list.="<td><input type=checkbox name=\"checks[$i]\" value=\"x\"><input type=hidden name=\"studentids[$i]\" value=\"$row2[id]\"> $row2[first] $row2[last]</td>";
               $i++;
	    }
	    if($allids!='') $allids=substr($allids,0,strlen($allids)-1);
            echo "<p style=\"text-align:left;\"><b>When you use one of the options below to generate a certificate or multiple certificates, you can then Print. For help, click: <input type=button name='printingtips' value='Printing Tips' onClick=\"window.open('../printingtips.php','Printing_Tips','width=500,height=350');\"></b></p>";
	    echo "<ul>";
	    $highschool=trim(preg_replace("/High School/","",$school));
	    if(!preg_match("/School/",$highschool)) $highschool.=" High School";
	    echo "<li>By default, these certificates will say <i>\"$ensemble of $highschool\"</i> is receiving this award. You can customize it to say something else below, making sure to click \"Save\" BEFORE generating the certificate(s).
		<p><b>Custom Ensemble Title:</b> <input type=text size=25 name=\"customtitle\" value=\"$customtitle\"> 
		<input type='submit' name='savetitle' value='Save'><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(default: <b><i>$ensemble of $highschool</b></i>)</p>";
	    if($savetitle) echo "<div class='alert'>The custom title has been saved. You can now generate your certificates.</div>";
	    echo "</form></li>";
            echo "<form target=\"_blank\" method=post action=\"ensemblecert.php\">
           <input type=hidden name=\"schid\" value=\"$schid\">
		   <input type=hidden name=\"school\" value=\"$school\">
	   <input type=hidden name=\"entryid\" value=\"$entryid\">
           <input type=hidden name=\"session\" value=\"$session\">";
	    echo "<li><a target=\"_blank\" href=\"ensemblecert.php?session=$session&entryid=$entryid&school=$school\">Generate Certificate for this Ensemble (8.5 in x 11 in)</a></li>";
	    if($allids!='')
	    {
		//echo "<li><a target=\"_blank\" href=\"ensemblecert.php?session=$session&entryid=$entryid&ensemblestuds=1&studentids=$allids\">Generate Individual Certificates for ALL ".mysql_num_rows($result2)." Members of this Ensemble (10 per page)</a></li>";
	    }
	    if($list!='')
	    {
	        echo "<li>Check the box next to the member(s) of the ensemble you want to generate a certificate for and click \"Generate Individual Certificates.\"";
	        echo $list."</tr></table>";
	        echo "<input type=submit name=\"generateens\" value=\"Generate Individual Certificates\">";
	        echo "</li><li><b>OR:</b> ";
	    }
     	    else echo "<li>";
	    echo "Enter names below for member(s) of the ensemble you want to generate a certificate for (10 to a page) and click \"Generate Individual Certificates.\"";
	    $ix=0;
	    for($i=1;$i<=10;$i++)
	    {
      	       echo "<div style=\"margin:5px;\" id=\"$ix\">$i) <input type=text size=35 name=\"students[$ix]\"></div>";
	       $ix++;
	    }
   	    $nextix=$ix;
   	    //HIDDEN DIVS FOR ADDING MORE
   	    while($ix<100)
   	    {
      	       $num=$ix+1;
      	       echo "<div id=\"$ix\" style=\"margin:5px;display:none;\">$num) <input type=text size=35 name=\"students[$ix]\"></div>";
      	       $ix++;
   	    }
            //ADD MORE:
   	    echo "<input type=hidden name=\"nextshown\" id=\"nextshown\" value=\"$nextix\"><input type=button name=\"addmore\" onClick=\"var nextshown=document.getElementById('nextshown').value; document.getElementById(nextshown).style.display=''; nextshown++; document.getElementById('nextshown').value=nextshown;\" value=\"Add More Names\">";
	   
	    echo "<br><br><input type=submit name=\"generateens2\" value=\"Generate Individual Certificates\"></li></ul></div>";
	 } //END IF ENTRYID
	 else echo "</form>";
      } //END IF ENSEMBLE
   }
}

echo "</form>";
echo GetFooter($session);

?>
