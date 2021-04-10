<?php

namespace Vault\ShipmentCourier\Services;

use Vault\ShipmentCourier\ShipmentCourier;

// /* Facade Includes */
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;


class ShipmentOrigin extends ShipmentCourier
{

  public $accountInfo;


  public function __construct()
  {
    parent::__construct();

    $this->accountInfo = $this->shipmentConfig['accountInfo'];

    return $this;
  }

  public function setupOrigin()
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
