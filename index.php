<?php require_once "./config.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content="utf-8" http-equiv="encoding">
    <title>Facebook Albums</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" 
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
    <link rel="stylesheet" type='text/css' href="css/custom.css" />
</head>
<body id="login-body">
    <?php
        $permissions = ['email,user_photos']; // Optional permissions
        $loginUrl = $helper->getLoginUrl('https://localhost/rtCamp_Facebook_Assignment/home.php', $permissions);
        // $loginUrl = $helper->getLoginUrl('https://fbalbumrtcamp.000webhostapp.com/home.php', $permissions);
    ?>
    <div class="mt-5 container jumbotron">
        <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1>rtCamp<br><strong> Facebook Challenge<strong></h1>
            <br>
            <a href="<?php echo htmlspecialchars($loginUrl); ?>" class="btn btn-primary col-lg-8 col-md-8 col-sm-8 col-xs-8">
                <img class="login-button-img" src="img/facebook_icon.png" width="32" height="32">Login</a>
        </div>
    </div>
</body>
</html>