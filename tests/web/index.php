<?php
include_once '../../vendor/autoload.php';
use PhpDavSvn\Svn;
use PhpDavSvn\Exceptions\SvnException;
use GuzzleHttp\Exception\GuzzleException;
    //OD CONFIG
?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>php-dav-svn test</title>
<link href="/css/main.css" rel="stylesheet" />
</head>
<body>
<pre style="margin-left:5px">
<?php
try{
  $svn = new Svn('http://xand.mn/svn/atatek');
  //$svn->attemptConnect();


  $request = $svn->logReport(1,10);
  echo htmlspecialchars($request->getBody());
} catch(SvnException $ex){
  echo "SvnException: ".$ex->getMessage();
} catch(GuzzleException $ex){
  echo "GuzzleException: ".$ex->getMessage();
}

?>
</pre>
</body>
</html>
