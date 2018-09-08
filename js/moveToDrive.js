function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function uploadToDrive(albumID, albumName) {
    document.getElementById('divLoaderOuter').innerHTML = "<div id=\"divLoader\" class=\"loader\"></div><h4 id=\"loaderText\">Generating album's zip file</h4>";
    if (getCookie('credentials') == "") {
        $('#googleLoginModal').modal('toggle');
    } else {
        $('#loaderModal').modal('toggle');
        document.getElementById('loaderText').innerText = "Moving album to drive";
        var strURL = "googleDriveOperation.php?uploadAlbum=" + albumID + "&albumName=" + albumName + "&reqType=single";
        var req = new XMLHttpRequest();
        if (req) {
            req.onreadystatechange = function () {
                if (req.readyState == 4) {
                    if (req.status == 200) {
                        var resText = req.responseText;
                        if (resText == 'Success' || resText.search('Success') != -1) {
                            document.getElementById('divLoaderOuter').innerHTML =
                                "<span class=\"fa fa-3x fa-check load-complete-icon text-success\"></span><h4 id=\"loaderText\">Album moved to drive</h4>";
                            setTimeout(() => {
                                $('#loaderModal').modal('toggle');
                            }, 3000);
                        } else {
                            setTimeout(function () {
                                document.getElementById('divLoaderOuter').innerHTML =
                                    "<span class=\"fa fa-3x fa-times load-complete-icon text-danger\"></span><h4 id=\"loaderText\">Oops! Something went wrong.</h4>";
                            }, 3000);
                        }
                    } else {
                        document.getElementById('divLoaderOuter').innerHTML =
                            "<span class=\"fa fa-3x fa-times load-complete-icon text-danger\"></span><h4 id=\"loaderText\">Oops! Something went wrong.</h4>";
                        setTimeout(function () {
                            $('#loaderModal').modal('toggle');
                        }, 3000);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
    }
}

function multipleUploadsToDrive() {
    document.getElementById('divLoaderOuter').innerHTML = "<div id=\"divLoader\" class=\"loader\"></div><h4 id=\"loaderText\">Generating album's zip file</h4>";
    var allCheckboxes = document.getElementsByClassName('album-checkbox');
    var selectedAlbums = "";
    for (i = 0; i < allCheckboxes.length; i++) {
        if (allCheckboxes[i].checked) {
            selectedAlbums += allCheckboxes[i].id.split('_')[1] + "_";
        }
    }
    selectedAlbums = selectedAlbums.slice(0, -1);
    if (getCookie('credentials') == "") {
        $('#googleLoginModal').modal('toggle');
    } else {
        $('#loaderModal').modal('toggle');
        document.getElementById('loaderText').innerText = "Moving selected albums to drive";
        var strURL = "googleDriveOperation.php?uploadAlbums=" + selectedAlbums  + "&reqType=multiple";
        var req = new XMLHttpRequest();
        if (req) {
            req.onreadystatechange = function () {
                if (req.readyState == 4) {
                    if (req.status == 200) {
                        var resText = req.responseText;
                        if (resText == 'Success' || resText.search('Success') != -1) {
                            document.getElementById('divLoaderOuter').innerHTML =
                                "<span class=\"fa fa-3x fa-check load-complete-icon text-success\"></span><h4 id=\"loaderText\">Selected albums moved to drive</h4>";
                            setTimeout(() => {
                                $('#loaderModal').modal('toggle');
                            }, 3000);
                        } else {
                            setTimeout(function () {
                                document.getElementById('divLoaderOuter').innerHTML =
                                    "<span class=\"fa fa-3x fa-times load-complete-icon text-danger\"></span><h4 id=\"loaderText\">Oops! Something went wrong.</h4>";
                            }, 3000);
                        }
                    } else {
                        document.getElementById('divLoaderOuter').innerHTML =
                            "<span class=\"fa fa-3x fa-times load-complete-icon text-danger\"></span><h4 id=\"loaderText\">Oops! Something went wrong.</h4>";
                        setTimeout(function () {
                            $('#loaderModal').modal('toggle');
                        }, 3000);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
    }
}

function allUploadsToDrive() {
    document.getElementById('divLoaderOuter').innerHTML = "<div id=\"divLoader\" class=\"loader\"></div><h4 id=\"loaderText\">Generating album's zip file</h4>";
    var allCheckboxes = document.getElementsByClassName('album-checkbox');
    var selectedAlbums = "";
    for (i = 0; i < allCheckboxes.length; i++) {
        selectedAlbums += allCheckboxes[i].id.split('_')[1] + "_";
    }
    selectedAlbums = selectedAlbums.slice(0, -1);
    if (getCookie('credentials') == "") {
        $('#googleLoginModal').modal('toggle');
    } else {
        document.getElementById('loaderText').innerText = "Moving all albums to drive";
        $('#loaderModal').modal('toggle');
        var strURL = "googleDriveOperation.php?uploadAlbums=" + selectedAlbums  + "&reqType=multiple";
        var req = new XMLHttpRequest();
        if (req) {
            req.onreadystatechange = function () {
                if (req.readyState == 4) {
                    if (req.status == 200) {
                        var resText = req.responseText;
                        if (resText == 'Success' || resText.search('Success') != -1) {
                            document.getElementById('divLoaderOuter').innerHTML =
                                "<span class=\"fa fa-3x fa-check load-complete-icon text-success\"></span><h4 id=\"loaderText\">All albums moved to drive</h4>";
                            setTimeout(() => {
                                $('#loaderModal').modal('toggle');
                            }, 3000);
                        } else {
                            setTimeout(function () {
                                document.getElementById('divLoaderOuter').innerHTML =
                                    "<span class=\"fa fa-3x fa-times load-complete-icon text-danger\"></span><h4 id=\"loaderText\">Oops! Something went wrong.</h4>";
                            }, 3000);
                        }
                    } else {
                        document.getElementById('divLoaderOuter').innerHTML =
                            "<span class=\"fa fa-3x fa-times load-complete-icon text-danger\"></span><h4 id=\"loaderText\">Oops! Something went wrong.</h4>";
                        setTimeout(function () {
                            $('#loaderModal').modal('toggle');
                        }, 3000);
                    }
                }
            }
            req.open("GET", strURL, true);
            req.send(null);
        }
    }
}