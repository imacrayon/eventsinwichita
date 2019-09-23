<?php

namespace App\Console\Commands;

use App\Source;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SyncEventbrite extends Command
{
    use SyncsEvents;

    protected $signature = 'sync:eventbrite';

    protected $description = 'Sync events from Eventbrite';

    public function handle()
    {
        $response = Cache::remember('eventbrite', 3600, function () {
            return $this->fetch();
        });

        $bar = $this->output->createProgressBar($response->pagination->object_count ?? 0);

        do {
            foreach ($response->events as $event) {
                $this->sync($event);
                $bar->advance();
            }

            if (optional($response->pagination)->has_more_items) {
                $response = $this->fetch(['page' => ++$response->pagination->page_number]);
                continue;
            }

            $response = null;
        } while ($response);

        $bar->finish();
    }

    protected function fetch($options = [])
    {
        $options = array_merge([
            'token' => config('services.eventbrite.secret'),
            'location.address' => 'wichita,kansas',
            'location.within' => '10mi',
            'expand' => 'venue',
        ], $options);

        $body = file_get_contents('https://www.eventbriteapi.com/v3/events/search?'.http_build_query($options));

        return json_decode($body);
    }

    protected function isValidSource($data)
    {
        return ! in_array($data->organization_id, [
            233446054037, 5386334244, 276436486174, 184961650433,
            300421523337, 229369625393, 183656722434, 308256707370,
            328509897191, 3069190992, 309235372404, 270797351629,
            308401027589, 308410324213,
        ]);
    }

    protected function source($data)
    {
        return [
            'name' => Source::NAME_EVENTBRITE,
            'key' => $data->id,
            'details' => [
                'organization_id' => $data->organization_id ?? null,
            ],
        ];
    }

    protected function event($data)
    {
        $start = new Carbon($data->start->utc);
        $end = optional($data)->end ? new Carbon($data->end->utc) : (new Carbon($start))->endOfDay();

        return [
            'name' => $data->name->text,
            'description' => trim($data->description->text),
            'start' => $start,
            'end' => $end,
            'timezone' => $data->start->timezone,
            'location' => $this->location($data),
            'approved_at' => $start->diffInDays($end) <= 7 ? now() : null,
        ];
    }

    protected function location($data)
    {
        return trim("{$data->venue->name} {$data->venue->address->address_1} {$data->venue->address->address_2}");
    }
}
