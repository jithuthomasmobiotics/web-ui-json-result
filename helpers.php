<?php

/*********************************************************
   Author: 	Anuraj Ennai
   Copyright: 	Mobiotics IT Solution Private Limited
   Version: 	1.0
   Date:	17-Feb-2011


   FileName: 	helpers.php
   Description:	utility functions
**********************************************************/

error_reporting(E_ALL);

//Log and error log file to be used for Hive Analysis
function hlog_errorlog_withlevel($msg,$logfile="hlog_errorlog",$loglevel=VBOXLITE_DEFAULT_LOGLEVEL)
{
	$sethost = true;
	$levelstr="";
	switch($loglevel)
	{
		case VBOXLITE_LOGLEVEL_CRITICAL :
		$levelstr = "CRITICAL";
		break;

		case VBOXLITE_LOGLEVEL_WARNING :
		$levelstr = "WARNING";
		break;

		case VBOXLITE_LOGLEVEL_INFO :
		$levelstr = "INFO";
		break;
	}

	if(VBOXLITE_SYSTEM_LOGLEVEL >= $loglevel){
		hlog($msg,$logfile,false,$sethost,$levelstr);
	}
}

function errorlog_withlevel($msg, $loglevel=VBOXLITE_DEFAULT_LOGLEVEL)
{
	$levestr ="";
	switch($loglevel)
	{
		case VBOXLITE_LOGLEVEL_CRITICAL :
		$levelstr = "CRITICAL";
		break;

		case VBOXLITE_LOGLEVEL_WARNING :
		$levelstr = "WARNING";
		break;

		case VBOXLITE_LOGLEVEL_INFO :
		$levelstr = "INFO";
		break;
	}

	if(VBOXLITE_SYSTEM_LOGLEVEL >= $loglevel)
	{
		error_log("[".$levelstr."]".$msg);
	}
}

//Log file to be used for Hive Analysis
function hlog($msg,$logfile,$setperm=false,$sethost=false,$levelstr=NULL)
{

		date_default_timezone_set(DEFAULT_TIME_ZONE);
		$today = date("Y-m-d");

		if($sethost)
		{
			$hostname = gethostname();
			$logfile = $logfile.'_'.$hostname;
		}

		$filename = VBOXLITE_LOG_LOCATION.$logfile.'_'.$today.'.csv';

		if (file_exists($filename)){
			if($setperm)
			{
				chmod($filename,0777);
			}
		}

		$fd = fopen($filename, "a");
		$timestamp = round(microtime(true));

		if(!empty($levelstr))
		{
			fwrite($fd, $timestamp.LLOG_SEPARATOR."[".$levelstr."]".LLOG_SEPARATOR.$msg.PHP_EOL);
		}else
		{
			fwrite($fd, $timestamp.LLOG_SEPARATOR.$msg.PHP_EOL);
		}
		fclose($fd);
}

function dlog($msg, $logfile, $ext)
{
	$filename = VBOXLITE_LOG_LOCATION.$logfile.$ext;
	$fd = fopen($filename, "a");
	$timestamp = round(microtime(true));
	fwrite($fd, $timestamp.' '.$msg . PHP_EOL);
	fclose($fd);
}


//Get device type from user agent header
function mobileDeviceTypeFromHTTPHeader()
{
  	//figure out the device
	if(isset($_SERVER['HTTP_USER_AGENT']));
 	{
  		$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

  		if(strpos($user_agent, 'iphone'))
  		{
     			return DEVICE_IPHONE;
  		}else if(strpos($user_agent, 'android'))
  		{
     			return DEVICE_ANDROID;
  		}else if(strpos($user_agent, 'windows phone'))
  		{
     			return DEVICE_WP7;
  		}else if(strpos($user_agent, 'blackberry'))
  		{
     			return DEVICE_BB;
  		}else if(strpos($user_agent, 'ipad'))
  		{
     			return DEVICE_IPAD;
  		}else if(strpos($user_agent, 'symbianos'))
  		{
     			return DEVICE_SYMBIAN;
  		}

	}

        return DEVICE_GENERAL;
}



