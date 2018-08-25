<?php
    require_once "./lib/googleDrive_Functions.php";
    session_start();

        global $CLIENT_ID, $CLIENT_SECRET, $REDIRECT_URI;
        $client = new Google_Client();
        $client->setClientId($CLIENT_ID);
        $client->setClientSecret($CLIENT_SECRET);
        $client->setRedirectUri($REDIRECT_URI);
        $client->setScopes('email');
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $authUrl = $client->createAuthUrl();

    
    if (isset($_GET['code'])) {
        /* If gets the code in query string, then get Credetials and store them in Cookies */ 
        getCredentials($_GET['code'], $authUrl);
        echo "<script>window.location = 'home.php'</script>";
    } else { 
        /* Else redirect to auth url to authorize user's google account */
        $googleAuthUrl = getAuthorizationUrl("", "");
        echo "<script>window.location = '".$googleAuthUrl."'</script>";
    }
    
