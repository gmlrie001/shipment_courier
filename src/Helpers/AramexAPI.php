<?php

namespace App\Helpers\AramexShipping;

use App\Helpers\SimpleClient;

class AramexAPI
{
  private $base = 'https://nservice.aramex.co.za/Json/JsonV1';


  public function __construct( $new_api_base=NULL )
  {
    $this->apiBase = ( NULL != $new_api_base ) ? $new_api_base : $this->base;
    $this->customHeaders = [
      'Content-Type: application/json',
      'Accept: */*',
    ];
    $this->client = ( new SimpleClient );

    $cfpath = resource_path('views/templates/vault_config.php');
    $conf   = include($cfpath);
    $this->config = $conf['aramex_credentials'];

    // dd( get_defined_vars(), $this );
  }

  public function getShippingCost( array $params, $altEndPoint='GetDomesticBestService' )
  {
    $url_verbNoun = ( NULL != $altEndPoint ) ? $altEndPoint : 'GetRate';
    $url = $this->apiBase . '/' . $url_verbNoun;

    $fetchMethod  = 'POST';
    $data = $params; // json_encode( $params, JSON_PRETTY_PRINT );

    return $this->client->fetch( $url, $data, $this->customHeaders, $fetchMethod );
  }
    
  public function getBestRate( array $params )
  {
    $url_verbNoun = 'GetDomesticBestService';
    $url = $this->apiBase . '/' . $url_verbNoun;

    $fetchMethod  = 'POST';
    $data = $params; // json_encode( $params, JSON_PRETTY_PRINT );

    return $this->client->fetch( $url, $data, $this->customHeaders, $fetchMethod );
  }

  public function createShipment( array $params )
  {
    $url_verbNoun = 'SubmitWaybill';
    $url = $this->apiBase . '/' . $url_verbNoun;

    $fetchMethod  = 'POST';
    $data = $params; // json_encode( $params, JSON_PRETTY_PRINT );

    return $this->client->fetch( $url, $data, $this->customHeaders, $fetchMethod );
  }

  public function trackShipment( array $params )
  {
    $url_verbNoun = 'GetWaybillTracking';
    $url = $this->apiBase . '/' . $url_verbNoun;

    $fetchMethod  = 'POST';
    $data = $params; // json_encode( $params, JSON_PRETTY_PRINT );

    return $this->client->fetch( $url, $data, $this->customHeaders, $fetchMethod );
  }

  public function createShipmentPickup( array $params )
  {
    $url_verbNoun = 'SubmitCollection';
    $url = $this->apiBase . '/' . $url_verbNoun;

    $fetchMethod  = 'POST';
    $data = $params; // json_encode( $params, JSON_PRETTY_PRINT );

    return $this->client->fetch( $url, $data, $this->customHeaders, $fetchMethod );
  }

  public function trackShipmentPickup( array $params )
  {
    $url_verbNoun = 'GetCollectionTracking';
    $url = $this->apiBase . '/' . $url_verbNoun;

    $fetchMethod  = 'POST';
    $data = $params; // json_encode( $params, JSON_PRETTY_PRINT );

    return $this->client->fetch( $url, $data, $this->customHeaders, $fetchMethod );
  }

  // public function cancelShipmentPickup( array $params )
  // {
  //   return $this->client->fetch( $url, $data, $this->customHeaders, $fetchMethod );
  // }


  public function setupShipRateParams( $cart )
  {
    $params = [];
    $params = array_merge( 
      $params, 
      $this->clientInfo(), 
      $this->clientOrigin(), 
      $this->customerDestination( $cart ),
      $this->paymentOptions(),
      $this->miscOptions(),
      $this->shippingParcel( $cart )
    );

    return $params;
  }

  public function setupShipmentParams( $cart )
  {
    $params = [];
    $params = array_merge( 
      $params, 
      $this->clientInfo(), 
      $this->sender(), 
      $this->receiver( $cart ),
      $this->paymentOptions(),
      $this->miscOptions(),
      $this->specialInstructions( '' ),
      $this->insuranceReq(),
      $this->labelInfo(),
      $this->waybillInfo(),
      $this->shippingParcel( $cart )
    );

    return $params;
  }

  public function setupPickupParams( $cart )
  {
    $params = [];
    $params = array_merge( 
      $params, 
      $this->clientInfo(), 
      $this->sender(), 
      $this->receiver( $cart ),
      $this->paymentOptions(),
      $this->miscOptions(),
      $this->pickupInfo()
    );

    return $params;
  }

  private function pickupInfo()
  {
    $site = \App\Models\Site::first()->only( ['site_name'] );
    $siteUrl = url( '/' );
    $pickupStatus = ['Ready', '', 'Not Ready'];

    $reference1 = ucwords( $site->site_name ) . " Online Order Fulfilment and Shipping";
    $reference2 = ucwords( $site->site_name ) . " Online Order Aramex Shipping";
    $comments = "Online purchase from " . ucwords( $site->site_name ) . "(" . $siteUrl . ")";
    unset( $site, $siteUrl );

    $opening_time = date( 'H:i:s', strtotime( 'tomorrow 8am' ) );
    $closing_time = date( 'H:i:s', strtotime( 'tomorrow 5pm' ) );

    $pickup_date = date( 'Y-m-d', strtotime( 'tomorrow' ) );
    $ready_time  = date( 'H:i:s', strtotime( 'tomorrow noon' ) );

    return [
      "reference1" => $reference1,
      "reference2" => $reference2,
      "comments"   => $comments,
      "status"     => 'Ready',
      "ready_time"   => $ready_time,
      "pickup_date"  => $pickup_date,
      "closing_time" => $closing_time,
    ];
  }

