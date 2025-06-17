<?php

namespace CodersFree\LaravelGreenter\Builders\Summary;

use CodersFree\LaravelGreenter\Builders\Company\CompanyBuilder;
use CodersFree\LaravelGreenter\Contracts\DocumentBuilderInterface;
use Greenter\Model\Summary\Summary;

class SummaryBuilder implements DocumentBuilderInterface
{
    public function build(array $data): Summary
    {
        return (new Summary())
            ->setCorrelativo($data['correlativo'] ?? null)
            ->setFecGeneracion(
                isset($data['fecGeneracion'])
                    ? new \DateTime($data['fecGeneracion'])
                    : null
            )
            ->setFecResumen(
                isset($data['fecResumen'])
                    ? new \DateTime($data['fecResumen'])
                    : null
            )
            ->setMoneda($data['moneda'] ?? 'PEN')
            ->setCompany(
                (new CompanyBuilder())->build()
            )
            ->setDetails(
                array_map(
                    fn($detail) => (new SummaryDetailBuilder())->build($detail),
                    $data['details'] ?? []
                )
            );
    }
}