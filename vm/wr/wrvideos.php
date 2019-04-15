<?php
/************************************************
wrvideos.php
Browse WR Vidoes and Add to Cart
Created 2/7/13
by Ann Gaffigan
*************************************************/
require '../functions.php';
require '../../functions_jw.php';
require '../variables.php';

//connect to db
$db=mysql_connect($db_host,$db_user,$db_pass);
mysql_select_db($db_name, $db);

session_set_cookie_params(2*24*60*60);
session_start();

$now=time();
if(!$_SESSION['sessionid'] || $_SESSION['expires']<$num)
{
   //GET RANDOM 12 CHARACTER STRING AS sessionid
   $characters='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $randstring='';
   for($i=0;$i<12;$i++) 
   {
      $randstring.=$characters[rand(0, strlen($characters))];
   }
   $_SESSION['sessionid']=$randstring;
   $_SESSION['expires']=$num+7200;	//2 hour session

   $sql="INSERT INTO wrvideosessions (sessionid,sessionstart) VALUES ('".$_SESSION['sessionid']."','$now')";
   $result=mysql_query($sql);
}

for($i=0;$i<count($wrestlerfirst);$i++)
{
   $submitvar="addtocart".$i;
   if($$submitvar=="Add to Cart")	//ADD THIS WRESTLER TO CART
   {
      $sql="SELECT * FROM wrvideocarts WHERE sessionid='".$_SESSION['sessionid']."' AND wrestlerfirst='".addslashes($wrestlerfirst[$i])."' AND wrestlerlast='".addslashes($wrestlerlast[$i])."' AND wrestlerteam='".addslashes($wrestlerteam[$i])."'";
      $result=mysql_query($sql);
      if(mysql_num_rows($result)==0)
      {
         $sql="INSERT INTO wrvideocarts (sessionid,wrestlerfirst,wrestlerlast,wrestlerteam,dateadded) VALUES ('".$_SESSION['sessionid']."','".addslashes($wrestlerfirst[$i])."','".addslashes($wrestlerlast[$i])."','".addslashes($wrestlerteam[$i])."','".time()."')";
         $result=mysql_query($sql);
         $addedtocart=1;
      }
   }
}

echo GetMainHeader();

echo "<form method=post action='wrvideos.php'>";
echo "<h1>Wrestling Videos</h1>";

echo "<p>Videos from the 2013 NSAA Wrestling Championships are no longer available.</p>";

echo GetMainFooter(0);
exit();

echo "<div class='alert' style=\"width:600px;\"><p>The NSAA has the rights to distribute videos from all matches at the State Wrestling Championships, with the exception of the <b>finals matches.</b></p><p>Videos from the finals matches are being sold by NETV. <a href=\"https://secure.netnebraskastore.org/SearchResult.aspx?q=NSAA+Wrestling\" target=\"_blank\">Click Here</a></p></div><br>";

$cartct=GetWRCartCount($_SESSION['sessionid']);
if($cartct>0)
{
   echo "<div class='alert'>There are videos for <b><u>$cartct</u></b> ";
   if($cartct==1) echo "wrestler";
   else echo "wrestlers";
   echo " in your cart.&nbsp;&nbsp;<a href=\"https://secure.nsaahome.org/nsaaforms/wr/wrcart.php\">View your Cart</a></div>";
}

$sql="SELECT * FROM wrvideos WHERE ";
if($team && $team!='') $sql.="(redteam='".addslashes($team)."' OR blueteam='".addslashes($team)."') AND ";
if($weightclass && $weightclass!='') $sql.="weightclass='$weightclass' AND ";
$last=trim($last);
if($last && $last!='') $sql.="(redlast LIKE '".addslashes($last)."%' OR bluelast LIKE '".addslashes($last)."%') AND ";
if($sql=="SELECT * FROM wrvideos WHERE ")
   $sql=substr($sql,0,strlen($sql)-6)."ORDER BY weightclass";
else
   $sql=substr($sql,0,strlen($sql)-4);
