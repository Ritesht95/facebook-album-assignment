<?php
require_once "./config.php";

set_time_limit(300);

/* Funtion that Create zip of an Album with Images */

function CreateAlbumZip($albumID, $fb)
{
    try {
        $allImages = getAllAlbumImages($fb, $albumID);
        $zipArchive = new ZipArchive();
        $zipFile = $albumID.".zip";
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
    if (isset($_REQUEST['AlbumID'])) {
        $zipFile = CreateAlbumZip($_REQUEST['AlbumID'], $fb);
        echo "Success_downloads/".$zipFile;
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
            $zipFile = CreateAlbumZip($albumID, $fb);
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
