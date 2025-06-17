<?php

namespace CodersFree\LaravelGreenter\Models;

use Greenter\Model\DocumentInterface;

class XmlSigned
{
    public function __construct(
        private string $type,
        private DocumentInterface $document,
        private string $xml,
    ) {}

    public function getType(): string
    {
        return $this->type;
    }

    public function getDocument(): DocumentInterface
    {
        return $this->document;
    }

    public function getXml(): string
    {
        return $this->xml;
    }
}