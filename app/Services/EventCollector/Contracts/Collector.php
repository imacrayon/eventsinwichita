<?php

namespace App\Services\EventCollector\Contracts;

use App\Place;

interface Collector
{
    public function collectEvents(Place $place);
}
