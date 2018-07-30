<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class console_log extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot($data)
    {
        //
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
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
