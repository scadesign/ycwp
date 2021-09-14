<?php
class SightingException extends Exception
{
}

class Sighting
{

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


    public function __construct($id, $firstSeen, $lastSeen, $species, $confidence, $groupSize, $calves, $juveniles, $bearing, $distance, $behaviour, $associatedBirds)
    {
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
    public function getId()
    {
        return $this->_id;
    }

    public function getFirstSeen()
    {
        return $this->_firstSeen;
    }

    public function getLastSeen()
    {
        return $this->_lastSeen;
    }

    public function getSpecies()
    {
        return $this->_species;
    }

    public function getConfidence()
    {
        return $this->_confidence;
    }

    public function getGroupSize()
    {
        return $this->_groupSize;
    }

    public function getCalves()
    {
        return $this->_calves;
    }

    public function getJuveniles()
    {
        return $this->_juveniless;
    }

    public function getBearing()
    {
        return $this->_bearing;
    }

    public function getDistance()
    {
        return $this->_distance;
    }

    public function getBehaviour()
    {
        return $this->_behaviour;
    }

    public function getAssociatedBirds()
    {
        return $this->_associatedBirds;
    }


    //setters
    public function setId($id)
    {

        if (($id !== null) && (!is_numeric($id) || $id <= 0 || $id > 9223372036854775807 || $this->_id !== null)) {
            throw new SightingException("Sightings id error");
        }

        $this->_id = $id;
    }



    public function setFirstSeen($firstSeen)
    {

        if (($firstSeen !== null) &&  date_format(date_create_from_format('H:i', $firstSeen), 'H:i') != $firstSeen) {
            throw new SightingException("Sightings start time datetime error");
        }

        $this->_firstSeen = $firstSeen;
    }

    public function setLastSeen($lastSeen)
    {

        if (($lastSeen !== null) &&  date_format(date_create_from_format('H:i', $lastSeen), 'H:i') != $lastSeen) {
            throw new SightingException("Sightings end time datetime error");
        }

        $this->_lastSeen = $lastSeen;
    }

    public function setSpecies($species)
    {

        if (strlen($species < 0) || strlen($species) > 255) {
            throw new SightingException("sightings species error");
        }

        $this->_species = $species;
    }

    public function setConfidence($confidence)
    {

        if (strlen($confidence < 0) || strlen($confidence) > 255) {
            throw new SightingException("sightings confidence error");
        }

        $this->_confidence = $confidence;
    }

    public function setGroupSize($groupSize)
    {

        if (($groupSize !== null) && (!is_numeric($groupSize) || $groupSize <= 0 || $groupSize > 9223372036854775807 || $this->_groupSize !== null)) {
            throw new SightingException("group size error");
        }

        $this->_groupSize = $groupSize;
    }

    public function setCalves($calves)
    {

        if (($calves !== null) && (!is_numeric($calves) || $calves > 9223372036854775807 || $this->_calves !== null)) {
            throw new SightingException("Number of calves error");
        }

        $this->_calves = $calves;
    }

    public function setJuveniles($juveniles)
    {

        if (($juveniles !== null) && (!is_numeric($juveniles) || $juveniles < 0 || $juveniles > 9223372036854775807 || $this->_juveniless !== null)) {
            throw new SightingException("Number of juveniles error");
        }

        $this->_juveniless = $juveniles;
    }

    public function setBearing($bearing)
    {

        if (strlen($bearing < 0) || strlen($bearing) > 30) {
            throw new SightingException("sightings bearing error");
        }

        $this->_bearing = $bearing;
    }

    public function setDistance($distance)
    {

        if (($distance !== null) && (!is_numeric($distance) || $distance < 0 || $distance > 9223372036854775807 || $this->_distance !== null)) {
            throw new SightingException("distance from station error");
        }

        $this->_distance = $distance;
    }

    public function setBehaviour($behaviour)
    {

        if (strlen($behaviour < 0) || strlen($behaviour) > 255) {
            throw new SightingException("sightings behaviour error");
        }

        $this->_behaviour = $behaviour;
    }

    public function setAssociatedBirds($associatedBirds)
    {

        if (($associatedBirds !== null) && (strlen($associatedBirds) > 16777215)) {
            throw new SightingException("task description error");
        }

        $this->_associatedBirds = $associatedBirds;
    }



    public function returnSightingAsArray()
    {
        $sighting = array();
        $sighting['id'] = $this->getId();
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
