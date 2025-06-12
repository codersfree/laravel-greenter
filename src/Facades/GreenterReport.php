<?php

namespace CodersFree\LaravelGreenter\Facades;

use Illuminate\Support\Facades\Facade;

class GreenterReport extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'greenter.report';
    }
}