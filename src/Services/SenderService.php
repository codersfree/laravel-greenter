<?php

namespace CodersFree\LaravelGreenter\Services;

use CodersFree\LaravelGreenter\Exceptions\GreenterException;
use CodersFree\LaravelGreenter\Factories\DocumentBuilderFactory;
use CodersFree\LaravelGreenter\Factories\SenderFactory;
use CodersFree\LaravelGreenter\Models\SunatResponse;
use CodersFree\LaravelGreenter\Senders\SeeBuilder;
use Greenter\Model\Response\SummaryResult;

class SenderService
{
    public function setCompany(array $company): self
    {
        $defaultCompany = config('greenter.company');
        $customCompany = array_replace_recursive($defaultCompany, $company);

        config([
            'greenter.company' => $customCompany
        ]);

        return $this;
    }

    public function send(string $type, array $data): SunatResponse
    {
        try {
            $builder = DocumentBuilderFactory::create($type);
            $document = $builder->build($data);
            
            $sender = (SenderFactory::create($type))->build();

            $result = $sender->send($document);
            
            if (!$result->isSuccess()) {
                throw new GreenterException(
                    $result->getError()->getMessage(),
                    (int)$result->getError()->getCode()
                );
            }

            if ($result instanceof SummaryResult) {
                $ticket = $result->getTicket();
                $result = $sender->getStatus($ticket);
                
                if (!$result->isSuccess()) {

                    throw new GreenterException(
                        $result->getError()->getMessage(),
                        (int)$result->getError()->getCode()
                    );
                }

            }

            return new SunatResponse(
                $document,
                $result->getCdrZip(),
                $result->getCdrResponse(),
                $type === 'despatch'
                    ? $sender->getLastXml()
                    : $sender->getFactory()->getLastXml()
            );

        } catch (\Throwable $e) {
            throw new GreenterException(
                $e->getMessage(), 
                (int)$e->getCode(), 
                $e
            );
        }
    }

    public function getXmlSigned(string $type, array $data)
    {
        $builder = DocumentBuilderFactory::create($type);
        $document = $builder->build($data);
        
        $sender = (new SeeBuilder($type))->build();
        $xml = $sender->getXmlSigned($document);

        return $xml;
    }
}