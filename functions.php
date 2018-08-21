<?php

function getAccessToken($helper)
{
    try {
        $accessToken = $helper->getAccessToken();
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        // exit;
        return null;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        // exit;
        return null;
    }

    if (!isset($accessToken)) {
        if ($helper->getError()) {
            header('HTTP/1.0 401 Unauthorized');
            echo "Error: " . $helper->getError() . "\n";
            echo "Error Code: " . $helper->getErrorCode() . "\n";
            echo "Error Reason: " . $helper->getErrorReason() . "\n";
            echo "Error Description: " . $helper->getErrorDescription() . "\n";
        } else {
            header('HTTP/1.0 400 Bad Request');
            echo 'Bad request';
        }
        // exit;
        return null;
    }
    return $accessToken;
}

/* Gettign Basic User Details with Album list */
function getUserData($fb)
{
    $response = $fb->get('/me?fields=name,id,email,albums', $_SESSION['fb_access_token']);
    $user = $response->getGraphuser();

    return $user;
}
/* Gettign Basic User Details with Album list */
    

function getAlbumCover($fb, $albumID)
{
    try {
        $response = $fb->get(
            '/'.$albumID.'?fields=cover_photo',
            $_SESSION['fb_access_token']
        );
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    $albumCover = $response->getGraphNode();
    return $albumCover;
}

function getAllAlbumImages($fb, $albumID)
{
    try {
        $response = $fb->get(
            '/'.$albumID.'/photos?limit=500',
            $_SESSION['fb_access_token']
        );
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    $allImages = $response->getGraphEdge();

    return $allImages;
}


function getCoverImageDetails($fb, $albumCover)
{
    try {
        $existCoverPhoto = isset($albumCover['cover_photo']);
        if (count($albumCover) > 0 && $existCoverPhoto == 1) {
            $response = $fb->get(
                '/'.$albumCover['cover_photo']['id']."?fields=images",
                $_SESSION['fb_access_token']
            );
        }
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        return null;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    } catch (Exception $e) {
        return null;
    }
    try {
        if (count($albumCover) > 0 && $existCoverPhoto == 1) {
            $coverImage = $response->getGraphNode();
        } else {
            return null;
        }
    } catch (Exception $e) {
        return null;
    }

    return $coverImage;
}

function getImageDetails($fb, $image)
{
    try {
        if (count($image) > 0) {
            $response = $fb->get(
                '/'.$image['id'].'?fields=name,id,created_time,images',
                $_SESSION['fb_access_token']
            );
        }
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        return null;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    } catch (Exception $e) {
        return null;
    }
    try {
        if (count($image) > 0) {
            $coverImage = $response->getGraphNode();
        } else {
            return null;
        }
    } catch (Exception $e) {
        return null;
    }

    return $coverImage;
}
