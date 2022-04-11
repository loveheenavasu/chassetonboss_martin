<?php

namespace App\Http\Livewire;
use Livewire\Component;
use App\Models\Keywords;
use Livewire\WithPagination;
use File;

class KeywordsList extends Component
{

	public Keywords $Keywords;
    public bool $confirmingKeywordDeletion = false;
    public ?int $keywordBeingDeleted;
    // public ?LeadValidator $leadvalidatorBeingDeleted = null;
    // public $checkid = '';
    // public bool $ruleRunning = false;

    public function getKeywordsProperty()
    {
        return Keywords::query()->paginate(100);
    }
    public function render()
    {
        return view('livewire.keywords-list');
    }

    
    public function confirmKeywordDeletion($keyWordId)
    {
        $this->confirmingKeywordDeletion = true;
        $this->keywordBeingDeleted = $keyWordId;
    }

    public function deleteTemplate()
    {
        try{
            Keywords::query()->findOrNew($this->keywordBeingDeleted)->delete();
            $this->confirmingKeywordDeletion = false;
           }catch(\Exception $e){
             $this->confirmingKeywordDeletion = false;
           }
    }

}
