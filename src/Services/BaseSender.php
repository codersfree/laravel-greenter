<?php

namespace CodersFree\LaravelGreenter\Services;

use CodersFree\LaravelGreenter\Models\SunatResponse;

abstract class BaseSender
{
    public function setCompany(array $company): self
    {
        $defaultCompany = config('greenter.company');
        $customCompany = array_replace_recursive($defaultCompany, $company);

        config([
            'greenter.company' => $customCompany
        ]);

        return $this;
    }

    abstract public function send(string $type, array $data): SunatResponse;
}