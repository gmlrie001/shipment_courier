<?php

namespace Vault\ShipmentCourier;

// /* Facade Includes */
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;


class ShipmentCourier
{
  // Client credentials
  public $clientEmail;
  public $clientAccountNumber;
  public $clientPassword;
  public $clientAccountPin;
  // The shipment line items
  public $shipmentLineItems;
  // Special delivery instructions
  public $specialDeliveryInstructions;
  // Shipment type
  public $shipmentIsDocument = false;
  public $shipmentRequiresInsurance = false;
  public $shipmentInsuranceValue = 0;
  // Pickup details
  public $pickupComments;
  public $pickupReference1;
  public $pickupReference2;
  // public $pickupReference = [];
  public $pickupDate;
  public $pickupReadtTime;
  public $pickupClosingTime;
  public $pickupEntityStatus;
  // Origin physical address - pickup point
  public $originStreetAddress;
  public $originBusinessPark;
  public $originOtherAddress;
  public $originStateProvince;
  public $originCountryCode;
  public $originCountryName;
  public $originSuburb;
  public $originPostalCode;
  // Origin contact person details
  public $originContactName;
  public $originContactPerson;
  public $originContactPhone;
  public $originContactEmail;
  // Reference1 and Reference2 held in array;
  public $originReference = [];
  // Destination physical address - pickup point
  public $destinationStreetAddress;
  public $destinationBusinessPark;
  public $destinationOtherAddress;
  public $destinationStateProvince;
  public $destinationCountryCode;
  public $destinationCountryName;
  public $destinationSuburb;
  public $destinationPostalCode;
  // Destination contact person details
  public $destinationContactName;
  public $destinationContactPerson;
  public $destinationContactPhone;
  public $destinationContactEmail;
  // Reference1 and Reference2 held in array;
  public $destinationReference = [];
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
