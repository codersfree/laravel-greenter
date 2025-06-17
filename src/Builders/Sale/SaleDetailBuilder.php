<?php

namespace CodersFree\LaravelGreenter\Builders\Sale;

use Greenter\Model\Sale\SaleDetail;

class SaleDetailBuilder
{
    public function build(array $data): SaleDetail
    {
        return (new SaleDetail())
            ->setUnidad($data['unidad'] ?? null)
            ->setCantidad($data['cantidad'] ?? null)
            ->setCodProducto($data['codProducto'] ?? null)
            ->setCodProdSunat($data['codProdSunat'] ?? null)
            ->setCodProdGS1($data['codProdGS1'] ?? null)
            ->setDescripcion($data['descripcion'] ?? null)
            ->setMtoValorUnitario($data['mtoValorUnitario'] ?? null)
            ->setCargos(
                array_map(function ($cargo) {
                    return (new ChargeBuilder())->build($cargo);
                }, $data['cargos'] ?? []) 
            )
            ->setDescuentos(
                array_map(function ($descuento) {
                    return (new ChargeBuilder())->build($descuento);
                }, $data['descuentos'] ?? [])
            )
            ->setDescuento($data['descuento'] ?? null)
            ->setMtoBaseIgv($data['mtoBaseIgv'] ?? null)
            ->setPorcentajeIgv($data['porcentajeIgv'] ?? null)
            ->setIgv($data['igv'] ?? null)
            ->setTipAfeIgv($data['tipAfeIgv'] ?? null)
            ->setMtoBaseIsc($data['mtoBaseIsc'] ?? null)
            ->setPorcentajeIsc($data['porcentajeIsc'] ?? null)
            ->setIsc($data['isc'] ?? null)
            ->setTipSisIsc($data['tipSisIsc'] ?? null)
            ->setMtoBaseOth($data['mtoBaseOth'] ?? null)
            ->setPorcentajeOth($data['porcentajeOth'] ?? null)
            ->setOtroTributo($data['otroTributo'] ?? null)
            ->setIcbper($data['icbper'] ?? null)
            ->setFactorIcbper($data['factorIcbper'] ?? null)
            ->setTotalImpuestos($data['totalImpuestos'] ?? null)
            ->setMtoPrecioUnitario($data['mtoPrecioUnitario'] ?? null)
            ->setMtoValorVenta($data['mtoValorVenta'] ?? null)
            ->setMtoValorGratuito($data['mtoValorGratuito'] ?? null)
            ->setAtributos(
                array_map(function ($atributo) {
                    return (new DetailAttributeBuilder())->build($atributo);
                }, $data['atributos'] ?? [])
            )
           ;
    }
}