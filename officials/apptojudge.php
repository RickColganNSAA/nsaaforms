<?php

require 'functions.php';
require 'variables.php';

//connect to db
$db=mysql_connect("$db_host",$db_user2,$db_pass2);
mysql_select_db($db_name2, $db);

if(!ValidUser($session))
{
   header("Location:jindex.php?error=1");
   exit();
}


//GET PLAY DATES
   $ppdist=array(); $i=0;
   $ppdist2=array(); $ppdist_sm=array();
   $sql="SELECT * FROM pptourndates WHERE offdate='x' AND label NOT LIKE '%State%' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $ppdist[$i]=date("l, F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $ppdist2[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $ppdist_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i++;
   }
   $ppstate=array(); $i=0;
   $ppstate2=array(); $ppstate_sm=array();
   $sql="SELECT * FROM pptourndates WHERE offdate='x' AND label LIKE '%State%' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $class=trim(preg_replace("/State/","",$row[label]));
      $ppstate[$i]=date("l, F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]))." $class";
      $ppstate2[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]))." $class";
      $ppstate_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]))." $class";
      $i++;
   }
//GET SPEECH DATES
   $spdist=array(); $i=0;
   $spdist2=array(); $spdist_sm=array();
   $sql="SELECT * FROM sptourndates WHERE offdate='x' AND label NOT LIKE '%State%' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $spdist[$i]=date("l, F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $spdist2[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $spdist_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]));
      $i++;
   }
   $spstate=array(); $i=0;
   $spstate2=array(); $spstate_sm=array();
   $sql="SELECT * FROM sptourndates WHERE offdate='x' AND label LIKE '%State%' ORDER BY tourndate,id";
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result))
   {
      $date=explode("-",$row[tourndate]);
      $class=trim(preg_replace("/State/","",$row[label]));
      $spstate[$i]=date("l, F j, Y",mktime(0,0,0,$date[1],$date[2],$date[0]))." $class";
      $spstate2[$i]=date("M j",mktime(0,0,0,$date[1],$date[2],$date[0]))." $class";
      $spstate_sm[$i]=date("m/j",mktime(0,0,0,$date[1],$date[2],$date[0]))." $class";
      $i++;
   }

echo $init_html;
echo GetHeaderJ($session,"apptojudge");
echo "<br><br>";

echo "<a href=\"jtourndates.php?session=$session\">&larr; Edit Postseason Dates for Apps to Host, Apps to Officiate, Lodging</a><br><br />";

if($search)	//user submitted search criteria
{
   //create query for judges table based on selections of user
   $table=$sport."apply";
   $sql="SELECT t2.first,t2.middle,t2.last,t1.* FROM $table AS t1, judges AS t2 WHERE ";

   //district dates
   if($sport=='sp') $ct=count($spdist);
   else $ct=count($ppdist);
   $sql.="((";
   $dist=0;
   for($i=1;$i<=$ct;$i++)
   {
      $field="dist".$i;
      if($$field=='x')	//if user checked this date, add to query
      {
	 $sql.="t1.$field='x' OR ";
	 $dist=1;
      }
   }
   if($dist==1)
   {
      $sql=substr($sql,0,strlen($sql)-4);
      $sql.=") OR (";
   }
   else
   {
      $sql=substr($sql,0,strlen($sql)-2);
      $sql.="(";
   }

   //state states
   $state=0;
   if($sport=='sp') $max=count($spstate);
   else $max=count($ppstate);
   for($i=1;$i<=$max;$i++)
   {
      $field="state".$i;
      if($$field=='x')
      {
	 $sql.="t1.$field='x' OR ";
	 $state=1;
      }
   }
   if($state==1)
   {
      $sql=substr($sql,0,strlen($sql)-4);
      if($dist==1)
         $sql.=")) AND (";
      else
	 $sql.=") AND (";
   }
   else if($dist==1)
   {
      $sql=substr($sql,0,strlen($sql)-4);
      $sql.=") AND (";
   }
   else
   {
      $sql=substr($sql,0,strlen($sql)-1);
      $sql.="AND (";
   }

   //class representations
   $class=0;
   for($i=0;$i<count($classes);$i++)
   {
      $index=$i+1;
      $var="classrep".$index;
      if($$var=='x')
      {
	 $sql.="t1.classrep='$classes[$i]' OR ";
	 $class=1;
      }
   }
   if($class==1)
   {
      $sql=substr($sql,0,strlen($sql)-4);
      $sql.=") AND (";
   }

   //class preferences
   $class2=0;
   for($i=0;$i<count($classes);$i++)
   {
      $index=$i+1;
      $var="classpref".$index;
      if($$var=='x')
      {
	 $sql.="t1.classpref LIKE '%$classes[$i]%' OR ";
	 $class2=1;
      }
   }
   if($class2==1)
   {
      $sql=substr($sql,0,strlen($sql)-4);
      $sql.=") AND t1.offid=t2.id ORDER BY t2.last,t2.first,t2.middle";
   }
   else 
   {
      $sql=substr($sql,0,strlen($sql)-5);
      $sql.=" AND t1.offid=t2.id ORDER BY t2.last,t2.first,t2.middle";
   }

   if(ereg("WHERE ) AND",$sql))
      $sql=ereg_replace("WHERE ) AND","WHERE",$sql);

   //echo $sql."<br>";
}
if($query)
   $sql=$query;
