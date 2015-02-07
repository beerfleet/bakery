<?php

namespace broodjes2\TeLaet\Service\Admin;

use broodjes2\TeLaet\Entities\Constants\Entities;
use Doctrine\ORM\EntityRepository;
use broodjes2\TeLaet\Entities\Constants\Files;
use broodjes2\TeLaet\Exceptions\AccessDeniedException;

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
  
  private function queryUserByUserName($username) {    
    $em = $this->em;
    $repo = $em->getRepository(Entities::USER);
    $user = $repo->findBy(array('username' => $username));
    return $user;
  }

  public function getActiveUser() {
    if (isset($_SESSION['user']) && $_SESSION['user'] != null ) {
      $user = $this->queryUserByUserName($_SESSION['user']);
      return isset($user[0]) ? $user[0] : null;
    }
    return null;
  }
  
  public function isUserAnonymous() {
    return !$this->isLoggedIn();
  }
  
  public function isUserLoggedIn() {
    return $this->getUser() != null;
  }

  public function isUserAdmin() {
    if (isset($_SESSION['user'])) {
      $user = $this->getActiveUser();
      return $user->isAdmin() == 1 ? true : false;
    }
    return false;
  }

  public function provideAllAdminData() {
    $vars = array();
    $vars['breads'] = $this->provideBreads();
    $vars['toppings'] = $this->provideToppings();
    return $vars;
  }

  public function provideBreads() {
    $em = $this->em;
    /* @var $repo EntityRepository */
    $repo = $em->getReposiTory(Entities::BREAD);
    $breads = $repo->findBy(array(), array('name' => 'ASC'));
    return $breads;
  }

  public function provideToppings() {
    $em = $this->em;
    /* @var $repo EntityRepository */
    $repo = $em->getReposiTory(Entities::TOPPING);
    $toppings = $repo->findBy(array(), array('name' => 'ASC'));
    return $toppings;
  }
  
  public function getBreadImageFileInfos() {
    return $this->getFileInfos(Files::BREAD_IMG_DIR);
  }
  
  public function getToppingImageFileInfos() {
    return $this->getFileInfos(Files::TOPPING_IMG_DIR);
  }

  public function getFileInfos($path) {
    if (!$this->isUserAdmin()) {
      throw new AccessDeniedException();
    }
    $info = array();
    $infos = array();
    
    if ($handle = opendir($path)) {
      while (false !== ($file = readdir($handle))) {
        if ('.' === $file)
          continue;
        if ('..' === $file)
          continue;

        // do sum w file
        $info = array();
        $fullPath = $path . '/' . $file;
        $name = pathinfo($fullPath);
        $info['name'] = $name['filename'];
        $info['extension'] = $name['extension'];
        $info['fullname'] = $name['filename'] . '.' . $name['extension'];
        $info['size'] = filesize($fullPath);
        
        $infos[] = $info;
      }
      closedir($handle);
    }
    return($infos);
  }

}
