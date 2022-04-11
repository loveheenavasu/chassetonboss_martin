<?php

namespace App\Http\Livewire;

use App\Models\Content;
use App\Actions\DeploySyndication;
use App\Models\Syndication;
use Livewire\Component;
use Livewire\WithFileUploads;

class ContentForm extends Component
{
    use WithFileUploads;

    public Content $content;

    public array $extensions = ['html', 'phtml', 'txt', 'php'];

    public $file = null;

    public function rules(): array
    {
        return [
            'content.name' => ['required', 'string'],
            'content.content' => ['required', 'string'],
        ];
    }

    public function mount(Content $content): void
    {
        $this->content = $content;
    }

    public function updatedFile(): void
    {
        $this->validate([
            'file' => ['required', 'file', 'mimes:' . implode(',', $this->extensions)]
        ]);

        $this->content->content = file_get_contents($this->file->getRealPath());
        $this->file = null;
    }

    public function submit(DeploySyndication $deployer): void
    {
        $this->validate();

        $this->content->save();

        if ($this->content->wasChanged('content')) {
            $this->content->syndications->each(function (Syndication $syndication) use ($deployer) {
                $deployer->deploy($syndication);
            });
        }

        $this->redirectRoute('contents.index');
    }
}
