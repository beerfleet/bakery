<?php

namespace broodjes2\TeLaet\Service;

/**
 * Service
 *
 * @author jan van biervliet
 */
abstract class Service {

  private $em;
  private $app;

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

}
