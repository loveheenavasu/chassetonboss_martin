<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\EventInvalidEmail;
use App\Tools;

class EventInvalidEmailList extends Component
{
    public function getInvalidemailsProperty()
    {
        return EventInvalidEmail::latest('id')->paginate(100);
    }
}
