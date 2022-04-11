<?php

namespace App\Http\Livewire;

use App\Models\EventListing;
use App\Models\Event;
use Livewire\Component;

class EventruleMonitoring extends Component
{
    public Event $event;

    public function mount(Event $event)
    {
        $this->event = $event;
    }
}