function mysql_fix_string($string,$mysqlcon)
{
	if (get_magic_quotes_gpc()) $string = stripslashes($string);
	return $mysqlcon->real_escape_string($string);
}



//Fail with error json
function HTTPFail($message)
{
	errorlog_withlevel("message=====>".$message,3);
	print json_encode(array('error'=>$message));
	exit(0);
}


//Fail with HTTP Code
function HTTPFailWithCode($code,$message)
{
	header(reasonForCode($code));
	exit($message);
}


function HTTPRedirect($location)
{
	print json_encode(array('redirect'=>$location));
	exit(0);
}


//HTTP reason codes
function reasonForCode($code)
{

	switch ($code) {
                   case 100: $text = 'Continue'; break;
                   case 101: $text = 'Switching Protocols'; break;
                   case 200: $text = 'OK'; break;
                   case 201: $text = 'Created'; break;
                   case 202: $text = 'Accepted'; break;
                   case 203: $text = 'Non-Authoritative Information'; break;
                   case 204: $text = 'No Content'; break;
                   case 205: $text = 'Reset Content'; break;
                   case 206: $text = 'Partial Content'; break;
                   case 300: $text = 'Multiple Choices'; break;
                   case 301: $text = 'Moved Permanently'; break;
                   case 302: $text = 'Moved Temporarily'; break;
                   case 303: $text = 'See Other'; break;
                   case 304: $text = 'Not Modified'; break;
                   case 305: $text = 'Use Proxy'; break;
                   case 400: $text = 'Bad Request'; break;
                   case 401: $text = 'Unauthorized'; break;
                   case 402: $text = 'Payment Required'; break;
                   case 403: $text = 'Forbidden'; break;
                   case 404: $text = 'Not Found'; break;
                   case 405: $text = 'Method Not Allowed'; break;
                   case 406: $text = 'Not Acceptable'; break;
                   case 407: $text = 'Proxy Authentication Required'; break;
                   case 408: $text = 'Request Time-out'; break;
                   case 409: $text = 'Conflict'; break;
                   case 410: $text = 'Gone'; break;
                   case 411: $text = 'Length Required'; break;
                   case 412: $text = 'Precondition Failed'; break;
                   case 413: $text = 'Request Entity Too Large'; break;
                   case 414: $text = 'Request-URI Too Large'; break;
                   case 415: $text = 'Unsupported Media Type'; break;
                   case 500: $text = 'Internal Server Error'; break;
                   case 501: $text = 'Not Implemented'; break;
                   case 502: $text = 'Bad Gateway'; break;
                   case 503: $text = 'Service Unavailable'; break;
                   case 504: $text = 'Gateway Time-out'; break;
                   case 505: $text = 'HTTP Version not supported'; break;
		   default: $text = 'Unknown Error';break;
		}

	return 'HTTP/1.1'.' '.$code.' '.$text;
}

//Only alphanumeric characters and underscore permitted
function gen_uuid($len=8)
{
    $hex = md5("tvbuddy2_mobiotics" . uniqid("", true));

    $pack = pack('H*', $hex);

    $uid = base64_encode($pack);        // max 22 chars

    $uid = preg_replace("/[^a-z0-9_]+/i", "", $uid);   // mixed case


    if ($len<4)
        $len=4;
    if ($len>128)
        $len=128;                       // prevent silliness, can remove

    while (strlen($uid)<$len)
        $uid = $uid . gen_uuid(22);     // append until length achieved

    return substr($uid, 0, $len);
}

function sessionValidate($roleid,$id=NULL)
{

	if (session_status() == PHP_SESSION_NONE) {
    session_start();
	}

	if(!isset($_SESSION[$roleid]))
	{
		return false;
	}

	if(!empty($id) && $_SESSION[$roleid] !== $id)
	{
		return false;
	}

	$id = $_SESSION[$roleid];
// print_r($_SESSION);die;

	if((time()- $_SESSION['LOGIN_TIME']) >= SESSION_EXPIRY_TIME)
	{
		errorlog_withlevel('Session Expired: '.$id.' from '.$_SERVER['REMOTE_ADDR'],2);
		session_destroy();
		return false;
	}


	return $id;
}

