<?php

namespace CodersFree\LaravelGreenter\Senders;

use CodersFree\LaravelGreenter\Contracts\SenderInterface;
use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;

class SeeBuilder implements SenderInterface
{
    public function __construct(
        protected string $type
    ) {}

    public function build(): See
    {
        $company = config('greenter.company');

        $see = new See();
        $see->setCertificate(
            file_get_contents($company['certificate'])
        );
        $see->setService($this->getEndpoint());
        $see->setClaveSOL(
            $company['ruc'],
            $company['clave_sol']['user'],
            $company['clave_sol']['password']
        );

        return $see;
    }

    public function getEndpoint(): string
    {
        $mode = config('greenter.mode');
        $endpoints = config('greenter.endpoints');

        return match ($this->type) {
            'invoice', 
            'note',
            'voided',
            'summary' => $mode === 'prod'
                ? $endpoints['fe']['prod']
                : $endpoints['fe']['beta'],

            'perception', 
            'retention'=> $mode === 'prod'
                ? $endpoints['retencion']['prod']
                : $endpoints['retencion']['beta'],

            default => throw new \InvalidArgumentException("Tipo de documento no soportado: $this->type"),
        };

        
    }
}
