<?php

namespace CodersFree\LaravelGreenter\Builders\Retention;

use CodersFree\LaravelGreenter\Builders\Client\ClientBuilder;
use CodersFree\LaravelGreenter\Builders\Company\CompanyBuilder;
use CodersFree\LaravelGreenter\Contracts\DocumentBuilderInterface;
use Greenter\Model\Retention\Retention;

class RetentionBuilder implements DocumentBuilderInterface
{
    public function build(array $data): Retention
    {
        return (new Retention())
            ->setSerie($data['serie'] ?? null)
            ->setCorrelativo($data['correlativo'] ?? null)
            ->setFechaEmision(
                isset($data['fechaEmision']) 
                    ? new \DateTime($data['fechaEmision']) 
                    : null
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
            ->setImpRetenido($data['impRetenido'] ?? null)
            ->setImpPagado($data['impPagado'] ?? null)
            ->setObservacion($data['observacion'] ?? null)
            ->setDetails(
                array_map(
                    fn($detail) => (new RetentionDetailBuilder())->build($detail), 
                    $data['details'] ?? []
                ) 
            )
            ;
    }
}