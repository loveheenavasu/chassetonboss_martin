<?php

namespace App\Http\Livewire;

use App\Models\Template;
use App\Tools;
use Livewire\Component;
use Livewire\WithPagination;
class TemplateList extends Component
{
    use WithPagination;
    public bool $confirmingTemplateDeletion = false;
    public ?int $templateIdBeingDeleted;

    public function getTemplatesProperty()
    {
        
        return Template::byTool(Tools::current())->paginate(10);
    }

    public function confirmTemplateDeletion($templateId)
    {
        $this->confirmingTemplateDeletion = true;
        $this->templateIdBeingDeleted = $templateId;
    }

    public function deleteTemplate()
    {
        try{
            Template::query()->findOrNew($this->templateIdBeingDeleted)->delete();
            $this->confirmingTemplateDeletion = false;
           }catch(\Exception $e){
             $this->confirmingTemplateDeletion = false;
           }
    }
}
