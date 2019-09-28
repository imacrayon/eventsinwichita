<?php

namespace App\Console\Commands;

use App\Source;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\DomCrawler\Crawler;

class SyncWichita360 extends Command
{
    use SyncsEvents;

    protected $signature = 'sync:wichita360';

    protected $description = 'Sync events from Wichita 360';

    public function handle()
    {
        $response = $this->fetch();

        $bar = $this->output->createProgressBar(count($response) ?? 0);

        foreach ($response as $event) {
            $this->sync($event);
            $bar->advance();
        }

        $bar->finish();
    }

    public function fetch($options = [])
    {
        $start = today();
        $end = today()->addWeek();
        $url = 'https://www.360wichita.com/events?'.http_build_query([
            'cal_search' => 1,
            'event_allow_single' => 'on',
            'eventbegin' => $start->format('Y-m-d'),
            'eventend' => $end->format('Y-m-d'),
            'area_id' => -1,
        ]);

        $xml = Cache::remember('wichita360', 3600, function () use ($url) {
            return file_get_contents($url);
        });

        $items = (new Crawler($xml))->filterXPath('//script[@type="application/ld+json"]')
            ->each(function (Crawler $node, $i) {
                $text = str_replace(["\t", "\n"], '', $node->text());

                $json = json_decode($text);

                // Some event items are nested inside arrays?
                while (is_array($json)) {
                    $json = $json[0];
                }

                // Sanity check that the item looks like an event
                if (! is_object($json)) {
                    return null;
                }

                if ($json->{'@type'} !== 'Event') {
                    return null;
                }

                return $json;
            });

        return array_filter($items);
    }

    protected function source($data)
    {
        return [
            'name' => Source::NAME_WICHITA360,
            'key' => str_replace('https://www.360wichita.com/event/', '', $data->url),
        ];
    }

    protected function event($data)
    {
        // The timezome is off by one hour for some reason on this site
        $data->startDate = str_replace('-06:00', '-05:00', $data->startDate);
        $start = new Carbon($data->startDate);
        $end = (new Carbon($start))->endOfDay();

        return [
            'name' => $data->name,
            'description' => htmlspecialchars_decode(trim($data->description)),
            'start' => $start->timezone('UTC'),
            'end' => $end->timezone('UTC'),
            'timezone' => 'America/Chicago',
            'location' => $this->location($data),
            'approved_at' => $start->diffInDays($end) <= 7 ? now() : null,
        ];
    }

    protected function location($data)
    {
        return trim("{$data->location->name} {$data->location->address}");
    }
}
