<?php

namespace App\Http\Livewire;

use App\Models\Groups;
use App\Models\Listing;
use App\Models\Email;
use App\Models\GmailConnection;
use Livewire\Component;


class GroupAccountList extends Component
{
    public Groups $group;
    public $g_id;
    public bool $confirmingAccountDeletion = false;
    public ?int $accountIdBeingDeleted;

    
    public function getEmailsProperty()
    {
        return GmailConnection::where('group_id',$this->g_id)->latest()->paginate(10);
    }
   

   public function mount(Groups $group)
    {
        
        if (!$group->exists) {
            throw new \InvalidArgumentException('Listing model must exist in database.');
        }
        $this->g_id = $group->id;
        $this->group = $group;
    }
    // public function confirmAccountDeletion($accountId)
    // {
    //     $this->confirmingAccountDeletion = true;
    //     $this->accountIdBeingDeleted = $accountId;
    // }
    // public function deleteAccount()
    // {
        
    //     try{
    //         Email::query()->findOrNew($this->accountIdBeingDeleted)->delete();
    //         Email::where('id',$this->accountIdBeingDeleted)->update(array('group_id'=>NULL));
    //         $this->confirmingAccountDeletion = false;
            
    //        }catch(\Exception $e){
    //          $this->confirmingAccountDeletion = false;
    //        }
    // }
    
}
