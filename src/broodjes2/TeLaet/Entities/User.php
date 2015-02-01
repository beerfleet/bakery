<?php

namespace broodjes2\TeLaet\Entities;

use broodjes2\TeLaet\Entities\Postcode;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User entity
 *
 * @author jan van biervliet
 */
class User {

  private $id;
  private $username;
  private $email;
  private $enabled;
  private $password;
  private $last_logon;
  private $first_name;
  private $surname;
  /* @var $postcode Postcode */
  private $postcode;
  private $address;
  private $is_admin;
  private $password_token;

  function __construct() {
    $this->user_likes = new ArrayCollection();
    $this->comments = new ArrayCollection();
    $this->present_in_events = new ArrayCollection();
    $this->whisky_likes = new ArrayCollection();
    $this->whiskys_created = new ArrayCollection();
    $this->events_created = new ArrayCollection();
    
  }

  function getId() {
    return $this->id;
  }

  function getUsername() {
    return $this->username;
  }

  function getEmail() {
    return $this->email;
  }

  function isEnabled() {
    return $this->enabled == 1;
  }

  function getPassword() {
    return $this->password;
  }

  function getLastLogon() {
    return $this->last_logon;
  }

  function getFirstName() {
    return $this->first_name;
  }

  function getSurname() {
    return $this->surname;
  }

  function getPostcode() {
    return $this->postcode;
  }

  function getAddress() {
    return $this->address;
  }

  function getPasswordToken() {
    return $this->password_token;
  }

  function isAdmin() {
    return $this->is_admin == 1;
  }
  
  function setId($id) {
    $this->id = $id;
  }

  function setUsername($username) {
    $this->username = $username;
  }

  function setEmail($email) {
    $this->email = $email;
  }

  function setEnabled($enabled) {
    $this->enabled = $enabled;
  }

  function setPassword($password) {
    $this->password = $password;
  }

  function setLastLogon($last_logon) {
    $this->last_logon = $last_logon;
  }

  function setFirstName($first_name) {
    $this->first_name = $first_name;
  }

  function setSurname($surname) {
    $this->surname = $surname;
  }

  function setPostcode(Postcode $postcode) {
    $this->postcode = $postcode;
  }

  function setAddress($address) {
    $this->address = $address;
  }

  function setAdmin($is_admin) {
    $this->is_admin = $is_admin;
  }

  function resetPasswordToken() {
    $this->password_token = null;
  }

  function setPasswordToken() {
    $this->password_token = bin2hex(mcrypt_create_iv(128, MCRYPT_DEV_RANDOM));
  }

}