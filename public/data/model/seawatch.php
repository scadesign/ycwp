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

        if(($date !== null) &&  date_format(date_create_from_format('d/m/Y H:i', $date), 'd/m/Y H:i') != $date) {
            throw new SeawatchException("task datetime error");
        }

        $this->_date = $date;
    
    }

    public function setStation($station) {

        if(strlen($station <0) || strlen($station) > 255) {
            throw new SeawatchException("Seawatch Station error");
        }
        $this->_station = $station;
    }

    public function setVolunteer($volunteer) {

        if(($volunteer !== null) && (!is_numeric($volunteer) || $volunteer <= 0 || $volunteer > 9223372036854775807 || $this->_volunteer !== null)) {
            throw new SeaWatchException("Volunteer error");
        }

        $this->_volunteer = $volunteer;
    }


    public function returnSeaWatchAsArray() {
        $seaWatch = array();
        $seaWatch['id'] = $this->getId();
        $seaWatch['date'] = $this->getDate();
        $seaWatch['station'] = $this->getStation();
        $seawatch['volunteer'] = $this->getVolunteer();
        

        return $seaWatch;
    }


}

?>