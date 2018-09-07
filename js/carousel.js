document.addEventListener('webkitfullscreenchange', exitHandler, false);
document.addEventListener('mozfullscreenchange', exitHandler, false);
document.addEventListener('fullscreenchange', exitHandler, false);
document.addEventListener('MSFullscreenChange', exitHandler, false);


function openCarousel(albumID) {
    document.getElementById('divCarouselOuter').style.display = "block";
    element = document.getElementById('divCarouselOuter');
    document.getElementById('divCarouselInner').innerHTML = "<div id=\"divLoader\" class=\"loader\"></div>";
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
        req.onreadystatechange = function () {
            if (req.readyState == 4) {
                if (req.status == 200) {
                    var resText = req.responseText;
                    if (resText != 'NULL') {
                        document.getElementById('divCarouselInner').innerHTML = resText;
                    } else {
                        document.getElementById('loaderText').innerHTML = "Something went wrong!<br> Please Try again."
                        $('#loaderModal').modal('toggle');
                        setTimeout(() => {
                            $('#loaderModal').modal('toggle');
                        }, 3000);
                    }
                } else {
                    document.getElementById('loaderText').innerHTML = "Something went wrong!<br> Please Try again."
                    $('#loaderModal').modal('toggle');
                    setTimeout(() => {
                        $('#loaderModal').modal('toggle');
                    }, 3000);
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