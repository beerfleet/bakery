<?php

namespace broodjes2\TeLaet\Service\Admin;

use broodjes2\TeLaet\Entities\Constants\Entities;
use Doctrine\ORM\EntityRepository;
use broodjes2\TeLaet\Entities\Constants\Files;

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