  private function clientInfo()
  {
    return [
      'account_number' => $this->config['AccountNumber'],
      'email_address'  => $this->config['UserName'],
      'password'       => $this->config['Password'],
      'account_pin'    => $this->config['AccountPin'],
    ];
  }

  private function clientOrigin()
  {
    return [
      "sender_country_code" => "ZA",
      "sender_country_name" => "South Africa",
      "sender_suburb"       => "Brooklyn",
      "sender_postal_code"  => "7405",
    ];
  }

  private function customerDestination( $egCart )
  {
    return [
      "receiver_country_code" => $this->countryCodes( $egCart['delivery_country'] ),
      "receiver_country_name" => $egCart['delivery_country'],
      "receiver_suburb"       => $egCart['delivery_suburb'],
      "receiver_postal_code"  => $egCart['delivery_postal_code'],
    ];
  }

  private function sender()
  {
    return [
      "sender_street_address" => "7 Section Street",
      "sender_business_park" => "Shop No. 4, Section Street Business Park",
      "sender_other_address" => "None",
      "sender_country_code" => "ZA",
      "sender_country_name" => "South Africa",
      "sender_state" => "Cape Town",
      "sender_suburb" => "Brooklyn",
      "sender_postal_code" => "7405",
      "sender_name" => "Daniel",
      "sender_reference1" => "African Oils Online SalesOrder -w- https://africanoils.co.za",
      "sender_reference2" => "",
      "sender_contact_person" => "Daniel",
      "sender_contact_number" => "07699139873",
      "sender_contact_email" => "daniel@africanoils.co.za",
    ];
  }

  private function receiver( $egCart )
  {
    return [
      "receiver_street_address" => $egCart->delivery_address_line_1,
      "receiver_business_park"  => "None",
      "receiver_other_address"  => "None",
      "receiver_state"        => $egCart->delivery_province,
      "receiver_country_code" => $this->countryCodes( $egCart->delivery_country ),
      "receiver_country_name" => $egCart->delivery_country,
      "receiver_suburb"       => $egCart->delivery_suburb,
      "receiver_postal_code"  => $egCart->delivery_postal_code,
      "receiver_name"       => $egCart->delivery_name,
      "receiver_reference1" => "African Oils Online Order (ID:{".$egCart->id."}) _@_> https://africanoils.co.za",
      "receiver_reference2" => "",
      "receiver_contact_person" => $egCart->delivery_name . " " . $egCart->delivery_surname, // "Angelo Saim",
      "receiver_contact_number" => $egCart->delivery_phone, // "0769913873",
      "receiver_email_address"  => $egCart->user->email, // "studio@monzamedia.com",
    ];
  }

  private function shippingParcel( $egCart )
  {
    $cart_total = 0;
    $total_weight = 0;
    $l = $w = $h = 0;

    foreach( $egCart->products as $key=>$product ) {
      $l += $product->product->length;
      $w += $product->product->width;
      $h += $product->product->height;
      $cart_total   += $product->price * $product->quantity;
      $total_weight += $product->product->weight;
    }
    $package_dims = ['length' => $l, 'width' => $w, 'height' => $h];
    unset( $l, $w, $h );

    $params['parcels'] = [
      [
        "parcel_value" => $cart_total, // ZAR
        "quantity"     => $egCart->products->sum( 'quantity' ),
        "length"       => $package_dims['length'], // cm
        "width"        => $package_dims['width'], // cm
        "height"       => $package_dims['height'], // cm
        "weight"       => $total_weight // kg
      ],
    ];
    unset( $total_weight, $cart_total );

    return $params;
  }

  private function paymentOptions()
  {
    return [
      'payment_type' => $this->config['PaymentType'],
      'service_type'  => $this->config['ServiceType'],
    ];
  }

  private function miscOptions()
  {
    return [
      'is_documents'      => false,
      'require_insurance' => false,
    ];
  }

  private function specialInstructions( $str='' )
  {
    return ['special_instructions' => $str, ];
  }

  private function insuranceReq()
  {
    return [
      'require_insurance' => false,
      'insurance_value' => 0,
    ];
  }

  private function labelInfo()
  {
    return [
      'waybill_print_template' => 9201,
      'waybill_pdf_fetch_type' => 'URL',
    ];
  }

  private function waybillInfo()
  {
    // PLEASE NOTE: ONLY letters and numbers allowed no special characters/punctuation.
    $waybill_site_prefix = 'AOV';
    $waybill_tracking_no = md5( $this->getGUID() );
    
    return ['waybill_number'=> ''];
    // ['waybill_number' => $waybill_site_prefix . $waybill_tracking_no];
  }

  public function getGUID()
  {
    if (function_exists('com_create_guid')) {

      return com_create_guid();

    } else {

      mt_srand((double) microtime() * 10000);//optional for php 4.2.0 and up.

      $charid = strtoupper(md5(uniqid(rand(), true)));
      $hyphen = chr(45);// "-"
      $uuid   = chr(123)// "{"
                .substr($charid, 0, 8) . $hyphen
                .substr($charid, 8, 4) . $hyphen
                .substr($charid, 12, 4) . $hyphen
                .substr($charid, 16, 4) . $hyphen
                .substr($charid, 20, 12)
              .chr(125);// "}"

      return $uuid;
    }
  }

  public function countryCodes( $name )
  {
    $country_codes = [
      'South Africa'   => 'ZA',
      'Australia'      => 'AU',
      'United States'  => 'US',
      'United Kingdom' => 'UK',
      'Germany'        => 'DE',
    ];

    return $country_codes[$name];
  }

}
