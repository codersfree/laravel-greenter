<?php

namespace CodersFree\LaravelGreenter\Builders\Documents;

use CodersFree\LaravelGreenter\Builders\ClientBuilder;
use CodersFree\LaravelGreenter\Builders\CompanyBuilder;
use CodersFree\LaravelGreenter\Builders\PerceptionDetailBuilder;
use Greenter\Model\Perception\Perception;

class PerceptionBuilder implements DocumentBuilderInterface
{
    public function build(array $data): Perception
    {
        return (new Perception())
            ->setSerie($data['serie'] ?? null)
            ->setCorrelativo($data['correlativo'] ?? null)
            ->setFechaEmision(
                isset($data['fechaEmision'])
                    ? new \DateTime($data['fechaEmision'])
                    : new \DateTime()
            )
            ->setCompany(
                (new CompanyBuilder())->build()
            )
            ->setProveedor(
                isset($data['proveedor'])
                    ? (new ClientBuilder())->build($data['proveedor'])
                    : null
            )
            ->setRegimen($data['regimen'] ?? null)
            ->setTasa($data['tasa'] ?? null)
            ->setImpPercibido($data['impPercibido'] ?? null)
            ->setImpCobrado($data['impCobrado'] ?? null)
            ->setObservacion($data['observacion'] ?? null)
            ->setDetails(
                array_map(
                    function ($detail) {
                        return (new PerceptionDetailBuilder())->build($detail);
                    },
                    $data['details'] ?? []
                )
            );
    }
}