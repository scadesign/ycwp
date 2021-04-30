<?php
class VolunteerException extends Exception {}

class Volunteer {

    private $_id;
    private $_firstName;
    private $_lastName;
    private $_email;
    private $_phone;
    private $_password;


    public function __construct($id, $firstName, $lastName, $email, $phone, $password ){
        $this->setId($id);
        $this->setFirstName($firstName);
        $this->setlastName($lastName);
        $this->setemail($volunteer);
        $this->setPhone($phone);
        $this->setPassword($password);
    }

    //getters
    public function getId() {
        return $this->_id ;
    }

    public function getFirstName() {
        return $this->_firstName ;
    }

    public function getLastName() {
        return $this->_lastName ;
    }

    public function getEmail() {
        return $this->_email ;
    }

    public function getPhone() {
        return $this->_phone ;
    }
    public function getPassword() {
        return $this->_password ;
    }

    

    //setters
    public function setId($id) {

        if(($id !== null) && (!is_numeric($id) || $id <= 0 || $id > 9223372036854775807 || $this->_id !== null)) {
            throw new VolunteerchException("volunteer ID error");
        }

        $this->_id = $id;
    }

    public function setFirstName($firstName) {

        if(($firstName !== null) && (!is_numeric($firstName) || $firstName <= 0 || $firstName > 9223372036854775807 || $this->_firstName !== null)) {
            throw new VolunteerException("invalid first name");
        }

        $this->_firstName = $firstName;
    }

    public function setLastName($lastName) {

        if(($lastName !== null) && (!preg_match('/^[0-9]{2}:[0-9]{2}$', $lastName)  || $this->_lastName !== null)) {
            throw new VolunteerException("start time error");
        }

        $this->_lastName = $lastName;
    }

    public function setEmail($email) {

        if(($email !== null) && (!preg_match('/^[0-9]{2}:[0-9]{2}$', $email) || $this->_email !== null)) {
            throw new VolunteerException("invalid email");
        }

        $this->_email = $email;
    }

    public function setPhone($phone) {

        if(($phone !== null) && (!preg_match('/^[0-9]{2}:[0-9]{2}$', $phone) || $this->_phone !== null)) {
            throw new VolunteerException("invalid email");
        }

        $this->_phone = $phone;
    }
    public function setPassword($password) {

        if(($password !== null) && (!preg_match('/^[0-9]{2}:[0-9]{2}$', $password) || $this->_password !== null)) {
            throw new VolunteerException("invalid email");
        }

        $this->_password = $password;
    }


    public function returnVolunteerAsArray() {
        $volunteer = array();
        $volunteervolunteer['id'] = $this->getId();
        $volunteer['first_name'] = $this->getFirstName();
        $volunteer['last_name'] = $this->getLastName();
        $volunteer['email'] = $this->getEmail();
        $volunteer['phone'] = $this->getPhone();
        $volunteer['password'] = $this->getPassword();
        

        return $volunteer;
    }


}

?>