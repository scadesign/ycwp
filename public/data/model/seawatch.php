<?php
class SeaWatchException extends Exception {}

class SeaWatch {

    private $_id;
    private $_date;
    private $_station;
    private $_volunteer;


    public function __construct($id, $date, $station, $volunteer ){
        $this->setId($id);
        $this->setDate($date);
        $this->setStation($station);
        $this->setVolunteer($volunteer);
    }

    //getters
    public function getId() {
        return $this->_id ;
    }

    public function getDate() {
        return $this->_date ;
    }

    public function getStation() {
        return $this->_station ;
    }

    public function getVolunteer() {
        return $this->_volunteer ;
    }

    

    //setters
    public function setId($id) {

        if(($id !== null) && (!is_numeric($id) || $id <= 0 || $id > 9223372036854775807 || $this->_id !== null)) {
            throw new SeaWatchException("seawatch ID error");
        }

        $this->_id = $id;
    }

    public function setDate($date) {

        if(($date !== null) && (!is_numeric($date) || $date <= 0 || $date > 9223372036854775807 || $this->_date !== null)) {
            throw new SeaWatchException("invalid date");
        }

        $this->_date = $date;
    }

    public function setStation($station) {

        if(($station !== null) && (!preg_match('/^[0-9]{2}:[0-9]{2}$', $station)  || $this->_station !== null)) {
            throw new SeaWatchException("start time error");
        }

        $this->_station = $station;
    }

    public function setVolunteer($volunteer) {

        if(($volunteer !== null) && (!preg_match('/^[0-9]{2}:[0-9]{2}$', $volunteer) || $this->_volunteer !== null)) {
            throw new EnvironmentException("end time error");
        }

        $this->_volunteer = $volunteer;
    }


    public function returnSeaWatchAsArray() {
        $seaWatch = array();
        $seaWatch['id'] = $this->getId();
        $seaWatch['date'] = $this->getDate();
        $seaWatch['station'] = $this->getStation();
        

        return $seaWatch;
    }


}

?>