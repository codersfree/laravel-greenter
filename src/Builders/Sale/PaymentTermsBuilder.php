<?php

namespace CodersFree\LaravelGreenter\Builders\Sale;

use Greenter\Model\Sale\PaymentTerms;

class PaymentTermsBuilder
{
    public function build(array $data): PaymentTerms
    {
        return (new PaymentTerms())
            ->setMoneda($data['moneda'] ?? null)
            ->setTipo($data['tipo'] ?? null)
            ->setMonto($data['monto'] ?? null);
    }
}