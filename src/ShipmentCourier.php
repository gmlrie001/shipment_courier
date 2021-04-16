<?php

namespace Vault\ShipmentCourier;

// /* Facade Includes */
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;


class ShipmentCourier
{
  // Client credentials
  private $clientEmail;
  private $clientAccountNumber;
  private $clientPassword;
  private $clientAccountPin;

  // The shipment line items
  private $shipmentLineItems = [];

  // Special delivery instructions
  private $specialDeliveryInstructions;
  // Shipment type
  private $shipmentIsDocument        = false;
  private $shipmentRequiresInsurance = false;
  private $shipmentInsuranceValue    = 0;

  // Pickup details
  private $pickupDate;
  private $pickupReadtTime;
  private $pickupClosingTime;
  private $pickupEntityStatus;
  private $pickupComments;
  private $pickupReference1;
  private $pickupReference2;
  // Reference1 and Reference2 held in array;
  private $pickupReference = ["", ""];

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
  private $originReference1;
  private $originReference2;
  // Reference1 and Reference2 held in array;
  private $originReference = ["", ""];

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
  private $destinationReference1;
  private $destinationReference2;
  // Reference1 and Reference2 held in array;
  private $destinationReference = ["", ""];

  // Get the config. for this class
  public $shipmentConfig;


  /**
   * Class instantiation
   * 
   */
  public function __construct()
  {
    $this->shipmentConfig = Config::get( 'shipment_courier' );

    return $this;
  }

  /**
   * Generic public object property getter
   * 
   * @param: key (default: NULL)
   * 
   * @return: class::key
   * 
   */
  public function getProperty( $key = NULL )
  {
    if ( $key != NULL || isset( $key ) ) {
      if ( $this->checkPropertyExists( $key ) ) {
        return $this->{$key};
      }
    }
    return;
  }

  /**
   * Generic public object property setter
   * 
   * @param: key required
   * @param: value (default: NULL)
   * 
   * @return: class::instance
   * 
   */
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

  /**
   * Checks if the supplied property value actually exists.
   * 
   * @param: key required
   * 
   * @return: boolean
   * 
   */
  private function checkPropertyExists( $key = NULL )
  {
    if ( ! isset( $key ) || NULL == $key ) {
      return;
    }

    $objProps = array_keys( get_object_vars( $this ) );
    
    return in_array( $key, $objProps );
  }

}
