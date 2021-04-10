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

    ],

  ];
