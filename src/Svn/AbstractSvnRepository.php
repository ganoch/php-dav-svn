<?php

namespace PhpDavSvn\Svn;

abstract class AbstractSvnRepository{
  protected $_user;
  protected $_pass;
  public function setUsername($user){
    $this->_user = $user;
  }

  public function getUsername(){
    return $this->_user;
  }

  public function setPassword($pass){
    $this->_pass = $pass;
  }

  public function getPassword(){
    return $this->_pass;
  }
  abstract public function attemptConnect(); //Throws SvnException
  abstract public function logReport($from = 1, $to = 'HEAD', $orderBy='ASC'); //Throws SvnException
}
