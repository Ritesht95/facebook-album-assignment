<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="custom.css"> -->
</head>

<?php
    define('APP_ID', '228597831185938');
    define('APP_SECRET', '39bc2d3b5631fa68cd79f192162d9856');
    define('REDIRECT_URL', 'https://localhost/rtCamp_Facebook_Assignment/');

    //INCLUDING LIBRARIES

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

 //STARTING SESSION
 session_start();

 FacebookSession::setDefaultApplication(APP_ID, APP_SECRET);

 $helper = new FacebookRedirectLoginHelper(REDIRECT_URL);

 $sess = $helper->getSessionFromRedirect();

if (isset($sess)) {
    $request  = new FacebookRequest($sess, 'GET', '/me');
    $response = $request->execute();
    $graph = $response->getGraphObject(GraphUser::className());
    $name = $graph->getName();
    $loginFlag = 1;
} else {
    $loginFlag = 0;
}
?>

<body id="login-body">
    <div class="mt-5 container jumbotron">
        <div class=" col-lg-6 col-md-6 col-sm-10 col-xs-10">
            <h1>rtCamp Facebook Challenge</h1>
            <br>
            <?php if (!$loginFlag) { ?>
            <a href="<?php echo $helper->getLoginUrl(); ?>" class="btn btn-primary col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <img class="login-button-img" src="img/facebook_icon.png" width="32" height="32">Login</a>
            <?php } else { if(isset($name)) echo $name; }  ?>
        </div>
    </div>
</body>

</html>