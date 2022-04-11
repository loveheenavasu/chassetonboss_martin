<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\EventListing;
use App\Models\Listing;

class EventlistingEmailsList extends Component
{
    public EventListing $eventlisting;

    public function getEmailsProperty()
    {
        return $this->eventlisting->emails()->with('infos')->paginate(100);
    }

    public function mount(EventListing $eventlisting): void
    {
        if (!$eventlisting->exists) {
            throw new \InvalidArgumentException('Listing model must exist in database.');
        }

        $this->eventlisting = $eventlisting;
    }
}
