<?php

namespace App\Http\Livewire;

use App\Models\Listing;
use App\Models\Rule;
use Livewire\Component;

class RuleMonitoring extends Component
{
    public Rule $rule;

    public function mount(Rule $rule)
    {
        $this->rule = $rule;
    }
}
