<?php

namespace App\Http\Livewire;

use App\Models\LandingTemplate;
use App\Tools;
use Livewire\Component;
use Livewire\WithPagination;

class LandingTemplateList extends Component
{
    use WithPagination;
    public bool $confirminglandingTemplateDeletion = false;
    public ?int $landingtemplateIdBeingDeleted;

    public function getLandingtemplatesProperty()
    {
        
        return LandingTemplate::byTool(Tools::current())->paginate(10);
    }

    public function confirmTemplateDeletion($templateId)
    {
        $this->confirminglandingTemplateDeletion = true;
        $this->landingtemplateIdBeingDeleted = $templateId;
    }

    public function deleteTemplate()
    {
        try{
            LandingTemplate::query()->findOrNew($this->landingtemplateIdBeingDeleted)->delete();
            $this->confirminglandingTemplateDeletion = false;
           }catch(\Exception $e){
             $this->confirminglandingTemplateDeletion = false;
           }
    }
}
