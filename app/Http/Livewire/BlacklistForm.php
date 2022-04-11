<?php

namespace App\Http\Livewire;
use App\Tools;
use App\Models\Blacklist;
use Illuminate\Validation\Rule;
use Livewire\Component;

class BlacklistForm extends Component
{
    public Blacklist $blacklist;
    public function rules(): array
    {
        return [
            'blacklist.name' => ['required', 'string'],
            
        ];
    }

    public function mount(Blacklist $blacklist)
    {
        
        $this->blacklist = $blacklist;
    }

    public function submit(): void
    {
        if($this->blacklist->id == ''){
            $this->validate();
        }
      
        $alldata = explode(PHP_EOL, $this->blacklist->name);
         for($i=0; $i<count($alldata);$i++){
            $values = array( 'name'=>$alldata[$i]);
             Blacklist::create($values);
          }

        $this->redirectRoute('blacklist.index');
    }

}
