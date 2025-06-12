<?php

namespace CodersFree\LaravelGreenter\Builders\Api;

use CodersFree\LaravelGreenter\Exceptions\GreenterException;

class DocumentBuilderFactory
{
    public static function create(string $type): DocumentBuilderInterface
    {
        return match ($type) {
            'despatch' => new DespatchBuilder(),
            'summary' => new SummaryBuilder(),
            default => throw new GreenterException("Tipo de documento no soportado"),
        };
    }
}