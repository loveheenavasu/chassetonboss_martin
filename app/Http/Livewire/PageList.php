<?php

namespace App\Http\Livewire;

use App\Actions\DeployPage;
use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;
class PageList extends Component
{
    use WithPagination;
    public bool $confirmingPageDeletion = false;
    public ?int $pageIdBeingDeleted;

    public function getPagesProperty()
    {
        return Page::query()->latest()->paginate(10);
    }

    public function confirmPageDeletion($pageId): void
    {
        $this->confirmingPageDeletion = true;
        $this->pageIdBeingDeleted = $pageId;
    }

    public function deletePage(DeployPage $deployer): void
    {
        $page = Page::query()->findOrNew($this->pageIdBeingDeleted);
        if ($page->exists) {
            $page->delete();
            $deployer->delete($page);
        }

        $this->confirmingPageDeletion = false;
    }
}
