<?php
//eligibility.php: defines frames for Elig Screen

require 'functions.php';

//validate user
if(!ValidUser($session))
{
   header("Location:index.php");
//echo "Invalid user for $session";
   exit();
}

if($submit=="Cancel")
{
   header("Location:welcome.php?session=$session");
   exit();
}
$level=GetLevel($session);

session_start();
unset($_SESSION['query']);

if($activity_ch && $activity_ch=="") $activity_ch="All Activities";
?>

<html>
<head>
<title>NSAA Home</title>
<link rel="stylesheet" href="../css/nsaaforms.css" type="text/css">
</head>

<?php 

//check if school_ch and activity_ch strings are already submitted:
if(!$school_ch || !$activity_ch)
{
   //make school and activity choices into comma-separated strings
   $school=GetSchool($session);
   if($school=="All" && !$school_ch)	//Level 1 access 
   {
      $school_ch="";
      for($i=0;$i<count($school_array);$i++)
      {
         $school_ch.="$school_array[$i],";
      }
      $school_ch=substr($school_ch,0,strlen($school_ch)-1);
   }
   else if($school!="All") $school_ch=$school;
   if(!$activity_ch &&($activity_array[0]=="" || !$activity_array[0]))
   {
      $activity_ch=GetActivity($session);
   }
   else if(!$activity_ch)
   {
      $activity_ch="";
      for($i=0;$i<count($activity_array);$i++)
      {
	 if($activity_array[$i]=="Girls Track & Field")
	    $activity_array[$i]="Girls Track";
	 if($activity_array[$i]=="Boys Track & Field")
	    $activity_array[$i]="Boys Track";
         $activity_ch.="$activity_array[$i],";
      }
      $activity_ch=substr($activity_ch,0,strlen($activity_ch)-1);
   }
}


