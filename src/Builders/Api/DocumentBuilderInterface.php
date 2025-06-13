<?php

namespace CodersFree\LaravelGreenter\Builders\Api;

use Greenter\Model\DocumentInterface;

interface DocumentBuilderInterface
{
    public function build(array $data): DocumentInterface;
}