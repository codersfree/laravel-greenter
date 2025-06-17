<?php

namespace CodersFree\LaravelGreenter\Builders\Despatch;

use Greenter\Model\Despatch\AdditionalDoc;

class AdditionalDocBuilder
{
    public function build(array $data): AdditionalDoc
    {
        return (new AdditionalDoc())
            ->setTipoDesc($data['tipoDesc'] ?? null)
            ->setTipo($data['tipo'] ?? null)
            ->setNro($data['nro'] ?? null)
            ->setEmisor($data['emisor'] ?? null);
    }
}