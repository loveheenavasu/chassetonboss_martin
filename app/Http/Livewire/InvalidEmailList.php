<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\InvalidEmail;
use App\Tools;

class InvalidEmailList extends Component
{
    public function getInvalidemailsProperty()
    {
        return InvalidEmail::latest('id')->paginate(100);
    }
}
