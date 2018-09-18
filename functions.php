<?php

/* Gets the Facebook Access Token */
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
/* Gets the Facebook Access Token */

/* Gets Basic User Details with Album list */
function getUserData($fb)
{
    $response = $fb->get('/me?fields=name,id,email,albums', $_SESSION['fb_access_token']);
    $user = $response->getGraphuser();

    return $user;
}
/* Gets Basic User Details with Album list */
    
/* Gets album cover of given AlbumID */
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
/* Gets album cover of given AlbumID */

/* Gets All Images of given AlbumID */
function getAllAlbumImages($fb, $albumID)
{
    $allImages = array();
    try {
        $response = $fb->get(
            '/'.$albumID.'?fields=name,photos.limit(500){images}',
            $_SESSION['fb_access_token']
        );
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    
    $allImages = $response->getGraphNode();
    return $allImages;
}
/* Gets All Images of given AlbumID */

/* Gets Details of given Cover Image ID */
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
/* Gets Details of given Cover Image ID */
