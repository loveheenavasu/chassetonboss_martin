<?php

namespace App\Http\Livewire;

use App\Actions\DeployPage;
use App\Models\Page;
use App\Models\Connection;
use App\Models\Template;
use App\Tools;
use Livewire\Component;

class PageForm extends Component
{
    public Page $page;

    public function getConnectionsProperty()
    {
        return Connection::byTool(Tools::current())->get();
    }

    public function getTemplatesProperty()
    {
        return Template::all();
    }

    public function mount(Page $page): void
    {
        $this->page = $page;
    }

    public function rules(): array
    {
        return [
            'page.connection_id' => ['required', 'exists:connections,id'],
            'page.template_id' => ['required', 'exists:templates,id'],
            'page.slug' => ['required', 'string'],
            'page.product' => ['nullable', 'string'],
            'page.affiliate_link' => ['required', 'string'],
            'page.name' => ['nullable', 'string'],
        ];
    }

    public function submit(DeployPage $deployer): void
    {
        $this->validate();

        $this->page->save();

        $deployer->deploy($this->page);

        session()->flash('copyToClipboard', [
            'text' => 'Page link copied to clipboard',
            'value' => $this->page->full_url
        ]);

        $this->redirectRoute('pages.index');
    }
}

