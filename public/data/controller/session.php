<?php
require_once('db.php');
require_once('../model/Response.php');

/*
login a volunteer and create a session for the volunteer or 
update the session if it already exist
GEt and Patch methods used to check and update the session
Post used to login
Response sent to client using the response model and json data listing errors or success
*/

try{
    $dB = DB::connectDB();
} catch(PDOException $e) {
    error_log("Connection error - ".$e, 0);
    $response = new Response();
    $response->setHttpStatusCode(500);
    $response->setSuccess(false);
    $response->addMessage("database connection error");
    $response->send();
    exit; 
}

// check a session has been created
if(array_key_exists("sessionid", $_GET)) {

    //if a sesssion exists assign its id
    $sessionId = $_GET['sessionid'];

    if($sessionId ==='' || !is_numeric($sessionId)) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        ($sessionId === '' ? $response->addMessage("Session Id cannot be blank") : false);
        (!is_numeric($sessionId) ? $response->addMessage("Session Id must be numeric") : false);
        $response->send();
        exit; 
    }
    //Check an acces token exists and is not blank
    if(!isset($_SERVER['HTTP_AUTHORIZATION']) || strlen($_SERVER['HTTP_AUTHORIZATION']) <1) {
        $response = new Response();
        $response->setHttpStatusCode(401);
        $response->setSuccess(false);
        (!isset($_SERVER['HTTP_AUTHORIZATION']) ? $response->addMessage("Access token is missing from the header") : false);
        (strlen($_SERVER['HTTP_AUTHORIZATION']) <1 ? $response->addMessage("Access token cannot be blank") : false);
        $response->send();
        exit; 
    }

    //if access token exists assign it
    $access_token = $_SERVER['HTTP_AUTHORIZATION'];

    // set up the logout for a volunteer
    if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        try {
            $query = $dB->prepare('delete from tblsessions where id = :sessionid and accesstoken = :accesstoken');
            $query->bindParam(':sessionid', $sessionId, PDO::PARAM_INT);
            $query->bindParam('accesstoken', $access_token, PDO::PARAM_STR);
            $query->execute();

            $rowCount = $query->rowCount();
            $returnData = array();
            $returnData['rowCount'] = $rowCount;
            $returnData['session'] = $sessionId;
            $returnData['token'] = $access_token;

            // if no session matching the details was found
            if($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->setData($returnData);
                $response->addMessage("Failed to logout of the session using the provided access token");
                $response->send();
                exit; 
            }
            // if session data found sign out
            $returnData = array();
            $returnData['session_id'] = intval($sessionId);

            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->setData($returnData);
            $response->addMessage("Logged out");
            $response->send();
            exit; 
        // database error
        }catch(PDOException $e) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("There was an issue logging out - please try again ");
            $response->send();
            exit; 
        }
    // if new access token is required
    }elseif($_SERVER['REQUEST_METHOD'] === 'PATCH') {
        // check to see if content type header is valid json header
        if($_SERVER['CONTENT_TYPE'] !== 'application/json'){
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage(" Content type not set to json");
            $response->send();
            exit; 
        }
        // assign the content
        $rawPatchData = file_get_contents('php://input');
        
        // check the content body is valid json
        if(!$jsonData = json_decode($rawPatchData)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Content body is not valid json");
            $response->send();
            exit; 
        }
         // check there is a refresh token and the refresh token is not empty
        if(!isset($jsonData->refresh_token) || strlen($jsonData->refresh_token) < 1) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            (!isset($jsonData->refresh_token) ? $response->addMessage("Refresh token is not supplied") : false);
            (strlen($jsonData->refresh_token) < 1 ? $response->addMessage("Refresh token cannot be blank") : false);
            $response->send();
            exit; 
        }

        try {
            // assign the content body and find the session
            $refreshToken = $jsonData->refresh_token;

            $query =$writeDB->prepare('select tblsessions.id as sessionid, tblsessions.userid as userid, accesstoken, refreshtoken, accesstokenexpiry, refreshtokenexpiry from tblsessions, tblusers where tblusers.id = tblsessions.userid and tblsessions.id = :sessionid and tblsessions.accesstoken = :accesstoken and tblsessions.refreshtoken = :refreshtoken');
            $query->bindParam(':sessionid', $sessionId, PDO::PARAM_INT);
            $query->bindParam(':accesstoken', $access_token, PDO::PARAM_STR);
            $query->bindParam(':refreshtoken', $refresh_token, PDO::PARAM_STR);
            $query->execute();

            $rowCount = $query->rowCount();
            //if the seeion doesn't exist
            if($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(401);
                $response->setSuccess(false);
                $response->addMessage("Access token or refresh token is incorrect for session id");
                $response->send();
                exit; 
            }
        // database error
        }catch(PDOException $e) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("There was an issue refreshing the access token - please login again");
            $response->send();
            exit; 
        }

    // if patch request method not allowed
    }else{
        $response = new Response();
        $response->setHttpStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request method not allowed");
        $response->send();
        exit; 
    }

} elseif(empty($_GET)) {

    // login a volunteer with post request
    if($_SERVER['REQUEST_METHOD'] !=='POST') {
        $response = new Response();
        $response->setHttpStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request method not allowed");
        $response->send();
        exit;
    }

    sleep(1);
    // check content type header is set to json
    if($_SERVER['CONTENT_TYPE'] !== 'application/json') {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage("Content type header not set to json");
        $response->send();
    exit;
    }
    // assign the content
    $rowPostData = file_get_contents('php://input');
    // check content is valid json
    if(!$jsonData = json_decode($rowPostData)) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage("Not valid JSON");
        $response->send();
        exit;
    }   
    //check an email and password has been supplied
    if(!isset($jsonData->email) || !isset($jsonData->password)) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        (!isset($jsonData->username) ? $response->addMessage("email not supplied") : false);
        (!isset($jsonData->password) ? $response->addMessage("Password not supplied") : false);
        $response->send();
        exit;
    }

    // validate the username and password content
    if(strlen($jsonData->email) < 1 || strlen($jsonData->email) > 255 || strlen($jsonData->password) < 1 || strlen($jsonData->password) > 255) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        (strlen($jsonData->username) < 1 ? $response->addMessage("email cannot be blank") : false);
        (strlen($jsonData->username) > 255 ? $response->addMessage("Email cannot be greater than 255 characters") : false);
        (strlen($jsonData->password) < 1 ? $response->addMessage("Password cannot be blank") : false);
        (strlen($jsonData->password) > 255 ? $response->addMessage("Password cannot be greater than 255 characters") : false);
        $response->send();
        exit;
    }

    try {
        //assign the content username and password
        $email = $jsonData->email;
        $password = $jsonData->password;
        // select the volunteer
        $query = $dB->prepare('select id, email, password from volunteers where email = :email');
        $query->bindParam(':username', $email, PDO::PARAM_STR);
        $query->execute();

        $rowCount = $query->rowCount();
        // if the volunteer is not found
        if($rowCount === 0) {
            $response = new Response();
            $response->setHttpStatusCode(401);
            $response->setSuccess(false);
            $response->addMessage("email or password is incorrect");  // generic error message
            $response->send();
            exit; 
        }

        $row = $query->fetch(PDO::FETCH_ASSOC);
        // assign the vonteer details
        $returned_id = $row['id'];
        $returned_username = $row['email'];
        $returned_password = $row['password'];
        

        // check the provided password matches the volunteers password
        if(!password_verify($password, $returned_password)) {
            $response = new Response();
            $response->setHttpStatusCode(401);
            $response->setSuccess(false);
            $response->setData($returnData);
            $response->addMessage("email or password is incorrect");
            $response->send();
            exit; 
        }
    // general database error
    } catch(PDOException $e) {
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("There was an issue loggong in");
        $response->send();
        exit; 
    }
        // create the access and refresh token
        $accesstoken = base64_encode(bin2Hex(openssl_random_pseudo_bytes(24)).time());
        $refreshtoken = base64_encode(bin2Hex(openssl_random_pseudo_bytes(24)).time());

        //set the expiry times for the access and refresh tokens
        $access_token_expiry_seconds = 7200; //2 hours
        $refresh_token_expiry_seconds = 1209600; // 14 days

        try {
            
            //set up the session for the volunteer
            $query = $dB->prepare('insert into tblsessions (userid, accesstoken, accesstokenexpiry, refreshtoken, refreshtokenexpiry) values (:userid, :accesstoken, date_add(NOW(), INTERVAL :accesstokenexpiryseconds SECOND), :refreshtoken, date_add(NOW(), INTERVAL :refreshtokenexpiryseconds SECOND))');
            $query->bindParam(':userid', $returned_id, PDO::PARAM_INT);
            $query->bindParam(':accesstoken', $accesstoken, PDO::PARAM_STR);
            $query->bindParam(':accesstokenexpiryseconds', $access_token_expiry_seconds, PDO::PARAM_INT);
            $query->bindParam(':refreshtoken', $refreshtoken, PDO::PARAM_STR);
            $query->bindParam(':refreshtokenexpiryseconds', $refresh_token_expiry_seconds, PDO::PARAM_INT);
            $query->execute();

            $lastSessionID = $dB->lastInsertId();

            $dB->commit();

            $returndata = array();
            $returnData['sessiion_id'] = intval($lastSessionID);
            $returnData['access_token'] = $accesstoken;
            $returnData['access_token_expires_in'] = $access_token_expiry_seconds;
            $returnData['refresh_token'] = $refreshtoken;
            $returnData['refresh_token_expires_in'] = $refresh_token_expiry_seconds;
            // login the user and set confirmation response
            $response = new Response();
            $response->setHttpStatusCode(201);
            $response->setSuccess(true);
            $response->setData($returnData);
            $response->send();
            exit; 
        // general databse error
        } catch(PDOException $e) {
            $dB->rollBack();
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("There was an issue loggong in please try again ". $e);
            $response->send();
            exit; 
        }
// if everything fails
} else {
    $response = new Response();
    $response->setHttpStatusCode(404);
    $response->setSuccess(false);
    $response->addMessage("Endpoint not found");
    $response->send();
    exit; 
}
?>