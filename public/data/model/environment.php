<?php
class EnvironmentException extends Exception {}

class Environment {

    private $_id;
    private $_start;
    private $_end;
    private $_seaState;
    private $_swellHeight;
    private $_windDirection;
    private $_visibility;
    private $_notes;


    public function __construct($id,  $start, $end, $seaState, $swellHeight, $windDirection, $visibility, $notes ){
        $this->setId($id);
        $this->setStart($start);
        $this->setEnd($end);
        $this->setSeaState($seaState);
        $this->setSwellHeight($swellHeight);
        $this->setWindDirection($windDirection);
        $this->setVisibility($visibility);
        $this->setNotes($notes);
    }

    //getters
    public function getId() {
        return $this->_id ;
    }

    public function getStart() {
        return $this->_start ;
    }

    public function getEnd() {
        return $this->_end ;
    }

    public function getSeaState() {
        return $this->_seaState ;
    }

    public function getSwellHeight() {
        return $this->_swellHeight ;
    }

    public function getWindDirection() {
        return $this->_windDirection ;
    }

    public function getVisibility() {
        return $this->_visibility ;
    }

    public function getNotes() {
        return $this->_notes ;
    }
   


    //setters
    public function setId($id) {

        if(($id !== null) && (!is_numeric($id) || $id <= 0 || $id > 9223372036854775807 || $this->_seaWatch !== null)) {
            throw new EnvironmentException("environment ID error");
        }

        $this->_id = $id;
    }


    public function setStart($start) {

        if(($start !== null) && (!preg_match('/^[0-9]{2}:[0-9]{2}$', $start)  || $this->_start !== null)) {
            throw new EnvironmentException("start time error");
        }

        $this->_start = $start;
    }

    public function setEnd($end) {

        if(($end !== null) && (!preg_match('/^[0-9]{2}:[0-9]{2}$', $end) || $this->_end !== null)) {
            throw new EnvironmentException("end time error");
        }

        $this->_end = $end;
    }

    public function setSeaState($seaState) {

        if(($seaState !== null) && (!is_numeric($seaState) || $seaState <= 0 || $seaState > 9223372036854775807 || $this->_seaSState !== null)) {
            throw new EnvironmentException("sea state error");
        }

        $this->_seaState = $seaState;
    }

    public function setSwellHeight($swellHeight) {

        if(($swellHeight !== null) && (!is_numeric($swellHeight) || $swellHeight <= 0 || $swellHeight > 9223372036854775807 || $this->_swellHeight !== null)) {
            throw new EnvironmentException("swell height error");
        }

        $this->_swellHeight = $swellHeight;
    }

    public function setWindDirection($windDirection) {

        if(($windDirection !== null) && (!is_numeric($windDirection) || $windDirection <= 0 || $windDirection > 9223372036854775807 || $this->_windDirection !== null)) {
            throw new EnvironmentException("wind direction error");
        }

        $this->_windDirection = $windDirection;
    }

    public function setVisibility($visibilty) {

        if(($visibilty !== null) && (!is_numeric($visibilty) || $visibilty <= 0 || $visibilty > 9223372036854775807 || $this->_visibilty !== null)) {
            throw new EnvironmentException("visibility error");
        }

        $this->_visibility = $visibilty;
    }

    public function setNotes($notes) {

        if(($notes !== null) && (!is_numeric($notes) || $notes <= 0 || $notes > 9223372036854775807 || $this->_notes !== null)) {
            throw new EnvironmentException("notes error");
        }

        $this->_notes = $notes;
    }



    public function returnEnvironmentAsArray() {
        $environment = array();
        $environment['id'] = $this->getId();
        $environment['seawatch'] = $this->getSeaWatch();
        $environment['start'] = $this->getStart();
        $environment['end'] = $this->getEnd();
        $environment['sea_state'] = $this->getSeaState();
        $environment['swell_height'] = $this->getSwellHeight();
        $environment['wind_direction'] = $this->getWindDirection();
        $environment['visibilty'] = $this->getVisibility();
        $environment['notes'] = $this->getNotes();
        

        return $environment;
    }


}

?>