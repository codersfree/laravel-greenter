<?php

namespace CodersFree\LaravelGreenter\Builders\Sale;

use Greenter\Model\Sale\Document;

class DocumentBuilder
{
    public function build(array $data): Document
    {
        return (new Document())
            ->setTipoDoc($data['tipoDoc'] ?? null)
            ->setNroDoc($data['nroDoc'] ?? null);
    }
}