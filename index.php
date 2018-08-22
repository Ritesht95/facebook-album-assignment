<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="custom.css"> -->
</head>

<?php

    require_once "./config.php";
    session_destroy();
    $permissions = ['email,user_photos']; // Optional permissions
    $loginUrl = $helper->getLoginUrl('https://localhost/rtCamp_Facebook_Assignment/home.php', $permissions);
?>

<body id="login-body">
    <div class="mt-5 container jumbotron">
        <div class=" col-lg-6 col-md-6 col-sm-10 col-xs-10">
            <h1>rtCamp Facebook Challenge</h1>
            <br>
            <?php /*if (!$loginFlag) {*/ ?>
            <a href="<?php echo htmlspecialchars($loginUrl); ?>" class="btn btn-primary col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <img class="login-button-img" src="img/facebook_icon.png" width="32" height="32">Login</a>
        </div>
    </div>
</body>

</html>