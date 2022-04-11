<?php

namespace App\Http\Livewire;


use App\Actions\SendWebhookDummyData;
use App\Actions\TestConnection;
use App\Models\Groups;
use App\Models\GmailConnection;
use App\Tools;
use Livewire\Component;
use Livewire\WithPagination;
use DB;

class AccountList extends Component
{
    use WithPagination;
    public bool $confirmingGroupDeletion = false;
    public ?int $groupIdBeingDeleted;

    public function getGroupsProperty()
    {
        $data['result'] = Groups::latest()->paginate(10);
        return $data;
    }

    public function getGroupscountProperty()
    {
        $data = DB::table('gmail_connection_groups')->select('groups_id',DB::raw('count(*)as total'))->groupBy('groups_id')->get();
          $newArray = [];
        foreach($data as $value){
            $newArray[$value->groups_id] = $value->total; 
        }
        return $newArray;
            
    }

    public function render()
    {
        return view('livewire.account-list');
    }
    public function confirmGroupDeletion($groupId)
    {
        $this->confirmingGroupDeletion = true;
        $this->groupIdBeingDeleted = $groupId;
    }

    public function deleteGroup()
    {
        try{
            Groups::query()->findOrNew($this->groupIdBeingDeleted)->delete();
            DB::table('gmail_connection_groups')->where('groups_id',$this->groupIdBeingDeleted)->delete();
            GmailConnection::where('group_id',$this->groupIdBeingDeleted)->update(array('group_id'=>NULL));
            $this->confirmingGroupDeletion = false;
            
           }catch(\Exception $e){
             $this->confirmingGroupDeletion = false;
           }
    }
}
