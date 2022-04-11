<?php

namespace App\Http\Livewire;

use App\Actions\DeploySyndication;
use App\Models\Content;
use App\Models\Syndication;
use Livewire\Component;

class ContentList extends Component
{
    public bool $confirmingContentDeletion = false;
    public ?Content $contentBeingDeleted = null;

    public function getContentsProperty()
    {
        return Content::all();
    }

    public function confirmContentDeletion(Content $content): void
    {
        $this->confirmingContentDeletion = true;
        $this->contentBeingDeleted = $content;
    }

    public function deleteContent(DeploySyndication $deployer): void
    {
        if (! is_null($this->contentBeingDeleted)) {
            $this->contentBeingDeleted
                ->syndications->each(function (Syndication $syndication) use ($deployer) {
                    $deployer->destroy($syndication);
                });

            $this->contentBeingDeleted->delete();
        }

        $this->confirmingContentDeletion = false;
    }
}
