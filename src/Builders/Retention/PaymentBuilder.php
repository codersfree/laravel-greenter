<?php

namespace CodersFree\LaravelGreenter\Builders\Retention;

use Greenter\Model\Retention\Payment;

class PaymentBuilder
{
    public function build(array $data): Payment
    {
        return (new Payment())
            ->setMoneda($data['moneda'] ?? null)
            ->setImporte($data['importe'] ?? null)
            ->setFecha(
                isset($data['fecha'])
                    ? new \DateTime($data['fecha'])
                    : new \DateTime()
            );
    }
}