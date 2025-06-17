<?php

namespace CodersFree\LaravelGreenter\Builders\Company;

use Greenter\Model\Company\Address;

class AddressBuilder
{
    public function build(array $data): Address
    {
        return (new Address())
            ->setUbigueo($data['ubigeo'] ?? null)
            ->setCodigoPais($data['codigoPais'] ?? 'PE')
            ->setDepartamento($data['departamento'] ?? null)
            ->setProvincia($data['provincia'] ?? null)
            ->setDistrito($data['distrito'] ?? null)
            ->setUrbanizacion($data['urbanizacion'] ?? null)
            ->setDireccion($data['direccion'] ?? null)
            ->setCodLocal($data['codLocal'] ?? '0000');
    }
}