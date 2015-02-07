<?php

namespace broodjes2\TeLaet\Exceptions;

use Exception;

/**
 * Description of AccessDeniedException
 *
 * @author jan van biervliet
 */
class AccessDeniedException extends Exception {

  public function __construct() {
    parent::__construct("Unauthorized action.");
  }

}
