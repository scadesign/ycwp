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


// check the volunteer has aready signed in
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

    $query = $dB->prepare('select volunteer, accesstokenexpiry from sessions, volunteer where sessions.volunteer = volunteer.id and accesstoken = :accesstoken');
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
    $returned_accesstokenexpiry = $row['accesstokenexpiry'];
    
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

if($_SERVER['REQUEST_METHOD'] ==='POST'){

    try {
        // check there is data and it is valid json
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

        if(!isset($jsonData->station)) {
            $response = new Response();
            $response->setHttpStatusCode(400);
            $response->setSuccess(false);
            $response->addMessage('Sea watch station is missing');
            $response->send();
            exit;
        }
        //insert the seawatch into the sewatch table
        $newSeawatch = new SeaWatch(null, trim($jsonData->date), trim($jsonData->station), trim($returned_userid));
        
        $date = $newSeawatch->getDate();
        $station = $newSeawatch->getStation();
        $volunteer = $newSeawatch->getVolunteer();

        $query = $dB->prepare('insert into seawatch (date, station, volunteer) values (STR_TO_DATE(:date, \'%d/%m/%Y %H:%i\'), :station, :volunteer)');
        $query->bindParam(':date', $date, PDO::PARAM_STR);
        $query->bindParam(':station', $station, PDO::PARAM_STR);
        $query->bindParam(':volunteer', $volunteer, PDO::PARAM_STR);
        $query->execute();

        //check the insertion went ok and send message if not
        $rowCount = $query->rowCount();
        if($rowCount === 0) {
            $response = new Response();
            $response->setHttpStatusCode(500);
            $response->setSuccess(false);
            $response->addMessage("Failed to create SeaWatch");
            $response->send();
            exit;
        }
        // get the seawatch id to include in the environment and sightings table
        $lastSeawatchId = $writeDB->lastInsertId();

        // insert the environmental data

        $environment = $jsonData->environment;
        // itterate through all the environment datas and insert into the envirnment table
        for($i=0; $i < sizeOf($environment); $i++){
    
           

            try {
                //get the data
                $start = trim($environment[$i]['start']);
                $end = trim($environment[$i]['end']);
                $seaState = trim($environment[$i]['seastate']);
                $swellHeight = trim($environment[$i]['swellheight']);
                $windDirection = trim($environment[$i]['windDirection']);
                $visibility = trim($environment[$i]['visibility']);
                $notes = trim($environment[$i]['notes']);

                $env = new Environment(null, $start, $end, $seaState, $swellHeight, $windDirection, $visibility, $notes);

                // select the ids for the selections
                $query = $dB->prepare('select sea_state.id as sea_state, swell_height.id as swell_height, wind_direction.id as wind_direction, visibility.id as visibility from sea_state, swell_height, wind_direction, visibility 
                                    where sea_state.state = :seastate AND swell_height.type = :swellheight AND wind_direction.abbreviation = :winddirection AND visibilty.distance = :distance');
                $query->bindParam(':seastate', $env->getSeaState(), PDO::PARAM_STR);
                $query->bindParam(':swellheight', $env->getSwellHeight(), PDO::PARAM_STR);
                $query->bindParam(':winddirection', $env->getWindDirection(), PDO::PARAM_STR);
                $query->bindParam(':visibility', $env->getVisibility(), PDO::PARAM_STR);
                $query->execute();
        
                $rowCount = $query->rowCount();
                // if the volunteer is not found
                if($rowCount === 0) {
                    $response = new Response();
                    $response->setHttpStatusCode(401);
                    $response->setSuccess(false);
                    $response->addMessage("There was an issue matching some environment elements");  // generic error message
                    $response->send();
                    exit; 
                }
        
                $row = $query->fetch(PDO::FETCH_ASSOC);
                // assign the ids for insertion
                $seaWatch_id =  $lastSeawatchId;
                $returned_sea_state = $row['sea_state'];
                $returned_swell_height = $row['swell_height'];
                $returned_wind_direction = $row['wind_direction'];
                $returned_visibility = $row['visibility'];

                $query = $dB->prepare('insert into environment (seawatch, start, end, sea_state, swell_height, wind_direction, visibility, additional_notes) 
                                        values (:seawatch, STR_TO_DATE(:start, \'%H:%i\'), STR_TO_DATE(:end, \'%H:%i\'), :seastate, :swellheight, :winddirection, :visibility, :notes )');
                $query->bindParam(':seawatch', $seawatch_id, PDO::PARAM_INT);
                $query->bindParam(':start', $env->getStart(), PDO::PARAM_STR);
                $query->bindParam(':end', $env->getEnd(), PDO::PARAM_STR);
                $query->bindParam(':seastate', $returned_sea_state, PDO::PARAM_INT);
                $query->bindParam(':swellheight', $returned_swell_height, PDO::PARAM_INT);
                $query->bindParam(':winddirection', $returned_wind_direction, PDO::PARAM_INT);
                $query->bindParam(':visibility', $returned_visibility, PDO::PARAM_INT);
                $query->bindParam(':notes', $env->getNotes(), PDO::PARAM_STR);
                $query->execute();

                //check the insertion went ok and send message if not
                $rowCount = $query->rowCount();
                if($rowCount === 0) {
                    $response = new Response();
                    $response->setHttpStatusCode(500);
                    $response->setSuccess(false);
                    $response->addMessage("Failed to add Some environment data");
                    $response->send();
                    exit;
                }

            } catch(PDOException $e) {
                $response = new Response();
                $response->setHttpStatusCode(500);
                $response->setSuccess(false);
                $response->addMessage("There was an issue matching some data");
                $response->send();
                exit; 
            }
            
        }


        // add any sightings
        $sightings = $jsonData->sightings;
        // itterate through all the environment datas and insert into the envirnment table
        if(!empty($sightings)) {
        for($i=0; $i < sizeOf($sightings); $i++){
            try {
                //get the data
                $firstSeen = trim($sightings[$i]['first_seen']);
                $lastSeen = trim($sightings[$i]['last_seen']);
                $species = trim($sightings[$i]['species']);
                $confidence = trim($sightings[$i]['confidence']);
                $groupSize = trim($sightings[$i]['group_size']);
                $calves = trim($sightings[$i]['calves']);
                $juveniles = trim($sightings[$i]['juveniles']);
                $bearing = trim($sightings[$i]['bearing']);
                $distance = trim($sightings[$i]['distance']);
                $behaviour = trim($sightings[$i]['behaviour']);
                $associatedBirds = trim($sightings[$i]['associated_birds']);

                // create a new sighting object
                $sighting = new Sighting(null, $firstSeen, $lastSeen, $species, $confidence, $groupSize, $calves, $juveniles, $bearing, $distance, $behaviour, $associatedBirds);

                // select the ids for the selections
                $query = $dB->prepare('select species.id as species, confidence.id as confidence, wind_direction.id as bearing, behaviour.id as behaviour from species, confidence, wind_direction, behaviour 
                                    where species.species = :species AND confidence.name = :confidence AND wind_direction.abbreviation = :bearing AND behaviour.behaviour = :behaviour');
                $query->bindParam(':species', $sighting->getSpecies(), PDO::PARAM_STR);
                $query->bindParam(':confidence', $sighting->getCalves(), PDO::PARAM_STR);
                $query->bindParam(':bearing', $sighting->getBearing(), PDO::PARAM_STR);
                $query->bindParam(':behaviour', $sighting->getBehaviour(), PDO::PARAM_STR);
                $query->execute();
        
                $rowCount = $query->rowCount();
                // if the volunteer is not found
                if($rowCount === 0) {
                    $response = new Response();
                    $response->setHttpStatusCode(401);
                    $response->setSuccess(false);
                    $response->addMessage("There was an issue matching some sightings elements");  // generic error message
                    $response->send();
                    exit; 
                }

               
        
                $row = $query->fetch(PDO::FETCH_ASSOC);
                // assign the returned id's
                $seaWatch_id =  $lastSeawatchId;
                $returned_species_id = $row['species'];
                $returned_confidence_id = $row['confidence'];
                $returned_bearing_id = $row['bearing'];
                $returned_behaviour_id = $row['behaviour'];

                $query = $dB->prepare('insert into sighting (seawatch, first_seen, last_seen, species, confidence, group_size, calves, juveniles, bearing, distance, behaviour, associated_birds) 
                                        values (:seawatch, STR_TO_DATE(:firstseen, \'%H:%i\'), STR_TO_DATE(:lastseen, \'%H:%i\'), :species, :confidence, :groupsize, :calves, :juveniles, :bearing, :distance, : behaviour, :associatedbirds )');
                $query->bindParam(':seawatch', $seawatch_id, PDO::PARAM_INT);
                $query->bindParam(':firstseen', $sighting->getFirstSeen(), PDO::PARAM_STR);
                $query->bindParam(':lastseen', $sighting->getLastSeen(), PDO::PARAM_STR);
                $query->bindParam(':species', $returned_species_id, PDO::PARAM_INT);
                $query->bindParam(':confidence', $returned_confidence_id, PDO::PARAM_INT);
                $query->bindParam(':groupsize', $sighting->getGroupSize(), PDO::PARAM_INT);
                $query->bindParam(':calves', $sighting->getCalves(), PDO::PARAM_INT);
                $query->bindParam(':juveniles', $sighting->getJuveniles(), PDO::PARAM_INT);
                $query->bindParam(':bearing', $returned_bearing_id, PDO::PARAM_INT);
                $query->bindParam(':distance', $sighting->getDistance(), PDO::PARAM_INT);
                $query->bindParam(':behaviour', $returned_behaviour_id, PDO::PARAM_INT);
                $query->bindParam(':associatedbirds', $sighting->getAssociatedBirds(), PDO::PARAM_STR);
                
                $query->execute();

                //check the insertion went ok and send message if not
                $rowCount = $query->rowCount();
                if($rowCount === 0) {
                    $response = new Response();
                    $response->setHttpStatusCode(500);
                    $response->setSuccess(false);
                    $response->addMessage("Failed to add some sightings data");
                    $response->send();
                    exit;
                }

            } catch(PDOException $e) {
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
        $response->setSuccess(false);
        $response->addMessage("SeaWatch submitted successfully");
        $response->send();
        exit; 
        
    }


    } catch(SeaWatchException $e) {

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
        $response->addMessage("Failed to create the sea watch - check submitted data for errors");
        $response->send();
        exit;
    }


} else if($_SERVER['REQUEST_METHOD'] ==='GET') {
// collect the sightings to show on a map

/**
 * get all the stations
 * for each station select the species for every sighting for that station
 * return a count of the number of sightings for each station
 * return a count for each species sighting foreach station
 */
    $stations=array();
    // get the stations
    $query = $dB->prepare('select station.id, station.name, station.latitude, station.longitude from station');
    $query->execute();

    //check a selection was made
    $stationCount = $query->rowCount();
    if($stationCount === 0) {
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("Failed to select sea watch stations");
        $response->send();
        exit;
    }
    $theStations = $query->fetch(PDO::FETCH_ASSOC);

    // get the species
    $query = $dB->prepare('select species.id, species.species from species');
    $query->execute();

    //check a selection was made
    $speciesCount = $query->rowCount();
    if($speciesCount === 0) {
        $response = new Response();
        $response->setHttpStatusCode(500);
        $response->setSuccess(false);
        $response->addMessage("Failed to select species");
        $response->send();
        exit;
    }
    $species = $query->fetch(PDO::FETCH_ASSOC);

    


} else {

    $response = new Response();
    $response->setHttpStatusCode(405);
    $response->setSuccess(false);
    $response->addMessage("Request Method not allowed");
    $response->send();
    exit;

}
?>
