<?php

namespace App\Http\Livewire;
use App\Tools;
use App\Models\EventPlaceholder;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EventPlaceholderForm extends Component
{
    public EventPlaceholder $eventplaceholder;
    public function rules(): array
    {
        return [
            'eventplaceholder.name' => ['required', 'string'],
            
        ];
    }
    public function mount(EventPlaceholder $eventplaceholder)
    {
        
        $this->eventplaceholder = $eventplaceholder;
    }

    public function submit(): void
    {
        if($this->eventplaceholder->id == ''){
            $this->validate();
        }

        $this->eventplaceholder->save();

        $this->redirectRoute('eventplaceholders.index');
    }
}