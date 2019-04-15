<?PHP
require '../functions.php';
$db=mysql_connect($dbhost,$dbuser,$dbpass);
mysql_select_db($dbname,$db);

if(isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post' && !empty($_POST))
{	
	$sid=$_REQUEST['sid'];
	
	//check file existence in the request
	if ( !empty($_FILES) )
	{
		//existing folder on the server for files storing with write access
		$uploaddir = $_SERVER['DOCUMENT_ROOT']."/nsaaforms/downloads/"; 
		
		// define encoding for path names
		$codepage = "ISO-8859-1";
			
		$file = $_FILES["Filedata"]; 
		
		//check on upload errors
		if ( $file['error'] != UPLOAD_ERR_OK )
		{
			switch( $file['error'] )
			{
				case UPLOAD_ERR_INI_SIZE:
					echo "<eaferror>The uploaded file exceeds the upload_max_filesize directive in php.ini.</eaferror>";
					break;
				case UPLOAD_ERR_FORM_SIZE:
					echo "<eaferror>Uploader didn't allow such file size</eaferror>";
					break;
				case UPLOAD_ERR_PARTIAL:
					echo "<eaferror>Uploaded file hasn't been complete uploaded</eaferror>";
					break;
				case UPLOAD_ERR_NO_FILE:
					echo "<eaferror>File hasn't been uploaded</eaferror>";
					break;
			}
			
			return;
		}
		
		//Use this code if the existing files might be rewritten.
		//$uploadfile = $uploaddir . mb_convert_encoding( basename($file['name']), $codepage , 'UTF-8' );
	
		//define a full file path
		if (extension_loaded('mbstring'))
			$fileName = $uploaddir . mb_convert_encoding( basename($file['name']), $codepage , 'UTF-8' );
		else if (extension_loaded('iconv'))
			$fileName = $uploaddir . iconv("UTF-8", $codepage, basename($file['name']));
		else
		{
			echo "<eaferror>Please enable mbstring extension or iconv extension in your php.ini file.</eaferror>";	
		}
		
		// check on duplicate file names and if there is a file with the same name add "_(<counter>)" at the end of the name of the new file
		$pathinfo=pathinfo($fileName);
		$uniqueFileName = sprintf("%s/pp_teamphoto_%s_1.%s",$pathinfo['dirname'],$sid,$pathinfo['extension']); //$fileName;
		for($k = 1; $k < 100; $k++)
		{
			if(!citgf_file_exists($uniqueFileName))
			{
				break;
			}
			else
			{
				$pathInfo = pathinfo($fileName);
				$ext=$pathInfo['extension'];
				$uniqueFileName = sprintf("%s/pp_teamphoto_%s_%s.%s", $pathInfo['dirname'], $sid, $k, $pathInfo['extension']);
                                $uniqueThumbName = sprintf("%s/pp_teamphoto_resized_%s_%s.%s", $pathInfo['dirname'], $sid, $k, $pathInfo['extension']);
			}
	
		}
		
		//move uploaded file from temp location		
		if ( citgf_moveuploadedfile( $file['tmp_name'], $uniqueFileName ) )
		{
			//DOWNSIZE
        		$image;
        		if( strcasecmp($ext, "jpeg") == 0 || strcasecmp($ext, "jpg") == 0 )
             			$image = imagecreatefromjpeg( $uniqueFileName );
        		elseif( strcasecmp($ext, "png") == 0 )
             			$image = imagecreatefrompng( $uniqueFileName );
        		elseif( strcasecmp($ext, "gif") == 0 )
             			$image = imagecreatefromgif( $uniqueFileName );
			else if(citgf_file_exists($uniqueFileName))	//ASSUME JPEG
				$image = imagecreatefromjpeg( $uniqueFileName );
        		else $image="NONE";
        		if($image!="NONE")
        		{
	/*
             			$imageWidth = imagesx( $image );
             			$imageHeight = imagesy( $image );
             			$imageRatio = $imageWidth/$imageHeight;

                		//DOWNSIZE
             			$thumbRatio = (190/131);
				$thumbWidth=$imageWidth; //1000;
				$thumbHeight=$thumbWidth/$imageRatio;
                		$imageY=0;
                		$imageX=0;

             			$thumbimage = imagecreatetruecolor((int)$thumbWidth, (int)$thumbHeight);
             			if(@imagecopyresampled($thumbimage, $image, 0, 0, $imageX, $imageY, $thumbWidth, $thumbHeight, $imageWidth, $imageHeight))
             			{
                			imagejpeg($thumbimage,$uniqueThumbName,100);
                			imagedestroy($thumbimage);
             			}
             			imagedestroy($image);
	*/
                	}
			//UPDATE DATABASE	
			$useFileName=basename($uniqueFileName);
			$sql="UPDATE ppschool SET filename='$useFileName' WHERE sid='$sid'";
			//echo "<eaferror>$sql</eaferror>";
		      	$result=mysql_query($sql);
		}
		else
		{
			echo "<eaferror>Can't move file from temporary directory to destination. Please check read/write permissions of destination folder: $uploaddir.</eaferror>";
		}
		
	}
	else
	{
		echo "<eaferror>Request didn't contain the file. Usually this situation occures if request size exceeds the post_max_size directive in php.ini.</eaferror>";
	}
	
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Multiple files upload.</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<center>
<form name="Form1" >
<input type=hidden id='sid' name='sid' value="<?php echo $sid; ?>">
<div id="EAFlashUpload_holder">
You need at least Adobe Flash Player 10 for successful work. Download the latest version from here:
<br />
<a href="#" onClick="window.location.reload()">Adobe Flash Player</a>
</div>
</form>

<!-- Embedding the EAFlashUpload control -->
<script type="text/javascript" src="easyalgo/swfobject/swfobject.js"></script>
<script type="text/javascript">
	var params = {  
		BGcolor: "#ffffff",
		wmode: "window"
	};
	
	// id and name attribute may contain any value. 
	// You need to use specified below identifier to access to the EAFlashUpload methods and properties.
	var attributes = {  
		id: "EAFlashUpload",  
		name: "EAFlashUpload"								
	};
	
	var flashvars = new Object();	
	
	// In IE and non-IE browsers Flash resolve relative path differently if page with EAFlashUpload is placed in a different directory than swf files. 
	// If you place EAFlashUpload files and container page in the same directory then the problem doesn't appear.
	// Below code detects non-IE browsers and changes url of upload script.
	var uploadUrl = "simpleupload.php"; //"https://secure.nsaahome.org/nsaaforms/jo/simpleupload.php"; //Note: & symbol should be encoded to %26 for query string values. Ex: http://www.somesite.com/uploader.aspx?field1=value1%26field2=value2
        if (!document.all) {
            uploadUrl = "../simpleupload.php";
        }
	flashvars["uploader.uploadUrl"] = uploadUrl;	
    	flashvars["viewFile"] = "easyalgo/TableView.swf";
	flashvars["queue.filesCountLimit"] = "1";
        flashvars["uploader.formToSend"] = "Form1";
	flashvars["view.infoLabelText"] = "Click Add to find your file. Then click Upload. Please be patient.";

	swfobject.embedSWF("easyalgo/EAFUpload.swf", "EAFlashUpload_holder", "400", "200", "10.0.0", "easyalgo/swfobject/expressInstall.swf", flashvars, params, attributes);	
	
	// Handles EAFlashUpload's onMovieLoad event and displays existing loading errors.
	function EAFlashUpload_onMovieLoad(errors)
	{		
		if(errors != "")
			alert(errors);	
	}
</script>
</center>
</body>
</html>
