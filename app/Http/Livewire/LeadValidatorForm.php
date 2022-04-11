<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\LeadValidator;
class LeadValidatorForm extends Component
{
    public LeadValidator $leadvalidator;
    

     public function rules(): array
    {
        return [
            'leadvalidator.name' => ['required', 'string','unique:lead_validators,name']
        ];
    }

    public function mount(LeadValidator $leadvalidator)
    {
        $this->leadvalidator = $leadvalidator;
        
    }

    public function save()
    {
        $old_name = $this->leadvalidator->getOriginal('name');
        $old_name1 = $old_name.'-valid.csv';
        $old_name2 = $old_name.'-invalid.csv';
        $old_name3 = $old_name.'-unknown.csv';
        
        $new_name = $this->leadvalidator->name;
        $new_name1 = $new_name.'-valid.csv';
        $new_name2 = $new_name.'-invalid.csv';
        $new_name3 = $new_name.'-unknown.csv';
        
        if($this->leadvalidator->id == ''){
            $this->validate();
        }
        $this->leadvalidator->save();
        
        if ($this->leadvalidator->wasRecentlyCreated) {
            $this->redirectRoute('leadvalidator.show', ['leadvalidator' => $this->leadvalidator->id]);
        }
        else {
            if(file_exists(public_path('/'.$old_name1)) || file_exists(public_path('/'.$old_name2)) || file_exists(public_path('/'.$old_name3))){
                rename(public_path('/'.$old_name1),public_path('/'.$new_name1));
                rename(public_path('/'.$old_name2),public_path('/'.$new_name2));
                rename(public_path('/'.$old_name3),public_path('/'.$new_name3));
            }
            $this->redirectRoute('leadvalidator.index');
        }

        $this->emit('saved');
    }
    
}
