<?php

namespace broodjes2\TeLaet\Exceptions;

use Exception;

/**
 * Description of ImageException
 *
 * @author jan van biervliet
 */
class ImageException extends Exception {

  public function __construct($message) {
    parent::__construct($message);
  }

}
