<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ProxyServiceProvider extends ServiceProvider
{
    protected $defer = false;

    
    public function register()
    {
       $request = $this->app['request'];
        $proxies = $this->app['config']->get('proxy.proxies');

        if( $proxies === '*' )
        {
            // Trust all proxies - proxy is whatever
            // the current client IP address is
            $proxies = array( $request->getClientIp() );
        }

        $request->setTrustedProxies( $proxies );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
