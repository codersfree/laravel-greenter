<?php

namespace CodersFree\LaravelGreenter\Services;

use CodersFree\LaravelGreenter\Builders\Api\DocumentBuilderFactory;
use CodersFree\LaravelGreenter\Exceptions\GreenterException;
use CodersFree\LaravelGreenter\Models\SunatResponse;
use CodersFree\LaravelGreenter\Senders\ApiBuilder;

class ApiSender extends BaseSender
{
    public function __construct(
        protected ApiBuilder $apiBuilder,
    ) {}

    public function send(string $type, array $data): SunatResponse
    {
        try {
            
            $builder = DocumentBuilderFactory::create($type);
            $document = $builder->build($data);

            $api = $this->apiBuilder->build();
            $result = $api->send($document);

            if (!$result->isSuccess()) {
                throw new GreenterException(
                    $result->getError()->getMessage(),
                    $result->getError()->getCode()
                );
            }

            $ticket = $result->getTicket();

            $result = $api->getStatus($ticket);

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
                $api->getLastXml(),
            );

        } catch (\Throwable $e) {
            throw new GreenterException($e->getMessage(), $e->getCode(), $e);
        }
    }
}