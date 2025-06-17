<?php

namespace CodersFree\LaravelGreenter\Services;

use CodersFree\LaravelGreenter\Exceptions\GreenterException;
use CodersFree\LaravelGreenter\Factories\DocumentBuilderFactory;
use CodersFree\LaravelGreenter\Factories\SenderFactory;
use CodersFree\LaravelGreenter\Factories\XmlBuilderFactory;
use CodersFree\LaravelGreenter\Models\SunatResponse;
use CodersFree\LaravelGreenter\Models\XmlSigned;
use CodersFree\LaravelGreenter\Senders\SeeBuilder;
use Greenter\Model\Response\SummaryResult;
use Greenter\XMLSecLibs\Sunat\SignedXml;
use Termwind\Components\Dd;

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

    public function getXmlSigned(string $type, array $data)
    {
        $document = (DocumentBuilderFactory::create($type))->build($data);
        $xml = (XmlBuilderFactory::create($type))->build($document);

        $certPath = config('greenter.company.certificate');

        if (!file_exists($certPath)) {
            throw new GreenterException("Certificate file not found: $certPath");
        }

        $signer = new SignedXml();
        $signer->setCertificate(file_get_contents($certPath));
        $xmlSigned = $signer->signXml($xml);

        return new XmlSigned($type, $document, $xmlSigned);
    }

    public function send(string $type, array $data): SunatResponse
    {
        try {
            $document = (DocumentBuilderFactory::create($type))->build($data);

            $sender = (SenderFactory::create($type))->build();

            $result = $sender->send($document);
            $result = $this->processResult($result, $sender);

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

    public function sendXml(XmlSigned $xmlSigned)
    {
        try {
            $sender = (SenderFactory::create($xmlSigned->getType()))->build();
            $xml = $xmlSigned->getXml();

            $result = $xmlSigned->getType() === 'despatch'
                ? $sender->sendXml($xmlSigned->getDocument()->getName(), $xml)
                : $sender->sendXmlFile($xml);

            $result = $this->processResult($result, $sender);

            return new SunatResponse(
                $xmlSigned->getDocument(),
                $result->getCdrZip(),
                $result->getCdrResponse(),
                $xml
            );

        } catch (\Throwable $e) {
            throw new GreenterException(
                $e->getMessage(),
                (int)$e->getCode(),
                $e
            );
        }
    }

    private function processResult($result, $sender)
    {
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

        return $result;
    }
}
