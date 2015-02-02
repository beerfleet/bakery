<?php

namespace broodjes2\TeLaet\Service\Admin;

use broodjes2\TeLaet\Entities\Constants\Entities;
use Doctrine\ORM\EntityRepository;

/**
 * AdminService
 *
 * @author jan van biervliet
 */
class AdminService {
  
  private $em;
  
  public function __construct($em) {
    $this->em = $em;
  }
  
  public function provideAllAdminData() {
    $vars = array();
    $vars['breads'] = $this->provideBreads();
    
    return $vars;
  }
  
  public function provideBreads() {    
    $em = $this->em;
    /* @var $repo EntityRepository */
    $repo = $em->getReposiTory(Entities::BREAD);
    $breads = $repo->findBy(array(), array('name' => 'ASC'));
    return $breads;
  }
  
}
