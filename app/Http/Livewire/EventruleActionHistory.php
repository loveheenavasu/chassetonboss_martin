<?php

namespace App\Http\Livewire;

use App\Models\Event;
use Livewire\Component;

class EventruleActionHistory extends Component
{
    public Event $event;

    public function mount(Event $event)
    {
        $this->event = $event;
    }
}
