<?php

namespace CodersFree\LaravelGreenter\Builders\Despatch;

use Greenter\Model\Despatch\Direction;

class DirectionBuilder
{
    public function build(array $data): Direction
    {
        return (new Direction($data['ubigueo'] ?? null, $data['direccion'] ?? null))
            ->setCodLocal($data['codLocal'] ?? null)
            ->setRuc($data['ruc'] ?? null);
    }
}