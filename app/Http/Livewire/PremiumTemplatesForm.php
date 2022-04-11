<?php

namespace App\Http\Livewire;
use App\Actions\DeployPage;
use App\Models\PremiumTemplates;
use App\Tools;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PremiumTemplatesForm extends Component
{
    public PremiumTemplates $premiumtemplate;
    public $content = null;

    public function rules(): array
    {
        return [
            'premiumtemplate.tool' => ['required', Rule::in(Tools::all())],
            'premiumtemplate.name' => ['required', 'string'],
            'content' => ['required', 'string'],
            'premiumtemplate.button_text' => ['required', 'string'],
        ];
    }

    public function mount(PremiumTemplates $premiumtemplate): void
    {
        $this->premiumtemplate = $premiumtemplate;
        $this->premiumtemplate->tool = Tools::current();
        $this->content = $premiumtemplate->content;

    }

    public function submit(DeployPage $deployer)
    {
        if($this->premiumtemplate->id == ''){
            $this->validate();
        }
        if($this->premiumtemplate->id)
        {
            $template = PremiumTemplates::find($this->premiumtemplate->id);
            $template->update([
                'tool'  => $this->premiumtemplate->tool,
                'name' => $this->premiumtemplate->name,
                'content' => $this->content,
                'button_text' => $this->premiumtemplate->button_text,
            ]);
            $this->redirectRoute('premiumtemplates.index');
        }else{
            PremiumTemplates::create([
                'tool'  => $this->premiumtemplate->tool,
                'name' => $this->premiumtemplate->name,
                'content' => $this->content,
                'button_text' => $this->premiumtemplate->button_text,
            ]);
            $this->redirectRoute('premiumtemplates.index');
        }
        foreach ($this->premiumtemplate->premiumpages as $page) {
            $deployer->deployPremium($page);
        }
    }

    public function getSizesProperty()
    {
        return [
            '10px', '12px', '14px', '16px', '18px', '20px', '22px', '24px', '30px', '36px', '48px'
        ];
    }
}
