<?php

namespace App\Console\Commands;

use App\Source;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SyncKirbys extends Command
{
    use SyncsEvents;

    protected $signature = 'sync:kirbys';

    protected $description = 'Sync events from Kirby\'s Beer Store Google Calendar';

    public function handle()
    {
        $response = Cache::remember('kirbys', 3600, function () {
            return $this->fetch();
        });

        $bar = $this->output->createProgressBar(count($response->items) ?? 0);

        foreach ($response->items as $event) {
            $this->sync($event);
            $bar->advance();
        }

        $bar->finish();
    }

    protected function fetch($options = [])
    {
        $options = array_merge([
            'key' => config('services.google.secret'),
            'timeMin' => (new Carbon('today'))->toRfc3339String(),
        ], $options);

        $response = file_get_contents('https://www.googleapis.com/calendar/v3/calendars/kirbysbeerstore@gmail.com/events?'.http_build_query($options));

        return json_decode($response);
    }

    protected function source($data)
    {
        return [
            'name' => Source::NAME_KIRBYS,
            'key' => $data->id,
        ];
    }

    protected function event($data)
    {
        $start = new Carbon($data->start->dateTime ?? $data->start->date.'T22:00:00-05:00');
        $end = new Carbon($data->end->dateTime ?? $data->end->date.'T23:59:59-05:00');

        return [
            'name' => $data->summary,
            'description' => $data->description ?? null,
            'start' => $start->timezone('UTC'),
            'end' => $end->timezone('UTC'),
            'timezone' => 'America/Chicago',
            'location' => $this->location($data),
            'approved_at' => $start->diffInDays($end) <= 7 ? now() : null,
        ];
    }

    protected function location($data)
    {
        return 'Kirby\'s Beer Store 3227 East 17th St N';
    }
}
