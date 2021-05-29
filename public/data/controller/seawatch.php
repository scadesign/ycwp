<?php

require_once('db.php');
require_once('../model/Response.php');
require_once('../model/Environment.php');
require_once('../model/SeaWatch.php');
require_once('../model/Sighting.php');
require_once('../model/Volunteer.php');

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

$json = file_get_contents('php://input');
$data = json_decode($json, true);
$record = $data['record'];

// sanitize the record data
$date = htmlspecialchars($record['date']);
$siteName = htmlspecialchars($record['siteName']);
$latLong = htmlspecialchars($record['latLong']);
$observer = htmlspecialchars($record['observer']);
$address = htmlspecialchars($record['address']);
$email = filter_var(trim($record['email']), FILTER_SANITIZE_EMAIL);
$telephone = htmlspecialchars($record['telephone']);

// build the Email
$recording  = '<h3>'.$observer.'-'.$record['siteName'].'-'.$record['date'].'-landbasedform</h3>';
$recording .='<style> table { font-family: Verdana, Arial, Helvetica, sans-serif; margin-bottom:10px;width:95%; font-size:10pt;}';
$recording  .='td {border: 1px solid;padding:2px 10px;}.underline{background: lightgray;border-bottom:3px solid gray; font-weight:bold;}';
$recording  .='h3 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size:1.3em</style>';
$recording .= '<table><tr class="underline">';
$recording .= '<td>Date</td>';
$recording .= '<td>Site Name</td>';
$recording .= '<td colspan="2">Latitude/Longitude</td>';
$recording .= '</tr><tr>';
$recording .= '<td>'.$date.'</td>';
$recording .= '<td>'.$siteName.'</td>';
$recording .= '<td colspan="2">'.$latLong.'</td>';
$recording .= '</tr>';
$recording .= '<tr class="underline">';
$recording .= '<td>Obs. Name</td>';
$recording .= '<td>Address</td>';
$recording .= '<td>Email</td>';
$recording .= '<td>Tel</td>';
$recording .= '</tr><tr>';
$recording .= '<td>'.$observer.'</td>';
$recording .= '<td>'.$address.'</td>';
$recording .= '<td>'.$email.'</td>';
$recording .= '<td>'.$telephone.'</td>';
$recording .= '</tr></table>';


$env = $data['environment'];
$envDat = '<h3>Effort and environmental data</h3>';

for($i=0; $i < sizeOf($env); $i++){
	$envDat  .= '<table>';
	$envDat .= '<tr class="underline">';
	$envDat .= '<td>GMT/BST?</td>';
	$envDat .= '<td>Start</td>';
	$envDat .= '<td>End</td>';
	$envDat .= '<td>Sea State</td>';
	$envDat .= '<td>Swell Height</td>';
	$envDat .= '<td>Wind Direction</td>';
	$envDat .= '<td>Visibility</td>';
	$envDat .= '</tr>';
	$envDat .= '<tr>';
	$envDat .= '<td>'.htmlspecialchars($env[$i]['zone']).'</td>';
	$envDat .= '<td>'.htmlspecialchars($env[$i]['start']).'</td>';
	$envDat .= '<td>'.htmlspecialchars($env[$i]['end']).'</td>';
	$envDat .= '<td>'.htmlspecialchars($env[$i]['seaState']).'</td>';
	$envDat .= '<td>'.htmlspecialchars($env[$i]['swellHeight']).'</td>';
	$envDat .= '<td>'.htmlspecialchars($env[$i]['windDirection']).'</td>';
	$envDat .= '<td>'.htmlspecialchars($env[$i]['visibility']).'</td>';
	$envDat .= '<tr><tr>';
	$envDat .= '<td colspan="7" style="border:none;"><b>Additional Notes;</b> '.htmlspecialchars($env[$i]['notes']).'</td>';
	$envDat .= '</tr>';
	$envDat .= '</table>';
}
  
  


$cets = $data['sightings'];

$sighting = '<h3>Sightings</h3>';

	$sighting .= '<table><tr class="underline">';
	$sighting .= '<td>First Seen</td>';
	$sighting .= '<td>Last Seen</td>';
	$sighting .= '<td>Species</td>';
	$sighting .= '<td>Confidence</td>';
	$sighting .= '<td>Group Size</td>';
	$sighting .= '<td>Calves No.</td>';
	$sighting .= '<td>Juveniles No.</td>';
	$sighting .= '<td>Bearing</td>';
	$sighting .= '<td>Distance</td>';
	$sighting .= '<td>Behaviour</td>';
	$sighting .= '<td>Associated birds</td>';
	$sighting .= '</tr>';

