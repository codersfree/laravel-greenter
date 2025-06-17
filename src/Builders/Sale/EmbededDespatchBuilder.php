<?php

namespace CodersFree\LaravelGreenter\Builders\Sale;

use CodersFree\LaravelGreenter\Builders\Client\ClientBuilder;
use CodersFree\LaravelGreenter\Builders\Despatch\DirectionBuilder;
use Greenter\Model\Sale\EmbededDespatch;

class EmbededDespatchBuilder
{
    public function build(array $data): EmbededDespatch
    {
        return (new EmbededDespatch())
            ->setLlegada(
                isset($data['llegada']) 
                ? (new DirectionBuilder())->build($data['llegada']) 
                : null
            )
            ->setPartida(
                isset($data['partida']) 
                ? (new DirectionBuilder())->build($data['partida']) 
                : null
            )
            ->setTransportista(
                isset($data['transportista']) 
                ? (new ClientBuilder())->build($data['transportista']) 
                : null
            )
            ->setNroLicencia($data['nroLicencia'] ?? null)
            ->setTranspPlaca($data['transpPlaca'] ?? null)
            ->setTranspCodeAuth($data['transpCodeAuth'] ?? null)
            ->setTranspMarca($data['transpMarca'] ?? null)
            ->setModTraslado($data['modTraslado'] ?? null)
            ->setPesoBruto($data['pesoBruto'] ?? null)
            ->setUndPesoBruto($data['undPesoBruto'] ?? null);
    }
}