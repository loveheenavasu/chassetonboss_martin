<?php

namespace App\Http\Livewire;

use App\Tools;
use App\Models\Profession;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ProfessionForm extends Component
{
    public Profession $profession;
    public $i = 1;
    public $inputs = [];
    
    public function render()
    {
        return view('livewire.profession-form');
    }

    public function add($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputs ,$i);
    }
 
    public function remove($i)
    {
        unset($this->inputs[$i]);
    }

    
    public function rules(): array
    {
        return [
            'profession.name' => ['required', 'string'], 
            'profession.keyword' => ['nullable', 'string'], 
        ];
    }

    public function mount(Profession $profession)
    {
        
        $this->profession = $profession;
    }

    public function submit(): void
    {
        if($this->profession->id == ''){
            $this->validate();
        }
        $keyword = explode(PHP_EOL, $this->profession->keyword);
        $keyword = array_unique($keyword);
        $keyword = implode(PHP_EOL, $keyword);
        $name = $this->profession->name;
        $values = array( 'name'=>$name,'keyword'=>$keyword);  
        $this->profession->keyword= trim($keyword);
        $this->profession->save();

        // $alldata = explode(PHP_EOL, $this->profession->name);
        // for($i=0; $i<count($alldata);$i++){
        //     $values = array( 'name'=>$alldata[$i]);
        //      Profession::create($values);
        // }

        $this->redirectRoute('profession.index');
    }

}
