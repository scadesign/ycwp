<?php
require_once('db.php');
require_once('../model/Response.php');

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

if(array_key_exists("sessionid", $_GET)) {

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

    if(!isset($_SERVER['HTTP_AUTHORIZATION']) || strlen($_SERVER['HTTP_AUTHORIZATION']) <1) {
        $response = new Response();
        $response->setHttpStatusCode(401);
        $response->setSuccess(false);
        (!isset($_SERVER['HTTP_AUTHORIZATION']) ? $response->addMessage("Access token is missing from the header") : false);
        (strlen($_SERVER['HTTP_AUTHORIZATION']) <1 ? $response->addMessage("Access token cannot be blank") : false);
        $response->send();
        exit; 
    }

    $access_token = $_SERVER['HTTP_AUTHORIZATION'];

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

            if($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->setData($returnData);
                $response->addMessage("Failed to logout of the session using the provided access token");
                $response->send();
                exit; 
            }

            $returnData = array();
            $returnData['session_id'] = intval($sessionId);

            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->setData($returnData);
            $response->addMessage("Logged out");
            $response->send();
            exit; 

        }catch(PDOException $e) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("There was an issue logging out - please try again ");
            $response->send();
            exit; 
        }

    }elseif($_SERVER['REQUEST_METHOD'] === 'PATCH') {
        if($_SERVER['CONTENT_TYPE'] !== 'application/json'){
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage(" Content type not set to json");
            $response->send();
            exit; 
        }

        $rawPatchData = file_get_contents('php://input');
        
        if(!$jsonData = json_decode($rawPatchData)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("Content body is not valid json");
            $response->send();
            exit; 
        }

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

            $refreshToken = $jsonData->refresh_token;

            $query =$writeDB->prepare('select tblsessions.id as sessionid, tblsessions.userid as userid, accesstoken, refreshtoken, useractive, loginattempts, accesstokenexpiry, refreshtokenexpiry from tblsessions, tblusers where tblusers.id = tblsessions.userid and tblsessions.id = :sessionid and tblsessions.accesstoken = :accesstoken and tblsessions.refreshtoken = :refreshtoken');
            $query->bindParam(':sessionid', $sessionId, PDO::PARAM_INT);
            $query->bindParam(':accesstoken', $access_token, PDO::PARAM_STR);
            $query->bindParam(':refreshtoken', $refresh_token, PDO::PARAM_STR);
            $query->execute();

            $rowCount = $query->rowCount();

            if($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(401);
                $response->setSuccess(false);
                $response->addMessage("Access token or refresh token is incorrect for session id");
                $response->send();
                exit; 
            }

        }catch(PDOException $e) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("There was an issue refreshing the access token - please login again");
            $response->send();
            exit; 
        }


    }else{
        $response = new Response();
        $response->setHttpStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request method not allowed");
        $response->send();
        exit; 
    }

} elseif(empty($_GET)) {

    if($_SERVER['REQUEST_METHOD'] !=='POST') {
        $response = new Response();
        $response->setHttpStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request method not allowed");
        $response->send();
        exit;
    }

    sleep(1);

    if($_SERVER['CONTENT_TYPE'] !== 'application/json') {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage("Content type header not set to json");
        $response->send();
    exit;
    }

    $rowPostData = file_get_contents('php://input');

    if(!$jsonData = json_decode($rowPostData)) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage("Not valid JSON");
        $response->send();
        exit;
    }   

    if(!isset($jsonData->username) || !isset($jsonData->password)) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        (!isset($jsonData->username) ? $response->addMessage("Username not supplied") : false);
        (!isset($jsonData->password) ? $response->addMessage("Password not supplied") : false);
        $response->send();
        exit;
    }

    if(strlen($jsonData->username) < 1 || strlen($jsonData->username) > 255 || strlen($jsonData->password) < 1 || strlen($jsonData->password) > 255) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        (strlen($jsonData->username) < 1 ? $response->addMessage("Username cannot be blank") : false);
        (strlen($jsonData->username) > 255 ? $response->addMessage("Username cannot be greater than 255 characters") : false);
        (strlen($jsonData->password) < 1 ? $response->addMessage("Password cannot be blank") : false);
        (strlen($jsonData->password) > 255 ? $response->addMessage("Password cannot be greater than 255 characters") : false);
        $response->send();
        exit;
    }

    try {

        $username = $jsonData->username;
        $password = $jsonData->password;

        $query = $dB->prepare('select id, first_name, last_name, phone, email from volunteers where email = :email');
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->execute();

        $rowCount = $query->rowCount();

        if($rowCount === 0) {
            $response = new Response();
            $response->setHttpStatusCode(401);
            $response->setSuccess(false);
            $response->addMessage("email or password is incorrect");
            $response->send();
            exit; 
        }

        $row = $query->fetch(PDO::FETCH_ASSOC);

        $returned_id = $row['id'];
        $returned_fullname = $row['first_name'] . $row['last_name'];
        $returned_username = $row['email'];
        $returned_password = $row['password'];
        


        if(!password_verify($password, $returned_password)) {
            $response = new Response();
            $response->setHttpStatusCode(401);
            $response->setSuccess(false);
            $response->setData($returnData);
            $response->addMessage("email or password is incorrect");
            $response->send();
            exit; 
        }
    } catch(PDOException $e) {
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("There was an issue loggong in");
        $response->send();
        exit; 
    }

        $accesstoken = base64_encode(bin2Hex(openssl_random_pseudo_bytes(24)).time());
        $refreshtoken = base64_encode(bin2Hex(openssl_random_pseudo_bytes(24)).time());

        $access_token_expiry_seconds = 7200; //2 hours
        $refresh_token_expiry_seconds = 1209600; // 14 days

        try {
            $dB->beginTransaction();
            $query = $dB->prepare('update tblusers set loginattempts = 0 where id = :id');
            $query->bindParam(':id', $returned_id, PDO::PARAM_INT);
            $query->execute();

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

            $response = new Response();
            $response->setHttpStatusCode(201);
            $response->setSuccess(true);
            $response->setData($returnData);
            $response->send();
            exit; 
            
        } catch(PDOException $e) {
            $dB->rollBack();
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("There was an issue loggong in please try again ". $e);
            $response->send();
            exit; 
        }

} else {
    $response = new Response();
    $response->setHttpStatusCode(404);
    $response->setSuccess(false);
    $response->addMessage("Endpoint not found");
    $response->send();
    exit; 
}
?>