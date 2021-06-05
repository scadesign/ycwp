<?php 
require_once('db.php');
require_once('../model/Response.php');

/*
Sign up a volunteer and add them to the database
Volunteer created with post Json data consisting of
first_name, last_name, email, phone, password
All fieds are required and fields validated before adding to the database
Response sent to client using the response model and json data listing errors or success
*/

// database connection
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

// sign up a volunteer with post content
if($_SERVER['REQUEST_METHOD'] !=='POST') {
    $response = new Response();
    $response->setHttpStatusCode(405);
    $response->setSuccess(false);
    $response->addMessage("Request method not allowed");
    $response->send();
    exit;
}
// check content header is set to json
if($_SERVER['CONTENT_TYPE'] !== 'application/json') {
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    $response->addMessage("Content type header not set to json");
    $response->send();
    exit;
}
//assign the content
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
// some information is not supplied
if(!isset($jsonData->first_name) || !isset($jsonData->last_name) || !isset($jsonData->email) || !isset($jsonData->phone) || !isset($jsonData->password)) {
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    (!isset($jsonData->first_name) ? $response->addMessage("first name not supplied") : false);
    (!isset($jsonData->last_name) ? $response->addMessage("last name not supplied") : false);
    (!isset($jsonData->email) ? $response->addMessage("email not supplied") : false);
    (!isset($jsonData->phone) ? $response->addMessage("phone number not supplied") : false);
    (!isset($jsonData->password) ? $response->addMessage("Password not supplied") : false);
    $response->send();
    exit;
}

// validate the data is not blank and does notexceed the character limit
if(strlen($jsonData->first_name) < 1 || strlen($jsonData->first_name) > 40 ||  strlen($jsonData->last_name) < 1 || strlen($jsonData->last_name) > 40 || !filter_var($jsonData->email, FILTER_VALIDATE_EMAIL) || strlen($jsonData->phone) < 1 || strlen($jsonData->phone) > 16 || strlen($jsonData->password) < 1 || strlen($jsonData->password) > 255) {
    $response = new Response();
    $response->setHttpStatusCode(400);
    $response->setSuccess(false);
    (strlen($jsonData->first_name) < 1 ? $response->addMessage("First name cannot be blank") : false);
    (strlen($jsonData->first_name) > 255 ? $response->addMessage("First name cannot be greater than 40 characters") : false);
    (strlen($jsonData->last_name) < 1 ? $response->addMessage("Last name cannot be blank") : false);
    (strlen($jsonData->last_name) > 255 ? $response->addMessage("Last name cannot be greater than 40 characters") : false);
    (!filter_var($jsonData->email, FILTER_VALIDATE_EMAIL)?$response->addMessage("The email is not a valid email") : false);
    (strlen($jsonData->phone) < 1 ? $response->addMessage("Phone cannot be blank") : false);
    (strlen($jsonData->phone) > 255 ? $response->addMessage("Phone cannot be greater than 16 digits") : false);
    (strlen($jsonData->password) < 1 ? $response->addMessage("Password cannot be blank") : false);
    (strlen($jsonData->password) > 255 ? $response->addMessage("Password cannot be greater than 255 characters") : false);
    $response->send();
    exit;
}


// trim white space or other characters and check the email is a valid email and assign them
$first_name = trim($jsonData->first_name);
$last_name = trim($jsonData->last_name);
$email = $jsonData->email;
$phone = trim($jsonData->phone);
// password could have any character
$password = $jsonData->password;

try {

    //Check the volunteer doesn't already exist
    $query = $dB->prepare('select id from tblusers where email = :email');
    $query->bindParam(':username', $email, PDO::PARAM_STR);
    $query->execute();

    $rowCount = $query->rowCount();

    if($rowCount !== 0) {
        $response = new Response();
        $response->setHttpStatusCode(409); //conflict error
        $response->setSuccess(false);
        $response->addMessage("Volunteer already exists with that email- please try again");
        $response->send();
        exit;
    }
    // secure thepassword to stroe in the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    // add the volunteer
    $query = $dB->prepare('insert into tblusers (first_name, last_name, email, phone, password) values(:first_name, :last_name, :email, :phone , :password)');
    $query->bindParam(':first_name', $first_name, PDO::PARAM_STR);
    $query->bindParam(':last_name', $last_name, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':phone', $phone, PDO::PARAM_STR);
    $query->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    $query->execute();

    $rowCount = $query->rowCount();
    // if there was a problem
    if($rowCount === 0) {
        $response = new Response();
        $response->setHttpStatusCode(500); 
        $response->setSuccess(false);
        $response->addMessage("There was an issue creating a user account - please try again");
        $response->send();
        exit;
    }
    // return a confirmation response
    $lastUserID = $dB->lastInsertID();
    $returnData = array();
    $returnData['user_id'] = $lastUserID;
    $returnData['first_name'] = $first_name;
    $returnData['last_name'] = $last_name;
    $returnData['email'] = $email;

    $response = new Response();
    $response->setHttpStatusCode(201); 
    $response->setSuccess(true);
    $response->addMessage("User created");
    $response->setData($returnData);
    $response->send();
    exit;

// general database error
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