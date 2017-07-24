<?php

namespace App\Services\EventCollector\Contracts;

interface Factory
{
    /**
     * Get a provider implementation.
     *
     * @param  string  $driver
     * @return \App\Contracts\Provider
     */
    public function driver($driver = null);
}
