<?php

namespace CodersFree\LaravelGreenter\Facades;

use Illuminate\Support\Facades\Facade;

class GreenterApi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'greenter.api';
    }
}