/*function sessionValidate($id=NULL)
{
	session_start();

	if(!isset($_SESSION['SESS_SUBSCRIBER_ID']))
	{
		return false;
	}

	if(empty($id))
	{
		$id = $_SESSION['SESS_SUBSCRIBER_ID'];
	}

	$id = $_SESSION[$id];

	if((time()- $_SESSION['LOGIN_TIME']) >= SESSION_EXPIRY_TIME)
	{
		errorlog_withlevel('Session Expired: '.$id.' from '.$_SERVER['REMOTE_ADDR']);
		session_destroy();
		return false;
	}

	return $id;
}*/


/*function adminSessionValidate($id=NULL)
{
	session_start();

	if(!isset($_SESSION[$id]))
	{
		return false;
	}


	if((time()- $_SESSION['LOGIN_TIME']) >= SESSION_EXPIRY_TIME)
	{
		errorlog_withlevel('Session Expired: '.$_SESSION['SESS_ADMIN_ID'].' from '.$_SERVER['REMOTE_ADDR']);
		session_destroy();
		return false;
	}

	return true;
}

function sessionValidateVendor($id=NULL)
{
	session_start();

	if(!isset($_SESSION['SESS_VENDOR_ID']))
	{
		return false;
	}

	if(empty($id))
	{
		$id = $_SESSION['SESS_VENDOR_ID'];
	}

	$id = $_SESSION[$id];

	if((time()- $_SESSION['LOGIN_TIME_VENDOR']) >= SESSION_EXPIRY_TIME)
	{
		errorlog_withlevel('Session Expired: '.$_SESSION['SESS_VENDOR_ID'].' from '.$_SERVER['REMOTE_ADDR']);
		session_destroy();
		return false;
	}

	return $id;
}
*/

function getMemcache()
{
	$memcache = new MemCache;
	$memcache->connect(MEM_CACHE_SERVER_NAME, MEM_CACHE_SERVER_PORT);
	return $memcache;
}


function getVboxDBConnection()
{
        //Now create db connection
        $mysqlcon = new mysqli(VBOXLITEDB_SERVER,VBOXLITEDB_USER_NAME,VBOXLITEDB_USER_PASSWORD,VBOXLITEDB_NAME);

        if ($mysqlcon->connect_errno) {

                 errorlog_withlevel("Failed to connect to MySQL: (" . $mysqlcon->connect_errno . ") " . $mysqlcon->connect_error,1);
                 return false;
        }

        $mysqlcon->set_charset("utf8");

        return $mysqlcon;
}



function createRandomString($length=8,$type,$prefix=NULL)
{
	$characters = '';

	switch($type)
	{


		case CHAR_TYPE_NUMERIC:
		$characters = '0123456789';
		break;

		case CHAR_TYPE_ALPHABETS:
		$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		break;

		case CHAR_TYPE_ALPHANUMERIC:
		default:
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		break;
	}

    	$randomString = '';
    	for ($i = 0; $i < $length; $i++)
	{
        	$randomString .= $characters[rand(0, strlen($characters) - 1)];
    	}
	if(!empty($prefix))
	return $prefix.$randomString;
    	return $randomString;
}

function getMimeTypeForExtension($extn)
{
	$extn = strtolower($extn);

	errorlog_withlevel("getMimeTypeForExtension===>".$extn,3);

	switch($extn)
	{
		case 'png': return MIMETYPE_IMAGE_PNG;
		case 'jpg': return MIMETYPE_IMAGE_JPG;
		case 'jpeg': return MIMETYPE_IMAGE_JPG;
		case 'mp3': return MIMETYPE_AUDIO_MPEG;
		case 'm4a': return MIMETYPE_AUDIO_MP4;
		case 'csv': return MIMETYPE_TEXT_CSV;
		case 'mp4': return MIMETYPE_VIDEO_MP4;
		case 'smil': return MIMETYPE_MEDIA_SMIL;
		case 'xml': return MIMETYPE_XML;
		case 'mpd': return MIMETYPE_XML;
		case 'txt': return MIMETYPE_TEXT_PLAIN;
		default: return false;
	}
}

