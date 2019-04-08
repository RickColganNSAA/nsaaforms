function validateCoopForm1()
{
var errmes="";
var valid=true;
var sch2name=document.forms["first"]["sch2_name"].value;
var acts=new array();
acts=document.getElementsByName('activity[]');
var years=new array();
years=document.getElementsByName('years[]');


if (sch2name==null || sch2name=="")
  {
  errmes += "Please select at least one school\n";
  valid=false;
  } 

if (acts.length=0)
 {
 errmes += "Please select at least one activity\n";
 valid=false;
 }
 
if (years.length<2)
 {
 errmes += "Please select two consecutive years\n";
 valid=false;
 }
 if (!valid)
 {
 alert(errmes);
 }
return valid;
 
 }
 
 function validateCoopForm2()
 {
var errmes="";
var valid=true;
var purp1=document.forms["second"]["purpose1"].value;
var purp2=document.forms["second"]["purpose2"].value;
var purp3=document.forms["second"]["purpose3"].value;
var purp4=document.forms["second"]["purpose4"].value;
var teamname=document.forms["second"]["teamname"].value;
var teammascot=document.forms["second"]["teammascot"].value;
var teamcolors=document.forms["second"]["teamcolors"].value;
var condist=document.forms["second"]["contract_dist"].value;
var reimb=document.forms["second"]["reimburse"].value;


if (purp1==null || purp1=="")
  {
  errmes += "Please enter at least one purpose.\n";
  valid=false;

  } else if (purp1.length>500)
  {
  errmes += "Please enter no more than 500 characters for the first purpose\n";
  valid=false;  
  }
  
if (purp2.length>500)
  {
  errmes += "Please enter no more than 500 characters for the second purpose\n";
  valid=false;  
  }
  
 if (purp3.length>500)
  {
  errmes += "Please enter no more than 500 characters for the third purpose\n";
  valid=false;  
  }
  
 if (purp4.length>500)
  {
  errmes += "Please enter no more than 500 characters for the fourth purpose\n";
  valid=false;  
  }

if (teamname==null || teamname=="")
  {
  errmes += "Please enter the team name\n";
  valid=false;  
  } else if (teamname.length>100) 
  {
  errmes += "Team name cannot be longer than 100 characters\n";
  valid=false;  
  }
  
  if (teammascot==null || teammascot=="")
  {
  errmes += "Please enter the team mascot\n";
  valid=false;
  } else if (teammascot.length>100) 
  {
  errmes += "Team mascot cannot be longer than 100 characters\n";
  valid=false;
  }

  if (teamcolors==null || teamcolors=="")
  {
  errmes += "Please enter the team colors\n";
  valid=false;
  } else if (st.length>50) 
  {
  errmes += "Team colors cannot be longer than 50 characters\n";
  valid=false;
  }
  
  if (condist==null || condist=="")
  {
  errmes += "Please select the district contracts should be made out to\n";
  valid=false;
  } 
  
  if (reimb==null || reimb=="")
  {
  errmes += "Please select the school reimbursement checks should be written to\n";
  valid=false;
  }
if (!valid)
 {
 alert(errmes);
 }
return valid;
  
 }
 
