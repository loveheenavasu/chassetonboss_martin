<?php

namespace App\Http\Livewire;

use App\Models\EventTemplate;
use Livewire\Component;
use App\Models\EventPlaceholder;

use Illuminate\Validation\Rule as ValidationRule;

class EventTemplateForm extends Component
{
    public EventTemplate $eventtemplate;

    public $file = null;
    public $content = null;
    public $spin_text = null;
   

    public function rules(): array
    {
        return [
            'eventtemplate.name' => ['required', 'string','unique:event_template,name'],
            'eventtemplate.event_name' => ['required', 'string'],
            'eventtemplate.event_location' => ['required', 'string'],
            'content' => ['nullable'],
            'spin_text' => ['nullable'],
        ];
    }

    public function mount(EventTemplate $eventtemplate): void
    {
        $this->content = $eventtemplate->content;
        $this->spin_text = $eventtemplate->spin_text;
        $this->eventtemplate = $eventtemplate;
    }

    public function getEventPlaceholdersProperty()
    {
        return EventPlaceholder::get();
    }

    public function submit()
    { 
        if($this->eventtemplate->id == ''){
            $this->validate();
        }
        if($this->eventtemplate->id)
        {
            $template = EventTemplate::find($this->eventtemplate->id);
            $template->update([
                'name' => $this->eventtemplate->name,
                'event_name' => $this->eventtemplate->event_name,
                'event_location' => $this->eventtemplate->event_location,
                'content' => $this->content,
                'spin_text' => $this->spin_text,
                'event_datetime' => $this->eventtemplate->event_datetime,
                'randomize_invite' => $this->eventtemplate->randomize_invite,
            ]);
            $this->redirectRoute('eventtemplate.index');
        }else{
            EventTemplate::create([
                'name' => $this->eventtemplate->name,
                'event_name' => $this->eventtemplate->event_name,
                'event_location' => $this->eventtemplate->event_location,
                'content' => $this->content,
                'spin_text' => $this->spin_text,
                'event_datetime' => $this->eventtemplate->event_datetime,
                'randomize_invite' => $this->eventtemplate->randomize_invite,
            ]);
            $this->redirectRoute('eventtemplate.index');
        }
    }
    
}
