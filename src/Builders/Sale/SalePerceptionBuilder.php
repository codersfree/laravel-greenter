<?php

namespace CodersFree\LaravelGreenter\Builders\Sale;

use Greenter\Model\Sale\SalePerception;

class SalePerceptionBuilder
{
    public function build(array $data): SalePerception
    {
        return (new SalePerception())
            ->setCodReg($data['codReg'] ?? null)
            ->setPorcentaje($data['porcentaje'] ?? null)
            ->setMtoBase($data['mtoBase'] ?? null)
            ->setMto($data['mto'] ?? null)
            ->setMtoTotal($data['mtoTotal'] ?? null);
    }
}