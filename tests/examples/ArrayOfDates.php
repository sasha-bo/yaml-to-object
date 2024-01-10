<?php

namespace Unicon\Yaml\Examples;

class ArrayOfDates
{
    /** @var array<\DateTimeInterface> */
    public array $interfaceDates = [];

    /** @var array<\DateTime> */
    public array $dates = [];

    /** @var array<\DateTimeImmutable> */
    public array $immutableDates = [];

    /** @var array<?\DateTime> */
    public array $nullableDates = [];
}