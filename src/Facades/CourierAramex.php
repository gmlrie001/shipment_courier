<?php

namespace Vault\ShipmentCourier\Facades;

// Illuminate Facades
use Illuminate\Support\Facades\Facade;

class ShipmentCourier extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor()
  {
    return 'shipment_courier';
  }

}
