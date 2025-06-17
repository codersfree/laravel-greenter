<?php

namespace CodersFree\LaravelGreenter\Contracts;

use Greenter\Model\DocumentInterface;

interface DocumentBuilderInterface
{
    public function build(array $data): DocumentInterface;
}