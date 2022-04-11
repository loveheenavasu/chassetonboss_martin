<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\EventLinkTemplate;
use App\Tools;
use Livewire\WithPagination;

class EventlinkTemplateList extends Component
{
    use WithPagination;
    public bool $confirmingContentDeletion = false;
    public ?EventLinkTemplate $contentBeingDeleted = null;

    public function getContentsProperty()
    {
        return EventLinkTemplate::latest()->paginate(10);
    }

    public function confirmContentDeletion(EventLinkTemplate $eventlinktemplate): void
    {
        $this->confirmingContentDeletion = true;
        $this->contentBeingDeleted = $eventlinktemplate;
    }

    public function deleteContent(): void
    {
        if (! is_null($this->contentBeingDeleted)) {

            $this->contentBeingDeleted->delete();
        }

        $this->confirmingContentDeletion = false;
    }
}
