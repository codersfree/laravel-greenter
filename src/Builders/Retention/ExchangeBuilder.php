<?php

namespace CodersFree\LaravelGreenter\Builders\Retention;

use Greenter\Model\Retention\Exchange;

class ExchangeBuilder
{
    public function build(array $data): Exchange
    {
        return (new Exchange())
            ->setMonedaRef($data['monedaRef'] ?? null)
            ->setMonedaObj($data['monedaObj'] ?? null)
            ->setFactor($data['factor'] ?? null)
            ->setFecha(
                isset($data['fecha'])
                    ? new \DateTime($data['fecha'])
                    : new \DateTime()
            );
    }
}