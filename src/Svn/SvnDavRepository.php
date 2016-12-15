<?php
namespace PhpDavSvn\Svn;

use PhpDavSvn\Exceptions\SecurityException;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class SvnDavRepository extends AbstractSvnRepository{
  private $_url;
  private $_client;
  private $_params;

  public function __construct($params){
    $this->_params = array();

    $this->_url = rtrim($params['scheme'].'://'.
      (isset($params['host'])?$params['host']:'').
      (isset($params['port'])?(':'.$params['port']):'').
      (isset($params['path'])?($params['path']):''),
    '/').'/';

    $this->_client = new Client(['base_uri'=>$this->_url
    ]);

    if(array_key_exists('user',$params)){
      $this->setUsername($params['user']);
    }
    if(array_key_exists('pass',$params)){
      $this->setPassword($params['pass']);
    }
  }

  private function prepareParams(){
    if(strlen($this->getPassword())>0 && strlen($this->getUsername())>0){
      $this->_params = ['auth' => [$this->getUsername(), $this->getPassword()]
      ];
    }
  }

  public function attemptConnect(){
    $this->request('GET');
  }

  private function request($method='GET', $body = '', $contentType = 'text/plain'){
    // $ch = curl_Init();
    // curl_setopt_array($ch, array(
    //   CURLINFO_HEADER_OUT => true,
    //   CURLOPT_RETURNTRANSFER => true,
    //   CURLOPT_URL => $this->_url,
    //   CURLOPT_CUSTOMREQUEST => $method,
    //   CURLOPT_HTTPHEADER => array(
    //     'Content-Type: text/xml'
    //   ),
    //   CURLOPT_USERPWD => $this->_user.':'.$this->_pass,
    //   CURLOPT_POSTFIELDS => $body
    // ));
    //
    // $str = curl_exec($ch);
    // //array_push(Logger::$history, preg_replace( '/\s+/', ' ', $svn_repo->user.':'.$svn_repo->pass ));
    //
    // array_push(Logger::$history, preg_replace( '/\s+/', ' ', htmlspecialchars($str) ));
    // $info = curl_getinfo($ch);
    //
    // // echo '<pre>';
    // // print_r($info['request_header']);
    // // print_r(htmlspecialchars($request));
    // // echo "\n\n";
    // // echo "\n\n";
    // // echo htmlspecialchars($str);
    // // echo '</pre>';
    //
    // $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // if($httpcode != 200 && $httpcode != 400){
    //   return;
    // }
    // curl_close($ch);


    $this->prepareParams();
    if(strlen($body)>0){
      $this->_params['body'] = $body;
    }

    $this->_params['headers']['Content-Type'] = $contentType;


    try{
      $req = $this->_client->createRequest($method,'',$this->_params);
      print_r($req);
      $res = $this->_client->send($req);
      print_r($res);
      return $res;
    } catch(ClientException $cl_ex){
      $response = $cl_ex->getResponse();
      switch($response->getStatusCode()){
        case 401:
        case 403:
          throw new SecurityException('SecurityException: '.$response->getStatusCode(),0,$cl_ex);
        break;

        default:
          throw $cl_ex;
        break;
      }
    }
    return false;
  }

  public function logReport($from = 1, $to = 'HEAD', $orderBy='DESC'){
    $request = '<?xml version="1.0"?>'.
      '<S:log-report xmlns:S="svn:">'.
      '<S:start-revision>'.(strtolower($orderBy)=='desc'?$to:$from).'</S:start-revision>'.
      '<S:end-revision>'.(strtolower($orderBy)=='desc'?$from:$to).'</S:end-revision>'.
    '</S:log-report>';

    return $this->request('REPORT',$request,'text/xml');
  }
}
