<?php

require 'functions.php';
require 'variables.php';

$header=GetHeader($session);
$level=GetLevel($session);

//connect to db:
$db=mysql_connect("$db_host",$db_user,$db_pass);
mysql_select_db($db_name, $db);

//verify user
/* if(!ValidUser($session) )
{
   header("Location:index.php?error=3");
   exit();
} */
 echo $init_html;
 echo $header;

//echo $end_html;
?>
<table  width="100%" cellspacing="0" cellpadding="0">
 <tbody>
 <form method="post" action="belivers.php" enctype="multipart/form-data">

   <tr align=center><h2>U.S. BANK BELIEVERS & ARCHIVERS APPLICATION</h2></tr>
   <tr><h3>APPLICANT INFORMATION</h3></tr>
   <tr align=left>1. Applicant Name:<br></tr>
   <tr><select>
   </select><br></tr>
   <tr align=left>2. Gender:<br></tr>
   <tr><input type="radio" name="gender" value="male" > Female<br></tr>
   <tr><input type="radio" name="gender" value="female"> Male<br></tr>
   3. Please specify the applicant's ethnicity or race: <br>
   <input type="radio" name="race" value="white" > White<br>
   <input type="radio" name="race" value="hispanic"> Hispanic or Latinao<br>
   <input type="radio" name="race" value="black"> Black or African American<br>
   <input type="radio" name="race" value="inidan"> Native American or American Indian<br>
   <input type="radio" name="race" value="asian"> Asian/Pacific Island<br>
   <input type="radio" name="race" value="other"> Other<br>
   4. School <br><input type="text"; name="school"><br>
   5. Applicant Home Address<br>
   Street Address: <input type="text" name="street" value=""><br>
   City: <input type="text" name="city" value=""><br>
   Zip Code: <input type="text" name="zip" value=""><br>
   <br>
   6. Applicant Cell Phone: <input type="text" name="cell" value=""><br>
   7. Applicant Email Address: <input type="text" name="email" value=""><br>
   8. Parent/Guardian Name(s): <input type="text" name="parent_name" value=""><br>
   9. Parent/Guardian email address: <input type="text" name="parent_email" value=""><br>
   10. Person Submitting Application: <input type="text" name="submitted" value=""><br>
   11. Title of person submitting application:<br>
   <input type="radio" name="title" value="superintendent" checked> Superintendent<br>
   <input type="radio" name="title" value="principal"> Principal<br>
   <input type="radio" name="title" value="ad"> Athletic/Activity Director<br>
   <input type="radio" name="title" value="inidan"> Guidance Counselor<br>

   12. NSAA Classification(for Track & Field):<br>
   <input type="radio" name="class" value="a" > A<br>
   <input type="radio" name="class" value="b"> B<br>
   <input type="radio" name="class" value="c"> C<br>
   <input type="radio" name="class" value="d"> D<br><br>
   
   <h3>Scholastic Achievement</h3>
   <p>Individuals must have a cumulative grade point average of 3.75(on an unweighted 4.0 scale) or higher. 
   All academic classes that the student has taken since entering grade nine, and which count toward fulfillment
   of the school's graduation requirements are to be used in determining the grade point average. The grade point 
   average will be calculated through the second semester of the nominee's junior year. Grade point averages are
   to be rounded off to the nearest hundredth (example:3.756 = 3.76)</p>
   
   13. Cumulative Grade Point Average on an Unweighted Sclae<br><input type="text" name="average" value=""><br>  
   14. List Academic Honors and Awards (e.g. 2017 NCPA Academic All-state, Nationa Honor-society, Honor Roll, Academic Letter, etc)
   <br><textarea name="list" form="usrform"></textarea> <br><br>
   <h3>NSAA Activity Participation</h3>
   <p>Activities sanctioned by the NSAA are listed. A requirement of this award is the student must
   participant in an NSAA sanctioned activity. Achievement or recognition attained by participation in 
   non-NSAA sanctioned activities are prohibited in this category (but can be listed in community involvement).
   Outstanding achievement does not have to be primary factor. An individual serving as a studen manager or
   member of a stage crew can be judged on his/her commitment and positive contributions to the activity.</p>
   
   15. NSAA Activity Participation:(Check all activities participated in as a junior)
   
   <input type="checkbox" name="activity" value="cross_country"> Cross County<input type="checkbox" name="activity" value="swimming"> Swimming & Diving<input type="checkbox" name="activity" value="play"> Play Production<br>
   <input type="checkbox" name="activity" value="boys_tenis"> Boys Tennis<input type="checkbox" name="activity" value="basketball"> Basketball<input type="checkbox" name="activity" value="speech"> Speech<br>
   <input type="checkbox" name="activity" value="girls_golf"> Girls Golf<input type="checkbox" name="activity" value="baseball"> Baseball<input type="checkbox" name="activity" value="debate"> Debate<br>
   <input type="checkbox" name="activity" value="softball"> Softball<input type="checkbox" name="activity" value="boys_golf"> Boys Golf<input type="checkbox" name="activity" value="journalism"> Journalism<br>
   <input type="checkbox" name="activity" value="football"> Football<input type="checkbox" name="activity" value="girls_tennis"> Girls Tennis<input type="checkbox" name="activity" value="journalism"> Music<br>
   <input type="checkbox" name="activity" value="volleyball"> Volleyball<input type="checkbox" name="activity" value="Track"> Track & Field<input type="checkbox" name="activity" value="unified_bowling"> Unified Bowling<br>
   <input type="checkbox" name="activity" value="wreslting"> Wreslting<input type="checkbox" name="activity" value="soccer"> Soccer<br>

    16. List Awards from NSAA Activities (e.g. 2016 NSAA State Girls Golf Champion, Member of the 2016 NSAA Class C2 Girls Basketball
	Championship 4th Place Team, 2016 NSAA Class C2 State Speech 3rd Place Medalist in Serious Prose, etc)
    <br><textarea name="award" form="usrform"></textarea> <br><br>
   
    <h3>School Involvement</h3>
	Involvement in clubs or organizations, volunteerism in school programs, support of activities other than those in which applicant participates,
	and non-NSAA activities during applicant's high school career.
	<br><br>
    17. School Involvement-List Top 4
    # 1 Group/Club Activity: <input type="text" name="activity1" value=""><br>	
    Involvement or Office/Title Held: <input type="text" name="office1" value=""><br>	
    Length of involvement: <input type="text" name="length1" value=""><br>	
    Estimated time per month: <input type="text" name="time1" value=""><br>	
    # 2 Group/Club Activity: <input type="text" name="activity2" value=""><br>	
    Involvement or Office/Title Held: <input type="text" name="office2" value=""><br>	
    Length of involvement: <input type="text" name="length2" value=""><br>	
    Estimated time per month: <input type="text" name="time2" value=""><br>	
    # 3 Group/Club Activity: <input type="text" name="activity3" value=""><br>	
    Involvement or Office/Title Held: <input type="text" name="office3" value=""><br>	
    Length of involvement: <input type="text" name="length3" value=""><br>	
    Estimated time per month: <input type="text" name="time3" value=""><br>	
    # 4 Group/Club Activity: <input type="text" name="activity4" value=""><br>	
    Involvement or Office/Title Held: <input type="text" name="office4" value=""><br>	
    Length of involvement: <input type="text" name="length4" value=""><br>	
    Estimated time per month: <input type="text" name="time4" value=""><br>	
	<br>
	
	<h3>Community Involvement</h3>
	Involvement and volunteerism in community organizations, youth groups and programs during the applicant's high school career.
	<br><br>
    17. Community Involvement-List Top 4
    # 1 Group/Club Activity: <input type="text" name="c_activity1" value=""><br>	
    Involvement or Office/Title Held: <input type="text" name="c_office1" value=""><br>	
    Length of involvement: <input type="text" name="c_length1" value=""><br>	
    Estimated time per month: <input type="text" name="c_time1" value=""><br>	
    # 2 Group/Club Activity: <input type="text" name="c_activity2" value=""><br>	
    Involvement or Office/Title Held: <input type="text" name="c_office2" value=""><br>	
    Length of involvement: <input type="text" name="c_length2" value=""><br>	
    Estimated time per month: <input type="text" name="c_time2" value=""><br>	
    # 3 Group/Club Activity: <input type="text" name="c_activity3" value=""><br>	
    Involvement or Office/Title Held: <input type="text" name="c_office3" value=""><br>	
    Length of involvement: <input type="text" name="c_length3" value=""><br>	
    Estimated time per month: <input type="text" name="c_time3" value=""><br>	
    # 4 Group/Club Activity: <input type="text" name="c_activity4" value=""><br>	
    Involvement or Office/Title Held: <input type="text" name="c_office4" value=""><br>	
    Length of involvement: <input type="text" name="c_length4" value=""><br>	
    Estimated time per month: <input type="text" name="c_time4" value=""><br>	
    <br><br>
	
	<h3>Citizenship Essay</h3>
	<h4>The applicant shall respond to the following in 250-300 words.</h4>
	19. "Evaluate a significant experience, achievement or risk that you have taken in high school and its impact on you or your community"
	<br><textarea name="award" form="essay"></textarea> <br><br>	
	Upload Document:
    <input type="file" name="documentUpload" id="fileToUpload"><br><br>
	Upload Image:
    <input type="file" name="imageUpload" id="fileToUpload"><br><br>
	<input type="submit" value="submit" name="submit">
</form>
</tbody>
</table>