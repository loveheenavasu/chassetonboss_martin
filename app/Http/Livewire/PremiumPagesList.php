<?php

namespace App\Http\Livewire;
use App\Actions\DeployPage;
use App\Models\PremiumPages;
use Livewire\Component;
use Livewire\WithPagination;

class PremiumPagesList extends Component
{
    use WithPagination;
    public bool $confirmingPremiumPageDeletion = false;
    public ?int $premiumpageIdBeingDeleted;

    public function getPremiumPagesProperty()
    {
        return PremiumPages::query()->latest()->paginate(10);
    }

    public function confirmPremiumPageDeletion($pageId): void
    {
        $this->confirmingPremiumPageDeletion = true;
        $this->premiumpageIdBeingDeleted = $pageId;
    }

    public function deletePage(DeployPage $deployer): void
    {
        $page = PremiumPages::query()->findOrNew($this->premiumpageIdBeingDeleted);
        if ($page->exists) {
            $page->delete();
            $deployer->delete($page);
        }

        $this->confirmingPremiumPageDeletion = false;
    }
}
