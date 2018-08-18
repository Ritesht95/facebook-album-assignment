<?php
require_once "./config.php";


function CreateAlbumZip($albumID, $accessToken, $fb) {
    $allImages = getAllAlbumImages($fb, $accessToken, $albumID);
    $zipArchive = new ZipArchive();
    $zipFile = $albumID.".zip";
    if ($zipArchive->open('downloads/'.$zipFile, ZIPARCHIVE::CREATE) != true) {
        die("Couldn't generate zip file");
    }
    for ($i=0; $i < count($allImages)-1; $i++) {
        $imageDetails = getImageDetails($fb, $accessToken, $allImages[$i]);
        $zipArchive->addFromString($i.".jpg", file_get_contents($imageDetails['images'][1]['source']));
    }
    $zipArchive->close();
    return $zipFile;
}

try {
    if (isset($_REQUEST['AlbumID']) && isset($_REQUEST['AT'])) {
        $zipFile = CreateAlbumZip($_REQUEST['AlbumID'], $_REQUEST['AT'], $fb);
        echo "Success_downloads/".$zipFile;
    } else {
        echo "NULL";
    }
} catch (Exception $e) {
    echo "NULL";
}

try {
    if (isset($_REQUEST['AlbumIDs']) && isset($_REQUEST['AT'])) {
        $arrAlbumIDs = explode('_', $_REQUEST['AlbumIDs']);
        $zipFilesPath = "Success";
        foreach ($arrAlbumIDs as $albumID) {
            $zipFile = CreateAlbumZip($albumID, $_REQUEST['AT'], $fb);
            $zipFilesPath .= "_downloads/".$zipFile;
        }
        echo $zipFilesPath;
    } else {
        echo "NULL";
    }
} catch (Exception $e) {
    echo "NULL";
}
