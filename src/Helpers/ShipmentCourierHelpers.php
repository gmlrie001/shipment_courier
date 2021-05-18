<?php

namespace Vault\ShipmentCourier\Helpers; // ShipmentCourierHelper;

// use ;
// use ;

if ( ! function_exists( 'generateGUID' ) ) {

  function generateGUID()
  {
    if ( function_exists( 'com_create_guid' ) ) {
      return com_create_guid();

    } else {
      mt_srand( ( double ) microtime() * 10000 );

      $charid = strtoupper( md5( uniqid( rand(), true ) ) );
      $hyphen = chr( 45 );// "-"
      $uuid   = chr( 123 )// "{"
                .substr( $charid, 0, 8 )  . $hyphen
                .substr( $charid, 8, 4 )  . $hyphen
                .substr( $charid, 12, 4 ) . $hyphen
                .substr( $charid, 16, 4 ) . $hyphen
                .substr( $charid, 20, 12 )
              .chr( 125 );// "}"

      return $uuid;
    }
  }

}

if ( function_exists( 'getCountryCodes' ) ) {

  function getCountryCodes( $fullname )
  {
    $country_codes = [

      'South Africa'   => 'ZA',
      'Australia'      => 'AU',
      'United States'  => 'US',
      'United Kingdom' => 'UK',
      'Germany'        => 'DE',

    ];

    return $country_codes[$fullname];
  }

}



