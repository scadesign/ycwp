<?php
class SightingException extends Exception {}

class Sighting {

    private $_id;
    private $_firstSeen;
    private $_lastSeen;
    private $_species;
    private $_confidence;
    private $_groupSize;
    private $_calves;
    private $_juveniless;
    private $_bearing;
    private $_distance;
    private $_behaviour;
    private $_associatedBirds;


    public function __construct($id, $firstSeen, $lastSeen, $species, $confidence, $groupSize, $calves, $juveniles, $bearing, $distance, $behaviour, $associatedBirds ){
        $this->setId($id);
        $this->setFirstSeen($firstSeen);
        $this->setLastSeen($lastSeen);
        $this->setSpecies($species);
        $this->setConfidence($confidence);
        $this->setGroupSize($groupSize);
        $this->setCalves($calves);
        $this->setJuveniles($juveniles);
        $this->setBearing($bearing);
        $this->setDistance($distance);
        $this->setBehaviour($behaviour);
        $this->setAssociatedBirds($associatedBirds);

    }

    //getters
    public function getId() {
        return $this->_id ;
    }

    public function getFirstSeen() {
        return $this->_firstSeen ;
    }

    public function getLastSeen() {
        return $this->_lastSeen ;
    }

    public function getSpecies() {
        return $this->_species ;
    }

    public function getConfidence() {
        return $this->_confidence ;
    }

    public function getGroupSize() {
        return $this->_groupSize ;
    }

    public function getCalves() {
        return $this->_calves ;
    }

    public function getJuveniles() {
        return $this->_juveniless ;
    }

    public function getBearing() {
        return $this->_bearing ;
    }
   
    public function getDistance() {
        return $this->_distance ;
    }

    public function getBehaviour() {
        return $this->_behaviour ;
    }

    public function getAssociatedBirds() {
        return $this->_associatedBirds ;
    }


    //setters
    public function setId($id) {

        if(($id !== null) && (!is_numeric($id) || $id <= 0 || $id > 9223372036854775807 || $this->_id !== null)) {
            throw new SightingException("Sightings id error");
        }

        $this->_id = $id;
    }

   

    public function setFirstSeen($firstSeen) {

        if(($firstSeen !== null) && (!preg_match('/^[0-9]{2}:[0-9]{2}$', $firstSeen)  || $this->_firstSeen !== null)) {
            throw new SightingException("First seen time error");
        }

        $this->_firstSeen = $firstSeen;
    }

    public function setLastSeen($lastSeen) {

        if(($lastSeen !== null) && (!preg_match('/^[0-9]{2}:[0-9]{2}$', $lastSeen) || $this->_lastSeen !== null)) {
            throw new SightingException("Last seen time error");
        }

        $this->_lastSeen = $lastSeen;
    }

    public function setSpecies($species) {

        if(($species !== null) && (!is_numeric($species) || $species <= 0 || $species > 9223372036854775807 || $this->_species !== null)) {
            throw new SightingException("species id type error");
        }

        $this->_species = $species;
    }

    public function setConfidence($confidence) {

        if(($confidence !== null) && (!is_numeric($confidence) || $confidence <= 0 || $confidence > 9223372036854775807 || $this->_confidence !== null)) {
            throw new SightingException("confidence id error error");
        }

        $this->_confidencet = $confidence;
    }

    public function setGroupSize($groupSize) {

        if(($groupSize !== null) && (!is_numeric($groupSize) || $groupSize <= 0 || $groupSize > 9223372036854775807 || $this->_groupSize !== null)) {
            throw new SightingException("group size error");
        }

        $this->_groupSize = $groupSize;
    }

    public function setCalves($calves) {

        if(($calves !== null) && (!is_numeric($calves) || $calves <= 0 || $calves > 9223372036854775807 || $this->_calves !== null)) {
            throw new SightingException("Number of calves error");
        }

        $this->_calves = $calves;
    }

    public function setJuveniles($juveniles) {

        if(($juveniles !== null) && (!is_numeric($juveniles) || $juveniles <= 0 || $juveniles > 9223372036854775807 || $this->_juveniles !== null)) {
            throw new SightingException("Number of juveniles error");
        }

        $this->_juveniles = $juveniles;
    }

    public function setBearing($bearing) {

        if(($bearing !== null) && (!is_numeric($bearing) || $bearing <= 0 || $bearing > 9223372036854775807 || $this->_bearings !== null)) {
            throw new SightingException("bearing direction error");
        }

        $this->_bearing = $bearing;
    }

    public function setDistance($distance) {

        if(($distance !== null) && (!is_numeric($distance) || $distances <= 0 || $distance > 9223372036854775807 || $this->_distance !== null)) {
            throw new SightingException("distance from station error");
        }

        $this->_distance = $distance;
    }

    public function setBehaviour($behaviour) {

        if(($behaviour !== null) && (!is_numeric($behaviour) || $behaviour <= 0 || $behaviour > 9223372036854775807 || $this->_behaviour !== null)) {
            throw new SightingException("Behaviour error");
        }

        $this->_behaviour = $behaviour;
    }

    public function setAssociatedBirds($associatedBirds) {

        if(($associatedBirds !== null) && (!is_numeric($associatedBirds) || $associatedBirds <= 0 || $associatedBirds > 9223372036854775807 || $this->_associatedBirds !== null)) {
            throw new SightingException("Associated Birds error");
        }

        $this->_associatedBirds = $associatedBirds;
    }



    public function returnSightingAsArray() {
        $sighting = array();
        $sighting['id'] = $this->getId();
        $sighting['sea_watch'] = $this->getseaWatch();
        $sighting['first_seen'] = $this->getFirstSeen();
        $sighting['last_seen'] = $this->getLastSeen();
        $sighting['species'] = $this->getSpecies();
        $sighting['confidence'] = $this->getConfidence();
        $sighting['group_size'] = $this->getGroupSize();
        $sighting['calves'] = $this->getCalves();
        $sighting['juveniles'] = $this->getJuveniles();
        $sighting['bearing'] = $this->getBearing();
        $sighting['distance'] = $this->getDistance();
        $sighting['behaviour'] = $this->getBehaviour();
        $sighting['associated_birds'] = $this->getAssociatedBirds();
        

        return $sighting;
    }
}

?>