<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class EmailConfigProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
                $config = array(
                    'driver'     => 'smtp',
                    'host'       => 'smtp.gmail.com',
                    'port'       => 465,
                    'from'       => array('address' => 'hello@example.com', 'name' => env('APP_NAME', 'Example')),
                    'encryption' => 'ssl',
                    'username'   => 'frranad1@gmail.com',
                    'password'   => 'kgotmxzgvksdaeid',
                    'sendmail'   => '/usr/sbin/sendmail -bs',
                    'pretend'    => false,
                );
                Config::set('mail', $config);
    }
}
