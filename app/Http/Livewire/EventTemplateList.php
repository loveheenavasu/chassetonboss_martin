<?php

namespace App\Http\Livewire;

use App\Models\EventTemplate;
use Livewire\Component;
use App\Tools;
use Livewire\WithPagination;

class EventTemplateList extends Component
{   
    use WithPagination;
    public bool $confirmingContentDeletion = false;
    public ?EventTemplate $contentBeingDeleted = null;

    public function getContentsProperty()
    {
        return EventTemplate::latest()->paginate(10);
    }

    public function confirmContentDeletion(EventTemplate $eventtemplate): void
    {
        $this->confirmingContentDeletion = true;
        $this->contentBeingDeleted = $eventtemplate;
    }

    public function deleteContent(): void
    {
        if (! is_null($this->contentBeingDeleted)) {

            $this->contentBeingDeleted->delete();
        }

        $this->confirmingContentDeletion = false;
    }

    public function cloneContent(EventTemplate $eventtemplate): void
    {
        $new = $eventtemplate->replicate();
        $new->name = $new->name . ' (Copy)';
        $new->save();
    }
}
