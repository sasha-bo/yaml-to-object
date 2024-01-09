<?php

namespace Unicon\Yaml\Examples;

class Simple
{
    public int $integerParameter = 1;
    /** @var positive-int */
    public int $positiveIntegerParameter;
    public ?bool $booleanOrNullParameter;
    public string $stringParameter = 'default';
}