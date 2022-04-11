<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\EventLinkTemplate;
use App\Models\EventTemplate;

class EventlinkTemplateForm extends Component
{
    public EventLinkTemplate $eventlinktemplate;
    public EventTemplate $eventtemplate;
    public $file = null;

    public function rules(): array
    {
        return [
            'eventlinktemplate.name' => ['required', 'string'],
            'eventlinktemplate.links' => ['required'],
            'eventlinktemplate.template' => ['nullable'],
        ];
    }
    public function getEventTemplatesProperty()
    {
        return EventTemplate::latest()->paginate(10);
    }

    public function mount(EventLinkTemplate $eventlinktemplate): void
    {
        $this->eventlinktemplate = $eventlinktemplate;
    }

    public function submit(): void
    {
        if($this->eventlinktemplate->id == ''){
            $this->validate();
        }

        $this->eventlinktemplate->save();

        $this->redirectRoute('eventlinktemplate.index');
    }
}
