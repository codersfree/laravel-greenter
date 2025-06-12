<?php

namespace CodersFree\LaravelGreenter\Builders\See;

use CodersFree\LaravelGreenter\Exceptions\GreenterException;

class DocumentBuilderFactory
{
    public static function create(string $type): DocumentBuilderInterface
    {
        return match ($type) {
            'invoice' => new InvoiceBuilder(),
            'note' => new NoteBuilder(),
            'perception' => new PerceptionBuilder(),
            default => throw new GreenterException("Tipo de documento no soportado"),
        };
    }
}