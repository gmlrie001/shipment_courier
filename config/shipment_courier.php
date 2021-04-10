<?php

  return [

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

    ],

    'shipmentDestination' => [

      'destinationStreetAddress' => 'delivery_address_line_1',
      'destinationBusinessPark'  => 'delivery_address_line_2',
      'destinationOtherAddress'  => 'delivery_address_line_2',
      'destinationStateProvince' => 'delivery_province',
      'destinationCountryCode'   => 'delivery_country',
      'destinationCountryName'   => 'delivery_country',
      'destinationSuburb'        => 'delivery_suburb',
      'destinationPostalCode'    => 'delivery_postal_code',

      'destinationContactName'   => 'delivery_company',

      'destinationContactPerson' => 'delivery_name',
      'destinationContactPhone'  => 'delivery_phone',
      'destinationContactEmail'  => 'user.email',

      'destinationReferenceArr'  => 'id', // 

    ],

    'orderDeliveryKeys' => [

      'delivery_address_line_1',
      'delivery_address_line_2', 

      'delivery_province',
      'delivery_country', // 2 letter ISO-Code
      'delivery_country', // Full name
      'delivery_suburb',
      'delivery_city', 
      'delivery_postal_code',

      'delivery_company',
      'delivery_name',
      'delivery_surname',
      'delivery_phone',

    ],
    // ],

    'shipmentOrderItems' => [

    ],

    'shipmentPickupDetails' => [

      'pickupComments'     => NULL,
      'pickupReference1'   => NULL,
      'pickupReference2'   => NULL,
      'pickupDate'         => NULL,
      'pickupReadtTime'    => NULL,
      'pickupClosingTime'  => NULL,
      'pickupEntityStatus' => NULL

    ],

  ];
