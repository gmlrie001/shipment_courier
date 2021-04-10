<?php

namespace Vault\ShipmentCourier\Services;

use Vault\ShipmentCourier\ShipmentCourier;

// /* Facade Includes */
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;


class ShipmentAccountInfo extends ShipmentCourier
{
  // Client credentials
  private $clientEmail;
  private $clientAccountNumber;
  private $clientPassword;
  private $clientAccountPin;


  // Get the config. for this class
  public $shipmentConfig;


  // public function __construct( Config $shipmentConfig = NULL )
  // {
  //   $this->shipmentConfig = $shipmentConfig;
  //   return $this;
  // }

  public function setupAccountInfo()
  {
    $this->configAccountInfo = $this->shipmentConfig['accountInfo'];
  }

  public function getEmail( $key = 'clientEmail' )
  {
    return $this->getProperty( $key );
  }

  public function setEmail( $key = 'clientEmail', $value = 'dev2@monzamedia.com' )
  {
    return $this->setProperty( $key, $value );
  }



  public function getProperty( $key = NULL )
  {
    if ( $key != NULL || isset( $key ) ) {
      if ( $this->checkPropertyExists( $key ) ) {
        return $this->{$key};
      }
    }

    return;
  }

  public function setProperty( $key, $value = NULL )
  {
    if ( $key != NULL || isset( $key ) ) {
      if ( $this->checkPropertyExists( $key ) ) {
        $this->{$key} = $value;
        return $this;
      }
    }

    return;
  }

  private function checkPropertyExists( $key )
  {
    $objProps = array_keys( get_object_vars( $this ) );
    
    return in_array( $key, $objProps );
  }

}
