function makeSingleAlbumZip(albumID, albumName) {
    document.getElementById('divLoaderOuter').innerHTML = "<div id=\"divLoader\" class=\"loader\"></div><h4 id=\"loaderText\">Generating album's zip file</h4>";
    $('#loaderModal').modal('toggle');
    var strURL = "downloadAlbum.php?AlbumID=" + albumID + "&AlbumName=" + albumName;
    var req = new XMLHttpRequest();
    if (req) {
        req.onreadystatechange = function() {
            if (req.readyState == 4) {
                // only if "OK"
                if (req.status == 200) {
                    var resText = req.responseText;
                    var resArray = resText.split("_");
                    if (resArray[0] === 'Success' || resArray[0].search('Success') != -1) {
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
    document.getElementById('divLoaderOuter').innerHTML = "<div id=\"divLoader\" class=\"loader\"></div><h4 id=\"loaderText\">Generating album's zip file</h4>";
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
                    if (resArray[0] === 'Success' || resArray[0].search('Success') != -1) {
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
    document.getElementById('divLoaderOuter').innerHTML = "<div id=\"divLoader\" class=\"loader\"></div><h4 id=\"loaderText\">Generating album's zip file</h4>";
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
                    if (resArray[0] === 'Success' || resArray[0].search('Success') != -1) {
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