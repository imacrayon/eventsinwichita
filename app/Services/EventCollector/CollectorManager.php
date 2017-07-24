<?php

namespace App\Services\EventCollector;

use App\Tag;
use App\Event;
use App\Place;
use InvalidArgumentException;
use Illuminate\Support\Manager;
use App\Repositories\EventRepository;
use App\Repositories\PlaceRepository;
use App\Repositories\TagRepository;

class CollectorManager extends Manager implements Contracts\Factory
{
    /**
     * Get a driver instance.
     *
     * @param  string  $driver
     * @return mixed
     */
    public function with($driver)
    {
        return $this->driver($driver);
    }

    /**
     * Create an instance of the specified driver.
     *
     * @return \Laravel\Socialite\Two\AbstractProvider
     */
    protected function createFacebookDriver()
    {
        $config = config('collectors.facebook');
        return $this->buildProvider(
            Collectors\Facebook::class, $config
        );
    }

    /**
     * Build a provider instance.
     *
     * @param  string  $provider
     * @param  array  $config
     * @return \Laravel\Socialite\Two\AbstractProvider
     */
    public function buildProvider($provider, $config)
    {
        return new $provider(
            $config['token'],
            new EventRepository(new Event, new PlaceRepository(new Place), new TagRepository(new Tag)),
            new PlaceRepository(new Place)
        );
    }

    /**
     * Get the default driver name.
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        throw new InvalidArgumentException('No Event Collector driver was specified.');
    }
}
