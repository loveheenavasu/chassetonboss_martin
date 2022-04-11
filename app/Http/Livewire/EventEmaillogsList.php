<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\EventEmailLogs;
use App\Tools;
use Livewire\WithPagination;


class EventEmaillogsList extends Component
{
    use WithPagination;
    public function getEmaillogsProperty()
    {
        return EventEmailLogs::latest('id')->paginate(100);
    }
}
