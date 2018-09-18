<?php

require_once "./config.php";

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
        for ($i=0; $i < count($allImages['photos']); $i++) {            
            $zipArchive->addFromString($i.".jpg", file_get_contents($allImages['photos'][$i]['images'][1]['source']));
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

/* Fetches Images from Multiple/All Albums and Create zip File*/

try {
    if (isset($_REQUEST['AlbumIDs'])) {
        $arrAlbumIDs = explode('_', $_REQUEST['AlbumIDs']);
        $zipFilesPath = "Success";
        $zip = new ZipArchive();
        if (file_exists("downloads/MultipleAlbums.zip")) {
            unlink("downloads/MultipleAlbums.zip");
        }
        $zipFile = "MultipleAlbums.zip";
        if ($zip->open('downloads/'.$zipFile, ZIPARCHIVE::CREATE) != true) {
            die("Couldn't generate zip file");
        }
        foreach ($arrAlbumIDs as $albumID) {
            $album_ID_Name = explode('-', $albumID);
            $zip->addEmptyDir($album_ID_Name[1]);
            $allImages = getAllAlbumImages($fb, $album_ID_Name[0]);
            for ($i=0; $i < count($allImages['photos']); $i++) {                
                $zip->addFromString(
                    $album_ID_Name[1]."/".$i.".jpg",
                    file_get_contents($allImages['photos'][$i]['images'][1]['source'])
                );
            }
        }
        $zipFilesPath .= "_downloads/".$zipFile;
        echo $zipFilesPath;
    }
} catch (Exception $e) {
    echo "NULL";
}

/* Fetches Images from Multiple/All Albums and Create zip File*/

/* Fetches Images from Single Album for Carousel*/

try {
    if (isset($_REQUEST['AlbumIDForSlider'])) {
        $allImages = getAllAlbumImages($fb, $_REQUEST['AlbumIDForSlider']);
        for ($i=0; $i < count($allImages['photos']); $i++) {
            if ($i == 0) {
                echo "<div class=\"carousel-item active\" style=\"border: 1px solid;\">
                                <div class=\"img\">
                                    <div class=\"single-slide-div\" style=\"border:1px solid;\">
                                        <img src=".$allImages['photos'][$i]['images'][1]['source']." class=\"carousel-img img-responsive\">
                                    </div>
                                </div>
                            </div>";
            } else {
                echo "<div class=\"carousel-item\" style=\"border: 1px solid;\">
                            <div class=\"img\">
                                <div class=\"single-slide-div\">
                                    <img src=".$allImages['photos'][$i]['images'][1]['source']." class=\"carousel-img img-responsive\">
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
