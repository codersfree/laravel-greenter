<?php

namespace CodersFree\LaravelGreenter\Builders\Summary;

use CodersFree\LaravelGreenter\Builders\Sale\DocumentBuilder;
use Greenter\Model\Summary\SummaryDetail;

class SummaryDetailBuilder
{
    public function build(array $data): SummaryDetail
    {
        return (new SummaryDetail())
            ->setTipoDoc($data['tipoDoc'] ?? null)
            ->setSerieNro($data['serieNro'] ?? null)
            ->setClienteTipo($data['clienteTipo'] ?? null)
            ->setClienteNro($data['clienteNro'] ?? null)
            ->setDocReferencia(
                isset($data['docReferencia'])
                    ? (new DocumentBuilder())->build($data['docReferencia'])
                    : null
            )
            ->setPercepcion(
                isset($data['percepcion'])
                    ? (new SummaryPerceptionBuilder())->build($data['percepcion'])
                    : null
            )
            ->setEstado($data['estado'] ?? null)
            ->setTotal($data['total'] ?? null)
            ->setMtoOperGravadas($data['mtoOperGravadas'] ?? null)
            ->setMtoOperInafectas($data['mtoOperInafectas'] ?? null)
            ->setMtoOperExoneradas($data['mtoOperExoneradas'] ?? null)
            ->setMtoOperExportacion($data['mtoOperExportacion'] ?? null)
            ->setMtoOperGratuitas($data['mtoOperGratuitas'] ?? null)
            ->setMtoOtrosCargos($data['mtoOtrosCargos'] ?? null)
            ->setMtoIGV($data['mtoIGV'] ?? null)
            ->setMtoIvap($data['mtoIvap'] ?? null)
            ->setMtoISC($data['mtoISC'] ?? null)
            ->setMtoOtrosTributos($data['mtoOtrosTributos'] ?? null)
            ->setMtoIcbper($data['mtoIcbper'] ?? null);
    }
}