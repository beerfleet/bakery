<?php

namespace broodjes2\TeLaet\Exceptions;

use Exception;

/**
 * Description of ElementNotFoundException
 *
 * @author jan van biervliet
 */
class ElementNotFoundException extends Exception {

  public function __construct($code = null, $previous = null) {
    $message = "Element not found";
    parent::__construct($message, $code, $previous);
  }

}
