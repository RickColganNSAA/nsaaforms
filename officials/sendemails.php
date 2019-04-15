<?php
/************************************************
sendemails.php:
Sends e-mails of announcements; runs in background
Created 3/3/08
*************************************************/
$db_name="nsaaofficials";
$db_user="nsaa";
$db_pass="3zyg15rexvs4kgo";
$db_name2="nsaaofficials";
$db_user2="nsaa";
$db_pass2="3zyg15rexvs4kgo";
$db_host="phpapp-pub-new.c1pz8ojztooh.us-east-1.rds.amazonaws.com";
//$db_host="phpapp-pub.c1pz8ojztooh.us-east-1.rds.amazonaws.com";
$stateassn="NSAA";
global $db;
$db=mysqli_connect($db_host,$db_user,$db_pass);
$dbselected=mysqli_select_db($db, $db_name);


function get_mime_type($filename) {
    $idx = explode( '.', $filename );
    $count_explode = count($idx);
    $idx = strtolower($idx[$count_explode-1]);

    $mimet = array( 
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',

        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',

        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',

        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',

        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',

        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'docx' => 'application/msword',
        'xlsx' => 'application/vnd.ms-excel',
        'pptx' => 'application/vnd.ms-powerpoint',


        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    );

    if (isset( $mimet[$idx] )) {
     return $mimet[$idx];
    } else {
     return 'application/octet-stream';
    }
 }

require_once ('/var/www/html/aws-autoloader.php');

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
global $s3Client;
global $bucketName;
global $stremwraperbucket;

$s3Client = new S3Client([
	'version'     => 'latest',
	'region'      => 'us-east-1',
	]);
	
$bucketName='nsaa-static'; 

$stremwraperbucket='s3://'.$bucketName; 

$s3Client->registerStreamWrapper();
	
function getsource($getsource){
	
	global $s3Client;
	global $bucketName;
	global $stremwraperbucket;
		$basename=basename($getsource);
	$getsource=str_replace('//','/',$getsource);
	
	$root="/var/www/html";
	$root=str_replace('//','/',$root);
	
	$fileroot=str_replace("//","/",dirname  ("/var/www/html/".$_SERVER['PHP_SELF'])); 
	
	if(strstr($getsource,'/home/nsaahome/public_html'))
		return str_replace('/home/nsaahome/public_html/','', $getsource);
	
	if(strstr($getsource,'/home/nsaahome/www/'))
		return str_replace('/home/nsaahome/www/','', $getsource);
	
	if(strstr($getsource,'/home/nsaahome/photos'))
		return str_replace('/home/nsaahome/photos/','photos/', $getsource);
	
	if(strstr($getsource,'/home/nsaahome/reports'))
		return str_replace('/home/nsaahome/reports/','reports/', $getsource);
	
	if(strstr($getsource,'/home/nsaahome/attachments'))
		return str_replace('/home/nsaahome/attachments/','attachments/', $getsource);
		
	if(strstr($getsource,$root))
	{	
		$getsource=str_replace("//",'/', $getsource); 
		return str_replace($root,'', $getsource);
	}
	
	if(strstr($getsource,'../../'))
	{	
		
		$newfileroot=dirname(dirname($fileroot)); 
			$basename=str_replace('../../','', $getsource);
		
			if(strstr($newfileroot,rtrim($root,'/')))
			{
				 $filesource=str_replace($root,'', $newfileroot)."/".$basename; 
				 
				if(strstr($filesource,$root))
				{
				
				
						return	str_replace($root,'', $filesource); 
				}		
				else 	return	str_replace($root,'', $newfileroot)."/".$basename; 
			}	
			elseif(strstr($newfileroot."/".$basename,dirname($root)))
			{
				return str_replace(dirname($root),'', $newfileroot)."/".$basename;
			}	
				
		
	}
	
	
	
	if(strstr ($getsource,'../')!=false)
	{	
	
	 	  $newfileroot=dirname($fileroot); 
		$basename=str_replace('../','', $getsource);
			if(strstr($newfileroot,rtrim($root,'/')))
			{	
				return  str_replace($root,'', $newfileroot."/".$basename); 
				
			}	
			
			elseif(strstr($newfileroot,rtrim(dirname($root).'/')))
				return str_replace(dirname($root),'', $newfileroot)."/".$basename;
				
		
	}
	
	
	 $string=str_replace($root,'', $fileroot."/".$getsource);
	$string=str_replace('//','/', $string);
	$string=str_replace('calculate/calculate/','calculate/', $string);
	$string=str_replace('nsaaforms/nsaaforms/','nsaaforms', $string);
	$string=str_replace('publications/publications/','publications', $string);
	$string=str_replace('awards/awards/','awards', $string);
	$string=str_replace('about/about/','about', $string);
	$string=str_replace('middle/middle/','middle', $string);
	$string=str_replace('textfile/textfile/','textfile', $string);
	
	return $string;
		  
	
}


