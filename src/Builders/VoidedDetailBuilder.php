<?php

namespace CodersFree\LaravelGreenter\Builders;

use Greenter\Model\Voided\VoidedDetail;

class VoidedDetailBuilder
{
    public function build(array $data): VoidedDetail
    {
        return (new VoidedDetail())
            ->setTipoDoc($data['tipoDoc'] ?? null)
            ->setSerie($data['serie'] ?? null)
            ->setCorrelativo($data['correlativo'] ?? null)        
            ->setDesMotivoBaja($data['desMotivoBaja'] ?? null);
    }
}