<?php

namespace CodersFree\LaravelGreenter\Builders;

use Greenter\Model\Despatch\Direction;

class DirectionBuilder
{
    public function build(array $data): Direction
    {
        return (new Direction($data['ubigueo'], $data['direccion']))
            ->setCodLocal($data['codLocal'] ?? null)
            ->setRuc($data['ruc'] ?? null);
    }
}