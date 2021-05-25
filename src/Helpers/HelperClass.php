<?php

namespace Vault\ShipmentCourier\Helpers; // ShipmentCourierHelper;

// use {};


class HelperClass
{
  public static $debug = false;


  public static function objectProps( $key, $obj )
  {
    if ( ! is_object( $obj ) ) return;

    // dd( __METHOD__, __LINE__, $obj, self::getObjectProps( $obj ), self::getArrayKeys( self::getObjectProps( $obj ) ) );


    return self::isInArray( 
      $key, 
      self::getArrayKeys( self::getObjectProps( $obj ) )
    );
  }


  public static function isInArray( $needle=NULL, $haystack=[] )
  {
    if ( NULL == $needle || ! is_array( $haystack ) || empty( $haystack ) ) return;

    try {
      return in_array( $needle, $haystack );

    } catch( \Exception $error ) {}
  }


  public static function getArrayKeys( $obj=[] )
  {
    if ( ! is_array( $obj ) || empty( $obj ) || count( $obj ) == 0 ) return;

    return array_keys( $obj );
  }


  public static function getArrayValues( $obj=[] )
  {
    if ( ! is_array( $obj ) || empty( $obj ) || count( $obj ) == 0 ) return;

    return array_values( $obj );
  }


  public static function getObjectProps( $obj )
  {
    if ( ! is_object( $obj ) ) return;

    return get_object_vars( $obj );
  }


  public static function getObjectMethods( $obj=NULL )
  {
    if ( ! is_object( $obj ) ) return;

    $container = new \StdClass();
    $container->viaObject      = get_class_methods( $obj );
    $container->viaCalledClass = get_class_methods( \get_called_class() );

    if ( \property_exists( $container, 'viaObject' ) ) {
        return $container->viaObject;
    }

    if ( self::$debug && \property_exists( $container, 'viaCalledClass' ) ) {
      if ( count( $container->viaObject ) === count( $container->viaCalledClass ) ) {
        return $container->viaCalledClass;
      }
    }

    // $container->methodArrayDiff
    // return $container->methodArrayDiff;
    return ( count( $container->viaObject ) > count( $container->viaCalledClass ) ) 
                ? array_diff( $container->viaObject, $container->viaCalledClass ) 
                  : array_diff( $container->viaCalledClass, $container->viaObject );
  }

}
