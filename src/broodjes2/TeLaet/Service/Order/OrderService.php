<?php

namespace broodjes2\TeLaet\Service\Order;

use broodjes2\TeLaet\Service\Service;
use broodjes2\TeLaet\Service\Bread\BreadService;
use broodjes2\TeLaet\Entities\Constants\Entities;

/**
 * Description of OrderService
 *
 * @author jan van biervliet
 */
class OrderService extends Service {

  public function __construct($em) {
    parent::__construct($em);
  }
  
  public function fetchBreadsOrdered() {
    $repo = $this->getRepo(Entities::BREAD);
    return $repo->findBy(array(), array('name' => 'ASC'));
  }

}
