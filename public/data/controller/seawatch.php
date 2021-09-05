<?php

require_once('db.php');
require_once('../model/Response.php');
require_once('../model/Environment.php');
require_once('../model/SeaWatch.php');
require_once('../model/Sighting.php');
require_once('../model/Volunteer.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

try {
    $dB = DB::connectDB();
} catch (PDOException $e) {
    error_log("Connection error - " . $e, 0);
    $response = new Response();
    $response->setHttpStatusCode(500);
    $response->setSuccess(false);
    $response->addMessage("database connection error");
    $response->send();
    exit;
}




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // check the volunteer has aready signed in
    if (!isset($_SERVER['HTTP_AUTHORIZATION']) || strlen($_SERVER['HTTP_AUTHORIZATION']) < 1) {
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

        $query = $dB->prepare('select volunteer, access_expiry from sessions, volunteer where sessions.volunteer = volunteer.id and access_token = :accesstoken');
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
        $returned_userid = $row['volunteer'];
        $returned_accesstokenexpiry = $row['access_expiry'];

        if (strtotime($returned_accesstokenexpiry) < time()) {
            $response = new Response();
            $response->setHttpStatusCode(401);
            $response->setSuccess(false);
            $response->addMessage("Access token expired");
            $response->send();
            exit;
        }
    } catch (PDOException $e) {
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("There was an issue authenticating - please try again" . $e);
        $response->send();
        exit;
    }
    // end auth script

    try {
        // check there is data and it is valid json
        if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage('NOT VALID Json Data');
            $response->send();
            exit;
        }
        $rawPostData = file_get_contents('php://input');

        if (!$jsonData = json_decode($rawPostData)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage('Request body is not valid JSON');
            $response->send();
            exit;
        }

        if (!isset($jsonData->station)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage('Sea watch station is missing');
            $response->send();
            exit;
        }
        //insert the seawatch into the sewatch table
        $newSeawatch = new SeaWatch(null, null, trim($jsonData->station), trim($returned_userid));

        $date = trim($jsonData->date);
        $station = $newSeawatch->getStation();
        $volunteer = $newSeawatch->getVolunteer();

        $stationquery = $dB->prepare('select station.id from  station where station.name = :name');
        $stationquery->bindParam(':name', $station);
        $stationquery->execute();
        $row = $stationquery->fetch(PDO::FETCH_ASSOC);
        $returned_station = $row['id'];

        $query = $dB->prepare('insert into seawatch (date, station, volunteer) values (STR_TO_DATE(:date, \'%d/%m/%Y %H:%i\'), :station, :volunteer)');
        $query->bindParam(':date', $date, PDO::PARAM_STR);
        $query->bindParam(':station', $returned_station, PDO::PARAM_INT);
        $query->bindParam(':volunteer', $volunteer, PDO::PARAM_INT);
        $query->execute();

        //check the insertion went ok and send message if not
        $rowCount = $query->rowCount();
        if ($rowCount === 0) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Failed to create SeaWatch");
            $response->send();
            exit;
        }
        // get the seawatch id to include in the environment and sightings table
        $lastSeawatchId = $dB->lastInsertId();

        // insert the environmental data

        $environment = (array) $jsonData->environment;
        $envLength = count($environment);
        // itterate through all the environment datas and insert into the envirnment table
        for ($i = 0; $i < $envLength; $i++) {


            try {
                //get the data
                $env = (array) $environment[$i];
                $start = trim($env['start']);
                $end = trim($env['end']);
                $seaState = trim($env['seaState']);
                $swellHeight = trim($env['swellHeight']);
                $windDirection = trim($env['windDirection']);
                $visibility = trim($env['visibility']);
                $notes = trim($env['notes']);

                $env = new Environment(null, $start, $end, $seaState, $swellHeight, $windDirection, $visibility, $notes);
                $returnedSeaState = $env->getSeaState();
                $returnedSwellHeight = $env->getSwellHeight();
                $returnedWindDirection = $env->getWindDirection();
                $returnedVisibilty = $env->getVisibility();
                $returnedStart = $env->getStart();
                $returnedEnd = $env->getEnd();
                $returnedNotes = $env->getNotes();

                // select the ids for the selections
                $query = $dB->prepare('select sea_state.id as sea_state, swell_height.id as swell_height, wind_direction.id as wind_direction, visibility.id as visibility from sea_state, swell_height, wind_direction, visibility 
                                    where sea_state.state = :seastate AND swell_height.height = :swellheight AND wind_direction.abbreviation = :winddirection AND visibility.distance = :visibility');
                $query->bindParam(':seastate', $returnedSeaState, PDO::PARAM_STR);
                $query->bindParam(':swellheight', $returnedSwellHeight, PDO::PARAM_STR);
                $query->bindParam(':winddirection', $returnedWindDirection, PDO::PARAM_STR);
                $query->bindParam(':visibility', $returnedVisibilty, PDO::PARAM_STR);
                $query->execute();

                $rowCount = $query->rowCount();
                // if an insert is not made
                if ($rowCount === 0) {
                    $response = new Response();
                    $response->setHttpStatusCode(401);
                    $response->setSuccess(false);
                    $response->addMessage("There was an issue matching some environment elements");  // generic error message
                    $response->send();
                    exit;
                }

                $row = $query->fetch(PDO::FETCH_ASSOC);
                // assign the ids for insertion
                $returned_sea_state = $row['sea_state'];
                $returned_swell_height = $row['swell_height'];
                $returned_wind_direction = $row['wind_direction'];
                $returned_visibility = $row['visibility'];

                $query = $dB->prepare('insert into environment (seawatch, start, end, sea_state, swell_height, wind_direction, visibility, additional_notes) 
                                        values (:seawatch, STR_TO_DATE(:start, \'%H:%i\'), STR_TO_DATE(:end, \'%H:%i\'), :seastate, :swellheight, :winddirection, :visibility, :notes )');
                $query->bindParam(':seawatch', $lastSeawatchId, PDO::PARAM_INT);
                $query->bindParam(':start', $returnedStart, PDO::PARAM_STR);
                $query->bindParam(':end', $returnedEnd, PDO::PARAM_STR);
                $query->bindParam(':seastate', $returned_sea_state, PDO::PARAM_INT);
                $query->bindParam(':swellheight', $returned_swell_height, PDO::PARAM_INT);
                $query->bindParam(':winddirection', $returned_wind_direction, PDO::PARAM_INT);
                $query->bindParam(':visibility', $returned_visibility, PDO::PARAM_INT);
                $query->bindParam(':notes', $returnedNotes, PDO::PARAM_STR);
                $query->execute();

                //check the insertion went ok and send message if not
                $rowCount = $query->rowCount();
                if ($rowCount === 0) {
                    $response = new Response();
                    $response->setHttpStatusCode(500);
                    $response->setSuccess(false);
                    $response->addMessage("Failed to add Some environment data");
                    $response->send();
                    exit;
                }
            } catch (PDOException $e) {
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("There was an issue matching some data" . $e);
                $response->send();
                exit;
            }
        }


        // add any sightings
        $sightings = $jsonData->sightings;
        $sightingsLength = count($sightings);
        // itterate through all the environment datas and insert into the envirnment table
        if ($sightingsLength > 0) {
            for ($i = 0; $i < $sightingsLength; $i++) {
                try {
                    //get the data
                    $sighting = (array) $sightings[$i];
                    $firstSeen = trim($sighting['firstSeen']);
                    $lastSeen = trim($sighting['lastSeen']);
                    $species = trim($sighting['species']);
                    $confidence = trim($sighting['confidence']);
                    $groupSize = trim($sighting['groupSize']);
                    $calves = trim($sighting['calves']);
                    $juveniles = trim($sighting['juveniles']);
                    $bearing = trim($sighting['bearing']);
                    $distance = trim($sighting['distance']);
                    $behaviour = trim($sighting['behaviour']);
                    $associatedBirds = trim($sighting['associatedBirds']);

                    // create a new sighting object
                    $sightingModel = new Sighting(null, $firstSeen, $lastSeen, $species, $confidence, $groupSize, $calves, $juveniles, $bearing, $distance, $behaviour, $associatedBirds);

                    $returnedFirstSeen = $sightingModel->getFirstSeen();

                    $returnedLastSeen = $sightingModel->getLastSeen();

                    $returnedSpecies = $sightingModel->getSpecies();
                    $returnedConfidence = $sightingModel->getConfidence();
                    $returnedGroupSize = $sightingModel->getGroupSize();
                    $returnedCalves = $sightingModel->getCalves();
                    $returnedjuveniles = $sightingModel->getJuveniles();
                    $returnedBearing = $sightingModel->getBearing();
                    $returnedDistance = $sightingModel->getDistance();
                    $returnedBehaviour = $sightingModel->getBehaviour();
                    $returnedAsscBirds = $sightingModel->getAssociatedBirds();

                    // select the ids for the selections
                    $query = $dB->prepare('select species.id as species, confidence.id as confidence, wind_direction.id as bearing, behaviour.id as behaviour from species, confidence, wind_direction, behaviour 
                                    where species.species = :species AND confidence.name = :confidence AND wind_direction.abbreviation = :bearing AND behaviour.behaviour = :behaviour');
                    $query->bindParam(':species', $returnedSpecies, PDO::PARAM_STR);
                    $query->bindParam(':confidence', $returnedConfidence, PDO::PARAM_STR);
                    $query->bindParam(':bearing', $returnedBearing, PDO::PARAM_STR);
                    $query->bindParam(':behaviour', $returnedBehaviour, PDO::PARAM_STR);
                    $query->execute();

                    $rowCount = $query->rowCount();
                    // if an insertion wasn't made
                    if ($rowCount === 0) {
                        $response = new Response();
                        $response->setHttpStatusCode(401);
                        $response->setSuccess(false);
                        $response->addMessage("There was an issue matching some sightings elements");  // generic error message
                        $response->send();
                        exit;
                    }



                    $row = $query->fetch(PDO::FETCH_ASSOC);
                    // assign the returned id's
                    $returned_species_id = $row['species'];
                    $returned_confidence_id = $row['confidence'];
                    $returned_bearing_id = $row['bearing'];
                    $returned_behaviour_id = $row['behaviour'];



                    $query = $dB->prepare('insert into sighting (seawatch, first_seen, last_seen, species, confidence, group_size, calves, juveniles, bearing, distance, behaviour, associated_birds) 
                                        values (:seawatch, STR_TO_DATE(:firstseen, \'%H:%i\'), STR_TO_DATE(:lastseen, \'%H:%i\'), :species, :confidence, :groupsize, :calves, :juveniles, :bearing, :distance, :behaviour, :associatedbirds )');
                    $query->bindParam(':seawatch', $lastSeawatchId, PDO::PARAM_INT);
                    $query->bindParam(':firstseen', $returnedFirstSeen, PDO::PARAM_STR);
                    $query->bindParam(':lastseen', $returnedLastSeen, PDO::PARAM_STR);
                    $query->bindParam(':species', $returned_species_id, PDO::PARAM_INT);
                    $query->bindParam(':confidence', $returned_confidence_id, PDO::PARAM_INT);
                    $query->bindParam(':groupsize', $returnedGroupSize, PDO::PARAM_INT);
                    $query->bindParam(':calves', $returnedCalves, PDO::PARAM_INT);
                    $query->bindParam(':juveniles', $returnedjuveniles, PDO::PARAM_INT);
                    $query->bindParam(':bearing', $returned_bearing_id, PDO::PARAM_INT);
                    $query->bindParam(':distance', $returnedDistance, PDO::PARAM_INT);
                    $query->bindParam(':behaviour', $returned_behaviour_id, PDO::PARAM_INT);
                    $query->bindParam(':associatedbirds', $returnedAsscBirds, PDO::PARAM_STR);

                    $query->execute();

                    //check the insertion went ok and send message if not
                    $rowCount = $query->rowCount();
                    if ($rowCount === 0) {
                        $response = new Response();
                        $response->setHttpStatusCode(500);
                        $response->setSuccess(false);
                        $response->addMessage("Failed to add some sightings data");
                        $response->send();
                        exit;
                    }
                    //delete the session
                    $query = $dB->prepare('delete from tblsessions where accesstoken = :accesstoken');
                    $query->bindParam('accesstoken', $access_token, PDO::PARAM_STR);
                    $query->execute();
                } catch (PDOException $e) {
                    $response = new Response();
                    $response->setHttpStatusCode(500);
                    $response->setSuccess(false);
                    $response->addMessage("There was an issue matching some sightings data");
                    $response->send();
                    exit;
                }
            }

            // create and send a reponse that evrything went ok
            $response = new Response();
            $response->setHttpStatusCode(200);
            $response->setSuccess(True);
            $response->addMessage("SeaWatch submitted successfully");
            $response->send();
            exit;
        }
    } catch (SeaWatchException $e) {

        $response = new Response();
        $response->setHttpStatusCode(400);
        $response->setSuccess(false);
        $response->addMessage($e->getMessage());
        $response->send();
        exit;
    } catch (PDOException $e) {
        error_log('Database query error - ' . $e, 0);
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage($e);
        $response->send();
        exit;
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    /*
    get the total sightings for each station to display on a map
    if filter exist for species type get the total sightings for the filtered species only
   */
    $stations = array();
    // get the stations
    $query = $dB->prepare('select station.id, station.name, station.latitude, station.longitude from station');
    $query->execute();

    //check a selection was made
    $stationCount = $query->rowCount();
    if ($stationCount === 0) {
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("Failed to select sea watch stations");
        $response->send();
        exit;
    }


    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $stationId = $row['id'];

        $stationQuery = $dB->prepare('select sighting.id from sighting where sighting.station = :station');
        $stationQuery->bindParam(':station', $stationId, PDO::PARAM_INT);
        $stationQuery->execute();


        $station = array();
        $station['id'] = $row['id'];
        $station['name'] = $row['name'];
        $station['latitude'] = $row['latitude'];
        $station['longitude'] = $row['longitude'];
        $station['total'] = $stationQuery->rowCount();

        $stations[] = $station;
    }
    $response = new Response();
    $response->setHttpStatusCode(200);
    $response->setSuccess(True);
    $response->addMessage("SeaWatch submitted successfully");
    $response->setdata($stations);
    $response->send();
    exit;
} else {

    $response = new Response();
    $response->setHttpStatusCode(405);
    $response->setSuccess(false);
    $response->addMessage("Request Method not allowed");
    $response->send();
    exit;
}