if($search || $query)
{
   //SHOW RESULTS
   if($sport=='sp') $sportname="Speech";
   else $sportname="Play Production";
   echo "<a class=small href=\"apptojudge.php?session=$session\">Return to Applications to Judge Admin</a><br><br>";
   echo "<table cellspacing=0 cellpadding=3 frame=all rules=all style=\"border:#808080 1px solid;\">";
   echo "<caption><b>Apps to Judge $sportname Districts/State Contests Search Results:</b><br>";
   $sql=ereg_replace("WHERE AND","WHERE",$sql);
   $sql=ereg_replace("WHERE  AND","WHERE",$sql);
   echo $sql;
   $result=mysql_query($sql);
   if(mysql_error())
   {
      echo $sql."<br>";
      echo mysql_error();
   }
   echo "(".mysql_num_rows($result)." Results)</caption>";
   echo "<tr align=center><th class=small rowspan=2><a class=tiny href=\"apptojudge.php?session=$session&query=$sql\">Name</a><br>(click for App)</th>";
   echo "<th class=small rowspan=2><a class=tiny href=\"apptojudge.php?session=$session&query=$sql&sort=city\">City, State</a></th>";
   echo "<th class=small rowspan=2><a class=tiny href=\"apptojudge.php?session=$session&query=$sql&sort=zip\">Zip</a></th>";
   echo "<th class=small rowspan=2><a class=tiny href=\"apptojudge.php?session=$session&query=$sql&sort=firstyr\">New Judge</a></th>";
   echo "<th class=small";
   if($sport=='sp') echo " colspan=".count($spdist);
   else echo " colspan=".count($ppdist);
   echo ">Districts</th>";
   echo "<th class=small";
   if($sport=='sp') echo " colspan=".count($spstate);
   else echo " colspan=".count($ppstate);
   echo ">State</th>";
   echo "<th class=small rowspan=2>Class</th>";
   echo "<th class=small colspan=6>Class Preference</th></tr>";
   echo "<tr align=center>";
   if($sport=='sp')
   {
      for($i=0;$i<count($spdist);$i++)
      {
         echo "<th class=small>$spdist_sm[$i]</th>";
      }
      echo "<th class=small>$spstate_sm[0]</th>";
      echo "<th class=small>$spstate_sm[1]</th>";
   }
   else
   {
      for($i=0;$i<count($ppdist);$i++)
      {
         echo "<th class=small>$ppdist_sm[$i]</th>";
      }
      for($i=0;$i<count($ppstate);$i++)
      {
         echo "<th class=small>$ppstate_sm[$i]</th>";
      }
   }
   for($i=0;$i<count($classes);$i++)
   {
      echo "<th width=15 class=small>$classes[$i]</th>";
   }
   echo "</tr>";

   $results=array(); $ix=0;	//create array of results to put in correct order according to $sort
   while($row=mysql_fetch_array($result))
   {
      $curid=$row[offid];
      //get name, city, state, zip, new judge, ld qual from judges table
      $sql2="SELECT city,state,zip,firstyr FROM judges WHERE id='$curid'";
      $result2=mysql_query($sql2);
      $row2=mysql_fetch_array($result2);
      $name="$row[first] $row[middle] $row[last]";
      $cityst="$row2[city], $row2[state]";
      $zip=$row2[zip];
      $firstyr=strtoupper($row2[firstyr]);

      if($sport=='sp') $app="speechapp";
      else $app="playapp";
      if(!$sort || $sort=="")	//sort by NAME is default
      {
	 echo "<tr align=left><td><a href=\"$app.php?session=$session&givenoffid=$curid&header=no\" class=small target=new>$name</a></td>";
         echo "<td>$cityst</td><td>$zip</td><td>$firstyr</td>";
      }
      else
      {
	 $results[offid][$ix]=$curid;
	 $results[name][$ix]=$name;
	 $results[city][$ix]=$cityst;
	 $results[zip][$ix]=$zip;
	 $results[firstyr][$ix]=strtolower($firstyr);
      }
      if($sport=='sp')
      {
	 for($i=0;$i<count($spdist);$i++)
	 {
	    $index=$i+1;
	    $field="dist".$index;
	    if(!$sort || $sort=="")
	    {
	       if($row[$field]=='x')
	       {
	          echo "<td bgcolor=yellow><b>X</b></td>";
	       }
	       else
	       {
	          echo "<td>&nbsp;</td>";
	       }
	    }
	    else
	       $results[$field][$ix]=$row[$field];
	 }
      }
      else
      {
	 for($i=0;$i<count($ppdist);$i++)
	 {
	    $index=$i+1;
	    $field="dist".$index;
	    if(!$sort || $sort=="")
	    {
	       if($row[$field]=='x')
	          echo "<td bgcolor=yellow><b>X</b></td>";
	       else
	          echo "<td>&nbsp;</td>";
	    }
	    else
	       $results[$field][$ix]=$row[$field];
	 }
      }
      if(!$sort || $sort=='')
      {
         if($row[state1]=='x')
	    echo "<td bgcolor=blue><b>X</b></td>";
         else
	    echo "<td>&nbsp;</td>";
         if($row[state2]=='x')
	    echo "<td bgcolor=blue><b>X</b></td>";
         else
	    echo "<td>&nbsp;</td>";
         if($sport=='pp')
         {
            if($row[state3]=='x')
               echo "<td bgcolor=blue><b>X</b></td>";
            else
               echo "<td>&nbsp;</td>";
         }
      }
      else
      {
	 $results[state1][$ix]=$row[state1];
	 $results[state2][$ix]=$row[state2];
         if($sport=='pp') $results[state3][$ix]=$row[state3];
      }
      if(!$sort || $sort=='')
	 echo "<td>$row[classrep]</td>";
      else
	 $results[classrep][$ix]=$row[classrep];
      for($i=0;$i<count($classes);$i++)
      {
	  if(ereg($classes[$i],$row[classpref]))
	  {
	     if(!$sort || $sort=='')
 	        echo "<td bgcolor=green><b>X</b></td>";
             else
             {
		$num=$i+1;
		$index="classpref".$num;
		$results[$index][$ix]='x';
	     }
	  }
	  else
	  {
	     if(!$sort || $sort=='')
	        echo "<td>&nbsp;</td>";
	     else
	     {
		$num=$i+1;
		$index="classpref".$num;
		$results[$index][$ix]=" ";
	     }
	  }
      }
      if(!$sort || $sort=='')
         echo "</tr>";
      $ix++;
   }

   if($sort && $sort!="")	//display results in $sort order
   {
      $sql="SELECT DISTINCT $sort";
      if($sort=='city') $sql.=",state";
      $sql.=" FROM judges ORDER BY $sort";
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result))
      {
	 if($sort=='city')
	    $row[0]="$row[0], $row[1]";
	 for($i=0;$i<count($results[offid]);$i++)
	 {
	    if($results[$sort][$i]==$row[0])
	    {
	       echo "<tr align=left><td><a class=small href=\"edit_judge.php?session=$session&offid=".$results[offid][$i]."\" target=new>".$results[name][$i]."</a></td>";
	       echo "<td>".$results[city][$i]."</td>";
	       echo "<td>".$results[zip][$i]."</td>";
	       echo "<td>".strtoupper($results[firstyr][$i])."</td>";
	       echo "<td";
	       if($results[dist1][$i]=='x') echo " bgcolor=yellow";
	       echo ">".strtoupper($results[dist1][$i])."</td>";
	       echo "<td";
	       if($results[dist2][$i]=='x') echo " bgcolor=yellow";
	       echo ">".strtoupper($results[dist2][$i])."</td>";
	       echo "<td";
	       if($results[dist3][$i]=='x') echo " bgcolor=yellow";
	       echo ">".strtoupper($results[dist3][$i])."</td>";
	       echo "<td";
	       if($results[dist4][$i]=='x') echo " bgcolor=yellow";
	       echo ">".strtoupper($results[dist4][$i])."</td>";
	       echo "<td";
	       if($results[dist5][$i]=='x') echo " bgcolor=yellow";
	       echo ">".strtoupper($results[dist5][$i])."</td>";
	       echo "<td";
	       if($results[state1][$i]=='x') echo " bgcolor=blue";
	       echo ">".strtoupper($results[state1][$i])."</td>";
	       echo "<td";
	       if($results[state2][$i]=='x') echo " bgcolor=blue";
	       echo ">".strtoupper($results[state2][$i])."</td>";
	       if($sport=='pp')
	       {
                  echo "<td";
                  if($results[state3][$i]=='x') echo " bgcolor=blue";
                  echo ">".strtoupper($results[state3][$i])."</td>";
  	       }
	       echo "<td";
	       echo ">".strtoupper($results[classrep][$i])."</td>";
	       echo "<td";
	       if($results[classpref1][$i]=='x') echo " bgcolor=green";
	       echo ">".strtoupper($results[classpref1][$i])."</td>";
	       echo "<td";
	       if($results[classpref2][$i]=='x') echo " bgcolor=green";
	       echo ">".strtoupper($results[classpref2][$i])."</td>";
	       echo "<td";
	       if($results[classpref3][$i]=='x') echo " bgcolor=green";
	       echo ">".strtoupper($results[classpref3][$i])."</td>";
	       echo "<td";
	       if($results[classpref4][$i]=='x') echo " bgcolor=green";
	       echo ">".strtoupper($results[classpref4][$i])."</td>";
	       echo "<td";
	       if($results[classpref5][$i]=='x') echo " bgcolor=green";
	       echo ">".strtoupper($results[classpref5][$i])."</td>";
	       echo "<td";
	       if($results[classpref6][$i]=='x') echo " bgcolor=green";
	       echo ">".strtoupper($results[classpref6][$i])."</td>";
	       echo "</tr>";
	    }
	 }
      }
   }

   echo "</table>";

      
   exit();
}
echo "<form method=post action=\"apptojudge.php\">";
echo "<input type=hidden name=session value=$session>";
echo "<table width=500 cellspacing=3 cellpadding=3><caption><b>Applications to Judge Admin:</b><hr></caption>";
echo "<tr align=left><td><li><a class=small target=new href=\"playapp.php?session=$session&print=1\">Preview Application to Judge PLAY</a></td></tr>";
echo "<tr align=left><td><li><a class=small target=new href=\"speechapp.php?session=$session&print=1\">Preview Application to Judge SPEECH</a></td></tr>";
echo "<tr align=left><td><li><a class=small href=\"apptooffzones.php?session=$session&judges=y&sport=pp\">Manage PLAY Zones</a></td></tr>";
echo "<tr align=left><td><li><a class=small href=\"apptooffzones.php?session=$session&judges=y&sport=sp\">Manage SPEECH Zones</a></td></tr>";
echo "<tr align=left><td><li><a class=small href=\"judgesnoapp.php?sport=pp&session=$session\">View Judges who Have NOT Submitted an Application to Judge PLAY</a></td></tr>";
echo "<tr align=left><td><li><a class=small href=\"judgesnoapp.php?session=$session&sport=sp\">View Judges who have NOT submitted an Application to Judge SPEECH</a></td></tr>";
echo "<tr align=left><td><li><b>ADVANCED SEARCH:&nbsp;</b><select name=sport onchange=submit()>";
echo "<option value='~'>Choose Activity</option>";
echo "<option value='sp'";
if($sport=="sp") echo " selected";
echo ">Speech</option><option value='pp'";
if($sport=='pp') echo " selected";
echo ">Play Production</option></select></td></tr>";

