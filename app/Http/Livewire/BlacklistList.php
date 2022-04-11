<?php

namespace App\Http\Livewire;
use Livewire\Component;
use App\Models\Blacklist;
use Livewire\WithPagination;
use File;

class BlacklistList extends Component
{
    public Blacklist $blacklist;
    public bool $confirmingBlacklistDeletion = false;
    public ?int $blacklistBeingDeleted;

    public function getBlacklistsProperty()
    {
        return Blacklist::query()->paginate(100);
    }

    public function confirmBlacklistDeletion($blackListId)
    {
        $this->confirmingBlacklistDeletion = true;
        $this->blacklistBeingDeleted = $blackListId;
    }

    public function deleteBlacklist()
    {
        try{
            Blacklist::query()->findOrNew($this->blacklistBeingDeleted)->delete();
            $this->confirmingBlacklistDeletion = false;
           }catch(\Exception $e){
             $this->confirmingBlacklistDeletion = false;
           }
    }
}
