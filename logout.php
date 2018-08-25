<?php
    session_start();

/* Deletes the given directory */
function deleteDirectory($dir)
{
    if (!file_exists($dir)) {
        return true;
    }
    if (!is_dir($dir)) {
        return unlink($dir);
    }
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    return rmdir($dir);
}

    if (session_destroy()) {
        /* Deletes the downloads directory */
        deleteDirectory('downloads');
        /* Redirecting to login page */
        header("Location: index.php");
    }
