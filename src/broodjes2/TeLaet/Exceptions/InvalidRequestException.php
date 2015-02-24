<?php

namespace broodjes2\TeLaet\Exceptions;

use Exception;

/**
 * Description of InvalidRequestException
 *
 * @author jan van biervliet
 */
class InvalidRequestException extends Exception {

  public function __construct() {
    parent::__construct("Invalid request.");
  }

}
