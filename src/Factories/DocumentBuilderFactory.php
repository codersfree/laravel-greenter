<?php

namespace CodersFree\LaravelGreenter\Factories;

use CodersFree\LaravelGreenter\Builders\Despatch\DespatchBuilder;
use CodersFree\LaravelGreenter\Builders\Perception\PerceptionBuilder;
use CodersFree\LaravelGreenter\Builders\Retention\RetentionBuilder;
use CodersFree\LaravelGreenter\Builders\Sale\InvoiceBuilder;
use CodersFree\LaravelGreenter\Builders\Sale\NoteBuilder;
use CodersFree\LaravelGreenter\Builders\Summary\SummaryBuilder;
use CodersFree\LaravelGreenter\Builders\Voided\VoidedBuilder;
use CodersFree\LaravelGreenter\Contracts\DocumentBuilderInterface;
use CodersFree\LaravelGreenter\Exceptions\GreenterException;

class DocumentBuilderFactory
{
    public static function create(string $type): DocumentBuilderInterface
    {
        return match ($type) {
            'despatch' => new DespatchBuilder(),
            'invoice' => new InvoiceBuilder(),
            'note' => new NoteBuilder(),
            'perception' => new PerceptionBuilder(),
            'retention' => new RetentionBuilder(),
            'summary' => new SummaryBuilder(),
            'voided' => new VoidedBuilder(),
            default => throw new GreenterException("Tipo de documento no soportado"),
        };
    }
}