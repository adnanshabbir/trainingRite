<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot ()
    {
        Schema::defaultStringLength(191);

        /**
         * Count the contacts in the CSV uploaded by user
         */
        Validator::extend('count_contacts', function ( $attribute, $value, $parameters, $validator ) {


            $csvRows   = file($value, FILE_SKIP_EMPTY_LINES);
            $csvRows   = count($csvRows);
            $threshold = $parameters[0];

            if ( $csvRows > $threshold ) {
                return false;
            }

            return true;
        });

        Validator::replacer('count_contacts', function ( $message, $attribute, $rule, $parameters ) {

            $threshold = $parameters[0];

            return str_replace(':attribute', $attribute, 'The numbers of rows in your uploaded CSV can not be greater then ' . $threshold);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register ()
    {
        //
    }
}
