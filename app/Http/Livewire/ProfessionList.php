<?php
namespace App\Http\Livewire;
use Livewire\Component;
use App\Models\Profession;
use Livewire\WithPagination;
use File;


class ProfessionList extends Component
{
    public Profession $profession;
    public bool $confirmingKeywordDeletion = false;
    public ?int $keywordBeingDeleted;

    public function getProfessionsProperty()
    {
        return Profession::query()->paginate(100);
    }
    public function render()
    {
        return view('livewire.profession-list');
    }

    
    public function confirmKeywordDeletion($keyWordId)
    {
        $this->confirmingKeywordDeletion = true;
        $this->keywordBeingDeleted = $keyWordId;
    }

    public function deleteTemplate()
    {
        try{
            Profession::query()->findOrNew($this->keywordBeingDeleted)->delete();
            $this->confirmingKeywordDeletion = false;
           }catch(\Exception $e){
             $this->confirmingKeywordDeletion = false;
           }
    }
}
