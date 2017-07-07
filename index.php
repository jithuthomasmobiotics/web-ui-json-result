<?php
/*********************************************************
   Author:      Niyas T
   Copyright:   Mobiotics IT Solution Private Limited
   Version:     1.0
   Date:        1-Oct-2015

   FileName:    login.php
   Description: VBOXLITE Vendor Account Login
**********************************************************/
header("Content-Type:application/json");
error_reporting(E_ALL);


require_once('helpers.php');
require_once('utilities.php');


//First check what is the method
if(!isset($_SERVER['REQUEST_METHOD']) || !isset($_SERVER['REQUEST_URI']))
{

        HTTPFailWithCode(400,'HTTP Method or request URI is not set');
}

$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];

if($method!='GET')
{
        HTTPFailWithCode(405,'HTTP Method not allowed');

}

$parts = parse_url($request);
$path_parts = pathinfo($parts['path']);
$id = $path_parts['filename'];
if(empty($id){
   echo "empty";
}
echo $id;

$result = getResponseResult($_GET);


print json_encode($result);

exit(0);
?>
