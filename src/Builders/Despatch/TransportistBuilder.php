<?php

namespace CodersFree\LaravelGreenter\Builders\Despatch;

use Greenter\Model\Despatch\Transportist;

class TransportistBuilder
{
    public function build(array $data): Transportist
    {
        return (new Transportist())
            ->setTipoDoc($data['tipoDoc'] ?? null)
            ->setNumDoc($data['numDoc'] ?? null)
            ->setRznSocial($data['rznSocial'] ?? null)
            ->setNroMtc($data['nroMtc'] ?? null)
            ->setPlaca($data['placa'] ?? null)
            ->setChoferTipoDoc($data['choferTipoDoc'] ?? null)
            ->setChoferDoc($data['choferDoc'] ?? null);
    }
}