<?php

namespace Unicon\tests;

use PHPUnit\Framework\TestCase;
use Unicon\Yaml\Examples\ArrayOfSimple;
use Unicon\Yaml\Yaml;

include_once(__DIR__.'/examples/ArrayOfSimple.php');

final class ArrayOfSimpleTest extends TestCase
{
    private Yaml $yaml;
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->yaml = new Yaml(ArrayOfSimple::class);
    }

    public function testArraySuccess1(): void
    {
        $object = $this->yaml->read(__DIR__.'/examples/array-of-simples-success-1.yml');
        $this->assertInstanceOf(ArrayOfSimple::class, $object);
        $this->assertSame(true, $object->booleanParameter);
        $this->assertCount(3, $object->simples);
        $this->assertSame(777, $object->simples[0]->integerParameter);
        $this->assertSame(1, $object->simples[0]->positiveIntegerParameter);
        $this->assertSame(null, $object->simples[0]->booleanOrNullParameter);
        $this->assertSame('888', $object->simples[0]->stringParameter);
        $this->assertSame(888, $object->simples[1]->integerParameter);
        $this->assertSame(2, $object->simples[1]->positiveIntegerParameter);
        $this->assertSame(true, $object->simples[1]->booleanOrNullParameter);
        $this->assertSame('aaa', $object->simples[1]->stringParameter);
        $this->assertSame(999, $object->simples[2]->integerParameter);
        $this->assertSame(3, $object->simples[2]->positiveIntegerParameter);
        $this->assertSame(false, $object->simples[2]->booleanOrNullParameter);
        $this->assertSame('bbb', $object->simples[2]->stringParameter);
    }

    public function testArraySuccess2(): void
    {
        $object = $this->yaml->read(__DIR__.'/examples/array-of-simples-success-2.yml');
        $this->assertInstanceOf(ArrayOfSimple::class, $object);
        $this->assertSame(true, $object->booleanParameter);
        $this->assertCount(3, $object->simples);
        $this->assertSame(777, $object->simples[0]->integerParameter);
        $this->assertSame(1, $object->simples[0]->positiveIntegerParameter);
        $this->assertSame(null, $object->simples[0]->booleanOrNullParameter);
        $this->assertSame('888', $object->simples[0]->stringParameter);
        $this->assertSame(888, $object->simples[1]->integerParameter);
        $this->assertSame(2, $object->simples[1]->positiveIntegerParameter);
        $this->assertSame(true, $object->simples[1]->booleanOrNullParameter);
        $this->assertSame('aaa', $object->simples[1]->stringParameter);
        $this->assertSame(999, $object->simples[2]->integerParameter);
        $this->assertSame(3, $object->simples[2]->positiveIntegerParameter);
        $this->assertSame(false, $object->simples[2]->booleanOrNullParameter);
        $this->assertSame('bbb', $object->simples[2]->stringParameter);
    }

    public function testArrayWithStringKeysSuccess(): void
    {
        $object = $this->yaml->read(__DIR__.'/examples/array-of-simples-success-3.yml');
        $this->assertInstanceOf(ArrayOfSimple::class, $object);
        $this->assertSame(true, $object->booleanParameter);
        $this->assertSame(['stringKey'], array_keys($object->simples));
        $this->assertSame(777, $object->simples['stringKey']->integerParameter);
        $this->assertSame(1, $object->simples['stringKey']->positiveIntegerParameter);
        $this->assertSame(null, $object->simples['stringKey']->booleanOrNullParameter);
        $this->assertSame('888', $object->simples['stringKey']->stringParameter);
    }
}
