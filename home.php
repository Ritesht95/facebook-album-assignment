<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ"
        crossorigin="anonymous">
    <link rel="stylesheet" href="./css/custom.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ"
        crossorigin="anonymous">
</head>
<?php

        require_once "./config.php";
        require_once "./lib/googleDrive_Functions.php";

if (!isset($_SESSION['fb_access_token'])) {
    $accessToken = getAccessToken($helper);
    $_SESSION['fb_access_token'] = (string)$accessToken;
}
        $user = getUserData($fb, $_SESSION['fb_access_token']);
        $_SESSION['UserID'] = $user['id'];
        $_SESSION['Name'] = str_replace(" ", "", $user['name']);

?>

<body>
    <div class="divSelected" id="divSelectedButtons">
        <button id="btnDownloadSelected" class="btn btn-primary" data-toggle="modal" data-target="#loaderModal" onclick="makeMultipleAlbumZip();">
            Download Selected</button>
        <button id="btnMoveSelected" class="btn btn-primary" data-toggle="modal" data-target="#loaderModal" onclick="multipleUploadsToDrive();">
            Move Selected to Drive</button>
    </div>
    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="loaderModal" tabindex="-1" role="dialog" aria-labelledby="loaderModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row loader-outer" id="divLoaderOuter">
                        <div id="divLoader" class="loader"></div>
                        <h4 id="loaderText">Generating album's zip file</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" data-backdrop="static" data-keyboard="false" id="googleLoginModal" tabindex="-1" role="dialog" aria-labelledby="googleLoginModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row google-login-outer" id="divGoogleLogin">
                        <a href="googleLogin.php" class="btn btn-primary">Google Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-custom">
        <a class="navbar-brand text-info" href="#">rtCamp Facebook Assignment</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item text-dark">
                    <a href="#" class="nav-link">
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        Hello,
                        <?php echo $user['name']; ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <section id="album-grid">
            <h1 class="jumbotron">Your Albums
                <div class="clearfix"></div>
                <button id="btnDownloadAll" data-toggle="modal" data-target="#loaderModal" class="btn btn-primary" onclick="makeAllAlbumZip();">Download All Albums</button>
                <button id="btnBackupAll" class="btn btn-primary" onclick="allUploadsToDrive();">Save All to Drive</button>
            </h1>
            <div class="container-fluid">
                <div class="row">
                    <?php
                    for ($i=0; $i < count($user['albums']); $i++) {
                        $albumCover = getAlbumCover($fb, $user['albums'][$i]['id']);
                        $coverImage = getCoverImageDetails($fb, $albumCover);
                        if (isset($coverImage)) {
                            ?>
                    <div class="column">
                        <div class="column-inner">
                            <div class="content text-center content-custom" id="divContent<?php echo $user['albums'][$i]['id']; ?>">
                                <img src="<?php echo $coverImage['images'][3]['source']; ?>" style="width:100%;"
                                    alt="Album Cover">
                                <h3>
                                    <a style="cursor: pointer;" onclick="openCarousel
                                            ('<?php echo $user['albums'][$i]['id']; ?>');">
                                        <?php echo $user['albums'][$i]['name']; ?>
                                    </a>
                                </h3>
                                <div id="album-footer" class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <input type="checkbox" class="album-checkbox" name="chkAlbum<?php echo $user['albums'][$i]['id'].'-'.$user['albums'][$i]['name']; ?>"
                                            id="chkAlbum_<?php echo $user['albums'][$i]['id'].'-'.$user['albums'][$i]['name']; ?>"
                                            onchange="changeAlbumBG(this.id, this.checked);">
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <button class="btn btn-primary" onclick="uploadToDrive(
                                                '<?php echo $user['albums'][$i]['id']; ?>', 
                                                '<?php echo $user['albums'][$i]['name']; ?>'
                                            );">
                                            <i class="fa fa-cloud-upload-alt"></i>
                                        </button>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <button class="btn btn-primary" onclick="makeSingleAlbumZip('<?php echo $user['albums'][$i]['id']; ?>',
                                                    '<?php echo $user['albums'][$i]['name']; ?>');">
                                            <i class="fa fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </section>
    </div>
    <div class="clearfix">
        <br>
    </div>
    <footer id="footer" class="bg-dark mt-3">
        &nbsp; &copy; Ritesh Tailor
    </footer>

    <div class="row" id="divCarouselOuter">
        <section id="imgSlider">
            <div id="imgCarousel" class="carousel slide" data-ride="carousel">
                <div id="divCarouselInner" class="carousel-inner text-center carousel-custom-style" role="listbox">
                    <div id="divLoader" class="loader"></div>
                </div>
                <a class="carousel-control-prev" href="#imgCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#imgCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </section>
    </div>

    <script src="./js/carousel.js"></script>
    <script src="./js/moveToDrive.js"></script>
    <script src="./js/downloadAlbums.js"></script>

    <script>
        function changeAlbumBG(id, value) {
            var allCheckboxes = document.getElementsByClassName('album-checkbox');
            var selectedCount = 0;
            for (i = 0; i < allCheckboxes.length; i++) {
                if (allCheckboxes[i].checked) {
                    selectedCount += 1;
                }
            };
            if (selectedCount == 0) {
                document.getElementById('divSelectedButtons').style.display = "none";
            } else {
                document.getElementById('divSelectedButtons').style.display = "block";
            }
            if (value) {
                var element = document.getElementById('divContent' + id.split("_")[1].split("-")[0]);
                element.style.backgroundColor = "navy";
                element.style.color = "white";
                element.parentNode.style.borderColor = "#fff";
                element.parentNode.style.boxShadow = "0.2px 0.2px 0.5px 3px #fff";
            } else {
                var element = document.getElementById('divContent' + id.split("_")[1].split("-")[0]);
                element.style.backgroundColor = "white";
                element.style.color = "black";
                element.parentNode.style.borderColor = "#808080";
                element.parentNode.style.boxShadow = "0.2px 0.2px 0.5px 3px #808080";
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
</body>

</html>
