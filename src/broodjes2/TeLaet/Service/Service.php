<?php

namespace broodjes2\TeLaet\Service;

/**
 * Service
 *
 * @author jan van biervliet
 */
abstract class Service {

  private $em;  

  function __construct($em) {
    $this->em = $em;
  }
  
  public function getEntityManager() {
    return $this->em;;
  }
  
  public function store($object) {
    $this->em->persist($object);
    $this->em->flush();
  }
  
  public function getRepo($entity) {
    $em = $this->getEntityManager();
    return $em->getRepository($entity);
  }

}
