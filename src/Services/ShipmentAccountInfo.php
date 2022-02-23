<?php

namespace Vault\ShipmentCourier\Services;

use Vault\ShipmentCourier\ShipmentCourier;

// /* Facade Includes */
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;

use Vault\ShipmentCourier\Helpers\HelperClass;


class ShipmentAccountInfo extends ShipmentCourier
{
  // Client credentials
  // private $clientEmail;
  // private $clientPassword;
  // private $clientAccountNumber;
  // private $clientAccountPin;

  // shipmentAccountInfo
  public $accountInfo;


  public function __construct()
  {
    parent::__construct();

    if ( NULL != get_parent_class() && is_subclass_of( get_called_class(), get_parent_class() ) ) {
      parent::__construct();
    }

    if ( ! property_exists( $this, 'shipmentConfig' ) ) return;

    $this->accountInfo = $this->shipmentConfig['accountInfo'];

    return $this;
  }

  public function setupAccountInfo()
  {
    $this->setEmail( $this->accountInfo['email'] ) //  ->getEmail();
         ->setPassword( $this->accountInfo['password'] ); //  ->getPassword();

    if ( NULL != $this->accountInfo['accountNumber'] && isset( $this->accountInfo['accountNumber'] ) ) {
      $this->setAccountNumber( $this->accountInfo['accountNumber'] ); //  ->getAccountNumber();
    }

    if ( NULL != $this->accountInfo['accountPin'] && isset( $this->accountInfo['accountPin'] ) ) {
      $this->setAccountPin( $this->accountInfo['accountPin'] ); //  ->getAccountNumber();
    }

    return $this;
  }

  public function getEmail()
  {
    return $this->getProperty( 'clientEmail' );
  }

  public function setEmail( $value )
  {
    return $this->setProperty( 'clientEmail', $value );
    // return $this;
  }

  public function getPassword()
  {
    return $this->getProperty( 'clientPassword' );
  }
  
  public function setPassword( $value )
  {
    return $this->setProperty( 'clientPassword', $value );
  }

  public function getAccountNumber()
  {
    return $this->getProperty( 'clientAccountNumber' );
  }
  
  public function setAccountNumber( $value )
  {
    return $this->setProperty( 'clientAccountNumber', $value );
  }

  public function getAccountPin()
  {
    return $this->getProperty( 'clientAccountPin' );
  }
  
  public function setAccountPin( $value )
  {
    return $this->setProperty( 'clientAccountPin', $value );
  }

  public function getAllAccountInfoProps()
  {
    $obj = new \StdClass();

    $obj->email         = $this->getEmail();
    $obj->password      = $this->getPassword();
    $obj->accountNumber = $this->getAccountNumber();
    $obj->accountPin    = $this->getAccountPin();

    return $obj;
  }

}
