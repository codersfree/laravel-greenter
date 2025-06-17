<?php

namespace CodersFree\LaravelGreenter\Builders\Sale;

use Greenter\Model\Sale\Charge;

class ChargeBuilder
{
    public function build(array $data): Charge
    {
        return (new Charge())
            ->setCodTipo($data['codTipo'] ?? null)
            ->setFactor($data['factor'] ?? null)
            ->setMonto($data['monto'] ?? null)
            ->setMontoBase($data['montoBase'] ?? null);
    }
}