function citgf_fopen($source_path, $readtype='r'){
	global $s3Client;
	global $stremwraperbucket;
	global $bucketName;
	global $handle;
	$getsource=getsource($source_path);
	
	$getsource=str_replace("//","/",$getsource);
	$getsource=ltrim($getsource,"/");
	
	
	
			
	if(is_file($stremwraperbucket.'/'.$getsource)){
		
		$result = $s3Client->putObjectAcl(array(
			'ACL' => 'public-read',
			'Bucket' => $bucketName,
			'Key' => $getsource,
			
		));
		
		
		return ($stremwraperbucket.'/'.$getsource);
		
		
		
	}
	elseif($readtype=='w'|| $readtype=='W'||$readtype=='w+'){
	
		$ContentType=get_mime_type($getsource) ; 
		
		$result = $s3Client->putObject(array(	
			'Bucket' => $bucketName,
			'ContentType'  => $ContentType,
			'Key'    => $getsource,
			'Body' => '',
			'ACL'    => 'public-read'
			));
		
			return ($stremwraperbucket.'/'.$getsource);
		
	}
	else {
		if(is_file($source_path))
		{
			return $source_path;
		
		}
		else 
		{
			$ContentType=get_mime_type($getsource) ; 
			$result = $s3Client->putObject(array(	
				'Bucket' => $bucketName,
				'ContentType'  => $ContentType,
				'Key'    => $getsource,
				'Body' => '',
				'ACL'    => 'public-read'
			));
			return ($stremwraperbucket.'/'.$getsource);
		}	
	}
	
	if(is_file($source_path))
	{
		return $source_path;
		
	}	
return $source_path;
		
	
	
}



function SendMail($From,$FromName,$To,$ToName,$Subject,$Text,$Html,$AttmFiles)
{
	global $db;
  
  $Html=$Html?$Html:preg_replace("/\n/","{br}",$Text) or die("neither text nor html part present.");
  $Text=$Text?$Text:"Sorry, but you need an html mailer to read this mail.";
  

   include_once("/var/www/html/nsaaforms/PHPMailer/class.phpmailer.php");
   // Instantiate your new class  
   $mail = new PHPMailer;
   // Now you only need to add the necessary stuff  
   $mail->AddAddress($To, $ToName);
   $mail->SMTPAuth = true;
   $mail->SMTPDebug = 3;
   $mail->SMTPSecure = 'tls';
   $mail->Port = 587;
   $mail->Host = 'email-smtp.us-east-1.amazonaws.com';
   $mail->Username = 'AKIAJNOLQTFTCIEP7ZCA';
//Password to use for SMTP authentication
	$mail->Password = 'AlPp0g02UR2xWZ1Lvk8DDJqaB11HMV3Uuh+sh41+7l/s';
   $mail->setFrom("nsaa@nsaahome.org", "NSAA");
   $mail->AddReplyTo("nsaa@nsaahome.org", "NSAA");
  
   $mail->Subject = $Subject;
   $mail->IsHTML(true);
   $mail->AltBody = $Text;
   $mail->Body = $Html;
	
	if($AttmFiles)
  {
    foreach($AttmFiles as $AttmFile)
    {
      $patharray = explode ("/", $AttmFile); 
      $FileName=$patharray[count($patharray)-1];
      
      $fd=fopen(citgf_fopen ($AttmFile), "r");
      //$FileContent=fread($fd,citgf_filesize($AttmFile));
	  $FileContent=stream_get_contents($fd);
      fclose ($fd);
      
      $mail->addStringAttachment($FileContent, $FileName);
    }
  }
	if(!$mail->send()) {
		$dump="Email not sent  $To " . $mail->ErrorInfo;
		echo "Email not sent  $To " . $mail->ErrorInfo;
	} else {
		$dump="Email  sent " . $To;
		echo "Email  sent " . $To;
  
	}
	
   /*
	if(!$mail->send()) {
		echo "email sent ". $Subject;
		$dump=null;
	} else {
		$dump=true;
	}
  */
  $db_name="nsaascores";
  $Subject=addslashes($Subject);
  $time=date(r);
  $sql="INSERT INTO $db_name.maillog (recipient,subject,time) VALUES ('$To','$Subject','$time')";
  global $db;
  $result=mysqli_query($db,$sql);
  return $dump;
}

