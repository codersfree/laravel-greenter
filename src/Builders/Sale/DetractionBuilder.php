<?php

namespace CodersFree\LaravelGreenter\Builders\Sale;

use Greenter\Model\Sale\Detraction;

class DetractionBuilder
{
    public function build(array $data): Detraction
    {
        return (new Detraction())
            ->setPercent($data['percent'] ?? null)
            ->setMount($data['mount'] ?? null)
            ->setCtaBanco($data['ctaBanco'] ?? null)
            ->setCodMedioPago($data['codMedioPago'] ?? null)
            ->setCodBienDetraccion($data['codBienDetraccion'] ?? null)
            ->setValueRef($data['valueRef'] ?? null)
        ;
    }
}