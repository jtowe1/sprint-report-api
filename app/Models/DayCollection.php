<?php

namespace App\Models;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

class DayCollection implements JsonSerializable, IteratorAggregate
{
    private array $days = [];

    public function __construct(Day ...$days)
    {
        $this->days = $days;
    }

    public function add(Day $day)
    {   
        $this->days[] = $day;
    }

    public function jsonSerialize(): mixed
    {
        return $this->days;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->days);
    }
}
