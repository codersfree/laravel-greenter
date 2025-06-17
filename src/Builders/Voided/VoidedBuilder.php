<?php

namespace CodersFree\LaravelGreenter\Builders\Voided;

use CodersFree\LaravelGreenter\Builders\Company\CompanyBuilder;
use CodersFree\LaravelGreenter\Contracts\DocumentBuilderInterface;
use Greenter\Model\Voided\Voided;

class VoidedBuilder implements DocumentBuilderInterface
{
    public function build(array $data): Voided
    {
        return (new Voided())
            ->setCorrelativo($data['correlativo'] ?? null)
            ->setFecGeneracion(
                isset($data['fecGeneracion']) 
                    ? new \DateTime($data['fecGeneracion']) 
                    : null
            )
            ->setFecComunicacion(
                isset($data['fecComunicacion']) 
                    ? new \DateTime($data['fecComunicacion']) 
                    : null
            )
            ->setCompany(
                (new CompanyBuilder())->build()
            )
            ->setDetails(
                array_map(
                    fn($detail) => (new VoidedDetailBuilder())->build($detail),
                    $data['details'] ?? []
                )
            );
    }
}