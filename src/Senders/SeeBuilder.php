<?php

namespace CodersFree\LaravelGreenter\Senders;

use Greenter\See;

class SeeBuilder
{
    public function build(): See
    {
        $mode = config('greenter.mode');
        $endpoints = config('greenter.endpoints.see');
        $company = config('greenter.company');

        $see = new See();
        $see->setCertificate(file_get_contents($company['certificate']));
        $see->setService(
            $mode === 'prod'
                ? $endpoints['prod']
                : $endpoints['beta']
        );
        $see->setClaveSOL(
            $company['ruc'],
            $company['clave_sol']['user'],
            $company['clave_sol']['password']
        );

        return $see;
    }
}
