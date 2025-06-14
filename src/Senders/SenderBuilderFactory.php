<?php

namespace CodersFree\LaravelGreenter\Senders;

class SenderBuilderFactory
{
    /**
     * Create a sender instance based on the type.
     *
     * @param string $type
     * @return mixed
     */
    public static function create(string $type): SenderBuilderInterface
    {
        return match ($type) {
            // Para guías de remisión
            'despatch' => new ApiBuilder(),

            // Para documentos electrónicos
            'invoice', 
            'note', 
            'perception', 
            'retention', 
            'summary', 
            'voided' => new SeeBuilder($type),
            default => throw new \InvalidArgumentException("Sender type not supported: $type"),
        };
    }
}