<?php

namespace broodjes2\TeLaet\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Description of UserRepository
 *
 * @author jan van biervliet
 */
class UserRepository extends EntityRepository {
  
  public function findUserByToken($token) {
    return $this->findOneBy(array('password_token' => $token));
  }
  
  public function findUserByResetToken($token) {
    return $this->findOneBy(array('reset_token' => $token));
  }
  
  public function findByUsername($username) {
    return $this->findOneBy(array('username' => $username));
  }
  
}
