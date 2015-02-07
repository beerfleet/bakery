<?php

namespace broodjes2\TeLaet\Repository;

use Doctrine\ORM\EntityRepository;
use broodjes2\TeLaet\Entities\User;
use broodjes2\TeLaet\Entities\Constants\Entities;

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
  
  public function isEmailTaken($id, $email) {
    $em = $this->getEntityManager();
    $qb = $em->createQueryBuilder('u');
    $dql = $qb->select('u')
        ->from('broodjes2\TeLaet\Entities\User', 'u')
        ->where('u.id != :id')
        ->andWhere('u.email = :email')
        ->setParameter('id', $id)
        ->setParameter('email', $email);
    $query = $dql->getQuery();
    return count($query->execute()) < 1;
  }
  
}
