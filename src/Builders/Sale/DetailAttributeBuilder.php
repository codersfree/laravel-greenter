<?php

namespace CodersFree\LaravelGreenter\Builders\Sale;

use Greenter\Model\Sale\DetailAttribute;

class DetailAttributeBuilder
{
    public function build(array $data): DetailAttribute
    {
        return (new DetailAttribute())
            ->setCode($data['code'] ?? null)
            ->setName($data['name'] ?? null)
            ->setValue($data['value'] ?? null)
            ->setFecInicio(
                isset($data['fecInicio'])
                    ? new \DateTime($data['fecInicio'])
                    : null
            )
            ->setFecFin(
                isset($data['fecFin'])
                    ? new \DateTime($data['fecFin'])
                    : null
            )
            ->setDuracion($data['duracion'] ?? null);
    }
}
