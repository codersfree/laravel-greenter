<?php

namespace CodersFree\LaravelGreenter\Services;

class XmlService
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
}