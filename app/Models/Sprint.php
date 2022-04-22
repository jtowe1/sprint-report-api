<?php

namespace App\Models;

use Carbon\Carbon;
use JsonSerializable;

class Sprint implements JsonSerializable
{
    public function __construct(
        private int $id,
        private string $name,
        private int $boardId,
        private int $length,
        private string $totalPoints,
        private string $totalGoalPoints,
        private ?DayCollection $days,
        private Carbon $createdAt,
        private ?Carbon $lastUpdated
    )
    {}

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBoardId(): int
    {
        return $this->boardId;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getTotalPoints(): string
    {
        return $this->totalPoints;
    }

    public function getTotalGoalPoints(): string
    {
        return $this->totalGoalPoints;
    }

    public function getDays(): ?DayCollection
    {
        return $this->days;
    }

    public function getCreatedDate(): Carbon
    {
        return $this->createdAt;
    }

    public function getLastUpdatedDate(): Carbon
    {
        return $this->lastUpdated;
    }
}
