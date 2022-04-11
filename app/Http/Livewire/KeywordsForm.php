<?php

namespace App\Http\Livewire;

use App\Tools;
use App\Models\Keywords;
use Illuminate\Validation\Rule;
use Livewire\Component;

class KeywordsForm extends Component
{
	public Keywords $keywords;
    public function render()
    {
        return view('livewire.keywords-form');
    }


    public function rules(): array
    {
        return [
            'keywords.name' => ['required', 'string'],
            //'keywords.type' => ['required', 'string'],
            
        ];
    }

    public function mount(Keywords $keywords)
    {
        
        $this->keywords = $keywords;
    }

     public function submit(): void
    {
        if($this->keywords->id == ''){
            $this->validate();
        }
      
        $alldata = explode(PHP_EOL, $this->keywords->name);
         for($i=0; $i<count($alldata);$i++){
            $values = array( 'name'=>$alldata[$i]);
             Keywords::create($values);
          }

        $this->redirectRoute('keyword.index');
    }


}