function getExtensionForMimeType($mimetype)
{
	switch($mimetype)
	{
		case MIMETYPE_IMAGE_PNG: return IMAGE_PNG_EXTN;
		case MIMETYPE_IMAGE_JPG: return IMAGE_JPEG_EXTN;
		case MIMETYPE_TEXT_CSV: return TEXT_CSV_EXTN;
		case MIMETYPE_TEXT_PLAIN: return TEXT_PLAIN_EXTN;
		case MIMETYPE_VIDEO_MP4: return VIDEO_MP4_EXTN;
		case MIMETYPE_AUDIO_MPEG:return AUDIO_MP3_EXTN;
		case MIMETYPE_AUDIO_MPEG3: return AUDIO_MP3_EXTN;
		case MIMETYPE_AUDIO_X_MPEG3: return AUDIO_MP3_EXTN;
		case MIMETYPE_AUDIO_MP3: return AUDIO_MP3_EXTN;
		case MIMETYPE_VIDEO_STREAM: return VIDEO_MP4_EXTN;
		default: return false;
	}
}

function cleanRedisData($data)
{
	foreach($data as $key=>$value)
	{
		if(empty($value))
		{
			$data[$key] = NULL;
		}
	}
	return $data;
}

function loadCSVDataWithKeys($csvfile,$stripempty,$linelength)
{
	$storedata = array();

	if (($handle = fopen($csvfile, "r")) !== FALSE)
	{
		$keys = fgetcsv($handle,$linelength);

		do
		{
			$values=fgetcsv($handle,$linelength);
			$data = array_combine($keys,$values);
			errorlog_withlevel(json_encode($data),3);
			//Remove empty lines
			if(!empty($data))
			{

				if($stripempty)
				{
					foreach($data as $key=>$value)
					{
						if(empty($value))
						{
							unset($data[$key]);
						}
					}
				}
				$ndata[] = $data;

			}
		}while(!feof($handle));


	}

	if(empty($ndata))
	{
		return false;
	}

	return $ndata;
}


function formatError($number)
{
	$error = 'Unknown Error';

	if(!empty($GLOBALS['errorcodes'][$number]))
	{
		$error = $GLOBALS['errorcodes'][$number];
	}

	errorlog_withlevel($number."===>".$error,1);

	return array("error"=>$number,'reason'=>$GLOBALS['errorcodes'][$number]);
}

function getLanguageCode($code,$full=FALSE)
{
	$code2array = array('ur','mr','bn','hi','en','gu','kn','ml','de','fr','pa','bh','ta','te','or','ko','ne','as','ks','sa','sd');
	$code3array = array('urd','mar','ben','hin','eng','kan','mal','deu','fra','pan','bih','tel','ori','kor','nep','asm','kas','san','snd');

	$languagearray = array('Urdu','Marathi','Bengali','Hindi','English','Gujarathi','Kannada','Malayalam','German','French','Panjabi','Bihari','Tamil','Telugu','Oriya','Korean','Nepali','Assamese','Kashmiri','Sanskrit','Sindhi');

	//Get index
	if(strlen($code)===2)
	{
		if(!($index = array_search($code,$code2array)))
		{
			return false;
		}

		if($full)
		{
			return $languagearray[$index];
		}

		return $code3array[$index];
	}

	//Get index
	if(strlen($code)===3)
	{
		if(!($index = array_search($code,$code3array)))
		{
			return false;
		}

		if($full)
		{
			return $languagearray[$index];
		}

		return $code2array[$index];
	}


}

function toMonthString($num)
{
	$months = array('JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE','JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER');

	if($num<1 or $num>12)
	{
		return false;
	}

	return $months[$num-1];
}

function getDBConnectorPhrase($query)
{
  	if(!stristr($query,'WHERE'))
	{
		return " WHERE ";
	}
	else
	{
		return " AND ";
	}
}

