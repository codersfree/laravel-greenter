<?php

namespace CodersFree\LaravelGreenter\Senders;

use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;

class SeeBuilder
{
    public function build(string $type): See
    {
        $company = config('greenter.company');

        $see = new See();
        $see->setCertificate(file_get_contents($company['certificate']));
        $see->setService($this->getEndpoint($type));
        $see->setClaveSOL(
            $company['ruc'],
            $company['clave_sol']['user'],
            $company['clave_sol']['password']
        );

        return $see;
    }

    public function getEndpoint(string $type): string
    {
        $mode = config('greenter.mode');
        $endpoints = config('greenter.endpoints');

        return match ($type) {
            'invoice', 'note' => $mode === 'prod'
                ? $endpoints['fe']['prod']
                : $endpoints['fe']['beta'],

            'perception', 'retention' => $mode === 'prod'
                ? $endpoints['retencion']['prod']
                : $endpoints['retencion']['beta'],

            default => throw new \InvalidArgumentException("Tipo de documento no soportado: $type"),
        };
    }
}
