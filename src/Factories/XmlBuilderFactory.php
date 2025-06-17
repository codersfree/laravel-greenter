<?php

namespace CodersFree\LaravelGreenter\Factories;

use Greenter\Xml\Builder\DespatchBuilder;
use Greenter\Xml\Builder\InvoiceBuilder;
use Greenter\Xml\Builder\NoteBuilder;
use Greenter\Xml\Builder\PerceptionBuilder;
use Greenter\Xml\Builder\RetentionBuilder;
use Greenter\Xml\Builder\SummaryBuilder;
use Greenter\Xml\Builder\VoidedBuilder;

class XmlBuilderFactory
{
    /**
     * Create an XML builder instance based on the type.
     *
     * @param string $type
     * @return mixed
     */
    public static function create(string $type)
    {
        return match ($type) {
            'despatch' => new DespatchBuilder(),
            'invoice' => new InvoiceBuilder(),
            'note' => new NoteBuilder(),
            'perception' => new PerceptionBuilder(),
            'retention' => new RetentionBuilder(),
            'summary' => new SummaryBuilder(),
            'voided' => new VoidedBuilder(),
            default => throw new \InvalidArgumentException("XML Builder type not supported: $type"),
        };
    }
}