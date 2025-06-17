<?php

namespace CodersFree\LaravelGreenter\Builders\Despatch;

use Greenter\Model\Despatch\Vehicle;

class VehiculoBuilder
{
    public function build(array $data): Vehicle
    {
        return (new Vehicle())
            ->setPlaca($data['placa'] ?? null)
            ->setNroCirculacion($data['nroCirculacion'] ?? null)
            ->setCodEmisor($data['codEmisor'] ?? null)
            ->setNroAutorizacion($data['nroAutorizacion'] ?? null)
            ->setSecundarios(
                array_map(function ($secundario) {
                    return (new VehiculoBuilder())->build($secundario);
                }, $data['secundarios'] ?? [])
            );
    }
}