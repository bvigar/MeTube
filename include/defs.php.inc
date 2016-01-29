<?php
	/************************/
	/*								*/
	/*		GLOBAL DEFINES		*/
	/*								*/
	/************************/
	define('SERVER_ROOT', '/var/www/webapps');
	define('SITE_ROOT', 'http://localhost/webapps/');
	define('ROOT_USER_DIR', '/var/www/webapps/users/');
	define('GENERIC_VIDEO_IMAGE', '/var/www/webapps/images/generic_video.png');

	define('FILE_ALREADY_EXISTS', 1);
	define('FILE_SIZE_TOO_LARGE', 2);
	define('FILE_INVALID_TYPE', 3);
	define('FILE_VALID', 4);

	/* enable php errors in browser */
	// error_reporting(E_ALL);
	// ini_set('display_errors', 'on');

	/************************/
	/*								*/
	/*		UTIL FUNCTIONS		*/
	/*								*/
	/************************/

	function debug_print($string) {
		$errorString = "---- DEBUG from ";
		$errorString .= substr(date( DATE_RFC822 ), 0, 24);
		$errorString .= " ----\n";
		$errorString .= $string;
		$errorString .= "\n";
		error_log( $errorString, 3, SERVER_ROOT . "logs/errors.log" );
	}

	function validateFileUpload($fileArray) {
		$uploadName 		= $fileArray["name"];
		$uploadTempName	= $fileArray["tmp_name"];
		$uploadType			= $fileArray["type"];
		$uploadError		= $fileArray["error"];
		$uploadSize			= $fileArray["size"];
		$fullPath 			= ROOT_USER_DIR . $_COOKIE["username"] . "/" . $uploadName;
		$allowedExts 		= array("gif", "jpeg", "jpg", "png", "mp3", "mp4", "mov", "wmv", "flv", "avi");
		$extension 			= end( explode(".", $uploadName) );	/* gets the chars after the last period */

		/* set the default file perms to 644 */
		umask(022);

		if ((($uploadType == "image/gif") || 
			  ($uploadType == "image/jpeg") ||
			  ($uploadType == "image/jpg") ||
			  ($uploadType == "image/png") ||
			  ($uploadType == "audio/mpeg") ||		/* mp3 */
			  ($uploadType == "audio/mp3") ||
			  ($uploadType == "video/mp4") ||
			  ($uploadType == "video/quicktime") ||	/* mov */
			  ($uploadType == "video/x-flv") || 		/* flash video */
			  ($uploadType == "video/flv") || 
			  ($uploadType == "video/avi") || 			
			  ($uploadType == "video/x-ms-wmv"))		/* wmv */
					&&
			  ($uploadSize < 20971520)			/* 20MB on school machines */ 
			  		&&
			  in_array($extension, $allowedExts)) 
		{
			if ( file_exists($fullPath) ) {
				debug_print("ERROR : unable to upload file \"" . $fullPath . "\" - already exists.\n");
				return FILE_ALREADY_EXISTS;
			}	
			else {
				debug_print("FILE UPLOAD SIZE : " . $uploadSize);
				move_uploaded_file($uploadTempName, $fullPath);
				debug_print("SUCCESSFUL UPLOAD FROM USER \"" . $_COOKIE["username"] . "\"\n" . $fullPath);
				return FILE_VALID;
			}
		}
		else {
			if ( $uploadSize >= 20971520) {
				debug_print("ERROR : upload file size too large : \n" . $uploadSize);
				return FILE_SIZE_TOO_LARGE;
			}
			else {
				$debugString = "Error : upload file type invalid.\n Size : " . $uploadSize . "\nType : " . $uploadType;
				debug_print($debugString);
				return FILE_INVALID_TYPE;
			}
		}
	}

	function getDateString($month, $day, $year) {
		$retString = "";
	
		$retString .= $year . "-";

		switch ( $month ) {
			case "january" :
				$retString .= "01";
				break;
			case "february" :
				$retString .= "02";
				break;
			case "march" :
				$retString .= "03";
				break;
			case "april" :
				$retString .= "04";
				break;
			case "may" :
				$retString .= "05";
				break;
			case "june" :
				$retString .= "06";
				break;
			case "july" :
				$retString .= "07";
				break;
			case "august" :
				$retString .= "08";
				break;
			case "september" :
				$retString .= "09";
				break;
			case "october" :
				$retString .= "10";
				break;
			case "november" :
				$retString .= "11";
				break;
			case "december" :
				$retString .= "12";
				break;
			default :
				$retString .= "00";
				break;
		}

		$retString .= "-" . $day;

		return $retString;
	}

	function isValidEmail($email)
	{
   	$isValid = true;
   	$atIndex = strrpos($email, "@");
   	if (is_bool($atIndex) && !$atIndex)
   	{
      	$isValid = false;
   	}
   	else
   	{
      	$domain = substr($email, $atIndex+1);
      	$local = substr($email, 0, $atIndex);
      	$localLen = strlen($local);
      	$domainLen = strlen($domain);
      	if ($localLen < 1 || $localLen > 64)
      	{
         	// local part length exceeded
         	$isValid = false;
      	}
      	else if ($domainLen < 1 || $domainLen > 255)
      	{
         	// domain part length exceeded
         	$isValid = false;
      	}
      	else if ($local[0] == '.' || $local[$localLen-1] == '.')
      	{
         	// local part starts or ends with '.'
         	$isValid = false;
      	}
      	else if (preg_match('/\\.\\./', $local))
      	{
         	// local part has two consecutive dots
         	$isValid = false;
      	}
      	else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      	{
         	// character not valid in domain part
         	$isValid = false;
      	}
      	else if (preg_match('/\\.\\./', $domain))
      	{
         	// domain part has two consecutive dots
         	$isValid = false;
      	}
      	else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
      	{
         	// character not valid in local part unless 
         	// local part is quoted
         	if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local)))
         	{
            	$isValid = false;
         	}
      	}
      	if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      	{
         	// domain not found in DNS
         	$isValid = false;
      	}
   	}
   return $isValid;
}

?>
