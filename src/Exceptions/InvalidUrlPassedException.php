<?php
namespace PhpDavSvn\Exceptions;

class InvalidUrlPassedException extends \Exception implements SvnException{
  public function __construct($message, $code = 0, Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }

  const MALFORMED_URL = 1;
  const UNRECOGNIZED_PROTOCOL = 2;
  const NO_PROTOCOL = 4;
  const NO_HOST = 8;
  const NO_HOST_OR_PROTOCOL = 12;
}
