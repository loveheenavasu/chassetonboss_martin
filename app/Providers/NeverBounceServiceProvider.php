<?php

namespace App\Providers;

use NeverBounce\Auth;
use Illuminate\Support\ServiceProvider;

class NeverBounceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Auth::setApiKey(config('services.neverbounce.api_key'));
    }
}
