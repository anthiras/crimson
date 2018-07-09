<?php

namespace App\Providers;

use App\Http\Controllers\Controller;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;

class CollectionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Collection::macro('call', function($functionName) {
            return $this->map(function ($item) use ($functionName) {
                return call_user_func([$item, $functionName]);
            });
        });

        Collection::macro('verifyType', function($type) {
            if (!$this->every(function ($item) use ($type) {
                return is_a($item, $type) || is_subclass_of($item, $type);
            })) {
                throw new \Exception("Collection contains unexpected types. Only ".$type." is allowed.");
            }
            return $this;
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