if(isset($_REQUEST['get_argv'])){
	
	$argv=array();
	if(isset($_GET['var1']))	$argv[1]=$_GET['var1'];
	if(isset($_GET['var2']))	$argv[2]=$_GET['var2'];
	if(isset($_GET['var3']))	$argv[3]=$_GET['var3'];
	if(isset($_GET['var4']))	$argv[4]=$_GET['var4'];
	if(isset($_GET['var5']))	$argv[5]=$_GET['var5'];
	if(isset($_GET['var6']))	$argv[6]=$_GET['var6'];
	if(isset($_GET['var7']))	$argv[7]=$_GET['var7'];
	if(isset($_GET['var8']))	$argv[8]=$_GET['var8'];
	if(isset($_GET['var9']))	$argv[9]=$_GET['var9'];
	if(isset($_GET['var10']))	$argv[10]=$_GET['var10'];
	if(isset($_GET['var11']))	$argv[11]=$_GET['var11'];
	if(isset($_GET['var12']))	$argv[12]=$_GET['var12'];
}


$session=$argv[1];
 $annid=$argv[2];
$recipients=$argv[3];
/*
$level=GetLevel($session);

if(!ValidUser($session))
{
   echo "Invalid User.";
   exit();
}

*/
 
 

$recips=explode("<recipient>",$recipients);
$fromname="NSAA";
 $sql="SELECT * FROM messages WHERE id='$annid'";  
$result=mysqli_query($db,$sql);
if(mysqli_num_rows($result)==0)
{
   echo "No message found for ID# $annid.";
   exit();
}
else
{
	
   $row=mysqli_fetch_array($result);
   
   $from=$row[fromemail];
   if(trim($from)=="") $from="nsaa@nsaahome.org";
   $title=$row[title]; 
   $announcement=$row[message];  
   $linkname=$row[linkname]; 
   $email_text="The following message has been posted by the NSAA:\r\n\r\n".$announcement;   
   if($row[filename]!='')
      $email_text.="\r\n\r\nA file was attached to this message.  Please login at https://secure.nsaahome.org/nsaaforms/officials to view the attachment under \"Messages\".  Thank You!";
   $email_html=str_replace("\r\n","<br>",$email_text);   
   $email_html=str_replace("https://secure.nsaahome.org/nsaaforms/","<a href=\"https://secure.nsaahome.org/nsaaforms/officials\">https://secure.nsaahome.org/nsaaforms/officials</a>",$email_html);
   $attm=array(); $ct=0;
   for($i=0;$i<count($recips);$i++)
   {
      $To=trim($recips[$i]); $ToName=$To;
      if(trim($To)!='') 
      {
		  
         $string[]=SendMail($from,$fromname,$To,$ToName,$title,$email_text,$email_html,$attm);
		 if($ct%50==0) {
			  $result = $s3Client->putObject(array(	
			'Bucket' => $bucketName,
			'ContentType'  => 'text/plain',
			'Key'    => 'emaillogcountpost.txt',
			'Body' => $ct.' eamils sent ',
			'ACL'    => 'public-read'
			));
			sleep(3);
			
		}
	 $ct++;
	
      }
      //if(trim($To)!='')
         //SendMail($from,$fromname,"run7soccer@aim.com","Ann Gaffigan",$title,$email_text,$email_html."<br><br>$To, $ToName<br>".count($recips)." recipients total. ($recipients)",$attm);
   }
   
   $result = $s3Client->putObject(array(	
			'Bucket' => $bucketName,
			'ContentType'  => 'text/plain',
			'Key'    => 'emaillogcountpost.txt',
			'Body' => $ct.' eamils sent ',
			'ACL'    => 'public-read'
			));
   
   $strings=implode("\n",$string);
   
   $result = $s3Client->putObject(array(	
			'Bucket' => $bucketName,
			'ContentType'  => 'text/plain',
			'Key'    => 'emaillogpost.txt',
			'Body' => $strings,
			'ACL'    => 'public-read'
			));
   echo "$ct emails sent!";
}
?>
