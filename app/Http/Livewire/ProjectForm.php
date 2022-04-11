<?php

namespace App\Http\Livewire;

use App\Models\ProjectListing;
use Livewire\Component;

class ProjectForm extends Component
{
    public ProjectListing $projectlist;

    public function rules(): array
    {
        return [
            'projectlist.name' => ['required', 'string','unique:project_listings,name']
        ];
    }

    public function mount(ProjectListing $projectlist): void
    {
        $this->projectlist = $projectlist;
    }

    public function save(): void
    {
        if($this->projectlist->id == ''){
            $this->validate();
        }
        $this->projectlist->save();

        if ($this->projectlist->wasRecentlyCreated) {
            $this->redirectRoute('projectlist.show', ['projectlist' => $this->projectlist->id]);
        } else {
            $this->redirectRoute('projectlist.index');
        }

        $this->emit('saved');
    }
}
