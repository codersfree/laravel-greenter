<?php

namespace CodersFree\LaravelGreenter\Builders\Sale;

use Greenter\Model\Sale\Legend;

class LegendBuilder
{
    public function build(array $data): Legend
    {
        return (new Legend())
            ->setCode($data['code'] ?? null)
            ->setValue($data['value'] ?? null);
    }
}