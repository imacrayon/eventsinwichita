<?php

namespace App\Services\EventCollector\Contracts;

interface Geocoder
{
    public function getData($address);
}
