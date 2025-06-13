<?php

namespace CodersFree\LaravelGreenter\Builders\Documents;

use Greenter\Model\DocumentInterface;

interface DocumentBuilderInterface
{
    public function build(array $data): DocumentInterface;
}