if($sport && $sport!='~')	//if a sport was selected, show rest of options
{
   echo "<tr align=center><td><table>";
   if($sport=='sp') $sportname="Speech";
   else $sport="Play Production";
   echo "<tr align=left><th align=left>Show me judges who...</th></tr>";

   //allow user to select district dates available
   echo "<tr align=left><td><b>...are available to judge a District $sportname Contest on any of the following dates:</b></td></tr>";
   echo "<tr align=center><td><table>";
   if($sport=='sp')	//Speech was selected
   {
      // show checkboxes for the speech district dates that judges are available for
      for($i=0;$i<count($spdist);$i++)
      {
	 $index=$i+1;
	 $var="dist".$index;
	 echo "<tr align=left><td><input type=checkbox name=$var value='x'>$spdist[$i]</td></tr>";
      }
   }
   else	//Play
   {
      // show checkboxes for play districts
      for($i=0;$i<count($ppdist);$i++)
      {
	 $index=$i+1;
	 $var="dist".$index;
	 echo "<tr align=left><td><input type=checkbox name=$var value='x'>$ppdist[$i]</td></tr>";
      }
   }
   echo "</table></td></tr>";

   //allow user to select state dates available
   echo "<tr align=left><td><b>...OR are available to judge a State $sportname Contest on any of the following dates:</b></td></tr>";
   echo "<tr align=center><td><table>";
   if($sport=='sp')
   {
      echo "<tr align=left><td><input type=checkbox name=state1 value='x'>$spstate[0]</td></tr>";
      echo "<tr align=left><td><input type=checkbox name=state2 value='x'>$spstate[1]</td></tr>";
   }
   else
   {
      echo "<tr align=left><td><input type=checkbox name=state1 value='x'>$ppstate[0]</td></tr>";
      echo "<tr align=left><td><input type=checkbox name=state2 value='x'>$ppstate[1]</td></tr>";
      echo "<tr align=left><td><input type=checkbox name=state3 value='x'>$ppstate[2]</td></tr>";
   }
   echo "</table></td></tr>";

   //allow user to select classes judges may represent
   echo "<tr align=left><th align=left>AND who also...</th></tr>";
   echo "<tr align=left><td><b>...represent any of the following classifications:</b></td></tr>";
   echo "<tr align=center><td>";
   for($i=0;$i<count($classes);$i++)
   {
      $index=$i+1;
      $var="classrep".$index;
      echo "<input type=checkbox name=$var value='x'>$classes[$i]&nbsp;&nbsp;";
   }
   echo "</td></tr>";
   
   //allow user to select classes judges may prefer
   echo "<tr align=left><th align=left>AND who also...</th></tr>";
   echo "<tr align=left><td><b>...prefer to judge any of the following classes:</b></td></tr>";
   echo "<tr align=center><td>";
   for($i=0;$i<count($classes);$i++)
   {
      $index=$i+1;
      $var="classpref".$index;
      echo "<input type=checkbox name=$var value='x'>$classes[$i]&nbsp;&nbsp;";
   }
   echo "</td></tr>";

   echo "<tr align=center><td><br><input type=submit name=search value=\"Search\"></td></tr>";
   echo "</table></td></tr>";
   echo "</table></form>";
}

echo $end_html;

?>
