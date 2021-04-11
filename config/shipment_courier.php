<?php

  return [

    'api' => [
      'aramex' => [
        'courierName' => "Aramex", 
        'apiBase' => "https://nservice.aramex.co.za/Json/JsonV1", 
        'apiEndpoints' => [
          'get_rate' => [
            'uri' => "GetRate", 
            'method' => "POST"
          ], 
          'submit_waybill' => [
            'uri' => "SubmitWaybill", 
            'method' => "POST"
          ], 
          'track_waybill'  => [
            'uri' => "GetWaybillTracking",
            'method' => "POST"
          ], 
          'submit_pickup' => [
            'uri' => "SubmitCollection", 
            'method' => "POST",
          ], 
          'track_pickup' => [
            'uri' => "GetCollectionTracking", 
            'method' => "POST",
          ], 
          'get_domestic_best_rate_monzamedia' => [
            'uri' => "GetDomesticBestService", 
            'method' => "POST"
          ],
        ], 
      ], 

      'the_courier_guy' => [
        'courierName' => "The Courier Guy", 
        'apiBase' => "", 
        'apiEndpoints' => [
          'get_rate' => [
            'uri' => "", 
            'method' => "POST"
          ], 
          'submit_waybill' => [
            'uri' => "", 
            'method' => "POST"
          ], 
          'track_waybill'  => [
            'uri' => "",
            'method' => "POST"
          ], 
          'submit_pickup' => [
            'uri' => "", 
            'method' => "POST",
          ], 
          'track_pickup' => [
            'uri' => "", 
            'method' => "POST",
          ], 
          'tcg_specific' => [
            'uri' => "", 
            'method' => "POST"
          ], 
        ], 
      ], 
    ], 

    // Special delivery instructions default value
    'specialDeliveryInstructions' => "None",

    // Client specific account information/credentials
    // 'shipmentAccountInfo'
    'accountInfo' => [

      'email'         => "riedaa@monzamedia.com",
      'password'      => "12345678",
      'accountNumber' => "094551",
      'accountPin'    => "3254"
    
    ],

    'shipmentOrigin' => [

      'originStreetAddress' => "25 Upper Cambridge Street",
      'originBusinessPark'  => NULL,
      'originOtherAddress'  => "None",
      'originStateProvince' => "Western Cape",
      'originCountryCode'   => "ZA",
      'originCountryName'   => "South Africa",
      'originSuburb'        => "Walmer Estate",
      'originPostalCode'    => "7925",

      'originContactName'   => "Riedaa",

      'originContactPerson' => "M. Riedaa Gamieldien",
      'originContactPhone'  => "89412116",
      'originContactEmail'  => "riedaa@monzamedia.com",

      'originReference'     => NULL,

    ],

    'shipmentOrderDestination' => [

    ],

    'shipmentOrderItems' => [

    ],

    'shipmentPickupDetails' => [

    ],

  ];
