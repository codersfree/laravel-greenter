<?php

namespace CodersFree\LaravelGreenter\Builders\Client;

use CodersFree\LaravelGreenter\Builders\Company\AddressBuilder;
use Greenter\Model\Client\Client;

class ClientBuilder
{
    public function build(array $data): Client
    {
        return (new Client())
            ->setTipoDoc($data['tipoDoc'] ?? null)
            ->setNumDoc($data['numDoc'] ?? null)
            ->setRznSocial($data['rznSocial'] ?? null)
            ->setAddress(
                isset($data['address'])
                ? (new AddressBuilder())->build($data['address']) 
                : null
            )
            ->setEmail($data['email'] ?? null)
            ->setTelephone($data['telephone'] ?? null);
    }
}