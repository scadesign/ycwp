<?php

require_once('db.php');
require_once('../model/Response.php');
require_once('../model/Environment.php');
require_once('../model/SeaWatch.php');
require_once('../model/Sighting.php');
require_once('../model/Volunteer.php');

try{
    $writeDB = DB::connectWriteDB();
    $readDB = DB::connectReadeDB();
} catch(PDOException $e) {
    error_log("Connection error - ".$e, 0);
    $response = new Response();
    $response->setHttpStatusCode(500);
    $response->setSuccess(false);
    $response->addMessage("database connection error");
    $response->send();
    exit;
}
