<?php

require_once "./config.php";
ini_set('max_execution_time', 300);//for 300 seconds
/* Funtion that Create zip of an Album with Images */

function CreateAlbumZip($albumID, $fb, $albumName = "")
{
    try {
        $allImages = getAllAlbumImages($fb, $albumID);
        $zipArchive = new ZipArchive();
        if ($albumName != "") {
            $zipFile = $albumName.".zip";
        } else {
            $zipFile = $albumID.".zip";
        }
        
        if (!file_exists("downloads")) {
            mkdir("downloads");
        }
        if ($zipArchive->open('downloads/'.$zipFile, ZIPARCHIVE::CREATE) != true) {
            die("Couldn't generate zip file");
        }
        for ($i=0; $i < count($allImages); $i++) {
            $imageDetails = getImageDetails($fb, $allImages[$i]);
            $zipArchive->addFromString($i.".jpg", file_get_contents($imageDetails['images'][1]['source']));
        }
        $zipArchive->close();
        return $zipFile;
    } catch (Exception $e) {
        return "NULL";
    }
}

/* Funtion that Create zip of an Album with Images */

/* Fetches Images of single Album*/

try {
    if (isset($_REQUEST['AlbumID']) && isset($_REQUEST['AlbumName'])) {
        $zipFile = CreateAlbumZip($_REQUEST['AlbumID'], $fb, $_REQUEST['AlbumName']);
        if ($zipFile != "NULL") {
            echo "Success_downloads/".$zipFile;
        } else {
            echo "NULL";
        }
    }
} catch (Exception $e) {
    echo "NULL";
}

/* Fetches Images of single Album*/

/* Fetches Images from Multiple Albums*/

try {
    if (isset($_REQUEST['AlbumIDs'])) {
        $arrAlbumIDs = explode('_', $_REQUEST['AlbumIDs']);
        $zipFilesPath = "Success";
        foreach ($arrAlbumIDs as $albumID) {
            $album_ID_Name = explode('-', $albumID);
            $zipFile = CreateAlbumZip($album_ID_Name[0], $fb, $album_ID_Name[1]);
            if ($zipFile == "NULL") {
                continue;
            }
            $zipFilesPath .= "_downloads/".$zipFile;
        }
        echo $zipFilesPath;
    }
} catch (Exception $e) {
    echo "NULL";
}

/* Fetches Images from Multiple Albums*/

/* Fetches Images from Single Album for Carousel*/

try {
    if (isset($_REQUEST['AlbumIDForSlider'])) {
        $allImages = getAllAlbumImages($fb, $_REQUEST['AlbumIDForSlider']);
        for ($i=0; $i < count($allImages); $i++) {
            $imageDetails = getImageDetails($fb, $allImages[$i]);
            if ($i == 0) {
                echo "<div class=\"carousel-item active\" style=\"border: 1px solid;\">
                                <div class=\"img\">
                                    <div class=\"single-slide-div\" style=\"border:1px solid;\">
                                        <img src=".$imageDetails['images'][1]['source']." class=\"carousel-img img-responsive\">
                                    </div>
                                </div>
                            </div>";
            } else {
                echo "<div class=\"carousel-item\" style=\"border: 1px solid;\">
                            <div class=\"img\">
                                <div class=\"single-slide-div\">
                                    <img src=".$imageDetails['images'][1]['source']." class=\"carousel-img img-responsive\">
                                </div>
                            </div>
                        </div>";
            }
        }
    }
} catch (Exception $e) {
    echo "<center><h1>Sorry, Couldn't fetch Images!</h1></center>";
}

/* Fetches Images from Single Album for Carousel*/