for($i=0; $i < sizeOf($cets); $i++){
	$sighting .= '<tr>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['firstSeen']).'</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['lastSeen']).'</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['species']).'</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['confidence']).'</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['groupSize']).'</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['calves']).'</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['juveniles']).'</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['bearing']).'</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['distance']).'km</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['behaviour']).'</td>';
	$sighting .= '<td>'.htmlspecialchars($cets[$i]['associatedBirds']).'</td>';
	$sighting .= '</tr>';

}
  $sighting .= '</table></tr></table>';
  

  if(!isset($_SERVER['HTTP_AUTHORIZATION']) || strlen($_SERVER['HTTP_AUTHORIZATION']) < 1) {
    $response = new Response();
    $response->setHttpStatusCode(401);
    $response->setSuccess(false);
    (!isset($_SERVER['HTTP_AUTHORIZATION']) ? $response->addMessage("Access token is missing from the header") : false);
    (strlen($_SERVER['HTTP_AUTHORIZATION']) < 1 ? $response->addMessage("Access token cannot be blank") : false);
    $response->send();
    exit; 
}

$access_token = $_SERVER['HTTP_AUTHORIZATION'];

try {

    $query = $writeDB->prepare('select userid, accesstokenexpiry, useractive, loginattempts from tblsessions, tblusers where tblsessions.userid = tblusers.id and accesstoken = :accesstoken');
    $query->bindParam(':accesstoken', $access_token, PDO::PARAM_STR);
    $query->execute();
    
    $rowCount = $query->rowCount();
    
    if ($rowCount === 0) {
        $response = new Response();
        $response->setHttpStatusCode(401);
        $response->setSuccess(false);
        $response->addMessage("Invalid access token");
        $response->send();
        exit; 
    }
    
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $returned_userid = $row['userid'];
    $returned_accesstokenexpiry = $row['accesstokenexpiry'];
    $returned_useractive = $row['useractive'];
    $returned_loginattempts = $row['loginattempts'];
    

    
    if(strtotime($returned_accesstokenexpiry) < time()) {
        $response = new Response();
        $response->setHttpStatusCode(401);
        $response->setSuccess(false);
        $response->addMessage("Access token expired");
        $response->send();
        exit; 
    }
} catch(PDOException $e) {
    $response = new Response();
    $response->setHttpStatusCode(500);
    $response->setSuccess(false);
    $response->addMessage("There was an issue authenticating - please try again");
    $response->send();
    exit; 
}
// end auth script


