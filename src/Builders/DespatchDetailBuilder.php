<?php

namespace CodersFree\LaravelGreenter\Builders;

use Greenter\Model\Despatch\DespatchDetail;

class DespatchDetailBuilder
{
    public function build(array $data): DespatchDetail
    {
        return (new DespatchDetail())
            ->setCodigo($data['codigo'] ?? null)
            ->setDescripcion($data['descripcion'] ?? null)
            ->setUnidad($data['unidad'] ?? null)
            ->setCantidad($data['cantidad'] ?? null)
            ->setCodProdSunat($data['codProdSunat'] ?? null)
            ->setAtributos(
                array_map(function ($attribute) {
                    return (new DetailAttributeBuilder())->build($attribute);
                }, $data['atributos'] ?? [])
            );
    }
}