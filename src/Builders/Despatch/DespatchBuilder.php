<?php

namespace CodersFree\LaravelGreenter\Builders\Despatch;

use CodersFree\LaravelGreenter\Builders\Client\ClientBuilder;
use CodersFree\LaravelGreenter\Builders\Company\CompanyBuilder;
use CodersFree\LaravelGreenter\Builders\Sale\DocumentBuilder;
use CodersFree\LaravelGreenter\Contracts\DocumentBuilderInterface;
use Greenter\Model\Despatch\Despatch;

class DespatchBuilder implements DocumentBuilderInterface
{
    public function build(array $data): Despatch
    {
        return (new Despatch())
            ->setVersion($data['version'] ?? null)
            ->setTipoDoc($data['tipoDoc'] ?? null)
            ->setSerie($data['serie'] ?? null)
            ->setCorrelativo($data['correlativo'] ?? null)
            ->setObservacion($data['observacion'] ?? null)
            ->setFechaEmision(
                $data['fechaEmision']
                    ? new \DateTime($data['fechaEmision'])
                    : null
            )
            ->setCompany(
                (new CompanyBuilder())->build()
            )
            ->setDestinatario(
                isset($data['destinatario'])
                    ? (new ClientBuilder())->build($data['destinatario'])
                    : null
            )
            ->setTercero(
                isset($data['tercero'])
                    ? (new ClientBuilder())->build($data['tercero'])
                    : null
            )
            ->setComprador(
                isset($data['comprador'])
                    ? (new ClientBuilder())->build($data['comprador'])
                    : null
            )
            ->setEnvio(
                isset($data['envio'])
                    ? (new ShipmentBuilder())->build($data['envio'])
                    : null
            )
            ->setDocBaja(
                isset($data['docBaja'])
                    ? (new DocumentBuilder())->build($data['docBaja'])
                    : null
            )
            ->setRelDoc(
                isset($data['relDoc'])
                    ? (new DocumentBuilder())->build($data['relDoc'])
                    : null
            )
            ->setAddDocs(
                array_map(
                    fn($doc) => (new AdditionalDocBuilder())->build($doc),
                    $data['addDocs'] ?? []
                )
            )
            ->setDetails(
                array_map(
                    fn($detail) => (new DespatchDetailBuilder())->build($detail),
                    $data['details'] ?? []
                )
            );
    }
}