function validateCoopForm3()
{
var errmes="";
var valid=true;
var dtexp=document.forms["third"]["dtransexp_all"].value;
var atexp=document.forms["third"]["atransexp_all"].value;
var spbus=document.forms["third"]["specbusexp_all"].value;
var facil=document.forms["third"]["facilexp_all"].value;
var banq=document.forms["third"]["banqexp_all"].value;
var scout=document.forms["third"]["scoutexp_all"].value;
var ref=document.forms["third"]["refexp_all"].value;
var supp=document.forms["third"]["suppexp_all"].value;
var sal=document.forms["third"]["salexp_all"].value;
var other=document.forms["third"]["otherexp_all"].value;


  if (dtexp==null || dtexp=="")
  {
  errmes += "Please enter daily transportation expense allocation\n";
  valid=false;
  } else if (dtexp.length>500) 
  {
  errmes += "Daily transporation expense allocation must not be longer than 500 characters\n";
  valid=false;
  }
  if (atexp==null || atexp=="")
  {
  errmes += "Please enter away contest transportation expense allocation\n";
  valid=false;
  } else if (atexp.length>500) 
  {
  errmes += "Away contest transporation expense allocation must not be longer than 500 characters\n";
  valid=false;
  }
  
  if (spbus==null || spbus=="")
  {
  errmes += "Please enter expense allocation for spectator buses\n";
  valid=false;
  } else if (spbus.length>500) 
  {
  errmes += "Spectator bus expense allocation must not be longer than 500 characters\n";
  valid=false;
  }
 
 if (facil==null || facil=="")
  {
  errmes += "Please enter facilities expense allocation\n";
  valid=false;
  } else if (facil.length>500) 
  {
  errmes += "Facilities expense allocation must not be longer than 500 characters\n";
  valid=false;
  }
  
if (banq==null || banq=="")
  {
  errmes += "Please enter banquets and awards expense allocation\n";
  valid=false;
  } else if (banq.length>500) 
  {
  errmes += "Banquet and award expense allocation must not be longer than 500 characters\n";
  valid=false;
  }
  
if (scout==null || scout=="")
  {
  errmes += "Please enter scouting/meeting/workshop expense allocation\n";
  valid=false;
  } else if (scout.length>500) 
  {
  errmes += "Scouting, meeting, and workshop expense allocation must not be longer than 500 characters\n";
  valid=false;
  }
  
if (sal==null || sal=="")
  {
  errmes += "Please enter salary and fringe benefit expense allocation\n";
  valid=false;
  } else if (sal.length>500) 
  {
  errmes += "Salary and fringe benefit expense allocation must not be longer than 500 characters\n";
  valid=false;
  }

if (ref==null || ref=="")
  {
  errmes += "Please enter referee payment expense allocation\n";
  valid=false;
  } else if (ref.length>500) 
  {
  errmes += "Referee payment expense allocation must not be longer than 500 characters\n";
  valid=false;
  }

if (supp==null || supp=="")
  {
  errmes += "Please enter supplies and equipments expense allocation\n";
  valid=false;
  } else if (supp.length>500) 
  {
  errmes += "Supplies and equipments expense allocation must not be longer than 500 characters\n";
  valid=false;
  }  
  
if (other.length>500) 
  {
  errmes += "Other expense allocation must not be longer than 500 characters\n";
  valid=false;
  }  
if (!valid)
 {
 alert(errmes);
 } 
return valid;
}

function validateCoopForm4()
{
var errmes="";
var valid=true;
var gate=document.forms["fourth"]["gate_all"].value;
var insuf=document.forms["fourth"]["insufgate_all"].value;
var hdist=document.forms["fourth"]["hdist"].value;
var jp1=document.forms["fourth"]["jp_personnel1"].value;
var jp2=document.forms["fourth"]["jp_personnel2"].value;
var jp3=document.forms["fourth"]["jp_personnel3"].value;
var jpe1=document.forms["fourth"]["jp_employer1"].value;
var jpe2=document.forms["fourth"]["jp_employer2"].value;
var jpe3=document.forms["fourth"]["jp_employer3"].value;  
var claimant=document.forms["fourth"]["claimant_ins"].value;  
var claim=document.forms["fourth"]["claim_ins"].value;  
 
if (gate==null || gate=="")
  {
  errmes += "Please specify how gate receipts funds are to be divided\n";
  valid=false;
  } else if (gate.length>500) 
  {
  errmes += "Allocation of gate receipts must not be longer than 500 characters\n";
  valid=false;
  }
  
if (insuf==null || insuf=="")
  {
  errmes += "Please specify how referees are paid if gate receipts are insufficient\n";
  valid=false;
  } else if (insuf.length>500) 
  {
  errmes += "Referee payment division after insufficient gate receipts must not be longer than 500 characters\n";
  valid=false;
  }
  
if (hdist==null || hdist=="")
  {
  errmes += "Please select the district employing the head coach\n";
  valid=false;
  }

if (jp1!=null || jp1!="")
  {
    if (jpe1==null || jpe1=="")
	  {
	    errmes += "Please select an employer for the first joint program personnel listed\n";
		valid=false;
	  }
	}
	
if (jp2!=null || jp2!="")
  {
    if (jpe2==null || jpe2=="")
	  {
	    errmes += "Please select an employer for the second joint program personnel listed\n";
		valid=false;
	  }
	}
	
if (jp3!=null || jp3!="")
  {
    if (jpe3==null || jpe3=="")
	  {
	    errmes += "Please select an employer for the third joint program personnel listed\n";
		valid=false;
	  }
	}

if (claimant==null || claimant=="")
  {
  errmes += "Please enter amount of insurance per claimant\n";
  valid=false;
  } 
  
if (claim==null || claim=="")
  {
  errmes += "Please enter amount of insurance per claim\n";
  valid=false;
  } 
  
 if (!valid)
 {
 alert(errmes);
 }
 return valid;
   
}