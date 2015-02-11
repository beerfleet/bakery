<?php

namespace broodjes2\TeLaet\Exceptions;

/**
 * Description of AlreadyAddedExc eption
 *
 * @author jan van biervliet
 */
class AlreadyAddedException extends \Exception {

  public function __construct($message, $code = null, $previous = null) {
    parent::__construct($message, $code, $previous);
  }

}
