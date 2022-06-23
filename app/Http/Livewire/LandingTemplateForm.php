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
            'landingtemplate.button_text' => ['required', 'string'],
            'content' => ['required', 'string'],
        ];
    }
    public function mount(LandingTemplate $landingtemplate): void
    { 
        $this->content = $landingtemplate->content;
        $this->landingtemplate = $landingtemplate;
        $this->landingtemplate->tool = Tools::current();
    }

    public function submit(DeployPage $deployer): void
    {
        $this->landingtemplate->content = $this->content;
        echo "<pre>"; print_r($this->landingtemplate);die;
        $this->emit('submitting');

        $this->validate();
        
        $this->landingtemplate->save();
        foreach ($this->landingtemplate->landingpages as $page) {
            $deployer->deploylandingpage($page);
        }
        $this->redirectRoute('landingtemplates.index');
    }

    public function getSizesProperty()
    {
        return [
            '10px', '12px', '14px', '16px', '18px', '20px', '22px', '24px', '30px', '36px', '48px'
        ];
    }

    public function getTokennnProperty(){
        return Tokens::get();
    }
    public function editor_data()
    {
        $html_data= $_GET['html_data'];
        $this->content=$html_data;
        
        
    }


}
