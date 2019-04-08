<?PHP
require '../functions.php';
$db=mysql_connect($dbhost,$dbuser,$dbpass);
mysql_select_db($dbname,$db);

if(isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post' && !empty($_POST))
{	
	$entryid=$_REQUEST['entryid'];
        $filenum=$_REQUEST['filenum'];
	
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
		$uniqueFileName = sprintf("%s/%s_1.%s",$pathinfo['dirname'],$entryid,$pathinfo['extension']); //$fileName;
		$k=2;
		while(citgf_file_exists($uniqueFileName))
		{
				$pathInfo = pathinfo($fileName);
				$uniqueFileName = sprintf("%s/%s_%s.%s", $pathInfo['dirname'], $entryid, $k, $pathInfo['extension']);
				$k++;
		}
                        //echo "<eaferror>FILE: $uniqueFileName</eaferror>";
		
		//move uploaded file from temp location		
		if ( citgf_moveuploadedfile( $file['tmp_name'], $uniqueFileName ) )
		{
			//UPDATE DATABASE	
			$useFileName=basename($uniqueFileName);
	 		if($filenum==2) $filenamevar="filename2";
			else $filenamevar="filename";
			$sql="UPDATE joentries SET $filenamevar='$useFileName' WHERE id='$entryid'";
		      	$result=mysql_query($sql);
                        echo "Upload was successful.";
?>
                <script>
                console.log("Moved file from ".$file['tmp_name']." to $uniqueFileName - $sql");
                </script>
<?php
		}
		else
		{
			echo "<eaferror>Can't move file from temporary directory to destination. Please check read/write permissions of destination folder: $uploaddir.</eaferror>";
			?>
		<script>
		console.log("Cannot move file from ".$file['tmp_name']." to $uniqueFileName");
		</script>
	<?php
		}
		
	}
	else
	{
?>
                <script>
		console.log("Request didn't contain the file. Usually this situation occures if request size exceeds the post_max_size directive in php.ini.");
	</script>
<?php
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
<input type=hidden id='entryid' name='entryid' value="<?php echo $entryid; ?>">
<input type=hidden id='filenum' name='filenum' value="<?php echo $filenum; ?>">
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
	flashvars["view.infoLabelText"] = "Choose file to upload. Then click Upload.";

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
