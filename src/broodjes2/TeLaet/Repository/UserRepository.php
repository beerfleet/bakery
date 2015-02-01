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
  
}
