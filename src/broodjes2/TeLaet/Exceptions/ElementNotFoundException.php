<?php

namespace broodjes2\TeLaet\Exceptions;

use Exception;

/**
 * Description of ElementNotFoundException
 *
 * @author jan van biervliet
 */
class ElementNotFoundException extends Exception {

  public function __construct($element, $code = null, $previous = null) {
    $message = "$element not found";
    parent::__construct($message, $code, $previous);
  }

}
