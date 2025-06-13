<?php

namespace CodersFree\LaravelGreenter\Services;

use CodersFree\LaravelGreenter\Builders\See\DocumentBuilderFactory;
use CodersFree\LaravelGreenter\Exceptions\GreenterException;
use CodersFree\LaravelGreenter\Models\SunatResponse;
use CodersFree\LaravelGreenter\Senders\SeeBuilder;

class SeeSender extends BaseSender
{
    public function __construct(
        protected SeeBuilder $seeBuilder,
    ) {}

    public function send(string $type, array $data): SunatResponse
    {
        try {
            $builder = DocumentBuilderFactory::create($type);
            $document = $builder->build($data);
            
            $see = $this->seeBuilder->build($type);
            $result = $see->send($document);
            
            if (!$result->isSuccess()) {
                throw new GreenterException(
                    $result->getError()->getMessage(),
                    $result->getError()->getCode()
                );
            }

            return new SunatResponse(
                $document,
                $result->getCdrZip(),
                $result->getCdrResponse(),
                $see->getFactory()->getLastXml()
            );

        } catch (\Throwable $e) {
            throw new GreenterException($e->getMessage(), $e->getCode(), $e);
        }
    }
}