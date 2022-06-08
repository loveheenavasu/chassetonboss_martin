<?php

namespace App\Http\Livewire\Token;
use App\Actions\DeployPage;
use App\Models\PremiumPages;
use App\Models\Connection;
use App\Models\PremiumTemplates;
use App\Models\Tokens;
use App\Tools;
use Livewire\WithFileUploads;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;


class Form extends Component
{
    use WithFileUploads;
    public $name;
    public array $data = [];
    public Tokens $token;
    public function render()
    {
        return view('livewire.token.form');
    }
    public function mount(Tokens $token): void
    { 
       $this->token = $token;

    }

    public function rules(): array
    {

        return [
            
            'token.name' => ['required', 'string'],

        ];
    } 

    public function submit(): void
    {
         $this->validate();

        $this->token->save();

        $this->redirectRoute('tokens.index');
    }


}
