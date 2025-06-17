<?php

namespace CodersFree\LaravelGreenter\Models;

use Greenter\Model\DocumentInterface;
use Greenter\Model\Response\CdrResponse;
use Greenter\Report\XmlUtils;

class SunatResponse
{
    public function __construct(
        private DocumentInterface $document,
        private ?string $cdrZip,
        private CdrResponse $cdrResponse,
        private string $xml,
    ) {}

    public function getDocument(): DocumentInterface
    {
        return $this->document;
    }

    public function getCdrZip(): ?string
    {
        return $this->cdrZip;
    }

    public function getCdrResponse(): CdrResponse
    {
        return $this->cdrResponse;
    }

    public function getXml(): string
    {
        return $this->xml;
    }

    public function getHash(): string
    {
        return (new XmlUtils())->getHashSign($this->xml);
    }

    public function readCdr(): array
    {
        return [
            'id' => $this->cdrResponse->getId(),
            'code' => (int) $this->cdrResponse->getCode(),
            'description' => $this->cdrResponse->getDescription(),
            'notes' => $this->cdrResponse->getNotes(),
            'reference' => $this->cdrResponse->getReference(),
        ];
    }
}