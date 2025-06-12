<?php

namespace CodersFree\LaravelGreenter\Facades;

use Illuminate\Support\Facades\Facade;

class Greenter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'greenter';
    }
}