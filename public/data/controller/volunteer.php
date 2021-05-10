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
if($_SERVER['REQUEST_METHOD'] !=='POST') {
    $response = new Response();
    $response->setHttpStatusCode(405);
    $response->setSuccess(false);
    $response->addMessage("Request method not allowed");
    $response->send();
    exit;
}

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

if(!isset($jsonData->fullname)|| !isset($jsonData->username) || !isset($jsonData->password)) {
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    (!isset($jsonData->fullname) ? $response->addMessage("Fullname not supplied") : false);
    (!isset($jsonData->username) ? $response->addMessage("Username not supplied") : false);
    (!isset($jsonData->password) ? $response->addMessage("Password not supplied") : false);
    $response->send();
    exit;
}

if(strlen($jsonData->fullname) < 1 || strlen($jsonData->fullname) > 255 || strlen($jsonData->username) < 1 || strlen($jsonData->username) > 255 || strlen($jsonData->password) < 1 || strlen($jsonData->password) > 255) {
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    (strlen($jsonData->fullname) < 1 ? $response->addMessage("Fullname cannot be blank") : false);
    (strlen($jsonData->fullname) > 255 ? $response->addMessage("Fullname cannot be greater than 255 characters") : false);
    (strlen($jsonData->username) < 1 ? $response->addMessage("Username cannot be blank") : false);
    (strlen($jsonData->username) > 255 ? $response->addMessage("Username cannot be greater than 255 characters") : false);
    (strlen($jsonData->password) < 1 ? $response->addMessage("Password cannot be blank") : false);
    (strlen($jsonData->password) > 255 ? $response->addMessage("Password cannot be greater than 255 characters") : false);
    $response->send();
    exit;
}

$fullname = trim($jsonData->fullname);
$username = trim($jsonData->username);
$password = $jsonData->password;

try {
    $query = $dB->prepare('select id from tblusers where username = :username');
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->execute();

    $rowCount = $query->rowCount();

    if($rowCount !== 0) {
        $response = new Response();
        $response->setHttpStatusCode(409); //conflict error
        $response->setSuccess(false);
        $response->addMessage("Username already exists - please try again");
        $response->send();
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = $dB->prepare('insert into tblusers (fullname, username, password) values(:fullname, :username, :password)');
    $query->bindParam(':fullname', $fullname, PDO::PARAM_STR);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    $query->execute();

    $rowCount = $query->rowCount();

    if($rowCount === 0) {
        $response = new Response();
        $response->setHttpStatusCode(500); 
        $response->setSuccess(false);
        $response->addMessage("There was an issue creating a user account - please try again");
        $response->send();
        exit;
    }

    $lastUserID = $dB->lastInsertID();
    $returnData = array();
    $returnData['user_id'] = $lastUserID;
    $returnData['fullname'] = $fullname;
    $returnData['username'] = $username;

    $response = new Response();
    $response->setHttpStatusCode(201); 
    $response->setSuccess(true);
    $response->addMessage("User created");
    $response->setData($returnData);
    $response->send();
    exit;


}catch(PDOException $e){
    error_log("Database query error - ".$e, 0);
    $response = new Response();
    $response->setHttpStatusCode(500);
    $response->setSuccess(false);
    $response->addMessage("There was an issue creating a user account - please try again");
    $response->send();
    exit;
}

?>