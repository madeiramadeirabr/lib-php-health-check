<?php

namespace Tests\Mock;

use MadeiraMadeira\HealthCheck\Core\Entities\Status;
use MadeiraMadeira\HealthCheck\Core\Repositories\RunnerRepository;

class RunnerUnhealthyMock implements RunnerRepository
{
    public function getStatus()
    {
        return Status::getUnhealthyStatus();
    }
}