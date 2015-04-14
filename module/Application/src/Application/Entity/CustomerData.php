<?php
namespace Application\Entity;


class CustomerData {
  private $fullName;
  private $address1;
  private $address2;
  private $city;
  private $state;
  private $zip;
  private $phone;
  private $email;


  /**
   * @return mixed
   */
  public function getEmail() {
    return $this->email;
  }


  /**
   * @param mixed $email
   */
  public function setEmail($email) {
    $this->email = $email;
  }


  /**
   * @return mixed
   */
  public function getPhone() {
    return $this->phone;
  }


  /**
   * @param mixed $phone
   */
  public function setPhone($phone) {
    $this->phone = $phone;
  }


  /**
   * @return mixed
   */
  public function getZip() {
    return $this->zip;
  }


  /**
   * @param mixed $zip
   */
  public function setZip($zip) {
    $this->zip = $zip;
  }


  /**
   * @return mixed
   */
  public function getState() {
    return $this->state;
  }


  /**
   * @param mixed $state
   */
  public function setState($state) {
    $this->state = $state;
  }


  /**
   * @return mixed
   */
  public function getCity() {
    return $this->city;
  }


  /**
   * @param mixed $city
   */
  public function setCity($city) {
    $this->city = $city;
  }


  /**
   * @return mixed
   */
  public function getAddress2() {
    return $this->address2;
  }


  /**
   * @param mixed $address2
   */
  public function setAddress2($address2) {
    $this->address2 = $address2;
  }


  /**
   * @return mixed
   */
  public function getAddress1() {
    return $this->address1;
  }


  /**
   * @param mixed $address1
   */
  public function setAddress1($address1) {
    $this->address1 = $address1;
  }


  /**
   * @return mixed
   */
  public function getFullName() {
    return $this->fullName;
  }


  /**
   * @param mixed $fullName
   */
  public function setFullName($fullName) {
    $this->fullName = $fullName;
  }

}
