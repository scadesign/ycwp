<?php

// ini_set('display_errors',1);
// ini_set('display_startup_errors',1);
// error_reporting(-1);


/*
creates the arrays to populate the drop down lists
*/

require_once('db.php');
require_once('../model/Response.php');

try {
    $dB = dB::connectdB();
} catch (PDOException $e) {
    error_log("Connection error - " . $e, 0);
    $response = new Response();
    $response->setHttpStatusCode(500);
    $response->setSuccess(false);
    $response->addMessage("database connection error");
    $response->send();
    exit;
}

if (array_key_exists("task", $_GET)) {
    $task = $_GET['task'];
    if ($task == '' || is_numeric($task)) {
        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage("Task cannot be blank or numeric");
        $response->send();
        exit;
    }
    //check its a GET request as no other method is allowed
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // create the selection based on the task description
        if ($task == 'wind-direction') {

            try {
                $query = $dB->prepare('select id, abbreviation from wind_direction');

                $query->execute();

                $rowCount = $query->rowCount();

                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $wind = array($row['id'], $row['abbreviation']);
                    $taskArray[] = $wind;
                }
            } catch (Exception $e) {
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage($e->getMessage());
                $response->send();
                exit;
            } catch (PDOException $e) {
                error_log("Connection error - " . $e, 0);
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to retrieve task");
                $response->send();
                exit;
            }
        } else if ($task == 'visibility') {
            try {
                $query = $dB->prepare('select id, distance from visibility');

                $query->execute();

                $rowCount = $query->rowCount();

                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $visibility = array($row['id'], $row['distance']);
                    $taskArray[] = $visibility;
                }
            } catch (Exception $e) {
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage($e->getMessage());
                $response->send();
                exit;
            } catch (PDOException $e) {
                error_log("Connection error - " . $e, 0);
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to retieve visibilty types");
                $response->send();
                exit;
            }
        } else if ($task == 'swell-height') {
            try {
                $query = $dB->prepare('select id, type, height from swell_height');

                $query->execute();

                $rowCount = $query->rowCount();

                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $swell = array($row['id'], $row['type'], $row['height']);
                    $taskArray[] = $swell;
                }
            } catch (Exception $e) {
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage($e->getMessage());
                $response->send();
                exit;
            } catch (PDOException $e) {
                error_log("Connection error - " . $e, 0);
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to retieve swell information");
                $response->send();
                exit;
            }
        } else if ($task == 'station') {
            try {
                $query = $dB->prepare('select id, name from station');

                $query->execute();

                $rowCount = $query->rowCount();

                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $station = array($row['id'], $row['name']);
                    $taskArray[] = $station;
                }
            } catch (Exception $e) {
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage($e->getMessage());
                $response->send();
                exit;
            } catch (PDOException $e) {
                error_log("Connection error - " . $e, 0);
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to retrieve stations");
                $response->send();
                exit;
            }
        } else if ($task == 'species') {
            try {
                $query = $dB->prepare('select id, species from species');

                $query->execute();

                $rowCount = $query->rowCount();

                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $species = array($row['id'], $row['species']);
                    $taskArray[] = $species;
                }
            } catch (Exception $e) {
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage($e->getMessage());
                $response->send();
                exit;
            } catch (PDOException $e) {
                error_log("Connection error - " . $e, 0);
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to retieve species");
                $response->send();
                exit;
            }
        } else if ($task == 'sea-state') {
            try {
                $query = $dB->prepare('select id, state, description from sea_state');

                $query->execute();

                $rowCount = $query->rowCount();

                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $seaState = array($row['id'], $row['state'], $row['description']);
                    $taskArray[] = $seaState;
                }
            } catch (Exception $e) {
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage($e->getMessage());
                $response->send();
                exit;
            } catch (PDOException $e) {
                error_log("Connection error - " . $e, 0);
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to retieve sea states");
                $response->send();
                exit;
            }
        } else if ($task == 'confidence') {
            try {
                $query = $dB->prepare('select id, name from confidence');

                $query->execute();

                $rowCount = $query->rowCount();

                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $confidence = array($row['id'], $row['name']);
                    $taskArray[] = $confidence;
                }
            } catch (Exception $e) {
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage($e->getMessage());
                $response->send();
                exit;
            } catch (PDOException $e) {
                error_log("Connection error - " . $e, 0);
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to retieve confidence types");
                $response->send();
                exit;
            }
        } else if ($task == 'behaviour') {
            try {
                $query = $dB->prepare('select id, behaviour from behaviour');

                $query->execute();

                $rowCount = $query->rowCount();

                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $behaviour = array($row['id'], $row['behaiour']);
                    $taskArray[] = $behaviour;
                }
            } catch (Exception $e) {
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage($e->getMessage());
                $response->send();
                exit;
            } catch (PDOException $e) {
                error_log("Connection error - " . $e, 0);
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("Failed to retrieve behaviours");
                $response->send();
                exit;
            }
        } else {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage("failed to get end point");
            $response->send();
            exit;
        }

        $returnData = array();
        $returnData['Rows_returned'] = $rowCount;
        $returnData['task'] = $taskArray;

        $response = new Response();
        $response->setHttpStatusCode(200);
        $response->setSuccess(true);
        $response->toCache(true);
        $response->setData($returnData);
        $response->send();
        exit;
    }
}
