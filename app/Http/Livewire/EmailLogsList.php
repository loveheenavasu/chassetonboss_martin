<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\EmailLogs;
use App\Tools;
use Livewire\WithPagination;

class EmailLogsList extends Component
{
	use WithPagination;
    public function getEmaillogsProperty()
    {
        return EmailLogs::latest('id')->paginate(100);
    }
}