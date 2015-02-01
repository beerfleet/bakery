<?php

namespace broodjes2\TeLaet\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Postcode repository
 *
 * @author jan van biervliet
 */
class PostcodeRepository extends EntityRepository {
  
  public function findAllOrderPostcodes() {
    return $this->findBy(array(), array('postcode' => 'ASC'));
  }
  
}
