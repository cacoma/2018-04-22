<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
use Illuminate\Http\Request;
use App\Providers\console_log;

class greaterThanRule extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
      Validator::extend('greaterThan', function($attribute, $value, $comparedValue, $validator){

            new console_log('funcionando');
            // $other = $request->quant;
            return intval($value) > intval($comparedValue[0]);
        });

        Validator::replacer('greaterThan', function($message, $attribute, $rule, $params) {
            return str_replace('_', ' ' , 'The '. $attribute .' must be greater than the ' .$params[0]);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
