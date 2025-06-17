<?php

namespace CodersFree\LaravelGreenter\Builders\Despatch;

use Greenter\Model\Despatch\Driver;

class DriverBuilder
{
    public function build(array $data): Driver
    {
        return (new Driver())
            ->setTipo($data['tipo'] ?? null)
            ->setTipoDoc($data['tipoDoc'] ?? null)
            ->setNroDoc($data['nroDoc'] ?? null)
            ->setNombres($data['nombres'] ?? null)
            ->setApellidos($data['apellidos'] ?? null)
            ->setLicencia($data['licencia'] ?? null);
    }
}