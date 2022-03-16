<?php

namespace App\Models;

use Carbon\Carbon;
use JsonSerializable;

class Day implements JsonSerializable
{
    public function __construct(
        private int $id,
        private int $dateCode,
        private int $sprintId,
        private string $totalPointsDone,
        private string $totalPointsRemaining,
        private string $totalGoalPointsDone,
        private string $totalGoalPointsRemaining,
        private Carbon $createdAt,
        private ?Carbon $updatedAt,
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

    public function getDateCode(): int
    {
        return $this->dateCode;
    }

    public function getSprintId(): int
    {
        return $this->sprintId;
    }

    public function getTotalPointsDone(): string
    {
        return $this->totalPointsDone;
    }

    public function getTotalPointsRemaining(): string
    {
        return $this->totalPointsRemaining;
    }

    public function getTotalGoalPointsDone(): string
    {
        return $this->totalGoalPointsDone;
    }

    public function getTotalGoalPointsRemaining(): string
    {
        return $this->totalGoalPointsRemaining;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updatedAt;
    }
}
