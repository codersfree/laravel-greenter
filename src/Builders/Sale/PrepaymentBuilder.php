<?php

namespace CodersFree\LaravelGreenter\Builders\Sale;

use Greenter\Model\Sale\Prepayment;

class PrepaymentBuilder
{
    public function build(array $data): Prepayment
    {
        return (new Prepayment())
            ->setTipoDocRel($data['tipoDocRel'] ?? null)
            ->setNroDocRel($data['nroDocRel'] ?? null)
            ->setTotal($data['total'] ?? null);
    }
}