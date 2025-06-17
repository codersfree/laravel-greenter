<?php

namespace CodersFree\LaravelGreenter\Builders\Despatch;

use Greenter\Model\Despatch\Shipment;

class ShipmentBuilder
{
    public function build(array $data): Shipment
    {
        return (new Shipment())
            ->setCodTraslado($data['codTraslado'] ?? null)
            ->setDesTraslado($data['desTraslado'] ?? null)
            ->setSustentoPeso($data['sustentoPeso'] ?? null)
            ->setIndTransbordo($data['indTransbordo'] ?? null)
            ->setIndicadores($data['indicadores'] ?? [])
            ->setPesoItems($data['pesoItems'] ?? null)
            ->setPesoTotal($data['pesoTotal'] ?? null)
            ->setUndPesoTotal($data['undPesoTotal'] ?? null)
            ->setNumBultos($data['numBultos'] ?? null)
            ->setModTraslado($data['modTraslado'] ?? null)
            ->setFecTraslado(
                isset($data['fecTraslado'])
                    ? new \DateTime($data['fecTraslado'])
                    : null
            )
            ->setNumContenedor($data['numContenedor'] ?? null)
            ->setContenedores($data['contenedores'] ?? [])
            ->setCodPuerto($data['codPuerto'] ?? null)
            ->setPuerto(
                isset($data['puerto'])
                    ? (new PuertoBuilder())->build($data['puerto'])
                    : null
            )
            ->setAeropuerto(
                isset($data['aeropuerto'])
                    ? (new PuertoBuilder())->build($data['aeropuerto'])
                    : null
            )
            ->setTransportista(
                isset($data['transportista'])
                    ? (new TransportistBuilder())->build($data['transportista'])
                    : null
            )
            ->setVehiculo(
                isset($data['vehiculo'])
                    ? (new VehiculoBuilder())->build($data['vehiculo'])
                    : null
            )
            ->setChoferes(
                array_map(function ($chofer) {
                    return (new DriverBuilder())->build($chofer);
                }, $data['choferes'] ?? [])
            )
            ->setLlegada(
                isset($data['llegada'])
                    ? (new DirectionBuilder())->build($data['llegada'])
                    : null
            )
            ->setPartida(
                isset($data['partida'])
                    ? (new DirectionBuilder())->build($data['partida'])
                    : null
            );
    }
}
