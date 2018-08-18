<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" 
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" 
        integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ"
        crossorigin="anonymous">
    <link rel="stylesheet" href="custom.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" 
        integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ"
        crossorigin="anonymous">
</head>
<?php

        require_once "./config.php";

        $accessToken = getAccessToken($helper);

        $user = getUserData($fb, $accessToken);
?>
<body>
    <button id="btnDownloadSelected" class="btn btn-primary" 
        data-toggle="modal" data-target="#loaderModal" onclick="makeMultipleAlbumZip('<?php echo $accessToken; ?>');">
            Download Selected</button> 
    <div class="modal fade" data-backdrop="static" 
        data-keyboard="false" id="loaderModal" tabindex="-1" role="dialog" aria-labelledby="loaderModalCenterTitle"
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
            <h1 class="jumbotron">Your Albums</h1>
            <div class="container-fluid">
                <div class="row">
                    <?php
                    for ($i=0; $i < count($user['albums']); $i++) {
                        $albumCover = getAlbumCover($fb, $accessToken, $user['albums'][$i]['id']);
                        $coverImage = getCoverImageDetails($fb, $accessToken, $albumCover); ?>
                    <div class="column">
                        <div class="column-inner">
                            <div class="content text-center content-custom" 
                                id="divContent<?php echo $user['albums'][$i]['id']; ?>">
                                <img src="<?php echo $coverImage['images'][3]['source']; ?>" style="width:100%;"
                                    alt="Album Cover">
                                <h3>
                                    <?php echo $user['albums'][$i]['name']; ?>
                                </h3>
                                <div id="album-footer" class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <input type="checkbox" class="album-checkbox" 
                                            name="chkAlbum<?php echo $user['albums'][$i]['id']; ?>"
                                            id="chkAlbum_<?php echo $user['albums'][$i]['id']; ?>"
                                            onchange="changeAlbumBG(this.id, this.checked);">
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <button class="btn btn-primary">
                                            <i class="fa fa-cloud-upload-alt"></i>
                                        </button>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <button data-toggle="modal" data-target="#loaderModal" 
                                            class="btn btn-primary" 
                                            onclick="makeSingleAlbumZip('<?php echo $user['albums'][$i]['id']; ?>', 
                                                                        '<?php echo $accessToken; ?>');">
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
    <div class="modal fade" id="albumDetailsModal" tabindex="-1" role="dialog" 
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                            <img src="img/dark_blue_bg.jpg.jpg" alt="User's Image" 
                                class="rounded-circle" height="100" width="250">
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

        function makeSingleAlbumZip(albumID, accessToken) {
            var strURL = "downloadAlbum.php?AlbumID=" + albumID + "&AT=" + accessToken;
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

        function makeMultipleAlbumZip(accessToken) {

            var allCheckboxes = document.getElementsByClassName('album-checkbox');
            var selectedAlbums = "";
            for (i = 0; i < allCheckboxes.length; i++) {
                if (allCheckboxes[i].checked) {
                    selectedAlbums += allCheckboxes[i].id.split('_')[1]+"_";
                }
            }
            selectedAlbums = selectedAlbums.slice(0,-1);
            alert(selectedAlbums);
            var strURL = "downloadAlbum.php?AlbumIDs=" + selectedAlbums + "&AT=" + accessToken;
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
                                    window.location = resArray[i];
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
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" 
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" 
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" 
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
</body>

</html>