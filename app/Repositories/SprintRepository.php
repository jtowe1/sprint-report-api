<?php

namespace App\Repositories;

use App\Hydrators\SprintHydrator;
use App\Loaders\SprintLoader;
use App\Models\Sprint;
use App\Writers\SprintWriter;

class SprintRepository
{
    public function __construct(
        private SprintLoader $sprintLoader,
        private SprintHydrator $sprintHydrator,
        private SprintWriter $sprintWriter,
        private DayRepository $dayRepository
    )
    {}

    public function loadById(int $id): Sprint
    {
        $sprintData = $this->sprintLoader->loadById($id);
        $sprintData["days"] = $this->dayRepository->loadBySprintId($id);
        $sprint = $this->sprintHydrator->hydrate($sprintData);

        return $sprint;
    }

    public function save(Sprint $sprint): bool
    {
        return $this->sprintWriter->write($sprint);
    }
}
