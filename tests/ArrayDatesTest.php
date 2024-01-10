<?php

namespace Unicon\tests;

use PHPUnit\Framework\TestCase;
use Unicon\Yaml\Examples\ArrayOfDates;
use Unicon\Yaml\Yaml;
use Unicon\Yaml\YamlException;

include_once(__DIR__.'/examples/ArrayOfDates.php');

final class ArrayDatesTest extends TestCase
{
    private Yaml $yaml;
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->yaml = new Yaml(ArrayOfDates::class);
    }

    public function testDateTimeInterface(): void
    {
        $object = $this->yaml->read(__DIR__.'/examples/array-of-dates-success-1.yml');
        $this->assertInstanceOf(ArrayOfDates::class, $object);
        $this->assertCount(0, $object->dates);
        $this->assertCount(0, $object->immutableDates);
        $this->assertCount(0, $object->nullableDates);
        $this->assertSame([0, 1], array_keys($object->interfaceDates));
        $this->assertInstanceOf(\DateTimeInterface::class, $object->interfaceDates[0]);
        $this->assertSame('2011-01-13 12:00:00', $object->interfaceDates[0]->format('Y-m-d H:i:s'));
        $this->assertInstanceOf(\DateTimeInterface::class, $object->interfaceDates[1]);
        $this->assertSame('2013-01-25 12:00:00', $object->interfaceDates[1]->format('Y-m-d H:i:s'));
    }

    public function testDateTime(): void
    {
        $object = $this->yaml->read(__DIR__.'/examples/array-of-dates-success-2.yml');
        $this->assertInstanceOf(ArrayOfDates::class, $object);
        $this->assertCount(0, $object->interfaceDates);
        $this->assertCount(0, $object->immutableDates);
        $this->assertCount(0, $object->nullableDates);
        $this->assertSame([0, 1], array_keys($object->dates));
        $this->assertInstanceOf(\DateTime::class, $object->dates[0]);
        $this->assertSame('2011-01-13 12:00:00', $object->dates[0]->format('Y-m-d H:i:s'));
        $this->assertInstanceOf(\DateTime::class, $object->dates[1]);
        $this->assertSame('2013-01-25 12:00:00', $object->dates[1]->format('Y-m-d H:i:s'));
    }

    public function testDateTimeImmutable(): void
    {
        $object = $this->yaml->read(__DIR__.'/examples/array-of-dates-success-3.yml');
        $this->assertInstanceOf(ArrayOfDates::class, $object);
        $this->assertCount(0, $object->interfaceDates);
        $this->assertCount(0, $object->dates);
        $this->assertCount(0, $object->nullableDates);
        $this->assertSame(['date1', 'date2'], array_keys($object->immutableDates));
        $this->assertInstanceOf(\DateTimeImmutable::class, $object->immutableDates['date1']);
        $this->assertSame('2011-01-13 12:00:00', $object->immutableDates['date1']->format('Y-m-d H:i:s'));
        $this->assertInstanceOf(\DateTimeImmutable::class, $object->immutableDates['date2']);
        $this->assertSame('2013-01-25 12:00:00', $object->immutableDates['date2']->format('Y-m-d H:i:s'));
    }

    public function testNullableDateTime(): void
    {
        $object = $this->yaml->read(__DIR__.'/examples/array-of-dates-success-4.yml');
        $this->assertInstanceOf(ArrayOfDates::class, $object);
        $this->assertCount(0, $object->interfaceDates);
        $this->assertCount(0, $object->dates);
        $this->assertCount(0, $object->immutableDates);
        $this->assertSame(['date1', 'date2', 'date3', 'date4'], array_keys($object->nullableDates));
        $this->assertInstanceOf(\DateTime::class, $object->nullableDates['date1']);
        $this->assertSame('2011-01-13 12:00:00', $object->nullableDates['date1']->format('Y-m-d H:i:s'));
        $this->assertNull($object->nullableDates['date2']);
        $this->assertInstanceOf(\DateTime::class, $object->nullableDates['date3']);
        $this->assertSame('2013-01-25 12:00:00', $object->nullableDates['date3']->format('Y-m-d H:i:s'));
        $this->assertNull($object->nullableDates['date4']);
    }

    public function testError(): void
    {
        $exception = null;
        try {
            $this->yaml->read(__DIR__.'/examples/array-of-dates-error-1.yml');
        } catch (YamlException $exception) {}
        $this->assertNotNull($exception);
        $this->assertSame('Can\'t convert interface_dates.1 "cccccc" to \DateTimeInterface', $exception->getMessage());
    }
}