/*** GET QUERY ***/
$school_ch_str=$school_ch;
$activity_ch_str=$activity_ch;
$school_ch2=ereg_replace("\'","\'",$school_ch);
if($level==1)   //level-1/NSAA access
{
   $multiple_schools=0;
   if((ereg("All",$activity_ch_str) || (ereg("Sports",$activity_ch_str) && ereg("Non",$activity_ch_str))) && ereg("All Schools",$school_ch_str))
   {
      $sql="SELECT * FROM eligibility";
      $multiple_schools=1;
   }
   else
   {
      $sql="SELECT * FROM eligibility WHERE ";
      if(!ereg("All",$activity_ch) && !(ereg("Sports",$activity_ch) && ereg("Non",$activity_ch)))
      {
        if(ereg("Non",$activity_ch))    //Non-Athletic Activities Only
           $sql.="(sp='x' OR pp='x' OR de='x' OR im='x' OR vm='x' OR jo='x')";
        else if(ereg("Sports",$activity_ch)) //Sports Only
           $sql.="(fb68='x' OR fb11='x' OR vb='x' OR sb='x' OR cc='x' OR te='x' OR bb='x' OR wr='x' OR sw='x' OR go='x' OR tr='x' OR ba='x' OR so='x')";
        else    //List of specific activities
        {
           $activity_ch=split(",",$activity_ch);
           $sql.="(";
           for($i=0;$i<count($activity_ch);$i++)
           {
              if($activity_ch[$i]=="Girls Track" || $activity_ch[$i]=="Boys Track")
                 $activity_ch[$i].=" & Field";
              $string=GetActivityQuery($activity_ch[$i]);
              $sql.=$string;
           }
           $sql=substr($sql,0,strlen($sql)-3).")";
        }
      }
      if(!ereg("All Schools",$school_ch))
      {
         if(!ereg("All",$activity_ch)) $sql.=" AND ";
         $sql.="(";
         $schoolch=split(",",$school_ch);
         for($i=0;$i<count($schoolch);$i++)
         {
            $schoolch[$i]=ereg_replace("\'","\'",$schoolch[$i]);
            $sql.="school='$schoolch[$i]' OR ";
         }
         $sql=substr($sql,0,strlen($sql)-3).")";
         if(count($schoolch)>1)  $multiple_schools=1;
      }
      else  $multiple_schools=1;
   }
}
else if($level==2)      //AD-Access (Level 2)
{
  //pull school from db according to session id
   if(ereg("All",$activity_ch))
   {
      $sql="SELECT * FROM eligibility WHERE school='$school_ch2'";
   }
   else         //pull only specified activities
   {
      $sql="SELECT * FROM eligibility WHERE ";
      if(ereg("Non",$activity_ch))    //Non-Athletic Activities Only
      {
         $sql.="(sp='x' OR pp='x' OR de='x' OR im='x' OR vm='x' OR jo='x')";
      }
      else if(ereg("Sports",$activity_ch)) //Sports Only
      {
         $sql.="(fb68='x' OR fb11='x' OR vb='x' OR sb='x' OR cc='x' OR te='x' OR bb='x' OR wr='x' OR sw='x' OR go='x' OR tr='x' OR ba='x' OR so='x')";
      }
      else      //List of specific activities
      {
         $sql.="(";
         $activity_ch=split(",",$activity_ch);
         for($i=0;$i<count($activity_ch);$i++)
         {
            $activity_ch[$i]=trim($activity_ch[$i]);
            if($activity_ch[$i]=="Girls Track" || $activity_ch[$i]=="Boys Track")
               $activity_ch[$i].=" & Field";
            $string=GetActivityQuery($activity_ch[$i]);
            $sql.="$string";
         }
         $sql=substr($sql,0,strlen($sql)-3);
         $sql.=")";
      }
      $sql.=" AND school='$school_ch2'";
   }
}
//if this is an advanced search, add more to the sql statement:
if($gender && $gender!="All")// && (ereg("All",$activity_ch) || ereg("Only", $activity_ch)))    //if gender is specified and is relevant
{
   if(ereg("WHERE",$sql))   $sql.=" AND";
   else $sql.=" WHERE";
   $sql.=" gender='$gender'";
}
if($grade && $grade!="All")     //if grade in school is specified
{
   if(ereg("WHERE",$sql))   $sql.=" AND";
   else $sql.=" WHERE";
   //change grade to semesters
   switch($grade)
   {
      case "<9":
         $sql.=" semesters='0'";
         break;
      case 9:
         $sql.=" (semesters='1' OR semesters='2')";
         break;
      case 10:
         $sql.=" (semesters='3' OR semesters='4')";
         break;
      case 11:
         $sql.=" (semesters='5' OR semesters='6')";
         break;
      case 12:
         $sql.=" (semesters='7' OR semesters='8')";
         break;
   }
}
if($transfer=='y' || $ineligible=='y' || $foreign_x=='y' || $enroll_option=='y')
{
//if one of the boxes was checked:
   if(ereg("WHERE",$sql))   $sql.=" AND";
   else $sql.=" WHERE";
   $sql.=" (";
   if($transfer=='y')
      $sql.=" transfer='y' OR";
   if($ineligible=='y')
      $sql.=" eligible!='y' OR";
   if($foreign_x=='y')
      $sql.=" foreignx='y' OR";
   if($enroll_option=='y')
      $sql.=" enroll_option='y' OR";
   $sql=substr($sql,0,strlen($sql)-3);
   $sql.=")";
}
$sql=str_replace('WHER) AND ',' WHERE ',$sql); 
$_SESSION['query']=$sql;
?>
<frameset border=0 rows="75,*,75">
   <frame src="elig_header.php?session=<?php echo $session;?>" marginheight=0 scrolling=yes>
<?php
//send all info available to elig_list.php:

echo "<frame src=\"elig_list.php?school_ch=$school_ch&activity_ch=$activity_ch&session=$session&gender=$gender&grade=$grade&transfer=$transfer&ineligible=$ineligible&foreign_x=$foreign_x&enroll_option=$enroll_option&last=$last\" name=list scrolling=yes marginheight=0>";

echo "<frame src=\"elig_footer.php?school_ch=$school_ch&activity_ch=$activity_ch&session=$session&gender=$gender&grade=$grade&transfer=$transfer&ineligible=$ineligible&foreign_x=$foreign_x&enroll_option=$enroll_option\" scrolling=auto marginheight=0>";

?>

   <noframes>
   Please view our no frames page. 
   </noframes>
</frameset>
</html>
