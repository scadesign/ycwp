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
            throw new VolunteerException("volunteer ID error");
        }

        $this->_id = $id;
    }

    public function setFirstName($firstName) {

        if(strlen($firstName <0) || strlen($firstName) > 255) {
            throw new VolunteerException("volunteer first name error");
        }

        $this->_firstName = $firstName;
    }

    public function setLastName($lastName) {

        if(strlen($lastName <0) || strlen($lastName) > 255) {
            throw new VolunteerException("volunteer last name error");
        }

        $this->_lastName = $lastName;
    }

    public function setEmail($email) {

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new VolunteerException("volunteer email error");
        }

        $this->_email = $email;
    }

    public function setPhone($phone) {

        if(strlen($phone <0) || strlen($phone) > 255) {
            throw new VolunteerException("volunteer last name error");
        }  // check this one

        $this->_phone = $phone;
    }
    public function setPassword($password) {

        if(strlen($password <0) || strlen($password) > 255) {
            throw new VolunteerException("volunteer password error");
        }  // 

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