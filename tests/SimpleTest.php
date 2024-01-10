<?php

namespace Unicon\tests;

use PHPUnit\Framework\TestCase;
use Unicon\Yaml\Examples\Simple;
use Unicon\Yaml\Yaml;
use Unicon\Yaml\YamlException;

include_once(__DIR__.'/examples/Simple.php');

final class SimpleTest extends TestCase
{
    private Yaml $yaml;
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->yaml = new Yaml(Simple::class);
    }

    public function testSimpleSuccess1(): void
    {
        $object = $this->yaml->read(__DIR__.'/examples/simple-success-1.yml');
        $this->assertInstanceOf(Simple::class, $object);
        $this->assertSame(777, $object->integerParameter);
        $this->assertSame(1, $object->positiveIntegerParameter);
        $this->assertSame(null, $object->booleanOrNullParameter);
        $this->assertSame('888', $object->stringParameter);
    }

    public function testSimpleSuccess2(): void
    {
        $object = $this->yaml->read(__DIR__.'/examples/simple-success-2.yml');
        $this->assertInstanceOf(Simple::class, $object);
        $this->assertSame(0, $object->integerParameter);
        $this->assertSame(555, $object->positiveIntegerParameter);
        $this->assertSame(false, $object->booleanOrNullParameter);
        $this->assertSame('false', $object->stringParameter);
    }

    public function testSimpleSuccessDefaults(): void
    {
        $object = $this->yaml->read(__DIR__.'/examples/simple-success-3.yml');
        $this->assertInstanceOf(Simple::class, $object);
        $this->assertSame(1, $object->integerParameter);
        $this->assertSame(100, $object->positiveIntegerParameter);
        $this->assertSame(null, $object->booleanOrNullParameter);
        $this->assertSame('default', $object->stringParameter);
    }

    public function testSimpleNotIntError(): void
    {
        $exception = null;
        try {
            $this->yaml->read(__DIR__.'/examples/simple-error-1.yml');
        } catch (YamlException $exception) {}
        $this->assertNotNull($exception);
        $this->assertSame('Can\'t convert integer_parameter "aaa" to int', $exception->getMessage());
    }

    public function testNotPositiveError(): void
    {
        $exception = null;
        try {
            $this->yaml->read(__DIR__.'/examples/simple-error-2.yml');
        } catch (YamlException $exception) {}
        $this->assertNotNull($exception);
        $this->assertSame('Yaml parameter positive_integer_parameter must be greater or equal to 1, -1 given', $exception->getMessage());
    }

    public function testNotBooleanOrNullError(): void
    {
        $exception = null;
        try {
            $this->yaml->read(__DIR__.'/examples/simple-error-3.yml');
        } catch (YamlException $exception) {}
        $this->assertNotNull($exception);
        // TODO: better ?bool or null|bool
        $this->assertSame('Can\'t convert boolean_or_null_parameter "aaa" to bool', $exception->getMessage());
    }

    public function testMissedParameterError(): void
    {
        $exception = null;
        try {
            $this->yaml->read(__DIR__.'/examples/simple-error-4.yml');
        } catch (YamlException $exception) {}
        $this->assertNotNull($exception);
        $this->assertSame('Yaml parameter positive_integer_parameter is missed', $exception->getMessage());
    }

    public function testExtraParameterError(): void
    {
        $exception = null;
        try {
            $this->yaml->read(__DIR__.'/examples/simple-error-5.yml');
        } catch (YamlException $exception) {}
        $this->assertNotNull($exception);
        $this->assertSame('Yaml parameter extra_parameter with value "extra" is unexpected', $exception->getMessage());
    }
}
