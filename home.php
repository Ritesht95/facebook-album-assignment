<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ"
        crossorigin="anonymous">
    <link rel="stylesheet" href="custom.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ"
        crossorigin="anonymous">
</head>
<?php

        require_once "./config.php";

        // $_SESSION['FBRLH_state']=$_GET['state'];

if (!isset($_SESSION['fb_access_token'])) {
    $accessToken = getAccessToken($helper);
    $_SESSION['fb_access_token'] = (string)$accessToken;
}
        //  echo ":".$_SESSION['fb_access_token'].":";
        $user = getUserData($fb, $_SESSION['fb_access_token']);
?>

<body>
    <button id="btnDownloadSelected" class="btn btn-primary" data-toggle="modal" data-target="#loaderModal" onclick="makeMultipleAlbumZip();">
        Download Selected</button>
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
                        <a class="dropdown-item" href="#">Action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="#">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <section id="album-grid">
            <h1 class="jumbotron">Your Albums
                <div class="clearfix"></div>
                <button id="btnDownloadAll" data-toggle="modal" data-target="#loaderModal" 
                    class="btn btn-primary" onclick="makeAllAlbumZip();">Download All Albums</button>
                <button id="btnBackupAll" class="btn btn-primary">Save All to Drive</button>
            </h1>
            <div class="container-fluid">
                <div class="row">
                    <?php
                    for ($i=0; $i < count($user['albums']); $i++) {
                        $albumCover = getAlbumCover($fb, $user['albums'][$i]['id']);
                        $coverImage = getCoverImageDetails($fb, $albumCover); ?>
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
                                        <input type="checkbox" class="album-checkbox" name="chkAlbum<?php echo $user['albums'][$i]['id']; ?>"
                                            id="chkAlbum_<?php echo $user['albums'][$i]['id']; ?>"
                                            onchange="changeAlbumBG(this.id, this.checked);">
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <button class="btn btn-primary">
                                            <i class="fa fa-cloud-upload-alt"></i>
                                        </button>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <button data-toggle="modal" data-target="#loaderModal" class="btn btn-primary" onclick="makeSingleAlbumZip('<?php echo $user['albums'][$i]['id']; ?>');">
                                            <i class="fa fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <?php
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

    <!-- Modal -->
    <div class="modal fade" id="albumDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">album</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="img/dark_blue_bg.jpg.jpg" alt="User's Image" class="rounded-circle" height="100" width="250">
                            <div class="col-md-9">
                                <h1>Ritesh Tailor</h1>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="divCarouselOuter">
        <section id="imgSlider">
            <div id="imgCarousel" class="carousel slide" data-ride="carousel">
                <div id="divCarouselInner" class="carousel-inner text-center carousel-custom-style" role="listbox">
                    
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

    <script>
        document.addEventListener('webkitfullscreenchange', exitHandler, false);
        document.addEventListener('mozfullscreenchange', exitHandler, false);
        document.addEventListener('fullscreenchange', exitHandler, false);
        document.addEventListener('MSFullscreenChange', exitHandler, false);

        function changeAlbumBG(id, value) {
            var allCheckboxes = document.getElementsByClassName('album-checkbox');
            var selectedCount = 0;
            for (i = 0; i < allCheckboxes.length; i++) {
                if (allCheckboxes[i].checked) {
                    selectedCount += 1;
                }
            };
            if (selectedCount == 0) {
                document.getElementById('btnDownloadSelected').style.display = "none";
            } else {
                document.getElementById('btnDownloadSelected').style.display = "block";
            }
            if (value) {
                var element = document.getElementById('divContent' + id.split("_")[1]);
                element.style.backgroundColor = "navy";
                element.style.color = "white";
                element.parentNode.style.borderColor = "#fff";
                element.parentNode.style.boxShadow = "0.2px 0.2px 0.5px 3px #fff";
            } else {
                var element = document.getElementById('divContent' + id.split("_")[1]);
                element.style.backgroundColor = "white";
                element.style.color = "black";
                element.parentNode.style.borderColor = "#808080";
                element.parentNode.style.boxShadow = "0.2px 0.2px 0.5px 3px #808080";
            }
        }

        function makeSingleAlbumZip(albumID) {
            var strURL = "downloadAlbum.php?AlbumID=" + albumID;
            var req = new XMLHttpRequest();
            if (req) {
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        // only if "OK"
                        if (req.status == 200) {
                            var resText = req.responseText;
                            var resArray = resText.split("_");
                            if (resArray[0] === 'Success') {
                                window.location = resArray[1];
                                $('#loaderModal').modal('toggle');
                            }
                        } else {
                            alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                        }
                    }
                }
                req.open("GET", strURL, true);
                req.send(null);
            }
        }

        function makeMultipleAlbumZip() {

            var allCheckboxes = document.getElementsByClassName('album-checkbox');
            var selectedAlbums = "";
            for (i = 0; i < allCheckboxes.length; i++) {
                if (allCheckboxes[i].checked) {
                    selectedAlbums += allCheckboxes[i].id.split('_')[1] + "_";
                }
            }
            selectedAlbums = selectedAlbums.slice(0, -1);
            var strURL = "downloadAlbum.php?AlbumIDs=" + selectedAlbums;
            var req = new XMLHttpRequest();
            if (req) {
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        // only if "OK"
                        if (req.status == 200) {
                            var resText = req.responseText;
                            var resArray = resText.split("_");
                            if (resArray[0] === 'Success') {
                                for (i = 1; i < resArray.length; i++) {
                                    window.open(resArray[i], '_blank');
                                }
                                $('#loaderModal').modal('toggle');
                            }
                        } else {
                            alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                        }
                    }
                }
                req.open("GET", strURL, true);
                req.send(null);
            }
        }

        function makeAllAlbumZip() {

            var allCheckboxes = document.getElementsByClassName('album-checkbox');
            var selectedAlbums = "";
            for (i = 0; i < allCheckboxes.length; i++) {
                if (allCheckboxes[i]) {
                    selectedAlbums += allCheckboxes[i].id.split('_')[1] + "_";
                }
            }
            selectedAlbums = selectedAlbums.slice(0, -1);
            var strURL = "downloadAlbum.php?AlbumIDs=" + selectedAlbums;
            var req = new XMLHttpRequest();
            if (req) {
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        // only if "OK"
                        if (req.status == 200) {
                            var resText = req.responseText;
                            var resArray = resText.split("_");
                            if (resArray[0] === 'Success') {
                                for (i = 1; i < resArray.length; i++) {
                                    window.open(resArray[i], '_blank');
                                }
                                $('#loaderModal').modal('toggle');
                            }
                        } else {
                            alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                        }
                    }
                }
                req.open("GET", strURL, true);
                req.send(null);
            }
        }

        function openCarousel(albumID) {
            $('#loaderModal').modal('toggle');
            document.getElementById('divCarouselOuter').style.display = "block";
            element = document.getElementById('imgCarousel');
            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.mozRequestFullScreen) {
                element.mozRequestFullScreen();
            } else if (element.webkitRequestFullscreen) {
                element.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            } else if (element.msRequestFullscreen) {
                element.msRequestFullscreen();
            }
            var strURL = "downloadAlbum.php?AlbumIDForSlider=" + albumID;
            var req = new XMLHttpRequest();
            if (req) {
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        if (req.status == 200) {
                            var resText = req.responseText;
                            if (resText != 'NULL') {
                                $('#loaderModal').modal('toggle');
                                document.getElementById('divCarouselInner').innerHTML = resText;
                            }
                        } else {
                            alert("There was a problem while using XMLHTTP:\n" + req.statusText);
                        }
                    }
                }
                req.open("GET", strURL, true);
                req.send(null);
            }
        }

            function exitHandler() {
                if (!(document.fullscreenElement ||
                        document.webkitFullscreenElement ||
                        document.mozFullScreenElement ||
                        document.msFullscreenElement)) {
                    document.getElementById('divCarouselOuter').style.display = "none";
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