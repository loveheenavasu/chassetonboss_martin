<?php

namespace App\Http\Livewire;

use App\Models\Syndication;
use Livewire\Component;
use Livewire\WithPagination;

class SyndicationList extends Component
{   
    use WithPagination;
    public bool $confirmingSyndicationDeletion = false;
    public ?Syndication $syndicationBeingDeleted = null;

    public function getSyndicationsProperty()
    {
        return Syndication::latest()->paginate(10);
    }

    public function confirmSyndicationDeletion(Syndication $syndication): void
    {
        $this->confirmingSyndicationDeletion = true;
        $this->syndicationBeingDeleted = $syndication;
    }

    public function deleteSyndication(): void
    {
        if (! is_null($this->syndicationBeingDeleted)) {
            $this->syndicationBeingDeleted->delete();
        }

        $this->confirmingSyndicationDeletion = false;
    }

    public function hasSyndications(Syndication $syndication): bool
    {
        return $syndication->syndicatedConnections()->count() > 0;
    }

    public function downloadLinksFile(Syndication $syndication)
    {
        if (! $syndication->content) {
            return;
        }

        return response()->streamDownload(function () use ($syndication) {
            echo $syndication->copiedValue();
        }, 'Syndication of ' . $syndication->content->name . '.txt');
    }
}
