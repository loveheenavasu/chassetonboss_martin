<?php

namespace App\Http\Livewire;


use App\Tools;
use App\Models\Proxy;
//use Illuminate\Validation\Rule;
use Livewire\Component;

class ProxyForm extends Component
{
    public Proxy $proxy;

    public function rules(): array
    {
        return [
            'proxy.name' => ['required','string','unique:proxy,name']
            
        ];
    }
    public function mount(Proxy $proxy)
    {
        
        $this->proxy = $proxy;
    }

    public function submit(): void
    {
        if($this->proxy->id == ''){
            $this->validate();
        }

        $this->proxy->save();

        $this->redirectRoute('proxy.index');
    }

}
