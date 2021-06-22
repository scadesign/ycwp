<?php
class EnvironmentException extends Exception
{
}

class Environment
{

    private $_id;
    private $_start;
    private $_end;
    private $_seaState;
    private $_swellHeight;
    private $_windDirection;
    private $_visibility;
    private $_notes;


    public function __construct($id,  $start, $end, $seaState, $swellHeight, $windDirection, $visibility, $notes)
    {
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
    public function getId()
    {
        return $this->_id;
    }

    public function getStart()
    {
        return $this->_start;
    }

    public function getEnd()
    {
        return $this->_end;
    }

    public function getSeaState()
    {
        return $this->_seaState;
    }

    public function getSwellHeight()
    {
        return $this->_swellHeight;
    }

    public function getWindDirection()
    {
        return $this->_windDirection;
    }

    public function getVisibility()
    {
        return $this->_visibility;
    }

    public function getNotes()
    {
        return $this->_notes;
    }



    //setters
    public function setId($id)
    {

        if (($id !== null) && (!is_numeric($id) || $id <= 0 || $id > 9223372036854775807 || $this->_seaWatch !== null)) {
            throw new EnvironmentException("environment ID error");
        }

        $this->_id = $id;
    }


    public function setStart($start)
    {

        if (($start !== null) &&  date_format(date_create_from_format('H:i', $start), 'H:i') != $start) {
            throw new EnvironmentException("environment start error");
        }

        $this->_start = $start;
    }

    public function setEnd($end)
    {

        if (($end !== null) &&  date_format(date_create_from_format('H:i', $end), 'H:i') != $end) {
            throw new EnvironmentException("environment end error");
        }

        $this->_end = $end;
    }

    public function setSeaState($seaState)
    {

        if (strlen($seaState < 0) || strlen($seaState) > 60) {
            throw new EnvironmentException("environment sea state error");
        }

        $this->_seaState = $seaState;
    }

    public function setSwellHeight($swellHeight)
    {

        if (strlen($swellHeight < 0) || strlen($swellHeight) > 10) {
            throw new EnvironmentException("environment seaswell height error");
        }

        $this->_swellHeight = $swellHeight;
    }

    public function setWindDirection($windDirection)
    {

        if (strlen($windDirection < 0) || strlen($windDirection) > 5) {
            throw new EnvironmentException("environment wind direction error");
        }

        $this->_windDirection = $windDirection;
    }

    public function setVisibility($visibilty)
    {

        if (strlen($visibilty < 0) || strlen($visibilty) > 10) {
            throw new EnvironmentException("environment visibility error");
        }

        $this->_visibility = $visibilty;
    }

    public function setNotes($notes)
    {


        if (($notes !== null) && (strlen($notes) > 16777215)) {
            throw new EnvironmentException("task description error");
        }

        $this->_notes = $notes;
    }



    public function returnEnvironmentAsArray()
    {
        $environment = array();
        $environment['id'] = $this->getId();
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