function uploadFilePOST($dest,$filename)
{
	errorlog_withlevel(json_encode($_FILES),3);
	if (!move_uploaded_file($_FILES[$filename]['tmp_name'], $dest))
	{
		errorlog_withlevel('Could not upload '.$filename,2);
		return false;
	}

	return true;

}

function uploadFilePUT($dest)
{

	/* PUT data comes in on the stdin stream */
	if(!($putdata = fopen("php://input", "r")))
	{
		errorlog_withlevel('Could not read Input data',1);
	}

	/* Open a file for writing */
	if(!($fp = fopen($dest, "w")))
	{
		errorlog_withlevel('Could not open output file: '.$dest,2);
	}

	/* Read the data 1 KB at a time  and write to the file */
	while ($data = fread($putdata, 1024))
  		fwrite($fp, $data);

	/* Close the streams */
	fclose($fp);
	fclose($putdata);

	return true;
}


function uploadFiletoS3($file,$type,$folder,$amazonfolder,$amazonbucket,$filename=NULL,$uploadToOrigin=false)
{
	$source = $folder.$file;
	$metadata = array('type' => $type);
	$extn = explode(".",$file);

	if(empty($filename))
	{
		$inputfile = $amazonfolder."/".$file;
	}
	else
	{
		$inputfile = $amazonfolder."/".$filename;
	}

	errorlog_withlevel("FIleNameAfter == ".$inputfile,3);

	if(!addFiletoS3($inputfile, $amazonbucket,$source,getMimeTypeForExtension(end($extn)),$metadata,$uploadToOrigin))
	{
		errorlog_withlevel('Uploading File to S3 Failed',1);
		return false;
	}

	return $file;
}

function uploadFiletoS3FromURL($file,$type,$folder,$amazonfolder,$amazonbucket,$filename=NULL)
{
	$source = $folder.$file;
	$metadata = array('type' => $type);
	$extn = explode(".",$source);

	if(empty($filename))
	{
		$inputfile = $amazonfolder."/".$file;
	}
	else
	{
		$inputfile = $amazonfolder."/".$filename;
	}

	errorlog_withlevel("FIleNameAfter == ".$inputfile,3);

	if(!addFiletoS3FromURL($inputfile, $amazonbucket,$source,getMimeTypeForExtension(end($extn)),$metadata))
	{
		errorlog_withlevel('Uploading File to S3 Failed',1);
		return false;
	}

	if($filename != NULL){
		$file = $filename;
	}

	return $file;
}

