<?php

session_start();
require_once './lib/vendor/autoload.php';
require_once './functions.php';

$fb = new Facebook\Facebook(
    [
    'app_id' => '228597831185938', // Replace {app-id} with your app id
    'app_secret' => '39bc2d3b5631fa68cd79f192162d9856',
    'default_graph_version' => 'v2.2',
    ]
);

// $fb = new Facebook\Facebook([
//   'app_id' => '385601321972144', // Replace {app-id} with your app id
//   'app_secret' => '8b539b7a46ba509712859320c8a0705c',
//   'default_graph_version' => 'v2.2',
// ]);

  

$helper = $fb->getRedirectLoginHelper();

if (isset($_GET['state'])) {
    if (!isset($_SESSION['state'])) {
        $_SESSION['fbState'] = $_GET['state'];
        $helper->getPersistentDataHandler()->set('state', $_SESSION['fbState']);
    } else {
        $helper->getPersistentDataHandler()->set('state', $_SESSION['fbState']);
    }
}
