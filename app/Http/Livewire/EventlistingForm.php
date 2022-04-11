<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\EventListing;

class EventlistingForm extends Component
{
    public EventListing $eventlisting;

    public function rules(): array
    {
        return [
            'eventlisting.name' => ['required', 'string','unique:eventlistings,name']
        ];
    }

    public function mount(EventListing $eventlisting): void
    {
        $this->eventlisting = $eventlisting;
    }

    public function save(): void
    {
        if($this->eventlisting->id == ''){
            $this->validate();
        }
        $this->eventlisting->save();

        if ($this->eventlisting->wasRecentlyCreated) {
            $this->redirectRoute('eventlistings.show', ['eventlisting' => $this->eventlisting->id]);
        } else {
            $this->redirectRoute('eventlistings.index');
        }

        $this->emit('saved');
    }
}
