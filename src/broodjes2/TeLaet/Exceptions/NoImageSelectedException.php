<?php

namespace broodjes2\TeLaet\Exceptions;

use broodjes2\TeLaet\Exceptions\ImageException;

/**
 * Description of NoImageSelectedException
 *
 * @author jan van biervliet
 */
class NoImageSelectedException extends ImageException {

  public function __construct($message = "No image selected") {
    parent::__construct($message);
  }

}