function checkValidEmail($email)
{
	errorlog_withlevel("checkValidEmail===>".$email,3);

	if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function getAudioInfo($src)
{
	$ffdata = array();
	$ffdata['ffmpeg.binaries'] = VIVE_FFMPEG_BASE_PATH."ffmpeg";
	$ffdata['ffprobe.binaries'] = VIVE_FFPROBE_BASE_PATH."ffprobe";

	if(empty(VIVE_FFPROBE_BASE_PATH))
	{
		$ffdata = NULL;
	}

	try
	{
		date_default_timezone_set(DEFAULT_TIME_ZONE);
		$ffprobe = FFMpeg\FFProbe::create($ffdata);
		$audiodata = $ffprobe->streams($src)->audios()->first()->all();
		hlog_errorlog_withlevel("getAudioInfo===>".json_encode($audiodata),VBOXLITE_TRANSCODING_LOG_AWS,false,true,2);
		return $audiodata;
	}
	catch(Exception $e)
	{
		hlog_errorlog_withlevel("getAudioInfo_Exception:".$e->getMessage(),VBOXLITE_TRANSCODING_LOG_AWS,false,true,1);
		return formatError(5075);
	}
}

function getVideoInfo($src)
{
	$ffdata = array();
	$ffdata['ffmpeg.binaries'] = VIVE_FFMPEG_BASE_PATH."ffmpeg";
	$ffdata['ffprobe.binaries'] = VIVE_FFPROBE_BASE_PATH."ffprobe";

	if(empty(VIVE_FFPROBE_BASE_PATH))
	{
		$ffdata = NULL;
	}

	try
	{
		date_default_timezone_set(DEFAULT_TIME_ZONE);
		$ffprobe = FFMpeg\FFProbe::create($ffdata);
		$videodata = $ffprobe->streams($src)->videos()->first()->all();
		hlog_errorlog_withlevel("getVideoInfo===>".json_encode($videodata),VBOXLITE_TRANSCODING_LOG_AWS,false,true,2);
		return $videodata;
	}
	catch(Exception $e)
	{
		hlog_errorlog_withlevel("getVideoInfo_Exception:".$e->getMessage(),VBOXLITE_TRANSCODING_LOG_AWS,false,true,1);
		return formatError(5075);
	}
}

function getProbeInfoViaCMD($src,$type)
{
	$returnflag = true;
	try
	{
		$url = str_replace( 'https://', 'http://', $src);

		$command = 'ffprobe -v quiet -print_format json -show_format -show_streams "'.$url.'"';

		$result = exec($command." 2>&1",$output,$return);

		$jsondata = "";

		foreach($output as $thisdata)
		{
			$jsondata = $jsondata.trim($thisdata);
		}

		error_log("Json Data===>".$jsondata);

		$jsondata = json_decode($jsondata,TRUE);
		error_log("Ffprobe Output===>".json_encode($jsondata));

		$stream = $jsondata['streams'];

		foreach($stream as $row){
			if(trim($row['codec_type']) == trim(strtolower($type))){
				hlog_errorlog_withlevel("getVideoInfo===>".json_encode($result),VBOXLITE_CONTENT_LOG,false,true,2);
				$returnflag = false;
				return $row;
			}
		}
		if($returnflag){
			return formatError(5077);
		}

	}
	catch(Exception $e)
	{
		hlog_errorlog_withlevel("getVideoInfo_Exception:".$e->getMessage(),VBOXLITE_TRANSCODING_LOG_AWS,false,true,1);
		return formatError(5075);
	}
}

function getTotalCount($query,$mysqlcon)
{
	hlog_errorlog_withlevel("getTotalCount===>".$query,"hlog",false,true,4);

	$totalcount = 0;

	if($result = $mysqlcon->query($query))
	{
		$totalcount = $result->num_rows;
		$result->close();
		hlog_errorlog_withlevel("getTotalCount===>".$totalcount,"hlog",false,true,4);
	}

	return $totalcount;
}

function getcssnjsversion()
{
	$timestamp = round(microtime(true));

	//$timestamp = VHITS_RELEASE_VERSION_NUMBER;

	//date_default_timezone_set(DEFAULT_TIME_ZONE);
	//$timestamp = strtotime("today");

	return $timestamp;
}

function randomNumber($length) {
    $result = '';

    for($i = 0; $i < $length; $i++) {
        $result .= mt_rand(0, 9);
    }

    return $result;
}

function processCurl($method,$url,$params,$smstoken=NULL)
{
	if($method==="GET")
	{
		$i=0;
		foreach($params as $key=>$value)
		{
			if($i==0)
			{
				$url.="?".$key."=".$value;
			}
			else
			{
				$url.="&".$key."=".$value;
			}
			$i++;
		}
	}

	try
	{
		$curl=curl_init($url);

		error_log("Curl Init URL===>".$url);
		error_log("Curl Init Method===>".$method);
		error_log("Curl Init Data===>".json_encode($params));

		if (!$curl)
		{
			throw new Exception('Invalid Request');
		}

		$headers = array();
		$headers[] = 'Cache-Control: no-cache';
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Data-Type: application/json';

		if(!empty($smstoken))
		{
			$headers[] = 'Authorization: key='.$smstoken;
		}

		curl_setopt($curl,CURLOPT_VERBOSE, FALSE);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl,CURLOPT_HEADER, TRUE);
		curl_setopt($curl,CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl,CURLOPT_CONNECTTIMEOUT_MS, VBOXLITE_CURL_CONNECTION_TIMEOUT);
		curl_setopt($curl,CURLOPT_TIMEOUT_MS, VBOXLITE_CURL_EXECUTION_TIMEOUT);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);

		switch($method)
		{
			case "GET":
			curl_setopt($curl,CURLOPT_HTTPGET,TRUE);
			break;

			case "POST":
			curl_setopt($curl,CURLOPT_POST,TRUE);
			curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($params));
			break;

			case "PUT":
			curl_setopt($curl,CURLOPT_CUSTOMREQUEST,"PUT");
			$params = json_encode($params);
			error_log("Curl Init PUT Data===>".$params);
			curl_setopt($curl,CURLOPT_POSTFIELDS,$params);
			break;

			case "DELETE":
			curl_setopt($curl,CURLOPT_CUSTOMREQUEST,"DELETE");
			curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($params));
			break;

			default:
			HTTPFailWithCode(405,VLIVE_HTTP_METHOD_NOT_ALLOWED);
			break;
		}

		$response = curl_exec($curl);

		$errNo = curl_errno($curl);

		$err = curl_error($curl);

		if($errNo)
		{
			error_log("CurlException===>".$errNo."===>".$err);
			throw new Exception($err);
		}

		$responsestatus = curl_getinfo($curl);

		//error_log("Curl Response Status===>".json_encode($responsestatus));

		$responsehttpcode = $responsestatus['http_code'];

		error_log("Curl Response HTTP Code===>".$responsehttpcode);

		$header_size = $responsestatus['header_size'];
		$responseheader = substr($response, 0, $header_size);
		$responsebody = substr($response, $header_size);

		error_log("Header Response===>".$responseheader);
		error_log("Body Response===>".$responsebody);

		if($bodydecoed = json_decode($responsebody,TRUE))
		{
			$responsebody = $bodydecoed;
		}
		else
		{
			$responsebody = urlStringToArray($responsebody);
		}

		curl_close($curl);

		switch($responsehttpcode)
		{
			case 200:
			return $responsebody;
			break;

			default:
			$httperror = reasonForCode($responsehttpcode);
			$httperror = substr($httperror, 13);
			return array("errorcode"=>$responsehttpcode,"reason"=>$httperror);
			break;
		}
	}
	catch (Exception $e)
	{
		$message = $e->getMessage();
		error_log("Curl Exception===>".$message);
		return array("errorcode"=>4003,"reason"=>$message);
	}
}

