<?php

namespace broodjes2\TeLaet\Exceptions;

use broodjes2\TeLaet\Exceptions\ImageException;

/**
 * Description of NoImageSelectedException
 *
 * @author jan van biervliet
 */
class ImageTooLargeException extends ImageException {

  public function __construct($message) {
    parent::__construct($message);
  }

}