//echo $sql;
$result=mysql_query($sql);
if(mysql_error())
{
   echo "UNEXPECTED ERROR FOR QUERY: $sql<br>".mysql_error()."<br>";
}
//GET RESULTS INTO ARRAY SO WE CAN SORT AND THEN SHOW THE CURRENT ONES FROM offset TO limit
$videos=array(); $ix=0;
while($row=mysql_fetch_array($result))
{
   //RED
   //IF Team or Last Name was entered as search criteria, check if this matches the Red Wrestler
   $redgo=1;
   if($team && $team!='' && $row[redteam]!=$team)
      $redgo=0;
   if($last && $last!='' && strtolower(substr($row[redlast],0,strlen($last)))!=strtolower($last))
      $redgo=0;
   $uniqueid=preg_replace("/[^0-9a-zA-Z_]/","",$row[redfirst]."_".$row[redlast]."_".$row[redteam]."_".$row[weightclass]);	//IDENTIFIES THIS STUDENT
   if(!$$uniqueid && $redgo==1)	//THIS VARIABLE HAS NOT BEEN SET YET
   {
      $$uniqueid=1;
      $videos[first][$ix]=$row[redfirst];
      $videos[last][$ix]=$row[redlast];
      $videos[team][$ix]=$row[redteam];
      $videos[weightclass][$ix]=$row[weightclass];
      $ix++;
   }
   //BLUE
   //IF Team or Last Name was entered as search criteria, check if this matches the Blue Wrestler
   $bluego=1;
   if($team && $team!='' && $row[blueteam]!=$team)
      $bluego=0; 
   if($last && $last!='' && strtolower(substr($row[bluelast],0,strlen($last)))!=strtolower($last))
      $bluego=0; 
   $uniqueid=preg_replace("/[^0-9a-zA-Z_]/","",$row[bluefirst]."_".$row[bluelast]."_".$row[blueteam]."_".$row[weightclass]);   //IDENTIFIES THIS STUDENT
   if(!$$uniqueid && $bluego==1)      //THIS VARIABLE HAS NOT BEEN SET YET
   {
      $$uniqueid=1;
      $videos[first][$ix]=$row[bluefirst];
      $videos[last][$ix]=$row[bluelast];
      $videos[team][$ix]=$row[blueteam];
      $videos[weightclass][$ix]=$row[weightclass];
      $ix++;
   }
}
$total=$ix;

//NOW WE HAVE THE STUDENTS IN A 3D ARRAY.
//LET'S SORT APPROPRIATELY
if(!$sort) $sort="name";
if($sort=="name")	//SORT BY NAME
{
   array_multisort($videos[last],SORT_STRING,SORT_ASC,$videos[first],SORT_STRING,SORT_ASC,$videos[team],SORT_STRING,SORT_ASC,$videos[weightclass]);
}
else if($sort=="team")	//SORT BY TEAM
{
   array_multisort($videos[team],SORT_STRING,SORT_ASC,$videos[last],SORT_STRING,SORT_ASC,$videos[first],SORT_STRING,SORT_ASC,$videos[weightclass]);
}
else //WEIGHT CLASS
{
   array_multisort($videos[weightclass],SORT_NUMERIC,SORT_ASC,$videos[last],SORT_STRING,SORT_ASC,$videos[first],SORT_STRING,SORT_ASC,$videos[team],SORT_STRING,SORT_ASC);
}
//NOW SHOW THOSE BETWEEN $offset AND $showcount
	//PREP FOR NAVIGATION
if(!$showcount)
   $showcount=20;
if(!$offset)
   $offset=0;
$start=$offset+1;
$end=$offset+$showcount;
if($total<$start) 
{
   $start=$total; $end=$total;
   $offset=0;
}
if($end>$total)
   $end=$total;
$prevoffset=$offset-$showcount;
$nextoffset=$offset+$showcount;
	//END NAVIGATION PREP
echo "<table cellspacing=0 cellpadding=5 frame=all rules=all style=\"border:#a0a0a0 1px solid;\">";
echo "<caption>";
	//SEARCH BOX
echo "<div class='normal'><h2>Search Videos:</h2>";
echo "<p><b>Team: </b> <select name=\"team\"><option value=''>Select Team</option>";
$teams=explode("<team>",GetWRVideoTeams());
for($i=0;$i<count($teams);$i++)
{
   echo "<option value=\"$teams[$i]\"";
   if($team==$teams[$i]) echo " selected";
   echo ">$teams[$i]</option>";
}
echo "</select></p><p>";
echo "<b>Weight Class:</b> <select name=\"weightclass\"><option value=\"\">Select Weight</option>";
$sql="SELECT DISTINCT weightclass FROM wrvideos ORDER BY weightclass";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
   echo "<option value=\"$row[0]\"";
   if($weightclass==$row[weightclass]) echo " selected";
   echo ">$row[weightclass]</option>";
}
echo "</select></p><p>";
echo "<b>Last Name:</b> <input type=text size=20 name=\"last\" value=\"$last\"></p><p>";
echo "<input type=submit name='search' value='Search'></p>";
echo "</div><br>";
	//END SEARCH BOX
	//NAVIGATION:
