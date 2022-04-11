<?php

namespace App\Http\Livewire;

use App\Models\Listing;
use Livewire\Component;

class ListingEmailsList extends Component
{
    public Listing $listing;

    public function getEmailsProperty()
    {
        return $this->listing->emails()->with('infos')->paginate(100);
    }

    public function mount(Listing $listing): void
    {
        if (!$listing->exists) {
            throw new \InvalidArgumentException('Listing model must exist in database.');
        }

        $this->listing = $listing;
    }
}