function urlStringToArray($url)
{
	error_log("urlStringToArray===>".$url);

	$result = array();

	$decryptValues = explode('&', $url);
	$dataSize=sizeof($decryptValues);

	for($i = 0; $i < $dataSize; $i++)
	{
		$information = explode('=',$decryptValues[$i]);

		if(!empty($information[1]))
		{
			$information[0] = ltrim($information[0],"?");
			$result[$information[0]] = $information[1];
		}
	}

	error_log("urlStringToArray===>".json_encode($result));

	return $result;
}
//compare JSONArray/Array without order @return true if same
function compareJSONArray($firstArr,$secondArr){
	if(!empty($firstArr) && !empty($secondArr)){
		if(!is_array($firstArr)){
			$firstResult = json_decode($firstArr,TRUE);
		}else{
			$firstResult = $firstArr;
		}
		if(!is_array($secondArr)){
			$secondResult = json_decode($secondArr,TRUE);
		}else{
			$secondResult = $secondArr;
		}
		if(!is_null($firstResult)){ sort($firstResult); }
		if(!is_null($secondResult)){ sort($secondResult); }
		if($firstResult == $secondResult){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
//from remote url get file size
function retrieve_remote_file_size_type($url,$token=NULL){
     $ch = curl_init($url);

     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
     curl_setopt($ch, CURLOPT_HEADER, TRUE);
     curl_setopt($ch, CURLOPT_NOBODY, TRUE);
     if(!empty($token)){
         curl_setopt($ch,CURLOPT_HTTPHEADER,array('Authorization: Bearer '.$token));
     }

     $data = curl_exec($ch);
     $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
	 $type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

     curl_close($ch);

	 $result =array("size"=>$size,"type"=>$type);
     return $result;
}
?>
