<?php
require_once "./config.php";

set_time_limit(300);

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

try {
    if (isset($_REQUEST['AlbumID'])) {
        $zipFile = CreateAlbumZip($_REQUEST['AlbumID'], $fb);
        echo "Success_downloads/".$zipFile;
    }
} catch (Exception $e) {
    echo "NULL";
}

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

if (isset($_REQUEST['AlbumIDForSlider'])) {
    $allImages = getAllAlbumImages($fb, $_REQUEST['AlbumIDForSlider']);
    for ($i=0; $i < count($allImages); $i++) {
        $imageDetails = getImageDetails($fb, $allImages[$i]);
        if ($i == 0) {
            echo "<div class=\"carousel-item active\">
                            <div class=\"img\">
                                <div class=\"single-slide-div\" style=\"border:1px solid;\">
                                    ".$imageDetails['name'].
                                    "<img src=".$imageDetails['images'][1]['source']." 
                                    style=\"height:100%;width:100%;object-fit:contain;\">
                                </div>
                            </div>
                        </div>";
        } else {
            echo "<div class=\"carousel-item\">
                        <div class=\"img\">
                            <div class=\"single-slide-div\">"
                                .$imageDetails['name'].
                                "<img src=".$imageDetails['images'][1]['source']." style=\"height: 100%; width: 100%; object-fit: contain;\">
                            </div>
                        </div>
                    </div>";
        }
    }
}
