<?php

namespace Unicon\Yaml\Examples;

class Tree extends Simple
{
    /** @var list<self> */
    public array $children = [];
}