echo "<input type=hidden name='offset' id='offset' value='$offset'>";
echo "<input type=hidden name='showcount' id='showcount' value='$showcount'>";
$navhtml="<div id='navdiv' style='text-align:center;width:700px;'><div style=\"float:left;width:100px;\">&nbsp;";
if($prevoffset<0) 
   $navhtml.="<input type=button class=\"jumptostart disabled\">";
else
   $navhtml.="<input type=button name=\"jumptostart\" class=\"jumptostart\" value=\"\" onClick=\"document.getElementById('offset').value='0';submit();\">";
$navhtml.="&nbsp;&nbsp;";
$prevlimit="$prevoffset,$showcount";
if($prevoffset<0) 
   $navhtml.="<input type=button class=\"arrowleft disabled\" value=\"\">";
else 
   $navhtml.="<input type=button name=\"arrowleft\" class=\"arrowleft\" value=\"\" onClick=\"document.getElementById('offset').value='$prevoffset';submit();\">";
$navhtml.="</div>";
$nextoffset=$offset+$showcount;
$navhtml.="<div style=\"float:right;width:100px;\">&nbsp;";
$nextlimit="$nextoffset,$showcount";
if($nextoffset>=$total) 
   $navhtml.="<input type=button class=\"arrowright disabled\" value=\"\">";
else
   $navhtml.="<input type=button name=\"arrowright\" class=\"arrowright\" value=\"\" onClick=\"document.getElementById('offset').value='$nextoffset';submit();\">";
$navhtml.="&nbsp;&nbsp;";
$leftover=$total % $showcount;
$lastoffset=$total-$leftover;
if($nextoffset>=$total) 
   $navhtml.="<input type=button class=\"jumptoend disabled\">";
else
   $navhtml.="<input type=button name=\"jumptoend\" class=\"jumptoend\" value=\"\" onClick=\"document.getElementById('offset').value='$lastoffset';submit();\">";
$navhtml.="</div>";
$navhtml.="<label style='line-height:30px;'>Showing $start-$end of ".number_format($total,0,'.',',')." Results</label>";
$navhtml.="<div style=\"clear:both;\"></div></div>";
echo $navhtml;
	//END NAVIGATION
echo "</caption>";
echo "<tr align=center><td>NAME</td><td>TEAM</td><td>WEIGHT<br>CLASS</td><td>AVAILABLE VIDEOS</td><td>ADD TO CART</td></tr>";
for($i=$offset;$i<($offset+$showcount);$i++)
{
   $ix=$i-$offset;
   if($videos[last][$i])
   {
      echo "<tr align=left><td>".$videos[last][$i].", ".$videos[first][$i]."</td><td>".$videos[team][$i]."</td><td align=center>".$videos[weightclass][$i]."</td>";
      echo "<td>";
      //GET VIDEOS FOR THIS PERSON
      $sql="SELECT DISTINCT filename,bouttype FROM wrvideos WHERE (redfirst='".addslashes($videos[first][$i])."' AND redlast='".addslashes($videos[last][$i])."' AND redteam='".addslashes($videos[team][$i])."') OR (bluefirst='".addslashes($videos[first][$i])."' AND bluelast='".addslashes($videos[last][$i])."' AND blueteam='".addslashes($videos[team][$i])."') ORDER BY division,bouttype,boutnumber";
      $result=mysql_query($sql);
      $curvids="";
      while($row=mysql_fetch_array($result))
      {
         $curvids.="$row[bouttype]<br>"; // $row[filename]<br>";
      }
      if($curvids!='')
  	 $curvids=substr($curvids,0,strlen($curvids)-4);
      echo $curvids;
      echo "</td>";
	//HIDDEN VARIABLES and ADD TO CART:
      echo "<td align=center>
	<input type=hidden name=\"wrestlerfirst[$ix]\" value=\"".$videos[first][$i]."\">
	<input type=hidden name=\"wrestlerlast[$ix]\" value=\"".$videos[last][$i]."\">
	<input type=hidden name=\"wrestlerteam[$ix]\" value=\"".$videos[team][$i]."\">
	<input type=submit name=\"addtocart".$ix."\"";
      if(IsInWRVideoCart($_SESSION['sessionid'],$videos[first][$i],$videos[last][$i],$videos[team][$i]))
         echo " value=\"In Cart\" disabled";
      else 
         echo " value=\"Add to Cart\"";
      echo "></td>";
      echo "</tr>";
   }
}
echo "</table>";
echo "<p style='text-align:center;'>".$navhtml."</p>";
echo "</form>";

echo GetMainFooter(0);
?>
