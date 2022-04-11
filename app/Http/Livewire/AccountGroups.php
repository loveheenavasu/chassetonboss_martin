<?php

namespace App\Http\Livewire;

use App\Tools;
use App\Models\Groups;
use App\Models\Email;
use App\Models\GmailConnection;
use App\Models\ListingGroup;
use Illuminate\Validation\Rule;
use Livewire\Component;
use DB;

class AccountGroups extends Component
{
    public Groups $group;
    public $update_case;
    public $emailids;
    public $gmails = [];
    
    public function rules(): array
    {
        return [
            'group.name' => ['required', 'string','unique:groups,name'],
            'gmails' => ['array'],

        ];
    }
    public function render()
    {
        return view('livewire.account-groups');
    }

    public function getEmailsProperty()
    {
        return GmailConnection::whereNotNull('token')->latest()->paginate(1000);
    }

    public function mount(Groups $group)
    {

        $this->group = $group;
       
        if($this->group->id){
            $this->update_case = 1;
        }
         $this->gmails = $group->listinggroups()
            ->pluck('id')
            ->map(fn ($id) => (string)$id)
            ->toArray();

        $this->group->tool = Tools::current();
        $this->emailids = GmailConnection::whereNotNull('group_id')->pluck('id')->toArray();
      
    }

    public function submit()
    {  
        if($this->group->id == ''){
            $this->validate();
        }
       
        $this->group->save();
        $this->group->listinggroups()->sync($this->gmails);
        if(!empty($this->gmails)){
            foreach ($this->gmails as $gmail) {
                DB::table('gmail_connections')
                ->where('id',$gmail)
                ->update([
                        'group_name'=> $this->group['name'],
                        'group_id' => $this->group['id']
                    ]);
            }
        }
        $this->redirectRoute('groups.index');
    }

}
