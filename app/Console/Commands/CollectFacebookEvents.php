<?php

namespace App\Console\Commands;

use App\Place;
use Illuminate\Console\Command;
use App\Services\EventCollector\Contracts\Factory;

class CollectFacebookEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collect:facebook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collects new Facebook events for all places.';

    /**
     * The event fetcher service.
     *
     * @var fetcher
     */
    protected $collector;

    protected $place;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Factory $factory, Place $place)
    {
        parent::__construct();

        $this->collector = $factory->with('facebook');

        $this->place = $place;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $places = $this->place->whereNotNull('facebook_id')->get();
        $this->info('[' . $this->getTimestamp() .'] Start fetching new Facebook events.');
        foreach($places as $place) {
            try {
                $events = $this->collector->collectEvents($place);
                $this->info(count($events) . " events updated {$place->name}");
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                $this->error("Error fetching events for {$place->name}:\n{$e->getResponse()->getBody()}");
            } catch (\Exception $e) {
                $this->error("Error fetching events for {$place->name} on line {$e->getLine()} of {$e->getFile()}:\n{$e->getMessage()}");
            }
            sleep(1);
        }
        $this->info('[' . $this->getTimestamp() .'] Finish fetching new Facebook events.');
    }

    protected function getTimestamp()
    {
        return (new \DateTime('NOW'))->format('Y-m-d g:i:s');
    }
}
