<?php

namespace CodersFree\LaravelGreenter\Builders\See;

use Greenter\Model\DocumentInterface;

interface DocumentBuilderInterface
{
    public function build(array $data): DocumentInterface;
}