<?php

namespace Vault\ShipmentCourier\Services;

use Vault\ShipmentCourier\ShipmentCourier;

// /* Facade Includes */
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;


class ShipmentAdditionalServices extends ShipmentCourier
{

  // shipmentAccountInfo
  public $additionalServices;


  public function __construct()
  {
    parent::__construct();

    $this->additionalServices = $this->shipmentConfig['additionalServices'];

    return $this;
  }

  public function setupAdditionalServices()
  {
    // $this->setEmail($this->shipmentConfig['accountInfo']['email']);
    //     //  ->getEmail();
    // $this->setPassword($this->shipmentConfig['accountInfo']['password']);
    //     //  ->getPassword();
    // $this->setAccountNumber($this->shipmentConfig['accountInfo']['accountNumber']);
    //     //  ->getAccountNumber();
    // $this->setAccountPin($this->shipmentConfig['accountInfo']['accountPin']);
    //     //  ->getAccountPin();

    return $this;
  }

}
