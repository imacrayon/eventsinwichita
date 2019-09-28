<?php

namespace App\Console\Commands;

use App\Source;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SyncTicketmaster extends Command
{
    use SyncsEvents;

    protected $signature = 'sync:ticketmaster';

    protected $description = 'Sync events from Ticketmaster';

    public function handle()
    {
        $response = Cache::remember('ticketmaster', 3600, function () {
            return $this->fetch();
        });

        $bar = $this->output->createProgressBar($response->page->totalElements ?? 0);

        do {
            if (! property_exists($response, '_embedded')) {
                break;
            }

            foreach ($response->_embedded->events as $event) {
                $this->sync($event);
                $bar->advance();
            }

            if ($response->page->number < $response->page->totalPages) {
                $response = $this->fetch(['page' => ++$response->page->number]);
                continue;
            }

            $response = null;
        } while ($response);

        $bar->finish();
    }

    protected function fetch($options = [])
    {
        $options = array_merge([
                'apikey' => config('services.ticketmaster.secret'),
                'countryCode' => 'US',
                'stateCode' => 'KS',
                'city' => 'Wichita',
            ], $options);

        $response = file_get_contents('https://app.ticketmaster.com/discovery/v2/events.json?'.http_build_query($options));

        return json_decode($response);
    }

    protected function source($data)
    {
        return [
            'name' => Source::NAME_TICKETMASTER,
            'key' => $data->id,
        ];
    }

    protected function event($data)
    {
        $start = new Carbon($data->dates->start->dateTime);
        $end = optional($data->dates)->end ? new Carbon($data->dates->end->dateTime) : (new Carbon($start))->endOfDay();

        return [
            'name' => $data->name,
            'description' => null,
            'start' => $start,
            'end' => $end,
            'timezone' => $data->dates->timezone ?? 'America/Chicago',
            'location' => $this->location($data),
            'approved_at' => $start->diffInDays($end) <= 7 ? now() : null,
        ];
    }

    protected function location($data)
    {
        $venue = $data->_embedded->venues[0];

        return trim("{$venue->name} {$venue->address->line1} ".optional($venue->address)->line2);
    }
}
