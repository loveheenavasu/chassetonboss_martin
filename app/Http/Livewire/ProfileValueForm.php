<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Actions\DeployPage;
use App\Models\Profiles;
use App\Models\Connection;
use App\Models\Template;
use App\Models\Tokens;
use App\Tools;
use Illuminate\Validation\Rule;

class ProfileValueForm extends Component
{
    //public $name;
    public Profiles $tokenprofiles;


    public function render()
    {
        return view('livewire.profile-value-form');
    }

    public function mount(Profiles $tokenprofiles): void
    {
        $this->tokenprofiles = $tokenprofiles;
    }
    
    public function rules(): array
    {
        return [
            
           'tokenprofiles.name' => ['required', 'string'],

        ];
    }

    public function submit(): void
    {
        $this->tokenprofiles->save();

        $this->redirectRoute('tokenprofile.index');
    }

    public function getTokensProperty()
    {

        return Tokens::all();
    }
}
