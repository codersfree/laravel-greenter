<?php

namespace CodersFree\LaravelGreenter\Senders;

use CodersFree\LaravelGreenter\Contracts\SenderInterface;
use CodersFree\LaravelGreenter\Exceptions\GreenterException;
use Greenter\Api;

class ApiBuilder implements SenderInterface
{
    public function build(): Api
    {
        $mode = config('greenter.mode');
        $endpoints = config('greenter.endpoints.api');
        $company = config('greenter.company');
        $certPath = config('greenter.company.certificate');

        if (!file_exists($certPath)) {
            throw new GreenterException("Certificate file not found: $certPath");
        }

        $api = new Api(
            $mode === 'prod' ?
                $endpoints['prod'] :
                $endpoints['beta']
        );

        $api->setBuilderOptions([
            'strict_variables' => true,
            'optimizations' => 0,
            'debug' => true,
            'cache' => false,
        ]);

        $api->setApiCredentials(
            $mode === 'prod'
                ? $company['credentials']['client_id']
                : 'test-85e5b0ae-255c-4891-a595-0b98c65c9854',
            $mode === 'prod'
                ? $company['credentials']['client_secret']
                : 'test-Hty/M6QshYvPgItX2P0+Kw=='
        );

        $api->setClaveSOL(
            $company['ruc'],
            $mode === 'prod'
                ? $company['clave_sol']['user']
                : 'MODDATOS',
            $mode === 'prod'
                ? $company['clave_sol']['password']
                : 'MODDATOS'
        );

        $api->setCertificate(
            file_get_contents($company['certificate'])
        );

        return $api;
    }
}
