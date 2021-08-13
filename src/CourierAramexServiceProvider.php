<?php

namespace Vault\ShipmentCourier;

use Illuminate\Support\ServiceProvider;


class ShipmentCourierServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap the application services.
   */
  public function boot()
  {
    /**
     * Optional methods to load your package assets
     */
    // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'shipment_courier');
    // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    // $this->loadRoutesFrom(__DIR__.'/routes.php');
    // $this->loadViewsFrom( __DIR__ . '/../resources/views', 'shipment_courier' );
    $this->publishes([
      __DIR__ . '/../config/shipment_courier.php' => config_path( 'shipment_courier.php' ),
    ], 'config');

    // if ($this->app->runningInConsole()) {

      // Publishing the config.
        /*$this->publishes([
          __DIR__.'/../config/shipment_courier.php' => config_path( 'shipment_courier.php' ),
        ], 'config');*/

      // Publishing the views.
      /*$this->publishes([
        __DIR__.'/../resources/views' => resource_path( 'views/vendor/shipment_courier' ),
      ], 'views' );*/

      // Publishing assets.
      /*$this->publishes([
        __DIR__.'/../resources/assets' => public_path( 'vendor/shipment_courier' ),
      ], 'assets' );*/

      // Publishing the translation files.
      /*$this->publishes([
        __DIR__.'/../resources/lang' => resource_path( 'lang/vendor/shipment_courier' ),
      ], 'lang' );*/

      // Registering package commands.
      // $this->commands([
      //   Console\Commands\ShipmentCourier::class
      // ]);
    // }

  }

  /**
   * Register the application services.
   */
  public function register()
  {
    // Automatically apply the package configuration
    $this->mergeConfigFrom( __DIR__ . '/../config/shipment_courier.php', 'shipment_courier' ) ;

    // Register the main class to use with the facade
    $this->app->bind( 'shipment_courier', function () {
      return new ShipmentCourier();
    });

    // return $this->app->make( 'Vault\ShipmentCourier\ShipmentCourier' );
  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides()
  {
    return ['shipment_courier'];
  }

}
