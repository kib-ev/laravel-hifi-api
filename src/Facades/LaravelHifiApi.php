<?php

namespace KibEv\LaravelHifiApi\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelHifiApi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-hifi-api';
    }
}
