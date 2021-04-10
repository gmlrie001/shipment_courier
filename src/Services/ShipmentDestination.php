<?php

namespace Vault\ShipmentCourier\Services;

use Vault\ShipmentCourier\ShipmentCourier;

// /* Facade Includes */
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;


class ShipmentDestination extends ShipmentCourier
{

  public $destinationInfo;
  public $orderDetails;


  public function __construct( $orderDetails )
  {
    parent::__construct();

    $this->destinationInfo = $this->shipmentConfig;
    $this->orderDetails = $orderDetails;

    return $this;
  }

  public function setupDestination()
  {
    $this->setEmail($this->shipmentConfig['accountInfo']['email']);
        //  ->getEmail();
    $this->setPassword($this->shipmentConfig['accountInfo']['password']);
        //  ->getPassword();
    $this->setAccountNumber($this->shipmentConfig['accountInfo']['accountNumber']);
        //  ->getAccountNumber();
    $this->setAccountPin($this->shipmentConfig['accountInfo']['accountPin']);
        //  ->getAccountPin();

    return $this;
  }

  public function getEmail()
  {
    return $this->getProperty( 'clientEmail' );
  }

  public function setEmail( $value )
  {
    return $this->setProperty( 'clientEmail', $value );
  }

  public function getPassword( $key = 'clientPassword' )
  {
    return $this->getProperty( $key );
  }
  
  public function setPassword( $value )
  {
    return $this->setProperty( 'clientPassword', $value );
  }

  public function getAccountNumber( $key = 'clientAccountNumber' )
  {
    return $this->getProperty( $key );
  }
  
  public function setAccountNumber( $value )
  {
    return $this->setProperty( 'clientAccountNumber', $value );
  }

  public function getAccountPin( $key = 'clientAccountPin' )
  {
    return $this->getProperty( $key );
  }
  
  public function setAccountPin( $value )
  {
    return $this->setProperty( 'clientAccountPin', $value );
  }

}
