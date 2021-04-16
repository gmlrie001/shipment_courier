<?php

namespace App\Helpers;

class SimpleClient
{

  public function __construct()
  {
    $this->curlHandler = curl_init();
  }

  function fetch($url, $data=NULL, $headers, $method="GET")
  {
    $this->setupDefaultOptions();

    curl_setopt($this->curlHandler, CURLOPT_HTTPHEADER, $headers );
    curl_setopt($this->curlHandler, CURLOPT_URL, $url);

    if( NULL == $data || ! $data ): $data = []; endif;
    $data = \json_encode( $data, JSON_PRETTY_PRINT );
    $this->setupMethod( 'POST', ['data' => $data, 'dataLen' => strlen( $data )] );

    // Make the call request, query info and results from handler and clean up
    $rtn = $this->call();
    curl_close($this->curlHandler);
    unset($this->curlHandler);

    $output = new \stdClass;
    $output->header = $rtn['header'];
    $output->info   = $rtn['info'];
    $output->result = $rtn['response'];

    return $output;
  }

  private function setupDefaultOptions()
  {
    // Fail the cURL request if response code = 400 (like 404 errors)
    curl_setopt($this->curlHandler, CURLOPT_FAILONERROR, false);

    // Do not check the SSL certificates
    curl_setopt($this->curlHandler, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($this->curlHandler, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($this->curlHandler, CURLOPT_CAINFO, $this->ssl_cert); // Set the location of the CA-bundle

    curl_setopt($this->curlHandler, CURLINFO_HEADER_OUT, true);
    curl_setopt($this->curlHandler, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($this->curlHandler, CURLOPT_VERBOSE, true);
    // Follow redirects, if any
    curl_setopt($this->curlHandler, CURLOPT_FOLLOWLOCATION, true);

    curl_setopt($this->curlHandler, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($this->curlHandler, CURLOPT_TIMEOUT, 30);
    curl_setopt($this->curlHandler, CURLOPT_DNS_CACHE_TIMEOUT, 75);
  }

  private function setupMethod()
  {
    $argn = func_num_args();
    $args = func_get_args();

    $method = $args[0];
    $arg    = $args[1];

    switch( strtolower( $method ) ):

      case( 'post' ):
        curl_setopt($this->curlHandler, CURLOPT_POST, true);
        curl_setopt($this->curlHandler, CURLOPT_POSTFIELDS, $arg['data']);
        break;

      case( 'put' ):
        throw new Exception();
        break;

      // Default method = GET
      default:
        return;

    endswitch;
  }

  private function call()
  {
    // Execute the request
    $result = curl_exec( $this->curlHandler );

    // Get the headers and other info. regarding the request
    $header = curl_getinfo( $this->curlHandler, CURLINFO_HTTP_CODE );
    $info   = curl_getinfo( $this->curlHandler );

    return ['header'=>$header, 'info'=>$info, 'response'=>$result];
  }

}
