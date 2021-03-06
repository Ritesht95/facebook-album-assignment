<?php
require_once 'google-api-php-client/src/Google/Client.php';
require_once "google-api-php-client/src/Google/Service/Oauth2.php";

$str = "{\"web\":{\"client_id\":\"59128490941-9pi7oolm20ot5h9m62ngj6g0f3e7j0pb.apps.googleusercontent.com\",\"project_id\":\"twittertemp-1533798939629\",\"auth_uri\":\"https://accounts.google.com/o/oauth2/auth\",\"token_uri\":\"https://www.googleapis.com/oauth2/v3/token\",\"auth_provider_x509_cert_url\":\"https://www.googleapis.com/oauth2/v1/certs\",\"client_secret\":\"OIDqKUtb5GpwMjM12ob6fUIV\",\"redirect_uris\":[\"http://localhost/FacebookTest/\",\"https://localhost/rtCamp_Facebook_Assignment/googleLogin.php\",\"https://facebookalbums.herokuapp.com/googleLogin.php\"]}}";
$json = json_decode($str, true);

$CLIENT_ID = $json['web']['client_id'];
$CLIENT_SECRET = $json['web']['client_secret'];
$REDIRECT_URI = $json['web']['redirect_uris'][2];

// Set the scopes you need
$SCOPES = array(
    'https://www.googleapis.com/auth/drive.file',
    'https://www.googleapis.com/auth/userinfo.email',
    'https://www.googleapis.com/auth/userinfo.profile');

/**
 * Store OAuth 2.0 credentials in the application's database.
 *
 * @param String $userId User's ID.
 * @param String $credentials Json representation of the OAuth 2.0 credentials to store.
 * @param String $userInfo Overall user data
 */
function storeCredentials($userId, $credentials, $userInfo)
{
    $_SESSION["userInfo"] = $userInfo;
    setcookie("userId", $userId, time() + (86400 * 30), "/");
    setcookie("credentials", $credentials, time() + (86400 * 30), "/");
}

/**
 * Get OAuth 2.0 credentials from the application's database.
 *
 * @param String $userId User's ID.
 * @return JSON $credentials if the user has logged in to the service before, else return null
 */
function getStoredCredentials($userId)
{
    // TODO: Integrate with a database
    if (isset($_COOKIE["credentials"])) {
        return $_COOKIE["credentials"];
    } else {
        return null;
    }
}

/**
* Lets first get an authorization URL to our client, it will forward the client to Google's Concent window
* @param String $emailAddress
* @param String $state
* @return String URL to Google Concent screen
*/
function getAuthorizationUrl($emailAddress, $state)
{
    global $CLIENT_ID, $REDIRECT_URI, $SCOPES;
    $client = new Google_Client();

    $client->setClientId($CLIENT_ID);
    $client->setRedirectUri($REDIRECT_URI);
    $client->setAccessType('offline');
    $client->setApprovalPrompt('force');
    $client->setState($state);
    $client->setScopes($SCOPES);
    $tmpUrl = parse_url($client->createAuthUrl());
    $query = explode('&', $tmpUrl['query']);
    $query[] = 'user_id=' . urlencode($emailAddress);
    
    return
    $tmpUrl['scheme'] . '://' . $tmpUrl['host'] .
    $tmpUrl['path'] . '?' . implode('&', $query);
}

/**
 * Exchange an authorization code for OAuth 2.0 credentials.
 *
 * @param String $authorizationCode Authorization code to exchange for OAuth 2.0
 *                                  credentials.
 * @return String Json representation of the OAuth 2.0 credentials.
 * @throws An error occurred. And prints the error message
 */
function exchangeCode($authorizationCode)
{
    try {
        global $CLIENT_ID, $CLIENT_SECRET, $REDIRECT_URI;
        $client = new Google_Client();

        $client->setClientId($CLIENT_ID);
        $client->setClientSecret($CLIENT_SECRET);
        $client->setRedirectUri($REDIRECT_URI);
        return $client->authenticate($authorizationCode);
    } catch (Exception $e) {
        print 'An error occurred: ' . $e->getMessage();
    }
}

/**
 * Retrieve credentials using the provided authorization code.
 *
 * @param String authorizationCode Authorization code to use to retrieve an access token.
 * @param String state State to set to the authorization URL in case of error.
 * @return String Json representation of the OAuth 2.0 credentials.
 */
