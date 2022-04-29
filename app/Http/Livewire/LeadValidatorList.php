<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\LeadValidator;
use Livewire\WithPagination;
use File;
class LeadValidatorList extends Component
{
    public LeadValidator $leadvalidator;
    public bool $confirmingLeadvalidatorDeletion = false;
    public ?LeadValidator $leadvalidatorBeingDeleted = null;
    public $checkid = '';
    public bool $ruleRunning = false;

    public function getLeadvalidatorsProperty()
    {
        return LeadValidator::query()->latest()->paginate(100);
    }

    public function confirmingLeadvalidatorDeletion(LeadValidator $leadvalidator): void
    {
        $this->confirmingLeadvalidatorDeletion = true;
        $this->leadvalidatorBeingDeleted = $leadvalidator;
    }

    public function deleteLeadvalidator(): void
    {
        $validfile = $this->leadvalidatorBeingDeleted->name.'-valid.csv';
        $invalidfile = $this->leadvalidatorBeingDeleted->name.'-invalid.csv';
        $unknownfile = $this->leadvalidatorBeingDeleted->name.'-unknown.csv';
        File::delete($validfile); 
        File::delete($invalidfile); 
        File::delete($unknownfile); 
        $this->leadvalidatorBeingDeleted->delete();
        $this->confirmingLeadvalidatorDeletion = false;
    }
}
