<?php

namespace App\Http\Livewire;
use App\Models\PremiumTemplates;
use App\Tools;
use Livewire\WithPagination;
use Livewire\Component;

class PremiumTemplateList extends Component
{
    use WithPagination;
    public bool $confirmingPremiumTemplateDeletion = false;
    public ?int $templateIdBeingDeleted;

    public function getPremiumTemplatesProperty()
    {
        
        return PremiumTemplates::byTool(Tools::current())->paginate(10);
    }
    public function confirmPremiumTemplateDeletion($templateId)
    {
        $this->confirmingPremiumTemplateDeletion = true;
        $this->templateIdBeingDeleted = $templateId;
    }

    public function deleteTemplate()
    {
        try{
            PremiumTemplates::query()->findOrNew($this->templateIdBeingDeleted)->delete();
            $this->confirmingPremiumTemplateDeletion = false;
           }catch(\Exception $e){
             $this->confirmingPremiumTemplateDeletion = false;
           }
    }
}
