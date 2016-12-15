<?php
namespace PhpDavSvn;

use PhpDavSvn\Svn\SvnRepositoryFactory;

class Svn{
  private $svn_repo = false;
  public function __construct($svn_url, $user=null, $pass=null){
    $this->svn_repo = SvnRepositoryFactory::getSvnRepository($svn_url);
    if(isset($user)){
      $this->svn_repo->setUsername($user);
    }
    if(isset($pass)){
      $this->svn_repo->setPassword($user);
    }
  }

  public function attemptConnect(){
    $this->svn_repo->attemptConnect();
  }


  public function logReport($from = 1, $to = 'HEAD', $orderBy='DESC'){
    return $this->svn_repo->logReport($from, $to, $orderBy);
  }
}