if(array_key_exists("seawatchid", $_GET)) {
    $seawatchId = $_GET['taskidseawatchid'];
    if($seawatchId == '' || !is_numeric($seawatchId)){
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage("A sea watch has not been registered");
        $response->send();
        exit;

    }

    if($_SERVER['REQUEST_METHOD'] === 'GET') {

        try{
            $query = $readDB->prepare('select id, title, description, DATE_FORMAT(deadline, "%d/%m/%Y %H:%i") as deadline, completed from tbltasks where id = :taskid and userid = :userid');
            $query->bindParam(':taskid', $taskid, PDO::PARAM_INT);
            $query->bindParam(':userid', $returned_userid, PDO::PARAM_INT);
            $query->execute();

            $rowCount = $query->rowCount();

            if($rowCount === 0){
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("Task not found");
                $response->send();
                exit;
            }

            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $task = new Task($row['id'], $row['title'], $row['description'] ,$row['deadline'], $row['completed']);
                $taskArray[] = $task->returnTaskAsArray();

            }

            $returnData = array();
            $returnData['Rows_returned'] = $rowCount;
            $returnData['tasks'] = $taskArray;

            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnData);
            $response->send();
            exit;
    
        } catch(TaskException $e) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($e->getMessage());
            $response->send();
            exit;
        }
        catch(PDOException $e) {
            error_log("Connection error - ".$e, 0);
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Failed to get task");
            $response->send();
            exit;
        }

    }else if($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        try {
            $query = $writeDB->prepare('delete from tbltasks where id = :taskid and userid = :userid');
            $query->bindParam(':taskid', $taskid, PDO::PARAM_INT);
            $query->bindParam(':userid', $returned_userid, PDO::PARAM_INT);
            $query->execute();

            $rowCount = $query->rowCount();

            if($rowCount === 0){
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("not found");
                $response->send();
                exit;
            }

            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->addMessage("Task deleted");
            $response->send();
            exit;

        } catch(PDOException $e) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Failed to get task");
            $response->send();
            exit;
        }

    }else if($_SERVER['REQUEST_METHOD'] === 'PATCH') {
        
        try {
            if($_SERVER['CONTENT_TYPE'] !== 'application/json'){
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Content type header not set to JSON");
                $response->send();
                exit;
            }

            $rawPatchData = file_get_contents('php://input');

            if(!$jsonData = json_decode($rawPatchData)) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("Request body id not valid JSON");
                $response->send();
                exit;
            }

            $title_updated = false;
            $description_updated = false;
            $deadline_updated = false;
            $completed_updated = false;

            $queryFields = "";

            if(isset($jsonData->title)) {
                $title_updated = true;
                $queryFields .= "title = :title, ";
            }

            if(isset($jsonData->description)) {
                $description_updated = true;
                $queryFields .= "description = :description, ";
            }

            if(isset($jsonData->deadline)) {
                $deadline_updated = true;
                $queryFields .= "deadline = STR_TO_DATE(:deadline, '%d/%m/%Y %H:%i'), ";
            }

            if(isset($jsonData->completed)) {
                $completed_updated = true;
                $queryFields .= "completed = :completed, ";
            }
            
            $queryFields = rtrim($queryFields, ", "); 

            if($title_updated ===false && $description_updated === false && $deadline_updated === false && $completed_updated === false) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("No task fields have beed provided");
                $response->send();
                exit;                
            }

            $query = $writeDB->prepare('select id, title, description, DATE_FORMAT(deadline, "%d/%m/%Y %H:%i") as deadline, completed from tbltasks where id = :taskid and userid = :userid');
            $query->bindParam(':taskid', $taskid, PDO::PARAM_INT);
            $query->bindParam(':userid', $returned_userid, PDO::PARAM_INT);
            $query->execute();

            $rowCount = $query->rowCount();

            if($rowCount === 0){
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("Task not found");
                $response->send();
                exit;
            }

            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                $task = new Task($row['id'], $row['title'], $row['description'] ,$row['deadline'], $row['completed']);

            }

            $queryString = "update tbltasks set ". $queryFields . " where id = :taskid and userid = :userid";
            $query = $writeDB->prepare($queryString);

            if($title_updated === true){
                $task->setTitle($jsonData->title);
                $up_title = $task->getTitle();
                $query->bindParam(':title', $up_title, PDO::PARAM_STR);
            }

            if($description_updated === true){
                $task->setDescription($jsonData->description);
                $up_description = $task->getDescription();
                $query->bindParam(':description', $up_description, PDO::PARAM_STR);
            }

            if($deadline_updated === true){
                $task->setDeadline($jsonData->deadline);
                $up_deadline = $task->getDeadline();
                $query->bindParam(':deadline', $up_deadline, PDO::PARAM_STR);
            }

            if($completed_updated === true){
                $task->setCompleted($jsonData->completed);
                $up_completed = $task->getCompleted();
                $query->bindParam(':completed', $up_completed, PDO::PARAM_STR);
            }

            $query->bindParam(':taskid', $taskid, PDO::PARAM_INT);
            $query->bindParam(':userid', $returned_userid, PDO::PARAM_INT);
            $query->execute();

            $rowCount = $query->rowCount();

            if($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage("task not updated");
                $response->send();
                exit;
            }

            $query = $writeDB->prepare('select id, title, description, DATE_FORMAT(deadline, "%d/%m/%Y %H:%i") as deadline, completed from tbltasks where id = :taskid and userid = :userid');
            $query->bindParam(':taskid', $taskid, PDO::PARAM_INT);
            $query->bindParam(':userid', $returned_userid, PDO::PARAM_INT);
            $query->execute();

            $rowCount = $query->rowCount();

            if($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage("No task found after update");
                $response->send();
                exit;
            }

            $taskArray = array();

            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $task = new Task($row['id'], $row['title'], $row['description'], $row['deadline'], $row['completed']);
                $taskArray[] = $task->returnTaskAsArray();      
            }

            $returnData = array();
            $returnData['rows_returned'] = $rowCount;
            $returnData['tasks'] = $taskArray;

            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->addMessage("task updated");
            $response->setData($returnData);
            $response->send();
            exit;


        } catch(TaskException $e) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage($e->getMessage());
            $response->send();
            exit;

        } catch(PDOException $e) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Failed to update task - " .$e);
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
}elseif(array_key_exists('completed', $_GET)) {

    $completed = $_GET['completed'];

    if($completed !== 'Y' && $completed !== 'N') {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage("Completed filter must be Y or N");
        $response->send();
        exit;
    }

    if($_SERVER['REQUEST_METHOD'] === 'GET') {
    
        try {

            $query = $readDB->prepare('select id, title, description, DATE_FORMAT(deadline, "%d/%m/%Y %H:%i") as deadline, completed from tbltasks where completed = :completed and userid = :userid');
            $query->bindParam(':completed', $completed, PDO::PARAM_STR);
            $query->bindParam(':userid', $returned_userid, PDO::PARAM_INT);
            $query->execute();

            $rowCount = $query->rowCount();
            $taskArray = array();

            while($row = $query->fetch(PDO::FETCH_ASSOC)) {

                $task = new Task($row['id'], $row['title'], $row['description'] ,$row['deadline'], $row['completed']);
                $taskArray[] = $task->returnTaskAsArray();
            }

            $returnData = array();
            $returnData['rows_returned'] = $rowCount;
            $returnData['tasks'] = $taskArray;

            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnData);
            $response->send();
            exit;

        } catch(TaskException $e){
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($e->getMessage());
            $response->send();
            exit;

        }
        catch(PDOException $e){
            error_log("Connection error - ".$e, 0);
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("failed to get tasks");
            $response->send();
            exit;

        }
    } else {
        $response = new Response();
        $response->setHttpStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request Method not allowed");
        $response->send();
        exit;
    }


}elseif(array_key_exists('page', $_GET)){
    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        $page = $_GET['page'];
        if($page ==''  || !is_numeric($page)) {
            $response = new Response();
            $response->setHttpStatusCode(404);
            $response->setSuccess(false);
            $response->addMessage("Page number cannot be blank and must be numeric");
            $response->send();
            exit;
        }

        $limitPerPage = 20;

        try {
            $query = $readDB->prepare('select count(id) as totalNoOfTasks from tbltasks where userid = :userid');
            $query->bindParam(':userid', $returned_userid, PDO::PARAM_INT);
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $taskCount = intval($row['totalNoOfTasks']);
            $numOfPages = ceil($taskCount/$limitPerPage);
            
            if($numOfPages == 0) {
                $numOfPages = 1;
            }

            if($page > $numOfPages  || $page == 0) {
                $response = new Response();
                $response->setHttpStatusCode(404);
                $response->setSuccess(false);
                $response->addMessage('Page not found');
                $response->send();
                exit;
            }

            $offset = ($page == 1 ? 0 : ($limitPerPage*($page-1)));

            $query = $readDB->prepare('select id, title, description, DATE_FORMAT(deadline, "%d/%m/%Y %H:%i") as deadline, completed from tbltasks where userid = :userid limit :pglimit offset :offset');
            $query->bindParam(':userid', $returned_userid, PDO::PARAM_INT);
            $query->bindParam(':pglimit', $limitPerPage, PDO::PARAM_INT);
            $query->bindParam(':offset', $offset, PDO::PARAM_INT);
            $query->execute();

            $rowCount = $query->rowCount();
            $taskArray = array();

            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $task = new Task($row['id'], $row['title'], $row['description'], $row['deadline'], $row['completed']);
                $taskArray[] = $task->returnTaskAsArray();
            }

            $returnData = array();
            $returnData['rows_returned'] = $rowCount;
            $returnData['total_rows'] = $taskCount;
            $returnData['total_pages'] = $numOfPages;
            $returnData['page'] = $page;
            ($page < $numOfPages  ? $returnData['has_next_page'] = true : $returnData['has_next_page'] = false);
            ($page > 1 ? $returnData['has_previous_page'] = true : $returnData['has_previous_page'] = false);
            $returnData['tasks'] = $taskArray;

            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnData);
            $response->send();
            exit;

        } catch(TaskException $e) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($e->getMessage());
            $response->send();
            exit;

        }
        catch(PDOException $e) {
            error_log("Connection error - ".$e, 0);
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("failed to get tasks");
            $response->send();
            exit;
        }

    } else {
        $response = new Response();
        $response->setHttpStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("request method not allowed");
        $response->send();
        exit;
    }
} elseif(empty($_GET)) {

    if($_SERVER['REQUEST_METHOD'] === 'GET') {

        try {
            $query = $readDB->prepare('select id, title, description, DATE_FORMAT(deadline, "%d/%m/%Y %H:%i") as deadline, completed from tbltasks where userid = :userid');
            $query->bindParam(':userid', $returned_userid, PDO::PARAM_INT);
            $query->execute();

            $rowCount = $query->rowCount();

            $taskArray = array();

            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $task = new Task($row['id'], $row['title'], $row['description'] ,$row['deadline'], $row['completed']);
                $taskArray[] = $task->returnTaskAsArray();
            }

            $returnData = array();
            $returnData['rows_returned'] = $rowCount;
            $returnData['tasks'] = $taskArray;

            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(true);
            $response->toCache(true);
            $response->setData($returnData);
            $response->send();
            exit;

        } catch(TaskException $e) {

            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage($e->getMessage());
            $response->send();
            exit;

        } catch(PDOException $e) {
            error_log('Database query error - '. $e, 0);
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Failed to get tasks");
            $response->send();
            exit;

        }

    } elseif($_SERVER['REQUEST_METHOD'] ==='POST'){

        try {

            if($_SERVER['CONTENT_TYPE'] !== 'application/json') {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage('NOT VALID Json Data');
                $response->send();
                exit;
            }
            $rawPostData = file_get_contents('php://input');
            
            if(!$jsonData = json_decode($rawPostData)) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                $response->addMessage('Request body is not valid JSON');
                $response->send();
                exit;
            }

            if(!isset($jsonData->title) || !isset($jsonData->completed)) {
                $response = new Response();
                $response->setHttpStatusCode(400);
                $response->setSuccess(false);
                (!isset($jsonData->title) ? $response->addMessage('Title field is missing and must be provided') : false);
                (!isset($jsonData->completed) ? $response->addMessage('completed status is missing and must be provided') : false);
                $response->send();
                exit;
            }

            $newTask = new Task(null, $jsonData->title, (isset($jsonData->description)) ? $jsonData->description : null, (isset($jsonData->deadline)) ? $jsonData->deadline : null, $jsonData->completed);
            
            $title = $newTask->getTitle();
            $description = $newTask->getDescription();
            $deadline = $newTask->getDeadline();
            $completed = $newTask->getCompleted();

            $query = $writeDB->prepare('insert into tbltasks (title, description, deadline, completed, userid) values (:title, :description, STR_TO_DATE(:deadline, \'%d/%m/%Y %H:%i\'), :completed, :userid)');
            $query->bindParam(':title', $title, PDO::PARAM_STR);
            $query->bindParam(':description', $description, PDO::PARAM_STR);
            $query->bindParam(':deadline', $deadline, PDO::PARAM_STR);
            $query->bindParam(':completed', $completed, PDO::PARAM_STR);
            $query->bindParam(':userid', $returned_userid, PDO::PARAM_INT);
            $query->execute();

            $rowCount = $query->rowCount();
            if($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to create task");
                $response->send();
                exit;
            }

            $lasTaskId = $writeDB->lastInsertId();

            $query = $writeDB->prepare('select id, title, description, DATE_FORMAT(deadline, "%d/%m/%Y %H:%i") as deadline, completed from tbltasks where id = :taskid and userid = :userid');
            $query->bindParam(':taskid', $lasTaskId, PDO::PARAM_INT);
            $query->bindParam(':userid', $returned_userid, PDO::PARAM_INT);
            $query->execute();

            $rowCount = $query->rowCount();

            if($rowCount === 0) {
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to retrieve task creation");
                $response->send();
                exit;
            }

            $taskArray = array();

            while($row =  $query->fetch(PDO::FETCH_ASSOC)) {
                $task = new Task($row['id'], $row['title'], $row['description'], $row['deadline'], $row['completed']);

                $taskArray[] = $task->returnTaskAsArray();

                $returnData = array();
                $returnData['returned_rows'] = $rowCount;
                $returnData['tasks'] = $taskArray;

                $response = new Response();
                $response->setHttpStatusCode(201);  // 201 created
                $response->setSuccess(true);
                $response->setData($returnData);
                $response->send();
                exit;
            }



        } catch(TaskException $e) {

            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage($e->getMessage());
            $response->send();
            exit;

        } catch(PDOException $e) {
            error_log('Database query error - '. $e, 0);
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Failed to create task - check submitted data for errors");
            $response->send();
            exit;
        }

    } else {

        $response = new Response();
        $response->setHttpStatusCode(405);
        $response->setSuccess(false);
        $response->addMessage("Request Method not allowed");
        $response->send();
        exit;

    }
    


} else {

        $response = new Response();
        $response->setHttpStatusCode(404);
        $response->setSuccess(false);
        $response->addMessage("End Point not found");
        $response->send();
        exit;
}

?>
