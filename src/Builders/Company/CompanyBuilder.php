<?php

namespace CodersFree\LaravelGreenter\Builders\Company;

use Greenter\Model\Company\Company;

class CompanyBuilder
{
    public function build(): Company
    {
        $company = config('greenter.company');

        return (new Company())
            ->setRuc($company['ruc'] ?? null)
            ->setRazonSocial($company['razonSocial'] ?? null)
            ->setNombreComercial($company['nombreComercial'] ?? null)
            ->setAddress(
                isset($company['address'])
                ? (new AddressBuilder())->build($company['address'])
                : null
            );
    }
}