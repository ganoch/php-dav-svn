<?php
namespace PhpDavSvn\Svn;

use PhpDavSvn\Exceptions\InvalidUrlPassedException;

class SvnRepositoryFactory{
  public static function getSvnRepository($url){
    $params = parse_url($url);

    if(!$params){
      throw new InvalidUrlPassedException('malformed repository url', InvalidUrlPassedException::MALFORMED_URL);
    }

    if(!array_key_exists('scheme',$params) || !array_key_exists('host',$params)){
      $error_code = 0;
      $error_message = '';
      if(!array_key_exists('scheme',$params) ){
        $error_code |= InvalidUrlPassedException::NO_PROTOCOL;
        $error_message .= 'repository protocol not found';
      }
      if(!array_key_exists('host',$params) ){
        $error_code |= InvalidUrlPassedException::NO_HOST;
        $error_message .= (strlen($error_message)>0?', ':'').'repository host not provided';
      }
      throw new InvalidUrlPassedException($error_message, $error_code);
    }

    switch($params['scheme']){
      case 'http':
      case 'https':
        return new SvnDavRepository($params);
      break;

      default:
        throw new InvalidUrlPassedException(sprintf('unrecognized repository protocol, "%s" not implemented', $params['scheme']), InvalidUrlPassedException::UNRECOGNIZED_PROTOCOL);
      break;
    }
  }
}
