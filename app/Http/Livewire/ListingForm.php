<?php

namespace App\Http\Livewire;

use App\Models\Listing;
use Livewire\Component;

class ListingForm extends Component
{
    public Listing $listing;

    public function rules(): array
    {
        return [
            'listing.name' => ['required', 'string','unique:listings,name']
        ];
    }

    public function mount(Listing $listing): void
    {
        $this->listing = $listing;
    }

    public function save(): void
    {
        if($this->listing->id == ''){
            $this->validate();
        }

        $this->listing->save();

        if ($this->listing->wasRecentlyCreated) {
            $this->redirectRoute('listings.show', ['listing' => $this->listing->id]);
        } else {
            $this->redirectRoute('listings.index');
        }

        $this->emit('saved');
    }
}
