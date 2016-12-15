<?php
namespace PhpDavSvn\Exceptions;

class SecurityException extends \Exception implements SvnException{
  public function __construct($message = 'SVN. no authorized', $code = 0, \Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}