function getCredentials($authorizationCode, $state)
{
    $emailAddress = '';
    try {
        $credentials = exchangeCode($authorizationCode);
        $userInfo = getUserInfo($credentials);
        $emailAddress = $userInfo->getEmail();
        $userId = $userInfo->getId();
        $credentialsArray = json_decode($credentials, true);
        if (isset($credentialsArray['refresh_token'])) {
            storeCredentials($userId, $credentials, $userInfo);
            return $credentials;
        } else {
            $credentials = getStoredCredentials($userId);
            if ($credentials != null && isset($credentials)) {
                storeCredentials($userId, $credentials, $userInfo);
                return $credentials;
            } else {
                echo "Unexpected error.";
                die;
            }
        }
    } catch (CodeExchangeException $e) {
        print 'An error occurred during code exchange.';
        // Drive apps should try to retrieve the user and credentials for the current
        // session.
        // If none is available, redirect the user to the authorization URL.
        $e->setAuthorizationUrl(getAuthorizationUrl($emailAddress, $state));
        throw $e;
    } catch (NoUserIdException $e) {
        print 'No e-mail address could be retrieved.';
    }
    // No token has been retrieved.
    $authorizationUrl = getAuthorizationUrl($emailAddress, $state);
}

/**
 * Send a request to the UserInfo API to retrieve the user's information.
 *
 * @param String credentials OAuth 2.0 credentials to authorize the request.
 * @return Userinfo User's information.
 * @throws NoUserIdException An error occurred.
 */
function getUserInfo($credentials)
{
    $apiClient = new Google_Client();
    $apiClient->setAccessToken($credentials);
    $userInfoService = new Google_Service_Oauth2($apiClient);
    try {
        $userInfo = $userInfoService->userinfo->get();

        if ($userInfo != null && $userInfo->getId() != null) {
            return $userInfo;
        } else {
            echo "No user ID";
        }
    } catch (Exception $e) {
        print 'An error occurred: ' . $e->getMessage();
    }
}

/**
* Get the folder ID if it exists, if it doesnt exist, create it and return the ID
*
* @param Google_DriveService $service Drive API service instance.
* @param String $folderName Name of the folder you want to search or create
* @param String $folderDesc Description metadata for Drive about the folder (optional)
* @return Google_Drivefile that was created or got. Returns NULL if an API error occured
*/
function getFolderExistsCreate($service, $folderName, $folderDesc, $parentFolderID)
{
    // List all user files (and folders) at Drive root
    $files = $service->files->listFiles();
    $found = false;

    // Go through each one to see if there is already a folder with the specified name
    foreach ($files['items'] as $item) {
        if ($item['title'] == $folderName) {
            $found = true;
            return $item['id'];
            break;
        }
    }

    // If not, create one
    if ($found == false) {
        $folder = new Google_Service_Drive_DriveFile();

        //Setup the folder to create
        $folder->setTitle($folderName);

        if (!empty($folderDesc)) {
            $folder->setDescription($folderDesc);
        }

        $folder->setMimeType('application/vnd.google-apps.folder');

        if ($parentFolderID != "NULL") {
            $parent = new Google_Service_Drive_ParentReference();
            $parent->setId($parentFolderID);
            $folder->setParents(array($parent));
        }
        

        //Create the Folder
        try {
            $createdFile = $service->files->insert($folder, array(
                'mimeType' => 'application/vnd.google-apps.folder',
                ));

            // Return the created folder's id
            return $createdFile->id;
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }
    }
}

/**
 * Insert new file in the Application Data folder.
 *
 * @param Google_DriveService $service Drive API service instance.
 * @param string $title Title of the file to insert, including the extension.
 * @param string $description Description of the file to insert.
 * @param string $mimeType MIME type of the file to insert.
 * @param string $filename Filename of the file to insert.
 * @return Google_DriveFile The file that was inserted. NULL is returned if an API error occurred.
 */
function insertFile($service, $title, $description, $mimeType, $filename, $folderID)
{
    $file = new Google_Service_Drive_DriveFile();

    // Set the metadata
    $file->setTitle($title);
    $file->setDescription($description);
    $file->setMimeType($mimeType);

    // Setup the folder you want the file in, if it is wanted in a folder
    if ($folderID != "NULL") {
        $parent = new Google_Service_Drive_ParentReference();
        $parent->setId($folderID);
        $file->setParents(array($parent));
    }
    try {
        // Get the contents of the file uploaded
        $data = file_get_contents($filename);

        // Try to upload the file, you can add the parameters e.g. if you want to convert a .doc to editable google format, add 'convert' = 'true'
        $createdFile = $service->files->insert($file, array(
            'data' => $data,
            'mimeType' => $mimeType,
            'uploadType'=> 'multipart'
            ));

        // Return a bunch of data including the link to the file we just uploaded
        return $createdFile;
    } catch (Exception $e) {
        print "An error occurred: " . $e->getMessage();
    }
}
