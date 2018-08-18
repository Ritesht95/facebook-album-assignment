<?php


require_once('lib/Facebook/FacebookSession.php');
 require_once('lib/Facebook/FacebookRequest.php');
 require_once('lib/Facebook/FacebookResponse.php');
 require_once('lib/Facebook/FacebookSDKException.php');
 require_once('lib/Facebook/FacebookRequestException.php');
 require_once('lib/Facebook/FacebookRedirectLoginHelper.php');
 require_once('lib/Facebook/FacebookAuthorizationException.php');
 require_once('lib/Facebook/FacebookAuthorizationException.php');
 require_once('lib/Facebook/GraphObject.php');
 require_once('lib/Facebook/GraphUser.php');
 require_once('lib/Facebook/GraphSessionInfo.php');
 require_once('lib/Facebook/Entities/AccessToken.php');
 require_once('lib/Facebook/HttpClients/FacebookCurl.php');
 require_once('lib/Facebook/HttpClients/FacebookHttpable.php');
 require_once('lib/Facebook/HttpClients/FacebookCurlHttpClient.php');

 //USING NAMESPACES
 use Facebook\FacebookSession;
 use Facebook\FacebookRedirectLoginHelper;
 use Facebook\FacebookRequest;
 use Facebook\FacebookResponse;
 use Facebook\FacebookSDKException;
 use Facebook\FacebookRequestException;
 use Facebook\FacebookAuthorizationException;
 use Facebook\GraphObject;
 use Facebook\GraphUser;
 use Facebook\GraphSessionInfo;
 use Facebook\HttpClients\FacebookHttpable;
 use Facebook\HttpClients\FacebookCurlHttpClient;
 use Facebook\HttpClients\FacebookCurl;

session_start();

// echo $_SESSION['facebook_access_token'];
// exit;

if (!isset($_SESSION['facebook_access_token'])) {
    header("Location: index.php");
    exit;
}

$token= $_SESSION['facebook_access_token'];

Facebook\FacebookSession::setDefaultApplication(
    Config::get('api/facebook/app_id'),
    Config::get('api/facebook/app_secret')
);

$fb = new Facebook\FacebookRedirectLoginHelper(Config::get('api/facebook/url'));

if (Session::exists(Config::get('session/facebook'))) {
    $session = new Facebook\FacebookSession(Session::get(Config::get('session/facebook')));
} else {
    $session = $fb->getSessionFromRedirect();
}

try {
    // Returns a `Facebook\FacebookResponse` object
    $response= $session->get('/me?fields=id,name,first_name,last_name,gender,link,birthday,location,picture', $token);
} catch (Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

$user= $response->getGraphUser();

$albums = $fb->get('/me/albums', $token);

$action = ( isset($_REQUEST['action']) ) ? $_REQUEST['action'] : null;

$album_id = '';
if ($action=='viewalbum') {
    $album_id = $_REQUEST['album_id'];
    $photos = $fb->get("/{$album_id}/photos", $token);
}
