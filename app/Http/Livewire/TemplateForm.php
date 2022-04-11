<?php

namespace App\Http\Livewire;

use App\Actions\DeployPage;
use App\Models\Template;
use App\Tools;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TemplateForm extends Component
{
    public Template $template;

    public function rules(): array
    {
        return [
            'template.tool' => ['required', Rule::in(Tools::all())],
            'template.name' => ['required', 'string'],
            'template.content' => ['required', 'string'],
            'template.button_text' => ['required', 'string'],
        ];
    }

    public function mount(Template $template): void
    {
        $this->template = $template;

        $this->template->tool = Tools::current();
    }

    public function submit(DeployPage $deployer): void
    {
        $this->emit('submitting');

        $this->validate();

        $this->template->save();

        foreach ($this->template->pages as $page) {
            $deployer->deploy($page);
        }

        $this->redirectRoute('templates.index');
    }

    public function getSizesProperty()
    {
        return [
            '10px', '12px', '14px', '16px', '18px', '20px', '22px', '24px', '30px', '36px', '48px'
        ];
    }
}
