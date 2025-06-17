<?php

namespace CodersFree\LaravelGreenter\Builders\Sale;

use Greenter\Model\Sale\Cuota;

class CuotaBuilder
{
    public function build(array $data): Cuota
    {
        return (new Cuota())
            ->setMoneda($data['moneda'] ?? null)
            ->setMonto($data['monto'] ?? null)
            ->setFechaPago(
                isset($data['fechaPago'])
                    ? new \DateTime($data['fechaPago'])
                    : null
            );
    }
}