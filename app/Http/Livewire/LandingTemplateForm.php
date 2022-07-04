<?php

namespace App\Http\Livewire;
use Illuminate\Http\Request;
use App\Actions\DeployPage;
use App\Models\LandingTemplate;
use App\Models\Tokens;
use App\Tools;
use Illuminate\Validation\Rule;
use Livewire\Component;

class LandingTemplateForm extends Component
{
    public LandingTemplate $landingtemplate;
    public $content = null;
   
    public function rules(): array
    {
        return [
            'landingtemplate.tool' => ['required', Rule::in(Tools::all())],
            'landingtemplate.name' => ['required', 'string'],
            'landingtemplate.content' => ['required', 'string'],
        ];
    }
    public function mount(LandingTemplate $landingtemplate): void
    { 
        $this->landingtemplate = $landingtemplate;
        $this->landingtemplate->tool = Tools::current();
    }

    public function submit(DeployPage $deployer): void
    {
        
        if($this->landingtemplate->id == ''){
            $this->validate();
        }

        $this->landingtemplate->save();

        foreach ($this->landingtemplate->landingpages as $page) {
            $deployer->deploylandingpage($page);
        }
        

        $this->redirectRoute('landingtemplates.index');
    }

    public function getTokennnProperty(){

        return Tokens::get();

    }

    
}
