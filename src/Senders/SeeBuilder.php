<?php

namespace CodersFree\LaravelGreenter\Senders;

use CodersFree\LaravelGreenter\Contracts\SenderInterface;
use CodersFree\LaravelGreenter\Exceptions\GreenterException;
use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;

class SeeBuilder implements SenderInterface
{
    private const FE_TYPES = ['invoice', 'note', 'voided', 'summary'];
    private const RET_TYPES = ['perception', 'retention'];

    public function __construct(
        protected string $type
    ) {}

    public function build(): See
    {
        $company = config('greenter.company');
        $certPath = config('greenter.company.certificate');

        if (!file_exists($certPath)) {
            throw new GreenterException("Certificate file not found: $certPath");
        }

        $see = new See();
        $see->setCertificate(
            file_get_contents($certPath)
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
