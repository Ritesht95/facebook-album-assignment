<?php
require_once './lib/vendor/autoload.php';
require_once './functions.php';

session_start();

/* Initializing Facebook Object to call APIs  */

$fb = new Facebook\Facebook(
    [
    'app_id' => '228597831185938', // Replace {app-id} with your app id
    'app_secret' => '39bc2d3b5631fa68cd79f192162d9856',
    'default_graph_version' => 'v2.2',
    ]
);

$helper = $fb->getRedirectLoginHelper();


    /*
    * Checks for state param in query string
    * if it gets then set the session(if not set before)
    */

if (isset($_GET['state'])) {
    if (!isset($_SESSION['state'])) {
        $_SESSION['fbState'] = $_GET['state'];
        $helper->getPersistentDataHandler()->set('state', $_SESSION['fbState']);
    } else {
        $helper->getPersistentDataHandler()->set('state', $_SESSION['fbState']);
    }
}
