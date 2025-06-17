<?php

namespace CodersFree\LaravelGreenter\Builders\Perception;

use CodersFree\LaravelGreenter\Builders\Retention\ExchangeBuilder;
use CodersFree\LaravelGreenter\Builders\Retention\PaymentBuilder;
use Greenter\Model\Perception\PerceptionDetail;

class PerceptionDetailBuilder
{
    public function build(array $data): PerceptionDetail
    {
        return (new PerceptionDetail())
            ->setTipoDoc($data['tipoDoc'] ?? null)
            ->setNumDoc($data['numDoc'] ?? null)
            ->setFechaEmision(
                isset($data['fechaEmision'])
                    ? new \DateTime($data['fechaEmision'])
                    : new \DateTime()
            )
            ->setImpTotal($data['impTotal'] ?? null)
            ->setMoneda($data['moneda'] ?? null)
            ->setCobros(
                array_map(
                    function ($cobro) {
                        return (new PaymentBuilder())->build($cobro);
                    },
                    $data['cobros'] ?? []
                )
            )
            ->setFechaPercepcion(
                isset($data['fechaPercepcion'])
                    ? new \DateTime($data['fechaPercepcion'])
                    : new \DateTime()
            )
            ->setImpPercibido($data['impPercibido'] ?? null)
            ->setImpCobrar($data['impCobrar'] ?? null)
            ->setTipoCambio(
                isset($data['tipoCambio'])
                    ? (new ExchangeBuilder())->build($data['tipoCambio'])
                    : null
            );
    }
}