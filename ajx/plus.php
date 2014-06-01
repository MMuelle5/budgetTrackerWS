<?php
session_start();
/************************************************
 Check CSRF attack
*************************************************/
if (isset($_POST['state']) && isset($_SESSION['state'])) {
	if (!($_POST['state'] == $_SESSION['state'])) {
		header($_SERVER['SERVER_PROTOCOL'] . ' Unauthorized' , true, 401);
		exit;
	}
} else {
	header($_SERVER['SERVER_PROTOCOL'] . ' Unauthorized' , true, 401);
	exit;
}
include_once '../config/config.php';
// 
// $gPlusWhenLogout = '	<div id="gSignInWrapper">
							// <div id="customBtn">
								// <a onclick="gapi.auth.signIn({\'clientid\' : \'' . $_SESSION['clientid'] . '\',\'cookiepolicy\' : \'single_host_origin\',
// \'callback\' : \'signinCallback\',\'requestvisibleactions\' : \'http://schemas.google.com/AddActivity\',\'immediate\': true, \'scope\' : \'https://www.googleapis.com/auth/plus.login email\'}); $(\'#customBtn\').hide();">+Sign In</a>
							// </div>
						// </div>';


	$gPlusWhenLogout = '	<div id="gSignInWrapper">
							<div id="customBtn">
								<a onclick="gapi.auth.signIn({\'clientid\' : \'' . $_SESSION['clientid'] . '\',\'cookiepolicy\' : \'single_host_origin\',
\'callback\' : \'signinCallback\',\'requestvisibleactions\' : \'http://schemas.google.com/AddActivity\',\'immediate\': true, \'scope\' : \'https://www.googleapis.com/auth/plus.login\'}); $(\'#customBtn\').hide();">+Sign In</a>
							</div>
						</div>';
/************************************************
 Handles logout
*************************************************/

if (isset($_REQUEST['logout'])) {
	unset($_SESSION['access_token']);
	$_SESSION['logout'] = 1;
	echo $gPlusWhenLogout;
	exit;
}

set_include_path("../libs/google-api/src/" . PATH_SEPARATOR . get_include_path());

require_once 'Google/Client.php';
require_once 'Google/Service.php';
require_once 'Google/Service/Plus.php';
  
/************************************************
  Make an API request on behalf of a user. In
  this case we need to have a valid OAuth 2.0
  token for the user, so we need to send them
  through a login flow. To do this we need some
  information from our API console project.
 ************************************************/

$client = new Google_Client();
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope("https://www.googleapis.com/auth/plus.login");
$client->addScope("email");

/************************************************
 Handles revoke
*************************************************/

if (isset($_REQUEST['revoke'])) {
	$_SESSION['logout'] = 1;
  	$token = json_decode($_SESSION['access_token'])->access_token;
  	$discon = $client->revokeToken($token); 
	unset($_SESSION['access_token']);
	echo $gPlusWhenLogout;
	exit;
}

/************************************************
 If asked to store the token, get a token and 
 saveit to the session.
 
************************************************/
if (isset($_REQUEST['storeToken'])) {
	
	/************************************************
	 If we have a code back from the OAuth 2.0 flow,
	we need to exchange that with the authenticate()
	function. We store the resultant access token
	bundle in the session.
	************************************************/
	if (isset($_POST['code'])) {
		$client->authenticate($_POST['code']);
		$_SESSION['access_token'] = $client->getAccessToken();
		unset($_SESSION['logout']);
	}
}

/************************************************
  If we have an access token, we can make
  requests, else we generate an error
 ************************************************/
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
  if ($_SESSION['access_token']) {
  	$client->setAccessToken($_SESSION['access_token']);
  	$PlusService = new Google_Service_Plus($client);
  	$me = new Google_Service_Plus_Person();
  	$me = $PlusService->people->get('me');
  	$user_id 			= $me->id;
  	$_SESSION['token'] 	= $client->getAccessToken();
  	$_SESSION['id'] = $user_id;  	
  	echo '<div id="gPlusNav">
			<a onclick="gvnSignOut(); return false;" href="#">Disconnect</a>
		</div>';
  } else {
  		header("HTTP/1.1 401 Bad token");
  	exit;
  }
} else {
  header("HTTP/1.1 401 Bad token");
}
