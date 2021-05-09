<?php

namespace Vault\ShipmentCourier;

// /* Facade Includes */
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;

use Vault\ShipmnentCourier\Exceptions\ShipmentCourierExceptions;


class ShipmentCourier
{
  // Client credentials
  private $clientEmail;
  private $clientAccountNumber;
  private $clientPassword;
  private $clientAccountPin;

  // The shipment line items
  private $shipmentLineItems;
  // item_{
  //   key, 
  //   desc, 
  //   pieces, 
  //   price, 
  //   [l, w, h], 
  //   actual_mass
  // }

  // Special delivery instructions
  private $specialDeliveryInstructions;
  // Shipment type
  private $shipmentIsDocument = false;
  private $shipmentRequiresInsurance = false;
  private $shipmentInsuranceValue = 0;

  // Pickup details
  private $pickupComments;
  private $pickupReference1;
  private $pickupReference2;
  // private $pickupReference = [];
  private $pickupDate;
  private $pickupReadyTime;
  private $pickupClosingTime;
  private $pickupEntityStatus;

  // Origin physical address - pickup point
  private $originStreetAddress;
  private $originBusinessPark;
  private $originOtherAddress;
  private $originStateProvince;
  private $originCountryCode;
  private $originCountryName;
  private $originSuburb;
  private $originPostalCode;
  // Origin contact person details
  private $originContactName;
  private $originContactPerson;
  private $originContactPhone;
  private $originContactEmail;
  // Reference1 and Reference2 held in array;
  private $originReference = [];

  // Destination physical address - pickup point
  private $destinationStreetAddress;
  private $destinationBusinessPark;
  private $destinationOtherAddress;
  private $destinationStateProvince;
  private $destinationCountryCode;
  private $destinationCountryName;
  private $destinationSuburb;
  private $destinationPostalCode;
  // Destination contact person details
  private $destinationContactName;
  private $destinationContactPerson;
  private $destinationContactPhone;
  private $destinationContactEmail;
  // Reference1 and Reference2 held in array;
  private $destinationReference = [];

  // Get the config. for this class
  public $shipmentConfig;


  public function __construct()
  {
    $this->shipmentConfig = Config::get( 'shipment_courier' );

    return $this;
  }

  public function getProperty( $key = NULL )
  {
    if ( $key != NULL || isset( $key ) ) {

      if ( $this->__isset( $key ) ) {
        return $this->{$key};
      }

    }

    return;
  }

  public function setProperty( $key, $value = NULL )
  {
    if ( ! $key || NULL == $key ) throw new ShipmentCourierException( 'Need to provide a valid key to set its value.' );

    if ( $key != NULL || isset( $key ) ) {
      if ( $this->__isset( $key ) ) {
        $this->{$key} = $value;

        return $this;
      }
    }

    return;
  }

  public function __isset( $key )
  {
    $objProps = array_keys( get_object_vars( $this ) );
    
    return in_array( $key, $objProps );
  }

}
