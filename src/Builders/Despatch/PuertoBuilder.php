<?php

namespace CodersFree\LaravelGreenter\Builders\Despatch;

use Greenter\Model\Despatch\Puerto;

class PuertoBuilder
{
    public function build(array $data): Puerto
    {
        return (new Puerto())
            ->setCodigo($data['codigo'] ?? null)
            ->setNombre($data['nombre'] ?? null);
    }
}