<?php

namespace App\Console\Commands;

use App\Event;
use App\Source;
use Illuminate\Support\Arr;

trait SyncsEvents
{
    protected function sync($data)
    {
        if ($this->isValidSource($data)) {
            if ($source = $this->findSource($data)) {
                return $this->updateSource($source, $data);
            }

            return $this->createSource($data);
        }

        if ($source = $this->findSource($data)) {
            return $this->deleteSource($source);
        }
    }

    protected function isValidSource($data)
    {
        return true;
    }

    protected function findSource($data)
    {
        return Source::where(Arr::only($this->source($data), ['name', 'key']))
            ->with('event')->first();
    }

    protected function createSource($data)
    {
        return Event::create($this->event($data))
                ->sources()->create($this->source($data));
    }

    protected function updateSource($source, $data)
    {
        return tap($source)->update($this->source($data))
            ->event->update($this->event($data));
    }

    protected function deleteSource($source)
    {
        return $source->event->delete();
    }
}
