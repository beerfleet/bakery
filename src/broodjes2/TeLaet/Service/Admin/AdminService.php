<?php

namespace broodjes2\TeLaet\Service\Admin;

use broodjes2\TeLaet\Entities\Constants\Entities;
use broodjes2\TeLaet\Entities\Constants\Files;
use Doctrine\ORM\EntityRepository;
use broodjes2\TeLaet\Exceptions\AccessDeniedException;
use broodjes2\TeLaet\Exceptions\ImageException;
use broodjes2\TeLaet\Exceptions\NoImageSelectedException;
use broodjes2\TeLaet\Exceptions\ImageTooLargeException;
use broodjes2\TeLaet\Exceptions\ImageUploadException;
use broodjes2\TeLaet\Exceptions\FileTypeException;
use finfo;

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
    if (isset($_SESSION['user']) && $_SESSION['user'] != null) {
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

  
  public function check_image($file) {
    $message = 'Error uploading file';        
    
    if(!isset($_FILES[$file])) {
      throw new ImageException('An undefined error occurred. The image might be WAY too large');
    }
    
    switch ($_FILES[$file]['error']) {
      case UPLOAD_ERR_OK:
        $message = false;
        break;
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        $message .= ' - file too large (limit of ' . Files::MAX_SIZE . ' bytes).';        
        throw new ImageTooLargeException($message);
        break;
      case UPLOAD_ERR_PARTIAL:
        $message .= ' - file upload was not completed.';
        throw new ImageUploadException($message);
        break;
      case UPLOAD_ERR_NO_FILE:
        $message .= ' - zero-length file uploaded.';
        throw new NoImageSelectedException($message);
        break;
      default:
        $message .= ' - internal error #' . $_FILES['newfile']['error'];
        throw new ImageException($message);
        break;
    }
    
    if ($_FILES[$file]['size'] > Files::MAX_SIZE) {
      $message .= ' - file too large (limit of ' . Files::MAX_SIZE . ' bytes).'; 
      throw new ImageTooLargeException($message);
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES[$file]['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ),
        true
    )) {
      throw new FileTypeException("Only jpg, jpeg and png files allowed.");
    }
  }

  public function uploadBreadImage($file) {
    $this->check_image($file);
    $target_dir = Files::IMG_DIR . "Breads/";
    $destination = $target_dir . basename($_FILES[$file]["name"]);
    if (move_uploaded_file($_FILES[$file]['tmp_name'], $destination)) {
      return true;
    } else {
      throw new ImageException("Error uploading image.");
    }
  }
  
  public function uploadToppingImage($file) {
    $this->check_image($file);
    $target_dir = Files::IMG_DIR . "Toppings/";
    $destination = $target_dir . basename($_FILES[$file]["name"]);
    if (move_uploaded_file($_FILES[$file]['tmp_name'], $destination)) {
      return true;
    } else {
      throw new ImageException("Error uploading image.");
    }
  }
  
  public function deleteImage($full_path) {     
    if (file_exists($full_path)) {      
      unlink($full_path);
    } else {
      throw new ImageException("File does not exist");
    }
  }
  
  public function deleteBreadImage($filename) {
    $path_to_file = Files::BREAD_IMG_DIR . '/' . $filename;
    $this->deleteImage($path_to_file);
  }
  
  public function deleteToppingImage($filename) {
    $path_to_file = Files::TOPPING_IMG_DIR . '/' . $filename;
    $this->deleteImage($path_to_file);
  }

}
