<?php

namespace App\Http\Livewire;

use App\Actions\DeployPage;
use App\Models\LandingPage;
use Livewire\Component;
use Livewire\WithPagination;


class LandingPageList extends Component
{
    use WithPagination;
    public bool $confirmingLandingpageDeletion = false;
    public ?int $pageIdBeingDeleted;

    public function getLandingPagesProperty()
    {
        return LandingPage::query()->latest()->paginate(10);
    }

    public function confirmLandingPageDeletion($pageId): void
    {
        $this->confirmingLandingpageDeletion = true;
        $this->pageIdBeingDeleted = $pageId;
    }

    public function deletePage(DeployPage $deployer): void
    {
        $page = LandingPage::query()->findOrNew($this->pageIdBeingDeleted);
        if ($page->exists) {
            $page->delete();
            $deployer->deploylandingpage($page);
        }

        $this->confirmingLandingpageDeletion = false;
    }
}
