<?php

namespace App\Repositories;

use App\Hydrators\DayHydrator;
use App\Loaders\DayLoader;
use App\Models\Day;
use App\Models\DayCollection;
use App\Writers\DayWriter;

class DayRepository
{
    public function __construct(
        private DayLoader $dayLoader,
        private DayHydrator $dayHydrator,
        private DayWriter $dayWriter
    )
    {}

    public function loadById(int $id): Day
    {
        $dayData = $this->dayLoader->loadById($id);
        $day = $this->dayHydrator->hydrate($dayData);

        return $day;
    }

    public function loadByDateCode(int $dateCode): Day
    {
        $dayData = $this->dayLoader->loadByDateCode($dateCode);
        $day = $this->dayHydrator->hydrate($dayData);

        return $day;
    }

    public function loadBySprintId(int $sprintId): DayCollection
    {
        $dayCollection = new DayCollection();

        $dayData = $this->dayLoader->loadBySprintId($sprintId);
        $dayArray = [];

        foreach ($dayData as $day) {
            $dayCollection->add($this->dayHydrator->hydrate($day));
        }

        return $dayCollection;
    }

    public function save(Day $day): bool
    {
        return $this->dayWriter->write($day);
    }
}
