<?php

namespace App\Http\Livewire;
use App\Models\EventPlaceholder;
use App\Tools;
use Livewire\Component;
use Livewire\WithPagination;

class EventPlaceholderList extends Component
{
    use WithPagination;
    public bool $confirmingPlaceholderDeletion = false;
    public ?EventPlaceholder $placeholderBeingDeleted = null;

    public function getEventPlaceholdersProperty()
    {
        return EventPlaceholder::paginate(100);
    }

    public function confirmPlaceholderDeletion(EventPlaceholder $eventplaceholder): void
    {
        $this->confirmingPlaceholderDeletion = true;
        $this->placeholderBeingDeleted = $eventplaceholder;
    }

    public function deletePlaceholder(): void
    {
        $this->placeholderBeingDeleted->delete();

        $this->confirmingPlaceholderDeletion = false;
    }
}
