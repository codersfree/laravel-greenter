<?php

namespace CodersFree\LaravelGreenter\Builders;

use Greenter\Model\Summary\SummaryPerception;

class SummaryPerceptionBuilder
{
    public function build(array $data): SummaryPerception
    {
        return (new SummaryPerception())
            ->setCodReg($data['codReg'] ?? null)
            ->setTasa($data['tasa'] ?? null)
            ->setMtoBase($data['mtoBase'] ?? null)
            ->setMtoTotal($data['mtoTotal'] ?? null);
    }
}