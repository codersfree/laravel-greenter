<?php

namespace CodersFree\LaravelGreenter\Builders\Retention;

use Greenter\Model\Retention\RetentionDetail;

class RetentionDetailBuilder
{
    public function build(array $data): RetentionDetail
    {
        return (new RetentionDetail())
            ->setTipoDoc($data['tipoDoc'] ?? null)
            ->setNumDoc($data['numDoc'] ?? null)
            ->setFechaEmision(
                isset($data['fechaEmision']) 
                    ? new \DateTime($data['fechaEmision']) 
                    : null
            )
            ->setImpTotal($data['impTotal'] ?? null)
            ->setMoneda($data['moneda'] ?? null)
            ->setPagos(
                array_map(
                    fn($pago) => (new PaymentBuilder())->build($pago), 
                    $data['pagos'] ?? []
                )
            )
            ->setFechaRetencion(
                isset($data['fechaRetencion']) 
                    ? new \DateTime($data['fechaRetencion']) 
                    : null
            )
            ->setImpRetenido($data['impRetenido'] ?? null)
            ->setImpPagar($data['impPagar'] ?? null)
            ->setTipoCambio(
                isset($data['tipoCambio']) 
                    ? (new ExchangeBuilder())->build($data['tipoCambio'])
                    : null
            );
    }
}