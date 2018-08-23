<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Google Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ"
        crossorigin="anonymous">
    <link rel="stylesheet" href="custom.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ"
        crossorigin="anonymous">
</head>
<body>
    <?php
        session_start();
        require_once "./lib/googleDrive_Functions.php";

        global $CLIENT_ID, $CLIENT_SECRET, $REDIRECT_URI;
        $client = new Google_Client();
        $client->setClientId($CLIENT_ID);
        $client->setClientSecret($CLIENT_SECRET);
        $client->setRedirectUri($REDIRECT_URI);
        $client->setScopes('email');

        $authUrl = $client->createAuthUrl();

    if (isset($_REQUEST['code'])) {
        getCredentials($_REQUEST['code'], $authUrl);
        header("Location: home.php");
    } else {
        $googleAuthUrl = getAuthorizationUrl("", "");
        echo "<script>window.location = ".$googleAuthUrl."</script>";
        ?>
        <!-- <div class="mt-5 container jumbotron">
        <div class=" col-lg-6 col-md-6 col-sm-10 col-xs-10">
            <h1>Google Login (To upload albums on google drive)</h1>
            <br>
                <a id="btnGoogleLogin" class="btn btn-primary col-lg-8 col-md-8 col-sm-8 col-xs-8" 
                    href=''>Google Login</a>
        </div>
    </div> -->    
        <?php
    }
    ?>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
</body>
</html>

        

if (!isset($_COOKIE['credentials'])) {
?>
<script>
   gLoginFlag = 1;
</script>
<?php
    
    
}
?>