<?php

namespace Vault\ShipmentCourier\Helpers;

use Illuminate\Support\Facades\Config;
use Vault\ShipmentCourier\ShipmentCourier;


class ShipmentCourierApiUriBuilder extends ShipmentCourier 
{

  public $apiConfig;
  private $baseUri;
  private $apiEndpoints;
  private $apiEndpointMethods;


  public function __construct()
  {
    parent::__construct();

    $this->apiConfig = $this->shipmentConfig['api'];

    return $this;
  }

  public function getApiBaseUri()
  {
    $this->baseUri = $this->apiConfig['baseUri'];

    return $this;
  }

  public function getApiEndpointsUri()
  {
    $this->apiEndpoints = ( isset( $this->apiConfig ) || NULL != $this->apiConfig ) 
                          ? $this->apiConfig['endpointVerbs'] 
                          : $this->shipmentConfig['api']['endpointVerbs'];

    return $this;
  }

  public function setApiEndpointsMethod()
  {
    $this->apiEndpointMethods = array_map( $this->apiEndpoints, function( $m ) { return 'POST'; } );

    return $this;
  }


}